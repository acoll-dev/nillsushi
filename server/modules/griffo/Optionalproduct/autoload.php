<?php
              
              function OptionalproductModel($class_name)
              {
                  $file = 'modules/griffo/Optionalproduct/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function OptionalproductController($class_name)
              {
                  $file = 'modules/griffo/Optionalproduct/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('OptionalproductModel');
              spl_autoload_register('OptionalproductController');

?>
