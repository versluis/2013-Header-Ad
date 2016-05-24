// 2013 Header Ad - JavaScript Document
// since @1.0

// append our #p2013HeaderAd to the current #header
var $p2013HeaderAdContent = jQuery('#p2013HeaderAd');
jQuery('.site-header').append($p2013HeaderAdContent);

// make our ad visible via fadeIn - see http://api.jquery.com/fadeIn/
jQuery('#p2013HeaderAd').delay(1000).fadeIn('slow', function () {
	// could add something here upon completion	
});

/**************************************************************
   ALTERNATIVE APPROACHES
   
   - use this function with document.ready in the head section
   - instead of fade in, just set the css to visible, like so
   
   jQuery('#p2013HeaderAd').css('visibility', 'visible');
   
**************************************************************/