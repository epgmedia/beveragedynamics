jQuery(document).ready(function ($) {


	// Top Bar
	$(document)
	.foundation('alerts clearing cookie dropdown forms joyride magellan orbit placeholder reveal section tooltips')
	.foundation('topbar', {
		custom_back_text: false
	}, function (response) {
		if (window.console) {
			console.log(response.errors);
		}
	});


	// CSS fix for custom Flash news title
	$('.flash-news').fadeIn(200);
	$('.flash-news .orbit-container').css({ left: $('.flash-header').outerWidth() });


	// Flexsliders
	$('.slider').each( function() {

		var autoplay = parseInt($(this).attr('data-autoplay'), 10);
		var random = parseInt($(this).attr('data-random'), 10);
		var sh = true;

		if( autoplay === 0 ) {
			sh = false;
		}

		if( random === 1 ) {
			random = true;
		} else {
			random = false;
		}

		$(this).flexslider({
			slideshow: sh,
			slideshowSpeed: autoplay,
			randomize: random,
			prevText: '<i class="icon-chevron-left"></i>',
			nextText: '<i class="icon-chevron-right"></i>',
			smoothHeight: true
		});
	});


	$('body').fitVids();


	/* DROPDOWN NAV ------------- */

	$(".menu-item.has-dropdown").hover(
	function () {
	    $("ul:first", this).css({
	        visibility: "visible",
	        display: "none"
	    }).fadeIn(100);
	}, function () {
	    $("ul:first", this).css({
	        visibility: "hidden",
	        display: "none"
	    });
	});

	// Preserves the mouse-over on top-level menu elements when hovering over children
	$(".menu-item.has-dropdown .dropdown").each(
	function (i) {
	    $(this).hover(
	    function () {
	        $(this).parent().slice(0, 1).addClass("active");
	    }, function () {
	        $(this).parent().slice(0, 1).removeClass("active");
	    });
	});

	$(".header-main-menu").each(
	function (i) {
	    $(this).hover(
	    function () {
	        $(this).find(".trigger").slice(0, 1).addClass("active");
	    }, function () {
	        $(this).find(".trigger").slice(0, 1).removeClass("active");
	    });
	});

	$('.header-style-default').click( function() {

		$('body').addClass('header_default');
		$('body').removeClass('header_style_2');
		$('body').removeClass('header_style_3');

		return false;
	});

	$('.header-style-2').click( function() {

		$('body').addClass('header_style_2');
		$('body').removeClass('header_default');
		$('body').removeClass('header_style_3');

		return false;
	})

	$('.header-style-3').click( function() {

		$('body').addClass('header_style_3');
		$('body').removeClass('header_style_2');
		$('body').removeClass('header_default');

		return false;
	});



	if (!(window.console && console.log)) {
		(function() {
			var noop = function() {};
			var methods = ['assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error', 'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log', 'markTimeline', 'profile', 'profileEnd', 'markTimeline', 'table', 'time', 'timeEnd', 'timeStamp', 'trace', 'warn'];
			var length = methods.length;
			var console = window.console = {};
			while (length--) {
				console[methods[length]] = noop;
			}
		}());
	}



});