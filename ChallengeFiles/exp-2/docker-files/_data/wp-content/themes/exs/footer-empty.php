<?php
/**
 * The template for displaying the footer
 *
 * Contains the footer.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div id="search_dropdown">
	<?php get_search_form(); ?>
</div><!-- #search_dropdown -->
<button
	id="search_modal_close"
	class="nav-btn active"
	aria-controls="search_dropdown"
	aria-expanded="true"
	aria-label="<?php esc_attr_e( 'Search Toggler', 'exs' ); ?>"
	<?php if ( defined( 'AMP__VERSION' ) ) : ?>
	on="tap:search_dropdown.toggleClass(class='active'),search_modal_close.toggleClass(class='active'),body.toggleClass(class='search-dropdown-active')"
	<?php endif; ?>
>
	<span></span>
</button>

<?php
//if there is no header chosen  we need to show #overlay here for side menu overlay
$exs_header = exs_option( 'header', '' );
if ( empty( $exs_header ) ) :
	get_template_part( 'template-parts/header/overlay' );
endif; //header

get_template_part( 'template-parts/footer/footer-totop' );

/**
 * Fires at the bottom of whole web page before the wp_footer function.
 *
 * @since ExS 0.0.4
 */
do_action( 'exs_action_before_wp_footer' );

wp_footer();
?>
</body>
</html>
