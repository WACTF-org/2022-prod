<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function eme_new_person() {
   $person = array(
   'lastname' => '',
   'firstname' => '',
   'email' => '',
   'related_person_id' => 0,
   'status' => EME_PEOPLE_STATUS_ACTIVE,
   'phone' => '',
   'birthdate' => '',
   'bd_email' => get_option('eme_bd_email'),
   'birthplace' => '',
   'address1' => '',
   'address2' => '',
   'city' => '',
   'zip' => '',
   'state' => '',
   'country' => '',
   'state_code'=>'',
   'country_code'=>'',
   'lang' => eme_detect_lang(),
   'wp_id' => NULL,
   'massmail' => get_option('eme_people_massmail'),
   'newsletter' => get_option('eme_people_newsletter'),
   'gdpr' => 0,
   'properties' => array()
   );
   $person['properties'] = eme_init_person_props($person['properties']);
   return $person;
}

function eme_new_group() {
   $group = array(
   'name' => '',
   'type' => 'static',
   'public' => 0,
   'description' => '',
   'email' => '',
   'search_terms' => array()
   );
   return $group;
}

function eme_init_person_props($props) {
   if (!isset($props['image_id']))
      $props['image_id']=0;
   return $props;
}

function eme_people_page() {
   $message="";

   $current_userid=get_current_user_id();

   if (isset($_POST['eme_admin_action']) && $_POST['eme_admin_action'] == 'import_people' && isset($_FILES['eme_csv']) && current_user_can(get_option('eme_cap_cleanup')) ) {
      // eme_cap_cleanup is used for cleanup, cron and imports (should more be something like 'eme_cap_actions')
	   if (current_user_can( get_option('eme_cap_edit_people'))) {
		   check_admin_referer('eme_admin','eme_admin_nonce');
		   $message = eme_import_csv_people();
	   } else {
		   $message = __('You have no right to update people!','events-made-easy');
	   }
   } elseif (isset($_POST['eme_admin_action']) && $_POST['eme_admin_action'] == "do_addperson") {
	   if (current_user_can( get_option('eme_cap_edit_people'))) {
		   check_admin_referer('eme_admin','eme_admin_nonce');
		   list($add_update_message,$person_id)=eme_add_update_person_from_backend();
		   if ($person_id) {
			   $message = __('Person added','events-made-easy');
			   if (get_option('eme_stay_on_edit_page')) {
				   eme_person_edit_layout($person_id,$message);
				   return;
			   }
		   } else {
			   $message = __('Problem detected while adding person','events-made-easy');
		   }
		   if (!empty($add_update_message))
			   $message .= '<br />'.$add_update_message;
	   } else {
		   $message = __('You have no right to update people!','events-made-easy');
	   }
   } elseif (isset($_POST['eme_admin_action']) && $_POST['eme_admin_action'] == "do_editperson") {
	   $person_id = intval($_POST['person_id']);
	   $wp_id = eme_get_wpid_by_personid($person_id);
	   if (current_user_can( get_option('eme_cap_edit_people')) || (current_user_can( get_option('eme_cap_author_person')) && $wp_id==$current_userid) ) {
		   check_admin_referer('eme_admin','eme_admin_nonce');
		   list($add_update_message,$person_id)=eme_add_update_person_from_backend($person_id);
		   if ($person_id) {
			   $message = __('Person updated','events-made-easy');
		   } else {
			   $message = __('Problem detected while updating person','events-made-easy');
		   }
		   $message .= '<br />'.$add_update_message;
		   if ($person_id && get_option('eme_stay_on_edit_page')) {
			   eme_person_edit_layout($person_id,$message);
			   return;
		   }
	   } else {
		   $message = __('You have no right to update this person!','events-made-easy');
	   }
   } elseif (isset($_POST['eme_admin_action']) && $_POST['eme_admin_action'] == "add_person") {
	   check_admin_referer('eme_admin','eme_admin_nonce');
	   if (current_user_can( get_option('eme_cap_edit_people'))) {
		   eme_person_edit_layout();
		   return;
	   } else {
		   $message = __('You have no right to add people!','events-made-easy');
	   }
   } elseif (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action'] == "edit_person") {
	   $person_id = intval($_GET['person_id']);
	   $wp_id = eme_get_wpid_by_personid($person_id);
	   if (current_user_can( get_option('eme_cap_edit_people')) || (current_user_can( get_option('eme_cap_author_person')) && $wp_id==$current_userid) ) {
		   eme_person_edit_layout($person_id);
		   return;
	   } else {
		   $message = __('You have no right to update this person!','events-made-easy');
	   }
   } elseif (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action'] == "verify_people") {
	   if (current_user_can( get_option('eme_cap_edit_people'))) {
		   eme_person_verify_layout();
		   return;
	   } else {
		   $message = __('You have no right to update people!','events-made-easy');
	   }
   }
   eme_manage_people_layout($message);
}

function eme_groups_page() {
	$message="";
	if (!current_user_can( get_option('eme_cap_edit_people')) && (isset($_POST['eme_admin_action']) || isset($_GET['eme_admin_action'])) ) {
		$message = __('You have no right to manage groups!','events-made-easy');
	} elseif (isset($_POST['eme_admin_action']) && $_POST['eme_admin_action'] == "do_addgroup") {
		check_admin_referer('eme_admin','eme_admin_nonce');
		$group_id=eme_add_update_group();
		if ($group_id) {
			$message = __('Group added','events-made-easy');
			if (get_option('eme_stay_on_edit_page')) {
				eme_group_edit_layout($group_id,$message);
				return;
			}
		} else {
			$message = __('Problem detected while adding group','events-made-easy');
		}
	} elseif (isset($_POST['eme_admin_action']) && $_POST['eme_admin_action'] == "do_editgroup") {
		check_admin_referer('eme_admin','eme_admin_nonce');
		$group_id = intval($_POST['group_id']);
		$res=eme_add_update_group($group_id);
		if ($res) {
			$message = __('Group updated','events-made-easy');
		} else {
			$message = __('Problem detected while updating group','events-made-easy');
		}
		if (get_option('eme_stay_on_edit_page')) {
			eme_group_edit_layout($group_id,$message);
			return;
		}
	} elseif (isset($_POST['eme_admin_action']) && $_POST['eme_admin_action'] == "add_group") {
		check_admin_referer('eme_admin','eme_admin_nonce');
		if (current_user_can( get_option('eme_cap_edit_people'))) {
			eme_group_edit_layout();
			return;
		} else {
			$message = __('You have no right to add groups!','events-made-easy');
		}
	} elseif (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action'] == "edit_group") {
		$group_id = intval($_GET['group_id']);
		if (current_user_can( get_option('eme_cap_edit_people'))) {
			eme_group_edit_layout($group_id);
			return;
		} else {
			$message = __('You have no right to update groups!','events-made-easy');
		}
	}
	eme_manage_groups_layout($message);
}

function eme_person_shortcode($atts) {
	extract(shortcode_atts(array(
		'person_id'  => 0,
		'template_id' => 0
	), $atts));

	$person=array();
	// the GET param prid (person randomid) overrides person_id if present
	if (isset($_GET['prid'])) {
		$random_id=eme_sanitize_request($_GET['prid']);
		$person=eme_get_person_by_randomid($random_id);
	} elseif ($person_id) {
		$person=eme_get_person(intval($person_id));
	} elseif (is_user_logged_in()) {
		$wp_id=get_current_user_id();
		$person=eme_get_person_by_wp_id($wp_id);
	}
	if ($template_id && !empty($person)) {
		$format = eme_get_template_format($template_id);
		$output = eme_replace_people_placeholders($format,$person);
		return $output;
	} else {
		return '';
	}
}

function eme_people_shortcode($atts) {
	extract(shortcode_atts(array(
		'group_id'  => 0,
		'order'  => 'ASC',
		'template_id' => 0,
		'template_id_header' => 0,
		'template_id_footer' => 0
	), $atts));

	if (!empty($group_id)) {
		$persons=eme_get_grouppersons($group_id,$order);
	} else {
		$persons=eme_get_persons('','','',$order);
	}

	$format="";
	$eme_format_header="";
	$eme_format_footer="";
	if ($template_id) {
		$format = eme_get_template_format($template_id);
	}
	if ($template_id_header) {
		$eme_format_header = eme_translate(eme_replace_generic_placeholders(eme_get_template_format($template_id_header)));
	}
	if ($template_id_footer) {
		$eme_format_footer = eme_translate(eme_replace_generic_placeholders(eme_get_template_format($template_id_footer)));
	}
	$output = "";
	if (!empty($persons) && is_array($persons)) {
		foreach ($persons as $person) {
			$output .= eme_replace_people_placeholders($format,$person);
		}
	}
	$output = $eme_format_header . $output . $eme_format_footer;
	return $output;
}

function eme_replace_email_event_placeholders($format, $email, $lastname, $firstname, $event,$lang='') {
	$needle_offset=0;
	preg_match_all('/#(ESC|URL)?@?_?[A-Za-z0-9_]+(\{(?>[^{}]+|(?2))*\})*+/', $format, $placeholders,PREG_OFFSET_CAPTURE);
	foreach($placeholders[0] as $orig_result) {
		$result = $orig_result[0];
		$orig_result_needle = $orig_result[1]-$needle_offset;
		$orig_result_length = strlen($orig_result[0]);
		$replacement='';
		$found = 1;
		if (preg_match('/#_INVITEURL$/', $result, $matches)) {
			if ($event['event_properties']['invite_only'])
				$replacement = eme_invite_url($event,$email,$lastname,$firstname,$lang);
		} else {
			$found = 0;
		}
		if ($found) {
			$format = substr_replace($format, $replacement, $orig_result_needle,$orig_result_length );
			$needle_offset += $orig_result_length-strlen($replacement);
		}
	}
	return $format;

}

function eme_replace_people_placeholders($format, $person, $target="html", $lang='', $do_shortcode=1) {

	$email_target = 0;
	$orig_target = $target;
	if ($target == "htmlmail") {
		$email_target = 1;
		$target = "html";
	}

	if (!$person) return;

	$answers = eme_get_person_answers($person['person_id']);
	$files = eme_get_uploaded_files($person['person_id'],"people");
	if (empty($lang))
		$lang=$person['lang'];

	// now the generic placeholders
	$format = eme_replace_generic_placeholders ( $format, $target );

	$needle_offset=0;
	preg_match_all('/#(ESC|URL)?@?_?[A-Za-z0-9_]+(\{(?>[^{}]+|(?2))*\})*+/', $format, $placeholders,PREG_OFFSET_CAPTURE);
	foreach($placeholders[0] as $orig_result) {
                $result = $orig_result[0];
                $orig_result_needle = $orig_result[1]-$needle_offset;
                $orig_result_length = strlen($orig_result[0]);
		$replacement='';
		$found = 1;
		$need_escape = 0;
		$need_urlencode = 0;

		if (strstr($result,'#ESC')) {
			$result = str_replace("#ESC","#",$result);
			$need_escape=1;
		} elseif (strstr($result,'#URL')) {
			$result = str_replace("#URL","#",$result);
			$need_urlencode=1;
		}

		# support for ATTEND, RESP and PERSON
		$result = preg_replace("/#_ATTEND|#_RESP|#_PERSON/","#_",$result);

		if (preg_match('/#_ID/', $result)) {
			$replacement = intval($person['person_id']);
		} elseif (preg_match('/#_WPID/', $result)) {
			$replacement = intval($person['wp_id']);
		} elseif (preg_match('/#_FULLNAME/', $result)) {
			$replacement = eme_format_full_name($person['firstname'],$person['lastname']);
			if ($target == "html") {
				$replacement = eme_esc_html($replacement);
				$replacement = apply_filters('eme_general', $replacement); 
			} else {
				$replacement = apply_filters('eme_text', $replacement); 
			}
		} elseif (preg_match('/#_(NAME|LASTNAME|FIRSTNAME|ZIP|CITY|ADDRESS1|ADDRESS2|PHONE|BIRTHDATE|BIRTHPLACE)$/', $result)) {
			$field = str_replace("#_","",$result);
			$field = strtolower($field);
			if ($field=="name") $field="lastname";
			$replacement = $person[$field];
			if ($target == "html") {
				$replacement = eme_esc_html($replacement);
				$replacement = apply_filters('eme_general', $replacement); 
			} else {
				$replacement = apply_filters('eme_text', $replacement); 
			}
		} elseif (preg_match('/#_EMAIL$/', $result)) {
			$replacement = $person['email'];
			if ($target == "html") {
				$replacement = eme_email_obfuscate($replacement,$orig_target);
				$replacement = apply_filters('eme_general', $replacement); 
			} else {
				$replacement = apply_filters('eme_text', $replacement); 
			}
		} elseif (preg_match('/#_FIRSTNAME\{(.+)\}/', $result, $matches)) {
			$length=intval($matches[1]);
			$replacement = substr($person['firstname'], 0, $length);
			// add trailing '.'
			$replacement .= (substr($replacement, -1) == '.' ? '' : '.');
			if ($target == "html") {
				$replacement = eme_esc_html($replacement);
				$replacement = apply_filters('eme_general', $replacement); 
			} else {
				$replacement = apply_filters('eme_text', $replacement); 
			}
		} elseif (preg_match('/#_LASTNAME\{(.+)\}/', $result, $matches)) {
			$length=intval($matches[1]);
			$replacement = substr($person['lastname'], 0, $length);
			if ($target == "html") {
				$replacement = eme_esc_html($replacement);
				$replacement = apply_filters('eme_general', $replacement); 
			} else {
				$replacement = apply_filters('eme_text', $replacement); 
			}
		} elseif (preg_match('/#_INITIALS/', $result, $matches)) {
			$fullname = eme_format_full_name($person['firstname'],$person['lastname']);
			preg_match_all('/\b\w/', $fullname, $name_matches);
			$replacement = implode('.', $name_matches[0]);
			// add trailing '.'
			$replacement .= (substr($replacement, -1) == '.' ? '' : '.');
			if ($target == "html") {
				$replacement = eme_esc_html($replacement);
				$replacement = apply_filters('eme_general', $replacement); 
			} else {
				$replacement = apply_filters('eme_text', $replacement); 
			}
		} elseif (preg_match('/#_LASTNAME_INITIALS/', $result, $matches)) {
			preg_match_all('/\b\w/', $person['lastname'], $name_matches);
			$replacement = implode('.', $name_matches[0]);
			// add trailing '.'
			$replacement .= (substr($replacement, -1) == '.' ? '' : '.');
			if ($target == "html") {
				$replacement = eme_esc_html($replacement);
				$replacement = apply_filters('eme_general', $replacement); 
			} else {
				$replacement = apply_filters('eme_text', $replacement); 
			}
		} elseif (preg_match('/#_COUNTRY/', $result)) {
			$replacement = eme_get_country_name($person['country_code'],$lang);
			if ($target == "html") {
				$replacement = eme_esc_html($replacement);
				$replacement = apply_filters('eme_general', $replacement);
			} else {
				$replacement = apply_filters('eme_text', $replacement);
			}
		} elseif (preg_match('/#_STATE/', $result)) {
			$replacement = eme_get_state_name($person['state_code'],$person['country_code'],$lang);
			if ($target == "html") {
				$replacement = eme_esc_html($replacement);
				$replacement = apply_filters('eme_general', $replacement);
			} else {
				$replacement = apply_filters('eme_text', $replacement);
			}
		} elseif (preg_match('/#_GROUPS/', $result)) {
			$replacement = join(',',eme_esc_html(eme_get_persongroup_names($person['person_id'])));
			if ($target == "html") {
				$replacement = eme_esc_html($replacement);
				$replacement = apply_filters('eme_general', $replacement);
			} else {
				$replacement = apply_filters('eme_text', $replacement);
			}
		} elseif (preg_match('/#_BIRTHDAY_EMAIL/', $result)) {
			$replacement = $person['bd_email'] ? __('Yes','events-made-easy') : __('No','events-made-easy');
			if ($target == "html") {
				$replacement = eme_esc_html($replacement);
				$replacement = apply_filters('eme_general', $replacement); 
			} else {
				$replacement = apply_filters('eme_text', $replacement); 
			}
		} elseif (preg_match('/#_MASSMAIL|#_OPT_IN|#_OPT_OUT/', $result)) {
			$replacement = $person['massmail'] ? __('Yes','events-made-easy') : __('No','events-made-easy');
			if ($target == "html") {
				$replacement = eme_esc_html($replacement);
				$replacement = apply_filters('eme_general', $replacement); 
			} else {
				$replacement = apply_filters('eme_text', $replacement); 
			}
		} elseif (preg_match('/#_GDPR/', $result)) {
			$replacement = $person['gdpr'] ? __('Yes','events-made-easy') : __('No','events-made-easy');
			if ($target == "html") {
				$replacement = eme_esc_html($replacement);
				$replacement = apply_filters('eme_general', $replacement); 
			} else {
				$replacement = apply_filters('eme_text', $replacement); 
			}
		} elseif (preg_match('/#_IMAGETITLE$/', $result)) {
			if (!empty($person['properties']['image_id'])) {
				$info = eme_get_wp_image($person['properties']['image_id']);
				$replacement = $info['title'];
				if ($target == "html") {
					$replacement = apply_filters('eme_general', $replacement);
				} elseif ($target == "rss")  {
					$replacement = apply_filters('the_content_rss', $replacement);
				} else {
					$replacement = apply_filters('eme_text', $replacement);
				}
			}
		} elseif (preg_match('/#_IMAGEALT$/', $result)) {
			if (!empty($person['properties']['image_id'])) {
				$info = eme_get_wp_image($person['properties']['image_id']);
				$replacement = $info['alt'];
				if ($target == "html") {
					$replacement = apply_filters('eme_general', $replacement);
				} elseif ($target == "rss")  {
					$replacement = apply_filters('the_content_rss', $replacement);
				} else {
					$replacement = apply_filters('eme_text', $replacement);
				}
			}
		} elseif (preg_match('/#_IMAGECAPTION$/', $result)) {
			if (!empty($person['properties']['image_id'])) {
				$info = eme_get_wp_image($person['properties']['image_id']);
				$replacement = $info['caption'];
				if ($target == "html") {
					$replacement = apply_filters('eme_general', $replacement);
				} elseif ($target == "rss")  {
					$replacement = apply_filters('the_content_rss', $replacement);
				} else {
					$replacement = apply_filters('eme_text', $replacement);
				}
			}
		} elseif (preg_match('/#_IMAGEDESCRIPTION$/', $result)) {
			if (!empty($person['properties']['image_id'])) {
				$info = eme_get_wp_image($person['properties']['image_id']);
				$replacement = $info['description'];
				if ($target == "html") {
					$replacement = apply_filters('eme_general', $replacement);
				} elseif ($target == "rss")  {
					$replacement = apply_filters('the_content_rss', $replacement);
				} else {
					$replacement = apply_filters('eme_text', $replacement);
				}
			}
		} elseif (preg_match('/#_IMAGE$/', $result)) {
			if (!empty($person['properties']['image_id'])) {
				$replacement = wp_get_attachment_image($person['properties']['image_id'], 'full', 0, array('class'=>'eme_person_image') );
				if ($target == "html") {
					$replacement = apply_filters('eme_general', $replacement);
				} elseif ($target == "rss")  {
					$replacement = apply_filters('the_content_rss', $replacement);
				} else {
					$replacement = apply_filters('eme_text', $replacement);
				}
			}
		} elseif (preg_match('/#_IMAGEURL$/', $result)) {
			if (!empty($person['properties']['image_id'])) {
				$replacement = wp_get_attachment_image_url($person['properties']['image_id'],'full');
				if ($target == "html")
					$replacement=esc_url($replacement);
			}
		} elseif (preg_match('/#_IMAGETHUMB$/', $result)) {
			if (!empty($person['properties']['image_id'])) {
				$replacement = wp_get_attachment_image($person['properties']['image_id'], get_option('eme_thumbnail_size'), 0, array('class'=>'eme_person_image') );
				if ($target == "html") {
					$replacement = apply_filters('eme_general', $replacement);
				} elseif ($target == "rss")  {
					$replacement = apply_filters('the_content_rss', $replacement);
				} else {
					$replacement = apply_filters('eme_text', $replacement);
				}
			}
		} elseif (preg_match('/#_IMAGETHUMBURL$/', $result)) {
			if (!empty($person['properties']['image_id'])) {
				$replacement = wp_get_attachment_image_url($person['properties']['image_id'], get_option('eme_thumbnail_size'));
				if ($target == "html")
					$replacement=esc_url($replacement);
			}
		} elseif (preg_match('/#_IMAGETHUMB\{(.+)\}/', $result, $matches)) {
			if (!empty($person['properties']['image_id'])) {
				$replacement = wp_get_attachment_image( $person['properties']['image_id'], $matches[1], 0, array('class'=>'eme_person_image'));
				if ($target == "html") {
					$replacement = apply_filters('eme_general', $replacement);
				} elseif ($target == "rss")  {
					$replacement = apply_filters('the_content_rss', $replacement);
				} else {
					$replacement = apply_filters('eme_text', $replacement);
				}
			}
		} elseif (preg_match('/#_IMAGETHUMBURL\{(.+)\}/', $result, $matches)) {
			if (!empty($person['properties']['image_id'])) {
				$replacement = wp_get_attachment_image_url( $person['properties']['image_id'], $matches[1]);
				if ($target == "html")
					$replacement=esc_url($replacement);
			}
		} elseif (preg_match('/#_INVITEURL\{(.+)\}/', $result, $matches)) {
			$event=eme_get_event($matches[1]);
			if (!empty($event) && $event['event_properties']['invite_only']) {
				$replacement = eme_invite_url($event,$person['email'],$person['lastname'],$person['firstname'],$lang);
				if ($target == "html")
					$replacement=esc_url($replacement);
			}

		} elseif (preg_match('/#_PERSONFIELD\{(.+)\}/', $result, $matches)) {
			$tmp_attkey=$matches[1];
			if (isset($person[$tmp_attkey]) && !is_array($person[$tmp_attkey])) {
				$replacement = $person[$tmp_attkey];
				if ($target == "html") {
					$replacement = eme_trans_esc_html($replacement,$lang);
					$replacement = apply_filters('eme_general', $replacement);
				} elseif ($target == "rss")  {
					$replacement = eme_translate($replacement,$lang);
					$replacement = apply_filters('the_content_rss', $replacement);
				} else {
					$replacement = eme_translate($replacement,$lang);
					$replacement = apply_filters('eme_text', $replacement);
				}
			}

		} elseif (preg_match('/#_PERSONAL_FILES/', $result)) {
			$files = eme_get_uploaded_files($person['person_id'],"people");
			$res_files=array();
			foreach ($files as $file) {
				if ($target == "html") {
					$res_files[] = eme_get_uploaded_file_html($file,0,1);
				} else {
					$res_files[] = $file['name'] . ' ['.$file['url'].']';
				}
			}
			if ($target == "html") {
				$replacement=join('<br />',$res_files);
			} else {
				$replacement=join("\n",$res_files);
			}

		} elseif (preg_match('/#_FIELDNAME\{(.+)\}/', $result, $matches)) {
			$field_key = $matches[1];
			$formfield = eme_get_formfield($field_key);
			if (!empty($formfield)) {
				if ($target == "html") {
					$replacement = eme_trans_esc_html($formfield['field_name'],$lang);
					$replacement = apply_filters('eme_general', $replacement);
				} else {
					$replacement = eme_translate($formfield['field_name'],$lang);
					$replacement = apply_filters('eme_text', $replacement);
				}
			} else {
				$found = 0;
			}
		} elseif (preg_match('/#_FIELD(VALUE)?\{(.+?)\}(\{.+?\})?/', $result, $matches)) {
			$field_key = $matches[2];
			if (isset($matches[3])) {
				// remove { and } (first and last char of second match)
				$sep=substr($matches[3], 1, -1);
			} else {
				$sep='||';
			}
			$formfield = eme_get_formfield($field_key);
			if (!empty($formfield) && $formfield['field_purpose']=='people') {
				$field_id = $formfield['field_id'];
				$field_replace = "";
				foreach ($answers as $answer) {
					if ($answer['field_id'] == $field_id) {
						if ($matches[1] == "VALUE")
							$field_replace=eme_answer2readable($answer['answer'],$formfield,1,$sep,$target);
						else
							$field_replace=eme_answer2readable($answer['answer'],$formfield,0,$sep,$target);
						continue;
					}
				}
				foreach ($files as $file) {
					if ($file['field_id']==$field_id) {
						if ($target == "html") {
							$field_replace .= eme_get_uploaded_file_html($file,0,1)."<br />";
						} else {
							$field_replace .= $file['name'] . ' ['.$file['url'].']'."\n";
						}
					}
				}
				$replacement = eme_translate($field_replace,$lang);
				if ($target == "html") {
					$replacement = apply_filters('eme_general', $replacement);
				} else {
					$replacement = apply_filters('eme_text', $replacement);
				}
			} else {
				// no people custom field? Then leave it alone
				$found=0;
			}
		} elseif (preg_match('/#_NICKNAME$/', $result)) {
			if ($person['wp_id']>0) {
				$user = get_userdata( $person['wp_id']);
				if ($user)
					$replacement=$user->user_nicename;
				if ($target == "html") {
					$replacement = eme_esc_html($replacement);
					$replacement = apply_filters('eme_general', $replacement); 
				} else {
					$replacement = apply_filters('eme_text', $replacement); 
				}
			}
		} elseif (preg_match('/#_DISPNAME$/', $result)) {
			if ($person['wp_id']>0) {
				$user = get_userdata( $person['wp_id']);
				if ($user)
					$replacement=$user->display_name;
				if ($target == "html") {
					$replacement = eme_esc_html($replacement);
					$replacement = apply_filters('eme_general', $replacement); 
				} else {
					$replacement = apply_filters('eme_text', $replacement); 
				}
			}
		} elseif (preg_match('/#_RANDOMID$/', $result)) {
			// if random id is empty, create one
			if (empty($person['random_id'])) {
				$person['random_id']=eme_random_id();
				$person_id=eme_db_update_person($person['person_id'],$person);
			}
			$replacement = $person['random_id'];
			if ($target == "html") {
				$replacement = eme_esc_html($replacement);
				$replacement = apply_filters('eme_general', $replacement); 
			} else {
				$replacement = apply_filters('eme_text', $replacement); 
			}
		} else {
			$found = 0;
		}

		if ($found) {
			if ($need_escape)
				$replacement = eme_esc_html(preg_replace('/\n|\r/','',$replacement));
			if ($need_urlencode)
				$replacement = rawurlencode($replacement);
			$format = substr_replace($format, $replacement, $orig_result_needle,$orig_result_length );
                        $needle_offset += $orig_result_length-strlen($replacement);
		}
	}

	$format = eme_translate($format,$lang);

	// now some html
	if ($target=="html")
		$format=eme_nl2br_save_html($format);

	if ($do_shortcode)
		return do_shortcode($format);
	else
		return $format;
}

