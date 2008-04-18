<?php if (!defined('WEBPATH')) die(); ?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?php printGalleryTitle(); ?> > <?=getAlbumTitle();?> > <?=getImageTitle();?></title>
	<link rel="stylesheet" href="<?= $_zp_themeroot ?>/css/master.css" type="text/css" />
	<script type="text/javascript">var blogrelurl = "<?= $_zp_themeroot ?>/";</script>
	<script type="text/javascript" src="<?= $_zp_themeroot ?>/js/rememberMe.js"></script>
	<script type="text/javascript" src="<?= $_zp_themeroot ?>/js/comments.js"></script>
	<script type="text/javascript" src="<?= $_zp_themeroot ?>/js/fadein.js"></script>
	<script type="text/javascript" src="<?= $_zp_themeroot ?>/js/addEvent.js"></script>
    <script type="text/javascript" src="<?= $_zp_themeroot ?>/js/sweetTitles.js"></script>
	<?php require ('thinkdreams-functions.php'); ?>
	<?php printPreloadScript(); ?>
	<?php zenJavascript(); ?>
</head>

<body class="photosolo">

<div id="content">
	<?php $exif = getExif(); ?>
	<div id="desc">
		<h1><?php printImageTitle(true); ?></h1>
		<p><?php printImageDesc(true); ?></p>
		<div id="exifdata">
		<span>Exif Data</span>
		<ul>
			<?php 
				if($exif['model']) 						echo'<li><img src="'.$_zp_themeroot.'/img/camera.png" />'.$exif['model'].'</li>';
				if($exif['datetime']) 					echo'<li><img src="'.$_zp_themeroot.'/img/time.png" />'.$exif['datetime'].'</li>';
				if($exif['width'] && $exif['height']) 	echo'<li><img src="'.$_zp_themeroot.'/img/photo.png" />'.$exif['width'].' x '.$exif['height'].'</li>';
				if($exif['flash']) 						echo'<li><img src="'.$_zp_themeroot.'/img/lightning.png" />Flash: '.$exif['flash'].'</li>';
				if($exif['focal']) 						echo'<li><img src="'.$_zp_themeroot.'/img/eye.png" />f/'.$exif['focal'].'</li>';
				if($exif['iso']) 						echo'<li><img src="'.$_zp_themeroot.'/img/eye.png" />f/'.$exif['iso'].'</li>'; 
			?>
		</ul>
		</div>
	</div>
	
	

	<div id="main">
		<div id="photo_container"><a href="<?=getFullImageURL();?>" title="<?=getImageTitle();?>"><?php printCustomSizedImage(getImageTitle(), null, 400); ?></a></div>
		<div id="meta">
		    <ul>
		      <li class="count"></li>
		      <li class="date"></li>
		      <li class="tags"></li>
		    </ul>
		 </div>
		 
		<div id="commentblock">

				<?php $showhide = "<a href=\"#comments\" id=\"showcomments\"><img src=\"".$_zp_themeroot."/img/btn_show.gif\" width=\"35\" height=\"11\" alt=\"SHOW\" /></a> <a href=\"#content\" id=\"hidecomments\"><img src=\"".$_zp_themeroot."/img/btn_hide.gif\" width=\"35\" height=\"11\" alt=\"HIDE\" /></a>"; $num = getCommentCount(); if ($num == 0) echo "<h2>No comments yet</h2>"; if ($num == 1) echo "<h2>1 comment so far $showhide</h2>"; if ($num > 1) echo "<h2>$num comments so far $showhide</h2>"; ?>

				 <div <?php $num = getCommentCount(); if ($num > 0) echo "id=\"comments\""; ?>>
				<dl class="commentlist">
					<?php while (next_comment()):  ?>
					<dt>
					<a class="postno"> </a>
					<em>On <?=getCommentDate();?>, <?php printCommentAuthorLink(); ?> wrote:</em>
    				</dt>

    				<dd>
					<p><?=getCommentBody();?><?php printEditCommentLink('Edit', ' (', ')'); ?></p>
					</dd>
    				<?php endwhile; ?>
    			</dl>
<?php if (isset($error)) { ?><p><div class="error">There was an error submitting your comment. Name, a valid e-mail address, and a comment are required.</div></p><?php } ?>
    			<p class="mainbutton" id="addcommentbutton"><a href="#addcomment" class="btn"><img src="<?= $_zp_themeroot ?>/img/btn_add_a_comment.gif" alt="" /></a></p>

    			<div id="addcomment">
				<h2>Add a comment</h2>


						<!-- If comments are on for this image AND album... -->

						<form id="comments-form" action="#" method="post">
						<input type="hidden" name="comment" value="1" />
		          		<input type="hidden" name="remember" value="1" />

							<table border="0">

								<tr valign="top" align="left" id="row-name">
									<th><label for="name">name:</label></td>
									<td><input type="text" id="name" name="name" class="text" value="<?=$stored[0];?>" class="inputbox" />
									</td>
								</tr>
								<tr valign="top" align="left" id="row-email">
									<th><label for="email">email:</label></td>
									<td><input type="text" id="email" name="email" class="text" value="<?=$stored[1];?>" class="inputbox" /> <em>(not displayed)</em>
									</td>
								</tr>
								<tr valign="top" align="left">
									<th><label for="website">url:</label></td>
									<td><input type="text" id="website" name="website" class="text" value="<?=$stored[2];?>" class="inputbox" /></td>
								</tr>
								<tr valign="top" align="left">
									<th><label for="c-text">comment:</label></th>
									<td><textarea name="comment" rows="10" cols="40"></textarea></td>
								</tr>
								<tr valign="top" align="left">
								    <th class="buttons">&nbsp;</th>
    								<td class="buttons"><input type="submit" value="Add comment" class="pushbutton" id="btn-preview" /><p>Fill in "name", "email" and "comment".</p></td>
    								</tr>
							</table>
						</form>
			</div>
		</div>
	</div>
</div>

<?php if (hasPrevImage()) { ?>
<div id="prev" class="slides">  <p><a href="<?=getPrevImageURL();?>" title="Previous photo"><img src="<?=getPrevImageThumb(); ?>" /></a></p></div>
<?php } if (hasNextImage()) { ?>
<div id="next" class="slides">  <p><a href="<?=getNextImageURL();?>" title="Next photo"><img src="<?=getNextImageThumb(); ?>" /></a></p></div>
<?php } ?>

</div>

<p id="path"><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> >
			 <a href="<?=getAlbumLinkURL();?>" title="Gallery Index"><?=getAlbumTitle();?></a> >
		     <a style="color:white;><?php printImageTitle(false); ?></a></p>

<?php printFooter(); ?>
<?php printAdminToolbox(); ?>
</body>
</html>
