<?php

class OrderBase extends ActiveRecord\Model {

  public static $columns = array('idorder', 'fetch', 'formpayment','change', 'deliveryfee', 'subtotal', 'total','complement', 'address','number','district','city','state','created', 'updated', 'status','fkidclient','fkidmodule','fkidshop');
  public static $table_name = 'gr_order';
  public static $table_vanity_name = 'gr_order';
  public static $primary_key = 'idorder';
  public static $tables_parents = array(0 => array('name' => 'gr_orderproduct','path' => 'complementary/Orderproduct/'));

}
?>
