<?php

              function GroupModel($class_name)
              {
                  $file = 'modules/griffo/Group/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function GroupController($class_name)
              {
                  $file = 'modules/griffo/Group/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('GroupModel');
              spl_autoload_register('GroupController');

?>
