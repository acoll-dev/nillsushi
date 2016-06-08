<?php

class PageBase extends ActiveRecord\Model {

  public static $columns = array('idpage', 'tile','description','metatag','url','idpageparent','filecss', 'filejs','fileview', 'picture', 'keywords','authenticate','status','fkidmodule');
  public static $table_name = 'gr_page';
  public static $table_vanity_name = 'gr_page';
  public static $primary_key = 'idpage';

}
?>
