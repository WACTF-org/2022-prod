<?php
/**
 * The Fixed sidebar template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 1.8.11
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! is_active_sidebar( 'sidebar-side-fixed' ) ) {
	if( is_customize_preview() ) {
		echo '<section id="sfix" class="d-none"></section>';
	}
	return;
}

$class_bg     = exs_option( 'fixed_sidebar_background', 'l' );
$class_border = exs_option( 'fixed_sidebar_border', '1' ) ? 'sfix-border' : '';
$class_shadow = exs_option( 'fixed_sidebar_shadow', '' ) ? 'sfix-shadow' : '';
$class_fs     = exs_option( 'fixed_sidebar_font_size', '' );
$class_fs     = $class_fs ? 'fs-' . $class_fs : '';

?>
<section id="sfix" class="<?php echo esc_attr( $class_bg . ' ' . $class_border . ' ' . $class_shadow . ' ' . $class_fs ); ?>">
	<div class="sidebar-wrap">
		<?php dynamic_sidebar( 'sidebar-side-fixed' ); ?>
	</div>
	<button id="sfix_toggle" class="i"
	        aria-controls="sfix"
	        aria-expanded="false"
	        aria-label="<?php esc_attr_e( 'Fixed Sidebar Toggler', 'exs' ); ?>"
		<?php if ( defined( 'AMP__VERSION' ) ) : ?>
			on="tap:sfix.toggleClass(class='active'),sfix_toggle.toggleClass(class='active'),body.toggleClass(class='sfix-active')"
		<?php endif; ?>
	>
		<span></span>
	</button>
</section>
