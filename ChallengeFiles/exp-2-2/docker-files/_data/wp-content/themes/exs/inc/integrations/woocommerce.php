<?php
/**
 * WooCommerce support
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Woo min required version is 3.6

//header products counter ajax refresh
add_filter( 'woocommerce_add_to_cart_fragments', 'exs_filter_woocommerce_cart_count_fragments', 10, 1 );
if ( ! function_exists( 'exs_filter_woocommerce_cart_count_fragments' ) ) :
	function exs_filter_woocommerce_cart_count_fragments( $fragments ) {
		$fragments['span.cart-count'] = '<span class="cart-count">';
		if ( ! empty( WC()->cart->get_cart_contents_count() ) ) {
			$fragments['span.cart-count'] .= WC()->cart->get_cart_contents_count();
		}
		$fragments['span.cart-count'] .= '</span>';
		return $fragments;
	}
endif;

//removing wrapper 'main' and 'div' elements - templates/global and adding our custom with class .woo
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

add_action( 'woocommerce_before_main_content', 'exs_action_woocommerce_output_content_wrapper', 10 );
if ( ! function_exists( 'exs_action_woocommerce_output_content_wrapper' ) ) :
	function exs_action_woocommerce_output_content_wrapper() {
		echo '<div class="woo">';
	}
endif;
add_action( 'woocommerce_after_main_content', 'exs_action_woocommerce_output_content_wrapper_end', 10 );
if ( ! function_exists( 'exs_action_woocommerce_output_content_wrapper_end' ) ) :
	function exs_action_woocommerce_output_content_wrapper_end() {
		echo '</div><!--.woo-->';
	}
endif;


//removing default WooCommerce sidebar - we have our sidebar
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

//removing default breadcrumbs - we have our breadcrumbs
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

//////////////////
//Single Product//
//////////////////

if ( ! function_exists( 'exs_woocommerce_setup_for_customizer' ) ) :
	function exs_woocommerce_setup_for_customizer() {
		//removing page title if it is showing in the title section (to prevent duplication)
		$exs_title = exs_option( 'title_show_title', '' );
		if ( ! empty( $exs_title ) ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
			add_filter( 'woocommerce_show_page_title', '__return_false' );
		}

		// related products separate section
		if( exs_option( 'product_related_separate' ) ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
			add_action( 'exs_action_before_bottom_wrap', 'exs_woocommerce_output_related_products' );
		}
	}
endif;
add_action( 'wp_loaded', 'exs_woocommerce_setup_for_customizer' );


//print meta after price
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 15 );

//related products options
if ( ! function_exists( 'exs_woocommerce_filter_related_products_args' ) ) :
	function exs_woocommerce_filter_related_products_args( $args ) {
		$count = exs_option( 'product_related_products_count' );
		if ( ! empty( $count ) ) {
			$args['posts_per_page'] = (int) $count;
		}
		$cols =  exs_option( 'product_related_products_cols' );
		if ( ! empty( $count ) ) {
			$args['columns'] = (int) $cols;
		}
		return $args;
	}
endif;
add_filter( 'woocommerce_output_related_products_args', 'exs_woocommerce_filter_related_products_args' );

if ( ! function_exists( 'exs_woocommerce_filter_related_products_title' ) ) :
	function exs_woocommerce_filter_related_products_title( $title ) {
		$custom_title = exs_option( 'product_related_products_title' );
		return ! empty( $custom_title ) ? $custom_title : $title;
	}
endif;
add_filter( 'woocommerce_product_related_products_heading', 'exs_woocommerce_filter_related_products_title' );

if ( ! function_exists( 'exs_woocommerce_output_related_products' ) ) :
	function exs_woocommerce_output_related_products() {

		if ( ! is_singular( 'product' ) ) {
			return;
		}

		$exs_container_width    = exs_option( 'product_related_separate_container_width', '' );
		$exs_related_background = exs_option( 'product_related_separate_background', '' );
		$padding_class = 'pt-0 pb-2';
		if ( $exs_related_background ) {
			$padding_class = 'pt-5 pb-2';
		}

		echo '<div class="exs-related-products-wrap container-' . esc_attr( $exs_container_width . ' ' . $exs_related_background . ' ' . $padding_class ) . '">';
		echo '<div class="container">';
		woocommerce_output_related_products();
		echo '</div><!-- .container -->';
		echo '</div><!-- .exs-related-products-wrap -->';

	}
endif;

/////////////////
//Products Loop//
/////////////////

//wrap products counter and filter dropdown to div on products archive
add_action( 'woocommerce_before_shop_loop', 'exs_action_woocommerce_before_shop_loop_open_wrap_div', 15 );
if ( ! function_exists( 'exs_action_woocommerce_before_shop_loop_open_wrap_div' ) ) :
	function exs_action_woocommerce_before_shop_loop_open_wrap_div() {
		$view_switcher  = exs_option( 'shop_layout' );
		$default_layout = exs_option( 'shop_layout_default' );
		$layout_class   = ( ! empty ( $view_switcher ) ) && ( ! empty( $_COOKIE['exs-shop-layout'] ) ) ? sanitize_title( $_COOKIE['exs-shop-layout'] ) : '';
		$layout_class   = empty( $layout_class ) ? $default_layout : $layout_class;
		echo '<div class="row clear woo-count-filter-wrap ' . esc_html( $layout_class ) . '">';
		if ( ! empty( $view_switcher ) ) {

			$shop_layout_list = exs_option( 'shop_layout_list', true );
			$shop_layout_table = exs_option( 'shop_layout_table', false );
			$shop_layout_hide_labels = exs_option( 'shop_layout_hide_labels', true );
			$label_text_class = $shop_layout_hide_labels ? 'screen-reader-text' : '';
			?>
			<div id="shop-layout">
				<label class="label-layout-default">
					<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M6 20q-.825 0-1.412-.587Q4 18.825 4 18q0-.825.588-1.413Q5.175 16 6 16t1.412.587Q8 17.175 8 18q0 .825-.588 1.413Q6.825 20 6 20Zm6 0q-.825 0-1.412-.587Q10 18.825 10 18q0-.825.588-1.413Q11.175 16 12 16t1.413.587Q14 17.175 14 18q0 .825-.587 1.413Q12.825 20 12 20Zm6 0q-.825 0-1.413-.587Q16 18.825 16 18q0-.825.587-1.413Q17.175 16 18 16q.825 0 1.413.587Q20 17.175 20 18q0 .825-.587 1.413Q18.825 20 18 20ZM6 14q-.825 0-1.412-.588Q4 12.825 4 12t.588-1.413Q5.175 10 6 10t1.412.587Q8 11.175 8 12q0 .825-.588 1.412Q6.825 14 6 14Zm6 0q-.825 0-1.412-.588Q10 12.825 10 12t.588-1.413Q11.175 10 12 10t1.413.587Q14 11.175 14 12q0 .825-.587 1.412Q12.825 14 12 14Zm6 0q-.825 0-1.413-.588Q16 12.825 16 12t.587-1.413Q17.175 10 18 10q.825 0 1.413.587Q20 11.175 20 12q0 .825-.587 1.412Q18.825 14 18 14ZM6 8q-.825 0-1.412-.588Q4 6.825 4 6t.588-1.412Q5.175 4 6 4t1.412.588Q8 5.175 8 6t-.588 1.412Q6.825 8 6 8Zm6 0q-.825 0-1.412-.588Q10 6.825 10 6t.588-1.412Q11.175 4 12 4t1.413.588Q14 5.175 14 6t-.587 1.412Q12.825 8 12 8Zm6 0q-.825 0-1.413-.588Q16 6.825 16 6t.587-1.412Q17.175 4 18 4q.825 0 1.413.588Q20 5.175 20 6t-.587 1.412Q18.825 8 18 8Z"/></svg>
					<input type="radio" name="shop-layout" value="default">
					<span class="<?php echo esc_attr( $label_text_class ); ?>"><?php esc_html_e( 'Columns', 'exs' ); ?></span>
				</label>
				<?php if ( $shop_layout_list ) : ?>
				<label class="label-layout-list">
					<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M4.5 19.25q-.525 0-.888-.363-.362-.362-.362-.887t.362-.887q.363-.363.888-.363t.888.363q.362.362.362.887t-.362.887q-.363.363-.888.363Zm3.75-.5v-1.5h12.5v1.5Zm-3.75-5.5q-.525 0-.888-.363-.362-.362-.362-.887t.362-.887q.363-.363.888-.363t.888.363q.362.362.362.887t-.362.887q-.363.363-.888.363Zm3.75-.5v-1.5h12.5v1.5ZM4.5 7.25q-.525 0-.888-.363Q3.25 6.525 3.25 6t.362-.888q.363-.362.888-.362t.888.362q.362.363.362.888t-.362.887q-.363.363-.888.363Zm3.75-.5v-1.5h12.5v1.5Z"/></svg>
					<input type="radio" name="shop-layout" value="list">
					<span class="<?php echo esc_attr( $label_text_class ); ?>"><?php esc_html_e( 'List', 'exs' ); ?></span>
				</label>
				<?php
				endif; //list
				if ( $shop_layout_table ) :
				?>
				<label class="label-layout-table">
					<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M3 18v-2h18v2Zm0-5v-2h18v2Zm0-5V6h18v2Z"/></svg>
					<input type="radio" name="shop-layout" value="table">
					<span class="<?php echo esc_attr( $label_text_class ); ?>"><?php esc_html_e( 'Table', 'exs' ); ?></span>
				</label>
				<?php
				endif; //table
				?>
			</div>
			<?php
		}
	}
endif;
add_action( 'woocommerce_before_shop_loop', 'exs_action_woocommerce_before_shop_loop_close_wrap_div', 35 );
if ( ! function_exists( 'exs_action_woocommerce_before_shop_loop_close_wrap_div' ) ) :
	function exs_action_woocommerce_before_shop_loop_close_wrap_div() {
		echo '</div><!--.woo-count-filter-wrap-->';
	}
endif; //exs_action_woocommerce_before_shop_loop_close_wrap_div

//wrap product and category loop item into div
add_action( 'woocommerce_before_subcategory', 'exs_action_woocommerce_before_shop_loop_item_open_wrap_div', 5 );
add_action( 'woocommerce_before_shop_loop_item', 'exs_action_woocommerce_before_shop_loop_item_open_wrap_div', 5 );
if ( ! function_exists( 'exs_action_woocommerce_before_shop_loop_item_open_wrap_div' ) ) :
	function exs_action_woocommerce_before_shop_loop_item_open_wrap_div() {
		echo '<div class="product-loop-item">';
		echo '<div class="product-thumbnail-wrap">';
	}
endif; //exs_action_woocommerce_before_shop_loop_item_open_wrap_div
add_action( 'woocommerce_after_subcategory', 'exs_action_woocommerce_after_shop_loop_item_close_wrap_div', 15 );
add_action( 'woocommerce_after_shop_loop_item', 'exs_action_woocommerce_after_shop_loop_item_close_wrap_div', 15 );
if ( ! function_exists( 'exs_action_woocommerce_after_shop_loop_item_close_wrap_div' ) ) :
	function exs_action_woocommerce_after_shop_loop_item_close_wrap_div() {
		echo '</div><!--.product-text-wrap-->';
		echo '</div><!--.product-loop-item-->';
	}
endif;

add_action( 'woocommerce_after_shop_loop_item', 'exs_action_woocommerce_after_shop_loop_item_product_short_description', 7 );
if ( ! function_exists( 'exs_action_woocommerce_after_shop_loop_item_product_short_description' ) ) :
	function exs_action_woocommerce_after_shop_loop_item_product_short_description() {
		global $product;
		echo '<div class="product-short-description">';
		echo wp_kses_post( $product->get_short_description() );
		echo '</div><!-- .product-short-description -->';
	}
endif;

//quick view button
if ( class_exists( 'YITH_WCQV_Frontend' ) ) :

	add_filter( 'yith_add_quick_view_button_html', 'exs_filter_yith_add_quick_view_button_html' );
	if ( ! function_exists( 'exs_filter_yith_add_quick_view_button_html' ) ) :
		function exs_filter_yith_add_quick_view_button_html( $html ) {
			return str_replace( 'class="button ', 'class="', $html );
		}
	endif;

	add_action( 'init', 'exs_filter_yith_add_quick_view_remove_button_actions' );
	if ( ! function_exists( 'exs_filter_yith_add_quick_view_remove_button_actions' ) ) :
		function exs_filter_yith_add_quick_view_remove_button_actions() {
			remove_action( 'woocommerce_after_shop_loop_item', array( YITH_WCQV_Frontend::get_instance(), 'yith_add_quick_view_button' ), 15 );
			remove_action( 'yith_wcwl_table_after_product_name', array( YITH_WCQV_Frontend::get_instance(), 'yith_add_quick_view_button' ), 15, 0 );
		}
	endif;
endif;

//closing product link after image
add_action( 'woocommerce_before_shop_loop_item_title', 'exs_action_woocommerce_template_loop_close_link_and_div_after_thumbnail', 11 );
if ( ! function_exists( 'exs_action_woocommerce_template_loop_close_link_and_div_after_thumbnail' ) ) :
	function exs_action_woocommerce_template_loop_close_link_and_div_after_thumbnail() {
		echo '</a>';
		$show_link        = exs_option( 'product_show_thumbnail_link', '' );
		$show_add_to_cart = exs_option( 'product_show_thumbnail_add_to_cart', '' );
		$show_whishlist   = defined( 'YITH_WCWL' );
		$show_quick_view  = class_exists( 'YITH_WCQV_Frontend' );
		//add to cart button options
		$hide_btn  = exs_option( 'product_simple_add_to_cart_hide_button', '' ) ? 'hide-btn' : '';
		$hide_icon = exs_option( 'product_simple_add_to_cart_hide_icon', '' ) ? 'hide-icon' : '';
		$btn_block = exs_option( 'product_simple_add_to_cart_block_button', '' ) ? 'block-btn' : '';
		$center    = exs_option( 'product_center_content', '' ) ? 'text-center' : '';
		//additional product info options
		$show_cat  = exs_option( 'product_show_category', '' ) ? 'show-cat' : '';
		$show_sku  = exs_option( 'product_show_sku', '' ) ? 'show-sku' : '';
		$show_desc = exs_option( 'product_show_short_description', '' ) ? 'show-desc' : '';

		$shop_animation = exs_option( 'shop_animation', '');
		if ( ! empty( $shop_animation ) ) {
			echo '<div class="product-animation-button ' . esc_attr( $btn_block . ' ' . $hide_icon ) . '">';
			woocommerce_template_loop_add_to_cart();
			echo '</div>';
		}

		if ( $show_link || $show_add_to_cart || $show_whishlist ) :
			echo '<div class="product-buttons-wrap ' . esc_attr( $center ) . '">';
			if ( $show_link ) {
				echo '<a class="button view_product" href="' . esc_url( get_the_permalink() ) . '"></a>';
			}
			//YITH WooCommerce Quick View
			if ( $show_quick_view ) {
				echo do_shortcode( '[yith_quick_view]' );
			}
			//YITH WooCommerce Wishlist
			if ( $show_whishlist ) {
				echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
			}
			if ( $show_add_to_cart ) {
				woocommerce_template_loop_add_to_cart();
			}
			echo '</div><!-- .product-buttons-wrap -->';
		endif; //buttons
		echo '</div><!-- .product-thumbnail-wrap -->';
		echo '<div class="product-text-wrap ' . esc_attr( $hide_btn . ' ' . $btn_block . ' ' . $hide_icon . ' ' . $show_cat . ' ' . $show_sku . ' ' . $show_desc . ' ' . $center ) . '">';
	}
endif; //exs_woocommerce_template_loop_close_link_and_div_after_thumbnail

//putting link to product in the product title heading
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'exs_action_woocommerce_template_loop_product_title', 10 );
if ( ! function_exists( 'exs_action_woocommerce_template_loop_product_title' ) ) :
	function exs_action_woocommerce_template_loop_product_title() {
		echo '<h2 class="woocommerce-loop-product__title">';
		woocommerce_template_loop_product_link_open();
		the_title();
		woocommerce_template_loop_product_link_close();
		echo '</h2>';
	}
endif;

//closing category link after image
add_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_link_close', 9 );
remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
//putting link to category in the product category title heading
add_action( 'woocommerce_shop_loop_subcategory_title', 'exs_action_woocommerce_template_loop_category_title', 10 );
if ( ! function_exists( 'exs_action_woocommerce_template_loop_category_title' ) ) :
	function exs_action_woocommerce_template_loop_category_title( $category ) {
		echo '</div><!-- .product-thumbnail-wrap -->';
		echo '<div class="product-text-wrap">';
		echo '<h2 class="woocommerce-loop-category__title">';
		woocommerce_template_loop_category_link_open( $category );
		echo esc_html( $category->name );
		if ( $category->count > 0 ) {
			echo wp_kses(
				apply_filters(
					'exs_woocommerce_subcategory_count_html',
					' <mark class="count">(' . esc_html( $category->count ) . ')</mark>',
					$category
				),
				array(
					'mark' => array(
						'class' => array(),
					),
				)
			);
		}
		woocommerce_template_loop_category_link_close();
		echo '</h2>';
	}
endif;

//add categories to loop
add_action( 'woocommerce_shop_loop_item_title', 'exs_action_woocommerce_shop_loop_item_title_open_wrap', 5 );
if ( ! function_exists( 'exs_action_woocommerce_shop_loop_item_title_open_wrap' ) ) :
	function exs_action_woocommerce_shop_loop_item_title_open_wrap() {
		echo '<div class="product-title-cat-wrap">';
	}
endif;
add_action( 'woocommerce_shop_loop_item_title', 'exs_action_woocommerce_shop_loop_item_title', 20 );
if ( ! function_exists( 'exs_action_woocommerce_shop_loop_item_title' ) ) :
	function exs_action_woocommerce_shop_loop_item_title() {
		global $product;
		?>
		<?php
			if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) && exs_option( 'product_show_sku') ) :
				$sku = $product->get_sku() ? $product->get_sku() : esc_html__( 'N/A', 'exs' );
			?>
			<span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'exs' ); ?> <span class="sku"><?php echo esc_html( $sku ); ?></span></span>
		<?php
		endif;

		echo wp_kses_post( wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">', '</span>' ) );
		echo '</div><!-- .product-title-cat-wrap -->';
	}
endif;

//remove closing A tag from the end of product and category loop item - we have our own earlier
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_after_subcategory', 'woocommerce_template_loop_category_link_close', 10 );

//add rating wrap and reviews count inside it
add_action( 'woocommerce_after_shop_loop_item_title', 'exs_action_woocommerce_after_shop_loop_item_title_rating_wrap_and_reviews_count', 4 );
if ( ! function_exists( 'exs_action_woocommerce_after_shop_loop_item_title_rating_wrap_and_reviews_count' ) ) :
	function exs_action_woocommerce_after_shop_loop_item_title_rating_wrap_and_reviews_count() {
		if ( ! wc_review_ratings_enabled() ) {
			return;
		}
		/*
		 * You can use following to display rating instead of option from customizer
		global $product;
		$review_count = $product->get_review_count();
		$css_class    = $review_count ? 'visible' : 'hidden';
		*/
		$css_class = exs_option( 'product_show_reviews', '' ) ? 'visible' : 'hidden';
		echo '<div class="product-rating-wrap ' . esc_attr( $css_class ) . '">';
	}
