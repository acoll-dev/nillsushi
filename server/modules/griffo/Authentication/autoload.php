<?php

              function AuthenticationModel($class_name)
              {
                  $file = 'modules/griffo/Authentication/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function AuthenticationController($class_name)
              {
                  $file = 'modules/griffo/Authentication/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('AuthenticationModel');
              spl_autoload_register('AuthenticationController');

?>
