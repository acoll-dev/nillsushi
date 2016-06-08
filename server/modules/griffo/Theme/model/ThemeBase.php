<?php

class ThemeBase extends ActiveRecord\Model {

  public static $columns = array('idtheme', 'name', 'path', 'fkidlayer');
  public static $table_name = 'gr_theme';
  public static $table_vanity_name = 'gr_theme';
  public static $primary_key = 'idtheme';
}
?>
