<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Parse the BBcode into HTML for output.
 *
 * @param string $str content that needs to be parsed
 * @return string parsed content
 */
function gwolle_gb_bbcode_parse( $str ) {
	$bb = array();
	$html = array();

	$bb[] = "#\[b\](.*?)\[/b\]#si";
	$html[] = "<strong>\\1</strong>";
	$bb[] = "#\[i\](.*?)\[/i\]#si";
	$html[] = "<i>\\1</i>";
	$bb[] = "#\[u\](.*?)\[/u\]#si";
	$html[] = "<u>\\1</u>";
	// We run the regex on lists twice to support sublists.
	$bb[] = "#\[ul\](.*?)\[/ul\]#si";
	$html[] = "<ul>\\1</ul>";
	$bb[] = "#\[ul\](.*?)\[/ul\]#si";
	$html[] = "<ul>\\1</ul>";
	$bb[] = "#\[ol\](.*?)\[/ol\]#si";
	$html[] = "<ol>\\1</ol>";
	$bb[] = "#\[ol\](.*?)\[/ol\]#si";
	$html[] = "<ol>\\1</ol>";
	$bb[] = "#\[li\](.*?)\[/li\]#si";
	$html[] = "<li>\\1</li>";
	$bb[] = "#\[li\](.*?)\[/li\]#si";
	$html[] = "<li>\\1</li>";
	$str = preg_replace($bb, $html, $str);

	// First images, then links, so we support images inside links.
	$bbcode_img_referrer = apply_filters( 'gwolle_gb_bbcode_img_referrer', 'no-referrer' );
	$pattern = "#\[img\]([^\[]*)\[/img\]#i";
	$replace = '<img src="\\1" alt="" referrerpolicy="' . $bbcode_img_referrer . '" loading="lazy" />';
	$str = preg_replace($pattern, $replace, $str);

	// Links with quotes.
	$bbcode_link_rel = apply_filters( 'gwolle_gb_bbcode_link_rel', 'nofollow noopener noreferrer' );
	$pattern = "#\[url href=\&\#034\;([^\]]*)\&\#034\;\]([^\[]*)\[/url\]#i";
	$replace = '<a href="\\1" target="_blank" rel="' . $bbcode_link_rel . '">\\2</a>';
	$str = preg_replace($pattern, $replace, $str);
	// Links without quotes.
	$pattern = "#\[url href=([^\]]*)\]([^\[]*)\[/url\]#i";
	$replace = '<a href="\\1" target="_blank" rel="' . $bbcode_link_rel . '">\\2</a>';
	$str = preg_replace($pattern, $replace, $str);

	if ( get_option( 'gwolle_gb-showLineBreaks', 'false' ) === 'true' ) {
		// fix nl2br adding <br />'s
		$str = str_replace( '<br /><ol>', '<ol>', $str );
		$str = str_replace( '<ol><br />', '<ol>', $str );
		$str = str_replace( '</ol><br />', '</ol>', $str );
		$str = str_replace( '<br /><ul>', '<ul>', $str );
		$str = str_replace( '<ul><br />', '<ul>', $str );
		$str = str_replace( '</ul><br />', '</ul>', $str );
		$str = str_replace( '</li><br />', '</li>', $str );
	}

	return $str;
}


/*
 * Strip the BBcode from the output.
 *
 * @param string $str content that needs to be stripped
 * @return string stripped content
 */
function gwolle_gb_bbcode_strip( $str ) {
	$bb = array();
	$html = array();

	$bb[] = "#\[b\](.*?)\[/b\]#si";
	$html[] = "\\1";
	$bb[] = "#\[i\](.*?)\[/i\]#si";
	$html[] = "\\1";
	$bb[] = "#\[u\](.*?)\[/u\]#si";
	$html[] = "\\1";
	$bb[] = "#\[ul\](.*?)\[/ul\]#si";
	$html[] = "\\1";
	$bb[] = "#\[ol\](.*?)\[/ol\]#si";
	$html[] = "\\1";
	$bb[] = "#\[li\](.*?)\[/li\]#si";
	$html[] = "\\1";
	$str = preg_replace($bb, $html, $str);

	$pattern = "#\[url href=([^\]]*)\]([^\[]*)\[/url\]#i";
	$replace = '\\1';
	$str = preg_replace($pattern, $replace, $str);

	$pattern = "#\[img\]([^\[]*)\[/img\]#i";
	$replace = '';
	$str = preg_replace($pattern, $replace, $str);

	return $str;
}


