<?php

              function BannerModel($class_name)
              {
                  $file = 'modules/Banner/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function BannerController($class_name)
              {
                  $file = 'modules/Banner/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('BannerModel');
              spl_autoload_register('BannerController');
?>

