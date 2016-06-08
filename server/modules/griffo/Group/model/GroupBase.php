<?php

class GroupBase extends ActiveRecord\Model {

  public static $columns = array('idgroup', 'name', 'default');
  public static $table_name = 'gr_group';
  public static $table_vanity_name = 'gr_group';
  public static $primary_key = 'idgroup';

}
?>