function eme_import_csv_people() {
	global $wpdb;
	$answers_table = $wpdb->prefix.ANSWERS_TBNAME;

	//validate whether uploaded file is a csv file
	$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
	if (empty($_FILES['eme_csv']['name']) || !in_array($_FILES['eme_csv']['type'],$csvMimes)) {
		return sprintf(__('No CSV file detected: %s','events-made-easy'),$_FILES['eme_csv']['type']);
	}
	if (!is_uploaded_file($_FILES['eme_csv']['tmp_name'])) {
		return __('Problem detected while uploading the file','events-made-easy');
		return $result;
	}
	$updated=0;
	$inserted=0;
	$errors=0;
	$error_msg='';
	$handle = fopen($_FILES['eme_csv']['tmp_name'], "r");
	if (!$handle) {
		return __('Problem accessing the uploaded the file, maybe some security issue?','events-made-easy');
	}
	// BOM as a string for comparison.
	$bom = "\xef\xbb\xbf";
	// Progress file pointer and get first 3 characters to compare to the BOM string.
	if (fgets($handle, 4) !== $bom) {
		// BOM not found - rewind pointer to start of file.
		rewind($handle);
	}

	if (!eme_is_empty_string($_POST['enclosure'])) {
		$enclosure=eme_sanitize_request($_POST['enclosure']);
		$enclosure=substr($enclosure, 0, 1);
	} else {
		$enclosure='"';
	}
	if (!eme_is_empty_string($_POST['delimiter'])) {
		$delimiter=eme_sanitize_request($_POST['delimiter']);
	} else {
		$delimiter=',';
	}

	// get the first row as keys and lowercase them
	$headers = array_map('strtolower', fgetcsv($handle,0,$delimiter,$enclosure));

	// check required columns
	if (!in_array('lastname',$headers)||!in_array('firstname',$headers)||!in_array('email',$headers)) {
		$result = __("Not all required fields present.",'events-made-easy');
	} else {
		$empty_props=array();
		$empty_props=eme_init_person_props($empty_props);
		// now loop over the rest
		while (($row = fgetcsv($handle,0,$delimiter,$enclosure)) !== FALSE) {
			$line = array_combine($headers, $row);
			// remove columns with empty values
			$line = eme_array_remove_empty_elements($line);
			// we need at least 3 fields present, otherwise nothing will be done
			if (isset($_POST['allow_empty_email']) && $_POST['allow_empty_email']==1 && !isset($line['email'])) {
				$line['email']='';
				$line['massmail']=0;
			}
			// also allow empty firstname
                        if (!isset($line['firstname'])) {
                                $line['firstname']='';
                        }
			if (!empty($line['email']) && !eme_is_email($line['email'])) {
                                $errors++;
				$error_msg.='<br />'.eme_esc_html(sprintf(__('Not imported (field %s not valid): %s','events-made-easy'),'email',implode(',',$row)));
			} elseif (isset($line['lastname']) && isset($line['firstname']) && isset($line['email'])) {
				// also import properties
				foreach ($line as $key=>$value) {
					if (preg_match('/^prop_(.*)$/', $key, $matches)) {
						$prop=$matches[1];
						if (!isset($line['properties'])) {
							$line['properties']=array();
						}
						if (array_key_exists($prop,$empty_props))
							$line['properties'][$prop]=$value;
					}
				}
				// if the person already exists: update him
				$person = eme_get_person_by_name_and_email($line['lastname'],$line['firstname'],$line['email']);
				if (!$person)
					$person=eme_get_person_by_email_only($line['email']);
				$person_id=0;
				if ($person) {
					$person_id=eme_db_update_person($person['person_id'],$line);
					if ($person_id) {
						$updated++;
					} else {
						$errors++;
						$error_msg.='<br />'.eme_esc_html(sprintf(__('Not imported (problem updating the person in the db): %s','events-made-easy'),implode(',',$row)));
					}
				} else {
					$person_id=eme_db_insert_person($line);
					if ($person_id) {
						$inserted++;
					} else {
						$errors++;
						$error_msg.='<br />'.eme_esc_html(sprintf(__('Not imported (problem inserting the person in the db): %s','events-made-easy'),implode(',',$row)));
					}
				}
				if ($person_id) {
					// now handle all the extra info, in the CSV they need to be named like 'answer_XX' (with 'XX' being either the fieldid or the fieldname, e.g. answer_myfieldname)
					foreach ($line as $key=>$value) {
						if (preg_match('/^answer_(.*)$/', $key, $matches)) {
							$grouping=0;
							$field_name = $matches[1];
							$formfield = eme_get_formfield($field_name);
							if (!empty($formfield)) {
								$field_id=$formfield['field_id'];
								$sql = $wpdb->prepare("DELETE FROM $answers_table WHERE related_id = %d and field_id=%d AND type='person'",$person_id,$field_id);
								$wpdb->query($sql);

								$sql = $wpdb->prepare("INSERT INTO $answers_table (related_id,field_id,answer,eme_grouping,type) VALUES (%d,%d,%s,%d,%s)",$person_id,$field_id,$value,$grouping,'person');
								$wpdb->query($sql);
							}
						}
						if (preg_match('/^groups?$/', $key, $matches)) {
							$groups=eme_convert_multi2array($key);
							eme_add_persongroups($person_id,$groups);
						}
					}

				}
			} else {
				$errors++;
				$error_msg.='<br />'.eme_esc_html(sprintf(__('Not imported (not all required fields are present): %s','events-made-easy'),implode(',',$row)));
			}

		}
	}
	fclose($handle);
	$result = sprintf(__('Import finished: %d inserts, %d updates, %d errors','events-made-easy'),$inserted,$updated,$errors);
	if ($errors) $result.='<br />'.$error_msg;
	return $result;
}

function eme_csv_booking_report($event_id) {
	global $eme_timezone;

	$event = eme_get_event($event_id);
	if (empty($event))
		return;
	$is_multiprice = eme_is_multi($event['price']);
	if ($is_multiprice) {
		$price_count=count(eme_convert_multi2array($event['price']));
	} else {
		$price_count=1;
	}
	$current_userid=get_current_user_id();
	if (!(current_user_can( get_option('eme_cap_edit_events')) || current_user_can( get_option('eme_cap_list_events')) ||
		(current_user_can( get_option('eme_cap_author_event')) && ($event['event_author']==$current_userid || $event['event_contactperson_id']==$current_userid)))) {
		echo "No access";
		die;
	}

	$separator = get_option('eme_csv_separator');
	if (eme_is_empty_string($separator))
		$separator = ',';

	//header("Content-type: application/octet-stream");
	header('Content-type: text/csv; charset=UTF-8');
	header('Content-Encoding: UTF-8');
	header("Content-Disposition: attachment; filename=\"export.csv\"");
	eme_nocache_headers();
	echo "\xEF\xBB\xBF"; // UTF-8 BOM, Excell otherwise doesn't show the characters correctly ...
	$bookings =  eme_get_bookings_for($event_id);
	$people_answer_fieldids = eme_get_people_export_fieldids();
	$answer_fieldids = eme_get_answer_fieldids(eme_get_bookingids_for($event_id));
	$out = fopen('php://output', 'w');
	if (has_filter('eme_csv_header_filter')) {
		$line=apply_filters('eme_csv_header_filter',$event);
		eme_fputcsv($out,$line,$separator);
	}
	$line=array();
	$line[]=__('ID', 'events-made-easy');
	$line[]=__('Last name', 'events-made-easy');
	$line[]=__('First name', 'events-made-easy');
	$line[]=__('Address1', 'events-made-easy');
	$line[]=__('Address2', 'events-made-easy');
	$line[]=__('City', 'events-made-easy');
	$line[]=__('Zip', 'events-made-easy');
	$line[]=__('State', 'events-made-easy');
	$line[]=__('Country', 'events-made-easy');
	$line[]=__('E-mail', 'events-made-easy');
	$line[]=__('Phone number', 'events-made-easy');
	$line[]=__('MassMail', 'events-made-easy');
	$line[]=__('Newsletter', 'events-made-easy');
	foreach($people_answer_fieldids as $field_id) {
		$tmp_formfield=eme_get_formfield($field_id);
		if (!empty($tmp_formfield))
			$line[]=$tmp_formfield['field_name'];
	}
	if ($is_multiprice) {
		#$line[]=__('Seats (Multiprice)', 'events-made-easy');
		$multprice_desc_arr=eme_convert_multi2array($event['event_properties']['multiprice_desc']);
		for ($i = 0; $i < $price_count; $i++) {
			if (!empty($multprice_desc_arr[$i]))
				$line[]=sprintf(__('Seats "%s"', 'events-made-easy'),$multprice_desc_arr[$i]);
			else
				$line[]=sprintf(__('Seats category %d', 'events-made-easy'),$i+1);
		}
	} else {
		$line[]=__('Seats', 'events-made-easy');
	}
	$line[]=__('Paid', 'events-made-easy');
	$line[]=__('Received', 'events-made-easy');
	$line[]=__('Remaining', 'events-made-easy');
	$line[]=__('Booking date','events-made-easy');
	$line[]=__('Discount','events-made-easy');
	$line[]=__('Total price','events-made-easy');
	$line[]=__('Unique nbr','events-made-easy');
	$line[]=__('Attendance count','events-made-easy');
	$line[]=__('Comment', 'events-made-easy');
	foreach($answer_fieldids as $field_id) {
		$tmp_formfield=eme_get_formfield($field_id);
		if (!empty($tmp_formfield))
			$line[]=$tmp_formfield['field_name'];
	}
	$line_nbr=1;
	if (has_filter('eme_csv_column_filter'))
		$line=apply_filters('eme_csv_column_filter',$line,$event,$line_nbr);

	eme_fputcsv($out,$line,$separator);
	foreach($bookings as $booking) {
		$localized_booking_datetime = eme_localized_datetime($booking['creation_date'],$eme_timezone,1);
		$person = eme_get_person ($booking['person_id']);
		// if the person no longer exists, use an empty one
		if (!$person) $person = eme_new_person();
		$person_answers = eme_get_person_answers($booking['person_id']);
		$line=array();
		$pending_string="";
		if ($booking['waitinglist']) {
			$pending_string=__('(waiting list)','events-made-easy');
		} elseif (eme_event_needs_approval($event_id) && $booking['status']==EME_RSVP_STATUS_PENDING) {
			$pending_string=__('(pending)','events-made-easy');
		} elseif ($booking['status']==EME_RSVP_STATUS_USERPENDING) {
                        $pending_string=__('(awaiting user confirmation)','events-made-easy');
                }

		$line[]=$booking['booking_id'];
		$line[]=$person['lastname'];
		$line[]=$person['firstname'];
		$line[]=$person['address1'];
		$line[]=$person['address2'];
		$line[]=$person['city'];
		$line[]=$person['zip'];
		$line[]=eme_get_state_name($person['state_code'],$person['country_code']);
		$line[]=eme_get_country_name($person['country_code']);
		$line[]=$person['email'];
		$line[]=$person['phone'];
		$line[]=$person['massmail'] ? __('Yes','events-made-easy'): __('No','events-made-easy');
		$line[]=$person['newsletter'] ? __('Yes','events-made-easy'): __('No','events-made-easy');
		foreach($people_answer_fieldids as $field_id) {
			$found=0;
			foreach ($person_answers as $answer) {
				if ($answer['field_id'] == $field_id) {
					$tmp_formfield=eme_get_formfield($answer['field_id']);
					if (!empty($tmp_formfield))
						$line[]=eme_answer2readable($answer['answer'],$tmp_formfield,1,"||","text",1);
					$found=1;
					break;
				}
			}
			# to make sure the number of columns are correct, we add an empty answer if none was found
			if (!$found)
				$line[]='';
		}
		if ($is_multiprice) {
			// in cases where the event switched to multiprice, but somebody already registered while it was still single price: booking_seats_mp is then empty
			if ($booking['booking_seats_mp'] == "")
				$booking['booking_seats_mp']=$booking['booking_seats'];
			$booking_seats_mp_arr=eme_convert_multi2array($booking['booking_seats_mp']);
			for ($i = 0; $i < $price_count; $i++) {
				if (isset($booking_seats_mp_arr[$i]))
					$line[]=$booking_seats_mp_arr[$i];
				else
					$line[]=0;
			}
		} else {
			$line[]=$booking['booking_seats']." ".$pending_string;
		}
		$line[]=$booking['booking_paid'] ? __('Yes','events-made-easy'): __('No','events-made-easy');
                $line[]=eme_convert_multi2br(eme_localized_price($booking['received'],$event['currency']));
		if (empty($booking['remaining']) && empty($booking['received'])) {
                        $line[]=$line['totalprice'];
                } else {
                        $line[]=eme_localized_price($booking['remaining'],$event['currency']);
                }

		$line[]=$localized_booking_datetime;
		$discount_names=array();
		if ($booking['dgroupid']) {
			$dgroup=eme_get_discountgroup($booking['dgroupid']);
			if ($dgroup && isset($dgroup['name']))
				$discount_names[]=sprintf(__('Discountgroup %s','events-made-easy'),$dgroup['name']);
			else
				$discount_name[]=sprintf(__('Applied discount group %d no longer exists','events-made-easy'), $booking['dgroupid']);
		}
		if (!empty($booking['discountids'])) {
			$discount_ids=explode(',',$booking['discountids']);
			foreach ($discount_ids as $discount_id) {
				$discount=eme_get_discount($discount_id);
				if ($discount && isset($discount['name']))
					$discount_names[]=$discount['name'];
				else
					$discount_names[]=sprintf(__('Applied discount %d no longer exists','events-made-easy'), $discount_id);
			}
		}
		if (!empty($discount_names))
			$discount_name=' ('.join(',',$discount_names).')';
		else
			$discount_name='';
		$line[]=eme_localized_price($booking['discount'],$event['currency'],"text").$discount_name;
		$line[]=eme_localized_price(eme_get_total_booking_price($booking),$event['currency'],"text");
		$line[]=$booking['transfer_nbr_be97'];
		$line[]=$booking['booking_comment'];
		$line[]=intval($booking['attend_count']);
		$answers = eme_get_nodyndata_booking_answers($booking['booking_id']);
		foreach($answer_fieldids as $field_id) {
			$found=0;
			foreach ($answers as $answer) {
				if ($answer['field_id'] == $field_id) {
					$tmp_formfield=eme_get_formfield($answer['field_id']);
					if (!empty($tmp_formfield))
						$line[]=eme_answer2readable($answer['answer'],$tmp_formfield,1,"||","text",1);
					$found=1;
					break;
				}
			}
			# to make sure the number of columns are correct, we add an empty answer if none was found
			if (!$found)
				$line[]='';
		}

		# add dynamic fields to the right
		if (isset($event['event_properties']['rsvp_dyndata'])) {
			$answers = eme_get_dyndata_booking_answers($booking['booking_id']);
			foreach ($answers as $answer) {
				$grouping=$answer['eme_grouping'];
				$occurence=$answer['occurence'];
				$tmp_formfield=eme_get_formfield($answer['field_id']);
				if (!empty($tmp_formfield))
					$line[]="$grouping.$occurence ".$tmp_formfield['field_name'].": ".eme_answer2readable($answer['answer'],$tmp_formfield,1,"||","text",1);
			}
		}

		$line_nbr++;
		if (has_filter('eme_csv_column_filter'))
			$line=apply_filters('eme_csv_column_filter',$line,$event,$line_nbr);
		eme_fputcsv($out,$line,$separator);
	}

	if (has_filter('eme_csv_footer_filter')) {
		$line=apply_filters('eme_csv_footer_filter',$event);
		eme_fputcsv($out,$line,$separator);
	}
	fclose($out);
	die();
}

function eme_printable_booking_report($event_id) {
	global $eme_timezone, $eme_plugin_url;
	$event = eme_get_event($event_id);
	if (empty($event))
		return;
	$current_userid=get_current_user_id();
	if (!(current_user_can( get_option('eme_cap_edit_events')) || current_user_can( get_option('eme_cap_list_events')) ||
		(current_user_can( get_option('eme_cap_author_event')) && ($event['event_author']==$current_userid || $event['event_contactperson_id']==$current_userid)))) {
		echo "No access";
		die;
	}

	$is_multiprice = eme_is_multi($event['price']);
	$is_multiseat = eme_is_multi($event['event_seats']);
	$bookings = eme_get_bookings_for($event_id);
	$answer_fieldids = eme_get_answer_fieldids(eme_get_bookingids_for($event_id));
	$available_seats = eme_get_available_seats($event_id);
	$total_seats = eme_get_total($event['event_seats']);
	$booked_seats = eme_get_booked_seats($event_id);
	$pending_seats = eme_get_pending_seats($event_id);
	if ($is_multiseat) {
		$available_seats_ms=eme_convert_array2multi(eme_get_available_multiseats($event_id));
	}

	$stylesheet = $eme_plugin_url."css/eme.css";

	eme_nocache_headers();
	header("Content-type: text/html; charset=utf-8");
?>
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
      <html>
      <head>
	 <title><?php echo __('Bookings for', 'events-made-easy')." ".eme_trans_esc_html($event['event_name']);?></title>
	 <link rel="stylesheet" href="<?php echo $stylesheet; ?>" type="text/css" media="screen" />
        <?php
	$file_name= get_stylesheet_directory()."/eme.css";
	if (file_exists($file_name))
		echo "<link rel='stylesheet' href='".get_stylesheet_directory_uri()."/eme.css' type='text/css' media='screen' />\n";
	$file_name= get_stylesheet_directory()."/eme_print.css";
	if (file_exists($file_name))
		echo "<link rel='stylesheet' href='".get_stylesheet_directory_uri()."/eme_print.css' type='text/css' media='print' />\n";
        ?>
      </head>
      <body id="eme_printable_body">
	 <div id="eme_printable_container">
	 <h1><?php echo __('Bookings for', 'events-made-easy')." ".eme_trans_esc_html($event['event_name']);?></h1> 
	 <p><?php echo eme_localized_datetime($event['event_start'],$eme_timezone); ?></p>
	 <p><?php if ($event['location_id']) { $location=eme_get_location($event['location_id']); echo eme_replace_locations_placeholders("#_LOCATIONNAME, #_ADDRESS, #_TOWN", $location);} ?></p>
	 <?php if ($event['price']) ?>
	    <p><?php _e ( 'Price: ','events-made-easy'); echo eme_replace_event_placeholders("#_PRICE", $event); ?></p>
	 <h1><?php _e('Bookings data', 'events-made-easy');?></h1>
	 <table id="eme_printable_table">
	    <tr>
	       <th scope='col' class='eme_print_id'><?php _e('ID', 'events-made-easy'); $nbr_columns=1; ?></th>
	       <th scope='col' class='eme_print_name'><?php _e('Last name', 'events-made-easy'); $nbr_columns++; ?></th>
	       <th scope='col' class='eme_print_name'><?php _e('First name', 'events-made-easy'); $nbr_columns++; ?></th>
	       <th scope='col' class='eme_print_email'><?php _e('E-mail', 'events-made-easy'); $nbr_columns++; ?></th>
	       <th scope='col' class='eme_print_phone'><?php _e('Phone number', 'events-made-easy'); $nbr_columns++; ?></th> 
	       <th scope='col' class='eme_print_seats'><?php if ($is_multiprice) _e('Seats (Multiprice)', 'events-made-easy'); else _e('Seats', 'events-made-easy'); $nbr_columns++; ?></th>
	       <th scope='col' class='eme_print_paid'><?php _e('Paid', 'events-made-easy'); $nbr_columns++; ?></th>
	       <th scope='col' class='eme_print_booking_date'><?php _e('Booking date', 'events-made-easy'); $nbr_columns++; ?></th>
	       <th scope='col' class='eme_print_discount'><?php _e('Discount', 'events-made-easy'); $nbr_columns++; ?></th>
	       <th scope='col' class='eme_print_total_price'><?php _e('Total price', 'events-made-easy'); $nbr_columns++; ?></th>
	       <th scope='col' class='eme_print_unique_nbr'><?php _e('Unique nbr', 'events-made-easy'); $nbr_columns++; ?></th>
	       <th scope='col' class='eme_print_comment'><?php _e('Comment', 'events-made-easy'); $nbr_columns++; ?></th> 
               <?php
	          foreach($answer_fieldids as $field_id) {
		     $class="eme_print_formfield".$field_id;
		     $tmp_formfield=eme_get_formfield($field_id);
		     if (!empty($tmp_formfield)) {
			print "<th scope='col' class='$class'>".$tmp_formfield['field_name']."</th>";
			$nbr_columns++;
		     }
	          }
               ?>
	    </tr>
            <?php
	    foreach($bookings as $booking) {
		$localized_booking_datetime = eme_localized_datetime($booking['creation_date'],$eme_timezone);
		$person = eme_get_person ($booking['person_id']);
		// if the person no longer exists, use an empty one
		if (!$person) $person = eme_new_person();
		$pending_string="";
		if ($booking['waitinglist']) {
			$pending_string=__('(waiting list)','events-made-easy');
		} elseif ($event['registration_requires_approval'] && $booking['status']==EME_RSVP_STATUS_PENDING) {
			$pending_string=__('(pending)','events-made-easy');
		} elseif ($booking['status']==EME_RSVP_STATUS_USERPENDING) {
                        $pending_string=__('(awaiting user confirmation)','events-made-easy');
		}
            ?>
	    <tr>
	       <td class='eme_print_id'><?php echo $booking['booking_id']?></td> 
	       <td class='eme_print_name'><?php echo $person['lastname']?></td> 
	       <td class='eme_print_name'><?php echo $person['firstname']?></td> 
	       <td class='eme_print_email'><?php echo $person['email']?></td>
	       <td class='eme_print_phone'><?php echo $person['phone']?></td>
	       <td class='eme_print_seats' class='seats-number'><?php 
		if ($is_multiprice) {
			// in cases where the event switched to multiprice, but somebody already registered while it was still single price: booking_seats_mp is then empty
			if ($booking['booking_seats_mp'] == "")
				$booking['booking_seats_mp']=$booking['booking_seats'];
			echo $booking['booking_seats']." (".$booking['booking_seats_mp'].") ".$pending_string;
		} else {
			echo $booking['booking_seats']." ".$pending_string;
		}
               ?>
	       </td>
	       <td class='eme_print_paid'><?php if ($booking['booking_paid']) _e('Yes','events-made-easy'); else _e('No','events-made-easy'); ?></td>
	       <td class='eme_print_booking_date'><?php echo $localized_booking_datetime; ?></td>
	       <td class='eme_print_discount'><?php
		$discount_name="";
		if ($booking['dgroupid']) {
			$dgroup=eme_get_discountgroup($booking['dgroupid']);
			if ($dgroup && isset($dgroup['name']))
				$discount_name='<br />'.sprintf(__('Discountgroup %s','events-made-easy'),eme_esc_html($dgroup['name']));
			else
				$discount_name='<br />'.sprintf(__('Applied discount group %d no longer exists','events-made-easy'), $booking['dgroupid']);
		}
		if (!empty($booking['discountids'])) {
			$discount_ids=explode(',',$booking['discountids']);
			foreach ($discount_ids as $discount_id) {
				$discount=eme_get_discount($discount_id);
				if ($discount && isset($discount['name']))
					$discount_name.='<br />'.eme_esc_html($discount['name']);
				else
					$discount_name.='<br />'.sprintf(__('Applied discount %d no longer exists','events-made-easy'), $discount_id);
			}
		}
		echo eme_localized_price($booking['discount'],$event['currency']).$discount_name; ?>
	       </td>
	       <td class='eme_print_total_price'><?php echo eme_localized_price(eme_get_total_booking_price($booking),$event['currency']); ?></td>
	       <td class='eme_print_unique_nbr'><?php echo $booking['transfer_nbr_be97']; ?></td>
	       <td class='eme_print_comment'><?=$booking['booking_comment'] ?></td> 
               <?php
		$answers = eme_get_nodyndata_booking_answers($booking['booking_id']);
		foreach($answer_fieldids as $field_id) {
			$found=0;
			foreach ($answers as $answer) {
				if ($answer['field_id'] == $field_id) {
					$class="eme_print_formfield".$answer['field_id'];
					$tmp_formfield=eme_get_formfield($answer['field_id']);
					if (!empty($tmp_formfield))
						print "<td class='$class'>".eme_answer2readable($answer['answer'],$tmp_formfield,1,"<br />","html")."</td>";
					$found=1;
					break;
				}
			}
			# to make sure the number of columns are correct, we add an empty answer if none was found
			if (!$found)
				print "<td class='$class'>&nbsp;</td>";
		}
               ?>
	    </tr>
	    <tr>
	       <td>&nbsp;</td>
	       <td colspan='<?php echo $nbr_columns-1;?>' style='text-align: left;' >
               <?php
		if (isset($event['event_properties']['rsvp_dyndata'])) {
			$answers = eme_get_dyndata_booking_answers($booking['booking_id']);
			foreach ($answers as $answer) {
				$grouping=$answer['eme_grouping'];
				$occurence=$answer['occurence'];
				$class="eme_print_formfield".$answer['field_id'];
				$tmp_formfield=eme_get_formfield($answer['field_id']);
				if (!empty($tmp_formfield))
					print "<span class='$class'>$grouping.$occurence ".eme_esc_html($tmp_formfield['field_name']).": ".eme_answer2readable($answer['answer'],$tmp_formfield,1,"<br />","html")."</span><br />";
			}
		}
               ?>
	       <td>
	    </tr>
	    <?php } ?>
	    <tr id='eme_printable_booked-seats'>
	       <td colspan='<?php echo $nbr_columns-4;?>'>&nbsp;</td>
	       <td class='total-label'><?php _e('Booked', 'events-made-easy')?>:</td>
	       <td colspan='3' class='seats-number'><?php
		print $booked_seats;
		if ($is_multiprice) {
			$booked_seats_mp=eme_convert_array2multi(eme_get_booked_multiseats($event_id));
			print " ($booked_seats_mp)";
		}
                ?>
	    </td>
	    </tr>
            <?php
		if ($event['registration_requires_approval'] && $pending_seats>0) {
            ?>
	    <tr>
	       <td colspan='<?php echo $nbr_columns-4;?>'>&nbsp;</td>
	       <td class='total-label'><?php _e('Approved', 'events-made-easy')?>:</td>
	       <td colspan='3' class='seats-number'>
               <?php
			$approved_seats = eme_get_approved_seats($event_id);
			print $approved_seats;
			if ($is_multiprice) {
				$approved_seats_mp=eme_convert_array2multi(eme_get_approved_multiseats($event_id));
				print " ($approved_seats_mp)";
			}
               ?>
	       </td>
	    </tr>
	    </tr>
	    <tr>
	       <td colspan='<?php echo $nbr_columns-4;?>'>&nbsp;</td>
	       <td class='total-label'><?php _e('Pending', 'events-made-easy')?>:</td>
	       <td colspan='3' class='seats-number'>
               <?php
			print $pending_seats;
			if ($is_multiprice) {
				$pending_seats_mp=eme_convert_array2multi(eme_get_pending_multiseats($event_id));
				print " ($pending_seats_mp)";
			}
               ?>
	       </td>
	    </tr>
            <?php
		}
            ?>
	    <?php if ($total_seats>0) { ?>
	    <tr id='eme_printable_available-seats'>
	       <td colspan='<?php echo $nbr_columns-4;?>'>&nbsp;</td>
	       <td class='total-label'><?php _e('Available', 'events-made-easy')?>:</td>
	       <td colspan='3' class='seats-number'><?php print $available_seats; if ($is_multiseat) print " ($available_seats_ms)"; ?></td>
	    </tr>
	    <?php } ?>

	    <?php if ($event['event_properties']['take_attendance']) {
	              $absent_bookings = eme_get_absent_bookings($event['event_id']);
		      if ($absent_bookings>0) {
            ?>
	    <tr id='eme_printable_absent-bookings'>
	       <td colspan='<?php echo $nbr_columns-4;?>'>&nbsp;</td>
	       <td class='total-label'><?php _e('Absent', 'events-made-easy')?>:</td>
	       <td colspan='3' class='seats-number'><?php print $absent_bookings; ?></td>
	    </tr>
	    <?php     }
	          }
            ?>
	 </table>
	 </div>
      </body>
      </html>
<?php
		die();
} 

