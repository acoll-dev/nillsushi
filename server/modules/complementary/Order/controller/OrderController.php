<?php

class OrderController {

    public static function controller($function = "", $attributes = array(), $id = 0, $json = false) {
        if (!empty($function)) {
            return self::$function($attributes, $id, $json);
        } else {
            return false;
        }
    }

    private static function insert($attributes = array(), $id = 0, $json = false) {
        try {
            global $APP;
            $APP->CON->transaction();

                if (!empty($attributes)) {

                    $APP->setDependencyModule(array('Order','Orderproduct', 'Authentication','Module','Daysenabled'));
                    $array = array();
                    $token = "";
                    $products = array();
                    $find = array();
                    
                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    /*if(!Authentication::check_access_control(CUR_LAYER,'order','insert')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }*/

                    ////////////////////////////////////////////////////////////

                    foreach ($attributes as $key => $value) {
                        if (isset($value->name)) {
                            
                            if(empty($value->value)){
                                $value->value = null;
                            }
                            /*if($value->name === 'status'){
                                $status = true;
                                if($value->value == true){
                                    $value->value = 1;
                                }else{
                                    $value->value = 0;
                                }
                            }*/
                            if($value->name === 'products'){
                                $products = $value->value;
                                unset($attributes[$key]);
                                continue;
                            }
                            if($value->name === 'fetch'){
                                $status = true;
                                if($value->value == true){
                                    $value->value = 1;
                                }else{
                                    $value->value = 0;
                                }
                            }
                            if ($value->name === 'token') {
                                $token = $value->value;
                                unset($attributes[$key]);
                            } else {
                                $array = array_merge($array, array($value->name => $value->value));
                            }

                        }
                    }
                    

                    if(!empty($token)){
                        if (Authentication::check_token($token) === false) {
                            throw new Exception("ERROR.INVALID.TOKEN");
                        }
                    }

                    
                    $find = Module::find_by_name('order');
                    $array['fkidmodule'] = $find->idmodule;
                    
                    if(isset($array['deliveryfee'])){
                        $array['total'] = (float) $array['subtotal'] + (float) $array['deliveryfee'];
                    }else{
                        $array['total'] = (float) $array['subtotal'];
                    }
                    
                    if(!isset($array['status'])){
                        $array['status'] = 0;
                    }
                    
                    if(!isset($array['created'])){
                        $array['created'] = date('Y-m-d H:i:s');
                    }else{
                        $auxDate = explode(" ",$array['created']);
                        //Se na camada website, a data do pedido não pode ser menor que a do dia atual
                        if(LAYER == "website"){
                            if($auxDate[0] < date('Y-m-d')){
                                throw new Exception("ERROR.DATEINVALID");
                            }
                        }
                    }
                    
                    $auxDate = explode(" ",$array['created']);
                    
                    $find = array();
                    
                    //Verificando se a data do pedido escolhida está cadastrada
                    
                    $find = Daysenabled::find_by_sql("SELECT iddaysenabled from ".DB_PREFIX."daysenabled WHERE date = '{$auxDate[0]}'");
                    
                    if(!$find){
                        throw new Exception("ERROR.DAYNOTEXISTS");
                    }
                    
                    
                    Order::create($array);
                    $lastorder = Order::last();
                    
                    $products = Tools::objectToArray($products);
                    
                    if(!empty($products)){
                        foreach($products as $value){
                            
                            $APP->CON->query("INSERT INTO ".DB_PREFIX."orderproduct values(null,{$lastorder->idorder},{$value['idproduct']},{$value['quantity']})");
                            //Orderproduct::create(array('fkidorder' => $lastorder->idorder,'fkidproduct' => $value['id'],'quantity' => $value['quantity']));
                        }
                    }
                    
                    $APP->CON->commit();
                    $APP->activityLog("Register Order");
                    return $APP->response(true, 'success', 'SUCCESS.CREATE.COMPLETED', $json);
                } else {
                    throw new Exception("There are no values for registering!");
                }
        } catch (\Exception $e) {
            $APP->CON->rollback();
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function completed($attributes = array(), $id = 0, $json = false) {
        try {
            global $APP;
            $APP->setDependencyModule(array('Order','Client'));
            $return = array();
            $orders = array();
            $layer = (LAYER === 'admin') ? CUR_LAYER : LAYER;
            $layer = Layer::find_by_name($layer);
            unset($attributes['layer']);
            
            if ($id > 0) {

                $orders = Order::find_by_sql("SELECT DISTINCT o.idorder,o.fetch,o.formpayment,o.change,o.deliveryfee,o.subtotal,o.total,o.complement,o.address,o.number,o.district,o.city,o.state,o.created,o.updated,o.status,o.fkidclient,o.fkidshop FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."module m on o.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE o.idorder = {$id} AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer} and o.status = 3");

                $cont = 0;
                foreach($orders as $key2 => $value2){

                    $return[$cont] = $value2->to_array();
                    $return[$cont]['fetch'] = (boolean) $return[$cont]['fetch'];
                    $findproducts = false;
                    $products = array();
                    $findproducts = Order::find_by_sql("SELECT DISTINCT p.idproduct,p.name,p.url,p.shortdescription,p.description,p.unitvalue,p.picture,p.pictures,op.quantity FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."orderproduct op ON o.idorder = op.fkidorder INNER JOIN ".DB_PREFIX."product p ON op.fkidproduct = p.idproduct WHERE p.status = 1 and op.fkidorder = {$value2->idorder}");
                    if($findproducts){
                        foreach($findproducts as $value3){
                            $products[] = $value3->to_array();
                        }
                    }
                    $return[$cont]['products'] = $products;

                    $findclient = false;
                    $findclient = Client::find($value2->fkidclient);

                    if($findclient){
                        $return[$cont]['client'] = $findclient->to_array();
                    }

                    $cont++;
                }

                return $APP->response($return, 'success', '', $json);
            } else {
                if(!empty($attributes)){
                    foreach($attributes as $key => $value){
                        if(!is_numeric($value)){
                            $orders = Order::find_by_sql("SELECT DISTINCT o.idorder,o.fetch,o.formpayment,o.change,o.deliveryfee,o.subtotal,o.total,o.complement,o.address,o.number,o.district,o.city,o.state,o.created,o.updated,o.status,o.fkidclient,o.fkidshop FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."module m on o.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE o.{$key} = '{$value}' AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer} and o.status = 3");

                            $cont = 0;
                            foreach($orders as $key2 => $value2){

                                $return[$cont] = $value2->to_array();
                                $return[$cont]['fetch'] = (boolean) $return[$cont]['fetch'];
                                $findproducts = false;
                                $products = array();
                                $findproducts = Order::find_by_sql("SELECT DISTINCT p.idproduct,p.name,p.url,p.shortdescription,p.description,p.unitvalue,p.picture,p.pictures,op.quantity FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."orderproduct op ON o.idorder = op.fkidorder INNER JOIN ".DB_PREFIX."product p ON op.fkidproduct = p.idproduct WHERE p.status = 1 and op.fkidorder = {$value2->idorder}");
                                if($findproducts){
                                    foreach($findproducts as $value3){
                                        $products[] = $value3->to_array();
                                    }
                                }
                                $return[$cont]['products'] = $products;

                                $findclient = false;
                                $findclient = Client::find($value2->fkidclient);

                                if($findclient){
                                    $return[$cont]['client'] = $findclient->to_array();
                                }

                                $cont++;
                            }
                        }else{
                            $orders = Order::find_by_sql("SELECT DISTINCT o.idorder,o.fetch,o.formpayment,o.change,o.deliveryfee,o.subtotal,o.total,o.complement,o.address,o.number,o.district,o.city,o.state,o.created,o.updated,o.status,o.fkidclient,o.fkidshop FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."module m on o.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE o.{$key} = {$value} AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer} and o.status = 3");

                            $cont = 0;
                            foreach($orders as $key2 => $value2){

                                $return[$cont] = $value2->to_array();
                                $return[$cont]['fetch'] = (boolean) $return[$cont]['fetch'];
                                $findproducts = false;
                                $products = array();
                                $findproducts = Order::find_by_sql("SELECT DISTINCT p.idproduct,p.name,p.url,p.shortdescription,p.description,p.unitvalue,p.picture,p.pictures,op.quantity FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."orderproduct op ON o.idorder = op.fkidorder INNER JOIN ".DB_PREFIX."product p ON op.fkidproduct = p.idproduct WHERE p.status = 1 and op.fkidorder = {$value2->idorder}");
                                if($findproducts){
                                    foreach($findproducts as $value3){
                                        $products[] = $value3->to_array();
                                    }
                                }
                                $return[$cont]['products'] = $products;

                                $findclient = false;
                                $findclient = Client::find($value2->fkidclient);

                                if($findclient){
                                    $return[$cont]['client'] = $findclient->to_array();
                                }

                                $cont++;
                            }
                        }
                    }
                    return $APP->response($return, 'success', '', $json);
                }else{
                    $orders = Order::find_by_sql("SELECT DISTINCT o.idorder,o.fetch,o.formpayment,o.change,o.deliveryfee,o.subtotal,o.total,o.complement,o.address,o.number,o.district,o.city,o.state,o.created,o.updated,o.status,o.fkidclient,c.name as client,o.fkidshop FROM ".DB_PREFIX."client c INNER JOIN ".DB_PREFIX."order o ON c.idclient = o.fkidclient INNER JOIN ".DB_PREFIX."module m on o.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer} and o.status = 3");

                    $cont = 0;
                    foreach($orders as $key2 => $value2){

                        $return[$cont] = $value2->to_array();
                        $return[$cont]['fetch'] = (boolean) $return[$cont]['fetch'];
                        $findproducts = false;
                        $products = array();
                        $findproducts = Order::find_by_sql("SELECT DISTINCT p.idproduct,p.name,p.url,p.shortdescription,p.description,p.unitvalue,p.picture,p.pictures,op.quantity FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."orderproduct op ON o.idorder = op.fkidorder INNER JOIN ".DB_PREFIX."product p ON op.fkidproduct = p.idproduct WHERE p.status = 1 and op.fkidorder = {$value2->idorder}");
                        if($findproducts){
                            foreach($findproducts as $value3){
                                $products[] = $value3->to_array();
                            }
                        }
                        $return[$cont]['products'] = $products;

                        $findclient = false;
                        $findclient = Client::find($value2->fkidclient);

                        if($findclient){
                            $return[$cont]['client'] = $findclient->to_array();
                        }

                        $cont++;
                    }
                    return $APP->response($return, 'success', '', $json);
                }
            }
            
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }
    
    private static function get($attributes = array(), $id = 0, $json = false) {
        try {
            global $APP;
            $APP->setDependencyModule(array('Order','Client'));
            $return = array();
            $orders = array();
            $layer = (LAYER === 'admin') ? CUR_LAYER : LAYER;
            $layer = Layer::find_by_name($layer);
            unset($attributes['layer']);
            if(LAYER === 'admin'){
                if ($id > 0) {
                    
                    $orders = Order::find_by_sql("SELECT DISTINCT o.idorder,o.fetch,o.formpayment,o.change,o.deliveryfee,o.subtotal,o.total,o.complement,o.address,o.number,o.district,o.city,o.state,o.created,o.updated,o.status,o.fkidclient,o.fkidshop FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."module m on o.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE o.idorder = {$id} AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");
                    
                    $cont = 0;
                    foreach($orders as $key2 => $value2){
                        
                        $return[$cont] = $value2->to_array();
                        $return[$cont]['fetch'] = (boolean) $return[$cont]['fetch'];
                        $findproducts = false;
                        $products = array();
                        $findproducts = Order::find_by_sql("SELECT DISTINCT p.idproduct,p.name,p.url,p.shortdescription,p.description,p.unitvalue,p.picture,p.pictures,op.quantity FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."orderproduct op ON o.idorder = op.fkidorder INNER JOIN ".DB_PREFIX."product p ON op.fkidproduct = p.idproduct WHERE p.status = 1 and op.fkidorder = {$value2->idorder}");
                        if($findproducts){
                            foreach($findproducts as $value3){
                                $products[] = $value3->to_array();
                            }
                        }
                        $return[$cont]['products'] = $products;
                        
                        $findclient = false;
                        $findclient = Client::find($value2->fkidclient);
                        
                        if($findclient){
                            $return[$cont]['client'] = $findclient->to_array();
                        }
                        
                        $cont++;
                    }

                    return $APP->response($return, 'success', '', $json);
                } else {
                    if(!empty($attributes)){
                        foreach($attributes as $key => $value){
                            if(!is_numeric($value)){
                                $orders = Order::find_by_sql("SELECT DISTINCT o.idorder,o.fetch,o.formpayment,o.change,o.deliveryfee,o.subtotal,o.total,o.complement,o.address,o.number,o.district,o.city,o.state,o.created,o.updated,o.status,o.fkidclient,o.fkidshop FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."module m on o.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE o.{$key} = '{$value}' AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer} and o.status <> 3");

                                $cont = 0;
                                foreach($orders as $key2 => $value2){
                                    
                                    $return[$cont] = $value2->to_array();
                                    $return[$cont]['fetch'] = (boolean) $return[$cont]['fetch'];
                                    $findproducts = false;
                                    $products = array();
                                    $findproducts = Order::find_by_sql("SELECT DISTINCT p.idproduct,p.name,p.url,p.shortdescription,p.description,p.unitvalue,p.picture,p.pictures,op.quantity FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."orderproduct op ON o.idorder = op.fkidorder INNER JOIN ".DB_PREFIX."product p ON op.fkidproduct = p.idproduct WHERE p.status = 1 and op.fkidorder = {$value2->idorder}");
                                    if($findproducts){
                                        foreach($findproducts as $value3){
                                            $products[] = $value3->to_array();
                                        }
                                    }
                                    $return[$cont]['products'] = $products;
                                    
                                    $findclient = false;
                                    $findclient = Client::find($value2->fkidclient);

                                    if($findclient){
                                        $return[$cont]['client'] = $findclient->to_array();
                                    }
                                    
                                    $cont++;
                                }
                            }else{
                                $orders = Order::find_by_sql("SELECT DISTINCT o.idorder,o.fetch,o.formpayment,o.change,o.deliveryfee,o.subtotal,o.total,o.complement,o.address,o.number,o.district,o.city,o.state,o.created,o.updated,o.status,o.fkidclient,o.fkidshop FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."module m on o.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE o.{$key} = {$value} AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer} and o.status <> 3");

                                $cont = 0;
                                foreach($orders as $key2 => $value2){
                                    
                                    $return[$cont] = $value2->to_array();
                                    $return[$cont]['fetch'] = (boolean) $return[$cont]['fetch'];
                                    $findproducts = false;
                                    $products = array();
                                    $findproducts = Order::find_by_sql("SELECT DISTINCT p.idproduct,p.name,p.url,p.shortdescription,p.description,p.unitvalue,p.picture,p.pictures,op.quantity FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."orderproduct op ON o.idorder = op.fkidorder INNER JOIN ".DB_PREFIX."product p ON op.fkidproduct = p.idproduct WHERE p.status = 1 and op.fkidorder = {$value2->idorder}");
                                    if($findproducts){
                                        foreach($findproducts as $value3){
                                            $products[] = $value3->to_array();
                                        }
                                    }
                                    $return[$cont]['products'] = $products;
                                    
                                    $findclient = false;
                                    $findclient = Client::find($value2->fkidclient);

                                    if($findclient){
                                        $return[$cont]['client'] = $findclient->to_array();
                                    }
                                    
                                    $cont++;
                                }
                            }
                        }
                        return $APP->response($return, 'success', '', $json);
                    }else{
                        $orders = Order::find_by_sql("SELECT DISTINCT o.idorder,o.fetch,o.formpayment,o.change,o.deliveryfee,o.subtotal,o.total,o.complement,o.address,o.number,o.district,o.city,o.state,o.created,o.updated,o.status,o.fkidclient,c.name as client,o.fkidshop FROM ".DB_PREFIX."client c INNER JOIN ".DB_PREFIX."order o ON c.idclient = o.fkidclient INNER JOIN ".DB_PREFIX."module m on o.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer} and o.status <> 3");

                        $cont = 0;
                        foreach($orders as $key2 => $value2){
                            
                            $return[$cont] = $value2->to_array();
                            $return[$cont]['fetch'] = (boolean) $return[$cont]['fetch'];
                            $findproducts = false;
                            $products = array();
                            $findproducts = Order::find_by_sql("SELECT DISTINCT p.idproduct,p.name,p.url,p.shortdescription,p.description,p.unitvalue,p.picture,p.pictures,op.quantity FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."orderproduct op ON o.idorder = op.fkidorder INNER JOIN ".DB_PREFIX."product p ON op.fkidproduct = p.idproduct WHERE p.status = 1 and op.fkidorder = {$value2->idorder}");
                            if($findproducts){
                                foreach($findproducts as $value3){
                                    $products[] = $value3->to_array();
                                }
                            }
                            $return[$cont]['products'] = $products;
                            
                            $findclient = false;
                            $findclient = Client::find($value2->fkidclient);

                            if($findclient){
                                $return[$cont]['client'] = $findclient->to_array();
                            }
                            
                            $cont++;
                        }
                        return $APP->response($return, 'success', '', $json);
                    }
                }
            }else{
                if ($id > 0) {
                    $orders = Order::find_by_sql("SELECT DISTINCT o.idorder,o.fetch,o.formpayment,o.change,o.deliveryfee,o.subtotal,o.total,o.complement,o.address,o.number,o.district,o.city,o.state,o.created,o.updated,o.status,o.fkidclient,o.fkidshop FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."module m on o.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE o.idorder = {$id} AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer} and o.status <> 3");

                    $cont = 0;
                    foreach($orders as $key2 => $value2){
                        
                        $return[$cont] = $value2->to_array();
                        $return[$cont]['fetch'] = (boolean) $return[$cont]['fetch'];
                        $findproducts = false;
                        $products = array();
                        $findproducts = Order::find_by_sql("SELECT DISTINCT p.idproduct,p.name,p.url,p.shortdescription,p.description,p.unitvalue,p.picture,p.pictures,op.quantity FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."orderproduct op ON o.idorder = op.fkidorder INNER JOIN ".DB_PREFIX."product p ON op.fkidproduct = p.idproduct WHERE p.status = 1 and op.fkidorder = {$value2->idorder}");
                        if($findproducts){
                            foreach($findproducts as $value3){
                                $products[] = $value3->to_array();
                            }
                        }
                        $return[$cont]['products'] = $products;
                        
                        $findclient = false;
                        $findclient = Client::find($value2->fkidclient);

                        if($findclient){
                            $return[$cont]['client'] = $findclient->to_array();
                        }
                                    
                        $cont++;
                    }
                    return $APP->response($return, 'success', '', $json);
                } else {
                    if(!empty($attributes)){
                        
                        foreach($attributes as $key => $value){
                            
                            if(!is_numeric($value)){
                                $orders = Order::find_by_sql("SELECT DISTINCT o.idorder,o.fetch,o.formpayment,o.change,o.deliveryfee,o.subtotal,o.total,o.complement,o.address,o.number,o.district,o.city,o.state,o.created,o.updated,o.status,o.fkidclient,o.fkidshop FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."module m on o.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE o.{$key} = '{$value}' AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer} and o.status <> 3");

                                $cont = 0;
                                foreach($orders as $key2 => $value2){
                                    
                                    $return[$cont] = $value2->to_array();
                                    $return[$cont]['fetch'] = (boolean) $return[$cont]['fetch'];
                                    $findproducts = false;
                                    $products = array();
                                    $findproducts = Order::find_by_sql("SELECT DISTINCT p.idproduct,p.name,p.url,p.shortdescription,p.description,p.unitvalue,p.picture,p.pictures,op.quantity FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."orderproduct op ON o.idorder = op.fkidorder INNER JOIN ".DB_PREFIX."product p ON op.fkidproduct = p.idproduct WHERE p.status = 1 and op.fkidorder = {$value2->idorder}");
                                    if($findproducts){
                                        foreach($findproducts as $value3){
                                            $products[] = $value3->to_array();
                                        }
                                    }
                                    $return[$cont]['products'] = $products;
                                    
                                    $findclient = false;
                                    $findclient = Client::find($value2->fkidclient);

                                    if($findclient){
                                        $return[$cont]['client'] = $findclient->to_array();
                                    }
                                    
                                    $cont++;
                                }
                            }else{
                                
                                $orders = Order::find_by_sql("SELECT DISTINCT o.idorder,o.fetch,o.formpayment,o.change,o.deliveryfee,o.subtotal,o.total,o.complement,o.address,o.number,o.district,o.city,o.state,o.created,o.updated,o.status,o.fkidclient,o.fkidshop FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."module m on o.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE o.{$key} = {$value} AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer} and o.status <> 3");

                                $cont = 0;
                                foreach($orders as $key2 => $value2){
                                    
                                    $return[$cont] = $value2->to_array();
                                    $return[$cont]['fetch'] = (boolean) $return[$cont]['fetch'];
                                    $findproducts = false;
                                    $products = array();
                                    $findproducts = Order::find_by_sql("SELECT DISTINCT p.idproduct,p.name,p.url,p.shortdescription,p.description,p.unitvalue,p.picture,p.pictures,op.quantity FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."orderproduct op ON o.idorder = op.fkidorder INNER JOIN ".DB_PREFIX."product p ON op.fkidproduct = p.idproduct WHERE p.status = 1 and op.fkidorder = {$value2->idorder}");
                                    if($findproducts){
                                        foreach($findproducts as $value3){
                                            $products[] = $value3->to_array();
                                        }
                                    }
                                    $return[$cont]['products'] = $products;
                                    
                                    $findclient = false;
                                    $findclient = Client::find($value2->fkidclient);

                                    if($findclient){
                                        $return[$cont]['client'] = $findclient->to_array();
                                    }
                                    
                                    $cont++;
                                }
                            }
                        }
                        return $APP->response($return, 'success', '', $json);
                    }else{
                        
                        $orders = Order::find_by_sql("SELECT DISTINCT o.idorder,o.fetch,o.formpayment,o.change,o.deliveryfee,o.subtotal,o.total,o.complement,o.address,o.number,o.district,o.city,o.state,o.created,o.updated,o.status,o.fkidclient,o.fkidshop FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."module m on o.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer} and o.status <> 3");

                        $cont = 0;
                        foreach($orders as $key2 => $value2){
                            
                            $return[$cont] = $value2->to_array();
                            $return[$cont]['fetch'] = (boolean) $return[$cont]['fetch'];
                            $findproducts = false;
                            $products = array();
                            $findproducts = Order::find_by_sql("SELECT DISTINCT p.idproduct,p.name,p.url,p.shortdescription,p.description,p.unitvalue,p.picture,p.pictures,op.quantity FROM ".DB_PREFIX."order o INNER JOIN ".DB_PREFIX."orderproduct op ON o.idorder = op.fkidorder INNER JOIN ".DB_PREFIX."product p ON op.fkidproduct = p.idproduct WHERE p.status = 1 and op.fkidorder = {$value2->idorder}");
                            if($findproducts){
                                foreach($findproducts as $value3){
                                    $products[] = $value3->to_array();
                                }
                            }
                            $return[$cont]['products'] = $products;
                            
                            $findclient = false;
                            $findclient = Client::find($value2->fkidclient);

                            if($findclient){
                                $return[$cont]['client'] = $findclient->to_array();
                            }
                                    
                            $cont++;
                        }
                        return $APP->response($return, 'success', '', $json);
                    }
                }
            }
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function delete($attributes = array(), $id = 0, $json = false) {
        if (is_numeric($id) && $id > 0) {
            try {
                global $APP;
                //$APP->CON->transaction();
                $id = (int) $id;
                $APP->setDependencyModule(array('Order','Orderproduct'));
                $order = 0;
                $return = false;

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'order','delete')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

                /*$findproducts = false;
                
                $findproducts = Orderproduct::find('all',array('conditions' => "fkidorder = {$id}"));
                
                if($findproducts){
                    foreach($findproducts as $value){
                        $value->delete();
                    }
                }*/
                
                $APP->CON->query("DELETE FROM ".DB_PREFIX."orderproduct WHERE fkidorder = {$id}");
                
                $order = Order::find($id);
                $order->delete();
                //$APP->CON->commit();
                $APP->activityLog("Delete Order");
                return $APP->response(true, 'success', 'SUCCESS.DELETE.COMPLETED', $json);
            } catch (\Exception $e) {
                //$APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        }
    }

    private static function update_attributes($attributes = array(), $id = 0, $json = false) {

        if (!empty($attributes) && (is_numeric($id) && $id > 0)) {
            try {
                global $APP;
                $APP->CON->transaction();
                $APP->setDependencyModule(array('Order','Orderproduct','Authentication','Daysenabled'));
                $array = array();
                $update_attributes = array();
                $products = array();
                $token = "";
                $idorder = 0;

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'order','update')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if(empty($value->value)){
                            $value->value = null;
                        }
                        /*if($value->name === 'status'){
                            if($value->value == true){
                                $value->value = 1;
                            }else{
                                $value->value = 0;
                            }
                        }*/
                        if ($value->name === 'idorder') {
                            $idorder = $value->value;
                            unset($attributes[$key]);
                        }
                        if($value->name === 'fetch'){
                            $status = true;
                            if($value->value == true){
                                $value->value = 1;
                            }else{
                                $value->value = 0;
                            }
                        }
                        if($value->name === 'products'){
                            $products = $value->value;
                            unset($attributes[$key]);
                            continue;
                        }
                        if ($value->name === 'token') {
                            $token = $value->value;
                            unset($attributes[$key]);
                        } else {
                            $array = array_merge($array, array($value->name => $value->value));
                        }
                    }
                }

                if (Authentication::check_token($token) === false) {
                    throw new Exception("ERROR.INVALID.TOKEN");
                }
                
                
                $find = array();
                    
                //Verificando se a data do pedido escolhida está cadastrada

                $aux = explode("-",$auxDate[0]);

                $find = Daysenabled::find_by_sql("SELECT iddaysenabled from ".DB_PREFIX."daysenabled WHERE DAY(date) = DAY(".$aux[2].") AND MONTH(date) = MONTH(".$auxDate[0].") AND YEAR(date) = YEAR(".$aux[0].")");

                if(!$find){
                    throw new Exception("ERROR.DAYNOTEXISTS");
                }
                
                
                $array['updated'] = date('Y-m-d H:i:s');
                
                if(isset($array['deliveryfee'])){
                    $array['total'] = (float) $array['subtotal'] + (float) $array['deliveryfee'];
                }else{
                    $array['total'] = (float) $array['subtotal'];
                }
                
                if (!empty($array)) {
                    $update_attributes = $array;
                } else {
                    $update_attributes = $attributes;
                }

                $find = Order::find($id);
                $find->update_attributes($update_attributes);
                    
                
                
                if(!empty($products)){
                    
                    $products = Tools::objectToArray($products);
                    
                    /*$findproducts = false;
                    //$findproducts = Orderproduct::find('all',array('conditions' => "fkidorder = {$id}"));
                    $findproducts = $APP->CON->query("SELECT * from ".DB_PREFIX."orderproduct WHERE fkidorder = {$id}");
                    
                    if($findproducts){
                        foreach($findproducts as $value){
                            $value->delete();
                        }
                    }*/
                    
                    $APP->CON->query("DELETE FROM ".DB_PREFIX."orderproduct WHERE fkidorder = {$id}");
                    
                    foreach($products as $value){
                        //Orderproduct::create(array('fkidorder' => $id,'fkidproduct' => $value->idproduct,'quantity' => $value->quantity));
                        $APP->CON->query("INSERT INTO ".DB_PREFIX."orderproduct values(null,{$id},{$value['idproduct']},{$value['quantity']})");
                    }
                }
                
                $APP->CON->commit();
                $APP->activityLog("Update Order");

                return $APP->response(true, 'success', 'SUCCESS.UPDATE.COMPLETED', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        }
        return $APP->response(false, 'danger', 'ERROR.EMPTY.VALUE', $json);
    }

    private static function config($attributes = array(), $id = 0, $json = false) {
            try {
                global $APP;
                $APP->CON->transaction();
                $APP->setDependencyModule(array('Authentication','Layermodule','Layer','Module'));

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'order','config')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

                $array = array();

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if (empty($value->value)) {
                            $value->value = null;
                        }
                        if ($value->name === 'url') {
                            if(!empty($value->value)){
                                if (substr($value->value, -1) != '/') {
                                    $value->value = Tools::formatUrl($value->value . '/');
                                }else{
                                    $value->value = Tools::formatUrl($value->value);
                                }
                            }
                        }
                        if ($value->name === 'token') {
                            $token = $value->value;
                            unset($attributes[$key]);
                        } else {
                            $array = array_merge($array, array($value->name => $value->value));
                        }
                    }
                }

                $module = Module::find_by_name(MODULE);
                $layer = Layer::find_by_name(CUR_LAYER);
                $find = false;

                if (Authentication::check_token($token) === false) {
                    throw new Exception("ERROR.INVALID.TOKEN");
                }

                $find = Layermodule::find('all', array('conditions' => "url = '{$array['url']}' and idlayer = {$layer->idlayer} and idmodule <> {$module->idmodule}"));

                if ($find) {
                    throw new Exception("ERROR.EXISTS.URL");
                }

                $layermodule = Layermodule::find('all', array('conditions' => "idlayer = {$layer->idlayer} and idmodule = {$module->idmodule}"));

                if($layermodule){
                    $layermodule = false;
                    $layermodule = $APP->CON->query("UPDATE ".DB_PREFIX."layermodule SET url = '{$array['url']}',filtermodel = '{$array['filtermodel']}',custom = '{$array['custom']}' WHERE idlayer = {$layer->idlayer} AND idmodule = {$module->idmodule}");

                    if($layermodule){
                        $APP->CON->commit();
                        $APP->activityLog("Update Config Order");
                        return $APP->response(true, 'success', 'MODULE.BUDGET.SUCCESS.CONFIG', $json);
                    }
                }
                return $APP->response(true, 'success', 'MODULE.ORDER.SUCCESS.CONFIG', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
    }

    private static function get_config($attributes = array(), $id = 0, $json = false) {
        global $APP;
        try {
            $APP->setDependencyModule(array('Layer', 'Authentication', 'Layermodule', 'Order', 'Module'));

            ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

            if (!Authentication::check_access_control(CUR_LAYER, 'order', 'config')) {
                throw new Exception("ERROR.RECUSED.RESOURCE");
            }

            ////////////////////////////////////////////////////////////

            $layer = false;
            $layermodule = false;
            $module = false;
            $return = array();
            $layer = Layer::find_by_name(CUR_LAYER);
            $module = Module::find_by_name('order');
            $layermodule = Layermodule::find('all',array('conditions' => "idlayer = {$layer->idlayer} AND idmodule = {$module->idmodule} AND status = 1"));

            if($layermodule){
                foreach($layermodule as $value){
                    $return = $value->to_array();
                }
            }

            return $APP->response($return, 'success', true, $json);
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }
    
}

?>
