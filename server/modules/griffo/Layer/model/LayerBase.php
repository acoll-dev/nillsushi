<?php

class LayerBase extends ActiveRecord\Model {

  public static $columns = array('idlayer', 'name', 'label', 'url','urllogin','filtermodel','thumbwidth','thumbheight','defaultsitemap','status','fkidlanguage','fkidtemplate');
  public static $table_name = 'gr_layer';
  public static $table_vanity_name = 'gr_layer';
  public static $primary_key = 'idlayer';

}
?>
