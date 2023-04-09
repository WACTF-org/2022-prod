<?php
/**
 * Easy Digital Downloads support
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'exs_is_edd_fes_active' ) ) :
	/**
	 * Is EDD Frontend Submissions active?
	 *
	 * @since 0.0.6
	 * @return bool
	 */
	function exs_is_edd_fes_active() {
		return class_exists( 'EDD_Front_End_Submissions' );
	}
endif;

if ( ! function_exists( 'exs_is_edd_sl_active' ) ) :
	/**
	 * Is EDD Software Licensing active?
	 *
	 * @since 0.0.6
	 * @return bool
	 */
	function exs_is_edd_sl_active() {
		return class_exists( 'EDD_Software_Licensing' );
	}
endif;

if ( ! function_exists( 'exs_edd_price_enhancements' ) ) :
	/**
	 * EDD Price Enhancements
	 *
	 * While enabled:
	 *
	 * 1. Prices from purchase buttons are removed
	 * 2. Prices are automatically shown when using the [downloads] shortcode (unless "price" is set to "no")
	 *
	 * @since 0.0.6
	 *
	 * @return boolean true
	 */
	function exs_edd_price_enhancements() {
		return apply_filters( 'exs_edd_price_enhancements', true );
	}
endif;

if ( ! function_exists( 'exs_edd_download_author_options' ) ) :
	/**
	 * Download author options
	 *
	 * @since 0.0.6
	 */
	function exs_edd_download_author_options( $args = array() ) {

		// Set some defaults for the download sidebar when the widget is not in use.
		$defaults = apply_filters( 'exs_edd_download_author_defaults', array(
			'avatar'      => true,
			'avatar_size' => 80,
			'store_name'  => true,
			'name'        => true,
			'signup_date' => true,
			'website'     => true,
			'title'       => ''
		) );

		// Merge any args passed in from the widget with the defaults.
		$args = wp_parse_args( $args, $defaults );

		// If Frontend Submissions is active, show the author details by default.
		if ( exs_is_edd_fes_active() ) {
			$args['show'] = true;
		}

		/**
		 * Return the final $args
		 * Developers can use this filter hook to override options from widget settings or on a per-download basis.
		 */
		return apply_filters( 'exs_edd_download_author_options', $args );

	}
endif;

if ( ! function_exists( 'exs_edd_has_download_author' ) ) :
	/**
	 * Determine if the current download has any author details.
	 *
	 * @since 0.0.6
	 */
	function exs_edd_has_download_author( $options = array() ) {

		// Remove "show" from the $options array since we don't want to check against it.
		unset( $options['show'] );

		// If (bool) true exists anywhere in the $options array then there are author details that need to be shown.
		if ( in_array( (bool) true, $options, true ) ) { // Uses strict mode.
			return true;
		}

		return false;

	}
endif;

if ( ! function_exists( 'exs_edd_show_download_author' ) ) :
	/**
	 * Determine if the author details can be shown
	 */
	function exs_edd_show_download_author( $options = array() ) {

		// If no options are passed in, use the default options.
		if ( empty( $options ) ) {
			$options = exs_edd_download_author_options();
		}

		if ( isset( $options['show'] ) && true === $options['show'] && true === exs_edd_has_download_author( $options ) ) {
			return true;
		}

		return false;

	}
endif;


if ( ! function_exists( 'exs_edd_download_date_published' ) ) :
	/**
	 * Date published
	 *
	 * @since 0.0.6
	 */
	function exs_edd_download_date_published() {

		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		return $time_string;

	}
endif;

if ( ! function_exists( 'exs_edd_download_version' ) ) :
	/**
	 * Get the version number of a download, given its ID.
	 *
	 * @since 0.0.6
	 */
	function exs_edd_download_version( $download_id = 0 ) {

		if ( ! $download_id ) {
			return false;
		}

		if ( exs_is_edd_sl_active() && (new Themedd_EDD_Software_Licensing)->has_licensing_enabled() ) {
			// Get version number from EDD Software Licensing.
			return get_post_meta( $download_id, '_edd_sl_version', true );
		}

		return false;

	}
endif;

