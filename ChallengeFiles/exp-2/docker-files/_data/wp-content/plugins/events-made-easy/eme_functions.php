<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function eme_plugin_url() {
        $url = wp_cache_get("eme_plugin_url");
        if ($url === false) {
                $url = plugin_dir_url( __FILE__ );
                wp_cache_set("eme_plugin_url", $url, '', 60);
        }
	return $url;
}
function eme_plugin_dir() {
        $dir = wp_cache_get("eme_plugin_dir");
        if ($dir === false) {
                $dir = plugin_dir_path( __FILE__ );
                wp_cache_set("eme_plugin_dir", $dir, '', 60);
        }
	return $dir;
}

add_action( 'wp_ajax_eme_client_clock', 'eme_client_clock_ajax' );
add_action( 'wp_ajax_nopriv_eme_client_clock', 'eme_client_clock_ajax' );
function eme_client_clock_ajax() {
   global $eme_timezone;
   $valid = true;
   $ret='0';
   // Set php clock values in an array
   $phptime_obj = new ExpressiveDate("now",$eme_timezone);
   // if clock data not set
   if (!isset($_COOKIE['eme_client_time'])) {
      // no valid data received
      $valid = false;
      // ret=1 will cause the client browser to refresh, this will allow the cookie to be read upon refresh (and so no global var is needed)
      $ret='1';
      // Preset php clock values in client cookie for fall-back if valid client clock data isn't received.
      $client_timeinfo['eme_client_unixtime'] = (int) $phptime_obj->format('U'); // Integer seconds since 1/1/1970 @ 12:00 AM
      $client_timeinfo['eme_client_seconds'] = (int) $phptime_obj->format('s'); // Integer second this minute (0-59)
      $client_timeinfo['eme_client_minutes'] = (int) $phptime_obj->format('i'); // Integer minute this hour (0-59)
      $client_timeinfo['eme_client_hours'] = (int) $phptime_obj->format('h'); // Integer hour this day (0-23)
      $client_timeinfo['eme_client_wday'] = (int) $phptime_obj->format('w'); // Integer day this week (0-6), 0 = Sunday, ... , 6 = Saturday
      $client_timeinfo['eme_client_mday'] = (int) $phptime_obj->format('j'); // Integer day this month 1-31)
      $client_timeinfo['eme_client_month'] = (int) $phptime_obj->format('n'); // Integer month this year (1-12)
      $client_timeinfo['eme_client_fullyear'] = (int) $phptime_obj->format('Y'); // Integer year (1970-9999)
   } else {
      try {
	 $client_timeinfo = eme_sanitize_request(json_decode($_COOKIE['eme_client_time'],true));
	 if (!is_array($client_timeinfo)) $client_timeinfo = array();
	 $ret='0';
      } catch (Exception $error) {
         $client_timeinfo = array();
	 $valid=false;
      }
      if (!isset($client_timeinfo['eme_client_unixtime'])) $client_timeinfo['eme_client_unixtime'] = (int) $phptime_obj->format('U');
      if (!isset($client_timeinfo['eme_client_unixtime'])) $client_timeinfo['eme_client_seconds'] = (int) $phptime_obj->format('s');
      if (!isset($client_timeinfo['eme_client_unixtime'])) $client_timeinfo['eme_client_minutes'] = (int) $phptime_obj->format('i');
      if (!isset($client_timeinfo['eme_client_unixtime'])) $client_timeinfo['eme_client_hours'] = (int) $phptime_obj->format('h');
      if (!isset($client_timeinfo['eme_client_unixtime'])) $client_timeinfo['eme_client_wday'] = (int) $phptime_obj->format('w');
      if (!isset($client_timeinfo['eme_client_unixtime'])) $client_timeinfo['eme_client_mday'] = (int) $phptime_obj->format('j');
      if (!isset($client_timeinfo['eme_client_unixtime'])) $client_timeinfo['eme_client_month'] = (int) $phptime_obj->format('n');
      if (!isset($client_timeinfo['eme_client_unixtime'])) $client_timeinfo['eme_client_fullyear'] = (int) $phptime_obj->format('Y');
   }
   
   // Cast client clock values as integers to avoid mathematical errors and set in temporary local variables.
   $client_unixtime = isset($_POST["page"]) ? intval($_POST["client_unixtime"]) : 0;
   $client_seconds = isset($_POST["page"]) ? intval($_POST["client_seconds"]) : 0;
   $client_minutes = isset($_POST["page"]) ? intval($_POST["client_minutes"]) : 0;
   $client_hours = isset($_POST["page"]) ? intval($_POST["client_hours"]) : 0;
   $client_wday = isset($_POST["page"]) ? intval($_POST["client_wday"]) : 0;
   $client_mday = isset($_POST["page"]) ? intval($_POST["client_mday"]) : 0;
   $client_month = isset($_POST["client_month"]) ? intval($_POST["client_month"]) : 0;
   $client_fullyear = isset($_POST["client_fullyear"]) ? intval($_POST["client_fullyear"]) : 0;
   
   // Client clock sanity tests
   if (abs($client_unixtime - $client_timeinfo['eme_client_unixtime']) > 300) $valid = false; // allow +/-5 min difference
   if (abs($client_seconds - 30) > 30) $valid = false; // Seconds <0 or >60
   if (abs($client_minutes - 30) > 30) $valid = false; // Minutes <0 or >60
   if (abs($client_hours - 12) > 12) $valid = false; // Hours <0 or >24
   if (abs($client_wday - 3) > 3) $valid = false; // Weekday <0 or >6
   if (abs($client_mday - $client_timeinfo['eme_client_mday']) > 30) $valid = false; // >30 day difference
   if (abs($client_month - $client_timeinfo['eme_client_month']) > 11) $valid = false; // >11 month difference
   if (abs($client_fullyear - $client_timeinfo['eme_client_fullyear']) > 1) $valid = false; // >1 year difference

   // To insure mutual consistency, don't use any client values unless they all passed the tests.
   if ($valid) {
      $client_timeinfo['eme_client_unixtime'] = $client_unixtime;
      $client_timeinfo['eme_client_seconds'] = $client_seconds;
      $client_timeinfo['eme_client_minutes'] = $client_minutes;
      $client_timeinfo['eme_client_hours'] = $client_hours;
      $client_timeinfo['eme_client_wday'] = $client_wday;
      $client_timeinfo['eme_client_mday'] = $client_mday;
      $client_timeinfo['eme_client_month'] = $client_month;
      $client_timeinfo['eme_client_fullyear'] = $client_fullyear;
   }

   // client cookie lifetime = 0 (the session)
   // the cookie is stored using json_encode and not serialize, to avoid for Object Injection
   // See https://www.owasp.org/index.php/PHP_Object_Injection
   setcookie('eme_client_time',json_encode($client_timeinfo), 0, COOKIEPATH, COOKIE_DOMAIN);
   echo $ret;
   wp_die();
}

function eme_captcha_generate($file) {
	// 23 letters (not the "l",o","q")
	$alphabet="abcdefghjkmnpqrstuvwxyz";
	$random1 = substr($alphabet,rand(1,23)-1,1);
	$random2 = rand(2,9);
	$rand=rand(1,23)-1;
	$random3 = substr($alphabet,rand(1,23)-1,1);
	$random4 = rand(2,9);
	$rand=rand(1,23)-1;
	$random5 = substr($alphabet,rand(1,23)-1,1);

	$randomtext=$random1.$random2.$random3.$random4.$random5;
	// strtolower not needed, $alphabet lowercase only
	//$randomtext=strtolower($randomtext);

	$res= "eme_captcha_".$file.'-'.md5($randomtext);
        touch (get_temp_dir().$res);

	$im = imagecreatetruecolor(120, 38);

	// some colors
	$white = imagecolorallocate($im, 255, 255, 255);
	$grey = imagecolorallocate($im, 128, 128, 128);
	$black = imagecolorallocate($im, 0, 0, 0);
	$red = imagecolorallocate($im, 255, 0, 0);
	$blue = imagecolorallocate($im, 0, 0, 255);
	$green = imagecolorallocate($im, 0, 255, 0);
	$background_colors=array($red,$blue,$green,$black);

	// draw rectangle in random color
	$background_color=$background_colors[rand(0,3)];
	imagefilledrectangle($im, 0, 0, 120, 38, $background_color);

	// replace font.ttf with the location of your own ttf font file
	$eme_plugin_dir=eme_plugin_dir();
	$font = $eme_plugin_dir.'font.ttf';

	if (function_exists('imagettftext')) {
		// add shadow
		imagettftext($im, 25, 8, 15, 28, $grey, $font, $random1);
		imagettftext($im, 25, -8, 35, 28, $grey, $font, $random2);
		imagettftext($im, 25, 8, 55, 28, $grey, $font, $random3);
		imagettftext($im, 25, -8, 75, 28, $grey, $font, $random4);
		imagettftext($im, 25, 8, 95, 28, $grey, $font, $random5);

		// add text
		imagettftext($im, 25, 8, 8, 30, $white, $font, $random1);
		imagettftext($im, 25, -8, 28, 30, $white, $font, $random2);
		imagettftext($im, 25, 8, 48, 30, $white, $font, $random3);
		imagettftext($im, 25, -8, 68, 30, $white, $font, $random4);
		imagettftext($im, 25, 8, 88, 30, $white, $font, $random5);
	} else {
		// add shadow
		imagestring ($im, 5, 15 , 5 , $random1 , $grey );
		imagestring ($im, 5, 35 , 5 , $random2 , $grey );
		imagestring ($im, 5, 55 , 5 , $random3 , $grey );
		imagestring ($im, 5, 75 , 5 , $random4 , $grey );
		imagestring ($im, 5, 95 , 5 , $random5 , $grey );
		// add text
		imagestring ($im, 5, 15 , 7 , $random1 , $white );
		imagestring ($im, 5, 35 , 7 , $random2 , $white );
		imagestring ($im, 5, 55 , 7 , $random3 , $white );
		imagestring ($im, 5, 75 , 7 , $random4 , $white );
		imagestring ($im, 5, 95 , 7 , $random5 , $white );
	}

	// give image back
	header ("Content-type: image/gif");
	imagegif($im);
	imagedestroy($im);
	exit;
}

function eme_load_captcha_html() {
	if (eme_is_admin_request())
		return '';

	$captcha_id=eme_random_id();
	$captcha_url=eme_captcha_url($captcha_id);
	if ($captcha_url) {
		$replacement = "<input type='hidden' name='eme_captcha_id' value='$captcha_id'><img id='eme_captcha_img' src='$captcha_url'><br /><input required='required' type='text' name='captcha_check' id='captcha_check' autocomplete='off' class='nodynamicupdates' />";
	} else {
		$replacement = __('Problem while generating the captcha, please check with the site administrator','events-made-easy');
	}
	return $replacement;
}

function eme_load_recaptcha_html() {
	if (eme_is_admin_request())
		return '';

	if (!wp_script_is('eme-recaptcha','enqueued'))
		wp_enqueue_script( 'eme-recaptcha');

	$eme_recaptcha_sitekey=get_option('eme_recaptcha_site_key');
	   return '<!-- Google reCAPTCHA widget -->
            <div class="g-recaptcha" data-sitekey="'.$eme_recaptcha_sitekey.'"></div>';
}

function eme_load_hcaptcha_html() {
	if (eme_is_admin_request())
		return '';

	if (!wp_script_is('eme-hcaptcha','enqueued'))
		wp_enqueue_script( 'eme-hcaptcha');

	$eme_hcaptcha_sitekey=get_option('eme_hcaptcha_site_key');
	   return '<!-- hCaptcha widget -->
            <div class="h-captcha" data-sitekey="'.$eme_hcaptcha_sitekey.'"></div>';
}