function eme_person_verify_layout() {
?>
      <div class="wrap nosubsub">
       <div id="poststuff">
	 <div id="icon-edit" class="icon32">
	    <br />
	 </div>

	 <h1><?php _e('Verify link between people and WP', 'events-made-easy') ?></h1>
<?php
	// the next function returns a array containing wp_ids linked to multiple EME persons
	$wp_ids_arr = eme_find_persons_double_wp();
	if (count($wp_ids_arr)>0) {
		_e('The table below shows the people that are linked to the same Wordpres user', 'events-made-easy');
		print "<br />";
		_e('Please correct these errors: a Wordpress user should be linked to at most one EME person.', 'events-made-easy');
		$wp_ids=join(',',$wp_ids_arr);
		$people = eme_get_people_by_wp_ids($wp_ids);
		$wp_users = eme_get_indexed_users();

		print "<table class='eme_admin_table'>";
		print "<tr>";
		print "<th>".__('ID','events-made-easy')."</th>";
		print "<th>".__('Last name','events-made-easy')."</th>";
		print "<th>".__('First name','events-made-easy')."</th>";
		print "<th>".__('Email','events-made-easy')."</th>";
		print "<th>".__('Linked WP user','events-made-easy')."</th>";
		print "</tr>";
		foreach ($people as $person) {
			print "<tr style='border-collapse: collapse;border: 1px solid black;'>";
			print "<td>".$person['person_id']."</td>";
			$lastname = "<a href='".admin_url("admin.php?page=eme-people&amp;eme_admin_action=edit_person&amp;person_id=".$person['person_id'])."' title='".__('Edit person','events-made-easy')."'>".eme_esc_html($person['lastname'])."</a>";
			$firstname = "<a href='".admin_url("admin.php?page=eme-people&amp;eme_admin_action=edit_person&amp;person_id=".$person['person_id'])."' title='".__('Edit person','events-made-easy')."'>".eme_esc_html($person['firstname'])."</a>";
			$email = "<a href='".admin_url("admin.php?page=eme-people&amp;eme_admin_action=edit_person&amp;person_id=".$person['person_id'])."' title='".__('Edit person','events-made-easy')."'>".eme_esc_html($person['email'])."</a>";
			print "<td>$lastname</td>";
			print "<td>$firstname</td>";
			print "<td>$email</td>";
			if ($person['wp_id'] && isset($wp_users[$person['wp_id']]))
				print "<td>".eme_esc_html($wp_users[$person['wp_id']])."</td>";
			else
				print "<td></td>";
			print "</tr>";
		}
		print "</table>";
	} else {
		_e('No issues found', 'events-made-easy');
	}

	if (get_option('eme_unique_email_per_person')): 
?>
	 <h1><?php _e('Verify unique emails', 'events-made-easy') ?></h1>
<?php
	// the next function returns a row containing person ids linked to multiple emails
	$emails_arr = eme_find_persons_double_email();
	if (count($emails_arr)>0) {
		_e('The table below shows the people that have identical emails while you require a unique email per person', 'events-made-easy');
		print "<br />";
		_e('Please correct these errors: all EME people should have a unique email.', 'events-made-easy');
		$people=array();
		foreach ($emails_arr as $email) {
			$person_ids_arr = eme_get_personids_by_email($email);
			$person_ids = join(',',$person_ids_arr);
			$people = array_merge($people,eme_get_people_by_ids($person_ids));
		}

		print "<table class='eme_admin_table'>";
		print "<tr>";
		print "<th>".__('ID','events-made-easy')."</th>";
		print "<th>".__('Last name','events-made-easy')."</th>";
		print "<th>".__('First name','events-made-easy')."</th>";
		print "<th>".__('Email','events-made-easy')."</th>";
		print "</tr>";
		foreach ($people as $person) {
			print "<tr style='border-collapse: collapse;border: 1px solid black;'>";
			print "<td>".$person['person_id']."</td>";
			$lastname = "<a href='".admin_url("admin.php?page=eme-people&amp;eme_admin_action=edit_person&amp;person_id=".$person['person_id'])."' title='".__('Edit person','events-made-easy')."'>".eme_esc_html($person['lastname'])."</a>";
			$firstname = "<a href='".admin_url("admin.php?page=eme-people&amp;eme_admin_action=edit_person&amp;person_id=".$person['person_id'])."' title='".__('Edit person','events-made-easy')."'>".eme_esc_html($person['firstname'])."</a>";
			$email = "<a href='".admin_url("admin.php?page=eme-people&amp;eme_admin_action=edit_person&amp;person_id=".$person['person_id'])."' title='".__('Edit person','events-made-easy')."'>".eme_esc_html($person['email'])."</a>";
			print "<td>$lastname</td>";
			print "<td>$firstname</td>";
			print "<td>$email</td>";
			print "</tr>";
		}
		print "</table>";
	} else {
		_e('No issues found', 'events-made-easy');
	}

	endif;
?>
   </div>
   </div>
<?php
}

function eme_render_people_searchfields($group=array()) {
        $eme_member_status_array = eme_member_status_array();
        $memberships = eme_get_memberships();
	$groups=eme_get_static_groups();

        $value='';
        if (!empty($group)) {
                $edit_group=1;
                $search_terms=maybe_unserialize($group['search_terms']);
        } else {
                $edit_group=0;
        }
        if ($edit_group) {
                echo "</td></tr><tr><td>".esc_html__('Filter on person','events-made-easy')."</td><td>";
                if (isset($search_terms['search_person']))
                        $value=$search_terms['search_person'];
        }
	echo '<input type="text" value="'.$value.'" class="clearable" name="search_person" id="search_person" placeholder="'. __('Filter on person','events-made-easy').'" size=15 />';

        if ($edit_group) {
                echo "</td></tr><tr><td>".esc_html__('Filter on group','events-made-easy')."</td><td>";
                if (isset($search_terms['search_groups']))
                        $value=$search_terms['search_groups'];
        }
        echo eme_ui_multiselect_key_value($value,'search_groups',$groups,'group_id','name',5,'',0,'eme_select2_people_groups_class');

        if ($edit_group) {
                echo "<tr><td>".esc_html__('Select memberships','events-made-easy')."</td><td>";
                if (isset($search_terms['search_membershipids']))
                        $value=$search_terms['search_membershipids'];
        }
        echo eme_ui_multiselect_key_value($value,'search_membershipids',$memberships,'membership_id','name',5,'',0,'eme_select2_memberships_class');

	if ($edit_group) {
		echo "</td></tr><tr><td>".esc_html__('Select member status','events-made-easy')."</td><td>";
		if (isset($search_terms['search_memberstatus']))
			$value=$search_terms['search_memberstatus'];
	}
        echo eme_ui_multiselect($value,'search_memberstatus',$eme_member_status_array,5,'',0,'eme_select2_memberstatus_class');

        $formfields_searchable=eme_get_searchable_formfields('people');
        if (!empty($formfields_searchable)) {
                if ($edit_group) {
                        echo "</td></tr><tr><td>".esc_html__('Custom field value to search','events-made-easy')."</td><td>";
                        if (isset($search_terms['search_customfields']))
                                $value=$search_terms['search_customfields'];
                }
                echo '<input type="text" value="'.$value.'" class="clearable" name="search_customfields" id="search_customfields" placeholder="'.esc_html__('Custom field value to search','events-made-easy').'" size=20 />';
                if ($edit_group) {
                        echo "</td></tr><tr><td>".esc_html__('Custom field to search','events-made-easy')."</td><td>";
                        if (isset($search_terms['search_customfieldids']))
                                $value=$search_terms['search_customfieldids'];
                }
                echo eme_ui_multiselect_key_value($value,'search_customfieldids',$formfields_searchable,'field_id','field_name',5,'',0,'eme_select2_customfieldids_class');
	}
}

function eme_get_sql_people_searchfields($search_terms,$start=0,$pagesize=0,$sorting='',$count=0,$ids_only=0,$emails_only=0) {
   global $wpdb;
   $people_table = $wpdb->prefix.PEOPLE_TBNAME;
   $usergroups_table = $wpdb->prefix.USERGROUPS_TBNAME;
   $answers_table = $wpdb->prefix.ANSWERS_TBNAME;
   $members_table = $wpdb->prefix.MEMBERS_TBNAME;

   $search_person = isset($search_terms['search_person']) ? esc_sql($search_terms['search_person']) : '';
   $where ='';
   $where_arr = array();

   // if the person is not allowed to manage all people, show only himself
   if (!current_user_can( get_option('eme_cap_list_people'))) {
	   $wp_id=get_current_user_id();
	   $person_id = eme_get_personid_by_wpid($wp_id);
	   if (!$person_id)
		   $person_id=-1;
	   $where_arr[] = "people.person_id = $person_id";
   }

   // lets get the trash info from the url if present
   $status = isset($_GET['trash']) && $_GET['trash']==1 ? 0 : 1;
   $where_arr[] = "people.status=$status";

   if(!empty($search_person)) {
      $where_arr[] = "(people.lastname like '%".$search_person."%' OR people.firstname like '%".$search_person."%' OR people.email like '%".$search_person."%')";
   }
   $usergroup_join="";
   if (!empty($search_terms['search_groups']) && eme_array_integers($search_terms['search_groups'])) {
      $search_groups = join(',',$search_terms['search_groups']);
      $where_arr[] = "ugroups.group_id IN ($search_groups)";
      $usergroup_join="LEFT JOIN $usergroups_table AS ugroups ON people.person_id=ugroups.person_id";
   }
   $member_join="";
   if (!empty($search_terms['search_membershipids']) && eme_array_integers($search_terms['search_membershipids'])) {
      $search_membershipids=join(',',$search_terms['search_membershipids']);
      $where_arr[]="(members.membership_id IN ($search_membershipids))";
      $member_join="INNER JOIN $members_table AS members ON people.person_id=members.person_id";
   }
   // search_status can be 0 too, for pending
   if (!empty($search_terms['search_memberstatus']) && eme_array_integers($search_terms['search_memberstatus'])) {
      $search_memberstatus=join(',',$search_terms['search_memberstatus']);
      $where_arr[]="(members.status IN ($search_memberstatus))";
      $member_join="INNER JOIN $members_table AS members ON people.person_id=members.person_id";
   }

   if ($where_arr)
      $where = "WHERE ".implode(" AND ",$where_arr);

   $formfields_searchable = eme_get_searchable_formfields('people');

   // we need this GROUP_CONCAT so we can sort on those fields too (otherwise the columns FIELD_* don't exist in the returning sql
   $group_concat_sql="";
   $field_ids_arr=array();
   foreach ($formfields_searchable as $formfield) {
           $field_id=$formfield['field_id'];
           $field_ids_arr[]=$field_id;
           $group_concat_sql.="GROUP_CONCAT(CASE WHEN field_id = $field_id THEN answer END) AS 'FIELD_$field_id',";
   }

   if (!empty($formfields_searchable) && isset($search_terms['search_customfields']) && $search_terms['search_customfields']!="") {
	   if (!empty($search_terms['search_customfieldids']) && eme_array_integers($search_terms['search_customfieldids'])) {
                   $field_ids=join(',',$search_terms['search_customfieldids']);
           } else {
                   $field_ids=join(',',$field_ids_arr);
           }
	   $search_customfields=esc_sql($search_terms['search_customfields']);
	   $sql_join="
		   JOIN (SELECT $group_concat_sql related_id FROM $answers_table
			 WHERE answer LIKE '%$search_customfields%' AND related_id>0 AND field_id IN ($field_ids) AND type='person'
			 GROUP BY related_id
			) ans
		   ON people.person_id=ans.related_id";
   } else {
	   $sql_join="
		   LEFT JOIN (SELECT $group_concat_sql related_id FROM $answers_table
			 WHERE related_id>0 AND type='person'
			 GROUP BY related_id
			) ans
		   ON people.person_id=ans.related_id";
   }
   if ($count) {
     $sql = "SELECT COUNT(distinct(people.person_id)) FROM $people_table AS people $usergroup_join $member_join $sql_join $where";
   } elseif ($ids_only) {
     $sql = "SELECT people.person_id FROM $people_table AS people $usergroup_join $member_join $sql_join $where GROUP BY people.person_id";
   } elseif ($emails_only) {
     $sql = "SELECT people.email FROM $people_table AS people $usergroup_join $member_join $sql_join $where GROUP BY people.person_id";
   } else {
     $sql = "SELECT people.* FROM $people_table AS people $usergroup_join $member_join $sql_join $where GROUP BY people.person_id $sorting"; 
     if (!empty($pagesize))
             $sql .= " LIMIT $start,$pagesize";
   }
   return $sql;
}

function eme_manage_people_layout($message="") {
	global $plugin_page, $eme_plugin_url;

	$nonce_field = wp_nonce_field('eme_admin','eme_admin_nonce',false,false);
	$groups=eme_get_static_groups();
	$pdftemplates = eme_get_templates('pdf',1);
	$htmltemplates = eme_get_templates('html',1);
	$memberships = eme_get_memberships();
	$eme_member_status_array = eme_member_status_array();

	if (empty($message))
		$hidden_style="display:none;";
	else
		$hidden_style="";

?>
      <div class="wrap nosubsub">
       <div id="poststuff">
	 <div id="icon-edit" class="icon32">
	    <br />
	 </div>

	 <div id="people-message" class="notice is-dismissible eme-message-admin" style="<?php echo $hidden_style; ?>">
	       <p><?php echo $message; ?></p>
	 </div>

<?php if (current_user_can( get_option('eme_cap_edit_people'))) : ?>
	 <h1><?php _e('Add a new person', 'events-made-easy') ?></h1>
	 <div class="wrap">
	 <form id="people-filter" method="post" action="<?php echo admin_url("admin.php?page=eme-people"); ?>">
	    <?php echo $nonce_field; ?>
	    <input type="hidden" name="eme_admin_action" value="add_person" />
	    <input type="submit" class="button-primary" name="submit" value="<?php _e('Add person', 'events-made-easy');?>" />
	 </form>
	 </div>
<?php endif; ?>

	 <h1><?php _e('Manage people', 'events-made-easy') ?></h1>
	 <?php echo sprintf(__("Click <a href='%s'>here</a> to verify the integrity of EME people",'events-made-easy'),admin_url("admin.php?page=$plugin_page&eme_admin_action=verify_people")); ?><br />

   <?php if (isset($_GET['trash']) && $_GET['trash']==1) { ?> 
      <a href="<?php echo admin_url("admin.php?page=$plugin_page&trash=0"); ?>"><?php _e('Show regular content','events-made-easy');?></a><br />
   <?php } else { ?>
      <a href="<?php echo admin_url("admin.php?page=$plugin_page&trash=1"); ?>"><?php _e('Show trash content','events-made-easy');?></a><br />
      <?php if (current_user_can(get_option('eme_cap_cleanup'))) { ?>
      <span class="eme_import_form_img">
      <?php _e('Click on the icon to show the import form','events-made-easy'); ?>
      <img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="div_import" style="cursor: pointer; vertical-align: middle; ">
      </span>
      <div id='div_import' style='display:none;'>
      <form id='people-import' method='post' enctype='multipart/form-data' action='#'>
      <?php echo $nonce_field; ?>
      <input type="file" name="eme_csv" />
      <?php _e('Delimiter:','events-made-easy'); ?>
      <input type="text" size=1 maxlength=1 name="delimiter" value=',' required='required' />
      <?php _e('Enclosure:','events-made-easy'); ?>
      <input required="required" type="text" size=1 maxlength=1 name="enclosure" value='"' required='required' />
      <input type="hidden" name="eme_admin_action" value="import_people" />
      <?php _e('Allow empty email?','events-made-easy'); echo eme_ui_select_binary('','allow_empty_email'); ?>
      <input type="submit" value="<?php _e ( 'Import','events-made-easy'); ?>" name="doaction" id="doaction" class="button-primary action" />
      <?php _e('If you want, use this to import people info into the database', 'events-made-easy'); ?>
      </form>
      </div>
      <br />
      <?php } ?>
      <br />
   <?php } ?>
   <form id="eme-admin-regsearchform" name="eme-admin-regsearchform" action="#" method="post">
<?php
	eme_render_people_searchfields();
?>
      <button id="PeopleLoadRecordsButton" class="button action eme_admin_button_middle"><?php _e('Filter people','events-made-easy'); ?></button>
      <button id="StoreQueryButton" class="button action eme_admin_button_middle"><?php _e('Store result as dynamic group','events-made-easy'); ?></button>
      <div id="StoreQueryDiv"><?php _e('Enter a name for this dynamic group','events-made-easy'); ?> <input type="text" id="dynamicgroupname" name="dynamicgroupname" class="clearable" size=20 />
	 <button id="StoreQuerySubmitButton" class="button action"><?php _e('Store dynamic group','events-made-easy'); ?></button>
      </div>
<?php
	$formfields_searchable = eme_get_searchable_formfields('people');
	if (!empty($formfields_searchable)) {
?>
      <div id="hint">
	<?php _e('Hint: when searching for custom field values, you can optionally limit which custom fields you want to search in the "Custom fields to filter on" select-box shown.','events-made-easy'); ?><br />
	<?php _e('If you can\'t see your custom field in the "Custom fields to filter on" select-box, make sure you marked it as "searchable" in the field definition.','events-made-easy'); ?>
      </div>
<?php
	}
?>
   </form>

   <form id='people-form' action="#" method="post">
   <?php echo $nonce_field; ?>
   <select id="eme_admin_action" name="eme_admin_action">
   <option value="" selected="selected"><?php _e ( 'Bulk Actions' , 'events-made-easy'); ?></option>
   <?php if (isset($_GET['trash']) && $_GET['trash']==1) { ?> 
   <option value="untrashPeople"><?php _e ( 'Restore selected persons','events-made-easy'); ?></option>
   <option value="deletePeople"><?php _e ( 'Permanently delete selected persons','events-made-easy'); ?></option>
   <?php } else { ?>
   <option value="sendMails"><?php _e ( 'Send generic email to selected persons','events-made-easy'); ?></option>
   <option value="addToGroup"><?php _e ( 'Add to group','events-made-easy'); ?></option>
   <option value="removeFromGroup"><?php _e ( 'Remove from group','events-made-easy'); ?></option>
   <option value="gdprApprovePeople"><?php _e ( 'Set GDPR approval to yes','events-made-easy'); ?></option>
   <option value="gdprUnapprovePeople"><?php _e ( 'Set GDPR approval to no','events-made-easy'); ?></option>
   <option value="massmailPeople"><?php _e ( 'Set Massmail to yes','events-made-easy'); ?></option>
   <option value="noMassmailPeople"><?php _e ( 'Set Massmail to no','events-made-easy'); ?></option>
   <option value="bdemailPeople"><?php _e ( 'Set Birthday email to yes','events-made-easy'); ?></option>
   <option value="noBdemailPeople"><?php _e ( 'Set Birthday email to no','events-made-easy'); ?></option>
<?php if (current_user_can( get_option('eme_cap_edit_people'))) : ?>
   <option value="trashPeople"><?php _e ( 'Delete selected persons (move to trash)','events-made-easy'); ?></option>
   <option value="gdprPeople"><?php _e ( 'Remove personal data (and move to trash bin)','events-made-easy'); ?></option>
<?php endif; ?>
   <option value="changeLanguage"><?php _e ( 'Change language of selected persons','events-made-easy'); ?></option>
   <option value="pdf"><?php _e ( 'PDF output','events-made-easy'); ?></option>
   <option value="html"><?php _e ( 'HTML output','events-made-easy'); ?></option>
   <?php } ?>
   </select>
   <span id="span_language" class="eme-hidden">
   <?php _e('Change language to: ','events-made-easy'); ?>
   <input type='text' id='language' name='language'>
   </span>
   <span id="span_transferto" class="eme-hidden">
   <?php _e('Transfer associated bookings to (leave empty for moving bookings for future events to trash too):','events-made-easy'); ?>
   <input type='hidden' id='transferto_id' name='transferto_id'>
   <input type='text' id='chooseperson' name='chooseperson' placeholder="<?php _e('Start typing a name','events-made-easy'); ?>">
   </span>
   <span id="span_addtogroup" class="eme-hidden">
   <?php echo eme_ui_select_key_value('',"addtogroup",$groups,'group_id','name',__('Select a group','events-made-easy'),1);?>
   </span>
   <span id="span_removefromgroup" class="eme-hidden">
   <?php echo eme_ui_select_key_value('',"removefromgroup",$groups,'group_id','name',__('Select a group','events-made-easy'),1);?>
   </span>
   <span id="span_pdftemplate" class="eme-hidden">
   <?php echo eme_ui_select_key_value('',"pdf_template_header",$pdftemplates,'id','name',__('Select an optional header template','events-made-easy'),1);?>
   <?php echo eme_ui_select_key_value('',"pdf_template",$pdftemplates,'id','name',__('Please select a template','events-made-easy'),1);?>
   <?php echo eme_ui_select_key_value('',"pdf_template_footer",$pdftemplates,'id','name',__('Select an optional footer template','events-made-easy'),1);?>
   </span>
   <span id="span_htmltemplate" class="eme-hidden">
   <?php echo eme_ui_select_key_value('',"html_template_header",$htmltemplates,'id','name',__('Select an optional header template','events-made-easy'),1);?>
   <?php echo eme_ui_select_key_value('',"html_template",$htmltemplates,'id','name',__('Please select a template','events-made-easy'),1);?>
   <?php echo eme_ui_select_key_value('',"html_template_footer",$htmltemplates,'id','name',__('Select an optional footer template','events-made-easy'),1);?>
   </span>
   <button id="PeopleActionsButton" class="button-secondary action"><?php _e ( 'Apply' , 'events-made-easy'); ?></button>
   <span class="rightclickhint">
      <?php _e('Hint: rightclick on the column headers to show/hide columns','events-made-easy'); ?>
   </span>
<?php
	$formfields=eme_get_formfields('','people');
	$extrafields_arr=array();
	$extrafieldnames_arr=array();
	$extrafieldsearchable_arr=array();
	foreach ($formfields as $formfield) {
		$extrafields_arr[]=$formfield['field_id'];
		$extrafieldnames_arr[]=eme_trans_esc_html($formfield['field_name']);
		$extrafieldsearchable_arr[]=$formfield['searchable'];
	}
	$extrafields=join(',',$extrafields_arr);
	$extrafieldnames=join(',',$extrafieldnames_arr);
	$extrafieldsearchable=join(',',$extrafieldsearchable_arr);
?>
   </form>
   <div id="PeopleTableContainer" data-extrafields='<?php echo $extrafields;?>' data-extrafieldnames='<?php echo $extrafieldnames;?>' data-extrafieldsearchable='<?php echo $extrafieldsearchable;?>'></div>
   </div>
   </div>
<?php
}

