<?php

class ProfileBase extends ActiveRecord\Model {

  public static $columns = array('idprofile', 'name','label');
  public static $table_name = 'gr_profile';
  public static $table_vanity_name = 'gr_profile';
  public static $primary_key = 'idprofile';

}
?>
