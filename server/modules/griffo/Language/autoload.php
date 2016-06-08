<?php

              function LanguageModel($class_name)
              {
                  $file = 'modules/griffo/Language/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function LanguageBaseModel($class_name)
              {
                  $file = 'modules/griffo/Language/model/' . $class_name . 'Base.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function LanguageController($class_name)
              {
                  $file = 'modules/griffo/Language/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('LanguageModel');
              spl_autoload_register('LanguageBaseModel');
              spl_autoload_register('LanguageController');

?>