function eme_person_edit_layout($person_id=0, $message = "") {
	global $plugin_page, $eme_wp_date_format;

	// if only 1 country, set it as default
	$countries_alpha2=eme_get_countries_alpha2();
	if (count($countries_alpha2)==1)
		$person['country_code']=$countries_alpha2[0];

	if (!$person_id) {
		$action="add";
		$persongroup_ids=array();
		$person=eme_new_person();
	} else {
		$action="edit";
		$person=eme_get_person($person_id);
		$persongroup_ids=eme_get_persongroup_ids($person_id);
	}
	if (!empty($person['country_code'])) {
		$country_code=$person['country_code'];
		$country_arr=array($country_code=>eme_get_country_name($country_code));
	} else {
		$country_arr=array();
	}
	if (!empty($person['state_code']) && !empty($person['country_code'])) {
		$country_code=$person['country_code'];
		$state_code=$person['state_code'];
		$state_arr=array($state_code=>eme_get_state_name($state_code,$country_code));
	} else {
		$state_arr=array();
	}
	if (!empty($person['related_person_id'])) {
		$related_person=eme_get_person($person['related_person_id']);
	} else {
		$related_person=null;
	}
	if (!empty($related_person)) {
		$related_person_name=eme_format_full_name($related_person['firstname'],$related_person['lastname']);
		$related_person_class="readonly='readonly' class='clearable x'";
	} else {
		$related_person_name='';
		$related_person_class='';
	}
	if ($person['status']==EME_PEOPLE_STATUS_TRASH) {
		$readonly=1;
	} else {
		$readonly=0;
	}
	if ($person['wp_id']) {
		$wp_readonly="readonly='readonly'";
	} else {
		$wp_readonly="";
	}

	$nonce_field = wp_nonce_field('eme_admin','eme_admin_nonce',false,false);
	$groups=eme_get_static_groups();
?>
   <div class="wrap">
      <div id="poststuff">
	 <div id="icon-edit" class="icon32">
	    <br />
	 </div>

	 <h1><?php 
		if ($action=="add") {
	 		_e('Add person', 'events-made-easy');
		} else {
			if ($readonly) {
				_e('View person in trash (read-only)', 'events-made-easy');
			} else {
				_e('Edit person', 'events-made-easy');
			}
		}
	?></h1>

	 <?php if ($message != "") { ?>
	    <div id="message" class="updated notice notice-success is-dismissible">
	       <p><?php  echo $message ?></p>
	    </div>
	 <?php } ?>
	 <div id="ajax-response"></div>
	 <?php if (!$readonly) { ?>
	 <form name="editperson" id="editperson" method="post" autocomplete="off" action="<?php echo admin_url("admin.php?page=$plugin_page"); ?>" class="validate" enctype='multipart/form-data'>
	    <?php echo $nonce_field; ?>
	    <?php if ($action == "add") { ?>
	       <input type="hidden" name="eme_admin_action" value="do_addperson" />
	    <?php } else { ?>
	       <input type="hidden" name="eme_admin_action" value="do_editperson" />
	       <input type="hidden" name="person_id" value="<?php echo $person['person_id'] ?>" />
	    <?php } ?>
	 <?php } else { ?>
	 <fieldset disabled="disabled">
	 <?php } ?>

	 <div id="div_person" class="postbox">
	    <div class="inside">
	    <table>
	    <tr>
	    <td style="vertical-align:top"><label for="lastname"><?php _e('Last name', 'events-made-easy') ?></label></td>
	    <td><input id="lastname" name="lastname" type="text" value="<?php echo eme_esc_html($person['lastname']); ?>" size="40" <?php echo $wp_readonly; ?> /><br />
                <?php if (!empty($wp_readonly)) _e('Since this person is linked to a WP user, this field is read-only', 'events-made-easy'); ?>
            </td>
	    </tr>
	    <tr>
	    <td style="vertical-align:top"><label for="firstname"><?php _e('First name', 'events-made-easy') ?></label></td>
	    <td><input id="firstname" name="firstname" type="text" value="<?php echo eme_esc_html($person['firstname']); ?>" size="40" <?php echo $wp_readonly; ?> /><br />
                <?php if (!empty($wp_readonly)) _e('Since this person is linked to a WP user, this field is read-only', 'events-made-easy'); ?>
            </td>
	    </tr>
	    <tr>
	    <td style="vertical-align:top"><label for="email"><?php _e('Email', 'events-made-easy') ?></label></td>
	    <td><input id="email" name="email" type="email" value="<?php echo eme_esc_html($person['email']); ?>" size="40" <?php echo $wp_readonly; ?> /><br />
                <?php if (!empty($wp_readonly)) _e('Since this person is linked to a WP user, this field is read-only', 'events-made-easy'); ?>
            </td>
	    </tr>
	    <tr>
	    <td style="vertical-align:top"><label for="chooserelatedperson"><?php _e('Related family member', 'events-made-easy') ?></label></td>
	    <td> <input type="hidden" name="related_person_id" id="related_person_id" value="<?php echo intval($person['related_person_id']); ?>" />
	    <input type='text' id='chooserelatedperson' name='chooserelatedperson' placeholder="<?php _e('Start typing a name','events-made-easy'); ?>" value="<?php echo $related_person_name; ?>" <?php echo $related_person_class; ?>>
            </td>
	    </tr>
	    <tr>
	    <td style="vertical-align:top"><label for="chooserelatedperson"><?php _e('Family members:', 'events-made-easy') ?></label></td>
            <td>
<?php
                  $familymember_person_ids=eme_get_family_person_ids($person['person_id']);
                  if ($action == "edit" && !empty($familymember_person_ids)) {
			  foreach ($familymember_person_ids as $related_person_id) {
				  $related_person=eme_get_person($related_person_id);
				  if ($related_person)
					  print "<a href='".admin_url("admin.php?page=eme-people&amp;eme_admin_action=edit_person&amp;person_id=$related_person_id")."' title='".esc_attr__('Edit person','events-made-easy')."'>".eme_esc_html(eme_format_full_name($related_person['firstname'],$related_person['lastname']))."</a><br />";
			  }
		  }
?>
            </td>
	    </tr>
	    <tr>
	    <td><label for="phone"><?php _e('Phone Number', 'events-made-easy') ?></label></td>
	    <td><input id="phone" name="phone" type="text" value="<?php echo eme_esc_html($person['phone']); ?>" size="40" /></td>
	    </tr>
	    <tr>
	    <td><label for="address1"><?php _e('Address1', 'events-made-easy') ?></label></td>
	    <td><input id="address1" name="address1" type="text" value="<?php echo eme_esc_html($person['address1']); ?>" size="40" /></td>
	    </tr>
	    <tr>
	    <td><label for="address2"><?php _e('Address2', 'events-made-easy') ?></label></td>
	    <td><input id="address2" name="address2" type="text" value="<?php echo eme_esc_html($person['address2']); ?>" size="40" /></td>
	    </tr>
	    <tr>
	    <td><label for="city"><?php _e('City', 'events-made-easy') ?></label></td>
	    <td><input name="city" id="city" type="text" value="<?php echo eme_esc_html($person['city']); ?>" size="40" /></td>
	    </tr>
	    <tr>
	    <td><label for="zip"><?php _e('Zip', 'events-made-easy') ?></label></td>
	    <td><input name="zip" id="zip" type="text" value="<?php echo eme_esc_html($person['zip']); ?>" size="40" /></td>
	    </tr>
	    <tr>
	    <td><label for="state"><?php _e('State', 'events-made-easy') ?></label></td>
	    <td><?php echo eme_ui_select($person['state_code'], 'state_code', $state_arr, '', 0,'eme_select2_state_class'); ?></td>
	    </tr>
	    <tr>
	    <td><label for="country"><?php _e('Country', 'events-made-easy') ?></label></td>
	    <td><?php echo eme_ui_select($person['country_code'], 'country_code', $country_arr, '', 0,'eme_select2_country_class'); ?></td>
	    </tr>
	    <tr>
	    <td><label for="birthdate"><?php _e('Birth date', 'events-made-easy') ?></label></td>
	    <td><input type='hidden' name='birthdate' id='birthdate' value='<?php echo eme_esc_html($person['birthdate']); ?>' />
		<input readonly='readonly' type='text' name='dp_birthdate' id='dp_birthdate' data-date='<?php echo eme_esc_html($person['birthdate']); ?>' data-date-format='<?php echo $eme_wp_date_format; ?>' data-alt-field='#birthdate' class='eme_formfield_fdate' />
	    </tr>
	    <tr>
	    <td><label for="bd_email"><?php _e('Birthday email', 'events-made-easy') ?></label></td>
	    <td><?php 
		  echo eme_ui_select_binary($person['bd_email'],'bd_email');
		  _e("If active, the person will receive a birthday email.",'events-made-easy');
		?>
	    </td>
	    </tr>
	    <tr>
	    <td><label for="birthplace"><?php _e('Birth place', 'events-made-easy') ?></label></td>
	    <td><input id="birthplace" name="birthplace" type="text" value="<?php echo eme_esc_html($person['birthplace']); ?>" size="40" /></td>
	    </tr>
	    <tr>
	    <td><label for="lang"><?php _e('Language', 'events-made-easy') ?></label></td>
	    <td><input id="language" name="language" type="text" value="<?php echo eme_esc_html($person['lang']); ?>" size="40" maxlength="7" /></td>
	    </tr>
	    <tr>
	    <tr>
	    <td><label for="massmail"><?php _e('MassMail', 'events-made-easy') ?></label></td>
	    <td><?php echo eme_ui_select_binary($person['massmail'],'massmail'); ?></td>
	    </tr>
	    <tr>
	    <td><label for="newsletter"><?php _e('Newsletter', 'events-made-easy') ?></label></td>
	    <td><?php echo eme_ui_select_binary($person['newsletter'],'newsletter'); ?></td>
	    </tr>
	    <tr>
	    <td><label for="gdpr"><?php _e('GDPR approval', 'events-made-easy') ?></label></td>
	    <td><?php echo eme_ui_select_binary($person['gdpr'],'gdpr'); ?></td>
	    </tr>
	    <tr>
	    <td><label for="groups"><?php _e('Groups', 'events-made-easy') ?></label></td>
	    <td><?php echo eme_ui_multiselect_key_value($persongroup_ids,'groups',$groups,'group_id','name',5,'',0,'dyngroups eme_select2_width50_class'); ?><br />
		<?php _e("Don't forget that you can define custom fields with purpose 'People' that will allow extra info based on the group the person is in.",'events-made-easy');?>
	    </td>
	    </tr>
<?php if (current_user_can( get_option('eme_cap_edit_people'))) : ?>
	    <tr>
	    <td style="vertical-align:top"><label for="wpid"><?php _e('Linked WP user', 'events-made-easy') ?></label></td>
	    <td><?php $used_wp_ids=eme_get_used_wpids($person['wp_id']);
			$exclude=join(',',$used_wp_ids);
			 wp_dropdown_users(array('name' => 'wp_id', 'show_option_none' => "&nbsp;", 'selected' => $person['wp_id'], 'exclude' => $exclude)); ?><br />
		<?php _e("Linking an EME person with a WP user will not be allowed if there's another EME person matching the WP user's firstname/lastname/email.",'events-made-easy');?><br />
		<?php _e("Linking an EME person with a WP user will change the person firstname/lastname/email to the WP user's firstname/lastname/email and those fields can then only be changed via the WP profile of that person.",'events-made-easy');?>
            </td>
	    </tr>
<?php
	endif;
	
	if ($action == "edit") {
		$files_title=__('Uploaded files', 'events-made-easy');
		print eme_get_uploaded_files_tr($person_id,"people",$files_title);
	}
?>
	    </table>
	    </div>
	    <?php echo eme_person_image_div($person); ?>
	    <div class='inside' id='eme_dynpersondata'></div>
	 </div>
	 <?php if ($readonly) { ?>
	 </fieldset>
	 <?php } else { ?>
	 <p class="submit"><input type="submit" class="button-primary" name="submit" value="<?php if ($action=="add") _e('Add person', 'events-made-easy'); else _e('Update person', 'events-made-easy'); ?>" /></p>
         </form>
	 <?php } ?>
   </div>
<?php
}

function eme_group_edit_layout($group_id=0, $message = "") {
	global $plugin_page,$eme_plugin_url;

	$grouppersons=array();
	$mygroups=array();
	if (!$group_id) {
		$action = "add";
		$group = eme_new_group();
	} else {
		$action="edit";
		$group = eme_get_group($group_id);
		$persons=eme_get_grouppersons($group['group_id']);
		if (!empty($persons) && is_array($persons)) {
			foreach ($persons as $person) {
				// account for possible empty values
				if (empty($person['lastname']))
					$mygroups[$person['person_id']]=$person['email'];
				else
					$mygroups[$person['person_id']]=eme_format_full_name($person['firstname'],$person['lastname']);
				$grouppersons[]=$person['person_id'];
			}
		}
	}
	$nonce_field = wp_nonce_field('eme_admin','eme_admin_nonce',false,false);
?>
   <div class="wrap">
      <div id="poststuff">
	 <div id="icon-edit" class="icon32">
	    <br />
	 </div>

	 <h1><?php if ($action=="add")
	 _e('Add group', 'events-made-easy');
	else
		_e('Edit group', 'events-made-easy');
?></h1>

	 <?php if ($message != "") { ?>
	    <div id="message" class="updated notice notice-success is-dismissible">
	       <p><?php  echo $message ?></p>
	    </div>
	 <?php } ?>
	 <div id="ajax-response"></div>
	 <form name="editgroup" id="editgroup" method="post" autocomplete="off" action="<?php echo admin_url("admin.php?page=$plugin_page"); ?>" class="validate">
	 <?php echo $nonce_field; ?>
	 <?php if ($action == "add") { ?>
	 <input type="hidden" name="eme_admin_action" value="do_addgroup" />
	 <?php } else { ?>
	 <input type="hidden" name="eme_admin_action" value="do_editgroup" />
	 <input type="hidden" name="group_id" value="<?php echo $group['group_id'] ?>" />
	 <?php } ?>

	 <!-- we need titlediv and title for qtranslate as ID -->
	 <div id="titlediv" class="postbox">
	    <div class="inside">
	    <table>
	    <tr>
	    <td><label for="name"><?php _e('Name', 'events-made-easy') ?></label></td>
	    <td><input required='required' id="name" name="name" type="text" value="<?php echo eme_esc_html($group['name']); ?>" size="40" /></td>
	    </tr>
	    <tr>
	    <td><label for="description"><?php _e('Description', 'events-made-easy') ?></label></td>
	    <td><input id="description" name="description" type="text" value="<?php echo eme_esc_html($group['description']); ?>" size="40" /></td>
	    </tr>
	    <tr>
	    <td><label for="email"><?php _e('Group email', 'events-made-easy') ?></label></td>
	    <td><input id="email" name="email" type="text" value="<?php echo eme_esc_html($group['email']); ?>" size="40" /><br />
            <?php _e('If you want to be able to send mail to this group via your mail client (and not just via EME), you need to configure the cli_mail method (see doc) and enter a unique email address for this group. This can be left empty.','events-made-easy'); ?>
            </td>
	    </tr>
	    <?php if ($group['type'] == 'static') {?>
	    <tr>
	    <td><label for="public"><?php _e('Public?', 'events-made-easy') ?></label></td>
	    <td><?php echo eme_ui_select_binary($group['public'],'public'); ?><br />
	    <?php _e('If you chose for this group to be a public group, then this group will appear in the list of groups to subscribe/unsubscribe in the eme_subform and eme_unsubform shortcodes (and the form generated by #_UNSUB_URL).','events-made-easy');?>
	    </td>
	    </tr>
	    <tr>
	    <td><label for="People"><?php _e('People', 'events-made-easy') ?></label></td>
	    <td><?php echo eme_ui_multiselect($grouppersons,'persons',$mygroups,5,'',0,'eme_select2_people_class'); ?></td>
	    </tr>
	    <?php
	    } elseif ($group['type'] == 'dynamic_people') {
		    if (empty($group['search_terms']))
			    echo "<tr><td colspan=2><img style='vertical-align: middle;' src='".$eme_plugin_url."images/warning.png' alt='warning' />".esc_html__("Warning: this group is using an older method of defining the criteria for the members in it. Upon saving this group, you will lose that info, so make sure to reenter the criteria in the fields below","events-made-easy")."</td></tr>";
		    eme_render_people_searchfields($group);
	    } elseif ($group['type'] == 'dynamic_members') {
		    if (empty($group['search_terms']))
			    echo "<tr><td colspan=2><img style='vertical-align: middle;' src='".$eme_plugin_url."images/warning.png' alt='warning' />".esc_html__("Warning: this group is using an older method of defining the criteria for the members in it. Upon saving this group, you will lose that info, so make sure to reenter the criteria in the fields below","events-made-easy")."</td></tr>";
		    eme_render_members_searchfields($group);
	    } ?>
	    </table>
	    </div>
	 </div>
	 <p class="submit"><input type="submit" class="button-primary" name="submit" value="<?php if ($action=="add") _e('Add group', 'events-made-easy'); else _e('Update group', 'events-made-easy'); ?>" /></p>
      </div>
      </form>
   </div>
<?php
}

function eme_manage_groups_layout($message="") {
	$nonce_field = wp_nonce_field('eme_admin','eme_admin_nonce',false,false);
	if (empty($message))
		$hidden_style="display:none;";
	else
		$hidden_style="";
?>
      <div class="wrap nosubsub">
       <div id="poststuff">
	 <div id="icon-edit" class="icon32">
	    <br />
	 </div>

	 <div id="groups-message" class="notice is-dismissible eme-message-admin" style="<?php echo $hidden_style; ?>">
	       <p><?php echo $message; ?></p>
	 </div>

<?php if (current_user_can( get_option('eme_cap_edit_people'))) : ?>
	 <h1><?php _e('Add a new group', 'events-made-easy') ?></h1>
	 <div class="wrap">
	 <form id="groups-filter" method="post" action="<?php echo admin_url("admin.php?page=eme-groups"); ?>">
	    <?php echo $nonce_field; ?>
	    <input type="hidden" name="eme_admin_action" value="add_group" />
	    <input type="submit" class="button-primary" name="submit" value="<?php _e('Add group', 'events-made-easy');?>" />
	 </form>
	 </div>
<?php endif; ?>

	 <h1><?php _e('Manage groups', 'events-made-easy') ?></h1>

   <form id='groups-form' action="#" method="post">
   <?php echo $nonce_field; ?>
   <select id="eme_admin_action" name="eme_admin_action">
   <option value="" selected="selected"><?php _e ( 'Bulk Actions' , 'events-made-easy'); ?></option>
<?php if (current_user_can( get_option('eme_cap_edit_people'))) : ?>
   <option value="deleteGroups"><?php _e ( 'Delete selected groups','events-made-easy'); ?></option>
<?php endif; ?>
   </select>
   <button id="GroupsActionsButton" class="button-secondary action"><?php _e ( 'Apply' , 'events-made-easy'); ?></button>
   <span class="rightclickhint">
      <?php _e('Hint: rightclick on the column headers to show/hide columns','events-made-easy'); ?>
   </span>
   <div id="GroupsTableContainer"></div>
   </form>
   </div>
   </div>
<?php
}

function eme_person_image_div($person,$relative_div=0) {
	wp_enqueue_media();
	if ($person['properties']['image_id']>0)
		$image_url = esc_url(wp_get_attachment_image_url($person['properties']['image_id'],'full'));
	else
		$image_url = '';
	$no_image = __('No image set','events-made-easy');
	$set_image = __('Choose image','events-made-easy');
	$unset_image = __('Remove image','events-made-easy');
	$person_image = __('Person image','events-made-easy');
	if ($relative_div == 1) {
		$div_class = "div_person_image_relative";
		$person_image_bold = "";
	} else {
		$div_class = "div_person_image";
		$person_image_bold = "<b>$person_image</b>";
	}
	$output =<<<EOT
<div id="{$div_class}">
      <br />{$person_image_bold}</b>
   <div id="eme_person_no_image" class="postarea">
      {$no_image}
   </div>
   <div id="eme_person_current_image" class="postarea">
   <img id='eme_person_image_example' alt='{$person_image}' title='{$person_image}' src='$image_url' />
   <input type='hidden' name='properties[image_id]' id='eme_person_image_id' value='{$person['properties']['image_id']}' />
   </div>
   <br />

   <div class="uploader">
   <input type="button" name="image_button" id="eme_person_image_button" value="{$set_image}" class="button-secondary" />
   <input type="button" id="eme_person_remove_old_image" name="remove_old_image" value="{$unset_image}" class="button-secondary" />
   </div>
</div>
EOT;
	return $output;
}

// API function for people wanting to check if somebody is already registered
function eme_get_person_by_post() {
	if (isset($_POST['lastname']) && isset($_POST['email'])) {
		$lastname = eme_sanitize_request($_POST['lastname']);
		if (isset($_POST['firstname']))
			$firstname = eme_sanitize_request($_POST['firstname']);
		else
			$firstname = "";
		$email = eme_sanitize_email($_POST['email']);
		if (!eme_is_email($email)) {
			return false;
		}
		$person = eme_get_person_by_name_and_email($lastname, $firstname, $email);
		return $person;
	} else {
		return false;
	}
}

function eme_count_persons_by_email($email) {
	global $wpdb; 
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	$sql = $wpdb->prepare("SELECT COUNT(*) FROM $people_table WHERE email = %s AND status=".EME_PEOPLE_STATUS_ACTIVE,$email);
	return $wpdb->get_var($sql);
}

function eme_get_personids_by_email($email) {
	global $wpdb; 
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	$sql = $wpdb->prepare("SELECT person_id FROM $people_table WHERE email = %s AND status=".EME_PEOPLE_STATUS_ACTIVE,$email);
	return $wpdb->get_col($sql);
}

function eme_get_person_by_email_only($email) {
	global $wpdb; 
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	// by default this function (eme_get_person_by_email_only) searches for persons with empty name and matching email
	// but if the option eme_unique_email_per_person is set, we search only for matching email 
	// this option will get activated once donation has been done
	//if (get_option('eme_unique_email_per_person'))
	//	$sql = $wpdb->prepare("SELECT * FROM $people_table WHERE email = %s AND status=".EME_PEOPLE_STATUS_ACTIVE. " ORDER BY wp_id DESC LIMIT 1",$email);
	//else
		$sql = $wpdb->prepare("SELECT * FROM $people_table WHERE lastname = '' AND firstname = '' AND email = %s AND status=".EME_PEOPLE_STATUS_ACTIVE. " ORDER BY wp_id DESC LIMIT 1",$email);
	$res = $wpdb->get_row($sql, ARRAY_A);
	if ($res)
		$res['properties']=eme_init_person_props(unserialize($res['properties']));
	return $res;
}

function eme_get_person_by_name_and_email($lastname, $firstname, $email) {
	// INFO: database searches are case insensitive
	// we order by "wp_id DESC" so if someone matches with and without wp_id, the one with wp_id wins
	// we also search for lastname+firstname in the wrong order (if someone missed and switched last/firstname)
	global $wpdb; 
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	if (!empty($firstname)) {
		$sql = $wpdb->prepare("SELECT * FROM $people_table WHERE ((lastname = %s AND firstname = %s) OR (firstname = %s AND lastname = %s)) AND email = %s AND status=".EME_PEOPLE_STATUS_ACTIVE. " ORDER BY wp_id DESC",$lastname,$firstname,$lastname,$firstname,$email);
	} else {
		$sql = $wpdb->prepare("SELECT * FROM $people_table WHERE lastname = %s AND email = %s AND status=".EME_PEOPLE_STATUS_ACTIVE. " ORDER BY wp_id DESC",$lastname,$email);
	}
	$res = $wpdb->get_row($sql, ARRAY_A);
	if (!$res && get_option('eme_rsvp_check_without_accents')) {
		if (!empty($firstname))
			$sql = $wpdb->prepare("SELECT * FROM $people_table WHERE ((lastname = %s AND firstname = %s) OR (firstname = %s AND lastname = %s)) AND email = %s AND status=".EME_PEOPLE_STATUS_ACTIVE. " ORDER BY wp_id DESC",remove_accents($lastname),remove_accents($firstname),remove_accents($lastname),remove_accents($firstname),$email);
		else
			$sql = $wpdb->prepare("SELECT * FROM $people_table WHERE lastname = %s AND email = %s AND status=".EME_PEOPLE_STATUS_ACTIVE. " ORDER BY wp_id DESC",remove_accents($lastname),$email);
		$res = $wpdb->get_row($sql, ARRAY_A);
	}
	if ($res)
		$res['properties']=eme_init_person_props(unserialize($res['properties']));
	return $res;
}

function eme_get_personid_by_wpid($wp_id) {
	global $wpdb; 
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	$sql = $wpdb->prepare("SELECT person_id FROM $people_table WHERE wp_id = %d LIMIT 1",$wp_id);
	return intval($wpdb->get_var($sql));
}
function eme_get_wpid_by_personid($person_id) {
	global $wpdb; 
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	$sql = $wpdb->prepare("SELECT wp_id FROM $people_table WHERE person_id = %d",$person_id);
	return intval($wpdb->get_var($sql));
}
function eme_get_used_wpids($exclude_id=0) {
        global $wpdb;
        $people_table = $wpdb->prefix.PEOPLE_TBNAME;
        if (!empty($exclude_id))
                $sql = $wpdb->prepare("SELECT DISTINCT wp_id FROM $people_table WHERE wp_id <> %d", $exclude_id);
        else
                $sql = "SELECT DISTINCT wp_id FROM $people_table";
        return $wpdb->get_col($sql);
}

function eme_find_persons_double_email() {
	global $wpdb; 
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	$sql = "SELECT email FROM $people_table WHERE status=".EME_PEOPLE_STATUS_ACTIVE." GROUP BY email HAVING COUNT(*)>1";
	return $wpdb->get_col($sql);
}

function eme_find_persons_double_wp() {
	global $wpdb; 
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	$sql = "SELECT wp_id FROM $people_table WHERE status=".EME_PEOPLE_STATUS_ACTIVE." AND wp_id>0 AND wp_id IS NOT NULL GROUP BY wp_id HAVING COUNT(*)>1";
	return $wpdb->get_col($sql);
}

function eme_count_persons_with_wp_id($wp_id) {
	global $wpdb; 
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	$sql = $wpdb->prepare("SELECT COUNT(*) FROM $people_table WHERE wp_id = %d AND status=".EME_PEOPLE_STATUS_ACTIVE,$wp_id);
	return $wpdb->get_var($sql);
}

function eme_get_people_by_ids($ids) {
	global $wpdb; 
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	$sql = "SELECT * FROM $people_table WHERE person_id IN ($ids) AND status=".EME_PEOPLE_STATUS_ACTIVE." ORDER BY person_id";
	return $wpdb->get_results($sql, ARRAY_A);
}

function eme_get_people_by_wp_ids($wp_ids) {
	global $wpdb; 
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	$sql = "SELECT * FROM $people_table WHERE wp_id IN ($wp_ids) AND status=".EME_PEOPLE_STATUS_ACTIVE." ORDER BY wp_id";
	return $wpdb->get_results($sql, ARRAY_A);
}