if ( ! function_exists( 'exs_edd_download_details_options' ) ) :
	/**
	 * Download details options.
	 *
	 * @since  0.0.6
	 * @param  array $args Download Details options passed in from the Themedd: Download Details widget
	 *
	 * @return array $args The final Download Details options
	 */
	function exs_edd_download_details_options( $args = array() ) {

		//here we will pares the theme mods

		// Set some defaults for the download sidebar when the widget is not in use.
		$defaults = apply_filters( 'exs_edd_download_details_defaults', array(
			'show'           => true,
			'sale_count'     => true,
			'date_published' => true,
			'categories'     => true,
			'tags'           => true,
			'version'        => true,
			'title'          => ''
		) );

		// Set some defaults when Frontend Submissions is activated.
		if ( exs_is_edd_fes_active() ) {
			/* translators: %s: 'Download' post type label name. */
			$defaults['title']          = sprintf( __( '%s Details', 'exs' ), edd_get_label_singular() );
			$defaults['date_published'] = true;
			$defaults['sale_count']     = true;
		}

		// Set some defaults when Software Licensing is activated.
		if ( exs_is_edd_sl_active() ) {
			$defaults['version'] = true;
		}

		// Merge any args passed in from the widget with the defaults.
		$args = wp_parse_args( $args, $defaults );

		/**
		 * Return the final $args
		 * Developers can use this filter hook to override options from widget settings or on a per-download basis.
		 */
		return apply_filters( 'exs_edd_download_details_options', $args );

	}
endif;

if ( ! function_exists( 'exs_edd_download_categories' ) ) :
	/**
	 * Get the download categories of a download, given its ID
	 *
	 * @since 0.0.6
	 */
	function exs_edd_download_categories( $download_id = 0, $before = '', $sep = ', ', $after = '' ) {

	if ( ! $download_id ) {
		return false;
	}

	$categories = get_the_term_list( $download_id, 'download_category', $before, $sep, $after );

	if ( $categories ) {
		return $categories;
	}

	return false;
}
endif;

if ( ! function_exists( 'exs_edd_download_tags' ) ) :
	/**
	 * Get the download tags of a download, given its ID.
	 *
	 * @since 0.0.6
	 */
	function exs_edd_download_tags( $download_id = 0, $before = '', $sep = ', ', $after = '' ) {

	if ( ! $download_id ) {
		return false;
	}

	$tags = get_the_term_list( $download_id, 'download_tag', $before, $sep, $after );

	if ( $tags ) {
		return $tags;
	}

	return false;
}
endif;

if ( ! function_exists( 'exs_edd_has_download_details' ) ) :
	/**
	 * Determine if the current download has any download details.
	 *
	 * @since 0.0.6
	 */
	function exs_edd_has_download_details( $options = array() ) {

		$return = false;

		$download_id = get_the_ID();

		if (
			true === $options['categories'] && exs_edd_download_categories( $download_id ) || // Download categories are enabled and exist.
			true === $options['tags'] && exs_edd_download_tags( $download_id )             || // Download tags are enabled and exist.
			true === $options['sale_count']                                                    || // Sale count has been enabled from the "Themedd: Download Details" widget.
			true === $options['date_published']                                                || // Date published as been enabled from the "Themedd: Download Details" widget.
			true === $options['version'] && exs_edd_download_version( $download_id )          // Version number is allowed, and the download has a version number, the download details can be shown.
		) {
			$return = true;
		}

		return apply_filters( 'exs_edd_has_download_details', $return, $options );

	}
endif;

if ( ! function_exists( 'exs_edd_show_download_details' ) ) :
	/**
	 * Determine if the download details can be shown.
	 *
	 * @since 0.0.6
	 */
	function exs_edd_show_download_details( $options = array() ) {

		// If no options are passed in, use the default options.
		if ( empty( $options ) ) {
			$options = exs_edd_download_details_options();
		}

		if ( isset( $options['show'] ) && true === $options['show'] && true === exs_edd_has_download_details( $options ) ) {
			return true;
		}

		return false;

	}
endif;

if ( ! function_exists( 'exs_edd_download_meta_options' ) ) :
	/**
	 * Download meta options.
	 *
	 * @since 0.0.6
	 *
	 * @return array $options Download meta options
	 */
	function exs_edd_download_meta_options() {

		$options = array(
			'echo'        => true,
			'position'    => 'after_title', // See exs_edd_download_meta_position() for possible values.
			'price'       => false,
			'price_link'  => false, // Whether or not the price is linked through to the download.
			'author_name' => false,
			'author_link' => true,
			'author_by'   => __( 'by', 'exs' ),
			'avatar'      => false,
			'avatar_size' => 32 // Size of avatar, in pixels.
		);

		// Display author name (which will be their store name) and avatar if FES is active.
		if ( exs_is_edd_fes_active() ) {
			$options['author_name'] = true;
			$options['avatar']      = true;
		}

		return apply_filters( 'exs_edd_download_meta_options', $options );

	}
