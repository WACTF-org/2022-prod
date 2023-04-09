
/*
Copyright 2014 - 2021  Marcel Pol  (email: marcel@timelord.nl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


/*
 * JavaScript for Gwolle Guestbook Frontend.
 */

var gwolle_gb_ajax_callback = jQuery.Callbacks(); // Callback function to be fired after AJAX request.


/*
 * Click the button and the form becomes visible.
 */
jQuery(document).ready(function($) {
	jQuery( "div.gwolle-gb-write-button input" ).on( 'click', function() {
		var main_div = jQuery( this ).closest( 'div.gwolle-gb' );
		jQuery("div.gwolle-gb-write-button", main_div).slideUp(1000);
		jQuery("form.gwolle-gb-write", main_div).slideDown(1000);
		return false;
	});

	// And close it again.
	jQuery( "button.gb-notice-dismiss" ).on( 'click', function() {
		var main_div = jQuery( this ).closest( 'div.gwolle-gb' );
		jQuery("div.gwolle-gb-write-button", main_div).slideDown(1000);
		jQuery("form.gwolle-gb-write", main_div).slideUp(1000);
		return false;
	});
});


/*
 * Click the readmore and the full content of that entry becomes visible.
 */
jQuery(document).ready(function($) {
	gwolle_gb_readmore();
	gwolle_gb_ajax_callback.add( gwolle_gb_readmore );
});
function gwolle_gb_readmore() {
	jQuery(".gb-entry-content .gwolle-gb-readmore").off('click');
	jQuery(".gb-entry-content .gwolle-gb-readmore").on('click', function() {
		var content_div = jQuery(this).closest( '.gb-entry-content' );
		jQuery('.gb-entry-excerpt', content_div).css( 'display', 'none' );
		jQuery('.gb-entry-full-content', content_div).slideDown(500);
		return false;
	});

	jQuery(".gb-entry-admin_reply .gwolle-gb-readmore").off('click');
	jQuery(".gb-entry-admin_reply .gwolle-gb-readmore").on('click', function() {
		var content_div = jQuery(this).closest( '.gb-entry-admin_reply' );
		jQuery('.gb-admin_reply-excerpt', content_div).css( 'display', 'none' );
		jQuery('.gb-admin_reply-full-content', content_div).slideDown(500);
		return false;
	});
}


/*
 * Metabox, toggle on and off.
 */
jQuery(document).ready(function($) {
	gwolle_gb_metabox_handle();
	gwolle_gb_ajax_callback.add( gwolle_gb_metabox_handle );
});
function gwolle_gb_metabox_handle() {
	jQuery('div.gb-metabox-handle').off('click');
	jQuery('div.gb-metabox-handle').on('click', function() {
		var entry_div = jQuery(this).closest('div.gb-entry');
		jQuery('div.gb-metabox', entry_div).fadeToggle( 'fast', 'linear' );
		return false;
	});
	jQuery("div.gb-metabox-handle").on( 'keypress', function(e) {
		if (e.keyCode == 13) { // Enter key.
			var entry_div = jQuery(this).closest('div.gb-entry');
			jQuery('div.gb-metabox', entry_div).fadeToggle(  'fast', 'linear' );
			return false;
		}
	});
	return false;
}
jQuery(document).ready(function($) {
	jQuery('body').on('click', function( el ) {
		jQuery('div.gb-metabox').fadeOut(  'fast', 'linear' );
	});
});


/*
 * Infinite Scroll. Get more pages when you are at the bottom.
 * This function does not support multiple lists on one page.
 */
var gwolle_gb_scroll_on = true; // The end has not been reached yet. We still get entries back.
var gwolle_gb_scroll_busy = false; // Handle async well. Only one request at a time.
// deprecated since 4.3.0, remove this callback sometime soon.
var gwolle_gb_scroll_callback = jQuery.Callbacks(); // Callback function to be fired after AJAX request.

