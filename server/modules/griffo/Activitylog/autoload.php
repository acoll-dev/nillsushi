<?php

          function ActivitylogModel($class_name)
          {
              $file = 'modules/griffo/Activitylog/model/' . $class_name . '.php';

              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function ActivitylogBaseModel($class_name)
          {
              $file = 'modules/griffo/Activitylog/model/' . $class_name . 'Base.php';

              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function ActivitylogController($class_name)
          {
              $file = 'modules/griffo/Activitylog/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('ActivitylogModel');
          spl_autoload_register('ActivitylogBaseModel');
          spl_autoload_register('ActivitylogController');
?>
