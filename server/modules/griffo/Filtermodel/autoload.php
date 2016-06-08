<?php

          function FiltermodelModel($class_name)
          {
              $file = 'modules/griffo/Filtermodel/model/' . $class_name . '.php';

              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function FiltermodelBaseModel($class_name)
          {
              $file = 'modules/griffo/Filtermodel/model/' . $class_name . 'Base.php';

              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function FiltermodelController($class_name)
          {
              $file = 'modules/griffo/Filtermodel/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('FiltermodelModel');
          spl_autoload_register('FiltermodelBaseModel');
          spl_autoload_register('FiltermodelController');
?>
