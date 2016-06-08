<?php

              function ProfilemenuModel($class_name)
              {
                  $file = 'modules/griffo/Profilemenu/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function ProfilemenuBaseModel($class_name)
              {
                  $file = 'modules/griffo/Profilemenu/model/' . $class_name . 'Base.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function ProfilemenuController($class_name)
              {
                  $file = 'modules/griffo/Profilemenu/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('ProfilemenuModel');
              spl_autoload_register('ProfilemenuBaseModel');
              spl_autoload_register('ProfilemenuController');

?>
