<?php
/**
 * Template for displaying search forms
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.1
 *
 */

$exs_unique_id = uniqid( 'search-form-' );

?>
<form autocomplete="off" role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">

	<input
		type="search"
		id="<?php echo esc_attr( $exs_unique_id ); ?>"
		class="search-field"
		placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', 'exs' ); ?>"
		value="<?php echo get_search_query(); ?>"
		name="s"
	/>
	<?php
	//fix for Elementor builder
	if ( function_exists( 'exs_icon' ) ) :
		?>
		<button type="submit" class="search-submit"><?php exs_icon( 'magnify' ); ?>
			<span class="screen-reader-text"><?php echo esc_html_x( 'Search', 'submit button', 'exs' ); ?></span>
		</button>
		<?php
	else:
		?>
		<button type="submit" class="search-submit-unstyled">
			<span><?php echo esc_html_x( 'Search', 'submit button', 'exs' ); ?></span>
		</button>
		<?php
	endif;
	?>

	<label for="<?php echo esc_attr( $exs_unique_id ); ?>" class="screen-reader-text">
		<?php echo esc_html_x( 'Search for:', 'label', 'exs' ); ?>
	</label>

</form><!-- .search-form -->
