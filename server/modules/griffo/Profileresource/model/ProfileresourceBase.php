<?php

class ProfileresourceBase extends ActiveRecord\Model {

  public static $columns = array('idprofileresource','idprofile', 'idresource', 'status');
  public static $table_name = 'gr_profileresource';
  public static $table_vanity_name = 'gr_profileresource';
  public static $primary_key = 'idprofileresource';

}
?>
