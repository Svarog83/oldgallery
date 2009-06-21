<?php

require_once("exif.inc.php");

//Prints EXIF Information

function getExif() {
	$path = urldecode(getFullImageURL());
	$url = '..' . $path; // CHANGE THIS TO $url = '.' . $path; IF YOUR ZENPHOTO INSTALLATION IS AT THE BASE DIRECTORY
	$info = array();
	

	$er = new phpExifRW($url);
	$er->processFile();
    if ($er->ImageInfo[TAG_MAKE]){
            $info['model'] = $er->ImageInfo[TAG_MODEL];
    }        
    if ($er->ImageInfo["DateTime"]){
            $info['datetime'] = $er->ImageInfo[TAG_DATETIME_ORIGINAL];
    }				
    $info['width'] = $er->ImageInfo["Width"];
	$info['height'] = $er->ImageInfo["Height"];
	
    if ($er->ImageInfo[TAG_FLASH] >= 0){
            $info['flash'] = $er->ImageInfo[TAG_FLASH] ? "Yes" :"No";
    }				
    if ($er->ImageInfo[TAG_FNUMBER]){
            $info['focal'] = (double)$er->ImageInfo[TAG_FNUMBER][0];
    }			
    if ($er->ImageInfo[TAG_ISO_EQUIVALENT]){
            $info['iso'] = (int)$er->ImageInfo[TAG_ISO_EQUIVALENT];
    }
	return($info);
}

function getExifPHP() {
	$path = urldecode(getFullImageURL());
	$url = '.' . $path;

	$exif = exif_read_data($url, 0, true);
	foreach ($exif as $key => $section) {
		foreach ($section as $name => $val) {
	       echo "$key.$name: $val<br />\n";
		}
	}
}

// Get Random Images 
 
function getRandomImage() {
   $result = query_single_row('SELECT '.prefix("images").'.filename, '.prefix("images").'.title, '.prefix("albums").'.folder FROM '.prefix("images").' INNER JOIN '.prefix("albums").' ON '.prefix("images").'.albumid = '.prefix("albums").'.id ORDER BY RAND() LIMIT 1');
   $image = new Image(new Album(new Gallery(), $result['folder']), $result['filename']);
   return $image;
}

function getSlideshowLink() {
	if (getOption('mod_rewrite')) {
		return getAlbumLinkURL().'slideshow';
	} 
	else {
		return getAlbumLinkURL().'&view=slideshow';
	}
}

function printFooter() {
	echo '<div id="footer">
		<p>Powered by <a href="http://www.thinkdreams.com/">Thinkdreams</a>, <a href="http://www.lostocean.net/">Lostocean</a>, <a href="http://www.couloir.org/js_slideshow">Couloir</a>, <a href="http://www.dustindiaz.com/sweet-titles">DustinDiaz</a>, <a href="http://cow.neondragon.net/stuff/reflection/">Cow</a>, <a href="http://www.famfamfam.com/lab/icons/silk/">FamFamFam</a> flavored <a href="http://www.zenphoto.org">ZenPhoto</a> ';
	printVersion();
	echo'</p><a href="'.getGalleryIndexURL().'index.php?p=credit">What made me what I am today?</a>
	<br />
	</div>';
}

?>