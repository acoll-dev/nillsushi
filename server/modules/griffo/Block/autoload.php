<?php

              function BlockModel($class_name)
              {
                  $file = 'modules/griffo/Block/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function BlockBaseModel($class_name)
              {
                  $file = 'modules/griffo/Block/model/' . $class_name . 'Base.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function BlockController($class_name)
              {
                  $file = 'modules/griffo/Block/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('BlockModel');
              spl_autoload_register('BlockBaseModel');
              spl_autoload_register('BlockController');

?>
