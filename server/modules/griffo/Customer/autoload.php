<?php

          function CustomerModel($class_name)
          {
              $file = 'modules/griffo/Customer/model/' . $class_name . '.php';

              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function CustomerBaseModel($class_name)
          {
              $file = 'modules/griffo/Customer/model/' . $class_name . 'Base.php';

              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          function CustomerController($class_name)
          {
              $file = 'modules/griffo/Customer/controller/' . $class_name . '.php';
              if (file_exists($file))
              {
                  require_once ( $file );
              }
          }

          spl_autoload_register('CustomerModel');
          spl_autoload_register('CustomerBaseModel');
          spl_autoload_register('CustomerController');
?>
