<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<!-- wp:html -->
<form action="#" class="exs-ajax-form">
	<input required="required" placeholder="<?php echo esc_attr__( 'Name', 'exs' ); ?>" name="name" type="text" class="d-block mb-05">

	<input required="required" placeholder="<?php echo esc_attr__( 'Email', 'exs' ); ?>" name="email" type="email" class="d-block mb-05">

	<textarea required="required" placeholder="<?php echo esc_attr__( 'Message', 'exs' ); ?>" name="message" class="d-block mb-05"></textarea>

	<button type="submit" class="wp-block-button__link">
		<?php echo esc_html__( 'Submit', 'exs' ); ?>
	</button>
</form>
<!-- /wp:html -->