endif;



if ( ! function_exists( 'exs_edd_has_download_meta' ) ) :
	/**
	 * Determine if there's download meta
	 *
	 * @since 0.0.6
	 *
	 * @return bool true if download meta, false otherwise.
	 */
	function exs_edd_has_download_meta() {

		$has_download_meta = false;

		// Get the download meta options
		$options = exs_edd_download_meta_options();

		if (
			true === $options['price'] ||
			true === $options['author_name'] ||
			true === $options['avatar']
		) {
			$has_download_meta = true;
		}

		return $has_download_meta;

	}
endif;

if ( ! function_exists( 'exs_edd_download_grid_options' ) ) :
	/**
	 * Download grid options.
	 *
	 * Used by all download grids:
	 *
	 * via the [downloads] shortcode
	 * archive-download.php
	 * taxonomy-download_category.php
	 * taxonomy-download_tag.php
	 *
	 * @since 0.0.6
	 *
	 * @param array $atts Attributes from [downloads] shortcode (if passed in).
	 *
	 * @return array $options Download grid options
	 */
	function exs_edd_download_grid_options( $atts = array() ) {

		/**
		 * Do some homekeeping on the [downloads] shortcode.
		 *
		 * Converts the various "yes", "no, "true" etc into a format that the $options array uses.
		 */
		if ( ! empty( $atts ) ) {

			// Buy button.
			if ( isset( $atts['buy_button'] ) && 'yes' === $atts['buy_button'] ) {
				$atts['buy_button'] = true;
			}

			// Price.
			if ( isset( $atts['price'] ) && 'yes' === $atts['price'] ) {
				$atts['price'] = true;
			}

			// Excerpt.
			if ( isset( $atts['excerpt'] ) && 'yes' === $atts['excerpt'] ) {
				$atts['excerpt'] = true;
			}

			// Full content.
			if ( isset( $atts['full_content'] ) && 'yes' === $atts['full_content'] ) {
				$atts['full_content'] = true;
			}

			// Thumbnails.
			if ( isset( $atts['thumbnails'] ) ) {
				if ( 'true' === $atts['thumbnails'] || 'yes' === $atts['thumbnails'] ) {
					$atts['thumbnails'] = true;
				}
			}

		}

		$excerpt = exs_option( 'edd_list_hide_excerpt' ) ? false : true;
		$columns = exs_option( 'edd_list_columns', '3' );

		// Options.
		$options = array(
			'title'        => true, // This is unique to Themedd.
			'excerpt'      => $excerpt,
			'full_content' => false,
			'price'        => true,
			'buy_button'   => true,
			'columns'      => $columns,
			'thumbnails'   => true,
			'pagination'   => true,
			'number'       => 9,
			'order'        => 'DESC',
			'orderby'      => 'post_date'
		);

		// Merge the arrays.
		$options = wp_parse_args( $atts, $options );

		// Return the options.
		return apply_filters( 'exs_edd_download_grid_options', $options );

	}
endif;

