<?php

class FractionproductBase extends ActiveRecord\Model {

  public static $columns = array('idfractionproduct','fkidproduct','fkidfraction');
  public static $table_vanity_name = 'gr_fractionproduct';
  public static $primary_key = 'idfractionproduct';

}
?>
