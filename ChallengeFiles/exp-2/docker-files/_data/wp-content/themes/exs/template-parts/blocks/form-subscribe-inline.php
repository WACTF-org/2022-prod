<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<!-- wp:html -->
<form action="#" class="exs-ajax-form is-layout-flex items-justified-center">
	<input required="required" placeholder="Email" name="mailchimp" type="email" style="width:auto">

	<button type="submit" class="wp-block-button__link"><?php echo esc_html__( 'Subscribe', 'exs' ); ?></button>
</form>
<!-- /wp:html -->
