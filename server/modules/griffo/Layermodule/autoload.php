<?php

              function LayermoduleModel($class_name)
              {
                  $file = 'modules/griffo/Layermodule/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function LayermoduleBaseModel($class_name)
              {
                  $file = 'modules/griffo/Layermodule/model/' . $class_name . 'Base.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function LayermoduleController($class_name)
              {
                  $file = 'modules/griffo/Layermodule/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('LayermoduleModel');
              spl_autoload_register('LayermoduleBaseModel');
              spl_autoload_register('LayermoduleController');

?>
