<?php

class UserauthapplicationBase extends ActiveRecord\Model {

  public static $columns = array('iduserauthapplication','iduserauth', 'idapplicationauth', 'status');
  public static $table_name = 'gr_userauthapplication';
  public static $table_vanity_name = 'gr_userauthapplication';
  public static $primary_key = 'iduserauthapplication';

}
?>
