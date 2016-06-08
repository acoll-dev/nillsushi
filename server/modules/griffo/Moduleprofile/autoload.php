<?php

          function ModuleprofileModel($class_name)
          {
              $file = 'module/griffo/Moduleprofile/model/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function ModuleprofileBaseModel($class_name)
          {
              $file = 'module/griffo/Moduleprofile/model/' . $class_name . 'Base.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function ModuleprofileController($class_name)
          {
              $file = 'module/griffo/Moduleprofile/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('ModuleprofileModel');
          spl_autoload_register('ModuleprofileBaseModel');
          spl_autoload_register('ModuleprofileController');
?>
