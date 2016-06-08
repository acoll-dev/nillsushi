<?php

class CustomerBase extends ActiveRecord\Model {

  public static $columns = array('idcustomer', 'logo', 'name', 'phone', 'email', 'address','map','social');
  public static $table_name = 'gr_customer';
  public static $table_vanity_name = 'gr_customer';
  public static $primary_key = 'idcustomer';

}
?>
