<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function eme_cron_schedules($schedules){
   if(!isset($schedules["5min"])){
      $schedules["5min"] = array(
            'interval' => 5*60,
            'display' => __('Once every 5 minutes','events-made-easy'));
   }
   if(!isset($schedules["15min"])){
      $schedules["15min"] = array(
            'interval' => 15*60,
            'display' => __('Once every 15 minutes','events-made-easy'));
   }
   if(!isset($schedules["30min"])){
      $schedules["30min"] = array(
            'interval' => 30*60,
            'display' => __('Once every 30 minutes','events-made-easy'));
   }
   if(!isset($schedules["4weeks"])){
      $schedules["4weeks"] = array(
            'interval' => 60*60*24*28,
            'display' => __('Once every 4 weeks','events-made-easy'));
   }
   return $schedules;
}
add_filter('cron_schedules','eme_cron_schedules');

function eme_plan_queue_mails() {
   if (get_option('eme_queue_mails')) {
	   $schedules = wp_get_schedules();
	   // we stored the choosen schedule in the option with the same name eme_cron_send_queued
	   // and take hourly as sensible default if empty
           $schedule = get_option('eme_cron_send_queued');
	   if (empty($schedule) || !isset($schedules[$schedule])) {
		   $schedule = 'hourly';
                   update_option('eme_cron_send_queued',$schedule);
	   }
           if (!wp_next_scheduled('eme_cron_send_queued')) {
                   wp_schedule_event(time(), $schedule, 'eme_cron_send_queued');
           } else {
                   $current_schedule = wp_get_schedule('eme_cron_send_queued');
                   if ($current_schedule != $schedule) {
                           wp_unschedule_hook('eme_cron_send_queued');
                           wp_schedule_event(time(), $schedule, 'eme_cron_send_queued');
                   }
           }
           if (!get_option('eme_cron_queue_count')) {
                   update_option('eme_cron_queue_count',50);
           }
   } else {
           if (wp_next_scheduled('eme_cron_send_queued')) {
                   wp_unschedule_hook('eme_cron_send_queued');
           }
   }
}

add_action('eme_cron_send_new_events', 'eme_cron_send_new_events_function');
function eme_cron_send_new_events_function() {
   // no queuing active? Then no newsletter either
   if (!get_option('eme_queue_mails')) {
	   return;
   }

   $days=intval(get_option('eme_cron_new_events_days'));

   // make sure no mail is sent if no events are planned
   $check_for_events=eme_are_events_available("+".$days."d");
   if (!$check_for_events) return;

   $mail_subject=eme_get_template_format_plain(get_option('eme_cron_new_events_subject'));
   $header=eme_get_template_format_plain(get_option('eme_cron_new_events_header'));
   $entry=eme_get_template_format_plain(get_option('eme_cron_new_events_entry'));
   $footer=eme_get_template_format_plain(get_option('eme_cron_new_events_footer'));
   // get templates, replace people placeholders and then:
   $mail_message=eme_get_events_list(0, "+".$days."d", "ASC", $entry, $header, $footer);

   $person_ids = eme_get_newsletter_person_ids();
   $eme_cron_queue_count=intval(get_option('eme_cron_queue_count'));
   $mail_text_html = get_option('eme_rsvp_send_html')?"htmlmail":"text";
   $contact_email = get_option('eme_mail_sender_address');
   $contact_name = get_option('eme_mail_sender_name');
   if (empty($contact_email)) {
	   $blank_event = eme_new_event();
	   $contact = eme_get_event_contact($blank_event);
	   $contact_email = $contact->user_email;
	   $contact_name = $contact->display_name;
   }

   // we'll create a mailing for the newsletter, so we can delete/cancel if easily while ongoing too
   $eme_date_obj = new ExpressiveDate("now",$eme_timezone);
   $mailing_datetime = $eme_date_obj->getDateTime();
   $mailing_name = "newsletter $mailing_datetime";
   $mailing_id = eme_db_insert_ongoing_mailing($mailing_name,$mail_subject, $mail_message, $contact_email, $contact_name, $mail_text_html);
   // even if we fail to create a mailing, we'll continue
   if (!$mailing_id) $mailing_id = 0;

   foreach ( $person_ids as $person_id ) {
	   $person = eme_get_person($person_id);
	   $tmp_message = eme_replace_people_placeholders($mail_message, $person, $mail_text_html);
	   $person_name = eme_format_full_name($person['firstname'],$person['lastname']);
	   eme_queue_mail($mail_subject,$tmp_message, $person['email'], $person_name, $contact_email, $contact_name, $mailing_id, $person_id);
   }
}

