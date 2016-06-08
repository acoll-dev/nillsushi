<?php

class OptionalproductBase extends ActiveRecord\Model {

  public static $columns = array('idoptionalproduct','fkidoptional','fkidproduct');
  public static $table_name = 'gr_optionalproduct';
  public static $table_vanity_name = 'gr_optionalproduct';
  public static $primary_key = 'idoptionalproduct';

}
?>
