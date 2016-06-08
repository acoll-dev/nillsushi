<?php

class GalleryController {

    private static $ext = array(
        'gif',
        'jpg',
        'jpeg',
        'tiff',
        'png',
        'bmp',
        'mp3',
        'wma',
        'wav',
        'flv',
        'avi',
        'wmv',
        'rm',
        'rmvb',
        'mp4',
        'm4p',
        'm4v',
        'mpg',
        'mp2',
        'mpeg',
        'mpe',
        'mpv',
        'm2v',
        'mov',
        'mkv',
        'txt',
        'doc',
        'docx',
        'pdf'
    );

    private static $extImg = array(
        'gif',
        'jpg',
        'jpeg',
        'tiff',
        'png',
        'bmp'
    );

    private static $extVideo = array(
        'flv',
        'avi',
        'wmv',
        'rm',
        'rmvb',
        'mp4',
        'm4p',
        'm4v',
        'mpg',
        'mp2',
        'mpeg',
        'mpe',
        'mpv',
        'm2v',
        'mov',
        'mkv'
    );

    private static $extMusic = array(
        'mp3',
        'wma',
        'wav'
    );

    private static $extDoc = array(
        'txt',
        'doc',
        'docx',
        'pdf'
    );

    private static $maxSize = 2097152;
    private static $IMG;

    public static function controller($function = "", $attributes = array(), $id = 0, $json = false) {
        if (!empty($function)) {
            global $APP;
            $APP->GALLERY = new League\Flysystem\Filesystem(new League\Flysystem\Adapter\Local(DIR_UPLOADS));
            self::$IMG = new Imagine\Gd\Imagine();
            return self::$function($attributes, $id, $json);
        } else {
            return false;
        }
    }

    /*private static function update_attributes($attributes = array(), $id = 0, $json = false) {

        if (!empty($attributes) && (is_numeric($id) && $id > 0)) {
            try {
                global $APP;
                $APP->setDependencyModule(array('Gallery', 'Authentication'));
                $array = array();
                $update_attributes = array();
                $token = "";
                $idgallery = 0;

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'gallery','update')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if(empty($value->value)){
                            $value->value = null;
                        }
                        if ($value->name === 'path') {
     *                      if(!empty($value->value)){
                                if(substr($value->value,-1) != '/'){
                                    Tools::formatUrl($value->value . '/');
                                }else{
*                                  Tools::formatUrl($value->value);
*                              }
     *                      }
                        }
                        if ($value->name === 'idgallery') {
                            $idgallery = $value->value;
                            unset($attributes[$key]);
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

                $find = Gallery::find('all', array('conditions' => "(name = '{$array['name']}' OR path = '{$array['path']}') and idgallery <> {$idgallery}"));

                if ($find) {
                    throw new Exception("There is already a gallery registered under that name or path!");
                }

                if (!empty($array)) {
                    $update_attributes = $array;
                } else {
                    $update_attributes = $attributes;
                }

                $APP->CON->transaction();
                $find = Gallery::find($id);
                $name = $find->name;
                $find->update_attributes($update_attributes);
                $APP->CON->commit();
                $APP->activityLog("Update Gallery {$name}");

                return $APP->response(true, 'success', 'SUCCESS.UPDATE.COMPLETED', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        }
        return $APP->response(false, 'danger', 'ERROR.EMPTY.VALUE', $json);
    }*/

    /*private static function insert($attributes = array(), $id = 0, $json = false) {
        try {
            global $APP;
            $APP->CON->transaction();
            if (isset($_SESSION[CLIENT.LAYER])) {

                if (!empty($attributes)) {

                    $APP->setDependencyModule(array('Gallery', 'Authentication'));
                    $array = array();
                    $token = "";


                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'gallery','insert')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

                    foreach ($attributes as $key => $value) {
                        if (isset($value->name)) {
                            if(empty($value->value)){
                                $value->value = null;
                            }
                            if ($value->name === 'path') {
     *                          if(!empty($value->value)){
                                    if(substr($value->value,-1) != '/'){
                                        Tools::formatUrl($value->value . '/');
                                    }else{
     *                                  Tools::formatUrl($value->value);
     *                              }
     *                          }
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

                    $find = Gallery::find('all', array('conditions' => "name = '{$array['name']}' OR path = '{$array['path']}'"));

                    if ($find) {
                        throw new Exception("There is already a gallery registered under that name or path!");
                    }

                    Gallery::create($array);
                    $APP->CON->commit();
                    $APP->activityLog("Register Gallery {$array['name']}");
                    return $APP->response(true, 'success', 'SUCCESS.CREATE.COMPLETED', $json);
                } else {
                    throw new Exception("ERROR.NOTFOUND.VALUE");
                }
            } else {
                throw new Exception("ERROR.NOTFOUND.SESSION");
            }
        } catch (\Exception $e) {
            $APP->CON->rollback();
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }*/

