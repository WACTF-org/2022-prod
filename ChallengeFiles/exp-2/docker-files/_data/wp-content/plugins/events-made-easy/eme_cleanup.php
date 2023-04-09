<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function eme_cleanup_people() {
   global $wpdb, $eme_timezone;
   $bookings_table = $wpdb->prefix.BOOKINGS_TBNAME;
   $members_table = $wpdb->prefix.MEMBERS_TBNAME;
   $usergroups_table = $wpdb->prefix.USERGROUPS_TBNAME;
   $people_table = $wpdb->prefix.PEOPLE_TBNAME;
   $sql=$wpdb->prepare("SELECT person_id FROM $people_table WHERE person_id NOT IN (SELECT person_id FROM $bookings_table WHERE status != %d) AND person_id NOT IN (SELECT person_id FROM $members_table WHERE status != %d) AND person_id NOT IN (SELECT person_id FROM $usergroups_table) AND status !=%d ",EME_RSVP_STATUS_TRASH,EME_MEMBER_STATUS_EXPIRED,EME_PEOPLE_STATUS_TRASH);
   $person_ids=$wpdb->get_col($sql);
   $count=count($person_ids);
   $tmp_ids=join(",",$person_ids);
   eme_trash_people($tmp_ids);
   return $count;
}

function eme_cleanup_trashed_people($eme_number,$eme_period) {
   global $wpdb, $eme_timezone;

   $people_table = $wpdb->prefix.PEOPLE_TBNAME;

   if ($eme_number<1) $eme_number=1;
   $eme_date_obj=new ExpressiveDate("now",$eme_timezone);
   switch ($eme_period) {
	   case 'day':
		   $eme_date_obj->minusDays($eme_number);
		   break;
	   case 'week':
		   $eme_date_obj->minusWeeks($eme_number);
		   break;
	   default:
		   $eme_date_obj->minusMonths($eme_number);
		   break;
   }
   $datetime=$eme_date_obj->getDateTime();
   $sql="SELECT person_id FROM $people_table WHERE modif_date < '$datetime' AND status = ".EME_PEOPLE_STATUS_TRASH;
   $person_ids=$wpdb->get_col($sql);
   $count=count($person_ids);
   $tmp_ids=join(",",$person_ids);
   eme_delete_people($tmp_ids);
   return $count;
}

function eme_cleanup_unconfirmed($eme_number) {
   global $wpdb, $eme_timezone;

   $bookings_table = $wpdb->prefix.BOOKINGS_TBNAME;
   $members_table = $wpdb->prefix.MEMBERS_TBNAME;
   $events_table = $wpdb->prefix . EVENTS_TBNAME;

   $eme_date_obj=new ExpressiveDate("now",$eme_timezone);
   $today = $eme_date_obj->getDateTime();
   // the min is 5 minutes, but if 0 we won't do anything either, to be safe
   if (!$eme_number) return;
   if ($eme_number<5) $eme_number=5;
   $old_date = $eme_date_obj->minusMinutes($eme_number)->getDateTime();

   $sql = $wpdb->prepare("SELECT bookings.booking_id FROM $bookings_table AS bookings LEFT JOIN $events_table AS events ON bookings.event_id=events.event_id WHERE bookings.status = %d AND bookings.booking_paid = 0 AND events.event_start > %s AND bookings.creation_date < %s",EME_RSVP_STATUS_USERPENDING,$today, $old_date);
   $booking_ids=$wpdb->get_col($sql);
   foreach ($booking_ids as $booking_id) {
       $booking=eme_get_booking($booking_id);
       $person=eme_get_person($booking['person_id']);
       $event=eme_get_event($booking['event_id']);
       if (!empty($event)) {
	       eme_trash_booking($booking_id);
	       eme_manage_waitinglist($event);
       }
       $eme_date_obj_booking_created = new ExpressiveDate($booking['creation_date'],$eme_timezone);
       $eme_date_obj_person_modified = new ExpressiveDate($person['modif_date'],$eme_timezone);
       $diff=abs($eme_date_obj_booking_created->getDifferenceInMinutes($eme_date_obj_person_modified));
       // if the person was modified at most 2 minutes after booking creation (meaning in fact never), we also delete the person if no other bookings or members match that person
       if ($diff<2) {
	       $sql = $wpdb->prepare("select (select count(*) from $bookings_table where person_id=%d AND status != %d) + (select count(*) from $members_table where person_id=%d AND status != %d)",$booking['person_id'],EME_RSVP_STATUS_TRASH,$booking['person_id'],EME_MEMBER_STATUS_EXPIRED);
	       $count = $wpdb->get_var($sql);
	       if ($count == 0) {
		       eme_trash_people($booking['person_id']);
	       }
       }
   }
}

