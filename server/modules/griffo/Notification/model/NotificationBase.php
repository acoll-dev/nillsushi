<?php

class NotificationBase extends ActiveRecord\Model {

  public static $columns = array('idnotification', 'text', 'read', 'status', 'fkiduser');
  public static $table_name = 'gr_notification';
  public static $table_vanity_name = 'gr_notification';
  public static $primary_key = 'idnotification';
}
?>
