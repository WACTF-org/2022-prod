<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function eme_load_textdomain() {
   // wordpress loads the site locale by default
   // If the user profile is set to something else besides the site locale, we do something here, so we check for logged in users
   if (!is_user_logged_in())
	   return;

   $language=eme_detect_lang();
   // make sure no textdomain is loaded if the locale is en (since nobody has translated EME into English)
   // This is the case if the site language is something else than English but the user profile is set to English
   if ($language=="en") {
	   if (is_textdomain_loaded('events-made-easy'))
		   unload_textdomain('events-made-easy');
   } else {
	   // load the correct textdomain
	   // This is the case if the site locale is English (so no textdomain gets loaded by default since nobody has translated EME into English)
	   // but the user profile is set to something else besides English
	   if (!is_textdomain_loaded('events-made-easy'))
		   load_plugin_textdomain('events-made-easy');
   }
}

function eme_detect_lang() {
   $language = wp_cache_get("eme_language");
   if ($language !== false)
	   return $language;

   if (!empty($_GET['lang'])) {
	   $language=eme_sanitize_request($_GET['lang']);
   } else {
	   $language=substr(determine_locale(),0,2);
   }
   wp_cache_set("eme_language", $language, '', 10);
   return $language;
}

function eme_lang_url_mode() {
   $url_mode = wp_cache_get("eme_url_mode");
   if ($url_mode !== false)
	   return $url_mode;

   // should be an option
   $url_mode=0;
   if (isset($_GET['lang'])) {
	   $url_mode=1;
   } else {
	   if (function_exists('mqtranslate_conf')) {
		   // only some functions in mqtrans are different, but the options are named the same as for qtranslate
		   $url_mode=get_option('mqtranslate_url_mode');
	   } elseif (function_exists('qtrans_getLanguage')) {
		   $url_mode=get_option('qtranslate_url_mode');
	   } elseif (function_exists('ppqtrans_getLanguage')) {
		   $url_mode=get_option('pqtranslate_url_mode');
	   } elseif (function_exists('qtranxf_getLanguage')) {
		   $url_mode=get_option('qtranslate_url_mode');
		   if (empty($url_mode))
			   $url_mode=2;
	   } elseif (function_exists('pll_current_language')) {
		   $url_mode=2;
	   }
   }
   if (empty($url_mode)) {
      $lang_code = apply_filters('eme_language_regex',EME_LANGUAGE_REGEX);
      $url=eme_current_page_url();
      $home_url=preg_quote(preg_replace("/\/$lang_code\/?$/",'',home_url()), '/');
      if (preg_match("/$home_url\/($lang_code)\//",$url,$matches))
              $url_mode=2;
   }
   wp_cache_set("eme_url_mode", $url_mode, '', 10);
   return $url_mode;
}

function eme_uri_add_lang($name,$lang) {
   $the_link = home_url();
   // some plugins add the lang info to the home_url, remove it so we don't get into trouble or add it twice
   if (!empty($lang)) {
	   $url_mode=eme_lang_url_mode();
	   if ($url_mode==2) {
		   $lang_code = apply_filters('eme_language_regex',EME_LANGUAGE_REGEX);
		   $the_link = preg_replace("/\/$lang_code\/?$/","",$the_link);
		   $the_link = trailingslashit($the_link)."$lang/".user_trailingslashit($name);
	   } elseif ($url_mode==1) {
		   $the_link = trailingslashit(remove_query_arg('lang',$the_link));
		   $the_link = $the_link.user_trailingslashit($name);
		   $the_link = add_query_arg( array( 'lang' => $lang), $the_link );
	   } else {
		   // url_mode is 0, then we don't add the lang and let wp do it
		   $the_link = trailingslashit($the_link).user_trailingslashit($name);
	   }
   } else {
	   $the_link = trailingslashit($the_link).user_trailingslashit($name);
   }
   return $the_link;
}

//backwards compat
function eme_trans_sanitize_html( $value, $lang='') {
   return eme_trans_esc_html( $value, $lang);
}

function eme_trans_esc_html( $value, $lang='') {
   return eme_esc_html(eme_translate( $value,$lang));
}

function eme_translate ( $value, $lang='') {
   if (function_exists('qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage') && function_exists('qtrans_use')) {
      if (empty($lang))
         return qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($value);
      else
         return qtrans_use($lang,$value);
   } elseif (function_exists('ppqtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage') && function_exists('ppqtrans_use')) {
      if (empty($lang))
         return ppqtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($value);
      else
         return ppqtrans_use($lang,$value);
   } elseif (function_exists('qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage') && function_exists('qtranxf_use')) {
      if (empty($lang))
         return qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage($value);
      else
         return qtranxf_use($lang,$value);
   } elseif (function_exists('pll_translate_string') && function_exists('pll__')) {
      // pll language notation is different from what qtrans (and eme) support, so lets also translate the eme language tags
      $value = eme_translate_string($value,$lang);
      if (empty($lang))
         return pll__($value);
      else
         return pll_translate_string($value,$lang);
   } else {
      return eme_translate_string($value,$lang);
   }
}

function eme_translate_string($text,$lang='') {
   if (empty($lang))
      $lang=eme_detect_lang();
   $languages=eme_detect_used_languages($text);
   if (empty($languages)) {
	// no language is encoded in the $text (most frequent case), then run it through wp trans and be done with it
	return __($text,'events-made-easy');
   }
   $content = eme_split_language_blocks($text,$languages);
   $languages = array_keys($content);
   if (empty($lang)) {
	   // no language? then return the first one
	   $lang=$languages[0];
   }
   if (isset($content[$lang]))
	   return $content[$lang];
   else
	   return $content[$languages[0]];
}

function eme_detect_used_languages($text) {
    $lang_code = apply_filters('eme_language_regex',EME_LANGUAGE_REGEX);

    $languages=array();
    if (preg_match_all("/\[:($lang_code?)\]/",$text,$matches)) {
        $languages = array_unique($matches[1]);
    } elseif (preg_match_all("/\{:($lang_code?)\}/",$text,$matches)) {
        $languages = array_unique($matches[1]);
    }
    return $languages;
}

function eme_split_language_blocks($text,$languages) {
    $lang_code = apply_filters('eme_language_regex',EME_LANGUAGE_REGEX);

    $result = array();
    foreach ( $languages as $language ) {
        $result[ $language ] = '';
    }
    $current_language = false;
    $split_regex = "#(\[:$lang_code\]|\[:\]|\{:$lang_code\}|\{:\})#ism";
    $blocks = preg_split( $split_regex, $text, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
    foreach ( $blocks as $block ) {
        // detect tags
	if ( preg_match( "#^\[:($lang_code)]$#ism", $block, $matches ) ) {
            $current_language = $matches[1];
            continue;
	} elseif ( preg_match( "#^{:($lang_code)}$#ism", $block, $matches ) ) {
            $current_language = $matches[1];
            continue;
        }
        switch ($block) {
	    case '[:]':
            case '{:}':
                $current_language = false;
                break;
            default:
                // correctly categorize text block
                if ( $current_language ) {
                    if ( !isset($result[$current_language])) {
                        $result[$current_language] = '';
                    }
                    $result[$current_language] .= $block;
                    $current_language = false;
		} else {
		    // this catches the case for text outside a translation part
		    foreach ($languages as $language) {
                        $result[$language] .= $block;
                    }
		}
                break;
        }
    }
    return $result;
}

?>
