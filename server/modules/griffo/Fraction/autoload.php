<?php

              function FractionModel($class_name)
              {
                  $file = 'modules/griffo/Fraction/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function FractionController($class_name)
              {
                  $file = 'modules/griffo/Fraction/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('FractionModel');
              spl_autoload_register('FractionController');

?>
