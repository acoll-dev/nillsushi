<?php

class Rest {

    public function __construct() {

        $app = new \Slim\Slim(array(
            'debug' => true
        ));

        $app->config('debug', true);
        
        $app->response()->header('Content-Type', 'application/json;charset=utf-8');
        
        $app->error(function ( Exception $e = null) use ($app) {
            echo $e->getMessage();
        });

        if (LAYER === 'admin') {

            $app->get('/rest/:layer/:curlayer/:module/:action/:onelevel/:param+', function ($layer, $curlayer, $module, $action,$onelevel, $param = array()) use($app) {
                global $APP;
                
                define("ONELEVEL",($onelevel === 'true') ? true : false);
                
                $module = ucfirst(strtolower($module));
                
                $request = array();
                $return = false;
                if (!empty($layer) && !empty($module) && !empty($action) && !empty($param)) {

                    if (CACHE === true) {

                        if ($data = $APP->CACHE->get_cache($action)) {
                            $return = json_decode($data);
                        } else {

                            $request['layer'] = $curlayer;
                            if (count($param) == 1) {
                                if (is_numeric($param[0]) && $param[0] > 0) {

                                    $APP->includeModule($module);
                                    $auxModule = $module;
                                    $module = $module . 'Controller';
                                    $return = $module::controller($action, $request, $param[0]);

                                    $APP->CACHE->set_cache($auxModule . '_' . $action, json_encode($request));
                                } else {
                                    $aux = explode('=', $param[0]);
                                    $request[$aux[0]] = urldecode($aux[1]);
                                    $APP->includeModule($module);
                                    $auxModule = $module;
                                    $module = $module . 'Controller';
                                    $return = $module::controller($action, $request);

                                    $APP->CACHE->set_cache($auxModule . '_' . $action, json_encode($request));
                                }
                            } else {
                                $params = array();
                                foreach ($param as $value) {
                                    $params[] = urldecode($value);
                                }
                                $request['param'] = $params;

                                $APP->includeModule($module);
                                $auxModule = $module;
                                $module = $module . 'Controller';
                                $return = $module::controller($action, $request);

                                $APP->CACHE->set_cache($auxModule . '_' . $action, json_encode($request));
                            }
                        }
                    } else {
                        $request['layer'] = $curlayer;
                        if (count($param) == 1) {
                            if (is_numeric($param[0]) && $param[0] > 0) {
                                
                                $APP->includeModule($module);
                                $module = $module . 'Controller';
                                $return = $module::controller($action, $request, $param[0]);
                            } else {
                                
                                $aux = explode('=', $param[0]);
                                $request[$aux[0]] = urldecode($aux[1]);
                                $APP->includeModule($module);
                                $module = $module . 'Controller';
                                $return = $module::controller($action, $request);
                            }
                        } else {
                            $params = array();
                            foreach ($param as $value) {
                                $params[] = urldecode($value);
                            }
                            $request['param'] = $params;

                            $APP->includeModule($module);
                            $module = $module . 'Controller';
                            $return = $module::controller($action, $request);
                        }
                    }
                    echo json_encode($return);
                } else {
                    $app->response()->status(404);
                }
            });

            $app->get('/rest/:layer/:curlayer/:module/:action/:onelevel', function ($layer, $curlayer, $module, $action, $onelevel) use($app) {
                global $APP;
                
                define("ONELEVEL",($onelevel === 'true') ? true : false);
                
                $module = ucfirst(strtolower($module));
                $request = array();
                $return = false;

                if (!empty($layer) && !empty($module) && !empty($action)) {
                    if (CACHE === true) {

                        if ($data = $APP->CACHE->get_cache($action)) {

                            $return = json_decode($data);
                        } else {

                            $request['layer'] = $curlayer;
                            $APP->includeModule($module);
                            $auxModule = $module;
                            $module = $module . 'Controller';
                            $return = $module::controller($action, $request);

                            $APP->CACHE->set_cache($auxModule . '_' . $action, json_encode($return));
                        }
                    } else {
                        $request['layer'] = $curlayer;
                        $APP->includeModule($module);
                        $module = $module . 'Controller';
                        $return = $module::controller($action, $request);
                    }
                    echo json_encode($return);
                } else {
                    $app->response()->status(404);
                }
            });

            //POST não possui parâmetros na URL, e sim na requisição
            $app->post('/rest/:layer/:curlayer/:module/:action', function ($layer, $curlayer, $module, $action) use ($app) {
                global $APP;
                $module = ucfirst(strtolower($module));
                $request = (array) json_decode(\Slim\Slim::getInstance()->request()->getBody());
                $request['curlayer'] = $curlayer;
                if (!empty($layer) && !empty($module) && !empty($action)) {
                    $APP->includeModule($module);
                    $module = $module . 'Controller';
                    echo json_encode($module::controller($action, $request));
                } else {
                    $app->response()->status(404);
                }
            });

            //PUT possui parâmetros na URL
            $app->put('/rest/:layer/:curlayer/:module/:action/:param+', function ($layer, $curlayer, $module, $action, $param) use($app) {
                global $APP;
                $module = ucfirst(strtolower($module));
                //$request['layer'] = $curlayer;
                $request = (array) json_decode(\Slim\Slim::getInstance()->request()->getBody());

                if (!empty($module) && !empty($action) && !empty($param)) {

                    if (count($param) == 1) {
                        if (is_numeric($param[0]) && $param[0] > 0) {

                            $APP->includeModule($module);
                            $module = $module . 'Controller';
                            echo json_encode($module::controller($action, $request, $param[0]));
                        } else {

                            $aux = explode('=', $param[0]);
                            $request['param'][$aux[0]] = $aux[1];
                            $APP->includeModule($module);
                            $module = $module . 'Controller';
                            echo json_encode($module::controller($action, $request));
                        }
                    } else {
                        $request['param'] = $param;
                        $APP->includeModule($module);
                        $module = $module . 'Controller';
                        echo json_encode($module::controller($action, $request));
                    }
                } else {
                    $app->response()->status(404);
                }
            });

            //PUT não possui parâmetros na URL
            $app->put('/rest/:layer/:curlayer/:module/:action', function ($layer, $curlayer, $module, $action) use($app) {
                global $APP;
                $module = ucfirst(strtolower($module));
                //$request['layer'] = $curlayer;
                $request = (array) json_decode(\Slim\Slim::getInstance()->request()->getBody());
                if (!empty($module) && !empty($action)) {
                    $APP->includeModule($module);
                    $module = $module . 'Controller';
                    echo json_encode($module::controller($action, $request));
                } else {
                    $app->response()->status(404);
                }
            });

            //DELETE pode possuir um parametro na URL
            $app->delete('/rest/:layer/:curlayer/:module/:id', function ($layer, $curlayer, $module, $id) use($app) {
                global $APP;
                $return = array();
                $module = ucfirst(strtolower($module));
                $request = array();
                //$request['layer'] = $curlayer;
                $action = 'delete';
                if (!empty($module) && !empty($action) && is_numeric($id)) {
                    $APP->includeModule($module);
                    $module = $module . 'Controller';
                    echo json_encode($module::controller($action, $request, $id));
                } else {
                    $app->response()->status(404);
                }
            });
        } else {

            $app->get('/rest/:layer/:module/:action/:onelevel/:param+', function ($layer, $module, $action,$onelevel, $param = array()) use($app) {
                global $APP;

                define("ONELEVEL",($onelevel === 'true') ? true : false);
                
                $module = ucfirst(strtolower($module));
                $request = array();
                $return = false;

                if (!empty($layer) && !empty($module) && !empty($action) && !empty($param)) {

                    if (CACHE === true) {

                        if ($data = $APP->CACHE->get_cache($action)) {
                            $return = json_decode($data);
                        } else {
                            $request['layer'] = $layer;
                            if (count($param) == 1) {
                                if (is_numeric($param[0]) && $param[0] > 0) {

                                    $APP->includeModule($module);
                                    $auxModule = $module;
                                    $module = $module . 'Controller';
                                    $return = $module::controller($action, $request, $param[0]);

                                    $APP->CACHE->set_cache($auxModule . '_' . $action, json_encode($request));
                                } else {

                                    $aux = explode('=', $param[0]);
                                    $request[$aux[0]] = urldecode($aux[1]);
                                    $APP->includeModule($module);
                                    $auxModule = $module;
                                    $module = $module . 'Controller';
                                    $return = $module::controller($action, $request);

                                    $APP->CACHE->set_cache($auxModule . '_' . $action, json_encode($request));
                                }
                            } else {
                                $params = array();
                                foreach ($param as $value) {
                                    $params[] = urldecode($value);
                                }
                                $request['param'] = $params;

                                $APP->includeModule($module);
                                $auxModule = $module;
                                $module = $module . 'Controller';
                                $return = $module::controller($action, $request);

                                $APP->CACHE->set_cache($auxModule . '_' . $action, json_encode($request));
                            }
                        }
                    } else {
                        $request['layer'] = $layer;
                        if (count($param) == 1) {
                            if (is_numeric($param[0]) && $param[0] > 0) {

                                $APP->includeModule($module);
                                $module = $module . 'Controller';
                                $return = $module::controller($action, $request, $param[0]);
                            } else {
                                $aux = explode('=', $param[0]);
                                $request[$aux[0]] = urldecode($aux[1]);
                                $APP->includeModule($module);
                                $module = $module . 'Controller';
                                $return = $module::controller($action, $request);
                            }
                        } else {
                            $params = array();
                            foreach ($param as $value) {
                                $params[] = urldecode($value);
                            }
                            $request['param'] = $params;

                            $APP->includeModule($module);
                            $module = $module . 'Controller';
                            $return = $module::controller($action, $request);
                        }
                    }
                    echo json_encode($return);
                } else {
                    $app->response()->status(404);
                }
            });

            $app->get('/rest/:layer/:module/:action/:onelevel', function ($layer, $module, $action,$onelevel) use($app) {
                global $APP;
                
                define("ONELEVEL",($onelevel === 'true') ? true : false);
                
                $module = ucfirst(strtolower($module));
                $request = array();
                $return = false;

                if (!empty($layer) && !empty($module) && !empty($action)) {
                    if (CACHE === true) {

                        if ($data = $APP->CACHE->get_cache($action)) {
                            $return = json_decode($data);
                        } else {
                            $request['layer'] = $layer;
                            $APP->includeModule($module);
                            $auxModule = $module;
                            $module = $module . 'Controller';
                            $return = $module::controller($action, $request);
                            $APP->CACHE->set_cache($auxModule . '_' . $action, json_encode($request));
                        }
                    } else {
                        $request['layer'] = $layer;
                        $APP->includeModule($module);
                        $module = $module . 'Controller';
                        $return = $module::controller($action, $request);
                    }
                    echo json_encode($return);
                } else {
                    $app->response()->status(404);
                }
            });

            //POST não possui parâmetros na URL, e sim na requisição
            $app->post('/rest/:layer/:module/:action', function ($layer, $module, $action) use ($app) {
                global $APP;
                $module = ucfirst(strtolower($module));
                $request = (array) json_decode(\Slim\Slim::getInstance()->request()->getBody());
                if (!empty($layer) && !empty($module) && !empty($action)) {
                    $APP->includeModule($module);
                    $module = $module . 'Controller';
                    echo json_encode($module::controller($action, $request));
                } else {
                    $app->response()->status(404);
                }
            });

            //PUT possui parâmetros na URL e na requisição
            $app->put('/rest/:layer/:module/:action/:id', function ($layer, $module, $action, $id) use($app) {
                global $APP;
                $module = ucfirst(strtolower($module));
                $request = (array) json_decode(\Slim\Slim::getInstance()->request()->getBody());
                if (!empty($module) && !empty($action) && is_numeric($id)) {
                    $APP->includeModule($module);
                    $module = $module . 'Controller';
                    echo json_encode($module::controller($action, $request, $id));
                } else {
                    $app->response()->status(404);
                }
            });

            //DELETE pode possuir um parametro na URL
            $app->delete('/rest/:layer/:module/:id', function ($layer, $module, $id) use($app) {
                global $APP;
                $return = array();
                $module = ucfirst(strtolower($module));
                $request = array();
                $action = 'delete';
                if (!empty($module) && !empty($action) && is_numeric($id)) {
                    $APP->includeModule($module);
                    $module = $module . 'Controller';
                    echo json_encode($module::controller($action, $request, $id));
                } else {
                    $app->response()->status(404);
                }
            });
        }

        $app->run();
    }

}
?>