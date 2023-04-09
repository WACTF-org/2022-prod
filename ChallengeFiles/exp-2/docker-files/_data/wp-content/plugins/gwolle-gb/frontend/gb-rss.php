<?php

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Add the feed.
 */
function gwolle_gb_rss_init() {
	add_feed('gwolle_gb', 'gwolle_gb_rss');
}
add_action('init', 'gwolle_gb_rss_init');


/*
 * Add the RSS link to the html head.
 * There is no post_content yet, but we do have get_the_ID().
 */
function gwolle_gb_rss_head() {
	if ( is_singular() ) {
		$post = get_post( get_the_ID() );
		if ( has_shortcode( $post->post_content, 'gwolle_gb' ) || has_shortcode( $post->post_content, 'gwolle_gb_read' ) ) {

			// Remove standard RSS links.
			remove_action( 'wp_head', 'feed_links', 2 );
			remove_action( 'wp_head', 'feed_links_extra', 3 );

			// And add our own RSS link.
			global $wp_rewrite;
			$permalinks = $wp_rewrite->permalink_structure;
			if ( $permalinks ) {
				?>
				<link rel="alternate" type="application/rss+xml" title="<?php esc_attr_e('Guestbook Feed', 'gwolle-gb'); ?>" href="<?php bloginfo('url'); ?>/feed/gwolle_gb" />
				<?php
			} else {
				?>
				<link rel="alternate" type="application/rss+xml" title="<?php esc_attr_e('Guestbook Feed', 'gwolle-gb'); ?>" href="<?php bloginfo('url'); ?>/?feed=gwolle_gb" />
				<?php
			}
		}
	}
}
add_action('wp_head', 'gwolle_gb_rss_head', 1);


/*
 * Set the correct HTTP header for Content-type.
 */
function gwolle_gb_rss_content_type( $content_type, $type ) {
	if ( 'gwolle_gb' === $type ) {
		return feed_content_type( 'rss2' );
	}
	return $content_type;
}
add_filter( 'feed_content_type', 'gwolle_gb_rss_content_type', 10, 2 );


/*
 * Show the XML Feed
 */
function gwolle_gb_rss() {

	// Only show the first page of entries.
	$entries_per_page = (int) apply_filters( 'gwolle_gb_rss_nr_entries', 20 );

	/* Get the entries for the RSS Feed */
	$entries = gwolle_gb_get_entries(
		array(
			'offset'      => 0,
			'num_entries' => $entries_per_page,
			'checked'     => 'checked',
			'trash'       => 'notrash',
			'spam'        => 'nospam',
		)
	);

	// Date in RFC 822.
	$timezone = date_i18n('O'); // +0200 for example
	$datetimeformat = 'd M Y H:i:s';

	/* Get the time of the last entry, else of the last edited post */
	if ( is_array($entries) && ! empty($entries) ) {
		$lastbuild = gmdate( $datetimeformat, $entries[0]->get_datetime() ) . ' ' . $timezone;
	} else {
		$lastbuild = mysql2date($datetimeformat, get_lastpostmodified('GMT'), false) . ' GMT';
	}

	$blog_url = get_bloginfo('wpurl');
	$biggest_book = gwolle_gb_get_postid_biggest_book();
	if ( $biggest_book ) {
		$permalink_biggest_book = gwolle_gb_get_permalink( $biggest_book );
	}
	if ( is_wp_error( $permalink_biggest_book ) ) {
		$permalink_biggest_book = $blog_url . '?p=' . $biggest_book;
	}
	/* Get the Language setting */
	$wplang = get_locale();
	if ( ! $wplang ) {
		$wplang = 'en-us';
	}
	$wplang = str_replace( '_', '-', $wplang );
	$wplang = strtolower( $wplang );

	/* Build the XML content */
	header('Content-Type: ' . feed_content_type('rss2') . '; charset=' . get_option('blog_charset'), true);
	echo '<?xml version="1.0" encoding="' . get_option('blog_charset') . '"?' . '>';
	?>

	<rss version="2.0"
		xmlns:content="http://purl.org/rss/1.0/modules/content/"
		xmlns:wfw="http://wellformedweb.org/CommentAPI/"
		xmlns:dc="http://purl.org/dc/elements/1.1/"
		xmlns:atom="http://www.w3.org/2005/Atom"
		xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
		xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
		<?php do_action('rss2_ns'); ?>>

		<channel>
			<title><?php bloginfo_rss('name'); echo ' - ' . esc_html__('Guestbook Feed', 'gwolle-gb'); ?></title>
			<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
			<link><?php echo $permalink_biggest_book; ?></link>
			<description><?php bloginfo_rss('description'); echo ' - ' . esc_html__('Guestbook Feed', 'gwolle-gb'); ?></description>
			<lastBuildDate><?php echo $lastbuild; ?></lastBuildDate>
			<language><?php echo $wplang; ?></language>
			<sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
			<sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
			<?php do_action('rss2_head'); ?>

			<?php
			if ( is_array($entries) && ! empty($entries) ) {
				foreach ( $entries as $entry ) { ?>

					<item>
						<title><?php esc_html_e('Guestbook Entry by', 'gwolle-gb'); echo ' ' . trim( $entry->get_author_name() ) . ' (' . trim(date_i18n( get_option('date_format'), $entry->get_datetime() )) . ' ' . trim(date_i18n( get_option('time_format'), $entry->get_datetime() )) . ')'; ?></title>
						<link><?php
							$postid = gwolle_gb_get_postid( (int) $entry->get_book_id() );
							$permalink = $blog_url; // init for new entry.
							if ( $postid ) {
								$permalink = gwolle_gb_get_permalink( $postid );
							}
							if ( is_wp_error( $permalink ) ) {
								$permalink = $blog_url . '?p=' . $postid;
							}
							$permalink = add_query_arg( 'entry_id', $entry->get_id(), $permalink );
							$permalink = htmlspecialchars($permalink, ENT_COMPAT, 'UTF-8');
							echo $permalink; ?></link>
						<pubDate><?php echo gmdate( $datetimeformat, $entry->get_datetime() ) . ' ' . $timezone; ?></pubDate>
						<dc:creator><?php echo trim( $entry->get_author_name() ); ?></dc:creator>
						<guid isPermaLink="false"><?php echo $permalink; ?></guid>
						<description><![CDATA[<?php echo wp_trim_words( $entry->get_content(), 12, '...' ) ?>]]></description>
						<content:encoded><![CDATA[<?php echo wp_trim_words( $entry->get_content(), 25, '...' ) ?>]]></content:encoded>
						<?php rss_enclosure(); ?>
						<?php do_action('rss2_item'); ?>
					</item>

					<?php
				}
			} ?>

		</channel>
	</rss>
	<?php
}
