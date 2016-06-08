<?php

              function DashboardModel($class_name)
              {
                  $file = 'modules/griffo/Dashboard/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }
              
              function DashboardController($class_name)
              {
                  $file = 'modules/griffo/Dashboard/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('DashboardModel');
              spl_autoload_register('DashboardController');

?>