endif; //exs_action_woocommerce_after_shop_loop_item_title_rating_wrap_and_reviews_count
add_action( 'woocommerce_after_shop_loop_item_title', 'exs_action_woocommerce_after_shop_loop_item_title_rating_wrap_and_reviews_count_close', 6 );
if ( ! function_exists( 'exs_action_woocommerce_after_shop_loop_item_title_rating_wrap_and_reviews_count_close' ) ) :
	function exs_action_woocommerce_after_shop_loop_item_title_rating_wrap_and_reviews_count_close() {
		if ( ! wc_review_ratings_enabled() ) {
			return;
		}

		if ( comments_open() ) :
			global $product;
			$review_count = $product->get_review_count();
			$link         = apply_filters( 'exs_woocommerce_loop_product_link', get_the_permalink(), $product );

			?>
			<a href="<?php echo esc_url( $link ); ?>#reviews" class="product-review-link" rel="nofollow">
				<?php
				printf(
					esc_html(
						/* translators: 1: number of comments */
						_n(
							'%s review',
							'%s reviews',
							esc_html( $review_count ),
							'exs'
						)
					),
					'<span class="count">' . esc_html( $review_count ) . '</span>'
				);
				?>
			</a>
			<?php
		endif; //comments_open
		echo '</div><!-- .product-rating-wrap -->';
	}