jQuery(document).ready(function($) {
	if ( jQuery( ".gwolle-gb-read" ).hasClass( 'gwolle-gb-infinite' ) ) {
		var gwolle_gb_scroll_count = 2; // We already have page 1 listed.

		var gwolle_gb_load_message = '<div class="gb-entry gwolle_gb_load_message">' + gwolle_gb_frontend_script.load_message + '</div>';
		jQuery( ".gwolle-gb-read" ).append( gwolle_gb_load_message );

		jQuery(window).on('scroll', function() {
			// have 10px diff for sensitivity.
			if ( ( jQuery(window).scrollTop() > jQuery(document).height() - jQuery(window).height() - 10 ) && gwolle_gb_scroll_on == true && gwolle_gb_scroll_busy == false) {
				gwolle_gb_scroll_busy = true;
				gwolle_gb_load_page(gwolle_gb_scroll_count);
				gwolle_gb_scroll_count++;
			}
		});
	}

	function gwolle_gb_load_page( page ) {

		jQuery('.gwolle_gb_load_message').toggle();

		var gwolle_gb_end_message = '<div class="gb-entry gwolle_gb_end_message">' + gwolle_gb_frontend_script.end_message + '</div>';

		var data = {
			action: 'gwolle_gb_infinite_scroll',
			pageNum: page,
			permalink: window.location.href,
			book_id: jQuery( ".gwolle-gb-read" ).attr( "data-book_id" )
		};

		jQuery.post( gwolle_gb_frontend_script.ajax_url, data, function(response) {

			jQuery('.gwolle_gb_load_message').toggle();
			if ( response == 'false' ) {
				jQuery( ".gwolle-gb-read" ).append( gwolle_gb_end_message );
				gwolle_gb_scroll_on = false;
			} else {
				jQuery( ".gwolle-gb-read" ).append( response );
			}

			/*
			 * Add callback for after ajax event. Used for metabox-handle for new entries.
			 *
			 * @since 2.3.0
			 *
			 * Example code for using the callback:
			 *
			 * jQuery(document).ready(function($) {
			 *     gwolle_gb_ajax_callback.add( my_callback_function );
			 * });
			 *
			 * function my_callback_function() {
			 *     console.log('This is the callback');
			 *     return false;
			 * }
			 *
			 */
			gwolle_gb_ajax_callback.fire();

			gwolle_gb_scroll_busy = false;

		});

		return true;
	}
});


/*
 * Mangle data for the honeypot.
 */
jQuery(document).ready(function($) {
	jQuery( 'form.gwolle-gb-write' ).each( function( index, form ) {
		var honeypot  = gwolle_gb_frontend_script.honeypot;
		var honeypot2 = gwolle_gb_frontend_script.honeypot2;
		var val = jQuery( 'input.' + honeypot, form ).val();
		if ( val > 0 ) {
			jQuery( 'input.' + honeypot2, form ).val( val );
			jQuery( 'input.' + honeypot, form ).val( '' );
		}
	});
});


/*
 * Mangle data for the form timeout.
 */
jQuery(document).ready(function($) {
	jQuery( 'form.gwolle-gb-write' ).each( function( index, form ) {
		var timeout  = gwolle_gb_frontend_script.timeout;
		var timeout2 = gwolle_gb_frontend_script.timeout2;

		var timer  = new Number( jQuery( 'input.' + timeout, form ).val() );
		var timer2 = new Number( jQuery( 'input.' + timeout2, form ).val() );

		var timer  = timer - 1;
		var timer2 = timer2 + 1;

		jQuery( 'input.' + timeout, form ).val( timer );
		jQuery( 'input.' + timeout2, form ).val( timer2 );
	});
});


/*
 * AJAX Submit for Gwolle Guestbook Frontend.
 */
// Use an object, arrays are only indexed by integers. This var is kept for compatibility with add-on 1.0.0 till 1.1.1.
var gwolle_gb_ajax_data = {
	permalink: window.location.href,
	action: 'gwolle_gb_form_ajax'
};

