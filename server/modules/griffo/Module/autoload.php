<?php

          function ModuleModel($class_name)
          {
              $file = 'modules/griffo/Module/model/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function ModuleBaseModel($class_name)
          {
              $file = 'modules/griffo/Module/model/' . $class_name . 'Base.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function ModuleController($class_name)
          {
              $file = 'modules/griffo/Module/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('ModuleModel');
          spl_autoload_register('ModuleBaseModel');
          spl_autoload_register('ModuleController');
?>
