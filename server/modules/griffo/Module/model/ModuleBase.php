<?php

class ModuleBase extends ActiveRecord\Model {

  public static $columns = array('idmodule', 'name', 'struct','path','searchable','status');
  public static $table_name = 'gr_module';
  public static $table_vanity_name = 'gr_module';
  public static $primary_key = 'idmodule';

}
?>
