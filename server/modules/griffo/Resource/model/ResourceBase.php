<?php

class ResourceBase extends ActiveRecord\Model {

  public static $columns = array('idresource', 'resource','idresourceparent','fkidmodule');
  public static $table_name = 'gr_resource';
  public static $table_vanity_name = 'gr_resource';
  public static $primary_key = 'idresource';

}
?>
