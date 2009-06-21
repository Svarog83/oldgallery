<?php if (!defined('WEBPATH')) die(); require_once (ZENFOLDER . '/plugins/print_album_menu.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:b="http://www.google.com/2005/gml/b" xmlns:data="http://www.google.com/2005/gml/data" xmlns:expr="http://www.google.com/2005/gml/expr">
<head>
<title><?php printGalleryTitle(); ?> | <?php echo getAlbumTitle();?> | <?php echo getImageTitle();?></title>
<link rel="stylesheet" href="<?php echo $_zp_themeroot ?>/css/zen.css" type="text/css" />
<link rel="stylesheet" href="<?php echo FULLWEBPATH . "/" . ZENFOLDER ?>/js/thickbox.css" type="text/css" />
<script src="<?php echo FULLWEBPATH . "/" . ZENFOLDER ?>/js/jquery-1.2.1.min.js" type="text/javascript"></script>
<script src="<?php echo FULLWEBPATH . "/" . ZENFOLDER ?>/js/thickbox.js" type="text/javascript"></script>
<?php printRSSHeaderLink('Gallery','Gallery RSS'); ?>
<?php zenJavascript(); ?>
</head>
<body class="sidebars">
<div id="navigation"></div>
<div id="wrapper">
  <div id="container">
    <div id="header">
      <div id="logo-floater">
        <div>
          <h1 class="title"><a href="<?php echo getGalleryIndexURL();?>" title="Gallery Index"><?php echo getGalleryTitle();?></a></h1>
        </div>
      </div>
    </div>
    <!-- header -->
    <div class="sidebar">
      <?php if (getOption('Allow_search')) {  printSearchForm(); } ?>
      <?php if (getOption('Allow_album_menu')) { ?>
      <h2>Album Menu</h2>
      <?php printAlbumMenu("list","count","album_menu","album_menu_active","album_menu_sub","album_menu_sub_active"); ?>
      <?php } ?>
    </div>
    <div id="center">
      <div id="squeeze">
        <div class="right-corner">
          <div class="left-corner">
            <!-- begin content -->
            <div class="main section" id="main">
              <h3 id="gallerytitle"><a href="<?php echo getGalleryIndexURL();?>" title="Gallery Index"><?php echo getGalleryTitle();?></a> &raquo; <?php printParentBreadcrumb("", " &raquo; ", " &raquo; "); ?><a href="<?php echo getAlbumLinkURL();?>" title="Album Thumbnails"><?php echo getAlbumTitle();?></a> &raquo; <?php printImageTitle(true); ?></h3>
              <div id="image_container"><?php printCustomSizedImage(getImageTitle(), null, 540); ?></div>
              <div id="image_extras">
              	<?php 
				$MAP = printImageMap();
				if (getImageEXIFData()) {
			      echo "<div id=\"exif_link\"><a href=\"#TB_inline?height=400&width=300&inlineId=imagemetadata\" title=\"image details from exif\" class=\"thickbox\">Image Info</a></div>";
			      printImageMetadata('', false); 
				}
				if (hasMapData()) {
			      echo "<div id=\"map_link\"><a href=\"#TB_inline?height=500&width=595&inlineId=map\" class=\"thickbox\">View Map</a></div>";
			      printImageMap(); 
				}
			  	?>
              </div>
              <p><?php printImageDesc(true); ?></p>
              <?php if (getOption('Allow_comments')) { ?>
               <?php $num = getCommentCount(); echo ($num == 0) ? "" : ("<a href=\"javascript: toggle(\"comments\")\"><h3>Comments ($num)</h3></a>"); ?>
        		<div id="comments">
					<?php while (next_comment()):  ?>
                    <div class="comment">
						<div class="commentmeta">
							<span class="commentauthor"><?php printCommentAuthorLink(); ?></span> says: 
						</div>
						<div class="commentbody">
							<?php echo getCommentBody();?>
						</div>
						<div class="commentdate">
							<?php echo getCommentDate();?>
							,
							<?php echo getCommentTime();?>
         					<?php printEditCommentLink('Edit', ' | ', ''); ?>
						</div>
                    </div>
					<?php endwhile; ?>
                    <?php if (OpenedForComments()) { ?>
					  <div class="imgcommentform">
						<!-- If comments are on for this image AND album... -->
						<h3>Add a comment:</h3>
						<form id="commentform" action="#" method="post">
                            <div><input type="hidden" name="comment" value="1" /><input type="hidden" name="remember" value="1" /><?php if (isset($error)) { ?><tr><td><div class="error">There was an error submitting your comment. Name, a valid e-mail address, and a comment are required.</div></td></tr><?php } ?>
							<table border="0">
								<tr>
									<td><label for="name">Name:</label></td>
									<td><input type="text" id="name" name="name" size="20" value="<?php echo $stored[0];?>" class="inputbox" /></td>
								</tr>
								<tr>
									<td><label for="email">E-Mail:</label></td>
									<td><input type="text" id="email" name="email" size="20" value="<?php echo $stored[1];?>" class="inputbox" /></td>
								</tr>
								<tr>
									<td><label for="website">Site:</label></td>
									<td><input type="text" id="website" name="website" size="40" value="<?php echo $stored[2];?>" class="inputbox" /></td>
								</tr>
            
							</table>
							<textarea name="comment" rows="6" cols="40"></textarea><br />
							<input type="submit" value="Add Comment" class="pushbutton" />
                            </div>
						</form>
					  </div>
                    <?php } ?>
				</div>
        		<?php } ?>
              <div id="footer">
  			  	<?php printRSSLink('Gallery','','RSS', ' | '); ?><a href="?p=archive">Archive View</a> | Powered By <a href="http://www.zenphoto.org" title="A simpler web photo album"><span class="zen">zen</span><span class="photo">photo</span></a>
              </div>
              <div style="clear: both;"></div>
            </div>
            <!-- end content -->
            <span class="clear"></span> </div>
        </div>
      </div>
    </div>
    <div class="sidebar">
      <div id="rightsidebar">
        <?php if (hasNextImage()) { ?>
			<div id="next" class="slides"><h2>Next &raquo;</h2><a href="<?php echo getNextImageURL();?>" title="Next photo"><img src="<?php echo getNextImageThumb(); ?>" /></a></div>
		<?php } if (hasPrevImage()) { ?>
			<div id="prev" class="slides"><h2>&laquo; Previous</h2><a href="<?php echo getPrevImageURL();?>" title="Previous photo"><img src="<?php echo getPrevImageThumb(); ?>" /></a></div>
		<?php } ?>
        <?php printTags(true, 'Tags: '); ?>
      </div>
    </div>
    <span class="clear"></span> </div>
  <!-- /container -->
</div>
<?php printAdminToolbox(); ?>
</body>
</html>
