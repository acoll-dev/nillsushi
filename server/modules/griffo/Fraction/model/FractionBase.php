<?php

class FractionBase extends ActiveRecord\Model {

  public static $columns = array('idfraction', 'name', 'formula','fkidmodule');
  public static $table_name = 'gr_fraction';
  public static $table_vanity_name = 'gr_fraction';
  public static $primary_key = 'idfraction';

}
?>
