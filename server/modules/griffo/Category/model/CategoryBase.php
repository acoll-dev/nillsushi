<?php

class CategoryBase extends ActiveRecord\Model {

  public static $columns = array('idcategory', 'name', 'url','idcategoryparent', 'sort','status', 'fkidmodule');
  public static $table_name = 'gr_category';
  public static $table_vanity_name = 'gr_category';
  public static $primary_key = 'idcategory';

}
?>
