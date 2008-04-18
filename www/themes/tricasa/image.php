<?php if (!defined('WEBPATH')) die(); $themeResult = getTheme($zenCSS, $themeColor, 'light'); $firstPageImages = normalizeColumns('2', '6');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>	
	<title><?php printGalleryTitle(); ?> | <?php echo getAlbumTitle();?> | <?php echo getImageTitle();?></title>
	<link rel="stylesheet" href="<?php echo $zenCSS ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo FULLWEBPATH . "/" . ZENFOLDER ?>/js/thickbox.css" type="text/css" />
	<script src="<?php echo FULLWEBPATH . "/" . ZENFOLDER ?>/js/jquery.js" type="text/javascript"></script>
	<script src="<?php echo FULLWEBPATH . "/" . ZENFOLDER ?>/js/thickbox.js" type="text/javascript"></script>
	<?php printRSSHeaderLink('Gallery','Gallery RSS'); ?>	
	<script type="text/javascript">	 
	/* Proof of concept javascript image navigation with lo-res display while loading. 
	  *080404 - ulfben
	  *added full-size links if images are larger than the default settings.
	  *080403 - ulfben
	  *added wrap-around for albums browsing
	  *disabled exif, geo and tag-data (unused bloat)
	  *disabled auto-fire. (unused, buggy and stupid)
	  *somewhat useable in IE.
	  *071126 - ulfben
	  *modified Triswebs original solution to also support EXIF-data
	  *added support for comments (reading and posting) 
	  *fixed initial current image.	  
	*/	
	var current = 0;
	var maxloadlevel = 3;    		
	var highpreloadbracket = 10; 
	var lowpreloadbracket = 4; 	
	var fgdisplay = null;
	var bgdisplay = null;
	var currentdisplaylevel = -1;	
	var images = new Array();
	var albumImages = new Array();
	var loadOrder = new Array();
	topz = 5; 	
