<?php
              
              function GroupfractionModel($class_name)
              {
                  $file = 'modules/complementary/Groupfraction/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function GroupfractionController($class_name)
              {
                  $file = 'modules/complementary/Groupfraction/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('GroupfractionModel');
              spl_autoload_register('GroupfractionController');

?>