endif; //exs_action_woocommerce_after_shop_loop_item_title_rating_wrap_and_reviews_count

//change woo pagination
add_action( 'woocommerce_after_shop_loop', 'exs_action_woocommerce_after_shop_loop', 9 );
if ( ! function_exists( 'exs_action_woocommerce_after_shop_loop' ) ) :
	function exs_action_woocommerce_after_shop_loop() {
		echo '<div class="nav-links">';
	}
endif;
//change woo pagination
add_action( 'woocommerce_after_shop_loop', 'exs_action_woocommerce_after_shop_loop_end', 1 );
if ( ! function_exists( 'exs_action_woocommerce_after_shop_loop_end' ) ) :
	function exs_action_woocommerce_after_shop_loop_end() {
		echo '</div><!--.nav-links -->';
	}
endif;
//change woo pagination
add_filter( 'woocommerce_pagination_args', 'exs_filter_woocommerce_pagination_args' );
if ( ! function_exists( 'exs_filter_woocommerce_pagination_args' ) ) :
	function exs_filter_woocommerce_pagination_args( $args ) {
		$args['type'] = 'plain';
		$args         = wp_parse_args( exs_get_the_posts_pagination_atts(), $args );
		return $args;
	}
endif;

//change add to cart text for simple product
add_filter( 'woocommerce_product_add_to_cart_text', 'exs_filter_woocommerce_product_add_to_cart_text', 10, 2 );
if ( ! function_exists( 'exs_filter_woocommerce_product_add_to_cart_text' ) ) :
	function exs_filter_woocommerce_product_add_to_cart_text( $text, $class ) {
		if ( 'simple' !== $class->get_type() ) {
			return $text;
		}
		$custom_text = exs_option( 'product_simple_add_to_cart_text', '' );
		if ( empty( $custom_text ) ) {
			return $text;
		}
		if ( ! $class->is_purchasable() && ! $class->is_in_stock() ) {
			return $text;
		}
		$text = esc_html( $custom_text );
		return $text;
	}
