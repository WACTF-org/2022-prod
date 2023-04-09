<?php
/*
 * Functions to handle static variables for messages and errors.
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Add messages from the form to show again after submitting an entry.
 *
 * @param mixed string $message html and text to show, or false if no message.
 * @param bool  $error if it is a validation error for the form (default false).
 * @param mixed string $error_field with which field does not validate, otherwise bool false.
 *
 * @return array list of messages that are already added.
 *
 * @uses static array $gwolle_gb_messages list of messages in html.
 *
 * @since 1.5.6
 */
function gwolle_gb_add_message( $message = false, $error = false, $error_field = false ) {

	static $gwolle_gb_messages;

	if ( ! isset( $gwolle_gb_messages ) || ! is_array( $gwolle_gb_messages ) ) {
		$gwolle_gb_messages = array();
	}

	if ( $message ) {
		$gwolle_gb_messages[] = $message;
	}

	if ( $error === true ) {
		gwolle_gb_add_error( true );
	}

	if ( $error_field ) {
		gwolle_gb_add_error_field( $error_field );
	}

	return $gwolle_gb_messages;

}


/*
 * Used for the frontend form, html with messages.
 *
 * @return string with html with messages
 *
 * @since 1.5.6
 */
function gwolle_gb_get_messages() {

	$gwolle_gb_messages = gwolle_gb_add_message();
	$gwolle_gb_errors = gwolle_gb_get_errors();
	$gwolle_gb_error_fields = gwolle_gb_get_error_fields();

	$messages = '';

	if ( $gwolle_gb_errors && is_array( $gwolle_gb_error_fields ) && ! empty( $gwolle_gb_error_fields ) ) {
		// There was no data filled in, even though that was mandatory.
		$gwolle_gb_messages[] = '<p class="error_fields gb-error-fields"><strong>' . esc_html__('There were errors submitting your guestbook entry.', 'gwolle-gb') . '</strong></p>';

		$gwolle_gb_error_fields = gwolle_gb_array_flatten( $gwolle_gb_error_fields );
		$gwolle_gb_error_fields = implode( ', ', $gwolle_gb_error_fields );
		$gwolle_gb_messages[] = '<p class="error_fields gb-error-fields" style="display: none;">' . $gwolle_gb_error_fields . '</p>';
	}

	$gwolle_gb_messages = apply_filters( 'gwolle_gb_messages', $gwolle_gb_messages );

	foreach ( $gwolle_gb_messages as $message ) {
		$messages .= $message; // string
	}

	return $messages;

}


/*
 * Add errors to return the form after submitting an entry.
 *
 * @param bool $error is there a fatal error in submitting the form.
 *
 * @return bool if there was a fatal error already.
 *
 * @uses static bool $gwolle_gb_errors
 *
 * @since 3.0.0
 */
function gwolle_gb_add_error( $error = false ) {

	static $gwolle_gb_errors;

	if ( ! isset( $gwolle_gb_errors ) || ! is_bool( $gwolle_gb_errors ) ) {
		$gwolle_gb_errors = false;
	}

	if ( $error === true ) {
		$gwolle_gb_errors = $error;
	}

	return $gwolle_gb_errors;

}

/*
 * Used for the frontend form, if fatal errors were found.
 *
 * @return bool if errors were found.
 *
 * @since 1.5.6
 */
function gwolle_gb_get_errors() {

	$gwolle_gb_errors = gwolle_gb_add_error();

	if ( ! isset( $gwolle_gb_errors ) ) {
		$gwolle_gb_errors = false;
	}

	$gwolle_gb_errors = apply_filters( 'gwolle_gb_errors', $gwolle_gb_errors );

	return $gwolle_gb_errors;

}


/*
 * Add error_field to mark as red in the form after submitting an entry.
 *
 * @param string $field name of the formfield.
 *
 * @return array error_fields that were added to the static var.
 *
 * @uses static array $gwolle_gb_error_fields with error_fields.
 *
 * @since 3.0.0
 */
function gwolle_gb_add_error_field( $error_field = false ) {

	static $gwolle_gb_error_fields;

	if ( ! isset( $gwolle_gb_error_fields ) || ! is_array( $gwolle_gb_error_fields ) ) {
		$gwolle_gb_error_fields = array();
	}

	if ( $error_field ) {
		$gwolle_gb_error_fields[] = $error_field;
	}

	return $gwolle_gb_error_fields;

}


/*
 * @return array with the fields that did not validate.
 *
 * @since 1.5.6
 */
function gwolle_gb_get_error_fields() {

	$gwolle_gb_error_fields = gwolle_gb_add_error_field();

	$gwolle_gb_error_fields = apply_filters( 'gwolle_gb_error_fields', $gwolle_gb_error_fields );

	return $gwolle_gb_error_fields;

}


/*
 * Add formdata from the form to show again after submitting an entry.
 *
 * @param string $field name of the formfield.
 * @param string $value value of the formfield to be used again.
 *
 * @return array formdata that was added to the static var.
 *
 * @uses static array $gwolle_gb_formdata with list of formdata.
 *
 * @since 1.5.6
 */
function gwolle_gb_add_formdata( $field = false, $value = false ) {

	static $gwolle_gb_formdata;

	if ( ! isset( $gwolle_gb_formdata ) || ! is_array( $gwolle_gb_formdata ) ) {
		$gwolle_gb_formdata = array();
	}

	if ( $field && $value ) {
		$gwolle_gb_formdata["$field"] = esc_attr( $value );
	}

	return $gwolle_gb_formdata;

}


/*
 * formdata to be used again on the frontend form after submitting.
 *
 * @return array formdata to be used again on the frontend.
 *
 * @since 1.5.6
 */
function gwolle_gb_get_formdata() {

	$gwolle_gb_formdata = gwolle_gb_add_formdata();

	$gwolle_gb_formdata = apply_filters( 'gwolle_gb_formdata', $gwolle_gb_formdata );

	return $gwolle_gb_formdata;

}