function eme_check_recaptcha() {
	$eme_recaptcha=get_option('eme_recaptcha_for_forms');
	$eme_recaptcha_key=get_option('eme_recaptcha_secret_key');
	if (isset($_POST['g-recaptcha-response']) && !empty($eme_recaptcha_key) && $eme_recaptcha && function_exists('curl_init')) {
		$url = 'https://www.google.com/recaptcha/api/siteverify?secret='.$eme_recaptcha_key.'&response='.eme_sanitize_request($_POST['g-recaptcha-response']);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$data = curl_exec($curl);
		curl_close($curl);
		$responseCaptchaData = json_decode($data);
		if ($responseCaptchaData->success) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function eme_check_hcaptcha() {
	$eme_hcaptcha=get_option('eme_hcaptcha_for_forms');
	$eme_hcaptcha_key=get_option('eme_hcaptcha_secret_key');
	if (isset($_POST['h-captcha-response']) && !empty($eme_hcaptcha_key) && $eme_hcaptcha && function_exists('curl_init')) {
		$data = array(
			'secret' => $eme_hcaptcha_key,
			'response' => eme_sanitize_request($_POST['h-captcha-response'])
		);
		$query_string = http_build_query($data);

		$url = 'https://hcaptcha.com/siteverify';
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $query_string);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($curl);
		curl_close($curl);
		$responseCaptchaData = json_decode($data);
		if ($responseCaptchaData->success) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function eme_check_captcha($remove_upon_success=1) {
   if (empty($_POST['eme_captcha_id'])) return false;
   if (empty($_POST['captcha_check'])) return false;
   $captcha_id=eme_sanitize_filenamechars($_POST['eme_captcha_id']);
   if (empty($captcha_id)) return false;
   // make it a bit easier for people and lowercase their response
   $captcha_response=eme_sanitize_filenamechars(strtolower($_POST['captcha_check']));
   if (empty($captcha_response)) return false;
   $res= get_temp_dir()."eme_captcha_".$captcha_id.'-'.md5($captcha_response);

   // normally the captcha gets removed upon validation
   // sometimes you do other form validations later on, and then this is not desired
   // because if other form validation would fail, the captcha would still be gone
   // and people would need to re-enter it
   // In such cases, call eme_captcha_remove on the file later on
   // (although there's also a cron that removes captchas older than 1 hour, so no real requirement)
   if (file_exists($res)) {
      if ($remove_upon_success) {
	      unlink($res);
	      return true;
      } else {
	      return $res;
      }
   } else {
      return false;
   }
}

function eme_add_captcha_submit($format,$captcha='',$add_dyndadata=0) {
	if ($add_dyndadata && !preg_match('/#_DYNAMICDATA/',$format)) {
		$text='#_DYNAMICDATA';
		if (preg_match('/#_SUBMIT/',$format)) {
			$format = preg_replace('/#_SUBMIT/',"$text<br />#_SUBMIT",$format);
		} else {
			$format.=$text;
		}
	}
	if ($captcha == 'hcaptcha' && !preg_match('/#_HCAPTCHA/',$format)) {
		$captcha_text='#_HCAPTCHA';
		if (preg_match('/#_SUBMIT/',$format)) {
			$format = preg_replace('/#_SUBMIT/',"$captcha_text<br />#_SUBMIT",$format);
		} else {
			$format.=$captcha_text;
		}
	}
	if ($captcha == 'recaptcha' && !preg_match('/#_RECAPTCHA/',$format)) {
		$captcha_text='#_RECAPTCHA';
		if (preg_match('/#_SUBMIT/',$format)) {
			$format = preg_replace('/#_SUBMIT/',"$captcha_text<br />#_SUBMIT",$format);
		} else {
			$format.=$captcha_text;
		}
	}
	if ($captcha == 'captcha' && !preg_match('/#_CAPTCHA/',$format)) {
		$captcha_text="<p>".__("Please enter the displayed code.", 'events-made-easy').'</p> #_CAPTCHA';
		if (preg_match('/#_SUBMIT/',$format)) {
			$format = preg_replace('/#_SUBMIT/',"$captcha_text<br />#_SUBMIT",$format);
		} else {
			$format.=$captcha_text;
		}
	}
	if (!preg_match('/#_SUBMIT/',$format))
		$format.='<br />#_SUBMIT';
	return $format;
}

function eme_captcha_remove($captcha) {
   if (!empty($captcha) && file_exists($captcha)) {
	   unlink($captcha);
   }
}

function eme_if_shortcode($atts,$content) {
   extract ( shortcode_atts ( array ('tag' => '', 'value' => '', 'eq' => '', 'notvalue' => '', 'ne' => '', 'lt' => '', 'le' => '',  'gt' => '', 'ge' => '', 'contains'=>'', 'notcontains'=>'', 'is_empty'=>0, 'incsv'=>'', 'notincsv'=>'' ), $atts ) );
   // replace placeholders if eme_if is used outside EME shortcodes
   $tag=eme_replace_generic_placeholders($tag);
   $content=eme_replace_generic_placeholders($content);
   if ($is_empty) {
      if (empty($tag)) return do_shortcode($content);
   } elseif (is_numeric($value) || !empty($value)) {
      if ($tag==$value) return do_shortcode($content);
   } elseif (is_numeric($eq) || !empty($eq)) {
      if ($tag==$eq) return do_shortcode($content);
   } elseif (is_numeric($ne) || !empty($ne)) {
      if ($tag!=$ne) return do_shortcode($content);
   } elseif (is_numeric($notvalue) || !empty($notvalue)) {
      if ($tag!=$notvalue) return do_shortcode($content);
   } elseif (is_numeric($lt) || !empty($lt)) {
      if ($tag<$lt) return do_shortcode($content);
   } elseif (is_numeric($le) || !empty($le)) {
      if ($tag<=$le) return do_shortcode($content);
   } elseif (is_numeric($gt) || !empty($gt)) {
      if ($tag>$gt) return do_shortcode($content);
   } elseif (is_numeric($ge) || !empty($ge)) {
      if ($tag>=$ge) return do_shortcode($content);
   } elseif (is_numeric($contains) || !empty($contains)) {
      if (strpos($tag,"$contains")!== false) return do_shortcode($content);
   } elseif (is_numeric($notcontains) || !empty($notcontains)) {
      if (strpos($tag,"$notcontains")===false) return do_shortcode($content);
   } elseif (is_numeric($incsv) || !empty($incsv)) {
      if ( preg_match('/,/', $incsv) ) {
         $incsv_arr = explode(',', $incsv);
         foreach ($incsv_arr as $incsv) {
	    if (in_array($incsv,explode(",",$tag)) || in_array($incsv,explode(", ",$tag)) ) return do_shortcode($content);
         }
      } elseif ( preg_match('/\+/', $incsv) ) {
         $incsv_arr = preg_split('/\+/', $incsv,0,PREG_SPLIT_NO_EMPTY);
	 $found=1;
         foreach ($incsv_arr as $incsv) {
		 if (!(in_array($incsv,explode(",",$tag)) || in_array($incsv,explode(", ",$tag))) )
			 $found=0;
         }
	 if ($found) return do_shortcode($content);
      } else {
	 if (in_array($incsv,explode(",",$tag)) || in_array($incsv,explode(", ",$tag)) ) return do_shortcode($content);
      }
   } elseif (is_numeric($notincsv) || !empty($notincsv)) {
      if ( preg_match('/,/', $notincsv) ) {
         $notincsv_arr = explode(',', $notincsv);
	 $found=0;
         foreach ($notincsv_arr as $notincsv) {
		 if (in_array($incsv,explode(",",$tag)) || in_array($incsv,explode(", ",$tag)) )
			 $found=1;
	 }
	 if (!$found) return do_shortcode($content);
      } elseif ( preg_match('/\+/', $notincsv) ) {
         $notincsv_arr = preg_split('/\+/', $notincsv,0,PREG_SPLIT_NO_EMPTY);
	 $found=0;
         foreach ($notincsv_arr as $notincsv) {
		 if (in_array($incsv,explode(",",$tag)) || in_array($incsv,explode(", ",$tag)) )
			 $found=1;
		 else
			 $found=0;
         }
	 if (!$found) return do_shortcode($content);
      } else {
         if (!(in_array($notincsv,explode(",",$tag)) || in_array($incsv,explode(", ",$tag))) ) return do_shortcode($content);
      }
   } elseif (!empty($tag)) {
      return do_shortcode($content);
   } else {
      return "";
   }
}

function eme_for_shortcode($atts,$content) {
   extract ( shortcode_atts ( array ('min' => 1, 'max' => 0, 'list' => '', 'sep' => ','), $atts ) );
   $min = intval($min);
   $max = intval($max);
   $result = "";
   $loopcounter = 1;
   if (!empty($list)) {
	   $search = array("#_LOOPVALUE","#URL_LOOPVALUE","#_LOOPCOUNTER");
	   $array_values = explode($sep, $list);
	   foreach ($array_values as $val) {
		   $url_val=rawurlencode($val);
		   $esc_val=eme_sanitize_request(eme_esc_html(preg_replace('/\n|\r/','',$val)));
		   $replace = array($val,$url_val,$loopcounter);
		   $tmp_content = str_replace($search,$replace,$content);
		   $result .= do_shortcode($tmp_content);
		   $loopcounter++;
	   }
   } else {
	   $search = array("#_LOOPVALUE","#_LOOPCOUNTER");
	   while ($min <= $max) {
		   $replace = array($min,$loopcounter);
		   $tmp_content = str_replace($search,$replace,$content);
		   $result .= do_shortcode($tmp_content);
		   $min++;
		   $loopcounter++;
	   }
   }
   return $result;
}

// Returns true if the page in question is the events page
function eme_is_events_page() {
   $events_page_id = eme_get_events_page_id();
   if ($events_page_id) {
      return is_page($events_page_id);
   } else {
      return false;
   }
}

function eme_get_events_page_id() {
   return get_option('eme_events_page');
}

function eme_get_events_page($justurl = 1, $text = '') {
   $events_page_id = eme_get_events_page_id();
   $page_link = get_permalink ($events_page_id);
   if ($justurl || empty($text)) {
      $result = $page_link;
   } else {
      $text = eme_esc_html($text);
      $result = "<a href='$page_link' title='$text'>$text</a>";
   }
   return $result;
}

function eme_is_single_day_page() {
   return (eme_is_events_page() && !empty(get_query_var('calendar_day')));
}

function eme_is_single_event_page() {
   return (eme_is_events_page() && !empty(get_query_var('event_id')));
}

function eme_is_single_location_page() {
   return (eme_is_events_page() && !empty(get_query_var('location_id')) && empty(get_query_var('calendar_day')));
}

function eme_get_contact($contact_id) {
   // suppose the user has been deleted ...
   if (!get_userdata($contact_id)) $contact_id = get_option('eme_default_contact_person');
   if ($contact_id < 1) {
      if (function_exists('is_multisite') && is_multisite()) {
         $thisblog = get_current_blog_id();
         $userinfo = get_user_by('email', get_blog_option($thisblog, 'admin_email'));
      } else {
         $userinfo = get_user_by('email', get_option('admin_email'));
      }
      // still nothing ? Can be if the main admin email is not matching a user, so get the first admin
      if (!$userinfo) {
	      $eme_super_admins=get_super_admins();
	      if (!empty($eme_super_admins))
		      $userinfo = get_user_by('login', $eme_super_admins[0]);
     }
   } else {
      $userinfo=get_userdata($contact_id);
   }
   return $userinfo;
}

function eme_get_event_contact($event=null) {
   if (!is_null($event) && isset($event['event_contactperson_id']) && $event['event_contactperson_id'] >0 )
      $contact_id = $event['event_contactperson_id'];
   else
      $contact_id = get_option('eme_default_contact_person');
   // suppose the user has been deleted ...
   if (!get_userdata($contact_id)) $contact_id = get_option('eme_default_contact_person');
   if ($contact_id < 1 && isset($event['event_author']) && $event['event_author']>0)
      $contact_id = $event['event_author'];
   if ($contact_id < 1) {
      if (function_exists('is_multisite') && is_multisite()) {
         $thisblog = get_current_blog_id();
         $userinfo = get_user_by('email', get_blog_option($thisblog, 'admin_email'));
      } else {
         $userinfo = get_user_by('email', get_option('admin_email'));
      }
      if (!$userinfo) {
         // still nothing ? Can be if the main admin email is not matching a user, so get the first admin
	 $eme_super_admins=get_super_admins();
	 if (!empty($eme_super_admins))
	      $userinfo = get_user_by('login', $eme_super_admins[0]);
      }
   } else {
      $userinfo=get_userdata($contact_id);
   }
   return $userinfo;
}

function eme_get_author($event) {
   $author_id = $event['event_author'];
   if ($author_id < 1) {
      if (function_exists('is_multisite') && is_multisite()) {
         $thisblog = get_current_blog_id();
         $userinfo = get_user_by('email', get_blog_option($thisblog, 'admin_email'));
      } else {
         $userinfo = get_user_by('email', get_option('admin_email'));
      }
      #$contact_id = get_current_user_id();
   } else {
      $userinfo=get_userdata($author_id);
   }
   return $userinfo;
}

function eme_get_user_phone($user_id) {
   return get_user_meta($user_id, 'eme_phone',true);
}

function eme_update_user_phone($user_id,$phone) {
   update_user_meta($user_id,'eme_phone', $phone);
}

// got from http://davidwalsh.name/php-email-encode-prevent-spam
function eme_ascii_encode($e,$target="html") {
    return eme_email_obfuscate($e,$target);
}
function eme_email_obfuscate($e,$target="html") {
    if ($target == "htmlmail") {
	    return eme_esc_html($e);
    } elseif ($target == "text") {
	    return $e;
    } else {
	    $output = "";
	    if (has_filter('eme_email_obfuscate_filter')) {
		    $output=apply_filters('eme_email_obfuscate_filter',$e);
	    } else {
		    for ($i = 0; $i < strlen($e); $i++) { $output .= '&#'.ord($e[$i]).';'; }
	    }
	    return $output;
    }
}

function eme_unique_slug($slug,$table,$column, $index_col,$index_colval=0) {
	global $wpdb;
	$table_name = $wpdb->prefix . $table;
	$slug=untrailingslashit($slug);
	// code taken from function wp_unique_term_slug
	$query = $wpdb->prepare( "SELECT $column FROM $table_name WHERE $index_col<> $index_colval AND $column = %s", $slug);
	// it exists .... so strip of the last numbers and start finding new ones
	if ( $wpdb->get_var( $query ) ) {
	    $slug=preg_replace('/\-\d+$/','',$slug);
            $num = 2;
            do {
               $alt_slug = $slug . "-$num";
               $num++;
               $slug_check = $wpdb->get_var( $wpdb->prepare( "SELECT $column FROM $table_name WHERE $index_col<> $index_colval AND $column = %s", $alt_slug ) );
            } while ( $slug_check );
            $slug = $alt_slug;
        }
	return $slug;
}

function eme_permalink_convert_noslash($val) {
	// return the permalink without trailing slash
	return eme_permalink_convert($val,0);
}

function eme_permalink_convert($val,$trailing_slash=1) {
   // WP provides a function to convert accents to their ascii counterparts
   // called remove_accents, but we also want to replace spaces with "-"
   // and trim the last space. sanitize_title_with_dashes does all that
   // first call untrailingslashit to remove a trailing '/' if at all present in the value
   $val = sanitize_title_with_dashes(remove_accents(untrailingslashit($val)));
   // now remove anything that would be a html char
   // from https://stackoverflow.com/questions/2103797/url-friendly-username-in-php
   $val=strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($val, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
 
   // and then, add a trailing slash
   if ($trailing_slash)
	   return trailingslashit($val);
   else
	   return $val;
}

function eme_event_url($event,$language="") {
   global $wp_rewrite;

   if ($event['event_url'] != '' && get_option('eme_use_external_url') && eme_is_url($event['event_url'])) {
      $the_link = $event['event_url'];
      $parsed = parse_url($the_link);
      if (empty($parsed['scheme'])) {
          $the_link = 'https://' . ltrim($the_link, '/');
      }
      $the_link = esc_url($the_link);
   } else {
      if (empty($language))
	      $language = eme_detect_lang();
      if (isset($wp_rewrite) && $wp_rewrite->using_permalinks() && get_option('eme_seo_permalink')) {
	 $events_prefixes=explode(',',get_option('eme_permalink_events_prefix','events'));
	 if (!empty($event['event_prefix']) && in_array($event['event_prefix'],$events_prefixes)) {
		 $events_prefix=eme_permalink_convert($event['event_prefix']);
	 } else {
		 $events_prefix=eme_permalink_convert($events_prefixes[0]);
	 }
	 if (empty($event['event_slug'])) {
		 $name=$events_prefix.$event['event_id']."/".eme_permalink_convert_noslash($event['event_name']);
	 } elseif (substr($event['event_slug'], -1) == '/') { // old style stuff
		 $name=$events_prefix.$event['event_id']."/".eme_permalink_convert_noslash($event['event_slug']);
	 } else {
		 $name=$events_prefix.eme_permalink_convert_noslash($event['event_slug']);
	 }
	 $the_link = eme_uri_add_lang($name,$language);
      } else {
         $the_link = eme_get_events_page();
         $the_link = add_query_arg( array( 'event_id' => $event['event_id'] ), $the_link );
         if (!empty($language)) {
            // some plugins add the lang info to the home_url, remove it so we don't get into trouble or add it twice
	    $the_link = remove_query_arg('lang',$the_link);
            $the_link = add_query_arg( array( 'lang' => $language ), $the_link );
	 }
      }
   }
   return $the_link;
}

function eme_location_url($location,$language="") {
   global $wp_rewrite;

   $the_link = "";
   if ($location['location_url'] != '' && (get_option('eme_use_external_url') || $location['location_properties']['online_only']) && eme_is_url($location['location_url']) ) {
      $the_link = $location['location_url'];
      $parsed = parse_url($the_link);
      if (empty($parsed['scheme'])) {
          $the_link = 'https://' . ltrim($the_link, '/');
      }
      $the_link = esc_url($the_link);
   } else {
      if (isset($location['location_id']) && isset($location['location_name'])) {
         if (empty($language))
	    $language = eme_detect_lang();
         if (isset($wp_rewrite) && $wp_rewrite->using_permalinks() && get_option('eme_seo_permalink')) {
	    $locations_prefixes=explode(',',get_option('eme_permalink_locations_prefix','locations'));
	    if (!empty($location['location_prefix']) && in_array($location['location_prefix'],$locations_prefixes)) {
		    $locations_prefix=eme_permalink_convert($location['location_prefix']);
	    } else {
		    $locations_prefix=eme_permalink_convert($locations_prefixes[0]);
	    }

	    if (empty($location['location_slug'])) {
		    $name=$locations_prefix.$location['location_id']."/".eme_permalink_convert_noslash($location['location_name']);
	    } elseif (substr($location['location_slug'], -1) == '/') {
		    $name=$locations_prefix.$location['location_id']."/".eme_permalink_convert_noslash($location['location_slug']);
	    } else {
		    $name=$locations_prefix.eme_permalink_convert_noslash($location['location_slug']);
	    }
	    $the_link = eme_uri_add_lang($name,$language);
         } else {
            $the_link = eme_get_events_page();
            $the_link = add_query_arg( array( 'location_id' => $location['location_id'] ), $the_link );
            if (!empty($language)) {
               // some plugins add the lang info to the home_url, remove it so we don't get into trouble or add it twice
	       $the_link = remove_query_arg('lang',$the_link);
               $the_link = add_query_arg( array( 'lang' => $language ), $the_link );
	    }
         }
      }
   }
   return $the_link;
}

function eme_calendar_day_url($day) {
   global $wp_rewrite;

   $language = eme_detect_lang();

   if (isset($wp_rewrite) && $wp_rewrite->using_permalinks() && get_option('eme_seo_permalink')) {
      $cal_prefix_option=get_option('eme_permalink_calendar_prefix','');
      if (empty($cal_prefix_option)) {
	      $events_prefixes=explode(',',get_option('eme_permalink_events_prefix','events'));
	      $cal_prefix=eme_permalink_convert($events_prefixes[0]);
      } else {
	      $cal_prefix=eme_permalink_convert($cal_prefix_option);
      }
      $name=$cal_prefix.eme_permalink_convert_noslash($day);
      $the_link = eme_uri_add_lang($name,$language);
   } else {
      $the_link = eme_get_events_page();
      $the_link = add_query_arg( array( 'calendar_day' => $day ), $the_link );
      if (!empty($language)) {
         // some plugins add the lang info to the home_url, remove it so we don't get into trouble or add it twice
         $the_link = remove_query_arg('lang',$the_link);
         $the_link = add_query_arg( array( 'lang' => $language ), $the_link );
      }
   }
   return $the_link;
}

function eme_booking_confirm_url($payment) {
   // the user confirm url is actually going to the payment page as well, but we use another permalink to be nicer to people
   global $wp_rewrite;

   $language = eme_detect_lang();
   if (isset($wp_rewrite) && $wp_rewrite->using_permalinks() && get_option('eme_seo_permalink')) {
	   $events_prefixes=explode(',',get_option('eme_permalink_events_prefix','events'));
	   $confirm_prefix=eme_permalink_convert($events_prefixes[0])."confirm/";
	   $name=$confirm_prefix.$payment['random_id'];
	   $the_link = eme_uri_add_lang($name,$language);
   } else {
	   $the_link = eme_get_events_page();
	   $the_link = add_query_arg( array( 'eme_pmt_rndid' => $payment['random_id'] ), $the_link );
	   if (!empty($language)) {
		   // some plugins add the lang info to the home_url, remove it so we don't get into trouble or add it twice
		   $the_link = remove_query_arg('lang',$the_link);
		   $the_link = add_query_arg( array( 'lang' => $language ), $the_link );
	   }
   }
   return $the_link;
}

function eme_payment_url($payment,$resultcode=0) {
   global $wp_rewrite;

   $language = eme_detect_lang();
   if (isset($wp_rewrite) && $wp_rewrite->using_permalinks() && get_option('eme_seo_permalink')) {
      $payments_prefix_option=get_option('eme_permalink_payments_prefix','');
      if (empty($payments_prefix_option)) {
	      $events_prefixes=explode(',',get_option('eme_permalink_events_prefix','events'));
	      $payments_prefix=eme_permalink_convert($events_prefixes[0])."p/";
      } else {
	      $payments_prefix=eme_permalink_convert($payments_prefix_option);
      }
      $name=$payments_prefix.$payment['random_id'];
      $the_link = eme_uri_add_lang($name,$language);
   } else {
      $the_link = eme_get_events_page();
      $the_link = add_query_arg( array( 'eme_pmt_rndid' => $payment['random_id'] ), $the_link );
      if (!empty($language)) {
         // some plugins add the lang info to the home_url, remove it so we don't get into trouble or add it twice
         $the_link = remove_query_arg('lang',$the_link);
         $the_link = add_query_arg( array( 'lang' => $language ), $the_link );
      }
   }
   if ($resultcode>0) {
	 // we return the payment url but we want to indicate a payment failure too
         $the_link = add_query_arg( array( 'res_fail' => $resultcode ), $the_link );
   }
   return $the_link;
}

function eme_category_url($category) {
   global $wp_rewrite;

   $language = eme_detect_lang();
   if (isset($wp_rewrite) && $wp_rewrite->using_permalinks() && get_option('eme_seo_permalink')) {
         $categories_prefixes=explode(',',get_option('eme_permalink_categories_prefix',''));
	 if (empty($categories_prefixes)) {
		 $categories_prefixes=explode(',',get_option('eme_permalink_events_prefix','events'));
		 $extra_prefix="cat/";
	 } else {
		 $extra_prefix="";
	 }
         if (!empty($category['category_prefix']) && in_array($category['category_prefix'],$categories_prefixes)) {
                 $cats_prefix=eme_permalink_convert($category['category_prefix']);
         } else {
                 $cats_prefix=eme_permalink_convert($categories_prefixes[0]);
         }
         if (empty($category['category_slug'])) {
                 $name=$cats_prefix.$extra_prefix.eme_permalink_convert_noslash($category['category_name']);
         } else {
                 $name=$cats_prefix.$extra_prefix.eme_permalink_convert_noslash($category['category_slug']);
         }

	 $the_link = eme_uri_add_lang($name,$language);
   } else {
	   $the_link = eme_get_events_page();
	   $slug = $category['category_slug'] ? $category['category_slug'] : $category['category_name'];
	   $the_link = add_query_arg( array( 'eme_event_cat' => $slug ), $the_link );
	   if (!empty($language)) {
		   // some plugins add the lang info to the home_url, remove it so we don't get into trouble or add it twice
		   $the_link = remove_query_arg('lang',$the_link);
		   $the_link = add_query_arg( array( 'lang' => $language ), $the_link );
	   }
   }
   return $the_link;
}

function eme_check_invite_url($event_id) {
	if (isset($_GET['eme_invite'])) {
		$invite_get=eme_sanitize_request($_GET['eme_invite']);
	} else {
		return 0;
	}
	if (isset($_GET['eme_email'])) {
		$hash_string=sanitize_email($_GET['eme_email']);
	} else {
		return 0;
	}
	if (!empty($_GET['eme_ln'])) {
		$hash_string.=eme_sanitize_request($_GET['eme_ln']);
	}
	if (!empty($_GET['eme_fn'])) {
		$hash_string.=eme_sanitize_request($_GET['eme_fn']);
	}
        $invite_check=wp_hash($hash_string.'|'.$event_id,'nonce');
	if ($invite_check != $invite_get) {
		return 0;
	} else {
		return 1;
	}
}

function eme_invite_url($event,$email,$lastname,$firstname,$lang) {
   $the_link = eme_event_url($event,$lang);
   $hashstring=$email.$lastname.$firstname;
   $invite_hash=wp_hash($hashstring.'|'.$event['event_id'],'nonce');
   $the_link = add_query_arg( array( 'eme_email' => $email, 'eme_invite' => $invite_hash ), $the_link );
   if (!empty($lastname))
	   $the_link = add_query_arg( array( 'eme_ln' => $lastname), $the_link);
   if (!empty($firstname))
	   $the_link = add_query_arg( array( 'eme_fn' => $firstname), $the_link);
   return $the_link;
}

function eme_check_rsvp_url($payment,$booking_id) {
   $the_link = eme_get_events_page();
   $the_link = add_query_arg( array( 'eme_check_rsvp' => 1, 'eme_pmt_rndid' => $payment['random_id'] ), $the_link );
   $the_link = add_query_arg( array( 'booking_id' => $booking_id ), $the_link );
   return $the_link;
}

function eme_cpi_url($person_id,$orig_email) {
   $language = eme_detect_lang();

   $the_link = eme_get_events_page();
   $nonce = wp_create_nonce("change_pi $person_id $orig_email");
   $the_link = add_query_arg( array( 'eme_cpi'=>$person_id, 'email'=>$orig_email, 'eme_cpi_nonce'=>$nonce ), $the_link );
   if (!empty($language)) {
	   // some plugins add the lang info to the home_url, remove it so we don't get into trouble or add it twice
	   $the_link = remove_query_arg('lang',$the_link);
           $the_link = add_query_arg( array( 'lang' => $language ), $the_link );
   }
   return $the_link;
}

function eme_check_member_url() {
	if (isset($_GET['member_id'])) {
		$member_id=intval($_GET['member_id']);
	} else {
		return 0;
	}
	if (isset($_GET['eme_nonce'])) {
		$nonce_get=eme_sanitize_request($_GET['eme_nonce']);
	} else {
		return 0;
	}
        $nonce_check=wp_hash($member_id,'nonce');
	if ($nonce_check != $nonce_get) {
		return 0;
	} else {
                return eme_is_active_memberid($member_id);
	}
}

function eme_member_url($member) {
   $the_link = eme_get_events_page();
   $the_link = add_query_arg( array( 'eme_check_member' => 1 ), $the_link );
   $the_link = add_query_arg( array( 'member_id' => $member['member_id'] ), $the_link );
   $hash=wp_hash($member['member_id'],'nonce');
   $the_link = add_query_arg( array( 'eme_nonce' => $hash ), $the_link );
   return $the_link;
}

function eme_payment_return_url($payment,$resultcode) {
   $the_link = eme_get_events_page();
   if (is_numeric($resultcode) && $resultcode==0) {
         $res="success";
   } elseif (is_numeric($resultcode) && $resultcode==1) {
         //$res="fail";
	 return eme_payment_url($payment,$resultcode);
   } else {
	 // if the resultcode is not 1 or 2, we return it literally
	 // this is used for e.g. mollie, which in background first transfers the
	 // payment result using the notification link and then redirects to the return url
         $res=$resultcode;
   }
   $the_link = add_query_arg( array( 'eme_pmt_result' => $res ), $the_link );
   $the_link = add_query_arg( array( 'eme_pmt_rndid' => $payment['random_id'] ), $the_link );
   return $the_link;
}

function eme_tasksignup_cancel_url($signup) {
   $language = eme_detect_lang();

   $the_link = eme_get_events_page();
   $the_link = add_query_arg( array( 'eme_cancel_signup' => $signup['random_id'] ), $the_link );
   if (!empty($language)) {
	   // some plugins add the lang info to the home_url, remove it so we don't get into trouble or add it twice
	   $the_link = remove_query_arg('lang',$the_link);
           $the_link = add_query_arg( array( 'lang' => $language ), $the_link );
   }
   return $the_link;
}

function eme_cancel_url($payment) {
   $language = eme_detect_lang();

   $the_link = eme_get_events_page();
   $the_link = add_query_arg( array( 'eme_cancel_payment' => $payment['random_id'] ), $the_link );
   if (!empty($language)) {
	   // some plugins add the lang info to the home_url, remove it so we don't get into trouble or add it twice
	   $the_link = remove_query_arg('lang',$the_link);
           $the_link = add_query_arg( array( 'lang' => $language ), $the_link );
   }
   return $the_link;
}

function eme_unsub_url() {
   $language = eme_detect_lang();

   $the_link = eme_get_events_page();
   $the_link = add_query_arg( array( 'eme_unsub' => 1 ), $the_link );
   if (!empty($language)) {
	   // some plugins add the lang info to the home_url, remove it so we don't get into trouble or add it twice
	   $the_link = remove_query_arg('lang',$the_link);
           $the_link = add_query_arg( array( 'lang' => $language ), $the_link );
   }
   return $the_link;
}

function eme_unsub_confirm_url($email,$groups) {
   $language = eme_detect_lang();

   $the_link = eme_get_events_page();
   $nonce = wp_create_nonce("unsub $email$groups");
   $the_link = add_query_arg( array( 'eme_unsub_confirm' => $email, 'eme_unsub_nonce'=>$nonce ), $the_link );
   if (!empty($groups))
	   $the_link = add_query_arg( array( 'g' => $groups ), $the_link );
   if (!empty($language)) {
	   // some plugins add the lang info to the home_url, remove it so we don't get into trouble or add it twice
	   $the_link = remove_query_arg('lang',$the_link);
           $the_link = add_query_arg( array( 'lang' => $language ), $the_link );
   }
   return $the_link;
}

function eme_sub_confirm_url($lastname,$firstname,$email,$groups) {
   $language = eme_detect_lang();

   $the_link = eme_get_events_page();
   $nonce = wp_create_nonce("sub $lastname$firstname$email$groups");
   $the_link = add_query_arg( array( 'eme_sub_confirm' => $email, 'lastname' => $lastname,'firstname' => $firstname, 'eme_sub_nonce'=>$nonce ), $the_link );
   if (!empty($groups))
	   $the_link = add_query_arg( array( 'g' => $groups ), $the_link );
   if (!empty($language)) {
	   // some plugins add the lang info to the home_url, remove it so we don't get into trouble or add it twice
	   $the_link = remove_query_arg('lang',$the_link);
           $the_link = add_query_arg( array( 'lang' => $language ), $the_link );
   }
   return $the_link;
}

function eme_single_event_ical_url($id) {
   $language = eme_detect_lang();

   $the_link = site_url ("/?eme_ical=public_single&event_id=".$id);
   if (!empty($language)) {
           $the_link = add_query_arg( array( 'lang' => $language ), $the_link );
   }
   return $the_link;
}

function eme_captcha_url($file) {
   $the_link = "";
   // if there's an issue with the tmp dir, return false so we can show a message in the form
   $tmp_dir=get_temp_dir();
   if (!@is_dir($tmp_dir) || !wp_is_writable($tmp_dir)) {
	   return false;
   }
   // ts added to try and prevent initial caching
   $the_link = add_query_arg( array( 'eme_captcha' => 'generate','f' => $file, 'ts'=>time() ), $the_link );
   return $the_link;
}

function eme_tracker_url($random_id) {
   $the_link = eme_get_events_page();
   $the_link = add_query_arg( array( 'eme_tracker_id' => $random_id ), $the_link );
   return $the_link;
}

function eme_current_page_url($extra_arg=array()) {
	global $wp;
	$the_link = home_url( add_query_arg( array(), $wp->request ) );
	if (!empty($extra_arg)) {
		$extra_arg=array_map('esc_attr',$extra_arg);
		$the_link = add_query_arg( $extra_arg, $the_link );
	}
	return $the_link;
}

function eme_check_event_exists($id) {
        global $wpdb;
	$table = $wpdb->prefix . EVENTS_TBNAME;
	$event = eme_get_event($id);
	return $event;
}

function eme_check_location_exists($id) {
        global $wpdb;
	$table = $wpdb->prefix . LOCATIONS_TBNAME;
	$location = eme_get_location($id);
	return $location;
}

function eme_are_dates_valid($dates) {
   // if it is a series of dates
   if (strstr($dates, ',')) {
	$dates_arr=explode(',',$dates);
   	foreach ( $dates_arr as $date ) {
		if (!eme_is_date($date)) return false;
	}
   } elseif (!eme_is_date($dates)) {
	return false;
   }
   return true;
}
	
function eme_is_date($date) {
   // check the format yyyy-mm-dd
   if (strlen($date) != 10)
      return false;
   $year = intval(substr ( $date, 0, 4 ));
   $month = intval(substr ( $date, 5, 2 ));
   $day = intval(substr ( $date, 8 ));
   return (checkdate($month,$day,$year));
}

function eme_is_datetime($date) {
   $format = 'Y-m-d H:i:s';
   $d = DateTime::createFromFormat($format, $date);
   return $d && $d->format($format) == $date;
}

function eme_capNamesCB ( $cap ) {
   $cap = str_replace('_', ' ', $cap);
   $cap = ucfirst($cap);
   return $cap;
}
function eme_get_all_caps() {
   global $wp_roles;
   $caps = array();
   $capabilities = array();

   foreach ( $wp_roles->roles as $role ) {
      if ($role['capabilities']) {
         foreach ( $role['capabilities'] as $cap=>$val ) {
           if (!preg_match("/^level/",$cap))
	      $capabilities[$cap]=eme_capNamesCB($cap);
         }
      }
   }

#   $sys_caps = get_option('syscaps');
#   if ( is_array($sys_caps) ) {
#      $capabilities = array_merge($sys_caps, $capabilities);
#   }

   asort($capabilities);
   return $capabilities;
}

function eme_status_array() {
   $status_array = array();
   $status_array[EME_EVENT_STATUS_PUBLIC] = __ ( 'Public', 'events-made-easy');
   $status_array[EME_EVENT_STATUS_PRIVATE] = __ ( 'Private', 'events-made-easy');
   $status_array[EME_EVENT_STATUS_DRAFT] = __ ( 'Draft', 'events-made-easy');
   return $status_array;
}

function eme_member_status_array() {
   $status_array = array();
   $status_array[EME_MEMBER_STATUS_PENDING] = __ ( 'Pending', 'events-made-easy');
   $status_array[EME_MEMBER_STATUS_ACTIVE] = __ ( 'Active', 'events-made-easy');
   $status_array[EME_MEMBER_STATUS_GRACE] = __ ( 'Grace period', 'events-made-easy');
   $status_array[EME_MEMBER_STATUS_EXPIRED] = __ ( 'Expired', 'events-made-easy');
   return $status_array;
}

// The next function is to format the datetime in the expected format for the calendar javascript to be able to parse it
// If the argument is just a date (no time portion), this function doesn't need to be called since the calendar JS can cope with that already
function eme_js_datetime($mydate,$timezone="") {
   global $eme_timezone;

   if (empty($timezone))
	   $timezone=$eme_timezone;

//  $ExactBrowserNameUA=strtolower($_SERVER['HTTP_USER_AGENT']);
//  if (strpos($ExactBrowserNameUA, "safari/") and strpos($ExactBrowserNameUA, "opr/")==false and strpos($ExactBrowserNameUA, "chrome/")==false) {
//	   $safari=1;
//  } else {
//	   $safari=0;
//  }

   if (empty($mydate) || preg_match('/^0000-00-00/', $mydate)) {
	   return "";
   } elseif (strstr($mydate, ',')) {
	   $dates=explode(',',$mydate);
	   $res_arr=array();
	   foreach ( $dates as $date ) {
		   $eme_date_obj = new ExpressiveDate($date,$timezone);
		   //if ($safari)
		//	   $res_arr[]=$eme_date_obj->format('U')*1000;
		 //  else
			   $res_arr[]=$eme_date_obj->format('Y-m-d\TH:i:s');
	   }
	   return join(',',$res_arr);
   } else {
	   $eme_date_obj = new ExpressiveDate($mydate,$timezone);
	   //if ($safari)
	//	   return $eme_date_obj->format('U')*1000;
	 //  else
		   return $eme_date_obj->format('Y-m-d\TH:i:s');
   }
}

function eme_get_time_from_dt($datetime) {
	return substr($datetime,11,8);
}
function eme_get_date_from_dt($datetime) {
	return substr($datetime,0,10);
}

function eme_N_weekday($date_obj) {
   return $date_obj->format('N');
}

function eme_localized_datetime($mydate,$timezone='',$datetime_format='') {
   if (eme_is_empty_datetime($mydate))
	   return "";
   if ($datetime_format==1)
	   return eme_localized_date($mydate,$timezone,1).' '.eme_localized_time($mydate,$timezone,1);
   elseif (!empty($datetime_format))
	   return eme_localized_date($mydate,$timezone,$datetimeformat);
   else
	   return eme_localized_date($mydate,$timezone).' '.eme_localized_time($mydate,$timezone);
}

function eme_localized_date($mydate,$timezone="",$date_format='') {
   global $eme_wp_date_format,$eme_timezone;
   if (eme_is_empty_date($mydate))
	   return "";
   if ($date_format==1)
	   $date_format=get_option('eme_backend_dateformat');
   if (empty($date_format))
      $date_format = $eme_wp_date_format;
   if (empty($timezone))
      $timezone=$eme_timezone;

   // catch possible issues with invalid/unparseable dates etc ...
   try {
	   // $mydate contains the timezone, but in case it doesn't we provide it
	   $eme_date_obj = new ExpressiveDate($mydate,$timezone);
   } catch (Exception $error) {
	   return $mydate;
   }
   if (function_exists('wp_date')) {
	   $eme_tz_obj=new DateTimeZone($timezone);
	   $result = wp_date($date_format, $eme_date_obj->getTimestamp(),$eme_tz_obj);
   } else {
	   // Currently in the backend, the timezone is UTC, but maybe that changes in future wp versions
	   //   so we search for the current timezone using date_default_timezone_get
	   // Since DateTime::format doesn't respect the locale, we use date_i18n here
	   //   but date_i18n uses the WP backend timezone, so we need to account for the timezone difference
	   // All this because we don't want to use date_default_timezone_set() and wp doesn't set the backend
	   //   timezone correctly ...
	   $wp_date = new ExpressiveDate($eme_date_obj->getDateTime(),date_default_timezone_get());
	   $tz_diff=$eme_date_obj->getOffset()-$wp_date->getOffset();
	   // reason to add the tz_diff: getTimestamp returns the timestamp in the current timezoneformat set on the server
	   $result = date_i18n($date_format, $eme_date_obj->getTimestamp()+$tz_diff);
   }
   return $result;
}

function eme_localized_time($mytime,$timezone='',$time_format='') {
   global $eme_wp_time_format,$eme_timezone;

   if ($time_format==1)
	   $time_format=get_option('eme_backend_timeformat');
   if (empty($time_format))
      $time_format = $eme_wp_time_format;
   if (empty($timezone))
      $timezone=$eme_timezone;

   // if strlen is >6, we assume it includes the date-portion in the string too
   if (strlen($mytime)<6)
	   $result = eme_localized_date("2012-01-01 ".$mytime,$timezone,$time_format);
   else
	   $result = eme_localized_date($mytime,$timezone,$time_format);
   if (get_option('eme_time_remove_leading_zeros')) {
      $result = str_replace(":00","",$result);
      $result = str_replace(":0",":",$result);
   }
   return $result;
}

function eme_convert_localized_time($time_format,$mytime) {
   global $eme_wp_time_format,$eme_timezone;

   if (empty($time_format))
      $time_format = $eme_wp_time_format;

   $date_obj=ExpressiveDate::createfromformat($time_format,$mytime, ExpressiveDate::parseSuppliedTimezone($eme_timezone));
   return $date_obj->format("H:i:00");
}

// the eme_localized_db_* functions are no longer used, but let's keep them as a reference
function eme_localized_db_datetime($mydate) {
   return eme_localized_db_date($mydate).' '.eme_localized_db_time($mydate);
}
function eme_localized_db_date($mydate,$date_format='') {
   global $eme_wp_date_format, $eme_timezone;
   $eme_db_time_diff = eme_get_db_time_diff();

   if (empty($date_format))
      $date_format = $eme_wp_date_format;
   $eme_date_obj = new ExpressiveDate($mydate,$eme_timezone);
   // account for the difference stored in the db (we claim that $mydate is in timezone eme_timezone, but the db might be in a different tz)
   $eme_date_obj->addSeconds($eme_db_time_diff);
   // rest is taken from eme_localize_date
   if (function_exists('wp_date')) {
	   $eme_tz_obj=new DateTimeZone($eme_timezone);
	   $result = wp_date($date_format, $eme_date_obj->getTimestamp(),$eme_tz_obj);
   } else {
	   $wp_date = new ExpressiveDate($eme_date_obj->getDateTime(),date_default_timezone_get());
	   $tz_diff=$eme_date_obj->getOffset()-$wp_date->getOffset();
	   $result = date_i18n($date_format, $eme_date_obj->getTimestamp()+$tz_diff);
   }
   return $result;
}

function eme_localized_db_time($mydate) {
   global $eme_wp_time_format;
   $result = eme_localized_db_date($mydate,$eme_wp_time_format);
   if (get_option('eme_time_remove_leading_zeros')) {
      $result = str_replace(":00","",$result);
      $result = str_replace(":0",":",$result);
   }
   return $result;
}

// the following is the same as eme_localized_date, but for rfc822 format
function eme_rfc822_date($mydate,$tz) {
   //$result = date('r', strtotime($mydate));
   $eme_date_obj = new ExpressiveDate($mydate,$tz);
   return $eme_date_obj->format('r');
}

function eme_get_db_time_diff() {
   global $wpdb, $eme_timezone;
   $eme_date_obj = new ExpressiveDate("now",$eme_timezone);
   $db_diff=$wpdb->get_var("select time_to_sec(time_format(timediff(now(),UTC_TIMESTAMP),'%H:%i'))");
   $tz_diff=$eme_date_obj->getOffset()-$db_diff;
   return $tz_diff;
}

function eme_localized_currencysymbol($cur,$target="html") {
   if (!class_exists('NumberFormatter')) {
	   return $cur;
   } else {
	   $locale = determine_locale();
	   $formatter = new NumberFormatter( $locale."@currency=$cur", NumberFormatter::CURRENCY );
	   return $formatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
   }
}

function eme_localized_price($price,$cur,$target="html") {
   // number_format needs a floating point, so if price is empty (for e.g. discounts), make it 0
   if (empty($price)) $price=0;
   if (eme_is_multi($price))
	   $price_arr=eme_convert_multi2array($price);
   else
	   $price_arr=array($price);

   $locale = determine_locale();
   if (class_exists('NumberFormatter') && get_option('eme_localize_price'))
           $eme_localize_price=1;
   else
           $eme_localize_price=0;

   if ($eme_localize_price)
           $formatter = new NumberFormatter( $locale."@currency=$cur", NumberFormatter::CURRENCY );

   $eme_zero_decimal_currencies_arr=eme_zero_decimal_currencies();
   if (in_array($cur,$eme_zero_decimal_currencies_arr))
           $decimals = 0;
   else
           $decimals=intval(get_option('eme_decimals'));

   $res=array();
   foreach ($price_arr as $t_price) {
	   if ($eme_localize_price)
                   $result = $formatter->formatCurrency($t_price, $cur);
           else
                   $result = number_format_i18n($t_price,$decimals);

	   // the result can contain html entities, for e.g. text mails we don't want that of course
	   if ($target == "html") {
		   $res[] = $result;
	   } else {
		   $res[] = html_entity_decode($result);
	   }
   }
   return implode("||",$res);
}

function eme_currency_array() {
   $currency_array = array ();
   $currency_array ['ARS'] = __ ( 'Argentine Peso', 'events-made-easy');
   $currency_array ['AUD'] = __ ( 'Australian Dollar', 'events-made-easy');
   $currency_array ['BIF'] = __ ( 'Burundian Franc', 'events-made-easy');
   $currency_array ['BRL'] = __ ( 'Brazilian Real', 'events-made-easy');
   $currency_array ['CAD'] = __ ( 'Canadian Dollar', 'events-made-easy');
   $currency_array ['CLP'] = __ ( 'Chilean Peso', 'events-made-easy');
   $currency_array ['CNY'] = __ ( 'Chinese Yuan Renminbi', 'events-made-easy');
   $currency_array ['CZK'] = __ ( 'Czech Koruna', 'events-made-easy');
   $currency_array ['DKK'] = __ ( 'Danish Krone', 'events-made-easy');
   $currency_array ['DJF'] = __ ( 'Djiboutian Franc', 'events-made-easy');
   $currency_array ['EUR'] = __ ( 'Euro', 'events-made-easy');
   $currency_array ['GBP'] = __ ( 'Pound Sterling', 'events-made-easy');
   $currency_array ['GNF'] = __ ( 'Guinean Franc', 'events-made-easy');
   $currency_array ['HKD'] = __ ( 'Hong Kong Dollar', 'events-made-easy');
   $currency_array ['HUF'] = __ ( 'Hungarian Forint', 'events-made-easy');
   $currency_array ['INR'] = __ ( 'Indian Rupee', 'events-made-easy');
   $currency_array ['ILS'] = __ ( 'Israeli New Sheqel', 'events-made-easy');
   $currency_array ['JPY'] = __ ( 'Japanese Yen', 'events-made-easy');
   $currency_array ['KMF'] = __ ( 'Comoro Franc', 'events-made-easy');
   $currency_array ['KRW'] = __ ( 'South Korean Won', 'events-made-easy');
   $currency_array ['MGA'] = __ ( 'Malagasy Ariary', 'events-made-easy');
   $currency_array ['MXN'] = __ ( 'Mexican Peso', 'events-made-easy');
   $currency_array ['NOK'] = __ ( 'Norwegian Krone', 'events-made-easy');
   $currency_array ['NZD'] = __ ( 'New Zealand Dollar', 'events-made-easy');
   $currency_array ['PHP'] = __ ( 'Philippine Peso', 'events-made-easy');
   $currency_array ['PLN'] = __ ( 'Polish Zloty', 'events-made-easy');
   $currency_array ['PYG'] = __ ( 'Paraguayan Guaraní', 'events-made-easy');
   $currency_array ['RUB'] = __ ( 'Russian Ruble', 'events-made-easy');
   $currency_array ['RWF'] = __ ( 'Rwandan Franc', 'events-made-easy');
   $currency_array ['SGD'] = __ ( 'Singapore Dollar', 'events-made-easy');
   $currency_array ['SEK'] = __ ( 'Swedish Krona', 'events-made-easy');
   $currency_array ['CHF'] = __ ( 'Swiss Franc', 'events-made-easy');
   $currency_array ['THB'] = __ ( 'Thai Baht', 'events-made-easy');
   $currency_array ['USD'] = __ ( 'U.S. Dollar', 'events-made-easy');
   $currency_array ['VND'] = __ ( 'Vietnamese đồng', 'events-made-easy');
   $currency_array ['VUV'] = __ ( 'Vanuatu vatu', 'events-made-easy');
   $currency_array ['XAF'] = __ ( 'CFA Franc BEAC', 'events-made-easy');
   $currency_array ['XOF'] = __ ( 'CFA Franc BCEAO', 'events-made-easy');
   $currency_array ['XPF'] = __ ( 'CFP Franc (Franc Pacifique)', 'events-made-easy');
   $currency_array ['ZAR'] = __ ( 'South African Rand', 'events-made-easy');

   # the next filter allows people to add extra currencies:
   if (has_filter('eme_add_currencies')) $currency_array=apply_filters('eme_add_currencies',$currency_array);
   natcasesort($currency_array);
   return $currency_array;
}

function eme_currency_codes() {
   $currency_array = array ();
   $currency_array ['ARS'] = '032'; 
   $currency_array ['AUD'] = '036'; 
   $currency_array ['BIF'] = '108'; 
   $currency_array ['BRL'] = '986'; 
   $currency_array ['CAD'] = '124';
   $currency_array ['CLP'] = '152';
   $currency_array ['CNY'] = '156';
   $currency_array ['CZK'] = '203';
   $currency_array ['DKK'] = '208';
   $currency_array ['DJF'] = '262';
   $currency_array ['EUR'] = '978';
   $currency_array ['GBP'] = '826';
   $currency_array ['GNF'] = '978';
   $currency_array ['HKD'] = '344';
   $currency_array ['HUF'] = '348';
   $currency_array ['INR'] = '356';
   $currency_array ['ILS'] = '376';
   $currency_array ['JPY'] = '392';
   $currency_array ['KMF'] = '174';
   $currency_array ['KRW'] = '410';
   $currency_array ['MGA'] = '969';
   $currency_array ['MXN'] = '484';
   $currency_array ['NOK'] = '578';
   $currency_array ['NZD'] = '554';
   $currency_array ['PHP'] = '608';
   $currency_array ['PLN'] = '985';
   $currency_array ['PYG'] = '600';
   $currency_array ['RWF'] = '646';
   $currency_array ['SGD'] = '702';
   $currency_array ['SEK'] = '752';
   $currency_array ['CHF'] = '756';
   $currency_array ['THB'] = '764';
   $currency_array ['USD'] = '840';
   $currency_array ['VND'] = '704';
   $currency_array ['VUV'] = '548';
   $currency_array ['XAF'] = '950';
   $currency_array ['XOF'] = '952';
   $currency_array ['XPF'] = '953';
   $currency_array ['ZAR'] = '710';

   # the next filter allows people to add extra currencies:
   if (has_filter('eme_add_currency_codes')) $currency_array=apply_filters('eme_add_currency_codes',$currency_array);
   return $currency_array;
}

function eme_zero_decimal_currencies() {
   # returns an array of currencies that don't have decimals
   $currency_array = array (
    'BIF',
    'CLP',
    'DJF',
    'GNF',
    'JPY',
    'KMF',
    'KRW',
    'MGA',
    'PYG',
    'RWF',
    'VND',
    'VUV',
    'XAF',
    'XOF',
    'XPF'
   );
   # the next filter allows people to add extra currencies:
   if (has_filter('eme_add_zero_decimal_currencies')) $currency_array=apply_filters('eme_add_zero_decimal_currencies',$currency_array);
   return $currency_array;
}

function eme_thumbnail_sizes() {
   $sizes = array();
   foreach ( get_intermediate_image_sizes() as $s ) {
      $sizes[ $s ] = $s;
   }
   return $sizes;
}

function eme_transfer_nbr_be97($my_nbr) {
   $transfer_nbr_be97_main=sprintf("%010d",$my_nbr);
   // the control number is the %97 result, or 97 in case %97=0
   $transfer_nbr_be97_check=$transfer_nbr_be97_main % 97;
   if ($transfer_nbr_be97_check==0)
      $transfer_nbr_be97_check = 97 ;
   $transfer_nbr_be97_check=sprintf("%02d",$transfer_nbr_be97_check);
   return $transfer_nbr_be97_main.$transfer_nbr_be97_check;
}

function eme_transfer_nbr_be97_formatted($my_nbr) {
   return '+++'.substr($my_nbr,0,3).'/'.substr($my_nbr,3,4).'/'.substr($my_nbr,7,5).'+++';
}

# support older php version for array_replace_recursive
if (!function_exists('array_replace_recursive')) {
   function array_replace_recursive($array, $array1) {
      function recurse($array, $array1) {
         foreach ($array1 as $key => $value) {
            // create new key in $array, if it is empty or not an array
            if (!isset($array[$key]) || (isset($array[$key]) && !is_array($array[$key]))) {
               $array[$key] = array();
            }

            // overwrite the value in the base array
            if (is_array($value)) {
               $value = recurse($array[$key], $value);
            }
            $array[$key] = $value;
         }
         return $array;
      }

      // handle the arguments, merge one by one
      $args = func_get_args();
      $array = $args[0];
      if (!is_array($array)) {
         return $array;
      }
      for ($i = 1; $i < count($args); $i++) {
         if (is_array($args[$i])) {
            $array = recurse($array, $args[$i]);
         }
      }
      return $array;
   }
}

// returns 1 if each element of array1 is > than the correspondig element of array2 
function eme_array_gt($array1, $array2) {
   if (count($array1) != count($array2))
      return false;
   foreach ($array1 as $key => $value) {
      if ($array1[$key]<=$array2[$key])
         return 0;
   }
   return 1;
}

// returns 1 if each element of array1 is >= the correspondig element of array2 
function eme_array_ge($array1, $array2) {
   if (count($array1) != count($array2))
      return false;
   foreach ($array1 as $key => $value) {
      if ($array1[$key]<$array2[$key])
         return 0;
   }
   return 1;
}

// returns 1 if each element of array1 is < than the correspondig element of array2 
function eme_array_lt($array1, $array2) {
   if (count($array1) != count($array2))
      return false;
   foreach ($array1 as $key => $value) {
      if ($array1[$key]>=$array2[$key])
         return 0;
   }
   return 1;
}

// returns 1 if each elements of array1 is <= than the correspondig element of array2 
function eme_array_le($array1, $array2) {
   if (count($array1) != count($array2))
      return false;
   foreach ($array1 as $key => $value) {
      if ($array1[$key]>$array2[$key])
         return 0;
   }
   return 1;
}

function eme_get_query_arg($arg) {
   if (isset($_GET[$arg]))
      return eme_sanitize_request($_GET[$arg]);
   else
      return false;
}

// returns true if the array values are all integers
function eme_array_integers($only_integers) {
   if (!is_array($only_integers)) return false;
   return array_filter($only_integers,'is_numeric') === $only_integers;
}

function eme_is_list_of_int($text) {
        $is_ok=true;
        if (strstr($text, ',')) {
                $id_arr=explode(',',$text);
		if (!eme_array_integers($id_arr))
                        $is_ok=false;
        } else {
                if (!is_numeric($text))
                        $is_ok=false;
        }
	return $is_ok;
}

function eme_array_remove_empty_elements($arr) {
   $arr = array_filter($arr,'strlen');
   return $arr;
}

function eme_nl2br($arg) {
   return preg_replace("/\r\n?|\n\r?/","<br />",$arg);
}

function eme_br2nl($arg) {
   return preg_replace("/<br ?\/?>/", "\n", $arg);
}

function eme_str_numbers_only($arg) {
   return preg_replace('/[^0-9]/','',$arg);
}

function eme_is_url($url) {
   if (eme_is_empty_string($url)) return false;
   $parsed = parse_url($url);
   if (empty($parsed['scheme'])) {
          $url = 'https://' . ltrim($url, '/');
   }
   return filter_var($url, FILTER_VALIDATE_URL);
}

function eme_is_email($email,$dns_lookup_required=0) {
   if (eme_is_empty_string($email)) return false;
   // FILTER_VALIDATE_EMAIL is not utf-8 compliant
   // so we add an extra regex too, which allows pretty much every unicode word characters
   $email_regex_ok=0;
   if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_regex_ok=1;
   } elseif (preg_match("/^[\w\-\.\+]+@([\w\-]+\.)+[\w\-]{2,63}$/u", $email)) {
        $email_regex_ok=1;
   } else {
        return false;
   }

   // the regex is ok, now do a dns lookup if wanted
   if ($dns_lookup_required && $email_regex_ok) {
	   // get the part after the '@'
	   $fqdn = substr(strrchr($email, "@"), 1);
	   // add trailing '.'
           $fqdn .= (substr($fqdn, -1) == '.' ? '' : '.');
	   if ($result = getmxrr($fqdn, $mx_records, $mx_weight)) {
		   if (!isset($mx_records) || (count($mx_records) == 0)) {
			   $result=false;
		   }
		   $mx_resolve=false;
		   foreach ($mx_records as $mx_record) {
			   $result=filter_var(gethostbyname($mx_record), FILTER_VALIDATE_IP);
			   if (!empty($result))
				   $mx_resolve=true;
		   }
		   if (!$mx_resolve)
			   $result=false;
	   }
	   // no mx found? Then try the hostname
	   if (!$result)
		   $result=filter_var(gethostbyname($fqdn), FILTER_VALIDATE_IP);
	   if (empty($result))
		   return false;
   }
   return true;
}

function eme_is_multi($element) {
   if (preg_match("/\|\|/",$element))
      return 1;
   else
      return 0;
}

function eme_is_never_admin() {
        $is_ajax = false;
	if ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) {
		return true;
	} elseif (defined('DOING_AJAX') && DOING_AJAX) {
		return true;
	}
	if ( function_exists( 'wp_doing_cron' ) && wp_doing_cron() ) {
		return true;
	} elseif (defined('DOING_CRON') && DOING_CRON) {
		return true;
	}
        if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) { return true; }
        if (defined('REST_REQUEST') && REST_REQUEST) { return true; }
}

function eme_convert_multi2br($multistring) {
   return str_replace("||","<br />",$multistring);
}

function eme_convert_multi2array($multistring) {
   return explode("||",$multistring);
}

function eme_convert_array2multi($multiarr,$sep='||') {
   return join($sep,$multiarr);
}

function eme_random_id() {
	$length=14+32;
	if (function_exists("random_bytes")) {
		$bytes = random_bytes(ceil($length / 2));
		return substr(bin2hex($bytes), 0, $length);
	} elseif (function_exists("openssl_random_pseudo_bytes")) {
		$bytes = openssl_random_pseudo_bytes(ceil($length / 2));
		return substr(bin2hex($bytes), 0, $length);
	} else {
		// the original function, and added substr to it so it never goes beyond 50 chars
		return substr(uniqid(),0,13) . '_' . substr(md5(mt_rand()),0,32);
	}
}

// API function so people can call this from their theme's search.php
function eme_wordpress_search_locations() {
   if (isset($_REQUEST['s']))
      return eme_search_locations($_REQUEST['s']);
}

// API function so people can call this from their theme's search.php
function eme_wordpress_search($scope="future") {
      return eme_wordpress_search_events($scope);
}
function eme_wordpress_search_events($scope="future") {
   if (isset($_REQUEST['s']))
      return eme_search_events($_REQUEST['s'],$scope);
}

function eme_fake_booking($event) {
   $booking = eme_booking_from_form($event);
   // indicate a negative person ID, so we can check on that later on if needed
   $booking['person_id']=-1;
   return $booking;
}

function eme_booking_from_form($event) {
      $booking=eme_new_booking();
      $bookedSeats = 0;
      $bookedSeats_mp = array();
      $dcodes_entered = array();
      $event_id=$event['event_id'];
      if (!eme_is_multi($event['price'])) {
         if (isset($_POST['bookings'][$event_id]['bookedSeats']))
            $bookedSeats = intval($_POST['bookings'][$event_id]['bookedSeats']);
         else
            $bookedSeats = 0;
      } else {
         // for multiple prices, we have multiple booked Seats as well
         // the next foreach is only valid when called from the frontend

         // make sure the array contains the correct keys first
         $booking_prices_mp=eme_convert_multi2array($event['price']);
         foreach ($booking_prices_mp as $key=>$value) {
            $bookedSeats_mp[$key] = 0;
         }
	 if (isset($_POST['bookings'][$event_id])) {
		 foreach($_POST['bookings'][$event_id] as $key=>$value) {
			 if (preg_match('/bookedSeats(\d+)/', $key, $matches)) {
				 $field_id = intval($matches[1])-1;
				 $bookedSeats += intval($value);
				 $bookedSeats_mp[$field_id]=intval($value);
			 }
		 }
	 }
      }
      if (isset($_POST['bookings'][$event_id])) {
	      foreach($_POST['bookings'][$event_id] as $key=>$value) {
		      if (preg_match('/^DISCOUNT/', $key, $matches)) {
			      $discount_value=eme_sanitize_request($value);
			      if (!empty($value))
				      $dcodes_entered[]=$discount_value;
		      }
	      }
      }

      // for non-multi event seats, let's check the waitinglist option too
      if (!eme_is_multi($event['event_seats'])) {
	      $waitinglist_seats = intval($event['event_properties']['waitinglist_seats']);
	      // check for ream available seats excluding waitinglis
	      $avail_seats = eme_get_available_seats($event_id,1);
	      if ($waitinglist_seats>0 && $avail_seats<=0) {
		      $booking['waitinglist']=1;
	      }
      }

      if (isset($_POST['bookings'][$event_id]['eme_rsvpcomment']))
         $bookerComment = eme_sanitize_textarea($_POST['bookings'][$event_id]['eme_rsvpcomment']);
      elseif (isset($_POST['eme_rsvpcomment']))
         $bookerComment = eme_sanitize_textarea($_POST['eme_rsvpcomment']);
      else
         $bookerComment = "";

      $booking['booking_id']=0;
      $booking['event_id']=$event_id;
      $booking['booking_seats']=$bookedSeats;
      $booking['booking_seats_mp']=eme_convert_array2multi($bookedSeats_mp);
      $booking['event_price']=$event['price'];
      $booking['booking_comment']=$bookerComment;
      $booking['extra_charge']=eme_store_booking_answers($booking,0);

      $booking['dcodes_entered']=$dcodes_entered;
      $calc_discount=eme_booking_discount($event,$booking);
      $booking['discount']=$calc_discount['discount'];
      $booking['dcodes_used']=$calc_discount['dcodes_used'];
      $booking['discountids']=$calc_discount['discountids'];
      $booking['dgroupid']=$calc_discount['dgroupid'];
      $booking['remaining'] = eme_get_total_booking_price($booking);
      return $booking;
}

function eme_calc_bookingprice_ajax() {
   header("Content-type: application/json; charset=utf-8");
   // first detect multibooking
   $event_ids=array();
   if (isset($_POST['bookings'])) {
      foreach($_POST['bookings'] as $key=>$val) {
         $event_ids[]=intval($key);
      }
   }
   $total=0;
   $cur='';
   // in the admin interface, we have a booking id, so we use the currently applied discount
   if (!empty($_POST['booking_id'])) {
           $booking_id = intval($_POST['booking_id']);
           check_admin_referer("eme_admin $booking_id",'eme_admin_nonce');
   }

   foreach ($event_ids as $event_id) {
      $event = eme_get_event($event_id);
      if (!empty($event)) {
	      $fake_booking = eme_fake_booking($event);
	      $total += $fake_booking['remaining'];
	      $cur = $event['currency'];
      }
   }

   $result = eme_localized_price($total,$cur);
   echo json_encode(array('total'=>$result));
}

// the people dyndata only gets called from the backend, so we use wp ajax and check the admin nonce
add_action( 'wp_ajax_eme_people_dyndata', 'eme_dyndata_people_ajax' );
function eme_dyndata_people_ajax() {
   check_admin_referer('eme_admin','eme_admin_nonce');
   // for new persons, the id=0
   if (isset($_POST['person_id']))
	   $person_id=intval($_POST['person_id']);
   else
	   $person_id=0;
   $dynamic_field_class='nodynamicupdates dynamicfield';

   if ($person_id) {
	   $answers = eme_get_person_answers($person_id);
	   $files = eme_get_uploaded_files($person_id,"people");
   } else {
	   $answers = array();
	   $files = array();
   }

   if (isset($_POST['groups']) && eme_array_integers($_POST['groups']))
	   $groups=$_POST['groups'];
   else
	   $groups=array();
   // We need the groupid 0 as the first element, to show fields not belonging to a specific group (the array keys are not important here, only the values)
   array_unshift($groups,"0");
   $form_html="";
   foreach ($groups as $group_id) {
	   $group = eme_get_group($group_id);
           $fields = eme_get_dyndata_people_fields('group:'.$group_id);
	   if (!empty($fields)) {
		   if (!$group_id)
			   $form_html.="<hr><div><span id='eme-people-dyndata-group-".$group_id."'>".eme_esc_html(__('Extra info','events-made-easy'))."</span><table>";
		   else
			   $form_html.="<hr><div><span id='eme-people-dyndata-group-".$group_id."'>".eme_esc_html(__('Group','events-made-easy').' '.$group['name'])."</span><table>";
		   foreach ($fields as $formfield) {
			   $field_id=$formfield['field_id'];
			   $form_html.="<tr><td>";
			   $name = eme_trans_esc_html($formfield['field_name']);
			   $form_html.="$name</td><td>";
			   $var_prefix="dynamic_personfields[$person_id][";
			   $var_postfix="]";
			   $postfield_name="${var_prefix}FIELD".$field_id.$var_postfix;
			   $postvar_arr=array('dynamic_personfields',$person_id,"FIELD".$field_id);
			   // the first time there's no $_POST yet
			   if (!empty($_POST))
				   $entered_val = eme_getValueFromPath($_POST,$postvar_arr);
			   else
				   $entered_val = false;

			   if ($entered_val === false) {
				   foreach ($answers as $answer) {
					   if ($answer['field_id'] == $field_id) {
						   $entered_val = $answer['answer'];
					   }
				   }
			   }
			   // the next code prevents uploading a file for a file field if already done, but
			   // it is a bit counter-intuitive to be able to remove a file, but needing to save
			   // before the upload shows again
			   // So currently we don't do this
			   //if ($formfield['field_type'] == 'file') {
			   //        $uploaded_html='';
			   //        foreach ($files as $file) {
			   //     	   if ($file['field_id'] == $field_id) {
			   //     		   $entered_val = $file;
			   //     		   break;
			   //     	   }
			   //        }
			   //}
			   if ($formfield['field_required'])
				   $required=1;
			   else
				   $required=0;
			   $form_html.= eme_get_formfield_html($formfield,$postfield_name,$entered_val,$required, $dynamic_field_class);
			   if ($formfield['field_required'])
				   $form_html .= "<div class='eme-required-field'>".get_option('eme_form_required_field_string')."</div>";
			   $form_html.="</td></tr>";
		   }
		   $form_html.="</table></div>";
	   }
   }
   echo json_encode(array('Result'=>$form_html));
   wp_die();
}

function eme_dyndata_rsvp_ajax() {
   header("Content-type: application/json; charset=utf-8");
   // first detect multibooking
   $event_ids=array();
   if (isset($_POST['eme_event_ids'])) {
           $event_ids = array_map('intval',$_POST['eme_event_ids']);
   } elseif (eme_is_admin_request() && isset($_POST['event_id'])) {
	   // the case when adding a booking in the backend
           $event_ids = array(0=>intval($_POST['event_id']));
   }

   if (!empty($_POST['booking_id'])) {
	   // the case when editing a booking in the backend
	   $booking_id=intval($_POST['booking_id']);
	   check_admin_referer("eme_admin $booking_id",'eme_admin_nonce');
	   $booking = eme_get_booking($booking_id);
	   $event_ids = array(0=>$booking['event_id']);
   } else {
	   $booking=array();
   }

   $form_html='';
   foreach ($event_ids as $event_id) {
      $event = eme_get_event($event_id);
      if (empty($event))
	      continue;
      // we use a fake booking to get an initial price based on current entered data
      if (!empty($booking))
	      $fake_booking = $booking;
      else
	      $fake_booking = eme_fake_booking($event);
      if (isset($event['event_properties']['rsvp_dyndata'])) {
	      $conditions=$event['event_properties']['rsvp_dyndata'];
	      foreach ($conditions as $count=>$condition) {
		      // the next check is mostly to eliminate older conditions that didn't have the field-param
		      if (empty($condition['field'])) continue;
		      // sensible values ...
                      if (empty($condition['grouping']))
                              $grouping=$count;
                      else
                              $grouping=intval($condition['grouping']);

                      if ($condition['field']=="#_GROUPS") {
                              $wp_id=eme_get_wpid_by_post();
                              $entered_val = join(',',eme_esc_html(eme_get_persongroup_names(0,$wp_id)));
                      } else {
			      // indicate "1" to make sure the answers are taken from the POST, and not from the existing member
                              $entered_val = eme_replace_booking_placeholders($condition['field'],$event,$fake_booking,"html","",1);
                      }

		      if ($condition['condition'] == 'eq' && $entered_val == $condition['condval']) {
			      $template=eme_get_template_format($condition['template_id']);
			      $form_html.=eme_translate(eme_replace_generic_placeholders(eme_get_template_format($condition['template_id_header'])));
			      $form_html.=eme_replace_dynamic_rsvp_formfields_placeholders($event,$fake_booking,$template,$grouping);
			      $form_html.=eme_translate(eme_replace_generic_placeholders(eme_get_template_format($condition['template_id_footer'])));
		      }
		      if ($condition['condition'] == 'ne' && $entered_val != $condition['condval']) {
			      $template=eme_get_template_format($condition['template_id']);
			      $form_html.=eme_translate(eme_replace_generic_placeholders(eme_get_template_format($condition['template_id_header'])));
			      $form_html.=eme_replace_dynamic_rsvp_formfields_placeholders($event,$fake_booking,$template,$grouping);
			      $form_html.=eme_translate(eme_replace_generic_placeholders(eme_get_template_format($condition['template_id_footer'])));
		      }
		      if ($condition['condition'] == 'contains' && strpos($entered_val,$condition['condval'])!==false) {
			      $template=eme_get_template_format($condition['template_id']);
			      $form_html.=eme_translate(eme_replace_generic_placeholders(eme_get_template_format($condition['template_id_header'])));
			      $form_html.=eme_replace_dynamic_rsvp_formfields_placeholders($event,$fake_booking,$template,$grouping);
			      $form_html.=eme_translate(eme_replace_generic_placeholders(eme_get_template_format($condition['template_id_footer'])));
		      }
		      if ($condition['condition'] == 'notcontains' && strpos($entered_val,$condition['condval'])===false) {
			      $template=eme_get_template_format($condition['template_id']);
			      $form_html.=eme_translate(eme_replace_generic_placeholders(eme_get_template_format($condition['template_id_header'])));
			      $form_html.=eme_replace_dynamic_rsvp_formfields_placeholders($event,$fake_booking,$template,$grouping);
			      $form_html.=eme_translate(eme_replace_generic_placeholders(eme_get_template_format($condition['template_id_footer'])));
		      }
		      if ($condition['condition'] == 'incsv' && (in_array($condition['condval'],explode(",",$entered_val)) || in_array($condition['condval'],explode(", ",$entered_val))) ) {
			      $template=eme_get_template_format($condition['template_id']);
			      $form_html.=eme_translate(eme_replace_generic_placeholders(eme_get_template_format($condition['template_id_header'])));
			      $form_html.=eme_replace_dynamic_rsvp_formfields_placeholders($event,$fake_booking,$template,$grouping);
			      $form_html.=eme_translate(eme_replace_generic_placeholders(eme_get_template_format($condition['template_id_footer'])));
		      }
		      if ($condition['condition'] == 'notincsv' && !(in_array($condition['condval'],explode(",",$entered_val)) || in_array($condition['condval'],explode(", ",$entered_val))) ) {
			      $template=eme_get_template_format($condition['template_id']);
			      $form_html.=eme_translate(eme_replace_generic_placeholders(eme_get_template_format($condition['template_id_header'])));
			      $form_html.=eme_replace_dynamic_rsvp_formfields_placeholders($event,$fake_booking,$template,$grouping);
			      $form_html.=eme_translate(eme_replace_generic_placeholders(eme_get_template_format($condition['template_id_footer'])));
		      }
		      if ($condition['condition'] == 'lt' && $entered_val<$condition['condval']) {
			      $template=eme_get_template_format($condition['template_id']);
			      $form_html.=eme_translate(eme_replace_generic_placeholders(eme_get_template_format($condition['template_id_header'])));
			      if ($condition['repeat']) {
				      $entered_val=intval($entered_val);
				      $condition['condval']=intval($condition['condval']);
				      for ($i=$entered_val;$i<$condition['condval'];$i++) {
					      $form_html.=eme_replace_dynamic_rsvp_formfields_placeholders($event,$fake_booking,$template,$grouping,$i-$entered_val);
				      }
			      } else {
				      $form_html.=eme_replace_dynamic_rsvp_formfields_placeholders($event,$fake_booking,$template,$grouping);
			      }
			      $form_html.=eme_translate(eme_replace_generic_placeholders(eme_get_template_format($condition['template_id_footer'])));
		      }
		      if ($condition['condition'] == 'gt' && $entered_val>$condition['condval']) {
			      $template=eme_get_template_format($condition['template_id']);
			      $form_html.=eme_translate(eme_replace_generic_placeholders(eme_get_template_format($condition['template_id_header'])));
			      if ($condition['repeat']) {
				      $entered_val=intval($entered_val);
				      $condition['condval']=intval($condition['condval']);
				      for ($i=$condition['condval'];$i<$entered_val;$i++) {
					      $form_html.=eme_replace_dynamic_rsvp_formfields_placeholders($event,$fake_booking,$template,$grouping,$i-$condition['condval']);
				      }
			      } else {
				      $form_html.=eme_replace_dynamic_rsvp_formfields_placeholders($event,$fake_booking,$template,$grouping);
			      }
			      $form_html.=eme_translate(eme_replace_generic_placeholders(eme_get_template_format($condition['template_id_footer'])));
		      }
		      if ($condition['condition'] == 'ge' && $entered_val>=$condition['condval']) {
			      $template=eme_get_template_format($condition['template_id']);
			      $form_html.=eme_translate(eme_replace_generic_placeholders(eme_get_template_format($condition['template_id_header'])));
			      if ($condition['repeat']) {
				      $entered_val=intval($entered_val);
				      $condition['condval']=intval($condition['condval']);
				      for ($i=$condition['condval'];$i<=$entered_val;$i++) {
					      $form_html.=eme_replace_dynamic_rsvp_formfields_placeholders($event,$fake_booking,$template,$grouping,$i-$condition['condval']);
				      }
			      } else {
				      $form_html.=eme_replace_dynamic_rsvp_formfields_placeholders($event,$fake_booking,$template,$grouping);
			      }
			      $form_html.=eme_translate(eme_replace_generic_placeholders(eme_get_template_format($condition['template_id_footer'])));
		      }
	      }

      }
   }
   echo json_encode(array('Result'=>do_shortcode($form_html)));
}

function eme_safe_css_attributes($array) {
   $eme_allowed=get_option('eme_allowed_style_attr');
   foreach(preg_split("/((\r?\n)|(\r\n?))/", $eme_allowed) as $line) {
   	$array[] = $line;
   }
   return $array;
}
function eme_safe_css_remove_attributes($array) {
   $eme_allowed=get_option('eme_allowed_style_attr');
   foreach(preg_split("/((\r?\n)|(\r\n?))/", $eme_allowed) as $line) {
   	unset($array[$line]);
   }
   return $array;
}

function _eme_kses_single($value,$allow_unfiltered) {
   $value = eme_strip_weird($value);
   // To filter out JS, we should use domdocument, but the problem is that sometimes the 
   // html is intentional incomplete, which would cause issues with domdocument
   // See https://codereview.stackexchange.com/questions/30045/regex-to-remove-inline-javascript-from-string
   if ($allow_unfiltered) {
	   // even for unfiltered: strip out javascript
	   $res = preg_replace('#<\s*script(.*?)>(.*?)<\s*/\s*script\s*>#is', '', wp_unslash($value));
	   # also strip out inline javascript (onalert etc)
	   $res = preg_replace('#\b[^(\?|\&)]on\w+\s*=\s*\S+(?=.*>)#', '', $res);
	   return $res;
   }
   $allowed_html = wp_kses_allowed_html('post');
   // this option is per line
   // one line is first the tag and then the allowed attributes, all seperated by ','
   $eme_allowed = get_option('eme_allowed_html');
   foreach(preg_split("/((\r?\n)|(\r\n?))/", $eme_allowed) as $line) {
      $line = preg_replace('/\s+/', '', $line);
      $info = explode(',',$line);
      // the first element is the tag, the rest the attributes
      $tag=array_shift($info);
      if (!array_key_exists($tag,$allowed_html))
	      $allowed_html[$tag] = array();
      foreach ($info as $attr) {
	      $allowed_html[$tag][$attr] = true;
      }
   }
   add_filter( 'safe_style_css', 'eme_safe_css_attributes' );
   // brute-force remove script tags, even if wp_kses wouldn't do it
   $res = preg_replace('#<\s*script(.*?)>(.*?)<\s*/\s*script\s*>#is', '', wp_kses(wp_unslash($value),$allowed_html));
   # also strip out inline javascript (onalert etc)
   $res = preg_replace('#\bon\w+\s*=\s*\S+(?=.*>)#', '', $res);
   add_filter( 'safe_style_css', 'eme_safe_css_remove_attributes' );
   return $res;
}

function eme_kses($value) {
	$allow_unfiltered=0;
	if (is_array($value)) {
		return array_map('eme_kses', $value);
	} else {
		return _eme_kses_single($value,$allow_unfiltered);
	}
}

function eme_kses_maybe_unfiltered($value) {
	$allow_unfiltered=1;
	// if allow_unfiltered is on: check if the user is actually allowed do it
	if (!current_user_can('unfiltered_html'))
		$allow_unfiltered=0;
	if (is_array($value)) {
		return array_map('eme_kses_maybe_unfiltered', $value);
	} else {
		return _eme_kses_single($value,$allow_unfiltered);
	}
}

// backwards compatible
function eme_strip_js($value) {
   return eme_kses($value);
}

function eme_strip_weird($value) {
   if (is_array($value)) {
	   return array_map('eme_strip_weird', $value);
   } else {
	   # the next preg_replace would remove emoji codes from html, so we don't do this
	   #$value = preg_replace('/[\x00-\x08\x10\x0B\x0C\x0E-\x19\x7F]'.
	   #                      '|[\x00-\x7F][\x80-\xBF]+'.
	   #                      '|([\xC0\xC1]|[\xF0-\xFF])[\x80-\xBF]*'.
	   #                      '|[\xC2-\xDF]((?![\x80-\xBF])|[\x80-\xBF]{2,})'.
	   #                      '|[\xE0-\xEF](([\x80-\xBF](?![\x80-\xBF]))|(?![\x80-\xBF]{2})|[\x80-\xBF]{3,})/S',
	   #                       '?', $value);
	   //reject overly long 3 byte sequences and UTF-16 surrogates and replace with ?
	   $value = preg_replace('/\xE0[\x80-\x9F][\x80-\xBF]'.
		   '|\xED[\xA0-\xBF][\x80-\xBF]/S','?', $value);
	   return $value;
   }
}

function eme_get_editor_settings($tinymce=true,$quicktags=true,$media_buttons=true,$rows=10) {
   if (!$tinymce && !has_action('eme_add_my_quicktags')) 
	add_action('admin_print_footer_scripts', 'eme_add_my_quicktags');

   return array( 'textarea_rows' => $rows, 'tinymce'=>$tinymce, 'quicktags'=>$quicktags, 'media_buttons'=>$media_buttons );
}

function eme_nl2br_save_html($string) {
    if (!$string) return $string;
    // no \n found: do nothing (this also allow this function to be called multiple times on the same string without doing anything on subsequent calls
    if (!strstr($string,"\n"))
	    return $string;
    // avoid looping if no tags in the string.
    if (!preg_match("#<.+>#", $string))
        return nl2br($string);

    // replace other lineendings
    $string = str_replace(array("\r\n", "\r"), "\n", $string);

    // if br is found, replace it by BREAK
    $string = preg_replace("/\n?<br\W*?\/?>\n?/", "BREAK", $string);

    $lines=explode("\n", $string);
    $last_index = count($lines)-1;
    $add_br=1;
    foreach($lines as $i=>$line) {
        if ($i!=$last_index) {
		// no br if certain tags are found
		if (strstr($line,"<script")) {
			$add_br=0;
			continue;
		}
		if (strstr($line,"</script>")) {
			$add_br=1;
			continue;
		}
		if ($add_br) {
			// if the line finishes with a html opening or closing tag or an eme_if (or events_if), we don't add a br-tag
			if (!preg_match("#</?(\w+).*?>\s*$|\[\/?(eme|events)_if.*?\]\s*$#", $line)) {
				# add a br only if the next line doesn't begin with a opening or closing tag or eme_if (or events_if)
				if (!preg_match("#^\s*</?|^\s*\[\/?(eme|events)_if#",$lines[$i+1]))
					$lines[$i] .= '<br />';
			} elseif (preg_match("#</span>\s*$#", $line) && preg_match("#^\s*<span#",$lines[$i+1])) {
				# add a br if one line ends on closing span and the next starts with opening span too
				$lines[$i] .= '<br />';
			}
		}
        }
    }
    // now that we added the needed br-tags, join back together and return the modified string
    $res = join("\n",$lines);
    return str_replace("BREAK","<br />\n",$res);
}

function eme_wp_date_format_php_to_datepicker_js($php_format) {
	return $php_format;
	$SYMBOLS_MATCHING = array(
		// Day
		'd' => 'dd',
		'D' => 'D',
		'j' => 'd',
		'l' => 'DD',
		'N' => '',
		'S' => '',
		'w' => '',
		'z' => 'o',
		// Week
		'W' => '',
		// Month
		'F' => 'MM',
		'm' => 'mm',
		'M' => 'M',
		'n' => 'm',
		't' => '',
		// Year
		'L' => '',
		'o' => '',
		'Y' => 'yyyy',
		'y' => 'yy',
	);
	$fdatepicker_format = "";
	for ($i = 0; $i < strlen($php_format); $i++) {
		$char = $php_format[$i];
		if ($char === '\\') { // PHP date format escaping character
			$i++;
			$fdatepicker_format .= $php_format[$i];
		} else {
			if (isset($SYMBOLS_MATCHING[$char]))
				$fdatepicker_format .= $SYMBOLS_MATCHING[$char];
			else
				$fdatepicker_format .= $char;
		}
	}
	return $fdatepicker_format;
}

function eme_wp_time_format_php_to_datepicker_js( $php_format ) {
	return $php_format;
        $SYMBOLS_MATCHING = array(
                'a' => 'aa', // am/pm
                'A' => 'AA', // AM/PM
                'g' => 'h', // 12-hour format of an hour without leading zeros
                'h' => 'hh', // 12-hour format of an hour with leading zeros
                'G' => 'h', // 24-hour format of an hour without leading zeros
                'H' => 'hh', // 24-hour format of an hour with leading zeros
                'i' => get_option('eme_time_remove_leading_zeros')? 'i':'ii', // Minutes with leading zeros
                's' => '', // seconds with leading zeros
                'e' => '', // Timezone identifier
                'I' => '', // Whether or not the date is in daylight saving time
                'O' => '', // Difference to Greenwich time (GMT) in hours
                'P' => '', // Difference to Greenwich time (GMT) with colon between hours and minutes
                'T' => '', // Timezone abbreviation
                'Z' => '', // Timezone offset in seconds
        );
	$fdatepicker_format = "";
	for ($i = 0; $i < strlen($php_format); $i++) {
		$char = $php_format[$i];
		if ($char === '\\') { // PHP date format escaping character
			$i++;
			$fdatepicker_format .= $php_format[$i];
		} else {
			if (isset($SYMBOLS_MATCHING[$char]))
				$fdatepicker_format .= $SYMBOLS_MATCHING[$char];
			else
				$fdatepicker_format .= $char;
		}
	}
	return $fdatepicker_format;
}

function eme_getValueFromPath($arr, $path) {
    // todo: add checks on $path
    $dest = $arr;
    $finalKey = array_pop($path);
    foreach ($path as $key) {
	if (!isset($dest[$key])) return false;
        $dest = $dest[$key];
    }
    if (!isset($dest[$finalKey])) return false;
    return $dest[$finalKey];
}

function eme_get_wp_image($image_id) {
	$image = get_post($image_id );
	return array(
		'alt' => get_post_meta( $image->ID, '_wp_attachment_image_alt', true ),
		'caption' => $image->post_excerpt,
		'description' => $image->post_content,
		'href' => get_permalink( $image->ID ),
		'src' => $image->guid,
		'title' => $image->post_title
	);
}

function eme_column_exists($table_name, $column_name) {
        global $wpdb;
        foreach ($wpdb->get_col("DESC $table_name", 0) as $column ) {
                if ($column == $column_name) {
			return true;
                }
        }
        return false;
}

function eme_table_exists($table_name) {
	global $wpdb;
	$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
	$db_result = $wpdb->get_var( $query );
	return strtolower( $db_result ) === strtolower( $table_name );
}

function eme_maybe_drop_column($table_name, $column_name) {
        global $wpdb;
	if (eme_column_exists($table_name, $column_name))
		$wpdb->query("ALTER TABLE $table_name DROP COLUMN $column_name;");
        return true;
}

function eme_drop_table($table) {
	global $wpdb;
	$table = $wpdb->prefix.$table;
	$wpdb->query("DROP TABLE IF EXISTS $table");
}

function eme_convert_charset($table,$charset,$collate) {
	global $wpdb;
	$table = $wpdb->prefix.$table;
	$sql = "ALTER TABLE $table CONVERT TO $charset $collate;";
	$wpdb->query($sql);
}

function eme_get_total($multistring) {
   if (eme_is_multi($multistring))
      return array_sum(eme_convert_multi2array($multistring));
   else
      return $multistring;
}

function eme_get_wpid_by_post()  {
	if (!empty($_POST['wp_id'])) {
		return intval($_POST['wp_id']);
	} else {
		return 0;
	}
}

// sanitize_text_field and sanitize_textarea_field strip html tags, be aware
function eme_sanitize_textarea($value) {
	if (is_array($value)) {
		return array_map('eme_sanitize_textarea',$value);
	}
	if (function_exists('sanitize_textarea_field'))
		return sanitize_textarea_field(wp_unslash($value));
	else
		return sanitize_text_field(wp_unslash($value));
}

// sanitize_text_field and sanitize_textarea_field strip html tags, be aware
function eme_sanitize_request($value) {
	if (is_array($value)) {
		return array_map('eme_sanitize_request', $value);
	} else {
		return sanitize_text_field(wp_unslash($value));
	}
}

function eme_sanitize_email($email) {
	// first replace " " by PLUSSIGN
	// then sanitize_email
	// then replace PLUSSIGN by "+" since sanitize_email doesn't allow "+" but it is in fact valid ...
	$email=str_replace(" ","PLUSSIGN",$email);
	$email=sanitize_email($email);
	$email=str_replace("PLUSSIGN","+",$email);
	return $email;
}

function eme_sort_stringlenth($a,$b){
   return strlen($b)-strlen($a);
}

function eme_sanitize_rss( $value ) {
   #$value =  str_replace ( ">", "&gt;", str_replace ( "<", "&lt;", $value ) );
   return "<![CDATA[".$value."]]>";
}

function eme_esc_html($value) {
   if (is_null($value) || $value==="")
	   return $value;
   if (is_array($value)) {
	   return array_map('eme_esc_html', $value);
   } else {
	   return esc_html($value);
   }
}

// unused function
function eme_strip_tags($value) {
   if (is_null($value))
      return $value;
   if (is_array($value)) {
	   return array_map('eme_strip_tags', $value);
   } else {
	   $value = preg_replace("/^\s*$/","",wp_strip_all_tags($value));
	   return eme_sanitize_request(eme_strip_weird($value));
   }
   return $value;
}

function eme_sanitize_filenamechars($filename) {
    $filename=trim($filename);
    return preg_replace("/[^\da-z]/i", "_", $filename);
}

function eme_sanitize_upload_filename($fName,$field_id,$extra_id="") {
    $fName=trim($fName);
    $indexOFF  = strrpos($fName, '.');
    if ($indexOFF) {
	    $nameFile = substr($fName, 0, $indexOFF);
	    $extension = substr($fName, $indexOFF+1);
    } else {
	    $nameFile = $fName;
	    $extension = 'none';
    }
    if (empty($fName))
	    return false;
    $clean    = eme_sanitize_filenamechars($nameFile);
    $clean_ext= eme_sanitize_filenamechars($extension);
    $rand_id=eme_random_id();
    return "$rand_id-$field_id-$extra_id-$clean.$clean_ext";
}

function eme_upload_file_err($code) {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;

            default:
                $message = "Unknown upload error";
                break;
        }
        return $message;
} 