endif;


//add autocomplete none for search form
add_filter( 'get_product_search_form', 'exs_filter_woocommerce_get_product_search_form' );
if ( ! function_exists( 'exs_filter_woocommerce_get_product_search_form' ) ) :
	function exs_filter_woocommerce_get_product_search_form( $form_html ) {
		$form_html = str_replace( '<form', '<form autocomplete="off"', $form_html );
		$form_html = str_replace( 'class="woocommerce-product-search"', 'class="woocommerce-product-search search-form"', $form_html );
		$form_html = str_replace( 'class="woocommerce-product-search"', 'class="woocommerce-product-search search-form"', $form_html );
		$form_html = str_replace( '<button type="submit"', '<button type="submit" class="search-submit"', $form_html );
		$form_html = str_replace( '</button>', exs_icon( 'magnify', true ) . '</button>', $form_html );
		return $form_html;
	}
endif;

////////////////////
//cart page layout//
////////////////////
add_action( 'woocommerce_before_cart', 'exs_action_woocommerce_before_cart' );
if ( ! function_exists( 'exs_action_woocommerce_before_cart' ) ) :
	function exs_action_woocommerce_before_cart() {
		echo '<div class ="cart-cols">';
	}
endif;

add_action( 'woocommerce_after_cart', 'exs_action_woocommerce_after_cart' );
if ( ! function_exists( 'exs_action_woocommerce_after_cart' ) ) :
	function exs_action_woocommerce_after_cart() {
		echo '</div><!-- .cart-cols.cols-2 -->';
	}
