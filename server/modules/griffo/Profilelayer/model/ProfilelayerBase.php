<?php

class ProfilelayerBase extends ActiveRecord\Model {

  public static $columns = array('idprofilelayer','idprofile', 'idlayer','status');
  public static $table_name = 'gr_profilelayer';
  public static $table_vanity_name = 'gr_profilelayer';
  public static $primary_key = 'idprofilelayer';

}
?>
