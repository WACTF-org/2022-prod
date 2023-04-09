<?php

/**
 * BuddyPress search form
 * BP Object search form
 *
 * @package BuddyPress
 * @subpackage ExS
 * @since 0.3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="<?php bp_nouveau_search_container_class(); ?> bp-search" data-bp-search="<?php bp_nouveau_search_object_data_attr() ;?>">
	<form action="" method="get" class="bp-dir-search-form search-form" id="<?php bp_nouveau_search_selector_id( 'search-form' ); ?>" role="search">

		<label for="<?php bp_nouveau_search_selector_id( 'search' ); ?>" class="bp-screen-reader-text"><?php bp_nouveau_search_default_text( '', false ); ?></label>

		<input id="<?php bp_nouveau_search_selector_id( 'search' ); ?>" name="<?php bp_nouveau_search_selector_name(); ?>" type="search"  placeholder="<?php bp_nouveau_search_default_text(); ?>" />

		<button type="submit" id="<?php bp_nouveau_search_selector_id( 'search-submit' ); ?>" class="nouveau-search-submit" name="<?php bp_nouveau_search_selector_name( 'search_submit' ); ?>">
			<?php exs_icon( 'magnify' ); ?>
			<span id="button-text" class="bp-screen-reader-text"><?php echo esc_html_x( 'Search', 'button', 'exs' ); ?></span>
		</button>

	</form>
</div>

