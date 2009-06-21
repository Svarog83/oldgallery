<?php

require_once(SERVERPATH . "/" . ZENFOLDER . "/admin-functions.php");

class ThemeOptions {

  var $iSupport = array('Allow_comments' => array('type' => 1, 'desc' => 'Set to enable comment section.'),
  						'Allow_search' => array('type' => 1, 'desc' => 'Set to enable search form.'),
						'Slideshow' => array('type' => 1, 'desc' => 'Set to enable slideshow.'),
                        'Theme_colors' => array('type' => 2, 'desc' => 'Set the colors of the theme')
                        );
  
  function ThemeOptions() {
    setOptionDefault('Allow_comments', true);
	setOptionDefault('Allow_search', true);
	setOptionDefault('Slideshow', false);
    setOptionDefault('Theme_colors', 'dark'); 
  }
  
  function getOptionsSupported() {
    return $this->iSupport;
  }

  function handleOption($option, $currentValue) {
    if ($option == 'Theme_colors') {
      $gallery = new Gallery();
      $theme = $gallery->getCurrentTheme();
      $themeroot = SERVERPATH . "/themes/$theme/styles";
      echo '<select id="themeselect" name="' . $option . '"' . ">\n";
      generateListFromFiles($currentValue, $themeroot , '.css');
      echo "</select>\n";
    }
  }
}
?>
