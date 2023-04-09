<?php
/*
 * ajax.php
 * Processes AJAX requests on admin pages.
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Add JavaScript to the admin Footer so we can do Ajax.
 */
add_action( 'admin_footer', 'gwolle_gb_ajax_javascript' );
function gwolle_gb_ajax_javascript() {
	if ( ! current_user_can('moderate_comments') ) {
		return;
	}

	// Set Your Nonce
	$ajax_nonce = wp_create_nonce( 'gwolle_gb_ajax' ); ?>

	<script>
	jQuery( document ).ready( function( $ ) {

		// Page-entries.php Admin page Click events
		jQuery( '#gwolle_gb_entries .gwolle_gb_actions a' ).on( 'click', function(event) {

			// Do not do anything here...
			var parent_class = jQuery(this).parent().hasClass('gwolle_gb_edit');
			if (parent_class) {
				//console.log('gwolle_gb_edit');
				return;
			}
			var parent_class = jQuery(this).parent().hasClass('gwolle_gb_ajax');
			if (parent_class) {
				//console.log('gwolle_gb_ajax');
				return;
			}


			// Set up data to send
			var a_el_id = jQuery(this).attr('id');
			var entry_id = a_el_id;
			entry_id = entry_id.replace(/uncheck_/,'');
			entry_id = entry_id.replace(/check_/,'');
			entry_id = entry_id.replace(/unspam_/,'');
			entry_id = entry_id.replace(/spam_/,'');
			entry_id = entry_id.replace(/untrash_/,'');
			entry_id = entry_id.replace(/trash_/,'');
			var setter = a_el_id.replace(new RegExp( "_" + entry_id, "g"), '');

			var data = {
				action: 'gwolle_gb_ajax',
				security: '<?php echo esc_attr( $ajax_nonce ); ?>',
				id: entry_id,
				setter: setter,
			};


			// Set Ajax icon on visible
			jQuery( 'tr#entry_' + entry_id + ' .gwolle_gb_ajax' ).css('display', 'inline-block');


			// Do the actual request
			jQuery.post( ajaxurl, data, function( response ) {
				response = response.trim();

				// Set classes accordingly
				if ( response == setter ) { // We got what we wanted

					// Countdown counter in admin menu, toolbar and top menu
					if ( jQuery( 'tr#entry_' + entry_id ).hasClass('unchecked') && jQuery( 'tr#entry_' + entry_id ).hasClass('nospam') && jQuery( 'tr#entry_' + entry_id ).hasClass('notrash') ) {
						var gwolle_gb_menu_counter = jQuery('li#toplevel_page_gwolle-gb-gwolle-gb a.menu-top span.awaiting-mod span').text();
						var old_gwolle_gb_menu_counter = new Number( gwolle_gb_menu_counter );
						var new_gwolle_gb_menu_counter = old_gwolle_gb_menu_counter - 1;
						var new_gwolle_gb_menu_counter_str = new_gwolle_gb_menu_counter.toString();

						jQuery('li#toplevel_page_gwolle-gb-gwolle-gb span.awaiting-mod span').text( new_gwolle_gb_menu_counter );
						jQuery('li#wp-admin-bar-gwolle-gb span.awaiting-mod.pending-count').text( new_gwolle_gb_menu_counter );
						jQuery('ul.subsubsub span.count.gwolle_gb_new').text( '(' + new_gwolle_gb_menu_counter_str + ')' );
					}

					switch ( response ) {
						// Possible classes: nospam notrash checked visible spam trash unchecked invisible
						case 'uncheck':
							jQuery( 'tr#entry_' + entry_id ).addClass('unchecked').removeClass('checked');
							break;
						case 'check':
							jQuery( 'tr#entry_' + entry_id ).addClass('checked').removeClass('unchecked');
							break;
						case 'unspam':
							jQuery( 'tr#entry_' + entry_id ).addClass('nospam').removeClass('spam');
							break;
						case 'spam':
							jQuery( 'tr#entry_' + entry_id ).addClass('spam').removeClass('nospam');
							break;
						case 'untrash':
							jQuery( 'tr#entry_' + entry_id ).addClass('notrash').removeClass('trash');
							break;
						case 'trash':
							jQuery( 'tr#entry_' + entry_id ).addClass('trash').removeClass('notrash');
							break;
						}

					// Set to visible or invisible respectively
					if ( jQuery( 'tr#entry_' + entry_id ).hasClass('checked') && jQuery( 'tr#entry_' + entry_id ).hasClass('nospam') && jQuery( 'tr#entry_' + entry_id ).hasClass('notrash') ) {
						jQuery( 'tr#entry_' + entry_id ).addClass('visible').removeClass('invisible');
					} else {
						jQuery( 'tr#entry_' + entry_id ).addClass('invisible').removeClass('visible');
					}

					// Countup counter in admin menu, toolbar and top menu
					if ( jQuery( 'tr#entry_' + entry_id ).hasClass('unchecked') && jQuery( 'tr#entry_' + entry_id ).hasClass('nospam') && jQuery( 'tr#entry_' + entry_id ).hasClass('notrash') ) {
						var gwolle_gb_menu_counter = jQuery('li#toplevel_page_gwolle-gb-gwolle-gb a.menu-top span.awaiting-mod span').text();
						var old_gwolle_gb_menu_counter = new Number( gwolle_gb_menu_counter );
						var new_gwolle_gb_menu_counter = old_gwolle_gb_menu_counter + 1;
						var new_gwolle_gb_menu_counter_str = new_gwolle_gb_menu_counter.toString();

						jQuery('li#toplevel_page_gwolle-gb-gwolle-gb span.awaiting-mod span').text( new_gwolle_gb_menu_counter );
						jQuery('li#wp-admin-bar-gwolle-gb span.awaiting-mod.pending-count').text( new_gwolle_gb_menu_counter );
						jQuery('ul.subsubsub span.count.gwolle_gb_new').text( '(' + new_gwolle_gb_menu_counter_str + ')' );
					}

				} else {
					// Error or unexpected answer...
					jQuery( 'tr#entry_' + entry_id + ' .gwolle_gb_ajax a' ).append(' (error)');
					event.preventDefault();
					return;
				}

				// Hide Ajax icon again
				jQuery( 'tr#entry_' + entry_id + ' .gwolle_gb_ajax' ).css('display', 'none');
			});

			event.preventDefault();
		});


		// Page-editor.php Admin page Click events
		jQuery( '#gwolle_gb_editor .gwolle_gb_actions a' ).on( 'click', function(event) {

			// Do not do anything here...
			var parent_class = jQuery(this).parent().hasClass('gwolle_gb_ajax');
			if (parent_class) {
				//console.log('gwolle_gb_ajax');
				return;
			}


			// Set up data to send
			var a_el_id = jQuery(this).attr('id');
			var entry_id = a_el_id;
			entry_id = entry_id.replace(/uncheck_/,'');
			entry_id = entry_id.replace(/check_/,'');
			entry_id = entry_id.replace(/unspam_/,'');
			entry_id = entry_id.replace(/spam_/,'');
			entry_id = entry_id.replace(/untrash_/,'');
			entry_id = entry_id.replace(/trash_/,'');
			var setter = a_el_id.replace(new RegExp( "_" + entry_id, "g"), '');

			var data = {
				action: 'gwolle_gb_ajax',
				security: '<?php echo esc_attr( $ajax_nonce ); ?>',
				id: entry_id,
				setter: setter,
			};


			// Set Ajax icon on visible
			jQuery( '.gwolle_gb_ajax' ).css('display', 'inline-block');


			// Do the actual request
			jQuery.post( ajaxurl, data, function( response ) {
				response = response.trim();

				// Set classes accordingly
				if ( response === setter ) { // We got what we wanted

					// Countdown counter in admin menu, toolbar
					if ( jQuery( '.gwolle_gb_actions' ).hasClass('unchecked') && jQuery( '.gwolle_gb_actions' ).hasClass('nospam') && jQuery( '.gwolle_gb_actions' ).hasClass('notrash') ) {
						var gwolle_gb_menu_counter = jQuery('li#toplevel_page_gwolle-gb-gwolle-gb a.menu-top span.awaiting-mod span').text();
						var old_gwolle_gb_menu_counter = new Number( gwolle_gb_menu_counter );
						var new_gwolle_gb_menu_counter = old_gwolle_gb_menu_counter - 1;

						jQuery('li#toplevel_page_gwolle-gb-gwolle-gb span.awaiting-mod span').text( new_gwolle_gb_menu_counter );
						jQuery('li#wp-admin-bar-gwolle-gb span.awaiting-mod.pending-count').text( new_gwolle_gb_menu_counter );
					}

					switch ( response ) {
						// Possible classes: nospam notrash checked visible spam trash unchecked invisible
						case 'uncheck':
							jQuery( '.entry-icons' ).addClass('unchecked').removeClass('checked');
							jQuery( '.gwolle_gb_actions' ).addClass('unchecked').removeClass('checked');
							jQuery( 'input#ischecked' ).prop('checked', false);
							break;
						case 'check':
							jQuery( '.entry-icons' ).addClass('checked').removeClass('unchecked');
							jQuery( '.gwolle_gb_actions' ).addClass('checked').removeClass('unchecked');
							jQuery( 'input#ischecked' ).prop('checked', true);
							break;
						case 'unspam':
							jQuery( '.entry-icons' ).addClass('nospam').removeClass('spam');
							jQuery( '.gwolle_gb_actions' ).addClass('nospam').removeClass('spam');
							jQuery( 'input#isspam' ).prop('checked', false);
							break;
						case 'spam':
							jQuery( '.entry-icons' ).addClass('spam').removeClass('nospam');
							jQuery( '.gwolle_gb_actions' ).addClass('spam').removeClass('nospam');
							jQuery( 'input#isspam' ).prop('checked', true);
							break;
						case 'untrash':
							jQuery( '.entry-icons' ).addClass('notrash').removeClass('trash');
							jQuery( '.gwolle_gb_actions' ).addClass('notrash').removeClass('trash');
							jQuery( 'input#istrash' ).prop('checked', false);

							jQuery( 'input#remove' ).prop('checked', false);
							jQuery( 'label.gwolle_gb_remove' ).addClass('gwolle-gb-hide');
							break;
						case 'trash':
							jQuery( '.entry-icons' ).addClass('trash').removeClass('notrash');
							jQuery( '.gwolle_gb_actions' ).addClass('trash').removeClass('notrash');
							jQuery( 'input#istrash' ).prop('checked', true);

							jQuery( 'label.gwolle_gb_remove' ).removeClass('gwolle-gb-hide');
							break;
						}

					// Set to visible or invisible respectively
					if ( jQuery( '.gwolle_gb_actions' ).hasClass('checked') && jQuery( '.gwolle_gb_actions' ).hasClass('nospam') && jQuery( '.gwolle_gb_actions' ).hasClass('notrash') ) {
						jQuery( '.entry-icons' ).addClass('visible').removeClass('invisible');
						jQuery( '.gwolle_gb_actions' ).addClass('visible').removeClass('invisible');
						jQuery( '.h3-invisible' ).css('display', 'none');
						jQuery( '.h3-visible' ).css('display', 'block');
					} else {
						jQuery( '.entry-icons' ).addClass('invisible').removeClass('visible');
						jQuery( '.gwolle_gb_actions' ).addClass('invisible').removeClass('visible');
						jQuery( '.h3-visible' ).css('display', 'none');
						jQuery( '.h3-invisible' ).css('display', 'block');
					}

					// Countup counter in admin menu, toolbar
					if ( jQuery( '.gwolle_gb_actions' ).hasClass('unchecked') && jQuery( '.gwolle_gb_actions' ).hasClass('nospam') && jQuery( '.gwolle_gb_actions' ).hasClass('notrash') ) {
						var gwolle_gb_menu_counter = jQuery('li#toplevel_page_gwolle-gb-gwolle-gb a.menu-top span.awaiting-mod span').text();
						var old_gwolle_gb_menu_counter = new Number( gwolle_gb_menu_counter );
						var new_gwolle_gb_menu_counter = old_gwolle_gb_menu_counter + 1;

						jQuery('li#toplevel_page_gwolle-gb-gwolle-gb span.awaiting-mod span').text( new_gwolle_gb_menu_counter );
						jQuery('li#wp-admin-bar-gwolle-gb span.awaiting-mod.pending-count').text( new_gwolle_gb_menu_counter );
					}

				} else {
					// Error or unexpected answer...
					jQuery( '.gwolle_gb_ajax a' ).append(' (error)');
					event.preventDefault();
					return;
				}

				// Hide Ajax icon again
				jQuery( '.gwolle_gb_ajax' ).css('display', 'none');
			});

			event.preventDefault();
		});


		// Dashboard Widget Click events
		jQuery( '#gwolle_gb_dashboard .row-actions a' ).on( 'click', function(event) {

			// Do not do anything here...
			var parent_class = jQuery(this).parent().hasClass('gwolle_gb_edit');
			if (parent_class) {
				//console.log('gwolle_gb_edit');
				return;
			}
			var parent_class = jQuery(this).parent().hasClass('gwolle_gb_ajax');
			if (parent_class) {
				//console.log('gwolle_gb_ajax');
				return;
			}


			// Set up data to send
			var a_el_id = jQuery(this).attr('id');
			var entry_id = a_el_id;
			entry_id = entry_id.replace(/check_/,'');
			entry_id = entry_id.replace(/spam_/,'');
			entry_id = entry_id.replace(/trash_/,'');
			var setter = a_el_id.replace(new RegExp( "_" + entry_id, "g"), '');

			var data = {
				action: 'gwolle_gb_ajax',
				security: '<?php echo esc_attr( $ajax_nonce ); ?>',
				id: entry_id,
				setter: setter,
			};


			// Set Ajax icon on visible
			jQuery( '.gwolle-gb-dashboard div#entry_' + entry_id + ' .gwolle_gb_ajax' ).css('display', 'inline-block');


			// Do the actual request
			jQuery.post( ajaxurl, data, function( response ) {
				response = response.trim();

				if ( response === setter ) { // We got what we wanted
					// Remove entry from widget
					jQuery( '.gwolle-gb-dashboard div#entry_' + entry_id ).slideUp();
				} else {
					// Error or unexpected answer...
					jQuery( '.gwolle-gb-dashboard div#entry_' + entry_id + ' .gwolle_gb_ajax a' ).append(' (error)');
				}
			});

			event.preventDefault();
		});

	});
	</script>
	<?php
}