// add an action for the cronjob to map to the cleanup function,
add_action('eme_cron_cleanup_actions', 'eme_cron_cleanup_function');
function eme_cron_cleanup_function() {
   $eme_number=intval(get_option('eme_cron_cleanup_unpaid_minutes'));
   if ($eme_number>0)
	   eme_cleanup_unpaid($eme_number);

   $eme_number=intval(get_option('eme_cron_cleanup_unconfirmed_minutes'));
   if ($eme_number>0)
	   eme_cleanup_unconfirmed($eme_number);

   if (get_option('eme_captcha_for_forms')) {
	$tmp_dir=get_temp_dir();
	foreach (glob($tmp_dir."eme_captcha_*") as $file) {
		// delete captcha files older than 1 hour
		if(time() - filemtime($file) > 3600) {
			unlink($file);
		}
	}
   }
}

add_action('eme_cron_send_queued', 'eme_cron_send_queued');
function eme_cron_send_queued() {
	if (get_option('eme_queue_mails'))
		eme_send_queued();
}

add_action('eme_cron_member_daily_actions', 'eme_cron_member_daily_actions');
function eme_cron_member_daily_actions() {
        eme_member_recalculate_status();
        eme_member_send_expiration_reminders();
        eme_member_remove_pending();
}

add_action('eme_cron_events_daily_actions', 'eme_cron_events_daily_actions');
function eme_cron_events_daily_actions() {
        eme_tasks_send_signup_reminders();
        eme_rsvp_send_pending_reminders();
        eme_rsvp_send_approved_reminders();
}

add_action('eme_cron_gdpr_daily_actions', 'eme_cron_gdpr_daily_actions');
function eme_cron_gdpr_daily_actions() {
        eme_member_remove_expired();
        eme_rsvp_anonymize_old_bookings();
        eme_delete_old_events();
        eme_tasks_remove_old_signups();
        eme_archive_old_mailings();
        eme_delete_old_attendances();
}

add_action('eme_cron_daily_actions', 'eme_cron_daily_actions');
function eme_cron_daily_actions() {
	eme_people_birthday_emails();
}

function eme_cron_page() {
   global $wpdb;
   $bookings_table = $wpdb->prefix.BOOKINGS_TBNAME;

   $message="";
   if (current_user_can( get_option('eme_cap_settings'))) {
      // do the actions if required
      if (isset($_POST['eme_admin_action'])) {
         check_admin_referer('eme_admin','eme_admin_nonce');
         if ($_POST['eme_admin_action'] == "eme_cron_cleanup_unpaid") {
            $eme_cron_cleanup_unpaid_minutes = intval($_POST['eme_cron_cleanup_unpaid_minutes']);
	    if ($eme_cron_cleanup_unpaid_minutes>=5) {
		    update_option('eme_cron_cleanup_unpaid_minutes',$eme_cron_cleanup_unpaid_minutes);
		    $message = sprintf ( __ ( "Scheduled the cleanup of unpaid pending bookings older than %d minutes",'events-made-easy'),$eme_cron_cleanup_unpaid_minutes);
	    } else {
		    update_option('eme_cron_cleanup_unpaid_minutes',0);
		    $message = __ ( "No automatic cleanup of unpaid pending bookings will be done.",'events-made-easy');
	    }
	 } elseif ($_POST['eme_admin_action'] == "eme_cron_cleanup_unconfirmed") {
            $eme_cron_cleanup_unconfirmed_minutes = intval($_POST['eme_cron_cleanup_unconfirmed_minutes']);
	    if ($eme_cron_cleanup_unconfirmed_minutes>=5) {
		    update_option('eme_cron_cleanup_unconfirmed_minutes',$eme_cron_cleanup_unconfirmed_minutes);
		    $message = sprintf ( __ ( "Scheduled the cleanup of unconfirmed bookings older than %d minutes",'events-made-easy'),$eme_cron_cleanup_unconfirmed_minutes);
	    } else {
		    update_option('eme_cron_cleanup_unconfirmed_minutes',0);
		    $message = __ ( "No automatic cleanup of unconfirmed bookings will be done.",'events-made-easy');
	    }
          } elseif ($_POST['eme_admin_action'] == "eme_cron_send_new_events") {
            $eme_cron_new_events_schedule = $_POST['eme_cron_new_events_schedule'];
            $eme_cron_new_events_days = intval($_POST['eme_cron_new_events_days']);
            $eme_cron_new_events_subject = intval($_POST['eme_cron_new_events_subject']);
            $eme_cron_new_events_header = intval($_POST['eme_cron_new_events_header']);
            $eme_cron_new_events_entry = intval($_POST['eme_cron_new_events_entry']);
            $eme_cron_new_events_footer = intval($_POST['eme_cron_new_events_footer']);
	    if (wp_next_scheduled('eme_cron_send_new_events')) {
		    $schedule=wp_get_schedule('eme_cron_send_new_events');
		    if ($schedule != $eme_cron_new_events_schedule)
			    wp_unschedule_hook('eme_cron_send_new_events');
	    }
	    if ($eme_cron_new_events_days>0) {
		    if ($eme_cron_new_events_schedule) {
			    $schedules = wp_get_schedules();
			    if (isset($schedules[$eme_cron_new_events_schedule])) {
				    $new_events_schedule = $schedules[$eme_cron_new_events_schedule];
				    if (!wp_next_scheduled('eme_cron_send_new_events'))
					    wp_schedule_event(time(), $eme_cron_new_events_schedule, 'eme_cron_send_new_events');
				    update_option('eme_cron_new_events_days',$eme_cron_new_events_days);
				    update_option('eme_cron_new_events_subject',$eme_cron_new_events_subject);
				    update_option('eme_cron_new_events_header',$eme_cron_new_events_header);
				    update_option('eme_cron_new_events_entry',$eme_cron_new_events_entry);
				    update_option('eme_cron_new_events_footer',$eme_cron_new_events_footer);
				    $eme_cron_queue_count = get_option('eme_cron_queue_count');
				    $eme_cron_queued_schedule = wp_get_schedule('eme_cron_send_queued');
				    $mail_schedule = $schedules[$eme_cron_queued_schedule];
				    $message = sprintf ( __ ( "%s there will be a check if new events should be mailed to EME registered people (those will then be queued and send out in batches of %d %s)",'events-made-easy'),$new_events_schedule['display'], $eme_cron_queue_count, $mail_schedule['display']);
			    }
		    } else {
			    $message = __ ( "New events will not be mailed to EME registered people.",'events-made-easy');
		    }
	    } else {
		    $message = __ ( "New events will not be mailed to EME registered people.",'events-made-easy');
	    }
          }
      }
   }

   eme_cron_form($message);
}

