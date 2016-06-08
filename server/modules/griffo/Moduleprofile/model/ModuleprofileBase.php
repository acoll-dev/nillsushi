<?php

class ModuleprofileBase extends ActiveRecord\Model {

  public static $columns = array('idmoduleprofile','idmodule', 'idprofile', 'visible','status');
  public static $table_name = 'gr_moduleprofile';
  public static $table_vanity_name = 'gr_moduleprofile';
  public static $primary_key = 'idmoduleprofile';

}
?>
