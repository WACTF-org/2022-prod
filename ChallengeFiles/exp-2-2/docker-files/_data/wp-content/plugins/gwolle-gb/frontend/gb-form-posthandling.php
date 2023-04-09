<?php

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Handle the $_POST for the Frontend on a new entry.
 * Use the 'wp' action, since global $post is populated and we can use get_the_ID().
 */
function gwolle_gb_handle_post() {
	if ( ! is_admin() ) {
		// Frontend Handling of $_POST, only one form
		if ( isset($_POST['gwolle_gb_function']) && $_POST['gwolle_gb_function'] === 'add_entry' ) {
			gwolle_gb_frontend_posthandling();
		}
	}
}
add_action('wp', 'gwolle_gb_handle_post');


/*
 * Save new entries to the database, when valid.
 * Handle $_POST and check and save entry.
 *
 * global vars used:
 * $gwolle_gb_formdata: the data that was submitted, and will be used to fill the form for resubmit.
 *
 * @return int entry->ID when saved.
 * @return bool false when not saved, and should return the form with an error.
 */
function gwolle_gb_frontend_posthandling() {

	if ( isset($_POST['gwolle_gb_function']) && $_POST['gwolle_gb_function'] === 'add_entry' ) {

		// Option to allow only logged-in users to post. Don't show the form if not logged-in.
		if ( ! is_user_logged_in() && get_option('gwolle_gb-require_login', 'false') === 'true' ) {
			gwolle_gb_add_message( '<p class="require_login"><strong>' . esc_html__('Submitting a new guestbook entry is only allowed for logged-in users.', 'gwolle-gb') . '</strong></p>', true, false);
			return false;
		}


		/*
		 * Collect data from the Form
		 */
		$gwolle_gb_formdata = array(); // used to set the data in the entry
		$form_setting = gwolle_gb_get_setting( 'form' );

		/* Name */
		if ( isset($form_setting['form_name_enabled']) && $form_setting['form_name_enabled'] === 'true' ) {
			$field_name = gwolle_gb_get_field_name( 'name' );
			if (isset($_POST["$field_name"])) {
				$author_name = trim($_POST["$field_name"]);
				$author_name = gwolle_gb_maybe_encode_emoji( $author_name, 'author_name' );
				$gwolle_gb_formdata['author_name'] = $author_name;
				gwolle_gb_add_formdata( 'author_name', $author_name );
				if ( $author_name === '' ) {
					if ( isset($form_setting['form_name_mandatory']) && $form_setting['form_name_mandatory'] === 'true' ) {
						gwolle_gb_add_message( '<p class="error_fields gb-error-fields"><strong>' . esc_html__('Your name is not filled in, even though it is mandatory.', 'gwolle-gb') . '</strong></p>', true, $field_name); // mandatory
					}
				}
			} else {
				if ( isset($form_setting['form_name_mandatory']) && $form_setting['form_name_mandatory'] === 'true' ) {
					gwolle_gb_add_message( '<p class="error_fields gb-error-fields"><strong>' . esc_html__('Your name is not filled in, even though it is mandatory.', 'gwolle-gb') . '</strong></p>', true, $field_name); // mandatory
				}
			}
		}

		/* City / Origin */
		if ( isset($form_setting['form_city_enabled']) && $form_setting['form_city_enabled'] === 'true' ) {
			$field_name = gwolle_gb_get_field_name( 'city' );
			if (isset($_POST["$field_name"])) {
				$author_origin = trim($_POST["$field_name"]);
				$author_origin = gwolle_gb_maybe_encode_emoji( $author_origin, 'author_origin' );
				$gwolle_gb_formdata['author_origin'] = $author_origin;
				gwolle_gb_add_formdata( 'author_origin', $author_origin );
				if ( $author_origin === '' ) {
					if ( isset($form_setting['form_city_mandatory']) && $form_setting['form_city_mandatory'] === 'true' ) {
						gwolle_gb_add_message( '<p class="error_fields gb-error-fields"><strong>' . esc_html__('Your origin is not filled in, even though it is mandatory.', 'gwolle-gb') . '</strong></p>', true, $field_name); // mandatory
					}
				}
			} else {
				if ( isset($form_setting['form_city_mandatory']) && $form_setting['form_city_mandatory'] === 'true' ) {
					gwolle_gb_add_message( '<p class="error_fields gb-error-fields"><strong>' . esc_html__('Your origin is not filled in, even though it is mandatory.', 'gwolle-gb') . '</strong></p>', true, $field_name); // mandatory
				}
			}
		}

		/* Email */
		if ( isset($form_setting['form_email_enabled']) && $form_setting['form_email_enabled'] === 'true' ) {
			$field_name = gwolle_gb_get_field_name( 'email' );
			if (isset($_POST["$field_name"])) {
				$author_email = trim($_POST["$field_name"]);
				$gwolle_gb_formdata['author_email'] = $author_email;
				gwolle_gb_add_formdata( 'author_email', $author_email );
				if ( filter_var( $author_email, FILTER_VALIDATE_EMAIL ) ) {
					// Valid Email address.
				} else if ( isset($form_setting['form_email_mandatory']) && $form_setting['form_email_mandatory'] === 'true' ) {
					gwolle_gb_add_message( '<p class="error_fields gb-error-fields"><strong>' . esc_html__('Your e-mail address is not filled in correctly, even though it is mandatory.', 'gwolle-gb') . '</strong></p>', true, $field_name); // mandatory
				}
			} else {
				if ( isset($form_setting['form_email_mandatory']) && $form_setting['form_email_mandatory'] === 'true' ) {
					gwolle_gb_add_message( '<p class="error_fields gb-error-fields"><strong>' . esc_html__('Your e-mail address is not filled in correctly, even though it is mandatory.', 'gwolle-gb') . '</strong></p>', true, $field_name); // mandatory
				}
			}
		} else {
			if (isset($_POST['gwolle_gb_author_email'])) {
				$author_email = trim($_POST['gwolle_gb_author_email']);
				$gwolle_gb_formdata['author_email'] = $author_email;
				gwolle_gb_add_formdata( 'author_email', $author_email );
			}
		}

		/* Website / Homepage */
		if ( isset($form_setting['form_homepage_enabled']) && $form_setting['form_homepage_enabled'] === 'true' ) {
			$field_name = gwolle_gb_get_field_name( 'website' );
			if (isset($_POST["$field_name"])) {
				$author_website = trim($_POST["$field_name"]);
				$gwolle_gb_formdata['author_website'] = $author_website;
				gwolle_gb_add_formdata( 'author_website', $author_website );
				$pattern = '/^http/';
				if ( ! preg_match($pattern, $author_website, $matches) ) {
					$author_website = 'http://' . $author_website;
				}
				if ( filter_var( $author_website, FILTER_VALIDATE_URL ) ) {
					// Valid Website URL.
				} else if ( isset($form_setting['form_homepage_mandatory']) && $form_setting['form_homepage_mandatory'] === 'true' ) {
					gwolle_gb_add_message( '<p class="error_fields gb-error-fields"><strong>' . esc_html__('Your website is not filled in, even though it is mandatory.', 'gwolle-gb') . '</strong></p>', true, $field_name); // mandatory
				}
			} else {
				if ( isset($form_setting['form_homepage_mandatory']) && $form_setting['form_homepage_mandatory'] === 'true' ) {
					gwolle_gb_add_message( '<p class="error_fields gb-error-fields"><strong>' . esc_html__('Your website is not filled in, even though it is mandatory.', 'gwolle-gb') . '</strong></p>', true, $field_name); // mandatory
				}
			}
		}

		/* Message */
		if ( isset($form_setting['form_message_enabled']) && $form_setting['form_message_enabled'] === 'true' ) {
			$field_name = gwolle_gb_get_field_name( 'content' );
			if (isset($_POST["$field_name"])) {
				$content = trim($_POST["$field_name"]);
				if ( $content === '' ) {
					if ( isset($form_setting['form_message_mandatory']) && $form_setting['form_message_mandatory'] === 'true' ) {
						gwolle_gb_add_message( '<p class="error_fields gb-error-fields"><strong>' . esc_html__('There is no message, even though it is mandatory.', 'gwolle-gb') . '</strong></p>', true, $field_name); // mandatory
					}
				} else {
					$content = gwolle_gb_maybe_encode_emoji( $content, 'content' );
					$gwolle_gb_formdata['content'] = $content;
					gwolle_gb_add_formdata( 'content', $content );
				}
			} else {
				if ( isset($form_setting['form_message_mandatory']) && $form_setting['form_message_mandatory'] === 'true' ) {
					gwolle_gb_add_message( '<p class="error_fields gb-error-fields"><strong>' . esc_html__('There is no message, even though it is mandatory.', 'gwolle-gb') . '</strong></p>', true, $field_name); // mandatory
				}
			}
		}

		/* Custom Anti-Spam */
		if ( isset($form_setting['form_antispam_enabled']) && $form_setting['form_antispam_enabled'] === 'true' ) {
			$field_name = gwolle_gb_get_field_name( 'custom' );
			$antispam_question = gwolle_gb_sanitize_output( get_option('gwolle_gb-antispam-question') );
			$antispam_answer   = gwolle_gb_sanitize_output( get_option('gwolle_gb-antispam-answer') );

			if ( isset($antispam_question) && strlen($antispam_question) > 0 && isset($antispam_answer) && strlen($antispam_answer) > 0 ) {
				if ( isset($_POST["$field_name"]) && trim($_POST["$field_name"]) === trim($antispam_answer) ) {
					//echo "You got it!";
				} else {
					gwolle_gb_add_message( '<p class="error_fields gb-error-fields"><strong>' . esc_html__('The anti-spam question was not answered correctly, even though it is mandatory.', 'gwolle-gb') . '</strong></p>', true, $field_name); // mandatory
				}
			}
			if ( isset($_POST["$field_name"]) ) {
				$antispam = trim($_POST["$field_name"]);
				$gwolle_gb_formdata['antispam_answer'] = $antispam;
				gwolle_gb_add_formdata( 'antispam_answer', $antispam );
			}
		}

		/* Privacy checkbox for GDPR compliance. */
		if ( isset($form_setting['form_privacy_enabled']) && $form_setting['form_privacy_enabled'] === 'true' ) {
			if (isset($_POST['gwolle_gb_privacy']) && $_POST['gwolle_gb_privacy'] === 'on') {
				gwolle_gb_add_formdata( 'gwolle_gb_privacy', 'on' );
			} else {
				gwolle_gb_add_message( '<p class="error_fields gb-error-fields"><strong>' . esc_html__('You did not accept the privacy policy, even though it is mandatory.', 'gwolle-gb') . '</strong></p>', true, 'gwolle_gb_privacy'); // mandatory
			}
		}


		/* New Instance of gwolle_gb_entry. */
		$entry = new gwolle_gb_entry();

		/* Set the data in the instance */
		$set_data = $entry->set_data( $gwolle_gb_formdata );
		if ( ! $set_data ) {
			// Data is not set in the Instance, something happened
			do_action( 'gwolle_gb_notsaved_entry_frontend', $entry );
			gwolle_gb_add_message( '<p class="set_data gb-set-data"><strong>' . esc_html__('There were errors submitting your guestbook entry.', 'gwolle-gb') . '</strong></p>', true, false );
			return false;
		}


		/* If Moderation is off, set it to "ischecked"
		 * Do this before the gwolle_gb_new_entry_frontend hook, so we can change it again if a hook needs to.
		 */
		$user_id = get_current_user_id(); // Returns 0 if no current user.
		if ( get_option('gwolle_gb-moderate-entries', 'true') === 'true' ) {
			// Moderation, only set to checked for moderators.
			if ( gwolle_gb_is_moderator($user_id) ) {
				$entry->set_ischecked( true );
			} else {
				$entry->set_ischecked( false );
			}
		} else {
			// No moderation, set to checked.
			$entry->set_ischecked( true );
		}


		/*
		 * Use this hook to do your own magic on the frontend submission.
		 *
		 * @since 3.1.2
		 *
		 * @param object $entry instance of gwolle_gb_entry.
		 */
		$entry = apply_filters( 'gwolle_gb_new_entry_frontend', $entry );


		/* If there are errors, stop here and return false */
		$gwolle_gb_errors = gwolle_gb_get_errors();
		if ( $gwolle_gb_errors ) {
			do_action( 'gwolle_gb_notsaved_entry_frontend', $entry );
			return false; // no need to check and save
		}


		/* Akismet: check for spam and set accordingly */
		$marked_by_akismet = false;
		if ( get_option( 'gwolle_gb-akismet-active', 'false' ) === 'true' ) {
			$isspam = gwolle_gb_akismet( $entry, 'comment-check' );
			if ( $isspam ) {
				// Returned true, so considered spam
				$entry->set_isspam(true);
				$marked_by_akismet = true;
				if (get_option( 'gwolle_gb-refuse-spam', 'false') === 'true') {
					gwolle_gb_add_message( '<p class="refuse-spam-akismet"><strong>' . esc_html__('Your entry was marked as spam. Please try again.', 'gwolle-gb') . '</strong></p>', true, false );
					do_action( 'gwolle_gb_notsaved_entry_frontend', $entry );
					return false;
				}
			}
		}

		/* Stop Forum Spam: check for spam and set accordingly */
		$marked_by_sfs = false;
		if ( get_option( 'gwolle_gb-sfs', 'false') === 'true' ) {
			$isspam = gwolle_gb_stop_forum_spam( $entry );
			if ( $isspam ) {
				// Returned true, so considered spam
				$entry->set_isspam(true);
				$marked_by_sfs = true;
				if (get_option( 'gwolle_gb-refuse-spam', 'false') === 'true') {
					gwolle_gb_add_message( '<p class="refuse-spam-sfs"><strong>' . esc_html__('Your entry was marked as spam. Please try again.', 'gwolle-gb') . '</strong></p>', true, false );
					do_action( 'gwolle_gb_notsaved_entry_frontend', $entry );
					return false;
				}
			}
		}


		/* Honeypot: check for spam and set accordingly. */
		$marked_by_honeypot = false;
		if (get_option( 'gwolle_gb-honeypot', 'true') === 'true') {
			$field_name = gwolle_gb_get_field_name( 'honeypot' );
			$field_name2 = gwolle_gb_get_field_name( 'honeypot2' );
			$honeypot_value = (int) get_option( 'gwolle_gb-honeypot_value', 15 );
			if ( isset($_POST["$field_name"]) && strlen($_POST["$field_name"]) > 0 ) {
				// Input field was filled in, so considered spam
				$entry->set_isspam(true);
				$marked_by_honeypot = true;
				if (get_option( 'gwolle_gb-refuse-spam', 'false') === 'true') {
					gwolle_gb_add_message( '<p class="refuse-spam-honeypot"><strong>' . esc_html__('Your entry was marked as spam. Please try again.', 'gwolle-gb') . '</strong></p>', true, false );
					do_action( 'gwolle_gb_notsaved_entry_frontend', $entry );
					return false;
				}
			}
			if ( ! isset($_POST["$field_name2"]) || (int) $_POST["$field_name2"] !== $honeypot_value ) {
				// Input field was not filled in correctly, so considered spam
				$entry->set_isspam(true);
				$marked_by_honeypot = true;
				if (get_option( 'gwolle_gb-refuse-spam', 'false') === 'true') {
					gwolle_gb_add_message( '<p class="refuse-spam-honeypot2"><strong>' . esc_html__('Your entry was marked as spam. Please try again.', 'gwolle-gb') . '</strong></p>', true, false );
					do_action( 'gwolle_gb_notsaved_entry_frontend', $entry );
					return false;
				}
			}
		}


		/* Nonce: check for spam and set accordingly. */
		$marked_by_nonce = false;
		if (get_option( 'gwolle_gb-nonce', 'true') === 'true') {
			$field_name = gwolle_gb_get_field_name( 'nonce' );
			$verified = wp_verify_nonce( $_POST["$field_name"], 'gwolle_gb_add_entry' );
			if ( $verified === false ) {
				// Nonce is invalid, so considered spam
				$entry->set_isspam(true);
				$marked_by_nonce = true;
				if (get_option( 'gwolle_gb-refuse-spam', 'false') === 'true') {
					gwolle_gb_add_message( '<p class="refuse-spam-nonce"><strong>' . esc_html__('Your entry was marked as spam. Please try again.', 'gwolle-gb') . '</strong></p>', true, false );
					do_action( 'gwolle_gb_notsaved_entry_frontend', $entry );
					return false;
				}
			}
		}


		/* Scan for long and abusive text. */
		$marked_by_longtext = false;
		if ( get_option( 'gwolle_gb-longtext', 'true') === 'true' ) {
			// Check for abusive content (too long words). Set it to unchecked, so manual moderation is needed.
			$maxlength = 100;
			$words = explode( ' ', $entry->get_content() );
			foreach ( $words as $word ) {
				$pattern = '/^href=http/';
				if ( preg_match($pattern, $word, $matches) ) {
					continue;
				}
				$pattern = '/^href=ftp/';
				if ( preg_match($pattern, $word, $matches) ) {
					continue;
				}
				$pattern = '/^\[img\]http/';
				if ( preg_match($pattern, $word, $matches) ) {
					continue;
				}
				if ( strlen($word) > $maxlength ) {
					$entry->set_ischecked( false );
					$marked_by_longtext = true;
					break;
				}
			}
			$maxlength = 60;
			$words = explode( ' ', $entry->get_author_name() );
			foreach ( $words as $word ) {
				if ( strlen($word) > $maxlength ) {
					$entry->set_ischecked( false );
					$marked_by_longtext = true;
					break;
				}
			}
		}


		/* Scan with Link Checker. */
		$marked_by_linkchecker = false;
		$counter_for_linkchecker = 0;
		if ( get_option( 'gwolle_gb-linkchecker', 'true') === 'true' ) {
			$words = explode( ' ', $entry->get_content() );
			foreach ( $words as $word ) {
				$pattern = '/(http|https)\:\/\/?/';
				if ( preg_match( $pattern, $word, $matches ) ) {
					// Match
					$counter_for_linkchecker++;
				}
			}
			if ( $counter_for_linkchecker > 1 ) {
				$entry->set_isspam( true );
				$marked_by_linkchecker = true;
				if (get_option( 'gwolle_gb-refuse-spam', 'false') === 'true') {
					gwolle_gb_add_message( '<p class="refuse-spam-linkchecker"><strong>' . esc_html__('Your entry was marked as spam. Please try again.', 'gwolle-gb') . '</strong></p>', true, false );
					do_action( 'gwolle_gb_notsaved_entry_frontend', $entry );
					return false;
				}
			}
		}


		/* Form Timeout: check for spam and set accordingly. */
		$marked_by_timeout = false;
		if (get_option( 'gwolle_gb-timeout', 'true') === 'true') {
			$field_name = gwolle_gb_get_field_name( 'timeout' );
			$field_name2 = gwolle_gb_get_field_name( 'timeout2' );
			if ( isset($_POST["$field_name"]) && strlen($_POST["$field_name"]) > 0 && isset($_POST["$field_name2"]) && strlen($_POST["$field_name2"]) > 0 ) {
				// Input fields were filled in, so continue.
				$timeout  = (int) $_POST["$field_name"];
				$timeout2 = (int) $_POST["$field_name2"];
				if ( ( $timeout2 - $timeout ) < 2 ) {
					// Submitted less then 1 second after loading. Considered spam.
					$entry->set_isspam(true);
					$marked_by_timeout = true;
					if (get_option( 'gwolle_gb-refuse-spam', 'false') === 'true') {
						gwolle_gb_add_message( '<p class="refuse-spam-timeout"><strong>' . esc_html__('Your entry was submitted too fast, please slow down and try again.', 'gwolle-gb') . '</strong></p>', true, false );
						do_action( 'gwolle_gb_notsaved_entry_frontend', $entry );
						return false;
					}
				}
			} else {
				// Input fields were not filled in correctly. Considered spam.
				$entry->set_isspam(true);
				$marked_by_timeout = true;
				if (get_option( 'gwolle_gb-refuse-spam', 'false') === 'true') {
					gwolle_gb_add_message( '<p class="refuse-spam-timeout"><strong>' . esc_html__('Your entry was marked as spam. Please try again.', 'gwolle-gb') . '</strong></p>', true, false );
					do_action( 'gwolle_gb_notsaved_entry_frontend', $entry );
					return false;
				}
			}
		}


		/* Check for logged in user, and set the userid as author_id, just in case someone is also admin, or gets promoted some day. */
		$entry->set_author_id( $user_id );


		/*
		 * Network Information
		 */
		$set_author_ip = (bool) apply_filters( 'gwolle_gb_set_author_ip', true );
		$set_author_ip2 = get_option('gwolle_gb-store_ip', 'true');
		if ( $set_author_ip && ( $set_author_ip2 === 'true' ) ) {
			$entry->set_author_ip( gwolle_gb_get_user_ip() );
			$entry->set_author_host( gethostbyaddr( gwolle_gb_get_user_ip() ) );
		}


		/*
		 * Book ID
		 */
		if ( isset( $_POST['gwolle_gb_book_id'] ) ) {
			$book_id = (int) $_POST['gwolle_gb_book_id'];
			gwolle_gb_add_formdata( 'book_id', $book_id );
		}
		if ( $book_id < 1 ) {
			$book_id = 1;
			gwolle_gb_add_formdata( 'book_id', $book_id );
		}
		$entry->set_book_id( $book_id );


		/*
		 * Save the Entry
		 */
		$save = $entry->save();

		//if ( WP_DEBUG ) { echo "save: "; var_dump($save); }


		if ( $save ) {
			// We have been saved to the Database.

			gwolle_gb_add_message( '<p class="entry_saved gb-entry-saved">' . esc_html__('Thank you for your entry.', 'gwolle-gb') . '</p>', false, false );
			if ( $entry->get_ischecked() === 0 || $entry->get_isspam() === 1 ) {
				gwolle_gb_add_message( '<p>' . esc_html__('We will review it and unlock it in a short while.', 'gwolle-gb') . '</p>', false, false );
			}


			/*
			 * No initial Log for the Entry needed, it has a default post date in the Entry itself.
			 * Only log when something specific happened:
			 */
			if ( $marked_by_akismet ) {
				gwolle_gb_add_log_entry( $entry->get_id(), 'marked-by-akismet' );
			}
			if ( $marked_by_sfs ) {
				gwolle_gb_add_log_entry( $entry->get_id(), 'marked-by-sfs' );
			}
			if ( $marked_by_honeypot ) {
				gwolle_gb_add_log_entry( $entry->get_id(), 'marked-by-honeypot' );
			}
			if ( $marked_by_nonce ) {
				gwolle_gb_add_log_entry( $entry->get_id(), 'marked-by-nonce' );
			}
			if ( $marked_by_longtext ) {
				gwolle_gb_add_log_entry( $entry->get_id(), 'marked-by-longtext' );
			}
			if ( $marked_by_linkchecker ) {
				gwolle_gb_add_log_entry( $entry->get_id(), 'marked-by-linkchecker' );
			}
			if ( $marked_by_timeout ) {
				gwolle_gb_add_log_entry( $entry->get_id(), 'marked-by-timeout' );
			}


			/* Privacy checkbox for GDPR compliance added to log. */
			if (isset($_POST['gwolle_gb_privacy']) && $_POST['gwolle_gb_privacy'] === 'on') {
				gwolle_gb_add_log_entry( $entry->get_id(), 'privacy-policy-accepted' );
			}


			/*
			 * Hooks gwolle_gb_clear_cache(), gwolle_gb_mail_moderators() and gwolle_gb_mail_author().
			 */
			do_action( 'gwolle_gb_save_entry_frontend', $entry );

			return $entry->get_id();

		} else {
			// We have not been saved to the Database.

			do_action( 'gwolle_gb_notsaved_entry_frontend', $entry );

			gwolle_gb_add_message( '<p class="entry_notsaved gb-entry-notsaved">' . esc_html__('Sorry, something went wrong with saving your entry. Please contact a site admin.', 'gwolle-gb') . '</p>', true, false );

			return false;

		}
	}
}


