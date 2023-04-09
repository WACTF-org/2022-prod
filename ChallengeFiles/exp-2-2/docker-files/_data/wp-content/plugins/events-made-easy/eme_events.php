<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function eme_new_event() {
   global $eme_timezone;
   $eme_date_obj=new ExpressiveDate("now",$eme_timezone);
   $today = $eme_date_obj->getDate();
   $this_datetime = $eme_date_obj->getDateTime();
   $event = array (
      "event_name" => '',
      "event_status" => get_option('eme_event_initial_state'),
      "event_start" => $this_datetime,
      "event_end" => '',
      "event_notes" => '',
      "event_rsvp" => get_option('eme_rsvp_reg_for_new_events')? 1:0,
      "event_tasks" => 0,
      "price" => get_option('eme_default_price'),
      "currency" => get_option('eme_default_currency'),
      "rsvp_number_days" => get_option('eme_rsvp_number_days'),
      "rsvp_number_hours" => get_option('eme_rsvp_number_hours'),
      "registration_requires_approval" => get_option('eme_rsvp_require_approval')? 1:0,
      "registration_wp_users_only" => get_option('eme_rsvp_registered_users_only')? 1:0,
      "event_seats" => get_option('eme_rsvp_default_number_spaces') ? intval(get_option('eme_rsvp_default_number_spaces')):0,
      "location_id" => 0,
      "event_author" => 0,
      "event_contactperson_id" => get_option('eme_default_contact_person'),
      "event_category_ids" => '',
      "event_attributes" => array(),
      "event_page_title_format" => '',
      "event_single_event_format" => '',
      "event_contactperson_email_body" => '',
      "event_respondent_email_body" => '',
      "event_registration_pending_email_body" => '',
      "event_registration_updated_email_body" => '',
      "event_registration_cancelled_email_body" => '',
      "event_registration_trashed_email_body" => '',
      "event_registration_paid_email_body" => '',
      "event_registration_form_format" => '',
      "event_cancel_form_format" => '',
      "event_registration_recorded_ok_html" => '',
      "event_prefix" => '',
      "event_slug" => '',
      "event_image_url" => '',
      "event_image_id" => 0,
      "event_external_ref" => '',
      "event_url" => '',
      "recurrence_id" => 0
   );
   // while in EME itself event_image_url can't be set when defining an event, it can be set in the frontend submit or a sync plugin
   $event['event_properties'] = eme_init_event_props();
   return $event;
}

function eme_init_event_props($props=array()) {
   if (!isset($props['create_wp_user']))
      $props['create_wp_user']=0;
   if (!isset($props['auto_approve']))
      $props['auto_approve']=0;
   if (!isset($props['ignore_pending']))
      $props['ignore_pending']=0;
   if (!isset($props['email_only_once']))
      $props['email_only_once']=0;
   if (!isset($props['person_only_once']))
      $props['person_only_once']=0;
   if (!isset($props['invite_only']))
      $props['invite_only']=0;
   if (!isset($props['all_day']))
      $props['all_day']=0;
   if (!isset($props['take_attendance']))
      $props['take_attendance']=0;
   if (!isset($props['require_user_confirmation']))
      $props['require_user_confirmation']=get_option('eme_rsvp_require_user_confirmation');
   if (!isset($props['min_allowed']))
      $props['min_allowed']=get_option('eme_rsvp_addbooking_min_spaces');
   if (!isset($props['max_allowed']))
      $props['max_allowed']=get_option('eme_rsvp_addbooking_max_spaces');
   if (!isset($props['rsvp_start_number_days']))
      $props['rsvp_start_number_days']='';
   if (!isset($props['rsvp_start_number_hours']))
      $props['rsvp_start_number_hours']='';
   if (!isset($props['rsvp_start_target']))
      $props['rsvp_start_target']='';
   if (!isset($props['rsvp_end_target']))
      $props['rsvp_end_target']=get_option('eme_rsvp_end_target');
   if (!isset($props['rsvp_discount']))
      $props['rsvp_discount']='';
   if (!isset($props['waitinglist_seats']))
      $props['waitinglist_seats']=0;
   if (!isset($props['check_free_waiting']))
      $props['check_free_waiting']=get_option('eme_check_free_waiting');
   if (!isset($props['rsvp_discountgroup']))
      $props['rsvp_discountgroup']='';
   if (!isset($props['rsvp_addpersontogroup']))
      $props['rsvp_addpersontogroup']=array();
   if (!isset($props['rsvp_password']))
      $props['rsvp_password']='';

   $payment_gateways=array('use_paypal','use_legacypaypal','use_2co','use_webmoney','use_fdgg','use_mollie','use_payconiq','use_worldpay','use_stripe','use_braintree','use_instamojo','use_mercadopago','use_fondy','use_offline');
   foreach ($payment_gateways as $gateway) {
           if (!isset($props[$gateway]))
                   $props[$gateway]=0;
   }

   if (!isset($props['cancel_rsvp_days']))
      $props['cancel_rsvp_days']=get_option('eme_cancel_rsvp_days');
   if (!isset($props['cancel_rsvp_age']))
      $props['cancel_rsvp_age']=get_option('eme_cancel_rsvp_age');
   if (!isset($props['attendance_begin']))
      $props['attendance_begin']=5;
   if (!isset($props['attendance_end']))
      $props['attendance_end']=0;
   if (!isset($props['ticket_template_id']))
      $props['ticket_template_id']=0;
   if (!isset($props['ticket_mail']))
      $props['ticket_mail']='approval';
   if (!isset($props['wp_page_template']))
      $props['wp_page_template']='';
   if (!isset($props['use_hcaptcha']))
      $props['use_hcaptcha']=get_option('eme_hcaptcha_for_forms')? 1:0;
   if (!isset($props['use_recaptcha']))
      $props['use_recaptcha']=get_option('eme_recaptcha_for_forms')? 1:0;
   if (!isset($props['use_captcha']))
      $props['use_captcha']=get_option('eme_captcha_for_forms')? 1:0;
   if (!isset($props['dyndata_all_fields']))
      $props['dyndata_all_fields']=0;
   if (!isset($props['booking_attach_ids']))
      $props['booking_attach_ids']=0;
   if (!isset($props['pending_attach_ids']))
      $props['pending_attach_ids']=0;
   if (!isset($props['paid_attach_ids']))
      $props['paid_attach_ids']=0;
   if (!isset($props['multiprice_desc']))
      $props['multiprice_desc']='';
   if (!isset($props['price_desc']))
      $props['price_desc']='';
   if (!isset($props['attendancerecord']))
      $props['attendancerecord']=0;
   if (!isset($props['skippaymentoptions']))
      $props['skippaymentoptions']=0;
   if (!isset($props['vat_pct']))
      $props['vat_pct']=get_option('eme_default_vat');
   if (!isset($props['task_registered_users_only']))
      $props['task_registered_users_only']=get_option('eme_task_registered_users_only');
   if (!isset($props['task_require_approval']))
      $props['task_require_approval']=get_option('eme_task_require_approval');
   if (!isset($props['task_allow_overlap']))
      $props['task_allow_overlap']=get_option('eme_task_allow_overlap');
   if (!isset($props['task_reminder_days']))
      $props['task_reminder_days']=get_option('eme_task_reminder_days');
   if (!isset($props['rsvp_pending_reminder_days']))
      $props['rsvp_pending_reminder_days']=get_option('eme_rsvp_pending_reminder_days');
   if (!isset($props['rsvp_approved_reminder_days']))
      $props['rsvp_approved_reminder_days']=get_option('eme_rsvp_approved_reminder_days');

   $template_override=array('event_page_title_format_tpl','event_single_event_format_tpl','event_contactperson_email_body_tpl','event_registration_recorded_ok_html_tpl','event_respondent_email_body_tpl','event_registration_pending_email_body_tpl','event_registration_userpending_email_body_tpl','event_registration_updated_email_body_tpl','event_registration_cancelled_email_body_tpl','event_registration_trashed_email_body_tpl','event_registration_form_format_tpl','event_cancel_form_format_tpl','event_registration_paid_email_body_tpl','event_contactperson_email_subject_tpl','event_respondent_email_subject_tpl','event_registration_pending_email_subject_tpl','event_registration_userpending_email_subject_tpl','event_registration_updated_email_subject_tpl','event_registration_cancelled_email_subject_tpl','event_registration_trashed_email_subject_tpl','event_registration_paid_email_subject_tpl','contactperson_registration_pending_email_subject_tpl','contactperson_registration_pending_email_body_tpl','contactperson_registration_cancelled_email_subject_tpl','contactperson_registration_cancelled_email_body_tpl','contactperson_registration_ipn_email_subject_tpl','contactperson_registration_ipn_email_body_tpl','contactperson_registration_paid_email_subject_tpl','contactperson_registration_paid_email_body_tpl','attendance_unauth_scan_tpl','attendance_auth_scan_tpl','task_signup_email_subject_tpl','task_signup_email_body_tpl','cp_task_signup_email_subject_tpl','cp_task_signup_email_body_tpl','task_signup_pending_email_subject_tpl','task_signup_pending_email_body_tpl','cp_task_signup_pending_email_subject_tpl','cp_task_signup_pending_email_body_tpl','task_signup_updated_email_subject_tpl','task_signup_updated_email_body_tpl','task_signup_cancelled_email_subject_tpl','task_signup_cancelled_email_body_tpl','cp_task_signup_cancelled_email_subject_tpl','cp_task_signup_cancelled_email_body_tpl','task_signup_trashed_email_subject_tpl','task_signup_trashed_email_body_tpl','task_signup_form_format_tpl','task_signup_recorded_ok_html_tpl','task_signup_reminder_email_subject_tpl','task_signup_reminder_email_body_tpl','event_registration_reminder_email_subject_tpl','event_registration_reminder_email_body_tpl','event_registration_pending_reminder_email_subject_tpl','event_registration_pending_reminder_email_body_tpl');
   foreach ($template_override as $line) {
      if (!isset($props[$line]))
         $props[$line]=0;
   }
   $text_override=array('event_contactperson_email_subject', 'event_respondent_email_subject', 'event_registration_pending_email_subject', 'event_registration_userpending_email_subject', 'event_registration_userpending_email_body', 'event_registration_updated_email_subject', 'event_registration_cancelled_email_subject', 'event_registration_paid_email_subject', 'event_registration_trashed_email_subject','contactperson_registration_pending_email_body','contactperson_registration_cancelled_email_body','contactperson_registration_ipn_email_body', 'contactperson_registration_pending_email_subject','contactperson_registration_cancelled_email_subject','contactperson_registration_ipn_email_subject','contactperson_registration_paid_email_subject','contactperson_registration_paid_email_body','attendance_unauth_scan_format','attendance_auth_scan_format','event_registration_reminder_email_subject','event_registration_reminder_email_body','event_registration_pending_reminder_email_subject','event_registration_pending_reminder_email_body');
   foreach ($text_override as $line) {
      if (!isset($props[$line]))
         $props[$line]='';
   }
   // for renamed properties
   $renamed_props=array('event_registration_denied_email_body_tpl'=>'event_registration_trashed_email_body_tpl',
	   		'event_registration_denied_email_subject_tpl'=>'event_registration_trashed_email_subject_tpl',
			'event_registration_denied_email_subject'=>'event_registration_trashed_email_subject'
			);
   foreach ($renamed_props as $old_prop=>$new_prop ) {
	   if (isset($props[$old_prop])) {
		   $props[$new_prop]=$props[$old_prop];
		   unset($props[$old_prop]);
	   }
   }

   return $props;
}

function eme_events_page() {
   global $wpdb, $eme_timezone, $eme_wp_time_format;

   $press_back = __('Press the back-button in your browser to return to the previous screen and correct your errors','events-made-easy');
   $extra_conditions = array();
   if (isset($_POST['eme_admin_action'])) {
	   $action = $_POST['eme_admin_action'];
	   $event_ID = isset($_POST['event_id']) ? eme_sanitize_request($_POST['event_id']) : 0;
	   $recurrence_ID = isset($_POST['recurrence_id']) ? intval($_POST['recurrence_id']) : 0;
   } elseif (isset($_GET['eme_admin_action'])) {
	   $action = $_GET['eme_admin_action'];
	   $event_ID = isset($_GET['event_id']) ? eme_sanitize_request($_GET['event_id']) : 0;
	   $recurrence_ID = isset($_GET['recurrence_id']) ? intval($_GET['recurrence_id']) : 0;
   } else {
	   $action = '';
	   $event_ID = 0;
	   $recurrence_ID = 0;
   }

   $current_userid=get_current_user_id();

   if ($action == 'import_events' && isset($_FILES['eme_csv']) && current_user_can(get_option('eme_cap_cleanup')) ) {
      // eme_cap_cleanup is used for cleanup, cron and imports (should more be something like 'eme_cap_actions')
      check_admin_referer('eme_admin','eme_admin_nonce');
      $message = eme_import_csv_events();
      eme_events_table($message);
   }

   // DELETE action (when the delete button is pushed while editing an event)
   if (isset($_POST['event_delete_button'])) {
      check_admin_referer('eme_admin','eme_admin_nonce');
      $tmp_event=eme_get_event($event_ID);
      if (empty($tmp_event)) {
         $feedback_message = __ ( 'No such event', 'events-made-easy');
      } elseif (current_user_can( get_option('eme_cap_edit_events')) ||
          (current_user_can( get_option('eme_cap_author_event')) && $tmp_event['event_author']==$current_userid)) {
         $res=eme_trash_events($event_ID);
         $feedback_message = __ ( 'Event moved to trash', 'events-made-easy');
      } else {
         $feedback_message = __ ( 'You have no right to delete events!', 'events-made-easy');
      }
      eme_events_table ($feedback_message);
      return;
   }

   // DELETE action (when the delete button is pushed while editing a recurrence)
   if (isset($_POST['event_deleteRecurrence_button'])) {
      check_admin_referer('eme_admin','eme_admin_nonce');
      $tmp_event=eme_get_event(eme_get_recurrence_first_eventid($recurrence_ID));
      if (empty($tmp_event)) {
         $feedback_message = __ ( 'No such event', 'events-made-easy');
      } elseif (current_user_can( get_option('eme_cap_edit_events')) ||
          (current_user_can( get_option('eme_cap_author_event')) && $tmp_event['event_author']==$current_userid)) {
         $res=eme_db_delete_recurrence($recurrence_ID);
         if ($res==0) {
            $feedback_message = __ ( 'Recurrence deleted!', 'events-made-easy');
         } else {
            $feedback_message = __ ( 'Error deleting the recurrence!', 'events-made-easy');
         }
      } else {
         $feedback_message = __ ( 'You have no right to delete events!', 'events-made-easy');
      }
      eme_events_table ($feedback_message);
      return;
   }

   // UPDATE or CREATE action
   if ($action == 'insert_event' || $action == 'update_event' || $action == 'insert_recurrence' || $action == 'update_recurrence') {
      check_admin_referer('eme_admin','eme_admin_nonce');
      // if not the result of a POST, then just show the list
      if ($_SERVER['REQUEST_METHOD'] != 'POST') {
         eme_events_table ();
         return;
      }
      if ( ! (current_user_can( get_option('eme_cap_add_event')) || current_user_can( get_option('eme_cap_edit_events'))) ) {
         $feedback_message = __('You have no right to insert or update events','events-made-easy');
	 if ($action == 'update_recurrence')
		 eme_recurrences_table ($feedback_message);
	 else
		 eme_events_table ($feedback_message);
         return;
      }

      if ($action == 'insert_event' || $action == 'insert_recurrence') {
         $event=eme_new_event();
         $location = eme_new_location();
      } elseif ($action == 'update_event' && $event_ID) {
         $event = eme_get_event($event_ID);
	 if (!empty($event)) {
		 $location = eme_get_location($event['location_id']);
		 if (empty($location))
			 $location = eme_new_location();
	 } else {
		 eme_events_table ();
		 return;
	 }
      } elseif ($action == 'update_recurrence' && $recurrence_ID) {
         $event = eme_get_event(eme_get_recurrence_first_eventid($recurrence_ID));
	 if (!empty($event)) {
		 $location = eme_get_location($event['location_id']);
		 if (empty($location))
			 $location = eme_new_location();
	 } else {
		 eme_events_table ();
		 return;
	 }
      }
      $orig_event = $event;
      $post_vars=array('event_name','event_seats','price','rsvp_number_days','rsvp_number_hours','currency','event_author','event_contactperson_id','event_url','event_image_url','event_image_id','event_prefix','event_slug','event_page_title_format','event_contactperson_email_body','event_registration_recorded_ok_html','event_respondent_email_body','event_registration_pending_email_body','event_registration_updated_email_body','event_registration_cancelled_email_body','event_registration_trashed_email_body','event_registration_form_format','event_cancel_form_format','event_registration_paid_email_body');
      foreach ($post_vars as $post_var) {
         if (isset($_POST[$post_var])) $event[$post_var]=eme_kses($_POST[$post_var]);
      }

      // now for the select boxes, we need to set to 0 if not in the _POST
      $select_post_vars=array('event_tasks','event_rsvp','registration_requires_approval','registration_wp_users_only');
      foreach ($select_post_vars as $post_var) {
         if (isset($_POST[$post_var])) $event[$post_var]=intval($_POST[$post_var]);
         else $event[$post_var]=0;
      }

      // event notes and format
      $event['event_single_event_format'] = isset($_POST['event_single_event_format']) ? eme_kses_maybe_unfiltered($_POST['event_single_event_format']) : '';
      $event['event_notes'] = isset($_POST['content']) ? eme_kses_maybe_unfiltered($_POST['content']) : '';
      
      if (!current_user_can( get_option('eme_cap_publish_event')) ) {
         $event['event_status']=EME_EVENT_STATUS_DRAFT;
      } else {
	 $event_status_array = eme_status_array ();
	 $event['event_status'] = intval($_POST['event_status']);
      }

      $eme_date_obj = new ExpressiveDate("now",$eme_timezone);
      // we do event_start_date and event_end_date differently
      if (isset($_POST['event_start_date'])) {
	      $event_start_date=eme_kses($_POST['event_start_date']);
      } else {
	      $event_start_date=$eme_date_obj->startOfDay()->getDate();
      }
      $recurrence['event_duration'] = isset($_POST['event_duration']) ? intval($_POST['event_duration']) : 1;
      if ($action == 'insert_recurrence' || $action == 'update_recurrence') {
	      $duration=$recurrence['event_duration']-1;
	      $end_date_obj = new ExpressiveDate($event_start_date,$eme_timezone);
	      $event_end_date = $end_date_obj->addDays($duration)->getDate();
      } elseif (isset($_POST['event_end_date'])) {
	      $event_end_date=eme_kses($_POST['event_end_date']);
      } else {
	      $event_end_date=$eme_date_obj->endOfDay()->getDate();
      }
      if (!empty($_POST['localized_start_time'])) {
         //$event_start_time = $eme_date_obj->setTimestampFromString(eme_sanitize_request($_POST['event_start_time'])." ".$eme_timezone)->format("H:i:00");
	 $start_date_obj=ExpressiveDate::createFromFormat($eme_wp_time_format,eme_sanitize_request($_POST['localized_start_time']),ExpressiveDate::parseSuppliedTimezone($eme_timezone));
	 $event_start_time = $start_date_obj->format("H:i:00");
      } else {
	 $event_start_time = "00:00:00";
      }
      if (!empty($_POST['localized_end_time'])) {
         //$event_end_time = $eme_date_obj->setTimestampFromString(eme_sanitize_request($_POST['event_end_time'])." ".$eme_timezone)->format("H:i:00");
	 $end_date_obj=ExpressiveDate::createFromFormat($eme_wp_time_format,eme_sanitize_request($_POST['localized_end_time']),ExpressiveDate::parseSuppliedTimezone($eme_timezone));
	 $event_end_time = $end_date_obj->format("H:i:00");
      } else {
	 $event_end_time = "23:59:59";
      }
      $event['event_start']="$event_start_date $event_start_time";
      $event['event_end']="$event_end_date $event_end_time";
      $recurrence['recurrence_freq'] = isset($_POST['recurrence_freq']) ? eme_sanitize_request($_POST['recurrence_freq']) : '';
      if ($recurrence['recurrence_freq'] == 'specific') {
         $recurrence['recurrence_specific_days'] = isset($_POST['recurrence_start_date']) ? eme_sanitize_request($_POST['recurrence_start_date']) : $event_start_date;
         $recurrence['recurrence_start_date'] = "";
         $recurrence['recurrence_end_date'] = "";
      } else {
         $recurrence['recurrence_specific_days'] = "";
         $recurrence['recurrence_start_date'] = isset($_POST['recurrence_start_date']) ? eme_sanitize_request($_POST['recurrence_start_date']) : $event_start_date;
         $recurrence['recurrence_end_date'] = isset($_POST['recurrence_end_date']) ? eme_sanitize_request($_POST['recurrence_end_date']) : $event_end_date;
      }
      if (!eme_is_date($recurrence['recurrence_start_date']))
          $recurrence['recurrence_start_date'] = "";
      if (!eme_is_date($recurrence['recurrence_end_date']))
          $recurrence['recurrence_end_date'] = $recurrence['recurrence_start_date'];
      if (!eme_are_dates_valid($recurrence['recurrence_specific_days']))
          $recurrence['recurrence_specific_days'] = "";
      if ($recurrence['recurrence_freq'] == 'weekly') {
         if (isset($_POST['recurrence_bydays'])) {
            $recurrence['recurrence_byday'] = implode ( ",", eme_sanitize_request($_POST['recurrence_bydays']));
         } else {
            $recurrence['recurrence_byday'] = '';
         }
      } else {
         if (isset($_POST['recurrence_byday'])) {
            $recurrence['recurrence_byday'] = eme_sanitize_request($_POST['recurrence_byday']);
         } else {
            $recurrence['recurrence_byday'] = '';
         }
      }
      $recurrence['recurrence_interval'] = isset($_POST['recurrence_interval']) ? eme_sanitize_request($_POST['recurrence_interval']) : 1;
      if ($recurrence['recurrence_interval'] ==0)
         $recurrence['recurrence_interval']=1;
      $recurrence['recurrence_byweekno'] = isset($_POST['recurrence_byweekno']) ? eme_sanitize_request($_POST['recurrence_byweekno']) : '';
      $recurrence['holidays_id'] = isset($_POST['holidays_id']) ? intval($_POST['holidays_id']) : 0;
      
      // set the location info
      $post_vars=array('location_name','location_address1','location_address2','location_city','location_state','location_zip','location_country','location_latitude','location_longitude','location_url');
      foreach ($post_vars as $post_var) {
         if (isset($_POST[$post_var])) $location[$post_var]=eme_sanitize_request($_POST[$post_var]);
      }

      if (isset ($_POST['event_category_ids']) && eme_array_integers($_POST['event_category_ids'])) {
         $event['event_category_ids']= join(',',$_POST['event_category_ids']);
      } else {
         $event['event_category_ids']="";
      }

      // set the attributes
      $event_attributes = array();
      for($i=1 ; isset($_POST["eme_attr_{$i}_ref"]) && trim($_POST["eme_attr_{$i}_ref"])!='' ; $i++ ) {
         if(trim($_POST["eme_attr_{$i}_name"]) != '') {
            $event_attributes[$_POST["eme_attr_{$i}_ref"]] = eme_kses($_POST["eme_attr_{$i}_name"]);
         }
      }
      $event['event_attributes'] = $event_attributes;

      // set the properties for both event and location
      $event_properties = array();
      $event_properties = eme_init_event_props($event_properties);
      $location_properties = array();
      $location_properties = eme_init_location_props($location_properties);
      // now for the select boxes, we need to set to 0 if not in the _POST
      $select_event_post_vars=array('use_offline','use_captcha','use_recaptcha','use_hcaptcha','use_paypal','use_2co','use_fdgg','use_mollie','use_payconiq','use_webmoney','use_worldpay','use_legacypaypal','use_stripe','use_braintree','use_instamojo','use_mercadopago','use_fondy','dyndata_all_fields','all_day');
      foreach ($select_event_post_vars as $post_var) {
         if (!isset($_POST['eme_prop_'.$post_var])) $event_properties[$post_var]=0;
      }
      if ($event_properties['use_captcha'] && !function_exists('imagecreatetruecolor')) {
	      $event_properties['use_captcha']=0;
      }
      $select_location_post_vars=array('online_only');
      foreach ($select_location_post_vars as $post_var) {
         if (!isset($_POST['eme_loc_prop_'.$post_var])) $location_properties[$post_var]=0;
      }

      foreach($_POST as $key=>$value) {
         if (preg_match('/eme_prop_(.+)/', eme_sanitize_request($key), $matches)) {
		 $found_key=$matches[1];
		 if ($found_key=='multiprice_desc')
			 $event_properties[$found_key] = join('||',eme_sanitize_request(eme_text_split_newlines($_POST[$key])));
		 else
			 $event_properties[$found_key] = eme_kses($value);
         }
	 if (preg_match('/eme_loc_prop_(.+)/', eme_sanitize_request($key), $matches)) {
		 $location_properties[$matches[1]] = eme_kses($value);
	 }
      }
      $event_rsvp_dyndata = eme_handle_dyndata_post_adminform();
      if (!empty($event_rsvp_dyndata))
	      $event_properties['rsvp_dyndata'] = $event_rsvp_dyndata;
      $event['event_properties'] = $event_properties;
      $location['location_properties'] = $location_properties;
      
      $event = eme_sanitize_event($event);
      $location = eme_sanitize_location($location);
      $validation_result = eme_validate_event($event);
      if ($validation_result != "OK") {
         // validation unsuccessful       
         echo "<div id='message' class='error '>
                  <p>$validation_result</p>
                  <p>$press_back</p>
              </div>";
         return;
      }

      // validation successful
      if(isset($_POST['location-select-id']) && $_POST['location-select-id'] != "") {
         $event['location_id'] = $_POST['location-select-id'];
      } else {
	 if (isset($_POST['location_id']) && intval($_POST['location_id'])>0) {
	    $event['location_id'] = intval($_POST['location_id']);
	 } elseif (empty($location['location_name']) && empty($location['location_address1']) && empty($location['location_city'])) {
            $event['location_id'] = 0;
         } else {
            $related_location_id = eme_get_identical_location_id($location);
            if ($related_location_id) {
               $event['location_id'] = $related_location_id;
            } else {
	       $validation_result = eme_validate_location($location);
	       if ($validation_result != "OK") {
		       echo "<div id='message' class='error '>
			     <p>$validation_result</p>
			     <p>$press_back</p>
			     </div>";
		       return;
               } else {
                  $new_location_id = eme_insert_location($location);
		  eme_location_store_cf_answers($new_location_id);
                  if (!$new_location_id) {
                     echo "<div id='message' class='error '>
                        <p>" . __ ( "Could not create the new location for this event: either you don't have the right to insert locations or there's a DB problem.", "eme" , 'events-made-easy') . "</p>
			<p>$press_back</p>
                        </div>";
                     return;
                  }
                  $event['location_id'] = $new_location_id;
               }
            }
         }
      }

      $stay_on_edit_page = get_option('eme_stay_on_edit_page');
      if (! $event_ID && ! $recurrence_ID) {
         // new event or new recurrence
         if (!empty($_POST['repeated_event'])) {
            //insert new recurrence
            $recurrence_id = eme_db_insert_recurrence ( $recurrence, $event );
            if (!$recurrence_id) {
	       $feedback_message = __ ( 'No recurrence created!', 'events-made-easy');
            } else {
	       $count = eme_recurrence_count($recurrence_id);
               $feedback_message = sprintf(__ ( 'New recurrence inserted containing %d events', 'events-made-easy'),$count);
	       if ($stay_on_edit_page) {
		       $info = array('title' => __("Edit Recurrence",'events-made-easy'));
		       $info['feedback']=$feedback_message;
		       $event=eme_get_event(eme_get_recurrence_first_eventid($recurrence_id));
		       if (!empty($event))
			       eme_event_form ( $event, $info, 1 );
		       return;
	       }
            }
         } else {
            // INSERT new event 
            $event_id=eme_db_insert_event($event);
            if (!$event_id) {
               $feedback_message = __ ( 'Database insert failed!', 'events-made-easy');
            } else {
	       eme_event_store_cf_answers($event_id);
	       // the eme_insert_event_action is only executed for single events, not those part of a recurrence
	       if (has_action('eme_insert_event_action')) {
		       $event=eme_get_event($event_id);
		       do_action('eme_insert_event_action',$event);
	       }
               $feedback_message = __ ( 'New event successfully inserted!', 'events-made-easy');
	       if ($stay_on_edit_page) {
		       $info = array('title' => sprintf(__("Edit Event '%s'",'events-made-easy'),eme_translate($event['event_name'])));
		       $info['feedback']=$feedback_message;
		       $event=eme_get_event($event_id);
		       if (!empty($event))
			       eme_event_form ( $event, $info );
		       return;
	       }
            }
         }
      } else {
         // something exists
         if ($recurrence_ID) {
            if (current_user_can( get_option('eme_cap_edit_events')) ||
                (current_user_can( get_option('eme_cap_author_event')) && $orig_event['event_author']==$current_userid)) {
               // UPDATE old recurrence
               $recurrence['recurrence_id'] = $recurrence_ID;
               $count=eme_db_update_recurrence ($recurrence,$event);
               if ($count) {
                  $feedback_message = sprintf(__ ( 'Recurrence updated, contains %d events', 'events-made-easy'),$count);
		  if ($stay_on_edit_page) {
			  $info = array('title' => __("Edit Recurrence",'events-made-easy'));
			  $info['feedback']=$feedback_message;
			  $event=eme_get_event(eme_get_recurrence_first_eventid($recurrence_ID));
			  if (!empty($event))
				  eme_event_form($event, $info,1);
			  return;
		  }
               } else {
                  $feedback_message = __ ('Recurrence no longer contains events, so it has been removed', 'events-made-easy');
               }
            } else {
               $feedback_message = sprintf(__("You have no right to update '%s'",'events-made-easy'),eme_translate($orig_event['event_name']));
            }
         } else {
            if (current_user_can( get_option('eme_cap_edit_events')) ||
                (current_user_can( get_option('eme_cap_author_event')) && $orig_event['event_author']==$current_userid)) {
               if (isset($_POST['repeated_event']) && $_POST['repeated_event']) {
                  // we go from single event to recurrence: create the recurrence and delete the single event
		  $recurrence_id = eme_db_insert_recurrence ( $recurrence, $event );
		  if (!$recurrence_id) {
	             $feedback_message = __ ('No recurrent event created!', 'events-made-easy');
		  } else {
		     eme_db_delete_event ( $orig_event['event_id'] );
		     $count = eme_recurrence_count($recurrence_id);
                     $feedback_message = sprintf(__ ( 'New recurrent event inserted containing %d events', 'events-made-easy'),$count);
		     if ($stay_on_edit_page) {
			     $info = array('title' => __("Edit Recurrence",'events-made-easy'));
			     $info['feedback']=$feedback_message;
			     $event=eme_get_event(eme_get_recurrence_first_eventid($recurrence_id));
			     if (!empty($event))
				     eme_event_form($event, $info, 1);
			     return;
		     }
		  }
               } else {
                  // UPDATE old event
                  // unlink from recurrence in case it was generated by one
                  $event['recurrence_id'] = 0;
                  if (eme_db_update_event ($event,$event_ID)) {
                     eme_event_store_cf_answers($event_ID);
		     if (has_action('eme_update_event_action')) {
			     $event=eme_get_event($event_ID);
			     do_action('eme_update_event_action',$event);
		     }
                     $feedback_message = sprintf(__("Updated '%s'",'events-made-easy'),eme_translate($event['event_name']));
		     if ($stay_on_edit_page) {
			     $info = array('title' => sprintf(__("Edit Event '%s'",'events-made-easy'),eme_translate($event['event_name'])));
			     $info['feedback']=$feedback_message;
			     $event=eme_get_event($event_ID);
			     if (!empty($event))
				     eme_event_form($event, $info);
			     return;
		     }
                  } else {
                     $feedback_message = sprintf(__("Failed to update '%s'",'events-made-easy'),eme_translate($event['event_name']));
                  }
               }
            } else {
               $feedback_message = sprintf(__("You have no right to update '%s'",'events-made-easy'),eme_translate($orig_event['event_name']));
            }
         }
      }
         
      // this gets reached in case event updates fails
      // or the option to stay on the edit page is not set
      if ($action == 'insert_recurrence' || $action == 'update_recurrence')
	      eme_recurrences_table ($feedback_message);
      else
	      eme_events_table ($feedback_message);
      return;
   }

   if ($action == 'add_new_event') {
         if (current_user_can( get_option('eme_cap_add_event'))) {
	    $event=eme_new_event();
            $info = array('title' => __ ( "Insert New Event", 'events-made-easy'));
            eme_event_form ( $event, $info );
         } else {
            $feedback_message = __('You have no right to add events!','events-made-easy');
            eme_events_table ($feedback_message);
         }
	 return;
   }

   if ($action == 'add_new_recurrence') {
         if (current_user_can( get_option('eme_cap_add_event'))) {
	    $event=eme_new_event();
            $info = array('title' => __ ( "Insert New Recurrence", 'events-made-easy'));
            eme_event_form ( $event, $info, 1 );
         } else {
            $feedback_message = __('You have no right to add events!','events-made-easy');
            eme_events_table ($feedback_message);
         }
	 return;
   }

   if ($action == 'edit_event') {
	 $event = eme_get_event($event_ID);
	 if (empty($event)) {
            $feedback_message = __("No such event",'events-made-easy');
            eme_events_table ($feedback_message);
	 } elseif (current_user_can( get_option('eme_cap_edit_events')) ||
             (current_user_can( get_option('eme_cap_author_event')) && $event['event_author']==$current_userid)) {
            // UPDATE event
	    $info = array('title' => sprintf(__("Edit Event '%s'",'events-made-easy'),eme_translate($event['event_name'])));
            eme_event_form ( $event, $info );
         } else {
            $feedback_message = sprintf(__("You have no right to update '%s'",'events-made-easy'),eme_translate($event['event_name']));
            eme_events_table ($feedback_message);
         }
	 return;
   }

   //Add duplicate event if requested
   if ($action == 'duplicate_event') {
      $event = eme_get_event($event_ID);
      if (empty($event)) {
	      $feedback_message = __("No such event",'events-made-easy');
	      eme_events_table ($feedback_message);
	      return;
      }
      // indicate this is a duplicate, we can use that further down the road for more actions
      $event['is_duplicate']=1;
      $event['orig_id']=$event['event_id'];

      if (current_user_can( get_option('eme_cap_edit_events')) ||
          (current_user_can( get_option('eme_cap_author_event')) && $event['event_author']==$current_userid)) {
         $info = array('title' => sprintf(__("Edit event copy '%s'",'events-made-easy'),$event['event_name']));
         eme_event_form ($event, $info);
      } else {
         $feedback_message = sprintf(__("You have no right to copy '%s'",'events-made-easy'),eme_translate($event['event_name']));
         eme_events_table ($feedback_message);
      }
      return;
   }
   if ($action == 'duplicate_recurrence') {
      $event=eme_get_event(eme_get_recurrence_first_eventid($recurrence_ID));
      if (empty($event)) {
	      $feedback_message = __("No such event",'events-made-easy');
	      eme_events_table ($feedback_message);
	      return;
      }
      // indicate this is a duplicate, we can use that further down the road for more actions
      $event['is_duplicate']=1;
      $event['orig_id']=$event['event_id'];

      if (current_user_can( get_option('eme_cap_edit_events')) ||
          (current_user_can( get_option('eme_cap_author_event')) && $event['event_author']==$current_userid)) {
         $info = array('title' => sprintf(__("Edit recurrence copy '%s'",'events-made-easy'),$event['event_name']));
         eme_event_form ($event, $info, 1);
      } else {
         $feedback_message = sprintf(__("You have no right to copy '%s'",'events-made-easy'),eme_translate($event['event_name']));
         eme_events_table ($feedback_message);
      }
      return;
   }

   if ($action == 'edit_recurrence') {
      $event=eme_get_event(eme_get_recurrence_first_eventid($recurrence_ID));
      if (empty($event)) {
	      $feedback_message = __("No such event",'events-made-easy');
	      eme_events_table ($feedback_message);
	      return;
      }
      if (current_user_can( get_option('eme_cap_edit_events')) ||
          (current_user_can( get_option('eme_cap_author_event')) && $event['event_author']==$current_userid)) {
         $info = array('title' => __ ( "Edit Recurrence", 'events-made-easy') . " '" . eme_translate($event['event_name']) . "'");
         eme_event_form ($event, $info, 1);
      } else {
         $feedback_message = sprintf(__("You have no right to update '%s'",'events-made-easy'),eme_translate($event['event_name']));
         eme_recurrences_table ($feedback_message);
      }
      return;
   }
   
   if ($action == "-1" || $action == "") {
      // No action, only showing the events list
      if (!empty($_GET['recurrences'])) 
	      eme_recurrences_table();
      else
	      eme_events_table();
      return;
   }
}

// array of all pages, bypasses the filter I set up :)
function eme_get_all_pages() {
   global $wpdb;
   $query = "SELECT id, post_title FROM " . $wpdb->prefix . "posts WHERE post_type = 'page' AND post_status='publish'";
   $pages = $wpdb->get_results ( $query, ARRAY_A );
   // get_pages() is better, but uses way more memory and it might be filtered by eme_filter_get_pages()
   //$pages = get_pages();
   $output = array ();
   $output[] = __( 'Please select a page','events-made-easy');
   foreach ( $pages as $page ) {
      $output[$page['id']] = $page['post_title'];
   // $output[$page->ID] = $page->post_title;
   }
   return $output;
}

//This is the content of the event page
function eme_events_page_content() {
   global $eme_timezone, $eme_plugin_url;

   $page_body="";
   if (isset($_GET['eme_cancel_payment'])) {
      $payment_randomid=eme_sanitize_request($_REQUEST['eme_cancel_payment']);
      return eme_cancel_payment_form($payment_randomid);

   } elseif (isset($_GET['eme_cancel_signup'])) {
      $signup_randomid=eme_sanitize_request($_REQUEST['eme_cancel_signup']);
      $res = eme_cancel_task_signup($signup_randomid);
      if ($res === false) {
	      return "<div class='eme-message-error eme-subscribe-message-error'>".__("Task signup cancellation failed.",'events-made-easy')."</div>";
      } else {
	      return "<div class='eme-message-success eme-subscribe-message-success'>".__("You have successfully cancelled your signup for this task.",'events-made-easy')."</div>";
      }

   } elseif (isset($_GET['eme_unsub'])) {
      // lets act as if the unsub shortcode is on the page
      $atts=array();
      return eme_unsubform_shortcode($atts);

   } elseif (isset($_GET['eme_sub_confirm']) && isset($_GET['eme_sub_nonce'])) {
      $eme_email=eme_sanitize_email($_GET['eme_sub_confirm']);
      $eme_lastname = isset($_GET['lastname']) ? eme_sanitize_request($_GET['lastname']) : '';
      $eme_firstname = isset($_GET['firstname']) ? eme_sanitize_request($_GET['firstname']) : '';
      if (!empty($_GET['g'])) {
              $eme_email_groups=eme_sanitize_request($_GET['g']);
              $eme_email_groups_arr=explode(',',$eme_email_groups);
              if (!eme_array_integers($eme_email_groups_arr))
                      $eme_email_groups_arr=array();
      } else {
              $eme_email_groups='';
              $eme_email_groups_arr=array();
      }
      if (wp_verify_nonce(eme_sanitize_request($_GET['eme_sub_nonce']),"sub $eme_lastname$eme_firstname$eme_email$eme_email_groups")) {
              $res=eme_sub_do($eme_lastname,$eme_firstname,$eme_email,$eme_email_groups_arr);
	      if ($res)
		      return "<div class='eme-message-success eme-subscribe-message-success'>".__("You have been subscribed.",'events-made-easy')."</div>";
	      else
		      return "<div class='eme-message-error eme-subscribe-message-error'>".__("Subscription failed.",'events-made-easy')."</div>";
      } else {
              return "<div class='eme-message-error eme-subscribe-message-error'>".__("This link is not (or no longer) valid.",'events-made-easy')."</div>";
      }

   } elseif (isset($_GET['eme_unsub_confirm']) && isset($_GET['eme_unsub_nonce'])) {
      $eme_email=eme_sanitize_email($_GET['eme_unsub_confirm']);
      if (!empty($_GET['g'])) {
              $eme_email_groups=eme_sanitize_request($_GET['g']);
              $eme_email_groups_arr=explode(',',$eme_email_groups);
              if (!eme_array_integers($eme_email_groups_arr))
                      $eme_email_groups_arr=array();
      } else {
              $eme_email_groups='';
              $eme_email_groups_arr=array();
      }
      if (wp_verify_nonce(eme_sanitize_request($_GET['eme_unsub_nonce']),"unsub $eme_email$eme_email_groups")) {
              eme_unsub_do($eme_email,$eme_email_groups_arr);
              return "<div class='eme-message-success eme-unsubscribe-message-success'>".__("You have been unsubscribed.",'events-made-easy')."</div>";
      } else {
              return "<div class='eme-message-error eme-unsubscribe-message-error'>".__("This link is not (or no longer) valid.",'events-made-easy')."</div>";
      }

   } elseif (isset($_GET['eme_gdpr_approve']) && isset($_GET['eme_gdpr_nonce'])) {
      $eme_email=eme_sanitize_email($_GET['eme_gdpr_approve']);
      if (wp_verify_nonce(eme_sanitize_request($_GET['eme_gdpr_nonce']),"gdpr $eme_email")) {
	      eme_update_email_gdpr($eme_email);
	      return eme_gdpr_approve_show();
      } else {
	      return "<div class='eme-message-error eme-gdpr-message-error'>".__("This link is no longer valid, please request a new link.",'events-made-easy')."</div>";
      }

   } elseif (isset($_GET['eme_gdpr']) && isset($_GET['eme_gdpr_nonce'])) {
      $eme_email=eme_sanitize_email($_GET['eme_gdpr']);
      if (wp_verify_nonce(eme_sanitize_request($_GET['eme_gdpr_nonce']),"gdpr $eme_email")) {
	      return eme_show_personal_info($eme_email);
      } else {
	      return "<div class='eme-message-error eme-gdpr-message-error'>".__("This link is no longer valid, please request a new link.",'events-made-easy')."</div>";
      }

   } elseif (!empty($_GET['eme_cpi']) && !empty($_GET['eme_cpi_nonce']) && !empty($_GET['email'])) {
      $person_id=intval($_GET['eme_cpi']);
      $orig_email=eme_sanitize_email($_GET['email']);
      if (wp_verify_nonce(eme_sanitize_request($_GET['eme_cpi_nonce']),"change_pi $person_id $orig_email")) {
	      return eme_cpi_form($person_id);
      } else {
	      return "<div class='eme-message-error eme-cpi-message-error'>".__("This link is no longer valid, please request a new link.",'events-made-easy')."</div>";
      }

   } elseif ((get_query_var('eme_check_attendance')||get_query_var('eme_check_rsvp')) && get_query_var('eme_pmt_rndid')) {
      $payment_randomid=eme_sanitize_request(get_query_var('eme_pmt_rndid'));
      $booking_id=eme_sanitize_request(get_query_var('booking_id')); // this one is optional
      $payment = eme_get_payment(0,$payment_randomid);
      if ($payment['member_id']>0) {
	      return "<div class='eme-message-error eme-attendance-message-error'>".__('Attendance check only valid for events and bookings, not members','events-made-easy')."</div>";
      }
      $booking_ids = eme_get_randompayment_booking_ids($payment_randomid);
      if (!$booking_id || !in_array($booking_id,$booking_ids)) {
	      return "<div class='eme-message-error eme-attendance-message-error'>".__('Invalid URL','events-made-easy')."</div>";
      }
      $booking = eme_get_booking($booking_id);
      $event = eme_get_event($booking['event_id']);
      if (empty($event)) {
	      return "<div class='eme-message-error eme-attendance-message-error'>".__('No such event','events-made-easy')."</div>";
      }
      if (!empty($booking['booking_paid'])) {
	      $img="<img src='".$eme_plugin_url."images/good-48.png'>";
	      $format = "<div class='eme-message-success eme-attendance-message-success'>$img".__('Payment ok','events-made-easy')."</div>";
      } else {
	      $img="<img src='".$eme_plugin_url."images/error-48.png'>";
	      $format = "<div class='eme-message-error eme-attendance-message-error'>$img".__('Payment not ok','events-made-easy')."</div>";
      }
      // if not logged in or not enough rights, just show that the payment is ok or not and return
      if (!current_user_can(get_option('eme_cap_attendancecheck')) && !current_user_can(get_option('eme_cap_approve')) && !current_user_can(get_option('eme_cap_registrations'))) {
	      #return "<div class='eme-message-error eme-attendance-message-error'>".__("Only WP people with the correct rights can use this link.",'events-made-easy')."</div>";
	      if (!empty($event['event_properties']['attendance_unauth_scan_tpl'])) {
		      $format.="<div class='eme-message-info eme-attendance-message-info'>";
		      $tpl=eme_get_template_format($event['event_properties']['attendance_unauth_scan_tpl']);
		      $format.=eme_replace_booking_placeholders($tpl,$event,$booking);
		      $format.="</div>";
	      } elseif (!eme_is_empty_string($event['event_properties']['attendance_unauth_scan_format'])) {
		      $format.="<div class='eme-message-info eme-attendance-message-info'>";
		      $format.=eme_replace_booking_placeholders($event['event_properties']['attendance_unauth_scan_format'],$event,$booking);
		      $format.="</div>";
	      }
	      return $format;
      }
      if (!empty($event['event_properties']['attendance_auth_scan_tpl'])) {
	      $format.="<div class='eme-message-info eme-attendance-message-info'>";
	      $tpl=eme_get_template_format($event['event_properties']['attendance_auth_scan_tpl']);
	      $format.=eme_replace_booking_placeholders($tpl,$event,$booking);
	      $format.="</div>";
      } elseif (!eme_is_empty_string($event['event_properties']['attendance_auth_scan_format'])) {
	      $format.="<div class='eme-message-info eme-attendance-message-info'>";
	      $format.=eme_replace_booking_placeholders($event['event_properties']['attendance_auth_scan_format'],$event,$booking);
	      $format.="</div>";
      }
      // no more processing if not paid
      if (empty($booking['booking_paid'])) {
	      return $format;
      }

      $eme_date_obj_now=new ExpressiveDate("now",$eme_timezone);
      $eme_start_date_obj = new ExpressiveDate($event['event_start'],$eme_timezone);
      $eme_end_date_obj = new ExpressiveDate($event['event_end'],$eme_timezone);
      $begin_difference = round($eme_date_obj_now->getDifferenceInHours($eme_start_date_obj));
      $end_difference = round($eme_end_date_obj->getDifferenceInHours($eme_date_obj_now));
      if ($begin_difference>intval($event['event_properties']['attendance_begin'])) {
	      $img="<img src='".$eme_plugin_url."images/error-48.png'>";
	      $format .= "<div class='eme-message-error eme-attendance-message-error'>$img".__('No entry allowed yet','events-made-easy')."</div>";
      } elseif ($end_difference>intval($event['event_properties']['attendance_end'])) {
	      $img="<img src='".$eme_plugin_url."images/error-48.png'>";
	      $format .= "<div class='eme-message-error eme-attendance-message-error'>$img".__('No entry allowed anymore','events-made-easy')."</div>";
      } else {
	      eme_update_attendance_count($booking_id);
	      $attendance_count = eme_get_attendance_count($booking_id);
	      $seats_booked = $booking['booking_seats'];
	      if ($attendance_count>$seats_booked) {
		      $img="<img src='".$eme_plugin_url."images/error-48.png'>";
		      $format .= "<div class='eme-message-error eme-attendance-message-error'>$img".sprintf(__('Access denied: scan count=%d, max count=%d','events-made-easy'),$attendance_count,$seats_booked)."</div>";
	      } else {
		      $img="<img src='".$eme_plugin_url."images/good-48.png'>";
		      $format .= "<div class='eme-message-success eme-attendance-message-success'>$img".sprintf(__('Access granted: scan count=%d, max count=%d','events-made-easy'),$attendance_count,$seats_booked);
		      $format .= "<br />".sprintf(__('Event : %s','events-made-easy'),eme_esc_html($event['event_name']));
		      if ($event['event_properties']['attendancerecord']) {
			      $res=eme_db_insert_attendance("event",$booking['person_id'],'',$booking['event_id']);
			      if ($res)
				      $format .= "<br />".__('Attendance record added','events-made-easy');
		      }
		      $format .= "</div>";
	      }
      }
      return $format;

   } elseif (get_query_var('eme_check_member')) {
      //if (!current_user_can( get_option('eme_cap_membercheck')) && !current_user_can( get_option('eme_cap_edit_members')) ) {
//	      $img="<img src='".$eme_plugin_url."images/denied-48.png'>";
//	      return "<div class='eme-rsvp-message-error'>$img ".__("Access denied!",'events-made-easy')."</div>";
//     }
      $member_id=intval($_GET['member_id']);
      if (!eme_check_member_url()) {
	      $img="<img src='".$eme_plugin_url."images/error-48.png'>";
	      $format = "<div class='eme-message-error eme-member-message-error'>$img ".sprintf(__('NOK: member %d is either not active or does not exist!','events-made-easy'),$member_id)."</div>";
      } else {
	      $img="<img src='".$eme_plugin_url."images/good-48.png'>";
		      $member=eme_get_member($member_id);
		      $membership=eme_get_membership($member['membership_id']);
		      $format = "<div class='eme-message-success eme-rsvp-message-success'>$img ";
		      if (current_user_can( get_option('eme_cap_membercheck')) || current_user_can( get_option('eme_cap_edit_members')) ) {
			      eme_update_member_lastseen($member_id);
			      $eme_membership_attendance_msg = eme_nl2br_save_html(get_option('eme_membership_attendance_msg'));
			      $format .= eme_replace_member_placeholders($eme_membership_attendance_msg,$membership,$member);
			      if ($membership['properties']['attendancerecord']) {
				      $res=eme_db_insert_attendance("membership",$member['person_id'],'',$member['membership_id']);
				      if ($res)
					      $format .= "<br />".__('Attendance record added','events-made-easy');
			      }
		      } else {
			      $eme_membership_attendance_msg = eme_nl2br_save_html(get_option('eme_membership_unauth_attendance_msg'));
			      $format .= eme_replace_member_placeholders($eme_membership_attendance_msg,$membership,$member);
		      }
		      $format .= "</div>";
      }
      return $format;
   } elseif (get_query_var('eme_pmt_result') && get_query_var('eme_pmt_rndid')) {
      $payment_randomid = eme_sanitize_request(get_query_var('eme_pmt_rndid'));
      $payment = eme_get_payment(0,$payment_randomid);
      if (!$payment) {
	    $format = "<div class='eme-message-error eme-rsvp-message-error'>".__('Nothing linked to this payment id','events-made-easy')."</div>";
            return $format;
      }
      $paid = eme_get_payment_paid($payment);
      $pg_pid = $payment['pg_pid'];
      // eme_get_payment_paid calculates the payment status of $payment, but this is done for each related booking or member
      // So, if a user arrives at a payment gateway and doesn't do anything but clicks on 'back to website', he might arrive here too
      //     but the payment might already be paid (for an active member extension for example), so next to that we check if pg_handled==1
      //     to make sure the payment was actually handled by a payment gateway
      //     (pg_handled gets set to 0 if a new pg pid gets generated, see function eme_update_payment_pg_pid, and gets set to 1 if a payment is handled via a payment gateway)
 
      // mollie updates the state of the payment before returning to the success/failure url, but it doesn't make a distinction for success/failure url, so we check it here
      $result = get_query_var('eme_pmt_result');
      if ($result == 'mollie') {
	      // we can get to the returnurl (this section) faster than the webhook, so act as if we received a notification here too
	      if (($payment['pg_handled'] == 0 || !$paid) && !empty($pg_pid))
		      eme_notification_mollie($pg_pid);
	      // the state can change after the last function call, so check it
	      $payment = eme_get_payment(0,$payment_randomid);
	      $paid = eme_get_payment_paid($payment);
	      if (empty($pg_pid)) {
		      $result = 'fail';
	      } elseif ($payment['pg_handled'] == 1 && $paid) {
		      $result = 'success';
	      } else {
		      $result = 'fail';
	      }
      }
      if ($result == 'payconiq') {
	      // we can get to the returnurl (this section) faster than the webhook, so act as if we received a notification here too
	      if (($payment['pg_handled'] == 0 || !$paid) && !empty($pg_pid))
		      eme_notification_payconiq($pg_pid);
	      // the state can change after the last function call, so check it
	      $payment = eme_get_payment(0,$payment_randomid);
	      $paid = eme_get_payment_paid($payment);
	      if (empty($pg_pid)) {
		      $result = 'fail';
	      } elseif ($payment['pg_handled'] == 1 && $paid) {
		      $result = 'success';
	      } else {
		      $result = 'fail';
	      }
      }
      if ($result == 'paypal') {
	      if (($payment['pg_handled'] == 0 || !$paid) && !empty($pg_pid))
		      eme_complete_paypal_transaction($payment);
	      // the state can change after the last function call, so check it
	      $payment = eme_get_payment(0,$payment_randomid);
	      $paid = eme_get_payment_paid($payment);
	      if (empty($pg_pid)) {
		      $result = 'fail';
	      } elseif ($payment['pg_handled'] == 1 && $paid) {
		      $result = 'success';
	      } else {
		      $result = 'fail';
	      }
      }
      if ($result == 'stripe') {
	      if (($payment['pg_handled'] == 0 || !$paid) && !empty($pg_pid))
		      eme_complete_stripe_transaction($payment);
	      // the state can change after the last function call, so check it
	      $payment = eme_get_payment(0,$payment_randomid);
	      $paid = eme_get_payment_paid($payment);
	      if (empty($pg_pid)) {
		      $result = 'fail';
	      } elseif ($payment['pg_handled'] == 1 && $paid) {
		      $result = 'success';
	      } else {
		      $result = 'fail';
	      }
      }
      if ($result == 'instamojo') {
	      if (($payment['pg_handled'] == 0 || !$paid) && !empty($pg_pid))
		      eme_complete_instamojo_transaction($payment);
	      // the state can change after the last function call, so check it
	      $payment = eme_get_payment(0,$payment_randomid);
	      $paid = eme_get_payment_paid($payment);
	      if (empty($pg_pid)) {
		      $result = 'fail';
	      } elseif ($payment['pg_handled'] == 1 && $paid) {
		      $result = 'success';
	      } else {
		      $result = 'fail';
	      }
      }
      if ($result == 'fondy') {
	      if (($payment['pg_handled'] == 0 || !$paid) && !empty($pg_pid))
		      eme_complete_fondy_transaction($payment);
	      // the state can change after the last function call, so check it
	      $payment = eme_get_payment(0,$payment_randomid);
	      $paid = eme_get_payment_paid($payment);
	      if (empty($pg_pid)) {
		      $result = 'fail';
	      } elseif ($payment['pg_handled'] == 1 && $paid) {
		      $result = 'success';
	      } else {
		      $result = 'fail';
	      }
      }
      if ($payment['member_id']>0) {
	 $member = eme_get_member($payment['member_id']);
         $membership = eme_get_membership($member['membership_id']);
	 if ($result == 'success') {
		 if (!eme_is_empty_string($membership['properties']['payment_success_text']))
			 $format = $membership['properties']['payment_success_text'];
		 elseif (!empty($membership['properties']['payment_success_tpl']))
			 $format = eme_get_template_format($membership['properties']['payment_success_tpl']);
		 else
			 $format = get_option('eme_payment_member_succes_format');
		 if (eme_is_empty_string($format)) $format = "<div class='eme-message-success eme-member-message-success'>".__('Payment success for your membership signup for #_MEMBERSHIPNAME','events-made-easy')."</div>";
	 } else {
		 $format = get_option('eme_payment_member_fail_format');
		 if (eme_is_empty_string($format)) $format = "<div class='eme-message-error eme-member-message-error'>".__('Payment failed for your membership signup for #_MEMBERSHIPNAME','events-made-easy')."</div>";
	 }
	 return eme_replace_member_placeholders($format,$membership,$member);
      } else {
	 $booking_ids = eme_get_randompayment_booking_ids($payment_randomid);
	 if (count($booking_ids)==1) {
	    $is_multi=0;
	 } else {
	    $is_multi=1;
	 }
         if ($result == 'success') {
            $format = get_option('eme_payment_succes_format');
	    if (eme_is_empty_string($format)) {
		    if ($is_multi)
			    $format = "<div class='eme-message-success eme-rsvp-message-success'>".__('Payment success for your booking for #_EVENTNAME','events-made-easy')."</div>";
		    else
			    $format = "<div class='eme-message-success eme-rsvp-message-success'>".__('Payment success','events-made-easy')."</div>";
	    }
         } else {
            $format = get_option('eme_payment_fail_format');
	    if (eme_is_empty_string($format)) {
		    if ($is_multi)
			    $format = "<div class='eme-message-error eme-rsvp-message-error'>".__('Payment failed','events-made-easy')."</div>";
		    else
			    $format = "<div class='eme-message-error eme-rsvp-message-error'>".__('Payment failed for your booking for #_EVENTNAME','events-made-easy')."</div>";
	    }
         }
         if ($booking_ids) {
            // since each booking is for a different event, we can't know which one to show
            // so we show only the first one
            $booking = eme_get_booking($booking_ids[0]);
	    $event = eme_get_event($booking['event_id']);
	    if (!empty($event)) {
		    return eme_replace_booking_placeholders($format,$event,$booking);
	    } else {
		    $format = "<div class='eme-message-error eme-rsvp-message-error'>".__('No such event','events-made-easy')."</div>";
		    return $format;
	    }
         } else {
	    $format = "<div class='eme-message-error eme-rsvp-message-error'>".__('Nothing linked to this payment id','events-made-easy')."</div>";
            return $format;
         }
      }
   } elseif (get_query_var('eme_pmt_rndid')) {
      $payment_randomid=eme_sanitize_request(get_query_var('eme_pmt_rndid'));
      $payment=eme_get_payment(0,$payment_randomid);
      if (get_query_var('res_fail')) {
	      $resultcode=1;
      } else {
	      $resultcode=0;
      }
      // the next div is the same one as in eme_rsvp.php and eme_members.php for the payment form
      $page_body = "<div id='div_eme-payment-form' class='eme-payment-form-div'>";
      // the last 1 to indicate the form is shown standalone and not as a result of a booking
      $page_body .= eme_payment_form($payment['id'],$resultcode,1);
      $page_body .= "</div>";
      return $page_body;
   }

   if (get_query_var('eme_city')) {
      $eme_city=eme_sanitize_request(get_query_var('eme_city'));
      $location_ids = join(',',eme_get_city_location_ids($eme_city));
      $stored_format = get_option('eme_event_list_item_format');
      if (count($location_ids)>0) {
         $format_header = get_option('eme_event_list_item_format_header' );
         if (eme_is_empty_string($format_header)) $format_header = DEFAULT_EVENT_LIST_HEADER_FORMAT;
         $format_footer = get_option('eme_event_list_item_format_footer' );
         if (eme_is_empty_string($format_footer)) $format_footer = DEFAULT_EVENT_LIST_FOOTER_FORMAT;
         $page_body = eme_get_events_list ( get_option('eme_event_list_number_items' ), "future", "ASC", $stored_format, $format_header,$format_footer, 0, '','',0,'','',0,0,$location_ids);
      } else {
         $page_body = "<span class='events-no-events'>" . do_shortcode(get_option('eme_no_events_message')) . "</span>";
      }
      return $page_body;
   }
   if (get_query_var('eme_country')) {
      $eme_country=eme_sanitize_request(get_query_var('eme_country'));
      $location_ids = join(',',eme_get_country_location_ids($eme_country));
      $stored_format = get_option('eme_event_list_item_format');
      if (count($location_ids)>0) {
         $format_header = get_option('eme_event_list_item_format_header' );
         if (eme_is_empty_string($format_header)) $format_header = DEFAULT_EVENT_LIST_HEADER_FORMAT;
         $format_footer = get_option('eme_event_list_item_format_footer' );
         if (eme_is_empty_string($format_footer)) $format_footer = DEFAULT_EVENT_LIST_FOOTER_FORMAT;
         $page_body = eme_get_events_list ( get_option('eme_event_list_number_items' ), "future", "ASC", $stored_format, $format_header,$format_footer, 0, '','',0,'','',0,0,$location_ids);
      } else {
         $page_body = "<span class='events-no-events'>" . do_shortcode(get_option('eme_no_events_message')) . "</span>";
      }
      return $page_body;
   }
   if (!get_query_var('calendar_day') && get_query_var('location_id')) {
      $location = eme_get_location ( eme_sanitize_request(get_query_var('location_id')));
      if (!empty($location)) {
	      $single_location_format = get_option('eme_single_location_format' );
	      $page_body = eme_replace_locations_placeholders ( $single_location_format, $location );
      } else {
	      $page_body = "<span class='events-no-events'>" . __('No such location','events-made-easy') . "</span>";
      }
      return $page_body;
   }
   if (!get_query_var('calendar_day') && get_query_var('eme_event_cat')) {
      $format_header = get_option('eme_cat_event_list_item_format_header' );
      if (eme_is_empty_string($format_header)) $format_header = DEFAULT_CAT_EVENT_LIST_HEADER_FORMAT;
      $format_footer = get_option('eme_cat_event_list_item_format_footer' );
      if (eme_is_empty_string($format_footer)) $format_footer = DEFAULT_CAT_EVENT_LIST_FOOTER_FORMAT;
      $eme_event_cat=eme_sanitize_request(get_query_var('eme_event_cat'));
      $cat_ids = join(',',eme_get_category_ids($eme_event_cat));
      $stored_format = get_option('eme_event_list_item_format');
      if (!empty($cat_ids)) {
         $page_body = eme_get_events_list ( get_option('eme_event_list_number_items' ), "future", "ASC", $stored_format, $format_header,$format_footer, 0, $cat_ids);
      } else {
         $page_body = "<span class='events-no-events'>" . do_shortcode(get_option('eme_no_events_message')) . "</span>";
      }
      return $page_body;
   }

   //if (isset ( $_REQUEST['event_id'] ) && $_REQUEST['event_id'] != '') {
   if (eme_is_single_event_page()) {
      // single event page
      $event_id = eme_sanitize_request(get_query_var('event_id'));
      return eme_display_single_event($event_id);
   } elseif (get_query_var('calendar_day')) {
      // we don't use urldecode on the _GET params, since we pass them the url-way to eme_get_events_list
      $scope = urlencode(eme_sanitize_request(get_query_var('calendar_day')));
      $location_id = isset( $_GET['location_id'] ) ? eme_sanitize_request($_GET['location_id']) : '';
      $category = isset( $_GET['category'] ) ? eme_sanitize_request($_GET['category']) : '';
      $notcategory = isset( $_GET['notcategory'] ) ? eme_sanitize_request($_GET['notcategory']) : '';
      $author = isset( $_GET['author'] ) ? eme_sanitize_request($_GET['author']) : '';
      $contact_person = isset( $_GET['contact_person'] ) ? eme_sanitize_request($_GET['contact_person']) : '';
      // the hash char and everything following it in a GET is not getting through a browszr request, so if it passed through via the calendar, we used _MYSELF, and here we restore it again
      $author = str_replace('_MYSELF', '#_MYSELF' ,$author );
      $contact_person = str_replace('_MYSELF', '#_MYSELF' ,$contact_person );

      $page_body = eme_get_events_list ("limit=0&scope=$scope&category=$category&author=$author&contact_person=$contact_person&location_id=$location_id&notcategory=$notcategory");
      return $page_body;
   } else {
      // Defaults events page
      if (get_option('eme_display_calendar_in_events_page' )) {
         $page_body = eme_get_calendar ('full=1');
      }
      if (get_option('eme_display_events_in_events_page' )) {
         $scope = isset($_GET['scope']) ? urlencode(eme_sanitize_request($_GET['scope'])) : "future";
         $page_body .= eme_get_events_list ("scope=$scope");
      }
      return $page_body;
   }
}

function eme_events_count_for($date) {
   global $wpdb;
   $table_name = $wpdb->prefix . EVENTS_TBNAME;
   $conditions = array ();
   if (!eme_is_admin_request()) {
      if (is_user_logged_in()) {
         $conditions[] = "event_status IN (".EME_EVENT_STATUS_PUBLIC.",".EME_EVENT_STATUS_PRIVATE.")";
      } else {
         $conditions[] = "event_status=".EME_EVENT_STATUS_PUBLIC;
      }
   }
   $conditions[] = "((event_start LIKE '$date%') OR (event_start <= '$date 00:00:00' AND event_end >= '$date 23:59:59'))";
   $where = implode ( " AND ", $conditions );
   if ($where != "")
      $where = " WHERE " . $where;
   $sql = "SELECT COUNT(*) FROM  $table_name $where";
   return $wpdb->get_var ( $sql );
}

// filter function to call the event page when appropriate
function eme_filter_events_page($data) {

   global $wp_current_filter, $eme_timezone;
   // we need to make sure we do this only once. Reason being: other plugins can call the_content as well
   // Suppose you add a shortcode from another plugin to the detail part of an event and that other plugin
   // calls apply_filter('the_content'), then this would cause recursion since that call would call our filter again
   // If the_content is the current filter definition (last element in the array), when there's more than one
   // (this is possible since one filter can call another, apply_filters does this), we can be in such a loop
   // And since our event content is only meant to be shown as content of a page (the_content is then the only element
   // in the $wp_current_filter array), we can then skip it
   $eme_count_arr=array_count_values($wp_current_filter);
 
   // we change the content of the page only if we're "in the loop",
   // otherwise this filter also gets applied if e.g. a widget calls
   // the_content or the_excerpt to get the content of a page
   if (in_the_loop() && is_main_query() ) {
	   if (eme_is_events_page() && $eme_count_arr['the_content']==1) {
		   return eme_events_page_content();
	   } else {
		   $post_id = get_the_ID();
		   $access_allowed = eme_check_access($post_id);
		   if ($access_allowed) {
			   return $data;
		   } else {
			   $custom_values = @get_post_custom();
			   $eme_access_denied = !empty( $custom_values['eme_access_denied'] ) ? eme_get_template_format(intval($custom_values['eme_access_denied'][0])): '';
			   if (empty($eme_access_denied))
				   $eme_access_denied = get_option('eme_page_access_denied');
			   $eme_access_denied = eme_replace_generic_placeholders($eme_access_denied);
			   return $eme_access_denied;
		   }
	   }
   } else {
      return $data;
   }
}
add_filter ( 'the_content', 'eme_filter_events_page' );

//function eme_filter_wpautop($content) {
//      if (get_option('eme_disable_wpautop')) {
//	      remove_filter('the_content', 'wpautop');
//	      remove_filter('the_excerpt', 'wpautop');
//	      add_filter('the_content', function ($pee) { return eme_nl2br_save_html($pee); } );
//	      add_filter('the_excerpt', function ($pee) { return eme_nl2br_save_html($pee); } );
//      }
//      return $content;
//}
//add_filter ( 'the_content', 'eme_filter_wpautop' );

$eme_use_is_page_for_title=get_option('eme_use_is_page_for_title');
function eme_page_title($data, $post_id=null) {
   global $eme_use_is_page_for_title;
   $events_page_id = eme_get_events_page_id();
   $events_page = get_page ( $events_page_id );
   $events_page_title = $events_page->post_title;

   // make sure we only replace the title for the events page, not anything
   if (($data == $events_page_title) && eme_is_events_page() &&
       ((!$eme_use_is_page_for_title && in_the_loop()) || $eme_use_is_page_for_title) ) {
      if (get_query_var('eme_pmt_rndid')) {
         $payment_randomid=eme_sanitize_request(get_query_var('eme_pmt_rndid'));
	 $payment=eme_get_payment(0,$payment_randomid);
         if ($payment['member_id']>0) {
                 $page_title = eme_sanitize_request(__('Membership payment page','events-made-easy'));
         } else {
		 $booking_ids = eme_get_payment_booking_ids($payment['id']);
		 if (count($booking_ids)==1) {
			 $event = eme_get_event_by_booking_id($booking_ids[0]);
			 if (empty($event))
				 return $data;
			 elseif (!eme_is_empty_string($event['event_page_title_format']))
				 $stored_page_title_format = $event['event_page_title_format'];
			 elseif ($event['event_properties']['event_page_title_format_tpl']>0)
				 $stored_page_title_format = eme_get_template_format_plain($event['event_properties']['event_page_title_format_tpl']);
			 else
				 $stored_page_title_format = get_option('eme_event_page_title_format' );
			 $page_title = eme_replace_event_placeholders($stored_page_title_format, $event);
		 } else {
			 $page_title = get_option('eme_events_page_title' );
		 }
	 }
	 $res = $page_title;
      } elseif (eme_is_single_event_page()) {
         // single event page
         $event_id = eme_sanitize_request(get_query_var('event_id'));
         $event = eme_get_event($event_id);
	 if (empty($event)) {
		 $res = $data;
	 } else {
		 if (!eme_is_empty_string($event['event_page_title_format']))
			 $stored_page_title_format = $event['event_page_title_format'];
		 elseif ($event['event_properties']['event_page_title_format_tpl']>0)
			 $stored_page_title_format = eme_get_template_format_plain($event['event_properties']['event_page_title_format_tpl']);
		 else
			 $stored_page_title_format = get_option('eme_event_page_title_format' );
		 $page_title = eme_replace_event_placeholders($stored_page_title_format, $event);
		 $res = $page_title;
	 }
      } elseif (eme_is_single_location_page()) {
         $location = eme_get_location (eme_sanitize_request(get_query_var('location_id')));
	 if (!empty($location)) {
		 $stored_page_title_format = get_option('eme_location_page_title_format' );
		 $page_title = eme_replace_locations_placeholders($stored_page_title_format, $location);
		 $res = $page_title;
	 } else {
		 $res = $data;
	 }
      } elseif (isset($_GET['eme_gdpr_approve']) && isset($_GET['eme_gdpr_nonce'])) {
         $res = get_option('eme_gdpr_approve_page_title');
      } elseif (isset($_GET['eme_gdpr']) && isset($_GET['eme_gdpr_nonce'])) {
         $res = get_option('eme_gdpr_page_title');
      } elseif (isset($_GET['eme_cpi']) && isset($_GET['eme_cpi_nonce'])) {
         $res = get_option('eme_cpi_page_title');
      } elseif (get_query_var('eme_check_attendance') && get_query_var('eme_pmt_rndid')) {
         $res = __('Attendance check','events-made-easy');
      } elseif (get_query_var('eme_check_member') && isset($_GET['member_id'])) {
         $res = __('Membership check','events-made-easy');
      } else {
         // Multiple events page
         $res = get_option('eme_events_page_title' );
      }
      // make sure the filter is not called again (weirdly, the_title filter is called for several parts in a post too)
      // while removing the filter works for most, sometimes it doesn't. Meaning other plugins/themes do weird stuff ...
   } else {
      $res = $data;
   }

   return $res;
}

function eme_html_title($data) {
   if (eme_is_events_page()) {
      if (get_query_var('eme_pmt_rndid')) {
         $payment_randomid=eme_sanitize_request(get_query_var('eme_pmt_rndid'));
	 $payment=eme_get_payment(0,$payment_randomid);
	 if ($payment['member_id']) {
		 $html_title = eme_sanitize_request(__('Membership payment page','events-made-easy'));
	 } else {
		 $booking_ids = eme_get_payment_booking_ids($payment['id']);
		 if (count($booking_ids)==1) {
			 $event = eme_get_event_by_booking_id($booking_ids[0]);
			 if (empty($event)) {
				 return $data;
			 } else {
				 $stored_html_title_format = get_option('eme_event_html_title_format');
				 $html_title = eme_sanitize_request(eme_replace_event_placeholders($stored_html_title_format, $event));
			 }
		 } else {
			 $html_title = eme_sanitize_request(get_option('eme_events_page_title'));
		 }
	 }
	 return $html_title;
      } elseif (eme_is_single_event_page()) {
         // single event page
         $event_id =eme_sanitize_request(get_query_var('event_id'));
         $event = eme_get_event($event_id);
	 if (empty($event)) {
		 return $data;
	 } else {
		 $stored_html_title_format = get_option('eme_event_html_title_format');
		 // no html tags or anything weird in the title: we sanitize it, so it already removes all problems
		 $html_title = eme_sanitize_request(eme_replace_event_placeholders($stored_html_title_format, $event));
		 return $html_title;
	 }
      } elseif (eme_is_single_location_page()) {
         $location_id = eme_sanitize_request(get_query_var('location_id'));
         $location = eme_get_location($location_id);
	 if (empty($location)) {
		 return $data;
	 } else {
		 $stored_html_title_format = get_option('eme_location_html_title_format');
		 // no html tags or anything weird in the title: we sanitize it, so it already removes all problems
		 $html_title = eme_sanitize_request(eme_replace_locations_placeholders($stored_html_title_format, $location));
		 return $html_title;
	 }
      } elseif (isset($_GET['eme_gdpr_approve']) && isset($_GET['eme_gdpr_nonce'])) {
         return __('GDPR approval','events-made-easy');
      } elseif (isset($_GET['eme_gdpr']) && isset($_GET['eme_gdpr_nonce'])) {
         return __('GDPR','events-made-easy');
      } elseif (isset($_GET['eme_cpi']) && isset($_GET['eme_cpi_nonce'])) {
         return __('Change personal info','events-made-easy');
      } elseif (get_query_var('eme_check_attendance') && get_query_var('eme_pmt_rndid')) {
         return __('Attendance check','events-made-easy');
      } elseif (get_query_var('eme_check_member') && isset($_GET['member_id'])) {
         return __('Membership check','events-made-easy');
      } else {
         // Multiple events page
	 // no html tags or anything weird in the title: we sanitize it, so it already removes all problems
         $html_title = eme_sanitize_request(get_option('eme_events_page_title'));
         return $html_title;
      }
   } else {
      return $data;
   }
}
// the filter single_post_title influences the html header title and the page title
// we want to prevent html tags in the html header title (if you add html in the 'single event title format', it will show)
add_filter ( 'single_post_title', 'eme_html_title' );
add_filter ( 'the_title', 'eme_page_title', 10, 2 );

if ($eme_use_is_page_for_title) {
	function eme_remove_title_filter_nav_menu( $nav_menu, $args ) {
		// we are working with menu, so remove the title filter
		remove_filter( 'the_title', 'eme_page_title', 10, 2 );
		return $nav_menu;
	}
	// this filter fires just before the nav menu item creation process
	add_filter( 'pre_wp_nav_menu', 'eme_remove_title_filter_nav_menu', 10, 2 );

	function eme_add_title_filter_non_menu( $items, $args ) {
		// we are done working with menu, so add the title filter back
		add_filter( 'the_title', 'eme_page_title', 10, 2 );
		return $items;
	}
	// this filter fires after nav menu item creation is done
	add_filter( 'wp_nav_menu_items', 'eme_add_title_filter_non_menu', 10, 2 );
}

function eme_post_image_html( $data, $post_id, $post_image_id ) {
	$access_allowed = eme_check_access($post_id);
	if ($access_allowed)
		return $data;
	else
		return '';
}
// filter out the featured image for protected pages
add_filter ( 'post_thumbnail_html', 'eme_post_image_html', 10, 3 );

function eme_filter_comments_access( $data, $post_id ) {
	$access_allowed = eme_check_access($post_id);
	if ($access_allowed)
		return $data;
	else
		return '';
}
// Close comments on the front-end
add_filter('comments_open', 'eme_filter_comments_access', 20, 2);
add_filter('pings_open', 'eme_filter_comments_access', 20, 2);

// Hide existing comments
function eme_filter_comments_array_access( $data, $post_id ) {
        $access_allowed = eme_check_access($post_id);
        if ($access_allowed) {
                return $data;
        } else {
                $empty_arr=array();
                return $empty_arr;
        }
}
add_filter('comments_array', 'eme_filter_comments_array_access', 10, 2);

// let's change the layout of the page if wanted
function eme_single_event_page_template($template) {
   if (eme_is_single_event_page()) {
	   $event=eme_get_event(get_query_var('event_id'));
	   if (!empty($event) && !empty($event['event_properties']['wp_page_template'])) {
		   if ( $overridden_template = locate_template( $event['event_properties']['wp_page_template'] ) ) {
			   return $overridden_template;
		   } else {
			   return $template;
		   }
	   } else {
		   return $template;
	   }
   } else {
	   return $template;
   }
}
add_filter('template_include','eme_single_event_page_template',99);

// let's change the edit url used in wp themes for single events and locations
function eme_edit_post_link($data) {
      if (eme_is_single_event_page()) {
         $event_id =eme_sanitize_request(get_query_var('event_id'));
	 return admin_url("admin.php?page=eme-manager&amp;eme_admin_action=edit_event&amp;event_id=".$event_id);
      } elseif (eme_is_single_location_page()) {
         $location_id =eme_sanitize_request(get_query_var('location_id'));
	 return admin_url("admin.php?page=eme-locations&amp;eme_admin_action=edit_location&amp;location_id=".$location_id);
      } else {
	 return $data;
      }
}
add_filter ('get_edit_post_link', 'eme_edit_post_link');

// add/remove links/menus from the admin bar
// (this works fine, but currently happy with the code above to eme_edit_post_link)
//function eme_admin_bar_render() {
//	global $wp_admin_bar;
//	if (eme_is_single_event_page()) {
//		$event_id =eme_sanitize_request(get_query_var('event_id'));
//		//$wp_admin_bar->remove_menu('edit');
//		$wp_admin_bar->add_menu( array(
//			'parent' => 'edit', // use 'false' for a root menu, or pass the ID of the parent menu
//			'id' => 'edit_event', // link ID, defaults to a sanitized title value
//			'title' => __('Edit Event','events-made-easy'), // link title
//			'href' => admin_url("admin.php?page=eme-manager&amp;eme_admin_action=edit_event&amp;event_id=".$event_id),
//			'meta' => false // array of any of the following options: array( 'html' => '', 'class' => '', 'onclick' => '', target => '', title => '' );
//		));
//	} elseif (eme_is_single_location_page()) {
//		$location_id =eme_sanitize_request(get_query_var('location_id'));
//		//$wp_admin_bar->remove_menu('edit');
//		$wp_admin_bar->add_menu( array(
//			'parent' => 'edit', // use 'false' for a root menu, or pass the ID of the parent menu
//			'id' => 'edit_location', // link ID, defaults to a sanitized title value
//			'title' => __('Edit Location','events-made-easy'), // link title
//			'href' => admin_url("admin.php?page=eme-manager&amp;eme_admin_action=edit_location&amp;location_id=".$location_id),
//			'meta' => false // array of any of the following options: array( 'html' => '', 'class' => '', 'onclick' => '', target => '', title => '' );
//		));
//	}
//}
//add_action( 'wp_before_admin_bar_render', 'eme_admin_bar_render' );

function eme_template_redir() {
# We need to catch the request as early as possible, but
# since it needs to be working for both permalinks and normal,
# I can't use just any action hook. parse_query seems to do just fine
   global $post;
   $redir_url=get_option('eme_redir_protected_pages_url');
   $is_user_logged_in=is_user_logged_in();
   if (!empty($redir_url) && !$is_user_logged_in) {
	   $custom_values = @get_post_custom($post->ID);
	   if (is_array($custom_values)) {
		   $eme_memberships = isset( $custom_values['eme_membershipids'] ) ? $custom_values['eme_membershipids'][0]: '';
		   if (is_serialized($eme_memberships))
			   $page_membershipids=unserialize($eme_memberships);
		   else
			   $page_membershipids=array();
		   if (!empty($page_membershipids)) {
			   $page_permalink=get_permalink();
			   $redir_url=add_query_arg(array('redirect'=>$page_permalink),$redir_url);
			   eme_nocache_headers();
			   wp_redirect(esc_url($redir_url));
			   exit;
		   }
	   }
   }

   if (get_query_var('event_id')) {
      $event_id =eme_sanitize_request(get_query_var('event_id'));
      if (!eme_check_event_exists($event_id)) {
         status_header(404);
         eme_nocache_headers();
         if ('' != get_404_template())
            include( get_404_template() );
         exit;
      }
      $event=eme_get_event($event_id);
      if (!$is_user_logged_in) {
         if ($event['event_status'] == EME_EVENT_STATUS_PRIVATE) {
		 // if the event is private and not logged in: return
		 $redir_url=get_option('eme_redir_priv_event_url');
		 if (!empty($redir_url)) {
			 $page_permalink=get_permalink();
			 $redir_url=add_query_arg(array('redirect'=>$page_permalink),$redir_url);
			 eme_nocache_headers();
			 wp_redirect(esc_url($redir_url));
			 exit;
		 } else {
			 auth_redirect();
		 }
	 } elseif ($event['event_status'] == EME_EVENT_STATUS_DRAFT) {
		 // if the event is draft and not logged in: return not found
		 status_header(404);
		 eme_nocache_headers();
		 if ('' != get_404_template())
			 include( get_404_template() );
		 exit;
	 }
      }
   }
   if (!get_query_var('calendar_day') && get_query_var('location_id')) {
      $location_id =eme_sanitize_request(get_query_var('location_id'));
      if (!eme_check_location_exists($location_id)) {
         status_header(404);
         eme_nocache_headers();
         if ('' != get_404_template())
            include( get_404_template() );
         exit;
      }
   }

   // if we want to show a day and it has only 1 event: redir to that event
   if (get_query_var('calendar_day') && get_option('eme_cal_show_single')) {
	   $scope = urlencode(eme_sanitize_request(get_query_var('calendar_day')));
	   $location_id = isset( $_GET['location_id'] ) ? eme_sanitize_request($_GET['location_id']) : '';
	   $category = isset( $_GET['category'] ) ? eme_sanitize_request($_GET['category']) : '';
	   $notcategory = isset( $_GET['notcategory'] ) ? eme_sanitize_request($_GET['notcategory']) : '';
	   $author = isset( $_GET['author'] ) ? eme_sanitize_request($_GET['author']) : '';
	   $contact_person = isset( $_GET['contact_person'] ) ? eme_sanitize_request($_GET['contact_person']) : '';
	   // the hash char and everything following it in a GET is not getting through a browszr request, so if it passed through via the calendar, we used _MYSELF, and here we restore it again
	   $author = str_replace('_MYSELF', '#_MYSELF' ,$author );
	   $contact_person = str_replace('_MYSELF', '#_MYSELF' ,$contact_person );

	   $events = eme_get_events("limit=0&scope=$scope&category=$category&author=$author&contact_person=$contact_person&location_id=$location_id&notcategory=$notcategory");
	   if (count($events) == 1) {
		   $event = $events[0];
		   eme_nocache_headers();
		   wp_redirect(eme_event_url($event));
		   die;
	   }
   }

   if (isset ( $_GET['eme_bookings'] ) && $_GET['eme_bookings'] == 'report') {
      $public = (isset($_GET['public_access'])) ? intval($_GET['public_access']) : 0;
      $nonce = (isset($_GET['eme_bookings_nonce'])) ? eme_sanitize_request($_GET['eme_bookings_nonce']) : '';
      if (isset($_GET['event_id']) && isset($_GET['template_id']) && isset($_GET['template_id_header'])) {
              if (is_user_logged_in() && current_user_can(get_option('eme_cap_list_registrations'))) {
                           eme_bookings_frontend_csv_report(intval($_GET['event_id']),intval($_GET['template_id']),intval($_GET['template_id_header']));
              } elseif ($public && !empty($nonce) && wp_verify_nonce($nonce,"eme_bookings $public") && !empty($post->post_password) && !post_password_required()) {
                           eme_bookings_frontend_csv_report(intval($_GET['event_id']),intval($_GET['template_id']),intval($_GET['template_id_header']));
              }
      }
      exit;
   }
   if (isset ( $_GET['eme_attendees'] ) && $_GET['eme_attendees'] == 'report') {
      $public = (isset($_GET['public_access'])) ? intval($_GET['public_access']) : 0;
      $nonce = (isset($_GET['eme_attendees_nonce'])) ? eme_sanitize_request($_GET['eme_attendees_nonce']) : '';
      if (isset($_GET['scope']) && isset($_GET['event_template_id']) && isset($_GET['attend_template_id'])) {
              if (is_user_logged_in() && current_user_can(get_option('eme_cap_list_registrations'))) {
		      eme_attendees_frontend_csv_report(eme_sanitize_request($_GET['scope']),eme_sanitize_request($_GET['category']),eme_sanitize_request($_GET['notcategory']),intval($_GET['event_template_id']),intval($_GET['attend_template_id']));
              } elseif ($public && !empty($nonce) && wp_verify_nonce($nonce,"eme_attendees $public") && !empty($post->post_password) && !post_password_required()) {
		      // $post->post_password not empty means a password is set, post_password_required() returning false means the password is already entered
                      eme_attendees_frontend_csv_report(eme_sanitize_request($_GET['scope']),eme_sanitize_request($_GET['category']),eme_sanitize_request($_GET['notcategory']),intval($_GET['event_template_id']),intval($_GET['attend_template_id']));
              }
      }
      exit;
   }

   if (isset ( $_GET['eme_members'] ) && $_GET['eme_members'] == 'report') {
           $public = (isset($_GET['public_access'])) ? intval($_GET['public_access']) : 0;
           $group_id = (isset($_GET['group_id'])) ? intval($_GET['group_id']) : 0;
           $membership_id = (isset($_GET['membership_id'])) ? intval($_GET['membership_id']) : 0;
           $template_id = (isset($_GET['template_id'])) ? intval($_GET['template_id']) : 0;
           $template_id_header = (isset($_GET['template_id_header'])) ? intval($_GET['template_id_header']) : 0;
           $nonce = (isset($_GET['eme_members_nonce'])) ? eme_sanitize_request($_GET['eme_members_nonce']) : '';
           if (is_user_logged_in() && current_user_can(get_option('eme_cap_list_members'))) {
                   eme_members_frontend_csv_report($group_id,$membership_id,$template_id,$template_id_header);
           } elseif ($public && !empty($nonce) && wp_verify_nonce($nonce,"eme_members $public") && !empty($post->post_password) && !post_password_required()) {
		   // $post->post_password not empty means a password is set, post_password_required() returning false means the password is already entered
                   eme_members_frontend_csv_report($group_id,$membership_id,$template_id,$template_id_header);
           }
           exit;
   }

   if (eme_is_single_event_page() || eme_is_single_location_page()) {
      eme_nocache_headers();
      // remove rel_canonical from the header, EME generates it's own one
      remove_action( 'wp_head', 'rel_canonical' );
      // remove yoast SEO from the header, EME generates it's own one
      if (function_exists('YoastSEO')) {
	      $front_end = YoastSEO()->classes->get( Yoast\WP\SEO\Integrations\Front_End_Integration::class );
	      remove_action( 'wpseo_head', [ $front_end, 'present_head' ], -9999 );
      }
   }
}

// this is used to add some extra text in the admin pages overview for the eme page
function eme_add_post_state($post_states, $post) {
   $events_page_id = eme_get_events_page_id();
   if ($events_page_id == $post->ID) {
         $post_states['page_for_eme'] = __('Events Made Easy plugin page','events-made-easy');
   }
   return $post_states;
}
add_filter('display_post_states','eme_add_post_state', 10, 2);

function eme_replace_generic_placeholders($format, $target="html") {
   global $eme_timezone;

   $email_target = 0;
   $orig_target = $target;
   if ($target == "htmlmail") {
           $email_target = 1;
           $target = "html";
   }

   $eme_date_obj_now=new ExpressiveDate("now",$eme_timezone);
   $current_userid=get_current_user_id();
   $needle_offset=0;
   preg_match_all('/#(ESC|URL)?@?_?[A-Za-z0-9_]+(\{(?>[^{}]+|(?2))*\})*+/', $format, $placeholders,PREG_OFFSET_CAPTURE);
   foreach($placeholders[0] as $orig_result) {
      $result = $orig_result[0];
      $orig_result_needle = $orig_result[1]-$needle_offset;
      $orig_result_length = strlen($orig_result[0]);
      $need_escape = 0;
      $need_urlencode = 0;
      $found = 1;
      if (strstr($result,'#ESC')) {
         $result = str_replace("#ESC","#",$result);
         $need_escape=1;
      } elseif (strstr($result,'#URL')) {
         $result = str_replace("#URL","#",$result);
         $need_urlencode=1;
      }
      $replacement = "";
      // matches all fields placeholder
      if (preg_match('/#_CURDATE\{(.+?)\}$/', $result,$matches)) {
         $replacement = eme_localized_date($eme_date_obj_now->getDateTime(),$eme_timezone,$matches[1]);
      } elseif (preg_match('/#_CURDATE$/', $result)) {
         $replacement = eme_localized_date($eme_date_obj_now->getDateTime(),$eme_timezone);
      } elseif (preg_match('/#_CURTIME\{(.+?)\}$/', $result,$matches)) {
         $replacement = eme_localized_time($eme_date_obj_now->getDateTime(),$eme_timezone,$matches[1]);
      } elseif (preg_match('/#_CURTIME$/', $result)) {
         $replacement = eme_localized_time($eme_date_obj_now->getDateTime(),$eme_timezone);
      } elseif (preg_match('/#_SINGLE_EVENTPAGE_EVENTID/', $result)) {
         // returns the event id of the single event page currently shown
         if (eme_is_single_event_page()) {
               $eventid_or_slug = eme_sanitize_request(get_query_var('event_id'));
	       $event=eme_get_event($eventid_or_slug);
	       if (!empty($event))
		       $replacement=$event['event_id'];
	 }
      } elseif (preg_match('/#_CALENDAR_DAY/', $result)) {
         $day_key = get_query_var('calendar_day');
         $replacement=eme_localized_date($day_key,$eme_timezone);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }
      } elseif (preg_match('/^#_WPID$/', $result, $matches)) {
	 if ($current_userid)
		 $replacement=$current_userid;
      } elseif (preg_match('/^#_USER_HAS_CAP\{(.+?)\}$/', $result, $matches)) {
         $caps=$matches[1];
	 if (preg_match('/#_/',$caps)) {
		 // if it contains another placeholder as value, don't do anything here
		 $found=0;
	 } else {
		 $caps_arr = explode(",", $caps);
		 $replacement = 0;
		 if ($current_userid) {
			 foreach ($caps_arr as $cap) {
				 if (current_user_can($cap)) {
					 $replacement=1;
					 break;
				 }
			 }
		 }
	 }
      } elseif (preg_match('/^#_USER_HAS_ROLE\{(.+?)\}$/', $result, $matches)) {
         $roles=$matches[1];
	 if (preg_match('/#_/',$roles)) {
		 // if it contains another placeholder as value, don't do anything here
		 $found=0;
	 } else {
		 $replacement = 0;
		 if ($current_userid) {
			 $wp_user=wp_get_current_user();
			 $roles_arr = explode(",", $roles);
			 foreach ($roles_arr as $role) {
				 if (in_array( $role, (array) $wp_user->roles)) {
					 $replacement=1;
					 break;
				 }
			 }
		 }
	 }
      } elseif (preg_match('/^#_IS_USER_IN_GROUP\{(.+?)\}$/', $result, $matches)) {
         $groups=$matches[1];
	 if (preg_match('/#_/',$groups)) {
		 // if it contains another placeholder as value, don't do anything here
		 $found=0;
	 } else {
		 $replacement = 0;
		 if ($current_userid) {
			 $groups_arr = explode(",", $groups);
			 foreach ($groups_arr as $group) {
				 if (is_numeric($group))
					 $group=eme_get_group_name($group);
				 if (in_array($group,eme_get_persongroup_names(0,$current_userid))) {
					 $replacement=1;
					 break;
				 }
			 }
		 }
	 }
      } elseif (preg_match('/^#_IS_USER_MEMBER_PENDING\{(.+?)\}$/', $result, $matches)) {
	 $memberships=$matches[1];
	 if (preg_match('/#_/',$memberships)) {
		 // if it contains another placeholder as value, don't do anything here
		 $found=0;
	 } else {
		 $replacement=0;
		 if ($current_userid) {
			 $memberships_arr = explode(",", $memberships);
			 foreach ($memberships_arr as $membership_t) {
				 $membership=eme_get_membership($membership_t);
				 $member=eme_get_member_by_wpid_membershipid($current_userid,$membership['membership_id'],EME_MEMBER_STATUS_PENDING);
				 if (!empty($member)) {
					 $replacement = 1;
					 break;
				 }
			 }
		 }
	 }
      } elseif (preg_match('/^#_IS_USER_MEMBER_EXPIRED\{(.+?)\}$/', $result, $matches)) {
	 $memberships=$matches[1];
	 if (preg_match('/#_/',$memberships)) {
		 // if it contains another placeholder as value, don't do anything here
		 $found=0;
	 } else {
		 $replacement=0;
		 if ($current_userid) {
			 $memberships_arr = explode(",", $memberships);
			 foreach ($memberships_arr as $membership_t) {
				 $membership=eme_get_membership($membership_t);
				 $member=eme_get_member_by_wpid_membershipid($current_userid,$membership['membership_id'],EME_MEMBER_STATUS_EXPIRED);
				 if (!empty($member)) {
					 $replacement = 1;
					 break;
				 }
			 }
		 }
	 }
      } elseif (preg_match('/^#_IS_USER_MEMBER_OF\{(.+?)\}$/', $result, $matches)) {
	 $memberships=$matches[1];
	 if (preg_match('/#_/',$memberships)) {
		 // if it contains another placeholder as value, don't do anything here
		 $found=0;
	 } else {
		 $replacement=0;
		 if ($current_userid) {
			 $memberships_arr = explode(",", $memberships);
			 foreach ($memberships_arr as $membership_t) {
				 $membership=eme_get_membership($membership_t);
				 $member=eme_get_member_by_wpid_membershipid($current_userid,$membership['membership_id'],EME_MEMBER_STATUS_ACTIVE.','.EME_MEMBER_STATUS_GRACE);
				 if (!empty($member)) {
					 $replacement = 1;
					 break;
				 }
			 }
		 }
	 }
      } elseif (preg_match('/^#_MEMBERSHIP_PAYMENT_URL\{(.+?)\}$|^#_EXPIRED_MEMBERSHIP_PAYMENT_URL\{(.+?)\}$/', $result, $matches)) {
         $match=$matches[1];
	 if (preg_match('/#_/',$match)) {
		 // if it contains another placeholder as value, don't do anything here
		 $found=0;
	 } else {
		 if ($current_userid) {
			 $membership=eme_get_membership($match);
			 if (!empty($membership)) {
				 $member=eme_get_member_by_wpid_membershipid($current_userid,$membership['membership_id']);
				 if (!empty($member)) {
					 // no payment id yet? let's create one (can be old members, older imports, ...)
					 if (empty($member['payment_id']))
						 $member['payment_id']=eme_create_member_payment($member['member_id']);
					 $payment = eme_get_payment($member['payment_id']);
					 $replacement = eme_payment_url($payment);
					 if ($target == "html")
						 $replacement = esc_url($replacement);
				 }
			 }
		 }
	 }
      } elseif (preg_match('/^#_HAS_USER_TASK_REGISTERED\{(.+?)\}$/', $result, $matches)) {
	 $tasks=$matches[1];
	 if (preg_match('/#_/',$tasks)) {
		 // if it contains another placeholder as value, don't do anything here
		 $found=0;
	 } else {
		 $replacement=0;
		 if ($current_userid) {
			 $tasks_arr = explode(",", $tasks);
			 foreach ($tasks_arr as $task_id) {
				 $signups=eme_get_task_signups_by($current_userid,$task_id);
				 if (!empty($signups)) {
					 $replacement = 1;
					 break;
				 }
			 }
		 }
	 }
      } elseif (preg_match('/#_INCLUDE_TEMPLATE\{(.+?)\}$/', $result, $matches)) {
         $template_id=$matches[1];
	 if (preg_match('/#_/',$template_id)) {
		 // if it contains another placeholder as value, don't do anything here
		 $found=0;
	 } else {
		 $replacement=eme_get_template_format(intval($template_id));
	 }

      } elseif (preg_match('/#_IS_SINGLE_DAY/', $result)) {
         if (eme_is_single_day_page())
            $replacement = 1;
         else
            $replacement = 0;

      } elseif (preg_match('/#_IS_SINGLE_EVENT/', $result)) {
         if (eme_is_single_event_page())
            $replacement = 1;
         else
            $replacement = 0;

      } elseif (preg_match('/#_IS_SINGLE_LOC/', $result)) {
         if (eme_is_single_location_page())
            $replacement = 1;
         else
            $replacement = 0;

      } elseif (preg_match('/#_IS_LOGGED_IN/', $result)) {
         if (is_user_logged_in())
            $replacement = 1;
         else
            $replacement = 0;

      } elseif (preg_match('/#_IS_ADMIN|#_IS_ADMIN_PAGE/', $result)) {
         if (eme_is_admin_request())
            $replacement = 1;
         else
            $replacement = 0;
      } elseif (preg_match('/#_LOCALE/', $result)) {
         $replacement = determine_locale();

      } elseif (preg_match('/#_LANG/', $result)) {
	 if (empty($lang))
	    $replacement=eme_detect_lang();
         else
            $replacement=$lang;

      } elseif (preg_match('/#_UNSUB_URL$/', $result)) {
         $replacement = eme_unsub_url();

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
   return $format;
}

function eme_replace_placeholders($format, $event, $target="html", $lang='', $do_shortcode=1) {
	return eme_replace_event_placeholders($format, $event, $target, $lang, $do_shortcode);
}

function eme_replace_event_placeholders($format, $event, $target="html", $lang='', $do_shortcode=1, $recursion_level=0) {
   global $eme_timezone;

   $email_target = 0;
   $orig_target = $target;
   if ($target == "htmlmail") {
           $email_target = 1;
           $target = "html";
   }

   if (empty($lang))
	   $lang=eme_detect_lang();

   // an initial filter for the format, in case people want to change anything before the placeholders get replaced
   if (has_filter('eme_events_format_prefilter')) $format=apply_filters('eme_events_format_prefilter',$format, $event);

   // some variables we'll use further down more than once
   $current_userid=get_current_user_id();
   $eme_enable_notes_placeholders = get_option('eme_enable_notes_placeholders'); 

   $total_seats = eme_get_total($event['event_seats']);

   if ($recursion_level==0) {
	   // replace the generic placeholders
	   $format = eme_replace_generic_placeholders ( $format, $orig_target);

	   // replace the notes sections, since these can contain other placeholders
	   if ($eme_enable_notes_placeholders)
		   $format = eme_replace_notes_placeholders ( $format, $event, $orig_target );
   }

   // then we do the custom attributes, since these can contain other placeholders
   preg_match_all("/#(ESC|URL)?_ATT\{.+?\}(\{.+?\})?/", $format, $results);
   foreach($results[0] as $resultKey => $orig_result) {
      $need_escape = 0;
      $need_urlencode = 0;
      $result = $orig_result;
      if (strstr($result,'#ESC')) {
         $result = str_replace("#ESC","#",$result);
         $need_escape=1;
      } elseif (strstr($result,'#URL')) {
         $result = str_replace("#URL","#",$result);
         $need_urlencode=1;
      }
      $replacement = "";
      //Strip string of placeholder and just leave the reference
      $attRef = substr( substr($result, 0, strpos($result, '}')), 6 );
      if (isset($event['event_attributes'][$attRef])) {
         $replacement = $event['event_attributes'][$attRef];
      }
      if( trim($replacement) == ''
         && isset($results[2][$resultKey])
         && $results[2][$resultKey] != '' ) {
         //Check to see if we have a second set of braces;
         $replacement = substr( $results[2][$resultKey], 1, strlen(trim($results[2][$resultKey]))-2 );
      }

      if ($need_escape)
         $replacement = eme_esc_html(preg_replace('/\n|\r/','',$replacement));
      if ($need_urlencode)
         $replacement = rawurlencode($replacement);
      $format = str_replace($orig_result, $replacement ,$format );
   }

   // replace what we can inside curly brackets
   if ($recursion_level<=1) {
	   $needle_offset=0;
	   preg_match_all('/#(ESC|URL)?@?_?[A-Za-z0-9_]+(\{(?>[^{}]+|(?2))*\})(\{(?>[^{}]+|(?2))*\})?/', $format, $placeholders,PREG_OFFSET_CAPTURE);
	   $curlies=array_merge($placeholders[2],$placeholders[3]);
	   foreach ($curlies as $orig_result) {
		   if (empty($orig_result[0])) continue; // don't even handle empty results, avoid php warning
		   $orig_result_length = strlen($orig_result[0]);
		   if ($orig_result_length<=2) continue; // strlen 2 is the empty {}, so no need to handle
		   $orig_result_needle = $orig_result[1]-$needle_offset;
		   $replacement='';
		   $found = 1;
		   if (strstr($orig_result[0],'#')) {
			   $replacement=eme_replace_event_placeholders($orig_result[0], $event, $target, $lang, $do_shortcode,$recursion_level+1);
		   } else {
			   $found=0;
		   }
		   if ($found) {
			   $format = substr_replace($format, $replacement, $orig_result_needle,$orig_result_length );
			   $needle_offset += $orig_result_length-strlen($replacement);
		   }
	   }
   }

   // some vars that will get filled when needed/used
   $all_categories = eme_get_cached_categories();
   $event_categories = null;
   $contact = null;
   $contact_person = null;
   $author = null;
   $author_person = null;

   $eme_date_obj_now=new ExpressiveDate("now",$eme_timezone);
   $needle_offset=0;
   preg_match_all('/#(ESC|URL)?@?_?[A-Za-z0-9_]+(\{(?>[^{}]+|(?2))*\})*+/', $format, $placeholders,PREG_OFFSET_CAPTURE);
   foreach($placeholders[0] as $orig_result) {
      $result = $orig_result[0];
      $orig_result_needle = $orig_result[1]-$needle_offset;
      $orig_result_length = strlen($orig_result[0]);
      $need_escape = 0;
      $need_urlencode = 0;
      $found = 1;
      if (strstr($result,'#ESC')) {
         $result = str_replace("#ESC","#",$result);
         $need_escape=1;
      } elseif (strstr($result,'#URL')) {
         $result = str_replace("#URL","#",$result);
         $need_urlencode=1;
      }
      $replacement = "";
      // matches all fields placeholder
      if ($event && preg_match('/#_EDITEVENTLINK/', $result)) { 
         if (current_user_can( get_option('eme_cap_edit_events')) ||
             (current_user_can( get_option('eme_cap_author_event')) && $event['event_author']==$current_userid)) {
            $url = admin_url("admin.php?page=eme-manager&eme_admin_action=edit_event&event_id=".$event['event_id']);
	    if ($target == "html")
		    $url = esc_url($url);
            $replacement = "<a href='$url'>".__('Edit', 'events-made-easy')."</a>";
         }

      } elseif ($event && preg_match('/#_EDITEVENTURL/', $result)) { 
         if (current_user_can( get_option('eme_cap_edit_events')) ||
             (current_user_can( get_option('eme_cap_author_event')) && $event['event_author']==$current_userid)) {
            $replacement = admin_url("admin.php?page=eme-manager&eme_admin_action=edit_event&event_id=".$event['event_id']);
	    if ($target == "html")
		    $replacement = esc_url($replacement);
         }

      } elseif ($event && preg_match('/#_EVENTPRINTBOOKINGSLINK/', $result)) { 
         if (current_user_can( get_option('eme_cap_edit_events')) || current_user_can( get_option('eme_cap_list_events')) ||
             (current_user_can( get_option('eme_cap_author_event')) && $event['event_author']==$current_userid)) {
            $url = admin_url("admin.php?page=eme-people&eme_admin_action=booking_printable&event_id=".$event['event_id']);
	    if ($target == "html")
		    $url = esc_url($url);
            $replacement = "<a href='$url'>".__('Printable view of bookings','events-made-easy')."</a>";
         }

      } elseif ($event && preg_match('/#_EVENTPRINTBOOKINGSURL/', $result)) { 
         if (current_user_can( get_option('eme_cap_edit_events')) || current_user_can( get_option('eme_cap_list_events')) ||
             (current_user_can( get_option('eme_cap_author_event')) && $event['event_author']==$current_userid)) {
            $replacement = admin_url("admin.php?page=eme-people&eme_admin_action=booking_printable&event_id=".$event['event_id']);
	    if ($target == "html")
		    $replacement = esc_url($replacement);
         }

      } elseif ($event && preg_match('/#_EVENTCSVBOOKINGSLINK/', $result)) { 
         if (current_user_can( get_option('eme_cap_edit_events')) || current_user_can( get_option('eme_cap_list_events')) ||
             (current_user_can( get_option('eme_cap_author_event')) && $event['event_author']==$current_userid)) {
            $url = admin_url("admin.php?page=eme-people&eme_admin_action=booking_csv&event_id=".$event['event_id']);
	    if ($target == "html")
		    $url = esc_url($url);
            $replacement = "<a href='$url'>".__('CSV view of bookings','events-made-easy')."</a>";
         }

      } elseif ($event && preg_match('/#_EVENTCSVBOOKINGSURL/', $result)) { 
         if (current_user_can( get_option('eme_cap_edit_events')) || current_user_can( get_option('eme_cap_list_events')) ||
             (current_user_can( get_option('eme_cap_author_event')) && $event['event_author']==$current_userid)) {
            $replacement = admin_url("admin.php?page=eme-people&eme_admin_action=booking_csv&event_id=".$event['event_id']);
	    if ($target == "html")
		    $replacement = esc_url($replacement);
         }

      } elseif (preg_match('/#_STARTDATETIME_8601/', $result)) {
         $replacement = eme_localized_date($event['event_start'],$eme_timezone,"c");
      } elseif (preg_match('/#_ENDDATETIME_8601/', $result)) {
         $replacement = eme_localized_date($event['event_end'],$eme_timezone,"c");
      } elseif (preg_match('/#_STARTDATE\{(.+)\}/', $result,$matches)) {
         $replacement = eme_localized_date($event['event_start'],$eme_timezone,$matches[1]);

      } elseif (preg_match('/#_ENDDATE\{(.+)\}/', $result,$matches)) {
         $replacement = eme_localized_date($event['event_end'],$eme_timezone,$matches[1]);

      } elseif ($event && preg_match('/#_STARTDATE$/', $result)) { 
         $replacement = eme_localized_date($event['event_start'],$eme_timezone);

      } elseif ($event && preg_match('/#_STARTTIME/', $result)) { 
         $replacement = eme_localized_time($event['event_start'],$eme_timezone);

      } elseif ($event && preg_match('/#_ENDDATE$/', $result)) { 
         $replacement = eme_localized_date($event['event_end'],$eme_timezone);

      } elseif ($event && preg_match('/#_ENDTIME/', $result)) { 
         $replacement = eme_localized_time($event['event_end'],$eme_timezone);

      } elseif ($event && preg_match('/#_24HSTARTTIME/', $result)) { 
         $replacement = $eme_date_obj_now->copy()->setTimestampFromString($event['event_start']." ".$eme_timezone)->format('H:i');

      } elseif ($event && preg_match('/#_24HENDTIME$/', $result)) {
         $replacement = $eme_date_obj_now->copy()->setTimestampFromString($event['event_end']." ".$eme_timezone)->format('H:i');

      } elseif ($event && preg_match('/#_PAST_FUTURE_CLASS/', $result)) { 
         $eme_start_obj = new ExpressiveDate($event['event_start'],$eme_timezone);
         $eme_end_obj = new ExpressiveDate($event['event_end'],$eme_timezone);
         if ($eme_start_obj> $eme_date_obj_now) {
            $replacement="eme-future-event";
         } elseif ($eme_start_obj <= $eme_date_obj_now && $eme_end_obj >= $eme_date_obj_now) {
            $replacement="eme-ongoing-event";
         } else {
            $replacement="eme-past-event";
         }

      } elseif ($event && preg_match('/#_12HSTARTTIME$/', $result)) {
         $replacement = $eme_date_obj_now->copy()->setTimestampFromString($event['event_start']." ".$eme_timezone)->format('h:i A');

      } elseif ($event && preg_match('/#_12HENDTIME$/', $result)) {
         $replacement = $eme_date_obj_now->copy()->setTimestampFromString($event['event_end']." ".$eme_timezone)->format('h:i A');

      } elseif ($event && preg_match('/#_12HSTARTTIME_NOLEADINGZERO/', $result)) {
         $replacement = $eme_date_obj_now->copy()->setTimestampFromString($event['event_start']." ".$eme_timezone)->format('g:i A');
         if (get_option('eme_time_remove_leading_zeros')) {
            $replacement = str_replace(":00","",$replacement);
            $replacement = str_replace(":0",":",$replacement);
         }

      } elseif ($event && preg_match('/#_12HENDTIME_NOLEADINGZERO/', $result)) {
         $replacement = $eme_date_obj_now->copy()->setTimestampFromString($event['event_end']." ".$eme_timezone)->format('g:i A');
         if (get_option('eme_time_remove_leading_zeros')) {
            $replacement = str_replace(":00","",$replacement);
            $replacement = str_replace(":0",":",$replacement);
         }

      } elseif ($event && preg_match('/#_EVENTS_FILTERFORM/', $result)) {
         if ($target == "rss" || $target == "text" || eme_is_single_event_page()) {
            $replacement = "";
         } else {
            $replacement = eme_filter_form();
         }

      } elseif ($event && preg_match('/#_ADDBOOKINGFORM$/', $result)) {
         if ($target == "rss" || $target == "text") {
            $replacement = "";
         } else {
	    $replacement = eme_add_booking_form($event['event_id']);
         }

      } elseif ($event && preg_match('/^#_ADDBOOKINGFORM_IF_LOGGED_IN/', $result)) {
	 if (is_user_logged_in()) {
		 if ($target == "rss" || $target == "text") {
			 $replacement = "";
		 } else {
			 $replacement = eme_add_booking_form($event['event_id']);
		 }
	 }

      } elseif ($event && preg_match('/^#_ADDBOOKINGFORM_IF_USER_HAS_CAP\{(.+?)\}$/', $result, $matches)) {
         $caps=$matches[1];
	 if (is_user_logged_in() && $target=="html") {
		 $caps_arr = explode(",", $caps);
		 $show_form=0;
		 foreach ($caps_arr as $cap) {
			 if (current_user_can($cap)) {
				 $show_form=1;
				 break;
			 }
		 }
		 if ($show_form)
			 $replacement = eme_add_booking_form($event['event_id']);
	 }

      } elseif ($event && preg_match('/^#_ADDBOOKINGFORM_IF_USER_HAS_ROLE\{(.+?)\}$/', $result, $matches)) {
         $roles=$matches[1];
	 if (is_user_logged_in() && $target=="html") {
		 $wp_user=wp_get_current_user();
		 $roles_arr = explode(",", $roles);
		 $show_form=0;
		 foreach ($roles_arr as $role) {
			 if (in_array( $role, (array) $wp_user->roles)) {
				 $show_form=1;
				 break;
			 }
		 }
		 if ($show_form)
			 $replacement = eme_add_booking_form($event['event_id']);
	 }

      } elseif ($event && preg_match('/^#_ADDBOOKINGFORM_IF_USER_IN_GROUP\{(.+?)\}$/', $result, $matches)) {
         $groups=$matches[1];
	 if (is_user_logged_in() && $target=="html") {
		 $wp_id=get_current_user_id();
		 $groups_arr = explode(",", $groups);
		 $show_form=0;
		 foreach ($groups_arr as $group) {
			 if (is_numeric($group))
				 $group=eme_get_group_name($group);
			 if (in_array($group,eme_get_persongroup_names(0,$wp_id))) {
				 $show_form=1;
				 break;
			 }
		 }
		 if ($show_form)
			 $replacement = eme_add_booking_form($event['event_id']);
	 }

      } elseif ($event && preg_match('/^#_ADDBOOKINGFORM_IF_USER_IS_MEMBER_OF\{(.+?)\}$/', $result, $matches)) {
         $memberships=$matches[1];
	 if (is_user_logged_in() && $target=="html") {
		 $wp_id=get_current_user_id();
		 $memberships_arr = explode(",", $memberships);
		 $show_form=0;
		 foreach ($memberships_arr as $membership_t) {
			 $membership=eme_get_membership($membership_t);
			 $member=eme_get_member_by_wpid_membershipid($wp_id,$membership['membership_id'],EME_MEMBER_STATUS_ACTIVE.','.EME_MEMBER_STATUS_GRACE);
			 if (!empty($member)) {
				 $show_form=1;
				 break;
			 }
		 }
		 if ($show_form)
			 $replacement = eme_add_booking_form($event['event_id']);
	 }
      } elseif ($event && preg_match('/#_ADDBOOKINGFORM_IF_NOT_REGISTERED/', $result)) {
         if ($target == "rss" || $target == "text") {
            $replacement = "";
         } else {
            $only_if_not_registered=1;
	    $replacement = eme_add_booking_form($event['event_id'],$only_if_not_registered);
         }

      } elseif ($event && preg_match('/#_REMOVEBOOKINGFORM$|#_DELBOOKINGFORM$|#_DELETEBOOKINGFORM$|#_CANCELBOOKINGFORM$|#_CANCEL_ALL_BOOKINGS_FORM$/', $result)) {
         if ($target == "rss" || $target == "text") {
            $replacement = "";
         } else {
            // when the booking just happened and the user needs to pay, we don't show the remove booking form
            if (isset($_POST['eme_eventAction']) && $_POST['eme_eventAction'] == 'pay_bookings' && isset($_POST['eme_message']) && isset($_POST['eme_payment_id'])) {
               $replacement = "";
	    } else {
	       $replacement = eme_cancel_bookings_form($event['event_id']);
	    }
         }

      } elseif ($event && preg_match('/#_REMOVEBOOKINGFORM_IF_REGISTERED|#_DELBOOKINGFORM_IF_REGISTERED|#_DELETEBOOKINGFORM_IF_REGISTERED|#_CANCELBOOKINGFORM_IF_REGISTERED|#_CANCEL_ALL_BOOKINGS_FORM_IF_REGISTERED/', $result)) {
         if ($target == "rss" || $target == "text") {
            $replacement = "";
         } elseif (is_user_logged_in() ) {
            // when the booking just happened and the user needs to pay, we don't show the remove booking form
            // also show the form if a delete-message needs to be shown (that will actuallyonly shown the message anyway)
            if (isset($_POST['eme_eventAction']) && $_POST['eme_eventAction'] == 'pay_bookings' && isset($_POST['eme_message']) && isset($_POST['eme_payment_id'])) {
               $replacement = "";
	    } elseif (isset($_POST['eme_eventAction']) && $_POST['eme_eventAction'] == 'delmessage') {
	       $replacement = eme_cancel_bookings_form($event['event_id']);
	    } elseif ($event && eme_get_booking_ids_by_wp_event_id($current_userid,$event['event_id'])) {
	       $replacement = eme_cancel_bookings_form($event['event_id']);
	    }
         }

      } elseif ($event && preg_match('/#_WAITING_LIST_ACTIVATED$/', $result)) {
	 $waitinglist_seats = intval($event['event_properties']['waitinglist_seats']);
	 // check for avail seats excluding waiting list
	 $avail_seats = eme_get_available_seats($event['event_id'],1);
	 if ($waitinglist_seats>0 && $avail_seats<=0 && !eme_is_multi($event['event_seats'])) {
		 $replacement=1;
	 } else {
		 $replacement=0;
	 }
      
      } elseif ($event && preg_match('/#_WAITING_LIST_CLOSED$/', $result)) {
	 $waitinglist_seats = intval($event['event_properties']['waitinglist_seats']);
	 $avail_seats = eme_get_available_seats($event['event_id']);
	 if ($waitinglist_seats>0 && $avail_seats<=0 && !eme_is_multi($event['event_seats'])) {
		 $replacement=1;
	 } else {
		 $replacement=0;
	 }
      
      } elseif ($event && preg_match('/#_WAITINGLISTSEATS$/', $result)) {
	 $replacement = intval($event['event_properties']['waitinglist_seats']);

      } elseif ($event && preg_match('/#_AVAILABLEWAITINGLISTSEATS$/', $result)) {
	 if ($total_seats==0 || eme_is_multi($event['event_seats'])) {
		 $replacement=0;
	 } else {
		 $waitinglist_seats = intval($event['event_properties']['waitinglist_seats']);
		 $booked_seats = eme_get_booked_waitinglistseats($event['event_id']);
		 $avail_seats_waiting = $waitinglist_seats-$booked_seats;
		 if ($avail_seats_waiting<0) $avail_seats_waiting=0;
		 if ($waitinglist_seats >0) {
			 $replacement = $avail_seats_waiting;
		 } else {
			 $replacement = 0;
		 }
	 }

      } elseif ($event && preg_match('/#_BOOKEDWAITINGLISTSEATS$/', $result)) {
	 if ($total_seats==0 || eme_is_multi($event['event_seats'])) {
		 $replacement=0;
	 } else {
		 $waitinglist_seats = intval($event['event_properties']['waitinglist_seats']);
		 $booked_seats = eme_get_booked_waitinglistseats($event['event_id']);
		 if ($waitinglist_seats >0) {
			 $replacement = $booked_seats;
		 } else {
			 $replacement = 0;
		 }
	 }

      } elseif ($event && preg_match('/#_(AVAILABLESPACES|AVAILABLESEATS)$/', $result)) {
	 if ($total_seats==0) {
		 $replacement='&infin;';
	 } else {
		 $replacement = eme_get_available_seats($event['event_id'],1); // this is without the waiting list seats
	 }

      } elseif ($event && preg_match('/#_(AVAILABLESPACES|AVAILABLESEATS)\{(\d+)\}$/', $result, $matches)) {
	 if ($total_seats==0) {
		 if ($target == "html") {
			 $replacement='&infin;';
		 } else {
			 $replacement=__('No limit','events-made-easy');
		 }
	 } else {
		 $field_id = intval($matches[2])-1;
		 $replacement = 0;
		 $seats=eme_get_available_multiseats($event['event_id']);
		 if (array_key_exists($field_id,$seats))
			 $replacement = $seats[$field_id];
	 }

      } elseif ($event && preg_match('/#_(TOTALSPACES|TOTALSEATS)$/', $result)) {
	 if ($total_seats==0) {
		 if ($need_escape) {
			 $replacement=0;
		 } elseif ($target == "html") {
			 $replacement='&infin;';
		 } else {
			 $replacement=__('No limit','events-made-easy');
		 }
	 } else {
		 $waitinglist_seats = intval($event['event_properties']['waitinglist_seats']);
		 $replacement = $total_seats-$waitinglist_seats;
	 }

      } elseif ($event && preg_match('/#_(TOTALSPACES|TOTALSEATS)\{(\d+)\}$/', $result, $matches)) {
	 if ($total_seats==0) {
		 if ($need_escape) {
			 $replacement=0;
		 } elseif ($target == "html") {
			 $replacement='&infin;';
		 } else {
			 $replacement=__('No limit','events-made-easy');
		 }
	 } else {
		 $field_id = intval($matches[2])-1;
		 $replacement = 0;
		 $seats = eme_convert_multi2array($event['event_seats']);
		 if (array_key_exists($field_id,$seats))
			 $replacement = $seats[$field_id];
         }

      } elseif ($event && preg_match('/#_(RESERVEDSPACES|BOOKEDSEATS|RESERVEDSEATS)$/', $result)) {
         $replacement = eme_get_booked_seats($event['event_id']);

      } elseif ($event && preg_match('/#_(RESERVEDSPACES|BOOKEDSEATS|RESERVEDSEATS)\{(\d+)\}$/', $result, $matches)) {
         $field_id = intval($matches[2])-1;
         $replacement = 0;
	 $seats=eme_get_booked_multiseats($event['event_id']);
	 if (array_key_exists($field_id,$seats))
		 $replacement = $seats[$field_id];

      } elseif ($event && preg_match('/#_(PAIDSPACES|PAIDSEATS)$/', $result)) {
         $replacement = eme_get_paid_seats($event['event_id']);

      } elseif ($event && preg_match('/#_(PAIDSPACES|PAIDSEATS)\{(\d+)\}$/', $result, $matches)) {
         $field_id = intval($matches[2])-1;
	 $replacement = 0;
	 $seats=eme_get_paid_multiseats($event['event_id']);
	 if (array_key_exists($field_id,$seats))
		 $replacement = $seats[$field_id];

      } elseif ($event && preg_match('/#_(YOUNGPENDINGSPACES|YOUNGPENDINGSEATS)$/', $result)) {
         $replacement = eme_get_young_pending_seats($event['event_id']);

      } elseif ($event && preg_match('/#_(YOUNGPENDINGSPACES|YOUNGPENDINGSEATS)\{(\d+)\}$/', $result, $matches)) {
         $field_id = intval($matches[2])-1;
	 $replacement = 0;
	 $seats=eme_get_young_pending_multiseats($event['event_id']);
	 if (array_key_exists($field_id,$seats))
		 $replacement = $seats[$field_id];

      } elseif ($event && preg_match('/#_(PENDINGSPACES|PENDINGSEATS)$/', $result)) {
         $replacement = eme_get_pending_seats($event['event_id']);

      } elseif ($event && preg_match('/#_(PENDINGSPACES|PENDINGSEATS)\{(\d+)\}$/', $result, $matches)) {
         $field_id = intval($matches[2])-1;
	 $replacement = 0;
	 $seats=eme_get_pending_multiseats($event['event_id']);
	 if (array_key_exists($field_id,$seats))
		 $replacement = $seats[$field_id];

      } elseif ($event && preg_match('/#_(APPROVEDSPACES|APPROVEDSEATS)$/', $result)) {
         $replacement = eme_get_approved_seats($event['event_id']);

      } elseif ($event && preg_match('/#_(APPROVEDSPACES|APPROVEDSEATS)\{(\d+)\}$/', $result, $matches)) {
         $field_id = intval($matches[2])-1;
         $replacement = 0;
         $seats=eme_get_approved_multiseats($event['event_id']);
         if (array_key_exists($field_id,$seats))
		 $replacement = $seats[$field_id];

      } elseif ($event && preg_match('/#_USER_(RESERVEDSPACES|BOOKEDSEATS|RESERVEDSEATS)$/', $result)) {
         if (is_user_logged_in()) {
            $replacement = eme_get_booked_seats_by_wp_event_id($current_userid,$event['event_id']);
         }

      } elseif ($event && preg_match('/#_AVAILABLETASKS|#_FREETASKS$/', $result)) {
	 if ($event['event_tasks']) {
		 $tasks = eme_get_event_tasks($event['event_id']);
		 if (empty($tasks)) {
			 $replacement = 0;
		 } else {
			 $open_tasks_found = 0;
			 foreach ($tasks as $task) {
				 $used_spaces = eme_count_task_signups($task['task_id']);
				 $free_spaces = $task['spaces']-$used_spaces;
				 if ($free_spaces == 0 && $skip_full) {
					 // skip full option, so check the free spaces for that task, if 0: set $skip=1
					 $skip = 1;
				 }
				 if ($free_spaces > 0) {
					 $open_tasks_found++;
				 }
			 }
			 $replacement = $open_tasks_found;
		 }
	 }

      } elseif ($event && preg_match('/#_LINKEDNAME/', $result)) {
         $event_link = eme_event_url($event,$lang);
         if ($target == "html") {
		 $event_link = esc_url($event_link);
		 $replacement="<a href='$event_link' title='".eme_trans_esc_html($event['event_name'],$lang)."'>".eme_trans_esc_html($event['event_name'],$lang)."</a>";
         } else {
            $replacement=eme_translate($event['event_name'],$lang);
         }
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif ($event && preg_match('/#_EXTERNALURL/', $result)) {
         if ($event['event_url'] != '')
            $replacement=$event['event_url'];
         if ($target == "html")
		 $replacement = esc_url($replacement);

      } elseif ($event && preg_match('/#_ICALLINK$/', $result)) {
	 $url = eme_single_event_ical_url($event['event_id']);
         if ($target == "html")
		 $url = esc_url($url);
         $replacement = "<a href='$url'>ICAL</a>";
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif ($event && preg_match('/#_ICALURL/', $result)) {
	 $url = eme_single_event_ical_url($event['event_id']);
         if ($target == "html")
		 $replacement = esc_url($url);

      } elseif (preg_match('/#_EVENTIMAGETITLE$/', $result)) {
         if (!empty($event['event_image_id'])) {
              $info = eme_get_wp_image($event['event_image_id']);
              $replacement = $info['title'];
              if ($target == "html") {
                      $replacement = apply_filters('eme_general', $replacement);
              } elseif ($target == "rss")  {
                      $replacement = apply_filters('the_content_rss', $replacement);
              } else {
                      $replacement = apply_filters('eme_text', $replacement);
              }
         }
      } elseif (preg_match('/#_EVENTIMAGEALT$/', $result)) {
         if (!empty($event['event_image_id'])) {
              $info = eme_get_wp_image($event['event_image_id']);
              $replacement = $info['alt'];
              if ($target == "html") {
                      $replacement = apply_filters('eme_general', $replacement);
              } elseif ($target == "rss")  {
                      $replacement = apply_filters('the_content_rss', $replacement);
              } else {
                      $replacement = apply_filters('eme_text', $replacement);
              }
         }
      } elseif (preg_match('/#_EVENTIMAGECAPTION$/', $result)) {
         if (!empty($event['event_image_id'])) {
              $info = eme_get_wp_image($event['event_image_id']);
              $replacement = $info['caption'];
              if ($target == "html") {
                      $replacement = apply_filters('eme_general', $replacement);
              } elseif ($target == "rss")  {
                      $replacement = apply_filters('the_content_rss', $replacement);
              } else {
                      $replacement = apply_filters('eme_text', $replacement);
              }
         }
      } elseif (preg_match('/#_EVENTIMAGEDESCRIPTION$/', $result)) {
         if (!empty($event['event_image_id'])) {
              $info = eme_get_wp_image($event['event_image_id']);
              $replacement = $info['description'];
              if ($target == "html") {
                      $replacement = apply_filters('eme_general', $replacement);
              } elseif ($target == "rss")  {
                      $replacement = apply_filters('the_content_rss', $replacement);
              } else {
                      $replacement = apply_filters('eme_text', $replacement);
              }
         }
	 
      } elseif ($event && preg_match('/#_EVENTIMAGE$/', $result)) {
         if (!empty($event['event_image_id'])) {
            $replacement = wp_get_attachment_image($event['event_image_id'], 'full', 0, array('class'=>'eme_event_image') );
	 } elseif(!empty($event['event_image_url'])) {
            $url = $event['event_image_url'];
            if ($target == "html")
		 $url = esc_url($url);
            $replacement = "<img src='$url' alt='".eme_trans_esc_html($event['event_name'],$lang)."'/>";
         }
         if(!empty($replacement)) {
            if ($target == "html") {
               $replacement = apply_filters('eme_general', $replacement); 
            } elseif ($target == "rss")  {
               $replacement = apply_filters('the_content_rss', $replacement);
            } else {
               $replacement = apply_filters('eme_text', $replacement);
            }
         }

      } elseif ($event && preg_match('/#_EVENTIMAGEURL$/', $result)) {
         if (!empty($event['event_image_id']))
		 $replacement = wp_get_attachment_image_url($event['event_image_id'],'full');
         elseif(!empty($event['event_image_url']))
		 $replacement = $event['event_image_url'];
	 if ($target == "html")
		 $replacement = esc_url($replacement);

      } elseif ($event && preg_match('/#_EVENTIMAGETHUMB$/', $result)) {
         if (!empty($event['event_image_id'])) {
            $replacement = wp_get_attachment_image($event['event_image_id'], get_option('eme_thumbnail_size'), 0, array('class'=>'eme_event_image') );
            if ($target == "html") {
               $replacement = apply_filters('eme_general', $replacement); 
            } elseif ($target == "rss")  {
               $replacement = apply_filters('the_content_rss', $replacement);
            } else {
               $replacement = apply_filters('eme_text', $replacement);
            }
         }

      } elseif ($event && preg_match('/#_EVENTIMAGETHUMBURL$/', $result)) {
         if (!empty($event['event_image_id'])) {
		 $replacement = wp_get_attachment_image_url($event['event_image_id'],get_option('eme_thumbnail_size'));
		 if ($target == "html")
			 $replacement = esc_url($replacement);
         }

      } elseif ($event && preg_match('/#_EVENTIMAGETHUMB\{(.+?)\}$/', $result, $matches)) {
         if (!empty($event['event_image_id'])) {
            $replacement = wp_get_attachment_image($event['event_image_id'], $matches[1], 0, array('class'=>'eme_event_image'));
            if ($target == "html") {
               $replacement = apply_filters('eme_general', $replacement);
            } elseif ($target == "rss")  {
               $replacement = apply_filters('the_content_rss', $replacement);
            } else {
               $replacement = apply_filters('eme_text', $replacement);
            }
         }

      } elseif ($event && preg_match('/#_EVENTIMAGETHUMBURL\{(.+?)\}$/', $result, $matches)) {
         if (!empty($event['event_image_id'])) {
		 $replacement = wp_get_attachment_image_url($event['event_image_id'], $matches[1]);
		 if ($target == "html")
			 $replacement = esc_url($replacement);
         }

      } elseif (preg_match('/#_EVENTFIELD\{(.+?)\}$/', $result, $matches)) {
         $tmp_attkey=$matches[1];
         if (isset($event[$tmp_attkey]) && !is_array($event[$tmp_attkey])) {
            $replacement = $event[$tmp_attkey];
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

      } elseif ($event && preg_match('/#_EVENTATT\{(.+?)\}\{(.+?)\}$/', $result, $matches)) {
         $tmp_event_id=intval($matches[1]);
         $tmp_event_attkey=$matches[2];
         $tmp_event = eme_get_event($tmp_event_id);
         if (!empty($tmp_event) && isset($tmp_event['event_attributes'][$tmp_event_attkey])) {
            $replacement = $tmp_event['event_attributes'][$tmp_event_attkey];
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

      } elseif (preg_match('/#_FIELDNAME\{(.+?)\}$/', $result, $matches)) {
         $field_key = $matches[1];
         $formfield = eme_get_formfield($field_key);
	 if (!empty($formfield) && $formfield['field_purpose']=='events') {
		 if ($target == "html") {
			 $replacement = eme_trans_esc_html($formfield['field_name'],$lang);
			 $replacement = apply_filters('eme_general', $replacement); 
		 } else {
			 $replacement = eme_translate($formfield['field_name'],$lang);
			 $replacement = apply_filters('eme_text', $replacement); 
		 }
	 } else {
		 // no event custom field? Then leave it alone
		 $found=0;
	 }
      } elseif (preg_match('/#_FIELD(VALUE)?\{(.+?)\}(\{.+?\})?$/', $result, $matches)) {
         $field_key = $matches[2];
	 if (isset($matches[3])) {
		 // remove { and } (first and last char of second match)
		 $sep=substr($matches[3], 1, -1);
	 } else {
		 $sep='||';
	 }
         $formfield = eme_get_formfield($field_key);
	 if (!empty($formfield) && $formfield['field_purpose']=='events') {
		 $field_id = $formfield['field_id'];
		 $field_replace = "";
		 $answers=eme_get_event_cf_answers($event['event_id']);
		 foreach ($answers as $answer) {
			 if ($answer['field_id'] == $field_id) {
				 if ($matches[1] == "VALUE")
					 $field_replace=eme_answer2readable($answer['answer'],$formfield,0,$sep,$target);
				 else
					 $field_replace=eme_answer2readable($answer['answer'],$formfield,1,$sep,$target);
			 }
		 }
		 $replacement = eme_translate($field_replace,$lang);
		 if ($target == "html") {
			 $replacement = apply_filters('eme_general', $replacement); 
		 } else {
			 $replacement = apply_filters('eme_text', $replacement); 
		 }
	 } else {
		 // no event custom field? Then leave it alone
		 $found=0;
	 }
      } elseif ($event && preg_match('/#_EVENTPAGEURL\{(.+?)\}$/', $result, $matches)) {
	      $events_page_link = eme_get_events_page();
	      $replacement = add_query_arg(array('event_id'=>intval($matches[1])),$events_page_link);
	      if (!empty($lang))
		      $replacement = add_query_arg(array('lang'=>$lang),$replacement);
	      if ($target == "html")
		      $replacement = esc_url($replacement);

      } elseif ($event && preg_match('/#_EVENTPAGEURL$/', $result)) {
	      $replacement = eme_event_url($event,$lang);
	      if ($target == "html")
		      $replacement = esc_url($replacement);

      } elseif ($event && preg_match('/#_(NAME|EVENTNAME)$/', $result)) {
         $field = "event_name";
         if (isset($event[$field]))  $replacement = $event[$field];
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

      } elseif ($event && preg_match('/#_EVENTID/', $result)) {
         $field = "event_id";
         $replacement = intval($event[$field]);

      } elseif ($event && preg_match('/#_DATETIMEDIFF_(TILL|FROM)_(START|END)$/', $result, $matches)) {
	 if ($matches[2]=='START')
		 $eme_date_obj = new ExpressiveDate($event['event_start'],$eme_timezone);
	 else
		 $eme_date_obj = new ExpressiveDate($event['event_end'],$eme_timezone);
         $diff = $eme_date_obj_now->diff($eme_date_obj)->format('%r1:%y:%m:%a:%h:%i:%s');
	 list($pos_neg,$years, $months,$days,$hours,$mins,$secs) = explode(':',$diff); 
	 if ($matches[1]=='TILL' && $pos_neg<0) {
		 $replacement=0;
	 } elseif ($matches[1]=='FROM' && $pos_neg>0) {
		 $replacement=0;
	 } else {
		 // let's produce a nice string but not add something like "0 years 0 months" to it
		 $replacement=sprintf(_n('%d second', '%d seconds', $secs, 'events-made-easy'),$secs);
		 if ($years || $months || $days || $hours || $mins)
			 $replacement=sprintf(_n('%d minute', '%d minutes', $mins, 'events-made-easy'),$mins).' '.$replacement;
		 if ($years || $months || $days || $hours)
			 $replacement=sprintf(_n('%d hour', '%d hours', $hours, 'events-made-easy'),$hours).' '.$replacement;
		 if ($years || $months || $days)
			 $replacement=sprintf(_n('%d day', '%d days', $days, 'events-made-easy'),$days).' '.$replacement;
		 if ($years || $months)
			 $replacement=sprintf(_n('%d month', '%d months', $months, 'events-made-easy'),$months).' '.$replacement;
		 if ($years)
			 $replacement=sprintf(_n('%d year', '%d years', $years, 'events-made-easy'),$years).' '.$replacement;
	 }

      } elseif ($event && preg_match('/#_DATETIMEDIFF_(TILL|FROM)_(START|END)\{(.+?)\}$/', $result, $matches)) {
	 if ($matches[2]=='START')
		 $eme_date_obj = new ExpressiveDate($event['event_start'],$eme_timezone);
	 else
		 $eme_date_obj = new ExpressiveDate($event['event_end'],$eme_timezone);
         $replacement = $eme_date_obj_now->diff($eme_date_obj)->format($matches[3]);

      } elseif ($event && preg_match('/#_DATETIMEDIFF_START_END\{(.+?)\}$/', $result, $matches)) {
	 $eme_date_obj_start = new ExpressiveDate($event['event_start'],$eme_timezone);
	 $eme_date_obj_end = new ExpressiveDate($event['event_end'],$eme_timezone);
         $replacement = $eme_date_obj_start->diff($eme_date_obj_end)->format($matches[1]);

      } elseif ($event && preg_match('/#_DAYS_TILL_START$/', $result)) {
         $eme_date_obj = new ExpressiveDate($event['event_start'],$eme_timezone);
         $replacement = $eme_date_obj_now->getDifferenceInDays($eme_date_obj);

      } elseif ($event && preg_match('/#_NIGHTS_TILL_START$/', $result)) {
         $eme_date_obj = new ExpressiveDate($event['event_start'],$eme_timezone);
         $replacement = $eme_date_obj_now->getDifferenceInDays($eme_date_obj->endOfDay());

      } elseif ($event && preg_match('/#_DAYS_FROM_START$/', $result)) {
         $eme_date_obj = new ExpressiveDate($event['event_start'],$eme_timezone);
         $replacement = $eme_date_obj->getDifferenceInDays($eme_date_obj_now);

      } elseif ($event && preg_match('/#_DAYS_TILL_END$/', $result)) {
         $eme_date_obj = new ExpressiveDate($event['event_end'],$eme_timezone);
         $replacement = $eme_date_obj_now->getDifferenceInDays($eme_date_obj);

      } elseif ($event && preg_match('/#_NIGHTS_TILL_END$/', $result)) {
         $eme_date_obj = new ExpressiveDate($event['event_end'],$eme_timezone);
         $replacement = $eme_date_obj_now->getDifferenceInDays($eme_date_obj->endOfDay());

      } elseif ($event && preg_match('/#_HOURS_TILL_START$/', $result)) {
         $eme_date_obj = new ExpressiveDate($event['event_start'],$eme_timezone);
         $replacement = round($eme_date_obj_now->getDifferenceInHours($eme_date_obj));

      } elseif ($event && preg_match('/#_HOURS_FROM_START$/', $result)) {
         $eme_date_obj = new ExpressiveDate($event['event_start'],$eme_timezone);
         $replacement = round($eme_date_obj->getDifferenceInHours($eme_date_obj_now));

      } elseif ($event && preg_match('/#_HOURS_TILL_END$/', $result)) {
         $eme_date_obj = new ExpressiveDate($event['event_end'],$eme_timezone);
         $replacement = round($eme_date_obj_now->getDifferenceInHours($eme_date_obj));

      } elseif ($event && preg_match('/#_EVENTPRICE$|#_PRICE$/', $result)) {
         $field = "price";
         if ($event[$field]) {
		 if ($need_escape)
			 $replacement=$event[$field];
		 else
			 $replacement = eme_localized_price($event[$field],$event['currency'],$target);
	 }
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif ($event && preg_match('/#_(EVENT)?PRICE\{(\d+)\}$/', $result, $matches)) {
         $field_id = intval($matches[2]-1);
         if ($event["price"] && eme_is_multi($event["price"])) {
            $prices = eme_convert_multi2array($event["price"]);
            if (is_array($prices) && array_key_exists($field_id,$prices)) {
	       if ($need_escape)
		       $replacement=$prices[$field_id];
	       else
		       $replacement = eme_localized_price($prices[$field_id],$event['currency'],$target);
               if ($target == "html") {
                  $replacement = apply_filters('eme_general', $replacement);
               } elseif ($target == "rss")  {
                  $replacement = apply_filters('the_content_rss', $replacement);
               } else {
                  $replacement = apply_filters('eme_text', $replacement);
               }
            }
         }

      } elseif ($event && preg_match('/#_EVENTPRICE_NO_VAT$|#_PRICE_NO_VAT$/', $result)) {
         $field = "price";
         if ($event[$field]) {
		 $price=$event[$field];
		 $price=$price/(1+$event['event_properties']['vat_pct']/100);
		 if ($need_escape)
			 $replacement=$price;
		 else
			 $replacement = eme_localized_price($price,$event['currency'],$target);
	 }
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }
      } elseif ($event && preg_match('/#_(EVENT)?PRICE_NO_VAT\{(\d+)\}$/', $result, $matches)) {
         $field_id = intval($matches[2]-1);
         if ($event["price"] && eme_is_multi($event["price"])) {
            $prices = eme_convert_multi2array($event["price"]);
            if (is_array($prices) && array_key_exists($field_id,$prices)) {
		 $price=$prices[$field_id];
		 $price=$price/(1+$event['event_properties']['vat_pct']/100);
		 if ($need_escape)
			 $replacement=$price;
		 else
			 $replacement = eme_localized_price($price,$event['currency'],$target);
	    }
	 }
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif ($event && preg_match('/#_EVENTPRICE_VAT_ONLY$|#_PRICE_VAT_ONLY$/', $result)) {
         $field = "price";
         if ($event[$field]) {
	       $price=$event[$field];
	       $price=$price-$price/(1+$event['event_properties']['vat_pct']/100);
	       if ($need_escape)
		       $replacement=$price;
	       else
		       $replacement = eme_localized_price($price,$event['currency'],$target);
               if ($target == "html") {
                  $replacement = apply_filters('eme_general', $replacement);
               } elseif ($target == "rss")  {
                  $replacement = apply_filters('the_content_rss', $replacement);
               } else {
                  $replacement = apply_filters('eme_text', $replacement);
               }
         }

      } elseif ($event && preg_match('/#_(EVENT)?PRICEDESCRIPTION\{(\d+)\}$/', $result, $matches)) {
         $field_id = intval($matches[2]-1);
         if ($event["price"] && eme_is_multi($event["price"])) {
            $prices_desc = eme_convert_multi2array($event['event_properties']['multiprice_desc']);
            if (is_array($prices_desc) && array_key_exists($field_id,$prices_desc)) {
	       $replacement=$prices_desc[$field_id];
               if ($target == "html") {
                  $replacement = apply_filters('eme_general', $replacement);
               } elseif ($target == "rss")  {
                  $replacement = apply_filters('the_content_rss', $replacement);
               } else {
                  $replacement = apply_filters('eme_text', $replacement);
               }
            }
         }

      } elseif ($event && preg_match('/#_(EVENT)?PRICEDESCRIPTION$/', $result, $matches)) {
	 // description can also be used if the price is 0
         if (!eme_is_multi($event["price"])) {
            $replacement = $event['event_properties']['price_desc'];
            if ($target == "html") {
                  $replacement = apply_filters('eme_general', $replacement);
            } elseif ($target == "rss")  {
                  $replacement = apply_filters('the_content_rss', $replacement);
            } else {
                  $replacement = apply_filters('eme_text', $replacement);
            }
         }

      } elseif ($event && preg_match('/#_CURRENCY$/', $result)) {
         $field = "currency";
         // currency is only important if the price is not empty as well
         if ($event['price'])
            $replacement = $event[$field];
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif ($event && preg_match('/#_VAT_PCT$/', $result)) {
         // currency is only important if the price is not empty as well
         $replacement=$event['event_properties']['vat_pct'];
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif ($event && preg_match('/#_CURRENCYSYMBOL$/', $result)) {
         $field = "currency";
         // currency is only important if the price is not empty as well
         if ($event['price'])
		 $replacement = eme_localized_currencysymbol($event[$field]);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif ($event && preg_match('/#_ATTENDEES/', $result)) {
         $replacement=eme_get_attendees_list($event);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif ($event && preg_match('/#_BOOKINGS/', $result)) {
         $replacement=eme_get_bookings_list_for_event($event);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif ($event && preg_match('/#_(CONTACT|AUTHOR)/', $result)) {
	 if (preg_match('/#_CONTACT/', $result)) {
		 if (is_null($contact))
			 $contact = eme_get_event_contact($event);
		 if (!empty($contact) && is_null($contact_person))
			 $contact_person = eme_get_person_by_wp_id($contact->ID);
		 $t_contact=$contact;
		 $t_person=$contact_person;
	 } else {
		 if (is_null($author))
			 $author = eme_get_author($event);
		 if (!empty($author) && is_null($author_person))
			 $author_person = eme_get_person_by_wp_id($author->ID);
		 $t_contact=$author;
		 $t_person=$author_person;
	 }
         if (!empty($t_contact)) {
		 if ($result=="#_CONTACTPERSON")
			 $t_format="#_NAME";
		 elseif ($result=="#_PLAIN_CONTACTEMAIL")
			 $t_format="#_EMAIL";
		 else
			 $t_format=$result;
		 $t_format = str_replace("#_CONTACT","#_",$t_format);
		 $t_format = str_replace("#_AUTHOR","#_",$t_format);
		 if (!empty($t_person)) {
			 // to be consistent: #_CONTACTNAME returns the full name if not linked to an EME user, so we do that here too
                         if ($t_format=="#_NAME") $t_format="#_FULLNAME";
			 $replacement = eme_replace_people_placeholders($t_format, $t_person, $target, $lang);
		 } else {
			 if (preg_match('/#_NAME|#_DISPNAME/', $t_format)) {
				 $replacement = $t_contact->display_name;
				 if ($target == "html")
					 $replacement = eme_trans_esc_html($replacement,$lang);
			 } elseif (preg_match('/#_LASTNAME/', $t_format)) {
				 $replacement = $t_contact->user_lastname;
				 if ($target == "html")
					 $replacement = eme_trans_esc_html($replacement,$lang);
			 } elseif (preg_match('/#_FIRSTNAME/', $t_format)) {
				 $replacement = $t_contact->user_firstname;
				 if ($target == "html")
					 $replacement = eme_trans_esc_html($replacement,$lang);
			 } elseif (preg_match('/#_EMAIL/', $t_format)) {
				 $replacement = $t_contact->user_email;
				 // ascii encode for primitive harvesting protection ...
				 $replacement = eme_email_obfuscate($replacement,$orig_target);
			 } elseif (preg_match('/#_PHONE/', $t_format)) {
				 $replacement = eme_get_user_phone($t_contact->ID);
				 if ($target == "html")
					 $replacement = eme_trans_esc_html($replacement,$lang);
			 }

			 if ($target == "html") {
				 $replacement = apply_filters('eme_general', $replacement); 
			 } elseif ($target == "rss")  {
				 $replacement = apply_filters('the_content_rss', $replacement);
			 } else {
				 $replacement = apply_filters('eme_text', $replacement);
			 }
		 }
	 }

      } elseif (preg_match('/#_EVENTCREATIONDATE\{(.+?)\}$/', $result,$matches)) {
         $replacement = eme_localized_date($event['creation_date'],$eme_timezone,$matches[1]);
      } elseif (preg_match('/#_EVENTMODIFDATE\{(.+?)\}/', $result,$matches)) {
         $replacement = eme_localized_date($event['modif_date'],$eme_timezone,$matches[1]);
      } elseif (preg_match('/#_EVENTCREATIONDATE$/', $result)) {
         $replacement = eme_localized_date($event['creation_date'],$eme_timezone);
      } elseif (preg_match('/#_EVENTMODIFDATE$/', $result)) {
         $replacement = eme_localized_date($event['modif_date'],$eme_timezone);
      } elseif (preg_match('/#_EVENTCREATIONTIME/', $result)) {
         $replacement = eme_localized_time($event['creation_date'],$eme_timezone);
      } elseif (preg_match('/#_EVENTMODIFTIME/', $result)) {
         $replacement = eme_localized_time($event['modif_date'],$eme_timezone);

      } elseif ($event && preg_match('/#[A-Za-z]$/', $result)) {
         // matches all PHP date placeholders for startdate-time
         $replacement=eme_localized_date($event['event_start'],$eme_timezone,ltrim($result,"#"));
         if (get_option('eme_time_remove_leading_zeros') && $result=="#i") {
            $replacement=ltrim($replacement,"0");
         }

      } elseif ($event && preg_match('/#@[A-Za-z]$/', $result)) {
         // matches all PHP time placeholders for enddate-time
         $replacement=eme_localized_date($event['event_end'],$eme_timezone,ltrim($result,"#@"));
         if (get_option('eme_time_remove_leading_zeros') && $result=="#@i") {
            $replacement=ltrim($replacement,"0");
         }

      } elseif ($event && preg_match('/#_EVENTCATEGORYIDS$/', $result) && get_option('eme_categories_enabled')) {
         $category_ids = $event['event_category_ids'];
         if ($target == "html") {
            $replacement = eme_trans_esc_html($category_ids,$lang);
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = eme_translate($category_ids,$lang);
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = eme_translate($category_ids,$lang);
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif ($event && preg_match('/#_(EVENT)?CATEGORIES$/', $result) && get_option('eme_categories_enabled')) {
	 if (is_null($event_categories))
		 $event_categories = eme_get_categories_filtered($event['event_category_ids'],$all_categories);
	 $cat_names = array_column($event_categories,'category_name');
         foreach ($cat_names as $key=>$cat_name) {
		 if ($target == "html")
			 $cat_names[$key]=eme_trans_esc_html($cat_name,$lang);
		 else
			 $cat_names[$key]=eme_translate($cat_name,$lang);
         }
	 $sep=', ';
	 if (has_filter('eme_categories_sep_filter')) $sep=apply_filters('eme_categories_sep_filter',$sep);
         $replacement = join($sep,$cat_names);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif ($event && preg_match('/#_(EVENT)?CATEGORIES_CSS$/', $result) && get_option('eme_categories_enabled')) {
	 if (is_null($event_categories))
		 $event_categories = eme_get_categories_filtered($event['event_category_ids'],$all_categories);
	 $cat_names = array_column($event_categories,'category_name');
         if ($target == "html") {
            $replacement = eme_trans_esc_html(join(" ",$cat_names),$lang);
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = eme_translate(join(" ",$cat_names),$lang);
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = eme_translate(join(" ",$cat_names),$lang);
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif ($event && preg_match('/#_(EVENT)?CATEGORYDESCRIPTIONS$/', $result) && get_option('eme_categories_enabled')) {
	 if (is_null($event_categories))
		 $event_categories = eme_get_categories_filtered($event['event_category_ids'],$all_categories);
	 $cat_descs = array_column($event_categories,'description');
	 $sep=', ';
	 if (has_filter('eme_categorydescriptions_sep_filter')) $sep=apply_filters('eme_categorydescriptions_sep_filter',$sep);
	 $replacement = eme_translate(join($sep,$cat_descs),$lang);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif ($event && preg_match('/#_LINKED(EVENT)?CATEGORIES$/', $result) && get_option('eme_categories_enabled')) {
	 if (is_null($event_categories))
		 $event_categories = eme_get_categories_filtered($event['event_category_ids'],$all_categories);
         $cat_links = array();
         foreach ($event_categories as $category) {
            $cat_link=eme_category_url($category);
            $cat_name=$category['category_name'];
            if ($target == "html") {
               $cat_link=esc_url($cat_link);
               array_push($cat_links,"<a href='$cat_link' title='".eme_trans_esc_html($cat_name,$lang)."'>".eme_trans_esc_html($cat_name,$lang)."</a>");
	    } else {
               array_push($cat_links,eme_translate($cat_name,$lang));
	    }
         }
	 $sep=', ';
	 if (has_filter('eme_categories_sep_filter')) $sep=apply_filters('eme_categories_sep_filter',$sep);
         $replacement = join($sep,$cat_links);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif ($event && preg_match('/^#_(EVENT)?CATEGORIES\{(.*?)\}\{(.*?)\}$/', $result, $matches) && get_option('eme_categories_enabled')) {
         $include_cats=$matches[2];
         $exclude_cats=$matches[3];
         $extra_conditions_arr = array();
	 $order_by="";
         if (!empty($include_cats)) {
            array_push($extra_conditions_arr, "category_id IN ($include_cats)");
	    $order_by="FIELD(category_id,$include_cats)";
	 }
         if (!empty($exclude_cats))
            array_push($extra_conditions_arr, "category_id NOT IN ($exclude_cats)");
         $extra_conditions = join(" AND ",$extra_conditions_arr);
         $t_categories = eme_get_event_category_names($event['event_id'],$extra_conditions,$order_by);
         $cat_names = array();
         foreach ($t_categories as $cat_name) {
		 if ($target == "html")
			 array_push($cat_names,eme_trans_esc_html($cat_name,$lang));
		 else
			 array_push($cat_names,eme_translate($cat_name,$lang));
         }
	 $sep=', ';
	 if (has_filter('eme_categories_sep_filter')) $sep=apply_filters('eme_categories_sep_filter',$sep);
         $replacement = join($sep,$cat_names);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif ($event && preg_match('/^#_(EVENT)?CATEGORIES_CSS\{(.*?)\}\{(.*?)\}$/', $result, $matches) && get_option('eme_categories_enabled')) {
         $include_cats=$matches[2];
         $exclude_cats=$matches[3];
         $extra_conditions_arr = array();
	 $order_by="";
         if (!empty($include_cats)) {
            array_push($extra_conditions_arr, "category_id IN ($include_cats)");
	    $order_by="FIELD(category_id,$include_cats)";
	 }
         if (!empty($exclude_cats))
            array_push($extra_conditions_arr, "category_id NOT IN ($exclude_cats)");
         $extra_conditions = join(" AND ",$extra_conditions_arr);
         $t_categories = eme_get_event_category_names($event['event_id'],$extra_conditions,$order_by);
         if ($target == "html") {
            $replacement = eme_trans_esc_html(join(" ",$t_categories),$lang);
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = eme_translate(join(" ",$t_categories),$lang);
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = eme_translate(join(" ",$t_categories),$lang);
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif ($event && preg_match('/#_(EVENT)?CATEGORYDESCRIPTIONS\{(.*?)\}\{(.*?)\}$/', $result, $matches) && get_option('eme_categories_enabled')) {
         $include_cats=$matches[2];
         $exclude_cats=$matches[3];
         $extra_conditions_arr = array();
	 $order_by="";
         if (!empty($include_cats)) {
            array_push($extra_conditions_arr, "category_id IN ($include_cats)");
	    $order_by="FIELD(category_id,$include_cats)";
	 }
         if (!empty($exclude_cats))
            array_push($extra_conditions_arr, "category_id NOT IN ($exclude_cats)");
         $extra_conditions = join(" AND ",$extra_conditions_arr);
         $t_categories = eme_get_event_category_descriptions($event['event_id'],$extra_conditions,$order_by);
	 $sep=', ';
	 if (has_filter('eme_categorydescriptions_sep_filter')) $sep=apply_filters('eme_categorydescriptions_sep_filter',$sep);
         $replacement = eme_translate(join($sep,$t_categories),$lang);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif ($event && preg_match('/#_LINKED(EVENT)?CATEGORIES\{(.*?)\}\{(.*?)\}$/', $result, $matches) && get_option('eme_categories_enabled')) {
         $include_cats=$matches[2];
         $exclude_cats=$matches[3];
         $extra_conditions_arr = array();
	 $order_by="";
         if (!empty($include_cats)) {
            array_push($extra_conditions_arr, "category_id IN ($include_cats)");
	    $order_by="FIELD(category_id,$include_cats)";
	 }
         if (!empty($exclude_cats))
            array_push($extra_conditions_arr, "category_id NOT IN ($exclude_cats)");
         $extra_conditions = join(" AND ",$extra_conditions_arr);
         $t_categories = eme_get_event_categories($event['event_id'],$extra_conditions,$order_by);
         $cat_links = array();
         foreach ($t_categories as $category) {
            $cat_link=eme_category_url($category);
            $cat_name=$category['category_name'];
            if ($target == "html") {
               $cat_link=esc_url($cat_link);
               array_push($cat_links,"<a href='$cat_link' title='".eme_trans_esc_html($cat_name,$lang)."'>".eme_trans_esc_html($cat_name,$lang)."</a>");
	    } else {
               array_push($cat_links,eme_translate($cat_name,$lang));
	    }
         }
	 $sep=', ';
	 if (has_filter('eme_categories_sep_filter')) $sep=apply_filters('eme_categories_sep_filter',$sep);
         $replacement = join($sep,$cat_links);
         if ($target == "html") {
            $replacement = apply_filters('eme_general', $replacement); 
         } elseif ($target == "rss")  {
            $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }

      } elseif ($event && preg_match('/#_RECURRENCE_DESC|#_RECURRENCEDESC/', $result)) {
         if ($event ['recurrence_id']) {
            $replacement = eme_get_recurrence_desc ( $event ['recurrence_id'] );
            if ($target == "html") {
               $replacement = apply_filters('eme_general', $replacement); 
            } elseif ($target == "rss")  {
               $replacement = apply_filters('the_content_rss', $replacement);
            } else {
               $replacement = apply_filters('eme_text', $replacement);
            }
         }

      } elseif ($event && preg_match('/#_RECURRENCE_NBR/', $result)) {
         // returns the sequence number of an event in a recurrence series
         if ($event ['recurrence_id']) {
            $event_ids = eme_get_recurrence_eventids ( $event ['recurrence_id'] );
            $nbr = array_search($event['event_id'],$event_ids);
            if ($nbr !== false) {
               $replacement = $nbr+1;
            }
         }

      } elseif ($event && preg_match('/#_PASSWORD$/', $result)) {
         if (eme_is_event_rsvp($event) && !empty($event['event_properties']['rsvp_password'])) {
            $replacement = $event['event_properties']['rsvp_password'];
            if ($target == "html") {
               $replacement = apply_filters('eme_general', $replacement); 
            } elseif ($target == "rss")  {
               $replacement = apply_filters('the_content_rss', $replacement);
            } else {
               $replacement = apply_filters('eme_text', $replacement);
            }
	 }

      } elseif ($event && preg_match('/#_RSVPSTART/', $result)) {
         // show the end date+time for which a user can rsvp for an event
         if (eme_is_event_rsvp($event)) {
               $rsvp_number_days=intval($event['event_properties']['rsvp_start_number_days']);
               $rsvp_number_hours=intval($event['event_properties']['rsvp_start_number_hours']);
	       if ($rsvp_number_days || $rsvp_number_hours) {
		       if ($event['event_properties']['rsvp_start_target']=='end')
			       $rsvp_date_obj = new ExpressiveDate($event['event_end'],$eme_timezone);
		       else
			       $rsvp_date_obj = new ExpressiveDate($event['event_start'],$eme_timezone);
		       $rsvp_date_obj->minusDays($rsvp_number_days)->minusHours($rsvp_number_hours);
		       $replacement = eme_localized_datetime($rsvp_date_obj->getDateTime(),$eme_timezone);
	       }
         }

      } elseif ($event && preg_match('/#_RSVPEND/', $result)) {
         // show the end date+time for which a user can rsvp for an event
         if (eme_is_event_rsvp($event)) {
	       if ($event['event_properties']['rsvp_end_target']=='start')
		       $rsvp_date_obj = new ExpressiveDate($event['event_start'],$eme_timezone);
	       else
		       $rsvp_date_obj = new ExpressiveDate($event['event_end'],$eme_timezone);
               $rsvp_number_days=intval($event['rsvp_number_days']);
               $rsvp_number_hours=intval($event['rsvp_number_hours']);
               $rsvp_date_obj->minusDays($rsvp_number_days)->minusHours($rsvp_number_hours);
               $replacement = eme_localized_datetime($rsvp_date_obj->getDateTime(),$eme_timezone);
         }

      } elseif ($event && preg_match('/#_CANCELEND/', $result)) {
         // show the end date+time for which a user can cancel an rsvp for an event
         if (eme_is_event_rsvp($event)) {
		 $eme_cancel_rsvp_days=intval($event['event_properties']['cancel_rsvp_days']);
		 $cancel_cutofftime=new ExpressiveDate($event['event_start'],$eme_timezone);
		 $cancel_cutofftime->minusDays($eme_cancel_rsvp_days);
		 $replacement = eme_localized_datetime($cancel_cutofftime->getDateTime(),$eme_timezone);
         }

      } elseif ($event && preg_match('/#_RSVP_STATUS/', $result)) {
	 $replacement=eme_event_rsvp_status($event);

      } elseif ($event && preg_match('/#_IS_RSVP_STARTED/', $result)) {
	 $replacement=eme_is_event_rsvp_started($event);

      } elseif ($event && preg_match('/#_IS_RSVP_ENDED/', $result)) {
	 $replacement=eme_is_event_rsvp_ended($event);

      } elseif ($event && preg_match('/#_EVENT_EXTERNAL_REF/', $result)) {
         if (!empty($event['event_external_ref'])) {
            // remove the 'fb_' prefix
            $replacement=preg_replace('/fb_/','',$event['event_external_ref']);
            if ($target == "html") {
               $replacement = apply_filters('eme_general', $replacement);
            } elseif ($target == "rss")  {
               $replacement = apply_filters('the_content_rss', $replacement);
            } else {
               $replacement = apply_filters('eme_text', $replacement);
            }
         }

      } elseif ($event && preg_match('/#_IS_RSVP_PASSWORD_ENABLED/', $result)) {
         if (eme_is_event_rsvp($event) && !empty($event['event_properties']['rsvp_password']))
            $replacement = 1;
         else
            $replacement = 0;

      } elseif ($event && preg_match('/#_IS_RSVP_ENABLED/', $result)) {
         if (eme_is_event_rsvp($event))
            $replacement = 1;
         else
            $replacement = 0;

      } elseif ($event && preg_match('/#_IS_LOGIN_REQUIRED/', $result)) {
         if ($event ['event_status'] == EME_EVENT_STATUS_PRIVATE || $event['registration_wp_users_only'])
            $replacement = 1;
         else
            $replacement = 0;

      } elseif ($event && preg_match('/#_IS_PRIVATE_EVENT/', $result)) {
         if ($event ['event_status'] == EME_EVENT_STATUS_PRIVATE)
            $replacement = 1;
         else
            $replacement = 0;

      } elseif ($event && preg_match('/#_IS_RECURRENT_EVENT/', $result)) {
         if ($event ['recurrence_id'])
            $replacement = 1;
         else
            $replacement = 0;

      } elseif ($event && preg_match('/#_IS_ONGOING_EVENT/', $result)) {
         $eme_start_obj = new ExpressiveDate($event['event_start'],$eme_timezone);
         $eme_end_obj = new ExpressiveDate($event['event_end'],$eme_timezone);
         if ($eme_start_obj <= $eme_date_obj_now &&
             $eme_end_obj >= $eme_date_obj_now)
            $replacement = 1;
         else
            $replacement = 0;

      } elseif ($event && preg_match('/#_IS_ENDED_EVENT/', $result)) {
         $eme_end_obj = new ExpressiveDate($event['event_end'],$eme_timezone);
         if ($eme_end_obj < $eme_date_obj_now)
            $replacement = 1;
         else
            $replacement = 0;

      } elseif ($event && preg_match('/#_IS_REGISTERED$/', $result)) {
         if (is_user_logged_in() && eme_get_booking_ids_by_wp_event_id($current_userid,$event['event_id']))
            $replacement = 1;
         else
            $replacement = 0;

      } elseif ($event && preg_match('/#_IS_REGISTERED_PAID$/', $result)) {
	      $replacement = 0;
	      if (is_user_logged_in()) {
		      $booking_ids_arr=eme_get_booking_ids_by_wp_event_id($current_userid,$event['event_id']);
		      if (!empty($booking_ids_arr)) {
			      $booking_ids=join(',',$booking_ids_arr);
			      $unpaid_booking_ids=eme_get_unpaid_booking_ids_by_bookingids($booking_ids);
			      if (empty($unpaid_booking_ids))
				      $replacement = 1;
		      }
	      }

      } elseif ($event && preg_match('/#_IS_REGISTERED_PENDING$/', $result)) {
	      $replacement = 0;
	      if (is_user_logged_in()) {
		      $booking_ids_arr=eme_get_booking_ids_by_wp_event_id($current_userid,$event['event_id']);
		      if (!empty($booking_ids_arr)) {
			      $booking_ids=join(',',$booking_ids_arr);
			      $pending_booking_ids=eme_get_pending_booking_ids_by_bookingids($booking_ids);
			      if (!empty($pending_booking_ids))
				      $replacement = 1;
		      }
	      }
      } elseif ($event && preg_match('/#_IS_REGISTERED_APPROVED$/', $result)) {
	      $replacement = 0;
	      if (is_user_logged_in()) {
		      $booking_ids_arr=eme_get_booking_ids_by_wp_event_id($current_userid,$event['event_id']);
		      if (!empty($booking_ids_arr)) {
			      $booking_ids=join(',',$booking_ids_arr);
			      $pending_booking_ids=eme_get_pending_booking_ids_by_bookingids($booking_ids);
			      if (empty($pending_booking_ids))
				      $replacement = 1;
		      }
	      }

      } elseif ($event && preg_match('/#_IS_MULTIPRICE/', $result)) {
         if (eme_is_multi($event['price']))
            $replacement = 1;
         else
            $replacement = 0;

      } elseif ($event && preg_match('/#_IS_MULTISEAT/', $result)) {
         if (eme_is_multi($event['event_seats']))
            $replacement = 1;
         else
            $replacement = 0;

      } elseif ($event && preg_match('/#_IS_ALLDAY/', $result)) {
         if ($event['event_properties']['all_day'])
            $replacement = 1;
         else
            $replacement = 0;

      } elseif ($event && preg_match('/#_IS_ATTENDANCE/', $result)) {
         if ($event['event_properties']['take_attendance'])
            $replacement = 1;
         else
            $replacement = 0;

      } elseif ($event && preg_match('/#_IS_AUTHOR$/', $result)) {
         if ($event['event_author']==$current_userid)
            $replacement = 1;
         else
            $replacement = 0;

      } elseif ($event && preg_match('/#_IS_CONTACTPERSON/', $result)) {
         if ($event['event_contactperson_id']==$current_userid || ($event['event_contactperson_id']==-1 && $event['event_author']==$current_userid))
            $replacement = 1;
         else
            $replacement = 0;

      } elseif ($event && preg_match('/#_IS_AUTHOR_OR_CONTACTPERSON/', $result)) {
         if ($event['event_author']==$current_userid || $event['event_contactperson_id']==$current_userid || ($event['event_contactperson_id']==-1 && $event['event_author']==$current_userid))
            $replacement = 1;
         else
            $replacement = 0;

      } elseif ($event && preg_match('/#_IS_MULTIDAY/', $result)) {
         if (eme_get_date_from_dt($event['event_start']) != eme_get_date_from_dt($event['event_end']))
            $replacement = 1;
         else
            $replacement = 0;

      } elseif ($event && preg_match('/#_IS_FIRST_RECURRENCE/', $result)) {
         // returns 1 if the event is the first event in a recurrence series
         if ($event ['recurrence_id']) {
            $event_ids = eme_get_recurrence_eventids ( $event ['recurrence_id'] );
            $nbr = array_search($event['event_id'],$event_ids);
            if ($nbr !== false && $nbr==0) {
               $replacement = 1;
            }
	 }

      } elseif ($event && preg_match('/#_IS_LAST_RECURRENCE/', $result)) {
         // returns 1 if the event is the last event in a recurrence series
         if ($event ['recurrence_id']) {
            $event_ids = eme_get_recurrence_eventids ( $event ['recurrence_id'] );
            $nbr = array_search($event['event_id'],$event_ids);
            $last_index = count($events)-1;
            if ($nbr !== false && $nbr==$last_index) {
               $replacement = 1;
            }
         }

      } elseif ($event && preg_match('/#_IS_INVITE_ONLY/', $result)) {
         if ($event['event_properties']['invite_only'])
            $replacement = 1;
         else
            $replacement = 0;
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

   # now handle all possible location placeholders
   # but the eme_replace_locations_placeholders can't do "do_shortcode" at the end, because
   # this would cause [eme_if] tags to be replaced here already, while some placeholders of the
   # event haven't been replaced yet (like time placeholders, and event details)
   $format = eme_replace_event_location_placeholders ( $format, $event, $orig_target, 0, $lang );

  // for extra date formatting, eg. #_{d/m/Y}
   $needle_offset=0;
   preg_match_all("/#(ESC|URL)?@?_\{.*?\}/", $format, $placeholders2, PREG_OFFSET_CAPTURE);
   foreach($placeholders2[0] as $orig_result) {
      $result = $orig_result[0];
      $orig_result_needle = $orig_result[1]-$needle_offset;
      $orig_result_length = strlen($orig_result[0]);
      $need_escape = 0;
      $need_urlencode = 0;
      if (strstr($result,'#ESC')) {
         $result = str_replace("#ESC","#",$result);
         $need_escape=1;
      } elseif (strstr($result,'#URL')) {
         $result = str_replace("#URL","#",$result);
         $need_urlencode=1;
      }
      $replacement = '';
      if(substr($result, 0, 3 ) == "#@_") {
         $my_dt = "event_end";
         $offset = 4;
      } else {
         $my_dt = "event_start";
         $offset = 3;
      }

      $replacement = eme_localized_date($event[$my_dt],$eme_timezone,substr($result, $offset, (strlen($result)-($offset+1)) ));

      if ($need_escape)
         $replacement = eme_esc_html(preg_replace('/\n|\r/','',$replacement));
      if ($need_urlencode)
         $replacement = rawurlencode($replacement);
      $format = substr_replace($format, $replacement, $orig_result_needle,$orig_result_length );
      $needle_offset += $orig_result_length-strlen($replacement);
   }

   if ($recursion_level==0) {
	   # we handle NOTES the last, this used to be the default behavior
	   # so no placeholder replacement happened accidentaly in possible shortcodes inside #_NOTES
	   # but since we have templates to aid in all that ...
	   if (!$eme_enable_notes_placeholders)
		   $format = eme_replace_notes_placeholders ( $format, $event, $orig_target );

	   // replace leftover generic placeholders
	   $format = eme_replace_generic_placeholders ( $format, $orig_target );

	   // now replace any language tags found in the format itself
	   $format = eme_translate($format,$lang);

	   // now some html
	   if ($target=="html")
		   $format=eme_nl2br_save_html($format);

	   if ($do_shortcode)
		   $format = do_shortcode($format);
   }

   return $format;
}

function eme_replace_notes_placeholders($format, $event="", $target="html") {
   $email_target = 0;
   $orig_target = $target;
   if ($target == "htmlmail") {
           $email_target = 1;
           $target = "html";
   }

   if ($event && preg_match_all('/#(ESC)?_(DETAILS|NOTES|EXCERPT|EVENTDETAILS|NOEXCERPT)/', $format, $placeholders,PREG_OFFSET_CAPTURE)) {
      $needle_offset=0;
      foreach($placeholders[0] as $orig_result) {
         $result = $orig_result[0];
         $orig_result_needle = $orig_result[1]-$needle_offset;
         $orig_result_length = strlen($orig_result[0]);
         $found = 1;
	 $need_escape = 0;
         if (strstr($result,'#ESC')) {
            $result = str_replace("#ESC","#",$result);
            $need_escape=1;
         }
         $field = ltrim(strtolower($result), "#_");
         // to catch every alternative (we just need to know if it is an excerpt or not)
         $show_excerpt=0;
         $show_rest=0;
         if ($field == "excerpt")
            $show_excerpt=1;
         if ($field == "noexcerpt")
            $show_rest=1;

         $replacement = "";
         if (isset($event['event_notes'])) {
            // first translate, since for "noexcerpt" the language indication is not there (it is only at the beginning of the notes, not after the separator)
            $event_notes = eme_translate($event['event_notes']);

            // make sure no windows line endings are in
            $event_notes = preg_replace('/\r\n|\n\r/',"\n",$event_notes);
            if ($show_excerpt) {
               // If excerpt, use the part before the more delimiter, removing a possible line ending
               if (preg_match ( '/<\!--more-->/', $event_notes)) {
		       $matches=preg_split('/\n?<\!--more-->/',$event_notes);
		       $replacement = eme_excerpt($matches[0]);
	       } else {
		       $replacement = eme_excerpt($event_notes);
		       $excerpt_length = apply_filters( 'eme_excerpt_length', 55 );
		       $replacement = wp_trim_words($replacement, $excerpt_length);
	       }
            } elseif ($show_rest) {
               // If the rest is wanted, use the part after the more delimiter, removing a possible line ending
               $matches=preg_split('/<\!--more-->\n?/',$event_notes);
               if (isset($matches[1]))
		  $replacement = $matches[1];
	       else
		  $replacement = $event_notes;
            } else {
               if (preg_match ( '/<\!--more-->/', $event_notes)) {
		       // remove the more-delimiter, but if it was on a line by itself, replace by a linefeed
		       $replacement = preg_replace('/\n<\!--more-->\n/', "\n" ,$event_notes );
		       $replacement = preg_replace('/<\!--more-->/', '' ,$replacement );
	       } else {
		       $replacement = $event_notes;
	       }
            }
         }
         if ($target == "html") {
            if ($show_excerpt) {
               $replacement = apply_filters('the_excerpt', $replacement);
	    } else {
	       // apply the_content filter, but don't replace shortcodes here already
	       remove_filter( 'the_content', 'do_shortcode', 11 );
               $replacement = apply_filters('the_content', $replacement);
	       add_filter( 'the_content', 'do_shortcode', 11 );
	    }
         } elseif ($target == "rss") {
            if ($show_excerpt)
               $replacement = apply_filters('the_excerpt_rss', $replacement);
            else
               $replacement = apply_filters('the_content_rss', $replacement);
         } else {
            $replacement = apply_filters('eme_text', $replacement);
         }
         if ($found) {
            if ($need_escape)
               $replacement = eme_esc_html(preg_replace('/\n|\r/','',$replacement));
	    $format = substr_replace($format, $replacement, $orig_result_needle,$orig_result_length );
	    $needle_offset += $orig_result_length-strlen($replacement);
         }
      }
   }

   // in case generic placeholders are in here, replace them already
   $format = eme_replace_generic_placeholders ( $format, $orig_target );
 
   return $format;
}

// TEMPLATE TAGS
function eme_get_events_list($limit, $scope = "future", $order = "ASC", $format = '', $format_header='', $format_footer='', $echo = 0, $category = '',$showperiod = '', $long_events = 0, $author = '', $contact_person='', $paging=0, $event_ids="", $location_id = "", $user_registered_only = 0, $show_ongoing=1, $link_showperiod=0, $notcategory = '', $show_recurrent_events_once= 0, $template_id = 0, $template_id_header=0, $template_id_footer=0, $no_events_message="", $template_id_no_events=0) {
   global $post, $eme_timezone;
   if ($limit === "") {
      $limit = get_option('eme_event_list_number_items' );
   }
   if (strpos ( $limit, "=" )) {
      // allows the use of arguments without breaking the legacy code
      $eme_event_list_number_events=get_option('eme_event_list_number_items' );
      $defaults = array ('limit' => $eme_event_list_number_events, 'scope' => $scope, 'order' => $order, 'format' => $format, 'format_header'=>$format_header, 'format_footer'=>$format_footer,'echo' => $echo , 'category' => $category, 'showperiod' => $showperiod, 'author' => $author, 'contact_person' => $contact_person, 'paging'=>$paging, 'long_events' => $long_events, 'event_ids'=>$event_ids, 'location_id' => $location_id, 'show_ongoing' => $show_ongoing=1, 'user_registered_only'=>$user_registered_only, 'link_showperiod' => $link_showperiod, 'notcategory' => $notcategory, 'show_recurrent_events_once' => $show_recurrent_events_once, 'template_id' => $template_id, 'template_id_header' => $template_id_header, 'template_id_footer' => $template_id_footer, 'no_events_message' => $no_events_message, 'template_id_no_events' => $template_id_no_events);
      $r = wp_parse_args ( $limit, $defaults );
      extract ( $r );
      // for AND categories: the user enters "+" and this gets translated to " " by wp_parse_args
      // so we fix it again
      $category = preg_replace("/ /","+",$category);
      $notcategory = preg_replace("/ /","+",$notcategory);
   }
   $echo = filter_var($echo,FILTER_VALIDATE_BOOLEAN);
   $long_events = filter_var($long_events,FILTER_VALIDATE_BOOLEAN);
   $paging = filter_var($paging,FILTER_VALIDATE_BOOLEAN);
   $show_ongoing = filter_var($show_ongoing,FILTER_VALIDATE_BOOLEAN);

   if ($scope == "")
      $scope = "future";

   if ($template_id) {
      $format = eme_get_template_format($template_id);
   }
   if ($template_id_header) {
      $format_header = eme_get_template_format($template_id_header);
   }
   if ($template_id_footer) {
      $format_footer = eme_get_template_format($template_id_footer);
   }
   if (eme_is_empty_string($format)) {
      $format = get_option('eme_event_list_item_format' );
      if (empty($format_header)) {
	      $format_header = get_option('eme_event_list_item_format_header' );
	      if (eme_is_empty_string($format_header)) $format_header = DEFAULT_EVENT_LIST_HEADER_FORMAT;
      }
      if (empty($format_footer)) {
	      $format_footer = get_option('eme_event_list_item_format_footer' );
	      if (eme_is_empty_string($format_footer)) $format_footer = DEFAULT_EVENT_LIST_FOOTER_FORMAT;
      }
   }

   // for registered users: we'll add a list of event_id's for that user only
   $extra_conditions = "";
   $extra_conditions_arr = array();
   if ($user_registered_only == 1 && is_user_logged_in()) {
      $current_userid=get_current_user_id();
      $list_of_event_ids_arr=eme_get_event_ids_for($current_userid);
      if (!empty($list_of_event_ids_arr)) {
         $list_of_event_ids=join(",",eme_get_event_ids_for($current_userid));
         $extra_conditions_arr[] = "event_id in ($list_of_event_ids)";
      } else {
         // user has no registered events, then make sure none are shown
         $extra_conditions_arr[] = "event_id = 0";
      }
   }
   if (!empty($event_ids)) {
	   $extra_conditions_arr[] = "event_id in ($event_ids)";
   }
   if (!empty($extra_conditions_arr)) {
	   $extra_conditions="(".join(" AND ",$extra_conditions_arr).")";
   }

   $prev_text = "";
   $next_text = "";
   $limit_start=0;
   $limit_end=0;

   // for browsing: if limit=0,paging=1 and only for this_week,this_month or today
   if ($limit>0 && $paging==1 && isset($_GET['eme_offset'])) {
      $limit_offset=intval($_GET['eme_offset']);
   } else {
      $limit_offset=0;
   }

   if ($paging==1 && $limit==0) {
      $eme_date_obj=new ExpressiveDate("now",$eme_timezone);
      $scope_offset=0;
      $scope_text = "";
      if (isset($_GET['eme_offset']))
         $scope_offset=$_GET['eme_offset'];
      $prev_offset=$scope_offset-1;
      $next_offset=$scope_offset+1;
      if ($scope=="this_week") {
         $start_of_week = get_option('start_of_week');
         $eme_date_obj->setWeekStartDay($start_of_week);
         $eme_date_obj->modifyWeeks($scope_offset);
         $limit_start=$eme_date_obj->startOfWeek()->format('Y-m-d');
         $limit_end=$eme_date_obj->endOfWeek()->format('Y-m-d');
         $scope = "$limit_start--$limit_end";
         $scope_text = eme_localized_date($limit_start,$eme_timezone)." -- ".eme_localized_date($limit_end,$eme_timezone);
         $prev_text = __('Previous week','events-made-easy');
         $next_text = __('Next week','events-made-easy');

      } elseif ($scope=="this_month") {
         // we first set the current date to the beginning of this month, otherwise the offset flips (e.g. if you're
         // on August 31 and call modifyMonths(1), it will give you October because Sept 31 doesn't exist
         $eme_date_obj->startOfMonth()->modifyMonths($scope_offset);
         $limit_start = $eme_date_obj->startOfMonth()->format('Y-m-d');
         $limit_end   = $eme_date_obj->endOfMonth()->format('Y-m-d');
         $scope = "$limit_start--$limit_end";
         $scope_text = eme_localized_date($limit_start,$eme_timezone,get_option('eme_show_period_monthly_dateformat'));
         $prev_text = __('Previous month','events-made-easy');
         $next_text = __('Next month','events-made-easy');

      } elseif ($scope=="this_year") {
         $eme_date_obj->modifyYears($scope_offset);
         $year=$eme_date_obj->getYear();
         $limit_start = "$year-01-01";
         $limit_end   = "$year-12-31";
         $scope = "$limit_start--$limit_end";
         $scope_text = eme_localized_date($limit_start,$eme_timezone,get_option('eme_show_period_yearly_dateformat'));
         $prev_text = __('Previous year','events-made-easy');
         $next_text = __('Next year','events-made-easy');

      } elseif ($scope=="today") {
         $scope = $eme_date_obj->modifyDays($scope_offset)->format('Y-m-d');
         $limit_start = $scope;
         $limit_end   = $scope;
         $scope_text = eme_localized_date($limit_start,$eme_timezone);
         $prev_text = __('Previous day','events-made-easy');
         $next_text = __('Next day','events-made-easy');

      } elseif ($scope=="tomorrow") {
         $scope_offset++;
         $scope = $eme_date_obj->modifyDays($scope_offset)->format('Y-m-d');
         $limit_start = $scope;
         $limit_end   = $scope;
         $scope_text = eme_localized_date($limit_start,$eme_timezone);
         $prev_text = __('Previous day','events-made-easy');
         $next_text = __('Next day','events-made-easy');
      }
   }
   // We request $limit+1 events, so we know if we need to show the pagination link or not.
   if ($limit==0) {
      $events = eme_get_events ( 0, $scope, $order, $limit_offset, $location_id, $category, $author, $contact_person, $show_ongoing, $notcategory, $show_recurrent_events_once, $extra_conditions );
   } else {
      $events = eme_get_events ( $limit+1, $scope, $order, $limit_offset, $location_id, $category, $author, $contact_person, $show_ongoing, $notcategory, $show_recurrent_events_once, $extra_conditions );
   }
   $events_count=count($events);

   // get the paging output ready
   $id_base = preg_replace("/\D/","_",microtime(1));
   $id_base = rand()."_".$id_base;
   $pagination_top = "<div id='div_events-pagination-top_$id_base' class='events-pagination-top'> ";
   $nav_hidden_class="style='visibility:hidden;'";
   if ($paging==1 && $limit>0) {
      // for normal paging and there're no events, we go back to offset=0 and try again
      if ($events_count==0) {
         $limit_offset=0;
         $events = eme_get_events ( $limit+1, $scope, $order, $limit_offset, $location_id, $category, $author, $contact_person, $show_ongoing, $notcategory, $show_recurrent_events_once, $extra_conditions );
         $events_count=count($events);
      }
      $prev_text=__('Previous page','events-made-easy');
      $next_text=__('Next page','events-made-easy');
      $page_number = floor($limit_offset/$limit) + 1;
      $this_page_url=get_permalink($post->ID);
      //$this_page_url=$_SERVER['REQUEST_URI'];
      // remove the offset info
      $this_page_url= remove_query_arg('eme_offset',$this_page_url);

      // we add possible fields from the filter section
      $eme_filters["eme_eventAction"]=1;
      $eme_filters["eme_cat_filter"]=1;
      $eme_filters["eme_loc_filter"]=1;
      $eme_filters["eme_city_filter"]=1;
      $eme_filters["eme_country_filter"]=1;
      $eme_filters["eme_scope_filter"]=1;
      $eme_filters["eme_contact_filter"]=1;
      $eme_filters["eme_author_filter"]=1;
      foreach ($_REQUEST as $key => $item) {
         if (isset($eme_filters[$key])) {
            # if you selected multiple items, $item is an array, but rawurlencode needs a string
            if (is_array($item)) $item=join(',',eme_sanitize_request($item));
	    if (!empty($item))
		    $this_page_url=add_query_arg(array($key=>$item),$this_page_url);
         }
      }

      // we always provide the text, so everything stays in place (but we just hide it if needed, and change the link to empty
      // to prevent going on indefinitely and thus allowing search bots to go on for ever
      if ($events_count > $limit) {
         $forward = $limit_offset + $limit;
         $backward = $limit_offset - $limit;
         if ($backward < 0)
            $pagination_top.= "<a class='eme_nav_left' $nav_hidden_class href='#'>&lt;&lt; $prev_text</a>";
         else
            $pagination_top.= "<a class='eme_nav_left' href='".add_query_arg(array('eme_offset'=>$backward),$this_page_url)."'>&lt;&lt; $prev_text</a>";
         $pagination_top.= "<a class='eme_nav_right' href='".add_query_arg(array('eme_offset'=>$forward),$this_page_url)."'>$next_text &gt;&gt;</a>";
         $pagination_top.= "<span class='eme_nav_center'>".__('Page ','events-made-easy').$page_number."</span>";
      }
      if ($events_count <= $limit && $limit_offset>0) {
         $forward = 0;
         $backward = $limit_offset - $limit;
         if ($backward < 0)
            $pagination_top.= "<a class='eme_nav_left' $nav_hidden_class href='#'>&lt;&lt; $prev_text</a>";
         else
            $pagination_top.= "<a class='eme_nav_left' href='".add_query_arg(array('eme_offset'=>$backward),$this_page_url)."'>&lt;&lt; $prev_text</a>";
         $pagination_top.= "<a class='eme_nav_right' $nav_hidden_class href='#'>$next_text &gt;&gt;</a>";
         $pagination_top.= "<span class='eme_nav_center'>".__('Page ','events-made-easy').$page_number."</span>";
      }
   }
   if ($paging==1 && $limit==0) {
      $this_page_url=$_SERVER['REQUEST_URI'];
      // remove the offset info
      $this_page_url= remove_query_arg('eme_offset',$this_page_url);

      // we add possible fields from the filter section
      $eme_filters["eme_eventAction"]=1;
      $eme_filters["eme_cat_filter"]=1;
      $eme_filters["eme_loc_filter"]=1;
      $eme_filters["eme_city_filter"]=1;
      $eme_filters["eme_country_filter"]=1;
      $eme_filters["eme_scope_filter"]=1;
      $eme_filters["eme_contact_filter"]=1;
      $eme_filters["eme_author_filter"]=1;
      foreach ($_REQUEST as $key => $item) {
         if (isset($eme_filters[$key])) {
            # if you selected multiple items, $item is an array, but rawurlencode needs a string
            if (is_array($item)) $item=join(',',eme_sanitize_request($item));
	    if (!empty($item))
		    $this_page_url=add_query_arg(array($key=>$item),$this_page_url);
         }
      }

      // to prevent going on indefinitely and thus allowing search bots to go on for ever,
      // we stop providing links if there are no more events left
      $older_events=eme_get_events ( 1, "--".$limit_start, $order, 0, $location_id, $category, $author, $contact_person, $show_ongoing, $notcategory, $show_recurrent_events_once, $extra_conditions );
      $newer_events=eme_get_events ( 1, "++".$limit_end, $order, 0, $location_id, $category, $author, $contact_person, $show_ongoing, $notcategory, $show_recurrent_events_once, $extra_conditions );
      if (count($older_events)>0)
         $pagination_top.= "<a class='eme_nav_left' href='".add_query_arg(array('eme_offset'=>$prev_offset),$this_page_url) ."'>&lt;&lt; $prev_text</a>";
      else
         $pagination_top.= "<a class='eme_nav_left' $nav_hidden_class href='#'>&lt;&lt; $prev_text</a>";

      if (count($newer_events)>0)
         $pagination_top.= "<a class='eme_nav_right' href='".add_query_arg(array('eme_offset'=>$next_offset),$this_page_url) ."'>$next_text &gt;&gt;</a>";
      else
         $pagination_top.= "<a class='eme_nav_right' $nav_hidden_class href='#'>$next_text &gt;&gt;</a>";

      $pagination_top.= "<span class='eme_nav_center'>$scope_text</span>";
   }
   $pagination_top.= "</div>";
   $pagination_bottom = str_replace("events-pagination-top","events-pagination-bottom",$pagination_top);

 
   // lets's replace some placeholders that only matter in the list
   // we requested $limit+1 events, so we need to account for $limit, if reached
   $actual_count=$events_count;
   if ($actual_count>$limit) $actual_count=$limit;
   $format=str_replace('#_EVENTS_COUNT', $actual_count ,$format );
   $format_header=str_replace('#_EVENTS_COUNT', $actual_count ,$format_header );
   $format_footer=str_replace('#_EVENTS_COUNT', $actual_count ,$format_footer );
   
   $output = "";
   if ($events_count>0 && empty($showperiod) && !$long_events) {
	   $event_counter=1;
	   foreach($events as $event) {
		   // we requested $limit+1 events, so we need to break at the $limit, if reached
		   if ($limit>0 && $event_counter>$limit)
			   break;
		   $tmp_format=str_replace('#_EVENT_COUNTER', $event_counter ,$format );
		   $output .= eme_replace_event_placeholders ( $tmp_format, $event );
		   $event_counter++;
	   }
	   //Add headers and footers to output
	   $empty_event = eme_new_event();
	   $output =  eme_replace_event_placeholders($format_header, $empty_event) .  $output . eme_replace_event_placeholders($format_footer,$empty_event);
   } elseif ($events_count>0) {
	   # we first need to determine on which days events occur
	   # this code is identical to that in eme_calendar.php for "long events"
	   $eventful_days= array();
	   $event_counter=1;
	   foreach ( $events as $event ) {
		   // we requested $limit+1 events, so we need to break at the $limit, if reached
		   if ($limit>0 && $event_counter>$limit)
			   break;
		   $eme_date_obj_tmp=new ExpressiveDate($event['event_start'],$eme_timezone);
		   $eme_date_obj_end=new ExpressiveDate($event['event_end'],$eme_timezone);
		   if ($eme_date_obj_end < $eme_date_obj_tmp)
			   $eme_date_obj_end=$eme_date_obj_tmp->copy();
		   if ($long_events) {
			   //Show events on every day that they are still going on
			   while( $eme_date_obj_tmp < $eme_date_obj_end) {
				   $event_eventful_date = $eme_date_obj_tmp->getDate();
				   if(isset($eventful_days[$event_eventful_date]) &&  is_array($eventful_days[$event_eventful_date]) ) {
					   $eventful_days[$event_eventful_date][] = $event;
				   } else {
					   $eventful_days[$event_eventful_date] = array($event);
				   }
				   $eme_date_obj_tmp->addOneDay();
			   }
		   } else {
			   //Only show events on the day that they start
			   $event_start_date=eme_get_date_from_dt($event['event_start']);
			   if ( isset($eventful_days[$event_start_date]) && is_array($eventful_days[$event_start_date]) ) {
				   $eventful_days[$event_start_date][] = $event;
			   } else {
				   $eventful_days[$event_start_date] = array($event);
			   }
		   }
		   $event_counter++;
	   }

	   $event_counter=1;
	   $curyear="";
	   $curmonth="";
	   $curday="";
	   foreach($eventful_days as $day_key => $day_events) {
		   $eme_date_obj=new ExpressiveDate($day_key,$eme_timezone);
		   list($theyear, $themonth, $theday) = explode('-', $eme_date_obj->getDate());
		   if ($showperiod == "yearly" && $theyear != $curyear) {
			   $output .= "<li class='eme_period'>".eme_localized_date ($day_key,$eme_timezone,get_option('eme_show_period_yearly_dateformat'))."</li>";
		   } elseif ($showperiod == "monthly" && "$theyear$themonth" != "$curyear$curmonth") {
			   $output .= "<li class='eme_period'>".eme_localized_date ($day_key,$eme_timezone,get_option('eme_show_period_monthly_dateformat'))."</li>";
		   } elseif ($showperiod == "daily" && "$theyear$themonth$theday" != "$curyear$curmonth$curday") {
			   $output .= "<li class='eme_period'>";
			   if ($link_showperiod) {
				   // if there is a specific class filter for the urls, do it
				   $class="";
				   if (has_filter('eme_calday_url_class_filter')) $class=apply_filters('eme_calday_url_class_filter',$class);
				   if (!empty($class)) $class="class='$class'";

				   $eme_link=eme_calendar_day_url($theyear."-".$themonth."-".$theday);
				   $output .= "<a href='$eme_link' $class>".eme_localized_date ($day_key,$eme_timezone)."</a>";
			   } else {
				   $output .= eme_localized_date ($day_key,$eme_timezone);
			   }
			   $output .= "</li>";
		   }
		   $curyear=$theyear;
		   $curmonth=$themonth;
		   $curday=$theday;
		   foreach($day_events as $event) {
			   $tmp_format=str_replace('#_EVENT_COUNTER', $event_counter ,$format );
			   $output .= eme_replace_event_placeholders ( $tmp_format, $event );
			   $event_counter++;
		   }
	   }

	   //Add headers and footers to output
	   $empty_event = eme_new_event();
	   $output =  eme_replace_event_placeholders($format_header, $empty_event) .  $output . eme_replace_event_placeholders($format_footer,$empty_event);
   } else {
	   if ($template_id_no_events) {
		   $output = do_shortcode(eme_get_template_format($template_id_no_events));
	   } elseif (empty($no_events_message)) {
		   $no_events_message=do_shortcode(get_option('eme_no_events_message'));
		   // this is also used in eme_widgets, so if you change something here, check the code there too
		   $output = "<span class='events-no-events'>" . $no_events_message . "</span>";
	   } 
   }

   // add the pagination if needed
   if ($paging)
	   $output = $pagination_top . $output . $pagination_bottom;

   // see how to return the output
   if ($echo)
	   echo $output;
   else
	   return $output;
}

function eme_get_events_list_shortcode($atts) {
   $eme_event_list_number_events=get_option('eme_event_list_number_items' );
   extract ( shortcode_atts ( array ('limit' => $eme_event_list_number_events, 'scope' => 'future', 'order' => 'ASC', 'format' => '', 'category' => '', 'showperiod' => '', 'author' => '', 'contact_person' => '', 'paging' => 0, 'long_events' => 0, 'location_id' => 0, 'user_registered_only' => 0, 'show_ongoing' => 1, 'link_showperiod' => 0, 'notcategory' => '', 'show_recurrent_events_once' => 0, 'template_id' => 0, 'template_id_header' => 0, 'template_id_footer' => 0, 'no_events_message' => '', 'template_id_no_events' => 0, 'ignore_filter' => 0 ), $atts ) );

   $event_id="";
   $event_id_arr=array();
   $location_id_arr=array();

   // the filter list overrides the settings
   if (!$ignore_filter && isset($_REQUEST['eme_eventAction']) && $_REQUEST['eme_eventAction'] == 'filter') {
      if (!empty($_REQUEST['eme_scope_filter'])) {
         $scope = eme_sanitize_request($_REQUEST['eme_scope_filter']);
      }
      if (!empty($_REQUEST['eme_author_filter']) && intval($_REQUEST['eme_author_filter'])>0) {
         $author = intval($_REQUEST['eme_author_filter']);
      }
      if (!empty($_REQUEST['eme_contact_filter']) && intval($_REQUEST['eme_contact_filter'])>0) {
         $contact_person = intval($_REQUEST['eme_contact_filter']);
      }
      if (!empty($_REQUEST['eme_loc_filter'])) {
         if (is_array($_REQUEST['eme_loc_filter'])) {
	    $arr = eme_array_remove_empty_elements(eme_sanitize_request($_REQUEST['eme_loc_filter']));
            if (!empty($arr))
            	$location_id_arr=$arr;
	 } else {
            $location_id_arr[]=eme_sanitize_request($_REQUEST['eme_loc_filter']);
	 }
	 if (empty($location_id_arr))
		 $location_id=-1;
      }
      if (!empty($_REQUEST['eme_city_filter'])) {
         $cities=eme_sanitize_request($_REQUEST['eme_city_filter']);
         $tmp_ids=eme_get_city_location_ids($cities);
         if (empty($location_id_arr))
                 $location_id_arr=$tmp_ids;
         else
                 $location_id_arr=array_intersect($location_id_arr,$tmp_ids);
	 if (empty($location_id_arr))
		 $location_id=-1;

      }
      if (!empty($_REQUEST['eme_country_filter'])) {
         $countries=eme_sanitize_request($_REQUEST['eme_country_filter']);
         $tmp_ids=eme_get_country_location_ids($countries);
         if (empty($location_id_arr))
                 $location_id_arr=$tmp_ids;
         else
                 $location_id_arr=array_intersect($location_id_arr,$tmp_ids);
	 if (empty($location_id_arr))
		 $location_id=-1;
      }
      if (!empty($_REQUEST['eme_cat_filter'])) {
         if (is_array($_REQUEST['eme_cat_filter'])) {
	    $arr = eme_array_remove_empty_elements(eme_sanitize_request($_REQUEST['eme_cat_filter']));
            if (!empty($arr))
		    $category=join(',',$arr);
	 } else {
            $category=eme_sanitize_request($_REQUEST['eme_cat_filter']);
	 }
      }
      foreach($_REQUEST as $key=>$value) {
         if (preg_match('/eme_customfield_filter(\d+)/', eme_sanitize_request($key), $matches)) {
               $field_id = intval($matches[1]);
               $formfield = eme_get_formfield($field_id);
	       if (!empty($formfield)) {
		       $is_multi = eme_is_multifield($formfield['field_type']);
		       if ($formfield['field_purpose'] == 'events') {
			       $tmp_ids=eme_get_cf_event_ids(eme_sanitize_request($value),$field_id,$is_multi);
			       if (empty($event_id_arr))
				       $event_id_arr=$tmp_ids;
			       else
				       $event_id_arr=array_intersect($event_id_arr,$tmp_ids);
			       if (empty($event_id_arr))
				       $event_id=-1;
		       }
		       if ($formfield['field_purpose'] == 'locations') {
			       $tmp_ids=eme_get_cf_location_ids(eme_sanitize_request($value),$field_id,$is_multi);
			       if (empty($location_id_arr))
				       $location_id_arr=$tmp_ids;
			       else
				       $location_id_arr=array_intersect($location_id_arr,$tmp_ids);
			       if (empty($location_id_arr))
				       $location_id=-1;
		       }
               }
         }
      }
   }
   if ($event_id != -1 && !empty($event_id_arr))
	   $event_id=join(',',$event_id_arr);
   if ($location_id != -1 && !empty($location_id_arr))
	   $location_id=join(',',$location_id_arr);

   // if format is given as argument, sometimes people need url-encoded strings inside so wordpress doesn't get confused, so we decode them here again
   $format = urldecode($format);
   // for format: sometimes people want to give placeholders as options, but when using the shortcode inside
   // another (e.g. when putting[eme_events format="#_EVENTNAME"] inside the "display single event" setting,
   // the replacement of the placeholders happens too soon (placeholders get replaced first, before any other
   // shortcode is interpreted). So we add the option that people can use "#OTHER_", and we replace this with
   // "#_" here
   $format = preg_replace('/#OTHER/', "#", $format);

   $result = eme_get_events_list ( $limit,$scope,$order,$format,'','',0,$category,$showperiod,$long_events,$author,$contact_person,$paging,$event_id,$location_id,$user_registered_only,$show_ongoing,$link_showperiod,$notcategory,$show_recurrent_events_once,$template_id,$template_id_header,$template_id_footer,$no_events_message,$template_id_no_events);
   return $result;
}

function eme_display_single_event($event_id,$template_id=0,$ignore_url=0) {
   $page_body="";
   $event = eme_get_event($event_id);
   // also take into account the generic option for using the external url
   if (!$ignore_url) $ignore_url=!get_option('eme_use_external_url');
   if (empty($event)) {
	   return __('No such event','events-made-easy');
   } elseif (!eme_is_empty_string($event['event_url']) && !$ignore_url && eme_is_url($event['event_url'])) {
      // url not empty, so we redirect to it
      $page_body = '<script type="text/javascript">window.location.href="'.$event['event_url'].'";</script>';
      return $page_body;
   } elseif ($template_id) {
      $single_event_format= eme_get_template_format($template_id);
   } else {
      if (!eme_is_empty_string($event['event_single_event_format']))
         $single_event_format = $event['event_single_event_format'];
      elseif ($event['event_properties']['event_single_event_format_tpl']>0)
         $single_event_format = eme_get_template_format($event['event_properties']['event_single_event_format_tpl']);
      else
         $single_event_format = get_option('eme_single_event_format' );
   }
   $page_body = eme_replace_event_placeholders ($single_event_format, $event);
   return $page_body;
}

function eme_display_single_event_shortcode($atts) {
   extract ( shortcode_atts ( array ('id'=>'','template_id'=>0,'ignore_url'=>0), $atts ) );
   return eme_display_single_event($id,$template_id,$ignore_url);
}

function eme_get_events_page_shortcode($atts) {
   // we don't want just the url, but the clickable link by default for the shortcode
   extract ( shortcode_atts ( array ('justurl' => 0, 'text' => get_option ( 'eme_events_page_title' ) ), $atts ) );
   $result = eme_get_events_page ( $justurl,$text );
   return $result;
}

// API function
function eme_are_events_available($scope = "future",$order = "ASC", $location_id = "", $category = '', $author = '', $contact_person = '') {
   if ($scope == "")
      $scope = "future";
   $events = eme_get_events ( 1, $scope, $order, 0, $location_id, $category, $author, $contact_person);
   
   if (empty ( $events ))
      return false;
   else
      return true;
}

function eme_search_events($name,$scope="future",$name_only=0,$exclude_id=0,$only_rsvp=0) {
   global $wpdb, $eme_timezone;
   $table = $wpdb->prefix.EVENTS_TBNAME;
   $eme_date_obj=new ExpressiveDate("now",$eme_timezone);
   $start_of_week = get_option('start_of_week');
   $eme_date_obj->setWeekStartDay($start_of_week);
   $now = $eme_date_obj->getDateTime();
   // the LIKE needs "%", but for prepare to work, we need to escape % using %%
   // and then the prepare is a sprintf, so we need %s for the search string too
   // This results in 3 %-signs, but it is what it is :-)
   $where=array();
   if ($scope == 'past')
	   $where[] = "event_start < '$now'";
   elseif ($scope == 'future')
	   $where[] = "event_start >= '$now'";
   if ($exclude_id)
	   $where[]="event_id != ".intval($exclude_id);
   if ($only_rsvp)
	   $where[]="event_rsvp = 1";

   $condition = "";
   if (!empty($where))
	   $condition = "AND ".implode(" AND ",$where);
   
   if (!empty($name)) {
	   if ($name_only) {
		   $query = "SELECT * FROM $table WHERE event_name LIKE '%%%s%%' $condition ORDER BY event_start";
		   $sql=$wpdb->prepare($query,$name);
	   } else {
		   $query = "SELECT * FROM $table WHERE ((event_name LIKE '%%%s%%') OR
		   (event_notes LIKE '%%%s%%')) $condition ORDER BY event_start";
		   $sql=$wpdb->prepare($query,$name,$name);
	   }
   } else {
	   $sql = "SELECT * FROM $table WHERE (1=1) $condition ORDER BY event_start";
   }

   return $wpdb->get_results ( $sql, ARRAY_A );
}

// main function querying the database event table
function eme_get_events($o_limit=0, $scope = "future", $order = "ASC", $o_offset = 0, $location_id = "", $category = "", $author = "", $contact_person = "",  $show_ongoing=1, $notcategory = "", $show_recurrent_events_once=0, $extra_conditions = "", $count=0, $include_customformfields=0, $search_customfieldids='',$search_customfields='') {
   global $wpdb, $eme_timezone;

   $events_table = $wpdb->prefix.EVENTS_TBNAME;
   $bookings_table = $wpdb->prefix.BOOKINGS_TBNAME;
   $locations_table = $wpdb->prefix.LOCATIONS_TBNAME;

   if (strpos ( $o_limit, "=" )) {
      // allows the use of arguments
      $defaults = array ('o_limit' => 0, 'scope' => $scope, 'order' => $order, 'o_offset' => $o_offset, 'location_id' => $location_id, 'category' => $category, 'author' => $author, 'contact_person' => $contact_person, 'show_ongoing'=>$show_ongoing, 'notcategory' => $notcategory, 'show_recurrent_events_once'=>$show_recurrent_events_once, 'extra_conditions' => $extra_conditions );
      
      $r = wp_parse_args ( $o_limit, $defaults );
      extract ( $r );
   }
   if ($o_limit === "") {
      $o_limit = get_option('eme_event_list_number_items' );
   }
   if ($o_limit > 0) {
      $event_limit = intval($o_limit);
      $limit = "LIMIT $event_limit";
   } else {
      $limit="";
      $event_limit = 0;
   }
   if ($o_offset >0) {
      if ($o_limit == 0) {
          $limit = "LIMIT ".intval($o_offset);
	  $event_limit=intval($o_offset);
      }
      $offset = "OFFSET ".intval($o_offset);
   } else {
      $offset="";
   }

   // we can provide our own order statements
   $orderby="";
   // remove trailing ','
   $order = preg_replace('/,$/','',$order);
   if ($order == "ASC" || $order=="DESC") {
      $orderby= "ORDER BY event_start $order, event_name $order";
   } elseif (!eme_is_empty_string($order) && preg_match("/^[\w_\-\, ]+$/",$order)) {
      $order_arr=[];
      $order_by="";
      if (preg_match("/^[\w_\-\, ]+$/",$order)) {
	      $order_tmp_arr=explode(',',$order);
	      foreach ($order_tmp_arr as $order_ell) {
		      $asc_desc="ASC";
		      if (preg_match("/DESC$/",$order_ell))
			      $asc_desc="DESC";
		      $order_ell=trim(preg_replace("/ASC$|DESC$|\s/",'',$order_ell));
		      $order_arr[]="$order_ell $asc_desc";
	      }
      }
      if (!empty($order_arr)) {
	      $orderby="ORDER BY ".join(', ',$order_arr);
      } else {
	      $orderby= "ORDER BY event_start ASC, event_name ASC";
      }
   }

   $eme_date_obj=new ExpressiveDate("now",$eme_timezone);
   $start_of_week = get_option('start_of_week');
   $eme_date_obj->setWeekStartDay($start_of_week);
   $today = $eme_date_obj->getDate();
   $this_time = $eme_date_obj->getTime();
   $this_datetime = $eme_date_obj->getDateTime();

   $conditions = array ();
   // extra sql conditions we put in front, most of the time this is the most
   // effective place
   if ($extra_conditions != "") {
      $conditions[] = $extra_conditions;
   }

   // if we're not in the admin itf, we don't want draft events
   if (!eme_is_admin_request()) {
      if (is_user_logged_in()) {
         $conditions[] = "event_status IN (".EME_EVENT_STATUS_PUBLIC.",".EME_EVENT_STATUS_PRIVATE.")";
      } else {
         $conditions[] = "event_status=".EME_EVENT_STATUS_PUBLIC;
      }
      if (get_option('eme_rsvp_hide_full_events')) {
         // COALESCE is used in case the SUM returns NULL
         // this is a correlated subquery, so the FROM clause should specify events_table again, so it will search in the outer query for events_table.event_id
         $conditions[] = "(event_rsvp=0 OR (event_rsvp=1 AND event_seats > (SELECT COALESCE(SUM(booking_seats),0) AS booked_seats FROM $bookings_table WHERE $bookings_table.event_id = $events_table.event_id AND $bookings_table.status IN (".EME_RSVP_STATUS_APPROVED.",".EME_RSVP_STATUS_PENDING .") )))";
      }
   }

   if (preg_match ( "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $scope)) {
      if ($show_ongoing)
         $conditions[] = "((event_start LIKE '$scope%') OR (event_start <= '$scope' AND event_end >= '$scope'))";
      else
         $conditions[] = "(event_start LIKE '$scope%') ";
   } elseif (preg_match ( "/^--([0-9]{4}-[0-9]{2}-[0-9]{2})$/", $scope, $matches )) {
         $limit_start = $matches[1];
         if ($show_ongoing)
            $conditions[] = "(event_start < '$limit_start') ";
         else
            $conditions[] = "(event_end < '$limit_start') ";
   } elseif (preg_match ( "/^\+\+([0-9]{4}-[0-9]{2}-[0-9]{2})$/", $scope, $matches )) {
         $limit_start = $matches[1];
         $conditions[] = " (event_start > '$limit_start 23:59:59') ";
   } elseif (preg_match ( "/^0000-([0-9]{2})$/", $scope, $matches )) {
      $month=$matches[1];
      $eme_date_obj->setMonth($month);
      $limit_start=$eme_date_obj->startOfMonth()->getDateTime();
      $limit_end=$eme_date_obj->endOfMonth()->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif ($scope == "this_week") {
      // this comes from global wordpress preferences
      $start_of_week = get_option('start_of_week');
      $eme_date_obj->setWeekStartDay($start_of_week);
      $limit_start=$eme_date_obj->startOfWeek()->getDateTime();
      $limit_end=$eme_date_obj->endOfWeek()->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif ($scope == "next_week") {
      // this comes from global wordpress preferences
      $start_of_week = get_option('start_of_week');
      $eme_date_obj->setWeekStartDay($start_of_week);
      $eme_date_obj->addOneWeek();
      $limit_start=$eme_date_obj->startOfWeek()->getDateTime();
      $limit_end=$eme_date_obj->endOfWeek()->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif ($scope == "this_month") {
      $limit_start=$eme_date_obj->startOfMonth()->getDateTime();
      $limit_end=$eme_date_obj->endOfMonth()->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif ($scope == "next_month") {
      $eme_date_obj->startOfMonth()->addOneMonth();
      $limit_start=$eme_date_obj->startOfMonth()->getDateTime();
      $limit_end=$eme_date_obj->endOfMonth()->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif ($scope == "this_year") {
      $year=$eme_date_obj->getYear();
      $limit_start = "$year-01-01 00:00:00";
      $limit_end = "$year-12-31 23:59:59";
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif ($scope == "next_year") {
      $year=$eme_date_obj->getYear()+1;
      $limit_start = "$year-01-01 00:00:00";
      $limit_end = "$year-12-31 23:59:59";
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif (preg_match ( "/^([0-9]{4}-[0-9]{2}-[0-9]{2})--([0-9]{4}-[0-9]{2}-[0-9]{2})$/", $scope, $matches )) {
      $limit_start=$matches[1]. " 00:00:00";
      $limit_end=$matches[2]." 23:59:59";
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif (preg_match ( "/^([0-9]{4}-[0-9]{2}-[0-9]{2})--today$/", $scope, $matches )) {
      $limit_start=$matches[1]. " 00:00:00";
      $limit_end=$eme_date_obj->endOfDay()->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif (preg_match ( "/^([0-9]{4}-[0-9]{2}-[0-9]{2})--yesterday$/", $scope, $matches )) {
      $limit_start=$matches[1]. " 00:00:00";
      $limit_end=$eme_date_obj->minusDays(1)->endOfDay()->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif (preg_match ( "/^today--([0-9]{4}-[0-9]{2}-[0-9]{2})$/", $scope, $matches )) {
      $limit_start=$eme_date_obj->startOfDay()->getDateTime();
      $limit_end=$matches[1]." 23:59:59";
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif (preg_match ( "/^\+(\d+)d$/", $scope, $matches )) {
      $days=$matches[1];
      $limit_start=$eme_date_obj->startOfDay()->getDateTime();
      $limit_end=$eme_date_obj->addDays($days)->endOfDay()->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif (preg_match ( "/^\-(\d+)d$/", $scope, $matches )) {
      $days=$matches[1];
      $limit_start=$eme_date_obj->minusDays($days)->startOfDay()->getDateTime();
      $limit_end=$eme_date_obj->endOfDay()->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif (preg_match ( "/^(\-?\+?\d+)d--(\-?\+?\d+)d$/", $scope, $matches )) {
      $day1=$matches[1];
      $day2=$matches[2];
      $limit_start=$eme_date_obj->copy()->modifyDays($day1)->startOfDay()->getDateTime();
      $limit_end=$eme_date_obj->copy()->modifyDays($day2)->endOfDay()->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif (preg_match ( "/^relative\-(\d+)d--([0-9]{4}-[0-9]{2}-[0-9]{2})$/", $scope, $matches )) {
      $days=$matches[1];
      $limit_end=$matches[2]." 23:59:59";
      $eme_date_obj->setTimestampFromString($limit_end." ".$eme_timezone);
      $limit_start=$eme_date_obj->minusDays($days)->startOfDay()->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif (preg_match ( "/^([0-9]{4}-[0-9]{2}-[0-9]{2})--relative\+(\d+)d$/", $scope, $matches )) {
      $limit_start=$matches[1]." 00:00:00";
      $days=$matches[2];
      $eme_date_obj->setTimestampFromString($limit_start." ".$eme_timezone);
      $limit_end=$eme_date_obj->addDays($days)->endOfDay()->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif (preg_match ( "/^\+(\d+)m$/", $scope, $matches )) {
      $months_in_future=$matches[1]++;
      $limit_start= $eme_date_obj->startOfMonth()->getDateTime();
      $eme_date_obj->addMonths($months_in_future);
      $limit_end = $eme_date_obj->endOfMonth()->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif (preg_match ( "/^\-(\d+)m$/", $scope, $matches )) {
      $months_in_past=$matches[1]++;
      $limit_start = $eme_date_obj->startOfMonth()->minusMonths($months_in_past)->startOfMonth()->getDateTime();
      $limit_end = $eme_date_obj->endOfMonth()->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif (preg_match ( "/^(\-?\+?\d+)m--(\-?\+?\d+)m$/", $scope, $matches )) {
      $months1=$matches[1];
      $months2=$matches[2];
      $limit_start = $eme_date_obj->copy()->startOfMonth()->modifyMonths($months1)->startOfMonth()->getDateTime();
      $limit_end = $eme_date_obj->copy()->startOfMonth()->modifyMonths($months2)->endOfMonth()->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif ($scope == "today--this_week") {
      $limit_start = $today." 00:00:00";
      $limit_end   = $eme_date_obj->endOfWeek()->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif ($scope == "today--this_week_plus_one") {
      $limit_start = $today." 00:00:00";
      $limit_end   = $eme_date_obj->endOfWeek()->addOneDay()->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
    } elseif ($scope == "today--this_month") {
      $limit_start = $today." 00:00:00";
      $limit_end   = $eme_date_obj->endOfMonth()->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif ($scope == "today--this_year") {
      $year=$eme_date_obj->getYear();
      $limit_start = $today." 00:00:00";
      $limit_end = "$year-12-31 23:59:59";
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
    } elseif ($scope == "this_week--today") {
      $limit_start = $eme_date_obj->startOfWeek()->getDateTime();
      $limit_end   = $today." 23:59:59";
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif ($scope == "this_month--today") {
      $limit_start = $eme_date_obj->startOfMonth()->getDateTime();
      $limit_end   = $today." 23:59:59";
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif ($scope == "this_year--today") {
      $limit_start = "$year-01-01 00:00:00";
      $limit_end   = $today." 23:59:59";
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif ($scope == "this_year--yesterday") {
      $limit_start = "$year-01-01 00:00:00";
      $limit_end=$eme_date_obj->minusDays(1)->endOfDay()->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif ($scope == "today--future") {
      if ($show_ongoing)
         $conditions[] = "(event_start >= '$today 00:00:00' OR event_end>='$this_datetime')";
      else
         $conditions[] = "(event_start >= '$today 00:00:00')";
   } elseif ($scope == "tomorrow--future") {
      if ($show_ongoing)
         $conditions[] = "(event_start > '$today 23:59:59' OR event_end > '$today 23:59:59')";
      else
         $conditions[] = "(event_start > '$today 23:59:59')";
   } elseif ($scope == "past--yesterday") {
      if ($show_ongoing)
         $conditions[] = "event_end < '$today 00:00:00'";
      else
         $conditions[] = "event_start < '$today 00:00:00'";
   } elseif ($scope == "past") {
      if ($show_ongoing)
         $conditions[] = "event_end < '$this_datetime'";
      else
         $conditions[] = "event_start < '$this_datetime'";
   } elseif ($scope == "today") {
      if ($show_ongoing)
         $conditions[] = "((event_start LIKE '$today%') OR (event_start <= '$today 00:00:00' AND event_end >= '$today 00:00:00'))";
      else
         $conditions[] = "(event_start LIKE '$today%')";
   } elseif ($scope == "now--today") {
      if ($show_ongoing)
         $conditions[] = "((event_start LIKE '$today%' AND event_start>='$this_datetime') OR (event_start < '$today 00:00:00' AND event_end>='$this_datetime'))";
      else
         $conditions[] = "(event_start LIKE '$today%' AND event_start>='$this_datetime')";
   } elseif (preg_match ( "/now--(\d+)d$/", $scope, $matches )) {
      $days=$matches[1];
      $limit_start = $this_datetime;
      $limit_end=$eme_date_obj->addDays($days)->getDateTime();
      if ($show_ongoing)
         $conditions[] = "((event_start BETWEEN '$limit_start' AND '$limit_end') OR (event_end BETWEEN '$limit_start' AND '$limit_end') OR (event_start <= '$limit_start' AND event_end >= '$limit_end'))";
      else
         $conditions[] = "(event_start BETWEEN '$limit_start' AND '$limit_end')";
   } elseif ($scope == "tomorrow") {
      $tomorrow = $eme_date_obj->addOneDay()->getDate();
      if ($show_ongoing)
         $conditions[] = "(event_start LIKE '$tomorrow%' OR (event_start <= '$tomorrow 00:00:00' AND event_end >= '$tomorrow'))";
      else
         $conditions[] = "(event_start LIKE '$tomorrow%')";
   } elseif ($scope == "ongoing") {
      // only shows ongoing events, for this we try to use the date and time, but it might be incorrect since there's no user timezone info
      $conditions[] = "(event_start <='$this_datetime' AND event_end >= '$this_datetime')";
   } else {
      if ($scope != "all")
         $scope = "future";
      if ($scope == "future") {
         if ($show_ongoing)
            $conditions[] = "(event_end >= '$this_datetime')";
         else
            $conditions[] = "(event_start >= '$this_datetime')";
      }
   }
   
   // when used inside a location description, you can use "this_location" to indicate the current location being viewed
   if (is_string($location_id) && ($location_id == "#_SINGLE_LOCATIONPAGE_LOCATIONID" || $location_id == "this_location") && eme_is_single_location_page()) {
           $locationid_or_slug = eme_sanitize_request(get_query_var('location_id'));
           $location=eme_get_location($locationid_or_slug);
           if (!empty($location))
                   $location_id=$location['location_id'];
   }

   // since we do a LEFT JOIN with the location table, the column location_id
   // appears 2 times in the sql result, so we need to specify which one to use
   if (is_numeric($location_id)) {
      if ($location_id>0)
         $conditions[] = " $events_table.location_id = $location_id";
   } elseif ($location_id == "none") {
      $conditions[] = " $events_table.location_id = ''";
   } elseif ( preg_match('/,/', $location_id) ) {
      $location_ids=explode(',', $location_id);
      $location_conditions = array();
      foreach ($location_ids as $loc) {
         if (is_numeric($loc) && $loc>0) {
            $location_conditions[] = "$events_table.location_id = $loc";
         } elseif ($loc == "none") {
            $location_conditions[] = "$events_table.location_id = ''";
         }
      }
      $conditions[] = "(".implode(' OR ', $location_conditions).")";
   } elseif ( preg_match('/\+| /', $location_id) ) {
      // url decoding of '+' is ' '
      $location_ids=preg_split('/\+| /', $location_id,0,PREG_SPLIT_NO_EMPTY);
      $location_conditions = array();
      foreach ($location_ids as $loc) {
         if (is_numeric($loc) && $loc>0)
               $location_conditions[] = "$events_table.location_id = $loc";
         }
         $conditions[] = "(".implode(' AND ', $location_conditions).")";
   }

   // now filter the author ID
   if ($author != '' && !preg_match('/,/', $author)){
      if (is_numeric($author)) {
	      $conditions[] = "event_author = ".$author;
      } elseif ($author == "#_MYSELF") {
	      $current_userid=get_current_user_id();
	      $conditions[] = "event_author = ".$current_userid;
      } else {
	      $authinfo=get_user_by('login', $author);
	      if (!empty($authinfo->ID))
		      $conditions[] = "event_author = ".$authinfo->ID;
      }
   }elseif( preg_match('/,/', $author) ){
      $authors = explode(',', $author);
      $author_conditions = array();
      foreach($authors as $authname) {
	      if (is_numeric($authname)) {
		      $author_conditions[] = "event_author = ".$authname;
	      } elseif ($authname == "#_MYSELF") {
		      $current_userid=get_current_user_id();
		      $author_conditions[] = "event_author = ".$current_userid;
	      } else {
		      $authinfo=get_user_by('login', $authname);
		      if (!empty($authinfo->ID))
			      $author_conditions[] = "event_author = ".$authinfo->ID;
	      }
      }
      $conditions[] = "(".implode(' OR ', $author_conditions).")";
   }

   // now filter the contact ID
   if ($contact_person != '' && !preg_match('/,/', $contact_person)){
      if (is_numeric($contact_person)) {
	      $conditions[] = "(event_contactperson_id = $contact_person OR (event_contactperson_id=-1 AND event_author=$contact_person))";
      } elseif ($contact_person == "#_MYSELF") {
	      $current_userid=get_current_user_id();
	      $conditions[] = "(event_contactperson_id = $current_userid OR (event_contactperson_id=-1 AND event_author=$current_userid))";
      } else {
	      $authinfo=get_user_by('login', $contact_person);
	      if (!empty($authinfo->ID)) {
		      $userid=$authinfo->ID;
		      $conditions[] = "(event_contactperson_id = $userid OR (event_contactperson_id=-1 AND event_author=$userid))";
	      }
      }
   } elseif( preg_match('/,/', $contact_person) ){
      $contact_persons = explode(',', $contact_person);
      $contact_person_conditions = array();
      foreach($contact_persons as $authname) {
	      if (is_numeric($authname)) {
		      $contact_person_conditions[] = "(event_contactperson_id = $authname OR (event_contactperson_id=-1 AND event_author=$authname))";
	      } elseif ($authname == "#_MYSELF") {
		      $current_userid=get_current_user_id();
		      $contact_person_conditions[] = "(event_contactperson_id = $current_userid OR (event_contactperson_id=-1 AND event_author=$current_userid))";
	      } else {
		      $authinfo=get_user_by('login', $authname);
		      if (!empty($authinfo->ID)) {
			      $userid=$authinfo->ID;
			      $contact_person_conditions[] = "(event_contactperson_id = $userid OR (event_contactperson_id=-1 AND event_author=$userid))";
		      }
	      }
      }
      $conditions[] = "(".implode(' OR ', $contact_person_conditions).")";
   }

   if (get_option('eme_categories_enabled')) {
      if (is_numeric($category)) {
         if ($category>0)
            $conditions[] = "FIND_IN_SET($category,event_category_ids)";
      } elseif ($category == "none") {
         $conditions[] = "event_category_ids = ''";
      } elseif ( preg_match('/,/', $category) ) {
         $category = explode(',', $category);
         $category_conditions = array();
         foreach ($category as $cat) {
            if (is_numeric($cat) && $cat>0) {
               $category_conditions[] = "FIND_IN_SET($cat,event_category_ids)";
            } elseif ($cat == "none") {
               $category_conditions[] = "event_category_ids = ''";
            }
         }
         $conditions[] = "(".implode(' OR ', $category_conditions).")";
      } elseif ( preg_match('/\+| /', $category) ) {
         $category = preg_split('/\+| /', $category,0,PREG_SPLIT_NO_EMPTY);
         $category_conditions = array();
         foreach ($category as $cat) {
            if (is_numeric($cat) && $cat>0)
               $category_conditions[] = "FIND_IN_SET($cat,event_category_ids)";
         }
         $conditions[] = "(".implode(' AND ', $category_conditions).")";
      }

      if (is_numeric($notcategory)) {
         if ($notcategory>0)
            $conditions[] = "(NOT FIND_IN_SET($notcategory,event_category_ids) OR event_category_ids IS NULL)";
      } elseif ($notcategory == "none") {
         $conditions[] = "event_category_ids != ''";
      } elseif ( preg_match('/,/', $notcategory) ) {
         $notcategory = explode(',', $notcategory);
         $category_conditions = array();
         foreach ($notcategory as $cat) {
            if (is_numeric($cat) && $cat>0) {
               $category_conditions[] = "(NOT FIND_IN_SET($cat,event_category_ids) OR event_category_ids IS NULL)";
            } elseif ($cat == "none") {
               $category_conditions[] = "event_category_ids != ''";
            }
         }
         $conditions[] = "(".implode(' OR ', $category_conditions).")";
      } elseif ( preg_match('/\+| /', $notcategory) ) {
         // url decoding of '+' is ' '
         $notcategory = preg_split('/\+| /', $notcategory,0,PREG_SPLIT_NO_EMPTY);
         $category_conditions = array();
         foreach ($notcategory as $cat) {
            if (is_numeric($cat) && $cat>0)
               $category_conditions[] = "(NOT FIND_IN_SET($cat,event_category_ids) OR event_category_ids IS NULL)";
         }
         $conditions[] = "(".implode(' AND ', $category_conditions).")";
      }
   }

   // extra conditions for authors: if we're in the admin itf, return only the events for which you have the right to change anything
   $current_userid=get_current_user_id();
   if ($current_userid && eme_is_admin_request() && !current_user_can( get_option('eme_cap_edit_events')) && !current_user_can( get_option('eme_cap_list_events')) && current_user_can( get_option('eme_cap_author_event'))) {
      $conditions[] = "event_author = $current_userid";
   }

   // If not counting the rows, we want to return specific columns. Reason: we do left joins on event_id sometimes, and those can return event_id columns=NULL for the rightside table
   // and that can mess up the event_id in the end result of course ...
   if ($count) {
	   $columns='COUNT(*)';
   } else {
	   $columns="$events_table.*,$locations_table.location_name,$locations_table.location_address1,$locations_table.location_address2,$locations_table.location_zip,$locations_table.location_city,$locations_table.location_state,$locations_table.location_country,$locations_table.location_latitude,$locations_table.location_longitude,$locations_table.location_attributes,$locations_table.location_properties";
   }

   $where = implode ( " AND ", $conditions );
   $sql_join='';

   if ($show_recurrent_events_once) {
      if ($where != "") {
         $count_where = " WHERE " . $where. " AND $events_table.recurrence_id>0";
      } else {
         $count_where = " WHERE $events_table.recurrence_id>0";
      }
      // for show_recurrent_events_once: first we count the number of recurrent events that match, and then we increase the limit by that number
      // This will allow the sql to show all events relevant
      // Later on we we loop over the events and show only the amount wanted
      if (!empty($limit)) {
              $count_sql = "SELECT COUNT(*) FROM $events_table $count_where";
              $t_count = $wpdb->get_var($count_sql);
              // now increase the $limit with the number of events found
              // we don't change $event_limit because we need that later on
              $t_limit = $event_limit + intval($t_count);
              $limit = "LIMIT $t_limit";
      }
   } elseif ($include_customformfields) {
	   $formfields_searchable= eme_get_formfields($search_customfieldids);
	   $answers_table = $wpdb->prefix.ANSWERS_TBNAME;
	   foreach ($formfields_searchable as $formfield) {
		   $field_id=$formfield['field_id'];
	   }
	   if ($search_customfields!="") {
		   $search_customfields=esc_sql($search_customfields);
		   $sql_join="
		   JOIN (SELECT related_id FROM $answers_table
			 WHERE answer LIKE '%$search_customfields%' AND field_id IN ($search_customfieldids) AND type='event'
			 GROUP BY related_id
			) ans
		   ON $events_table.event_id=ans.related_id";
	   } else {
		   $sql_join="
		   LEFT JOIN (SELECT related_id FROM $answers_table WHERE type='event'
			 GROUP BY related_id
			) ans
		   ON $events_table.event_id=ans.related_id";
	   }
   }

   if ($where != "")
	   $where = " WHERE " . $where;
   $sql = "SELECT $columns FROM $events_table LEFT JOIN $locations_table ON $events_table.location_id=$locations_table.location_id
	   $sql_join $where $orderby $limit $offset";

   $sql_md5=md5($sql);
   $res = wp_cache_get("eme_events $sql_md5");
   if ($res === false) {
	   if ($count) {
		   $count = $wpdb->get_var ($sql);
		   wp_cache_add("eme_events $sql_md5", $count, '', 10);
		   return $count;
	   } else {
		   $events = $wpdb->get_results ( $sql, ARRAY_A );
		   $inflated_events = array();
		   $seen_recids=array();
		   if (! empty($events)) {
			   $event_count=0;
			   // if in the frontend we might want to hide rsvp ended events
			   if (!eme_is_admin_request())
				   $eme_rsvp_hide_rsvp_ended_events=get_option('eme_rsvp_hide_rsvp_ended_events');
			   else
				   $eme_rsvp_hide_rsvp_ended_events=0;
			   foreach ( $events as $this_event ) {
				   if ($show_recurrent_events_once && $this_event['recurrence_id']>0 && in_array($this_event['recurrence_id'],$seen_recids))
					   continue;
				   $this_event = eme_get_extra_event_data($this_event);
				   $this_event = eme_get_extra_location_data($this_event);
				   // this might throw of the limit parameter: you selected to show X events but in case the option to hide rsvp ended events is shown, the number of events shown will be less
				   if ($eme_rsvp_hide_rsvp_ended_events && eme_is_event_rsvp_ended($this_event))
					   continue;
				   $inflated_events[]=$this_event;
				   if ($this_event['recurrence_id']>0)
					   $seen_recids[]=$this_event['recurrence_id'];
				   $event_count++;
				   // if there's a limit set, let's respect it
				   if ($event_limit && $event_count>=$event_limit) break;
			   }
			   if (!eme_is_admin_request() && has_filter('eme_event_list_filter'))
				   $inflated_events=apply_filters('eme_event_list_filter',$inflated_events);
		   }
		   wp_cache_add("eme_events $sql_md5", $inflated_events, '', 10);
		   return $inflated_events;
	   }
   } else {
	   return $res;
   }
}

function eme_get_eventids_by_author($author_id,$scope,$event_id) {
   global $wpdb, $eme_timezone;
   $events_table = $wpdb->prefix . EVENTS_TBNAME;
   $eme_date_obj_now=new ExpressiveDate("now",$eme_timezone);
   $this_datetime=$eme_date_obj_now->getDateTime();
   $where_arr=array();

   // if an event id is provided, it takes precedence
   if (!empty($event_id)) {
      $where_arr[] = "event_id=$event_id";
   } elseif ($scope=='past') {
      $where_arr[] = "event_end < '$this_datetime'";
   } elseif ($scope=='future') {
      $where_arr[] = "event_end >= '$this_datetime'";
   }
   if (!empty($where_arr))
      $where = " AND ".implode(" AND ",$where_arr);
   else
      $where = "";

   $sql = $wpdb->prepare("SELECT event_id from $events_table WHERE event_author = %d $where",$author_id);
   return $wpdb->get_col($sql);
}

function eme_get_event_name($event_id) {
   global $wpdb;

   if (!$event_id) {
      return '';
   }

   $events_table = $wpdb->prefix . EVENTS_TBNAME;
   if (is_numeric($event_id)) {
	   $sql = $wpdb->prepare("SELECT event_name from $events_table WHERE event_id = %d",$event_id);
   } else {
	   $sql = $wpdb->prepare("SELECT event_name from $events_table WHERE event_slug = %s LIMIT 1",$event_id);
   }
   return $wpdb->get_var($sql);
}

function eme_get_event($event_id) {
   global $wpdb;

   if (is_string($event_id) && $event_id == "#_SINGLE_EVENTPAGE_EVENTID" && eme_is_single_event_page()) {
	   $event_id = eme_sanitize_request(get_query_var('event_id'));
   }

   if (empty($event_id)) {
	   return false;
   }

   $events_table = $wpdb->prefix . EVENTS_TBNAME;
   $conditions = array ();

   if (is_numeric($event_id)) {
	   $sql = $wpdb->prepare("SELECT * from $events_table WHERE event_id = %d",$event_id);
   } else {
	   $sql = $wpdb->prepare("SELECT * from $events_table WHERE event_slug = %s LIMIT 1",$event_id);
   }
   $event = wp_cache_get("eme_event $event_id");
   if ($event === false) {
	   $event = $wpdb->get_row ( $sql, ARRAY_A );
	   if ($event) {
		   $event = eme_get_extra_event_data($event);
		   wp_cache_set("eme_event $event_id", $event, '', 60);
	   }
   }
   return $event;
}


function eme_get_event_arr($event_ids) {
   global $wpdb;

   // remove possible empty elements
   if (!empty($event_ids)) {
	   $event_ids = eme_array_remove_empty_elements($event_ids);
   }
   // empty array or containing something else but integers? Then go away
   if (empty($event_ids) || !eme_array_integers($event_ids)) {
      return;
   }

   // optimize if only 1 event id
   if (count($event_ids)==1) {
	   $events=array();
	   $events[]=eme_get_event($event_ids[0]);
	   return $events;
   }

   $events_table = $wpdb->prefix . EVENTS_TBNAME;
   $conditions = array ();
   $event_ids_joined=join(',',$event_ids);
   $conditions[] = "event_id IN ($event_ids_joined)";

   // in the frontend and not logged in, only show public events
   // since this function is only called from the frontend for the multibooking form, we can drop the is_admin, but hey ...
   if (!eme_is_admin_request()) {
      if (is_user_logged_in()) {
         $conditions[] = "event_status IN (".EME_EVENT_STATUS_PUBLIC.",".EME_EVENT_STATUS_PRIVATE.")";
      } else {
         $conditions[] = "event_status=".EME_EVENT_STATUS_PUBLIC;
      }
   }
   $where = implode ( " AND ", $conditions );
   if ($where != "")
      $where = " WHERE " . $where;

   // the 'order by' is of course only useful if the event_id argument for the function was an array of event id's
   $sql = "SELECT * FROM $events_table
      $where ORDER BY FIELD(event_id,$event_ids_joined)";

   $events = $wpdb->get_results ( $sql, ARRAY_A );
   foreach ( $events as $key=>$event ) {
      $events[$key] = eme_get_extra_event_data($event);
   }
   return $events;
}

function eme_get_extra_event_data($event) {
   if ($event['event_end'] == "") {
      $event['event_end'] = $event['event_start'];
   }
      
   $event['event_attributes'] = maybe_unserialize($event['event_attributes']);
   $event['event_attributes'] = (!is_array($event['event_attributes'])) ?  array() : $event['event_attributes'] ;

   $event['event_properties'] = maybe_unserialize($event['event_properties']);
   $event['event_properties'] = (!is_array($event['event_properties'])) ?  array() : $event['event_properties'] ;
   $event['event_properties'] = eme_init_event_props($event['event_properties']);

   if (has_filter('eme_event_filter')) $event=apply_filters('eme_event_filter',$event);
   return $event;
}

function eme_import_csv_events() {
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
	if (!in_array('event_name',$headers)) {
		$result = __("Not all required fields present.",'events-made-easy');
	} else {
		$empty_props=array();
                $empty_props=eme_init_event_props($empty_props);
		// now loop over the rest
		while (($row = fgetcsv($handle,0,$delimiter,$enclosure)) !== FALSE) {
			$line = array_combine($headers, $row);
			// remove columns with empty values
			$line = eme_array_remove_empty_elements($line);

			// first we import the mentioned location, then we add that location id to the event
			$location_id=0;
			$event_id=0;
			if (!isset($line['location_id']) && isset($line['location_name']) && isset($line['location_address1']) && isset($line['location_city'])) {
				// if the location already exists: update it 
				if (isset($line['external_ref']))
					$location_id=eme_check_location_external_ref($line['external_ref']);
				if (!$location_id && isset($line['location_latitude']) && isset($line['location_longitude']))
					$location_id=eme_check_location_coord($line['location_latitude'],$line['location_longitude']);
				if (!$location_id)
					$location_id=eme_check_location_name_address($line);

				if ($location_id) {
					// location_id is returned if update is ok, and we use the location id later on
					$location_id=eme_update_location($line,$location_id);
					if (!$location_id) {
						$error_msg.='<br />'.eme_esc_html(sprintf(__('Location not imported: %s','events-made-easy'),implode(',',$row)));
					}
				} else {
					$location_id=eme_insert_location($line);
					if (!$location_id) {
						$errors++;
						$error_msg.='<br />'.eme_esc_html(sprintf(__('Location not imported: %s','events-made-easy'),implode(',',$row)));
					}
				}
				if ($location_id) {
					// now handle all the extra info, in the CSV they need to be named like 'answer_XX' (with 'XX' being either the fieldid or the fieldname, e.g. answer_myfieldname)
					foreach ($line as $key=>$value) {
						if (preg_match('/^answer_(.*)$/', $key, $matches)) {
							$field_name = $matches[1];
							$formfield = eme_get_formfield($field_name);
							if (!empty($formfield) && $formfield['field_purpose']=='locations') {
								$field_id=$formfield['field_id'];
								$sql = $wpdb->prepare("DELETE FROM $answers_table WHERE related_id = %d and field_id=%d AND type='location'",$location_id,$field_id);
                                                                $wpdb->query($sql);

								$sql = $wpdb->prepare("INSERT INTO $answers_table (related_id,field_id,answer,type) VALUES (%d,%d,%s,%s)",$location_id,$field_id,$value,'location');
								$wpdb->query($sql);
							}
						}
					}

				}
			}

			if (!empty($line['event_start_date']) && !eme_is_date($line['event_start_date'])) {
                                $errors++;
                                $error_msg.='<br />'.eme_esc_html(sprintf(__('Not imported (field %s not valid): %s','events-made-easy'),'event_start_date',implode(',',$row)));
			} elseif (!empty($line['event_end_date']) && !eme_is_date($line['event_end_date'])) {
                                $errors++;
                                $error_msg.='<br />'.eme_esc_html(sprintf(__('Not imported (field %s not valid): %s','events-made-easy'),'event_end_date',implode(',',$row)));
			} elseif (isset($line['event_name'])) {
				if (!isset($line['location_id'])) {
					$line['location_id']=$location_id;
				}

				// also import attributes
                                foreach ($line as $key=>$value) {
                                        if (preg_match('/^att_(.*)$/', $key, $matches)) {
                                                $att=$matches[1];
                                                if (!isset($line['event_attributes'])) {
                                                        $line['event_attributes']=array();
                                                }
						$line['event_attributes'][$att]=$value;
                                        }
                                }

				// also import properties
                                foreach ($line as $key=>$value) {
                                        if (preg_match('/^prop_(.*)$/', $key, $matches)) {
                                                $prop=$matches[1];
                                                if (!isset($line['event_properties'])) {
                                                        $line['event_properties']=array();
                                                }
                                                if (array_key_exists($prop,$empty_props))
                                                        $line['event_properties'][$prop]=$value;
                                        }
                                }

				if (isset($line['external_ref']))
					$event_id=eme_check_event_external_ref($line['external_ref']);

				$line=eme_sanitize_event($line);
				if ($event_id) {
					// event_id is returned if update is ok, and we use the location id later on
					$event_id=eme_db_update_event($line,$event_id);
					if ($event_id) {
						$updated++;
					} else {
						$errors++;
						$error_msg.='<br />'.eme_esc_html(sprintf(__('Not imported (problem updating the event in the db): %s','events-made-easy'),implode(',',$row)));
					}
				} else {
					$event_id=eme_db_insert_event($line);
					if ($event_id) {
						$inserted++;
					} else {
						$errors++;
						$error_msg.='<br />'.eme_esc_html(sprintf(__('Not imported (problem inserting the event in the db): %s','events-made-easy'),implode(',',$row)));
					}
				}
				if ($event_id) {
					// now handle all the extra info, in the CSV they need to be named like 'answer_XX' (with 'XX' being either the fieldid or the fieldname, e.g. answer_myfieldname)
					foreach ($line as $key=>$value) {
						if (preg_match('/^answer_(.*)$/', $key, $matches)) {
							$field_name = $matches[1];
							$formfield = eme_get_formfield($field_name);
							if (!empty($formfield) && $formfield['field_purpose']=='events') {
								$field_id=$formfield['field_id'];
								$sql = $wpdb->prepare("DELETE FROM $answers_table WHERE related_id = %d and field_id=%d AND type='event'",$event_id,$field_id);
                                                                $wpdb->query($sql);

								$sql = $wpdb->prepare("INSERT INTO $answers_table (related_id,field_id,answer,type) VALUES (%d,%d,%s,%s)",$event_id,$field_id,$value,'event');
								$wpdb->query($sql);
							}
						}
					}

				}
			} else {
				$errors++;
				$error_msg.='<br />'.eme_esc_html(sprintf(__('Not imported (not all required fields are present): %s','events-made-easy'),implode(',',$row)));
			}
		}
		$result = sprintf(__('Import finished: %d inserts, %d updates, %d errors','events-made-easy'),$inserted,$updated,$errors);
		if ($errors) $result.='<br />'.$error_msg;
	}
	fclose($handle);
	return $result;
}

function eme_events_table($message="") {
   global $eme_timezone, $plugin_page, $eme_plugin_url;

   if (empty($message))
           $hidden_style="display:none;";
   else
           $hidden_style="";

   $scope_names = array ();
   $scope_names['past'] = __ ( 'Past events', 'events-made-easy');
   $scope_names['all'] = __ ( 'All events', 'events-made-easy');
   $scope_names['future'] = __ ( 'Future events', 'events-made-easy');

   $event_status_array = eme_status_array ();
   $categories = eme_get_categories();
   $nonce_field = wp_nonce_field('eme_admin','eme_admin_nonce',false,false);

?>

<div class="wrap nosubsub">
<div id="poststuff">
   <div id="icon-edit" class="icon32">
   <br />
   </div>

   <div id="events-message" class="updated notice notice-success is-dismissible" style="<?php echo $hidden_style; ?>">
               <p><?php echo $message; ?></p>
   </div>

<?php if (current_user_can( get_option('eme_cap_add_event'))) : ?>
   <h1><?php _e('Add a new event', 'events-made-easy'); ?></h1>
   <div class="wrap">
      <form id="locations-filter" method="post" action="<?php echo admin_url("admin.php?page=eme-manager"); ?>">
         <input type="hidden" name="eme_admin_action" value="add_new_event" />
         <input type="submit" class="button-primary" name="submit" value="<?php _e('Add event', 'events-made-easy');?>" />
      </form>
   </div>
<?php endif; ?>

   <h1><?php _e('Manage events', 'events-made-easy') ?>
       <a href="<?php echo admin_url("admin.php?page=$plugin_page&recurrences=1"); ?>"><?php _e('Manage recurrences','events-made-easy');?></a><br />
   </h1>

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
      <form id='event-import' method='post' enctype='multipart/form-data' action='#'>
      <?php echo $nonce_field; ?>
      <input type="file" name="eme_csv" />
      <?php _e('Delimiter:','events-made-easy'); ?>
      <input type="text" size=1 maxlength=1 name="delimiter" value=',' required='required' />
      <?php _e('Enclosure:','events-made-easy'); ?>
      <input required="required" type="text" size=1 maxlength=1 name="enclosure" value='"' required='required' />
      <input type="hidden" name="eme_admin_action" value="import_events" />
      <input type="submit" value="<?php _e ( 'Import','events-made-easy'); ?>" name="doaction" id="doaction" class="button-primary action" />
      <?php _e('If you want, use this to import events into the database', 'events-made-easy'); ?>
      </form>
      </div>
      <?php } ?>
      <br />
   <?php } ?>

   <div>
   <form method='post' action="#">
   <select id="scope" name="scope">
   <?php
   foreach ( $scope_names as $key => $value ) {
      $selected = "";
      if ($key == 'future')
         $selected = "selected='selected'";
      echo "<option value='$key' $selected>$value</option>  ";
   }
   ?>
   </select>
   <select id="category" name="category">
   <option value='0'><?php _e('All categories','events-made-easy'); ?></option>
   <option value='none'><?php _e('Events without category','events-made-easy'); ?></option>
   <?php
   foreach ( $categories as $category) {
      echo "<option value='".$category['category_id']."'>".$category['category_name']."</option>";
   }
   ?>
   </select>
   <input type="text" class="clearable" name="search_name" id="search_name" placeholder="<?php _e('Event name','events-made-easy'); ?>" size=10 />
   <input id="search_start_date" type="hidden" name="search_start_date" value="" />
   <input id="eme_localized_search_start_date" type="text" name="eme_localized_search_start_date" value="" style="background: #FCFFAA;" readonly="readonly" placeholder="<?php _e('Filter on start date','events-made-easy'); ?>" size=15 data-date='' data-alt-field='search_start_date' class='eme_formfield_fdate' />
   <input id="search_end_date" type="hidden" name="search_end_date" value="" />
   <input id="eme_localized_search_end_date" type="text" name="eme_localized_search_end_date" value="" style="background: #FCFFAA;" readonly="readonly" placeholder="<?php _e('Filter on end date','events-made-easy'); ?>" size=15 data-date='' data-alt-field='search_end_date' class='eme_formfield_fdate' />
   <a onclick='return false;' href='#'  class="showhidebutton" alt="show/hide" data-showhide="extra_searchfields"><?php _e('Show/hide extra filters','events-made-easy'); ?></a>
   <div id="extra_searchfields" style="display:none;">
   <select id="status" name="status">
      <option value="0"><?php _e('Event Status','events-made-easy'); ?></option>
      <?php
      if (isset($_GET['status']))
         $get_status=intval($_GET['status']);
      else
	 $get_status=0;
      foreach($event_status_array as $event_status_key => $event_status_value) {
	 echo "<option value='$event_status_key' ".selected($get_status,$event_status_key)."> $event_status_value</option>";
      }
      ?>
   </select>
   <?php
   $formfields_searchable=eme_get_searchable_formfields('events');
   if (!empty($formfields_searchable)) {
   	echo '<input type="text" class="clearable" name="search_customfields" id="search_customfields" placeholder="'.__('Custom field value to search','events-made-easy').'" size=20 />';
	echo eme_ui_multiselect_key_value('','search_customfieldids',$formfields_searchable,'field_id','field_name',5,'',0,'eme_select2_customfieldids_class');
   }
   ?>
   </div>
   <button id="EventsLoadRecordsButton" class="button-secondary action"><?php _e('Filter events','events-made-easy'); ?></button>
   <?php
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
   </div>
   <?php
     if (current_user_can( get_option('eme_cap_edit_events'))): 
   ?>
   <div>
   <form action="#" method="post">
   <select id="eme_admin_action" name="eme_admin_action">
   <option value="" selected="selected"><?php _e ( 'Bulk Actions' , 'events-made-easy'); ?></option>
   <?php if (isset($_GET['trash']) && $_GET['trash']==1) { ?>
   <option value="untrashEvents"><?php _e ( 'Restore selected events (to draft status)','events-made-easy'); ?></option>
   <option value="deleteEvents"><?php _e ( 'Permanently delete selected events','events-made-easy'); ?></option>
   <?php } else { ?>
   <option value="trashEvents"><?php _e ( 'Delete selected events (move to trash)','events-made-easy'); ?></option>
   <option value="publicEvents"><?php _e ( 'Publish selected events','events-made-easy'); ?></option>
   <option value="privateEvents"><?php _e ( 'Make selected events private','events-made-easy'); ?></option>
   <option value="draftEvents"><?php _e ( 'Make selected events draft','events-made-easy'); ?></option>
   <option value="addCategory"><?php _e ( 'Add selected events to category','events-made-easy'); ?></option>
   <?php } ?>
   </select>
   <span id="span_sendtrashmails" class="eme-hidden">
   <?php _e('Send mails for cancelled bookings too?','events-made-easy'); echo eme_ui_select_binary(0,"send_trashmails"); ?>
   </span>
   <span id="span_addtocategory" class="eme-hidden">
   <?php echo eme_ui_select_key_value('',"addtocategory",$categories,'category_id','category_name',__('Please select a category','events-made-easy'),1);?>
   </span>
   <button id="EventsActionsButton" class="button-secondary action"><?php _e ( 'Apply' , 'events-made-easy'); ?></button>
   <span class="rightclickhint">
      <?php _e('Hint: rightclick on the column headers to show/hide columns','events-made-easy'); ?>
   </span>
   </form>
   </div>
   <?php
     endif;
   $formfields=eme_get_formfields('','events');
   $extrafields_arr=array();
   $extrafieldnames_arr=array();
   $extrafieldsearchable_arr=array();
   foreach ($formfields as $formfield) {
           $extrafields_arr[]=$formfield['field_id'];
           $extrafieldnames_arr[]=eme_trans_esc_html($formfield['field_name']);
	   $extrafieldsearchable_arr[]=$formfield['searchable'];
   }
   // these 2 values are used as data-fields to the container-div, and are used by the js to create extra columns
   $extrafields=join(',',$extrafields_arr);
   $extrafieldnames=join(',',$extrafieldnames_arr);
   $extrafieldsearchable=join(',',$extrafieldsearchable_arr);
   ?>
   <div id="EventsTableContainer" data-extrafields='<?php echo $extrafields;?>' data-extrafieldnames='<?php echo $extrafieldnames;?>' data-extrafieldsearchable='<?php echo $extrafieldsearchable;?>'></div>
</div>
</div>
<?php
}

function eme_recurrences_table($message="") {
   global $eme_timezone, $plugin_page;

   if (empty($message))
           $hidden_style="display:none;";
   else
           $hidden_style="";

   $scope_names = array ();
   $scope_names['past'] = __ ( 'Past recurrences', 'events-made-easy');
   $scope_names['all'] = __ ( 'All recurrences', 'events-made-easy');
   $scope_names['ongoing'] = __ ( 'Ongoing recurrences', 'events-made-easy');

   $nonce_field = wp_nonce_field('eme_admin','eme_admin_nonce',false,false);
?>

<div class="wrap nosubsub">
<div id="poststuff">
   <div id="icon-edit" class="icon32">
   <br />
   </div>

   <div id="events-message" class="updated notice notice-success is-dismissible" style="<?php echo $hidden_style; ?>">
               <p><?php echo $message; ?></p>
   </div>

<?php if (current_user_can( get_option('eme_cap_add_event'))) : ?>
   <h1><?php _e('Add a new recurrence', 'events-made-easy'); ?></h1>
   <div class="wrap">
      <form id="locations-filter" method="post" action="<?php echo admin_url("admin.php?page=eme-manager"); ?>">
         <input type="hidden" name="eme_admin_action" value="add_new_recurrence" />
         <input type="submit" class="button-primary" name="submit" value="<?php _e('Add recurrence', 'events-made-easy');?>" />
      </form>
   </div>
<?php endif; ?>

   <h1><?php _e('Manage recurrences', 'events-made-easy'); ?>
       <a href="<?php echo admin_url("admin.php?page=$plugin_page"); ?>"><?php _e('Manage events','events-made-easy');?></a><br />
   </h1>

   <form method='post' action="#">
   <select id="scope" name="scope">
   <?php
   foreach ( $scope_names as $key => $value ) {
      $selected = "";
      if ($key == 'ongoing')
         $selected = "selected='selected'";
      echo "<option value='$key' $selected>$value</option>  ";
   }
   ?>
   </select>
   <input type="text" class="clearable" name="search_name" id="search_name" placeholder="<?php _e('Event name','events-made-easy'); ?>" size=10 />
   <input id="search_start_date" type="hidden" name="search_start_date" value="" />
   <input id="eme_localized_search_start_date" type="text" name="eme_localized_search_start_date" value="" style="background: #FCFFAA;" readonly="readonly" placeholder="<?php _e('Filter on start date','events-made-easy'); ?>" size=15 data-date='' data-alt-field='search_start_date' class='eme_formfield_fdate' />
   <input id="search_end_date" type="hidden" name="search_end_date" value="" />
   <input id="eme_localized_search_end_date" type="text" name="eme_localized_search_end_date" value="" style="background: #FCFFAA;" readonly="readonly" placeholder="<?php _e('Filter on end date','events-made-easy'); ?>" size=15 data-date='' data-alt-field='search_end_date' class='eme_formfield_fdate' />
   <button id="RecurrencesLoadRecordsButton" class="button-secondary action"><?php _e('Filter recurrences','events-made-easy'); ?></button>
   </form>
   <form action="#" method="post">
   <select id="eme_admin_action" name="eme_admin_action">
   <option value="" selected="selected"><?php _e ( 'Bulk Actions' , 'events-made-easy'); ?></option>
   <option value="deleteRecurrences"><?php _e ( 'Delete selected recurrences (and move events to trash)','events-made-easy'); ?></option>
   <option value="publicRecurrences"><?php _e ( 'Publish selected recurrences','events-made-easy'); ?></option>
   <option value="privateRecurrences"><?php _e ( 'Make selected recurrences private','events-made-easy'); ?></option>
   <option value="draftRecurrences"><?php _e ( 'Make selected recurrences draft','events-made-easy'); ?></option>
   <option value="extendRecurrences"><?php _e ( 'Set new start/end date for selected recurrences','events-made-easy'); ?></option>
   </select>
   <span id="span_extendrecurrences" class="eme-hidden">
   <input id="rec_new_start_date" type="hidden" name="rec_new_start_date" value="" />
   <input id="eme_localized_rec_new_start_date" type="text" name="eme_localized_rec_new_start_date" value="" style="background: #FCFFAA;" readonly="readonly" placeholder="<?php _e('Select new start date','events-made-easy'); ?>" size=15 data-date='' data-alt-field='rec_new_start_date' class='eme_formfield_fdate' />
   <input id="rec_new_end_date" type="hidden" name="rec_new_end_date" value="" />
   <input id="eme_localized_rec_new_end_date" type="text" name="eme_localized_rec_new_end_date" value="" style="background: #FCFFAA;" readonly="readonly" placeholder="<?php _e('Select new end date','events-made-easy'); ?>" size=15 data-date='' data-alt-field='rec_new_end_date' class='eme_formfield_fdate' />
   </span>
   <button id="RecurrencesActionsButton" class="button-secondary action"><?php _e ( 'Apply' , 'events-made-easy'); ?></button>
   <span class="rightclickhint">
      <?php _e('Hint: rightclick on the column headers to show/hide columns','events-made-easy'); ?>
   </span>
   </form>
   <div id="RecurrencesTableContainer"></div>
</div>
</div>
<?php
}

function eme_event_form($event, $info, $edit_recurrence=0) {
   
   global $plugin_page, $eme_timezone;
   $event_status_array = eme_status_array ();
   $nonce_field = wp_nonce_field('eme_admin','eme_admin_nonce',false,false);
   if (!isset($info['feedback'])) {
           $hidden_style="display:none;";
	   $message="";
   } else {
           $hidden_style="";
	   $message=$info['feedback'];
   }

   // if it comes from a copy action, remove event_id and such
   if (isset($event['is_duplicate'])) {
	   // first add the custom field answers to the event copy
	   $event['cf_answers']=eme_get_event_cf_answers($event['event_id']);
	   // now make it look like a new event
	   unset($event['event_id']);
	   unset($event['recurrence_id']);
	   $event['event_name'].= __(" (Copy)",'events-made-easy');
   }

   // let's determine if it is a new event, handy
   // or, in case of validation errors, $event can already contain info, but no event_id
   // so we create a new event and copy over the info into $event for the elements that do not exist
   if (empty($event['event_id'])) {
      $is_new_event=1;
      $new_event=eme_new_event();
      $event = array_replace_recursive($new_event,$event);
      $event_id=0;
   } else {
      $is_new_event=0;
      $event_id=$event['event_id'];
   }

   if (!empty($_GET['eme_admin_action'])) {
           $action = $_GET['eme_admin_action'];
           $recurrence_ID = isset($_GET['recurrence_id']) ? intval($_GET['recurrence_id']) : 0;
   } else {
	   $action = "";
	   $recurrence_ID = "";
   }
   // some checks and unserialize if needed
   $event = eme_get_extra_event_data($event);

   $form_destination = "admin.php?page=eme-manager";
   $recurrence_id = $event['recurrence_id'];
   if ($edit_recurrence && $recurrence_id) {
      $recurrence = eme_get_recurrence($recurrence_id);
      $hidden_fields = "<input type='hidden' name='eme_admin_action' id='eme_admin_action' value='update_recurrence' />
	                <input type='hidden' name='recurrence_id' id='recurrence_id' value='$recurrence_id' />
		       ";
      if ($recurrence['recurrence_freq'] == 'specific') {
	      $recurrence_start_date = $recurrence['recurrence_specific_days'];
      } else {
	      $recurrence_start_date = $recurrence['recurrence_start_date'];
      }
      if (!$recurrence['event_duration']) {
	      // old recurrences didn't have this, so we take the event start/end date and calc the difference
	      $event_start_obj=new ExpressiveDate($event['event_start'],$eme_timezone);
	      $event_end_obj=new ExpressiveDate($event['event_end'],$eme_timezone);
	      $recurrence['event_duration']=abs($event_end_obj->getDifferenceInDays($event_start_obj))+1;
      }

   } elseif ($edit_recurrence && $action=="duplicate_recurrence" && $recurrence_ID>0) {
      $recurrence = eme_get_recurrence($recurrence_ID);
      unset($recurrence['recurrence_id']);
      $hidden_fields = "<input type='hidden' name='eme_admin_action' id='eme_admin_action' value='insert_recurrence' /> ";
      if ($recurrence['recurrence_freq'] == 'specific') {
	      $recurrence_start_date = $recurrence['recurrence_specific_days'];
      } else {
	      $recurrence_start_date = $recurrence['recurrence_start_date'];
      }
      if (!$recurrence['event_duration']) {
	      // old recurrences didn't have this, so we take the event start/end date and calc the difference
	      $event_start_obj=new ExpressiveDate($event['event_start'],$eme_timezone);
	      $event_end_obj=new ExpressiveDate($event['event_end'],$eme_timezone);
	      $recurrence['event_duration']=abs($event_end_obj->getDifferenceInDays($event_start_obj))+1;
      }
   } else {
      $recurrence = eme_new_recurrence();
      // even for new events, after the 'save' button is clicked, we want to go to the list of events
      // so we use page=eme-manager too, not page=eme-new_event
      if ($is_new_event) {
	 if ($edit_recurrence)
		 $hidden_fields = "<input type='hidden' name='eme_admin_action' id='eme_admin_action' value='insert_recurrence' /> ";
	 else
		 $hidden_fields = "<input type='hidden' name='eme_admin_action' id='eme_admin_action' value='insert_event' /> ";
      } else {
         $hidden_fields = "<input type='hidden' name='eme_admin_action' id='eme_admin_action' value='update_event' />
			<input type='hidden' name='event_id' id='event_id' value='$event_id' />
		       ";
      }

      $recurrence['recurrence_start_date'] = eme_get_date_from_dt($event['event_start']);
      $recurrence['recurrence_end_date'] = eme_get_date_from_dt($event['event_end']);
   }
   
   $registration_wp_users_only = ($event['registration_wp_users_only']) ? "checked='checked'" : "";
   $registration_requires_approval = ($event['registration_requires_approval']) ? "checked='checked'" : "";

   $eme_date_obj=new ExpressiveDate("now",$eme_timezone);

   ?>
   <div class="wrap">
   <div id="events-message" class="updated notice notice-success is-dismissible" style="<?php echo $hidden_style; ?>">
               <p><?php echo $message; ?></p>
   </div>
   <form id="eventForm" name="eventForm" method="post" autocomplete="off" enctype="multipart/form-data" action="<?php echo $form_destination; ?>">
   <?php echo $nonce_field; ?>
   <?php echo $hidden_fields; ?>
         <div id="icon-events" class="icon32"><br /></div>
         <h1><?php echo eme_trans_esc_html($info['title']); ?></h1>
         <?php
         if ($event['recurrence_id']) {
            ?>
         <p id='recurrence_warning'>
            <?php
               if ($edit_recurrence) {
		  if ($recurrence_id>0) {
			  _e('WARNING: This is a recurrence.','events-made-easy');
			  echo "<br />";
			  _e('If you change this recurrence, events with a start day no longer matching the defined days will be removed while missing events will get added. Existing events with a start day that still match the defined days will get updated but you will not lose bookings.','events-made-easy');
		  }
               } else {
                  _e('WARNING: This event is part of a recurrence.','events-made-easy');
                  echo "<br />";
                  _e('If you change this event, it will become an independent event and be removed from the recurrence.', 'events-made-easy');
                  echo "<br /> <a href='".admin_url("admin.php?page=eme-manager&amp;eme_admin_action=edit_recurrence&amp;recurrence_id=".$event['recurrence_id'])."'>";
                  _e('Edit Recurrence','events-made-easy');
                  echo "</a>";
               }
               ?>
         </p>
         <?php
         }
         ?>
         <div id="poststuff">
         <div id="post-body" class="metabox-holder columns-2">
            <!-- MAIN -->
            <div id="post-body-content">
               <?php 
               $templates_array=eme_get_templates_array_by_id('event');
		?>
<div id="event-tabs" style="display: none;">
  <ul>
    <li><a href="#tab-eventdetails"><?php _e('Event','events-made-easy');?></a></li>
    <li><a href="#tab-locationdetails"><?php _e('Location','events-made-easy');?></a></li>
<?php if(get_option('eme_rsvp_enabled')) : ?>
    <li><a href="#tab-rsvp"><?php _e('RSVP','events-made-easy');?></a></li>
<?php endif; ?>
<?php if(get_option('eme_tasks_enabled')) : ?>
    <li><a href="#tab-tasks"><?php _e('Tasks','events-made-easy');?></a></li>
<?php endif; ?>
<?php if(get_option('eme_attributes_enabled')) : ?>
    <li><a href="#tab-eventattributes"><?php _e('Attributes','events-made-easy');?></a></li>
<?php endif; ?>
    <li><a href="#tab-eventcustomfields"><?php _e('Custom fields','events-made-easy');?></a></li>
  </ul>
  <div id="tab-eventdetails">
<?php
eme_meta_box_div_event_name($event,$edit_recurrence);
eme_meta_box_div_event_datetime($event,$recurrence,$edit_recurrence);
eme_meta_box_div_recurrence_info($recurrence,$edit_recurrence);
eme_meta_box_div_event_page_title_format($event,$templates_array);
eme_meta_box_div_event_single_event_format($event,$templates_array);
eme_meta_box_div_event_notes($event);
eme_meta_box_div_event_image($event);
eme_meta_box_div_event_url($event);
?>
  </div>
  <div id="tab-locationdetails">
<?php
eme_meta_box_div_event_location($event);
?>
  </div>

<?php if(get_option('eme_rsvp_enabled')) : ?>
  <div id="tab-rsvp">
	<?php eme_meta_box_div_event_rsvp_enabled($event); ?>
  	<div id="rsvp-accordion">
<?php
echo '<h3>'.__('Generic RSVP info','events-made-easy').'</h3>';
echo '<div>';
eme_meta_box_div_event_rsvp($event);
echo '</div>';
echo '<h3>'.__('Payment methods','events-made-easy').'</h3>';
echo '<div>';
eme_meta_box_div_event_payment_methods($event);
echo '</div>';
echo '<h3>'.__('Dynamic data','events-made-easy').'</h3>';
echo '<div>';
$templates_array=eme_get_templates_array_by_id('rsvpform');
if (!empty($event['event_properties']['rsvp_dyndata']))
   $eme_data = $event['event_properties']['rsvp_dyndata'];
else
   $eme_data=array();
if (!empty($event['event_id']))
	$used_groupingids=eme_get_event_cf_answers_groupingids($event['event_id']);
else
	$used_groupingids=array();
eme_dyndata_adminform($eme_data,$templates_array,$used_groupingids);
eme_meta_box_div_event_dyndata_allfields($event,$templates_array);
echo '</div>';
echo '<h3>'.__('RSVP form formats','events-made-easy').'</h3>';
echo '<div>';
eme_meta_box_div_event_registration_form_format($event,$templates_array);
eme_meta_box_div_event_registration_recorded_ok_html($event,$templates_array);
eme_meta_box_div_event_cancel_form_format($event,$templates_array);
eme_meta_box_div_event_captcha_settings($event);
echo '</div>';
echo '<h3>'.__('Attendance settings','events-made-easy').'</h3>';
echo '<div>';
eme_meta_box_div_attendance_info($event,$templates_array);
echo '</div>';
?>
  	</div>
  	<div id="div_mailformats">
	<h3><?php _e('RSVP Mail format settings','events-made-easy'); ?></h3>
  		<div id="mailformats-accordion">
<?php
$templates_array=eme_get_templates_array_by_id('rsvpmail');
echo '<h3>'.__('Booking Made or Approved Email','events-made-easy').'</h3>';
echo '<div>';
eme_meta_box_div_event_registration_approved_email($event,$templates_array);
echo '</div>';
echo '<h3>'.__('Booking Awaiting User Confirmation Email','events-made-easy').'</h3>';
echo '<div>';
eme_meta_box_div_event_registration_userpending_email($event,$templates_array);
echo '</div>';
echo '<h3>'.__('Booking Pending Email','events-made-easy').'</h3>';
echo '<div>';
eme_meta_box_div_event_registration_pending_email($event,$templates_array);
echo '</div>';
echo '<h3>'.__('Booking Updated Email','events-made-easy').'</h3>';
echo '<div>';
eme_meta_box_div_event_registration_updated_email($event,$templates_array);
echo '</div>';
echo '<h3>'.__('Booking Reminder Email','events-made-easy').'</h3>';
echo '<div>';
eme_meta_box_div_event_registration_reminder_email($event,$templates_array);
echo '</div>';
echo '<h3>'.__('Booking Cancelled Email','events-made-easy').'</h3>';
echo '<div>';
eme_meta_box_div_event_registration_cancelled_email($event,$templates_array);
echo '</div>';
echo '<h3>'.__('Booking Deleted Email','events-made-easy').'</h3>';
echo '<div>';
eme_meta_box_div_event_registration_trashed_email($event,$templates_array);
echo '</div>';
echo '<h3>'.__('Booking Paid Email','events-made-easy').'</h3>';
echo '<div>';
eme_meta_box_div_event_registration_paid_email($event,$templates_array);
echo '</div>';
echo '<h3>'.__('Booking Payment Gateway Notification Email','events-made-easy').'</h3>';
echo '<div>';
eme_meta_box_div_event_contactperson_ipn_email($event,$templates_array);
echo '</div>';
?>
  		</div>
  	</div>
  </div>
<?php endif; ?>

<?php if(get_option('eme_tasks_enabled')) :
?>
  <div id="tab-tasks">
    <div class="inside">
      <p id='p_tasks'>
<?php echo eme_ui_checkbox_binary($event['event_tasks'],"event_tasks", __('Enable tasks for this event','events-made-easy')); ?>
      </p>
    </div>
    <div id="tab-tasks-container">
	<h3><?php _e('Tasks','events-made-easy'); ?></h3>
  	<div id="tasks-accordion">
	<h3><?php _e('List of tasks','events-made-easy'); ?></h3>
	<div>
	<?php eme_meta_box_div_event_tasks($event,$edit_recurrence); ?>
	</div>
	</div>

	<h3><?php _e('Tasks settings','events-made-easy'); ?></h3>
  	<div id="tasks-settings-accordion">
	<h3><?php _e('Tasks generic settings','events-made-easy'); ?></h3>
	<div>
	<?php eme_meta_box_div_event_task_settings($event); ?>
	</div>
	<h3><?php _e('Tasks form format','events-made-easy'); ?></h3>
	<div>
	<?php
	eme_meta_box_div_event_task_signup_form_format($event,$templates_array);
	eme_meta_box_div_event_task_signup_recorded_ok_html($event,$templates_array);
	?>
	</div>
	</div>
	<h3><?php _e('Tasks Mail format settings','events-made-easy'); ?></h3>
  	<div id="tasks-mailtemplates-accordion">
<?php
$templates_array=eme_get_templates_array_by_id('task');
echo '<h3>'.__('Task Signup Made Email','events-made-easy').'</h3>';
echo '<div>';
eme_meta_box_div_event_task_signup_made_email($event,$templates_array);
echo '</div>';
echo '<h3>'.__('Task Signup Reminder Email','events-made-easy').'</h3>';
echo '<div>';
eme_meta_box_div_event_task_signup_reminder_email($event,$templates_array);
echo '</div>';
echo '<h3>'.__('Task Signup Cancelled Email','events-made-easy').'</h3>';
echo '<div>';
eme_meta_box_div_event_task_signup_cancelled_email($event,$templates_array);
echo '</div>';
echo '<h3>'.__('Task Signup Deleted Email','events-made-easy').'</h3>';
echo '<div>';
eme_meta_box_div_event_task_signup_trashed_email($event,$templates_array);
echo '</div>';
?>
  	</div>
    </div>
  </div>
<?php endif; ?>

<?php if(get_option('eme_attributes_enabled')) : ?>
  <div id="tab-eventattributes">
<?php
eme_meta_box_div_event_attributes($event);
?>
  </div>
<?php endif; ?>
  <div id="tab-eventcustomfields">
<?php
eme_meta_box_div_event_customfields($event);
?>
  </div>
</div> <!-- end event-tabs -->

               <p class="submit">
                  <?php if ($is_new_event) { ?>
                     <input type="submit" class="button-primary" id="event_update_button" name="event_update_button" value="<?php esc_attr_e ( 'Save' , 'events-made-easy'); ?> &raquo;" />
		     <br /><?php _e('If pressing save or update does not seem to be doing anything, then check all other tabs to make sure all required fields are filled out.','events-made-easy');?>
                  <?php } else { 
                     $delete_button_text=esc_html__( 'Are you sure you want to delete this event?', 'events-made-easy');
                     $deleteRecurrence_button_text=esc_html__( 'Are you sure you want to delete this recurrence?', 'events-made-easy');
                  ?>
                     <input type="submit" class="button-primary" id="event_update_button" name="event_update_button" value="<?php esc_attr_e ( 'Update' , 'events-made-easy'); ?> &raquo;" />
                     <?php if (!$edit_recurrence) { ?>
                     <input type="submit" class="button-primary" id="event_delete_button" name="event_delete_button" value="<?php esc_attr_e ( 'Delete Event', 'events-made-easy'); ?> &raquo;" onclick="return areyousure('<?php echo $delete_button_text; ?>');" />
                     <?php
                                $view_button_text = __( 'View' );
                                $view_button = sprintf(
                                        '%1$s<span class="screen-reader-text"> %2$s</span> &raquo;',
                                        $view_button_text,
                                        /* translators: Accessibility text. */
                                        __( '(opens in a new tab)' )
                                );
                     ?>
                     <a class="button-primary" href="<?php echo eme_event_url($event); ?>" target="wp-view-<?php echo $event['event_id']; ?>" id="event-view"><?php echo $view_button; ?></a>

		     <br /><?php _e('If pressing save or update does not seem to be doing anything, then check all other tabs to make sure all required fields are filled out.','events-made-easy');?>
                     <?php } ?> 
                     <?php if ($edit_recurrence && $recurrence_id>0) { ?>
                     <input type="submit" class="button-primary" id="event_deleteRecurrence_button" name="event_deleteRecurrence_button" value="<?php esc_attr_e ( 'Delete Recurrence', 'events-made-easy'); ?> &raquo;" onclick="return areyousure('<?php echo $deleteRecurrence_button_text; ?>');" />
                     <?php } ?> 
                  <?php } ?>
               </p>
            </div>
            <!-- END OF MAIN -->
            <!-- SIDEBAR -->
            <div id="postbox-container-1" class="postbox-container">
               <div id='side-sortables' class="meta-box-sortables ui-sortable">
                  <?php if (current_user_can(get_option('eme_cap_author_event')) || current_user_can(get_option('eme_cap_edit_events'))) { ?>
                  <!-- status postbox -->
                  <div class="postbox" id="eme_statusdiv">
                     <h2 class='hndle'><span>
                        <?php _e ( 'Event Status', 'events-made-easy'); ?>
                        </span></h2>
                     <div class="inside">
                        <p><?php _e('Status','events-made-easy'); ?>
                        <select id="status" name="event_status">
                        <?php
                           foreach ( $event_status_array as $key=>$value) {
                              if ($event['event_status'] && ($event['event_status']==$key)) {
                                 $selected = "selected='selected'";
                              } else {
                                 $selected = "";
                              }
                              echo "<option value='$key' $selected>$value</option>";
                           }
                        ?>
                        </select><br />
                        <?php
                           _e('Private events are only visible for logged in users, draft events are not visible from the front end.','events-made-easy');
                        ?>
                        </p>
                     </div>
                  </div>
                  <?php } ?>

		  <?php
			   if (!$is_new_event)
				   $event_author=$event['event_author'];
			   else
				   $event_author=get_current_user_id();
                  ?>
                  <!-- author postbox -->
                  <div class="postbox" id="eme_authordiv">
                     <h2 class='hndle'><span>
                        <?php _e ( 'Author', 'events-made-easy'); ?>
                        </span></h2>
                     <div class="inside">
                        <p><?php _e('Author of this event: ','events-made-easy'); ?>
                           <?php
			   $eme_wp_user_arr=array();
			   if ($event_author>0) {
				   $user_info = get_userdata($event_author);
				   $eme_wp_user_arr[$event_author]=$user_info->display_name;
			   }
			   echo eme_ui_select($event_author, 'event_author', $eme_wp_user_arr, '', 0,'eme_select2_wpuser_class');
                           ?>
                        </p>
                     </div>
                  </div>

                  <!-- contact postbox -->
                  <div class="postbox" id="eme_contactdiv">
                     <h2 class='hndle'><span>
                        <?php _e ( 'Contact Person', 'events-made-easy'); ?>
                        </span></h2>
                     <div class="inside">
                        <p><?php _e('If you leave this empty, the author will be used as contact person.','events-made-easy'); ?>
                           <?php
			   $eme_wp_user_arr=array();
			   #$eme_wp_user_arr[-1]=__ ( "Event author", 'events-made-easy');
			   if ($event['event_contactperson_id']>0) {
				   $user_info = get_userdata($event['event_contactperson_id']);
				   $eme_wp_user_arr[$event['event_contactperson_id']]=$user_info->display_name;
			   }
			   echo eme_ui_select($event['event_contactperson_id'], 'event_contactperson_id', $eme_wp_user_arr, '' , 0,'eme_select2_wpuser_class');
                           // if it is not a new event and there's no contact person defined, then the event author becomes contact person
                           // So let's display a warning what this means if there's no author (like when submitting via the frontend submission form)
                           if (!$is_new_event && $event['event_contactperson_id']<1 && $event['event_author']<1)
                              print "<br />". __( 'Since the author is undefined for this event, any reference to the contact person (like when using #_CONTACTPERSON when sending mails), will use the admin user info.', 'events-made-easy');
                           ?>
                        </p>
                     </div>
                  </div>
                  <?php if(get_option('eme_categories_enabled')) :?>
                  <div class="postbox" id="eme_categoriesdiv">
                     <h2 class='hndle'><span>
                        <?php _e ( 'Category', 'events-made-easy'); ?>
                        </span></h2>
                     <div class="inside">
                     <?php
                     $categories = eme_get_categories();
		     if (empty($categories)) {
                     ?>
                        <span><?php _e ( 'No categories defined.', 'events-made-easy'); ?></span>
                     <?php
		     } else {
                        foreach ( $categories as $category) {
                           if ($event['event_category_ids'] && in_array($category['category_id'],explode(",",$event['event_category_ids']))) {
                              $selected = "checked='checked'";
                           } else {
                              $selected = "";
                           }
                     ?>
            <input type="checkbox" name="event_category_ids[]" value="<?php echo $category['category_id']; ?>" <?php echo $selected ?> /><?php echo eme_trans_esc_html($category['category_name']); ?><br />
                     <?php
                        } // end foreach
                     } // end if
                     ?>
                     </div>
                  </div> 
                  <?php endif; ?>
                  <div class="postbox" id="eme_ditev">
                     <h2 class='hndle'><span>
                        <?php _e ( 'WP Page template'); ?>
                        </span></h2>
                     <div class="inside">
                     <?php
                     $templates = get_page_templates();
		     print eme_ui_select_inverted($event['event_properties']['wp_page_template'], 'eme_prop_wp_page_template', $templates, __('Default Template'));
		     print "<br />". __( 'By default the event uses the same WP page template as the defined special events page. If your theme provides several different page templates, chose another one if wanted.', 'events-made-easy');
                     ?>
                     </div>
                  </div>
               </div>
            </div>
            <!-- END OF SIDEBAR -->
         </div>
         </div>
   </form>
   </div>
<?php
}

function eme_validate_event($event) {

   $event_properties = $event['event_properties'];

   $required_fields = array("event_name" => __('The event name', 'events-made-easy'), "start_date" => __('The start date','events-made-easy'));
   $troubles = "";
   if (eme_is_empty_datetime($event['event_start'])) {
      $troubles .= "<li>".$required_fields['start_date'].__(" is missing!", 'events-made-easy')."</li>";
   }
   if (empty($event['event_name'])) {
      $troubles .= "<li>".$required_fields['event_name'].__(" is missing!", 'events-made-easy')."</li>";
   }  
   if (isset($_POST['repeated_event']) && $_POST['repeated_event'] == "1" && $_POST['recurrence_freq']!="specific" && (!isset($_POST['recurrence_end_date']) || $_POST['recurrence_end_date'] == ""))
      $troubles .= "<li>".__ ( 'Since the event is repeated, you must specify an event date for the recurrence.', 'events-made-easy')."</li>";

   if ($event['event_rsvp']) {
	   if (eme_is_multi($event['event_seats']) && !eme_is_multi($event['price']))
		   $troubles .= "<li>".__ ( 'Since the event contains multiple seat categories (multiseat), you must specify the price per category (multiprice) as well.', 'events-made-easy')."</li>";
	   if (eme_is_multi($event['event_seats']) && eme_is_multi($event['price'])) {
		   $count1=count(eme_convert_multi2array($event['event_seats']));
		   $count2=count(eme_convert_multi2array($event['price']));
		   if ($count1 != $count2)
			   $troubles .= "<li>".__ ( 'Since the event contains multiple seat categories (multiseat), you must specify the exact same amount of prices (multiprice) as well.', 'events-made-easy')."</li>";
	   }

	   // check some placeholders
	   $password_found=0;
	   $lastname_found=0;
	   $email_found=0;
	   if (!eme_is_empty_string($event['event_registration_form_format']))
		   $format = $event['event_registration_form_format'];
	   elseif ($event_properties['event_registration_form_format_tpl']>0)
		   $format = eme_get_template_format($event_properties['event_registration_form_format_tpl']);
	   else
		   $format = get_option('eme_registration_form_format');
	   preg_match_all("/#(REQ)?_?[A-Za-z0-9_]+(\{.*?\})?/", $format, $placeholders);
	   foreach($placeholders[0] as $result) {
		   if (strstr($result,'#REQ')) {
			   $result = str_replace("#REQ","#",$result);
		   }
		   if (strstr($result,'#_RESP')) {
			   $result = str_replace("#_RESP","#_",$result);
		   }
		   if (preg_match('/#_SEATS$|#_SPACES$/', $result)) {
			   if (eme_is_multi($event['price'])) {
				   // this will show if people mix #_SEATS and #_SEATS{xx}
				   // for new events the function eme_validate_event already prevents this too
				   $troubles .= "<li>".__("Your event seems to have multiple price categories, so you can't use #_SEATS (or #_SPACES) in your event RSVP form format. Please correct the RSVP form format to use the #_SEATS{xx} notation.","events-made-easy")."</li>";
			   }
		   }
		   if (preg_match('/#_PASSWORD/', $result)) {
			   $password_found=1;
		   }
		   if (preg_match('/#_EMAIL$|#_HTML5_EMAIL$/', $result)) {
			   $email_found=1;
		   }
		   if (preg_match('/#_NAME|#_LASTNAME$/', $result)) {
			   $lastname_found=1;
		   }
	   }
	   if (!$email_found) {
		   $troubles .= "<li>".__("Please correct the RSVP form format to use #_EMAIL.","events-made-easy")."</li>";
	   }
	   if (!$lastname_found) {
		   $troubles .= "<li>".__("Please correct the RSVP form format to use #_LASTNAME.","events-made-easy")."</li>";
	   }
	   if (!empty($event_properties['rsvp_password']) && !$password_found) {
		   $troubles .= "<li>".__("You have indicated you want to use a password but your event RSVP form format doesn't contain #_PASSWORD. Please correct the RSVP form format to use #_PASSWORD.","events-made-easy")."</li>";
	   }

	   if (eme_is_multi($event_properties['max_allowed']) && eme_is_multi($event['price'])) {
		   $arr1=eme_convert_multi2array($event_properties['max_allowed']);
		   $count1=count($arr1);
		   $count2=count(eme_convert_multi2array($event['price']));
		   if (!eme_array_integers($arr1))
			   $troubles .= "<li>".__ ( 'If specified, the max amount of seats to book should consist of integers only.', 'events-made-easy')."</li>";
		   if ($count1 != $count2)
			   $troubles .= "<li>".__ ( 'Since this is a multiprice event and you decided to limit the max amount of seats to book (for one booking) per price category, you must specify the exact same amount of "max seats to book" as you did for the prices.', 'events-made-easy')."</li>";
	   } elseif (!empty($event_properties['max_allowed']) && !is_numeric($event_properties['max_allowed'])) {
		   $troubles .= "<li>".__ ( 'If specified, the max amount of seats to book should be an integer.', 'events-made-easy')."</li>";
	   }
	   if (eme_is_multi($event_properties['min_allowed']) && eme_is_multi($event['price'])) {
		   $arr1=eme_convert_multi2array($event_properties['min_allowed']);
		   $count1=count($arr1);
		   $count2=count(eme_convert_multi2array($event['price']));
		   if (!eme_array_integers($arr1))
			   $troubles .= "<li>".__ ( 'If specified, the min amount of seats to book should consist of integers only.', 'events-made-easy')."</li>";
		   if ($count1 != $count2)
			   $troubles .= "<li>".__ ( 'Since this is a multiprice event and you decided to limit the min amount of seats to book (for one booking) per price category, you must specify the exact same amount of "min seats to book" as you did for the prices.', 'events-made-easy')."</li>";
	   } elseif (!empty($event_properties['min_allowed']) && !is_numeric($event_properties['min_allowed'])) {
		   $troubles .= "<li>".__ ( 'If specified, the min amount of seats to book should be an integer.', 'events-made-easy')."</li>";
	   }
   }

   // own validation is possible
   if (has_filter('eme_validate_event_filter')) $troubles=apply_filters('eme_validate_event_filter',$event,$troubles);

   if (empty($troubles)) {
      return "OK";
   } else {
      $message = __('Ach, some problems here:', 'events-made-easy')."<ul>$troubles</ul>";
      return $message; 
   }
}

// General script to make sure hidden fields are shown when containing data
function eme_admin_event_script() {
   // jquery ui locales are with dashes, not underscores
   $lang = eme_detect_lang();

?>
<script type="text/javascript">
   //<![CDATA[

function eme_event_page_title_format(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_event_page_title_format' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_single_event_format(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_single_event_format' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_contactperson_email_subject(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_contactperson_email_subject' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_contactperson_email_body(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_contactperson_email_body' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_contactperson_pending_email_subject(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_contactperson_pending_email_subject' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_contactperson_pending_email_body(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_contactperson_pending_email_body' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_contactperson_cancelled_email_subject(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_contactperson_cancelled_email_subject' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_contactperson_cancelled_email_body(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_contactperson_cancelled_email_body' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_contactperson_ipn_email_subject(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_contactperson_ipn_email_subject' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_contactperson_ipn_email_body(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_contactperson_ipn_email_body' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_contactperson_paid_email_subject(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_contactperson_paid_email_subject' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_contactperson_paid_email_body(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_contactperson_paid_email_body' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_respondent_email_subject(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_respondent_email_subject' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_respondent_email_body(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_respondent_email_body' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_registration_recorded_ok_html(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_registration_recorded_ok_html' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_registration_cancelled_email_subject(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_registration_cancelled_email_subject' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_registration_cancelled_email_body(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_registration_cancelled_email_body' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_registration_trashed_email_subject(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_registration_trashed_email_subject' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_registration_trashed_email_body(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_registration_trashed_email_body' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_registration_pending_email_subject(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_registration_pending_email_subject' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_registration_pending_email_body(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_registration_pending_email_body' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_registration_pending_reminder_email_subject(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_registration_pending_reminder_email_subject' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_registration_pending_reminder_email_body(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_registration_pending_reminder_email_body' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_registration_updated_email_subject(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_registration_updated_email_subject' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_registration_updated_email_body(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_registration_updated_email_body' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_registration_reminder_email_subject(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_registration_reminder_email_subject' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_registration_reminder_email_body(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_registration_reminder_email_body' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_registration_paid_email_subject(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_registration_paid_email_subject' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_registration_paid_email_body(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_registration_paid_email_body' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_registration_form_format(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_registration_form_format' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

function eme_cancel_form_format(){
   var tmp_value='<?php echo rawurlencode(get_option('eme_cancel_form_format' )); ?>';
   tmp_value=decodeURIComponent(tmp_value).replace(/\r\n/g,"\n");
   return tmp_value;
}

//]]>
</script>

<?php
}

function eme_meta_box_div_event_name($event,$edit_recurrence=0) {
?>
<div id="titlediv">
   <!-- we need title for qtranslate as ID -->
   <input type="text" id="title" name="event_name" required="required" placeholder="<?php _e('Event name','events-made-easy');?>" value="<?php echo eme_esc_html($event['event_name']); ?>" />
   <br />
   <br />
   <?php 
   if (!empty($event['event_id']) && !empty($event['event_name']) != "") {
       echo "<b>". __ ('Permalink: ', 'events-made-easy') . "</b>";
   } else {
       echo "<b>". __ ('Permalink prefix: ', 'events-made-easy') . "</b>";
   }
   echo trailingslashit(home_url());
   $events_prefixes = get_option('eme_permalink_events_prefix','events');
   if (preg_match('/,/',$events_prefixes)) {
	   $events_prefixes=explode(',',$events_prefixes);
	   $events_prefixes_arr=array();
	   foreach ($events_prefixes as $events_prefix) {
		   $events_prefixes_arr[$events_prefix]=eme_permalink_convert($events_prefix);
	   }
	   $prefix = $event['event_prefix'] ? $event['event_prefix'] : '';
	   echo eme_ui_select($prefix,'event_prefix',$events_prefixes_arr);
   } else {
	   echo eme_permalink_convert($events_prefixes);
   }
   if (!empty($event['event_id']) && !empty($event['event_name']) != "") {
      $slug = $event['event_slug'] ? $event['event_slug'] : $event['event_name'];
      $slug = eme_permalink_convert_noslash($slug);
      if ($edit_recurrence) {
      ?>
         <input type="hidden" id="event_slug" name="event_slug" value="<?php echo $slug; ?>" />
      <?php
      } else {
      ?>
         <input type="text" id="event_slug" name="event_slug" value="<?php echo $slug; ?>" /><?php echo user_trailingslashit(""); ?>
      <?php
      }
   }
?>
</div>
<?php
}

function eme_meta_box_div_event_datetime($event,$recurrence,$edit_recurrence=0) {
   global $eme_timezone, $eme_wp_date_format, $eme_wp_time_format, $eme_plugin_url;
   // check if the user wants AM/PM or 24 hour notation
   // make sure that escaped characters are filtered out first
   $start_date_obj=new ExpressiveDate($event['event_start'],$eme_timezone);
   $end_date_obj=new ExpressiveDate($event['event_end'],$eme_timezone);
   $eme_recurrence_checked = "";
   if ($edit_recurrence) {
         $show_recurrent_form = 1;
	 if ($event['recurrence_id']) {
		// checkboxes can't be readonly, but we can prevent the click-change by this little neat trick
		$eme_recurrence_checked="checked='checked' onclick='this.checked=!this.checked;'";
	 } else {
		$eme_recurrence_checked="checked='checked'";
	 }
   } else {
      if (isset($event['recurrence_id']) && $event['recurrence_id']) {
         # editing a single event of an recurrence: don't show the recurrence form
         $show_recurrent_form = 0;
      } else {
         # for single non-recurrent events: we show the form, so we can make it recurrent if we want to
         # Also: in the case that bookings already took place for this event, we don't allow the conversion
         # to a recurrent event, as that would cause the bookings to be lost
	 if (isset($event['event_id']))
            $booking_ids=eme_get_bookingids_for($event['event_id']);
	 else
	    $booking_ids="";
         if (empty($booking_ids))
            $show_recurrent_form = 1;
         else
            $show_recurrent_form = 0;
      }
   }
?>
<div id="div_event_datetime">
      <div id="div_event_date">
      <b><?php _e('Event date', 'events-made-easy'); ?></b>
      <input id="start-date-to-submit" type="hidden" name="event_start_date" value="" />
      <input id="localized-start-date" type="text" name="localized_event_start_date" value="" style="background: #FCFFAA;" readonly="readonly" data-date='<?php if (!eme_is_empty_datetime($event['event_start'])) echo eme_js_datetime($event['event_start']); ?>' data-alt-field='start-date-to-submit' class='eme_formfield_fdate' required="required" />
      <input id="end-date-to-submit" type="hidden" name="event_end_date" value="" />
      <input id="localized-end-date" type="text" name="localized_event_end_date" value="" style="background: #FCFFAA;" readonly="readonly" data-date='<?php if (!eme_is_empty_datetime($event['event_end'])) echo eme_js_datetime($event['event_end']); ?>' data-alt-field='end-date-to-submit' class='eme_formfield_fdate' />
      <p class="eme_smaller">
      <?php _e ( 'The event beginning and end date.', 'events-made-easy'); ?>
      </p>
      </div>
      <div id="div_recurrence_event_duration">
      <b><?php _e('Event duration (in days)', 'events-made-easy'); ?></b>
      <input id="event_duration" type="number" name="event_duration" min="1" value="<?php echo $recurrence['event_duration']; ?>" /><?php _e ( 'day(s)', 'events-made-easy') ?>
      </div>
      <div id="time-selector">
      <?php
	echo "<b>". __('Event time', 'events-made-easy') . "</b>";
      ?>
      <input id="localized_start_time" type="text" size="8" name="localized_start_time" value="<?php if (!eme_is_empty_datetime($event['event_start'])) echo $start_date_obj->format($eme_wp_time_format); ?>" class='eme_formfield_timepicker'  />
      -
      <input id="localized_end_time" type="text" size="8" name="localized_end_time" value="<?php if (!eme_is_empty_datetime($event['event_end'])) echo $end_date_obj->format($eme_wp_time_format); ?>" class='eme_formfield_timepicker'  />
      <p class="eme_smaller">
      <?php _e ( 'The time of the event beginning and end', 'events-made-easy')?>
      </p>
      </div>
      <br />
      <?php echo eme_ui_checkbox_binary($event['event_properties']['all_day'],"eme_prop_all_day", __('This event lasts all day','events-made-easy'));?>
      <br />
      <?php
          if ($show_recurrent_form==1) {
      ?>
             <input id="event-recurrence" type="checkbox" name="repeated_event" value="1" <?php echo $eme_recurrence_checked; ?> />
             <label for="event-recurrence"><?php  _e ( 'Check if your event happens more than once.', 'events-made-easy'); ?></label>
      <?php
          } else { 
	     echo "<img style='vertical-align: middle;' src='".$eme_plugin_url."images/warning.png' alt='warning' />";
             _e ( 'Bookings found for this event, so not possible to convert to a recurring event.', 'events-made-easy');
          }
      ?>
</div>
<?php
}

function eme_meta_box_div_recurrence_info($recurrence,$edit_recurrence=0) {
   global $wp_locale;
   $freq_options = array ("daily" => __ ( 'Daily', 'events-made-easy'), "weekly" => __ ( 'Weekly', 'events-made-easy'), "monthly" => __ ( 'Monthly', 'events-made-easy'), "specific" => __('Specific days', 'events-made-easy') );
   $days_names = array (1 => $wp_locale->get_weekday_abbrev(__('Monday')), 2 => $wp_locale->get_weekday_abbrev(__('Tuesday')), 3 => $wp_locale->get_weekday_abbrev(__('Wednesday')), 4 => $wp_locale->get_weekday_abbrev(__('Thursday')), 5 => $wp_locale->get_weekday_abbrev(__('Friday')), 6 => $wp_locale->get_weekday_abbrev(__('Saturday')), 7 => $wp_locale->get_weekday_abbrev(__('Sunday')) );
   $saved_bydays = explode ( ",", $recurrence['recurrence_byday'] );
   $weekno_options = array ("1" => __ ( 'first', 'events-made-easy'), '2' => __ ( 'second', 'events-made-easy'), '3' => __ ( 'third', 'events-made-easy'), '4' => __ ( 'fourth', 'events-made-easy'), '5' => __ ( 'fifth', 'events-made-easy'), '-1' => __ ( 'last', 'events-made-easy'), "0" => __('Start day', 'events-made-easy') );
   $holidays_array_by_id=eme_get_holidays_array_by_id();
   if ($edit_recurrence && $recurrence['recurrence_freq'] == 'specific') {
              $recurrence_start_date = $recurrence['recurrence_specific_days'];
   } else {
              $recurrence_start_date = $recurrence['recurrence_start_date'];
   }
?>
<div id="div_recurrence_date" style="background-color: lightgrey;">
   <br />
   <b><?php _e('Recurrence dates', 'events-made-easy'); ?></b>
   <input id="rec-start-date-to-submit" type="hidden" name="recurrence_start_date" value="" />
   <input id="localized-rec-start-date" type="text" name="localized_recurrence_date" value="" style="background: #FCFFAA;" readonly="readonly" data-date='<?php echo eme_js_datetime($recurrence_start_date); ?>' data-alt-field='rec-start-date-to-submit' class='eme_formfield_fdate' />
   <input id="rec-end-date-to-submit" type="hidden" name="recurrence_end_date" value="" />
   <input id="localized-rec-end-date" type="text" name="localized_recurrence_end_date" value="" style="background: #FCFFAA;" readonly="readonly" data-date='<?php echo eme_js_datetime($recurrence['recurrence_end_date']); ?>' data-alt-field='rec-end-date-to-submit' class='eme_formfield_fdate' />
   <p class="eme_smaller" id='recurrence-dates-explanation'>
   <?php _e ( 'The recurrence beginning and end date.', 'events-made-easy'); ?>
   </p>
   <span id='recurrence-dates-explanation-specificdates'>
   <?php _e ( 'Select all the dates you want the event to begin on.', 'events-made-easy'); ?>
   </span>
   <span id='recurrence-dates-specificdates'>
   </span>
   <div id="event_recurrence_pattern">
      <?php _e('Frequency:','events-made-easy'); ?>
      <select id="recurrence-frequency" name="recurrence_freq">
            <?php eme_option_items ( $freq_options, $recurrence['recurrence_freq'] ); ?>
      </select>
	   <div id="recurrence-intervals">
                           <p>
                              <?php _e ( 'Every', 'events-made-easy')?>
                              <input id="recurrence-interval" name='recurrence_interval'
                                size='2' value='<?php if (isset ($recurrence['recurrence_interval'])) echo $recurrence['recurrence_interval']; ?>' />
			      <span class='interval-desc' id="interval-daily-singular"> <?php _e ( 'day', 'events-made-easy')?></span>
			      <span class='interval-desc' id="interval-daily-plural"> <?php _e ( 'days', 'events-made-easy') ?></span>
                              <span class='interval-desc' id="interval-weekly-singular"> <?php _e ( 'week', 'events-made-easy')?></span>
                              <span class='interval-desc' id="interval-weekly-plural"> <?php _e ( 'weeks', 'events-made-easy')?></span>
                              <span class='interval-desc' id="interval-monthly-singular"> <?php _e ( 'month', 'events-made-easy')?></span>
                              <span class='interval-desc' id="interval-monthly-plural"> <?php _e ( 'months', 'events-made-easy')?></span>
                              <br />
                           <span class="alternate-selector" id="weekly-selector">
                              <?php eme_checkbox_items ( 'recurrence_bydays[]', $days_names, $saved_bydays ); ?>
                              <br />
                              <?php _e ( 'If you leave this empty, the recurrence start date will be used as a reference.', 'events-made-easy')?>
                           </span>
                           <span class="alternate-selector" id="monthly-selector">
                              <?php _e ( 'Every', 'events-made-easy')?>
                              <select id="monthly-modifier" name="recurrence_byweekno">
                                 <?php eme_option_items ( $weekno_options, $recurrence['recurrence_byweekno'] ); ?>
                              </select>
                              <select id="recurrence-weekday" name="recurrence_byday">
                                 <?php eme_option_items ( $days_names, $recurrence['recurrence_byday'] ); ?>
                              </select>
                              <?php _e ( 'Day of month', 'events-made-easy')?>
                              <br />
                              <?php _e ( 'If you use "Start day" as day of the month, the recurrence start date will be used as a reference.', 'events-made-easy')?>
                              &nbsp;
                          </span>
                          </p>
     </div>
   <?php 
      if (!empty($holidays_array_by_id)) {
         _e('Holidays: ','events-made-easy');
         echo eme_ui_select($recurrence['holidays_id'],'holidays_id',$holidays_array_by_id);
	 ?><p class="eme_smaller"><?php
         _e('No events will be created on days matching an entry in the holidays list','events-made-easy');
	 ?></p><?php
      }
   ?>
  </div>
</div>
<?php
}

function eme_meta_box_div_event_page_title_format($event,$templates_array) {
	global $eme_plugin_url;
	if (eme_is_empty_string($event['event_page_title_format']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
<div id="div_event_page_title_format">
   <br />
   <b><?php _e('Single Event Title', 'events-made-easy'); ?></b>
   <p class="eme_smaller">
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_page_title_format_tpl'],'eme_prop_event_page_title_format_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="event_page_title_format_div" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <div id="event_page_title_format_div" <?php echo $showhide_style; ?>>
   <?php eme_wysiwyg_textarea("event_page_title_format", $event['event_page_title_format'], 1, 0); ?> 
   </div>
</div>
<?php
}

function eme_meta_box_div_event_single_event_format($event,$templates_array) {
	global $eme_plugin_url;
	if (eme_is_empty_string($event['event_single_event_format']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
<div id="div_event_single_event_format">
      <br />
      <?php
	echo "<b>". __('Single Event', 'events-made-easy') . "</b>";
      ?>
   <p class="eme_smaller">
   <?php _e ( 'The format of the single event page.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   <br />
   <?php _e ( 'This defines the layout of your event (where the event description goes, the RSVP form, the map, ... .','events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_single_event_format_tpl'],'eme_prop_event_single_event_format_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="event_single_event_format_div" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <div id="event_single_event_format_div" <?php echo $showhide_style; ?>>
   <?php eme_wysiwyg_textarea("event_single_event_format", $event['event_single_event_format'], 1, 0); ?> 
   <?php if (current_user_can('unfiltered_html')) {
            echo "<div class='eme_notice_unfiltered_html'>";
            _e('Your account has the ability to post unrestricted HTML content here, except javascript.', 'events-made-easy'); 
            echo "</div>";
         }
   ?>
   </div>
</div>
<?php
}

function eme_meta_box_div_event_contactperson_ipn_email($event,$templates_array) {
	global $eme_plugin_url;
	if (eme_is_empty_string($event['event_properties']['contactperson_registration_ipn_email_subject']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
	$use_html_editor=get_option('eme_rsvp_send_html');
?>
<div id="div_event_contactperson_email_ipn">
   <b><?php _e('Contact Person Payment Gateway Notification Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email which will be sent to the contact person when a payment notification is received via a payment gateway.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['contactperson_registration_ipn_email_subject_tpl'],'eme_prop_contactperson_registration_ipn_email_subject_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="eme_prop_contactperson_registration_ipn_email_subject" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <input type="text" maxlength="250" name="eme_prop_contactperson_registration_ipn_email_subject" id="eme_prop_contactperson_registration_ipn_email_subject" <?php echo $showhide_style; ?> value="<?php echo eme_esc_html($event['event_properties']['contactperson_registration_ipn_email_subject']);?>" />
   <br />
<?php
	if (eme_is_empty_string($event['event_properties']['contactperson_registration_ipn_email_body']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
	echo "<b>". __('Contact Person Payment Gateway Notification Email Body', 'events-made-easy') . "</b>";
?>
   <p class="eme_smaller"><?php _e ( 'The body of the email which will be sent to the contact person when a payment notification is received via a payment gateway.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['contactperson_registration_ipn_email_body_tpl'],'eme_prop_contactperson_registration_ipn_email_body_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="eme_prop_contactperson_registration_ipn_email_body_div" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <div id="eme_prop_contactperson_registration_ipn_email_body_div" <?php echo $showhide_style; ?>>
   <?php eme_wysiwyg_textarea("eme_prop_contactperson_registration_ipn_email_body", $event['event_properties']['contactperson_registration_ipn_email_body'], $use_html_editor, 0); ?> 
   </div>
</div>
<?php
}

function eme_meta_box_div_event_dyndata_allfields($event,$templates_array) {
   $eme_prop_dyndata_all_fields = ($event['event_properties']['dyndata_all_fields']) ? "checked='checked'" : "";
?>
<div id="div_event_dyndata_allfields">
      <br />
      <b><?php _e('Dynamic data check on every field', 'events-made-easy'); ?></b>
      <input id="eme_prop_dyndata_all_fields" name='eme_prop_dyndata_all_fields' value='1' type='checkbox' <?php echo $eme_prop_dyndata_all_fields; ?> />
      <span class="eme_smaller"><br /><?php _e ( 'By default the dynamic data check only happens for the fields mentioned in your dynamic data condition if those are present in your RSVP form definition. Using this option, you can use all booking placeholders, even if not defined in your RSVP form. The small disadvantage is that more requests will be made to the backend, so use only when absolutely needed.','events-made-easy'); ?></span>
      <br /><?php _e ( 'If your event uses a discount of type code and you want the dynamic price (using #_DYNAMICPRICE) to be updated taking that discount into account too, then also check this option.','events-made-easy'); ?>
</div>
<?php
}

function eme_meta_box_div_event_registration_recorded_ok_html($event,$templates_array) {
	global $eme_plugin_url;
	if (eme_is_empty_string($event['event_registration_recorded_ok_html']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
	$use_html_editor=get_option('eme_rsvp_send_html');
?>
<div id="div_event_registration_recorded_ok_html">
      <br />
      <?php
	echo "<b>". __('Booking recorded message', 'events-made-easy') . "</b>";
      ?>
   <p class="eme_smaller"><?php _e ( 'The text (html allowed) shown to the user when the booking has been made successfully.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_registration_recorded_ok_html_tpl'],'eme_prop_event_registration_recorded_ok_html_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="event_registration_recorded_ok_html_div" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <div id="event_registration_recorded_ok_html_div" <?php echo $showhide_style; ?>>
   <?php eme_wysiwyg_textarea("event_registration_recorded_ok_html", $event['event_registration_recorded_ok_html'], 1, 0); ?> 
   </div>
</div>
<?php
}

function eme_meta_box_div_event_registration_approved_email($event,$templates_array) {
	global $eme_plugin_url;
	$use_html_editor=get_option('eme_rsvp_send_html');
	if (eme_is_empty_string($event['event_properties']['event_respondent_email_subject']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
	echo "<img style='vertical-align: middle;' src='".$eme_plugin_url."images/warning.png' alt='warning' />";
        _e ( 'When an event is configured to auto-approve bookings after payment and you have selected to send out payment mails and the total amount to pay is not 0, this mail is not sent but the mail concerning a booking being paid is sent when a pending booking is marked as paid.','events-made-easy');
	if (!get_option('eme_rsvp_mail_notify_is_active')) {
		print "<div class='info eme-message-admin'><p>".__('RSVP notifications are not activated, so these mails will not be sent. Go in the Mail settings to activate this if wanted.','events-made-easy')."</p></div>";
	} elseif (!get_option('eme_rsvp_mail_notify_approved')) {
		print "<div class='info eme-message-admin'><p>".__('RSVP notifications are not activated for bookings made or approved, so these mails will not be sent. Go in the Mail settings to activate this if wanted.','events-made-easy')."</p></div>";
	}
?>
<div>
   <b><?php _e('Booking Made Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email sent to the respondent when a booking is made (not pending) or approved.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_respondent_email_subject_tpl'],'eme_prop_event_respondent_email_subject_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="eme_prop_event_respondent_email_subject" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <input type="text" maxlength="250" name="eme_prop_event_respondent_email_subject" id="eme_prop_event_respondent_email_subject" <?php echo $showhide_style; ?> value="<?php echo eme_esc_html($event['event_properties']['event_respondent_email_subject']);?>" />
   <br />
<?php
	if (eme_is_empty_string($event['event_respondent_email_body']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
   <b><?php _e('Booking Made Email Body', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The body of the email sent to the respondent when a booking is made (not pending) or approved.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_respondent_email_body_tpl'],'eme_prop_event_respondent_email_body_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="event_respondent_email_body_div" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <div id="event_respondent_email_body_div" <?php echo $showhide_style; ?>>
   <?php eme_wysiwyg_textarea("event_respondent_email_body", $event['event_respondent_email_body'], $use_html_editor, 0); ?> 
   </div>
</div>
<br />
<?php
	if (eme_is_empty_string($event['event_properties']['event_contactperson_email_subject']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
<div>
   <b><?php _e('Contact Person Booking Made Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email which will be sent to the contact person when a booking is made.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_contactperson_email_subject_tpl'],'eme_prop_event_contactperson_email_subject_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="eme_prop_event_contactperson_email_subject" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <input type="text" maxlength="250" name="eme_prop_event_contactperson_email_subject" id="eme_prop_event_contactperson_email_subject" <?php echo $showhide_style; ?> value="<?php echo eme_esc_html($event['event_properties']['event_contactperson_email_subject']);?>" />
   <br />
<?php
	if (eme_is_empty_string($event['event_contactperson_email_body']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
   <b><?php _e('Contact Person Booking Made Email Body', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The body of the email which will be sent to the contact person when a booking is made.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_contactperson_email_body_tpl'],'eme_prop_event_contactperson_email_body_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="event_contactperson_email_body_div" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <div id="event_contactperson_email_body_div" <?php echo $showhide_style; ?>>
   <?php eme_wysiwyg_textarea("event_contactperson_email_body", $event['event_contactperson_email_body'], $use_html_editor, 0); ?> 
   </div>
</div>
<br />
<div>
    <b><?php _e('Booking made mail attachments', 'events-made-easy');?></b><br />
<span id="booking_attach_links"><?php
   $attachment_ids=$event['event_properties']['booking_attach_ids'];
   if (!empty($attachment_ids)) {
           $attachment_id_arr=explode(",",$attachment_ids);
           foreach ($attachment_id_arr as $attachment_id) {
		   $attach_link= eme_get_attachment_link($attachment_id);
		   if (!empty($attach_link)) {
			   echo $attach_link;
			   echo "<br \>";
                   }
           }
   } else {
           $attachment_ids="";
   }
?></span>
<input type="hidden" name="eme_prop_booking_attach_ids" id="eme_booking_attach_ids" value="<?php echo $attachment_ids;?>">
<input type="button" name="booking_attach_button" id="booking_attach_button" value="<?php _e('Add attachments', 'events-made-easy');?>" class="button-secondary action">
<input type="button" name="booking_remove_attach_button" id="booking_remove_attach_button" value="<?php _e('Remove attachments', 'events-made-easy');?>" class="button-secondary action">
<br /><?php _e('Optionally add attachments to the mail when a new booking is made.','events-made-easy');?>
<br /><?php _e('Only fill this in if you want to override the default settings.','events-made-easy');?>
</div>
<?php
}

function eme_meta_box_div_event_registration_userpending_email($event,$templates_array) {
	global $eme_plugin_url;
	$use_html_editor=get_option('eme_rsvp_send_html');
	if (eme_is_empty_string($event['event_properties']['event_registration_userpending_email_subject']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
	if (!get_option('eme_rsvp_mail_notify_is_active')) {
		print "<div class='info eme-message-admin'><p>".__('RSVP notifications are not activated, so these mails will not be sent. Go in the Mail settings to activate this if wanted.','events-made-easy')."</p></div>";
	} elseif (!get_option('eme_rsvp_mail_notify_pending')) {
		print "<div class='info eme-message-admin'><p>".__('RSVP notifications are not activated for pending bookings, so these mails will not be sent. Go in the Mail settings to activate this if wanted.','events-made-easy')."</p></div>";
	}
?>

<div>
   <b><?php _e('Booking Awaiting User Confirmation Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email which will be sent to the respondent if the booking requires user confirmation.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_registration_userpending_email_subject_tpl'],'eme_prop_event_registration_userpending_email_subject_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="eme_prop_event_registration_userpending_email_subject" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <input type="text" maxlength="250" name="eme_prop_event_registration_userpending_email_subject" id="eme_prop_event_registration_userpending_email_subject" <?php echo $showhide_style; ?> value="<?php echo eme_esc_html($event['event_properties']['event_registration_userpending_email_subject']);?>" />
   <br />
<?php
	if (eme_is_empty_string($event['event_properties']['event_registration_userpending_email_body']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
	echo "<b>". __('Booking Awaiting User Confirmation Email Body', 'events-made-easy') . "</b>";
?>
   <p class="eme_smaller"><?php _e ( 'The body of the email which will be sent to the respondent if the booking requires user confirmation.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_registration_userpending_email_body_tpl'],'eme_prop_event_registration_userpending_email_body_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="event_registration_userpending_email_body_div" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <div id="event_registration_userpending_email_body_div" <?php echo $showhide_style; ?>>
   <?php eme_wysiwyg_textarea("eme_prop_event_registration_userpending_email_body", $event['event_properties']['event_registration_userpending_email_body'], $use_html_editor, 0); ?> 
   </div>
</div>
<?php
}

function eme_meta_box_div_event_registration_pending_email($event,$templates_array) {
	global $eme_plugin_url;
	$use_html_editor=get_option('eme_rsvp_send_html');
	if (eme_is_empty_string($event['event_properties']['event_registration_pending_email_subject']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
	if (!get_option('eme_rsvp_mail_notify_is_active')) {
		print "<div class='info eme-message-admin'><p>".__('RSVP notifications are not activated, so these mails will not be sent. Go in the Mail settings to activate this if wanted.','events-made-easy')."</p></div>";
	} elseif (!get_option('eme_rsvp_mail_notify_pending')) {
		print "<div class='info eme-message-admin'><p>".__('RSVP notifications are not activated for pending bookings, so these mails will not be sent. Go in the Mail settings to activate this if wanted.','events-made-easy')."</p></div>";
	}
?>

<div>
   <b><?php _e('Booking Pending Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email which will be sent to the respondent if the booking requires approval.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_registration_pending_email_subject_tpl'],'eme_prop_event_registration_pending_email_subject_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="eme_prop_event_registration_pending_email_subject" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <input type="text" maxlength="250" name="eme_prop_event_registration_pending_email_subject" id="eme_prop_event_registration_pending_email_subject" <?php echo $showhide_style; ?> value="<?php echo eme_esc_html($event['event_properties']['event_registration_pending_email_subject']);?>" />
   <br />
<?php
	if (eme_is_empty_string($event['event_registration_pending_email_body']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
	echo "<b>". __('Booking Pending Email Body', 'events-made-easy') . "</b>";
?>
   <p class="eme_smaller"><?php _e ( 'The body of the email which will be sent to the respondent if the booking requires approval.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_registration_pending_email_body_tpl'],'eme_prop_event_registration_pending_email_body_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="event_registration_pending_email_body_div" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <div id="event_registration_pending_email_body_div" <?php echo $showhide_style; ?>>
   <?php eme_wysiwyg_textarea("event_registration_pending_email_body", $event['event_registration_pending_email_body'], $use_html_editor, 0); ?> 
   </div>
</div>
<br />
<?php
	if (eme_is_empty_string($event['event_properties']['contactperson_registration_pending_email_subject']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
<div>
   <b><?php _e('Contact Person Pending Booking Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email which will be sent to the contact person if a booking requires approval.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['contactperson_registration_pending_email_subject_tpl'],'eme_prop_contactperson_registration_pending_email_subject_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="eme_prop_contactperson_registration_pending_email_subject" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <input type="text" maxlength="250" name="eme_prop_contactperson_registration_pending_email_subject" id="eme_prop_contactperson_registration_pending_email_subject" <?php echo $showhide_style; ?> value="<?php echo eme_esc_html($event['event_properties']['contactperson_registration_pending_email_subject']);?>" />
   <br />
<?php
	if (eme_is_empty_string($event['event_properties']['contactperson_registration_pending_email_body']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
   <b><?php _e('Contact Person Pending Booking Email Body', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The body of the email which will be sent to the contact person if a booking requires approval.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['contactperson_registration_pending_email_body_tpl'],'eme_prop_contactperson_registration_pending_email_body_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="eme_prop_contactperson_registration_pending_email_body_div" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <div id="eme_prop_contactperson_registration_pending_email_body_div" <?php echo $showhide_style; ?>>
   <?php eme_wysiwyg_textarea("eme_prop_contactperson_registration_pending_email_body", $event['event_properties']['contactperson_registration_pending_email_body'], $use_html_editor, 0); ?> 
   </div>
</div>
<br />
<div>
<b><?php _e('Pending mail attachments', 'events-made-easy');?></b><br />
<span id="pending_attach_links"><?php
   $attachment_ids=$event['event_properties']['pending_attach_ids'];
   if (!empty($attachment_ids)) {
           $attachment_id_arr=explode(",",$attachment_ids);
           foreach ($attachment_id_arr as $attachment_id) {
		   $attach_link= eme_get_attachment_link($attachment_id);
		   if (!empty($attach_link)) {
			   echo $attach_link;
			   echo "<br \>";
                   }
	   }
   } else {
           $attachment_ids="";
   }
?></span>
<input type="hidden" name="eme_prop_pending_attach_ids" id="eme_pending_attach_ids" value="<?php echo $attachment_ids;?>">
<input type="button" name="pending_attach_button" id="pending_attach_button" value="<?php _e('Add attachments', 'events-made-easy');?>" class="button-secondary action">
<input type="button" name="pending_remove_attach_button" id="pending_remove_attach_button" value="<?php _e('Remove attachments', 'events-made-easy');?>" class="button-secondary action">
<br /><?php _e('Optionally add attachments to the mail when a booking is approved.','events-made-easy');?>
<br /><?php _e('Only fill this in if you want to override the default settings.','events-made-easy');?>
</div>
<?php
}

function eme_meta_box_div_event_registration_updated_email($event,$templates_array) {
	global $eme_plugin_url;
	$use_html_editor=get_option('eme_rsvp_send_html');
	if (eme_is_empty_string($event['event_properties']['event_registration_updated_email_subject']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
<div id="div_event_registration_updated_email">
   <b><?php _e('Booking Updated Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email which will be sent to the respondent if the booking has been updated by an admin.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_registration_updated_email_subject_tpl'],'eme_prop_event_registration_updated_email_subject_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="eme_prop_event_registration_updated_email_subject" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <input type="text" maxlength="250" name="eme_prop_event_registration_updated_email_subject" id="eme_prop_event_registration_updated_email_subject" <?php echo $showhide_style; ?> value="<?php echo eme_esc_html($event['event_properties']['event_registration_updated_email_subject']);?>" />
   <br />
<?php
	if (eme_is_empty_string($event['event_registration_updated_email_body']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
   <b><?php _e('Booking Updated Email Body', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The body of the email which will be sent to the respondent if the booking has been updated by an admin.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_registration_updated_email_body_tpl'],'eme_prop_event_registration_updated_email_body_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="event_registration_updated_email_body_div" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <div id="event_registration_updated_email_body_div" <?php echo $showhide_style; ?>>
   <?php eme_wysiwyg_textarea("event_registration_updated_email_body", $event['event_registration_updated_email_body'], $use_html_editor, 0); ?> 
   </div>
</div>
<?php
}

function eme_meta_box_div_event_registration_reminder_email($event,$templates_array) {
	global $eme_plugin_url;
	$use_html_editor=get_option('eme_rsvp_send_html');
	if (eme_is_empty_string($event['event_properties']['event_registration_pending_reminder_email_subject']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
<div id="div_event_registration_pending_reminder_email">
   <b><?php _e('Pending Booking Reminder Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email which will be sent to the respondent as a reminder of a pending booking.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_registration_pending_reminder_email_subject_tpl'],'eme_prop_event_registration_pending_reminder_email_subject_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="eme_prop_event_registration_pending_reminder_email_subject" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <input type="text" maxlength="250" name="eme_prop_event_registration_pending_reminder_email_subject" id="eme_prop_event_registration_pending_reminder_email_subject" <?php echo $showhide_style; ?> value="<?php echo eme_esc_html($event['event_properties']['event_registration_pending_reminder_email_subject']);?>" />
   <br />
<?php
	if (eme_is_empty_string($event['event_properties']['event_registration_pending_reminder_email_body']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
   <b><?php _e('Pending Booking Reminder Email Body', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The body of the email which will be sent to the respondent as a reminder of a pending booking.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_registration_pending_reminder_email_body_tpl'],'eme_prop_event_registration_pending_reminder_email_body_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="event_registration_pending_reminder_email_body_div" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <div id="event_registration_pending_reminder_email_body_div" <?php echo $showhide_style; ?>>
   <?php eme_wysiwyg_textarea("event_registration_pending_reminder_email_body", $event['event_properties']['event_registration_pending_reminder_email_body'], $use_html_editor, 0); ?> 
   </div>
</div>
   <br />
<div id="div_event_registration_reminder_email">
   <b><?php _e('Accepted Booking Reminder Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email which will be sent to the respondent as a reminder of an approved booking.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_registration_reminder_email_subject_tpl'],'eme_prop_event_registration_reminder_email_subject_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="eme_prop_event_registration_reminder_email_subject" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <input type="text" maxlength="250" name="eme_prop_event_registration_reminder_email_subject" id="eme_prop_event_registration_reminder_email_subject" <?php echo $showhide_style; ?> value="<?php echo eme_esc_html($event['event_properties']['event_registration_reminder_email_subject']);?>" />
   <br />
<?php
	if (eme_is_empty_string($event['event_properties']['event_registration_reminder_email_body']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
   <b><?php _e('Accepted Booking Reminder Email Body', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The body of the email which will be sent to the respondent as a reminder of an approved booking.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_registration_reminder_email_body_tpl'],'eme_prop_event_registration_reminder_email_body_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="event_registration_reminder_email_body_div" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <div id="event_registration_reminder_email_body_div" <?php echo $showhide_style; ?>>
   <?php eme_wysiwyg_textarea("event_registration_reminder_email_body", $event['event_properties']['event_registration_reminder_email_body'], $use_html_editor, 0); ?> 
   </div>
</div>
<?php
}

function eme_meta_box_div_event_registration_cancelled_email($event,$templates_array) {
	global $eme_plugin_url;
	$use_html_editor=get_option('eme_rsvp_send_html');
	if (eme_is_empty_string($event['event_properties']['event_registration_cancelled_email_subject']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
<div>
   <b><?php _e('Booking Cancelled Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email which will be sent to the respondent when he cancels all his bookings for an event.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_registration_cancelled_email_subject_tpl'],'eme_prop_event_registration_cancelled_email_subject_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="eme_prop_event_registration_cancelled_email_subject" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <input type="text" maxlength="250" name="eme_prop_event_registration_cancelled_email_subject" id="eme_prop_event_registration_cancelled_email_subject" <?php echo $showhide_style; ?> value="<?php echo eme_esc_html($event['event_properties']['event_registration_cancelled_email_subject']);?>" />
   <br />
<?php
	if (eme_is_empty_string($event['event_registration_cancelled_email_body']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
   <b><?php _e('Booking Cancelled Email Body', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The body of the email which will be sent to the respondent when he cancels all his bookings for an event.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_registration_cancelled_email_body_tpl'],'eme_prop_event_registration_cancelled_email_body_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="event_registration_cancelled_email_body_div" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <div id="event_registration_cancelled_email_body_div" <?php echo $showhide_style; ?>>
   <?php eme_wysiwyg_textarea("event_registration_cancelled_email_body", $event['event_registration_cancelled_email_body'], $use_html_editor, 0); ?> 
   </div>
</div>
<br />
<?php
	if (eme_is_empty_string($event['event_properties']['contactperson_registration_cancelled_email_subject']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
<div>
   <b><?php _e('Contact Person Cancelled Booking Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email which will be sent to the contact person when a respondent cancels all his bookings for an event.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['contactperson_registration_cancelled_email_subject_tpl'],'eme_prop_contactperson_registration_cancelled_email_subject_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="eme_prop_contactperson_registration_cancelled_email_subject" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <input type="text" maxlength="250" name="eme_prop_contactperson_registration_cancelled_email_subject" id="eme_prop_contactperson_registration_cancelled_email_subject" <?php echo $showhide_style; ?> value="<?php echo eme_esc_html($event['event_properties']['contactperson_registration_cancelled_email_subject']);?>" />
   <br />
<?php
	if (eme_is_empty_string($event['event_properties']['contactperson_registration_cancelled_email_body']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
   <b><?php _e('Contact Person Cancelled Booking Email Body', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The body of the email which will be sent to the contact person when a respondent cancels all his bookings for an event.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['contactperson_registration_cancelled_email_body_tpl'],'eme_prop_contactperson_registration_cancelled_email_body_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="eme_prop_contactperson_registration_cancelled_email_body_div" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <div id="eme_prop_contactperson_registration_cancelled_email_body_div" <?php echo $showhide_style; ?>>
   <?php eme_wysiwyg_textarea("eme_prop_contactperson_registration_cancelled_email_body", $event['event_properties']['contactperson_registration_cancelled_email_body'], $use_html_editor, 0); ?> 
   </div>
</div>
<?php
}

function eme_meta_box_div_event_registration_paid_email($event,$templates_array) {
	global $eme_plugin_url;
	$use_html_editor=get_option('eme_rsvp_send_html');
	if (eme_is_empty_string($event['event_properties']['event_registration_paid_email_subject']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
	if (!get_option('eme_rsvp_mail_notify_is_active')) {
		print "<div class='info eme-message-admin'><p>".__('RSVP notifications are not activated, so these mails will not be sent. Go in the Mail settings to activate this if wanted.','events-made-easy')."</p></div>";
	} elseif (!get_option('eme_rsvp_mail_notify_paid')) {
		print "<div class='info eme-message-admin'><p>".__('RSVP notifications are not activated for paid bookings, so these mails will not be sent. Go in the Mail settings to activate this if wanted.','events-made-easy')."</p></div>";
	}
?>
<div>
   <br />
   <b><?php _e('Booking Paid Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email which will be sent to the respondent when a booking is marked as paid.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_registration_paid_email_subject_tpl'],'eme_prop_event_registration_paid_email_subject_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="eme_prop_event_registration_paid_email_subject" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <input type="text" maxlength="250" name="eme_prop_event_registration_paid_email_subject" id="eme_prop_event_registration_paid_email_subject" <?php echo $showhide_style; ?> value="<?php echo eme_esc_html($event['event_properties']['event_registration_paid_email_subject']);?>" />
   <br />
<?php
	if (eme_is_empty_string($event['event_registration_paid_email_body']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
   <b><?php _e('Booking Paid Email Body', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The body of the email which will be sent to the respondent when a booking is marked as paid.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_registration_paid_email_body_tpl'],'eme_prop_event_registration_paid_email_body_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="event_registration_paid_email_body_div" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <div id="event_registration_paid_email_body_div" <?php echo $showhide_style; ?>>
   <?php eme_wysiwyg_textarea("event_registration_paid_email_body", $event['event_registration_paid_email_body'], $use_html_editor, 0); ?> 
   </div>
</div>
<br >
<?php
	if (eme_is_empty_string($event['event_properties']['contactperson_registration_paid_email_subject']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
<div>
   <b><?php _e('Contact Person Booking Paid Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email which will be sent to the contact person when a booking is marked as paid (not via a payment gateway).','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['contactperson_registration_paid_email_subject_tpl'],'eme_prop_contactperson_registration_paid_email_subject_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="eme_prop_contactperson_registration_paid_email_subject" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <input type="text" maxlength="250" name="eme_prop_contactperson_registration_paid_email_subject" id="eme_prop_contactperson_registration_paid_email_subject" <?php echo $showhide_style; ?> value="<?php echo eme_esc_html($event['event_properties']['contactperson_registration_paid_email_subject']);?>" />
   <br />
<?php
	if (eme_is_empty_string($event['event_properties']['contactperson_registration_paid_email_body']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
	echo "<b>". __('Contact Person Booking Paid Email Body', 'events-made-easy') . "</b>";
?>
   <p class="eme_smaller"><?php _e ( 'The body of the email which will be sent to the contact person when a booking is marked as paid (not via a payment gateway).','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['contactperson_registration_paid_email_body_tpl'],'eme_prop_contactperson_registration_paid_email_body_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="eme_prop_contactperson_registration_paid_email_body_div" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <div id="eme_prop_contactperson_registration_paid_email_body_div" <?php echo $showhide_style; ?>>
   <?php eme_wysiwyg_textarea("eme_prop_contactperson_registration_paid_email_body", $event['event_properties']['contactperson_registration_paid_email_body'], $use_html_editor, 0); ?> 
   </div>
</div>
<br />
<b><?php _e('Booking paid mail attachments', 'events-made-easy');?></b><br />
<span id="paid_attach_links"><?php
   $attachment_ids=$event['event_properties']['paid_attach_ids'];
   if (!empty($attachment_ids)) {
           $attachment_id_arr=explode(",",$attachment_ids);
           foreach ($attachment_id_arr as $attachment_id) {
		   $attach_link= eme_get_attachment_link($attachment_id);
		   if (!empty($attach_link)) {
			   echo $attach_link;
			   echo "<br \>";
                   }
	   }
   } else {
           $attachment_ids="";
   }
?></span>
<input type="hidden" name="eme_prop_paid_attach_ids" id="eme_paid_attach_ids" value="<?php echo $attachment_ids;?>">
<input type="button" name="paid_attach_button" id="paid_attach_button" value="<?php _e('Add attachments', 'events-made-easy');?>" class="button-secondary action">
<input type="button" name="paid_remove_attach_button" id="paid_remove_attach_button" value="<?php _e('Remove attachments', 'events-made-easy');?>" class="button-secondary action">
<br /><?php _e('Optionally add attachments to the mail when a booking is paid.','events-made-easy');?>
<br /><?php _e('Only fill this in if you want to override the default settings.','events-made-easy');?>
<?php
}

function eme_meta_box_div_event_registration_trashed_email($event,$templates_array) {
	global $eme_plugin_url;
	$use_html_editor=get_option('eme_rsvp_send_html');
	if (eme_is_empty_string($event['event_properties']['event_registration_trashed_email_subject']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
<div id="div_event_registration_trashed_email">
   <b><?php _e('Booking Deleted Email Subject', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The subject of the email which will be sent to the respondent if the booking is deleted by an admin.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_registration_trashed_email_subject_tpl'],'eme_prop_event_registration_trashed_email_subject_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="eme_prop_event_registration_trashed_email_subject" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <input type="text" maxlength="250" name="eme_prop_event_registration_trashed_email_subject" id="eme_prop_event_registration_trashed_email_subject" <?php echo $showhide_style; ?> value="<?php echo eme_esc_html($event['event_properties']['event_registration_trashed_email_subject']);?>" />
   <br />
<?php
	if (eme_is_empty_string($event['event_registration_trashed_email_body']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
   <b><?php _e('Booking Deleted Email Body', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The body of the email which will be sent to the respondent if the booking is deleted by an admin.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_registration_trashed_email_body_tpl'],'eme_prop_event_registration_trashed_email_body_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="event_registration_trashed_email_body_div" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <div id="event_registration_trashed_email_body_div" <?php echo $showhide_style; ?>>
   <?php eme_wysiwyg_textarea("event_registration_trashed_email_body", $event['event_registration_trashed_email_body'], $use_html_editor, 0); ?> 
   </div>
</div>
<?php
}

function eme_meta_box_div_event_registration_form_format($event,$templates_array) {
	global $eme_plugin_url;
	if (eme_is_empty_string($event['event_registration_form_format']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
<div id="div_event_registration_form_format">
   <b><?php _e('Booking Form', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The booking form format.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_registration_form_format_tpl'],'eme_prop_event_registration_form_format_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="event_registration_form_format_div" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <div id="event_registration_form_format_div" <?php echo $showhide_style; ?>>
   <?php eme_wysiwyg_textarea("event_registration_form_format", $event['event_registration_form_format'], 1, 0); ?> 
   </div>
</div>
<?php
}

function eme_meta_box_div_event_cancel_form_format($event,$templates_array) {
	global $eme_plugin_url;
	if (eme_is_empty_string($event['event_cancel_form_format']))
		$showhide_style='style="display:none; width:100%;"';
	else
		$showhide_style='style="width:100%;"';
?>
<div id="div_event_cancel_form_format">
      <br />
   <b><?php _e('Cancel Booking Form', 'events-made-easy'); ?></b>
   <p class="eme_smaller"><?php _e ( 'The cancel booking form format.','events-made-easy');?>
   <br />
   <?php _e ('Only fill this in if you want to override the default settings.', 'events-made-easy');?>
   </p>
   <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['event_cancel_form_format_tpl'],'eme_prop_event_cancel_form_format_tpl',$templates_array); ?><br />
   <?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
	<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="event_cancel_form_format_div" style="cursor: pointer; vertical-align: middle; ">
   <br />
   <div id="event_cancel_form_format_div" <?php echo $showhide_style; ?>>
   <?php eme_wysiwyg_textarea("event_cancel_form_format", $event['event_cancel_form_format'], 1, 0); ?> 
   </div>
</div>
<?php
}

function eme_meta_box_div_event_captcha_settings($event) {
   global $eme_plugin_url;
   $eme_prop_use_captcha = ($event['event_properties']['use_captcha']) ? "checked='checked'" : "";
   $eme_prop_use_recaptcha = ($event['event_properties']['use_recaptcha']) ? "checked='checked'" : "";
   $eme_prop_use_hcaptcha = ($event['event_properties']['use_hcaptcha']) ? "checked='checked'" : "";
?>
<div id="div_event_captcha_settings">
      <br />
   <b><?php _e('Captcha settings', 'events-made-easy'); ?></b>
<?php if (!empty(get_option('eme_recaptcha_for_forms')) && !empty(get_option('eme_recaptcha_site_key'))): ?>
	<p id='p_use_recaptcha'>
        <input id="eme_prop_use_recaptcha" name='eme_prop_use_recaptcha' value='1' type='checkbox' <?php echo $eme_prop_use_recaptcha; ?> />
        <label for="eme_prop_use_recaptcha"><?php _e ( 'Use Google reCAPTCHA for forms?','events-made-easy'); ?></label>
        <span class="eme_smaller"><br /><?php _e ( 'If this option is checked, make sure to use #_RECAPTCHA in your booking/cancel form. If not present, it will be added just above the submit button.','events-made-easy'); ?></span>
        </p>
<?php endif; ?>
<?php if (!empty(get_option('eme_hcaptcha_for_forms')) && !empty(get_option('eme_hcaptcha_site_key'))): ?>
	<p id='p_use_hcaptcha'>
        <input id="eme_prop_use_hcaptcha" name='eme_prop_use_hcaptcha' value='1' type='checkbox' <?php echo $eme_prop_use_hcaptcha; ?> />
        <label for="eme_prop_use_hcaptcha"><?php _e ( 'Use hCaptcha for forms?','events-made-easy'); ?></label>
        <span class="eme_smaller"><br /><?php _e ( 'If this option is checked, make sure to use #_HCAPTCHA in your booking/cancel form. If not present, it will be added just above the submit button.','events-made-easy'); ?></span>
        </p>
<?php endif; ?>
	<p id='p_use_captcha'>
        <input id="eme_prop_use_captcha" name='eme_prop_use_captcha' value='1' type='checkbox' <?php echo $eme_prop_use_captcha; ?> />
        <label for="eme_prop_use_captcha"><?php _e ( 'Use EME captcha for forms?','events-made-easy'); ?></label>
        <span class="eme_smaller"><br /><?php _e ( 'If this option is checked, make sure to use #_CAPTCHA in your booking/cancel form. If not present, it will be added just above the submit button.','events-made-easy'); ?></span>
        </p>
</div>
<?php
}

function eme_meta_box_div_event_location($event) {
   // qtranslate there? Then we need the select, otherwise locations will be created again...
   if (function_exists('qtrans_getLanguage') ||
	   function_exists('ppqtrans_getLanguage') ||
	   function_exists('qtranxf_getLanguage') ||
	   function_exists('pll_current_language') ||
	   function_exists('mqtranslate_conf')
   ) {
      $use_select_for_locations=1;
   } else {
      $use_select_for_locations = get_option('eme_use_select_for_locations');
   }
   $map_is_active = get_option('eme_map_is_active' );

   // we get the location using the location id from the event, but later down the road
   // we use the location_id from the location
   // Reason: if someone deleted a location and didn't update the corresponding events, the event would
   // point to an nonexisting location. If we passed down that deleted location_id, it would prevent location updates for such an event
   // eme_get_location takes into account removed locations and just gives location_id=0 in case of a removed location
   $location = eme_get_location ( $event['location_id'] );
   if (empty($location))
      $location = eme_new_location();
?>
   <?php
   if($use_select_for_locations) {
      $location_0 = eme_new_location();
      $location_0['location_id']=0;
      $location_0['location_name']=' ';
      // use eme_get_all_locations, eme_get_locations in the backend would only return locations you're entitled to change, but for a select this is a non-issue
      $locations = eme_get_all_locations();
   ?>
   <div id="div_location_name">
   <table id="eme-location-data">
      <tr>
      <th><?php _e('Location','events-made-easy') ?></th>
      <td> 
      <select name="location-select-id" id='location-select-id' size="1">
      <option value="<?php echo $location_0['location_id'] ?>" ><?php echo eme_trans_esc_html($location_0['location_name']); ?></option>
      <?php 
      $selected_location=$location_0;
      foreach($locations as $tmp_location) {
         $selected = "";
         if (isset($location['location_id']) && $tmp_location['location_id'] == $location['location_id']) {
            $selected_location=$location;
            $selected = "selected='selected' ";
         }
         ?>
         <option value="<?php echo $tmp_location['location_id']; ?>" <?php echo $selected ?>><?php echo eme_trans_esc_html($tmp_location['location_name']); ?></option>
      <?php
      }
      ?>
      </select>
      <input type='hidden' name='location-select-name' value='<?php echo eme_trans_esc_html($selected_location['location_name'])?>' />
      <input type='hidden' name='location-select-city' value='<?php echo eme_trans_esc_html($selected_location['location_city'])?>' />
      <input type='hidden' name='location-select-address1' value='<?php echo eme_trans_esc_html($selected_location['location_address1'])?>' />
      <input type='hidden' name='location-select-address2' value='<?php echo eme_trans_esc_html($selected_location['location_address2'])?>' />
      <input type='hidden' name='location-select-state' value='<?php echo eme_trans_esc_html($selected_location['location_state'])?>' />
      <input type='hidden' name='location-select-zip' value='<?php echo eme_trans_esc_html($selected_location['location_zip'])?>' />
      <input type='hidden' name='location-select-country' value='<?php echo eme_trans_esc_html($selected_location['location_country'])?>' />
      <input type='hidden' name='location-select-latitude' value='<?php echo eme_trans_esc_html($selected_location['location_latitude'])?>' />
      <input type='hidden' name='location-select-longitude' value='<?php echo eme_trans_esc_html($selected_location['location_longitude'])?>' />
      </td>
      <?php
      if ($map_is_active) {
      ?>
         <td>
         <div id='eme-admin-map-not-found'>
         <p>
         <?php _e ( 'Map not found','events-made-easy'); ?>
         </p>
         </div>
         <div id='eme-admin-location-map'></div></td>
      <?php
      }
      ?>
      </tr>
    </table>
    </div>
   <?php
   } else {
      eme_meta_box_div_location_name_for_event($location);
      eme_meta_box_div_location_details($location);
      eme_meta_box_div_location_url($location);
   }
}
 
function eme_meta_box_div_event_notes($event) {
   $eme_editor_settings = eme_get_editor_settings();
?>
<div id="div_event_notes">
      <br />
      <?php
	echo "<b>". __('Event description', 'events-made-easy') . "</b>";
      ?>
   <p class="eme_smaller"><?php _e ( 'The event description. This is also used in html meta tags and google tags to show the event info.', 'events-made-easy')?></p>
   <div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
   <!-- we need content for qtranslate as ID -->
   <?php wp_editor($event['event_notes'],"content",$eme_editor_settings); ?>
   <?php if (current_user_can('unfiltered_html')) {
            echo "<div class='eme_notice_unfiltered_html'>";
            _e('Your account has the ability to post unrestricted HTML content here, except javascript.', 'events-made-easy');
            echo "</div>";
          }
   ?>
   </div>
</div>
<?php
}

function eme_meta_box_div_event_image($event) {
    if (!empty($event['event_image_id']))
       $event['event_image_url'] = esc_url(wp_get_attachment_image_url($event['event_image_id'],'full'));
?>
<div id="div_event_image" class="postarea">
   <h3>
      <?php _e('Event image', 'events-made-easy') ?>
   </h3>
   <?php if (!empty($event['event_image_url'])) {
      echo "<img id='eme_event_image_example' alt='". __('Event image','events-made-easy')."' src='".$event['event_image_url']."' width='200' />";
      echo "<input type='hidden' name='event_image_url' id='event_image_url' value='".$event['event_image_url']."' />";
   } else {
      echo "<img id='eme_event_image_example' src='' alt='". __('Event image','events-made-easy')."' width='200' />";
      echo "<input type='hidden' name='event_image_url' id='event_image_url' />";
   }
   if (!empty($event['event_image_id'])) {
      echo "<input type='hidden' name='event_image_id' id='event_image_id' value='".$event['event_image_id']."' />";
   } else {
      echo "<input type='hidden' name='event_image_id' id='event_image_id' />";
   }
   ?>
   <div class="uploader">
   <input type="button" name="event_image_button" id="event_image_button" value="<?php _e ( 'Set a featured image', 'events-made-easy')?>" class="button-secondary action" />
   <input type="button" id="event_remove_image_button" name="event_remove_image_button" value=" <?php _e ( 'Unset featured image', 'events-made-easy')?>" class="button-secondary action" />
   </div>
</div>
<?php
}

function eme_meta_box_div_event_attributes($event) {
?>
<div id="div_event_attributes">
      <br />
      <?php
	echo "<b>". __('Attributes', 'events-made-easy') . "</b>";
      ?>
<?php
    eme_attributes_form($event);
?>
</div>
<?php
}

function eme_meta_box_div_event_customfields($event) {
?>
<div id="div_event_customfields">
      <br /><b> <?php echo __('Custom fields', 'events-made-easy'); ?> </b>
      <p><?php echo __("Here custom fields of type 'events' are shown.", 'events-made-easy'); ?>
      <br /><?php echo __("The difference with event attributes is that attributes need to be defined in your format first and can only be text, here you can first create custom fields of any kind which allows more freedom.", 'events-made-easy'); ?>
      </p>
               <?php if (current_user_can('unfiltered_html')) {
                       echo "<div class='eme_notice_unfiltered_html'>";
                       _e('Your account has the ability to post unrestricted HTML content here, except javascript.', 'events-made-easy');
                       echo "</div>";
                     }
               ?>
      <table style='width: 100%;'>
<?php
	$formfields=eme_get_formfields('','events');
        $formfields=apply_filters('eme_event_formfields', $formfields);
	// only in case of event duplicate, the cf_answers is set
	if (isset($event['cf_answers'])) {
		$answers=$event['cf_answers'];
	} elseif (!empty($event['event_id'])) {
		$answers=eme_get_event_cf_answers($event['event_id']);
	} else {
		$answers=array();
	}

	foreach ($formfields as $formfield) {
		$field_name = eme_trans_esc_html($formfield['field_name']);
		$field_id = $formfield['field_id'];
		$postfield_name="FIELD".$field_id;
		$entered_val='';
		foreach ($answers as $answer) {
			if ($answer['field_id'] == $field_id) {
				$entered_val = $answer['answer'];
			}
		}
		if ($formfield['field_required'])
			$required=1;
		else
			$required=0;
		if ($formfield['field_type'] == 'file') {
			$field_html = __("File upload is not allowed here, use the regular WP media library to upload files or use the 'Add media' button in the event notes.","events-made-easy");
		} elseif ($formfield['field_type'] == 'hidden') {
			$field_html = __("Custom fields of type 'hidden' are useless here and of course won't be shown.","events-made-easy");
		} else {
			$field_html = eme_get_formfield_html($formfield,$postfield_name,$entered_val,$required);
		}
		echo "<tr><td>$field_name</td><td style='width: 100%;'>$field_html</td></tr>";
	}
?>
      </table>
</div>
<?php
}

function eme_meta_box_div_event_url($event) {
?>
<div id="div_event_url" class="postarea">
   <h3>
      <?php _e('External URL', 'events-made-easy') ?>
   </h3>
   <input type="url" id="event_url" name="event_url" autocomplete="off" value="<?php echo eme_esc_html($event['event_url']); ?>" />
   <br />
   <p class="eme_smaller"><?php _e ( 'If this is filled in, the single event URL will point to this url instead of the standard event page.', 'events-made-easy')?></p>
</div>
<?php
}

function eme_meta_box_div_event_rsvp_enabled($event) {
?>
                     <div class="inside">
                        <p id='p_rsvp'>
<?php echo eme_ui_checkbox_binary($event['event_rsvp'],"event_rsvp", __('Enable bookings for this event','events-made-easy')); ?>
                        </p>
                     </div>
<?php
}

function eme_meta_box_div_event_payment_methods($event) {
   $eme_prop_skippaymentoptions = ($event['event_properties']['skippaymentoptions']) ? "checked='checked'" : "";
?>
			<div id="div_event_payment_methods">
			   <p id='span_payment_methods_explain'>
<?php
   _e('If no payment method is selected, the "Booking Recorded Message" (defined in the RSVP Form format settings) will be shown. Otherwise the "Booking Recorded Message" will be shown and after some seconds the user gets redirected to the payment page (see the generic EME settings on the redirection timeout and more payment settings).','events-made-easy');
?>
			   </p>
			   <p id='span_payment_methods'>
<?php 
   $found_methods = 0;
   if (!empty(get_option('eme_paypal_clientid'))) {
	   echo eme_ui_checkbox_binary($event['event_properties']['use_paypal'],"eme_prop_use_paypal", __('Paypal','events-made-easy'));
	   echo "<br />";
	   $found_methods++;
   }
   if (!empty(get_option('eme_legacypaypal_business'))) {
	   echo eme_ui_checkbox_binary($event['event_properties']['use_legacypaypal'],"eme_prop_use_legacypaypal", __('Legacy Paypal','events-made-easy'));
	   echo "<br />";
	   $found_methods++;
   }
   if (!empty(get_option('eme_2co_business'))) {
	   echo eme_ui_checkbox_binary($event['event_properties']['use_2co'],"eme_prop_use_2co",__('2Checkout','events-made-easy'));
	   echo "<br />";
	   $found_methods++;
   }
   if (!empty(get_option('eme_webmoney_purse'))) {
	   echo eme_ui_checkbox_binary($event['event_properties']['use_webmoney'],"eme_prop_use_webmoney", __('Webmoney','events-made-easy'));
	   echo "<br />";
	   $found_methods++;
   }
   if (!empty(get_option('eme_fdgg_store_name'))) {
	   echo eme_ui_checkbox_binary($event['event_properties']['use_fdgg'],"eme_prop_use_fdgg",__( 'First Data','events-made-easy'));
	   echo "<br />";
	   $found_methods++;
   }
   if (!empty(get_option('eme_mollie_api_key'))) {
	   echo eme_ui_checkbox_binary($event['event_properties']['use_mollie'],"eme_prop_use_mollie",__( 'Mollie','events-made-easy'));
	   echo "<br />";
	   $found_methods++;
   }
   if (!empty(get_option('eme_payconiq_api_key'))) {
	   echo eme_ui_checkbox_binary($event['event_properties']['use_payconiq'],"eme_prop_use_payconiq",__( 'Payconiq','events-made-easy'));
	   echo "<br />";
	   $found_methods++;
   }
   if (!empty(get_option('eme_worldpay_instid'))) {
	   echo eme_ui_checkbox_binary($event['event_properties']['use_worldpay'],"eme_prop_use_worldpay",__( 'Worldpay','events-made-easy'));
	   echo "<br />";
	   $found_methods++;
   }
   if (!empty(get_option('eme_stripe_private_key'))) {
	   echo eme_ui_checkbox_binary($event['event_properties']['use_stripe'],"eme_prop_use_stripe",__( 'Stripe','events-made-easy'));
	   echo "<br />";
	   $found_methods++;
   }
   if (!empty(get_option('eme_braintree_private_key'))) {
	   echo eme_ui_checkbox_binary($event['event_properties']['use_braintree'],"eme_prop_use_braintree",__( 'Braintree','events-made-easy'));
	   echo "<br />";
	   $found_methods++;
   }
   if (!empty(get_option('eme_instamojo_key'))) {
	   echo eme_ui_checkbox_binary($event['event_properties']['use_instamojo'],"eme_prop_use_instamojo",__( 'Instamojo','events-made-easy'));
	   echo "<br />";
	   $found_methods++;
   }
   if (!empty(get_option('eme_mercadopago_sandbox_token')) || !empty(get_option('eme_mercadopago_live_token'))) {
	   echo eme_ui_checkbox_binary($event['event_properties']['use_mercadopago'],"eme_prop_use_mercadopago",__( 'Mercado Pago','events-made-easy'));
	   echo "<br />";
	   $found_methods++;
   }
   if (!empty(get_option('eme_fondy_secret_key'))) {
	   echo eme_ui_checkbox_binary($event['event_properties']['use_fondy'],"eme_prop_use_fondy",__( 'Fondy','events-made-easy'));
	   echo "<br />";
	   $found_methods++;
   }
   if (!empty(get_option('eme_offline_payment'))) {
	   echo eme_ui_checkbox_binary($event['event_properties']['use_offline'],"eme_prop_use_offline",__( 'Offline','events-made-easy'));
	   echo "<br />";
	   $found_methods++;
   }
   if (!$found_methods) {
	   _e('No payment methods configured yet. Go in the EME payment settings and configure some.','events-made-easy');
   }
?>
			   </p>
			   <p id='span_skippaymentoptions'>
                              <?php _e ( 'Skip payment methods after booking','events-made-easy'); ?><br />
                              <input id="eme_prop_skippaymentoptions" name='eme_prop_skippaymentoptions' value='1' type='checkbox' <?php echo $eme_prop_skippaymentoptions; ?> />
			       <span class="eme_smaller"><?php _e ( "If you want to skip the possibility to pay immediately after booking, select this option. This might be useful if you for example want to approve unpaid bookings and only then send them the payment link using #_PAYMENT_URL in the booked email message.",'events-made-easy'); ?></span>
			   </p>
			</div>
<?php
}

function eme_meta_box_div_attendance_info($event,$templates_array) {
   global $eme_plugin_url;
   $eme_prop_attendancerecord = ($event['event_properties']['attendancerecord']) ? "checked='checked'" : "";
   if (eme_is_empty_string($event['event_properties']['attendance_unauth_scan_tpl']))
	   $showhide_style_unauth='style="display:none; width:100%;"';
   else
	   $showhide_style_unauth='style="width:100%;"';
   if (eme_is_empty_string($event['event_properties']['attendance_auth_scan_tpl']))
	   $showhide_style_auth='style="display:none; width:100%;"';
   else
	   $showhide_style_auth='style="width:100%;"';
?>
			<div id='div_event_attendance_info'>
			   <p id='p_attendancerecord'>
                              <input id="eme_prop_attendancerecord" name='eme_prop_attendancerecord' value='1' type='checkbox' <?php echo $eme_prop_attendancerecord; ?> />
                              <label for="eme_prop_attendancerecord"><?php _e ( 'Select this option if you want an attendance record to be kept everytime the RSVP attendance QRCODE is scanned by an authorized user.','events-made-easy'); ?></label>
			   </p>
			   <p id='span_attendance_limit'>
                              <?php _e ( 'Attendance URL (generated by #_ATTENDANCE_URL) is valid from ','events-made-easy'); ?>
			      <input id="eme_prop_attendance_begin" type="text" name="eme_prop_attendance_begin" maxlength='3' size='2' value="<?php echo $event['event_properties']['attendance_begin']; ?>" />
                              <?php _e ( 'hours before the event starts until ','events-made-easy'); ?>
			      <input id="eme_prop_attendance_end" type="text" name="eme_prop_attendance_end" maxlength='3' size='2' value="<?php echo $event['event_properties']['attendance_end']; ?>" />
                              <?php _e ( 'hours after the event ends.','events-made-easy'); ?>
			      <br /><span class="eme_smaller"><?php _e ( "When scanning the URL generated by #_QRCODE or #_ATTENDANCE_URL, you can also decide to use this as entry ticket. This option then allows to define from which point people are allowed to enter. EME will then also count the number of times the code is scanned by an authorized user and issue a warning is this count is greater than the number of booked seats.",'events-made-easy'); ?></span>
			   </p>

			   <div id='span_attendance_unauth_scan_format'>
				<b><?php _e ( 'Extra attendance info for not authorized users','events-made-easy'); ?></b>
				<br /><span class="eme_smaller"><?php _e ( "When the URL generated by #_QRCODE or #_ATTENDANCE_URL is scanned by a not authorized user, only info concerning the payment status is shown. If you want to show extra info (like the event name or some booking info), you can define that in this template. All event and RSVP placeholders are allowed.",'events-made-easy'); ?></span><br />
				<?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['attendance_unauth_scan_tpl'],'eme_prop_attendance_unauth_scan_tpl',$templates_array); ?><br />
				<?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
				<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="attendance_unauth_scan_format_div" style="cursor: pointer; vertical-align: middle; ">
				<br />
				<div id="attendance_unauth_scan_format_div" <?php echo $showhide_style_unauth; ?>>
   				<?php eme_wysiwyg_textarea("eme_prop_attendance_unauth_scan_format", $event['event_properties']['attendance_unauth_scan_format'], 1, 0); ?> 
			        </div>
			   </div>
			   <div id='span_attendance_auth_scan_format'>
				<b><?php _e ( 'Extra attendance info for authorized users','events-made-easy'); ?></b>
				<br /><span class="eme_smaller"><?php _e ( "When the URL generated by #_QRCODE or #_ATTENDANCE_URL is scanned by a authorized user, only info concerning the payment status is shown, next to attendance count info if configured to do so. If you want to show extra info (like the event name or some booking info), you can define that in this template. All event and RSVP placeholders are allowed.",'events-made-easy'); ?></span><br />
				<?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select($event['event_properties']['attendance_auth_scan_tpl'],'eme_prop_attendance_auth_scan_tpl',$templates_array); ?><br />
				<?php _e('Or enter your own (if anything is entered here, it takes precedence over the selected template): ','events-made-easy');?>
				<img src="<?php echo $eme_plugin_url;?>images/showhide.png" class="showhidebutton" alt="show/hide" data-showhide="attendance_auth_scan_format_div" style="cursor: pointer; vertical-align: middle; ">
				<br />
				<div id="attendance_auth_scan_format_div" <?php echo $showhide_style_auth; ?>>
   				<?php eme_wysiwyg_textarea("eme_prop_attendance_auth_scan_format", $event['event_properties']['attendance_auth_scan_format'], 1, 0); ?> 
			        </div>
			   </div>
			</div>
<?php
}
function eme_meta_box_div_event_rsvp($event) {
   global $eme_plugin_url;
   $currency_array = eme_currency_array();
   $event_number_seats=$event['event_seats'];
   $registration_wp_users_only = ($event['registration_wp_users_only']) ? "checked='checked'" : "";
   $registration_requires_approval = ($event['registration_requires_approval']) ? "checked='checked'" : "";

   $eme_prop_rsvp_discount = ($event['event_properties']['rsvp_discount']) ? $event['event_properties']['rsvp_discount'] : "";
   $eme_prop_rsvp_discountgroup = ($event['event_properties']['rsvp_discountgroup']) ? $event['event_properties']['rsvp_discountgroup'] : "";
   $eme_prop_rsvp_addpersontogroup = ($event['event_properties']['rsvp_addpersontogroup']) ? $event['event_properties']['rsvp_addpersontogroup'] : array();
   $eme_prop_rsvp_password = ($event['event_properties']['rsvp_password']) ? $event['event_properties']['rsvp_password'] : "";
   $eme_prop_waitinglist_seats = ($event['event_properties']['waitinglist_seats']) ? $event['event_properties']['waitinglist_seats'] : 0;
   $eme_prop_cancel_rsvp_days = $event['event_properties']['cancel_rsvp_days'];
   $eme_prop_cancel_rsvp_age = $event['event_properties']['cancel_rsvp_age'];
   $eme_prop_rsvp_pending_reminder_days = $event['event_properties']['rsvp_pending_reminder_days'];
   $eme_prop_rsvp_approved_reminder_days = $event['event_properties']['rsvp_approved_reminder_days'];

   $discount_arr=array();
   $dgroup_arr=array();
   $group_arr=array();
   if (!empty($eme_prop_rsvp_discount))
      $discount_arr=array($eme_prop_rsvp_discount=>eme_get_discount_name($eme_prop_rsvp_discount));
   if (!empty($eme_prop_rsvp_discountgroup))
      $dgroup_arr=array($eme_prop_rsvp_discountgroup=>eme_get_dgroup_name($eme_prop_rsvp_discountgroup));

   $pdftemplates = eme_get_templates('pdf',1);

?>
<div id="div_event_rsvp">
                           <p id='p_approval_required'>
                              <input id="approval_required-checkbox" name='registration_requires_approval' value='1' type='checkbox' <?php echo $registration_requires_approval; ?> />
                              <label for="approval_required-checkbox"><?php _e ( 'Require booking approval','events-made-easy'); ?></label>
   				<br />
<?php if (!get_option('eme_rsvp_mail_notify_pending')) {
                print "<span id='span_approval_required_mail_warning'><img style='vertical-align: middle;' src='".$eme_plugin_url."images/warning.png' alt='warning' />".__('RSVP notifications are not activated for pending bookings, so these mails will not be sent. Go in the Mail settings to activate this if wanted.','events-made-easy')."</span>";
			      }
?>
                           </p>
                           <p id='p_user_confirmation_required'>
			      <?php echo eme_ui_checkbox_binary($event['event_properties']['require_user_confirmation'],"eme_prop_require_user_confirmation", __('Require user confirmation after booking','events-made-easy'));?>
                              <span class="eme_smaller"><br /><?php _e ( "If active, don't forget to use #_BOOKING_CONFIRM_URL in the mail being sent to a booker.",'events-made-easy'); ?></span>
                           </p>
			   <p id='p_auto_approve'>
			      <?php echo eme_ui_checkbox_binary($event['event_properties']['auto_approve'],"eme_prop_auto_approve", __('Auto-approve booking upon payment','events-made-easy'));?>
                           </p>
			   <p id='p_ignore_pending'>
			      <?php echo eme_ui_checkbox_binary($event['event_properties']['ignore_pending'],"eme_prop_ignore_pending", __('Consider pending bookings as available seats for new bookings','events-made-easy').'<br />'.__('In case online payments are possible, pending bookings younger than 5 minutes will count as occupied too, to be able to allow people to finish online payments.','events-made-easy'));?>
                           </p>
                           <p id='p_rsvp_pending_reminder_days'>
                              <input id="eme_prop_rsvp_pending_reminder_days" name='eme_prop_rsvp_pending_reminder_days' type='text' value="<?php echo eme_esc_html($eme_prop_rsvp_pending_reminder_days); ?>" />
                              <label for="eme_prop_rsvp_pending_reminder_days"><?php _e('Set the number of days before reminder emails will be sent for pending bookings (counting from the start date of the event). If you want to send out multiple reminders, seperate the days here by commas. Leave empty for no reminder emails.','events-made-easy'); ?></label>
                           </p>
                           <p id='p_rsvp_approved_reminder_days'>
                              <input id="eme_prop_rsvp_approved_reminder_days" name='eme_prop_rsvp_approved_reminder_days' type='text' value="<?php echo eme_esc_html($eme_prop_rsvp_approved_reminder_days); ?>" />
                              <label for="eme_prop_rsvp_approved_reminder_days"><?php _e('Set the number of days before reminder emails will be sent for approved bookings (counting from the start date of the event). If you want to send out multiple reminders, seperate the days here by commas. Leave empty for no reminder emails.','events-made-easy'); ?></label>
                           </p>
			   <p id='p_wp_member_required'>
                              <input id="wp_member_required-checkbox" name='registration_wp_users_only' value='1' type='checkbox' <?php echo $registration_wp_users_only; ?> />
                              <label for="wp_member_required-checkbox"><?php _e ( 'Require WP membership for booking','events-made-easy'); ?></label>
                              <span class="eme_smaller"><br /><?php _e ( "This will only show the booking form for logged in users and prefill the form with the personal data from their wordpress profile. That data can't be changed in the form then, so if you don't want this, you can deactivate this option and use #_ADDBOOKINGFORM_IF_LOGGED_IN to show the form to logged in users only.",'events-made-easy'); ?></span>
                           </p>
			   <p id='p_create_wp_user'>
			      <?php echo eme_ui_checkbox_binary($event['event_properties']['create_wp_user'],"eme_prop_create_wp_user", __('Create WP user after succesful booking','events-made-easy'));?>
                              <span class="eme_smaller"><br /><?php _e ( "This will create a WP user after the booking is completed, as if the person registered in WP itself. This will only create a user if the booker was not logged in and the email is not yet taken by another WP user.",'events-made-easy'); ?></span>
                           </p>
			   <p id='p_email_only_once'>
			      <?php echo eme_ui_checkbox_binary($event['event_properties']['email_only_once'],"eme_prop_email_only_once", __('Allow only 1 booking per unique email address','events-made-easy'));?>
			   </p>
			   <p id='p_person_only_once'>
			      <?php echo eme_ui_checkbox_binary($event['event_properties']['person_only_once'],"eme_prop_person_only_once", __('Allow only 1 booking per person (combo email/last name/first name)','events-made-easy'));?>
			   </p>
			   <table class="eme_event_admin_table">
                              <tr id='row_seats'>
                              <td><label for='seats-input'><?php _e ( 'Seats','events-made-easy'); ?> :</label></td>
                              <td><input id="seats-input" type="text" name="event_seats" size='8' title="<?php echo __('Enter 0 for no limit','events-made-easy')."\n".__('For multiseat events, separate the values by \'||\'','events-made-easy'); ?>" value="<?php echo eme_esc_html($event_number_seats); ?>" />
                              <span class="eme_smaller"><br /><?php echo __('The max available seats for this event. Enter 0 for no limit. For multiseat events, separate the values by \'||\'','events-made-easy'); ?></span>
                              </td>
                              </tr>
                              <tr id='row_price'>
                              <td><label for='price'><?php _e ( 'Price: ','events-made-easy'); ?></label></td>
                              <td><input id="price" type="text" name="price" size='8' title="<?php _e('For multiprice events, separate the values by \'||\'','events-made-easy'); ?>" value="<?php echo eme_esc_html($event['price']); ?>" />
                              <select id="currency" name="currency">
                              <?php
                                 foreach ( $currency_array as $key=>$value) {
                                    if ($event['currency'] && ($event['currency']==$key)) {
                                       $selected = "selected='selected'";
                                    } elseif (!$event['currency'] && ($key==get_option('eme_default_currency'))) {
                                       $selected = "selected='selected'";
                                    } else {
                                       $selected = "";
                                    }
                                    echo "<option value='$key' $selected>$value</option>";
                                 }
                              ?>
			      </select>
                              <span class="eme_smaller"><br /><?php _e('For multiprice events, separate the values by \'||\'','events-made-easy'); ?></span>
                              <span class="eme_smaller"><br /><?php _e('Use the point as decimal separator','events-made-easy'); ?></span>
                              </td>
                              </tr>
                              <tr id='row_price_desc'>
                              <td><label for='eme_prop_price_desc'><?php _e ( 'Price description','events-made-easy'); ?> :</label></td>
                              <td><input name="eme_prop_price_desc" id="eme_prop_price_desc" value="<?php echo eme_esc_html($event['event_properties']['price_desc']);?>" /><p class="eme_smaller"><?php _e('Add an optional description for the price (which can be used in templates).','events-made-easy'); ?></p></td>
                              </tr>
                              <tr id='row_multiprice_desc'>
                              <td><label for='eme_prop_multiprice_desc'><?php _e ( 'Price Categories descriptions','events-made-easy'); ?> :</label></td>
                              <td><textarea name="eme_prop_multiprice_desc" id="eme_prop_multiprice_desc" rows="6" ><?php echo str_replace('||',"\n",eme_esc_html($event['event_properties']['multiprice_desc']));?></textarea><p class="eme_smaller"><?php _e('Add an optional description for each price category (one price description per line).','events-made-easy'); ?></p></td>
                              </tr>
                              <tr id='row_vat'>
                              <td><label for='eme_prop_vat_pct'><?php _e ( 'VAT percentage: ','events-made-easy'); ?></label></td>
                              <td><input id="eme_prop_vat_pct" type="text" name="eme_prop_vat_pct" size='8' title="<?php _e('VAT percentage','events-made-easy'); ?>" value="<?php echo eme_esc_html($event['event_properties']['vat_pct']); ?>" />%
       				<br /><p class='eme_smaller'><?php  _e( 'The price you indicate for events is VAT included, special placeholders are foreseen to indicate the price without VAT.', 'events-made-easy'); ?></p>
                              </td>
                              </tr>
                              <tr id='row_discount'>
                              <td><label for='eme_prop_rsvp_discount'><?php _e ( 'Discount to apply','events-made-easy'); ?></label></td>
			      <td><?php echo eme_ui_select($event['event_properties']['rsvp_discount'], 'eme_prop_rsvp_discount', $discount_arr, '', 0,'eme_select2_discounts_class'); ?><p class="eme_smaller"><?php _e('The discount name you want to apply (is overridden by discount group if used).','events-made-easy'); ?></p></td>
                              </tr>
                              <tr id='row_discountgroup'>
                              <td><label for='eme_prop_rsvp_discountgroup'><?php _e ( 'Discount group to apply','events-made-easy'); ?></label></td>
			      <td><?php echo eme_ui_select($event['event_properties']['rsvp_discountgroup'], 'eme_prop_rsvp_discountgroup', $dgroup_arr, '', 0,'eme_select2_dgroups_class'); ?><p class="eme_smaller"><?php _e('The discount group name you want applied (overrides the discount).','events-made-easy'); ?></p></td>
                              </tr>
                              <tr id='row_waitinglist_seats'>
                              <td><label for='eme_prop_waitinglist_seats'><?php _e ( 'Waitinglist seats','events-made-easy'); ?></label></td>
			      <td><input id="eme_prop_waitinglist_seats" type="text" name="eme_prop_waitinglist_seats" size='8' title="<?php _e('The number of seats considered to be a waiting list.','events-made-easy'); ?>" value="<?php echo eme_esc_html($event['event_properties']['waitinglist_seats']); ?>" /><br /><span class="eme_smaller"><?php _e('The number of seats considered to be a waiting list.','events-made-easy'); ?></span></td>
                              </tr>
                              <tr id='row_check_free_waiting'>
                              <td><label for='eme_prop_check_free_waiting'><?php _e ( 'Check waitinglist when seats become available','events-made-easy'); ?></label></td>
			      <td><?php echo eme_ui_checkbox_binary($event['event_properties']['check_free_waiting'],"eme_prop_check_free_waiting");?>
                              <span class="eme_smaller"><br /><?php _e ( 'Automatically take a booking from the waiting list when seats become available again','events-made-easy'); ?></span></td>
                              </tr>
                              <tr id='row_max_allowed'>
                              <td><label for='eme_prop_max_allowed'><?php _e ( 'Max number of seats to book','events-made-easy'); ?></label></td>
                              <td><input id="eme_prop_max_allowed" type="text" name="eme_prop_max_allowed" size='8' title="<?php _e('The maximum number of seats a person can book in one go.','events-made-easy').' '._e('(is multi-compatible)','events-made-easy'); ?>" value="<?php echo eme_esc_html($event['event_properties']['max_allowed']); ?>" /><br /><span class="eme_smaller"><?php echo __('The maximum number of seats a person can book in one go.','events-made-easy').' '.__('(is multi-compatible)','events-made-easy').'<br />'.__('If the min and max number of seats to book are identical, then the field to choose the number of seats to book will be hidden.','events-made-easy'); ?></span></td>
                              </tr>
                              <tr id='row_min_allowed'>
                              <td><label for='eme_prop_min_allowed'><?php _e ( 'Min number of seats to book','events-made-easy'); ?></label></td>
                              <td><input id="eme_prop_min_allowed" type="text" name="eme_prop_min_allowed" size='8' title="<?php echo __('The minimum number of seats a person can book in one go (it can be 0, for e.g. just an attendee list).','events-made-easy').' '.__('(is multi-compatible)','events-made-easy'); ?>" value="<?php echo eme_esc_html($event['event_properties']['min_allowed']); ?>" /><br /><span class="eme_smaller"><?php echo __('The minimum number of seats a person can book in one go (it can be 0, for e.g. just an attendee list).','events-made-easy').' '.__('(is multi-compatible)','events-made-easy').'<br />'.__('If the min and max number of seats to book are identical, then the field to choose the number of seats to book will be hidden.','events-made-easy'); ?></span></td>
                              </tr>
                              <tr id='row_take_attendance'>
                              <td><label for='eme_prop_take_attendance'><?php _e ( 'Attendance-only event?','events-made-easy'); ?></label></td>
			      <td><?php echo eme_ui_checkbox_binary($event['event_properties']['take_attendance'],"eme_prop_take_attendance", __('Only take attendance (0 or 1 seat) for this event','events-made-easy'));?>
                              <span class="eme_smaller"><br /><?php _e ( 'If this option is set and the setting "Min number of seats to book" is set to a value greater than 0, then the field to choose the number of seats to book will be hidden.','events-made-easy'); ?>
                              </span></td>
                              </tr>
                              <tr id='row_addpersontogroup'>
                              <td><label for='eme_prop_rsvp_addpersontogroup'><?php _e ( 'Group to add people to','events-made-easy'); ?></label></td>
                              <td><?php echo eme_ui_multiselect_key_value($event['event_properties']['rsvp_addpersontogroup'], 'eme_prop_rsvp_addpersontogroup', eme_get_static_groups(),'group_id','name',5,'',0,'eme_select2_groups_class'); ?><p class="eme_smaller"><?php _e('The group you want people to automatically become a member of when they subscribe.','events-made-easy'); ?></p></td>
                              </tr>
                              <tr id='row_rsvppassword'>
                              <td><label for='eme_prop_rsvp_password'><?php _e ( 'RSVP Password','events-made-easy'); ?></label></td>
			      <td><input id="eme_prop_rsvp_password" type="text" class="eme_passwordfield" autocomplete='off' name="eme_prop_rsvp_password" size='20' value="<?php echo eme_esc_html($event['event_properties']['rsvp_password']); ?>" /><p class="eme_smaller"><?php _e('A password required for RSVP submit to succeed. If used, #_PASSWORD is required in the RSVP form too.','events-made-easy'); ?></p></td>
			      </tr>
                              </tr>
                              <tr id='row_inviteonly'>
                              <td><label for='eme_prop_invite_only'><?php _e ( 'Invite-only event?','events-made-easy'); ?></label></td>
			      <td><?php echo eme_ui_checkbox_binary($event['event_properties']['invite_only'],"eme_prop_invite_only", __('Require an invitation','events-made-easy'));?>
                              <span class="eme_smaller"><br /><?php _e ( 'Allow only bookings done if someone visits the event via the invite url generated by #_INVITEURL.','events-made-easy'); ?>
                              </span></td>
			      </tr>
                              <tr><td colspan="2">&nbsp;</td></tr>
                              <tr id='row_ticket'>
                              <td><label for='eme_prop_ticket_template_id'><?php _e ( 'Ticket PDF template','events-made-easy'); ?></label></td>
                              <td><?php echo eme_ui_select_key_value($event['event_properties']['ticket_template_id'],"eme_prop_ticket_template_id",$pdftemplates,'id','name','&nbsp;');?>
                                  <p class="eme_smaller"><?php _e ( 'This optional template is used to send a PDF attachment in the mail when the booking is approved or paid (see the next seting to configure when the attachment should be included).','events-made-easy'); ?><br />
                              <?php _e ( 'No template shown in the list? Then go in the section Templates and create a PDF template.','events-made-easy'); ?></p></td>
			      </tr>
                              <tr id='row_ticketmail'>
                              <td><label for='eme_prop_ticket_mail'><?php _e ( 'Ticket mail preference','events-made-easy'); ?></label></td>
                              <td><?php echo eme_ui_select($event['event_properties']['ticket_mail'],"eme_prop_ticket_mail",array('booking'=>__('At booking time','events-made-easy'),'approval'=>__('Upon approval','events-made-easy'),'payment'=>__('Upon payment','events-made-easy'),'always'=>__('All of the above','events-made-easy')));?>
                                  <p class="eme_smaller"><?php _e ( "Configure in which mail you want the optional PDF attachment to be included: when the booking is made, when it is approved or when the booking is paid for.",'events-made-easy'); ?><br />
			      </tr>
                              <tr><td colspan="2">&nbsp;</td></tr>
                              </table>
			   <p id='span_rsvp_allowed_from'>
                              <?php _e ( 'Allow RSVP from ','events-made-easy'); ?>
                              <input id="eme_prop_rsvp_start_number_days" type="text" name="eme_prop_rsvp_start_number_days" maxlength='4' size='4' value="<?php echo $event['event_properties']['rsvp_start_number_days']; ?>" />
                              <?php _e ( 'days','events-made-easy'); ?>
                              <input id="eme_prop_rsvp_start_number_hours" type="text" name="eme_prop_rsvp_start_number_hours" maxlength='3' size='2' value="<?php echo $event['event_properties']['rsvp_start_number_hours']; ?>" />
                              <?php _e ( 'hours','events-made-easy'); ?>
                              <?php _e ( 'before the event ','events-made-easy');
                                 $eme_rsvp_start_target_list = array('start'=>__('starts','events-made-easy'),'end'=>__('ends','events-made-easy'));
                                 echo eme_ui_select($event['event_properties']['rsvp_start_target'],'eme_prop_rsvp_start_target',$eme_rsvp_start_target_list);
                              ?>
                              &nbsp;<?php _e ('(Leave empty to disable this limit)','events-made-easy'); ?>
			   </p>
			   <p id='span_rsvp_allowed_until'>
                              <?php _e ( 'Allow RSVP until ','events-made-easy'); ?>
                              <input id="rsvp_number_days" type="text" name="rsvp_number_days" maxlength='4' size='4' value="<?php echo $event['rsvp_number_days']; ?>" />
                              <?php _e ( 'days','events-made-easy'); ?>
                              <input id="rsvp_number_hours" type="text" name="rsvp_number_hours" maxlength='4' size='4' value="<?php echo $event['rsvp_number_hours']; ?>" />
                              <?php _e ( 'hours','events-made-easy'); ?>
                              <?php _e ( 'before the event ','events-made-easy');
                                 $eme_rsvp_end_target_list = array('start'=>__('starts','events-made-easy'),'end'=>__('ends','events-made-easy'));
                                 echo eme_ui_select($event['event_properties']['rsvp_end_target'],'eme_prop_rsvp_end_target',$eme_rsvp_end_target_list);
                              ?>
			   </p>
			   <p id='span_rsvp_cutoff'>
                              <?php _e ( 'RSVP cancel cutoff before event starts','events-made-easy'); ?>
                              <input id="eme_prop_cancel_rsvp_days" type="text" name="eme_prop_cancel_rsvp_days" maxlength='4' size='4' value="<?php echo eme_esc_html($event['event_properties']['cancel_rsvp_days']); ?>" />
                              <span class="eme_smaller"><?php _e ( 'Allow RSVP cancellation until this many days before the event starts.', 'events-made-easy'); ?></span>
			      <br />
                              <?php _e ( 'RSVP cancel cutoff booking age','events-made-easy'); ?>
                              <input id="eme_prop_cancel_rsvp_age" type="text" name="eme_prop_cancel_rsvp_age" maxlength='3' size='2' value="<?php echo eme_esc_html($event['event_properties']['cancel_rsvp_age']); ?>" />
                              <span class="eme_smaller"><?php _e ( 'Allow RSVP cancellation until this many days after the booking has been made.', 'events-made-easy'); ?></span>
			   </p>
</div>
<?php
}

function eme_rss_link($justurl = 0, $echo = 0, $text = "RSS", $scope="future", $order = "ASC",$show_ongoing=1,$category='',$author='',$contact_person='',$limit=5, $location_id='',$title='') {
   if (strpos ( $justurl, "=" )) {
      // allows the use of arguments without breaking the legacy code
      $defaults = array ('justurl' => 0, 'show_ongoing' => $show_ongoing, 'echo' => $echo, 'text' => $text, 'scope' => $scope, 'order' => $order, 'category' => $category, 'author' => $author, 'contact_person' => $contact_person, 'limit' => $limit, 'location_id' => $location_id, 'title' => $title );
      
      $r = wp_parse_args ( $justurl, $defaults );
      extract ( $r );
      $echo = $r['echo'];
   }
   $echo = filter_var($echo,FILTER_VALIDATE_BOOLEAN);
   if ($text == '')
      $text = "RSS";
   $url = site_url ("/?eme_rss=main&scope=$scope&show_ongoing=$show_ongoing&order=$order&category=$category&author=$author&contact_person=$contact_person&limit=$limit&location_id=$location_id&title=".urlencode($title));
   $link = "<a href='$url'>$text</a>";
   
   if ($justurl)
      $result = $url;
   else
      $result = $link;
   if ($echo)
      echo $result;
   else
      return $result;
}

function eme_rss_link_shortcode($atts) {
   extract ( shortcode_atts ( array ('justurl' => 0, 'show_ongoing' => 1, 'text' => 'RSS', 'scope' => 'future', 'order' => 'ASC', 'category' => '', 'author' => '', 'contact_person' => '', 'limit' => 5, 'location_id' => '', 'title' => '' ), $atts ) );
   $result = eme_rss_link ( "justurl=$justurl&show_ongoing=$show_ongoing&text=$text&limit=$limit&scope=$scope&order=$order&category=$category&author=$author&contact_person=$contact_person&location_id=$location_id&title=".urlencode($title) );
   return $result;
}

function eme_rss() {
      global $eme_timezone;

      if (isset($_GET['limit'])) {
         $limit=intval($_GET['limit']);
      } else {
         $limit=get_option('eme_event_list_number_items' );
      }
      if (isset($_GET['author'])) {
         $author=eme_sanitize_request($_GET['author']);
      } else {
         $author="";
      }
      if (isset($_GET['contact_person'])) {
         $contact_person=eme_sanitize_request($_GET['contact_person']);
      } else {
         $contact_person="";
      }
      if (isset($_GET['order'])) {
         $order=eme_sanitize_request($_GET['order']);
      } else {
         $order="ASC";
      }
      if (isset($_GET['show_ongoing'])) {
         $show_ongoing=intval($_GET['show_ongoing']);
      } else {
         $show_ongoing=0;
      }
      if (isset($_GET['category'])) {
         $category=eme_sanitize_request($_GET['category']);
      } else {
         $category=0;
      }
      if (isset($_GET['location_id'])) {
         $location_id=eme_sanitize_request($_GET['location_id']);
      } else {
         $location_id='';
      }
      if (isset($_GET['scope'])) {
         $scope=eme_sanitize_request($_GET['scope']);
      } else {
         $scope="future";
      }
      if (isset($_GET['title'])) {
         $main_title=eme_sanitize_request($_GET['title']);
      } else {
         $main_title=get_option('eme_rss_main_title' );
      }
      
      header("Content-type: text/xml");
      echo "<?xml version='1.0'?>\n";
      
      ?>
<rss version="2.0">
<channel>
<title><?php
      echo eme_sanitize_rss($main_title);
      ?></title>
<link><?php
      $events_page_link = eme_get_events_page();
      echo eme_sanitize_rss($events_page_link);
      ?></link>
<description><?php
      echo eme_sanitize_rss(get_option('eme_rss_main_description' ));
      ?></description>
<docs>
http://blogs.law.harvard.edu/tech/rss
</docs>
<generator>
Weblog Editor 2.0
</generator>
<?php
      $title_format = get_option('eme_rss_title_format');
      $description_format = get_option('eme_rss_description_format');
      $events = eme_get_events ( $limit, $scope, $order, 0, $location_id, $category, $author, $contact_person, $show_ongoing );

      # some RSS readers don't like it when an empty feed without items is returned, so we add a dummy item then
      if (empty ( $events )) {
         echo "<item>\n";
         echo "<title></title>\n";
         echo "<link></link>\n";
         echo "</item>\n";
      } else {
         foreach ( $events as $event ) {
             $title = eme_sanitize_rss(eme_replace_event_placeholders ( $title_format, $event, "rss" ));
             $description = eme_sanitize_rss(eme_replace_event_placeholders ( $description_format, $event, "rss" ));
             $event_link = eme_sanitize_rss(eme_event_url($event));
	     if (!empty($event['event_image_id']))
                $image_url = esc_url(wp_get_attachment_image_url($event['event_image_id'],'full'));
	     elseif(!empty($event['event_image_url']))
                $image_url = esc_url($event['event_image_url']);
	     else
	        $image_url="";
             echo "<item>\n";
             echo "<title>$title</title>\n";
             echo "<link>$event_link</link>\n";
             if (get_option('eme_rss_show_pubdate' )) {
                if (get_option('eme_rss_pubdate_startdate' )) {
                   echo "<pubDate>".eme_rfc822_date($event['event_start'],$eme_timezone)."</pubDate>\n";
                } else {
                   echo "<pubDate>".eme_rfc822_date($event['modif_date'],"GMT")."</pubDate>\n";
                }
             }
             echo "<description>$description</description>\n";
             if (get_option('eme_categories_enabled')) {
                $categories = eme_sanitize_rss(eme_replace_event_placeholders ( "#_CATEGORIES", $event, "rss" ));
                echo "<category>$categories</category>\n";
             }
	     if (!empty($image_url)) {
                // the image title and link should in practice be the same as the event title and link
                echo "<image>\n";
                echo "<url>$image_url</url>\n";
                echo "<title>$title</title>\n";
                echo "<link>$event_link</link>\n";
                echo "</image>\n";
	     }
             echo "</item>\n";
         }
      }
      ?>
</channel>
</rss>
<?php
}

function eme_general_head() {
   $extra_html_header=get_option('eme_html_header');
   $extra_html_header=trim(preg_replace('/\r\n/', "\n", $extra_html_header));
   if (!empty($extra_html_header))
      echo $extra_html_header."\n";

   if (eme_is_single_event_page()) {
      $event_id=eme_sanitize_request(get_query_var('event_id'));
      $event=eme_get_event($event_id);
      if (empty($event)) {
	      return;
      }
      // I don't know if the canonical rel-link is needed, but since WP adds it by default ...
      $canon_url=eme_event_url($event);
      echo "<link rel=\"canonical\" href=\"$canon_url\" />\n";
      $extra_headers_format=trim(get_option('eme_event_html_headers_format'));
      $extra_headers=eme_extra_event_headers($event);
      if (eme_is_empty_string($extra_headers_format)) {
	      $extra_headers_format=$extra_headers;
      } else {
	      $extra_headers_format.="\n".$extra_headers;
      }
      if (!eme_is_empty_string($extra_headers_format)) {
            # we allow js in the header, so not too much escaping here
	    # but it needs to be text, otherwise html from the event might show in the headers ...
            $extra_header = eme_replace_event_placeholders($extra_headers_format, $event, "text");
	    # remove empty lines
	    $extra_header = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $extra_header);
            if ($extra_header != "")
               echo $extra_header."\n";
      }
   } elseif (eme_is_single_location_page()) {
      $location_id=eme_sanitize_request(get_query_var('location_id'));
      $location=eme_get_location($location_id);
      if (empty($location)) {
	      return;
      }
      $canon_url=eme_location_url($location);
      echo "<link rel=\"canonical\" href=\"$canon_url\" />\n";
      $extra_headers_format=trim(get_option('eme_location_html_headers_format'));
      if (!eme_is_empty_string($extra_headers_format)) {
            # we allow js in the header, so not too much escaping here
	    # but it needs to be text, otherwise html from the location might show in the headers ...
            $extra_header = eme_replace_locations_placeholders($extra_headers_format, $location, "text");
	    # remove empty lines
	    $extra_header = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $extra_header);
            if (!empty($extra_header))
               echo $extra_header."\n";
      }
   }
}

function eme_general_footer() {
   $extra_html_footer=get_option('eme_html_footer');
   $extra_html_footer=trim(preg_replace('/\r\n/', "\n", $extra_html_footer));
   if (!eme_is_empty_string($extra_html_footer))
      echo $extra_html_footer."\n";
}

function eme_sanitize_event($event) {
   global $eme_timezone;
   // remove possible unwanted fields
   if (isset($event['event_id'])) {
      unset($event['event_id']);
   }

   $eme_date_start_obj=new ExpressiveDate($event['event_start'],$eme_timezone);
   $eme_date_end_obj=new ExpressiveDate($event['event_end'],$eme_timezone);
   if (!empty($event['event_properties'])) {
	   $event_properties = $event['event_properties'];
	   if (isset($event_properties['all_day']) && $event_properties['all_day']) {
		   $event['event_start']=$eme_date_start_obj->startOfDay()->getDateTime();
		   $event['event_end']=$eme_date_end_obj->endOfDay()->getDateTime();
	   }
   } else {
	   $event_properties = array();
   }

   // some sanity checks
   if (!eme_is_datetime($event['event_start'])) {
	   $event['event_start'] = "";
   }
   if (eme_is_empty_datetime($event['event_end']) || $event['event_end']<$event['event_start']) {
      $event['event_end']=$event['event_start'];
   }
   if (!eme_is_empty_datetime($event['event_end']) && !eme_is_datetime($event['event_end'])) {
	   $event['event_end'] = $event['event_start'];
   }

   // if the end day/time is lower than the start day/time, then put
   // the end day one day ahead, but only if
   // the end time has been filled in, if it is empty then we keep
   // the end date as it is
   if (!eme_is_empty_datetime($event['event_end'])) {
      if ($eme_date_end_obj < $eme_date_start_obj) {
         $event['event_end']=$eme_date_start_obj->addOneDay()->getDateTime();
      }
   }

   if (isset($event['event_seats'])) {
	   if (eme_is_multi($event['event_seats'])) {
		   // waiting list only for simple events (no multiseats)
		   $event_properties['waitinglist_seats']=0;
		   $multiseat=eme_convert_multi2array($event['event_seats']);
		   foreach ($multiseat as $key=>$value) {
			   if (!is_numeric($value)) $multiseat[$key]=0;
		   }
		   $event['event_seats'] = eme_convert_array2multi($multiseat);
	   } else {
		   if (!is_numeric($event['event_seats'])) $event['event_seats'] = 0;
		   if (isset($event_properties['waitinglist_seats']))
			   $event_properties['waitinglist_seats']=intval($event_properties['waitinglist_seats']);
		   else
			   $event_properties['waitinglist_seats']=0;
		   if ($event_properties['waitinglist_seats']>=$event['event_seats'])
			   $event_properties['waitinglist_seats']=0;
	   }
   } else {
	   $event['event_seats'] = 0;
   }

   if (isset($event['price'])) {
	   if (eme_is_multi($event['price'])) {
		   $multiprice=eme_convert_multi2array($event['price']);
		   foreach ($multiprice as $key=>$value) {
			   if (!is_numeric($value)) $multiprice[$key]=0;
		   }
		   $event['price'] = eme_convert_array2multi($multiprice);
	   } else {
		   if (!is_numeric($event['price'])) $event['price'] = 0;
	   }
   } else {
	   $event['price'] = 0;
   }

   // check all variables that need to be urls
   $url_vars = array('event_url','event_image_url');
   foreach ($url_vars as $url_var) {
      if (!empty($event[$url_var])) {
           //make sure url's have a correct prefix
	   $parsed = parse_url($event[$url_var]);
	   if (empty($parsed['scheme'])) {
              $scheme  = is_ssl() ? 'https://' : 'http://';
              $event[$url_var] = $scheme . ltrim($event[$url_var], '/');
	   }
           //make sure url's are correctly escaped
	   $event[$url_var] = esc_url_raw ( $event[$url_var] ) ;
      }
   }

   if (!empty($event['event_slug']))
	$event['event_slug'] = eme_permalink_convert_noslash($event['event_slug']);
   else
	$event['event_slug'] = eme_permalink_convert_noslash($event['event_name']);

   // some things just need to be integers, let's brute-force them
   $int_vars=array('event_contactperson_id','event_author','event_tasks','event_rsvp','rsvp_number_days','rsvp_number_hours','registration_requires_approval','registration_wp_users_only','event_image_id');
   foreach ($int_vars as $int_var) {
	   if (isset($event[$int_var]))
		   $event[$int_var]=intval($event[$int_var]);
   }
 
   // make sure strings with only spaces are also empty strings
   $post_vars=array('event_name','event_page_title_format','event_single_event_format','event_contactperson_email_body','event_registration_recorded_ok_html','event_respondent_email_body','event_registration_pending_email_body','event_registration_updated_email_body','event_registration_cancelled_email_body','event_registration_trashed_email_body','event_registration_form_format','event_cancel_form_format','event_registration_paid_email_body');
   foreach ($post_vars as $post_var) {
	   if (eme_is_empty_string($event[$post_var])) $event[$post_var]='';
   }

   if (empty(get_option('eme_recaptcha_for_forms')) || empty(get_option('eme_recaptcha_site_key')))
	   $event_properties['use_recaptcha']=0;
   if (empty(get_option('eme_hcaptcha_for_forms')) || empty(get_option('eme_hcaptcha_site_key')))
	   $event_properties['use_hcaptcha']=0;

   if (!is_numeric($event_properties['vat_pct']) || $event_properties['vat_pct']<0 || $event_properties['vat_pct']>100)
	   $event_properties['vat_pct']=0;

   // the properties might have changed too
   $event['event_properties']=$event_properties;

   return $event;
}

function eme_db_insert_event($line,$event_is_part_of_recurrence=0,$day_difference=0) {
   global $wpdb, $eme_timezone;
   $table_name = $wpdb->prefix . EVENTS_TBNAME;

   if (empty($line['event_author']))
	   $line['event_author']=get_current_user_id();
   if (empty($line['event_contactperson_id']))
	   $line['event_contactperson_id']=$line['event_author'];
   if (empty($line['event_slug']))
	$line['event_slug'] = eme_permalink_convert_noslash($line['event_name']);
   $line['event_slug'] = eme_unique_slug($line['event_slug'],EVENTS_TBNAME,'event_slug','event_id');
   if (eme_is_empty_datetime($line['event_start']) && isset($line['event_start_date']) && isset($line['event_start_time'])) {
	   $line['event_start'] = $line['event_start_date'].' '.$line['event_start_time'];
   }
   if (eme_is_empty_datetime($line['event_end']) && isset($line['event_end_date']) && isset($line['event_end_time'])) {
	   $line['event_end'] = $line['event_end_date'].' '.$line['event_end_time'];
   }
   if (has_filter('eme_event_preinsert_filter')) $line=apply_filters('eme_event_preinsert_filter',$line);

   $event=eme_new_event();
   // we only want the columns that interest us
   // we need to do this since this function is also called for csv import
   $keys=array_intersect_key($line,$event);
   $new_line=array_merge($event,$keys);
   if (has_filter('eme_insert_event_filter')) $new_line=apply_filters('eme_insert_event_filter',$new_line);

   $new_line['event_attributes'] = eme_maybe_serialize($new_line['event_attributes']);
   $new_line['event_properties'] = eme_maybe_serialize($new_line['event_properties']);

   if (empty($new_line['creation_date']) || !(eme_is_date($new_line['creation_date']) || eme_is_datetime($new_line['creation_date'])))
	   $new_line['creation_date'] = current_time('mysql',false);
   $new_line['modif_date'] = $new_line['creation_date'];
 
   if (!$wpdb->insert ( $table_name, $new_line )) {
      return false;
   } else {
      $event_id = $wpdb->insert_id;
      $new_line['event_id']=$event_id;
      eme_handle_tasks_post_adminform($event_id,$day_difference);
      return $event_id;
   }
}

function eme_db_update_event($line,$event_id,$event_is_part_of_recurrence=0,$day_difference=0) {
   global $wpdb, $eme_timezone;
   $table_name = $wpdb->prefix . EVENTS_TBNAME;

   if (empty($line['event_author']))
	   $line['event_author']=get_current_user_id();
   if (empty($line['event_contactperson_id']))
	   $line['event_contactperson_id']=$line['event_author'];
   if (empty($line['event_slug']))
	$line['event_slug'] = eme_permalink_convert_noslash($line['event_name']);
   $line['event_slug'] = eme_unique_slug($line['event_slug'],EVENTS_TBNAME,'event_slug','event_id',$event_id);
   if (eme_is_empty_datetime($line['event_start']) && isset($line['event_start_date']) && isset($line['event_start_time'])) {
	   $line['event_start'] = $line['event_start_date'].' '.$line['event_start_time'];
   }
   if (eme_is_empty_datetime($line['event_end']) && isset($line['event_end_date']) && isset($line['event_end_time'])) {
	   $line['event_end'] = $line['event_end_date'].' '.$line['event_end_time'];
   }

   if (has_filter('eme_event_preupdate_filter')) {
	   if (!isset($line['event_id']))
		   $line['event_id']=$event_id;
	   $line=apply_filters('eme_event_preupdate_filter',$line);
   }

   $event=eme_new_event();
   // we only want the columns that interest us
   // we need to do this since this function is also called for csv import
   $keys=array_intersect_key($line,$event);
   $new_line=array_merge($event,$keys);
   $new_line['event_attributes'] = eme_maybe_serialize($new_line['event_attributes']);
   $new_line['event_properties'] = eme_maybe_serialize($new_line['event_properties']);

   $new_line['modif_date'] = current_time('mysql',false);

   $where=array('event_id' => $event_id);
   $wpdb->show_errors(true);
   if ($wpdb->update ( $table_name, $new_line, $where ) === false) {
      $wpdb->print_error();
      $wpdb->show_errors(false);
      return false;
   } else {
      $new_line['event_id']=$event_id;
      $task_ids=eme_handle_tasks_post_adminform($event_id,$day_difference);
      if (!empty($task_ids)) {
         eme_delete_event_old_tasks($event_id,$task_ids);
      } else {
         eme_delete_event_tasks($event_id);
      }
      $wpdb->show_errors(false);
      wp_cache_delete("eme_event $event_id");
      return true;
   }
}

function eme_change_event_status($events,$status) {
   global $wpdb;
   $table_name = $wpdb->prefix . EVENTS_TBNAME;

   if (is_array($events))
      $events_to_change=join(',',$events);
   else
      $event_to_change=$events;

   $sql = "UPDATE $table_name set event_status=$status WHERE event_id in (".$events_to_change.")";
   $wpdb->query($sql);
}

// for GDPR cron
function eme_delete_old_events() {
   global $wpdb,$eme_timezone;
   $events_table = $wpdb->prefix.EVENTS_TBNAME;
   $remove_old_events_days = get_option('eme_gdpr_remove_old_events_days');
   if (empty($remove_old_events_days))
           return;
   else
           $remove_old_events_days=abs($remove_old_events_days);

   $eme_date_obj=new ExpressiveDate("now",$eme_timezone);
   $old_date = $eme_date_obj->minusDays($remove_old_events_days)->getDateTime();

   $query = "SELECT event_id FROM $events_table WHERE $events_table.event_end <'$old_date'";
   $event_ids=$wpdb->get_col($sql);
   foreach ($event_ids as $event_id) {
	   eme_db_delete_event($event_id);
   }
}

function eme_db_delete_event($event_id,$event_is_part_of_recurrence=0) {
   global $wpdb;
 
   $event=eme_get_event($event_id);
   if (empty($event))
      return;

   // the eme_delete_event_action is only executed for single events, not those part of a recurrence
   if (!$event_is_part_of_recurrence && has_action('eme_delete_event_action')) {
	do_action('eme_delete_event_action',$event);
   }

   $table_name = $wpdb->prefix . EVENTS_TBNAME;
   $sql = $wpdb->prepare("DELETE FROM $table_name WHERE event_id = %d",$event_id);
   if ($wpdb->query($sql)) {
      eme_delete_all_bookings_for_event_id($event_id);
      eme_delete_event_attendances($event_id);
      eme_delete_event_answers($event_id);
      eme_delete_event_tasks($event_id);
   }
}

function eme_delete_event_answers($event_id) {
   global $wpdb;
   $answers_table = $wpdb->prefix.ANSWERS_TBNAME;
   $sql = $wpdb->prepare("DELETE FROM $answers_table WHERE related_id=%d AND type='event'",$event_id);
   $wpdb->query($sql);
}

function eme_check_event_external_ref($id) {
   global $wpdb;
   $table_name = $wpdb->prefix . EVENTS_TBNAME;
   $sql = $wpdb->prepare("SELECT location_id FROM $table_name WHERE location_external_ref = %s",$id);
   return $wpdb->get_var($sql);
}

function eme_admin_enqueue_js(){
   global $plugin_page, $eme_wp_date_format, $eme_wp_time_format, $eme_plugin_url;
   if (empty($plugin_page))
	   return;
   $language = eme_detect_lang();

   if (preg_match('/^eme-/',$plugin_page)) {
      eme_enqueue_datetimepicker();
      // remove some scripts from widgetopts that get loaded everywhere ...
      remove_action( 'admin_enqueue_scripts', 'widgetopts_load_admin_scripts', 100 );
      $translation_array = array(
	      'translate_plugin_url' => $eme_plugin_url,
	      'translate_ajax_url' => admin_url('admin-ajax.php'),
	      'translate_selectstate' => __('State','events-made-easy'),
	      'translate_selectcountry' => __('Country','events-made-easy'),
	      'translate_frontendnonce' => wp_create_nonce( 'eme_frontend' ),
	      'translate_error' => __('An error has occurred','events-made-easy'),
	      'translate_mailingpreferences' => __("Mailing preferences",'events-made-easy'),
	      'translate_yessure' => __("Yes, I'm sure",'events-made-easy'),
	      'translate_iwantmails' => __('I want to receive mails','events-made-easy'),
	      'translate_firstDayOfWeek' => get_option('start_of_week'),
	      'translate_flanguage' => $language,
	      'translate_minutesStep' => get_option('eme_timepicker_minutesstep'),
	      'translate_fdateformat' => $eme_wp_date_format,
	      'translate_ftimeformat' => $eme_wp_time_format,
	      'translate_selectimg' => __('Select the image to be used as person image','events-made-easy'),
	      'translate_setimg' => __('Set image','events-made-easy'),
	      'translate_chooseimg' => __('Choose image','events-made-easy'),
	      'translate_replaceimg' => __('Replace image','events-made-easy')
      );
      wp_localize_script('eme-basic', 'emebasic',$translation_array);
      wp_enqueue_script('eme-basic');
      $translation_array = array(
	      'translate_areyousuretodeleteselected' => __('Are you sure you want to delete the selected records?','events-made-easy'),
	      'translate_selectpersons' => __('Select one or more persons','events-made-easy'),
	      'translate_selectmembers' => __('Select one or more members','events-made-easy'),
	      'translate_selectgroups' => __('Select one or more groups','events-made-easy'),
	      'translate_anygroup' => __('Any group','events-made-easy'),
	      'translate_selectmemberships' => __('Filter on membership','events-made-easy'),
	      'translate_selectmemberstatus' => __('Filter on member status','events-made-easy'),
	      'translate_selectcustomfields' => __('Custom fields to filter on','events-made-easy'),
	      'translate_addatachments' => __('Add attachments','events-made-easy'),
	      'translate_selectdiscount' => __('Select a discount','events-made-easy'),
	      'translate_selectdiscountgroup' => __('Select a discountgroup','events-made-easy'),
	      'translate_adminnonce' => wp_create_nonce( 'eme_admin' ),
	      'translate_firstDayOfWeek' => get_option('start_of_week'),
	      'translate_flanguage' => $language,
	      'translate_minutesStep' => get_option('eme_timepicker_minutesstep'),
	      'translate_fdateformat' => $eme_wp_date_format,
	      'translate_ftimeformat' => $eme_wp_time_format
      );
      wp_localize_script('eme-admin', 'emeadmin', $translation_array);
      wp_enqueue_script('eme-admin');
      //wp_enqueue_style("wp-jquery-ui-dialog");
      wp_enqueue_style('eme-jquery-ui-css');
      wp_enqueue_style('eme-jquery-jtable-css');
      wp_enqueue_style('eme-jtables-css');
      wp_enqueue_style('eme-jquery-select2-css');
      if (wp_script_is('eme-jtable-locale','registered'))
            wp_enqueue_script('eme-jtable-locale');
   }
   if ($plugin_page == 'eme-new_event' || (in_array( $plugin_page, array('eme-locations', 'eme-manager')) && isset($_REQUEST['eme_admin_action'])) ) {
      // we need this to have the "postbox" javascript loaded, so closing/opening works for those divs
      // wp_enqueue_script('post');
      if (get_option('eme_map_is_active' )) {
        wp_enqueue_style('eme-leaflet-css');
        $translation_array = array(
		'translate_eme_map_zooming' => get_option('eme_map_zooming') ? 'true' : 'false',
		'translate_default_map_icon' => get_option('eme_location_map_icon'),
		'translate_eme_map_is_active' => 'true'
	);
        wp_localize_script('eme-admin-maps', 'emeadminmaps', $translation_array);
        wp_enqueue_script('eme-admin-maps');
      }
   }
   if ( in_array( $plugin_page, array('eme-new_event', 'eme-manager') ) ) {
      wp_enqueue_style('eme-jquery-ui-autocomplete');
      // Now we can localize the script with our data.
      $translation_array = array(
	      'translate_nomatchevent' => __('No matching event found','events-made-easy'),
	      'translate_eme_map_is_active' => get_option('eme_map_is_active')?'true':'false',
	      'translate_events' => __('Events','events-made-easy'),
	      'translate_recurrences' => __('Recurrences','events-made-easy'),
	      'translate_rsvp' => __('RSVP','events-made-easy'),
	      'translate_eventprice' => __('Event price','events-made-easy'),
	      'translate_id' => __('ID','events-made-easy'),
	      'translate_name' => __('Name','events-made-easy'),
	      'translate_insertnewevent' => __('Insert New Event','events-made-easy'),
	      'translate_editeventstring' => __("Edit Event '%s'",'events-made-easy'),
	      'translate_status' => __('Status','events-made-easy'),
	      'translate_copy' => __('Copy','events-made-easy'),
	      'translate_csv' => __('CSV','events-made-easy'),
	      'translate_print' => __('Print','events-made-easy'),
	      'translate_location' => __('Location','events-made-easy'),
	      'translate_recinfo' => __('Recurrence info','events-made-easy'),
	      'translate_rec_singledur' => __('Single event duration','events-made-easy'),
	      'translate_date' => __('Date','events-made-easy'),
	      'translate_datetime' => __('Date and time','events-made-easy'),
	      'translate_fdateformat' => $eme_wp_date_format,
	      'translate_selecteddates' => __('Selected dates:','events-made-easy'),
	      'translate_created_on' => __('Created on','events-made-easy'),
	      'translate_modified_on' => __('Modified on','events-made-easy'),
	      'translate_pleasewait' => __('Please wait','events-made-easy'),
	      'translate_apply' => __('Apply','events-made-easy'),
	      'translate_selectfeaturedimg' => __('Select the image to be used as featured image','events-made-easy'),
	      'translate_setfeaturedimg' => __('Set featured image','events-made-easy'),
	      'translate_pressdeletetoremove' => __('Press the delete button to remove','events-made-easy'),
	      'translate_areyousuretodeleteselected' => __('Are you sure you want to delete the selected records?','events-made-easy'),
	      'translate_enddate_required' => __('Since the event is repeated, you must specify an end date','events-made-easy'),
	      'translate_startenddate_identical' => __("In a recurrence, start and end date can't be identical",'events-made-easy'),
	      'translate_adminnonce' => wp_create_nonce( 'eme_admin' ),
	      'translate_selectcontact' => __('Event author','events-made-easy'),
	      'translate_firstDayOfWeek' => get_option('start_of_week')
      );
      wp_localize_script( 'eme-events', 'eme', $translation_array );
      wp_enqueue_script( 'eme-events');
       // some inline js that gets shown at the top
      eme_admin_event_script();
   }
   if ( in_array( $plugin_page, array('eme-options') ) ) {
      wp_enqueue_script('eme-options');
   }
   if ( in_array( $plugin_page, array('eme-attendance-reports') ) ) {
      wp_enqueue_style('eme-jquery-ui-autocomplete');
      $translation_array = array(
	      'translate_id' => __('ID','events-made-easy'),
	      'translate_type' => __('Type','events-made-easy'),
	      'translate_attendancedate' => __('Recorded on','events-made-easy'),
	      'translate_personinfo' => __('Person','events-made-easy'),
	      'translate_attendance_reports' => __('Attendance reports','events-made-easy'),
	      'translate_csv' => __('CSV','events-made-easy'),
	      'translate_print' => __('Print','events-made-easy'),
	      'translate_areyousuretodeletethis' => __('Are you sure to delete this record?','events-made-easy'),
	      'translate_nomatchperson' => __('No matching person found','events-made-easy'),
	      'translate_adminnonce' => wp_create_nonce( 'eme_admin' )
      );
      wp_localize_script('eme-attendances', 'emeattendances', $translation_array);
      wp_enqueue_script('eme-attendances');
   }
   if ( in_array( $plugin_page, array('eme-task-signups') ) ) {
      $translation_array = array(
	      'translate_id' => __('ID','events-made-easy'),
	      'translate_signups' => __('Task signups','events-made-easy'),
	      'translate_taskname' => __('Task name','events-made-easy'),
	      'translate_taskstart' => __('Task start date','events-made-easy'),
	      'translate_taskend' => __('Task end date','events-made-easy'),
	      'translate_event' => __('Event','events-made-easy'),
	      'translate_person' => __('Person','events-made-easy'),
	      'translate_csv' => __('CSV','events-made-easy'),
	      'translate_print' => __('Print','events-made-easy'),
	      'translate_pleasewait' => __('Please wait','events-made-easy'),
	      'translate_apply' => __('Apply','events-made-easy'),
	      'translate_areyousuretodeleteselected' => __('Are you sure to delete the selected records?','events-made-easy'),
	      'translate_pressdeletetoremove' => __('Press the delete button to remove this record','events-made-easy'),
	      'translate_deleted' => __('Records deleted','events-made-easy'),
	      'translate_admin_sendmails_url' => admin_url("admin.php?page=eme-emails"),
	      'translate_adminnonce' => wp_create_nonce( 'eme_admin' )
      );
      wp_localize_script('eme-tasksignups', 'emetasks', $translation_array);
      wp_enqueue_script('eme-tasksignups');
   }
   if ( in_array( $plugin_page, array('eme-templates') ) ) {
      $translation_array = array(
	      'translate_id' => __('ID','events-made-easy'),
	      'translate_templates' => __('Templates','events-made-easy'),
	      'translate_name' => __('Name','events-made-easy'),
	      'translate_description' => __('Description','events-made-easy'),
	      'translate_type' => __('Type','events-made-easy'),
	      'translate_edit' => __('Edit','events-made-easy'),
	      'translate_pleasewait' => __('Please wait','events-made-easy'),
	      'translate_apply' => __('Apply','events-made-easy'),
	      'translate_csv' => __('CSV','events-made-easy'),
	      'translate_print' => __('Print','events-made-easy'),
	      'translate_areyousuretodeleteselected' => __('Are you sure to delete the selected records?','events-made-easy'),
	      'translate_pressdeletetoremove' => __('Press the delete button to remove','events-made-easy'),
	      'translate_deleted' => __('Records deleted','events-made-easy'),
	      'translate_adminnonce' => wp_create_nonce( 'eme_admin' )
      );
      wp_localize_script('eme-templates', 'emetemplates', $translation_array);
      wp_enqueue_script('eme-templates');
   }
   if ( in_array( $plugin_page, array('eme-formfields') ) ) {
      $translation_array = array(
	      'translate_id' => __('ID','events-made-easy'),
	      'translate_name' => __('Name','events-made-easy'),
	      'translate_type' => __('Type','events-made-easy'),
	      'translate_extracharge' => __('Extra charge','events-made-easy'),
	      'translate_searchable' => __('Searchable','events-made-easy'),
	      'translate_purpose' => __('Purpose','events-made-easy'),
	      'translate_used' => __('Used in replies','events-made-easy'),
	      'translate_required' => __('Required','events-made-easy'),
	      'translate_edit' => __('Edit','events-made-easy'),
	      'translate_pleasewait' => __('Please wait','events-made-easy'),
	      'translate_apply' => __('Apply','events-made-easy'),
	      'translate_areyousuretodeleteselected' => __('Are you sure to delete the selected records?','events-made-easy'),
	      'translate_pressdeletetoremove' => __('Press the delete button to remove','events-made-easy'),
	      'translate_adminnonce' => wp_create_nonce( 'eme_admin' )
      );
      wp_localize_script('eme-formfields', 'emeformfields', $translation_array);
      wp_enqueue_script('eme-formfields');
   }
   if ( in_array( $plugin_page, array('eme-discounts') ) ) {
      $translation_array = array(
	      'translate_discounts' => __('Discounts','events-made-easy'),
	      'translate_name' => __('Name','events-made-easy'),
	      'translate_description' => __('Description','events-made-easy'),
	      'translate_discountgroups' => __('Discount groups','events-made-easy'),
	      'translate_coupon' => __('Coupon','events-made-easy'),
	      'translate_casesensitive' => __('Case sensitive','events-made-easy'),
	      'translate_use_per_seat' => __('Track discount usage per booked seat','events-made-easy'),
	      'translate_value' => __('Value','events-made-easy'),
	      'translate_type' => __('Type','events-made-easy'),
	      'translate_maxusage' => __('Max Usage','events-made-easy'),
	      'translate_usage' => __('Usage','events-made-easy'),
	      'translate_validfrom' => __('Valid from','events-made-easy'),
	      'translate_validto' => __('Valid until','events-made-easy'),
	      'translate_maxdiscounts' => __('Max Discounts','events-made-easy'),
	      'translate_edit' => __('Edit','events-made-easy'),
	      'translate_added' => __('Record added','events-made-easy'),
	      'translate_updated' => __('Record updated','events-made-easy'),
	      'translate_deleted' => __('Records deleted','events-made-easy'),
	      'translate_fixed' => __('Fixed','events-made-easy'),
	      'translate_fixed_per_seat' => __('Fixed per seat','events-made-easy'),
	      'translate_percentage' => __('Percentage','events-made-easy'),
	      'translate_code' => __('Code','events-made-easy'),
	      'translate_pleasewait' => __('Please wait','events-made-easy'),
	      'translate_apply' => __('Apply','events-made-easy'),
	      'translate_areyousuretodeleteselected' => __('Are you sure to delete the selected records?','events-made-easy'),
	      'translate_pressdeletetoremove' => __('Press the delete button to remove','events-made-easy'),
	      'translate_adminnonce' => wp_create_nonce( 'eme_admin' )
      );
      wp_localize_script( 'eme-discounts', 'emediscounts', $translation_array );
      wp_enqueue_script('eme-discounts');
   }
   if ( in_array( $plugin_page, array('eme-countries') ) ) {
      $translation_array = array(
	      'translate_countries' => __('Countries','events-made-easy'),
	      'translate_id' => __('ID','events-made-easy'),
	      'translate_name' => __('Name','events-made-easy'),
	      'translate_country_id' => __('Country ID','events-made-easy'),
	      'translate_states' => __('States','events-made-easy'),
	      'translate_country' => __('Country','events-made-easy'),
	      'translate_state' => __('State','events-made-easy'),
	      'translate_lang' => __('Language','events-made-easy'),
	      'translate_edit' => __('Edit','events-made-easy'),
	      'translate_missingcountry' => "<img style='vertical-align: middle;' src='".$eme_plugin_url."images/warning.png' alt='warning' title='".__('No country associated with this state, it will not show up in dropdown lists. Please edit this state and correct the country info.','events-made-easy')."'>",
	      'translate_added' => __('Record added','events-made-easy'),
	      'translate_updated' => __('Record updated','events-made-easy'),
	      'translate_deleted' => __('Records deleted','events-made-easy'),
	      'translate_code' => __('Code','events-made-easy'),
	      'translate_alpha_2' => __('Alpha-2','events-made-easy'),
	      'translate_alpha_3' => __('Alpha-3','events-made-easy'),
	      'translate_num_3' => __('Num-3','events-made-easy'),
	      'translate_pleasewait' => __('Please wait','events-made-easy'),
	      'translate_apply' => __('Apply','events-made-easy'),
	      'translate_areyousuretodeleteselected' => __('Are you sure to delete the selected records?','events-made-easy'),
	      'translate_adminnonce' => wp_create_nonce( 'eme_admin' )
      );
      wp_localize_script( 'eme-countries', 'emecountries', $translation_array );
      wp_enqueue_script('eme-countries');
   }
   if ( in_array( $plugin_page, array('eme-locations') ) ) {
      $translation_array = array(
	      'translate_nomatchlocation' => __('No matching location found','events-made-easy'),
	      'translate_locations' => __('Locations','events-made-easy'),
	      'translate_id' => __('ID','events-made-easy'),
	      'translate_name' => __('Name','events-made-easy'),
	      'translate_insertnewlocation' => __('Insert New Location','events-made-easy'),
	      'translate_editlocationstring' => __("Edit Location '%s'",'events-made-easy'),
	      'translate_copy' => __('Copy','events-made-easy'),
	      'translate_view' => __('View','events-made-easy'),
	      'translate_address1' => __('Address1','events-made-easy'),
	      'translate_address2' => __('Address2','events-made-easy'),
	      'translate_city' => __('City','events-made-easy'),
	      'translate_zip' => __('Zip','events-made-easy'),
	      'translate_state' => __('State','events-made-easy'),
	      'translate_country' => __('Country','events-made-easy'),
	      'translate_latitude' => __('Latitude','events-made-easy'),
	      'translate_longitude' => __('Longitude','events-made-easy'),
	      'translate_external_url' => __('External URL','events-made-easy'),
	      'translate_online_only' => __('Online only','events-made-easy'),
	      'translate_pleasewait' => __('Please wait','events-made-easy'),
	      'translate_apply' => __('Apply','events-made-easy'),
	      'translate_areyousuretodeleteselected' => __('Are you sure you want to delete the selected records?','events-made-easy'),
	      'translate_csv' => __('CSV','events-made-easy'),
	      'translate_print' => __('Print','events-made-easy'),
	      'translate_selectfeaturedimage' => __('Select the image to be used as featured image','events-made-easy'),
	      'translate_setfeaturedimage' => __('Set featured image','events-made-easy')
      );
      wp_localize_script( 'eme-locations', 'eme', $translation_array );
      wp_enqueue_script('eme-locations');
   }
   if ( in_array( $plugin_page, array('eme-people','eme-groups') ) ) {
	   wp_enqueue_media();
	   $translation_array = array(
		   'translate_nomatchperson' => __('No matching person found','events-made-easy'),
		   'translate_plugin_url' => $eme_plugin_url,
		   'translate_personid' => __('Person ID','events-made-easy'),
		   'translate_groupid' => __('Group ID','events-made-easy'),
		   'translate_people' => __('People','events-made-easy'),
		   'translate_groups' => __('Groups','events-made-easy'),
		   'translate_description' => __('Description','events-made-easy'),
		   'translate_name' => __('Name','events-made-easy'),
		   'translate_lastname' => __('Last name','events-made-easy'),
		   'translate_firstname' => __('First name','events-made-easy'),
		   'translate_address1' => __('Address1','events-made-easy'),
		   'translate_address2' => __('Address2','events-made-easy'),
		   'translate_city' => __('City','events-made-easy'),
		   'translate_zip' => __('Zip','events-made-easy'),
		   'translate_state' => __('State','events-made-easy'),
		   'translate_country' => __('Country','events-made-easy'),
		   'translate_email' => __('E-mail','events-made-easy'),
		   'translate_phone' => __('Phone number','events-made-easy'),
		   'translate_birthdate' => __('Birth date','events-made-easy'),
		   'translate_birthplace' => __('Birth place','events-made-easy'),
		   'translate_lang' => __('Language','events-made-easy'),
		   'translate_wpuser' => __('Linked WP user','events-made-easy'),
		   'translate_pleasewait' => __('Please wait','events-made-easy'),
		   'translate_apply' => __('Apply','events-made-easy'),
		   'translate_areyousuretodeleteselected' => __('Are you sure to delete the selected records?','events-made-easy'),
		   'translate_showallbookings' => __('Show all bookings','events-made-easy'),
		   'translate_personmemberships' => __('Member of','events-made-easy'),
		   'translate_persongroups' => __('Groups','events-made-easy'),
		   'translate_bookingsmade' => __('Bookings made','events-made-easy'),
		   'translate_csv' => __('CSV','events-made-easy'),
		   'translate_print' => __('Print','events-made-easy'),
		   'translate_answers' => __('Answers','events-made-easy'),
		   'translate_added' => __('Record added','events-made-easy'),
		   'translate_updated' => __('Record updated','events-made-easy'),
		   'translate_gdpr' => __('GDPR','events-made-easy'),
		   'translate_gdpr_date' => __('GDPR modification date','events-made-easy'),
		   'translate_created_on' => __('Created on','events-made-easy'),
		   'translate_modified_on' => __('Modified on','events-made-easy'),
		   'translate_related_to' => __('Related to','events-made-easy'),
		   'translate_massmail' => __('MassMail','events-made-easy'),
		   'translate_publicgroup' => __('Public group','events-made-easy'),
		   'translate_groupcount' => __('Nbr People','events-made-easy'),
		   'translate_adminnonce' => wp_create_nonce( 'eme_admin' ),
		   'translate_admin_sendmails_url' => admin_url("admin.php?page=eme-emails"),
		   'translate_firstDayOfWeek' => get_option('start_of_week'),
		   'translate_flanguage' => $language,
		   'translate_fdateformat' => $eme_wp_date_format
	   );
	   wp_localize_script( 'eme-people', 'eme', $translation_array );
	   wp_enqueue_script('eme-people');
   }
   if ( in_array( $plugin_page, array('eme-members','eme-memberships') ) ) {
	   wp_enqueue_media();
	   $translation_array = array(
		   'translate_nomatchperson' => __('No matching person found','events-made-easy'),
		   'translate_nomatchmember' => __('No matching member found','events-made-easy'),
		   'translate_plugin_url' => $eme_plugin_url,
		   'translate_members' => __('Members','events-made-easy'),
		   'translate_memberships' => __('Memberships','events-made-easy'),
		   'translate_membership' => __('Membership','events-made-easy'),
		   'translate_description' => __('Description','events-made-easy'),
		   'translate_id' => __('ID','events-made-easy'),
		   'translate_memberid' => __('Member ID','events-made-easy'),
		   'translate_personid' => __('Person ID','events-made-easy'),
		   'translate_wpuser' => __('Linked WP user','events-made-easy'),
		   'translate_contact' => __('Contact','events-made-easy'),
		   'translate_name' => __('Name','events-made-easy'),
		   'translate_lastname' => __('Last name','events-made-easy'),
		   'translate_firstname' => __('First name','events-made-easy'),
		   'translate_email' => __('E-mail','events-made-easy'),
		   'translate_related_to' => __('Related to','events-made-easy'),
		   'translate_address1' => __('Address1','events-made-easy'),
		   'translate_address2' => __('Address2','events-made-easy'),
		   'translate_birthdate' => __('Birth date','events-made-easy'),
		   'translate_birthplace' => __('Birth place','events-made-easy'),
		   'translate_city' => __('City','events-made-easy'),
		   'translate_zip' => __('Zip','events-made-easy'),
		   'translate_state' => __('State','events-made-easy'),
		   'translate_country' => __('Country','events-made-easy'),
		   'translate_pleasewait' => __('Please wait','events-made-easy'),
		   'translate_apply' => __('Apply','events-made-easy'),
		   'translate_areyousuretodeleteselected' => __('Are you sure to delete the selected records?','events-made-easy'),
		   'translate_csv' => __('CSV','events-made-easy'),
		   'translate_print' => __('Print','events-made-easy'),
		   'translate_answers' => __('Answers','events-made-easy'),
		   'translate_edit' => __('Edit','events-made-easy'),
		   'translate_membercount' => __('Nbr Active Members','events-made-easy'),
		   'translate_startdate' => __('Start','events-made-easy'),
		   'translate_enddate' => __('End','events-made-easy'),
		   'translate_registrationdate' => __('Registered on','events-made-easy'),
		   'translate_last_seen' => __('Last seen on','events-made-easy'),
		   'translate_paymentdate' => __('Paid on','events-made-easy'),
		   'translate_uniquenbr' => __('Unique nbr','events-made-easy'),
		   'translate_paymentid' => __('Payment ID','events-made-easy'),
		   'translate_paid' => __('Paid','events-made-easy'),
		   'translate_pg' => __('Payment GW','events-made-easy'),
		   'translate_pg_pid' => __('Payment GW ID','events-made-easy'),
		   'translate_lastreminder' => __('Last reminder','events-made-easy'),
		   'translate_nbrreminder' => __('Reminders sent','events-made-easy'),
		   'translate_status' => __('Status','events-made-easy'),
		   'translate_adminnonce' => wp_create_nonce( 'eme_admin' ),
		   'translate_admin_sendmails_url' => admin_url("admin.php?page=eme-emails"),
		   'translate_addatachments' => __('Add attachments','events-made-easy'),
		   'translate_discount' => __('Discount','events-made-easy'),
		   'translate_dcodes_used' => __('Used discount codes','events-made-easy'),
		   'translate_totalprice' => __('Total price','events-made-easy'),
		   'translate_membershipprice' => __('Membership price','events-made-easy')
	   );
	   wp_localize_script( 'eme-members', 'eme', $translation_array );
	   wp_enqueue_script('eme-members');
   }
   if ( in_array( $plugin_page, array('eme-registration-approval','eme-registration-seats') ) ) {
      $translation_array = array(
	      'translate_nomatchevent' => __('No matching event found','events-made-easy'),
	      'translate_bookings' => __('Bookings','events-made-easy'),
	      'translate_id' => __('ID','events-made-easy'),
	      'translate_rsvp' => __('RSVP','events-made-easy'),
	      'translate_eventinfo' => __('Event info','events-made-easy'),
	      'translate_datetime' => __('Date and time','events-made-easy'),
	      'translate_booker' => __('Booker','events-made-easy'),
	      'translate_wpuser' => __('Linked WP user','events-made-easy'),
	      'translate_bookingdate' => __('Booking date','events-made-easy'),
	      'translate_seats' => __('Seats','events-made-easy'),
	      'translate_eventprice' => __('Event price','events-made-easy'),
	      'translate_event_cats' => __('Category','events-made-easy'),
	      'translate_totalprice' => __('Total price','events-made-easy'),
	      'translate_uniquenbr' => __('Unique nbr','events-made-easy'),
	      'translate_paymentid' => __('Payment ID','events-made-easy'),
	      'translate_paid' => __('Paid','events-made-easy'),
	      'translate_remaining' => __('Remaining','events-made-easy'),
	      'translate_received' => __('Received','events-made-easy'),
	      'translate_pg' => __('Payment GW','events-made-easy'),
	      'translate_pg_pid' => __('Payment GW ID','events-made-easy'),
	      'translate_paymentdate' => __('Payment date','events-made-easy'),
	      'translate_paidandapprove' => __('Mark paid and approve','events-made-easy'),
	      'translate_edit' => __('Edit','events-made-easy'),
	      'translate_csv' => __('CSV','events-made-easy'),
	      'translate_print' => __('Print','events-made-easy'),
	      'translate_comment' => __('Comment','events-made-easy'),
	      'translate_lastreminder' => __('Last reminder','events-made-easy'),
	      'translate_discount' => __('Discount','events-made-easy'),
	      'translate_dcodes_used' => __('Used discount codes','events-made-easy'),
	      'translate_pleasewait' => __('Please wait','events-made-easy'),
	      'translate_apply' => __('Apply','events-made-easy'),
	      'translate_areyousuretodeleteselected' => __('Are you sure to delete the selected records?','events-made-easy'),
	      'translate_adminnonce' => wp_create_nonce( 'eme_admin' ),
	      'translate_firstDayOfWeek' => get_option('start_of_week'),
	      'translate_admin_sendmails_url' => admin_url("admin.php?page=eme-emails"),
	      'translate_attend_count' => __('Attendance count','events-made-easy'),
	      "translate_selectonerowonlyforpartial" => __('Please select only one record in order to do partial payments','events-made-easy')
      );
      wp_localize_script( 'eme-rsvp', 'eme', $translation_array );
      wp_enqueue_script('eme-rsvp');
   }
   if ( in_array( $plugin_page, array('eme-emails') ) ) {
      wp_enqueue_style('eme-jquery-ui-autocomplete');
      // if html mails are disabled, this is needed
      wp_enqueue_media();
      $translation_array = array(
	      'translate_pleasewait' => __('Please wait','events-made-easy'),
	      'translate_sendmail' => __('Send email','events-made-easy'),
	      'translate_planmail' => __('Queue email','events-made-easy'),
	      'translate_sentdatetime' => __('Sent on','events-made-easy'),
	      'translate_first_read_on' => __('First read on','events-made-easy'),
	      'translate_last_read_on' => __('Last read on','events-made-easy'),
	      'translate_readcount' => __('Read count','events-made-easy'),
	      'translate_errormessage' => __('Error message','events-made-easy'),
	      'translate_nomatchperson' => __('No matching person found','events-made-easy'),
	      'translate_name' => __('Name','events-made-easy'),
	      'translate_email' => __('E-mail','events-made-easy'),
	      'translate_status' => __('Status','events-made-easy'),
	      'translate_action' => __('Action','events-made-easy'),
	      'translate_mailingreport' => __('Mailing report','events-made-easy'),
	      'translate_selectevents' => __('Select one or more events','events-made-easy'),
	      'translate_htmlmail' => get_option('eme_rsvp_send_html')? "yes":"no",
	      'translate_addatachments' => __('Add attachments','events-made-easy'),
	      'translate_selecteddates' => __('Selected dates:','events-made-easy'),
	      'translate_firstDayOfWeek' => get_option('start_of_week'),
	      'translate_flanguage' => $language,
	      'translate_minutesStep' => get_option('eme_timepicker_minutesstep'),
	      'translate_fdateformat' => $eme_wp_date_format,
	      'translate_ftimeformat' => $eme_wp_time_format,
	      'translate_fdatetimeformat' => $eme_wp_date_format.' '.$eme_wp_time_format
      );
      wp_localize_script( 'eme-sendmails', 'eme', $translation_array );
      wp_enqueue_script('eme-sendmails');
   }

   if (preg_match('/^eme-/',$plugin_page)) {
      wp_enqueue_style('eme_textsec',$eme_plugin_url."css/text-security/text-security-disc.css");
      wp_enqueue_style('eme_stylesheet');
      wp_enqueue_style('eme_stylesheet_extra');
   }
}

# return number of days until next event or until the specified event
function eme_countdown_shortcode($atts) {
   global $eme_timezone;
   extract ( shortcode_atts ( array ('id'=>'','recurrence_id'=>0,'category_id'=>0), $atts ) );

   if (!empty($id)) {
	   $event=eme_get_event($id);
   } elseif ($recurrence_id) {
	   // we only want future events, so set the second arg to 1
	   $ids=eme_get_recurrence_eventids(intval($recurrence_id),1);
	   if (!empty($ids))
		   $event=eme_get_event($ids[0]);
   } elseif ($category_id) {
	   $ids=eme_get_category_eventids(intval($category_id));
	   if (!empty($ids))
		   $event=eme_get_event($ids[0]);
   } else {
	   $newest_event_array=eme_get_events(1);
	   if (!empty($newest_event_array))
		   $event=$newest_event_array[0];
   }
   if (!empty($event)) {
	   $eme_date_obj = new ExpressiveDate($event['event_start'],$eme_timezone);
	   $eme_date_obj_now=new ExpressiveDate("now",$eme_timezone);
	   return intval($eme_date_obj_now->startOfDay()->getDifferenceInDays($eme_date_obj->startOfDay()));
   } else {
	   return 0;
   }
}

function eme_ajax_events_search() {
   global $eme_timezone;

   $return = array();
   if (isset($_REQUEST['q']))
   $q = isset($_REQUEST['q']) ? strtolower($_REQUEST['q']) : '';
   if (empty($q)) {
      echo json_encode($return);
      return;
   }
   $search_all = isset($_REQUEST['search_all']) ? intval($_REQUEST['search_all']) : 0;
   if ($search_all) {
           $scope="all";
   } else {
           $scope="future";
   }
   $exclude_id = isset($_REQUEST['exclude_id']) ? intval($_REQUEST['exclude_id']) : 0;
   $only_rsvp = isset($_REQUEST['only_rsvp']) ? intval($_REQUEST['only_rsvp']) : 0;
   $events=eme_search_events($q,$scope,1,$exclude_id,$only_rsvp);
   foreach($events as $event) {
      $record = array();
      $record['event_id'] = $event['event_id'];
      $record['eventinfo'] = eme_esc_html($event['event_name']." (".eme_localized_date($event['event_start'],$eme_timezone,1).")");
      $return[] = $record;
   }
   header("Content-type: application/json; charset=utf-8");
   echo json_encode($return);
   wp_die();
}

add_action( 'wp_ajax_eme_wpuser_select2', 'eme_ajax_wpuser_select2' );
function eme_ajax_wpuser_select2() {
   check_ajax_referer('eme_admin','eme_admin_nonce');

   $jTableResult = array();
   $q = isset($_REQUEST['q']) ? strtolower($_REQUEST['q']) : '';
   $pagesize=intval($_REQUEST["pagesize"]);
   $start= isset($_REQUEST["page"]) ? intval($_REQUEST["page"])*$pagesize : 0;

   $records=array();
   list($persons,$total) = eme_get_wp_users($q,$start,$pagesize);
   foreach ($persons as $item) {
         $record = array();
         $user_info = get_userdata($item->ID);
         $record['id']= $item->ID;
         // no eme_esc_html here, select2 does it own escaping upon arrival
         $record['text'] = $user_info->display_name;
         $records[]  = $record;
   }
   $jTableResult['TotalRecordCount'] = $total;
   $jTableResult['Records'] = $records;
   print json_encode($jTableResult);
   wp_die();
}

add_action( 'wp_ajax_eme_events_list', 'eme_ajax_events_list' );
add_action( 'wp_ajax_eme_manage_events', 'eme_ajax_manage_events' );
add_action( 'wp_ajax_eme_autocomplete_event', 'eme_ajax_events_search' );

function eme_ajax_events_list() {
   global $eme_timezone, $eme_plugin_url;

   if (!current_user_can( get_option('eme_cap_list_events'))) {
	   $jTableResult['Result'] = "Error";
	   $jTableResult['Message'] = __('Access denied!','events-made-easy');
	   print json_encode($jTableResult);
	   wp_die();
   }

   $jtStartIndex= (isset($_REQUEST['jtStartIndex'])) ? intval($_REQUEST['jtStartIndex']) : 0;
   $jtPageSize= (isset($_REQUEST['jtPageSize'])) ? intval($_REQUEST['jtPageSize']) : 10;
   $jtSorting = (isset($_REQUEST['jtSorting'])) ? eme_sanitize_request($_REQUEST['jtSorting']) : 'ASC';
   $scope = (isset($_REQUEST['scope'])) ? eme_sanitize_request($_REQUEST['scope']) : 'future';
   $category = isset($_REQUEST['category']) ? $_REQUEST['category'] : '';
   $status = isset($_REQUEST['status']) ? intval($_REQUEST['status']) : '';
   $search_name = isset($_REQUEST['search_name']) ? eme_sanitize_request($_REQUEST['search_name']) : '';
   $search_start_date = isset($_REQUEST['search_start_date']) ? esc_sql($_REQUEST['search_start_date']) : '';
   $search_end_date = isset($_REQUEST['search_end_date']) ? esc_sql($_REQUEST['search_end_date']) : '';
   $where ='';
   $where_arr = array();
   if(!empty($search_name)) {
      $where_arr[] = "event_name like '%".$search_name."%'";
   }
   if (!empty($search_start_date) && eme_is_date($search_start_date) &&
       !empty($search_end_date) && eme_is_date($search_end_date)) {
      $where_arr[] = "event_start >= '$search_start_date'";
      $where_arr[] = "event_end <= '$search_end_date 23:59:59'";
      $scope='all';
   } elseif (!empty($search_start_date) && eme_is_date($search_start_date)) {
      $where_arr[] = "event_start LIKE '$search_start_date%'";
      $scope='all';
   } elseif (!empty($search_end_date) && eme_is_date($search_end_date)) {
      $where_arr[] = "event_end LIKE '$search_end_date%'";
      $scope='all';
   }
   // override in case of trash
   if (isset($_REQUEST['trash']) && $_REQUEST['trash']==1 ) {
      $view_trash=1;
      $where_arr[] = 'event_status = '.EME_EVENT_STATUS_TRASH;
   } else {
      $view_trash=0;
      if(!empty($status)) {
         $where_arr[] = 'event_status = '.$status;
      } else {
         $where_arr[] = 'event_status != '.EME_EVENT_STATUS_TRASH;
      }
   }

   // if the person is not allowed to manage all events, show only events he can edit
   if (!current_user_can( get_option('eme_cap_edit_events'))) {
           $wp_id=get_current_user_id();
           if (!$wp_id) {
		   $ajaxResult['Result'] = "OK";
                   $ajaxResult['TotalRecordCount'] = 0;
                   $ajaxResult['Records'] = array();
                   print json_encode($ajaxResult);
                   wp_die();
           }
	   $where_arr[] = "(event_author=$wp_id)";
   }

   if ($where_arr)
      $where = implode(" AND ",$where_arr);

   // we ask only for the event_id column here, more efficient
   $count_only=1;
   $formfields_searchable = eme_get_searchable_formfields('events');
   $field_ids_arr=array();
   foreach ($formfields_searchable as $formfield) {
      $field_id=$formfield['field_id'];
      $field_ids_arr[]=$field_id;
   }
   if (!empty($_REQUEST['search_customfieldids'])) {
      $field_ids=join(',',$_REQUEST['search_customfieldids']);
   } else {
      $field_ids=join(',',$field_ids_arr);
   }
   if (isset($_REQUEST['search_customfields']) && $_REQUEST['search_customfields']!="") {
      $search_customfields=eme_sanitize_request($_REQUEST['search_customfields']);
   } else {
      $search_customfields='';
   }
 
   $events_count = eme_get_events ( 0, $scope, '', 0, "", $category, '', '', 1, '', 0, $where, $count_only, 1, $field_ids, $search_customfields);

   // datetime is a column in the javascript definition of the events jtable, but of course the db doesn't know this
   // so we need to change this into something known, but since eme_get_events can work with "ASC/DESC" only and
   // then sorts on date/time, we just remove the word here
   $jtSorting = str_replace("datetime ","",$jtSorting);
   $events = eme_get_events ( $jtPageSize, $scope, $jtSorting, $jtStartIndex, "", $category, '', '', 1, '', 0, $where, 0, 1, $field_ids, $search_customfields);
   $event_status_array = eme_status_array ();
   $eme_date_obj_now=new ExpressiveDate("now",$eme_timezone);

   // no searchable formfields yet, so we use eme_get_formfields here
   $formfields=eme_get_formfields('','events');
   $rows=array();
   foreach ($events as $event) {
      $record=array();
      $record['event_id'] = $event['event_id'];
      if (empty($event['event_name'])) $event['event_name']=__('No name','events-made-easy');
      $date_obj = new ExpressiveDate($event['event_start'],$eme_timezone);

      $record['event_name_simple'] = "<strong><a href='".admin_url("admin.php?page=eme-manager&amp;eme_admin_action=edit_event&amp;event_id=".$event['event_id'])."' title='".__('Edit event','events-made-easy')."'>".eme_trans_esc_html($event['event_name'])."</a></strong>";
      $record['event_name'] = $record['event_name_simple'];
      if (!empty($event['event_category_ids'])) {
            $categories = explode(',', $event['event_category_ids']);
            $record['event_name'] .= "<br /><span class='eme_categories_small' title='".__('Category','events-made-easy')."'>";
            $cat_names=array();
            foreach($categories as $cat){
               $category = eme_get_category($cat);
               if($category)
                  $cat_names[] = eme_trans_esc_html($category['category_name']);
            }
            $record['event_name'] .= implode(', ',$cat_names);
            $record['event_name'] .= "</span>";
      }
      if ($event['event_rsvp'] && !$view_trash) {
	    $record['event_name'] .= "<br />".__('RSVP Info: ','events-made-easy');
	    $booked_seats = eme_get_approved_seats($event['event_id']);
	    $pending_seats = eme_get_pending_seats($event['event_id']);
            $total_seats = eme_get_total($event['event_seats']);
            if (eme_is_multi($event['event_seats'])) {
	       if ($pending_seats>0)
		       $pending_seats_string = $pending_seats.' ('.eme_convert_array2multi(eme_get_pending_multiseats($event['event_id'])).')';
	       else
		       $pending_seats_string = $pending_seats;
               $total_seats_string = $total_seats.' ('.$event['event_seats'].')';
	       if ($booked_seats>0)
		       $booked_seats_string = $booked_seats.' ('.eme_convert_array2multi(eme_get_approved_multiseats($event['event_id'])).')';
	       else
		       $booked_seats_string = $booked_seats;
            } else {
               $pending_seats_string = $pending_seats;
               $total_seats_string = $total_seats;
               $booked_seats_string = $booked_seats;
            }
            if ($total_seats>0) {
                    $available_seats = eme_get_available_seats($event['event_id']);
                    if (eme_is_multi($event['event_seats']))
                            $available_seats_string = $available_seats.' ('.eme_convert_array2multi(eme_get_available_multiseats($event['event_id'])).')';
                    else
                            $available_seats_string = $available_seats;
		    $record['event_name'] .= __('Free: ','events-made-easy')." ".$available_seats_string;
		    $record['event_name'] .= ', '."<a href='".admin_url("admin.php?page=eme-registration-seats&amp;event_id=".$event['event_id'])."'>".__('Approved:','events-made-easy')." $booked_seats_string</a>";
            } else {
                    $total_seats_string = '&infin;';
		    $record['event_name'] .= "<a href='".admin_url("admin.php?page=eme-registration-seats&amp;event_id=".$event['event_id'])."'>".__('Approved:','events-made-easy')."  $booked_seats_string</a>";
            }

            if ($pending_seats>0) {
               $record['event_name'] .= ', '."<a href='".admin_url("admin.php?page=eme-registration-approval&amp;event_id=".$event['event_id'])."'>".__('Pending:','events-made-easy')."$pending_seats_string</a>";
	    }
	    if ($event['event_properties']['take_attendance']) {
                    $absent_bookings = eme_get_absent_bookings($event['event_id']);
		    if ($absent_bookings>0)
			    $record['event_name'] .= ', '."<a href='".admin_url("admin.php?page=eme-registration-seats&amp;event_id=".$event['event_id'])."'>".__('Absent:','events-made-easy')." $absent_bookings</a>";
	    }
            $record['event_name'] .= ', '.__('Max: ','events-made-easy').$total_seats_string;
	    $waitinglist_seats = intval($event['event_properties']['waitinglist_seats']);
	    if ($waitinglist_seats>0)
               $record['event_name'] .= ' '.sprintf(__('(%d waiting list seats included)','events-made-easy'),$waitinglist_seats);
	    if ($booked_seats>0 || $pending_seats>0) {
               $printable_address = admin_url("admin.php?page=eme-manager&amp;eme_admin_action=booking_printable&amp;event_id=".$event['event_id']);
               $csv_address = admin_url("admin.php?page=eme-manager&amp;eme_admin_action=booking_csv&amp;event_id=".$event['event_id']);
               $record['event_name'] .= " <br />(<a id='booking_printable_".$event['event_id']."' href='$printable_address'>".__('Printable view','events-made-easy')."</a>)";
               $record['event_name'] .= " (<a id='booking_csv_".$event['event_id']."' href='$csv_address'>".__('CSV export','events-made-easy')."</a>)";
            }
      }

      if (empty($event['location_name'])) {
	      $record['location_name']="";
      } else {
	      $record['location_name']= "<a href='".admin_url("admin.php?page=eme-locations&amp;eme_admin_action=edit_location&amp;location_id=".$event['location_id'])."' title='".__('Edit location','events-made-easy')."'>".eme_trans_esc_html($event['location_name'])."</a>";
	      if (!$event['location_latitude'] && !$event['location_longitude'] && get_option('eme_map_is_active') && !$event['location_properties']['online_only'] ) {
		      $record['location_name'] .= "&nbsp;<img style='vertical-align: middle;' src='".$eme_plugin_url."images/warning.png' alt='warning' title='".__('Location map coordinates are empty! Please edit the location to correct this, otherwise it will not show correctly on your website.','events-made-easy')."'>";
	      }
      }

      if (!empty($event['location_address1']) || !empty($event['location_address2']))
         $record['location_name'] .= "<br />". eme_trans_esc_html($event['location_address1']) ." ".eme_trans_esc_html($event['location_address2']);
      if (!empty($event['location_city']) || !empty($event['location_state']) || !empty($event['location_zip']) || !empty($event['location_country']))
         $record['location_name'] .= "<br />". eme_trans_esc_html($event['location_city']) ." ".eme_trans_esc_html($event['location_state'])." ".eme_trans_esc_html($event['location_zip'])." ".eme_trans_esc_html($event['location_country']);
      if (!$event['location_properties']['online_only'] && !empty($event['location_url'])) {
	 $record['location_name'] .= "<br />".eme_trans_esc_html($event['location_url']);
      }


      if (isset ($event_status_array[$event['event_status']])) {
         $record['event_status'] = $event_status_array[$event['event_status']];
         $event_url = eme_event_url($event);
	 if (!$view_trash) {
		 if ($event['event_status'] == EME_EVENT_STATUS_DRAFT)
			 $record['event_status'] .= "<br /> <a href='$event_url'>".__('Preview event','events-made-easy')."</a>";
		 else
			 $record['event_status'] .= "<br /> <a href='$event_url'>".__('View event','events-made-easy')."</a>";
	 }
      }

      $record['copy'] = "<a href='".admin_url("admin.php?page=eme-manager&amp;eme_admin_action=duplicate_event&amp;event_id=".$event['event_id'])."' title='".__('Duplicate this event','events-made-easy')."'><img src='".$eme_plugin_url."images/copy_24.png'/></a>";

      if ($event['event_rsvp'] && !$view_trash) {
         if ($event['registration_requires_approval'])
            $page="eme-registration-approval";
         else
            $page="eme-registration-seats";

	 $record['rsvp'] = "<a href='".wp_nonce_url(admin_url("admin.php?page=$page&amp;eme_admin_action=newBooking&amp;event_id=".$event['event_id']),"eme_admin ".$event['event_id'],'eme_admin_nonce')."' title='".__('Add booking for this event','events-made-easy')."'>".__('RSVP','events-made-easy')."</a>";
	 if (!empty($event['event_properties']['rsvp_password'])) {
		 $record['rsvp'] .= "<br />(".__('Password protected','events-made-easy').")";
	 }
      } else {
         $record['rsvp'] = "";
      }

      if ($event['event_rsvp']) {
	      if (empty($event['price']))
		      $record['eventprice']=__('Free','events-made-easy');
	      else
		      $record['eventprice']=eme_convert_multi2br(eme_localized_price($event['price'],$event['currency']));
      } else {
         $record['eventprice'] = "";
      }

      $record['creation_date'] = eme_localized_datetime($event['creation_date'],$eme_timezone,1);
      $record['modif_date'] = eme_localized_datetime($event['modif_date'],$eme_timezone,1);

      $localized_start_date = eme_localized_date($event['event_start'],$eme_timezone,1);
      $localized_end_date = eme_localized_date($event['event_end'],$eme_timezone,1);
      if ($event['event_properties']['all_day']==1) {
         if ($localized_end_date !='' && $localized_end_date!=$localized_start_date) {
            $record['datetime'] = $localized_start_date .' - '. $localized_end_date;
         } else {
            $record['datetime'] = $localized_start_date;
         }
         $record['datetime'] .= "<br />";
         $record['datetime'] .= __('All day','events-made-easy');
      } else {
         if ($localized_end_date !='' && $localized_end_date!=$localized_start_date) {
            $record['datetime'] = eme_localized_datetime($event['event_start'],$eme_timezone,1) . " - <br />" . eme_localized_datetime($event['event_end'],$eme_timezone,1);
         } else {
            $record['datetime'] = $localized_start_date;
            $record['datetime'] .= "<br />";
            $record['datetime'] .= eme_localized_time($event['event_start'],$eme_timezone,1) . " - " . eme_localized_time($event['event_end'],$eme_timezone,1);
         }
      }

      // if in the past, show it
      if ($date_obj < $eme_date_obj_now) {
         $record['datetime'] = "<span style='text-decoration: line-through;'>".$record['datetime']."</span>";
      }

      if ($event['recurrence_id']>0) {
         $recurrence_desc = eme_get_recurrence_desc ( $event['recurrence_id'] );
	 $record['recinfo'] = "$recurrence_desc <br /> <a href='".admin_url("admin.php?page=eme-manager&amp;eme_admin_action=edit_recurrence&amp;recurrence_id=".$event['recurrence_id'])."'>";
	 $record['recinfo'] .= __('Edit Recurrence','events-made-easy');
	 $record['recinfo'] .= "</a>";
      }

      $event_cf_values=eme_get_event_cf_answers($event['event_id']);
      foreach ($formfields as $formfield) {
	      foreach ($event_cf_values as $val) {
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
      $rows[]=$record;
   }

   $ajaxResult=array();
   $ajaxResult['Result'] = "OK";
   $ajaxResult['TotalRecordCount'] = $events_count;
   $ajaxResult['Records'] = $rows; 
   print json_encode($ajaxResult);
   wp_die();

}

function eme_ajax_manage_events() {
   check_ajax_referer('eme_admin','eme_admin_nonce');
   $ajaxResult=array();
   if (isset($_REQUEST['do_action'])) {
     $do_action=eme_sanitize_request($_REQUEST['do_action']);
     $ids=$_REQUEST['event_id'];
     $ids_arr=explode(',',$ids);
     if (!eme_array_integers($ids_arr) || !current_user_can( get_option('eme_cap_edit_events'))) {
          $ajaxResult['Result'] = "Error";
          $ajaxResult['Message'] = __('Access denied!','events-made-easy');
	  print json_encode($ajaxResult);
          wp_die();
     }

     switch ($do_action) {
         case 'deleteEvents':
              eme_ajax_action_events_delete($ids_arr);
              break;
         case 'trashEvents':
	      $send_trashmails=(isset($_POST['send_trashmails'])) ? intval($_POST['send_trashmails']) : 1;
              eme_ajax_action_events_trash($ids,$send_trashmails);
              break;
         case 'untrashEvents':
              eme_ajax_action_events_untrash($ids);
              break;
         case 'publicEvents':
              eme_ajax_action_events_status($ids_arr,EME_EVENT_STATUS_PUBLIC);
              break;
         case 'privateEvents':
              eme_ajax_action_events_status($ids_arr,EME_EVENT_STATUS_PRIVATE);
              break;
         case 'draftEvents':
              eme_ajax_action_events_status($ids_arr,EME_EVENT_STATUS_DRAFT);
              break;
         case 'addCategory':
	      $category_id=intval($_REQUEST['addtocategory']);
              eme_ajax_action_events_addcat($ids,$category_id);
              break;
     }
   } else {
      $ajaxResult['Result'] = "Error";
      $ajaxResult['Message'] = __('No action defined!','events-made-easy');
      print json_encode($ajaxResult);
   }
   wp_die();
}
   
function eme_ajax_action_events_delete($ids_arr) {
   eme_delete_events($ids_arr);
   $ajaxResult=array();
   $ajaxResult['Result'] = "OK";
   $ajaxResult['Message'] = __('Events deleted','events-made-easy');
   print json_encode($ajaxResult);
}

function eme_ajax_action_events_trash($ids,$send_trashmails) {
   eme_trash_events($ids,$send_trashmails);
   $ajaxResult=array();
   $ajaxResult['Result'] = "OK";
   $ajaxResult['Message'] = __('Events moved to trash','events-made-easy');
   print json_encode($ajaxResult);
}

function eme_ajax_action_events_untrash($ids) {
   eme_untrash_events($ids);
   $ajaxResult=array();
   $ajaxResult['Result'] = "OK";
   $ajaxResult['Message'] = __('Restored selected events to draft status','events-made-easy');
   print json_encode($ajaxResult);
}

function eme_ajax_action_events_status($ids_arr,$status) {
   $ajaxResult=array();
   eme_change_event_status($ids_arr,$status);
   $ajaxResult['Result'] = "OK";
   $ajaxResult['Message'] = __('Events status updated','events-made-easy');
   print json_encode($ajaxResult);
}

function eme_ajax_action_events_addcat($ids,$category_id) {
   global $wpdb;
   $table_name = $wpdb->prefix . EVENTS_TBNAME;
   $sql = "UPDATE $table_name SET event_category_ids = CONCAT_WS(',',event_category_ids,$category_id)
	   WHERE event_id IN ($ids) AND (NOT FIND_IN_SET($category_id,event_category_ids) OR event_category_ids IS NULL)";
   $wpdb->query($sql);
   $ajaxResult['Result'] = "OK";
   $ajaxResult['Message'] = __('Events added to category','events-made-easy');
   print json_encode($ajaxResult);
}

function eme_trash_events($ids,$send_trashmails=0) {
   global $wpdb;
   $table_name = $wpdb->prefix . EVENTS_TBNAME;
   $bookings_table = $wpdb->prefix . BOOKINGS_TBNAME;
   $sql = "UPDATE $table_name SET recurrence_id = 0, event_status = ".EME_EVENT_STATUS_TRASH." WHERE event_id IN ($ids)";
   $wpdb->query($sql);

   if ($send_trashmails) {
	   $event_ids = explode(',', $ids);
	   foreach ($event_ids as $event_id) {
		   $booking_ids = eme_get_bookingids_for($event_id);
		   if (!empty($booking_ids)) {
			   foreach ($booking_ids as $booking_id) {
				   // first get the booking details, then delete it and then send the mail
				   // the mail needs to be sent after the deletion, otherwise the count of free seats is wrong
				   $booking = eme_get_booking ($booking_id);
				   eme_trash_booking($booking_id);
				   eme_email_booking_action($booking,"cancelBooking");
			   }
		   }
	   }
   } else {
	   eme_trash_bookings_for_event_ids($ids);
   }
}

function eme_untrash_events($ids) {
   global $wpdb;
   $table_name = $wpdb->prefix . EVENTS_TBNAME;
   $sql = "UPDATE $table_name SET event_status = ".EME_EVENT_STATUS_DRAFT." WHERE event_id IN ($ids)";
   $wpdb->query($sql);
}

function eme_delete_events($ids_arr) {
      foreach ( $ids_arr as $event_id ) {
         $tmp_event = eme_get_event ( $event_id );
         if ($tmp_event['recurrence_id']>0) {
            # if the event is part of a recurrence and it is the last event of the recurrence, delete the recurrence
            # else just delete the singe event
            if (eme_recurrence_count($tmp_event['recurrence_id'])==1) {
               eme_db_delete_recurrence ($tmp_event['recurrence_id']);
            }
         }
         eme_db_delete_event($event_id);
      }
}

function eme_get_event_post_cfs() {
   $answers=array();
   foreach($_POST as $key =>$value) {
   if (preg_match('/^FIELD(\d+)$/', $key, $matches)) {
         $field_id = intval($matches[1]);
         $formfield = eme_get_formfield($field_id);
         if (!empty($formfield) && $formfield['field_purpose']=='events') {
		 $value = eme_kses_maybe_unfiltered($value);
                 // for multivalue fields like checkbox, the value is in fact an array
                 // to store it, we make it a simple "multi" string using eme_convert_array2multi, so later on when we need to parse the values
                 // (when editing), we can re-convert it to an array with eme_convert_multi2array (see eme_formfields.php)
                 if (is_array($value)) $value=eme_convert_array2multi($value);
                 $answer=array('field_name'=>$formfield['field_name'],'field_id'=>$field_id,'extra_charge'=>$formfield['extra_charge'],'answer'=>$value);
                 $answers[]=$answer;
         }
      }
   }
   return $answers;
}

function eme_get_event_cf_answers($event_id) {
   global $wpdb;
   $answers_table = $wpdb->prefix.ANSWERS_TBNAME; 
   $cf = wp_cache_get("eme_event_cf $event_id");
   if ($cf === false) {
	   $sql = $wpdb->prepare("SELECT * FROM $answers_table WHERE related_id=%d AND type='event'",$event_id);
	   $cf = $wpdb->get_results($sql, ARRAY_A);
	   wp_cache_set("eme_event_cf $event_id", $cf, '', 60);
   }
   return $cf;
}

function eme_event_store_cf_answers($event_id) {
   $answer_ids_seen=array();

   $all_answers=eme_get_event_cf_answers($event_id);
   $found_answers=eme_get_event_post_cfs();
   foreach ($found_answers as $answer) {
	   $formfield = eme_get_formfield($answer['field_id']);
	   if (!empty($formfield) && $formfield['field_purpose']=='events') {
		   $answer_id=eme_get_answerid($all_answers,$event_id,'event',$answer['field_id']);
		   if ($answer_id) {
			   eme_update_answer($answer_id,$answer['answer']);
		   } else {
			   $answer_id=eme_insert_answer('event',$event_id,$answer['field_id'],$answer['answer']);
		   }
		   $answer_ids_seen[]=$answer_id;
	   }
   }

   // delete old answer_ids
   foreach ($all_answers as $answer) {
           if (!in_array($answer['answer_id'],$answer_ids_seen) && $event_id>0 && $answer['type']=='event' && $answer['related_id']==$event_id) {
		   eme_delete_answer($answer['answer_id']);
	   }
   }
   wp_cache_delete("eme_event_cf $event_id");
}

function eme_get_cf_event_ids($val,$field_id,$is_multi=0) {
   global $wpdb;
   $table = $wpdb->prefix.ANSWERS_TBNAME;
   $conditions=array();
   $val=eme_kses($val);

   if (is_array($val)) {
           foreach ($val as $tmpval) {
		   $tmpval=esc_sql($tmpval);
                   if ($is_multi) {
                           $conditions[]="answer REGEXP '^".$tmpval.'|\\\\|'.$tmpval.'\\\\||\\\\|'.$tmpval.'$'."'";
                   } else {
                           $conditions[]="answer LIKE '%$tmpval%'";
                   }
           }
   } else {
	   $val=esc_sql($val);
           if ($is_multi) {
                   $conditions[]="answer REGEXP '^".$val.'|\\\\|'.$val.'\\\\||\\\\|'.$val.'$'."'";
           } else {
                   $conditions[]="answer LIKE '%$val%'";
           }
   }
   $condition="";
   if (!empty($conditions)) {
      $condition =  'AND ('.join(" OR ",$conditions).')';
   }
   $sql = "SELECT DISTINCT related_id FROM $table WHERE field_id=$field_id AND type='event' $condition";

   return $wpdb->get_col($sql);
}

add_action( 'wp_ajax_eme_events_select2', 'eme_ajax_events_select2' );
function eme_ajax_events_select2() {
   global $wpdb, $eme_timezone;

   check_ajax_referer('eme_admin','eme_admin_nonce');
   $current_userid=get_current_user_id();
   $q = isset($_REQUEST['q']) ? strtolower($_REQUEST['q']) : '';
   $search_all = isset($_REQUEST['search_all']) ? intval($_REQUEST['search_all']) : 0;
   if ($search_all) {
	   $scope="all";
   } else {
	   $scope="future";
   }
   $events=eme_search_events($q,$scope,1);
   $records=array();
   $recordCount=0;
   foreach ($events as $event) {
	if (current_user_can( get_option('eme_cap_send_other_mails')) ||
                 (current_user_can( get_option('eme_cap_send_mails')) && ($event['event_author']==$current_userid || $event['event_contactperson_id']==$current_userid))) {
		 $records[]= array(
			 'id' => $event['event_id'],
			 'text' => trim( eme_esc_html( strip_tags($event['event_name'])." (".eme_localized_date($event['event_start'],$eme_timezone,1).")" ) ) ,
		 );
		 $recordCount++;
	}
   }
   $jTableResult['TotalRecordCount'] = $recordCount;
   $jTableResult['Records'] = $records;
   print json_encode($jTableResult);
   wp_die();
}

function eme_get_event_cf_answers_groupingids($event_id) {
	global $wpdb;
	$answers_table = $wpdb->prefix.ANSWERS_TBNAME;
	$bookings_table = $wpdb->prefix.BOOKINGS_TBNAME;
	$sql=$wpdb->prepare("SELECT DISTINCT a.eme_grouping FROM $answers_table a LEFT JOIN $bookings_table b ON b.booking_id=a.related_id WHERE b.event_id=%d AND a.type='booking'",$event_id);
	return $wpdb->get_col($sql);
}
?>