function eme_get_person_by_wp_id($wp_id,$use_wp_info=1) {
	global $wpdb; 
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	$user_info = get_userdata($wp_id);
	$lastname=$user_info->user_lastname;
	$firstname=$user_info->user_firstname;
	$email=$user_info->user_email;

	$person=wp_cache_get("eme_person_wpid $wp_id");
	if ($person===false) {
		$sql = $wpdb->prepare("SELECT * FROM $people_table WHERE wp_id = %d AND status=".EME_PEOPLE_STATUS_ACTIVE,$wp_id);
		$lines = $wpdb->get_results($sql, ARRAY_A);
	} else {
		return $person;
	}
	// if there's more than 1 person with the same wp_id, don't return anything since we might be returning the wrong person if we would use "LIMIT 1" in the sql
	if (count($lines)>1) return false;
	if (count($lines)==1) {
		$person = $lines[0];
		if ($use_wp_info) {
			// we use the lastname from the wp profile if that is not empty
			// if that is empty, we use the info from the person
			// if that is still empty, we use the display_name
			if (!empty($lastname))
				$person['lastname']=$lastname;
			if (empty($lastname))
				$lastname=$user_info->display_name;
			if (!empty($firstname))
				$person['firstname']=$firstname;
			$person['email']=$email;
		}
		$person['properties']=eme_init_person_props(unserialize($person['properties']));
	} else {
		// imagine there is no user yet, but someone matching with this info (lastname, firstname, email), then we add the wp id to that existing user
		if (empty($lastname))
			$lastname=$user_info->display_name;
		$person=eme_get_person_by_name_and_email($lastname,$firstname,$email);
		if (!$person)
			$person=eme_get_person_by_email_only($email);
		if (!empty($person)) {
			$res=eme_update_person_wp_id($person['person_id'],$wp_id);
			wp_cache_delete("eme_person_wpid $wp_id");
			if ($res!==false)
				$person['wp_id']=$wp_id;
		}
	}
	wp_cache_set("eme_person_wpid $wp_id",$person,"",10);
	return $person;
}

function eme_get_person_by_randomid($random_id) {
	global $wpdb; 
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	$sql = $wpdb->prepare("SELECT * FROM $people_table WHERE random_id = %s LIMIT 1",$random_id);
	$person = $wpdb->get_row($sql, ARRAY_A);
	if ($person)
		$person['properties']=eme_init_person_props(unserialize($person['properties']));
	return $person;
}
function eme_get_person($person_id) {
	global $wpdb; 
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	$sql = $wpdb->prepare("SELECT * FROM $people_table WHERE person_id = %d LIMIT 1",$person_id);
	$person = $wpdb->get_row($sql, ARRAY_A);
	if ($person)
		$person['properties']=eme_init_person_props(unserialize($person['properties']));
	return $person;
}

function eme_trash_people($person_ids) {
	global $wpdb;
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	if (has_action('eme_trash_person_action')) {
		$ids_arr = explode(',',$person_ids);
		foreach ($ids_arr as $person_id) {
			$person = eme_get_person($person_id);
			do_action('eme_trash_person_action',$person);
		}
	}
	eme_trash_person_bookings_future_events($person_ids);
	eme_delete_person_memberships($person_ids);
	eme_delete_person_groups($person_ids);
	$modif_date=current_time('mysql',false);
	$sql = "UPDATE $people_table SET status=".EME_PEOPLE_STATUS_TRASH.", modif_date='$modif_date' WHERE person_id IN ($person_ids)";
	$wpdb->query($sql);
	// break the family relationship
	$sql = "UPDATE $people_table SET related_person_id=0 WHERE related_person_id IN ($person_ids)";
	$wpdb->query($sql);
}

function eme_gdpr_trash_people($person_ids) {
	// we keep the bookings, so we can keep track of past events
	//eme_delete_person_bookings($ids);
	$ids_arr=explode(',',$person_ids);
	if (has_action('eme_trash_person_action')) {
		foreach ($ids_arr as $person_id) {
			$person = eme_get_person($person_id);
			do_action('eme_trash_person_action',$person);
		}
	}
	eme_trash_person_bookings_future_events($person_ids);
	eme_delete_person_answers($person_ids);
	eme_delete_person_memberships($person_ids);
	eme_delete_person_groups($person_ids);
	$new_person=eme_new_person();
	foreach ($ids_arr as $person_id) {
		$new_person['lastname']="GDPR deleted $person_id";
		$new_person['firstname']="GDPR deleted $person_id";
		$new_person['email']="GDPR deleted $person_id";
		$new_person['status']=EME_PEOPLE_STATUS_TRASH; // this moves the person to the trash too
		$new_person['massmail']=0;
		$new_person['newsletter']=0;
		$new_person['gdpr']=0;
		eme_db_update_person($person_id,$new_person);
	}
}

// for CRON
function eme_people_birthday_emails() {
	global $wpdb,$eme_timezone;

	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	$members_table = $wpdb->prefix.MEMBERS_TBNAME;
	$eme_date_obj_now=new ExpressiveDate("now",$eme_timezone);
	$month_day = $eme_date_obj_now->format("m-d");
	// let's do the leap year logic outside the db
	if (get_option('eme_bd_email_members_only')) {
		$join = "LEFT JOIN $members_table ON $people_table.person_id=$members_table.person_id"; 
		$status_active = EME_MEMBER_STATUS_ACTIVE;
		$status_grace = EME_MEMBER_STATUS_GRACE;
		$members_only = " AND $members_table.status IN ($status_active,$status_grace)";
	} else {
		$join = "";
		$members_only = "";
	}
	if ($month_day == "03-01") {
		$sql = "SELECT DISTINCT $people_table.person_id FROM $people_table $join
			WHERE 
			$people_table.bd_email=1
			AND (DATE_FORMAT(birthdate,'%m-%d') = '$month_day'
			OR DATE_FORMAT(birthdate,'%m-%d') = '02-29')
			AND $people_table.status=".EME_PEOPLE_STATUS_ACTIVE."
			$members_only"
			;
	} else {
		$sql = "SELECT DISTINCT $people_table.person_id FROM $people_table $join
			WHERE 
			$people_table.bd_email=1
			AND DATE_FORMAT(birthdate,'%m-%d') = '$month_day'
			AND $people_table.status=".EME_PEOPLE_STATUS_ACTIVE."
			$members_only"
			;
	}
	$person_ids = $wpdb->get_col($sql);

	$mail_text_html = get_option('eme_rsvp_send_html')?"htmlmail":"text";

	$contact_email = get_option('eme_mail_sender_address');
	$contact_name = get_option('eme_mail_sender_name');
	if (empty($contact_email)) {
		$contact = eme_get_contact(0);
		$contact_email = $contact->user_email;
		$contact_name = $contact->display_name;
	}

	$subject_template=get_option('eme_bd_email_subject');
	$body_template=eme_translate(get_option('eme_bd_email_body'));
	foreach ($person_ids as $person_id) {
		$person = eme_get_person($person_id);
		$subject = eme_replace_people_placeholders($subject_template, $person, "text");
		$body = eme_replace_people_placeholders($body_template, $person, $mail_text_html);
		$full_name = eme_format_full_name($person['firstname'],$person['lastname']);
		eme_queue_mail($subject,$body, $person['email'], $full_name, $contact_email, $contact_name);
	}
}

function eme_untrash_people($person_ids) {
	global $wpdb;
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	$sql = "UPDATE $people_table SET status=".EME_PEOPLE_STATUS_ACTIVE." WHERE person_id IN ($person_ids)";
	$wpdb->query($sql);
}

function eme_add_personid_to_newsletter($person_id) {
	global $wpdb;
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	$sql = $wpdb->prepare("UPDATE $people_table SET newsletter=1 WHERE person_id=%d",$person_id);
	$sql_res=$wpdb->query($sql);
	if ($sql_res === false)
		return false;
	else
		return true;
}
function eme_remove_email_from_newsletter($email) {
	global $wpdb;
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	$sql = $wpdb->prepare("UPDATE $people_table SET newsletter=0 WHERE email=%s",$email);
	$wpdb->query($sql);
}

function eme_delete_people($person_ids) {
	global $wpdb;
	// we call all delete functions here, even if not needed (delete only happens after thrash and when trashing we already delte the relevant memberships and groups for that person)
	// this way this function can be called from everywhere without needing to know what to clean up
	if (has_action('eme_delete_person_action')) {
		foreach ($ids_arr as $person_id) {
			$person = eme_get_person($person_id);
			do_action('eme_delete_person_action',$person);
		}
	}
	eme_delete_person_bookings($person_ids);
	eme_delete_person_answers($person_ids);
	eme_delete_person_memberships($person_ids);
	eme_delete_person_groups($person_ids);
	eme_delete_person_attendances($person_ids);
	$people_table = $wpdb->prefix.PEOPLE_TBNAME;
	$sql = "DELETE FROM $people_table WHERE person_id IN ($person_ids)";
	$wpdb->query($sql);
	$ids_arr=explode(',',$person_ids);
	foreach ($ids_arr as $person_id) {
		eme_delete_uploaded_files($person_id,"people");
	}
}

function eme_get_group($group_id) {
	global $wpdb; 
	$groups_table = $wpdb->prefix.GROUPS_TBNAME;
	$sql = $wpdb->prepare("SELECT * FROM $groups_table WHERE group_id = %d",$group_id);
	$res = $wpdb->get_row($sql, ARRAY_A);
	if ($res !== false && !empty($res)) {
		$group['search_terms']=unserialize($res['search_terms']);
	}
	return $res;
}
function eme_get_group_by_name($name) {
	global $wpdb; 
	$groups_table = $wpdb->prefix.GROUPS_TBNAME;
	$sql = $wpdb->prepare("SELECT * FROM $groups_table WHERE name = %s LIMIT 1",$name);
	return $wpdb->get_row($sql, ARRAY_A);
}
function eme_get_group_by_email($email) {
	global $wpdb; 
	$groups_table = $wpdb->prefix.GROUPS_TBNAME;
	$sql = $wpdb->prepare("SELECT * FROM $groups_table WHERE email = %s LIMIT 1",eme_sanitize_email($email));
	return $wpdb->get_row($sql, ARRAY_A);
}

function eme_get_group_name($group_id) {
	global $wpdb; 
	$groups_table = $wpdb->prefix.GROUPS_TBNAME;
	$sql = $wpdb->prepare("SELECT name FROM $groups_table WHERE group_id = %d",$group_id);
	$result = $wpdb->get_var($sql);
	return $result;
}

function eme_get_groups() {
	global $wpdb; 
	$table = $wpdb->prefix.GROUPS_TBNAME;
	$sql = "SELECT * FROM $table ORDER BY name";
	return $wpdb->get_results($sql, ARRAY_A );
}
function eme_get_public_groups($group_ids="") {
	global $wpdb; 
	$table = $wpdb->prefix.GROUPS_TBNAME;
	if (!empty($group_ids)) {
		$sql = "SELECT * FROM $table WHERE public=1 AND type='static' AND group_id IN ($group_ids) ORDER BY name";
	} else {
		$sql = "SELECT * FROM $table WHERE public=1 AND type='static' ORDER BY name";
	}
	return $wpdb->get_results($sql, ARRAY_A );
}
function eme_get_public_groupids() {
	global $wpdb; 
	$table = $wpdb->prefix.GROUPS_TBNAME;
	$sql = "SELECT group_id FROM $table WHERE public=1 AND type='static' ORDER BY name";
	return $wpdb->get_col($sql);
}
function eme_get_membergroups() {
	global $wpdb; 
	$table = $wpdb->prefix.GROUPS_TBNAME;
	$sql = "SELECT * FROM $table WHERE type='dynamic_members' ORDER BY name";
	return $wpdb->get_results($sql, ARRAY_A );
}

function eme_get_static_groups() {
	global $wpdb; 
	$table = $wpdb->prefix.GROUPS_TBNAME;
	$sql = "SELECT * FROM $table WHERE type = 'static' ORDER BY name";
	return $wpdb->get_results($sql, ARRAY_A );
}

function eme_get_persongroup_ids($person_id) {
	global $wpdb; 
	$table = $wpdb->prefix.USERGROUPS_TBNAME;
	$sql = $wpdb->prepare("SELECT group_id FROM $table WHERE person_id = %d", $person_id);
	return $wpdb->get_col($sql);
}

function eme_get_persongroup_names($person_id,$wp_id=0) {
   global $wpdb; 
   $table = $wpdb->prefix.USERGROUPS_TBNAME;
   $groups_table = $wpdb->prefix.GROUPS_TBNAME;
   $people_table = $wpdb->prefix.PEOPLE_TBNAME;
   if ($wp_id)
	   $sql = $wpdb->prepare("SELECT DISTINCT groups.name FROM $table AS ugroups,$groups_table AS groups WHERE ugroups.person_id IN (SELECT person_id FROM $people_table WHERE wp_id=%d) AND ugroups.group_id=groups.group_id", $wp_id);
   else
	   $sql = $wpdb->prepare("SELECT DISTINCT groups.name FROM $table AS ugroups,$groups_table AS groups WHERE ugroups.person_id = %d AND ugroups.group_id=groups.group_id", $person_id);
   return $wpdb->get_col($sql);
}

function eme_get_grouppersons($group_ids, $order="ASC") {
   if (!eme_is_list_of_int($group_ids)) {
	   return;
   }
   $person_ids_arr = eme_get_groups_person_ids($group_ids);
   // eme_get_persons returns all people if all 3 first args are empty, and of course that's not what we want here
   // so we return an empty result if $person_ids_arr is empty
   if (!empty($person_ids_arr))
	   return eme_get_persons($person_ids_arr,'','',$order);
   else
	   return;
}

function eme_add_persongroups($person_id,$group_ids,$public=0) {
   global $wpdb; 
   $table = $wpdb->prefix.USERGROUPS_TBNAME;
   $people_table = $wpdb->prefix.PEOPLE_TBNAME;
   if (empty($group_ids))
	   return;
   $current_group_ids = eme_get_persongroup_ids($person_id);
   // make sure it is an array
   if (!is_array($group_ids))
	   $group_ids=array($group_ids);

   $res=true;
   foreach ($group_ids as $t_group) {
	   // -1 is the newsletter
	   if (is_numeric($t_group) && $t_group==-1) {
		   $res = eme_add_personid_to_newsletter($person_id);
		   continue;
	   }
	   if (is_numeric($t_group))
		   $group=eme_get_group($t_group);
	   else
                   $group=eme_get_group_by_name($t_group);
	   if (!empty($group)) {
		   if ($public && empty($group['public']))
			   continue; // the continue-statement continues the higher foreach-loop
		   if (!in_array($group['group_id'],$current_group_ids)) {
			   $sql = $wpdb->prepare("INSERT INTO $table (person_id,group_id) VALUES (%d,%d)", $person_id, $group['group_id']);
			   $sql_res=$wpdb->query($sql);
			   if ($sql_res === false)
				   $res=false;
		   }
	   } else {
		   $res=false;
	   }
   }
   return $res;
}

function eme_get_person_in_groups($email,$group_ids) {
   global $wpdb; 
   $people_table = $wpdb->prefix.PEOPLE_TBNAME;
   $usergroups_table = $wpdb->prefix.USERGROUPS_TBNAME;
   if (empty($group_ids))
	   $sql = $wpdb->prepare("SELECT p.person_id FROM $people_table p WHERE p.email=%s LIMIT 1", $email);
   else
	   $sql = $wpdb->prepare("SELECT p.person_id FROM $people_table p LEFT JOIN $usergroups_table u ON u.person_id=p.person_id WHERE p.email=%s AND u.group_id IN ($group_ids) LIMIT 1", $email);
   $person_id = $wpdb->get_var($sql);
 
   // -1 is the newsletter, a special "group"
   if (empty($person_id) && in_array("-1",explode(",",$group_ids)) ) {
	   $sql = $wpdb->prepare("SELECT p.person_id FROM $people_table p WHERE p.email=%s AND p.newsletter=1 LIMIT 1", $email);
	   $person_id = $wpdb->get_var($sql);
   }
   return $person_id;
}

function eme_delete_emailfromgroup($email,$group_id) {
   global $wpdb; 
   $people_table = $wpdb->prefix.PEOPLE_TBNAME;
   $usergroups_table = $wpdb->prefix.USERGROUPS_TBNAME;
   $sql = $wpdb->prepare("DELETE FROM $usergroups_table WHERE group_id=%d AND person_id IN (SELECT person_id FROM $people_table WHERE email=%s)", $group_id, $email);
   $wpdb->query($sql);
}

function eme_delete_personfromgroup($person_id,$group_id) {
   global $wpdb; 
   $people_table = $wpdb->prefix.PEOPLE_TBNAME;
   $usergroups_table = $wpdb->prefix.USERGROUPS_TBNAME;
   $sql = $wpdb->prepare("DELETE FROM $usergroups_table WHERE group_id=%d AND person_id=%d", $group_id, $person_id);
   $wpdb->query($sql);
}

function eme_update_persongroups($person_id,$group_ids) {
   global $wpdb; 
   $table = $wpdb->prefix.USERGROUPS_TBNAME;
   $sql = $wpdb->prepare("DELETE from $table WHERE person_id = %d", $person_id);
   $wpdb->query($sql);
   foreach ($group_ids as $group_id) {
	   $sql = $wpdb->prepare("INSERT INTO $table (person_id,group_id) VALUES (%d,%d)", $person_id, $group_id);
	   $wpdb->query($sql);
   }
}

function eme_update_grouppersons($group_id,$person_ids) {
   global $wpdb; 
   $table = $wpdb->prefix.USERGROUPS_TBNAME;
   $sql = $wpdb->prepare("DELETE from $table WHERE group_id = %d", $group_id);
   $wpdb->query($sql);
   foreach ($person_ids as $person_id) {
	   $sql = $wpdb->prepare("INSERT INTO $table (person_id,group_id) VALUES (%d,%d)", $person_id, $group_id);
	   $wpdb->query($sql);
   }
}

function eme_delete_group($group_id) {
   global $wpdb;
   $groups_table = $wpdb->prefix.GROUPS_TBNAME;
   $usergroups_table = $wpdb->prefix.USERGROUPS_TBNAME;
   $sql = $wpdb->prepare("DELETE FROM $groups_table WHERE group_id = %d",$group_id);
   $wpdb->query($sql);
   $sql = $wpdb->prepare("DELETE FROM $usergroups_table WHERE group_id = %d",$group_id);
   $wpdb->query($sql);
}

function eme_delete_groups($group_ids) {
   global $wpdb;
   $groups_table = $wpdb->prefix.GROUPS_TBNAME;
   $usergroups_table = $wpdb->prefix.USERGROUPS_TBNAME;
   $sql = "DELETE FROM $groups_table WHERE group_id IN ($group_ids)";
   $wpdb->query($sql);
   $sql = "DELETE FROM $usergroups_table WHERE group_id IN ($group_ids)";
   $wpdb->query($sql);
}

function eme_get_persons($person_ids="",$extra_search="", $limit="", $order="ASC") {
   global $wpdb; 
   $people_table = $wpdb->prefix.PEOPLE_TBNAME;

   $where = "";
   $where_arr = array();
   $where_arr[] = "status=".EME_PEOPLE_STATUS_ACTIVE;
   if (!empty($person_ids) && eme_array_integers($person_ids)) {
      $tmp_ids=join(",",$person_ids);
      $where_arr[] = "person_id IN ($tmp_ids)";
   }
   if (!empty($extra_search)) $where_arr[] = $extra_search;
   if ($where_arr)
      $where = "WHERE ".implode(" AND ",$where_arr);

   $orderby = "";
   if ($order == "ASC" || $order == "DESC") {
	   // let's try to order as the full name dictates
	   $name_format = get_option('eme_full_name_format');
	   if (strpos($name_format,"#_LASTNAME")>strpos($name_format,"#_FIRSTNAME"))
		   $orderby = "ORDER BY firstname $order,lastname $order,person_id $order";
	   else
		   $orderby = "ORDER BY lastname $order,firstname $order,person_id $order";
   } elseif (!eme_is_empty_string($order) && preg_match("/^[\w_\-\, ]+$/",$order)) {
      $order_arr = [];
      if (preg_match("/^[\w_\-\, ]+$/",$order)) {
              $order_tmp_arr = explode(',',$order);
              foreach ($order_tmp_arr as $order_ell) {
                      $asc_desc = "ASC";
                      if (preg_match("/DESC$/",$order_ell))
                              $asc_desc = "DESC";
                      $order_ell = trim(preg_replace("/ASC$|DESC$|\s/",'',$order_ell));
                      $order_arr[] = "$order_ell $asc_desc";
              }
      }
      if (!empty($order_arr)) {
              $orderby = "ORDER BY ".join(', ',$order_arr);
      } else {
              $orderby = "ORDER BY lastname ASC,firstname ASC,person_id ASC";
      }
   }

   $sql = "SELECT * FROM $people_table $where $orderby $limit";

   $persons = $wpdb->get_results($sql, ARRAY_A);
   foreach ($persons as $key=>$person) {
      $person['properties'] = eme_init_person_props(unserialize($person['properties']));
      $persons[$key] = $person;
   }
   return $persons;
}

function eme_get_allmail_person_ids() {
   global $wpdb; 
   $people_table = $wpdb->prefix.PEOPLE_TBNAME;
   $sql = "SELECT person_id FROM $people_table WHERE status=".EME_PEOPLE_STATUS_ACTIVE." AND email<>'' GROUP BY email";
   return $wpdb->get_col($sql);
}

function eme_get_newsletter_person_ids() {
   global $wpdb; 
   $people_table = $wpdb->prefix.PEOPLE_TBNAME;
   $sql = "SELECT person_id FROM $people_table WHERE status=".EME_PEOPLE_STATUS_ACTIVE." AND massmail=1 AND newsletter=1 AND email<>'' GROUP BY email";
   return $wpdb->get_col($sql);
}

function eme_get_massmail_person_ids() {
   global $wpdb; 
   $people_table = $wpdb->prefix.PEOPLE_TBNAME;
   $sql = "SELECT person_id FROM $people_table WHERE status=".EME_PEOPLE_STATUS_ACTIVE." AND massmail=1 AND email<>'' GROUP BY email";
   return $wpdb->get_col($sql);
}

function eme_get_groups_person_massemails($group_ids) {
   global $wpdb;
   $people_table = $wpdb->prefix.PEOPLE_TBNAME;
   $usergroups_table = $wpdb->prefix.USERGROUPS_TBNAME;
   $groups_table = $wpdb->prefix.GROUPS_TBNAME;
   $sql = "SELECT group_id FROM $groups_table WHERE group_id IN ($group_ids) AND type = 'static'";
   $static_groupids = join(',',$wpdb->get_col($sql));

   // for static groups we look at the massmail option, for dynamic groups not
   if (!empty($static_groupids)) {
           $sql = "SELECT people.email FROM $people_table AS people LEFT JOIN $usergroups_table AS ugroups ON people.person_id=ugroups.person_id WHERE people.status=".EME_PEOPLE_STATUS_ACTIVE." AND people.massmail=1 AND people.email<>'' AND ugroups.group_id IN ($static_groupids) GROUP BY people.email";
           $res = $wpdb->get_col($sql);
   } else {
           $res=array();
   }

   $sql = "SELECT * FROM $groups_table WHERE group_id IN ($group_ids) AND (type = 'dynamic_people' OR type = 'dynamic_members')";
   $dynamic_groups = $wpdb->get_results($sql, ARRAY_A);
   foreach ($dynamic_groups as $dynamic_group) {
           if (!empty($dynamic_group['search_terms'])) {
                   if ($dynamic_group['type']=='dynamic_members')
                           $sql=eme_get_sql_members_searchfields($dynamic_group['search_terms'],0,0,'',0,0,0,1);
                   if ($dynamic_group['type']=='dynamic_people')
                           $sql=eme_get_sql_people_searchfields($dynamic_group['search_terms'],0,0,'',0,0,1);

           } else {
                   $sql = "SELECT people.email ".$dynamic_group['stored_sql'];
           }
           $res2 = $wpdb->get_col($sql);
           $res=array_merge($res,$res2);
   }
   return $res;
}

function eme_get_groups_person_ids($group_ids) {
   global $wpdb; 
   $people_table = $wpdb->prefix.PEOPLE_TBNAME;
   $usergroups_table = $wpdb->prefix.USERGROUPS_TBNAME;
   $groups_table = $wpdb->prefix.GROUPS_TBNAME;
   $sql = "SELECT group_id FROM $groups_table WHERE group_id IN ($group_ids) AND type = 'static'";
   $static_groupids = join(',',$wpdb->get_col($sql));

   if (!empty($static_groupids)) {
	   $sql = "SELECT people.person_id FROM $people_table AS people LEFT JOIN $usergroups_table AS ugroups ON people.person_id=ugroups.person_id WHERE people.status=".EME_PEOPLE_STATUS_ACTIVE." AND ugroups.group_id IN ($static_groupids)";
	   $res = $wpdb->get_col($sql);
   } else {
	   $res=array();
   }

   $sql = "SELECT * FROM $groups_table WHERE group_id IN ($group_ids) AND (type = 'dynamic_people' OR type = 'dynamic_members')";
   $dynamic_groups = $wpdb->get_results($sql, ARRAY_A);
   foreach ($dynamic_groups as $dynamic_group) {
	   if (!empty($dynamic_group['search_terms'])) {
		   $search_terms=unserialize($dynamic_group['search_terms']);
		   if ($dynamic_group['type']=='dynamic_members')
			   $sql=eme_get_sql_members_searchfields($search_terms,0,0,'',0,0,1); 
		   if ($dynamic_group['type']=='dynamic_people')
			   $sql=eme_get_sql_people_searchfields($search_terms,0,0,'',0,1); 
	   } else {
		   $sql = "SELECT people.person_id ".$dynamic_group['stored_sql'];
	   }
	   $res2 = $wpdb->get_col($sql);
	   $res=array_merge($res,$res2);
   }

   return array_unique($res);
}

