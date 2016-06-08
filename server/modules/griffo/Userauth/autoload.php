<?php

          function UserauthModel($class_name)
          {
              $file = 'modules/griffo/Userauth/model/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function UserauthBaseModel($class_name)
          {
              $file = 'modules/griffo/Userauth/model/' . $class_name . 'Base.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function UserauthController($class_name)
          {
              $file = 'modules/griffo/Userauth/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('UserauthModel');
          spl_autoload_register('UserauthBaseModel');
          spl_autoload_register('UserauthController');
?>
