<?php 
if (!defined('WEBPATH')) die(); 
$themeResult = getTheme($zenCSS, $themeColor, 'light');
$firstPageImages = normalizeColumns('2', '6');
setOption('images_per_page', 99999, false);
setOption('thumb_crop_width', 100, false);
setOption('thumb_crop_height', 75, false);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title><?php printGalleryTitle(); ?> | <?php echo getAlbumTitle();?></title>
	<link rel="stylesheet" href="<?php echo $zenCSS ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo $_zp_themeroot ?>/smooth/jd.gallery.css" type="text/css" media="screen" charset="utf-8" />
	<script src="<?php echo $_zp_themeroot ?>/smooth/mootools.v1.11.js" type="text/javascript"></script>
	<script src="<?php echo $_zp_themeroot ?>/smooth/jd.gallery.js" type="text/javascript"></script>
	<?php printRSSHeaderLink('Album',getAlbumTitle()); ?>
	<?php zenJavascript(); ?>
</head>

<body>
<script type="text/javascript">
	function startGallery() {
		var myGallery = new gallery($('smoothImages'), {
			timed: <?php (getOption('Slideshow')) ? print 'true' : print 'false'; ?>
		});
	}
	window.addEvent('domready',startGallery);
</script>
<?php printAdminToolbox(); ?>

<div id="main">

	<div id="gallerytitle">
		<h2><span><a href="<?php echo getGalleryIndexURL();?>" title="Albums Index"><?php echo getGalleryTitle();?></a> | <?php printParentBreadcrumb(); ?></span> <?php printAlbumTitle(true);?></h2>
	</div>
    
    <div id="padbox">
	
		<?php printAlbumDesc(true); ?>
	
  		<div id="albums">
			<?php while (next_album()): ?>
			<div class="album">
        
        		<div class="thumb">
					<a href="<?php echo getAlbumLinkURL();?>" title="View album: <?php echo getAlbumTitle();?>"><?php printAlbumThumbImage(getAlbumTitle()); ?></a>
        		</div>
				<div class="albumdesc">
					<h3><a href="<?php echo getAlbumLinkURL();?>" title="View album: <?php echo getAlbumTitle();?>"><?php printAlbumTitle(); ?></a></h3>
          			<small><?php printAlbumDate(""); ?></small>
					<p><?php printAlbumDesc(); ?></p>
				</div>
				<p style="clear: both; "></p>
			</div>
			<?php endwhile; ?>
		</div>
        
        <br clear="all" />
    

        <?php if (getNumImages() > 0) { ?>
        <div id="images">
            
            <div id="smoothImages">
            	<?php while (next_image(false, $firstPageImages)): ?>
				<div class="imageElement">
					<h3><?=getImageTitle();?></h3>
					<p><?=getImageDesc();?></p>
					<a href="<?php echo getImageLinkURL();?>" title="<?php echo getImageTitle();?>" class="open"></a>
                    <?php printCustomSizedImage(getImageTitle(), null, 540, null, null, null, null, null, 'full'); ?>
					<?php printImageThumb(getImageTitle(), 'thumbnail'); ?>
				</div>
                <?php endwhile; ?>
			</div>
			
		</div>
        <?php } ?>
	
		<?php printPageListWithNav("&laquo; prev", "next &raquo;"); ?>
        <?php printTags(true, 'Tags: '); ?>
        
	</div>
	
</div>

<div id="credit"><?php printRSSLink('Album', '', 'Album RSS', ''); ?> | <a href="<?php echo getGalleryIndexURL();?>?p=archive">Archive View</a> | Powered by <a href="http://www.zenphoto.org" title="A simpler web photo album">zenphoto</a></div>

</body>
</html>