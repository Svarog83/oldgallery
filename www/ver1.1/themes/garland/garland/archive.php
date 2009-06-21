<?php if (!defined('WEBPATH')) die(); require_once (ZENFOLDER . '/plugins/print_album_menu.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:b="http://www.google.com/2005/gml/b" xmlns:data="http://www.google.com/2005/gml/data" xmlns:expr="http://www.google.com/2005/gml/expr">
<head>
<title><?php printGalleryTitle(); ?> | Archive View</title>
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
              <h3 id="gallerytitle"><a href="<?php echo getGalleryIndexURL();?>" title="Gallery Index"><?php echo getGalleryTitle();?></a> &raquo; Archive View</h3>
              <div id="image_container">
              	<div id="archive">
					<?php printAllDates(); ?>
                </div>
              </div>
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
        <h2>Popular Tags</h2>
        <?php printAllTagsAs('cloud', 'tags'); ?>
      </div>
    </div>
    <span class="clear"></span> </div>
  <!-- /container -->
</div>
<?php printAdminToolbox(); ?>
</body>
</html>