if ( ! function_exists( 'exs_edd_downloads_list_wrapper_classes' ) ) :
	/**
	 * Downloads list wrapper classes
	 * These classes are applied wherever a downloads grid is outputted and used by:
	 *
	 * 1. The [downloads] shortcode
	 * 2. archive-download.php
	 * 3. taxonomy-download_category.php
	 * 4. taxonomy-download_tag.php
	 *
	 * @since 0.0.6
	 *
	 * @param string $wrapper_class The class passed in from the [downloads] shortcode
	 * @param array $atts The shortcode args passed in from the [downloads] shortcode
	 *
	 * @return string $classes The classes to be added
	 */
	function exs_edd_downloads_list_wrapper_classes( $wrapper_class = '', $atts = array() ) {

	// Get the download grid options.
	$options = exs_edd_download_grid_options();

	// Set up default $classes array.
	$classes = array( $wrapper_class );

	// [downloads] shortcode is being used
	if ( ! empty( $atts ) ) {

		// Add downloads class.
		$classes[] = 'edd_download_columns_' . $atts['columns'];

		$has_price   = $atts['price'] == 'yes' ? true : false;
		$has_excerpt = $atts['excerpt'] == 'yes' ? true : false;
		$buy_button  = $atts['buy_button'] == 'yes' ? true : false;
		$thumbnails  = $atts['thumbnails'] == 'true' ? true : false;

	} else {
		/**
		 * The download grid is being outputted by either:
		 *
		 * archive-download.php
		 * taxonomy-download_category.php
		 * taxonomy-download_tag.php
		 */

		// The [downloads] shortcode already has the following class applied so only add it for archive-download.php, taxonomy-download_category.php and taxonomy-download_tag.php.
		$classes[] = 'edd_downloads_list';

		// Add downloads class.
		$classes[] = 'edd_download_columns_' . $options['columns'];

		$has_price   = true === $options['price'] ? true : false;
		$has_excerpt = true === $options['excerpt'] ? true : false;
		$buy_button  = true === $options['buy_button'] ? true : false;
		$thumbnails  = true === $options['thumbnails'] ? true : false;

	}

	$classes[] = true === $has_price ? 'has-price' : 'no-price';
	$classes[] = true === $has_excerpt ? 'has-excerpt' : '';
	$classes[] = true === $buy_button ? 'has-buy-button' : 'no-buy-button';
	$classes[] = true === $thumbnails ? 'has-thumbnails' : 'no-thumbnails';

	//equal height new 1.9.1
	$classes[] = exs_option( 'edd_list_equal_height' ) ? 'equal-height' : '';
	//fullwidth
	$classes[] = exs_option( 'edd_list_fullwidth' ) ? 'alignfull' : '';

	// Add has-download-meta class.
	$classes[] = exs_edd_has_download_meta() ? 'has-download-meta' : '';

	$classes = implode( ' ', array_filter( $classes ) );

	// Finally, make sure that any classes can be added via EDD's filter
	$classes = apply_filters( 'exs_edd_downloads_list_wrapper_class', $classes, $atts );

	return $classes;
}
endif;

if ( ! function_exists( 'exs_edd_downloads_shortcode_custom_wrapper_class' ) ) :
	function exs_edd_downloads_shortcode_custom_wrapper_class( $wrapper_classes ) {
		return exs_option( 'edd_list_equal_height' ) ? $wrapper_classes . ' equal-height' : $wrapper_classes;
	}
endif;
add_filter( 'edd_downloads_list_wrapper_class', 'exs_edd_downloads_shortcode_custom_wrapper_class', 10 );

if ( ! function_exists( 'exs_edd_download_meta_position' ) ) :
	/**
	 * Download meta position.
	 *
	 * Appears in a download on the download grid (either with the [downloads] shortcode or archive-download.php)
	 *
	 * Possible values are:
	 * after_title (default)
	 * after
	 * before_title
	 *
	 * @since 0.0.6
	 * @return string $position The position of the download meta
	 */
	function exs_edd_download_meta_position() {

		$options  = exs_edd_download_meta_options();
		$position = $options['position'];

		return $position;

	}
endif;

if ( ! function_exists( 'exs_edd_download_nav' ) ) :
	/**
	 * Download navigation
	 * This is used by archive-download.php, taxonomy-download_category.php, taxonomy-download_tag.php
	 *
	 * @since 0.0.6
	 */
	function exs_edd_download_nav() {

		global $wp_query;

		$options = exs_edd_download_grid_options();

		// Exit early if pagination has been set to false.
		if ( true !== $options['pagination'] ) {
			return;
		}

		$big          = 999999;
		$search_for   = array( $big, '#038;' );
		$replace_with = array( '%#%', '&' );

		$args = wp_parse_args(
			array(
				'base'               => str_replace( $search_for, $replace_with, get_pagenum_link( $big ) ),
				'format'             => '?paged=%#%',
				'current'            => max( 1, get_query_var( 'paged' ) ),
				'total'              => $wp_query->max_num_pages,
				'screen_reader_text' => esc_html__( 'Pagination', 'exs' ),
			),
			exs_get_the_posts_pagination_atts()
		);

		$pagination = paginate_links( $args );
		?>

		<?php if ( ! empty( $pagination ) ) : ?>
			<nav id="edd_download_pagination" class="navigation pagination">
				<div class="nav-links">
				<?php
				// Previous/next page navigation.
				the_posts_pagination(
					$args
				);
				?>
				</div>
			</nav>
		<?php endif; ?>

		<?php
	}
endif;

