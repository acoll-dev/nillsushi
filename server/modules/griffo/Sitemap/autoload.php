<?php

              function SitemapModel($class_name)
              {
                  $file = 'modules/griffo/Sitemap/model/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function SitemapBaseModel($class_name)
              {
                  $file = 'modules/griffo/Sitemap/model/' . $class_name . 'Base.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              function SitemapController($class_name)
              {
                  $file = 'modules/griffo/Sitemap/controller/' . $class_name . '.php';
                  if (file_exists($file))
                  {
                      require_once ( $file );
                  }
              }

              spl_autoload_register('SitemapModel');
              spl_autoload_register('SitemapBaseModel');
              spl_autoload_register('SitemapController');

?>
