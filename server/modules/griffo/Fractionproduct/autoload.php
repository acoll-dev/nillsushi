<?php
              
              function FractionproductModel($class_name)
              {
                  $file = 'modules/griffo/Fractionproduct/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function FractionproductController($class_name)
              {
                  $file = 'modules/griffo/Fractionproduct/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('FractionproductModel');
              spl_autoload_register('FractionproductController');

?>
