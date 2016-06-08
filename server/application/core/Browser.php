<?php

   class Browser {

       public $detect;
       public $ismobile;
       private $props = array(
           "Version" => "0.0.0",
           "Name" => "unknown",
           "Agent" => "unknown",
           "Classe" => ""
       );

       public function __Construct()
       {
           $this->detect = new Mobile_Detect();

           $browsers = array(
               "firefox" => 'gecko',
               "msie" => 'ie',
               "opera" => 'opera',
               "chrome" => 'webkit',
               "safari" => 'webkit',
               "mozilla" => 'gecko'
           );

           $this->Agent = strtolower($_SERVER['HTTP_USER_AGENT']);
           foreach ($browsers as $browser => $sigla)
           {
               if (preg_match("#($browser)[/ ]?([0-9.]*)#", $this->Agent, $match))
               {
                   $this->Classe = $sigla;
                   $this->Name = $match[1];
                   $this->Version = (int) $match[2];
                   break;
               }
           }
       }

       public function __Get($name)
       {
           if (!array_key_exists($name, $this->props))
           {
               die("No such property or function $name");
           }
           return $this->props[$name];
       }

       public function __Set($name, $val)
       {
           if (!array_key_exists($name, $this->props))
           {
               SimpleError("No such property or function.", "Failed to set $name", $this->props);
               die;
           }
           $this->props[$name] = $val;
       }

   }

?>