/*
 * Callback function for handling the Ajax requests that are generated from the JavaScript above in gwolle_gb_ajax_javascript
 */
add_action( 'wp_ajax_gwolle_gb_ajax', 'gwolle_gb_ajax_callback' );
function gwolle_gb_ajax_callback() {

	if ( ! current_user_can('moderate_comments') ) {
		echo 'error';
		die();
	}

	check_ajax_referer( 'gwolle_gb_ajax', 'security' );

	/* Check Nonce */
	$verified = false;
	if ( isset($_POST['security']) ) {
		$verified = wp_verify_nonce( $_POST['security'], 'gwolle_gb_ajax' );
	}
	if ( $verified === false ) {
		// Nonce is invalid.
		esc_html_e('The Nonce did not validate. Please reload the page and try again.', 'gwolle-gb');
		die();
	}

	if (isset($_POST['id'])) {
		$id = (int) $_POST['id'];
	}
	if (isset($_POST['setter'])) {
		$setter = (string) $_POST['setter'];
	}


	if ( isset($id) && $id > 0 && isset($setter) && strlen($setter) > 0) {
		$entry = new gwolle_gb_entry();
		$result = $entry->load( $id );
		if ( ! $result ) {
			echo 'error, no such entry.';
			die();
		}


		switch ($setter) {
			case 'uncheck':
				if ( $entry->get_ischecked() === 1 ) {
					$entry->set_ischecked( false );
					$result = $entry->save();
					if ($result ) {
						$response = 'uncheck';
						gwolle_gb_add_log_entry( $entry->get_id(), 'entry-unchecked' );
					} else {
						$response = 'error';
					}
				} else {
					$response = 'nochange';
				}
				break;

			case 'check':
				if ( $entry->get_ischecked() === 0 ) {
					$entry->set_ischecked( true );
					$user_id = get_current_user_id(); // returns 0 if no current user
					$entry->set_checkedby( $user_id );
					$result = $entry->save();
					if ($result ) {
						$response = 'check';
						gwolle_gb_add_log_entry( $entry->get_id(), 'entry-checked' );
						gwolle_gb_mail_author_on_moderation( $entry );
					} else {
						$response = 'error';
					}
				} else {
					$response = 'nochange';
				}
				break;

			case 'unspam':
				if ( $entry->get_isspam() === 1 ) {
					$entry->set_isspam( false );
					$result = $entry->save();
					if ($result ) {
						$response = 'unspam';
						gwolle_gb_add_log_entry( $entry->get_id(), 'marked-as-not-spam' );
						gwolle_gb_mail_author_on_moderation( $entry );
						gwolle_gb_akismet( $entry, 'submit-ham' );
					} else {
						$response = 'error';
					}
				} else {
					$response = 'nochange';
				}
				break;

			case 'spam':
				if ( $entry->get_isspam() === 0 ) {
					$entry->set_isspam( true );
					$result = $entry->save();
					if ($result ) {
						$response = 'spam';
						gwolle_gb_add_log_entry( $entry->get_id(), 'marked-as-spam' );
						gwolle_gb_akismet( $entry, 'submit-spam' );
					} else {
						$response = 'error';
					}
				} else {
					$response = 'nochange';
				}
				break;

			case 'untrash':
				if ( $entry->get_istrash() === 1 ) {
					$entry->set_istrash( false );
					$result = $entry->save();
					if ($result ) {
						$response = 'untrash';
						gwolle_gb_add_log_entry( $entry->get_id(), 'entry-untrashed' );
						gwolle_gb_mail_author_on_moderation( $entry );
					} else {
						$response = 'error';
					}
				} else {
					$response = 'nochange';
				}
				break;

			case 'trash':
				if ( $entry->get_istrash() === 0 ) {
					$entry->set_istrash( true );
					$result = $entry->save();
					if ($result ) {
						$response = 'trash';
						gwolle_gb_add_log_entry( $entry->get_id(), 'entry-trashed' );
					} else {
						$response = 'error';
					}
				} else {
					$response = 'nochange';
				}
				break;

			default:
				$response = 'nochange';
				break;
		}

		do_action( 'gwolle_gb_save_entry_admin', $entry );

	} else {
		$response = 'error';
	}

	echo $response;
	die(); // this is required to return a proper result

}
