<?php if (!defined('WEBPATH')) die(); ?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?php printGalleryTitle(); ?> > <?=getAlbumTitle();?></title>
	<link rel="stylesheet" href="<?= $_zp_themeroot ?>/css/master.css" type="text/css" />
	<script type="text/javascript">var themeroot = '<?= $_zp_themeroot ?>';</script>
	
	<script type="text/javascript" src="<?= $_zp_themeroot ?>/js/addEvent.js"></script>
    <script type="text/javascript" src="<?= $_zp_themeroot ?>/js/sweetTitles.js"></script>
	<?php require ('thinkdreams-functions.php'); ?>
	<?php zenJavascript(); ?>
	
	<script type="text/javascript" src="<?= $_zp_themeroot ?>/js/prototype.js"></script>
	<script type="text/javascript" src="<?= $_zp_themeroot ?>/js/scriptaculous.js"></script>
	<script type="text/javascript" src="<?= $_zp_themeroot ?>/js/behaviour.js"></script>
	<script type="text/javascript">

	// Define Time Interval
		var delay = 7000;
		
		// Define each photo's name, height, width, and caption
		var photoArray = new Array(
		// Source, Width, Height, Caption
		<?php while (next_image()): 
			$size = getSizeCustomImage(null, 400);?>
			new Array("<?=getCustomImageURL(null, 400);?>", "<?=$size[0];?>", "<?=$size[1];?>", "<?=getImageTitle();?>"),
		<?php endwhile; ?>
			new Array('','','','')
		);

	</script>
	
	<script type="text/javascript" src="<?= $_zp_themeroot ?>/js/slideshow.js"></script>
		
</head>

<body class="gallery">

<div id="content">

	<?php if ($_GET['view'] == 'slideshow') { ?>

		<div class="galleryinfo">
			<h1><?php printAlbumTitle(true);?></h1>
			<p class="desc"><?php printAlbumDesc(true); ?></p>
			<br />
			<p>View: <a href="<? echo getAlbumLinkURL(); ?>" title="Normal View">Normal</a> - Slideshow</p>
		</div>	
		
			<div id="OuterContainer">
				<div id="Container">
					<img id="Photo" src="img/c.gif" alt="Photo: Couloir" />
					<div id="LinkContainer">
					    <a href="#" id="PrevLink" title="Previous Photo"><span>Previous</span></a>
					    <a href="#" id="NextLink" title="Next Photo"><span>Next</span></a>
				    </div>
				    <div id="Loading"><img src="<?= $_zp_themeroot ?>/img/loading_animated2.gif" width="48" height="47" alt="Loading..." /></div>
				</div>
			</div>
			
			<div id="CaptionContainer">
			    <div id="Info"><p><span id="Counter">&nbsp;</span> <span id="Caption">&nbsp;</span></p></div>
				<div id="Controls"><a href="#" id="PlayLink" title="Play Slideshow"><span>Play</span></a> <a href="#" id="PauseLink" title="Pause Slideshow"><span>Pause</span></a></div>
			</div>
			
			<script type="text/javascript">
	 		// <![CDATA[
	 		Behaviour.register(myrules);
	 		// ]]>
	 		</script>	
	
	<?php }
	else {?>
	
		<div class="galleryinfo">
			<h1><?php printAlbumTitle(true);?></h1>
			<p class="desc"><?php printAlbumDesc(true); ?></p>
			<br />
			<p>View: Normal - <a href="<?=getSlideshowLink(); ?>" title="Slideshow View">Slideshow</a></p>
		</div>	
		
			<div class="galleries">
	  			<h2>Albums</h2>
	  			<ul>
	  				<?php while (next_album()): ?>
					<li>
		  				<a href="<?=getAlbumLinkURL();?>" title="View album: <?=getAlbumTitle();?>" class="img">
          				<?php printCustomAlbumThumbImage(getAlbumTitle(), null, 230, null, 210, 60, null, null, 'reflect', null); ?>
                        </a>
		  				<h3><a href="<?=getAlbumLinkURL();?>" title="View album: <?=getAlbumTitle();?>"><?php printAlbumTitle(); ?></a></h3>
		  				<p><em>(<? $number = getNumImages(); if ($number > 1) $number .= " photos"; else $number .=" photo"; echo$number; ?>)</em> <?php $text = getAlbumDesc(); if( strlen($text) > 50) $text = preg_replace("/[^ ]*$/", '', substr($text, 0, 50))."&#8230;"; echo$text; ?></p>
					</li>
	  				<?php endwhile; ?>
	  			</ul>
			</div>
            
            <ul class="slides">
			<?php while (next_image()): ?>
				<li><a href="<?=getImageLinkURL();?>" title="<?=getImageTitle();?>"><?php printImageThumb(getImageTitle()); ?></a></li>
			<?php endwhile; ?>
			</ul>

			<div class="galleryinfo">
				<?php $number = getNumImages(); if ($number > $conf['images_per_page']) printPageListWithNav("&laquo; prev", "next &raquo;"); ?>
			</div>
	
	<?php } ?>

	</div>

<p id="path"><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> > <?php printAlbumTitle(false);?></p>

<?php printFooter(); ?>
<?php printAdminToolbox(); ?>
</body>
</html>