function eme_cleanup_unpaid($eme_number) {
   global $wpdb, $eme_timezone;

   $bookings_table = $wpdb->prefix.BOOKINGS_TBNAME;
   $events_table = $wpdb->prefix . EVENTS_TBNAME;

   $eme_date_obj=new ExpressiveDate("now",$eme_timezone);
   $today = $eme_date_obj->getDateTime();
   // the min is 5 minutes, but if 0 we won't do anything either, to be safe
   if (!$eme_number) return;
   if ($eme_number<5) $eme_number=5;
   $old_date = $eme_date_obj->minusMinutes($eme_number)->getDateTime();

   $sql = $wpdb->prepare("SELECT bookings.booking_id FROM $bookings_table AS bookings LEFT JOIN $events_table AS events ON bookings.event_id=events.event_id WHERE bookings.status = %d AND bookings.booking_paid = 0 AND events.event_start > %s AND bookings.creation_date < %s",EME_RSVP_STATUS_PENDING,$today, $old_date);
   $booking_ids=$wpdb->get_col($sql);
   foreach ($booking_ids as $booking_id) {
       $booking=eme_get_booking($booking_id);
       $event=eme_get_event($booking['event_id']);
       if (!empty($event)) {
	       eme_trash_booking($booking_id);
	       eme_manage_waitinglist($event);
	       eme_email_booking_action($booking,"cancelBooking");
       }
   }
}

function eme_cleanup_events($eme_number,$eme_period) {
   global $wpdb, $eme_timezone;

   $bookings_table = $wpdb->prefix.BOOKINGS_TBNAME;
   $attendances_table = $wpdb->prefix.ATTENDANCES_TBNAME;
   $events_table = $wpdb->prefix . EVENTS_TBNAME;
   $events_cf_table = $wpdb->prefix . EVENTS_CF_TBNAME;
   $recurrence_table = $wpdb->prefix.RECURRENCE_TBNAME;

   if ($eme_number<1) $eme_number=1;
   $eme_date_obj=new ExpressiveDate("now",$eme_timezone);
   switch ($eme_period) {
	   case 'day':
		   $eme_date_obj->minusDays($eme_number);
		   break;
	   case 'week':
		   $eme_date_obj->minusWeeks($eme_number);
		   break;
	   default:
		   $eme_date_obj->minusMonths($eme_number);
		   break;
   }
   $end_datetime=$eme_date_obj->getDateTime();
   $end_date=$eme_date_obj->getDate();
   $wpdb->query("DELETE FROM $bookings_table where event_id in (SELECT event_id from $events_table where event_end<'$end_datetime')");
   $wpdb->query("DELETE FROM $events_cf_table where event_id in (SELECT event_id from $events_table where event_end<'$end_datetime')");
   $wpdb->query("DELETE FROM $attendances_table where type='event' AND related_id in (SELECT event_id from $events_table where event_end<'$end_datetime')");
   $wpdb->query("DELETE FROM $events_table where event_end<'$end_datetime'");
   $wpdb->query("DELETE FROM $recurrence_table where recurence_freq <> 'specific' AND recurrence_end_date<'$end_date'");
}

function eme_cleanup_all_event_related_data($other_data) {
   global $wpdb;

   $tables=array(EVENTS_TBNAME,EVENTS_CF_TBNAME,BOOKINGS_TBNAME,LOCATIONS_TBNAME,LOCATIONS_CF_TBNAME,RECURRENCE_TBNAME,ANSWERS_TBNAME,PAYMENTS_TBNAME,PEOPLE_TBNAME,MEMBERS_TBNAME,MEMBERSHIPS_CF_TBNAME,MEMBERSHIPS_TBNAME,ATTENDANCES_TBNAME);
   if ($other_data) {
      $tables2=array(CATEGORIES_TBNAME,HOLIDAYS_TBNAME,TEMPLATES_TBNAME,FORMFIELDS_TBNAME,COUNTRIES_TBNAME,STATES_TBNAME);
      $tables = array_merge($tables,$tables2);
   }
   foreach ($tables as $table) {
      $wpdb->query("DELETE FROM ".$wpdb->prefix.$table);
   }
}