function eme_get_groups_member_ids($group_ids) {
   global $wpdb; 
   $groups_table = $wpdb->prefix.GROUPS_TBNAME;
   $sql = "SELECT * FROM $groups_table WHERE group_id IN ($group_ids) AND type = 'dynamic_members'";
   $dynamic_groups = $wpdb->get_results($sql, ARRAY_A);
   $res=array();
   foreach ($dynamic_groups as $dynamic_group) {
	   if (!empty($dynamic_group['search_terms'])) {
		   $search_terms=unserialize($dynamic_group['search_terms']);
		   $sql=eme_get_sql_members_searchfields($search_terms,0,0,'',0,1); 
	   } else {
		   $sql = "SELECT members.member_id ".$dynamic_group['stored_sql'];
	   }
	   $res2 = $wpdb->get_col($sql);
	   $res=array_merge($res,$res2);
   }
   return $res;
}

function eme_get_memberships_member_ids($membership_ids) {
   global $wpdb; 
   $people_table = $wpdb->prefix.PEOPLE_TBNAME;
   $members_table = $wpdb->prefix.MEMBERS_TBNAME;
   $status_active=EME_MEMBER_STATUS_ACTIVE;
   $status_grace=EME_MEMBER_STATUS_GRACE;
   $sql = "SELECT members.member_id FROM $people_table AS people LEFT JOIN $members_table AS members ON people.person_id=members.person_id WHERE people.status=".EME_PEOPLE_STATUS_ACTIVE." AND members.status IN ($status_active,$status_grace) AND members.membership_id IN ($membership_ids) GROUP BY people.email";
   return $wpdb->get_col($sql);
}

function eme_db_insert_person($line) {
   global $wpdb; 
   $table = $wpdb->prefix.PEOPLE_TBNAME;

   $person=eme_new_person();
   // we only want the columns that interest us
   // we need to do this since this function is also called for csv import
   $keys=array_intersect_key($line,$person);
   $new_line=array_merge($person,$keys);

   if (has_filter('eme_insert_person_filter')) $new_line=apply_filters('eme_insert_person_filter',$new_line);

   // some properties validation: only image_id as int is allowed
   $props=maybe_unserialize($new_line['properties']);
   $new_line['properties']=array();
   foreach ($props as $key=>$val) {
	   if ($key == 'image_id')
		   $new_line['properties'][$key]=intval($val);
   }
   $new_line['properties'] = serialize($new_line['properties']);

   if (empty($new_line['creation_date']) || !(eme_is_date($new_line['creation_date']) || eme_is_datetime($new_line['creation_date'])))
	   $new_line['creation_date']=current_time('mysql',false);
   $new_line['modif_date']=$new_line['creation_date'];

   // keep the wp-id seperate
   $wp_id=0;
   if (isset($new_line['wp_id'])) {
      $wp_id=$new_line['wp_id'];
      unset ($new_line['wp_id']);
   }

   $new_line['random_id']=eme_random_id();
   if ($wpdb->insert($table,$new_line) === false) {
      return false;
   } else {
      $person_id=$wpdb->insert_id;
      eme_update_person_wp_id($person_id,$wp_id);
      return $person_id;
   }
}

function eme_db_insert_group($line) {
   global $wpdb; 
   $table = $wpdb->prefix.GROUPS_TBNAME;

   $group=eme_new_group();
   // we only want the columns that interest us
   // we need to do this since this function is also called for csv import
   $keys=array_intersect_key($line,$group);
   $new_line=array_merge($group,$keys);

   if (has_filter('eme_insert_group_filter')) $new_line=apply_filters('eme_insert_group_filter',$new_line);

   if ($wpdb->insert($table,$new_line) === false) {
      return false;
   } else {
      return $wpdb->insert_id;
   }
}

function eme_db_update_person($person_id,$line) {
   global $wpdb; 
   $table = $wpdb->prefix.PEOPLE_TBNAME;
   $where = array();
   $where['person_id'] = intval($person_id);

   $person=eme_get_person($person_id);
   unset($person['person_id']);
   // we only want the columns that interest us
   // we need to do this since this function is also called for csv import
   $keys=array_intersect_key($line,$person);
   $new_line=array_merge($person,$keys);
   $new_line['properties'] = eme_maybe_serialize($new_line['properties']);

   // some properties validation: only image_id as int is allowed
   $props=maybe_unserialize($new_line['properties']);
   $new_line['properties']=array();
   foreach ($props as $key=>$val) {
	   if ($key == 'image_id')
		   $new_line['properties'][$key]=intval($val);
   }
   $new_line['properties'] = serialize($new_line['properties']);

   $new_line['modif_date']=current_time('mysql',false);

   // keep the wp-id seperate
   $wp_id=0;
   if (isset($new_line['wp_id'])) {
      $wp_id=$new_line['wp_id'];
      unset ($new_line['wp_id']);
   }

   if (!empty($new_line) && $wpdb->update($table, $new_line, $where) === false) {
      return false;
   } else {
      $res=eme_update_person_wp_id($person_id,$wp_id);
      if ($res!==false)
	      return $person_id;
      else
	      return false;
   }
}

function eme_db_update_group($group_id,$line) {
   global $wpdb; 
   $table = $wpdb->prefix.GROUPS_TBNAME;
   $where = array();
   $where['group_id'] = intval($group_id);

   $group=eme_get_group($group_id);
   unset($group['group_id']);
   // we only want the columns that interest us
   // we need to do this since this function is also called for csv import
   $keys=array_intersect_key($line,$group);
   $new_line=array_merge($group,$keys);

   if (!empty($new_line) && $wpdb->update($table, $new_line, $where) === false) {
      return false;
   } else {
      return $group_id;
   }
}

function eme_add_update_person_from_backend($person_id=0) {
   $person=array();
   if (isset($_POST['lastname'])) $person['lastname'] = eme_sanitize_request($_POST['lastname']);
   if (isset($_POST['firstname'])) $person['firstname'] = eme_sanitize_request($_POST['firstname']);
   if (isset($_POST['email'])) $person['email'] = eme_sanitize_email($_POST['email']);
   if (isset($_POST['birthdate']) && eme_is_date($_POST['birthdate'])) $person['birthdate'] = eme_sanitize_request($_POST['birthdate']);
   if (isset($_POST['birthplace'])) $person['birthplace'] = eme_sanitize_request($_POST['birthplace']);
   if (isset($_POST['address1'])) $person['address1'] = eme_sanitize_request($_POST['address1']);
   if (isset($_POST['address2'])) $person['address2'] = eme_sanitize_request($_POST['address2']);
   if (isset($_POST['city'])) $person['city'] = eme_sanitize_request($_POST['city']);
   if (isset($_POST['zip'])) $person['zip'] = eme_sanitize_request($_POST['zip']);
   if (isset($_POST['state_code'])) $person['state_code'] = eme_sanitize_request($_POST['state_code']);
   if (isset($_POST['country_code'])) $person['country_code'] = eme_sanitize_request($_POST['country_code']);
   if (isset($_POST['phone'])) $person['phone'] = eme_sanitize_request($_POST['phone']);
   // the language POST var has a different name ('language', not 'lang') to avoid a conflict with qtranslate-xt that
   //    also checks for $_POST['lang'] and redirects to that lang, which is of course not the intention when editing a person
   if (isset($_POST['language'])) $person['lang'] = eme_sanitize_request($_POST['language']);
   if (isset($_POST['wp_id'])) $person['wp_id'] = intval($_POST['wp_id']);
   if (isset($_POST['massmail']))
	   $person['massmail'] = intval($_POST['massmail']);
   else
	   $person['massmail'] = 0;
   if (isset($_POST['newsletter']))
	   $person['newsletter'] = intval($_POST['newsletter']);
   else
	   $person['newsletter'] = 0;
   if (isset($_POST['bd_email'])) $person['bd_email'] = intval($_POST['bd_email']);
   if (isset($_POST['related_person_id'])) $person['related_person_id'] = intval($_POST['related_person_id']);
   if (isset($_POST['gdpr'])) {
	   $person['gdpr'] = intval($_POST['gdpr']);
	   if ($person['gdpr'])
		   $person['gdpr_date'] = current_time('mysql', false);
   } else {
	   $person['gdpr'] = 0;
   }
   if (isset($_POST['groups']))
	   $groups = $_POST['groups'];
   else
	   $groups=array();
   if (isset($_POST['properties'])) $person['properties'] = eme_sanitize_request($_POST['properties']);

   $upload_failures = '';
   if ($person_id) {
	   $updated_personid=eme_db_update_person($person_id,$person);
	   if ($updated_personid) {
		   eme_update_persongroups($updated_personid,$groups);
		   eme_store_people_answers($updated_personid,0,1);
		   $upload_failures = eme_upload_files($updated_personid,"people");
	   }
	   $res_id = $updated_personid;
   } else {
	   // check existing
	   $t_person=eme_get_person_by_name_and_email($person['lastname'],$person['firstname'],$person['email']);
	   if (!$t_person)
		   $t_person=eme_get_person_by_email_only($person['email']);
	   if ($t_person) {
		   $person_id = $t_person['person_id'];
		   $updated_personid=eme_db_update_person($person_id,$person);
		   if ($updated_personid) {
			   eme_update_persongroups($updated_personid,$groups);
			   eme_store_people_answers($updated_personid,0,1);
			   $upload_failures = eme_upload_files($updated_personid,"people");
		   }
		   $res_id = $updated_personid;
	   } else {
		   $person_id=eme_db_insert_person($person);
		   if ($person_id) {
			   eme_update_persongroups($person_id,$groups);
			   eme_store_people_answers($person_id,1,1);
			   $upload_failures = eme_upload_files($person_id,"people");
		   }
		   $res_id = $person_id;
	   }
   }
   $res = array(0=>$upload_failures,1=>$res_id);
   return $res;

}

function eme_add_update_group($group_id=0) {
   global $wpdb;
   $table = $wpdb->prefix.GROUPS_TBNAME;
   $group=array();
   if (isset($_POST['name'])) $group['name'] = eme_sanitize_request($_POST['name']);
   if (isset($_POST['description'])) $group['description'] = eme_sanitize_request($_POST['description']);
   $group['public'] = isset($_POST['public']) ? intval($_POST['public']) : 0;
   $search_terms = array();
   $search_fields=array('search_membershipids','search_memberstatus','search_person','search_groups','search_memberid','search_customfields','search_customfieldids');
   foreach ($search_fields as $search_field) {
	   if (isset($_POST[$search_field]))
		   $search_terms[$search_field]=eme_sanitize_request($_POST[$search_field]);
   }
   $group['search_terms']=serialize($search_terms);

   // let's check if the email is unique
   if (!eme_is_empty_string($_POST['email']) && eme_is_email($_POST['email'],1)) {
	$email = eme_sanitize_email($_POST['email']);
	if ($group_id)
		$sql = $wpdb->prepare("SELECT COUNT(group_id) from $table WHERE email=%s AND group_id<>%d",$email,$group_id);
	else
		$sql = $wpdb->prepare("SELECT COUNT(group_id) from $table WHERE email=%s",$email);
	$count = $wpdb->get_var($sql);
	if ($count>0)
		return false;
	// all ok, set the email
	$group['email']=$email;
   }

   if ($group_id) {
	   $res=eme_db_update_group($group_id,$group);
	   if ($res) {
		   if (isset($_POST['persons']))
			   $persons = $_POST['persons'];
		   else
			   $persons = array();
		   eme_update_grouppersons($group_id,$persons);
	   }
	   return $res;
   } else {
	   $group_id=eme_db_insert_group($group);
	   if ($group_id) {
		   if (isset($_POST['persons']))
			   $persons = $_POST['persons'];
		   else
			   $persons = array();
		   eme_update_grouppersons($group_id,$persons);
	   }
	   return $group_id;
   }
}

function eme_add_familymember_from_frontend($main_person_id,$familymember) {
   $person = array();
 
   // lang detection
   $lang = eme_detect_lang();

   $lastname=$familymember['lastname'];
   $firstname=$familymember['firstname'];
   if (isset($familymember['email']))
	   $email=$familymember['email'];
   elseif (!empty($_POST['email']))
	   $email=eme_sanitize_request($_POST['email']);
   else
	   $email="";
   if (!empty($email) && !eme_is_email($email,1)) {
	   return array(0=>0,1=>__('Invalid email adress','events-made-easy'));
   }
   // most fields are taken from the main family person
   $country_code = "";
   if (!empty($_POST['country_code'])) {
	   $country_code = eme_sanitize_request($_POST['country_code']);
	   $country_name = eme_get_country_name($country_code);
	   if (empty($country_name)) {
		   return array(0=>0,1=>__('Invalid country code','events-made-easy'));
	   }
   }
   $state_code = "";
   if (!empty($_POST['state_code'])) {
	   $state_code = eme_sanitize_request($_POST['state_code']);
	   $state_name = eme_get_state_name($state_code,$country_code,$lang);
	   if (empty($state_name)) {
		   return array(0=>0,1=>__('Invalid state code','events-made-easy'));
	   }
   }

   if (!empty($familymember['birthdate']) && eme_is_date($familymember['birthdate'])) $person['birthdate'] = eme_sanitize_request($familymember['birthdate']);
   if (!empty($familymember['birthplace'])) $person['birthplace'] = eme_sanitize_request($familymember['birthplace']);
   if (!empty($familymember['phone']))
	   $person['phone'] = eme_sanitize_request($familymember['phone']);
   elseif (!empty($_POST['phone']))
	   $person['phone'] = eme_sanitize_request($_POST['phone']);
   if (!empty($_POST['address1'])) $person['address1'] = eme_sanitize_request($_POST['address1']);
   if (!empty($_POST['address2'])) $person['address2'] = eme_sanitize_request($_POST['address2']);
   if (!empty($_POST['city'])) $person['city'] = eme_sanitize_request($_POST['city']);
   if (!empty($_POST['zip'])) $person['zip'] = eme_sanitize_request($_POST['zip']);
   if (isset($familymember['newsletter']))
	   $person['newsletter'] = intval($familymember['newsletter']);
   elseif (isset($_POST['newsletter']))
	   $person['newsletter'] = intval($_POST['newsletter']);
   if (isset($familymember['massmail']))
	   $person['massmail'] = intval($familymember['massmail']);
   elseif (isset($_POST['massmail']))
	   $person['massmail'] = intval($_POST['massmail']);
   if (isset($familymember['bd_email']))
	   $person['bd_email'] = intval($familymember['bd_email']);
   elseif (isset($_POST['bd_email']))
	   $person['bd_email'] = intval($_POST['bd_email']);
   if (isset($_POST['gdpr'])) {
	   $person['gdpr'] = intval($_POST['gdpr']);
	   $person['gdpr_date'] = current_time('mysql', false);
   }
   $person['lang'] = $lang;
   $person['state_code'] = $state_code;
   $person['country_code'] = $country_code;
   $person['related_person_id'] = $main_person_id;
   $person['lastname'] = eme_sanitize_request($lastname);
   $person['firstname'] = eme_sanitize_request($firstname);
   $person['email'] = $email;

   $t_person=eme_get_person_by_name_and_email($lastname,$firstname,$email);
   if (!$person)
	   $person=eme_get_person_by_email_only($email);
   // if we have a matching person, update that one. But make sure we"re not updating the main one (can happen if someone entered the main account details also as member)
   if ($t_person && $t_person['person_id'] != $main_person_id) {
	   $person_id=$t_person['person_id'];
	   $res = eme_db_update_person($person_id,$person);
	   if ($res)
		   eme_store_family_answers($person_id,$familymember);
   } else {
	   $person_id = eme_db_insert_person($person);
	   if ($person_id)
		   eme_store_family_answers($person_id,$familymember);
   }
   return $person_id;
}

function eme_add_update_person_from_form($person_id,$lastname='', $firstname='', $email='', $wp_id=0, $create_wp_user=0,$return_fake_person=0) {
   $person = array();

   if (!$return_fake_person && !empty($email) && !eme_is_email($email,1)) {
	   return array(0=>0,1=>__('Invalid email adress','events-made-easy'));
   }

   // lang detection
   if ($person_id) {
	   $person_being_updated=eme_get_person($person_id);
	   if (!$person_being_updated) {
		   return array(0=>0,1=>__('Error encountered while updating person','events-made-easy'));
	   }
	   // when a booking is done via the admin backend, take the existing language for that person 
	   if (!empty($person_being_updated['lang']) && eme_is_admin_request())
		   $lang=$person_being_updated['lang'];
	   else
		   $lang = eme_detect_lang();
   } else {
	   $lang = eme_detect_lang();
   }

   if ($create_wp_user>0 && !eme_is_admin_request() && !is_user_logged_in() && email_exists($email) ) {
	   return array(0=>0,1=>__('The email address belongs to an existing user. Please log in first before continuing to register with this email address.','events-made-easy'));
   }

   // check for correct country value
   // This to take autocomplete field values into account, or people just submitting too fast
   $country_code = "";
   if (!empty($_POST['country_code'])) {
	   $country_code = eme_sanitize_request($_POST['country_code']);
	   $country_name = eme_get_country_name($country_code);
	   if (empty($country_name)) {
		   return array(0=>0,1=>__('Invalid country code','events-made-easy'));
	   }
   }
   $state_code = "";
   if (!empty($_POST['state_code'])) {
	   $state_code = eme_sanitize_request($_POST['state_code']);
	   $state_name = eme_get_state_name($state_code,$country_code,$lang);
	   if (empty($state_name)) {
		   return array(0=>0,1=>__('Invalid state code','events-made-easy'));
	   }
   }

   if (isset($_POST['birthdate']) && eme_is_date($_POST['birthdate'])) $person['birthdate'] = eme_sanitize_request($_POST['birthdate']);
   if (isset($_POST['birthplace'])) $person['birthplace'] = eme_sanitize_request($_POST['birthplace']);
   if (!empty($_POST['address1'])) $person['address1'] = eme_sanitize_request($_POST['address1']);
   if (!empty($_POST['address2'])) $person['address2'] = eme_sanitize_request($_POST['address2']);
   if (!empty($_POST['city'])) $person['city'] = eme_sanitize_request($_POST['city']);
   if (!empty($_POST['zip'])) $person['zip'] = eme_sanitize_request($_POST['zip']);
   if (isset($_POST['massmail'])) $person['massmail'] = intval($_POST['massmail']);
   if (isset($_POST['newsletter'])) $person['newsletter'] = intval($_POST['newsletter']);
   if (isset($_POST['bd_email'])) $person['bd_email'] = intval($_POST['bd_email']);
   if (isset($_POST['gdpr'])) {
	   $person['gdpr'] = intval($_POST['gdpr']);
	   $person['gdpr_date'] = current_time('mysql', false);
   }
   if (!empty($_POST['phone'])) $person['phone'] = eme_sanitize_request($_POST['phone']);
   if (isset($_POST['properties'])) $person['properties'] = eme_sanitize_request($_POST['properties']);
   $person['state_code'] = $state_code;
   $person['country_code'] = $country_code;
   $person['lang'] = $lang;

   if ($return_fake_person) {
	   $person['person_id'] = -1;
	   $person['wp_id'] = eme_get_wpid_by_post();
           $person['lastname'] = eme_sanitize_request($_POST['lastname']);
           if (isset($_POST['firstname']))
		   $person['firstname'] = eme_sanitize_request($_POST['firstname']);
	   else
		   $person['firstname'] = '';
	   $person['email'] = eme_sanitize_email($_POST['email']);
	   return $person;
   } elseif (!$person_id) {
	   $wp_count=0;
	   if ($wp_id>0)
		   $wp_count=eme_count_persons_with_wp_id($wp_id);
	   $t_person=eme_get_person_by_name_and_email($lastname,$firstname,$email);
	   if (!$t_person) {
		   $t_person=eme_get_person_by_email_only($email);
		   // we found a person matching with email only, meaning empty lastname/firstname, so we update it
		   // this prevents people from updating their name/email with only a case-difference from the frontend
		   if ($t_person) {
			   $person['lastname'] = eme_sanitize_request($lastname);
			   $person['firstname'] = eme_sanitize_request($firstname);
		   }
	   }
	   if ($t_person) {
		   $person_id=$t_person['person_id'];
		   if ($wp_id>0 && $wp_count==0 && $t_person['wp_id']==0) {
			   $person['wp_id'] = intval($wp_id);
		   }

		   $updated_personid = eme_db_update_person($person_id,$person);
		   if ($updated_personid) {
			   eme_store_people_answers($updated_personid);
			   if (!empty($_POST['subscribe_groups'])) {
				   eme_add_persongroups($updated_personid,eme_sanitize_request($_POST['subscribe_groups']));
			   }
			   return array(0=>$person_id,1=>'');
		   } else {
			   return array(0=>0,1=>__('Error encountered while updating person','events-made-easy'));
		   }
	   } else {
		   $person['lastname'] = eme_sanitize_request($lastname);
		   $person['firstname'] = eme_sanitize_request($firstname);
		   $person['email'] = $email;
		   if ($wp_id>0 && $wp_count==0) {
			   $person['wp_id'] = intval($wp_id);
		   }
		   $person_id = eme_db_insert_person($person);
		   if ($person_id) {
			   eme_store_people_answers($person_id);
			   if (!empty($_POST['subscribe_groups'])) {
				   eme_add_persongroups($updated_personid,eme_sanitize_request($_POST['subscribe_groups']));
			   }
			   return array(0=>$person_id,1=>'');
		   } else {
			   return array(0=>0,1=>__('Error encountered while adding person','events-made-easy'));
		   }
	   }
   } else {
	   if (!eme_is_empty_string($_POST['lastname']))
		   $person['lastname'] = eme_sanitize_request($_POST['lastname']);
	   else
		   $person['lastname'] = $person_being_updated['lastname'];
	   if (!eme_is_empty_string($_POST['firstname']))
		   $person['firstname'] = eme_sanitize_request($_POST['firstname']);
	   else
		   $person['firstname'] = $person_being_updated['firstname'];
	   if (!eme_is_empty_string($_POST['email']))
		   $person['email'] = eme_sanitize_email($_POST['email']);
	   else
		   $person['email'] = $person_being_updated['email'];
	   if (eme_is_empty_string($person['email']) || !eme_is_email($person['email'],1)) {
		   return array(0=>0,1=>__('Invalid email adress','events-made-easy'));
	   }
	   // check for conflicts
	   $existing_personid=eme_get_person_by_name_and_email($person['lastname'],$person['firstname'],$person['email']);
	   if ($existing_personid && $existing_personid['person_id']!=$person_id) {
		   return array(0=>0,1=>__('Conflict with info from other person, please use another lastname, firstname or email','events-made-easy'));
	   }
	   // when updating a person using the person id, we won't change the wp_id (that should happen in the admin interface for the person)
	   $person['wp_id'] = $person_being_updated['wp_id'];
	   $updated_personid = eme_db_update_person($person_id,$person);
	   if ($updated_personid) {
		   eme_store_people_answers($updated_personid);
		   if (!empty($_POST['subscribe_groups'])) {
			   eme_add_persongroups($updated_personid,eme_sanitize_request($_POST['subscribe_groups']));
		   }
		   return array(0=>$person_id,1=>'');
	   } else {
		   return array(0=>0,1=>__('Error encountered while updating person','events-made-easy'));
	   }
   }
}

function eme_user_profile($user) {
   // define a simple template
   $template="#_STARTDATE #_STARTTIME: #_EVENTNAME (#_RESPSEATS ".__('seats','events-made-easy')."). #_CANCEL_LINK<br />";
   $person_id = eme_get_personid_by_wpid($user->ID);
   $memberships=eme_get_activemembership_names_by_personid($person_id);
   if (!empty($memberships)) {
	   $memberships_list=join(", ",$memberships);
   } else {
	   $memberships_list="";
   }
   $memberships_list = apply_filters('eme_general', $memberships_list);
   ?>
   <h3><?php _e('Events Made Easy settings', 'events-made-easy')?></h3>
   <table class='form-table'>
      <tr>
         <th><label for="eme_phone"><?php _e('Phone number','events-made-easy');?></label></th>
         <td><input type="text" name="eme_phone" id="eme_phone" value="<?php echo esc_attr( eme_get_user_phone($user->ID) ); ?>" class="regular-text" /> <br />
         <?php _e('The phone number used by Events Made Easy when the user is indicated as the contact person for an event.','events-made-easy');?></td>
      </tr>
      <tr>
         <th><label for="eme_bookings"><?php _e('Bookings made for future events','events-made-easy');?></label></th>
	 <td><?php echo eme_get_bookings_list_for_wp_id($user->ID,"future",$template); ?>
      </tr>
      <tr>
         <th><label for="eme_memberships"><?php _e('Active memberships','events-made-easy');?></label></th>
	 <td><?php echo $memberships_list; ?>
      </tr>
   </table>
   <?php
}

function eme_update_user_profile($wp_id) {
   if (!eme_is_empty_string($_POST['eme_phone'])) {
      eme_update_user_phone($wp_id,$_POST['eme_phone']);
   }
}

function eme_after_profile_update( $wp_id, $old_user_data ) {
  $user_info = get_userdata($wp_id); 
  $lastname  = $user_info->user_lastname;
  if (empty($lastname))
	  $lastname = $user_info->display_name;
  $firstname = $user_info->user_firstname;
  $email = $user_info->user_email;
  $phone = eme_get_user_phone($wp_id);
  $person = eme_get_person_by_wp_id($wp_id,0);
  if (!empty($person)) {
	  if (!empty($lastname))
		  $person['lastname']=$lastname;
	  if (!empty($firstname))
		  $person['firstname']=$firstname;
	  $person['email']=$email;
	  $person['phone']=$phone;
	  eme_db_update_person($person['person_id'],$person);
  }
}

