<?php if (!defined('WEBPATH')) die(); normalizeColumns(3, 6); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/2002/REC-xhtml1-20020801/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<? if ( !$local_server ): ?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-4193855-1");
pageTracker._initData();
pageTracker._trackPageview();
</script>
<? endif; ?>
	<title><?php printGalleryTitle(); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" media="screen, projection" href="<?php echo $_zp_themeroot ?>/css/master.css" />
	<?php 
	printRSSHeaderLink('Gallery','Gallery RSS');
	zenJavascript(); 
	setOption('thumb_crop_width', 85, false);
	setOption('thumb_crop_height', 85, false);
	?>
</head>

<body class="index">
	<?php echo getGalleryTitle(); ?><?php if (getOption('Allow_search')) {  printSearchForm(''); } ?>

	<div id="content">
		<h1><?php echo getGalleryTitle(); ?></h1>
		<div class="galleries">
				<h2>Недавние обновления</h2>
				<ul>
					<?php $counter = 0; while (next_album( ) and $counter < 20): ?>
						<li class="gal">
							<h3><a href="<?php echo getAlbumLinkURL();?>" title="Посмотреть альбом: <?php echo getAlbumTitle();?>"><?php printAlbumTitle(); ?></a></h3>
							<a href="<?php echo getAlbumLinkURL();?>" title="Посмотреть альбом: <?php echo getAlbumTitle();?>" class="img"><?php printCustomAlbumThumbImage(getAlbumTitle(), null, 210, 59, 210, 59); ?></a>
							<p>
					<?php 
						$number = getNumsubalbums(); 
						if ($number > 0) { 
							if (!($number == 1)) {  $number .= " альбомов";} else {$number .=" альбом";}
								$counters = $number;
							} else {
								$counters = '';
							}
							$number = getNumImages();
							if ($number > 0) {	
								if (!empty($counters)) { $counters .= ",&nbsp;"; }					
							if ($number != 1) $number .= " фотографий"; else $number .=" фото"; 
							$counters .= $number;
						}
						if (!empty($counters)) {
							echo "<p><em>($counters)</em><br/>";
						}
						$text = getAlbumDesc(); 
						if(strlen($text) > 50) {
							$text = preg_replace("/[^ ]*$/", '', substr($text, 0, 50)) . "...";
						}
						echo $text; 
					?></p>
							<div class="date"><?php printAlbumDate(); ?></div>
					</li>
				<?php if ($counter == 2) {echo "</ul><ul>";}; $counter++; endwhile; ?>
				</ul>
				<p class="mainbutton"><a href="<?php echo getCustomPageURL('albumarchive'); ?>" class="btn"><img src="<?php echo $_zp_themeroot ?>/img/btn_gallery_archive.gif" width="118" height="21" alt="Архив Галереи" /></a></p>
		</div>

		<div id="secondary">
			<div class="module">
				<h2>Описание</h2>
				<p><?php echo getOption('Gallery_description'); ?></p>
			</div>
			<div class="module">
				<?php $selector = getOption('Mini_slide_selector'); ?>
				<h2><?php echo $selector; ?></h2>
				<ul id="randomlist">
					<?php 
						switch($selector) {
						case 'Новые фотографии':
							$images = getImageStatistic(6, "latest");
							foreach ($images as $image) {
								echo "<li><table><tr><td>\n";
								$imageURL = getURL($image); 
								echo '<a href="'.$imageURL.'" title="Посмотреть фотографию: ' . 
								$image->getTitle() . '"><img src="' . 
								$image->getCustomImage(null, 44.5, 33.5, null, null, null, null, true) . 
																		'" width="44.5" height="33.5" alt="' . $image->getTitle() . "\"/></a>\n"; 
								echo "</td></tr></table></li>\n";
							}
							break;
						case 'Случайные фотографии':
							for ($i=1; $i<=6; $i++) {
							echo "<li><table><tr><td>\n";
							$randomImage = getRandomImages();
							$randomImageURL = getURL($randomImage); 
							echo '<a href="' . $randomImageURL . '" title="Посмотреть фотографию: ' . $randomImage->getTitle() . '">' .
 								'<img src="' . $randomImage->getCustomImage(null, 44.5, 33.5, null, null, null, null, true) . 
								'" width="44.5" height="33.5" alt="'.$randomImage->getTitle().'"'; 
							echo "/></a></td></tr></table></li>\n";
							}
						break;
					}
					?>
				</ul>
			</div>
			<div class="module">
				<h2>Галерея</h2>
				<table cellspacing="0" class="gallerydata">
						<tr>
							<th><a href="<?php getCustomPageURL('albumarchive'); ?>">Галереи</a></th>
							<td><?php $albumNumber = getNumAlbums(); echo $albumNumber ?></td>
							<td></td>
						</tr>
						<tr>
							<th>Фотографии</th>
							<td><?php $photosArray = query_single_row("SELECT count(*) FROM ".prefix('images')); $photosNumber = array_shift($photosArray); echo $photosNumber ?></td>
							<td><?php printRSSLink('Gallery','','','',true,'i'); ?></td>
						</tr>
 					<?php if (getOption('Allow_comments')) { ?>
 						<tr>
							<th>Комментарии</th>
							<td><?php $commentsArray = query_single_row("SELECT count(*) FROM ".prefix('comments')." WHERE inmoderation = 0"); $commentsNumber = array_shift($commentsArray); echo $commentsNumber ?></td>
							<td><?php printRSSLink('Comments','','','',true,'i'); ?></td>
							</tr>
						<?php } ?>
				</table>
			</div>
		</div>
	</div>
	<p id="path"><?php printHomeLink('', ' > '); echo getGalleryTitle(); ?></p> 

	
		
	
	<div id="footer">
		<!--<hr />
		<p>
			<a href="http://stopdesign.com/templates/photos/">Photo Templates</a> from Stopdesign.
			Powered by <a href="http://www.zenphoto.org">ZenPhoto</a>.
		</p>-->
	
	</div>
	
	<? 	ShowAlbumMap( 0, 1 ) ?>

	<?php printAdminToolbox(ZP_INDEX); ?>

</body>

</html>
