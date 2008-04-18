/******************************************************************************
Version 2.0.3 (October 2 2006)

Author: Torstein Hønsi
Email: See www.vikjavev.no/megsjol

Licence:
Highslide JS is licensed under a Creative Commons Attribution-NonCommercial 2.5
License (http://creativecommons.org/licenses/by-nc/2.5/).

You are free:
    * to copy, distribute, display, and perform the work
    * to make derivative works

Under the following conditions:
    * Attribution. You must attribute the work in the manner  specified by  the
      author or licensor.
    * Noncommercial. You may not use this work for commercial purposes.

* For  any  reuse  or  distribution, you  must make clear to others the license
  terms of this work.
* Any  of  these  conditions  can  be  waived  if  you  get permission from the 
  copyright holder.

Your fair use and other rights are in no way affected by the above.

******************************************************************************/



var hs = {

// Apply your own settings here, or override them in the html file.  
graphicsDir : 'highslide/graphics/',
restoreCursor : "zoomout.cur", // necessary for preload
fullExpandIcon : 'fullexpand.gif',
expandSteps : 10, // number of steps in zoom. Each step lasts for duration/step milliseconds.
expandDuration : 250, // milliseconds
restoreSteps : 10,
restoreDuration : 250,
numberOfImagesToPreload : 5, // set to 0 for no preload
marginLeft : 10,
marginRight : 35, // leave room for scrollbars + outline
marginTop : 10,
marginBottom : 35, // leave room for scrollbars + outline
zIndexCounter : 1001, // adjust to other absolutely positioned elements
fullExpandDelay : 500, // delay of the 'full expand'-icon
fullExpandTitle : 'Expand to actual size',
restoreTitle : 'Click to restore thumbnail',
focusTitle : 'Click to bring to front',
loadingText : 'Loading...',
loadingTitle : 'Click to cancel',
showCredits : true, // you can set this to false if you want
creditsText : '',
creditsHref : '',
creditsTitle : 'Go to the Highslide JS homepage',

// These settings can also be overridden inline for each image
anchor : 'auto', // where the image expands from
spaceForCaption : 30, // leaves space below images with captions
outlineType : 'drop-shadow', // set null to disable outlines
wrapperClassName : null, // for enhanced css-control
// END OF YOUR SETTINGS


// declare internal properties
preloadTheseImages : new Array(),
continuePreloading: true,
expandedImagesCounter : 0,
expanders : new Array(),
mouseIsOverFullExpand : false,
isBusy : false,
container : null,
defaultRestoreCursor : null,
leftBeforeDrag : null,
topBeforeDrag : null,

// drag functionality
ie : (document.all && !window.opera),
nn6 : document.getElementById && !document.all,
hasFocused : false,
isDrag : false,
dragX : null,
dragY : null,
dragObj : null,

    

ieVersion : function () {
    arr = navigator.appVersion.split("MSIE");
    return parseFloat(arr[1]);
},

//--- Find client width and height
clientInfo : function ()	{
	var iebody = (document.compatMode && document.compatMode != "BackCompat") 
        ? document.documentElement : document.body;
    this.width = document.all ? iebody.clientWidth : self.innerWidth;
    this.height = document.all ? iebody.clientHeight : self.innerHeight;
	this.scrollLeft = document.all ? iebody.scrollLeft : pageXOffset;
	this.scrollTop = document.all ? iebody.scrollTop : pageYOffset;
} ,

//--- Finds the position of an element
position : function(el)	{ 
	var parent = el;
	this.x = parent.offsetLeft;
	this.y = parent.offsetTop;
	while (parent.offsetParent)	{
		parent = parent.offsetParent;
		this.x += parent.offsetLeft;
		this.y += parent.offsetTop;
	}
    
	return this;
}, 

//--- Expander object keeps track of all properties
expander : function() { },


//--- Do the thing
/* params can be one of: anchor, wrapperClassName, outlineType, spaceForCaption, thumbnailId */
expand : function(a, params) {
    //    if (hs.isBusy) return;
    hs.isBusy = true;
    try {
        
        hs.continuePreloading = false;
        hs.container = document.getElementById('highslide-container');
        
        if (params && params.thumbnailId) {
            var el = document.getElementById(params.thumbnailId);
        
        } else { // first img within anchor
            for (i = 0; i < a.childNodes.length; i++) {
				if (a.childNodes[i].tagName && a.childNodes[i].tagName == 'IMG') {
                    var el = a.childNodes[i];
                    break;
                }    		
            }
        }

		// cancel other instances
        for (i = 0; i < hs.expanders.length; i++) {
            if (hs.expanders[i] && hs.expanders[i].thumb != el && !hs.expanders[i].showFullImageStarted) {
                hs.cancelLoading(i);
            }
        }
		
        
        // check if already open
        for (i = 0; i < hs.expanders.length; i++) {
            if (hs.expanders[i] && hs.expanders[i].thumb == el) {
                hs.isBusy = false;
                return false;
            }
        }
        
        // initialize the new Expander object
        var key = hs.expandedImagesCounter++;
        hs.expanders[key] = new hs.expander();
        var exp = hs.expanders[key];
        exp.a = a;
		
		// override inline parameters
		var legalParams = new Array('anchor', 'outlineType', 'spaceForCaption', 'wrapperClassName');
		for (i = 0; i < legalParams.length; i++) {
			var name = legalParams[i];
			if (params && params[name]) exp[name] = params[name];
			else exp[name] = hs[name];
		}
		
        
        var imgId = 'expanded-'+ hs.expandedImagesCounter;
        exp.thumbsUserSetId = el.id;
        if (!el.id) el.id = 'hs-thumb-'+ hs.expandedImagesCounter;
        exp.thumb = el;
        
        exp.originalCursor = el.style.cursor;
        el.style.cursor = 'wait';

        var pos = hs.position(el); 
                
        // instanciate the wrapper
        var wrapper = document.createElement('div');
        wrapper.style.visibility = 'hidden';
        wrapper.style.position = 'absolute';
		if (exp.wrapperClassName) wrapper.className = exp.wrapperClassName;
        wrapper.style.zIndex = hs.zIndexCounter++;        
        exp.wrapper = wrapper;
        
        // store properties of the thumbnail
        exp.thumbWidth = el.width;
        exp.thumbHeight = el.height;
        exp.thumbLeft = pos.x;
        exp.thumbTop = pos.y;
        
        // thumb borders
        exp.thumbOffsetBorderW = (exp.thumb.offsetWidth - exp.thumbWidth) / 2;
        exp.thumbOffsetBorderH = (exp.thumb.offsetHeight - exp.thumbHeight) / 2;
		
		// loading symbol
		var loading = document.createElement('a');
		loading.style.position = 'absolute';
		loading.style.visibility = 'hidden';
		loading.className = 'highslide-loading';
        loading.title = hs.loadingTitle;
        loading.href = 'javascript:hs.cancelLoading('+ key +')'
		hs.container.appendChild(loading);
		if (hs.loadingText) loading.innerHTML = hs.loadingText;
        loading.style.left = (exp.thumbLeft + exp.thumbOffsetBorderW + (exp.thumbWidth - loading.offsetWidth) / 2) +'px';
		loading.style.top = (exp.thumbTop + (exp.thumbHeight - loading.offsetHeight) / 2) +'px';
		exp.loading = loading;
    	setTimeout("if (hs.expanders["+ key +"].loading) hs.expanders["+ key +"].loading.style.visibility = 'visible';", 100);
		
        // instanciate the full-size image
        var img = document.createElement('img');
        exp.fullImage = img;
        if (navigator.userAgent.indexOf("Safari") != -1) { // http://bugzilla.opendarwin.org/show_bug.cgi?id=3869
            img.onload = function () { 
                var safImg = new Image;
                safImg.src = this.src;
                exp.fullImage.width = safImg.width;
                exp.fullImage.height = safImg.height;
                hs.showFullImage(key); 
            }
        } else {
            img.onload = function () { hs.showFullImage(key); }
        }
        img.src = a.href;
        img.className = 'highslide-image '+ el.className;
        img.style.visibility = 'hidden' // prevent flickering in IE
        img.style.display = 'block';
		img.style.position = 'relative';
    	img.style.zIndex = 3;
		img.id = imgId;
        img.title = hs.restoreTitle;
        img.onmouseover = function () { setTimeout("hs.showFullExpand('"+ key +"')", hs.fullExpandDelay); }
        img.onmouseout = function () { setTimeout("hs.hideFullExpand('"+ key +"')", 50); }
        img.setAttribute('key', key); // used on drag

	
        return false; // important!
    
    } catch (e) {
        return true; // script failed: default href fires
    }
    
}, 

//--- Show the image after it has been preloaded
showFullImage : function(key) {
	
	try { 
	    var exp = hs.expanders[key];
	    
	    // prevent looping on certain Gecko engines:
	    if (exp.showFullImageStarted) return;
	    else exp.showFullImageStarted = true;
        
               
		// remove loading div
	    if (exp.loading) {
	        hs.container.removeChild(exp.loading);
	        exp.loading = null;
	    }    
	    
	    exp.thumb.style.cursor = exp.originalCursor;
	    
        var newWidth = exp.fullImage.width; // causes problems in Safari
	    var newHeight = exp.fullImage.height;
	    exp.fullExpandWidth = newWidth;
	    exp.fullExpandHeight = newHeight;
	    
	    exp.fullImage.width = exp.thumb.width;
	    exp.fullImage.height = exp.thumb.height;
	
	    // identify caption div
	    var modMarginBottom = hs.marginBottom;
	    var thumbsUserSetId = exp.thumbsUserSetId; // id has to be user set
	    if (thumbsUserSetId && document.getElementById('caption-for-'+ thumbsUserSetId)) {
	        var captionClone = document.getElementById('caption-for-'+ exp.thumb.id).cloneNode(true);
	        exp.caption = captionClone;
	        modMarginBottom += exp.spaceForCaption;
	    }
        
	    
	    exp.wrapper.appendChild(exp.fullImage)
	    exp.wrapper.style.left = exp.thumbLeft;
	    exp.wrapper.style.top = exp.thumbTop;
	    hs.container.appendChild(exp.wrapper);
		
		// correct for borders
	    exp.offsetBorderW = (exp.wrapper.offsetWidth - exp.thumb.width) / 2;
        exp.offsetBorderH = (exp.wrapper.offsetHeight - exp.thumb.height) / 2;
	    var modMarginRight = hs.marginRight + 2 * exp.offsetBorderW;
        modMarginBottom += 2 * exp.offsetBorderH;
	    
	    var ratio = newWidth / newHeight;
	    
	    // default (start) values
		var newLeft = parseInt(exp.thumbLeft) - exp.offsetBorderW + exp.thumbOffsetBorderW;
	    var newTop = parseInt(exp.thumbTop) - exp.offsetBorderH + exp.thumbOffsetBorderH;
	    var oldRight = newLeft + parseInt(exp.thumb.width);
	    var oldBottom = newTop + parseInt(exp.thumb.height);
	    
	    var justifyX;
	    var justifyY;
	    switch (exp.anchor) {
	    case 'auto':
	        justifyX = 'auto';
	        justifyY = 'auto';
	        break;
	    case 'top':
	        justifyX = 'auto';
	        break;
	    case 'top-right':
	        justifyX = 'right';
	        break;
	    case 'right':
	        justifyX = 'right';
	        justifyY = 'auto';
	        break;
	    case 'bottom-right':
	        justifyX = 'right';
	        justifyY = 'bottom';
	        break;
	    case 'bottom':
	        justifyX = 'auto';
	        justifyY = 'bottom';
	        break;
	    case 'bottom-left':
	        justifyY = 'bottom';
	        break;
	    case 'left':
	        justifyY = 'auto';
	        break;
	    case 'top-left':
	        break;
	    default:
	        justifyX = 'auto';
	        justifyY = 'auto';        
	    } 
	    
	    var client = new hs.clientInfo();
	    
	    
	    if (justifyX == 'auto') {
	        var hasMovedNewLeft = false;
	        // calculate newLeft
	        newLeft = Math.round(newLeft - ((newWidth - exp.thumb.width) / 2)); // as in center
	        if (newLeft < client.scrollLeft + hs.marginLeft) {
	            newLeft = client.scrollLeft + hs.marginLeft;
	            hasMovedNewLeft = true;
	        }
	        // calculate right/newWidth
	        if (newLeft + newWidth > client.scrollLeft + client.width - hs.marginLeft) {
	            if (hasMovedNewLeft) newWidth = client.width - hs.marginLeft - modMarginRight; // can't expand more
	            else if (newWidth < client.width - hs.marginLeft - modMarginRight) { // move newTop up
	                newLeft = client.scrollLeft + client.width - newWidth - hs.marginLeft - modMarginRight;
	            } else { // image larger than client
	                newLeft = client.scrollLeft + hs.marginLeft;
	                newWidth = client.width - hs.marginLeft - modMarginRight;
	            }
	        }
	    } else if (justifyX == 'right') {
	        newLeft = Math.floor(newLeft - newWidth + exp.thumb.width);
	    }
	    if (justifyY == 'auto') {
	        var hasMovedNewTop = false;
	        // calculate newTop
	        newTop = Math.round(newTop - ((newHeight - exp.thumb.height) / 2)); // as in center
	        if (newTop < client.scrollTop + hs.marginTop) {
	            newTop = client.scrollTop + hs.marginTop;
	            hasMovedNewTop = true;
	        }
	        // calculate bottom/newHeight
	        if (newTop + newHeight > client.scrollTop + client.height - hs.marginTop - modMarginBottom) {
	            if (hasMovedNewTop) newHeight = client.height - hs.marginTop - modMarginBottom; // can't expand more
	            else if (newHeight < client.height - hs.marginTop - modMarginBottom) { // move newTop up
	                newTop = client.scrollTop + client.height - newHeight - hs.marginTop - modMarginBottom;
	            } else { // image larger than client
	                newTop = client.scrollTop + hs.marginTop;
	                newHeight = client.height - hs.marginTop - modMarginBottom;
	            }
	        }
	    } else if (justifyY == 'bottom') {
	        newTop = Math.floor(newTop - newHeight + exp.thumb.height);
	    }
	    
	    // don't leave the page; better to expand right bottom
	    if (newLeft < hs.marginLeft) {
	        tmpLeft = newLeft;
	        newLeft = hs.marginLeft; 
	        newWidth = newWidth - (newLeft - tmpLeft);
	    }
	    if (newTop < hs.marginTop) {
	        tmpTop = newTop;
	        newTop = hs.marginTop;
	        newHeight = newHeight - (newTop - tmpTop);
	    }
	
	    // Correct ratio
	    if (newWidth / newHeight > ratio) { // width greater
	        var tmpWidth = newWidth;
	        newWidth = newHeight * ratio;
	        if (justifyX == 'center' || justifyX == 'auto') {
	            // recalculate newLeft
	            newLeft = Math.round(parseInt(exp.thumbLeft) 
	                - ((newWidth - exp.thumb.width) / 2));
	            if (newLeft < client.scrollLeft + hs.marginLeft) { // to the left
	                newLeft = client.scrollLeft + hs.marginLeft;
	            } else if (newLeft + newWidth > client.scrollLeft + client.width - modMarginRight) { // to the right
	                newLeft = client.scrollLeft + client.width - newWidth - modMarginRight;
	            }
	        }
	        if (justifyX == 'right') newLeft = newLeft + (tmpWidth - newWidth);
	    
	    } else if (newWidth / newHeight < ratio) { // height greater
	        var tmpHeight = newHeight;
	        newHeight = newWidth / ratio;
	        if (justifyY == 'center' || justifyY == 'auto') {
	            // recalculate newTop
	            newTop = Math.round(parseInt(exp.thumbTop) 
	                - ((newHeight - exp.thumb.height) / 2));
	            if (newTop < client.scrollTop + hs.marginTop) { // above
	                newTop = client.scrollTop + hs.marginTop;
	            } else if (newTop + newHeight > client.scrollTop + client.height - modMarginBottom) { // below
	                newTop = client.scrollTop + client.height - newHeight - modMarginBottom;
	            }
	        }
	        if (justifyY == 'bottom') newTop = newTop + (tmpHeight - newHeight);
	    }
	    
	    // Selectbox bug
        var imgPos = {x: newLeft - 20, y: newTop - 20, w: newWidth + 40, h: newHeight + 40 + exp.spaceForCaption}
        if (hs.ie) hs.showHideElements('SELECT', 'hidden', imgPos);
	    // Iframes bug
        hs.hideIframes = (window.opera || navigator.vendor == 'KDE' || (hs.ie && hs.ieVersion() < 5.5));
        if (hs.hideIframes) hs.showHideElements('IFRAME', 'hidden', imgPos);
        
	    // Apply size change
	    var width = exp.thumb.width;
	    var height = exp.thumb.height;
		var left = exp.thumbLeft + exp.thumbOffsetBorderW - exp.offsetBorderW;
	    var top = exp.thumbTop + exp.thumbOffsetBorderH - exp.offsetBorderH;
		intervalWidth = (newWidth - width) / hs.expandSteps;
		intervalHeight = (newHeight - height) / hs.expandSteps;
	    intervalLeft = (newLeft - left) / hs.expandSteps;
	    intervalTop = (newTop - top) / hs.expandSteps;
	    
	    for (i = 1; i < hs.expandSteps; i++) {
			width += intervalWidth;
			height += intervalHeight;
	        left += intervalLeft;
	        top += intervalTop;
	        if (justifyX == 'right') { // follow the edge nicely
	            width = Math.round(width);
	            left = oldRight - width;
	        }
	        if (justifyY == 'bottom') { 
	            height = Math.round(height);
	            top = oldBottom - height;
	        }
	        
			setTimeout("hs.changeSize("+ key +", "+ width +", "+ height +", "+ left +", "+ top +")", 
	            Math.round(i * (hs.expandDuration / hs.expandSteps)));
		}
	    
	    // setTimeout("hs.changeClassName("+ key +")", hs.expandDuration/2);    
		// Finally land on the right number:
		setTimeout("hs.changeSize("+ key +", "+ newWidth +", "+ newHeight +", "+ newLeft 
	        +", "+ newTop +")", hs.expandDuration);
		setTimeout("hs.focus("+ key +")", hs.expandDuration);
        if (hs.showCredits) setTimeout("hs.writeCredits("+ key +")", hs.expandDuration + 25);
	    if (exp.caption) {
	        setTimeout("hs.writeCaption("+ key +")", hs.expandDuration + 50);
	    } else {
			setTimeout( "hs.writeOutline("+ key +")", hs.expandDuration + 50);
	    }
		if (exp.fullExpandWidth > newWidth) {
	        setTimeout("hs.putFullExpand("+ key +")", hs.expandDuration + hs.fullExpandDelay);
	    }
	   
	} catch (e) {
	    if (hs.expanders[key] && hs.expanders[key].a) window.location.href = hs.expanders[key].a.href;
	}
}, 

cancelLoading : function(key) {
	var exp = hs.expanders[key];
	exp.thumb.style.cursor = exp.originalCursor;
	// remove loading div
    if (exp.loading) {
        hs.container.removeChild(exp.loading);
        exp.loading = null;
    }
        
    hs.expanders[key] = null;
},

// hide elements that show through image
showHideElements : function (tagName, visibility, imgPos) {
    var els = document.getElementsByTagName(tagName);
    if (els) {            
        for (i = 0; i < els.length; i++) {
            if (els[i].nodeName == tagName) {   
                if (visibility == 'visible') { // only on last
                    els[i].style.visibility = visibility;                
                } else {
                    var elPos = hs.position(els[i]);
                    elPos.w = els[i].offsetWidth;
                    elPos.h = els[i].offsetHeight;
                
                    var clearsX = (elPos.x + elPos.w < imgPos.x || elPos.x > imgPos.x + imgPos.w);
                    var clearsY = (elPos.y + elPos.h < imgPos.y || elPos.y > imgPos.y + imgPos.h);
                    if (!clearsX && !clearsY) { // element falls behind image
                        els[i].style.visibility = visibility;      
                    }
                }   
            }
        }
    }
},

writeCredits : function (key) {
    var exp = hs.expanders[key];
    var credits = document.createElement('a');
    credits.href = hs.creditsHref;
    credits.className = 'highslide-credits';
    credits.innerHTML = hs.creditsText;
    credits.title = hs.creditsTitle;
    credits.style.position = 'absolute';
    credits.style.zIndex = 3;
    
    if (hs.ie) { // strange bug sometimes makes values wrong in the first def.
        exp.offsetBorderW = (exp.wrapper.offsetWidth - exp.fullImage.width) / 2;
        exp.offsetBorderH = (exp.wrapper.offsetHeight - exp.fullImage.height) / 2;
	}  
    credits.style.top = exp.offsetBorderH +'px';
    credits.style.left = exp.offsetBorderW +'px';
    exp.credits = credits;
    exp.wrapper.appendChild(credits);

},

writeCaption : function(key) {
    var exp = hs.expanders[key];
    
    exp.wrapper.style.width = exp.wrapper.offsetWidth +'px';    
    exp.caption.style.visibility = 'hidden';
    exp.caption.style.position = 'relative';
    if (hs.ie) exp.caption.style.zoom = 1;  
    exp.caption.className += ' highslide-display-block'; // have to use className due to Opera
    exp.wrapper.appendChild(exp.caption);
    
    var capHeight = exp.caption.offsetHeight;
    var slideHeight = (capHeight < exp.fullImage.height) ? capHeight : exp.fullImage.height;
    exp.caption.style.marginTop = '-'+ slideHeight +'px';
    exp.caption.style.zIndex = 2;
    
    var step = 1;
    if (slideHeight > 400) step = 4;
    else if (slideHeight > 200) step = 2;
    else if (slideHeight > 100) step = 1;
    
    
    setTimeout("hs.expanders["+ key +"].caption.style.visibility = 'visible'", 10); // flickering in Gecko
	var t = 0;
    for (marginTop = - slideHeight; marginTop <= 0; marginTop += step, t += 10) {
        setTimeout ("if (hs.expanders["+ key +"] && hs.expanders["+ key +"].caption) "
            + "hs.expanders["+ key +"].caption.style.marginTop = '"+ marginTop +"px'", t);
    }
	
	setTimeout('hs.writeOutline('+ key +')', t + 10);
    
},

writeOutline : function(key) {
    if (!hs.expanders[key]) return;
    var exp = hs.expanders[key];    
    exp.outline = new Array();
    var v = hs.ieVersion();
    
    hs.hasAlphaImageLoader = (v >= 5.5) && (v < 7) && (document.body.filters);
	hs.hasPngSupport = v >= 7 || !document.body.filters;
    
    hs.preloadOutlineElement(key, 1); // start recursive process
}, 

preloadOutlineElement : function(key, i) {
    if (!hs.expanders[key]) return;
    if (!hs.hasAlphaImageLoader && !hs.hasPngSupport) return;
    
    var exp = hs.expanders[key];
    
    if (exp.outline[i] && exp.outline[i].onload) { // Gecko multiple onloads bug
        exp.outline[i].onload = null;
        return;
    }
    
    var src = hs.graphicsDir + "outlines/"+ exp.outlineType +"/"+ i +".png";
    
    if (hs.hasAlphaImageLoader) {
        
		exp.outline[i] = document.createElement('div');
	 	exp.outline[i].style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader("
	     	+ "enabled=true, sizingMethod=scale src='"+ src + "') ";
	} 
        
	var img = document.createElement('img'); // for onload trigger
	if (hs.hasPngSupport) {
        exp.outline[i] = img;
    }
    
    // common properties
	exp.outline[i].style.position = 'absolute';
	var dim = (i % 2 == 1) ? '10px' : '20px';
	exp.outline[i].style.height = dim;
	exp.outline[i].style.width = dim;
    
    if (i < 8) img.onload = function() { hs.preloadOutlineElement(key, i + 1); };                
    else img.onload = function() { hs.displayOutline(key); };
	img.src = src;
},

displayOutline : function(key) {
    if (!hs.expanders[key]) return; // already closed
    var exp = hs.expanders[key];
    
    hs.repositionOutline(key, 12);
	for (i = 1; i <= 8; i++) {
		exp.wrapper.appendChild(exp.outline[i]);
	}
	exp.hasOutline = true;
    
    // IE6
    exp.outline[1].style.lineHeight = '10px';
    exp.outline[5].style.lineHeight = '10px';
	
	for (i = 10, t = 0; i >= 0; i--, t += 50) {
	    setTimeout("hs.repositionOutline("+ key +", "+ i +");", t);   
	}   
},

repositionOutline : function(key, offset) {
	if (!hs.expanders[key]) return;
	var exp = hs.expanders[key];
    if (exp.isClosing) {
        hs.removeOutlines(key);
        return;
    }
    
	var w = exp.wrapper.offsetWidth;
	var h = exp.wrapper.offsetHeight;

	// top
	exp.outline[1].style.width = (w - (2 * offset) - 20) +'px';
    // strange khtml bug causes glitch in outline:
	if (navigator.vendor == 'KDE') exp.outline[1].style.height = ((offset % 2) + 10) +'px';
	exp.outline[1].style.left = (10 + offset) +'px';
	exp.outline[1].style.top = (-10 + offset) + 'px';
	
	// top-right
	exp.outline[2].style.left = (w - 10 - offset) +'px';
	exp.outline[2].style.top = (-10 + offset) + 'px';
	
	// right
	exp.outline[3].style.left = (w - offset) +'px';
	exp.outline[3].style.top = (10 + offset) +'px';
	exp.outline[3].style.height = (h - (2 * offset) - 20) +'px';
	
	// bottom-right
	exp.outline[4].style.left = (w - 10 - offset) +'px';
	exp.outline[4].style.top = (h - 10 - offset) +'px';
	
	// bottom
	exp.outline[5].style.width = (w - (2 * offset) - 20) +'px';
	if (navigator.vendor == 'KDE') exp.outline[5].style.height = ((offset % 2) + 10) +'px';
	exp.outline[5].style.left = (10 + offset) +'px';
	exp.outline[5].style.top = (h - offset) + 'px';
	
	// bottom-left
	exp.outline[6].style.left = (-10 + offset) +'px';
	exp.outline[6].style.top = (h - 10 - offset) +'px';
	
	// left
	exp.outline[7].style.left = (-10 + offset) +'px';
	exp.outline[7].style.top = (10 + offset) +'px';
	exp.outline[7].style.height = (h - (2 * offset) - 20) +'px';
	
	// top-left
	exp.outline[8].style.left = (-10 + offset) +'px';
	exp.outline[8].style.top = (-10 + offset) + 'px';
	
},
removeOutlines : function(key) {
    try {
        var exp = hs.expanders[key];
	    for (i = 1; i <= 8; i++) {
		    if (exp.hasOutline && exp.outline[i]) {
	            exp.wrapper.removeChild(exp.outline[i]);
	        }
	    }
    } catch (e) {}
},
//--- Focus by click
focus : function(key) {
    var img = hs.expanders[key].fullImage;
    // image
    for (i = 0; i < hs.expanders.length; i++) {
        if (hs.expanders[i] && hs.expanders[i].fullImage.className == 'highslide-image' && i != key) {
            var blurKey = i;
            hs.expanders[i].fullImage.className += ' highslide-image-blur';
            hs.expanders[i].fullImage.title = hs.focusTitle;
            if (hs.expanders[i].caption) {
                hs.expanders[i].caption.className += ' highslide-caption-blur';
            }
        }
    }
    hs.expanders[key].wrapper.style.zIndex = hs.zIndexCounter++;
    img.className = 'highslide-image';
    if (hs.expanders[key].caption) {
        hs.expanders[key].caption.className = hs.expanders[key].caption.className.replace(' highslide-caption-blur', '');
    }
    
    img.title = hs.restoreTitle;
    
    hs.isBusy = false;
},

//--- Focus the topmost image after restore
focusTopmost : function() {
    var topZ = 0;
    var topmostKey = '';
    for (i = 0; i < hs.expanders.length; i++) {
        if (hs.expanders[i] && hs.expanders[i].fullImage.className.match('highslide-image-blur')) {
            if (hs.expanders[i].wrapper.style.zIndex && hs.expanders[i].wrapper.style.zIndex > topZ) {
                topZ = hs.expanders[i].wrapper.style.zIndex;
                topmostKey = i;
            }
        }
    }
    //alert (topmostKey);
    if (topmostKey != '') hs.focus(topmostKey);
    hs.isBusy = false;
}, 

//--- Interface for text links
closeId : function(elId) {
    for (i = 0; i < hs.expanders.length; i++) {
        if (hs.expanders[i] && hs.expanders[i].thumb.id == elId) {
            hs.restoreThumb(i);
            return;
        }
    }
},

//--- Click on large image to restore thumb size
restoreThumb : function(key) {
    if (hs.isBusy) return;
    hs.isBusy = true;
    try {
		var exp = hs.expanders[key];
        exp.isClosing = true;
    
        // remove full expand icon
        if (exp.fullExpand) {
            hs.expanders[key].fullExpand.parentNode.removeChild(exp.fullExpand);
            exp.fullExpand = null;
        }
        // remove caption div
        if (exp.caption) {
            exp.wrapper.removeChild(exp.caption);
            exp.caption = null;
        }
        // remove credits
        if (exp.credits) {
            exp.wrapper.removeChild(exp.credits);
            exp.credits = null;
        }
        hs.removeOutlines(key);
        
		hs.outlinePreloader = 0;
        exp.wrapper.style.width = null;
        
        var width = exp.fullImage.width;
        var height = exp.fullImage.height;
        var left = parseInt(exp.wrapper.style.left);
        var top = parseInt(exp.wrapper.style.top);
	    intervalWidth = (exp.thumbWidth - width) / hs.restoreSteps;
	    intervalHeight = (exp.thumbHeight - height) / hs.restoreSteps;
        intervalLeft = (exp.thumbLeft + exp.thumbOffsetBorderW - exp.offsetBorderW - left) / hs.restoreSteps;
        intervalTop = (exp.thumbTop + exp.thumbOffsetBorderH - exp.offsetBorderH - top) / hs.restoreSteps;
        
        var oldRight = Math.round(left + width);
        var oldBottom = Math.round(top + height);        
	    
        for (i = 1; i < hs.restoreSteps; i++) {
		    width += intervalWidth;
		    height += intervalHeight;
            left += intervalLeft;
            top += intervalTop;
            
		    setTimeout("hs.changeSize("+ key +", "+ width +", "+ height +", "+ left +", "+ top +")", 
                Math.round(i * (hs.restoreDuration / hs.restoreSteps)));
	    }
        setTimeout('hs.endRestore('+ key +')', hs.restoreDuration);
    
    } catch (e) {
        hs.expanders[key].thumb.style.visibility = 'visible';
        hs.expanders[key].wrapper.parentNode.removeChild(hs.expanders[key].wrapper);
    }
},

endRestore : function (key) {
    var exp = hs.expanders[key];
    exp.thumb.style.visibility = 'visible';
    exp.fullImage.style.visibility = 'hidden';
    
    if (hs.hideIframes || hs.ie) {
        var remaining = -1;
        for (i = 0; i < hs.expanders.length; i++) {
            if (hs.expanders[i]) remaining++;
        }
        if (remaining <= 0) {
            if (hs.ie) hs.showHideElements('SELECT', 'visible');
	        if (hs.hideIframes) hs.showHideElements('IFRAME', 'visible');           
        }
    }
    
    exp.wrapper.parentNode.removeChild(exp.wrapper);
    
    hs.expanders[key] = null;
    
    
    if (hs.toggleImagesExpandEl) { // see below
        hs.expand(hs.toggleImagesExpandEl, hs.toggleImagesParams);
        hs.toggleImagesExpandEl = null;
        hs.toggleImagesParams = null;
    } else {
        hs.focusTopmost();
    }
},

//--- Function for next and previous links
toggleImages : function(closeId, expandEl, params) {
    hs.closeId(closeId);
    if (hs.ie) expandEl.href = expandEl.href.replace('about:blank', ''); // mysterious IE thing
    hs.toggleImagesExpandEl = expandEl;
    hs.toggleImagesParams = params;
    return false;
},


//--- Do the stepwise change
changeSize : function (key, newWidth, newHeight, newLeft, newTop) {
    try {
        var exp = hs.expanders[key];
    
        exp.fullImage.width = newWidth;
        exp.fullImage.height = newHeight;
        exp.wrapper.style.visibility = 'visible';
        exp.wrapper.style.left = newLeft +'px';
        exp.wrapper.style.top = newTop +'px';
        exp.fullImage.style.visibility = 'visible';
        exp.thumb.style.visibility = 'hidden';
        
    } catch (e) {
        window.location.href = hs.expanders[key].a.href;
    }
},

//--- Icon for full expand 
putFullExpand : function (key) {
    if (hs.isBusy) return;
    
    if (!hs.expanders[key]) {
        return;
    }
    
    var href = hs.expanders[key].fullImage.src;
    var thisKey = key;
    
    // the anchor
    
    var aFullExpand = document.createElement('a');
    aFullExpand.id = 'fullexpand-'+ hs.expanders[key].fullImage.id;
    aFullExpand.style.position = 'absolute';
    aFullExpand.style.left = (hs.expanders[key].fullImage.width - 55) +'px';
    aFullExpand.style.top = (hs.expanders[key].fullImage.height - 55) +'px';
    aFullExpand.style.zIndex = 3;
    aFullExpand.href = 'javascript:hs.fullExpand('+ key +');';
    aFullExpand.title = hs.fullExpandTitle;
    aFullExpand.onmouseover = function () { hs.mouseIsOverFullExpand = true; }
    aFullExpand.onmouseout = function () { hs.mouseIsOverFullExpand = false; }
    
    // the image
    var imgFullExpand = document.createElement('img');
    imgFullExpand.src = hs.graphicsDir + hs.fullExpandIcon;
    imgFullExpand.style.border = '0';
    imgFullExpand.style.display = 'block';
    aFullExpand.appendChild(imgFullExpand);
    
    hs.expanders[key].wrapper.appendChild(aFullExpand);
    hs.expanders[key].fullExpand = aFullExpand;
},

fullExpand : function (key) {
    try {
        var exp = hs.expanders[key];
        
        var newLeft = parseInt(exp.wrapper.style.left) - (exp.fullExpandWidth - exp.fullImage.width) / 2;
        if (newLeft < hs.marginLeft) newLeft = hs.marginLeft;
        exp.wrapper.style.left = newLeft +'px';
		
		var borderOffset = exp.wrapper.offsetWidth - exp.fullImage.width;
        
        exp.fullImage.width = exp.fullExpandWidth;
        exp.fullImage.height = exp.fullExpandHeight;
        hs.focus(key);
        
        exp.fullExpand.className = 'highslide-display-none';
        
        hs.mouseIsOverFullExpand = false;
        
        exp.wrapper.style.width = (exp.fullImage.width + borderOffset) +'px';
        
        if (hs.outlineType) hs.repositionOutline(key, 0);
        
        hs.onEndMove(key);
    
    } catch (e) {
        window.location.href = hs.expanders[key].fullImage.src;
    }
},

showFullExpand : function (key) {
    if (hs.expanders[key] && hs.expanders[key].fullExpand) {
        hs.expanders[key].fullExpand.style.visibility = 'visible';
    }
},

hideFullExpand : function(key) {
    if (hs.expanders[key] && hs.expanders[key].fullExpand && !hs.mouseIsOverFullExpand) {
        hs.expanders[key].fullExpand.style.visibility = 'hidden';
    }
},

//--- Preload a number of images recursively
preloadFullImage : function (i) {
    if (hs.continuePreloading && hs.preloadTheseImages[i] && hs.preloadTheseImages[i] != 'undefined') {
        var img = document.createElement('img');
        img.onload = function() { hs.preloadFullImage(i + 1); }
        img.src = hs.preloadTheseImages[i];
    }
},

mouseMoveHandler : function(e)
{
  if (hs.isDrag)
  {
    var key = hs.dragObj.getAttribute('key');
    if (!hs.expanders[key].wrapper) return;
    var wrapper = hs.expanders[key].wrapper;
    
    var left = hs.nn6 ? tx + e.clientX - hs.dragX : tx + event.clientX - hs.dragX;
    wrapper.style.left = left +'px';
    var top = hs.nn6 ? ty + e.clientY - hs.dragY : ty + event.clientY - hs.dragY;
    wrapper.style.top  = top +'px';
    
    return false;
  }
}, 

mouseDownHandler : function(e) 
{

  var fobj       = hs.nn6 ? e.target : event.srcElement;
  var topelement = hs.nn6 ? "HTML" : "BODY";
  
  while (fobj.tagName != topelement && fobj.tagName != 'HTML' && !fobj.className.match('highslide-image'))
  {
    fobj = hs.nn6 ? fobj.parentNode : fobj.parentElement;
  }
  if (fobj.className.match('highslide-image'))
  {
    hs.isDrag = true;
    hs.dragObj = fobj;
    var tmpCursor = hs.dragObj.style.cursor;
    hs.defaultRestoreCursor = tmpCursor;
    hs.dragObj.style.cursor = 'move';
    tx = parseInt(hs.dragObj.parentNode.style.left);
    ty = parseInt(hs.dragObj.parentNode.style.top);
    
    hs.leftBeforeDrag = tx;
    hs.topBeforeDrag = ty;
    
    hs.dragX = hs.nn6 ? e.clientX : event.clientX;
    hs.dragY = hs.nn6 ? e.clientY : event.clientY;
    document.onmousemove = hs.mouseMoveHandler;
    
    if (fobj.className.match('highslide-image-blur')) {
        hs.focus(fobj.getAttribute('key'));
        hs.hasFocused = true;
    }
    return false;
  }
},

mouseUpHandler : function(e) {
    hs.isDrag = false;
    var fobj       = hs.nn6 ? e.target : event.srcElement;
    var topelement = hs.nn6 ? "HTML" : "BODY";

    while (fobj.tagName != topelement && fobj.tagName != 'HTML' && !fobj.className.match('highslide-image'))
    {
        fobj = hs.nn6 ? fobj.parentNode : fobj.parentElement;
    }
    if (fobj.className == 'highslide-image') {
        fobj.style.cursor = hs.defaultRestoreCursor;
        //if (hasDragged) hasDragged = false;
        var left = parseInt(fobj.parentNode.style.left);
        var top = parseInt(fobj.parentNode.style.top);
        if (left == hs.leftBeforeDrag && top == hs.topBeforeDrag && !hs.hasFocused) {
            hs.restoreThumb(fobj.getAttribute('key'));
        } else if (!hs.hasFocused) {
            hs.onEndMove(fobj.getAttribute('key'));
        }
        hs.hasFocused = false;
    
    } else if (fobj.className.match('highslide-image-blur')) {
        fobj.style.cursor = hs.defaultRestoreCursor;
        
    }
},
// on end move and end resize
onEndMove : function(key) {
    var exp = hs.expanders[key];
    // Selectbox bug
    var imgPos = {
        x: parseInt(exp.wrapper.style.left) - 20, 
        y: parseInt(exp.wrapper.style.top) - 20, 
        w: exp.fullImage.width + 40, 
        h: exp.fullImage.height + 40 + exp.spaceForCaption
    }        
    if (hs.ie) hs.showHideElements('SELECT', 'hidden', imgPos);
	// Iframes bug
    if (hs.hideIframes) hs.showHideElements('IFRAME', 'hidden', imgPos);        
},

preloadImages : function () {
    
    var j = 0;
    
    var aTags = document.getElementsByTagName('A');
    for (i = 0; i < aTags.length; i++) {
        a = aTags[i];
        if (a.className && (a.className.match("highslide$") || a.className.match("highslide "))) {
            if (j < this.numberOfImagesToPreload) {
                hs.preloadTheseImages[j] = a.href;
                j++;
            }
        }
    }
    
    hs.preloadFullImage(0); // starts recursive process
    
    // preload cursor
    var cur = document.createElement('img');
    cur.src = hs.graphicsDir + hs.restoreCursor;
    
    // preload outlines
    for (i = 1; i <= 8; i++) {
        var img = document.createElement('img');
        img.src = hs.graphicsDir + "outlines/"+ hs.outlineType +"/"+ i +".png";
    }
    
}
} // end hs object

// set handlers
document.onmousedown = hs.mouseDownHandler;
document.onmouseup = hs.mouseUpHandler;