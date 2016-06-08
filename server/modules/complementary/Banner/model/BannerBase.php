<?php

class BannerBase extends ActiveRecord\Model {

  public static $columns = array('idbanner', 'title','link','width', 'height', 'description', 'keywords', 'picture', 'sort','registrationdate', 'status','fkidcategory','fkidmodule');
  public static $table_name = 'gr_banner';
  public static $table_vanity_name = 'gr_banner';
  public static $primary_key = 'idbanner';

}
?>
