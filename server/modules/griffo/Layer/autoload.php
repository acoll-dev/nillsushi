<?php

              function LayerModel($class_name)
              {
                  $file = 'modules/griffo/Layer/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function LayerBaseModel($class_name)
              {
                  $file = 'modules/griffo/Layer/model/' . $class_name . 'Base.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function LayerController($class_name)
              {
                  $file = 'modules/griffo/Layer/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('LayerModel');
              spl_autoload_register('LayerBaseModel');
              spl_autoload_register('LayerController');

?>
