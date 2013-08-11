/**
 * Java script document
 */

// Only do anything if jQuery isn't defined
if (typeof jQuery == 'undefined') {

    /**
     * 
     */
	if (typeof $ == 'function') {
		// warning, global var
		var thisPageUsingOtherJSLibrary = true;
	}

    /**
     * 
     */
	function mgridGetScript(url, success) {
	
		var script = document.createElement('script');
        script.src = url;
		
		var head = document.getElementsByTagName('head')[0],
            done = false;
		
		// Attach handlers for all browsers
		script.onload = script.onreadystatechange = function() {
		
			if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) {
			
			done = true;
				
				// callback function provided as param
				success();
				
				script.onload = script.onreadystatechange = null;
				head.removeChild(script);
				
			};
		
		};
		
		head.appendChild(script);
	
	};
	
    /**
     * 
     */
	mgridGetScript('http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js', function() {
	
		if (typeof jQuery=='undefined') {
			// Super failsafe - still somehow failed...
		} else {
			if (!thisPageUsingOtherJSLibrary) {
				jQuery.noConflict();
			}
            
            jQuery.getScript("/mgrid-assets/js/mgrid.js");
		}
	
	});
	
} else { // jQuery was already loaded
    jQuery.getScript("/mgrid-assets/js/mgrid.js");
};