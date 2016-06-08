<?php

class ApplicationBase extends ActiveRecord\Model {

  public static $columns = array('idapplication', 'id', 'key', 'name');
  public static $table_name = 'gr_application';
  public static $table_vanity_name = 'gr_application';
  public static $primary_key = 'idapplication';

}
?>
