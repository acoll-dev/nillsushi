<?php

          function TemplateModel($class_name)
          {
              $file = 'modules/griffo/Template/model/' . $class_name . '.php';

              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function TemplateBaseModel($class_name)
          {
              $file = 'modules/griffo/Template/model/' . $class_name . 'Base.php';

              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function TemplateController($class_name)
          {
              $file = 'modules/griffo/Template/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('TemplateModel');
          spl_autoload_register('TemplateBaseModel');
          spl_autoload_register('TemplateController');
?>
