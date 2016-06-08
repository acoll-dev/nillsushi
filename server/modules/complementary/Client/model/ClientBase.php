<?php

class ClientBase extends ActiveRecord\Model {

  public static $columns = array('idclient', 'name', 'email','address','number','complement','district','city','state','phone','mobilephone','preferredshop','created','updated','status','fkidmodule','fkidshop');
  public static $table_name = 'gr_client';
  public static $table_vanity_name = 'gr_client';
  public static $primary_key = 'idclient';

}
?>