function eme_update_person_wp_id($person_id,$wp_id) {
   global $wpdb; 
   $table = $wpdb->prefix.PEOPLE_TBNAME;
   // the function wp_dropdown_users uses -1 for an "empty" wp_id, but our db only allows >=0, so lets rectify that
   if ($wp_id<0) $wp_id=0;
   // first we check if another person has the wp_id and if that person matches in lastname/firstname/email with the wp user, then moving is not allowed
   if ($wp_id) {
	   $user_info = get_userdata($wp_id); 
	   if (!empty($user_info)) {
		   $lastname  = $user_info->user_lastname;
		   if (empty($lastname))
			   $lastname=$user_info->display_name;
		   $firstname = $user_info->user_firstname;
		   $email = $user_info->user_email;

		   // if there is another person matching the wp user info, don't update the current wp id
		   $person = eme_get_person_by_name_and_email($lastname,$firstname,$email);
		   if (!empty($person) && ($person['person_id']!=$person_id && $person['lastname']==$lastname && $person['firstname']==$firstname && $person['email']==$email)) {
			   return false;
		   }

		   // now unset the existing link if present (should not be, but one never knows)
		   $sql = $wpdb->prepare("UPDATE $table SET wp_id = 0 WHERE wp_id = %d AND person_id <> %d", $wp_id, $person_id);
		   $wpdb->query($sql);

		   // we'll set the wp_id and other info from wp too
		   $where = array();
		   $where['person_id'] = intval($person_id);
		   $person_update=compact('lastname','firstname','email','wp_id');
		   return $wpdb->update($table, $person_update, $where);
	   }
   } else {
	   $sql = $wpdb->prepare("UPDATE $table SET wp_id = 0 WHERE person_id = %d", $person_id);
	   return $wpdb->query($sql);
   }
}

function eme_update_email_gdpr($email) {
   global $wpdb; 
   $table = $wpdb->prefix.PEOPLE_TBNAME;
   $gdpr_date = current_time('mysql', false);
   $sql = $wpdb->prepare("UPDATE $table SET gdpr = 1, gdpr_date=%s WHERE email = %s", $gdpr_date, $email);
   $wpdb->query($sql);
}

function eme_update_people_gdpr($person_ids,$gdpr=1) {
	global $wpdb;
	$table = $wpdb->prefix.PEOPLE_TBNAME;
	$gdpr_date = current_time('mysql', false);
	$sql = "UPDATE $table SET gdpr=$gdpr, gdpr_date='$gdpr_date' WHERE person_id IN ($person_ids)";
	$wpdb->query($sql);
}

function eme_update_email_massmail($email,$massmail) {
   global $wpdb; 
   $table = $wpdb->prefix.PEOPLE_TBNAME;
   $sql = $wpdb->prepare("UPDATE $table SET massmail = %d WHERE email = %s", $massmail, $email);
   $wpdb->query($sql);
}

function eme_update_people_massmail($person_ids,$massmail=1) {
	global $wpdb;
	$table = $wpdb->prefix.PEOPLE_TBNAME;
	$sql = "UPDATE $table SET massmail=$massmail WHERE person_id IN ($person_ids)";
	$wpdb->query($sql);
}

function eme_update_people_bdemail($person_ids,$bd_email=1) {
	global $wpdb;
	$table = $wpdb->prefix.PEOPLE_TBNAME;
	$sql = "UPDATE $table SET bd_email=$bd_email WHERE person_id IN ($person_ids)";
	$wpdb->query($sql);
}

function eme_update_people_language($person_ids,$lang) {
	global $wpdb;
	$table = $wpdb->prefix.PEOPLE_TBNAME;
	$sql = $wpdb->prepare("UPDATE $table SET lang=%s WHERE person_id IN ($person_ids)",$lang);
	$wpdb->query($sql);
}

function eme_get_indexed_users() {
   global $wpdb;
   $sql = "SELECT display_name, ID FROM $wpdb->users";
   $users = $wpdb->get_results($sql, ARRAY_A);
   $indexed_users = array();
   foreach($users as $user) 
      $indexed_users[$user['ID']] = $user['display_name'];
   return $indexed_users;
}

function eme_get_wp_users($search,$offset=0,$pagesize=0) {
	$meta_query = array(
			'relation' => 'OR',
			array(
				'key'     => 'nickname',
				'value'   => $search,
				'compare' => 'LIKE'
			     ),
			array(
				'key'     => 'first_name',
				'value'   => $search,
				'compare' => 'LIKE'
			     ),
			array(
				'key'     => 'last_name',
				'value'   => $search,
				'compare' => 'LIKE'
			     )
			);
	$args = array(
			'meta_query'   =>$meta_query,
			'orderby'      => 'ID',
			'order'        => 'ASC',
			'count_total'  => true,
			'fields'       => array('ID'),
		     );
	if ($pagesize>0) {
		$args['offset']=$offset;
		$args['number']=$pagesize;
	}
	// while get_users works, it doesn't give the total for paged results, so we need WP_User_Query directly
	//$users = get_users($args);
	$user_query = new WP_User_Query($args);
	$users = $user_query->get_results(); // array of WP_User objects, like get_users
	$total = $user_query->get_total(); // int, total number of users (not just the first page)
	return array($users,$total);
}

add_action( 'wp_ajax_eme_subscribe', 'eme_subscribe_ajax' );
add_action( 'wp_ajax_nopriv_eme_subscribe', 'eme_subscribe_ajax' );
function eme_subscribe_ajax() {
      // check for spammers as early as possible
      if (get_option('eme_honeypot_for_forms')) {
         if (!isset($_POST['honeypot_check']) || !empty($_POST['honeypot_check'])) {
              $message = __("Bot detected. If you believe you've received this message in error please contact the site owner.",'events-made-easy');
	      echo json_encode(array('Result'=>'NOK','htmlmessage'=>$message));
	      wp_die();
         }
      }
      if (!isset($_POST['eme_frontend_nonce']) || !wp_verify_nonce($_POST['eme_frontend_nonce'],"eme_frontend")) {
              $message = __("Form tampering detected. If you believe you've received this message in error please contact the site owner.",'events-made-easy');
	      echo json_encode(array('Result'=>'NOK','htmlmessage'=>$message));
	      wp_die();
      }

      if (get_option('eme_hcaptcha_for_forms')) {
         if (!eme_check_hcaptcha()) {
              $message = __("Please check the hCaptcha box",'events-made-easy');
	      echo json_encode(array('Result'=>'NOK','htmlmessage'=>$message));
	      wp_die();
         }
      }
      if (get_option('eme_recaptcha_for_forms')) {
         if (!eme_check_recaptcha()) {
              $message = __("Please check the Google reCAPTCHA box",'events-made-easy');
	      echo json_encode(array('Result'=>'NOK','htmlmessage'=>$message));
	      wp_die();
         }
      }
      if (get_option('eme_captcha_for_forms')) {
         if (!eme_check_captcha(1)) {
              $message = __("You entered an incorrect code",'events-made-easy');
	      echo json_encode(array('Result'=>'NOK','htmlmessage'=>$message));
	      wp_die();
         }
      }

      $eme_lastname = isset($_POST['lastname']) ? eme_sanitize_request($_POST['lastname']) : '';
      $eme_firstname = isset($_POST['firstname']) ? eme_sanitize_request($_POST['firstname']) : '';
      $eme_email=eme_sanitize_email($_POST['email']);
      if (eme_is_email($eme_email,1)) {
	      if (isset($_POST['email_groups']) && eme_array_integers($_POST['email_groups'])) {
		      $eme_email_groups=join(',',$_POST['email_groups']);
	      } elseif (isset($_POST['email_group']) && is_numeric($_POST['email_group'])) {
		      $eme_email_groups=$_POST['email_group'];
	      } else {
		      $eme_email_groups="";
	      }
	      eme_sub_send_mail($eme_lastname,$eme_firstname,$eme_email,$eme_email_groups);
	      $message = __("A request for confirmation has been sent to the given email address.",'events-made-easy');
	      echo json_encode(array('Result'=>'OK','htmlmessage'=>$message));
      } else {
	      $message = __('Invalid email adress','events-made-easy');
	      echo json_encode(array('Result'=>'NOK','htmlmessage'=>$message));
      }
      wp_die();
}

function eme_subform_shortcode($atts) {
   global $eme_plugin_url;
   extract ( shortcode_atts ( array ('template_id' => 0), $atts ) );
   if ($template_id) {
	   $format=eme_get_template_format($template_id);
   } else {
	   $format="<p>".__('If you want to subscribe to future mailings, please do so by entering your email here.', 'events-made-easy')."<p/>#_EMAIL";
	   $tmp_groups=eme_get_public_groupids();
	   if (!empty($tmp_groups))
		   $format.="<p>".__("Please select the groups you wish to subscribe to.", 'events-made-easy').'</p> #_MAILGROUPS';
   }

   usleep(2);
   $form_id=uniqid();
   $form_html = "<noscript><div class='eme-noscriptmsg'>".__('Javascript is required for this form to work properly','events-made-easy')."</div></noscript>
   <div id='eme-subscribe-message-ok-$form_id' class='eme-message-success eme-subscribe-message eme-subscribe-message-success eme-hidden'></div><div id='eme-subscribe-message-error-$form_id' class='eme-message-error eme-subscribe-message eme-subscribe-message-error eme-hidden'></div><div id='div_eme-subscribe-form-$form_id' style='display: none' class='eme-showifjs'><form id='$form_id' name='eme-subscribe-form' method='post' action='#'><span id='honeypot_check'><input type='text' name='honeypot_check' value='' autocomplete='off' /></span><img id='loading_gif' alt='loading' src='".$eme_plugin_url."images/spinner.gif' style='display:none;'><br />";
   $form_html.=wp_nonce_field('eme_frontend','eme_frontend_nonce',false,false);
   $form_html.=eme_replace_subscribeform_placeholders($format);
   $form_html.="</form></div>";

   return $form_html;
}

add_action( 'wp_ajax_eme_unsubscribe', 'eme_unsubscribe_ajax' );
add_action( 'wp_ajax_nopriv_eme_unsubscribe', 'eme_unsubscribe_ajax' );
function eme_unsubscribe_ajax() {
      if (get_option('eme_honeypot_for_forms')) {
         if (!isset($_POST['honeypot_check']) || !empty($_POST['honeypot_check'])) {
              $message = __("Bot detected. If you believe you've received this message in error please contact the site owner.",'events-made-easy');
	      echo json_encode(array('Result'=>'NOK','htmlmessage'=>$message));
	      wp_die();
         }
      }
      // check for spammers as early as possible
      if (!isset($_POST['eme_frontend_nonce']) || !wp_verify_nonce($_POST['eme_frontend_nonce'],"eme_frontend")) {
              $message = __("Form tampering detected. If you believe you've received this message in error please contact the site owner.",'events-made-easy');
	      echo json_encode(array('Result'=>'NOK','htmlmessage'=>$message));
	      wp_die();
      }
      if (get_option('eme_hcaptcha_for_forms')) {
         if (!eme_check_hcaptcha()) {
              $message = __("Please check the hCaptcha box",'events-made-easy');
	      echo json_encode(array('Result'=>'NOK','htmlmessage'=>$message));
	      wp_die();
         }
      }
      if (get_option('eme_recaptcha_for_forms')) {
         if (!eme_check_recaptcha()) {
              $message = __("Please check the Google reCAPTCHA box",'events-made-easy');
	      echo json_encode(array('Result'=>'NOK','htmlmessage'=>$message));
	      wp_die();
         }
      }
      if (get_option('eme_captcha_for_forms')) {
         if (!eme_check_captcha(1)) {
              $message = __("You entered an incorrect code",'events-made-easy');
	      echo json_encode(array('Result'=>'NOK','htmlmessage'=>$message));
	      wp_die();
         }
      }

      $eme_email=eme_sanitize_email($_POST['email']);
      if (eme_is_email($eme_email,1)) {
	      if (isset($_POST['email_groups']) && eme_array_integers($_POST['email_groups'])) {
		      $eme_email_groups=join(',',$_POST['email_groups']);
	      } elseif (isset($_POST['email_group']) && is_numeric($_POST['email_group'])) {
		      $eme_email_groups=$_POST['email_group'];
	      } else {
		      $eme_email_groups="";
	      }
	      eme_unsub_send_mail($eme_email,$eme_email_groups);
	      $message = __("A request for confirmation has been sent to the given email address.",'events-made-easy');
	      echo json_encode(array('Result'=>'OK','htmlmessage'=>$message));
      } else {
	      $message = __('Invalid email adress','events-made-easy');
	      echo json_encode(array('Result'=>'NOK','htmlmessage'=>$message));
      }
      wp_die();
}

function eme_unsubform_shortcode($atts) {
   global $eme_plugin_url;
   extract ( shortcode_atts ( array ('template_id' => 0), $atts ) );
   if ($template_id) {
	   $format=eme_get_template_format($template_id);
   } else {
	   $format="<p>".__('If you want to unsubscribe from future mailings, please do so by entering your email here.', 'events-made-easy')."<p/>#_EMAIL";
	   $tmp_groups=eme_get_public_groups();
	   if (!empty($tmp_groups) || wp_next_scheduled('eme_cron_send_new_events'))
		   $format.="<p>".__("Please select the groups you wish to unsubscribe from.", 'events-made-easy').'</p> #_MAILGROUPS';
   }

   usleep(2);
   $form_id=uniqid();
   $form_html = "<noscript><div class='eme-noscriptmsg'>".__('Javascript is required for this form to work properly','events-made-easy')."</div></noscript>
   <div id='eme-unsubscribe-message-ok-$form_id' class='eme-message-success eme-unsubscribe-message eme-unsubscribe-message-success eme-hidden'></div><div id='eme-unsubscribe-message-error-$form_id' class='eme-message-error eme-unsubscribe-message eme-unsubscribe-message-error eme-hidden'></div><div id='div_eme-unsubscribe-form-$form_id' style='display: none' class='eme-showifjs'><form id='$form_id' name='eme-unsubscribe-form' method='post' action='#'><span id='honeypot_check'><input type='text' name='honeypot_check' value='' autocomplete='off' /></span><img id='loading_gif' alt='loading' src='".$eme_plugin_url."images/spinner.gif' style='display:none;'><br />";
   $form_html.=wp_nonce_field('eme_frontend','eme_frontend_nonce',false,false);
   $unsubscribe=1;
   $form_html.=eme_replace_subscribeform_placeholders($format,$unsubscribe);
   $form_html.="</form></div>";

   return $form_html;
}

function eme_sub_send_mail($lastname,$firstname,$email,$groups) {
	$contact_email = get_option('eme_mail_sender_address');
        $contact_name = get_option('eme_mail_sender_name');
        if (empty($contact_email)) {
                $contact = eme_get_contact(0);
                $contact_email = $contact->user_email;
                $contact_name = $contact->display_name;
        }
	$sub_link=eme_sub_confirm_url($lastname,$firstname,$email,$groups);
	$sub_subject=get_option('eme_sub_subject');
	$sub_body=eme_translate(get_option('eme_sub_body'));
	$sub_body=str_replace('#_SUB_CONFIRM_URL', $sub_link ,$sub_body );
	$sub_body=str_replace('#_LASTNAME', $lastname ,$sub_body );
	$sub_body=str_replace('#_FIRSTNAME', $firstname ,$sub_body );
	$sub_body=str_replace('#_EMAIL', $email ,$sub_body );
	$full_name=eme_format_full_name($firstname,$lastname);
	eme_queue_fastmail($sub_subject,$sub_body, $email, $full_name, $contact_email, $contact_name);
}

function eme_unsub_send_mail($email,$groupids) {
	// find persons with matching email in the mentioned groups
	$person_id=eme_get_person_in_groups($email,$groupids);
        if (!empty($person_id)) {
		$contact_email = get_option('eme_mail_sender_address');
		$contact_name = get_option('eme_mail_sender_name');
		if (empty($contact_email)) {
			$contact = eme_get_contact(0);
			$contact_email = $contact->user_email;
			$contact_name = $contact->display_name;
		}
		$unsub_link=eme_unsub_confirm_url($email,$groupids);
		$unsub_subject=get_option('eme_unsub_subject');
		$unsub_body=eme_translate(get_option('eme_unsub_body'));
		$unsub_body=str_replace('#_UNSUB_CONFIRM_URL', $unsub_link ,$unsub_body );
		$person = eme_get_person($person_id);
		$unsub_body=eme_replace_people_placeholders($unsub_body,$person);
		$name='';
		if (!empty($person['lastname']))
			$name=$person['lastname'];
		if (!empty($person['firstname']))
			$name.=' '.$person['firstname'];
		eme_queue_fastmail($unsub_subject,$unsub_body, $email, $name, $contact_email, $contact_name);
	}
}

function eme_sub_do($lastname,$firstname,$email,$group_ids) {
	$person = eme_get_person_by_name_and_email($lastname,$firstname,$email);
	$res=false;
	if (!$person)
		$person=eme_get_person_by_email_only($email);
	if (empty($group_ids))
		$group_ids=eme_get_public_groupids();
	if (!empty($person)) {
		$res=eme_add_persongroups($person['person_id'],$group_ids,1);
	} else {
		$wp_id=0;
		// if the user is logged in, we overwrite the lastname/firstname with that info
		if (is_user_logged_in()) {
			$wp_id=get_current_user_id();
			$user_info = get_userdata($wp_id);
			$lastname  = $user_info->user_lastname;
			if (empty($lastname))
				$lastname=$user_info->display_name;
			$firstname = $user_info->user_firstname;
		}
		$res2=eme_add_update_person_from_form(0,$lastname,$firstname,$email,$wp_id);
		$person_id=$res2[0];
		if ($person_id) {
			$res=eme_add_persongroups($person_id,$group_ids,1);
		}
	}
	if ($res)
		eme_update_email_massmail($email,1);
	return $res;
}

function eme_unsub_do($email,$group_ids) {
	if (eme_count_persons_by_email($email)>0) {
		if (empty($group_ids)) {
			$group_ids=eme_get_public_groupids();
			eme_update_email_massmail($email,0);
		}
		if (!empty($group_ids)) {
			foreach ($group_ids as $group_id) {
				// -1 is the newsletter
				if ($group_id==-1) {
					eme_remove_email_from_newsletter($email);
				} else {
					$group=eme_get_group($group_id);
					if (!empty($group) && isset($group['public']) && $group['public']) {
						eme_delete_emailfromgroup($email,$group_id);
					}
				}
			}
		}
	}
}

function eme_get_person_answers($person_id) {
   global $wpdb;
   $answers_table = $wpdb->prefix.ANSWERS_TBNAME;
   $answers = wp_cache_get("eme_person_answers $person_id");
   if ($answers === false) {
	   $sql = $wpdb->prepare("SELECT * FROM $answers_table WHERE related_id=%d AND type='person'",$person_id);
	   $answers = $wpdb->get_results($sql, ARRAY_A);
	   wp_cache_set("eme_person_answers $person_id", $answers, '', 10);
   }
   return $answers;
}

function eme_delete_person_groups($person_ids) {
   global $wpdb;
   $usergroups_table = $wpdb->prefix.USERGROUPS_TBNAME;
   $sql = "DELETE FROM $usergroups_table WHERE person_id IN ($person_ids)";
   $wpdb->query($sql);
}

function eme_delete_person_answers($person_ids) {
   global $wpdb;
   $answers_table = $wpdb->prefix.ANSWERS_TBNAME;
   $sql = "DELETE FROM $answers_table WHERE related_id IN ($person_ids) AND type='person'";
   $wpdb->query($sql);
}

function eme_delete_person_memberships($person_ids) {
   global $wpdb;
   $members_table = $wpdb->prefix.MEMBERS_TBNAME;
   $answers_table = $wpdb->prefix.ANSWERS_TBNAME;
   $sql = "DELETE FROM $answers_table WHERE type='member' AND related_id IN (SELECT member_id FROM $members_table WHERE person_id IN ($person_ids))";
   $wpdb->query($sql);
   $sql = "DELETE FROM $members_table WHERE person_id IN ($person_ids)";
   $wpdb->query($sql);
}

function eme_people_answers($person_id,$new_person=0) {
	return eme_store_people_answers($person_id,$new_person);
}
function eme_store_people_answers($person_id,$new_person=0,$backend=0) {
   $all_answers=array();
   if ($person_id>0) {
	   $all_answers=eme_get_person_answers($person_id);
   }

   $answer_ids_seen=array();
   $formfield_ids_seen=array();
   // for a new person the POST fields have key 0, since the person_id wasn't known yet 
   if ($new_person)
	   $field_person_id=0;
   else
	   $field_person_id=$person_id;
   if (isset($_POST['dynamic_personfields'][$field_person_id])) {
           foreach($_POST['dynamic_personfields'][$field_person_id] as $key =>$value) {
                   if (preg_match('/^FIELD(\d+)$/', $key, $matches)) {
                           $field_id = intval($matches[1]);
                           $formfield = eme_get_formfield($field_id);
                           if (!empty($formfield) && $formfield['field_purpose']=='people') {
				   $formfield_ids_seen[]=$field_id;
                                   // for multivalue fields like checkbox, the value is in fact an array
                                   // to store it, we make it a simple "multi" string using eme_convert_array2multi, so later on when we need to parse the values
                                   // (when editing a booking), we can re-convert it to an array with eme_convert_multi2array (see eme_formfields.php)
                                   if (is_array($value)) $value=eme_convert_array2multi($value);
                                   if ($formfield['field_type']=='textarea')
                                           $value=eme_sanitize_textarea($value);
				   elseif ($formfield['field_type']=='time_js')
					   $value=eme_convert_localized_time($formfield['field_attributes'],eme_sanitize_request($value));
                                   else
                                           $value=eme_sanitize_request($value);
				   $answer_id=eme_get_answerid($all_answers,$person_id,'person',$field_id);
				   if ($answer_id) {
					   eme_update_answer($answer_id,$value);
				   } else {
					   $answer_id=eme_insert_answer('person',$person_id,$field_id,$value);
				   }
				   $answer_ids_seen[]=$answer_id;
                           }
                   }
           }
   }

   // if via frontend-form: delete old answer_ids, but only for those fields we want updated/added. We don't touch other field answers, so we don't lose data
   // if via backend: delete old answer_ids unseen
   if ($person_id>0) {
	   foreach ($all_answers as $answer) {
		   if (!in_array($answer['answer_id'],$answer_ids_seen) && ($backend || in_array($answer['field_id'],$formfield_ids_seen)) && $answer['type']=='person' && $answer['related_id']==$person_id) {
			   eme_delete_answer($answer['answer_id']);
		   }
	   }
   }
}

function eme_store_family_answers($person_id,$familymember) {
   $all_answers=array();
   $all_answers=eme_get_person_answers($person_id);

   $answer_ids_seen=array();
   $formfield_ids_seen=array();
   foreach($familymember as $key =>$value) {
	   if (preg_match('/^FIELD(\d+)$/', $key, $matches)) {
		   $field_id = intval($matches[1]);
		   $formfield = eme_get_formfield($field_id);
		   if (!empty($formfield) && $formfield['field_purpose']=='people') {
			   $formfield_ids_seen[]=$field_id;
			   // for multivalue fields like checkbox, the value is in fact an array
			   // to store it, we make it a simple "multi" string using eme_convert_array2multi, so later on when we need to parse the values
			   // (when editing a booking), we can re-convert it to an array with eme_convert_multi2array (see eme_formfields.php)
			   if (is_array($value)) $value=eme_convert_array2multi($value);
			   if ($formfield['field_type']=='textarea')
				   $value=eme_sanitize_textarea($value);
			   elseif ($formfield['field_type']=='time_js')
				   $value=eme_convert_localized_time($formfield['field_attributes'],eme_sanitize_request($value));
			   else
				   $value=eme_sanitize_request($value);
			   $answer_id=eme_get_answerid($all_answers,$person_id,'person',$field_id);
			   if ($answer_id) {
				   eme_update_answer($answer_id,$value);
			   } else {
				   $answer_id=eme_insert_answer('person',$person_id,$field_id,$value);
			   }
			   $answer_ids_seen[]=$answer_id;
		   }
	   }
   }

   // delete old answer_ids, but only for those fields we want updated/added. We don't touch other field answers, so we don't lose data
   foreach ($all_answers as $answer) {
	   if (!in_array($answer['answer_id'],$answer_ids_seen) && in_array($answer['field_id'],$formfield_ids_seen) && $person_id>0 && $answer['type']=='person' && $answer['related_id']==$person_id) {
		   eme_delete_answer($answer['answer_id']);
	   }
   }
}

function eme_people_autocomplete_ajax($no_wp_die=0,$wp_membership_required=0) {
   $return = array();
   $q = '';
   if (isset($_REQUEST['lastname']))
      $q = strtolower(eme_sanitize_request($_REQUEST['lastname']));
   elseif (isset($_REQUEST['task_lastname']))
      $q = strtolower(eme_sanitize_request($_REQUEST['task_lastname']));
   elseif (isset($_REQUEST['q']))
      $q = strtolower(eme_sanitize_request($_REQUEST['q']));

   if (isset($_REQUEST['exclude_personids']))
      $exclude_personids = eme_sanitize_request($_REQUEST['exclude_personids']);
   else
      $exclude_personids = '';
   // verify $exclude_personids some more
   $exclude_personids_arr=explode(',',$exclude_personids);
   if (!eme_array_integers($exclude_personids_arr))
	   $$exclude_personids = '';

   header("Content-type: application/json; charset=utf-8");
   if (empty($q)) {
      echo json_encode($return);
      if (!$no_wp_die)
         wp_die();
      return;
   }

   $search_tables=get_option('eme_autocomplete_sources');
   if (isset($_REQUEST['eme_searchlimit']) && $_REQUEST['eme_searchlimit']=="people") {
      $search_tables="people";
   }
   if ($wp_membership_required) {
      $search_tables="wp_users";
   }

   if ($search_tables=='people' || $search_tables=='both') {
	   $search="(lastname LIKE '%".esc_sql($q)."%' OR firstname LIKE '%".esc_sql($q)."%')";
	   if (!empty($exclude_personids))
		   $search .= " AND person_id NOT IN ($exclude_personids)";
	   $persons = eme_get_persons('',$search);
	   foreach($persons as $item) {
		   $record = array();
		   $record['lastname']  = eme_esc_html($item['lastname']); 
		   $record['firstname'] = eme_esc_html($item['firstname']); 
		   $record['address1']  = eme_esc_html($item['address1']); 
		   $record['address2']  = eme_esc_html($item['address2']); 
		   $record['city']      = eme_esc_html($item['city']); 
		   $record['zip']       = eme_esc_html($item['zip']); 
		   $record['state']     = eme_esc_html(eme_get_state_name($item['state_code'],$item['country_code'])); 
		   $record['country']   = eme_esc_html(eme_get_country_name($item['country_code'])); 
		   $record['email']     = eme_esc_html($item['email']);
		   $record['phone']     = eme_esc_html($item['phone']); 
		   $record['person_id'] = intval($item['person_id']); 
		   $record['wp_id']     = intval($item['wp_id']); 
		   $record['massmail']  = intval($item['massmail']); 
		   $record['gdpr']      = intval($item['gdpr']); 
		   $return[]  = $record;
	   }
   }
   if ($search_tables=='wp_users' || $search_tables=='both') {
	   list($persons,$total) = eme_get_wp_users($q);
	   foreach($persons as $item) {
		   $record = array();
		   $user_info = get_userdata($item->ID);
		   $phone = eme_esc_html(eme_get_user_phone($item->ID));
		   $record['lastname']  = eme_esc_html($user_info->user_lastname);
		   if (empty($record['lastname']))
			   $record['lastname'] = eme_esc_html($user_info->display_name);
		   $record['firstname'] = eme_esc_html($user_info->user_firstname);
		   $record['email']     = eme_esc_html($user_info->user_email);
		   $record['address1']  = ''; 
		   $record['address2']  = ''; 
		   $record['city']      = ''; 
		   $record['zip']       = ''; 
		   $record['state']     = ''; 
		   $record['country']   = ''; 
		   $record['phone']     = eme_esc_html($phone); 
		   $record['wp_id']     = intval($item->ID); 
		   $record['massmail']  = 1;
		   $record['gdpr']  = 1;
		   $record['person_id'] = 0;
		   $return[]  = $record;
	   }
   }

   echo json_encode($return);
   if (!$no_wp_die)
      wp_die();
}

