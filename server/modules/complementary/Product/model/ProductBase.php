<?php

class ProductBase extends ActiveRecord\Model {

  public static $columns = array('idproduct', 'name', 'url','code','shortdescription', 'description', 'brand', 'manufacturer','supplier','unit', 'packaging', 'weight', 'stock', 'discount', 'unitvalue', 'amount', 'commission', 'observation', 'keywords','picture','pictures','videos','links','highlight','sort','registrationdate', 'status','fkidcategory','fkidmodule');
  public static $table_name = 'gr_product';
  public static $table_vanity_name = 'gr_product';
  public static $primary_key = 'idproduct';

}
?>
