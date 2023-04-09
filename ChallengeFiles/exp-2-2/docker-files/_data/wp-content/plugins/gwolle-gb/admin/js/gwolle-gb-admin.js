
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
 * JavaScript for Gwolle Guestbook, WP-Admin.
 */


/*
 * Postbox on every admin page of this plugin.
 */
jQuery(document).ready(function($) {
	jQuery('#gwolle_gb_editor_postbox_preview').addClass('closed');

	jQuery('.gwolle_gb .postbox button.handlediv').on( 'click', function() {
		jQuery(this).closest('.postbox').toggleClass('closed');
	});

	jQuery('.gwolle_gb .postbox h2').on( 'click', function() {
		jQuery(this).closest('.postbox').toggleClass('closed');
	});
});


/*
 * Entries Page
 */
jQuery(document).ready(function($) {

	jQuery("#gwolle_gb_entries input[name='check-all-top']").on('change', function() {
		gwolle_gb_toggleCheckboxes(jQuery("input[name='check-all-top']").is(":checked"));
	});

	jQuery("#gwolle_gb_entries input[name='check-all-bottom']").on('change', function() {
		gwolle_gb_toggleCheckboxes(jQuery("input[name='check-all-bottom']").is(":checked"));
	});

	// Function to check/uncheck all checkboxes.
	function gwolle_gb_toggleCheckboxes(checkAll_checked) {
		jQuery("input[name^='check-']").attr("checked", checkAll_checked);
	}

});


/*
 * Editor page
 */

/* Edit metadata */
jQuery(document).ready(function($) {
	jQuery('.gwolle_gb_edit_meta').on( 'click', function() {
		jQuery('.gwolle_gb_editor_meta_inputs').toggle();
		return false;
	});

	jQuery('.gwolle_gb_cancel_timestamp').on( 'click', function() {
		jQuery('.gwolle_gb_editor_meta_inputs').toggle();
		return false;
	});

	jQuery('.gwolle_gb_save_timestamp').on( 'click', function() {

		var dd = jQuery("#dd").val();
		var mm = jQuery("#mm").find(":selected").val();
		var yy = jQuery("#yy").val();
		var hh = jQuery("#hh").val();
		var mn = jQuery("#mn").val();

		var gwolle_date = new Date( yy, ( mm - 1 ), dd, hh, mn );
		readable_time = gwolle_date.toUTCString();
		readable_time = readable_time.replace(/GMT/i, '');
		var timestamp = Math.round( gwolle_date.getTime() / 1000 );
		jQuery("#gwolle_gb_timestamp").val(timestamp); // local time of the server.
		jQuery( 'span.gb-editor-datetime' ).text( readable_time );

		var author_name = jQuery("#gwolle_gb_author_name").val();
		jQuery( 'span.gb-editor-author-name' ).text( author_name );

		var book_id = jQuery("#gwolle_gb_book_id").val();
		jQuery( 'span.gb-editor-book-id' ).text( book_id );


		jQuery('.gwolle_gb_editor_meta_inputs').toggle();
		return false;
	});
});


/*
 * Settings Page
 */