add_action( 'wp_ajax_eme_autocomplete_people', 'eme_people_autocomplete_ajax' );
add_action( 'wp_ajax_eme_people_select2', 'eme_ajax_people_select2' );
add_action( 'wp_ajax_eme_people_list', 'eme_ajax_people_list' );
add_action( 'wp_ajax_eme_groups_list', 'eme_ajax_groups_list' );
add_action( 'wp_ajax_eme_manage_people', 'eme_ajax_manage_people' );
add_action( 'wp_ajax_eme_manage_groups', 'eme_ajax_manage_groups' );
add_action( 'wp_ajax_eme_store_people_query', 'eme_ajax_store_people_query' );

function eme_ajax_people_list($dynamic_groupname='') {
   global $wpdb, $eme_timezone;

   if (!current_user_can( get_option('eme_cap_list_people'))) {
	   $ajaxResult['Result'] = "Error";
	   $ajaxResult['Message'] = __('Access denied!','events-made-easy');
	   print json_encode($ajaxResult);
	   wp_die();
   }

   if (!empty($dynamic_groupname)) {
	   $table = $wpdb->prefix.GROUPS_TBNAME;
	   $group['type']='dynamic_people';
	   $group['name']=$dynamic_groupname.' '.__('(Dynamic)','events-made-easy');
	   $search_terms=array();
	   // the same as in add_update_group
	   $search_fields=array('search_membershipids','search_memberstatus','search_person','search_groups','search_memberid','search_customfields','search_customfieldids');
           foreach ($search_fields as $search_field) {
                   if (isset($_POST[$search_field]))
                           $search_terms[$search_field]=eme_sanitize_request($_POST[$search_field]);
           }
           $group['search_terms']=serialize($search_terms);
           return $wpdb->insert ( $table, $group );
   }

   $formfields=eme_get_formfields('','people');

   $jTableResult = array();
   $start= (isset($_REQUEST['jtStartIndex'])) ? intval($_REQUEST['jtStartIndex']) : 0;
   $pagesize= (isset($_REQUEST['jtPageSize'])) ? intval($_REQUEST['jtPageSize']) : 10;
   $sorting = (isset($_REQUEST['jtSorting'])) ? 'ORDER BY '.esc_sql($_REQUEST['jtSorting']) : '';
   $count_sql=eme_get_sql_people_searchfields($_POST,$start,$pagesize,$sorting,1);
   $sql=eme_get_sql_people_searchfields($_POST,$start,$pagesize,$sorting);
   $recordCount = $wpdb->get_var($count_sql);
   $rows=$wpdb->get_results($sql,ARRAY_A);
   $wp_users=eme_get_indexed_users();
   $records=array();
   foreach ($rows as $item) {
         $record = array();
	 if (empty($item['lastname'])) $item['lastname']=__('No surname','events-made-easy');
         $record['people.person_id']= $item['person_id'];
	 if ($item['related_person_id']) {
		 $related_person=eme_get_person($item['related_person_id']);
		 $record['people.related_to']= "<a href='".admin_url("admin.php?page=eme-people&amp;eme_admin_action=edit_person&amp;person_id=".$item['related_person_id'])."' title='".__('Edit person','events-made-easy')."'>".eme_esc_html($related_person['lastname'].' '.$related_person['firstname'])."</a>";
		 $familytext=__('(family member)','events-made-easy');
	 } else {
		 $record['people.related_to']= '';
		 $familytext='';
	 }

	 //$owner_user_info = get_userdata($item['wp_id']);
         //$record['people.wp_id'] = eme_esc_html($owner_user_info->display_name);
	 if ($item['wp_id'] && isset($wp_users[$item['wp_id']]))
		 $record['people.wp_user']= eme_esc_html($wp_users[$item['wp_id']]); 
	 else
		 $record['people.wp_user']= ''; 
         $record['people.lastname'] = "<a href='".admin_url("admin.php?page=eme-people&amp;eme_admin_action=edit_person&amp;person_id=".$item['person_id'])."' title='".__('Edit person','events-made-easy')."'>".eme_esc_html($item['lastname'].' '.$familytext)."</a>";
         $record['people.firstname']= "<a href='".admin_url("admin.php?page=eme-people&amp;eme_admin_action=edit_person&amp;person_id=".$item['person_id'])."' title='".__('Edit person','events-made-easy')."'>".eme_esc_html($item['firstname'].' '.$familytext)."</a>";
         $record['people.email']= "<a href='".admin_url("admin.php?page=eme-people&amp;eme_admin_action=edit_person&amp;person_id=".$item['person_id'])."' title='".__('Edit person','events-made-easy')."'>".eme_esc_html($item['email'].' '.$familytext)."</a>";
	 $record['people.phone']    = eme_esc_html($item['phone']);
	 $record['people.birthdate'] = eme_localized_date($item['birthdate'],$eme_timezone,1);
	 $record['people.bd_email'] = $item['bd_email'] ? __('Yes','events-made-easy') : __('No','events-made-easy');
	 $record['people.birthplace'] = eme_esc_html($item['birthplace']);
	 $record['people.address1'] = eme_esc_html($item['address1']);
         $record['people.address2'] = eme_esc_html($item['address2']);
         $record['people.city']     = eme_esc_html($item['city']);
         $record['people.zip']      = eme_esc_html($item['zip']);
         $record['people.lang']     = eme_esc_html($item['lang']);
	 if ($item['state_code'])
		 $record['people.state']    = eme_esc_html(eme_get_state_name($item['state_code'],$item['country_code']));
	 elseif (isset($item['state']))
		 $record['people.state']    = eme_esc_html($item['state']);
	 if ($item['country_code'])
		 $record['people.country']  = eme_esc_html(eme_get_country_name($item['country_code']));
	 elseif (isset($item['country']))
		 $record['people.country']  = eme_esc_html($item['country']);
	 $record['people.massmail'] = $item['massmail'] ? __('Yes','events-made-easy') : __('No','events-made-easy');
	 $record['people.gdpr']     = $item['gdpr'] ? __('Yes','events-made-easy') : __('No','events-made-easy');
         $record['people.gdpr_date']= eme_esc_html($item['gdpr_date']);
	 $record['people.creation_date'] = eme_localized_datetime($item['creation_date'],$eme_timezone,1);
	 $record['people.modif_date'] = eme_localized_datetime($item['modif_date'],$eme_timezone,1);
	 $record['people.groups'] = join(',',eme_esc_html(eme_get_persongroup_names($item['person_id'])));
	 $memberships=eme_get_activemembership_names_by_personid($item['person_id']);
	 if (!empty($memberships)) {
		 $record['people.memberships']=join(", ",$memberships);
	 } else {
		 $record['people.memberships']="";
	 }
	 $answers=eme_get_person_answers($item['person_id']);
	 foreach ($formfields as $formfield) {
		 foreach ($answers as $val) {
			 if ($val['field_id']==$formfield['field_id'] && $val['answer']!='') {
				 $tmp_answer=eme_answer2readable($val['answer'],$formfield,1,",","text",1);
                                 // the 'FIELD_' value is used by the container-js
                                 $key='FIELD_'.$val['field_id'];
                                 if (isset($record[$key])) {
                                         $record[$key].="<br />$tmp_answer";
                                 } else {
                                         $record[$key]=$tmp_answer;
                                 }
			 }
		 }
	 }
	 $files = eme_get_uploaded_files($item['person_id'],"people");
         foreach ($files as $file) {
                 $key='FIELD_'.$file['field_id'];
                 if (isset($record[$key])) {
                         $record[$key].=eme_get_uploaded_file_html($file,0,1);
                 } else {
                         $record[$key]=eme_get_uploaded_file_html($file,0,1);
                 }
         }
         $records[]  = $record;
   }
   $jTableResult['Result'] = "OK";
   $jTableResult['TotalRecordCount'] = $recordCount;
   $jTableResult['Records'] = $records;
   print json_encode($jTableResult);
   wp_die();
}

function eme_ajax_groups_list() {
   global $wpdb;
   $table = $wpdb->prefix.GROUPS_TBNAME;
   $usergroups_table = $wpdb->prefix.USERGROUPS_TBNAME;

   $jTableResult = array();
   $sql = "SELECT COUNT(*) FROM $table";
   $recordCount = $wpdb->get_var($sql);

   $sql = "SELECT group_id,COUNT(*) AS groupcount FROM $usergroups_table GROUP BY group_id";
   $res=$wpdb->get_results($sql,ARRAY_A);
   $groupcount=array();
   foreach ($res as $val) {
	  $groupcount[$val['group_id']]=$val['groupcount'];
   } 

   $start= (isset($_REQUEST['jtStartIndex'])) ? intval($_REQUEST['jtStartIndex']) : 0;
   $pagesize= (isset($_REQUEST['jtPageSize'])) ? intval($_REQUEST['jtPageSize']) : 10;
   $sorting = (isset($_REQUEST['jtSorting'])) ? 'ORDER BY '.esc_sql($_REQUEST['jtSorting']) : '';
   $sql="SELECT * FROM $table $sorting LIMIT $start,$pagesize";
   $groups=$wpdb->get_results($sql,ARRAY_A);
   $records=array();
   foreach ($groups as $group) {
         $record = array();
	 if (empty($group['name'])) $group['name']=__('No name','events-made-easy');
         $record['group_id']= $group['group_id'];
         $record['public']= $group['public'] ?  __('Yes','events-made-easy') : __('No','events-made-easy');
	 if (current_user_can( get_option('eme_cap_edit_people')))
		 $record['name'] = "<a href='".admin_url("admin.php?page=eme-groups&amp;eme_admin_action=edit_group&amp;group_id=".$group['group_id'])."' title='".__('Edit group','events-made-easy')."'>".eme_esc_html($group['name'])."</a>";
	 else
		 $record['name'] = eme_esc_html($group['name']);
	 $record['description'] = eme_esc_html($group['description']);
	 if ($group['type']=='dynamic_people') {
		 $record['groupcount'] = __('Dynamic group of people','events-made-easy');
		 if (!empty($group['search_terms'])) {
			 $search_terms=unserialize($group['search_terms']);
			 $count_sql=eme_get_sql_people_searchfields($search_terms,0,0,'',1);
			 $count = $wpdb->get_var($count_sql);
			 if ($count>0)
				 $record['groupcount'] .= "&nbsp;".sprintf(_n("(1 person)","(%d persons)",$count,"events-made-easy"),$count);
		 }
	 } elseif ($group['type']=='dynamic_members') {
		 $record['groupcount'] = __('Dynamic group of members','events-made-easy');
		 if (!empty($group['search_terms'])) {
			 $search_terms=unserialize($group['search_terms']);
			 $count_sql=eme_get_sql_members_searchfields($search_terms,0,0,'',1);
			 $count = $wpdb->get_var($count_sql);
			 if ($count>0)
				 $record['groupcount'] .= "&nbsp;".sprintf(_n("(1 member)","(%d members)",$count,"events-made-easy"),$count);
		 }
	 } else {
		 $record['groupcount'] = isset($groupcount[$group['group_id']]) ? $groupcount[$group['group_id']] : 0;
	 }
         $records[]  = $record;
   }
   $jTableResult['Result'] = "OK";
   $jTableResult['TotalRecordCount'] = $recordCount;
   $jTableResult['Records'] = $records;
   print json_encode($jTableResult);
   wp_die();
}

function eme_ajax_people_select2() {
   global $wpdb;

   check_ajax_referer('eme_admin','eme_admin_nonce');

   $table = $wpdb->prefix.PEOPLE_TBNAME;

   $jTableResult = array();
   $q = isset($_REQUEST['q']) ? strtolower($_REQUEST['q']) : '';
   if (!empty($q)) {
	   $where = "(lastname LIKE '%".esc_sql($q)."%' OR firstname LIKE '%".esc_sql($q)."%')";
   } else {
	   $where = "(1=1)";
   }
   $pagesize=intval($_REQUEST["pagesize"]);
   $start= isset($_REQUEST["page"]) ? intval($_REQUEST["page"])*$pagesize : 0;
   $count_sql = "SELECT COUNT(*) FROM $table WHERE $where";
   $recordCount = $wpdb->get_var($count_sql);
   $limit="LIMIT $start,$pagesize";

   $records=array();
   $persons = eme_get_persons('',$where,$limit);
   foreach ($persons as $person) {
         $record = array();
         $record['id']= $person['person_id'];
	 // no eme_esc_html here, select2 does it own escaping upon arrival
	 $record['text'] = eme_format_full_name($person['firstname'],$person['lastname']).' ('.$person['email'].')';
         $records[]  = $record;
   }
   $jTableResult['TotalRecordCount'] = $recordCount;
   $jTableResult['Records'] = $records;
   print json_encode($jTableResult);
   wp_die();
}

function eme_ajax_store_people_query() {
   check_ajax_referer('eme_admin','eme_admin_nonce');
   if (!empty($_POST['dynamicgroupname'])) {
	eme_ajax_people_list($_POST['dynamicgroupname']);
	$jTableResult['htmlmessage'] = "<div id='message' class='updated eme-message-admin'><p>".__('Dynamic group added','events-made-easy')."</p></div>";
   } else {
        $jTableResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('Please enter a name for the group','events-made-easy')."</p></div>";
   }
   print json_encode($jTableResult);
   wp_die();
}

function eme_ajax_manage_people() {
   check_ajax_referer('eme_admin','eme_admin_nonce');
   $ajaxResult=array();
   if (isset($_POST['do_action'])) {
      $do_action=eme_sanitize_request($_POST['do_action']);
      $ids=$_POST['person_id'];
      $ids_arr=explode(',',$ids);
      if (!eme_array_integers($ids_arr) || !current_user_can( get_option('eme_cap_edit_people'))) {
          $jTableResult['Result'] = "Error";
          $jTableResult['Message'] = __('Access denied!','events-made-easy');
	  print json_encode($ajaxResult);
	  wp_die();
      }

      switch ($do_action) {
         case 'untrashPeople':
	      eme_ajax_action_untrash_people($ids);
              break;
         case 'trashPeople':
	      eme_ajax_action_trash_people($ids);
              break;
         case 'gdprPeople':
	      eme_ajax_action_gdpr_trash_people($ids);
              break;
         case 'gdprApprovePeople':
	      eme_ajax_action_gdpr_approve_people($ids);
              break;
         case 'gdprUnapprovePeople':
	      eme_ajax_action_gdpr_unapprove_people($ids);
              break;
         case 'massmailPeople':
	      eme_ajax_action_set_massmail_people($ids);
              break;
         case 'noMassmailPeople':
	      eme_ajax_action_set_nomassmail_people($ids);
              break;
         case 'bdemailPeople':
	      eme_ajax_action_set_bdemail_people($ids);
              break;
         case 'noBdemailPeople':
	      eme_ajax_action_set_nobdemail_people($ids);
              break;
         case 'deletePeople':
	      eme_ajax_action_delete_people($ids);
              break;
         case 'addToGroup':
              $group_id=(isset($_POST['addtogroup'])) ? intval($_POST['addtogroup']) : 0;
	      eme_ajax_action_add_people_to_group($ids_arr,$group_id);
              break;
         case 'removeFromGroup':
              $group_id=(isset($_POST['removefromgroup'])) ? intval($_POST['removefromgroup']) : 0;
	      eme_ajax_action_delete_people_from_group($ids_arr,$group_id);
              break;
         case 'changeLanguage':
	      eme_ajax_action_set_people_language($ids);
              break;
         case 'pdf':
   	      $template_id=(isset($_POST['pdf_template'])) ? intval($_POST['pdf_template']) : 0;
              $template_id_header=(isset($_POST['pdf_template_header'])) ? intval($_POST['pdf_template_header']) : 0;
              $template_id_footer=(isset($_POST['pdf_template_footer'])) ? intval($_POST['pdf_template_footer']) : 0;
	      if ($template_id)
		      eme_ajax_generate_people_pdf($ids_arr,$template_id,$template_id_header,$template_id_footer);
              break;
         case 'html':
              $template_id=(isset($_POST['html_template'])) ? intval($_POST['html_template']) : 0;
              $template_id_header=(isset($_POST['html_template_header'])) ? intval($_POST['html_template_header']) : 0;
              $template_id_footer=(isset($_POST['html_template_footer'])) ? intval($_POST['html_template_footer']) : 0;
	      if ($template_id)
		      eme_ajax_generate_people_html($ids_arr,$template_id,$template_id_header,$template_id_footer);
              break;
      }
   }
   wp_die();
}

function eme_ajax_manage_groups() {
   check_ajax_referer('eme_admin','eme_admin_nonce');
   if (isset($_REQUEST['do_action'])) {
      $do_action=eme_sanitize_request($_REQUEST['do_action']);
      $ids=$_POST['group_id'];
      $ids_arr=explode(',',$ids);
      if (!eme_array_integers($ids_arr) || !current_user_can( get_option('eme_cap_edit_people'))) {
	  $ajaxResult = array();
          $ajaxResult['Result'] = "Error";
          $ajaxResult['Message'] = __('Access denied!','events-made-easy');
	  print json_encode($ajaxResult);
	  wp_die();
      }
      switch ($do_action) {
         case 'deleteGroups':
	      eme_ajax_action_delete_groups($ids);
              break;
      }
   }
   wp_die();
}

function eme_ajax_action_untrash_people($ids) {
   $ajaxResult=array();
   eme_untrash_people($ids);
   $ajaxResult['Result'] = "OK";
   $ajaxResult['htmlmessage'] = __('People recovered from trash bin.','events-made-easy');
   print json_encode($ajaxResult);
}

function eme_ajax_action_trash_people($ids) {
   $ajaxResult=array();
   if (!empty($_POST['transferto_id'])) {
	   $to_person_id=intval($_POST['transferto_id']);
	   eme_transfer_person_bookings($ids,$to_person_id);
	   eme_transfer_person_task_signups($ids,$to_person_id);
   }
   eme_trash_people($ids);
   $ajaxResult['Result'] = "OK";
   $ajaxResult['htmlmessage'] = __('People moved to trash bin.','events-made-easy');
   print json_encode($ajaxResult);
}

function eme_ajax_action_gdpr_trash_people($ids) {
   $ajaxResult=array();
   eme_gdpr_trash_people($ids);
   $ajaxResult['Result'] = "OK";
   $ajaxResult['htmlmessage'] = __('Personal data removed and moved to trash bin.','events-made-easy');
   print json_encode($ajaxResult);
}

function eme_ajax_action_gdpr_approve_people($ids) {
   $ajaxResult=array();
   eme_update_people_gdpr($ids);
   $ajaxResult['Result'] = "OK";
   $ajaxResult['htmlmessage'] = __('GDPR approval set to "Yes" (make sure the selected persons are aware of this).','events-made-easy');
   print json_encode($ajaxResult);
}

function eme_ajax_action_gdpr_unapprove_people($ids) {
   $ajaxResult=array();
   eme_update_people_gdpr($ids,0);
   $ajaxResult['Result'] = "OK";
   $ajaxResult['htmlmessage'] = __('GDPR approval set to "No" (make sure the selected persons are aware of this).','events-made-easy');
   print json_encode($ajaxResult);
}

function eme_ajax_action_set_massmail_people($ids) {
   $ajaxResult=array();
   eme_update_people_massmail($ids);
   $ajaxResult['Result'] = "OK";
   $ajaxResult['htmlmessage'] = __('Massmail set to "Yes" (make sure the selected persons are aware of this).','events-made-easy');
   print json_encode($ajaxResult);
}

function eme_ajax_action_set_nomassmail_people($ids) {
   $ajaxResult=array();
   eme_update_people_massmail($ids,0);
   $ajaxResult['Result'] = "OK";
   $ajaxResult['htmlmessage'] = __('Massmail set to "No" (make sure the selected persons are aware of this).','events-made-easy');
   print json_encode($ajaxResult);
}

function eme_ajax_action_set_bdemail_people($ids) {
   $ajaxResult=array();
   eme_update_people_bdemail($ids);
   $ajaxResult['Result'] = "OK";
   $ajaxResult['htmlmessage'] = __('Birthday email set to "Yes".','events-made-easy');
   print json_encode($ajaxResult);
}

function eme_ajax_action_set_nobdemail_people($ids) {
   $ajaxResult=array();
   eme_update_people_bdemail($ids,0);
   $ajaxResult['Result'] = "OK";
   $ajaxResult['htmlmessage'] = __('Birthday email set to "No".','events-made-easy');
   print json_encode($ajaxResult);
}

function eme_ajax_action_set_people_language($ids) {
   $ajaxResult=array();
   $lang=eme_sanitize_request($_POST['language']);
   eme_update_people_language($ids,$lang);
   $ajaxResult['Result'] = "OK";
   $ajaxResult['htmlmessage'] = __('Language updated.','events-made-easy');
   print json_encode($ajaxResult);
}

function eme_ajax_action_delete_people($ids) {
   $ajaxResult=array();
   if (!empty($_POST['transferto_id'])) {
	   $to_person_id=intval($_POST['transferto_id']);
	   eme_transfer_person_bookings($ids,$to_person_id);
	   eme_transfer_person_task_signups($ids,$to_person_id);
   }
   eme_delete_people($ids);
   $ajaxResult['Result'] = "OK";
   $ajaxResult['htmlmessage'] = __('People deleted.','events-made-easy');
   print json_encode($ajaxResult);
}

function eme_ajax_action_add_people_to_group($ids_arr,$group_id) {
   $ajaxResult=array();
   foreach ($ids_arr as $person_id) {
       eme_add_persongroups($person_id,$group_id);
   }
   $ajaxResult['Result'] = "OK";
   $ajaxResult['htmlmessage'] = __('People added to group.','events-made-easy');
   print json_encode($ajaxResult);
   wp_die();
}

function eme_ajax_action_delete_people_from_group($ids_arr,$group_id) {
   $ajaxResult=array();
   foreach ($ids_arr as $person_id) {
       eme_delete_personfromgroup($person_id,$group_id);
   }
   $ajaxResult['Result'] = "OK";
   $ajaxResult['htmlmessage'] = __('People removed from group.','events-made-easy');
   print json_encode($ajaxResult);
   wp_die();
}

function eme_ajax_action_delete_groups($ids) {
   $ajaxResult=array();
   eme_delete_groups($ids);
   $ajaxResult['Result'] = "OK";
   $ajaxResult['htmlmessage'] = __('Groups deleted.','events-made-easy');
   print json_encode($ajaxResult);
   wp_die();
}

function eme_ajax_generate_people_pdf($ids_arr,$template_id,$template_id_header=0,$template_id_footer=0) {
        $template=eme_get_template($template_id);
	// the template format needs br-handling, so lets use a handy function
        $format=eme_get_template_format($template_id);
        $header=eme_get_template_format($template_id_header);
        $footer=eme_get_template_format($template_id_footer);

	require_once("dompdf/1.2.0/autoload.inc.php");
        // instantiate and use the dompdf class
	$options = new Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf\Dompdf($options);
        $margin_info = "margin: ".$template['properties']['pdf_margins'].";";
	$font_info = "font-family: ".get_option('eme_pdf_font');
        $orientation = $template['properties']['pdf_orientation'];
        $pagesize = $template['properties']['pdf_size'];
        if ($pagesize == "custom" )
                $pagesize = array(0,0,$template['properties']['pdf_width'],$template['properties']['pdf_height']);

        $dompdf->setPaper($pagesize, $orientation);
        $html="
<html>
<head>
<style>
    @page { $margin_info; }
    body { $margin_info; $font_info; }
    div.page-break {
        page-break-before: always;
    }
</style>
</head>
<body>
$header
";
        $total = count($ids_arr);
        $i=1;
        foreach ($ids_arr as $person_id) {
                $person=eme_get_person($person_id);
                $html.=eme_replace_people_placeholders($format,$person);
                if ($i < $total) {
                        // dompdf uses a style to detect forced page breaks
                        $html.='<div class="page-break"></div>';
                        $i++;
                }
        }
        $html .= "$footer</body></html>";
        $dompdf->loadHtml($html,get_bloginfo('charset'));
        $dompdf->render();
        $dompdf->stream();
}

function eme_ajax_generate_people_html($ids_arr,$template_id,$template_id_header=0,$template_id_footer=0) {
        $format=eme_get_template_format($template_id);
        $header=eme_get_template_format($template_id_header);
        $footer=eme_get_template_format($template_id_footer);
        $html="<html><body>$header";
        $total = count($ids_arr);
        $i=1;
        foreach ($ids_arr as $person_id) {
                $person=eme_get_person($person_id);
                $html.=eme_replace_people_placeholders($format,$person);
        }
        $html .= "$footer</body></html>";
        print $html;
}

function eme_get_family_person_ids($person_id) {
   global $wpdb;
   $table = $wpdb->prefix.PEOPLE_TBNAME;
   $sql=$wpdb->prepare("select person_id from $table where related_person_id=%d && status<>%d",$person_id,EME_PEOPLE_STATUS_TRASH);
   return $wpdb->get_col($sql);
}

?>