    private static function list_contents($attributes = array(), $id = 0, $json = false) {
        global $APP;
        try {

//            if (isset($_SESSION[CLIENT.LAYER])) {
                global $APP;
                $APP->setDependencyModule(array('Authentication'));
                $ext = self::$ext;
                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if ($value->name === 'path') {
                            $path = $value->value;
                        }
                        if ($value->name === 'filetype') {
                            $filetype = $value->value;
                        }
                        if ($value->name === 'token') {
                            $token = $value->value;
                            unset($attributes[$key]);
                        }
                    }
                }

//                if (Authentication::check_token($token) === false) {
//                    throw new Exception("ERROR.INVALID.TOKEN");
//                }

                if (empty($path)) {

                    $contents['path'] = League\Flysystem\Util::pathinfo('/');
                    $contents['contents'] = $APP->GALLERY->listContents();
                }else{
                    $contents['path'] = League\Flysystem\Util::pathinfo($path);
                    $contents['contents'] = $APP->GALLERY->listContents($path);
                }

                if(isset($filetype)){
                    switch($filetype){
                        case 'image': {

                            $ext = self::$extImg;
                            break;
                        }
                        case 'music': {

                            $ext = self::$extMusic;
                            break;
                        }
                        case 'video': {

                            $ext = self::$extVideo;
                            break;
                        }
                        case 'document': {

                            $ext = self::$extDoc;
                            break;
                        }
                    }
                }

                foreach ($contents['contents'] as $key => $value) {

                    //retirando . e ..
                    if($contents['contents'][$key]['basename'] === '.' || $contents['contents'][$key]['basename'] === '..' || $contents['contents'][$key]['basename'] === 'thumb'){
                        unset($contents['contents'][$key]);
                    }else{
                        if ($contents['contents'][$key]['type'] === 'file') {

                            if (array_search($contents['contents'][$key]['extension'], $ext) === false) {
                                unset($contents['contents'][$key]);
                            }
                        }
                    }
                }

