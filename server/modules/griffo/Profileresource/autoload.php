<?php

          function ProfileresourceModel($class_name)
          {
              $file = 'modules/griffo/Profileresource/model/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function ProfileresourceBaseModel($class_name)
          {
              $file = 'modules/griffo/Profileresource/model/' . $class_name . 'Base.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function ProfileresourceController($class_name)
          {
              $file = 'modules/griffo/Profileresource/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('ProfileresourceModel');
          spl_autoload_register('ProfileresourceBaseModel');
          spl_autoload_register('ProfileresourceController');
?>
