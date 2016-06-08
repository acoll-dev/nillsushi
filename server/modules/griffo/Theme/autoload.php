<?php

          function ThemeModel($class_name)
          {
              $file = 'modules/griffo/Theme/model/' . $class_name . '.php';

              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function ThemeBaseModel($class_name)
          {
              $file = 'modules/griffo/Theme/model/' . $class_name . 'Base.php';

              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function ThemeController($class_name)
          {
              $file = 'modules/griffo/Theme/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('ThemeModel');
          spl_autoload_register('ThemeBaseModel');
          spl_autoload_register('ThemeController');
?>
