<?php

class MenuBase extends ActiveRecord\Model {

  public static $columns = array('idmenu', 'label', 'icone', 'idmenuparent', 'path', 'keywords','status','fkidmodule','fkidlayer');
  public static $table_name = 'gr_menu';
  public static $table_vanity_name = 'gr_menu';
  public static $primary_key = 'idmenu';

}
?>