/*
 * Get the list of Emoji for the form.
 *
 * @return string html with a elements with emoji
 */
function gwolle_gb_get_emoji() {
	$emoji = '
		<a title="😄" class="gwolle_gb_emoji_1 noslimstat">😄</a>
		<a title="😃" class="gwolle_gb_emoji_2 noslimstat">😃</a>
		<a title="😊" class="gwolle_gb_emoji_3 noslimstat">😊</a>
		<a title="😉" class="gwolle_gb_emoji_4 noslimstat">😉</a>
		<a title="😍" class="gwolle_gb_emoji_5 noslimstat">😍</a>
		<a title="😚" class="gwolle_gb_emoji_6 noslimstat">😚</a>
		<a title="😗" class="gwolle_gb_emoji_7 noslimstat">😗</a>
		<a title="😜" class="gwolle_gb_emoji_8 noslimstat">😜</a>
		<a title="😛" class="gwolle_gb_emoji_9 noslimstat">😛</a>
		<a title="😳" class="gwolle_gb_emoji_10 noslimstat">😳</a>
		<a title="😁" class="gwolle_gb_emoji_11 noslimstat">😁</a>
		<a title="😬" class="gwolle_gb_emoji_12 noslimstat">😬</a>
		<a title="😌" class="gwolle_gb_emoji_13 noslimstat">😌</a>
		<a title="😞" class="gwolle_gb_emoji_14 noslimstat">😞</a>
		<a title="😢" class="gwolle_gb_emoji_15 noslimstat">😢</a>
		<a title="😂" class="gwolle_gb_emoji_16 noslimstat">😂</a>
		<a title="😭" class="gwolle_gb_emoji_17 noslimstat">😭</a>
		<a title="😅" class="gwolle_gb_emoji_18 noslimstat">😅</a>
		<a title="😓" class="gwolle_gb_emoji_19 noslimstat">😓</a>
		<a title="😩" class="gwolle_gb_emoji_20 noslimstat">😩</a>
		<a title="😮" class="gwolle_gb_emoji_21 noslimstat">😮</a>
		<a title="😱" class="gwolle_gb_emoji_22 noslimstat">😱</a>
		<a title="😠" class="gwolle_gb_emoji_23 noslimstat">😠</a>
		<a title="😡" class="gwolle_gb_emoji_24 noslimstat">😡</a>
		<a title="😤" class="gwolle_gb_emoji_25 noslimstat">😤</a>
		<a title="😋" class="gwolle_gb_emoji_26 noslimstat">😋</a>
		<a title="😎" class="gwolle_gb_emoji_27 noslimstat">😎</a>
		<a title="😴" class="gwolle_gb_emoji_28 noslimstat">😴</a>
		<a title="😈" class="gwolle_gb_emoji_29 noslimstat">😈</a>
		<a title="😇" class="gwolle_gb_emoji_30 noslimstat">😇</a>
		<a title="😕" class="gwolle_gb_emoji_31 noslimstat">😕</a>
		<a title="😏" class="gwolle_gb_emoji_32 noslimstat">😏</a>
		<a title="😑" class="gwolle_gb_emoji_33 noslimstat">😑</a>
		<a title="👲" class="gwolle_gb_emoji_34 noslimstat">👲</a>
		<a title="👮" class="gwolle_gb_emoji_35 noslimstat">👮</a>
		<a title="💂" class="gwolle_gb_emoji_36 noslimstat">💂</a>
		<a title="👶" class="gwolle_gb_emoji_37 noslimstat">👶</a>
		<a title="❤" class="gwolle_gb_emoji_38 noslimstat">❤</a>
		<a title="💔" class="gwolle_gb_emoji_39 noslimstat">💔</a>
		<a title="💕" class="gwolle_gb_emoji_40 noslimstat">💕</a>
		<a title="💘" class="gwolle_gb_emoji_41 noslimstat">💘</a>
		<a title="💌" class="gwolle_gb_emoji_42 noslimstat">💌</a>
		<a title="💋" class="gwolle_gb_emoji_43 noslimstat">💋</a>
		<a title="🎁" class="gwolle_gb_emoji_44 noslimstat">🎁</a>
		<a title="💰" class="gwolle_gb_emoji_45 noslimstat">💰</a>
		<a title="💍" class="gwolle_gb_emoji_46 noslimstat">💍</a>
		<a title="👍" class="gwolle_gb_emoji_47 noslimstat">👍</a>
		<a title="👎" class="gwolle_gb_emoji_48 noslimstat">👎</a>
		<a title="👌" class="gwolle_gb_emoji_49 noslimstat">👌</a>
		<a title="✌️" class="gwolle_gb_emoji_50 noslimstat">✌️</a>
		<a title="🤘️" class="gwolle_gb_emoji_51 noslimstat">🤘</a>
		<a title="👏" class="gwolle_gb_emoji_52 noslimstat">👏</a>
		<a title="🎵" class="gwolle_gb_emoji_53 noslimstat">🎵</a>
		<a title="☕️" class="gwolle_gb_emoji_54 noslimstat">☕️</a>
		<a title="🍵" class="gwolle_gb_emoji_55 noslimstat">🍵</a>
		<a title="🍺" class="gwolle_gb_emoji_56 noslimstat">🍺</a>
		<a title="🍷" class="gwolle_gb_emoji_57 noslimstat">🍷</a>
		<a title="🍼" class="gwolle_gb_emoji_58 noslimstat">🍼</a>
		<a title="☀️" class="gwolle_gb_emoji_59 noslimstat">☀️</a>
		<a title="🌤" class="gwolle_gb_emoji_60 noslimstat">🌤</a>
		<a title="🌦" class="gwolle_gb_emoji_61 noslimstat">🌦</a>
		<a title="🌧" class="gwolle_gb_emoji_62 noslimstat">🌧</a>
		<a title="🌜" class="gwolle_gb_emoji_63 noslimstat">🌜</a>
		<a title="🌈" class="gwolle_gb_emoji_64 noslimstat">🌈</a>
		<a title="🏝" class="gwolle_gb_emoji_65 noslimstat">🏝</a>
		<a title="🎅" class="gwolle_gb_emoji_66 noslimstat">🎅</a>
		';
	/*
	 * Filters the list of emoji shown on textarea/bbcode/emoji at the frontend form.
	 *
	 * Returning the altered string is the recommended way use this filter.
	 * You can add emoji characters or replace them with str_replace.
	 *
	 * @since 2.3.0
	 *
	 * @param string $emoji The list of Emoji.
	 */
	$emoji = apply_filters( 'gwolle_gb_get_emoji', $emoji );
	return $emoji;
}


/*
 * Convert to 3byte Emoji for storing in db, if db-charset is not utf8mb4.
 *
 * @param string $string text string to encode
 * @param string $field the database field that is used for that string, will be checked on charset.
 * @return string original input string encoded or not.
 */
function gwolle_gb_maybe_encode_emoji( $string, $field ) {
	global $wpdb;
	$db_charset = $wpdb->charset;
	if ( 'utf8mb4' !== $db_charset ) {
		if ( function_exists( 'wp_encode_emoji' ) && function_exists( 'mb_convert_encoding' ) ) {
			// No support for the proper charset, so encode to html entities.
			$string = wp_encode_emoji( $string );
			// Enable this for debugging.
			// gwolle_gb_add_message( '<p class="debug_emoji"><strong>Ran wp_encode_emoji function.</strong></p>', false, false );
		}
		// Enable this for debugging.
		// gwolle_gb_add_message( '<p class="debug_emoji"><strong>MySQL Charset: ' . $charset . '</strong></p>', false, false );
	}
	return $string;
}
