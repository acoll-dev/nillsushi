<?php

class OptionalBase extends ActiveRecord\Model {

  public static $columns = array('idoptional', 'name', 'value','info', 'image', 'divisible', 'list','type', 'status','fkidmodule');
  public static $table_name = 'gr_optional';
  public static $table_vanity_name = 'gr_optional';
  public static $primary_key = 'idoptional';
  public static $tables_parents = array(0 => array('name' => 'gr_optionalproduct','path' => 'griffo/Optionalproduct/'));

}
?>
