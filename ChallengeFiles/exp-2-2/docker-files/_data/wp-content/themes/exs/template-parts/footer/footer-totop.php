<?php
/**
 * The footer section blank template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_customize_preview() ) {
	echo '<div id="to-top-wrap">';
}

$exs_to_top = exs_option( 'totop', '' );
//page totop button
if ( ! empty( $exs_to_top ) ) :
	?>
	<a id="to-top" href="#body">
		<span class="screen-reader-text">
			<?php esc_html_e( 'Go to top', 'exs' ); ?>
		</span>
	</a>
<?php
endif; //totop_enabled

$exs_mouse_cursor = exs_option( 'mouse_cursor_enabled', '' );
//mouse cursor
if ( ! empty( $exs_mouse_cursor ) ) :
	$mouse_cursor_border        = exs_option( 'mouse_cursor_border', '' );
	$mouse_cursor_border        = $mouse_cursor_border ? exs_get_color_name_based_on_bg_class( $mouse_cursor_border ) : 'transparent';
	$mouse_cursor_size          = exs_option( 'mouse_cursor_size', '20' );
	$mouse_cursor_size          = $mouse_cursor_size ? $mouse_cursor_size : '20';
	$mouse_cursor_opacity       = exs_option( 'mouse_cursor_opacity', '0.7' );
	$mouse_cursor_opacity       = $mouse_cursor_opacity ? $mouse_cursor_opacity : '0.7';
	$mouse_cursor_opacity_hover = exs_option( 'mouse_cursor_opacity_hover', '0.3' );
	$mouse_cursor_opacity_hover = $mouse_cursor_opacity_hover ? $mouse_cursor_opacity_hover : '0.3';
	$mouse_cursor_hidden_class  = exs_option( 'mouse_cursor_hidden', '' );
?>
<div
	id="m-cursor"
	class="<?php echo esc_attr( exs_option( 'mouse_cursor_background', 'i c' ) . ' ' . $mouse_cursor_hidden_class ); ?>"
	style="margin:0;opacity:<?php echo esc_attr( $mouse_cursor_opacity ); ?>;width:<?php echo esc_attr( $mouse_cursor_size ) ?>px;height:<?php echo esc_attr( $mouse_cursor_size ) ?>px;position:fixed;z-index:10000;pointer-events:none;border-radius:50%;transition:opacity .2s ease-in-out,transform .5s ease,left .1s ease-out,top .1s ease-out;transform:translate(-50%,-50%);border: 1px solid var(--<?php echo esc_attr( $mouse_cursor_border ); ?>)"
>
</div>
<script>
	'use strict';
	(function(d) {
		var el=d.getElementById('m-cursor');
		var timer;
		d.addEventListener('mousemove', function(e) {
			if('A'===e.target.tagName||'INPUT'===e.target.tagName||'BUTTON'===e.target.tagName||'TEXTAREA'===e.target.tagName||'LABEL'===e.target.tagName||e.target.closest('a')) {
				el.style.transform='translate(-50%,-50%) scale(1.3)';
				el.style.opacity=<?php echo esc_html( $mouse_cursor_opacity_hover ); ?>;
			} else {
				el.style.transform='translate(-50%,-50%) scale(1)';
				el.style.opacity=<?php echo esc_html( $mouse_cursor_opacity ); ?>;
			}
			var x=e.clientX;
			var y=e.clientY;
			el.style.top=y+'px';
			el.style.left=x+'px';
			clearTimeout(timer);
			timer=setTimeout(function(){
				el.style.opacity=0;
			}, 1000);
		});
		d.addEventListener('click', function(e) {
			el.style.transform='translate(-50%,-50%) scale(0.2)';
			el.style.opacity=0.2;
		});
	})(document);
</script>
<?php
endif; //mouse cursor

//read progress bar - since 1.9.3
if ( is_singular( 'post' ) && exs_option( 'blog_single_read_progress_enabled' ) ) :
	$height      = exs_option( 'blog_single_read_progress_height' );
	//5 px is default height
	$height      = empty( $height ) ? '5' : $height;
	$position    = exs_option( 'blog_single_read_progress_position' );
	$position    = empty( $position ) ? 'top' : $position;
	$bg          = exs_option( 'blog_single_read_progress_background' );
	$progress_bg = exs_option( 'blog_single_read_progress_bar_background' );
	$progress_bg = empty( $progress_bg ) ? 'i c c2' : $progress_bg;
	?>
	<div id="read-progress" class="<?php echo esc_attr( $bg ); ?> read-progress-<?php echo esc_attr( $position ); ?>" style="position:fixed;width:100%;z-index:10000;height:<?php echo esc_attr( $height ); ?>px;<?php echo esc_attr( $position ); ?>:0">
		<div class="<?php echo esc_attr( $progress_bg ); ?>" style="position:absolute;width:0;height:<?php echo esc_attr( $height ); ?>px"></div>
	</div>
<?php
endif; //read progress

if ( is_customize_preview() ) {
	echo '</div>';
}
