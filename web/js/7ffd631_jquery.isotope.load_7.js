jQuery(window).load(function($){
	"use strict";

	function eborLoadIsotope(){
		var $container = jQuery('#container, #container-popup'),
			$optionContainer = jQuery('#options'),
			$options = $optionContainer.find('a[href^="#"]').not('a[href="#"]'),
			isOptionLinkClicked = false;
	
	
		$container.isotope({
			itemSelector : '.element',
			resizable: false,
			filter: '*',
			transitionDuration: '0.6s',
			layoutMode: 'packery'
		});

		
		  if( jQuery('body').hasClass('video-detail') )
		  $container.isotope({
			transformsEnabled: false,
		});	
			
		jQuery(window).smartresize(function(){
			$container.isotope('layout');
		});
	  
		
	
		jQuery(window).bind( 'hashchange', function( event ){
			var theFilter = window.location.hash.replace( /^#/, '');
			
			if( theFilter == false )
				theFilter = 'home';
				
			$container.isotope({
				filter: '.' + theFilter
			});
			
			if ( isOptionLinkClicked == false ){
				$options.removeClass('selected');
				$optionContainer.find('a[href="#'+ theFilter +'"]').addClass('selected');
			}
			
			isOptionLinkClicked = false;
		}).trigger('hashchange');
		

		
	}
	
	
		eborLoadIsotope();
	
	
	
	jQuery(window).trigger('resize').trigger('smartresize');
	
});