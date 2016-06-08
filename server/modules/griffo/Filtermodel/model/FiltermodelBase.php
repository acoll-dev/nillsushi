<?php

class FiltermodelBase extends ActiveRecord\Model {

  public static $columns = array('idfiltermodel', 'filter');
  public static $table_name = 'gr_filtermodel';
  public static $table_vanity_name = 'gr_activitylog';
  public static $primary_key = 'idfiltermodel';

}
?>