function eme_upload_files($id,$type="bookings") {
	//$supported_mime_types = wp_get_mime_types();
	$errors=array();
	$max_upload_wp=wp_max_upload_size(); 
	$eme_is_admin_request=eme_is_admin_request();
	foreach($_FILES as $key =>$value) {
		if (preg_match('/^FIELD(\d+)_(.*)/', $key, $matches)) {
			$field_id = intval($matches[1]);
			// the extra_id variable contains numbers, this is the case where we upload dynamic fields etc ... where the name is not just FIELD22 but e.g. FIELD22_1
			$extra_id = intval($matches[2]);
		} elseif (preg_match('/^FIELD(\d+)/', $key, $matches)) {
			$field_id = intval($matches[1]);
			$extra_id = "";
		}
		if (!$field_id) continue;

		// if it is a person-field, override the type
		$formfield = eme_get_formfield($field_id);
		if ($formfield['field_purpose']=="people") {
			if ($type=="bookings") {
				$booking=eme_get_booking($id);
				$id=$booking['person_id'];
			} elseif ($type=="members") {
				$member=eme_get_member($id);
				$id=$member['person_id'];
			}
			$type="people";
			$extra_id="";
		}
		// now we know where to store it, so create the dir
		$targetPath=EME_UPLOAD_DIR."/".$type."/".$id;
		if (!is_dir($targetPath)) {
			$mkdir_res=wp_mkdir_p($targetPath);
			if (!$mkdir_res) {
				$errors[] = __("Failure creating upload dir, please check your permissions.","events-made-easy");
				continue;
			}
		}
		if (!is_file($targetPath."/index.html")) {
			touch($targetPath."/index.html");
		}
		if ($eme_is_admin_request && isset($_REQUEST['eme_admin_action'])) {
			if (!empty($formfield['admin_values']))
				$field_values = $formfield['admin_values'];
			else
				$field_values = $formfield['field_values'];
		} else {
			$field_values = $formfield['field_values'];
		}
		if (intval($field_values)>0) {
			$mb=1024*1024*intval($field_values);
			if ($mb>$max_upload_wp) {
				$max_size=$max_upload_wp;
			} elseif ($mb>0) {
				$max_size=$mb;
			}
		} else {
			$max_size=$max_upload_wp;
		}

		// check for upload issues
		$fileName = eme_sanitize_request($_FILES[$key]["name"]);
		$fileNameChanged = eme_sanitize_upload_filename($fileName,$field_id,$extra_id);
		$temp_name = $_FILES[$key]["tmp_name"];
		$file_size = $_FILES[$key]["size"];
		$fileError = $_FILES[$key]["error"];

		// first check if upload is required but nothing uploaded
		if ($fileError == UPLOAD_ERR_NO_FILE || $fileName=='' || $fileNameChanged=='' || $file_size==0) {
			// nothing uploaded, so check if we wanted something
			if ($formfield['field_required'] && !$eme_is_admin_request) {
				$errors[] = __("File upload is required.","events-made-easy");
			} else {
				continue;
			}
		}

		// now check the filename being ok
		if ($fileNameChanged === false) {
			$errors[] = __("Illegal filename.","events-made-easy");
			continue;
		}

		// if already uploaded
		if (file_exists($targetPath . "/" . $fileNameChanged)) {
			$errors[] = $fileName.': '.__("File already exists.","events-made-easy");
			continue;
		}

		// if too large
		if ($file_size > $max_size) {
			$errors[] = $fileName.': '.__("File too large.","events-made-easy");
			continue;
		}

		// if any upload error (the UPLOAD_ERR_NO_FILE code is already handled)
		if ($fileError > 0) {
			$errors[] = $fileName.': '.eme_upload_file_err($fileError);
			continue;
		}
		// the next is a basic test, but bit-only (checking the first bits of a file)
		// so it can be falsified (a file beginning with a certain string of characters can be seen as image/gif)
		// So we'll do multiple checks
		#if (!in_array(mime_content_type($temp_name),$supported_mime_types)) {
		#	$errors[] = $fileName.': '.__("Incorrect file type.","events-made-easy");
		#	continue;
		#}
		// this checks if the filetype detected matches with the extension given
		// based on code from wordpress file upload, but stricter (no upload if anything goes wrong)
		$wp_filetype = wp_check_filetype_and_ext($temp_name,$fileNameChanged);
		$t_ext = empty( $wp_filetype['ext'] ) ? '' : $wp_filetype['ext'];
		$t_type = empty( $wp_filetype['type'] ) ? '' : $wp_filetype['type'];
		$proper_filename = empty( $wp_filetype['proper_filename'] ) ? '' : $wp_filetype['proper_filename'];
		if (! $t_type || !$t_ext || !empty($proper_filename)) {
			#$errors[] = $fileName.': '.__("Incorrect file type, tampering detected.","events-made-easy");
			$errors[] = $fileName.': '.__("Incorrect file type.","events-made-easy");
			continue;
		}

		if (!move_uploaded_file($temp_name, $targetPath . "/" . $fileNameChanged)) {
			$errors[] = $fileName.': '.__("Upload failed.","events-made-easy");
			continue;
		}
	}
	if (!empty($errors)) {
		return '<p>'.__('Errors encountered while uploading files','events-made-easy').'<br />'.join('<br />',eme_esc_html($errors)).'</p>';
	} else {
		return '';
	}
}

