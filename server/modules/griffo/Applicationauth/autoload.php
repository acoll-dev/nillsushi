<?php

              function ApplicationauthModel($class_name)
              {
                  $file = 'modules/griffo/Application/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function ApplicationauthBaseModel($class_name)
              {
                  $file = 'modules/griffo/Application/model/' . $class_name . 'Base.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function ApplicationauthController($class_name)
              {
                  $file = 'modules/griffo/Application/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('ApplicationauthModel');
              spl_autoload_register('ApplicationauthBaseModel');
              spl_autoload_register('ApplicationauthController');

?>
