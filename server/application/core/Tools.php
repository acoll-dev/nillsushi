<?php

class Tools {

    private static $erros = array();
    private static $Blowfish_Pre = '$6$rounds=5000$';
    private static $Blowfish_End = '$';
    
    public static $salt_Pre = '89!@ad*&1';
    public static $salt_End = 'a7\%th6>ad';
    

    public static function tokenGenerator() {
        return md5(base64_encode(pack('N6', mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), uniqid())));
    }

    public static function myMD5($string, $salt = false) {
        $string = ($salt === true) ? self::$salt_Pre . $string . self::$salt_End : $string;
        return md5($string);
    }

    public static function checkMyMD5($string, $hash, $salt = false) {
        $string = ($salt === true) ? self::$salt_Pre . $string . self::$salt_End : $string;
        return strcmp(md5($string), $hash) == 0 ? true : false;
    }

    public static function myCrypt($string) {
        $Allowed_Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./';
        $salt = '';
        for ($i = 0; $i < 21; $i ++) {
            $salt .= $Allowed_Chars [mt_rand(0, 63)];
        }

        $bcrypt_salt = self::$Blowfish_Pre . $salt . self::$Blowfish_End;
        $Crypted = new stdClass;

        $Crypted->hash = crypt($string, $bcrypt_salt);
        $Crypted->salt = $salt;

        return $Crypted;
    }

    public static function loading($arrayDir) {
        if (is_array($arrayDir) && !empty($arrayDir)) {
            foreach ($arrayDir as $value) {

                $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($value, FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_SELF));
                foreach ($iterator as $item) {

                    if (!$item->isDot()) {
                        if ($item->getFilename() === 'autoload.php') {
                            require_once($item->getPathname());
                            $item->next();
                        } else {
                            if ($item->isFile()) {
                                if ($item->getExtension() === 'php') {
                                    require_once($item->getPathname());
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    static public function sortKeyArray($array = '', $keySort = '') {

        if (!empty($array) && !empty($keySort) && is_array($array)) {

            usort($array, function($a, $b) use ($keySort) {
                return $a[$keySort] > $b[$keySort];
            });

            return $array;
        }
        return false;
    }

    static public function getDiaSemana($data, $abr = 0) {
        // $data: 1999-01-05

        $ano = substr($data, 0, 4);
        $mes = substr($data, 5, -3);
        $dia = substr($data, 8, 9);

        $diasemana = date("w", mktime(0, 0, 0, $mes, $dia, $ano));
        $semana = '';
        if ($abr == 0) {
            switch ($diasemana) {
                case 0: $semana = "Segunda-feira";
                    break;
                case 2: $semana = "Terça-feira";
                    break;
                case 3: $semana = "Quarta-feira";
                    break;
                case 4: $semana = "Quinta-feira";
                    break;
                case 5: $semana = "Sexta-feira";
                    break;
                case 6: $semana = "Sábado";
                    break;
                case 7: $semana = "Domingo";
                    break;
            }
        } else {
            switch ($diasemana) {
                case 1: $semana = "Segunda";
                    break;
                case 2: $semana = "Terça";
                    break;
                case 3: $semana = "Quarta";
                    break;
                case 4: $semana = "Quinta";
                    break;
                case 5: $semana = "Sexta";
                    break;
                case 6: $semana = "Sábado";
                    break;
                case 0: $semana = "Domingo";
                    break;
            }
        }

        return $semana;
    }

    static public function check_email($email) {
        if (preg_match('/^\w[-.\w]*@(\w[-._\w]*\.[a-zA-Z]{2,}.*)$/', $email, $matches) === 1) {
            if (function_exists('checkdnsrr')) {
                if (checkdnsrr($matches[1] . '.', 'MX'))
                    return true;
                if (checkdnsrr($matches[1] . '.', 'A'))
                    return true;
            } else
                return true;
        }
        return false;
    }

    static public function check_url($url) {
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_HEADER, 1); //obter cabeçalho
        curl_setopt($c, CURLOPT_NOBODY, 1); //e *apenas* obter cabeçalho
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1); //obter a resposta como uma seqüência de curl_exec(), em vez de ecoa-lo
        curl_setopt($c, CURLOPT_FRESH_CONNECT, 1); //não use uma versão em cache do url
        if (!curl_exec($c)) {
            return false;
        } else {
            return true;
        }
    }

    static public function check_telefone($telefone) {
        if (!empty($telefone)) {
            if (preg_match('/^[(]([0-9]{2})[)] ([0-9]{4})[-]([0-9]{4})/i', $telefone))
                return true;
            else
                return false;
        } else
            return false;
    }

    static public function check_cep($cep) {
        if (!empty($cep)) {
            if (preg_match('/^([0-9]{2})[.]([0-9]{3})[-]([0-9]{3})/i', $cep))
                return true;
            else
                return false;
        } else
            return false;
    }

    static public function formataData($data, $delimitador) {
        if ($delimitador == '/')
            return join('/', array_reverse(explode('-', $data)));
        elseif ($delimitador == '-')
            return join('-', array_reverse(explode('/', $data)));
        else
            return false;
    }

    function checkDateTime($data) {
        if (date('Y-m-d H:i:s', strtotime($data)) == $data) {
            return true;
        } else {
            return false;
        }
    }

    static public function formataTimestamp($data) {
        $arrayData = explode(' ', $data);
        $time = end($arrayData);
        $date = array_shift($arrayData);

        if (strpos($date, '-') === FALSE)
            return Tools::formataData($date, '-') . ' ' . $time;
        elseif (strpos($date, '/') === FALSE)
            return Tools::formataData($date, '/') . ' ' . $time;
    }

    static public function checaData($vr) {
        if (strpos($vr, '-') === false) {
            $data = explode('/', $vr);
            if (isset($data[0]) && isset($data[1]) && isset($data[2])) {
                $checkdate = checkdate($data[1], $data[0], $data[2]);

                if (!empty($checkdate))
                    return $data[2] . '-' . $data[1] . '-' . $data[0];
                else
                    return false;
            }
            else {
                return false;
            }
        } elseif (strpos($vr, '/') === false) {
            $data = explode('-', $vr);
            if (isset($data[0]) && isset($data[1]) && isset($data[2])) {
                $checkdate = checkdate($data[1], $data[2], $data[0]);

                if (!empty($checkdate))
                    return $data[2] . '/' . $data[1] . '/' . $data[0];
                else
                    return false;
            }
            else {
                return false;
            }
        } else
            return false;
    }

    static public function removeAccents($string) {
        if (!empty($string)) {
            $array1 = array('à', 'á', 'â', 'ã', 'ä', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ç', 'ñ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', '&', 'Ç', 'ç');
            $array2 = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'c', 'n', 'A', 'A', 'A', 'A', 'A', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'e', 'C', 'c');

            return str_replace($array1, $array2, $string);
        } else
            return false;
    }

    static public function removeCharacters($string, $replace = "") {
        if (!empty($string)) {
            $characters = ' -,-"-!-@-#-$-%-¨-*-(-)-_-+-=-´-`-[-]-{-}-~-^-?-;-:-<->-ª-º-°-¹-²-³-£-¢-¬-§-|';

            $c = explode('-', $characters);

            foreach ($c as $character)
                $string = str_replace($character, $replace, $string);

            return $string;
        } else
            return false;
    }

    static public function formatUrl($string) {
        if (!empty($string)) {
            /* $string = trim($string);
              $string = str_replace(' ', '-', $string);
              $string = Tools::removeCaracteres($string, '');
              $string = Tools::removeAcentos($string);
              $string = str_replace('---', '-', $string);
              $string = str_replace('--', '-', $string); */
                
            //$string = str_replace('.','',$string);
            
            $string = strtolower(filter_var($string, FILTER_SANITIZE_URL));

            return $string;
        }
    }

    static public function formatarMoeda($valor) {
        $valor = str_replace('.', '', $valor);
        $valor = str_replace(',', '.', $valor);

        return $valor;
    }

    static public function setErros($label, $mensagem) {
        Tools::$erros[] = '<a href="#label_' . $label . '">' . $mensagem . '</a>';
    }

    static public function getErros() {
        return Tools::$erros;
    }

    static public function dataExtenso($data, $timestamp = false) {
        if (!empty($timestamp)) {
            $arrayData = explode(' ', $data);
            $time = end($arrayData);
            $date = array_shift($arrayData);

            $novaData = Tools::formataData($date, '/');

            $explodeData = explode('/', $novaData);

            $dataExtenso = $explodeData[0] . ' de ' . Tools::mesExtenso($explodeData[1]) . ' de ' . $explodeData[2];

            return $dataExtenso;
        } else {
            
        }
    }

    static public function mesExtenso($mes, $abreviacao = false) {
        switch ($mes) {
            case '01': return (empty($abreviacao) ? 'Janeiro' : 'Jan');
                break;
            case '02': return (empty($abreviacao) ? 'Fevereiro' : 'Fev');
                break;
            case '03': return (empty($abreviacao) ? 'Março' : 'Mar');
                break;
            case '04': return (empty($abreviacao) ? 'Abril' : 'Abr');
                break;
            case '05': return (empty($abreviacao) ? 'Maio' : 'Mai');
                break;
            case '06': return (empty($abreviacao) ? 'Junho' : 'Jun');
                break;
            case '07': return (empty($abreviacao) ? 'Julho' : 'Jul');
                break;
            case '08': return (empty($abreviacao) ? 'Agosto' : 'Ago');
                break;
            case '09': return (empty($abreviacao) ? 'Setembro' : 'Set');
                break;
            case '10': return (empty($abreviacao) ? 'Outubro' : 'Out');
                break;
            case '11': return (empty($abreviacao) ? 'Novembro' : 'Nov');
                break;
            case '12': return (empty($abreviacao) ? 'Dezembro' : 'Dez');
                break;
        }
    }

    static public function getDiaDaSemana($dia) {
        switch ($dia) {
            case '0' : $semana = 'Domingo';
                break;
            case '1' : $semana = 'Segunda';
                break;
            case '2' : $semana = 'Terça';
                break;
            case '3' : $semana = 'Quarta';
                break;
            case '4' : $semana = 'Quinta';
                break;
            case '5' : $semana = 'Sexta';
                break;
            case '6' : $semana = 'Sábado';
                break;
        }

        return $semana;
    }

    static public function charMinMax($vr, $min, $max = 0) {
        if (!empty($vr)) {
            if ($max == 0) {
                if (strlen($vr) >= $min)
                    return true;
            }
            else {
                if (strlen($vr) >= $min && strlen($vr) <= $max)
                    return true;
            }
        }
        return false;
    }

    public static function unzip($file = "", $dir = "") {

        if (!empty($file) && !empty($dir)) {
            if (file_exists($file) && file_exists($dir)) {
                try {
                    $zip = new ZipArchive;
                    $res = $zip->open($file);
                    if ($res === TRUE) {
                        $zip->extractTo($dir);
                        $zip->close();
                        //echo 'File successfully extracted!';
                        return true;
                    } else {
                        //echo 'Failure when trying to open the zip file!';
                        return false;
                    }
                } catch (\Exception $e) {
                    global $APP;
                    $APP->writeLog($e);
                    //echo $e->getMessage();
                    return false;
                }
            } else {
                return false;
            }
        } else {
            //echo 'File or empty directory!';
            return false;
        }
        return false;
    }

    public static function makeSelect($array, $nameid = "", $nameidparent = "", $labelsort = "") {

        $nameid = (!empty($nameid)) ? $nameid : 'idcategory';
        $nameidparent = (!empty($nameidparent)) ? $nameidparent : 'idcategoryparent';
        $labelsort = (!empty($labelsort)) ? $labelsort : 'name';

        function recursiveChilds($array, $id = '', $level = '', $nameid, $nameidparent) {
            $_parents = array();
            $_childs = array();

            foreach ($array as $value) {
                if (!empty($id)) {
                    if ($value[$nameidparent] === $id) {
                        $value['level'] = $level;
                        $_parents[] = $value;
                    } else {
                        $_childs[] = $value;
                    }
                } else {
                    if (empty($value[$nameidparent])) {
                        $value['level'] = 0;
                        $_parents[] = $value;
                    } else {
                        $_childs[] = $value;
                    }
                }
            }
            foreach ($_parents as $key => $_parent) {
                $_parents[$key]['childs'] = recursiveChilds($_childs, $_parent[$nameid], $_parent['level'] + 1, $nameid, $nameidparent);
            }
            return $_parents;
        }

        function objToArray($obj) {
            $arr = [];
            foreach ($obj as $value) {
                array_push($arr, $value->to_array());
            }
            return $arr;
        }

        function make($arr) {
            $_arr = array();
            ksort($arr);

            foreach ($arr as $key => $item) {
                $childs = $item['childs'];
                unset($item['childs']);
                array_push($_arr, $item);
                if (count($childs) > 0) {
                    $_arr = array_merge($_arr, make($childs));
                }
            }
            return $_arr;
        }

        return make(recursiveChilds(objToArray($array), "", "", $nameid, $nameidparent));
    }

    public static function objectToArray($object) {
        if (!is_object($object) && !is_array($object))
            return $object;

        return array_map('self::objectToArray', (array) $object);
    }

    public static function saveUrl($url) {

        try {
            global $APP;
            if (self::check_url(self::formatUrl($url))) {
                $file = DIR_TMP . 'url.json';
                $urls = array();
                if (file_exists($file)) {
                    $urls = json_decode(file_get_contents($file), true);
                    $urls[] = $url;
                    fopen($file, '+w');
                    fwrite($file, json_encode($urls));
                    fclose($file);
                    return true;
                } else {
                    $urls[] = $url;
                    fopen($file, '+x');
                    fwrite($file, $urls);
                    fclose($file);
                    return true;
                }
            } else {
                return false;
            }
        } catch (\Exception $e) {
            $APP->writeLog($e->getMessage());
            return false;
        }
    }

    public static function deleteUrl($url) {
        try {
            global $APP;
            $return = false;
            if (self::check_url(self::formatUrl($url))) {
                $file = DIR_TMP . 'url.json';
                $urls = array();
                $key = false;
                if (file_exists($file)) {
                    $urls = json_decode(file_get_contents($file), true);
                    $key = array_search($url, $urls);
                    if (is_int($key)) {
                        unset($urls[$key]);
                        fopen($file, '+w');
                        fwrite($file, json_encode($urls));
                        fclose($file);
                        $return = true;
                    }
                }
            } else {
                $return = false;
            }

            return $return;
        } catch (\Exception $e) {
            $APP->writeLog($e->getMessage());
            return false;
        }
    }

    public static function recursive_array_search($needle, $haystack) {
        foreach ($haystack as $key => $value) {
            $current_key = $key;
            if ($needle === $value OR ( is_array($value) && recursive_array_search($needle, $value) !== false)) {
                return $current_key;
            }
        }
        return false;
    }

    public static function set_position_array($array, $value, $position = 'last') {
        global $APP;
        switch ($position) {
            case 'first': {

                    if (is_array($array)) {

                        if (is_array($value)) {

                            foreach ($array as $key => $val) {
                                if (is_array($val)) {

                                    foreach ($val as $key2 => $val2) {

                                        if (is_int($key2)) {
                                            $value[] = $val2;
                                        } else {
                                            $value[$key2] = $val2;
                                        }
                                    }
                                } else {
                                    if (is_int($key)) {
                                        $value[] = $val;
                                    } else {
                                        $value[$key] = $val;
                                    }
                                }
                            }

                            $array = $value;
                        } else {
                            array_unshift($array[0], $value);
                            $arraytmp = array();
                            $cont = 0;
                            foreach ($array as $val) {
                                if ($cont === 0) {
                                    $arraytmp[] = $value;
                                    $cont++;
                                }
                                if (is_array($val)) {

                                    foreach ($val as $val2) {
                                        $arraytmp[] = $val2;
                                    }
                                } else {
                                    $arraytmp[] = $val;
                                }
                            }

                            $array = $arraytmp;
                        }
                    }

                    break;
                }
            default : {
                    if (is_array($array)) {
                        if (is_array($value)) {
                            foreach ($value as $key => $val) {
                                if (is_int($key)) {
                                    $array[] = $val;
                                } else {
                                    $array[$key] = $val;
                                }
                            }
                        } else {
                            $array[] = $value;
                        }
                    }

                    break;
                }
        }

        return $array;
    }

    private static function send_email($array) {

        global $APP;
        try {
            $mail = new PHPMailer(true);
            $mail->IsSMTP();
            $mail->isHTML(true);
            $mail->Host = "smail.acoll.com.br";
            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host = "smail.acoll.com.br";
            $mail->Port = 465;
            $mail->Username = "webmaster@acoll.com.br";
            $mail->Password = "_z9mx@-S]6]c";
            $mail->CharSet = 'utf-8';

            if (!empty($array['subject']) && !empty($array['address'])) {

                $body = '<html>';

                /* $body .= $mail->HeaderLine("Organization" , CLIENT);
                  $body .= $mail->HeaderLine("Content-Transfer-encoding" , "8bit");
                  $body .= $mail->HeaderLine("Message-ID" , "<".md5(uniqid(time()))."@{$_SERVER['SERVER_NAME']}>");
                  $body .= $mail->HeaderLine("X-MSmail-Priority" , "Normal");
                  $body .= $mail->HeaderLine("X-Mailer" , "Microsoft Office Outlook, Build 11.0.5510");
                  $body .= $mail->HeaderLine("X-MimeOLE" , "Produced By Microsoft MimeOLE V6.00.2800.1441");
                  $body .= $mail->HeaderLine("X-Sender" , $mail->Sender);
                  $body .= $mail->HeaderLine("X-AntiAbuse" , "This is a solicited email for - ".CLIENT." mailing list.");
                  $body .= $mail->HeaderLine("X-AntiAbuse" , "Servername - {$_SERVER['SERVER_NAME']}");
                  $body .= $mail->HeaderLine("X-AntiAbuse" , $mail->Sender);
                 *
                 */
                $body .= "<body><div>" . $array['body'];

                if (!empty($array['cc'])) {
                    if (is_array($array['cc'])) {
                        foreach ($array['cc'] as $value) {
                            if (!empty($value)) {
                                $mail->AddReplyTo($value);
                            }
                        }
                    } else {
                        $mail->AddReplyTo($array['cc']);
                    }
                }

                if (!empty($array['cco'])) {
                    if (is_array($array['cco'])) {
                        foreach ($array['cco'] as $value) {
                            if (!empty($value)) {
                                $mail->AddBCC($value);
                            }
                        }
                    } else {
                        $mail->AddBCC($array['cco']);
                    }
                }

                if (!empty($array['address'])) {
                    if (is_array($array['address'])) {
                        foreach ($array['address'] as $value) {
                            $mail->AddAddress($value);
                        }
                    } else {
                        $mail->AddAddress($array['address']);
                    }
                }

                if (!empty($array['attachment'])) {
                    if (is_array($array['attachment'])) {
                        foreach ($array['attachment'] as $value) {
                            if (!empty($value)) {
                                $mail->AddAttachment($value);
                            }
                        }
                    } else {
                        $mail->AddAttachment($array['attachment']);
                    }
                }

                if (empty($array['from'])) {
                    $mail->SetFrom('webmaster@acoll.com.br', 'Webmaster');
                } else {
                    $mail->SetFrom($array['from']->email, $array['from']->name);
                }

                $mail->Subject = $array['subject'];
                $mail->AltBody = "To view the message , please use a viewer e- mail compatible with HTML!";
                $body .= "</div></body></html>";
                $mail->msgHTML($body);
                $mail->Send();

                $mail->ClearAddresses();
                $mail->ClearCCs();
                $mail->ClearBCCs();
                $mail->ClearReplyTos();
                $mail->ClearAllRecipients();
                $mail->ClearAttachments();
                $mail->ClearCustomHeaders();
                $mail->smtpClose();

                return true;
            } else {
                throw new Exception("MODULE.AUTHENTICATION.ERROR.childJECTANDADDRESS.INVALID");
            }
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return false;
        } catch (\phpmailerException $e) {
            $APP->writeLog($e);
            return false;
        }
    }

    public static function submit_email($attributes = array(), $json = false) {
        global $APP;

        if (!empty($attributes)) {
            try {
                $token = "";
                $APP->setDependencyModule(array('Authentication'));
                $array = array();

                foreach ($attributes as $key => $value) {

                    if (isset($value->name)) {

                        if ($value->name === 'token') {
                            $token = $value->value;
                            unset($attributes[$key]);
                        }
                        if ($value->name === 'body') {
                            $array['body'] = $value->value;
                        }
                        if ($value->name === 'cc') {
                            $array['cc'] = $value->value;
                        }
                        if ($value->name === 'subject') {
                            $array['subject'] = $value->value;
                        }
                        if ($value->name === 'address') {
                            $array['address'] = $value->value;
                        }
                        if ($value->name === 'cco') {
                            $array['cco'] = $value->value;
                        }
                        if ($value->name === 'attachment') {
                            $array['attachment'] = $value->value;
                        }
                        if ($value->name === 'from') {
                            $array['from'] = $value->value;
                        }
                    } else {
                        if ($key === 'token') {
                            $token = $value;
                            unset($attributes[$key]);
                        }
                        if ($key === 'body') {
                            $array['body'] = $value;
                        }
                        if ($key === 'cc') {
                            $array['cc'] = $value;
                        }
                        if ($key === 'subject') {
                            $array['subject'] = $value;
                        }
                        if ($key === 'address') {
                            $array['address'] = $value;
                        }
                        if ($key === 'cco') {
                            $array['cco'] = $value;
                        }
                        if ($key === 'attachment') {
                            $array['attachment'] = $value;
                        }
                        if ($key === 'from') {
                            $array['from'] = $value;
                        }
                    }
                }

                if (!empty($token)) {
                    if (Authentication::check_token($token) === false) {
                        throw new Exception("ERROR.INVALID.TOKEN");
                    }
                }

                self::send_email($array);

                return $APP->response(true, 'success', 'MODULE.AUTHENTICATION.SUCCESS.SEND.MESSAGE', $json);
            } catch (\Exception $e) {
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        } else {
            return $APP->response(false, 'danger', 'ERROR.EMPTY.VALUE', $json);
        }
    }
    
    public static function array_key_exists_recursive($n, $arr){
      foreach ($arr as $key=>$val) {
        if ($n===$key) {
          return $key;
        }
        if (is_array($val)) {
          if(self::array_key_exists_recursive($n, $val)) {
            return $key . ":" . self::array_key_exists_recursive($n, $val);
          }
        }
      }
      return false;
    }
    
    public static function array_child_onelevel($array = array(),$keyid,$keychild,$keysort){

        //Coloca os array filhos dentro de um único array
        
        $newArray = array();
        
        if(is_array($array) && !empty($array)){
            
            function array_values_recursive($arr,$keychild)
            {
              foreach ($arr as $key => $value)
              {
                  if(!empty($value[$keychild])){
                      $arr = array_merge($arr,array_values_recursive($value[$keychild],$keychild));
                  }else{
                      $arr[] = $value;
                  }
              }
              return $arr;
            }
            $newArray = array_values_recursive($array,$keychild);
        }
        
        //Excluí a chave dos arrays filhos
        
        foreach($newArray as $key => $value){
            if(isset($value[$keychild])){
                unset($newArray[$key][$keychild]);
            }
        }
        
        //Remove os arrays duplicados
        
        foreach($newArray as $k => $v){
            foreach($newArray as $key => $value){
                if($k != $key && $v[$keyid] == $value[$keyid]){
                     unset($newArray[$k]);
                }
            }
        }
        
        //Ordena o array de acordo com uma chave
        
        if(self::array_key_exists_recursive($keysort, $newArray) !== false){
            $newArray = self::sortKeyArray($newArray,$keysort);
        }
        
        return $newArray;
    }

}

?>
