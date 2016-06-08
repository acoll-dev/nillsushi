<?php

          function UserauthapplicationModel($class_name)
          {
              $file = 'modules/griffo/Userauthapplication/model/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function UserauthapplicationBaseModel($class_name)
          {
              $file = 'modules/griffo/Userauthapplication/model/' . $class_name . 'Base.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function UserauthapplicationController($class_name)
          {
              $file = 'modules/griffo/Userauthapplication/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('UserauthapplicationModel');
          spl_autoload_register('UserauthapplicationBaseModel');
          spl_autoload_register('UserauthapplicationController');
?>
