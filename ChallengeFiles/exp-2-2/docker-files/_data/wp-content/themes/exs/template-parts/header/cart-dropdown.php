<?php
/**
 * The WooCommerce cart dropdown template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! is_cart() && ! is_checkout() && exs_option( 'header_cart_dropdown' ) ) :
	$icon = exs_option( 'cart_icon', '1' );
	?>
	<div class="cart-dropdown">
		<a class="dropdown-toggle" href="#" role="button" id="dropdown-cart-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
			<?php if ( defined( 'AMP__VERSION' ) ) : ?>
			on="tap:dropdown-cart.toggleClass(class='active'),dropdown-cart-toggle.toggleClass(class='active'),body.toggleClass(class='cart-dropdown-active')"
			<?php endif; ?>
		>
			<?php
			//old - checkbox or new - default 1 or '1'
			if ( 1 === $icon || '1' === $icon ) :
			?>
			<span class="wc-icon-cart"></span>
			<?php
			else :
				//new icons
				exs_icon( $icon );
			endif;
			echo '<span class="cart-count">';
		if ( ( WC()->cart ) && ( WC()->cart->get_cart_contents_count() !== 0 ) ) {
			echo esc_html( WC()->cart->get_cart_contents_count() );
		}
			echo '</span>';
		?>
		</a>
		<div class="cart-dropdown-menu dropdown-menu-right" id="dropdown-cart" aria-labelledby="dropdown-cart-toggle">
			<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
		</div>
	</div>
	<?php
endif; //is_cart check
