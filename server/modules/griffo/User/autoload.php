<?php

          function UserModel($class_name)
          {
              $file = 'modules/griffo/User/model/' . $class_name . '.php';

              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function UserBaseModel($class_name)
          {
              $file = 'modules/griffo/User/model/' . $class_name . 'Base.php';

              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function UserController($class_name)
          {
              $file = 'modules/griffo/User/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('UserModel');
          spl_autoload_register('UserBaseModel');
          spl_autoload_register('UserController');
?>
