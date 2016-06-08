<?php

class UsersessionBase extends ActiveRecord\Model {

  public static $columns = array('idusersession', 'ip', 'idsession', 'accesstoken', 'expirity','datetimein', 'datetimeout', 'fkiduserauth');
  public static $table_name = 'gr_usersession';
  public static $table_vanity_name = 'gr_usersession';
  public static $primary_key = 'idusersession';

}
?>
