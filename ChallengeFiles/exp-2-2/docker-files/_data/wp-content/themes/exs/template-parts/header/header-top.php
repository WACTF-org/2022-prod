<?php
/**
 * The header top template file
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

//wrapper for topline, toplogo, header, intro, intro-teasers and title
//css class for top and bottom container width - is always global customizer option
$exs_container_width = exs_option( 'main_container_width', '1140' );
echo '<div id="top-wrap" class="container-' . esc_attr( $exs_container_width ) . '">';

$exs_intro_position = exs_option( 'intro_position', '' );
//intro section on front page after header
if ( exs_is_front_page() && 'before' === $exs_intro_position ) :

	get_template_part( 'template-parts/header/intro' );

endif;

$exs_header_image_url = get_header_image();
if ( ! empty( $exs_header_image_url ) ) :
	$exs_background_image = exs_section_background_image_array( 'header_image', true );
	$exs_background_iverse = exs_option( 'header_image_background_image_inverse', true ) ? 'i' : '';

	?>
	<div id="header-image"
	class="<?php echo esc_attr( $exs_background_iverse . ' ' . $exs_background_image['class'] ); ?>"
	style="background-image: url('<?php echo esc_url( $exs_background_image['url'] ); ?>');<?php echo esc_attr( $exs_background_image['overlay'] ); ?>"
	>
<?php
endif; //header_image_url

$exs_header_absolute = exs_option( 'header_absolute', '' ) || is_page_template( 'page-templates/header-overlap.php' );
if ( ! empty( $exs_header_absolute ) ) :
	?>
	<div id="header-absolute-wrap" class="header-absolute-wrap">
	<div class="header-absolute-content">
<?php
endif; //$exs_header_absolute

//topline header section
//if topline is inside #header section for sticky - not loading it here
if ( '4' !== exs_option( 'header' ) ) {
	get_template_part( 'template-parts/header/topline/topline', exs_template_part( 'topline', '' ) );
}

//header section
get_template_part( 'template-parts/header/header', exs_template_part( 'header', '1' ) );

//header bottom section with header-bottom sidebar
get_template_part( 'template-parts/header-bottom/section', exs_template_part( 'header_bottom', '1' ) );

//title section not on front page
if ( exs_is_title_section_is_shown() ) :
	get_template_part( 'template-parts/title/title', exs_template_part( 'title', '1' ) );
//front page text
else :
	//TODO homepage fullwidth image
	$exs_display_header_text = display_header_text();
	if ( ! empty( $exs_display_header_text ) ) :
		?>
		<h1 class="site-title">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php echo wp_kses_post( get_bloginfo( 'name', 'display' ) ); ?>
			</a>
		</h1>
		<?php
		$exs_description = get_bloginfo( 'description', 'display' );

		if ( $exs_description || is_customize_preview() ) :
			?>
			<p class="site-description"><?php echo wp_kses_post( $exs_description ); ?></p>
		<?php
		endif; //description
	endif; //display_header_text
endif; //exs_is_front_page

if ( ! empty( $exs_header_absolute ) ) :
	?>
	</div><!-- .header-absolute-content -->
	</div><!-- .header-absolute-wrap -->
<?php
endif; //$exs_header_absolute

/**
 * Fires after the header.
 *
 * @since ExS 0.0.1
 */
do_action( 'exs_action_after_header' );

//intro section on front page after header
if ( exs_is_front_page() && 'after' === $exs_intro_position ) :
	get_template_part( 'template-parts/header/intro' );
endif;

if ( ! empty( $exs_header_image_url ) ) :
	?>
	</div><!-- #header-image-->
<?php
endif; //$exs_header_image_url

//intro teasers section on front page
if ( exs_is_front_page() ) :
	get_template_part( 'template-parts/header/intro-teasers' );
endif;

echo '</div><!-- #top-wrap-->';
