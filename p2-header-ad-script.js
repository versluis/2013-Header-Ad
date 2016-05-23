// P2 Categories - JavaScript Document
// since @1.2

// append our #p2HeaderAd to the current #header
var $p2HeaderAdContent = jQuery('#p2HeaderAd');
jQuery('#header').append($p2HeaderAdContent);

// make our ad visible via fadeIn - see http://api.jquery.com/fadeIn/
jQuery('#p2HeaderAd').delay(1000).fadeIn('slow', function () {
	// could add something here upon completion	
});

/**************************************************************
   ALTERNATIVE APPROACHES
   
   - use this function with document.ready in the head section
   - instead of fade in, just set the css to visible, like so
   
   jQuery('#p2HeaderAd').css('visibility', 'visible');
   
**************************************************************/