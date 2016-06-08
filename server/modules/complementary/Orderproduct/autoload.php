<?php
              
              function OrderproductModel($class_name)
              {
                  $file = 'modules/complementary/Orderproduct/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function OrderproductController($class_name)
              {
                  $file = 'modules/complementary/Orderproduct/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('OrderproductModel');
              spl_autoload_register('OrderproductController');

?>
