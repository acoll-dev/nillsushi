<?php

          function OptionalModel($class_name)
          {
              $file = 'modules/griffo/Optional/model/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function OptionalBaseModel($class_name)
          {
              $file = 'modules/griffo/Optional/model/' . $class_name . 'Base.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function OptionalController($class_name)
          {
              $file = 'modules/griffo/Optional/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('OptionalModel');
          spl_autoload_register('OptionalBaseModel');
          spl_autoload_register('OptionalController');
?>
