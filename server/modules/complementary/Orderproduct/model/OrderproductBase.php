<?php

class OrderproductBase extends ActiveRecord\Model {

  public static $columns = array('idorderproduct','fkidorder','fkidproduct','quantity');
  public static $table_vanity_name = 'gr_orderproduct';
  public static $primary_key = 'idorderproduct';

}
?>
