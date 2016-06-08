<?php

          function NotificationModel($class_name)
          {
              $file = 'modules/griffo/Notification/model/' . $class_name . '.php';

              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function NotificationBaseModel($class_name)
          {
              $file = 'modules/griffo/Notification/model/' . $class_name . 'Base.php';

              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function NotificationController($class_name)
          {
              $file = 'modules/griffo/Notification/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('NotificationModel');
          spl_autoload_register('NotificationBaseModel');
          spl_autoload_register('NotificationController');
?>
