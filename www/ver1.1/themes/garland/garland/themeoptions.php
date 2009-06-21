<?php

require_once(SERVERPATH . "/" . ZENFOLDER . "/admin-functions.php");

class ThemeOptions {

  var $iSupport = array('Allow_comments' => array('type' => 1, 'desc' => 'Set to enable comment section.'),
  						  'Allow_search' => array('type' => 1, 'desc' => 'Set to enable search form.'),
						  'Allow_album_menu' => array('type' => 1, 'desc' => 'Set to enable album menu.'),
						  'Allow_cloud' => array('type' => 1, 'desc' => 'Set to enable tag cloud for album page.')
                          );
						
  function ThemeOptions() {
      setOptionDefault('Allow_comments', true);
	  setOptionDefault('Allow_search', true);
	  setOptionDefault('Allow_album_menu', true);
	  setOptionDefault('Allow_cloud', true);
    }
  
  function getOptionsSupported() {
    return $this->iSupport;
  }
}
?>