if ( ! function_exists( 'exs_edd_download_footer' ) ) :
	/**
	 * The download footer
	 *
	 * Appears at the bottom of a download in the download grid.
	 * The download grid appears:
	 *
	 * 1. Wherever the [downloads] shortcode is used.
	 * 2. The custom post type archive page (/downloads), unless it has been disabled.
	 * 3. archive-download.php
	 * 4. taxonomy-download_category.php
	 * 5. taxonomy-download_tag.php
	 *
	 * @param array $atts Attributes from [downloads] shortcode.
	 * @since 0.0.6
	 */
	function exs_edd_download_footer( $atts = array() ) {

		// Pass the shortcode options into the download grid options.
		$download_grid_options = exs_edd_download_grid_options( $atts );

		// Get the download ID.
		$download_id = get_the_ID();

		/**
		 * Show the download footer.
		 *
		 * The download footer will be shown if one of the following is true:
		 *
		 * - The price is shown.
		 * - The buy button is shown.
		 * - The download meta is loaded into the download footer.
		 * - The exs_edd_download_footer filter hook has been set to true.
		 */
		if (
			true === $download_grid_options['buy_button']                                ||
			true === $download_grid_options['price']                                     ||
			true === apply_filters( 'exs_edd_download_footer', false, $download_id ) ||
			'after' === exs_edd_download_meta_position()
		) :
			?>

			<div class="downloadFooter">
				<?php

				/**
				 * Fires at the start of the download footer.
				 *
				 * @since 1.0.2
				 * @since 1.0.3 Added $download_id
				 *
				 * @param int $download_id The ID of the download.
				 */
				do_action( 'exs_edd_download_footer_start', $download_id );

				/**
				 * Show the price.
				 */
				if ( true === $download_grid_options['price'] ) :
					edd_get_template_part( 'shortcode', 'content-price' );
					do_action( 'exs_edd_download_after_price', $download_id );
				endif;

				/**
				 * Show the buy button.
				 */
				if ( true === $download_grid_options['buy_button'] ) {
					edd_get_template_part( 'shortcode', 'content-cart-button' );
				}

				/**
				 * Fires at the end of the download footer.
				 *
				 * @since 1.0.2
				 * @since 1.0.3 Added $download_id
				 *
				 * @param int $download_id The ID of the download.
				 */
				do_action( 'exs_edd_download_footer_end', $download_id );

				?>
			</div>
			<?php
		endif;
	}
endif;


///////////
//actions//
///////////
if ( ! function_exists( 'exs_edd_download_details_widget' ) ) :
	/**
	 * Adds exs_edd_price() and exs_edd_purchase_link() to the download details widget.
	 *
	 * @since 0.0.6
	 */
	function exs_edd_download_details_widget( $instance, $download_id ) {
		if ( ! is_active_sidebar( 'sidebar-download' ) ) {
			do_action( 'exs_edd_download_info', $download_id );
		}
	}
endif;
add_action( 'edd_product_details_widget_before_purchase_button', 'exs_edd_download_details_widget', 10, 2 );

if ( ! function_exists( 'exs_edd_price' ) ) :
	/**
	 * Download price
	 *
	 * @since 0.0.6
	 */
	function exs_edd_price( $download_id ) {
		// Return early if price enhancements has been disabled.
		if ( false === exs_edd_price_enhancements() ) {
			return;
		}

		if ( edd_is_free_download( $download_id ) ) {
			$price = '<span id="edd_price_' . get_the_ID() . '" class="edd_price">' . __( 'Free', 'exs' ) . '</span>';
		} elseif ( edd_has_variable_prices( $download_id ) ) {
			$price = '<span id="edd_price_' . get_the_ID() . '" class="edd_price">' . __( 'From', 'exs' ) . '&nbsp;' . edd_currency_filter( edd_format_amount( edd_get_lowest_price_option( $download_id ) ) ) . '</span>';
		} else {
			$price = edd_price( $download_id, false );
		}

		echo wp_kses( $price, array( 'span' => array( 'id' => true, 'class' => true ) ) );
	}
endif;
add_action( 'exs_edd_download_info', 'exs_edd_price', 10, 1 );

if ( ! function_exists( 'exs_edd_purchase_link' ) ) :
	/**
	 * Download purchase link
	 *
	 * @since 0.0.6
	 */
	function exs_edd_purchase_link( $download_id ) {

		if ( get_post_meta( $download_id, '_edd_hide_purchase_link', true ) ) {
			return; // Do not show if auto output is disabled
		}

		echo edd_get_purchase_link();

	}
endif;
add_action( 'exs_edd_download_info', 'exs_edd_purchase_link', 10, 1 );

