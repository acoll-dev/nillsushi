<?php

class LanguageBase extends ActiveRecord\Model {

  public static $columns = array('idlanguage', 'label', 'name');
  public static $table_name = 'gr_language';
  public static $table_vanity_name = 'gr_language';
  public static $primary_key = 'idlanguage';

}
?>
