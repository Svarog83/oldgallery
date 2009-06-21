This theme is an implementation of Triswebs proof of concept
javascript image navigation with lo-res display while loading.

I've updated the theme only slightly: 
	better fit for larger images (800x600)
	minimize page height  (no footers, no RSS-icons etc)
	added some javascript actions to link the large divs in album view.

This is a brute force approach to a dynamic gallery, since I can't be arsed
to properly learn ajax right now. :) 

*080405 - ulfben
  *added full-size links if images are larger than the default settings.	  
*080404 - ulfben
  *keeping the end-node of the bread crumb navigation in sync, 
	to allow users some way to link correctly.
  *using thumbnails instead of generating custom low-rez images
  *honoring the default size setting
  *fixed: flipping works when allow_comments is off. 


*080403 - ulfben
  *added wrap-around for albums browsing
  *disabled exif, geo and tag-data (unused bloat)
  *disabled auto-fire. (unused, buggy and stupid)
  *somewhat useable in IE.

*071126 - ulfben
  *modified Triswebs original solution to also support EXIF-data
  *added support for comments (reading and posting) 
  *fixed initial current image bug. 


//Ulf Benjaminsson
ulf@ulfben.com

Note:
Flipping through images works very well for visitors (... providing they're on Firefox...),
but less so for administrative purposes. Do not modify the ajax fields (image description, 
image title, tags) after flipping to a new image. These are not kept in sync with the 
tricasa hack so you'll end up altering the data of the first image you visited in the album.

