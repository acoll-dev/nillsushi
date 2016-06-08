<?php

class UserauthBase extends ActiveRecord\Model {

  public static $columns = array('iduserauth', 'user','password');
  public static $table_name = 'gr_userauth';
  public static $table_vanity_name = 'gr_userauth';
  public static $primary_key = 'iduserauth';

}
?>
