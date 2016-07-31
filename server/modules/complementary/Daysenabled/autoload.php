<?php
              
              function DaysenabledModel($class_name)
              {
                  $file = 'modules/complementary/Daysenabled/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function DaysenabledController($class_name)
              {
                  $file = 'modules/complementary/Daysenabled/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('DaysenabledModel');
              spl_autoload_register('DaysenabledController');

?>