function eme_cleanup_page() {
   $message="";
   if (current_user_can( get_option('eme_cap_cleanup'))) {
      // do the actions if required
      if (isset($_POST['eme_admin_action'])) {
         check_admin_referer('eme_admin','eme_admin_nonce');
         if ($_POST['eme_admin_action'] == "eme_cleanup_events" && isset($_POST['eme_number']) && isset($_POST['eme_period'])) {
            $eme_number = intval($_POST['eme_number']);
            $eme_period = $_POST['eme_period'];
            if ( !in_array( $eme_period, array( 'day', 'week', 'month' ) ) ) 
               $eme_period = "month";

            if ($eme_number>1) {
		    eme_cleanup_events($eme_number,$eme_period);
		    $message = sprintf ( __ ( "Cleanup done: events (and corresponding booking data) older than %d %s(s) have been removed.",'events-made-easy'),$eme_number,$eme_period);
	    }
         } elseif ($_POST['eme_admin_action'] == "eme_cleanup_unpaid") {
            $eme_number = 0;
            if (isset($_POST['eme_number']))
               $eme_number = intval($_POST['eme_number']);
            if ($eme_number>=5) {
               eme_cleanup_unpaid($eme_number);
               $message = sprintf ( __ ( "Cleanup done: unpaid pending bookings older than %d minutes have been removed.",'events-made-easy'),$eme_number);
            }
         } elseif ($_POST['eme_admin_action'] == "eme_cleanup_unconfirmed") {
            $eme_number = 0;
            if (isset($_POST['eme_number']))
               $eme_number = intval($_POST['eme_number']);
            if ($eme_number>=5) {
               eme_cleanup_unconfirmed($eme_number);
               $message = sprintf ( __ ( "Cleanup done: unconfirmed bookings older than %d minutes have been removed.",'events-made-easy'),$eme_number);
            }
         } elseif ($_POST['eme_admin_action'] == "eme_cleanup_trashed_people") {
               $count=eme_cleanup_trashed_people();
               $message = sprintf ( __ ( "Cleanup done: %d people removed from trash.",'events-made-easy'),$count);
         } elseif ($_POST['eme_admin_action'] == "eme_cleanup_people") {
               $count=eme_cleanup_people();
               $message = sprintf ( __ ( "Cleanup done: %d people who are no longer referenced in bookings, memberships or groups are now trashed.",'events-made-easy'),$count);
         } elseif ($_POST['eme_admin_action'] == 'eme_empty_queue') {
            eme_remove_all_queued();
            $message = __("The mail queue has been cleared.",'events-made-easy');
         } elseif ($_POST['eme_admin_action'] == "eme_cleanup_all_event_related_data") {
            $other_data=0;
            if (isset($_POST['other_data'])) $other_data=1;
            eme_cleanup_all_event_related_data($other_data);
            $message = __ ( "Cleanup done: all data concerning events, locations and bookings have been removed.",'events-made-easy');
         }
      }
   }

   eme_cleanup_form($message);
}