endif;

//////////
//Blocks//
//////////

//add custom markup for woocommerce grid block
add_filter( 'woocommerce_blocks_product_grid_item_html', 'exs_filter_woocommerce_blocks_product_grid_item_html', 10, 2 );
if ( ! function_exists( 'exs_filter_woocommerce_blocks_product_grid_item_html' ) ) :
	function exs_filter_woocommerce_blocks_product_grid_item_html( $html, $data ) {
		$hide_icon = exs_option( 'product_simple_add_to_cart_hide_icon', '' ) ? 'hide-icon' : '';
		$btn_block = exs_option( 'product_simple_add_to_cart_block_button', '' ) ? 'block-btn' : '';
		$center    = exs_option( 'product_center_content', '' ) ? 'text-center' : '';

		$show_link        = exs_option( 'product_show_thumbnail_link', '' );
		$show_add_to_cart = exs_option( 'product_show_thumbnail_add_to_cart', '' );

		$btns_link_html = '';
		if ( $show_link || $show_add_to_cart ) :
			$btns_link_html .= '<div class="product-buttons-wrap ' . esc_attr( $center ) . '">';
			if ( $show_link ) {
				$btns_link_html .= '<a class="button view_product" href="' . esc_url( $data->permalink ) . '"></a>';
			}
			if ( $show_add_to_cart ) {
				$btns_link_html .= $data->button;
			}
			$btns_link_html .= '</div><!-- .product-buttons-wrap -->';
		endif; //buttons


		$shop_animation     = exs_option( 'shop_animation', '');
		$animation_btn_html = '';
		if ( ! empty( $shop_animation ) ) {
			$animation_btn_html .= '<div class="product-animation-button ' . esc_attr( $btn_block . ' ' . $hide_icon ) . '">';
			$animation_btn_html .= $data->button;
			$animation_btn_html .= '</div>';
		}

		return '<li class="product wc-block-grid__product">' .
				'<div class="product-loop-item">' .
					'<div class="product-thumbnail-wrap">' .
						'<a href="' . esc_url( $data->permalink ) . '" class="wc-block-grid__product-link woocommerce-LoopProduct-link woocommerce-loop-product__link">' .
							wp_kses(
								$data->badge,
								array(
									'span' => array(
										'class'       => true,
										'aria-hidden' => true,
									),
									'div' => array(
										'class'       => true,
									),
								)
							) .
							wp_kses(
								$data->image,
								array(
									'div' => array(
										'class' => true
									),
									'img' => array(
										'class'  => true,
										'srcset' => true,
										'width'  => true,
										'height' => true,
										'sizes'  => true,
										'alt'    => true,
									)
								)
							) .
						'</a>' .
						wp_kses(
							$animation_btn_html,
							array(
								'div'  => array(
									'class' => true,
								),
								'a'    => array(
									'href'             => true,
									'class'            => true,
									'title'            => true,
									'aria-label'       => true,
									'data-quantity'    => true,
									'data-product_id'  => true,
									'data-product_sku' => true,
									'rel'              => true,
								),
								'span' => array(
									'class' => true,
									)
								)
							) .
						wp_kses(
							$btns_link_html,
							array(
								'div'  => array(
									'class' => true,
								),
								'a'    => array(
									'href'             => true,
									'class'            => true,
									'title'            => true,
									'aria-label'       => true,
									'data-quantity'    => true,
									'data-product_id'  => true,
									'data-product_sku' => true,
									'rel'              => true,
								),
								'span' => array(
									'class' => true,
									)
								)
							) .
					'</div>' .
					'<div class="product-text-wrap ' . esc_attr( $hide_icon . ' ' . $btn_block . ' ' . $center ) . '">' .
						'<h2 class="woocommerce-loop-product__title">' .
							'<a href="' . esc_url( $data->permalink ) . '" class="wc-block-grid__product-link">' .
								wp_kses( $data->title, array() ) .
							'</a>' .
						'</h2>'.
						wp_kses(
							$data->price,
							array(
								'div'  => array(
									'class' => true,
								),
								'span' => array(
									'class' => true
								),
								'del'  => true,
								'ins'  => true,
							)
						) .
						wp_kses(
							$data->rating,
							array(
								'div'    => array(
									'class'      => true,
									'role'       => true,
									'aria-label' => true,
								),
								'span'   => array(
									'width' => true,
									'style' => true,
									'class' => true,
								),
								'strong' => array(
									'class' => true,
								)
							)
						) .
						// data-product_id and data-product_sku are stripped out:
						// https://core.trac.wordpress.org/ticket/33121
						wp_kses(
							$data->button,
							array(
								'div'  => array(
									'class' => true,
								),
								'a'    => array(
									'href'             => true,
									'class'            => true,
									'title'            => true,
									'aria-label'       => true,
									'data-quantity'    => true,
									'data-product_id'  => true,
									'data-product_sku' => true,
									'rel'              => true,
								),
								'span' => array(
									'class' => true,
								)
							)
						) .
					'</div>' .
				'</div>' .
			'</li>';
	}
