<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title><?php printGalleryTitle(); ?></title>
	<link rel="stylesheet" href="<?= $_zp_themeroot ?>/zen.css" type="text/css" />
	<?php zenJavascript(); ?>
</head>

<body>
<div id="page">
<?php include("header.php") ?>



<div id="wrapper" class="clearfix">

<div id="content" class="widecolumn">
	<div id="post">
		<h2 class="title"><?php echo getGalleryTitle(); ?></h2>
	</div>
		
<div id="main">
    
    <div id="albums">
		<?php while (next_album()): ?>
		
		<div class="album">
			<div class="imagethumb">
				<a href="<?=getAlbumLinkURL();?>" title="View album: <?=getAlbumTitle();?>"><?php printAlbumThumbImage(getAlbumTitle()); ?></a>
			</div>
			<div class="albumdesc">
      			<? printAlbumDate("Date Taken: "); ?>
				<h2><a href="<?=getAlbumLinkURL();?>" title="View album: <?=getAlbumTitle();?>"><?php printAlbumTitle(); ?></a></h2>
				<p><?php printAlbumDesc(); ?></p>
			</div>
		</div>
		<?php endwhile; ?>
	</div>
	
</div>
<?php printPageListWithNav("&laquo; prev", "next &raquo;"); ?>
</div>
</div>


<hr />
</div>
</body>

</html>