                return $APP->response($contents, 'success', '', $json);
//            } else {
//                return $APP->response(false, 'danger', 'ERROR.NOTFOUND.SESSION', $json);
//            }
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function add_folder($attributes = array(), $id = 0, $json = false) {
        try {
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {
                global $APP;
                $APP->setDependencyModule(array('Gallery', 'Authentication'));

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if ($value->name === 'path') {
                            //$path = preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $value->value ));
                            $path = Tools::removeCharacters(Tools::removeAccents($value->value));

                        }
                        if ($value->name === 'token') {
                            $token = $value->value;
                            unset($attributes[$key]);
                        }
                    }else{
                        if($key === 'path'){
                            $path = Tools::removeCharacters(Tools::removeAccents($value));
                        }
                        if ($key === 'token') {
                            $token = $value;
                        }
                    }
                }

                if (Authentication::check_token($token) === false) {
                    throw new Exception("ERROR.INVALID.TOKEN");
                }

                if (!empty($path)) {
                    $res = $APP->GALLERY->createDir($path);
                    return $APP->response(true, 'success', 'MODULE.GALLERY.SUCCESS.CREATE.FOLDER', $json);
                } else {
                    return $APP->response(false, 'danger', 'MODULE.GALLERY.ERROR.EMPTY.NAME.FOLDER', $json);
                }
            } else {
                return $APP->response(false, 'danger', 'ERROR.NOTFOUND.SESSION', $json);
            }
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function rename_file($attributes = array(), $id = 0, $json = false) {
        try {
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {
                global $APP;
                $APP->setDependencyModule(array('Authentication'));

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if ($value->name === 'name') {
                            $file = $value->value;
                        }
                        if ($value->name === 'new-name') {
                            //$newfile = Tools::removeCharacters(Tools::removeAccents($value->value));
                            $newfile = Tools::removeCharacters(Tools::removeAccents($value->value));
                        }
                        if ($value->name === 'token') {
                            $token = $value->value;
                            unset($attributes[$key]);
                        }
                    }
                }

                if (Authentication::check_token($token) === false) {
                    throw new Exception("ERROR.INVALID.TOKEN");
                }

                if (!empty($file) && !empty($newfile)) {
                    
                    $APP->GALLERY->rename($file, $newfile);
                    
                    if(strpos($file,DS) !== false){
                        $aux = explode(DS,$file);
                        $fileEnd = end($aux);
                        $key = array_search($fileEnd,$aux);
                        $aux[$key-1] .= DS . 'thumb';
                        $path = "";
                        
                        foreach($aux as $key => $value){
                            $path .= $value . DS;
                        }
                        $path = substr($path,0,-1);
                        
                        if(file_exists(DIR_UPLOADS . DS . $path)){
                            
                            $aux = explode(DS,$newfile);
                            $fileEnd = end($aux);
                            $key = array_search($fileEnd,$aux);
                            $aux[$key-1] .= DS . 'thumb';
                            $path2 = "";

                            foreach($aux as $key => $value){
                                $path2 .= $value . DS;
                            }
                            $path2 = substr($path,0,-1);
                            
                            $APP->GALLERY->rename($path, $path2);
                        }
                    }else{
                        if(file_exists(DIR_UPLOADS . DS . 'thumb' . DS . $file)){
                            $APP->GALLERY->rename('thumb'.DS.$file, 'thumb'.DS.$newfile);
                        }
                    }
                    
                    $contents['path'] = League\Flysystem\Util::pathinfo($file);

                    return $APP->response($contents, 'success', '', $json);
                }else{
                    throw new Exception("MODULE.GALLERY.ERROR.EMPTY.NAME.FILE");
                }
            } else {
                return $APP->response(false, 'danger', 'ERROR.NOTFOUND.SESSION', $json);
            }
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function rename_folder($attributes = array(), $id = 0, $json = false) {
        try {
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {
                global $APP;
                $APP->setDependencyModule(array('Authentication'));

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if ($value->name === 'name') {
                            $file = $value->value;
                        }
                        if ($value->name === 'new-name') {
                            //$newfile = preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $value->value) );
                            $newfile = Tools::removeCharacters(Tools::removeAccents($value->value));
                        }
                        if ($value->name === 'token') {
                            $token = $value->value;
                            unset($attributes[$key]);
                        }
                    }
                }

                if (Authentication::check_token($token) === false) {
                    throw new Exception("ERROR.INVALID.TOKEN");
                }

                if (!empty($file) && !empty($newfile)) {
                    rename(DIR_UPLOADS . DS . $file, DIR_UPLOADS . DS . $newfile);
                    $contents['path'] = League\Flysystem\Util::pathinfo($file);

                    return $APP->response($contents, 'success', '', $json);
                }else{
                    throw new Exception("MODULE.GALLERY.ERROR.EMPTY.NAME.FILE");
                }
            } else {
                return $APP->response(false, 'danger', 'ERROR.NOTFOUND.SESSION', $json);
            }
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function delete_folder($attributes = array(), $id = 0, $json = false) {
        try {
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {
                global $APP;
                $APP->setDependencyModule(array('Authentication'));

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if ($value->name === 'folder') {
                            $folder = $value->value;
                        }
                        if ($value->name === 'token') {
                            $token = $value->value;
                            unset($attributes[$key]);
                        }
                    }
                }

                if (Authentication::check_token($token) === false) {
                    throw new Exception("ERROR.INVALID.TOKEN");
                }

                if (!empty($folder)) {
                    $APP->GALLERY->deleteDir($folder);

                    return $APP->response(true, 'success', 'MODULE.GALLERY.SUCCESS.DELETE.FOLDER', $json);
                }else{
                    throw new Exception("MODULE.GALLERY.ERROR.EMPTY.PATH.FOLDER");
                }
            } else {
                return $APP->response(false, 'danger', 'ERROR.NOTFOUND.SESSION', $json);
            }
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function upload_file($attributes = array(), $id = 0, $json = false) {
        try {
            global $APP;
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {
                $APP->setDependencyModule(array('Authentication'));

                if(isset($_POST['path']) && isset($_POST['token'])){
                    $path = $_POST['path'];
                    $token = $_POST['token'];
                }else{
                    throw new Exception("There is all the information needed to upload!");
                }

                if (Authentication::check_token($token) === false) {
                    throw new Exception("ERROR.INVALID.TOKEN");
                }

                if (!empty($path)) {
                    if (isset($_FILES['file'])) {

                        $file_name = strtolower(Tools::removeCharacters(Tools::removeAccents($_FILES['file']['name'])));

                        $file_size = $_FILES['file']['size'];
                        $file_tmp = $_FILES['file']['tmp_name'];
                        $file_type = $_FILES['file']['type'];
                        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                        
                        if (in_array($file_ext, self::$ext) === false) {
                            throw new Exception("The file extension is not allowed!");
                        }
                        if ($file_size > self::$maxSize) {
                            //throw new Exception("The file size exceeds 2MB!");
                        }

                        move_uploaded_file($file_tmp, DIR_UPLOADS . DS . $path . DS . $file_name);

                        //cria thumb
                        if (array_search($file_ext, self::$extImg) !== false){
                            
                            $token = (isset($_SESSION[CLIENT.LAYER])) ? $_SESSION[CLIENT.LAYER] : "";
                            if(!file_exists(DIR_UPLOADS . DS . $path . DS . 'thumb')){
                                self::add_folder(array('path' => $path . DS . 'thumb','token' => $token));
                            }
                            $size    = new Imagine\Image\Box(THUMB_WIDTH, THUMB_HEIGHT);
                            $mode    = Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;
                            $image = self::$IMG->open(DIR_UPLOADS . DS . $path . DS . $file_name)->thumbnail($size, $mode)->save(DIR_UPLOADS . DS . $path . DS . 'thumb' . DS . $file_name);
                            
                            //Filtrando imagens JPG, PNG e GIF
                            
                            if($file_ext == 'jpg' || $file_ext == 'jpeg' || $file_ext == 'png' || $file_ext == 'gif'){
                                self::$IMG->open(DIR_UPLOADS . DS . $path . DS . $file_name)->save(DIR_UPLOADS . DS . $path . DS . $file_name, array('quality' => COMPRESS_IMAGE));
                            }
                        }
                        //

                        return $APP->response(true, 'success', 'MODULE.GALLERY.SUCCESS.UPLOAD.FILE', $json);
                    }
                }else{
                    throw new Exception("MODULE.GALLERY.ERROR.EMPTY.PATH.FILE");
                }
            } else {
                return $APP->response(false, 'danger', 'ERROR.NOTFOUND.SESSION', $json);
            }
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function delete_file($attributes = array(), $id = 0, $json = false) {
        try {
            global $APP;
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {
                
                $APP->setDependencyModule(array('Authentication'));

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if ($value->name === 'file') {
                            $file = $value->value;
                        }
                        if ($value->name === 'token') {
                            $token = $value->value;
                            unset($attributes[$key]);
                        }
                    }
                }

                if (Authentication::check_token($token) === false) {
                    throw new Exception("ERROR.INVALID.TOKEN");
                }

                if (!empty($file)) {
 
                    $APP->GALLERY->delete($file);
                    
                    //Deleta thumb
                    
                    if(strpos($file,DS) !== false){
                        $aux = explode(DS,$file);
                        $fileEnd = end($aux);
                        $key = array_search($fileEnd,$aux);
                        $aux[$key-1] .= DS . 'thumb';
                        $path = "";
                        
                        foreach($aux as $key => $value){
                            $path .= $value . DS;
                        }
                        $path = substr($path,0,-1);
                        
                        if(file_exists(DIR_UPLOADS . DS . $path)){
                            $APP->GALLERY->delete($path);
                        }
                    }else{
                        if(file_exists(DIR_UPLOADS . DS . 'thumb' . DS . $file)){
                            $APP->GALLERY->delete('thumb' . DS . $file);
                        }
                    }

                    return $APP->response(true, 'success', 'MODULE.GALLERY.SUCCESS.DELETE.FILE', $json);
                }else{
                    throw new Exception("MODULE.GALLERY.ERROR.EMPTY.PATH.FILE");
                }
            } else {
                return $APP->response(false, 'danger', 'ERROR.NOTFOUND.SESSION', $json);
            }
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function delete_files($attributes = array(), $id = 0, $json = false) {
        try {
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {
                global $APP;
                $APP->setDependencyModule(array('Authentication'));

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if ($value->name === 'files') {
                            $files = $value->value;
                        }
                        if ($value->name === 'token') {
                            $token = $value->value;
                            unset($attributes[$key]);
                        }
                    }
                }

                if (Authentication::check_token($token) === false) {
                    throw new Exception("ERROR.INVALID.TOKEN");
                }

                if (!empty($files)) {
                    foreach($files as $value){
                        
                        $APP->GALLERY->delete($value);
                        
                        //Deleta thumb
                        
                        if(strpos($value,DS) !== false){
                            $aux = explode(DS,$value);
                            $fileEnd = end($aux);
                            $key = array_search($fileEnd,$aux);
                            $aux[$key-1] .= DS . 'thumb';
                            $path = "";

                            foreach($aux as $key => $value2){
                                $path .= $value2 . DS;
                            }
                            $path = substr($path,0,-1);
                            
                            if(file_exists(DIR_UPLOADS . DS . $path)){
                                $APP->GALLERY->delete($path);
                            }
                        }else{
                            if(file_exists(DIR_UPLOADS . DS . 'thumb' . DS . $value)){
                                $APP->GALLERY->delete('thumb' . DS . $value);
                            }
                        }
                    }

                    return $APP->response(true, 'success', 'MODULE.GALLERY.SUCCESS.DELETE.FILE', $json);
                }else{
                    throw new Exception("MODULE.GALLERY.ERROR.EMPTY.PATH.FILE");
                }
            } else {
                return $APP->response(false, 'danger', 'ERROR.NOTFOUND.SESSION', $json);
            }
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function recursive_delete($dir){
        try{
            $files = glob($dir.'{,.}*', GLOB_BRACE);
            foreach($files as $file){
              if(is_file($file))
                unlink($file);
            }
            return true;
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    private static function recursive_copy($src,$dst,$mkdir = false){
        try{
            $dir = opendir($src);
            if($mkdir)
                mkdir($dst,0777);

            while(false !== ( $file = readdir($dir)) ) {
                if (( $file != '.' ) && ( $file != '..' )) {
                    if ( is_dir($src . DS . $file) ) {
                        self::recursive_copy($src . DS . $file,$dst . DS . $file,true);
                    }
                    else {
                        copy($src . DS . $file,$dst . DS . $file);
                    }
                }
            }
            closedir($dir);

            return true;
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    private static function move_folder($attributes = array(), $id = 0, $json = false) {
        try {
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {
                global $APP;
                $APP->setDependencyModule(array('Authentication'));
                $return = false;

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if ($value->name === 'folder') {
                            $folder = $value->value;
                        }
                        if ($value->name === 'path') {
                            $path = str_replace('/',DS,$value->value).DS;
                        }
                        if ($value->name === 'token') {
                            $token = $value->value;
                            unset($attributes[$key]);
                        }
                    }
                }

                if (Authentication::check_token($token) === false) {
                    throw new Exception("ERROR.INVALID.TOKEN");
                }

                if (!empty($folder) && !empty($path)) {

                        $folder->dirname = str_replace('/',DS,$folder->dirname).DS;
                       if(!file_exists(DIR_UPLOADS.DS.$path.$folder->basename)){
                           $return = self::recursive_copy(DIR_UPLOADS.DS.$folder->dirname.$folder->basename, DIR_UPLOADS.DS.$path.$folder->basename,true);

                           if($return !== true){
                               throw new Exception($return);
                           }

                           $APP->GALLERY->deleteDir($folder->dirname.$folder->basename);
                       }else{

                           $return = self::recursive_copy(DIR_UPLOADS.DS.$folder->dirname.$folder->basename, DIR_UPLOADS.DS.$path.$folder->basename);
                           if($return !== true){
                               throw new Exception($return);
                           }

                           $APP->GALLERY->deleteDir($folder->dirname.$folder->basename);
                       }

                    return $APP->response(true, 'success', 'MODULE.GALLERY.SUCCESS.DELETE.FILE', $json);
                }else{
                    throw new Exception("MODULE.GALLERY.ERROR.EMPTY.PATH.FILE");
                }
            } else {
                return $APP->response(false, 'danger', 'ERROR.NOTFOUND.SESSION', $json);
            }
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function move_files($attributes = array(), $id = 0, $json = false) {
        try {
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {
                global $APP;
                $APP->setDependencyModule(array('Authentication'));
                $return = false;

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if ($value->name === 'files') {
                            $files = $value->value;
                        }
                        if ($value->name === 'path') {
                            $path = str_replace('/',DS,$value->value).DS;
                        }
                        if ($value->name === 'token') {
                            $token = $value->value;
                            unset($attributes[$key]);
                        }
                    }
                }

                if (Authentication::check_token($token) === false) {
                    throw new Exception("ERROR.INVALID.TOKEN");
                }

                if (!empty($files) && !empty($path)) {
                    foreach($files as $value){
                        $value->dirname = str_replace('/',DS,$value->dirname).DS;
                        $APP->GALLERY->rename($value->dirname.$value->basename,$path.$value->basename);
                        $APP->GALLERY->rename($value->dirname.'thumb'.DS.$value->basename,$path.'thumb'.DS.$value->basename);
                    }

                    return $APP->response(true, 'success', 'MODULE.GALLERY.SUCCESS.DELETE.FILE', $json);
                }else{
                    throw new Exception("MODULE.GALLERY.ERROR.EMPTY.PATH.FILE");
                }
            } else {
                return $APP->response(false, 'danger', 'ERROR.NOTFOUND.SESSION', $json);
            }
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

}

?>