endif;

/*
add .products to block UL
uncomment, if needed
*/
//add_filter( 'the_content', 'exs_filter_woocommerce_the_content' );
add_filter( 'render_block', 'exs_filter_woocommerce_the_content' );
if ( ! function_exists( 'exs_filter_woocommerce_the_content' ) ) :
	function exs_filter_woocommerce_the_content( $html ) {
		return str_replace( 'ul class="wc-block-grid__products"', 'ul class="products wc-block-grid__products"', $html );
	}
endif;

//add class to checkout button
remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );
add_action( 'woocommerce_widget_shopping_cart_buttons', 'exs_action_woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );
if ( ! function_exists( 'exs_action_woocommerce_widget_shopping_cart_proceed_to_checkout' ) ) :
	function exs_action_woocommerce_widget_shopping_cart_proceed_to_checkout() {
		echo '<a href="' . esc_url( wc_get_checkout_url() ) . '" class="button alt checkout wc-forward">' . esc_html__( 'Checkout', 'exs' ) . '</a>';
	}
endif;

//add spans to filter widget braces
add_filter( 'woocommerce_layered_nav_count', 'exs_filter_woocommerce_layered_nav_count', 10, 2 );
if ( ! function_exists( 'exs_filter_woocommerce_layered_nav_count' ) ) :
	function exs_filter_woocommerce_layered_nav_count( $html, $count ) {
		return '<span class="count"><span class="count-open">(</span>' . absint( $count ) . '<span class="count-close">)</span></span>';
	}
endif;

//add sidebar position option for product and shop
add_filter( 'exs_customizer_options', 'exs_filter_exs_customizer_options' );
if ( ! function_exists( 'exs_filter_exs_customizer_options' ) ) :
	function exs_filter_exs_customizer_options( $options ) {
		//sections
		$options['section_exs_woocommerce_layout']   = array(
			'type'        => 'section',
			'panel'       => 'woocommerce',
			'label'       => esc_html__( 'ExS Shop Layout', 'exs' ),
			'description' => esc_html__( 'These options let you manage sidebar positions on the shop and product pages and other layout options.', 'exs' ),
		);
		$options['section_exs_woocommerce_product_layout']   = array(
			'type'        => 'section',
			'panel'       => 'woocommerce',
			'label'       => esc_html__( 'ExS Singe Product Layout', 'exs' ),
			'description' => esc_html__( 'These options let you manage your single product page layout.', 'exs' ),
		);
		$options['section_exs_woocommerce_products'] = array(
			'type'        => 'section',
			'panel'       => 'woocommerce',
			'label'       => esc_html__( 'ExS Products List', 'exs' ),
			'description' => esc_html__( 'These options lets you manage your shop page layout.', 'exs' ),
		);

		$options['shop_products_list_extra'] = array(
			'type'        => 'extra-button',
			'section'     => 'section_exs_woocommerce_products',
			'label'       => esc_html__( 'Products display options', 'exs' ),
			'description' => esc_html__( 'Change your products list layout easily in your Customizer', 'exs' ),
		);

		//options
		$options['header_cart_dropdown'] = array(
			'type'        => 'checkbox',
			'section'     => 'section_exs_woocommerce_layout',
			'default'     => exs_option( 'header_cart_dropdown', '' ),
			'label'       => esc_html__( 'Show Cart Dropdown in Header', 'exs' ),
			'description' => esc_html__( 'Show cart icon in header with product count in shopping cart if added.', 'exs' ),
		);

		//shop
		$options['shop_sidebar_position'] = array(
			'type'        => 'radio',
			'section'     => 'section_exs_woocommerce_layout',
			'default'     => exs_option( 'shop_sidebar_position', 'right' ),
			'label'       => esc_html__( 'Shop sidebar position', 'exs' ),
			'description' => esc_html__( 'This option let you manage sidebar position on the shop page. Shop sidebar needs to be not empty to display.', 'exs' ),
			'choices'     => exs_get_sidebar_position_options(),
		);
		$options['shop_container_width']   = array(
			'type'    => 'radio',
			'section' => 'section_exs_woocommerce_layout',
			'label'   => esc_html__( 'Shop container max width', 'exs' ),
			'default' => esc_html( exs_option( 'shop_container_width', '' ) ),
			'choices' => array(
				''     => esc_html__( 'Inherit from Global', 'exs' ),
				'1400' => esc_html__( '1400px', 'exs' ),
				'1140' => esc_html__( '1140px', 'exs' ),
				'960'  => esc_html__( '960px', 'exs' ),
				'720'  => esc_html__( '720px', 'exs' ),
			),
		);
		$options['shop_animation']   = array(
			'type'    => 'select',
			'section' => 'section_exs_woocommerce_layout',
			'label'   => esc_html__( 'Products animation type', 'exs' ),
			'default' => esc_html( exs_option( 'shop_animation', '' ) ),
			'description' => esc_html__( 'Select buttons animation type for products list.', 'exs' ),
			'choices' => array(
				''     => esc_html__( 'Default', 'exs' ),
				'1' => esc_html__( 'Type 1', 'exs' ),
				'2' => esc_html__( 'Type 2', 'exs' ),
				'3' => esc_html__( 'Type 3', 'exs' ),
			),
		);
		$options['cart_icon'] = array(
			'type'        => 'select',
			'section'     => 'section_exs_woocommerce_layout',
			'default'     => exs_option( 'cart_icon', '1' ),
			'label'       => esc_html__( 'Cart icon type', 'exs' ),
			'description' => esc_html__( 'Select cart icon type to optionally show in the header and on "Add to Cart" button for simple products.', 'exs' ),
			'choices'     => array(
				'1'                         => esc_html__( 'Default Icon', 'exs' ),
				'cart-google'               => esc_html__( 'Google Icon', 'exs' ),
				'cart-google-outline'       => esc_html__( 'Google Outline Icon', 'exs' ),
				'cart-ionic'                => esc_html__( 'Ionic Icon', 'exs' ),
				'cart-ionic-outline'        => esc_html__( 'Ionic Outline Icon', 'exs' ),
				'cart-lineicons'            => esc_html__( 'Lineicons Icon', 'exs' ),
				'cart-google-basket'        => esc_html__( 'Google Basket Icon', 'exs' ),
				'cart-ionic-bag'            => esc_html__( 'Ionic Bag Icon', 'exs' ),
				'cart-ionic-bag-outline'    => esc_html__( 'Ionic Bag Outline Icon', 'exs' ),
				'cart-ionic-basket'         => esc_html__( 'Ionic Basket Icon', 'exs' ),
				'cart-ionic-basket-outline' => esc_html__( 'Ionic Basket Outline Icon', 'exs' ),
			),
		);

		//single product
		$options['product_sidebar_position'] = array(
			'type'        => 'radio',
			'section'     => 'section_exs_woocommerce_product_layout',
			'default'     => exs_option( 'product_sidebar_position', 'right' ),
			'label'       => esc_html__( 'Product sidebar position', 'exs' ),
			'description' => esc_html__( 'This option let you manage sidebar position on product pages. Shop sidebar needs to be not empty to display.', 'exs' ),
			'choices'     => exs_get_sidebar_position_options(),
		);
		$options['product_container_width']   = array(
			'type'    => 'radio',
			'section' => 'section_exs_woocommerce_product_layout',
			'label'   => esc_html__( 'Product container max width', 'exs' ),
			'default' => esc_html( exs_option( 'product_container_width', '' ) ),
			'choices' => array(
				''     => esc_html__( 'Inherit from Global', 'exs' ),
				'1400' => esc_html__( '1400px', 'exs' ),
				'1140' => esc_html__( '1140px', 'exs' ),
				'960'  => esc_html__( '960px', 'exs' ),
				'720'  => esc_html__( '720px', 'exs' ),
			),
		);

		$options['product_related_products_heading'] = array(
			'type'        => 'block-heading',
			'section'     => 'section_exs_woocommerce_product_layout',
			'label'       => esc_html__( 'Related products options', 'exs' ),
		);

		$options['product_related_products_title'] = array(
			'type'        => 'text',
			'section'     => 'section_exs_woocommerce_product_layout',
			'default'     => exs_option( 'product_related_products_title', '' ),
			'label'       => esc_html__( 'Custom Related Products title', 'exs' ),
			'description' => esc_html__( '"Related Products" is default value.', 'exs' ),
		);

		$options['product_related_products_count'] = array(
			'type'        => 'number',
			'section'     => 'section_exs_woocommerce_product_layout',
			'default'     => exs_option( 'product_related_products_count', '' ),
			'label'       => esc_html__( 'Custom Related Products number', 'exs' ),
			'description' => esc_html__( '"4" is default value.', 'exs' ),
		);

		$options['product_related_products_cols'] = array(
			'type'        => 'number',
			'section'     => 'section_exs_woocommerce_product_layout',
			'default'     => exs_option( 'product_related_products_cols', '' ),
			'label'       => esc_html__( 'Custom Related Products Columns number', 'exs' ),
			'description' => esc_html__( '"4" is default value.', 'exs' ),
		);

		$options['product_related_separate'] = array(
			'type'        => 'checkbox',
			'section'     => 'section_exs_woocommerce_product_layout',
			'default'     => exs_option( 'product_related_separate', '' ),
			'label'       => esc_html__( 'Show related products below main content', 'exs' ),
			'description' => esc_html__( 'You can move related products on the single product page below the main content.', 'exs' ),
		);

		$options['product_related_separate_background'] = array(
			'type'        => 'color-radio',
			'section'     => 'section_exs_woocommerce_product_layout',
			'default'     => exs_option( 'product_related_separate_background', '' ),
			'label'       => esc_html__( 'Related products section background', 'exs' ),
			'choices'     => exs_customizer_backgrounds_array(),
			'visible' => array(
					'key' => 'product_related_separate',
					'value' => true,
			),
		);
		$options['product_related_separate_container_width'] = array(
			'type'        => 'radio',
			'section'     => 'section_exs_woocommerce_product_layout',
			'default'     => exs_option( 'product_related_separate_container_width', '' ),
			'label'       => esc_html__( 'Related products section container width', 'exs' ),
			'choices' => array(
				''     => esc_html__( 'Inherit from Global', 'exs' ),
				'1400' => esc_html__( '1400px', 'exs' ),
				'1140' => esc_html__( '1140px', 'exs' ),
				'960'  => esc_html__( '960px', 'exs' ),
				'720'  => esc_html__( '720px', 'exs' ),
			),
			'visible' => array(
				'key' => 'product_related_separate',
				'value' => true,
			),
		);

		return $options;
	}
endif;

//not work - because of esc_html on text
add_filter( 'woocommerce_product_add_to_cart_text1', function ( $text ) {
	$icon = exs_option( 'header_cart_dropdown', '' );
	if ( '' === $icon || 1 === $icon || '1' === $icon ) {
		return $text;
	} else {
		return exs_icon( $icon, true ) . $text;
	}

} );

//add svg cart icon to simple product to a loop add to cart button
add_filter( 'woocommerce_loop_add_to_cart_link', function ( $html, $product, $args ) {
	$icon = exs_option( 'cart_icon', '' );
	if ( empty( $icon ) || 'simple' !== $product->get_type() || exs_option( 'product_simple_add_to_cart_hide_icon' ) || 1 === $icon || '1' === $icon ) {
		return $html;
	} else {
		$html = str_replace( 'product_type_simple', 'product_type_simple with_svg_icon', $html );
		return str_replace( '">', '">' . exs_icon( $icon, true ) , $html );
	}
}, 10, 3 );