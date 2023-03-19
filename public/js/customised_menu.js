// JavaScript Document
jQuery("document").ready(function($){
	
	var nav = $('.main-menu-1x');
	
	$(window).scroll(function () {
		if ($(this).scrollTop() >110) {
			nav.addClass("f-nav");
		} else {
			nav.removeClass("f-nav");
		}
	});

});