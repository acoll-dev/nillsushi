<?php

class TemplateBase extends ActiveRecord\Model {

  public static $columns = array('idtemplate', 'name', 'path');
  public static $table_name = 'gr_template';
  public static $table_vanity_name = 'gr_template';
  public static $primary_key = 'idtemplate';
}
?>
