<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Function to sanitize values from input fields for the database.
 *
 * @param string $input string to sanitize
 * @param string $field string to check which sanitizing to do
 * @return string $input sanitized string
 */
function gwolle_gb_sanitize_input( $input, $field = '' ) {
	$input = (string) $input;
	$input = htmlspecialchars_decode($input, ENT_COMPAT);
	$input = stripslashes($input); // Make sure we're not just adding lots of slashes (or WordPress does).
	$input = str_replace('\\', '&#92;', $input); // Replace these to avoid nightmares with addslashes/stripslashes.
	$input = str_replace('"', '&#34;', $input);
	$input = str_replace("'", '&#39;', $input);
	$input = trim($input);
	if ( $field === 'content' || $field === 'admin_reply' || $field === 'setting_textarea' ) {
		$input = wp_kses( $input, array() ); // Rely on this, not on strip_tags.
	} else {
		$input = sanitize_text_field( $input );
	}
	return $input;
}


/*
 * Function to sanitize values for output in a form or div.
 *
 * @param string $input string to sanitize
 * @param string $field string to check which sanitizing to do
 * @return string $input sanitized string
 */
function gwolle_gb_sanitize_output( $output, $field = '' ) {

	$output = (string) $output;
	$output = trim($output);
	$output = htmlspecialchars_decode($output, ENT_COMPAT);
	//$output = html_entity_decode($output, ENT_COMPAT, 'UTF-8'); // the opposite of htmlentities, for backwards compat. Breaks encoding...
	// Still wanting this encoded
	$output = str_replace('\\', '&#92;', $output);
	$output = str_replace('"', '&#34;', $output);
	$output = str_replace("'", '&#39;', $output);
	if ( $field === 'content' || $field === 'admin_reply' || $field === 'setting_textarea' ) {
		$output = wp_kses( $output, array() );
	} else {
		$output = sanitize_text_field( $output );
	}
	return $output;

}


/*
 * Function to format values for beeing send by mail.
 *
 * @param string $value string to sanitize
 * @return string $value sanitized string
 */
function gwolle_gb_format_values_for_mail( $value ) {

	$value = htmlspecialchars_decode($value, ENT_COMPAT);
	$value = str_replace('<', '{', $value);
	$value = str_replace('>', '}', $value);
	$value = str_replace('&#34;', '"', $value);
	$value = str_replace('&#034;', '"', $value);
	$value = str_replace('&#39;', "'", $value);
	$value = str_replace('&#039;', "'", $value);
	$value = str_replace('&#47;', '/', $value);
	$value = str_replace('&#047;', '/', $value);
	$value = str_replace('&#92;', '\\', $value);
	$value = str_replace('&#092;', '\\', $value);
	return $value;

}


/*
 * Function to build the excerpt
 *
 * @param string $content content of the entry to be shortened
 * @param int $excerpt_length the maximum length to return in number of words (uses wp_trim_words)
 * @return $excerpt string the shortened content
 */
function gwolle_gb_get_excerpt( $content, $excerpt_length = 20 ) {

	$excerpt = wp_trim_words( $content, $excerpt_length, '...' );
	$excerpt = gwolle_gb_sanitize_output( $excerpt );
	if ( trim($excerpt) === '' ) {
		$excerpt = '<i class="gb-no-content">' . esc_html__('No content to display. This entry is empty.', 'gwolle-gb') . '</i>';
	}
	return $excerpt;

}


/*
 * Function to highlight text.
 * Taken from SO:
 * https://stackoverflow.com/questions/2757556/highlight-multiple-keywords-in-search/2757619#2757619
 *
 * @param string $text the text with possible words to highlight.
 * @param array $words the words to highlight in that text.
 * @return $text with highlighted words.
 *
 * @since 3.1.8
 */
function gwolle_gb_highlight( $text, $words ) {

	if ( is_array($words) && ! empty($words) ) {
		$pattern = array();
		foreach ( $words as $word ) {
			$pattern[] = '/' . preg_quote($word, '/') . '/i';
		}

		$highlighted = preg_filter($pattern, '<b><span class="gb-highlight">$0</span></b>', $text);
		if ( ! empty($highlighted) ) {
			$text = $highlighted;
		}
	}
	return $text;

}


/*
 * Returns the number of single characters in a string.
 * Will count multibyte characters (like emoji) as one character if mb_strlen or iconv_strlen are available.
 * Expects string to be available in UTF-8.
 * No idea what happens when database doesn't support utf8mb4 but only utf8mb3, it will probably count wrong.
 *
 * @param string $string the string to count the number of characters of.
 *
 * @return int The number of characters.
 *
 * @since 4.2.0
 */
function gwolle_gb_count_characters( $string ) {

	$string = (string) $string;
	$string = str_replace( "\r\n", "\n", $string );

	if ( function_exists( 'mb_strlen' ) ) {
		return mb_strlen( $string, 'UTF-8' );
	}

	if ( function_exists( 'iconv_strlen' ) ) {
		return iconv_strlen( $string, 'UTF-8' );
	}

	return strlen( $string );

}
