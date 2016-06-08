<?php

          function CategoryModel($class_name)
          {
              $file = 'modules/griffo/Category/model/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function CategoryBaseModel($class_name)
          {
              $file = 'modules/griffo/Category/model/' . $class_name . 'Base.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function CategoryController($class_name)
          {
              $file = 'modules/griffo/Category/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('CategoryModel');
          spl_autoload_register('CategoryBaseModel');
          spl_autoload_register('CategoryController');
?>
