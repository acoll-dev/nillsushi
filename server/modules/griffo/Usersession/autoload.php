<?php

          function UsersessionModel($class_name)
          {
              $file = 'modules/griffo/Usersession/model/' . $class_name . '.php';

              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function UsersessionBaseModel($class_name)
          {
              $file = 'modules/griffo/Usersession/model/' . $class_name . 'Base.php';

              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function UsersessionController($class_name)
          {
              $file = 'modules/griffo/Usersession/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('UsersessionModel');
          spl_autoload_register('UsersessionBaseModel');
          spl_autoload_register('UsersessionController');
?>