function eme_get_uploaded_files($id,$type="bookings") {
	$res = array();
	$dir=EME_UPLOAD_DIR."/".$type."/".$id;
	if (is_dir($dir)) {
	    if ($handle = opendir($dir)) {
		while (($entry = readdir($handle)) !== false) {
			if ($entry != "." && $entry != "..") {
				$info=explode('-',$entry);
				// ignore files not matching the uploaded scheme
				// this already ignores the ticket.pdf, member.pdf and qrcode.gif files
				if (count($info)!=4) {
					continue;
				}
				$line=array();
				$line['id']=$id;
				$line['type']=$type;
				$line['random_id']=$info[0];
				$line['field_id']=$info[1];
				$formfield=eme_get_formfield($info[1]);
				if ($formfield) {
					$line['field_name']=eme_trans_esc_html($formfield['field_name']);
				} else {
					// unknown ...
					$line['field_name']=$entry;
				}

				$line['extra_id']=$info[2];
				// the name of the file given by the person
				$line['name']=$info[3];
				$line['url']= EME_UPLOAD_URL."/$type/$id/$entry";
				$res[]=$line;
			}
		}
		closedir($handle);
	   }
	}
	return $res;
} 

function eme_get_uploaded_file_html($file,$readonly=0,$short=0) {
	global $eme_plugin_url;
	$style_del='eme_del_upload-button';
	$field_name=$file['field_name'];
	// the name of the file given by the person
	$name=$file['name'];
	$url=$file['url'];
	// person/member/... id
	$id=$file['id'];
	// person, member ...
	$type=$file['type'];
	$random_id=$file['random_id'];
	$extra_id=$file['extra_id'];
	$field_id=$file['field_id'];
	if ($readonly) {
		return "$field_name: <a href='$url'>$name</a><br/>";
	} elseif ($short) {
		return "<a href='$url'>$name</a><br/>";
	} else {
		return "<span id='span_$random_id'>$field_name: <a href='$url'>$name</a> <a class='$style_del' href='#' data-id='$id' data-name='$name' data-random_id='$random_id' data-type='$type' data-extra_id='$extra_id' data-field_id='$field_id'><img src=".$eme_plugin_url."images/cross.png></a><br /></span>";
	}
}

