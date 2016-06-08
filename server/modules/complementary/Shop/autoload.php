<?php

              function ShopModel($class_name)
              {
                  $file = 'modules/complementary/Shop/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function ShopController($class_name)
              {
                  $file = 'modules/complementary/Shop/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('ShopModel');
              spl_autoload_register('ShopController');

?>
