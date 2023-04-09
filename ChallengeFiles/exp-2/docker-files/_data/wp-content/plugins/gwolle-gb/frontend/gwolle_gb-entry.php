<?php
/*
 * File: gwolle_gb-entry.php
 * Template with function: gwolle_gb_entry_template()
 *
 * By default this file will be loaded from /wp-content/plugins/gwolle-gb-frontend/gwolle_gb-entry.php.
 * If you place it in your childtheme or parenttheme, it will be overridden.
 * Make sure you only return values, and not to use echo statements.
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


if ( ! function_exists('gwolle_gb_entry_template') ) {
	/*
	 * Template file for s single guestbook entry.
	 *
	 * @param object $entry instance of gwolle_gb_entry.
	 * @param bool $first true if it is the first entry.
	 * @param int $counter the number of the entry. (since 1.4.7)
	 * @return: string, html with a single guestbook entry.
	 */
	function gwolle_gb_entry_template( $entry, $first, $counter ) {

		// Get the needed settings.
		$form_setting = gwolle_gb_get_setting( 'form' );
		$read_setting = gwolle_gb_get_setting( 'read' );

		// Main Author div
		$entry_output = '';

		$entry_class  = 'gb-entry';
		$entry_class .= ' gb-entry_' . $entry->get_id();
		$entry_class .= ' gb-entry-count_' . $counter;
		if ( is_int( $counter / 2 ) ) {
			$entry_class .= ' gwolle_gb_even gwolle-gb-even';
		} else {
			$entry_class .= ' gwolle_gb_uneven gwolle-gb-uneven';
		}
		if ( $first === true ) {
			$entry_class .= ' gwolle-gb-first';
		}

		if ( get_option( 'gwolle_gb-admin_style', 'false' ) === 'true' ) {
			$author_id = $entry->get_author_id();
			$is_moderator = gwolle_gb_is_moderator( $author_id );
			if ( $is_moderator ) {
				$entry_class .= ' admin-entry';
			}
		}
		$entry_class = apply_filters( 'gwolle_gb_entry_class', $entry_class );

		$entry_output .= '<div class="' . $entry_class . '" data-entry_id="' . (int) $entry->get_id() . '">';
		$entry_output .= '
				<article>';

		// Use this filter to just add something
		$entry_output .= apply_filters( 'gwolle_gb_entry_read_add_before', '', $entry );

		// Author Info
		$entry_output .= '
					<div class="gb-author-info">';

		// Author Avatar
		if ( isset($read_setting['read_avatar']) && $read_setting['read_avatar'] === 'true' ) {
			$avatar = get_avatar( $entry->get_author_email(), 32, '', $entry->get_author_name() );
			if ($avatar) {
				$avatar = str_replace('<img', '<img referrerpolicy="no-referrer"', $avatar);
				$entry_output .= '
						<span class="gb-author-avatar">' . $avatar . '</span>';
			}
		}

		// Author Name
		if ( isset($read_setting['read_name']) && $read_setting['read_name'] === 'true' ) {
			$author_name_html = gwolle_gb_get_author_name_html($entry);
			$entry_output .= '
						<span class="gb-author-name">' . $author_name_html . '
						</span>';
		}

		// Author Origin
		if ( isset($read_setting['read_city']) && $read_setting['read_city'] === 'true' ) {
			$origin = $entry->get_author_origin();
			if ( strlen(str_replace(' ', '', $origin)) > 0 ) {
				$entry_output .= '
						<span class="gb-author-origin">
							<span class="gb-author-origin-from-text"> ' . /* translators: city or origin */ esc_html__('from', 'gwolle-gb') . '</span>
							<span class="gb-author-origin-text"> ' . gwolle_gb_sanitize_output($origin) . '</span>
						</span>';
			}
		}

		// Entry Date and Time
		if ( ( isset($read_setting['read_datetime']) && $read_setting['read_datetime'] === 'true' ) || ( isset($read_setting['read_date']) && $read_setting['read_date'] === 'true' ) ) {
			$entry_output .= '
						<span class="gb-datetime">
							<span class="gb-date">';
			if ( isset($read_setting['read_name']) && $read_setting['read_name'] === 'true' ) {
				$entry_output .= '<span class="gb-date-wrote-text"> ' . /* translators: on a certain date */ esc_html__('wrote on', 'gwolle-gb') . '</span>';
			}
			$entry_output .= '<span class="gb-date-text"> ' . date_i18n( get_option('date_format'), $entry->get_datetime() ) . '</span>
							</span>';
			if ( isset($read_setting['read_datetime']) && $read_setting['read_datetime'] === 'true' ) {
				// Use 'at'. Follow WordPress Core: class-walker-comment.php
				$entry_output .= '<span class="gb-time">
									<span class="gb-time-at-text"> ' . /* translators: at a certain time */ esc_html__('at', 'gwolle-gb') . '</span>
									<span class="gb-time-text"> ' . trim(date_i18n( get_option('time_format'), $entry->get_datetime() )) . '</span>
								</span>';
			}
			$entry_output .= '
						</span> ';
		}

		$entry_output .= '
					</div>'; // end <div class="gb-author-info">

		// Main Content
		if ( isset($read_setting['read_content']) && $read_setting['read_content'] === 'true' ) {
			$entry_output .= '
					<div class="gb-entry-content">';

			// Use this filter to just add something.
			$entry_output .= apply_filters( 'gwolle_gb_entry_read_add_content_before', '', $entry );

			$real_content = gwolle_gb_sanitize_output( $entry->get_content(), 'content' );
			// This filters the real content of the entry.
			$entry_content = apply_filters( 'gwolle_gb_entry_the_content', $real_content, $entry );

			if ( get_option( 'gwolle_gb-showLineBreaks', 'false' ) === 'true' ) {
				$entry_content = nl2br($entry_content);
			}
			if ( isset($form_setting['form_bbcode_enabled']) && $form_setting['form_bbcode_enabled'] === 'true' ) {
				$entry_content = gwolle_gb_bbcode_parse($entry_content);
			} else {
				$entry_content = gwolle_gb_bbcode_strip($entry_content);
			}
			$excerpt_length = (int) get_option( 'gwolle_gb-excerpt_length', 0 );
			if ( $excerpt_length > 0 ) {
				$readmore = '... <a href="#" class="gwolle-gb-readmore" title="' . esc_attr__('Expand this entry and read more', 'gwolle-gb') . '">' . esc_html__('Read more', 'gwolle-gb') . '</a>';
				$entry_excerpt = wp_trim_words( $entry_content, $excerpt_length, $readmore );
				$entry_content = '
						<div class="gb-entry-excerpt">' . $entry_excerpt . '</div>
						<div class="gb-entry-full-content gwolle-gb-hide">' . $entry_content . '</div>';
			}
			if ( get_option('gwolle_gb-showSmilies', 'true') === 'true' ) {
				// should be done after wp_trim_words to keep all the smileys intact.
				$entry_content = convert_smilies($entry_content);
			}
			$entry_output .= $entry_content;

			// Use this filter to just add something
			$entry_output .= apply_filters( 'gwolle_gb_entry_read_add_content', '', $entry );

			$entry_output .= '
					</div>';

			/* Admin Reply */
			$admin_reply_content = gwolle_gb_sanitize_output( $entry->get_admin_reply(), 'admin_reply' );
			// This filters the real content of the admin reply.
			$admin_reply_content = apply_filters( 'gwolle_gb_entry_the_admin_reply', $admin_reply_content, $entry );

			if ( $admin_reply_content !== '' ) {

				$class = '';
				if ( get_option( 'gwolle_gb-admin_style', 'false' ) === 'true' ) {
					$class = ' admin-entry';
				}

				$admin_reply = '
					<div class="gb-entry-admin_reply' . $class . '">';

				/* Admin Reply Author */
				$admin_reply .= '
						<div class="gb-admin_reply_uid gb-admin-reply-uid">';
				$admin_reply_name = gwolle_gb_is_moderator( $entry->get_admin_reply_uid() );
				/* Admin Avatar */
				if ( isset($read_setting['read_aavatar']) && $read_setting['read_aavatar'] === 'true' ) {
					$user_info = get_userdata( $entry->get_admin_reply_uid() );
					if ( is_object($user_info) ) {
						$admin_reply_email = $user_info->user_email;
						$avatar = get_avatar( $admin_reply_email, 32, '', $admin_reply_name );
						if ($avatar) {
							$admin_reply .= '
								<span class="gb-admin-avatar">' . $avatar . '</span>';
						}
					}
				}
				/* Admin Header */
				if ( isset($read_setting['read_name']) && $read_setting['read_name'] === 'true' && $admin_reply_name ) {
					$admin_reply_header = '
							<em>' . esc_html__('Admin Reply by:', 'gwolle-gb') . ' ' . $admin_reply_name . '</em>';
				} else {
					$admin_reply_header = '
							<em>' . esc_html__('Admin Reply:', 'gwolle-gb') . '</em>';
				}
				$admin_reply .= apply_filters( 'gwolle_gb_admin_reply_header', $admin_reply_header, $entry );
				$admin_reply .= '
						</div> ';

				/* Admin Reply Content */
				if ( get_option('gwolle_gb-showSmilies', 'true') === 'true' ) {
					$admin_reply_content = convert_smilies($admin_reply_content);
				}
				if ( get_option( 'gwolle_gb-showLineBreaks', 'false' ) === 'true' ) {
					$admin_reply_content = nl2br($admin_reply_content);
				}
				if ( isset($form_setting['form_bbcode_enabled']) && $form_setting['form_bbcode_enabled'] === 'true' ) {
					$admin_reply_content = gwolle_gb_bbcode_parse($admin_reply_content);
				} else {
					$admin_reply_content = gwolle_gb_bbcode_strip($admin_reply_content);
				}
				if ( $excerpt_length > 0 ) {
					$admin_reply_excerpt = wp_trim_words( $admin_reply_content, $excerpt_length, $readmore );
					$admin_reply .= '
						<div class="gb-admin_reply-excerpt">' . $admin_reply_excerpt . '</div>
						<div class="gb-admin_reply-full-content gwolle-gb-hide">
						' . $admin_reply_content . '
						</div>';
				} else {
					$admin_reply .= '
						<div class="gb-admin_reply_content gb-admin-reply-content">
						' . $admin_reply_content . '
						</div>';
				}
				$admin_reply .= '
					</div>';

				$entry_output .= $admin_reply;
			}
		}

		/* Metabox for entry with more metabox-lines with information or actions.
		 * For the handle we use a div with a tabindex=0 instead of a button.
		 * Button elements are not easy to style on the frontend for every theme out there.
		 * With Javascript we toggle the metabox open or closed.
		 */
		$gb_metabox = apply_filters( 'gwolle_gb_entry_metabox_lines', '', $entry );
		if ( $gb_metabox ) {
			$entry_output .= '
					<div class="gb-metabox-handle" tabindex="0">' . esc_html__('...', 'gwolle-gb' ) . '<span class="screen-reader-text"> ' . esc_html__('Toggle this metabox.', 'gwolle-gb') . '</span></div>
					<div class="gb-metabox">' .
						$gb_metabox . '
					</div>';
		}

		/* Use this filter to just add something. */
		$entry_output .= apply_filters( 'gwolle_gb_entry_read_add_after', '', $entry );

		$entry_output .= '
				</article>';

		$entry_output .= '
			</div>
			';

		return $entry_output;
	}
}
