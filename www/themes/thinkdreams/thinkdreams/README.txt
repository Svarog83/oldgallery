==Thinkdreams 1.0 for ZenPhoto=

This theme was brought to you by Craig (Thinkdreams) and Ben (Skwid).
We hope you enjoy it ! 
Please keep the credits page on your installations, in order to fully aknowledge everyone whose work was used.

*Various notes:

Layout:
- The layout has been tested under Firefox 1.5.0.4, but should work on most recent browsers.

Slideshow:
- If you have mod_rewrite ON:
  + You will need to add the following line in your .htaccess (after the 4th RewriteRule is fine): 
    RewriteRule ^([^/]+)/slideshow/?$          index.php?album=$1&view=slideshow [R]
  + You will also need to do the following modification:
     "In template-functions.php, change line 47 to this:
    if (isset($zpitems[1]) && $zpitems[1] != 'slideshow')
    You're just replacing 'page' with 'slideshow' there."

- You can switch the loading image by replacing the file 'loading_animated2.gif' in the img/ folder (There is a second one already included)

Reflection:
- You can remove the reflections on the album thumbnails and on the random images by deleting all instances of: class="reflect"

Description:
- You can edit the description box on the index by changing the text in index.php in the description module.

EXIF:
- You can remove or edit the way exif fields are displayed by changing the code in image.php
- If your ZenPhoto installation is at the base directory (http://www.site.com), change line 9 in thinkdreams-functions.php to $url = '.' . $path;

*Todo:
- Link to individual images with comments in the slideshow
- Error pages


Please submit any bugs to the zenphoto support forums !