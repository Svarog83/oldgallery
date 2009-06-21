<?php if (!defined('WEBPATH')) die(); $themeResult = getTheme($zenCSS, $themeColor, 'light'); $firstPageImages = normalizeColumns('2', '6');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title><?php printGalleryTitle(); ?></title>
	<link rel="stylesheet" href="<?php echo  $zenCSS ?>" type="text/css" />
	<?php printRSSHeaderLink('Gallery','Gallery RSS'); ?>
	<?php zenJavascript(); ?>
</head>

<body>
<?php printAdminToolbox(); ?>

<div id="main">
	<div id="gallerytitle">
    <?php if (getOption('Allow_search')) {  printSearchForm(); } ?>
		<h2><?php echo getGalleryTitle(); ?></h2>
	</div>    
    <div id="padbox">    
		<div id="albums">
			<?php while (next_album()): 
			$url = getAlbumLinkURL();
			$title = getAlbumTitle(); ?>
			<div class="album" onclick="location.href='<?php echo $url?>';" style="cursor:pointer;">
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
		<p style="clear: both; "></p>
		<?php /*printPageListWithNav("&laquo; prev", "next &raquo;");*/ ?>        
	</div>
</div>
<div id="credit"><small>
<p>Albums: <?php echo mysql_result(query("SELECT count(*) FROM " . prefix('albums')), 0); ?> | 
Images: <?php  echo array_shift(query_single_row("SELECT count(*) FROM ".prefix('images'))); ?> 
<?php if (getOption('Allow_comments')) { ?> 
| Comments: <?php echo array_shift(query_single_row("SELECT count(*) FROM ".prefix('comments')));  ?> 
<?php } ?>
</p> </small>
</body>
</html>
