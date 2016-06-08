<?php

          function PageModel($class_name)
          {
              $file = 'modules/griffo/Page/model/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function PageBaseModel($class_name)
          {
              $file = 'modules/griffo/Page/model/' . $class_name . 'Base.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function PageController($class_name)
          {
              $file = 'modules/griffo/Page/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('PageModel');
          spl_autoload_register('PageBaseModel');
          spl_autoload_register('PageController');
?>
