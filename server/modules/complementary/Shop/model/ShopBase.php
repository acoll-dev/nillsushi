<?php

class ShopBase extends ActiveRecord\Model {

  public static $columns = array('idshop', 'name', 'address','number', 'complement', 'district', 'city','state', 'phone1','phone2','phone3','status','fkidmodule');
  public static $table_name = 'gr_shop';
  public static $table_vanity_name = 'gr_shop';
  public static $primary_key = 'idshop';

}
?>
