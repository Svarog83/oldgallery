<?php if (!defined('WEBPATH')) die(); ?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <title><?php printGalleryTitle(); ?></title>
  <link rel="stylesheet" href="<?= $_zp_themeroot ?>/css/master.css" type="text/css" />
  <script type="text/javascript" src="<?= $_zp_themeroot ?>/js/addEvent.js"></script>
  <script type="text/javascript" src="<?= $_zp_themeroot ?>/js/sweetTitles.js"></script>
  <script type="text/javascript" src="<?= $_zp_themeroot ?>/js/reflection.js"></script>
  <?php require ('thinkdreams-functions.php'); ?>
  <?php zenJavascript(); ?>
</head>

<body class="index">

<div id="content">

	<h1><?php echo getGalleryTitle(); ?></h1>

	<div id="credits">
	
	Contributors<br>
	<p><a href="http://www.thinkdreams.com/blog">How did it begin?</a> I started the mods to the original StopDesign theme to match my blog theme, and Skwid asked me for a copy on the Zenphoto forums. He started making his own changes, and we've collaborated since to make it better. I have EXIF working, and added faded thumbnails to the album pages.</a>
	<p><img src="<?= $_zp_themeroot ?>/img/zenphoto1.gif"><a href="http://www.zenphoto.com">Zenphoto, what makes all the pictures possible.</a> Zenphoto is a excellent photo gallery application that works using php, MySQL, and some javascript/AJAX. It features a themeable interface and very rich capabilities.</p>
	<p><a href="http://www.lostocean.net/">Skwid, a fine gentleman, and purveyor of all things slideshow.</a> Skwid, has contributed a great deal to the look and feel of this theme. He has added a very cool slideshow from <a href="http://www.couloir.org/js_slideshow/">couloir</a>, with buttons added by him to make the slideshow automatically load and also a way to select normal thumbnail vs slideshow view. Plus he modified the theme to include the <a href="http://cow.neondragon.net/stuff/reflection/">reflection.js</a> library on the main page.</p>
	<p><a href="http://www.stopdesign.com">Stopdesign, aka Douglas Bowman, who created the original template.</a> Douglas Bowman created the template, the template was adapted for use with Zenphoto and is now packaged as of v1.0.3.</p>
	<p>Miscellaneous bits were added like the icons from <a href="http://www.famfamfam.com/">FamFamFam</a>, and Dustin Diaz's <a href="http://www.dustindiaz.com/sweet-titles">Sweet Titles.</a></p>
	My humble apologies if I forgot someone.<br>
	
	</div>
  
	<div id="secondary">

		<div class="module">
			<h2>Description</h2>
			<p>This section is a listing of all the contributions to the design of this page including code samples, etc.</p>
		</div>

		<div class="module">
			<h2>Gallery data</h2>
			<table cellspacing="0" class="gallerydata">
			  <tr>
				<th><img src="<?= $_zp_themeroot ?>/img/photos.png" alt="Photo Galleries" /><a href="index.php?p=archive">Galleries</a></th>
				<td><? $albumNumber = getNumAlbums(); echo $albumNumber ?></td>
			  </tr>
			  <tr>
				<th><img src="<?= $_zp_themeroot ?>/img/photo.png" alt="Photos" />Photos</th>
				<td><? $photosArray = query_single_row("SELECT count(*) FROM ".prefix('images')); $photosNumber = array_shift($photosArray); echo $photosNumber ?></td>
			  </tr>
			  <tr>
				<th><img src="<?= $_zp_themeroot ?>/img/comment.png" alt="Comments" />Comments</th>
				<td><? $commentsArray = query_single_row("SELECT count(*) FROM ".prefix('comments')." WHERE inmoderation = 0"); $commentsNumber = array_shift($commentsArray); echo $commentsNumber ?></td>
			  </tr>
			</table>
		</div>

		<div class="module">
			<h2>Random Image</h2>
			
			<?php 
			$randomImage = getRandomImage();
			$randomImageURL = getURL($randomImage);				
			echo '<a href="'.$randomImageURL.'" title="View image: '.$randomImage->getTitle().'"><img src="'.$randomImage->getCustomImage(null, 230, null, 210, 60, null, null).'" alt="'.$randomImage->getTitle().'" id="random" class="reflect" /></a>'; 
			?>
				
		</div>

	</div>


</div>

<p id="path"><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> > Credits</p>

<?php printFooter(); ?>
<?php printAdminToolbox(); ?>
</body>
</html>