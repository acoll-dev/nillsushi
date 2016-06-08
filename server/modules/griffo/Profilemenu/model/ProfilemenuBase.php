<?php

class ProfilemenuBase extends ActiveRecord\Model {

  public static $columns = array('idprofilemenu','idmenu', 'idprofile', 'status');
  public static $table_name = 'gr_profilemenu';
  public static $table_vanity_name = 'gr_profilemenu';
  public static $primary_key = 'idprofilemenu';

}
?>
