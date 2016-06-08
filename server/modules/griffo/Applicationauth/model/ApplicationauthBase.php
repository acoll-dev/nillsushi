<?php

class ApplicationauthBase extends ActiveRecord\Model {

  public static $columns = array('idapplicationauth', 'id', 'key', 'name');
  public static $table_name = 'gr_applicationauth';
  public static $table_vanity_name = 'gr_applicationauth';
  public static $primary_key = 'idapplicationauth';

}
?>
