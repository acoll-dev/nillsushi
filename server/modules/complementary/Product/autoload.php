<?php

              function ProductModel($class_name)
              {
                  $file = 'modules/Product/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function ProductController($class_name)
              {
                  $file = 'modules/Product/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('ProductModel');
              spl_autoload_register('ProductController');

?>
