<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); ?>
<?php require_once ('joshuaink.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title><?php printGalleryTitle(); ?></title>
	<link rel="stylesheet" href="<?php echo  $_zp_themeroot ?>/css/gallery.css" type="text/css" />
	<script src="<?php echo WEBPATH . '/zen'; ?>/scriptaculous/prototype.js" type="text/javascript"></script>
	<script src="<?php echo WEBPATH . '/zen'; ?>/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<?php if (USE_AJAX) { ?>
	<script type="text/javascript">
		Effect.OpenUp = function(element) {
			element = $(element);
			new Effect.BlindDown(element, arguments[1] || {});
		}

		Effect.CloseDown = function(element) {
			element = $(element);
			new Effect.BlindUp(element, arguments[1] || {});
		}

		Effect.Combo = function(element) {
			element = $(element);

			if(element.style.display == 'none') {
				new Effect.OpenUp(element, arguments[1] || {});
			}else {
				new Effect.CloseDown(element, arguments[1] || {});
			}
		}
	</script>
<?php } ?>
	<?php printRSSHeaderLink('Gallery','Gallery RSS'); ?>
	<?php zenJavascript(); ?>
</head>
<body>
<div id="wrap">
	<div id="header">
		<?php printAlbumDesc(); ?>
	</div>

	<div id="gallerytitle">
	<h2><span>
		<a href="<?php echo getGalleryIndexURL();?>" title="Gallery Index"><?php echo getGalleryTitle();?></a> |
		<a href="<?php echo getAlbumLinkURL();?>" title="Gallery Index"><?php echo getAlbumTitle();?></a> |</span>
		<?php printImageTitle(true); ?></h2>
	</div>

	<div id="main-content">
		<div id="fullplate-photo">
			<h2><?php printImageDesc(true); ?></h2>
			<?php

				$mw = IMG_MAX_WIDTH;
				$mh = IMG_MAX_HEIGHT;
				$iw = getFullWidth();
				$ih = getFullHeight();

				if (isLandscape()) {
				   if ($iw >= $mw) {
				      list($nw, $nh) = getSizeCustomImage(null, $mw);
				      $iw = $nw;
				      $ih = null;

				      if ($nh > $mh) {
				      	list($nw, $nh) = getSizeCustomImage(null, null, $mh);
					      $iw = null;
					      $ih = $nh;
						}
					}
/*
						echo 'o: ' . getFullWidth() . 'x' . getFullHeight() . "<br />\n";
						echo 'i: ' . $iw . 'x' . $ih . "<br />\n";
						echo 'm: ' . $mw . 'x' . $mh . "<br />\n";
						echo 'n: ' . $nw . 'x' . $nh . "<br />\n";
*/
				} else {
				   if ($ih >= $mh) {
				      list($nw, $nh) = getSizeCustomImage(null, null, $mh);
				      $iw = null;
				      $ih = $nh;

				      if ($nw > $mw) {
				      	list($nw, $nh) = getSizeCustomImage(null, $mw);
					      $iw = $nw;
					      $ih = null;
						}
					}
/*
						echo 'o: ' . getFullWidth() . 'x' . getFullHeight() . "<br />\n";
						echo 'i: ' . $iw . 'x' . $ih . "<br />\n";
						echo 'm: ' . $mw . 'x' . $mh . "<br />\n";
						echo 'n: ' . $nw . 'x' . $nh . "<br />\n";
*/
				}
				list($imgw, $imgh) = getSizeCustomImage(null, $iw, $ih);
			?>
			<img src="<?php echo getCustomImageURL(null, $iw, $ih); ?>"
				alt="<?php echo getImageTitle(); ?>"
				width="<?php echo $imgw; ?>" height="<?php echo $imgh; ?>" /></a>
		</div>
	</div>