if ( ! function_exists( 'exs_edd_purchase_link_defaults' ) ) :
	/**
	 * Filter the purchase link defaults
	 *
	 * @since 0.0.6
	 */
	function exs_edd_purchase_link_defaults( $defaults ) {

		// Remove button class.
		$defaults['color'] = '';

		// Remove the price from the purchase button
		if ( exs_edd_price_enhancements() ) {
			$defaults['price'] = (bool) false;
		}

		return $defaults;

	}
endif;
add_filter( 'edd_purchase_link_defaults', 'exs_edd_purchase_link_defaults' );

if ( ! function_exists( 'exs_edd_change_post_thumbnail_size' ) ) :
	/**
	 * Filter the post thumbnail size for download shortcode
	 *
	 * @since 1.8.11
	 */
	function exs_edd_change_post_thumbnail_size( $size, $post_id ) {

		$post = get_post( $post_id );

		if ( $post && 'download' === $post->post_type && 'thumbnail' === $size && is_main_query() ) {
			$size = 'large';
		}
		return $size;
	}
endif;
add_filter( 'post_thumbnail_size', 'exs_edd_change_post_thumbnail_size', 10, 2 );

//download grid meta
if ( ! function_exists( 'exs_edd_downloads_grid_item_footer' ) ) :
	function exs_edd_downloads_grid_item_footer() {
		$cats = exs_option( 'edd_list_show_cats' ) ? exs_edd_download_categories( get_the_ID() ) : false;
		$tags = exs_option( 'edd_list_show_tags' ) ? exs_edd_download_tags( get_the_ID() ) : false;

		if ( $cats || $tags ) :
			echo '<div class="entry-footer download-terms">';
				if ( $cats ) {
					echo '<div class="icon-inline download-cats">';
					exs_icon( 'folder-outline' );
					echo wp_kses_post( $cats );
					echo '</div>';
				}
				if ( $tags ) {
					echo '<div class="icon-inline download-cats">';
					exs_icon( 'tag' );
					echo wp_kses_post( $tags );
					echo '</div>';
				}
			echo '</div>';
		endif;
	}
endif;
add_action( 'edd_download_after_title', 'exs_edd_downloads_grid_item_footer');
add_action( 'exs_edd_download_after_title', 'exs_edd_downloads_grid_item_footer');

//wrap download_inner into wrapper for styling
if ( ! function_exists( 'exs_edd_download_item_inner_wrap_open' ) ) :
	function exs_edd_download_item_inner_wrap_open() {
		$class_center    = exs_option( 'edd_list_center_text' ) ? 'text-center' : '';
		$class_btn_block = exs_option( 'edd_list_button_block' ) ? 'edd-btn-block' : '';
		$class_bg = exs_option( 'edd_list_item_bg' );
		$class_border = exs_option('edd_list_bordered') ? 'bordered' : '';
		$class_shadow = exs_option('edd_list_shadow') ? 'shadow' : '';
		$class_rounded = exs_option('edd_list_rounded') ? 'rounded' : '';
		$class_wider_image = exs_option('edd_list_image_wide') ? 'wider-image' : '';

		$class_padding = $class_bg || $class_shadow || $class_border ? ' extra-padding' : '';
		echo '<div class="download-inner-wrap ' . $class_center . ' ' . $class_btn_block . ' ' . $class_bg . ' ' . $class_border . ' ' . $class_shadow . ' ' . $class_rounded . ' ' . $class_padding . ' ' . $class_wider_image . '">';
	}
endif;
if ( ! function_exists( 'exs_edd_download_item_inner_wrap_close' ) ) :
	function exs_edd_download_item_inner_wrap_close() {
		echo '</div><!-- .download-inner-wrap-->';
	}
endif;
add_action( 'edd_download_before', 'exs_edd_download_item_inner_wrap_open' );
add_action( 'exs_edd_download_before', 'exs_edd_download_item_inner_wrap_open' );
add_action( 'edd_download_after', 'exs_edd_download_item_inner_wrap_close' );
add_action( 'exs_edd_download_after', 'exs_edd_download_item_inner_wrap_close' );

//wrap pagination to our classes
if ( ! function_exists( 'exs_edd_downloads_pagination_wrap_open' ) ) :
	function exs_edd_downloads_pagination_wrap_open() {
		echo '<nav class="pagination nav-links">';
	}
