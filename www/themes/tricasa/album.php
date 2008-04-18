<?php if (!defined('WEBPATH')) die(); $themeResult = getTheme($zenCSS, $themeColor, 'light'); $firstPageImages = normalizeColumns('2', '6');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title><?php printGalleryTitle(); ?> | <?php echo getAlbumTitle();?></title>
	<link rel="stylesheet" href="<?php echo $zenCSS ?>" type="text/css" />
	<?php printRSSHeaderLink('Album',getAlbumTitle()); ?>
	<?php zenJavascript(); ?>
</head>

<body>
<?php printAdminToolbox(); ?>

<div id="main">

	<div id="gallerytitle">
	<?php if (getOption('Allow_search')) {  printSearchForm(); } ?>
		<h2><span><a href="<?php echo getGalleryIndexURL();?>" title="Albums Index"><?php echo getGalleryTitle();?></a> | <?php printParentBreadcrumb(); ?></span> <?php printAlbumTitle(true);?></h2>
	</div>
    
    <div id="padbox">
	
		<?php printAlbumDesc(true); ?>
	
  		<div id="albums">
			<?php while (next_album()): 
			$url = getAlbumLinkURL();
			$title = getAlbumTitle(); ?>
			<div class="album" onclick="location.href='<?php echo $url;?>';" style="cursor:pointer;">        
        		<div class="thumb">
					<a href="<?php echo $url;?>" title="View album: <?php echo $title;?>"><?php printAlbumThumbImage($title); ?></a>
        		</div>
				<div class="albumdesc">
					<h3><a href="<?php echo $url;?>" title="View album: <?php echo $title;?>"><?php printAlbumTitle(); ?></a></h3>
          			<small><?php printAlbumDate(""); ?></small>
					<p><?php printAlbumDesc(); ?></p>
				</div>
				<p style="clear: both; "></p>
			</div>
			<?php endwhile; ?>
		</div>
    
    	<div id="images">
			<?php while (next_image(false, $firstPageImages)): ?>
			<div class="image">
				<div class="imagethumb"><a href="<?php echo getImageLinkURL();?>" title="<?php echo getImageTitle();?>"><?php printImageThumb(getImageTitle()); ?></a></div>
			</div>
			<?php endwhile; ?>
		</div>
	
		<?php if(getTotalPages(false) > 1){
			 printPageListWithNav("&laquo; prev", "next &raquo;");
		 } else {
			 echo '<p style="clear: both; "></p>';
		 }?>
        <?php printTags('links', 'Tags: ', 'taglist', ''); ?>
        
	</div>
	
</div>
</body>
</html>