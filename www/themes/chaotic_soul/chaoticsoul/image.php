<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title><?php printGalleryTitle(); ?> | <?=getAlbumTitle();?> | <?=getImageTitle();?></title>
	<link rel="stylesheet" href="<?= $_zp_themeroot ?>/zen.css" type="text/css" />


     <script src="/gallery/zen/scriptaculous/prototype.js" type="text/javascript"></script>
     <script src="/gallery/zen/scriptaculous/scriptaculous.js" type="text/javascript"></script> 
         
	<script type="text/javascript">
	
	function toggleComments() {
      	  var commentDiv = document.getElementById("comments");
      	  if (commentDiv.style.display == "block")
      	    commentDiv.style.display = "none";
      	  else
      	    commentDiv.style.display = "block";
	  }
	
	function toggleDiv(id) {
      	  var targetDiv = document.getElementById(id);
      	  if(targetDiv){
      	    targetDiv.style.display = (targetDiv.style.display == "block") ? "none" : "block";
      	  }
	}
	
	function commentPreview(field){
	
	  var previewDiv = document.getElementById("previewDiv");
	  var typedText = field.value;	  
	  
	  previewDiv.style.display = (typedText.length == 0) ? "none" : "block";
	  
	  var preview = "<h3>Comment Preview</h3>\n";
	  preview += typedText.split(/\n/).join("<br />");	  
	  previewDiv.innerHTML = preview;
	}
	
	function setImageBg(){
	  var image = document.getElementById("photo");	  
	  var imageDiv = document.getElementById("image");
	  if(image && imageDiv){
	    var className = (image.width > 500) ? "wideImage" : "tallImage";
	    imageDiv.className = className;
	  }	  
	}
	
	</script>
	
	<?php zenJavascript(); ?>

</head>

<body onload="setImageBg()">

<div id="page">
<?php include("header.php") ?>



<div id="wrapper" class="clearfix">


	<div id="content" class="widecolumn">
	<div id="post">
	</div>

		<h2 class="title"><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a>
		  | <a href="<?=getAlbumLinkURL();?>" title="Gallery Index"><?=getAlbumTitle();?></a>
		  | <?php printImageTitle(true); ?></h2>
	</div>
	
	<div class="imgnav">
		<?php if (hasPrevImage()) { ?>
		<a href="<?=getPrevImageURL();?>" title="Previous Image">&laquo; Prev</a>
		  
		<?php } if (hasPrevImage() && hasNextImage()) { ?>
		|
		
		<?php } if (hasNextImage()) { ?>
		<a href="<?=getNextImageURL();?>" title="Next Image">Next &raquo;</a>
		<?php } ?>
	</div>
	<div id="main">

	<div id="image">
		<a href="<?=getFullImageURL();?>" title="<?=getImageTitle();?>"> <?php printDefaultSizedImage(getImageTitle()); ?></a> 
	</div>
	
	<div id="narrow">
	
		<p class="imgdesc"><?php printImageDesc(true); ?></p>
		
		<div id="comments">
		<?php $currComment = 0; $num = getCommentCount(); echo ($num == 0) ? "" : ("<h2>Comments ($num)</h2>"); ?>
			<?php while (next_comment()): ++$currComment; ?>
			<div class="comment">
				<div class="commentmeta">
				        <span class="commentNumber"><? echo $currComment;?></span> 
					<h4><?php printCommentAuthorLink(); ?> says:</h4> 					
					<?=getCommentDate();?>, <?=getCommentTime();?><?php printEditCommentLink('Edit', ' | ', ''); ?>
				</div>
				<div class="commentbody">
					<?=getCommentBody();?> 					
				</div>
			</div>
			<?php endwhile; ?>
			
			
			
			<div class="imgcommentform">				
				
				<h3>Add a comment (<a href="javascript:toggleDiv('tags');" title="Toggle the allowed XHTML tags">Allowed Tags</a>)</h3>
				
				<div id="tags">				  
				  <?php $text = preg_replace("[<]" , " &lt;" , $conf['allowed_tags']); echo $text;?>
				</div>
				
				<form id="commentform" action="#" method="post">
				<div><input type="hidden" name="comment" value="1" />
          		<input type="hidden" name="remember" value="1" />
          <?php if (isset($FIXME)) { ?><tr><td><div class="error">There was an error submitting your comment. Name, a valid e-mail address, and a <acronym title="no potty mouth...">clean</acronym> comment are required.</div></td></tr><?php } ?>

					<table border="0">
						<tr>
							<td><label for="name">Name:</label></td>
							<td><input type="text" id="name" name="name" size="20" value="<?=$stored[0];?>" class="inputbox" /> <span class="required" title="Name is required">*</span>							 
							</td>
						</tr>
						<tr>
							<td><label for="email">E-Mail:</label></td>
							<td><input type="text" id="email" name="email" size="20" value="<?=$stored[1];?>" class="inputbox" /> <span class="required" title="E-Mail is required">*</span>
							</td>
						</tr>
						<tr>
							<td><label for="website">Site:</label></td>
							<td><input type="text" id="website" name="website" size="40" value="<?=$stored[2];?>" class="inputbox" /></td>
						</tr>
            
					</table>
					<textarea name="comment" rows="6" cols="40" onkeyup="commentPreview(this)"></textarea>					
					<br />
					<input type="submit" value="Add Comment" class="pushbutton" /> 
					<br /><br />
					
					<div id="previewDiv"></div>
					
					</div>
				</form>
			</div>			
		</div>
	</div>
	</div>
</div>

<hr />
</div>


</body>
</html>
