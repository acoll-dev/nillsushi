<?php

          function ResourceModel($class_name)
          {
              $file = 'modules/griffo/Resource/model/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function ResourceBaseModel($class_name)
          {
              $file = 'modules/griffo/Resource/model/' . $class_name . 'Base.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function ResourceController($class_name)
          {
              $file = 'modules/griffo/Resource/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('ResourceModel');
          spl_autoload_register('ResourceBaseModel');
          spl_autoload_register('ResourceController');
?>
