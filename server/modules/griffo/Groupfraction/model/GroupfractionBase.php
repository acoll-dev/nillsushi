<?php

class GroupfractionBase extends ActiveRecord\Model {

  public static $columns = array('idgroupfraction','fkidgroup','fkidfraction','image');
  public static $table_name = 'gr_groupfraction';
  public static $table_vanity_name = 'gr_groupfraction';
  public static $primary_key = 'idgroupfraction';

}
?>
