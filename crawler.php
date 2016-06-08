<?php

    // Prerender Middlewares: https://prerender.io/documentation/install-middleware

    define('PROTOCOL', (strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === false) ? 'http://' : 'https://');
    define('THIS', PROTOCOL . $_SERVER['HTTP_HOST'] . explode('?', $_SERVER['REQUEST_URI'])[0]);
    $ch = curl_init('http://service.prerender.io/' . THIS);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    list($header, $contents) = preg_split('/([\r\n][\r\n])\\1/', curl_exec($ch),2);
    $status = curl_getinfo($ch);
    curl_close($ch);
    $header_text = preg_split('/[\r\n]+/', $header);
//    echo '<pre>'; var_dump($header_text); echo '</pre>'; exit;
    foreach($header_text as $header){if(preg_match('/^(?:Content-Type|Content-Language|Set-Cookie):/i', $header)){header($header);}}
    print($contents);
?>
