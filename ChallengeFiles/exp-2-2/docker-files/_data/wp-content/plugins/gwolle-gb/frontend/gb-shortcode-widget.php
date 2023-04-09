<?php

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Widget Function for simple layout.
 *
 * For multiple guestbooks, use it like this:
 * gwolle_gb_shortcode_widget( array('book_id'=>2) );
 * which will show Book ID 2 in widget layout.
 *
 * @since 2.1.4
 *
 * @param array $atts array with the shortcode attributes.
 * - book_id, int with an ID.
 * - num_entries, int with the shown number of messages.
 * - num_words, int with the shown number of words per entry.
 */
function gwolle_gb_shortcode_widget( $atts ) {
	echo get_gwolle_gb_shortcode_widget( $atts );
}


/*
 * Frontend function to show the list of entries in widget layout.
 *
 * @param array $atts array with the shortcode attributes.
 * - book_id, int with an ID.
 * - num_entries, int with the shown number of messages.
 * - num_words, int with the shown number of words per entry.
 */
function get_gwolle_gb_shortcode_widget( $atts ) {

	$shortcode_atts = shortcode_atts( array(
		'book_id'     => 0,
		'num_entries' => 5,
		'num_words'   => 10,
	), $atts );

	if ( $shortcode_atts['book_id'] === 'post_id' ) {
		$shortcode_atts['book_id'] = get_the_ID();
	}

	// Load Frontend CSS in Footer, only when it's active.
	wp_enqueue_style('gwolle_gb_frontend_css');

	$widget_title = esc_html__('Guestbook', 'gwolle-gb');
	$book_id      = $shortcode_atts['book_id'];
	$num_entries  = $shortcode_atts['num_entries'];
	$num_words    = $shortcode_atts['num_words'];
	$postid       = 0;

	$widget_class = 'gwolle_gb_widget gwolle-gb-widget';
	$widget_class = apply_filters( 'gwolle_gb_widget_list_class', $widget_class );
	$widget_item_class = 'gwolle_gb_widget gwolle-gb-widget';
	$widget_item_class = apply_filters( 'gwolle_gb_widget_item_class', $widget_item_class );


	$widget_html = '
				<div class="gwolle_gb_widget gwolle-gb-widget">';
	$widget_html .= apply_filters('widget_title', $widget_title);

	$widget_html .= '
					<ul class="' . $widget_class . '">';

	// Get the latest $num_entries guestbook entries
	$entries = gwolle_gb_get_entries(
		array(
			'num_entries'   => $num_entries,
			'checked'       => 'checked',
			'trash'         => 'notrash',
			'spam'          => 'nospam',
			'book_id'       => $book_id,
			)
		);

	if ( is_array( $entries ) && ! empty( $entries ) ) {
		foreach ( $entries as $entry ) {
			$widget_html .= '
						<li class="' . $widget_item_class . '">';

			$widget_html .= '
							<article>';

			// Use this filter to just add something
			$widget_html .= apply_filters( 'gwolle_gb_entry_widget_add_before', '', $entry );

			$widget_html .= '
								<span class="gb-author-name">' . $entry->get_author_name() . '</span>';
			$widget_html .= ' / ';
			$widget_html .= '
								<span class="gb-date">' . date_i18n( get_option('date_format'), $entry->get_datetime() ) . '</span>';
			$widget_html .= ':<br />';

			$entry_content = gwolle_gb_get_excerpt( gwolle_gb_bbcode_strip($entry->get_content()), $num_words );
			if ( get_option('gwolle_gb-showSmilies', 'true') === 'true' ) {
				$entry_content = convert_smilies($entry_content);
			}
			$widget_html .= '
								<span class="gb-entry-content">' . $entry_content;

			// Use this filter to just add something
			$widget_html .= apply_filters( 'gwolle_gb_entry_widget_add_content', '', $entry );

			$widget_html .= '</span>';

			// Use this filter to just add something
			$widget_html .= apply_filters( 'gwolle_gb_entry_widget_add_after', '', $entry );

			$widget_html .= '
							</article>';

			$widget_html .= '
						</li>';
		}
	}

	$widget_html .= '
					</ul>';
	$widget_html .= '
				</div>';

	// Add a filter for the entries, so devs can add or remove parts.
	$widget_html = apply_filters( 'gwolle_gb_widget', $widget_html);

	return $widget_html;
}
add_shortcode( 'gwolle_gb_widget', 'get_gwolle_gb_shortcode_widget' );
