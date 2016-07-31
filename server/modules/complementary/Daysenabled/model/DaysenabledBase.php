<?php

class DaysenabledBase extends ActiveRecord\Model {

  public static $columns = array('iddaysenabled','date','enabled');
  public static $table_name = 'gr_daysenabled';
  public static $table_vanity_name = 'gr_daysenabled';
  public static $primary_key = 'iddaysenabled';

}
?>