endif;
if ( ! function_exists( 'exs_edd_downloads_pagination_wrap_close' ) ) :
	function exs_edd_downloads_pagination_wrap_close() {
		echo '</nav><!-- .pagination.nav-links-->';
	}
endif;
add_action( 'edd_downloads_list_after', 'exs_edd_downloads_pagination_wrap_open', 9);
add_action( 'edd_downloads_list_after', 'exs_edd_downloads_pagination_wrap_close', 11);

//hide purchase form at the bottom of the download content - we have it in the sidebar
remove_action( 'edd_after_download_content', 'edd_append_purchase_link' );

//Customizer EDD options
add_filter( 'exs_customizer_options', 'exs_filter_exs_customizer_options_edd' );
if ( ! function_exists( 'exs_filter_exs_customizer_options_edd' ) ) :
	function exs_filter_exs_customizer_options_edd( $options ) {
		$options['panel_edd']                           = array(
			'type'            => 'panel',
			'label'           => esc_html__( 'ExS EDD options', 'exs' ),
			'description'     => esc_html__( 'EDD specific ExS theme options', 'exs' ),
			'priority'        => 130,
		);

		$options['section_edd_list']                       = array(
			'type'            => 'section',
			'panel'           => 'panel_edd',
			'label'           => esc_html__( 'Downloads list', 'exs' ),
			'description'     => esc_html__( 'Options for display downloads list', 'exs' ),
			'priority'        => 100,
		);
		//options
		//header cart
		$options['edd_header_cart_dropdown']                       = array(
			'type'            => 'checkbox',
			'section'         => 'section_edd_list',
			'default'         => esc_html( exs_option( 'edd_header_cart_dropdown', '' ) ),
			'label'           => esc_html__( 'Show cart dropdown in the header', 'exs' ),
		);
		$options['edd_header_cart_dropdown'] = array(
			'type'        => 'select',
			'section'     => 'section_edd_list',
			'default'     => exs_option( 'edd_header_cart_dropdown', '' ),
			'label'       => esc_html__( 'Show cart dropdown in the header', 'exs' ),
			'description' => esc_html__( 'Select cart icon type to optionally show in the header.', 'exs' ),
			'choices'     => array(
				''                    => esc_html__( 'No', 'exs' ),
				'cart-google'         => esc_html__( 'Google Icon', 'exs' ),
				'cart-google-outline' => esc_html__( 'Google Outline Icon', 'exs' ),
				'cart-ionic'          => esc_html__( 'Ionic Icon', 'exs' ),
				'cart-ionic-outline'  => esc_html__( 'Ionic Outline Icon', 'exs' ),
				'cart-lineicons'      => esc_html__( 'Lineicons Icon', 'exs' ),
				'cart-google-basket'        => esc_html__( 'Google Basket Icon', 'exs' ),
				'cart-ionic-bag'            => esc_html__( 'Ionic Bag Icon', 'exs' ),
				'cart-ionic-bag-outline'    => esc_html__( 'Ionic Bag Outline Icon', 'exs' ),
				'cart-ionic-basket'         => esc_html__( 'Ionic Basket Icon', 'exs' ),
				'cart-ionic-basket-outline' => esc_html__( 'Ionic Basket Outline Icon', 'exs' ),
			),
		);


		//sidebars
		$options['downloads_sidebar_position'] = array(
			'type'        => 'radio',
			'section'     => 'section_edd_list',
			'default'     => exs_option( 'downloads_sidebar_position', 'right' ),
			'label'       => esc_html__( 'Downloads archive sidebar position', 'exs' ),
			'description' => esc_html__( 'This option let you manage sidebar position on the Downloads archive pages.', 'exs' ),
			'choices'     => exs_get_sidebar_position_options(),
		);

		$options['edd_list_columns']                       = array(
			'type'            => 'slider',
			'section'         => 'section_edd_list',
			'default'         => esc_html( exs_option( 'edd_list_columns', '3' ) ),
			'label'           => esc_html__( 'Columns on wide screens', 'exs' ),
			'atts' => array(
				'min' => 1,
				'max' => 6,
			),
		);
		$options['edd_list_fullwidth']                       = array(
			'type'            => 'checkbox',
			'section'         => 'section_edd_list',
			'default'         => esc_html( exs_option( 'edd_list_fullwidth', '' ) ),
			'label'           => esc_html__( 'Full width list', 'exs' ),
		);
		$options['edd_list_hide_excerpt']                       = array(
			'type'            => 'checkbox',
			'section'         => 'section_edd_list',
			'default'         => esc_html( exs_option( 'edd_list_hide_excerpt', '' ) ),
			'label'           => esc_html__( 'Hide excerpt', 'exs' ),
		);

		$options['edd_list_padding']                       = array(
			'type'            => 'slider',
			'section'         => 'section_edd_list',
			'default'         => esc_html( exs_option( 'edd_list_padding', '' ) ),
			'label'           => esc_html__( 'Download item padding', 'exs' ),
			'atts' => array(
				'min' => 0,
				'max' => 30,
			),
		);
		$options['edd_list_show_cats']                       = array(
			'type'            => 'checkbox',
			'section'         => 'section_edd_list',
			'default'         => esc_html( exs_option( 'edd_list_show_cats', '' ) ),
			'label'           => esc_html__( 'Show categories', 'exs' ),
		);
		$options['edd_list_show_tags']                       = array(
			'type'            => 'checkbox',
			'section'         => 'section_edd_list',
			'default'         => esc_html( exs_option( 'edd_list_show_tags', '' ) ),
			'label'           => esc_html__( 'Show tags', 'exs' ),
		);
		$options['edd_list_center_text']                       = array(
			'type'            => 'checkbox',
			'section'         => 'section_edd_list',
			'default'         => esc_html( exs_option( 'edd_list_center_text', '' ) ),
			'label'           => esc_html__( 'Center text', 'exs' ),
		);
		$options['edd_list_button_block']                       = array(
			'type'            => 'checkbox',
			'section'         => 'section_edd_list',
			'default'         => esc_html( exs_option( 'edd_list_button_block', '' ) ),
			'label'           => esc_html__( 'Full width purchase button', 'exs' ),
		);
		$options['edd_list_item_bg']                     = array(
			'type'    => 'color-radio',
			'section' => 'section_edd_list',
			'label'   => esc_html__( 'Download item background', 'exs' ),
			'default' => esc_html( exs_option( 'edd_list_item_bg', '' ) ),
			'choices' => exs_customizer_backgrounds_array(),
		);
		$options['edd_list_bordered']                       = array(
			'type'            => 'checkbox',
			'section'         => 'section_edd_list',
			'default'         => esc_html( exs_option( 'edd_list_bordered', '' ) ),
			'label'           => esc_html__( 'Add border', 'exs' ),
		);
		$options['edd_list_shadow']                       = array(
			'type'            => 'checkbox',
			'section'         => 'section_edd_list',
			'default'         => esc_html( exs_option( 'edd_list_shadow', '' ) ),
			'label'           => esc_html__( 'Add shadow', 'exs' ),
		);
		$options['edd_list_rounded']                       = array(
			'type'            => 'checkbox',
			'section'         => 'section_edd_list',
			'default'         => esc_html( exs_option( 'edd_list_rounded', '' ) ),
			'label'           => esc_html__( 'Rounded', 'exs' ),
		);
		$options['edd_list_image_wide']                       = array(
			'type'            => 'checkbox',
			'section'         => 'section_edd_list',
			'default'         => esc_html( exs_option( 'edd_list_image_wide', '' ) ),
			'label'           => esc_html__( 'Wider image', 'exs' ),
			'description'     => esc_html__( 'Useful if background, border or shadow are selected', 'exs' ),
		);
		$options['edd_list_equal_height']                       = array(
			'type'            => 'checkbox',
			'section'         => 'section_edd_list',
			'default'         => esc_html( exs_option( 'edd_list_equal_height', '' ) ),
			'label'           => esc_html__( 'Items equal height', 'exs' ),
		);

		return $options;
	}
endif;

//edd inline style
if ( ! function_exists( 'exs_enqueue_static_edd' ) ) :
	function exs_enqueue_static_edd() {
		$exs_css_vars_string = '';
		if ( ! is_admin() ) :
			//sidebar gap
			$exs_css_vars_string .= '--edd-gap:';
			$edd_gap_width = exs_option( 'edd_list_padding', '' );
			$exs_css_vars_string .= ! empty( $edd_gap_width ) || '0' === $edd_gap_width ? (int) ( exs_option( 'edd_list_padding', '' ) ) . 'px;' : '8px;';
		endif;
		if ( $exs_css_vars_string ) :
			wp_add_inline_style(
				'exs-edd-style',
				wp_kses(
					':root{' . $exs_css_vars_string . '}',
					false
				)
			);
		endif;
	}
endif;
add_action( 'wp_enqueue_scripts', 'exs_enqueue_static_edd' );
