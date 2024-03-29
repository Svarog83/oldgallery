<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title><?php if (getOption('website_title') != '') { echo getOption('website_title') . '&#187; '; } ?><?php printGalleryTitle(); ?> &#187; <?php echo getAlbumTitle();?></title>
	<link rel="stylesheet" href="<?php echo  $_zp_themeroot ?>/css/master.css" type="text/css" />
    <?php printRSSHeaderLink('Album',getAlbumTitle()); zenJavascript(); ?>
	<?php zenJavascript(); setOption('thumb_crop_width', 80, false);setOption('thumb_crop_height', 85, false);?>
</head>

<body class="gallery">

<?php printAdminToolbox(); ?>

<div id="content">

	<div class="galleryinfo">
	<h1><?php printAlbumTitle(true);?></h1>
	<p>Location: <?php printAlbumPlace(); ?></p><br />
	<p><?php printAlbumDesc(true); ?></p><br />
	<p>Click on a thumbnail to view the photo.</p>

	</div>

	<ul class="slides">
	<?php while (next_image()): ?>
	<li><a href="<?php echo getImageLinkURL();?>" title="<?php echo getImageTitle();?>"><?php printImageThumb(getImageTitle()); ?></a></li>
	<?php endwhile; ?>
	</ul>

	<div class="galleryinfo">
	<?php $number = getNumImages(); if ($number > $conf['images_per_page']) printPageListWithNav("&laquo; Previous", "Next &raquo;"); ?>
	</div>

</div>

<p id="path"><?php if (getOption('website_url') != '') { ?> <a href="<?php echo getOption('website_url'); ?>" title="Back"><?php echo getOption('website_title'); ?></a> &#187; <?php } ?>
  <a href="<?php echo getGalleryIndexURL();?>" title="Gallery Index"><?php echo getGalleryTitle();?></a> &#187; <a href="<?php echo getAlbumLinkURL();?>" title="<?php echo getAlbumTitle();?> Gallery" class="active"><?php echo getAlbumTitle();?></a></p>
</body>
</html>
