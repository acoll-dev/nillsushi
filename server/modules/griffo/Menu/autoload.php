<?php

              function MenuModel($class_name)
              {
                  $file = 'modules/griffo/Menu/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function MenuBaseModel($class_name)
              {
                  $file = 'modules/griffo/Menu/model/' . $class_name . 'Base.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function MenuController($class_name)
              {
                  $file = 'modules/griffo/Menu/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('MenuModel');
              spl_autoload_register('MenuBaseModel');
              spl_autoload_register('MenuController');

?>
