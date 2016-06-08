<?php

class LayermoduleBase extends ActiveRecord\Model {

  public static $columns = array('idlayermodule','idlayer','idmodule','filtermodel','url','custom','urlcategory','default','status');
  public static $table_name = 'gr_layermodule';
  public static $table_vanity_name = 'gr_layermodule';
  public static $primary_key = 'idlayermodule';

}
?>
