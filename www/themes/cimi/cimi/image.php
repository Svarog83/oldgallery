<?php if (!defined('WEBPATH')) die(); ?>

<?php include ('theme-functions.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php printGalleryTitle(); ?> | <?=getAlbumTitle();?> | <?=getImageTitle();?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="imagetoolbar" content="false" />
	<link rel="stylesheet" href="<?= $_zp_themeroot ?>/zen.css" type="text/css" />
	<script type="text/javascript">
	<!--
	var ua = navigator.userAgent;
	var opera = /opera [56789]|opera\/[56789]/i.test(ua);
	var ie = !opera && /msie [56789]/i.test(ua);
	var moz = !opera && /mozilla\/[56789]/i.test(ua);
	function toggle(obj) {document.getElementById(obj).style.display=(document.getElementById(obj).style.display!="block")? "block" : "none";} 
	  <?php if (hasNextImage()) { ?>var nextURL="<?=getNextImageURL();?>";<?php } ?>
	  <?php if (hasPrevImage()) { ?>var prevURL="<?=getPrevImageURL();?>";<?php } ?>
	 function keyDown(e){
		if (!ie) {var keyCode=e.which;}
		if (ie) {var keyCode=event.keyCode;}
		if(keyCode==39){<?php if (hasNextImage()) { ?>window.location=nextURL<?php } ?>};
		if(keyCode==37){<?php if (hasPrevImage()) { ?>window.location=prevURL<?php } ?>};}
		document.onkeydown = keyDown;
		if (!ie)document.captureEvents(Event.KEYDOWN);
		document.oncontextmenu=new Function("return false");
		//document.onselectstart=new Function ("return false");
		document.ondragstart=new Function ("return false") ;
	function opacity(id, opacStart, opacEnd, millisec) {
		var speed = Math.round(millisec / 100);
		var timer = 0;
		if(opacStart > opacEnd) {
			for(i = opacStart; i >= opacEnd; i--) {
				setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
			timer++;
			}
		} else if(opacStart < opacEnd) {
			for(i = opacStart; i <= opacEnd; i++){
				setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
				timer++;
				}
			}
		}
	function changeOpac(opacity, id) {
		var object = document.getElementById(id).style;
			object.opacity = (opacity / 100);
			object.MozOpacity = (opacity / 100);
			object.KhtmlOpacity = (opacity / 100);
			object.filter = "alpha(opacity=" + opacity + ")";
		}
	-->
	</script>
	<?php	printPreloadScript(); ?>
	<?php zenJavascript(); ?>
</head>

<body>
<?php printAdminToolbox(); ?>
<div id="framework">
	<div id="main">

	<div id="gallerytitle">
		<h2><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a></h2>
	</div>
	
	<div id="breadcrumb">
		<div class="left"><a title="Home" href="<?=getGalleryIndexURL();?>">home</a> > <a href="<?=getAlbumLinkURL();?>"> <?php printAlbumTitle(false);?></a>&nbsp;> <?php printImageTitle(true); ?></div><div class="right dark">use arrow keys to navigate&nbsp;</div>
	</div>
	
	<div id="imgnav">
	<?php if (hasNextImage()) { ?>
		<a id="forw" href="<?=getNextImageURL();?>" title="Next Image"><span>Next Image</span></a>
	<?php } else { ?>
		<div class="block"><span>Next Image</span></div>
	<?php } ?>
	<?php if (hasPrevImage()) { ?>
		<a id="prev" href="<?=getPrevImageURL();?>" title="Previous Image"><span>Previous Image</span></a>
	<?php } else { ?>
		<div class="block"><span>Previous Image</span></div>
	<?php } ?>
		<div><a class="block light" href="<?=getFullImageURL();?>" title="<?=getImageTitle();?>">full size</a></div>
	</div>
	
	<div id="image">
		<a href="<?=getAlbumLinkURL();?>" title="<?=getImageTitle();?>"> <?php printDefaultSizedImageAlt(getImageTitle()); ?></a>
	</div>

	
	<div id="narrow">
	
	<?php if (getImageDesc() !='') { ?>
		<div id="desc"> 
			<?printImageDesc(true); ?>
		</div>
	<?}?>
	
	<!-- exif -->

		<div id="toggle"><?php $num = getCommentCount(); echo ($num == 0) ? "<em>No Comments</em>" : ("<em>Comments ($num)</em>"); ?>, <a href="#toggle" onclick="toggle('imgcommentform')">Add comment</a></div>
		<br />
		<? if (isset($error)) { ?>
			<div id="imgcommentform" style="display:block;">
			<div class="error"><p>There was an error submitting your comment. Name, a valid e-mail address, and a comment are required.</p></div> 
		<? } else { ?>
			<div id="imgcommentform" style="display:none;">
		<? }?>
			<!-- If comments are on for this image AND album... -->				
			<form id="commentform" action="#" method="post">
				<div><input type="hidden" name="comment" value="1" />
          		<input type="hidden" name="remember" value="1" />
						<p><label for="name">Name:</label><input type="text" id="name" name="name" size="20" value="<?=$stored[0];?>" /></p>
						<p><label for="email">E-Mail:</label><input type="text" id="email" name="email" size="20" value="<?=$stored[1];?>" /></p>
						<!-- <p><label for="website">Site:</label><input type="text" id="website" name="website" size="40" value="<?=$stored[2];?>" /></p> -->
						<p><textarea name="comment" rows="6"></textarea></p>
						<input type="submit" value="Add Comment" onmouseover="this.className = 'pushbuttonl'" onmouseout="this.className = 'pushbutton'" class="pushbutton" />
					</div>
				</form>
			</div>
		</div>
			
		<div id="comments">
		
			<?php while (next_comment()):  ?>
			<div class="comment">
				<div class="commentmeta">
					<span class="commentauthor"><?php printCommentAuthorEmail(); ?></span>
					<span class="commentdate"><?=getCommentDate();?>, <?=getCommentTime();?> <?php printEditCommentLink('Edit', ' | ', ''); ?></span>
				</div>
				<div class="commentbody">
					<?=getCommentBody();?>				
				</div>
			</div>
			<?php endwhile; ?>
			
		</div>
	
	</div>
	<div id="credit">Powered by <a href="http://www.zenphoto.org" title="A simpler web photo album">zenphoto</a> | theme by <a href="http://www.cimi.nl/">cimi</a></div>
</div>


	
</body>
</html>
