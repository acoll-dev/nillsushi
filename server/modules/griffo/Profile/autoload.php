<?php

          function ProfileModel($class_name)
          {
              $file = 'modules/griffo/Profile/model/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function ProfileBaseModel($class_name)
          {
              $file = 'modules/griffo/Profile/model/' . $class_name . 'Base.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function ProfileController($class_name)
          {
              $file = 'modules/griffo/Profile/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('ProfileModel');
          spl_autoload_register('ProfileBaseModel');
          spl_autoload_register('ProfileController');
?>