<?php
 /*Set the context. This is needed in case the user has made a search. The context doesn't seem to get reset, and we would forever be left flipping 
between the images in the search result.*/
rem_context(ZP_SEARCH);
$loggedin = zp_loggedin(); //used to know if we want to link the image name in the breadcrumb, or if we should allow edits to it. 
$allowcomments = getOption("Allow_comments");
while (next_image(true)): ?>	
	var comments = "";
	<?php while (next_comment()): ?>
	comments += '<div class="comment"><div class="commentmeta"><span class="commentauthor"><?php printCommentAuthorLink(); ?></span> says:</div><div class="commentbody"><?php echo getCommentBody();?></div><div class="commentdate" id="commentdate"><?php echo getCommentDate();?>,<?php echo getCommentTime();?><?php printEditCommentLink('Edit', ' | ', ''); ?></div></div>';
	<?php endwhile; //array(id, lowsrc, src, w, h, title, description, comments /*,exif, tags*/,imgurl, needfullsizelink );	
	$imageID = getImageId();
	$lowRez = getImageThumb(); //making better use of the cache than generating lowrez versions.
	$highRez = getDefaultSizedImage();
	$size = getSizeDefaultImage();
	$fullSize = getSizeFullImage();
	$title = addslashes(getImageTitle());
	$desc = addslashes(getImageDesc());	
	$imgurl = addslashes(getImageLinkURL()); //for linking the breadcrumb navigation.
	if($fullSize[0] > $size[0] || $fullSize[1] > $size[1]){
		$fullSize = 1;
	} else { 
		$fullSize = 0;
	}
	/*not using exif-data. 
	global $_zp_exifvars; 
	$exifstring = "";
	if (false !== ($exif = getImageEXIFData())) {
		foreach ($exif as $field => $value) {
			$display = $_zp_exifvars[$field][3];
			if ($display) {
      			$label = $_zp_exifvars[$field][2];
      			$exifstring .= '<tr><td align="right">'.$label.':</td><td><strong>&nbsp;&nbsp;'.$value.'</strong></td></tr>';
    		}
  		}
	}
	//not using tags either.
	$tags = getTags();
	$label = "Tags: ";
	if(empty($tags)){$label = "";}
	$tagstring = '<strong>'.$label.'</strong><div id="imageTags" style="display: inline;">';
	$tagstring .= $tags;
	$tagstring .= "</div>";*/ ?>	
	images.push(new Array("<?php echo $imageID; ?>", "<?php echo $lowRez; ?>", "<?php echo $highRez; ?>", <?php echo $size[0] . ', ' . $size[1]; ?>, "<?php echo $title; ?>", "<?php echo $desc; ?>", comments /*'<?php /*echo $exifstring; */?>','<?php/* echo $tagstring;*/ ?>'*/,'<?php echo $imgurl;?>',<?php echo $fullSize;?>));
<?php endwhile; ?>    	    		
	function preLoadLores() {// Preload thumbnails: (onLoad).
		fgdisplay = $('#imagemain1');
		bgdisplay = $('#imagemain2'); 
		current = <?php global $_zp_current_image; echo $_zp_current_image->getIndex(); ?>;			
		albumImages[current] = createImage(current, 2);
		albumImages[current].onload = null;
		albumImages[current].src = images[current][2];
		var cont = document.getElementById('imagecontainer');
		cont.style.width  = images[current][3]+'px';
		cont.style.height = images[current][4]+'px';
		preloadAround(current);
		setFullSizeLink(current);
	}
	
	function getLevelSrc(index, level) {
		if (level > maxloadlevel) level = maxloadlevel;
		return images[index][level];
	}
	
	function loadImage(index, level) {
		var img = getImage(index);
		if (level > img.loadlevel) {
			img = createImage(index, level);
			img.src = getLevelSrc(index, level);
		}
		return img;
	}
    
	function setImage(index, img) {
		albumImages[index] = img;
	}
    
	function getImage(index) {
		if (albumImages[index] == null) {
			albumImages[index] = createImage(index, 0);
		}
		return albumImages[index];
	}

	function createImage(index, level) {
		var img = new Image();
		img.imageindex = index;
		img.loadlevel = level;
		img.imageid = images[index][0];
		img.onload = function() { handleImageLoad(this); }
		return img;
	}

	function handleImageLoad(img) {
		if (!img.src) return;
		var index = img.imageindex;
		if (img.loadlevel > getImage(index).loadlevel) {
			setImage(index, img);
		}
		if (current == index) {
			displayImage(current);
		}
	}

	function displayImage(index) {
		var img = getImage(index);
		setTitle(images[index][5]);
		setDesc(images[index][6]);		
		<?php if($allowcomments){
			echo 'setComments(images[index][7]);';
			echo 'setImageIdField(images[index][0]);';
		};?>
		setImageURL(images[index][8]);
		setFullSizeLink(index);
		
		/*setEXIFData(images[index][8]);*/ //not displaying EXIF.
		/*setTags(images[index][9]);*/ //not using tags.		
		$('#imagecontainer').width(getImageWidth(img)).height(getImageHeight(img));		
		if (img.loadlevel == 0) {
			$('#loading').css('opacity', 0).css({ zIndex: topz }).width(getImageWidth(img)).height(getImageHeight(img)).fadeIn('fast');        
		} else {
			$(bgdisplay).css({ zIndex : topz }).attr('src', img.src).width(getImageWidth(img)).height(getImageHeight(img)).show();
			$(fgdisplay).hide();
			var newfgdisplay = bgdisplay;
			bgdisplay = fgdisplay;
			fgdisplay = newfgdisplay;
		}		
		topz++;
	}

	function getImageWidth(img){ 
		return images[img.imageindex][3]; 
	}
	
	function getImageHeight(img){ 
		return images[img.imageindex][4]; 
	}

	function nextImage() {
		var next = current + 1;
		if (next >= images.length){next = 0;}  /*wrap around*/
		current = next;
		switchImage(current);      
	}

	function prevImage() {
		var prev = current - 1;
		if (prev < 0){prev = images.length-1;}   /*wrap around*/
		current = prev;
		switchImage(current);   
	}

	function switchImage(index) {
		currentdisplaylevel = -1;
		displayImage(index);
		loadImage(index, 1);
		loadImage(index, 2);
		preloadAround(index);
	}

	function preloadAround(index) {
		var i = 0;
		var img;
		for (i=index+1; i<images.length && i < index+lowpreloadbracket; i++) {
			img = loadImage(i, 1);
		}
		for (i=index+1; i < images.length && i < index+highpreloadbracket; i++) {
			img = loadImage(i, 2);
		}
	}

	function setDesc(desc) {
		var descDiv = document.getElementById('imageDesc');
		descDiv.innerHTML = desc;
	}

	function setTitle(title) {
		var titleDiv = document.getElementById('imageTitle');
		titleDiv.innerHTML = title;
	}
	
	function setFullSizeLink(index){
		var fsDiv = document.getElementById('fullSizeLink');
		if(fsDiv == null){return;}		
		if(images[index][9] == 1){ //if fullsize is larger than default display size																		
			fsDiv.setAttribute('href','/albums'+images[index][8]); //assuming images are in the album folder.	
			fsDiv.setAttribute('alt','View full size');
			fsDiv.setAttribute('title','View full size');
		} else{			
			fsDiv.removeAttribute('href');			
			fsDiv.removeAttribute('alt');
			fsDiv.removeAttribute('title');			
		}			
	}
	
	function setComments(comments){      	
		var commentDiv = document.getElementById('comments');      	
		commentDiv.innerHTML = comments;      	
	}

	function setImageIdField(imageid){
		var idDiv = document.getElementById('imageid');    	
		idDiv.value = imageid;   		
	}
	
	function setImageURL(url){
		var hrefDiv = document.getElementById('imgurl');
		<?php if(!$loggedin){ echo 'hrefDiv.href = url';};?>
	}

	/*function setEXIFData(exif){ //not using EXIF.
		var exifDataDiv = document.getElementById('imagemetadata_data');
		exifDataDiv.innerHTML = exif;
	}
	function setTags(tags){ //not using tags.
		var tagsDiv = document.getElementById('tagContainer');
		tagsDiv.innerHTML = tags;
	}*/

	function show(id) {
		document.getElementById(id).style.display = 'block';
	}

	function hide(id) {
		document.getElementById(id).style.display = 'none';
	}

	function toggleComments() {     
		var commentDiv = document.getElementById("comments");
		if (commentDiv.style.display == "block") {
			commentDiv.style.display = "none";
		} else {
			commentDiv.style.display = "block";
		}
	}
</script>
<?php zenJavascript(); ?>
</head>

<body onload="preLoadLores();">
<?php printAdminToolbox(); ?>
<div id="main">
	<div id="gallerytitle">	  			  	
		<div class="imgnav">
			<div id="imgprev" class="imgprevious"><a href="#" onmousedown="prevImage();" onmouseup="" title="Previous Image">&laquo; prev</a></div>      
	    	<div id="imgnext" class="imgnext"><a href="#" onmousedown="nextImage();" onmouseup="" title="Next Image">next &raquo;</a></div>      
	    </div>					  	
		<h2><span><a href="<?php echo getGalleryIndexURL();?>" title="Albums Index"><?php echo getGalleryTitle();?>
	    </a> | <?php printParentBreadcrumb("", " | ", " | "); ?><a href="<?php echo getAlbumLinkURL();?>" title="Album Thumbnails"><?php echo getAlbumTitle();?></a> | 
	    </span> <?php if(!$loggedin){echo '<a id="imgurl" href="'.getImageLinkURL().'" title="this page">';}; printImageTitle(true); if(!$loggedin){echo '</a>';};?>
	    </h2>    
	</div>
	
	<div id="image">
	 	<div id="imagecontainer" style="position: relative; width: <?php echo getDefaultWidth(); ?>px; height:<?php echo getDefaultHeight(); ?>px; ">
	 	<a href="<?php echo getFullImageURL(); ?>" id="fullSizeLink">
			<img id="imagemain1" style="position: absolute; top: 0px; left: 0px; z-index: 3;" src="<?php echo getDefaultSizedImage() ?>" />
			<img id="imagemain2" style="position: absolute; top: 0px; left: 0px; z-index: 2;" src="<?php echo getDefaultSizedImage() ?>" />
	    	<div id="loading"    style="position: absolute; top:<?php echo getDefaultHeight()/2; ?>px; left: <?php echo getDefaultWidth()/2-10; ?>px; z-index: 1;" ><img src="<?php echo $_zp_themeroot ?>/loading.gif" /></div>       
    	</a>
	    </div>			
  	</div>	  	
	<div id="narrow">
	<span class="desc"><?php printImageDesc(true); ?></span>
    <hr />
	<?php/*not using exif nor tags.
	if (getImageEXIFData()) {echo "<div id=\"exif_link\"><a href=\"#TB_inline?height=355&width=310&inlineId=imagemetadata\" title=\"Image Info\" class=\"thickbox\">Image Info</a></div>";
		printImageMetadata('', false); 	
	} 	
	printTags('links', 'Tags: ', 'taglist', ''); //not using tags.*/ ?>    		
		<?php if (getOption('Allow_comments')) { ?>
        <div id="comments" class="comments" >
			<?php $num = getCommentCount(); echo ($num == 0) ? "" : ("<h3>Comments ($num)</h3><hr />"); ?>
				<?php while (next_comment()):  ?>
				<div class="comment" id="comment">
					<div class="commentmeta">
						<span class="commentauthor"><?php printCommentAuthorLink(); ?></span> says: 
					</div>
					<div class="commentbody" id="commentbody">
						<?php echo getCommentBody();?>
					</div>
					<div class="commentdate" id="commentdate">
						<?php echo getCommentDate();?>
						,
						<?php echo getCommentTime();?>
	          			<?php printEditCommentLink('Edit', ' | ', ''); ?>
					</div>
				</div>
				<?php endwhile; ?>
			</div>
		<div id="comments2" class="comments" >
			<div class="imgcommentform">
				<!-- If comments are on for this image AND album... -->
				<h3>Add a comment:</h3>
				<form id="commentform" action="#" method="post">
				<div><input type="hidden" name="comment" value="1" />
          		<input type="hidden" name="remember" value="1" />
          		<input type="hidden" id="imageid" name="imageid" value="<?php echo getImageId(); ?>" />
                <?php printCommentErrors(); ?>
					<table border="0">
						<tr>
							<td><label for="name">Name:</label></td>
							<td><input type="text" id="name" name="name" size="15" value="<?php echo $stored[0];?>" class="inputbox" /></td>
						</tr>
						<tr>
							<td><label for="email">E-Mail:</label></td>
							<td><input type="text" id="email" name="email" size="15" value="<?php echo $stored[1];?>" class="inputbox" /></td>
						</tr>
						<tr>
							<td><label for="website">Site:</label></td>
							<td><input type="text" id="website" name="website" size="15" value="<?php echo $stored[2];?>" class="inputbox" /></td>
						</tr>            
					</table>
					<textarea id="commentarea" name="comment" rows="6" cols="40"></textarea>
					<br />
					<input type="submit" value="Add Comment" class="pushbutton" /></div>
				</form>
			</div>
		</div>
        <?php } ?>
	</div>
</div>

</body>
</html>
