<?php

class ActivitylogBase extends ActiveRecord\Model {

  public static $columns = array('idactivitylog', 'date', 'activity', 'url', 'fkidusersession');
  public static $table_name = 'gr_activitylog';
  public static $table_vanity_name = 'gr_activitylog';
  public static $primary_key = 'idactivitylog';

}
?>
