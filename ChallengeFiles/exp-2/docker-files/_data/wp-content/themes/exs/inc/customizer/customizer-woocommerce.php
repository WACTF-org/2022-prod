<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'exs_woocommerce_pages_ajax_render' ) ) :
	function exs_woocommerce_pages_ajax_render() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return '';
		}

		if ( is_shop() ) {
			/**
			 * Hook: woocommerce_before_main_content.
			 *
			 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
			 * @hooked woocommerce_breadcrumb - 20
			 * @hooked WC_Structured_Data::generate_website_data() - 30
			 */
			do_action( 'woocommerce_before_main_content' );

			?>
			<header class="woocommerce-products-header">
				<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
					<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
				<?php endif; ?>

				<?php
				/**
				 * Hook: woocommerce_archive_description.
				 *
				 * @hooked woocommerce_taxonomy_archive_description - 10
				 * @hooked woocommerce_product_archive_description - 10
				 */
				do_action( 'woocommerce_archive_description' );
				?>
			</header>
			<?php
			if ( woocommerce_product_loop() ) {

				/**
				 * Hook: woocommerce_before_shop_loop.
				 *
				 * @hooked woocommerce_output_all_notices - 10
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );

				woocommerce_product_loop_start();

				if ( wc_get_loop_prop( 'total' ) ) {
					while ( have_posts() ) {
						the_post();

						/**
						 * Hook: woocommerce_shop_loop.
						 */
						do_action( 'woocommerce_shop_loop' );

						wc_get_template_part( 'content', 'product' );
					}
				}

				woocommerce_product_loop_end();

				/**
				 * Hook: woocommerce_after_shop_loop.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			} else {
				/**
				 * Hook: woocommerce_no_products_found.
				 *
				 * @hooked wc_no_products_found - 10
				 */
				do_action( 'woocommerce_no_products_found' );
			}

			/**
			 * Hook: woocommerce_after_main_content.
			 *
			 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action( 'woocommerce_after_main_content' );
		} //is_shop

		if ( is_product() ) {
			/**
			 * woocommerce_before_main_content hook.
			 *
			 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
			 * @hooked woocommerce_breadcrumb - 20
			 */
			do_action( 'woocommerce_before_main_content' );
		?>

			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>

				<?php wc_get_template_part( 'content', 'single-product' ); ?>

			<?php endwhile; // end of the loop. ?>

		<?php
			/**
			 * woocommerce_after_main_content hook.
			 *
			 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action( 'woocommerce_after_main_content' );
		?>

		<?php
			/**
			 * woocommerce_sidebar hook.
			 *
			 * @hooked woocommerce_get_sidebar - 10
			 */
			do_action( 'woocommerce_sidebar' );
		} //is_product

		if ( is_checkout() ) {
			// Show non-cart errors.
			do_action( 'woocommerce_before_checkout_form_cart_notices' );

			// Check cart contents for errors.
			do_action( 'woocommerce_check_cart_items' );

			// Calc totals.
			WC()->cart->calculate_totals();

			// Get checkout object.
			$checkout = WC()->checkout();

			if ( empty( $_POST ) && wc_notice_count( 'error' ) > 0 ) { // WPCS: input var ok, CSRF ok.

				wc_get_template( 'checkout/cart-errors.php', array( 'checkout' => $checkout ) );
				wc_clear_notices();

			} else {

				$non_js_checkout = ! empty( $_POST['woocommerce_checkout_update_totals'] ); // WPCS: input var ok, CSRF ok.

				if ( wc_notice_count( 'error' ) === 0 && $non_js_checkout ) {
					wc_add_notice( __( 'The order totals have been updated. Please confirm your order by pressing the "Place order" button at the bottom of the page.', 'exs' ) );
				}

				wc_get_template( 'checkout/form-checkout.php', array( 'checkout' => $checkout ) );

			}
		}
	}
endif;
