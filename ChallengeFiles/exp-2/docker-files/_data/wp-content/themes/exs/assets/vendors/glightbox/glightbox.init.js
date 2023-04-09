'use strict';
//IIFE
;(function(d,w,gid) {
	var galleryBlockBox = new GLightbox({
		selector: '.blocks-gallery-item  figure a, .wp-block-gallery figure a, .gallery figure a'
	});
	var imageVideoLinkBlockBox = new GLightbox({
		selector: 'a[href*="youtube.com"]:not(.social-icon), a[href*="youtu.be"]'
	});
})(document,window,document.getElementById.bind(document));