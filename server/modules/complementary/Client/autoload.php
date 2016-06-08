<?php

              function ClientModel($class_name)
              {
                  $file = 'modules/Client/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }
              
              function ClientController($class_name)
              {
                  $file = 'modules/Client/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('ClientModel');
              spl_autoload_register('ClientController');

?>