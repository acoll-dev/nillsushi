<?php

          function GalleryModel($class_name)
          {
              $file = 'modules/griffo/Gallery/model/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function GalleryBaseModel($class_name)
          {
              $file = 'modules/griffo/Gallery/model/' . $class_name . 'Base.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function GalleryController($class_name)
          {
              $file = 'modules/griffo/Gallery/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('GalleryModel');
          spl_autoload_register('GalleryBaseModel');
          spl_autoload_register('GalleryController');
?>
