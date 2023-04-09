<?php

/**
 * bbPress search
 *
 * @package bbPress
 * @subpackage ExS
 * @since 0.2.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'bbp_allow_search' ) ) {
	return;
}

$exs_unique_id = uniqid( 'search-form-' );

if ( bbp_allow_search() ) :
	?>

	<div class="bbp-search-form">
		<form autocomplete="off" role="search" method="get" class="bbp-search-form search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<div>
				<input
					type="search"
					id="<?php echo esc_attr( $exs_unique_id ); ?>"
					class="search-field"
					placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', 'exs' ); ?>"
					value="<?php bbp_search_terms(); ?>"
					name="ts"
				/>
				<input type="hidden" name="action" value="bbp-search-request" />
				<button type="submit" class="search-submit"><?php exs_icon( 'magnify' ); ?>
					<span class="screen-reader-text"><?php echo esc_html_x( 'Search', 'submit button', 'exs' ); ?></span>
				</button>

				<label for="<?php echo esc_attr( $exs_unique_id ); ?>" class="screen-reader-text">
					<?php echo esc_html_x( 'Search topics:', 'label', 'exs' ); ?>
				</label>
			</div>
		</form><!-- .search-form -->
	</div>

<?php endif;
