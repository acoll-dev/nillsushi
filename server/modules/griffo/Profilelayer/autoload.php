<?php

          function ProfilelayerModel($class_name)
          {
              $file = 'modules/griffo/Profilelayer/model/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function ProfilelayerBaseModel($class_name)
          {
              $file = 'modules/griffo/Profilelayer/model/' . $class_name . 'Base.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function ProfilelayerController($class_name)
          {
              $file = 'modules/griffo/Profilelayer/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('ProfilelayerModel');
          spl_autoload_register('ProfilelayerBaseModel');
          spl_autoload_register('ProfilelayerController');
?>