function eme_cron_form($message = "") {
   $schedules = wp_get_schedules();
?>
<div class="wrap">
<div id="icon-events" class="icon32"><br />
</div>
<h1><?php _e ('Scheduled actions','events-made-easy'); ?></h1>

<?php if($message != "") { ?>
   <div id='message' class='updated eme-message-admin'>
   <p><?php echo $message; ?></p>
   </div>
<?php } else {
   if (!defined('DISABLE_WP_CRON') || (defined('DISABLE_WP_CRON') && !DISABLE_WP_CRON)) {
   	   echo "<div id='message' class='updated eme-message-admin'><p>";
	   _e('Cron tip for more accurate scheduled actions:','events-made-easy');
	   echo '<br />';
	   _e('Put something like this in the crontab of your server:','events-made-easy');
	   echo '<code>*/5 * * * * wget -q -O - '. site_url('/wp-cron.php') .' >/dev/null 2>&1 </code><br />';
	   _e('And add the following to your wp-config.php:','events-made-easy');echo " define('DISABLE_WP_CRON', true);";
	   echo "</p></div>";
   }
} ?>

<br />
   <h2><?php _e('Planned cleanup actions','events-made-easy'); ?></h2>
   <form action="" method="post">
   <label for="eme_cron_cleanup_unpaid_minutes">
   <?php echo wp_nonce_field('eme_admin','eme_admin_nonce',false,false);
      $minutes=intval(get_option('eme_cron_cleanup_unpaid_minutes'));
      _e('Schedule the automatic removal of unpaid pending bookings older than','events-made-easy');
   ?></label>
   <input type="number" id="eme_cron_cleanup_unpaid_minutes" name="eme_cron_cleanup_unpaid_minutes" size="6" maxlength="6" min="0" max="999999" step="5" value="<?php echo $minutes; ?>" />
   <?php _e ( '(value is in minutes, leave empty or 0 to disable the scheduled cleanup)','events-made-easy'); ?>
   <input type='hidden' name='eme_admin_action' value='eme_cron_cleanup_unpaid' />
   <input type="submit" value="<?php _e ( 'Apply' , 'events-made-easy'); ?>" name="doaction" id="eme_doaction" class="button-primary action" />
   </form>
   <form action="" method="post">
   <label for="eme_cron_cleanup_unconfirmed_minutes">
   <?php echo wp_nonce_field('eme_admin','eme_admin_nonce',false,false);
      $minutes=intval(get_option('eme_cron_cleanup_unconfirmed_minutes'));
      _e('Schedule the automatic removal of unconfirmed bookings older than','events-made-easy');
   ?></label>
   <input type="number" id="eme_cron_cleanup_unconfirmed_minutes" name="eme_cron_cleanup_unconfirmed_minutes" size="6" maxlength="6" min="0" max="999999" step="5" value="<?php echo $minutes; ?>" />
   <?php _e ( '(value is in minutes, leave empty or 0 to disable the scheduled cleanup)','events-made-easy'); ?>
   <input type='hidden' name='eme_admin_action' value='eme_cron_cleanup_unconfirmed' />
   <input type="submit" value="<?php _e ( 'Apply' , 'events-made-easy'); ?>" name="doaction" id="eme_doaction" class="button-primary action" />
   </form>
<br />
<hr />
   <h2><?php _e('Mail queue info','events-made-easy'); ?></h2>
<?php
   $eme_queued_count=eme_get_queued_count();
   if ($eme_queued_count>1)
	   echo sprintf(__('There are %d messages in the mail queue.','events-made-easy'),$eme_queued_count);
   elseif ($eme_queued_count)
	   echo __('There is 1 message in the mail queue.','events-made-easy');
   else
	   _e('There are no messages in the mail queue.','events-made-easy');

   if ($eme_queued_count && (!get_option('eme_queue_mails') || !get_option('eme_cron_queue_count') || !wp_next_scheduled('eme_cron_send_queued')) ) {
      echo '<br />';
      _e('WARNING: messages found in the queue but the mail queue is not configured correctly, so they will not be sent out','events-made-easy');
   } else {
	   $eme_cron_send_queued_schedule= wp_get_schedule('eme_cron_send_queued');
	   if (isset($schedules[$eme_cron_send_queued_schedule])) {
		   $schedule = $schedules[$eme_cron_send_queued_schedule];
		   echo '<br />';
		   echo sprintf ( __ ( "Queued mails will be send out in batches of %d %s",'events-made-easy'),get_option('eme_cron_queue_count'),$schedule['display']);
	   }
   }

?>

<br /><br />

<?php
   if (get_option('eme_queue_mails')) {
?>
<hr />
   <h2><?php _e('Newsletter','events-made-easy'); ?></h2>
   <form action="" method="post">
   <?php echo wp_nonce_field('eme_admin','eme_admin_nonce',false,false);
      $eme_cron_new_events=intval(get_option('eme_cron_new_events'));
      $days=intval(get_option('eme_cron_new_events_days'));
      $subject=intval(get_option('eme_cron_new_events_subject'));
      $header=intval(get_option('eme_cron_new_events_header'));
      $entry=intval(get_option('eme_cron_new_events_entry'));
      $footer=intval(get_option('eme_cron_new_events_footer'));
      _e('Send a mail to all EME registered people for upcoming events that will happen in the next','events-made-easy');
   ?>
   <input type="number" id="eme_cron_new_events_days" name="eme_cron_new_events_days" size="6" maxlength="6" min="0" max="999999" step="1" value="<?php echo $days; ?>" /><?php _e ( 'days','events-made-easy');?><br />
   <?php $templates_array=eme_get_templates_array_by_id('rsvpmail'); ?>
   <?php _e ( 'Mail subject template','events-made-easy'); echo eme_ui_select($subject,'eme_cron_new_events_subject',$templates_array); ?><br />
   <?php _e ( 'Mail body header','events-made-easy'); echo eme_ui_select($header,'eme_cron_new_events_header',$templates_array); ?><br />
   <?php _e ( 'Mail body single event entry','events-made-easy'); echo eme_ui_select($entry,'eme_cron_new_events_entry',$templates_array); ?><br />
   <?php _e ( 'Mail body footer','events-made-easy'); echo eme_ui_select($footer,'eme_cron_new_events_footer',$templates_array); ?><br />
   <input type='hidden' name='eme_admin_action' value='eme_cron_send_new_events' />
   <br />
   <select name="eme_cron_new_events_schedule">
   <option value=""><?php _e ( 'Not scheduled','events-made-easy'); ?></option>
   <?php
   $scheduled= wp_get_schedule('eme_cron_send_new_events');
   foreach ($schedules as $key=>$schedule) {
      $selected=($key==$scheduled)? 'selected="selected"':'';
      print "<option $selected value='$key'>".$schedule['display']."</option>";
   }
   ?>
   </select>
   <input type="submit" value="<?php _e ( 'Apply' , 'events-made-easy'); ?>" name="doaction" id="eme_doaction" class="button-primary action" />
   </form>

<?php
   } else {
	   echo '<br />';
	   _e('Mail queueing is not activated.' , 'events-made-easy');
	   echo '<br />';
	   _e('Because mail queueing is not activated, the newsletter functionality is not available.' , 'events-made-easy');
   }
?>


</div>
<?php
}

?>