function eme_get_uploaded_files_br($id,$type,$title,$readonly=0) {
	if ($id<1) {
		return;
	}
	$files = eme_get_uploaded_files($id,$type);
	$res="";
	if (!empty($files)) {
		if (!empty($title)) {
			$res = "<br/><br/><b>$title</b><br />";
		}
		$res_files=array();
		foreach ($files as $file) {
			$res_files[] = eme_get_uploaded_file_html($file,$readonly);
		}
		$res .= join('<br />',$res_files);
	}
	return $res;
}
function eme_get_uploaded_files_tr($id,$type,$title,$readonly=0) {
	if ($id<1) {
		return;
	}
	$files = eme_get_uploaded_files($id,$type);
	$res="";
	if (!empty($files)) {
		$res.= "<tr><td style='vertical-align:top'>$title</td><td style='vertical-align:top'>";
		$res_files=array();
		foreach ($files as $file) {
			$res_files[] = eme_get_uploaded_file_html($file,$readonly);
		}
		$res.= join('',$res_files);
		$res.= "</td></tr>";
	}
	return $res;
}

function eme_delTree($dir) {
	$files = array_diff(scandir($dir), array('.','..'));
	foreach ($files as $file) {
		(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
	}
	return rmdir($dir);
}
function eme_delete_uploaded_files($id,$type="bookings") {
	$dir=EME_UPLOAD_DIR."/".$type."/".$id;
	if (is_dir($dir)) {
		eme_delTree($dir);
	}
}
function eme_delete_uploaded_file($file,$id,$type="bookings") {
	$dir=EME_UPLOAD_DIR."/".$type."/".$id;
	if (is_dir($dir)) {
		unlink("$dir/$file");
	}
}

// the next 2 functions allow us to parse html-text and return the urls as "name [url]"
function eme_buildlink_callback($matches) {
    // Remove spaces in URL (#1487805)
    $url = str_replace(' ', '', $matches[3]);
    return $matches[5] . ' [' . $url . ']';
}

function eme_replacelinks($text) {
   $linksearch='/<(a) [^>]*href=("|\')([^"\']+)\2([^>]*)>(.*?)<\/a>/i';
   return preg_replace_callback($linksearch, 'eme_buildlink_callback', $text);
}

function eme_is_admin_request() {
        // Get admin URL and referrer.
        $admin_url = strtolower( admin_url() );
        $referrer  = strtolower( wp_get_referer() );

        // Check if this is a admin request. If true, it
        // could also be a AJAX request from the frontend.
        if (is_user_logged_in() && is_admin() ) {
                // Check if the user comes from an admin page.
                if ( 0 === strpos( $referrer, $admin_url ) ) {
                        return true;
                } else {
                        // Check for AJAX requests.
                        if ( function_exists( 'wp_doing_ajax' ) ) {
                                return ! wp_doing_ajax();
                        } else {
                                return ! ( defined( 'DOING_AJAX' ) && DOING_AJAX );
                        }
                }
        } else {
                return false;
        }
}

// a eme_fputcsv function to replace the original fputcsv
// reason: we want to enclose all fields with $enclosure
function eme_fputcsv ($fh, $fields, $delimiter = ';', $enclosure = '"', $mysql_null = false) {
    $delimiter_esc = preg_quote($delimiter, '/');
    $enclosure_esc = preg_quote($enclosure, '/');

    // not an array, then return
    if (!is_array($fields)) return;

    $output = array();
    foreach ($fields as $field) {
        if ($field === null && $mysql_null) {
            $output[] = 'NULL';
            continue;
        }

        $output[] = preg_match("/(?:${delimiter_esc}|${enclosure_esc}|\s|\r|\t|\n)/", $field) ? (
            $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure
        ) : $enclosure . $field . $enclosure;
    }

    fwrite($fh, join($delimiter, $output) . "\n");
}

function eme_nocache_headers() {
	@header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
	@header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
	@header('Cache-Control: post-check=0, pre-check=0', FALSE);
	@header('Pragma: no-cache');
}

function eme_text_split_newlines($text) {
	// returns an array of trimmed lines, based on text input
	$text = str_replace(array("\r\n", "\r"), "\n", $text);
	$lines = explode("\n", $text);
	return $lines;
}

function eme_ajax_record_list($tablename, $cap) {
   global $wpdb;
   $table = $wpdb->prefix.$tablename;
   $jTableResult = array();
   // The toolbar search input
   $q = isset($_REQUEST['q'])?$_REQUEST['q']:"";
   $opt = isset($_REQUEST['opt'])?$_REQUEST['opt']:"";
   $where ='';
   $where_array = array();
   if ($q) {
	for ($i = 0; $i < count($opt); $i++) {
		$fld = esc_sql($opt[$i]);
		$where_array[] = $fld." like '%".esc_sql($q[$i])."%'";
	}
	$where = " WHERE ".implode(" AND ",$where_array);
   }
   if (current_user_can( get_option($cap))) {
      $sql = "SELECT COUNT(*) FROM $table $where";
      $recordCount = $wpdb->get_var($sql);
      if (!empty($_REQUEST["jtSorting"]))
	      $sorting="ORDER BY ".esc_sql($_REQUEST["jtSorting"]);
      else
	      $sorting="";
      if (isset($_REQUEST["jtStartIndex"]) && isset($_REQUEST["jtPageSize"]))
	      $limit=" LIMIT ".intval($_REQUEST["jtStartIndex"]).",".intval($_REQUEST["jtPageSize"]);
      else
	      $limit="";

      $sql="SELECT * FROM $table $where $sorting $limit";
      $rows=$wpdb->get_results($sql,ARRAY_A);
      $jTableResult['Result'] = "OK";
      if (isset($_REQUEST['options_list'])) {
	      $jTableResult['Options'] = $rows;
      } else {
	      $jTableResult['TotalRecordCount'] = $recordCount;
	      $jTableResult['Records'] = $rows;
      }
   } else {
      $jTableResult['Result'] = "Error";
      $jTableResult['Message'] = __('Access denied!','events-made-easy');
   }
   print json_encode($jTableResult);
   wp_die();
}

function eme_ajax_record_delete($tablename,$cap,$postvar) {
   global $wpdb;
   $table = $wpdb->prefix.$tablename;
   $jTableResult = array();

   if (current_user_can(get_option($cap)) && isset($_POST[$postvar])) {
      // check the POST var
      $ids_arr=explode(',',$_POST[$postvar]);
      if (eme_array_integers($ids_arr)) {
         $wpdb->query("DELETE FROM $table WHERE $postvar in ( ".$_POST[$postvar].")");
      }
      $jTableResult['Result'] = "OK";
      $jTableResult['htmlmessage'] = __('Records deleted!','events-made-easy');
   } else {
      $jTableResult['Result'] = "Error";
      $jTableResult['Message'] = __('Access denied!','events-made-easy');
      $jTableResult['htmlmessage'] = __('Access denied!','events-made-easy');
   }
   print json_encode($jTableResult);
   wp_die();
}

function eme_ajax_record_edit($tablename,$cap,$id_column,$record,$record_function='',$update=0) {
   global $wpdb;
   $table = $wpdb->prefix.$tablename;
   $jTableResult = array();
   if (!$record) {
      $jTableResult['Result'] = "Error";
      $jTableResult['Message'] = __("No such record",'events-made-easy');
      print json_encode($jTableResult);
      wp_die();
   }
   $wpdb->show_errors(false);
   if (current_user_can( get_option($cap))) {
      if ($update)
         $wpdb->update($table,$record,array($id_column => $record[$id_column]));
      else
         $wpdb->insert($table,$record);
      if ($wpdb->last_error !== '') {
         $jTableResult['Result'] = "Error";
         if ($update)
            $jTableResult['Message'] = __('Update failed: ','events-made-easy').$wpdb->last_error;
         else
            $jTableResult['Message'] = __('Insert failed: ','events-made-easy').$wpdb->last_error;
      } else {
         $jTableResult['Result'] = "OK";
         if (!$update) {
            $record_id = $wpdb->insert_id;
            if ($record_function)
               $record=$record_function($record_id);
            else
               $record[$id_column]=$record_id;
            $jTableResult['Record'] = eme_esc_html($record);
         }
      }
   } else {
      $jTableResult['Result'] = "Error";
      $jTableResult['Message'] = __('Access denied!','events-made-easy');
   }

   //Return result to jTable
   print json_encode($jTableResult);
   wp_die();
}

function eme_str_split_unicode($str, $l = 0) {
    if ($l > 0) {
        $ret = array();
        $len = mb_strlen($str, "UTF-8");
        for ($i = 0; $i < $len; $i += $l) {
            $ret[] = mb_substr($str, $i, $l, "UTF-8");
        }
        return $ret;
    }
    return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
}

function eme_get_attachment_link($id) {
	if (!empty($id)) {
		if (is_numeric($id)) {
			$_post = get_post( $id );
			if (!empty($_post)) {
				$url = esc_url(wp_get_attachment_url( $_post->ID ));
				$link_text = $_post->post_title;
				if ( '' === trim( $link_text ) ) {
					$link_text = esc_html( pathinfo( get_attached_file( $_post->ID ), PATHINFO_FILENAME ) );
				}
				return "<a target='_blank' href='$url'>$link_text</a>";
			}
		} else {
			// not numeric ? Then it is a path
			$link_text = esc_html( pathinfo( $id , PATHINFO_FILENAME ) );
			#$link_text = var_dump($id); 
			$url = str_replace(EME_UPLOAD_DIR, EME_UPLOAD_URL, $id);
			return "<a target='_blank' href='$url'>$link_text</a>";
		}
	}
	return "";
}

function eme_excerpt($text) {
	// taken from wp_trim_excerpt function
	$text = strip_shortcodes( $text );
	$text = excerpt_remove_blocks( $text );

	/** This filter is documented in wp-includes/post-template.php */
	$text = apply_filters( 'the_content', $text );
	$text = str_replace( ']]>', ']]&gt;', $text );
	return $text;
}

function eme_generate_unique_wp_username($lastname,$firstname) {
	$basic_username=preg_replace('/\s+/', '', $lastname.$firstname);
	$username=sanitize_user($basic_username);
	while (username_exists($username)) {
		$rnd_str = sprintf("%0d", mt_rand(1, 999999));
		$username=sanitize_user($basic_username.'-'.$rnd_str);
	}
	return $username;
}

function eme_create_wp_user($person) {
	$username=eme_generate_unique_wp_username($person['lastname'],$person['firstname']);
	// use register_new_user so the person gets a mail
	// $user_id = register_new_user( $username, $person['email'] ) ;
	$user_pass = wp_generate_password( 12, false );
	$user_id   = wp_create_user( $username, $user_pass, $person['email'] );
	if (!is_wp_error($user_id)) {
		// allow people to add extra info to the new wp user
		// the filter eme_wp_userdata_filter should return an array that is accepted by wp_update_user
		if (has_filter('eme_wp_userdata_filter')) {
			$userdata=apply_filters('eme_wp_userdata_filter',$person);
		} else {
			$userdata = array();
		}
		// these 3 fields need to stay as is
		$userdata['ID']         = $user_id;
		$userdata['last_name']  = $person['lastname'];
		$userdata['first_name'] = $person['firstname'];
		wp_update_user($userdata);
		wp_send_new_user_notifications($user_id);
		if (!empty($person['phone']))
			eme_update_user_phone($user_id,$person['phone']);
		// now link the wp user to the person
		eme_update_person_wp_id($person['person_id'],$user_id);
		return $user_id;
	} else {
		return 0;
	}
}

function eme_format_full_name($firstname,$lastname) {
	$format=get_option('eme_full_name_format');
	if (!strstr($format,'#_LASTNAME'))
		$format.=" #_LASTNAME";
	if (!strstr($format,'#_FIRSTNAME'))
		$format.=" #_FIRSTNAME";
	$patterns = array();
	$patterns[0] = '/#_LASTNAME/';
	$patterns[1] = '/#_FIRSTNAME/';
	$replacements = array();
	$replacements[0] = $lastname;
	$replacements[1] = $firstname;
	$res = preg_replace($patterns, $replacements, $format);
	$res = trim($res);
	// if $res is a string that only contains spaces: return empty string
	if (eme_is_empty_string($res))
		return '';
	else
		return $res;
}

function eme_extra_event_headers($event) {
	global $eme_timezone;

	if ($event['event_status'] != EME_EVENT_STATUS_PUBLIC && !is_user_logged_in())
		return;

	$header='<meta property="og:type" content="article" />
<meta property="og:title" content="#_NAME"/>
<meta property="og:url" content="#_EVENTPAGEURL"/>
<meta property="og:image" content="#_EVENTIMAGEURL"/>
<meta property="og:description" content="#_EXCERPT" />
<meta name="twitter:image" content="#_EVENTIMAGEURL" />
<meta name="twitter:description" content="#_EXCERPT" />
<meta name="twitter:title" content="#_NAME" />
<meta name="twitter:card" content="summary" />
<meta name="description" content="#_EXCERPT" />';
	if (has_filter('eme_extra_event_headers_filter')) $header=apply_filters('eme_extra_event_headers_filter',$header,$event);
	$header.="\n";
	$header.='<script type="application/ld+json">';
	$footer='</script>';
	$content=array();
	$content["@context"]="http://www.schema.org";
	$content["@type"]="Event";
	$content["name"]="#_EVENTNAME";
	$content["url"]="#_EVENTPAGEURL";
	$content["description"]="#_EXCERPT";
	if ($event['event_properties']['all_day']) {
		$content["startDate"]="#_STARTDATE{Y-m-d}";
		$content["endDate"]="#_ENDDATE{Y-m-d}";
	} else {
		$content["startDate"]="#_STARTDATETIME_8601";
		$content["endDate"]="#_ENDDATETIME_8601";
	}
        if (!empty($event['event_image_id']))
		$content["image"]="#_EVENTIMAGEURL";

	// location is a required property, so add it, even if empty
	$loc=array();
	$location=eme_get_location($event['location_id']);
	if (!empty($location) && !empty($location['location_url'])) {
		if ($location['location_properties']['online_only']) {
			$content['eventAttendanceMode']="https://schema.org/OnlineEventAttendanceMode";
			$loc["@type"]="VirtualLocation";
			$loc["url"]="#_LOCATION_EXTERNAL_URL";
		} else {
			$content['eventAttendanceMode']="https://schema.org/MixedEventAttendanceMode";
			$location1=array();
			$location1["@type"]="VirtualLocation";
			$location1["url"]="#_LOCATION_EXTERNAL_URL";
			$location2=array();
			$location2["@type"]="Place";
			$location2["name"]="#_LOCATION";
			$address=array();
			$address["@type"]="PostalAddress";
			$address["streetAddress"]="#_ADDRESS";
			$address["addressLocality"]="#_CITY";
			$address["postalCode"]="#_ZIP";
			$address["addressCountry"]="#_COUNTRY";
			$location2["address"]=$address;
			$loc[]=$location1;
			$loc[]=$location2;
		}
	} else {
		$content['eventAttendanceMode']="https://schema.org/OfflineEventAttendanceMode";
		$loc["@type"]="Place";
		$loc["name"]="#_LOCATION";
		$address=array();
		$address["@type"]="PostalAddress";
		$address["streetAddress"]="#_ADDRESS";
		$address["addressLocality"]="#_CITY";
		$address["postalCode"]="#_ZIP";
		$address["addressCountry"]="#_COUNTRY";
		$loc["address"]=$address;
	}
	$content["location"]=$loc;

	if ($event['event_rsvp']) {
		$offers=array();
		$offers["@type"]="Offer";
		$offers["url"]="#_EVENTPAGEURL";
		if (!eme_is_multi($event['price'])) {
			$price=$event['price'];
		} else {
			$prices=eme_convert_multi2array($event['price']);
			// let's take the lowest price then
			$price=min($prices);
		}
		$offers["price"]=$price;
		$offers["priceCurrency"]=$event['currency'];

		// allow rsvp from rsvp_start_number_days:rsvp_start_number_hours before the event starts/ends (rsvp_start_target)
		if ( (intval($event['event_properties']['rsvp_start_number_days'])>0 || intval($event['event_properties']['rsvp_start_number_hours'])>0)) {
			$event_rsvp_startdatetime = new ExpressiveDate($event['event_start'],$eme_timezone);
			$event_rsvp_enddatetime = new ExpressiveDate($event['event_end'],$eme_timezone);
			if ($event['event_properties']['rsvp_start_target']=='end')
				$event_rsvp_start = $event_rsvp_enddatetime->copy();
			else
				$event_rsvp_start = $event_rsvp_startdatetime->copy();

			$event_rsvp_start->minusDays(intval($event['event_properties']['rsvp_start_number_days']))->minusHours(intval($event['event_properties']['rsvp_start_number_hours']));
			$validfrom=$event_rsvp_start->format("c");
		} elseif (eme_is_empty_datetime($event['creation_date'])) {
			$validfrom="#_STARTDATETIME_8601";
		} else {
			$eme_date_obj = new ExpressiveDate($event['creation_date'],$eme_timezone);
			$validfrom=$eme_date_obj->format("c");
		}
		$offers["validFrom"]=$validfrom;
		$seats_available=eme_are_seats_available($event);
		if ($seats_available)
			$offers["availability"]="https://schema.org/InStock";
		else
			$offers["availability"]="https://schema.org/SoldOut";
		$content["offers"]=$offers;
	}
	// performer is a recommended option, so lets add an empty one
        //$performer=array();
        //$performer["@type"]="Person";
        //$performer["name"]="";
	//$content["performer"]=$performer;
        $organizer=array();
        $organizer["name"]="#_CONTACTNAME";
        $organizer["url"]="#_EVENTPAGEURL";
	$content["organizer"]=$organizer;


	if (has_filter('eme_extra_event_headers_json_filter')) $content=apply_filters('eme_extra_event_headers_json_filter',$content,$event);

	return $header.json_encode($content,JSON_UNESCAPED_SLASHES).$footer;
}

function eme_maybe_serialize($data) {
	if (!is_serialized($data)) return serialize($data);
	else return $data;
}

function eme_prettyprint_assoc($jsonData, $pre="") {
        $pretty = "";
        foreach ($jsonData as $key => $val) {
            $pretty .= $pre . htmlspecialchars(ucfirst($key)) .": ";
            if (strcmp(gettype($val), "array") == 0) {
                $pretty .= "<br />\n";
                $sno = 1;
                foreach ($val as $value) {
                    $pretty .= $pre . "&nbsp;" . $sno++ . ":<br />\n";
                    $pretty .= eme_prettyprint_assoc($value, $pre . "&nbsp;&nbsp;");
                }
            } else {
                $pretty .= htmlspecialchars($val) . "<br />\n";
            }
        }
        return $pretty;
}

function eme_generate_qrcode($url_to_encode,$targetBasePath,$targetBaseUrl,$size) {
	if (!is_dir($targetBasePath)) {
		wp_mkdir_p($targetBasePath);
	}
	if (!is_file($targetBasePath."/index.html")) {
		touch($targetBasePath."/index.html");
	}
	if ($size=="small")
		$real_size=1;
	elseif ($size=="medium")
		$real_size=2;
	elseif ($size=="large")
		$real_size=4;
	elseif ($size=="huge")
		$real_size=8;
	else
		$real_size=intval($size);
	if ($real_size<1 || $real_size>8)
		$real_size=2;

	if ($real_size==2)
		$name="qrcode.gif";
	else
		$name="qrcode_".$real_size.".gif";

	$qr_arr=glob("$targetBasePath/*-$name");
	if (empty($qr_arr)) {
		$rand_id=eme_random_id();
		$target_file=$targetBasePath.'/'.$rand_id.'-'.$name;
		$target_url=$targetBaseUrl.'/'.$rand_id.'-'.$name;
		if (!class_exists('QRCode')) {
			require_once("qrcode.php");
		}
		if ($real_size<=2)
			$qr_error_level=QR_ERROR_CORRECT_LEVEL_M;
		else
			$qr_error_level=QR_ERROR_CORRECT_LEVEL_Q;
		$qr = QRCode::getMinimumQRCode($url_to_encode,$qr_error_level);
		$im = $qr->createImage($real_size);
		imagegif($im,$target_file);
		imagedestroy($im);
	} else {
		$target_file=$targetBasePath.'/'.basename($qr_arr[0]);
		$target_url=$targetBaseUrl.'/'.basename($qr_arr[0]);
	}
	return array($target_file,$target_url);
}

function eme_check_access($post_id) {
   global $eme_timezone;

   $access_allowed = wp_cache_get("eme_access $post_id");
   if ($access_allowed === false) {
	$custom_values = @get_post_custom($post_id);
	if (!is_array($custom_values)) {
		$access_allowed=1;
	} else {
		$eme_drip_counter = !empty( $custom_values['eme_drip_counter'] ) ? intval($custom_values['eme_drip_counter'][0]): 0;
		$eme_memberships = isset( $custom_values['eme_membershipids'] ) ? $custom_values['eme_membershipids'][0]: '';
		if (is_serialized($eme_memberships))
			$page_membershipids=unserialize($eme_memberships);
		else
			$page_membershipids=array();
		if (!empty($page_membershipids)) {
			// check if the memberships still exist
			$existing_membershipids = eme_memberships_exists($page_membershipids);
			if (!empty($existing_membershipids)) {
				// not logged in? then no access since at least one EME membership is required
				$wp_id=get_current_user_id();
				if (!$wp_id) {
					$access_allowed=0;
				} else {
					$person_membershipids=eme_get_active_membershipids_by_wpid($wp_id);
					$res_intersect=array_intersect($person_membershipids, $existing_membershipids);
					if (empty($person_membershipids) || empty($res_intersect)) {
						$access_allowed=0;
					} else {
						if (!$eme_drip_counter) {
							$access_allowed=1;
						} else {
							// if we need to drip content, get the member and check the payment date
							$person_id=eme_get_personid_by_wpid($wp_id);
							$show_content=0;
							$eme_date_obj_now=new ExpressiveDate("now",$eme_timezone);
							foreach ($res_intersect as $membership_id) {
								$member=eme_get_active_member_by_personid_membershipid($person_id,$membership_id);
								if (!empty($member)) {
									$payment_obj=new ExpressiveDate($member['payment_date'],$eme_timezone);
									if ($member['paid'] && $eme_date_obj_now >= $payment_obj->modifyDays($eme_drip_counter)) {
										$show_content=1;
										break;
									}
								}
							}
							if ($show_content)
								$access_allowed=1;
							else
								$access_allowed=0;
						}
					}
				}
			} else {
				$access_allowed=1;
			}
		} else {
			$access_allowed=1;
		}
		wp_cache_set("eme_access $post_id", $access_allowed, '', 60);
	}
   }
   return $access_allowed;
}

function eme_migrate_event_payment_options() {
        global $wpdb;
        $table_name = $wpdb->prefix . EVENTS_TBNAME;

        $payment_options=array('use_paypal','use_2co','use_webmoney','use_fdgg','use_mollie');
        foreach ($payment_options as $payment_option) {
		$sql="SELECT event_id from $table_name WHERE $payment_option=1";
		$ids=$wpdb->get_col($sql);
		if (!empty($ids)) {
			foreach ($ids as $id) {
				$event=eme_get_event($id);
				if (!empty($event)) {
					$event['event_properties'][$payment_option]=1;
					eme_db_update_event($event,$id);
				}
			}
		}
	}
}

function eme_is_empty_string($text) {
	if (is_array($text))
		   $text = array_map('trim', $text);
	else
		   $text = trim($text);
	if ($text == '')
		return 1;
	else
		return 0;
}

function eme_is_empty_date($mydate) {
	if (empty($mydate) || $mydate=="0000-00-00" || $mydate=="NULL")
		return 1;
	else
		return 0;
}

function eme_is_empty_datetime($mydate) {
	if (empty($mydate) || $mydate=="0000-00-00 00:00:00" || $mydate=="NULL")
		return 1;
	else
		return 0;
}

function eme_wysiwyg_textarea($name, $value, $show_wp_editor=0, $show_full=0) {
   if ($show_wp_editor) {
      if ($show_full)
         $eme_editor_settings = eme_get_editor_settings();
      else
         $eme_editor_settings = eme_get_editor_settings(false);
      wp_editor($value,$name,$eme_editor_settings);
   } else { ?>
      <textarea name="<?php echo $name; ?>" id="<?php echo $name; ?>" rows="6" style="width: 95%" ><?php echo eme_esc_html($value);?></textarea>
<?php 
   }
}

function eme_get_answerid($answers,$related_id,$type,$field_id,$grouping=0,$occurence=0) {
	foreach ($answers as $answer) {
		if ($answer['related_id']==$related_id &&
		    $answer['type']==$type &&
		    $answer['field_id']==$field_id &&
		    $answer['eme_grouping']==$grouping &&
		    $answer['occurence']==$occurence)
		    return $answer['answer_id'];
	}
	return 0;
}
function eme_insert_answer($type,$related_id,$field_id,$answer,$grouping_id=0,$occurence=0) {
	global $wpdb;
	$answers_table = $wpdb->prefix.ANSWERS_TBNAME;
	$sql = $wpdb->prepare("INSERT INTO $answers_table (type,related_id,field_id,answer,eme_grouping,occurence) VALUES (%s,%d,%d,%s,%d,%d)",$type,$related_id,$field_id,$answer,$grouping_id,$occurence);
	$wpdb->query($sql);
	return $wpdb->insert_id;
}

function eme_update_answer($answer_id,$value) {
	global $wpdb;
	$answers_table = $wpdb->prefix.ANSWERS_TBNAME;
	$sql = $wpdb->prepare("UPDATE $answers_table SET answer=%s WHERE answer_id=%d",$value,$answer_id);
	$wpdb->query($sql);
}
function eme_delete_answer($answer_id) {
	global $wpdb;
	$answers_table = $wpdb->prefix.ANSWERS_TBNAME;
	$sql = $wpdb->prepare("DELETE FROM $answers_table WHERE answer_id=%d",$answer_id);
	$wpdb->query($sql);
}

?>