function eme_cleanup_form($message = "") {
$areyousure = esc_html__('Are you sure you want to do this?','events-made-easy');
?>
<div class="wrap">
<div id="icon-events" class="icon32"><br />
</div>
<?php if($message != "") { ?>
<h1><?php _e ('Action info','events-made-easy'); ?></h1>
   <div id='message' class='updated eme-message-admin'>
   <p><?php echo $message; ?></p>
   </div>
<?php } ?>
<h1><?php _e ('Cleanup actions','events-made-easy'); ?></h1>
   <form action="" method="post">
   <label for="eme_number"><?php _e('Remove events older than','events-made-easy'); ?></label>
   <?php echo wp_nonce_field('eme_admin','eme_admin_nonce',false,false); ?>
   <input type='hidden' name='page' value='eme-cleanup' />
   <input type='hidden' name='eme_admin_action' value='eme_cleanup_events' />
   <input type="number" id="eme_number" name="eme_number" size="3" maxlength="3" min="1" max="999" step="1"  />
   <select name="eme_period">
   <option value="day" selected="selected"><?php _e ( 'Day(s)','events-made-easy'); ?></option>
   <option value="week"><?php _e ( 'Week(s)','events-made-easy'); ?></option>
   <option value="month"><?php _e ( 'Month(s)','events-made-easy'); ?></option>
   </select>
   <input type="submit" value="<?php esc_attr_e ( 'Apply' , 'events-made-easy'); ?>" name="doaction" id="eme_doaction" class="button-primary action" onclick="return areyousure('<?php echo $areyousure; ?>');" />
   </form>

<br /><br />
   <form action="" method="post">
   <label for="eme_number"><?php _e('Remove unpaid pending bookings older than','events-made-easy'); ?></label>
   <?php echo wp_nonce_field('eme_admin','eme_admin_nonce',false,false); ?>
   <input type='hidden' name='page' value='eme-cleanup' />
   <input type='hidden' name='eme_admin_action' value='eme_cleanup_unpaid' />
   <input type="number" id="eme_number" name="eme_number" size="6" maxlength="6" min="5" max="999999" step="1" />
   <?php _e ( 'minutes','events-made-easy'); ?>
   <input type="submit" value="<?php esc_attr_e ( 'Apply' , 'events-made-easy'); ?>" name="doaction" id="eme_doaction" class="button-primary action" onclick="return areyousure('<?php echo $areyousure; ?>');" />
   </form>

<br /><br />
   <form action="" method="post">
   <label for="eme_number"><?php _e('Remove unconfirmed bookings older than','events-made-easy'); ?></label>
   <?php echo wp_nonce_field('eme_admin','eme_admin_nonce',false,false); ?>
   <input type='hidden' name='page' value='eme-cleanup' />
   <input type='hidden' name='eme_admin_action' value='eme_cleanup_unconfirmed' />
   <input type="number" id="eme_number" name="eme_number" size="6" maxlength="6" min="5" max="999999" step="1" />
   <?php _e ( 'minutes','events-made-easy'); ?>
   <input type="submit" value="<?php esc_attr_e ( 'Apply' , 'events-made-easy'); ?>" name="doaction" id="eme_doaction" class="button-primary action" onclick="return areyousure('<?php echo $areyousure; ?>');" />
   </form>

<br /><br />
   <form action="" method="post">
   <?php _e('Set status of people who are no longer referenced in bookings, groups or memberships to trash','events-made-easy'); ?>
   <?php echo wp_nonce_field('eme_admin','eme_admin_nonce',false,false); ?>
   <input type='hidden' name='page' value='eme-cleanup' />
   <input type='hidden' name='eme_admin_action' value='eme_cleanup_people' />
   <input type="submit" value="<?php esc_attr_e ( 'Apply' , 'events-made-easy'); ?>" name="doaction" id="eme_doaction" class="button-primary action" onclick="return areyousure('<?php echo $areyousure; ?>');" />
   <br /><?php _e ( 'Tip: If you want to avoid certain people from being trashed through automatic cleanup, put them in a group.' , 'events-made-easy'); ?>
   </form>

<br /><br />
   <form action="" method="post">
   <label for="eme_number"><?php _e('Remove people in thrash older than','events-made-easy'); ?></label>
   <?php echo wp_nonce_field('eme_admin','eme_admin_nonce',false,false); ?>
   <input type='hidden' name='page' value='eme-cleanup' />
   <input type='hidden' name='eme_admin_action' value='eme_cleanup_trashed_people' />
   <input type="number" id="eme_number" name="eme_number" size="3" maxlength="3" min="1" max="999" step="1"  />
   <select name="eme_period">
   <option value="day" selected="selected"><?php _e ( 'Day(s)','events-made-easy'); ?></option>
   <option value="week"><?php _e ( 'Week(s)','events-made-easy'); ?></option>
   <option value="month"><?php _e ( 'Month(s)','events-made-easy'); ?></option>
   </select>
   <input type="submit" value="<?php esc_attr_e ( 'Apply' , 'events-made-easy'); ?>" name="doaction" id="eme_doaction" class="button-primary action" onclick="return areyousure('<?php echo $areyousure; ?>');" />
   </form>
   
<br /><br />
   <form action="" method="post">
<?php _e('Remove all data concerning events, locations, memberships, people and bookings','events-made-easy'); ?>
<?php echo wp_nonce_field('eme_admin','eme_admin_nonce',false,false); ?>
   <input type='hidden' name='page' value='eme-cleanup' />
   <input type='hidden' name='eme_admin_action' value='eme_cleanup_all_event_related_data' />
   <input id="other_data" type="checkbox" value="1" name="other_data"> <?php _e ( 'Also delete defined categories, templates, holidays and form fields' , 'events-made-easy'); ?><br />
   <input type="submit" value="<?php esc_attr_e ( 'Apply' , 'events-made-easy'); ?>" name="doaction" id="eme_doaction" class="button-primary action" onclick="return areyousure('<?php echo $areyousure; ?>');" />
   </form>
<br /><br />

<?php
   $eme_queued_count=eme_get_queued_count();
   if ($eme_queued_count>1)
           echo sprintf(__('There are %d messages in the mail queue.','events-made-easy'),$eme_queued_count);
   elseif ($eme_queued_count)
           echo sprintf(__('There is 1 message in the mail queue.','events-made-easy'),$eme_queued_count);
   else
           _e('There are no messages in the mail queue.','events-made-easy');
   if ($eme_queued_count) {
?>
   <form action="" method="post">
<?php _e('Empty the mail queue','events-made-easy'); ?>
<?php echo wp_nonce_field('eme_admin','eme_admin_nonce',false,false); ?>
   <input type='hidden' name='eme_admin_action' value='eme_empty_queue' />
   <input type="submit" value="<?php esc_attr_e ( 'Apply' , 'events-made-easy'); ?>" name="doaction" id="eme_doaction" class="button-primary action" onclick="return areyousure('<?php echo $areyousure; ?>');" />
   </form>
<br /><br />
<?php
   }
?>

</div>
<?php
}

?>