jQuery(document).ready(function($) {

	/* Select the right tab on the options page */
	jQuery( '.gwolle-nav-tab-wrapper a' ).on('click', function() {
		jQuery( 'form.gwolle_gb_options' ).removeClass( 'active' );
		jQuery( '.gwolle-nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );

		var rel = jQuery( this ).attr('rel');
		jQuery( '.' + rel ).addClass( 'active' );
		jQuery( this ).addClass( 'nav-tab-active' );

		return false;
	});


	/* Checking checkbox will enable the uninstall button */
	jQuery("input#gwolle_gb_uninstall_confirmed").prop("checked", false); // init

	jQuery("input#gwolle_gb_uninstall_confirmed").on('change', function() {
		var checked = jQuery( "input#gwolle_gb_uninstall_confirmed" ).prop('checked');
		if ( checked == true ) {
			jQuery("#gwolle_gb_uninstall").addClass( 'button-primary' );
			jQuery("#gwolle_gb_uninstall").prop('disabled', false);
		} else {
			jQuery("#gwolle_gb_uninstall").removeClass( 'button-primary' );
			jQuery("#gwolle_gb_uninstall").prop('disabled', true);
		}
	});

});


/*
 * Import Page
 */
jQuery(document).ready(function($) {

	/* Checking checkbox will enable the submit button for DMS import */
	jQuery("input#gwolle_gb_dmsguestbook").prop("checked", false); // init

	jQuery("input#gwolle_gb_dmsguestbook").on('change', function() {
		var checked = jQuery( "input#gwolle_gb_dmsguestbook" ).prop('checked');
		if ( checked == true ) {
			jQuery("#start_import_dms").addClass( 'button-primary' );
			jQuery("#start_import_dms").prop('disabled', false);
		} else {
			jQuery("#start_import_dms").removeClass( 'button-primary' );
			jQuery("#start_import_dms").prop('disabled', true);
		}
	});


	/* Checking radio-buttons will enable the submit button for Gwolle import */
	jQuery("input#gwolle_gb_importfrom").prop("checked", false); // init

	jQuery("input#gwolle_gb_importfrom").on('change', function() {
		if ( jQuery(this).val() ) {
			jQuery("#start_import_wp").addClass( 'button-primary' );
			jQuery("#start_import_wp").prop('disabled', false);
		} else {
			jQuery("#start_import_wp").removeClass( 'button-primary' );
			jQuery("#start_import_wp").prop('disabled', true);
		}
	});


	/* Checking checkbox will enable the submit button for CSV-file */
	jQuery("input#start_import_gwolle_file").on('change', function() {
		if ( jQuery(this).val() ) {
			jQuery("#start_import_gwolle").addClass( 'button-primary' );
			jQuery("#start_import_gwolle").prop('disabled', false);
		} else {
			jQuery("#start_import_gwolle").removeClass( 'button-primary' );
			jQuery("#start_import_gwolle").prop('disabled', true);
		}
	});

});


/*
 * Export Page for all entries.
 */
jQuery(document).ready(function($) {

	/* Checking checkbox will enable the submit button */
	jQuery("input#start_export_enable").prop("checked", false); // init
	jQuery("#gwolle_gb_export_part").val( 1 ); // init

	jQuery("input#start_export_enable").on('change', function() {
		var checked = jQuery( "input#start_export_enable" ).prop('checked');
		if ( checked == true ) {
			jQuery("#gwolle_gb_start_export").addClass( 'button-primary' );
			jQuery("#gwolle_gb_start_export").prop('disabled', false);
		} else {
			jQuery("#gwolle_gb_start_export").removeClass( 'button-primary' );
			jQuery("#gwolle_gb_start_export").prop('disabled', true);
		}
	});


	/* Click Event, submit the form through AJAX and receive a CSV-file.
	 * Will request multi part files, every 5 seconds to be easy on the webserver.
	 */
	jQuery( 'input#gwolle_gb_start_export' ).on( 'click', function(event) {

		if ( jQuery("#gwolle_gb_start_export").prop('disabled') ) {
			// Not sure if this block is needed... Just in case.
			return;
		}

		// Reset for to initial state.
		jQuery( "#gwolle_gb_start_export" ).removeClass( 'button-primary' );
		jQuery( "#gwolle_gb_start_export" ).prop( 'disabled', true );
		jQuery( "input#start_export_enable" ).prop( 'checked', false );
		// Show that we are busy.
		jQuery( ".gwolle_gb_export_gif" ).css( 'visibility', 'visible' );

		var parts = parseFloat( jQuery("#gwolle_gb_export_parts").val() );

		for ( var part = 1; part < (parts + 1); part++ ) {
			var timeout = ( part - 1 ) * 10000;
			gwolle_gb_export_part( part, timeout );
		}

		setTimeout(
			function() {
				jQuery( ".gwolle_gb_export_gif" ).css( 'visibility', 'hidden' );
			}, ( (part - 1) * 10000 )
		);

		event.preventDefault();
	});

	/* Do the Submit Event. */
	function gwolle_gb_export_part( part, timeout ) {
		setTimeout(
			function() {
				jQuery("#gwolle_gb_export_part").val( part );
				var form = jQuery('form#gwolle_gb_export');
				form.trigger('submit');
			}, ( timeout )
		);
	}

});


/*
 * Export Page for user ID / Email.
 */
jQuery(document).ready(function($) {

	/* Checking checkbox will enable the submit button */
	jQuery("input#start_export_user_enable").prop("checked", false); // init

	jQuery("input#start_export_user_enable").on('change', function() {
		var checked = jQuery( "input#start_export_user_enable" ).prop('checked');
		if ( checked == true ) {
			jQuery("#gwolle_gb_start_export_user").addClass( 'button-primary' );
			jQuery("#gwolle_gb_start_export_user").prop('disabled', false);
		} else {
			jQuery("#gwolle_gb_start_export_user").removeClass( 'button-primary' );
			jQuery("#gwolle_gb_start_export_user").prop('disabled', true);
		}
	});


	/* Click Event, submit the form through AJAX and receive a CSV-file.
	 * Will request multi part files, every 5 seconds to be easy on the webserver.
	 */
	jQuery( 'input#gwolle_gb_start_export_user' ).on( 'click', function(event) {

		if ( jQuery("#gwolle_gb_start_export_user").attr('disabled') ) {
			// Not sure if this block is needed... Just in case.
			return;
		}

		var form = jQuery('form#gwolle_gb_export_user');
		form.trigger('submit');

		// Reset for to initial state.
		jQuery( "#gwolle_gb_start_export_user" ).removeClass( 'button-primary' );
		jQuery( "#gwolle_gb_start_export_user" ).prop( 'disabled', true );
		jQuery( "input#start_export_user_enable" ).prop( 'checked', false );

		event.preventDefault();
	});

});
