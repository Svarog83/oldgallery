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
	
	<div class="navbottom">
  	<?php printPageListWithNav("&laquo; prev", "next &raquo;"); ?>
  </div>
  
	<div id="secondary">

		<div class="module">
			<h2>Description</h2>
			<p>Describe your gallery here !</p>
		</div>

		<div class="module">
			<h2>Gallery data</h2>
			<table cellspacing="0" class="gallerydata">
			  <tr>
				<th><img src="<?= $_zp_themeroot ?>/img/photos.png" alt="Photo Galleries" /><a href="index.php?p=archive">Galleries</a></th>
				<td><? $albumNumber = getNumAlbums(); echo $albumNumber ?></td>
			  </tr>
			  <tr>
				<th><img src="<?= $_zp_themeroot ?>/img/photo.png" alt="Photos"/>Photos</th>
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

<p id="path"><?php echo getGalleryTitle(); ?></p>

<?php printFooter(); ?>
<?php printAdminToolbox(ZP_INDEX); ?>
</body>
</html>