/*
 * Check for double entry using email field and content.
 * If there is a double entry, do not save the message and return the form with an error.
 *
 * @since 4.0.7
 *
 * @param object $entry the guestbook entry that was submitted by the user.
 *
 * @return object $entry the guestbook entry that was submitted by the user.
 */
function gwolle_gb_check_double_entry( $entry ) {

	$form_setting = gwolle_gb_get_setting( 'form' );

	if ( isset($form_setting['form_message_mandatory']) && $form_setting['form_message_mandatory'] === 'true' ) {
		$entries = gwolle_gb_get_entries(array(
				'email'   => $entry->get_author_email(),
				'book_id' => $entry->get_book_id(),
			));
		if ( is_array( $entries ) && ! empty( $entries ) ) {
			$field_name = gwolle_gb_get_field_name( 'content' );
			foreach ( $entries as $entry_email ) {
				if ( $entry_email->get_content() === $entry->get_content() ) {
					// Match is double entry
					gwolle_gb_add_message( '<p class="double_post gb-double-post"><strong>' . esc_html__('Double post: An entry with the data you entered has already been saved.', 'gwolle-gb') . '</strong></p>', true, $field_name );
					return $entry;
				}
			}
		}
	}
	return $entry;

}
add_filter( 'gwolle_gb_new_entry_frontend', 'gwolle_gb_check_double_entry' );


