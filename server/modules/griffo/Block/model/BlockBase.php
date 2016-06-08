<?php

class BlockBase extends ActiveRecord\Model {

  public static $columns = array('idblock', 'name', 'content','type', 'fkidpage');
  public static $table_name = 'gr_block';
  public static $table_vanity_name = 'gr_block';
  public static $primary_key = 'idblock';

}
?>
