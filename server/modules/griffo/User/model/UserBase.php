<?php

class UserBase extends ActiveRecord\Model {

  public static $columns = array('iduser', 'name', 'nickname','login', 'password', 'datecad', 'email', 'picture', 'menuorder','theme','status', 'fkidprofile','fkidlanguage','fkidlayer','fkidtheme','fkiduserauth');
  public static $table_name = 'gr_user';
  public static $table_vanity_name = 'gr_user';
  public static $primary_key = 'iduser';
}
?>
