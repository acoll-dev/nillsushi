<?php

              function ApplicationModel($class_name)
              {
                  $file = 'modules/griffo/Application/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function ApplicationBaseModel($class_name)
              {
                  $file = 'modules/griffo/Application/model/' . $class_name . 'Base.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function ApplicationController($class_name)
              {
                  $file = 'modules/griffo/Application/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('ApplicationModel');
              spl_autoload_register('ApplicationBaseModel');
              spl_autoload_register('ApplicationController');

?>
