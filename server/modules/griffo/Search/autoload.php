<?php

              function SearchModel($class_name)
              {
                  $file = 'modules/griffo/Search/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function SearchBaseModel($class_name)
              {
                  $file = 'modules/griffo/Search/model/' . $class_name . 'Base.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function SearchController($class_name)
              {
                  $file = 'modules/griffo/Search/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('SearchModel');
              spl_autoload_register('SearchBaseModel');
              spl_autoload_register('SearchController');

?>
