<?php
/**
 * The #overlay template file. Moved here for the AMP plugin support
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 2.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div id="overlay"
	<?php
	if ( defined( 'AMP__VERSION' ) ) :
		?>
		on="tap:search_dropdown.toggleClass(class='active',force=false),body.toggleClass(class='search-dropdown-active',force=false)
		<?php if ( exs_option( 'header', '' ) && has_nav_menu( 'primary' ) ) : ?>
		,nav_top.toggleClass(class='active',force=false),nav_toggle.toggleClass(class='active',force=false),body.toggleClass(class='top-menu-active',force=false)
		<?php
		endif; //header exists check
		if ( is_active_sidebar( 'sidebar-side-fixed' ) ) :
		?>
		,sfix.toggleClass(class='active',force=false),sfix_toggle.toggleClass(class='active',force=false),body.toggleClass(class='sfix-active',force=false)
		<?php
		endif; //fixed sidebar check
		if ( has_nav_menu( 'side' ) || is_active_sidebar( 'sidebar-side' ) ) :
		?>
		,nav_side.toggleClass(class='active',force=false),nav_side_toggle.toggleClass(class='active',force=false),body.toggleClass(class='side-menu-active',force=false)
		<?php endif; //side menu check ?>
		"
	<?php endif; //AMP plugin active ?>
></div>