jQuery(document).ready(function($) {
	jQuery( '.gwolle_gb_form_ajax input.gwolle_gb_submit' ).on( 'click', function( submit_button ) {
		var main_div = jQuery( this ).closest( 'div.gwolle-gb' );
		jQuery( '.gwolle_gb_submit_ajax_icon', main_div ).css( 'display', 'inline' );

		// Use an object, arrays are only indexed by integers.
		var gwolle_gb_ajax_data = {
			permalink: window.location.href,
			action: 'gwolle_gb_form_ajax'
		};

		jQuery('form.gwolle-gb-write input', main_div).each(function( index, value ) {
			var val = jQuery( this ).prop('value');
			var name = jQuery( this ).attr('name');
			var type = jQuery( this ).attr('type');
			if ( type === 'checkbox' ) {
				var checked = jQuery( this, main_div ).prop('checked');
				if ( checked === true ) {
					gwolle_gb_ajax_data[name] = 'on'; // Mimick standard $_POST value.
				}
			} else if ( type === 'radio' ) {
				var checked = jQuery( this, main_div ).prop('checked');
				if ( checked === true ) {
					gwolle_gb_ajax_data[name] = val;
				}
			} else {
				gwolle_gb_ajax_data[name] = val;
			}
		});
		jQuery('form.gwolle-gb-write textarea', main_div).each(function( index, value ) {
			var val = jQuery( this ).val();
			var name = jQuery( this ).attr('name');
			gwolle_gb_ajax_data[name] = val;
		});
		jQuery( 'form.gwolle-gb-write select', main_div ).each(function( index, value ) {
			var val = jQuery( value ).val();
			var name = jQuery( value ).attr('name');
			gwolle_gb_ajax_data[name] = val;
		});

		jQuery.post( gwolle_gb_frontend_script.ajax_url, gwolle_gb_ajax_data, function( response ) {

			if ( gwolle_gb_is_json( response ) ) {
				data = JSON.parse( response );

				if ( ( typeof data['saved'] === 'boolean' || typeof data['saved'] === 'number' )
					&& typeof data['gwolle_gb_messages'] === 'string'
					&& typeof data['gwolle_gb_errors'] === 'boolean'
					&& typeof data['gwolle_gb_error_fields'] === 'object' ) { // Too strict in testing?

					var saved                  = data['saved'];
					var gwolle_gb_messages     = data['gwolle_gb_messages'];
					var gwolle_gb_errors       = data['gwolle_gb_errors'];
					var gwolle_gb_error_fields = data['gwolle_gb_error_fields'];

					jQuery( '.gwolle_gb_form_ajax input' ).removeClass( 'error' );
					jQuery( '.gwolle_gb_form_ajax select' ).removeClass( 'error' );
					jQuery( '.gwolle_gb_form_ajax textarea' ).removeClass( 'error' );
					jQuery( '.gwolle_gb_form_ajax div.input').removeClass( 'error' );

					// we have all the data we expect.
					if ( typeof data['saved'] === 'number' ) {

						// Show returned messages.
						jQuery( '.gwolle_gb_messages_bottom_container', main_div ).html('');
						jQuery( '.gwolle_gb_messages_top_container', main_div ).html('<div class="gwolle_gb_messages">' + data['gwolle_gb_messages'] + '</div>');
						jQuery( '.gwolle_gb_messages', main_div ).removeClass( 'error' );

						// Remove form from view.
						jQuery( '.gwolle-gb-write', main_div ).css( 'display', 'none' );
						jQuery( '.gwolle-gb-write-button', main_div ).css( 'display', 'block' );

						// Prepend entry to the entry list if desired.
						if ( typeof data['entry'] === 'string' ) {
							jQuery( '.gwolle-gb-read', main_div ).prepend( data['entry'] );
						}

						// Scroll to messages div. Add 80px to offset for themes with fixed headers.
						var offset = jQuery( '.gwolle_gb_messages_top_container' ).offset().top - 80;
						jQuery('html, body').animate({
							scrollTop: offset
						}, 200, function() {
							// Animation complete.
						});

						// Reset content textarea.
						jQuery( 'textarea', main_div ).val('');

						jQuery( '.gwolle_gb_submit_ajax_icon', main_div ).css( 'display', 'none' );

						/*
						 * Add callback for after AJAX request. Used for metabox-handle for new entries.
						 *
						 * @since 2.3.0
						 *
						 * Example code for using the callback:
						 *
						 * jQuery(document).ready(function($) {
						 *     gwolle_gb_ajax_callback.add( my_callback_function );
						 * });
						 *
						 * function my_callback_function() {
						 *     console.log('This is the callback');
						 *     return false;
						 * }
						 *
						 */
						gwolle_gb_ajax_callback.fire();

					} else {
						// Not saved...

						// Show returned messages.
						jQuery( '.gwolle_gb_messages_top_container', main_div ).html('');
						jQuery( '.gwolle_gb_messages_bottom_container', main_div ).html('<div class="gwolle_gb_messages error">' + data['gwolle_gb_messages'] + '</div>');

						// Add error class to failed input fields.
						jQuery.each( gwolle_gb_error_fields, function( index, value ) {
							jQuery( 'textarea.' + value, main_div  ).addClass( 'error' );
							jQuery( 'input.' + value, main_div  ).addClass( 'error' );
							var type = jQuery( 'input.' + value, main_div ).attr('type');
							if ( typeof type !== 'undefined' && type === 'radio' ) {
								jQuery( 'input.' + value, main_div  ).closest('div.input').addClass( 'error' );
							}
							var select = jQuery( 'select.' + value, main_div ).length;
							if ( typeof select !== 'undefined' && select === 1 ) { // number of elements, which should be 1.
								jQuery( 'select.' + value, main_div  ).closest('div.input').addClass( 'error' );
							}
						});

						jQuery( '.gwolle_gb_submit_ajax_icon', main_div ).css( 'display', 'none' );

					}
				} else if (typeof console != "undefined") {
					console.log( 'Gwolle Error: Something unexpected happened. (not the data that is expected)' );
				}
			} else {
				if (typeof console != "undefined") {
					console.log( 'Gwolle Error: Something unexpected happened. (not json data)' );
				}
			}
		});
		return false;
	});
});


/*
 * Maxlength for text in textarea content.
 */
jQuery(document).ready(function($) {
	jQuery( 'form.gwolle-gb-write textarea.maxlength' ).on( 'keyup', function( textarea ) {
		var div_input = jQuery( textarea.target ).closest( 'div.input' );
		var content = jQuery( this ).prop('value');
		content = content.trim();

		// split and assign cut up emoji. Array.from and spread operator support multibyte characters like emoji.
		if ( typeof Array.from === 'function' ) {
			// New browsers with support for ES6
			var chars = Array.from( content );
			var length = chars.length;
		} else {
			// Old browsers: Count emoji as double characters.
			var length = content.length;
		}

		jQuery( 'span.gb-used-characters', div_input ).text( length );

		return false;
	});
});


function gwolle_gb_is_json( string ) {
	try {
		JSON.parse( string );
	} catch (e) {
		return false;
	}
	return true;
}
