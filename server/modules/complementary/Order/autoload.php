<?php

              function OrderModel($class_name)
              {
                  $file = 'modules/complementary/Order/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function OrderController($class_name)
              {
                  $file = 'modules/complementary/Order/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('OrderModel');
              spl_autoload_register('OrderController');

?>