<?php
/*
	if (hasPrevImage()) { $link = getPrevImageURL(); } else { $link = "#"; }
   echo "\t\t" . '<li id="previous"><a href="' . $link . '" title="Previous Image">' .
		'<img src="' . $_zp_themeroot . '/images/arrow_left.png" width="16" ' .
		'height="16" alt="left arrow" /></a></li>' . "\n";
	echo "\t\t" . '<li><a href="' . getFullImageURL() . '" title="Full Size Image">' .
	   '<img src="' . $_zp_themeroot . '/images/arrow_out.png" width="16" ' .
		'height="16" alt="arrows pointing out" /></a></li>' . "\n";
	if (hasNextImage()) { $link = getNextImageURL(); } else { $link = "#"; }
   echo "\t\t" . '<li id="next"><a href="' . $link . '" title="Next Image">' .
		'<img src="' . $_zp_themeroot . '/images/arrow_right.png" width="16" ' .
		'height="16" alt="right arrow" /></a></li>' . "\n";
*/
?>

   <div id="fullplate-navigation">
      <ul>
   <?php
      if (hasPrevImage()) {
         echo "\t\t" . '<li id="previous"><a href="' . getPrevImageURL() .
            '" title="Previous Image"><img src="' . $_zp_themeroot .
            '/images/arrow_left.png" width="16" height="16" alt="left ' .
            'arrow" /></a></li>' . "\n";
      } else {
            echo "\t\t" . '<li>&nbsp;</li>' . "\n";
      }
      if (in_context(ZP_IMAGE)) {
         echo "\t\t" . '<li><a href="' . getFullImageURL() .
            '" title="Full Size Image"><img src="' . $_zp_themeroot .
            '/images/arrow_out.png" width="16" height="16" alt="arrows ' .
            'pointing out" /></a></li>' . "\n";
      } else {
            echo "\t\t" . '<li>&nbsp;</li>' . "\n";
      }
      if (hasNextImage()) {
         echo "\t\t" . '<li id="next"><a href="' . getNextImageURL() .
            '" title="Next Image"><img src="' . $_zp_themeroot .
            '/images/arrow_right.png" width="16" height="16" alt="right ' .
            'arrow" /></a></li>' . "\n";
      } else {
            echo "\t\t" . '<li>&nbsp;</li>' . "\n";
      }
         ?>
      </ul>
   </div>

	<div id="comments">
		<?php $num = getCommentCount(); ?>

		<div id="commentControl">
			<p><strong><?php echo $num; ?></strong> comments</p>
<?php if (USE_AJAX) {
		   echo ' . ';
			if ($num > 0) { ?>
				<p><a href="javascript:Effect.Combo('commentList', {duration: .5});">view comments</a></p> .
			<?php } ?>
			<p><a href="javascript:Effect.Combo('commentForm', {duration: .5});">add comment</a></p>
<?php } ?>
		</div>

		<div id="commentForm" <?php if (USE_AJAX) { ?>style="display:none"<?php } ?>>
		<!-- If comments are on for this image AND album... -->
			<form action="#comments" method="post">
				<input type="hidden" name="comment" value="1" />
				<input type="hidden" name="remember" value="1" />
				<?php if (isset($error)) { ?><table><tr><td><div class="error">There was an error submitting your comment. Name, a valid e-mail address, and a comment are required.</div></td></tr></table><?php } ?>

				<div class="row">
					<span class="label">Name:</span>
					<span class="field"><input type="text" name="name" size="25" value="<?php echo $stored[0];?>" /></span>
				</div>
				<div class="row">
					<span class="label">E-Mail:</span>
					<span class="field"><input type="text" name="email" size="25" value="<?php echo $stored[1];?>" /></span>
				</div>
				<div class="row">
					<span class="label">Site:</span>
					<span class="field"><input type="text" name="website" size="25" value="<?php echo $stored[2];?>" /></span>
				</div>
				<div class="row">
					<span class="label">Comment:</span>
					<span class="field"><textarea name="comment" cols="25" rows="5"></textarea></span>
				</div>
				<div class="spacer">&nbsp;</div>
				<input type="submit" value="Add Comment" />
			</form>
		</div>

		<div id="commentList" <?php if (USE_AJAX) { ?>style="display:none"<?php } ?>>
		<?php while (next_comment()):  ?>
			<div class="commentmeta">
				<span class="commentdate">
					On <?php echo getCommentDate();?>, <?php echo getCommentTime();?> PST
				</span>
				<span class="commentauthor"><?php printCommentAuthorLink(); ?> wrote:</span>
			</div>
			<div class="commentbody"><?php echo getCommentBody();?></div>
		<?php endwhile; ?>
		</div>
	</div>

	<div id="foot">
		<div id="logo">
			<a href="http://www.zenphoto.org"
				title="A simpler web photo album"><img
				src="http://www.zenphoto.org/images/zenphoto1.gif"
				alt="ZenPhoto logo"
				width="90" height="24"
				/></a>
		</div>

		<div id="info">
			<?php	echo "\t\t" . round((array_sum(explode(" ",microtime())) - $startTime),4).' seconds'; ?> |
			<a href="http://joshuaink.com/blog/206/css-photo-gallery-template"><?php echo ji_ver(); ?></a> |
			ZenPhoto <?php echo getOption('version') . "\n"; ?>
		</div>
	</div>

</div>
</div>

</body>
</html>