/*
 * Check for max length in content textarea.
 * If there are too many characters, do not save the message and return the form with an error.
 *
 * @since 4.2.0
 *
 * @param object $entry the guestbook entry that was submitted by the user.
 *
 * @return object $entry the guestbook entry that was submitted by the user.
 */
function gwolle_gb_check_maxlength( $entry ) {

	$form_setting = gwolle_gb_get_setting( 'form' );

	if ( isset($form_setting['form_message_maxlength']) && is_numeric($form_setting['form_message_maxlength']) && $form_setting['form_message_maxlength'] > 0 ) {
		$form_message_maxlength = (int) $form_setting['form_message_maxlength'];
		$content = $entry->get_content();
		$used_characters = gwolle_gb_count_characters( $content );

		if ( $used_characters > $form_message_maxlength ) {
			// Content has too many characters.
			$field_name = gwolle_gb_get_field_name( 'content' );
			$message = sprintf( esc_html__('Too many characters: Allowed is %1$d characters, used is %2$d characters in content field.', 'gwolle-gb'), $form_message_maxlength, $used_characters );
			gwolle_gb_add_message( '<p class="gb-max-length"><strong>' . $message . '</strong></p>', true, $field_name );
			return $entry;
		}
	}
	return $entry;

}
add_filter( 'gwolle_gb_new_entry_frontend', 'gwolle_gb_check_maxlength' );

