<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<!-- wp:html -->
<form action="#" class="exs-ajax-form">
	<input required="required" placeholder="Name" name="name" type="text" class="d-block mb-1">

	<input required="required" placeholder="Email" name="mailchimp" type="email" class="d-block mb-1">

	<button type="submit" class="wp-block-button__link">
		<?php echo esc_html__( 'Subscribe', 'exs' ); ?>
	</button>
</form>
<!-- /wp:html -->
