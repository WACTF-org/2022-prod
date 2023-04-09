<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function eme_set_wpmail_html_content_type() {
	return "text/html";
}

function eme_send_mail($subject,$body, $receiveremail, $receivername='', $replytoemail='', $replytoname='', $atts=array(),$custom_headers=array()) {
   $subject = preg_replace("/(^\s+|\s+$)/m","",$subject);
   $res=true;
   $message='';

   // nothing to send? Then act as if all is ok
   if (empty($body) || empty($subject) || empty($receiveremail)) {
	   return array($res,$message);
   }

   if (get_option('eme_mail_sender_address') == "") {
      $fromMail = $replytoemail;
      $fromName = $replytoname;
   } else {
      $fromMail = get_option('eme_mail_sender_address');
      $fromName = get_option('eme_mail_sender_name'); // This is the from name in the email, you can put anything you like here
   }

   // get all mail options, put them in an array and apply filter
   // if you change this array, don't forget to update the doc
   $mail_options=array(
	   'fromMail'         => $fromMail,
	   'fromName'         => $fromName,
	   'toMail'           => $receiveremail,
	   'toName'           => $receivername,
	   'replytoMail'      => $replytoemail,
	   'replytoName'      => $replytoname,
	   'bcc_addresses'    => get_option('eme_mail_bcc_address',''),
	   'mail_send_method' => get_option('eme_rsvp_mail_send_method'), // smtp, mail, sendmail, qmail, wp_mail
	   'send_html'        => get_option('eme_rsvp_send_html'), // true or false
	   'smtp_host'        => get_option('eme_smtp_host','localhost'),
	   'smtp_encryption'  => get_option('eme_smtp_encryption'), // none, tls or ssl
	   'smtp_verify_cert' => get_option('eme_smtp_verify_cert'),  // true or false
	   'smtp_port'        => get_option('eme_smtp_port',25),
	   'smtp_auth'        => get_option('eme_rsvp_mail_SMTPAuth'), // 0 or 1, false or true
	   'smtp_username'    => get_option('eme_smtp_username',''),
	   'smtp_password'    => get_option('eme_smtp_password',''),
	   'smtp_debug'       => get_option('eme_smtp_debug')  // true or false
   );
   $mail_options = apply_filters('eme_filter_mail_options',$mail_options);
 
   // now extract all elements as variables, with eme prefix
   extract($mail_options,EXTR_PREFIX_ALL,"mailoption");

   if (empty($mailoption_smtp_host))
	   $mailoption_smtp_host='localhost';
   if (empty($mailoption_smtp_port))
	   $mailoption_smtp_port=25;
   
   $bcc_addresses=preg_split('/,|;/',$mailoption_bcc_addresses);

   // allow either an array of file paths or of attachment ids
   $attachment_paths_arr=array();
   foreach ($atts as $attachment) {
	   if (!empty($attachment)) {
		   if (is_numeric($attachment)) {
			   $file_path=get_attached_file($attachment);
			   if (!empty($file_path) && file_exists($file_path))
				   $attachment_paths_arr[]=$file_path;
		   } else {
			   // if it is not a numeric id, it is a file path (like for pdf tickets)
			   if (file_exists($attachment))
				   $attachment_paths_arr[]=$attachment;
		   }
	   }
   }

   if (!in_array($mailoption_mail_send_method,array('smtp','mail','sendmail','qmail','wp_mail')))
	   $mailoption_mail_send_method = 'wp_mail';

   if ($mailoption_mail_send_method == 'wp_mail') {
      // Set the correct mail headers
      $headers[] = "From: $mailoption_fromName <$mailoption_fromMail>";
      if ($mailoption_replytoMail != "") {
         $headers[] = "Reply-To: $mailoption_replytoName <$mailoption_replytoMail>";
      }
      if (!empty($mailoption_bcc_addresses)) {
         foreach ($bcc_addresses as $bcc_address) {
            $headers[] = "Bcc: ".trim($bcc_address);
	 }
      }
      if (!empty($custom_headers)) {
	      foreach ($custom_headers as $custom_header) {
		      $headers[]=$custom_header;
	      }
      }

      // set the correct content type
      if ($mailoption_send_html) {
	  $body = eme_nl2br_save_html($body);
          add_filter('wp_mail_content_type','eme_set_wpmail_html_content_type');
      }

      // now send it
      if (!empty($mailoption_toMail)) {
	      $res = wp_mail( $mailoption_toMail, $subject, $body, $headers, $attachment_paths_arr );  
	      if (!$res)
		      $message=__('There were some problems while sending mail.','events-made-easy');
      } else {
	      $res=false;
	      $message=__('Empty email','events-made-easy');
      }

      // Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
      if ($mailoption_send_html)
         remove_filter('wp_mail_content_type','eme_set_wpmail_html_content_type');

   } else {
      // we prefer the new location first
      if (file_exists(ABSPATH . WPINC . '/PHPMailer/PHPMailer.php')) {
	 require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
	 require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
	 require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
	 $mail = new PHPMailer\PHPMailer\PHPMailer();
      } else {
	 // for older wp instances (pre 5.5)
         require_once(ABSPATH . WPINC . "/class-phpmailer.php");
         $mail = new PHPMailer();
      }
      
      $mail->ClearAllRecipients();
      $mail->ClearAddresses();
      $mail->ClearAttachments();
      $mail->clearCustomHeaders();
      $mail->clearReplyTos();
      $mail->CharSet = 'utf-8';
      // avoid the x-mailer header
      $mail->XMailer = ' ';
      // add custom headers
      if (!empty($custom_headers)) {
	      foreach ($custom_headers as $custom_header) {
		      $mail->addCustomHeader($custom_header);
	      }
      }
      $mail->SetLanguage('en', dirname(__FILE__).'/');
      $mail->PluginDir = dirname(__FILE__).'/';

      if ($mailoption_mail_send_method == 'qmail')
	      $mail->IsQmail();
      else
	      $mail->Mailer = $mailoption_mail_send_method;

      if ($mailoption_mail_send_method == 'smtp') {
	      // let us keep a normal smtp timeout ...
	      $mail->Timeout = 10;
	      $mail->Host = $mailoption_smtp_host;

	      // we set optional encryption and port settings
	      // but if the Host contains ssl://, tls:// or port info, it will take precedence over these anyway
	      // so it is not bad at all :-)
	      if ($mailoption_smtp_encryption == "tls" || $mailoption_smtp_encryption == "ssl") {
		      $mail->SMTPSecure=$mailoption_smtp_encryption;
	      } else {
		      // if we don't want encryption, let's disable autotls too, since that might be a problem
		      $mail->SMTPAutoTLS=false;
	      }

	      if (!$mailoption_smtp_verify_cert) {
		      // let's disable certificate verification, but only for reserved ranges
		      // weirdly the private range filter doesn't contain 127.0.0.0/8, so we use reserved
		      //    range which is still internal
		      $tmp_ip=$mail->Host;
		      // remove the possible ssl:// or tls://
		      $tmp_ip=preg_replace('/.*?:\/\//','',$tmp_ip);
		      // if the host setting is not an ip, resolve it and get the ip
		      if (!filter_var($tmp_ip, FILTER_VALIDATE_IP)) {
			      $lookup = dns_get_record($tmp_ip);
			      if ($lookup) {
				      foreach ($lookup as $res) {
					      if (isset($res['ip'])) {
						      $tmp_ip=$res['ip'];;
					      } elseif (isset($res['ipv6'])) {
						      $tmp_ip=$res['ipv6'];;
					      }
					      // we're only interested in 1 result
					      break;
				      }
			      }
		      }

		      $in_reserved_range=0;
		      if (filter_var($tmp_ip, FILTER_VALIDATE_IP)
			      && !filter_var($tmp_ip, FILTER_VALIDATE_IP,FILTER_FLAG_NO_RES_RANGE)) {
			      // not reserved? then we still set it only if the ip is valid
			      $in_reserved_range=1;
		      }

		      // so now we disable cert verification as requested and allow self signed
		      // but only for ip's in the reserved range
		      if ($in_reserved_range) {
			      $mail->SMTPOptions = array(
				      'ssl' => array(
					      'verify_peer' => false,
					      'verify_peer_name' => false,
					      'allow_self_signed' => true
				      )
			      );
		      }
	      }

	      $mail->Port = intval($mailoption_smtp_port);

	      if ($mailoption_smtp_auth) {
		      $mail->SMTPAuth = true;
		      $mail->Username = $mailoption_smtp_username;
		      $mail->Password = $mailoption_smtp_password;
	      }
	      if ($mailoption_smtp_debug) {
		      $mail->SMTPDebug = 2;
		      $GLOBALS['eme_smtp_debug'] = "";
		      $mail->Debugoutput = function($str, $level) {
			      $GLOBALS['eme_smtp_debug'] .= "$level: $str\n";
		      };
	      }
      }
      $mail->setFrom($mailoption_fromMail,$mailoption_fromName);
      $altbody = eme_replacelinks($body);
      if ($mailoption_send_html) {
	      $mail->isHTML(true);
	      // Convert all message body line breaks to CRLF, makes quoted-printable encoding work much better
	      $mail->AltBody = $mail->normalizeBreaks($mail->html2text($altbody));
	      $mail->Body = $mail->normalizeBreaks(eme_nl2br_save_html($body));
      } else {
	      $mail->Body = $mail->normalizeBreaks($altbody);
      }
      $mail->Subject = $subject;
      if (!empty($mailoption_replytoMail)) {
	      $mail->addReplyTo($mailoption_replytoMail,$mailoption_replytoName);
      }
      if (!empty($mailoption_bcc_addresses)) {
	      foreach ($bcc_addresses as $bcc_address) {
		      $mail->addBCC(trim($bcc_address));
	      }
      }

      if (!empty($attachment_paths_arr)) {
	      foreach ($attachment_paths_arr as $att) {
		      $mail->addAttachment($att);
	      }
      }

      if (!empty($mailoption_toMail)) {
	      $mail->addAddress($mailoption_toMail,$mailoption_toName);
	      if(!$mail->send()){
		      $res=false;
		      $message=$mail->ErrorInfo;
	      } else {
		      $res=true;
	      }
      } else {
	      $res=false;
	      $message=__('Empty email','events-made-easy');
      }
   }
   // remove the phpmailer url added for some errors
   $message = str_replace('https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting','',$message);
   return array($res,$message);
}

function eme_db_insert_ongoing_mailing($mailing_name, $subject, $body, $replytoemail, $replytoname, $mail_text_html, $conditions=array()) {
	global $wpdb;
	$mailing_table = $wpdb->prefix.MAILINGS_TBNAME;
	$now=current_time('mysql',false);
	$mailing = array(
		'name'=>mb_substr($mailing_name,0,255),
		'planned_on'=>$now,
		'status'=>'ongoing',
		'subject'=>$subject,
		'body'=>$body,
		'replytoemail'=>$replytoemail,
		'replytoname'=>$replytoname,
		'mail_text_html'=>$mail_text_html,
		'creation_date'=>$now,
		'conditions'=>serialize($conditions)
	);
	if ($wpdb->insert($mailing_table, $mailing) === false) {
		return false;
	} else {
		return $wpdb->insert_id;
	}
}

function eme_db_insert_mailing($mailing_name, $planned_on, $subject, $body, $replytoemail, $replytoname, $mail_text_html, $conditions) {
	global $wpdb;
	$mailing_table = $wpdb->prefix.MAILINGS_TBNAME;
	$now=current_time('mysql',false);
	$mailing = array(
		'name'=>mb_substr($mailing_name,0,255),
		'planned_on'=>$planned_on,
		'status'=>'initial',
		'subject'=>$subject,
		'body'=>$body,
		'replytoemail'=>$replytoemail,
		'replytoname'=>$replytoname,
		'mail_text_html'=>$mail_text_html,
		'creation_date'=>$now,
		'conditions'=>serialize($conditions)
	);
	if ($wpdb->insert($mailing_table, $mailing) === false) {
		return false;
	} else {
		return $wpdb->insert_id;
	}
}

function eme_queue_fastmail($subject,$body, $receiveremail, $receivername, $replytoemail, $replytoname, $mailing_id=0, $person_id=0, $member_id=0,$atts=array()) {
	return eme_queue_mail($subject,$body, $receiveremail, $receivername, $replytoemail, $replytoname, $mailing_id, $person_id, $member_id, $atts, 1);
}

function eme_queue_mail($subject,$body, $receiveremail, $receivername, $replytoemail, $replytoname, $mailing_id=0, $person_id=0, $member_id=0,$atts=array(),$send_immediately=0) {
	global $wpdb;
	$mqueue_table = $wpdb->prefix.MQUEUE_TBNAME;

	if (eme_is_empty_string($subject) || eme_is_empty_string($body)) {
		// no mail to be sent: fake it
		return true;
	}

	$random_id=eme_random_id();
	$custom_headers=array('X-EME-mailid:'.$random_id);
	if (!get_option('eme_queue_mails')) {
                //return eme_send_mail($subject,$body, $receiveremail, $receivername, $replytoemail, $replytoname, $atts, $custom_headers);
		$send_immediately=1;
        }

	$now=current_time('mysql',false);
	$mail = array(
		'subject'=>mb_substr($subject,0,255),
		'body'=>$body,
		'receiveremail'=>$receiveremail,
		'receivername'=>mb_substr($receivername,0,255),
		'replytoemail'=>$replytoemail,
		'replytoname'=>mb_substr($replytoname,0,255),
		'mailing_id'=>$mailing_id,
		'person_id'=>$person_id,
		'member_id'=>$member_id,
		'attachments'=>serialize($atts),
		'creation_date'=>$now,
		'random_id'=>$random_id
	);
	if ($send_immediately) {
		// we add the mail to the queue as sent and send it immediately
		$mail['status'] = 1;
		$mail['sent_datetime']=$now;
		$wpdb->insert($mqueue_table, $mail);
		return eme_send_mail($subject,$body, $receiveremail, $receivername, $replytoemail, $replytoname, $atts, $custom_headers);
	} else {
		if ($wpdb->insert($mqueue_table, $mail) === false) {
			return false;
		} else {
			return true;
		}
	}
}

function eme_mark_mail_ignored($id) {
	global $wpdb;
	$mqueue_table = $wpdb->prefix.MQUEUE_TBNAME;
	$where = array();
	$fields = array();
	$where['id'] = intval($id);
	$fields['status'] = 4;
	$fields['sent_datetime']=current_time('mysql', false);
	if ($wpdb->update($mqueue_table, $fields, $where) === false)
		return false;
	else return true;
}

function eme_mark_mail_sent($id) {
	global $wpdb;
	$mqueue_table = $wpdb->prefix.MQUEUE_TBNAME;
	$where = array();
	$fields = array();
	$where['id'] = intval($id);
	$fields['status'] = 1;
	$fields['sent_datetime']=current_time('mysql', false);
	if ($wpdb->update($mqueue_table, $fields, $where) === false)
		return false;
	else return true;
}

function eme_mark_mail_fail($id,$error_msg="") {
	global $wpdb;
	$mqueue_table = $wpdb->prefix.MQUEUE_TBNAME;
	$where = array();
	$fields = array();
	$where['id'] = intval($id);
	$fields['status'] = 2;
	$fields['error_msg'] = esc_sql($error_msg);
	$fields['sent_datetime']=current_time('mysql', false);
	if ($wpdb->update($mqueue_table, $fields, $where) === false)
		return false;
	else return true;
}

function eme_remove_all_queued() {
	global $wpdb;
	$mqueue_table = $wpdb->prefix.MQUEUE_TBNAME;
	$sql = "DELETE FROM $mqueue_table WHERE status=0";
	return $wpdb->query($sql);
}

function eme_get_queued_count() {
	global $wpdb;
	$mqueue_table = $wpdb->prefix.MQUEUE_TBNAME;
	// the queued count is to know how much mails are left unsent in the queue
	$sql = "SELECT COUNT(*) FROM $mqueue_table WHERE status=0";
        return $wpdb->get_var($sql);
}

function eme_get_queued($now) {
	global $wpdb;
	$mqueue_table = $wpdb->prefix.MQUEUE_TBNAME;
	$mailings_table = $wpdb->prefix.MAILINGS_TBNAME;
	// we take only the queued mails with status=0 where either the planning date for the mailing has passed (so we know those can be send) or that are not part of a mailing
	$sql = "SELECT mqueue.* FROM $mqueue_table AS mqueue LEFT JOIN $mailings_table AS mailings ON mqueue.mailing_id=mailings.id WHERE mqueue.status=0 AND (mqueue.mailing_id=0 OR (mqueue.mailing_id>0 and mailings.planned_on<'$now'))";
	$eme_cron_queue_count=intval(get_option('eme_cron_queue_count'));
	if ($eme_cron_queue_count>0)
		$sql .= " LIMIT $eme_cron_queue_count";
        return $wpdb->get_results($sql, ARRAY_A);
}

function eme_send_queued() {
	global $eme_timezone;
	$eme_rsvp_send_html = get_option('eme_rsvp_send_html');

	// we use $now as an argument for eme_get_passed_planned_mailings and eme_get_queued
	// Reason: since eme_check_mailing_receivers can take some time, we want to make sure that 
	// both eme_get_passed_planned_mailings and eme_get_queued are talking about the same 'past'
	$eme_date_obj_now=new ExpressiveDate("now",$eme_timezone);
	$now=$eme_date_obj_now->getDateTime();
	// for all planned mailings in the passed: re-evaluate the receivers
	// since we mark the mailing as ongoing afterwards, this re-evalution only happens once
	// and as such doesn't get in the way of eme_get_queued doing it's work
	$passed_planned_mailings=eme_get_passed_planned_mailings($now); 
	foreach ($passed_planned_mailings as $mailing_id) {
		// the next function call can take a while
		eme_check_mailing_receivers($mailing_id);
		eme_mark_mailing_ongoing($mailing_id);
	}

	// now handle any queued mails
	$mails = eme_get_queued($now);
	if ($eme_rsvp_send_html && get_option('eme_mail_tracking'))
		$add_tracking = true;
	else
		$add_tracking = false;
	foreach ($mails as $mail) {
		if (empty($mail['receiveremail'])) {
			eme_mark_mail_ignored($mail['id']);
			continue; // the continue-statement continues the higher foreach-loop
		}
		$body=$mail['body'];
		$atts=unserialize($mail['attachments']);
		if ($add_tracking && !empty($mail['random_id'])) {
				$track_url=eme_tracker_url($mail['random_id']);
				$track_html="<img src='$track_url' alt='' />";
				// if a closing body-tag is present, add it before that
				// otherwise add it to the end
				if (strstr($body,'</body>'))
					$body=str_replace('</body>',$track_html.'</body>',$body);
				else
					$body.=$track_html;
		}
		$custom_headers=array('X-EME-mailid:'.$mail['random_id']);
		$mail_res_arr = eme_send_mail($mail['subject'],$body, $mail['receiveremail'], $mail['receivername'], $mail['replytoemail'], $mail['replytoname'], $atts,$custom_headers);
		if ($mail_res_arr[0]) {
			eme_mark_mail_sent($mail['id']);
		} else {
			eme_mark_mail_fail($mail['id'],$mail_res_arr[1]);
		}
	}

	// and for the mailings that were marked ongoing, mark them as finished if appropriate
	$ongoing_mailings=eme_get_ongoing_mailings(); 
	foreach ($ongoing_mailings as $mailing_id) {
		if (eme_count_mails_to_sent($mailing_id)==0) {
			eme_mark_mailing_completed($mailing_id);
		}
	}
}

function eme_get_passed_planned_mailings($now) {
	global $wpdb;
	$mailings_table = $wpdb->prefix.MAILINGS_TBNAME;
	$sql = "SELECT id FROM $mailings_table WHERE status='planned' AND planned_on<'$now'";
        return $wpdb->get_col($sql);
}

function eme_get_ongoing_mailings() {
	global $wpdb;
	$mailings_table = $wpdb->prefix.MAILINGS_TBNAME;
	$sql = "SELECT id FROM $mailings_table WHERE status='ongoing'";
        return $wpdb->get_col($sql);
}

function eme_mark_mailing_planned($mailing_id) {
	global $wpdb;
	$mailings_table = $wpdb->prefix.MAILINGS_TBNAME;
	$sql = $wpdb->prepare("UPDATE $mailings_table set status='planned' where id=%d",$mailing_id);
        $wpdb->query($sql);
}

function eme_mark_mailing_ongoing($mailing_id) {
	global $wpdb;
	$mailings_table = $wpdb->prefix.MAILINGS_TBNAME;
	$sql = $wpdb->prepare("UPDATE $mailings_table set status='ongoing' where id=%d",$mailing_id);
        $wpdb->query($sql);
}

function eme_mark_mailing_completed($mailing_id) {
	global $wpdb;
	$stats=eme_get_mailing_stats($mailing_id);
	$mailings_table = $wpdb->prefix.MAILINGS_TBNAME;
	$sql = $wpdb->prepare("UPDATE $mailings_table set status='completed', stats=%s where id=%d",serialize($stats),$mailing_id);
        $wpdb->query($sql);
	if ($stats['failed']>0) {
		$mailing=eme_get_mailing($mailing_id);
		$failed_subject=__('Mailing completed with errors','events-made-easy');
		$failed_body=sprintf(__('Mailing "%s" completed with %d errors, please check the mailing report','events-made-easy'), $mailing['name'],$stats['failed']);
		eme_send_mail($failed_subject,$failed_body, $mailing['replytoemail'], $mailing['replytoname'], $mailing['replytoemail'], $mailing['replytoname']);	
	}
}

function eme_archive_mailing($mailing_id) {
	global $wpdb;
	$mailing=eme_get_mailing($mailing_id);
	if ($mailing['status']=='planned' || $mailing['status']=='ongoing')
		return;
	$mailings_table = $wpdb->prefix.MAILINGS_TBNAME;
	$stats=serialize(eme_get_mailing_stats($mailing_id));
	$sql = $wpdb->prepare("UPDATE $mailings_table SET status='archived', stats=%s WHERE id=%d",$stats,$mailing_id);
        $wpdb->query($sql);
	$queue_table = $wpdb->prefix.MQUEUE_TBNAME;
	$sql = $wpdb->prepare("DELETE FROM $queue_table where mailing_id=%d",$mailing_id);
        $wpdb->query($sql);
}

// for GDPR CRON
function eme_archive_old_mailings() {
   	global $wpdb,$eme_timezone;
	$archive_old_mailings_days = get_option('eme_gdpr_archive_old_mailings_days');
	if (empty($archive_old_mailings_days))
		return;
	else
		$archive_old_mailings_days=abs($archive_old_mailings_days);
	$eme_date_obj=new ExpressiveDate("now",$eme_timezone);
	$now = $eme_date_obj->getDateTime();
	$old_date = $eme_date_obj->minusDays($archive_old_mailings_days)->getDateTime();
	$mailings_table = $wpdb->prefix.MAILINGS_TBNAME;
	$sql = "SELECT id from $mailings_table WHERE creation_date < '$old_date' AND (status='completed' OR status='cancelled')";
	$mailing_ids = $wpdb->get_col($sql);
	foreach ($mailing_ids as $mailing_id) {
		eme_archive_mailing($mailing_id);
	}

	// now remove old mails not belonging to a specific mailing
	$queue_table = $wpdb->prefix.MQUEUE_TBNAME;
	$sql = "DELETE FROM $queue_table where mailing_id=0 AND creation_date < '$old_date'";
        $wpdb->query($sql);
}

function eme_cancel_mail($mail_id) {
	global $wpdb;
	$queue_table = $wpdb->prefix.MQUEUE_TBNAME;
	$sql = $wpdb->prepare("UPDATE $queue_table SET status=3 WHERE status=0 AND id=%d",$mail_id);
        $wpdb->query($sql);
}

function eme_cancel_mailing($mailing_id) {
	global $wpdb;
	$queue_table = $wpdb->prefix.MQUEUE_TBNAME;
	$sql = $wpdb->prepare("UPDATE $queue_table SET status=3 WHERE status=0 AND mailing_id=%d",$mailing_id);
        $wpdb->query($sql);
	$stats=serialize(eme_get_mailing_stats($mailing_id));
	$mailings_table = $wpdb->prefix.MAILINGS_TBNAME;
	$sql = $wpdb->prepare("UPDATE $mailings_table SET status='cancelled', stats=%s WHERE id=%d",$stats,$mailing_id);
        $wpdb->query($sql);
}

function eme_delete_mailing_mails($id) {
	global $wpdb;
	$queue_table = $wpdb->prefix.MQUEUE_TBNAME;
	$sql = $wpdb->prepare("DELETE FROM $queue_table where mailing_id=%d",$id);
        $wpdb->query($sql);
}
function eme_delete_mailing($id) {
	global $wpdb;
	eme_delete_mailing_mails($id);
	$mailings_table = $wpdb->prefix.MAILINGS_TBNAME;
	$sql = $wpdb->prepare("DELETE FROM $mailings_table where id=%d",$id);
        $wpdb->query($sql);
}

function eme_get_mail($id) {
	global $wpdb;
	$table = $wpdb->prefix.MQUEUE_TBNAME;
	$sql = $wpdb->prepare("SELECT * from $table WHERE id=%d",$id);
        return $wpdb->get_row($sql, ARRAY_A);
}

function eme_get_mailing($id) {
	global $wpdb;
	$mailings_table = $wpdb->prefix.MAILINGS_TBNAME;
	$sql = $wpdb->prepare("SELECT * from $mailings_table WHERE id=%d",$id);
        return $wpdb->get_row($sql, ARRAY_A);
}
function eme_get_mailings($archive=0) {
	global $wpdb;
	$mailings_table = $wpdb->prefix.MAILINGS_TBNAME;
	if ($archive)
		$where=" WHERE status='archived' ";
	else
		$where=" WHERE status<>'archived' ";
	$sql = "SELECT * from $mailings_table $where ORDER BY planned_on,name";
        return $wpdb->get_results($sql, ARRAY_A);
}

function eme_mail_states() {
	$states = array(
		0 => 'planned',
		1 => 'sent',
		2 => 'failed',
		3 => 'cancelled',
		4 => 'ignored'
	);
	return $states;
}
function eme_mail_localizedstates() {
	$states = array(
		0 => __('Planned','events-made-easy'),
		1 => __('Sent','events-made-easy'),
		2 => __('Failed','events-made-easy'),
		3 => __('Cancelled','events-made-easy'),
		4 => __('Ignored','events-made-easy')
	);
	return $states;
}

function eme_count_mails_to_sent($mailing_id) {
	global $wpdb;
	$table = $wpdb->prefix.MQUEUE_TBNAME;
	$sql = $wpdb->prepare("SELECT COUNT(*) FROM $table WHERE status=0 AND mailing_id=%d",$mailing_id);
        return $wpdb->get_var($sql);
}

function eme_get_mailing_stats($mailing_id=0) {
	global $wpdb;
	$table = $wpdb->prefix.MQUEUE_TBNAME;
	$sql = "SELECT COUNT(*) AS count,status FROM $table WHERE mailing_id=$mailing_id GROUP BY mailing_id,status";
        $lines = $wpdb->get_results($sql, ARRAY_A);
	$res=array('planned'=>0,'sent'=>0,'failed'=>0,'cancelled'=>0,'ignored'=>0,'total_read_count'=>0);
	$states=eme_mail_states();
	foreach ($lines as $line) {
		$status=$states[$line['status']];
		$res[$status]=$line['count'];
	}
	return $res;
}

function eme_mail_track($random_id) {
	global $wpdb, $eme_timezone;
	$table = $wpdb->prefix.MQUEUE_TBNAME;
	$mailings_table = $wpdb->prefix.MAILINGS_TBNAME;

	if (!empty($random_id) && get_option('eme_mail_tracking')) {
		// we'll randomly sleep between 0 and 20 times 0.1 seconds (100000 microseconds)
		// so if 2 requests for the same id arrive at the same time, it will hopefully not do the select at the same time
		// Without the random sleep, 2 request for the same id would cause the read_count in the mailings_table to be updated too much (nothing to worry about, but it wouldn't reflect reality)
		// /usleep(rand(0, 20)*100000); // we do it in microseconds with random, is better than simple sleep(rand(0,2)) which could return the same result for rand too often
		$sql = $wpdb->prepare("SELECT * FROM $table WHERE random_id=%s",$random_id);
		$queued_mail= $wpdb->get_row($sql, ARRAY_A);
		if ($queued_mail) {
		    	// update the queue table when the mail was read for the first time
			$eme_date_obj_now=new ExpressiveDate("now",$eme_timezone);
			// ignore if the same track arrives within the firt 2 minutes
			$ignore=0;
			if (!eme_is_empty_datetime($queued_mail['last_read_on'])) {
				$eme_date_obj_lastread=new ExpressiveDate($queued_mail['last_read_on'],$eme_timezone);
				if ($eme_date_obj_lastread->getDifferenceInMinutes($eme_date_obj_now) < 2) {
					$ignore=1;
				}
			}
			if (!$ignore) {
				$now=$eme_date_obj_now->getDateTime();
				// we add the read_count=0 to the SQL statement so we know that 2 identical queries arriving almost at the same time will not cause the same update
				$sql = $wpdb->prepare("UPDATE $table SET first_read_on=%s, last_read_on=%s, read_count=1 WHERE id = %d AND read_count=0",$now, $now, $queued_mail['id']);
				$res=$wpdb->query($sql);
				// update the mailing table with the count of times the mail was read
				// read_count in the mailings_table is the unique read count for this mailing 
				if ($res!==false) {
					if ($res>0) {
						// res is >0, meaning a row was changed, so it was read for the first time
						if ($queued_mail['mailing_id']>0) {
							$sql = $wpdb->prepare("UPDATE $mailings_table SET read_count=read_count+1, total_read_count=total_read_count+1 WHERE id = %d",$queued_mail['mailing_id']);
							$wpdb->query($sql);
						}
					} else {
						// no row changed, meaning the mail was already read once, so do it without read_count=0 check
						$sql = $wpdb->prepare("UPDATE $table SET last_read_on=%s, read_count=read_count+1 WHERE id = %d", $now, $queued_mail['id']);
						$res=$wpdb->query($sql);
						if (!empty($res) && $queued_mail['mailing_id']>0) { // not false and >0
							$sql = $wpdb->prepare("UPDATE $mailings_table SET total_read_count=total_read_count+1 WHERE id = %d",$queued_mail['mailing_id']);
							$wpdb->query($sql);
						}
					}
				}
			}
		}

		// always return a transparant image of 1x1
		$eme_plugin_dir=eme_plugin_dir();
		header('Content-Type: image/gif');
		//$image = file_get_contents($eme_plugin_dir.'images/1x1.gif');
		//echo $image;
		readfile($eme_plugin_dir.'images/1x1.gif');
	}
}

function eme_check_mailing_receivers($mailing_id) {
	global $eme_timezone;
	if (!$mailing_id) return;
	$mailing=eme_get_mailing($mailing_id);
	if (!$mailing || empty($mailing['conditions'])) return;
	// don't update the receivers if the mailing was created less than 5 minutes ago
	$eme_date_obj_now=new ExpressiveDate("now",$eme_timezone);
	$eme_date_obj_created=new ExpressiveDate($mailing['creation_date'],$eme_timezone);
	if ($eme_date_obj_created->getDifferenceInMinutes($eme_date_obj_now) <= 5) {
		return;
	}
	$conditions=unserialize($mailing['conditions']);
	// we delete all planned mails for the mailing and enter the mails anew, this allows us to have all mails with the latest content and receivers
	eme_delete_mailing_mails($mailing_id);
	eme_update_mailing_receivers($mailing['subject'],$mailing['body'],$mailing['replytoemail'],$mailing['replytoname'],$mailing['mail_text_html'],$conditions,$mailing_id);
}

function eme_update_mailing_receivers($mail_subject,$mail_message, $contact_email, $contact_name, $mail_text_html, $conditions,$mailing_id=0) {
	$res['mail_problems']=0;
	$res['not_sent']='';
	$mail_subject = eme_replace_generic_placeholders($mail_subject);
	$mail_message = eme_replace_generic_placeholders($mail_message);
	$not_sent=array();
	$emails_handled=array();
	if (isset($conditions['ignore_massmail_setting']) && $conditions['ignore_massmail_setting']==1) {
		$ignore_massmail_setting=1;
	} else {
		$ignore_massmail_setting=0;
	}

	$atts=array();
	$attachment_ids="";
	if ($conditions['action']=='genericmail') {
		$person_ids = array();
		$member_ids = array();
		$cond_person_ids_arr = array();
		$cond_member_ids_arr = array();
		if (isset($conditions['eme_generic_attach_ids']))
			$attachment_ids=$conditions['eme_generic_attach_ids'];
                if (!empty($attachment_ids)) {
			$attachment_id_arr=explode(",",$attachment_ids);
		} else {
			$attachment_id_arr=array();
		}

		if (isset($conditions['eme_send_all_people'])) {
			// although we check later on the massmail preference per person too, we optimize the sql load a bit
			if ($ignore_massmail_setting)
				$person_ids = eme_get_allmail_person_ids();
			else
				$person_ids = eme_get_massmail_person_ids();
		} else {
			if (isset($conditions['eme_genericmail_send_persons'])) {
				$cond_person_ids_arr = explode(',',$conditions['eme_genericmail_send_persons']);
				$person_ids = $cond_person_ids_arr;
			}
			if (isset($conditions['eme_send_members'])) {
				$cond_member_ids_arr = explode(',',$conditions['eme_send_members']);
				$member_ids = $cond_member_ids_arr;
			}
			if (isset($conditions['eme_genericmail_send_peoplegroups'])) {
				$person_ids = array_unique(array_merge($person_ids,eme_get_groups_person_ids($conditions['eme_genericmail_send_peoplegroups'])));
			}
			if (isset($conditions['eme_genericmail_send_membergroups'])) {
				$member_ids = array_unique(array_merge($member_ids,eme_get_groups_member_ids($conditions['eme_genericmail_send_membergroups'])));
			}
			if (isset($conditions['eme_send_memberships'])) {
				$member_ids = array_unique(array_merge($member_ids,eme_get_memberships_member_ids($conditions['eme_send_memberships'])));
			}
		}
		foreach ( $member_ids as $member_id ) {
			$member = eme_get_member($member_id);
			$person = eme_get_person($member['person_id']);
			// if corresponding person has no massmail preference, then skip him unless the name was speficially defined as standalone member to mail to
			if (!$ignore_massmail_setting && !$person['massmail'] && !in_array($member_id,$cond_member_ids_arr)) continue;
			$person_name=eme_format_full_name($person['firstname'],$person['lastname']);
			if (eme_is_email($person['email'])) {
				// we will NOT ignore double emails for member-related mails
				// we could postpone the placeholder replacement until the moment of actual sending (for large number of mails)
				// but that complicates the queue-code and is in fact ugly (I did it, but removed it on 2017-12-04)
				// Once I hit execution timeouts I'll rethink it again
				$membership=eme_get_membership($member['membership_id']);
				$tmp_subject = eme_replace_member_placeholders($mail_subject, $membership, $member, "text");
				$tmp_message = eme_replace_member_placeholders($mail_message, $membership, $member, $mail_text_html);
				$mail_res = eme_queue_mail($tmp_subject,$tmp_message, $person['email'], $person_name, $contact_email, $contact_name, $mailing_id, 0, $member_id, $attachment_id_arr);
				if (!$mail_res) $res['mail_problems']=1;
				$emails_handled[]=$person['email'];
			} else {
				$res['mail_problems']=1;
				$not_sent[]=$person_name;
			}
		}
		foreach ( $person_ids as $person_id ) {
			$person = eme_get_person($person_id);
			// if person has no massmail preference, then skip him unless the name was speficially defined as standalone person to mail to
			if (!$ignore_massmail_setting && !$person['massmail'] && !in_array($person_id,$cond_person_ids_arr)) continue;
			$person_name=eme_format_full_name($person['firstname'],$person['lastname']);
			if (eme_is_email($person['email'])) {
				// we will ignore double emails
				if (!in_array($person['email'],$emails_handled)) {
					$tmp_subject = eme_replace_people_placeholders($mail_subject, $person, "text");
					$tmp_message = eme_replace_people_placeholders($mail_message, $person, $mail_text_html);
					$mail_res = eme_queue_mail($tmp_subject,$tmp_message, $person['email'], $person_name, $contact_email, $contact_name, $mailing_id, $person_id, 0, $attachment_id_arr);
					if (!$mail_res) $res['mail_problems']=1;
					$emails_handled[]=$person['email'];
				}
			} else {
				$res['mail_problems']=1;
				$not_sent[]=$person_name;
			}
		}
	} elseif ($conditions['action']=='eventmail') {
		if (!isset($conditions['rsvp_status'])) $conditions['rsvp_status']=0;
		if (!empty($conditions['pending_approved'])) {
			if ($conditions['pending_approved']==1)
				$conditions['rsvp_status']==EME_RSVP_STATUS_PENDING;
			if ($conditions['pending_approved']==2)
				$conditions['rsvp_status']==EME_RSVP_STATUS_APPROVED;
		}
		if (!isset($conditions['only_unpaid'])) $conditions['only_unpaid']=0;
		if (!isset($conditions['exclude_registered'])) $conditions['exclude_registered']=0;
		$event = eme_get_event($conditions['event_id']);
		if (!empty($event))
			$event_name = $event['event_name'];
		else
			$event_name = '';

		if (isset($conditions['eme_eventmail_attach_ids']))
			$attachment_ids=$conditions['eme_eventmail_attach_ids'];
                if (!empty($attachment_ids)) {
			$attachment_id_arr=explode(",",$attachment_ids);
		} else {
			$attachment_id_arr=array();
		}

		if (empty($event)) {
			$res['mail_problems']=1;
		} elseif ($conditions['eme_mail_type'] == 'attendees') {
			$attendee_ids = eme_get_attendee_ids($conditions['event_id'],$conditions['rsvp_status'],$conditions['only_unpaid']);
			foreach ($attendee_ids as $attendee_id) {
				$attendee = eme_get_person($attendee_id);
				$tmp_subject = eme_replace_attendees_placeholders($mail_subject, $event, $attendee, "text");
				$tmp_message = eme_replace_attendees_placeholders($mail_message, $event, $attendee, $mail_text_html);
				$person_name=eme_format_full_name($attendee['firstname'],$attendee['lastname']);
				$person_id=$attendee['person_id'];
				$mail_res = eme_queue_mail($tmp_subject,$tmp_message, $attendee['email'], $person_name, $contact_email, $contact_name, $mailing_id, $person_id, 0, $attachment_id_arr);
				if (!$mail_res) $res['mail_problems']=1;
			}
		} elseif ($conditions['eme_mail_type'] == 'bookings') {
			$bookings = eme_get_bookings_for($conditions['event_id'],$conditions['rsvp_status'],$conditions['only_unpaid']);
			foreach ($bookings as $booking) {
				// we use the language done in the booking for the mails, not the attendee lang in this case
				$attendee = eme_get_person($booking['person_id']);
				if ($attendee && is_array($attendee)) {
					$tmp_subject = eme_replace_booking_placeholders($mail_subject, $event, $booking, 0, "text");
					$tmp_message = eme_replace_booking_placeholders($mail_message, $event, $booking, 0, $mail_text_html);
					$person_name=eme_format_full_name($attendee['firstname'],$attendee['lastname']);
					$person_id=$attendee['person_id'];
					$mail_res = eme_queue_mail($tmp_subject,$tmp_message, $attendee['email'], $person_name, $contact_email, $contact_name, $mailing_id, $person_id, 0, $attachment_id_arr);
					if (!$mail_res) $res['mail_problems']=1;
				}
			}
		} elseif ($conditions['eme_mail_type'] == 'all_people' || $conditions['eme_mail_type'] == 'people_and_groups' || $conditions['eme_mail_type'] == 'all_people_not_registered') {
			if ($conditions['eme_mail_type'] == 'all_people' || $conditions['eme_mail_type'] == 'all_people_not_registered') {
				// although we check later on the massmail preference per person too, we optimize the sql load a bit
				if ($ignore_massmail_setting)
					$person_ids = eme_get_allmail_person_ids();
				else
					$person_ids = eme_get_massmail_person_ids();
			} elseif ($conditions['eme_mail_type'] == 'people_and_groups') {
				$person_ids = array();  
				if (isset($conditions['eme_eventmail_send_persons'])) {
					$person_ids = explode(',',$conditions['eme_eventmail_send_persons']);
				}
				if (isset($conditions['eme_eventmail_send_groups'])) {
					$person_ids = array_unique(array_merge($person_ids,eme_get_groups_person_ids($conditions['eme_eventmail_send_groups'])));
				}
			}
			if ($conditions['exclude_registered'] || $conditions['eme_mail_type'] == 'all_people_not_registered') {
				$registered_ids=eme_get_attendee_ids($conditions['event_id']);
			} else {
				$registered_ids=array();
			}
			foreach ( $person_ids as $person_id ) {
				if (in_array($person_id,$registered_ids)) continue;
				$person = eme_get_person($person_id);
				if (!$ignore_massmail_setting && !$person['massmail']) continue;
				$tmp_subject = eme_replace_event_placeholders($mail_subject, $event, "text",$person['lang'],0);
				$tmp_message = eme_replace_event_placeholders($mail_message, $event, $mail_text_html,$person['lang'],0);
				$tmp_message = eme_replace_email_event_placeholders($tmp_message, $person['email'], $person['lastname'], $person['firstname'], $event);
				$tmp_subject = eme_replace_people_placeholders($tmp_subject, $person, "text");
				$tmp_message = eme_replace_people_placeholders($tmp_message, $person, $mail_text_html);
				$person_name=eme_format_full_name($person['firstname'],$person['lastname']);
				$person_id=$person['person_id'];
				$mail_res = eme_queue_mail($tmp_subject,$tmp_message, $person['email'], $person_name, $contact_email, $contact_name, $mailing_id, $person_id, 0, $attachment_id_arr);
				if (!$mail_res) $res['mail_problems']=1;
			}
		} elseif ($conditions['eme_mail_type'] == 'all_wp' || $conditions['eme_mail_type'] == 'all_wp_not_registered') {
			$wp_users = get_users();
			if ($conditions['eme_mail_type'] == 'all_wp_not_registered' || $conditions['exclude_registered']) {
				$attendee_wp_ids = eme_get_wp_ids_for($conditions['event_id']);
			} else {
				$attendee_wp_ids = array();
			}
			$lang=eme_detect_lang();
			foreach ( $wp_users as $wp_user ) {
				if (in_array($wp_user->user_email,$emails_handled) ) continue;
				if (in_array($wp_user->ID,$attendee_wp_ids)) continue;
				$tmp_subject = eme_replace_event_placeholders($mail_subject, $event, "text",$lang,0);
				$tmp_message = eme_replace_event_placeholders($mail_message, $event, $mail_text_html,$lang,0);
				$tmp_message = eme_replace_email_event_placeholders($tmp_message, $wp_user->user_firstname, $wp_user->display_name, $wp_user->display_name, $event);
				$mail_res = eme_queue_mail($tmp_subject,$tmp_message, $wp_user->user_email, $wp_user->display_name, $contact_email, $contact_name, $mailing_id, 0, 0, $attachment_id_arr);
				if (!$mail_res) $res['mail_problems']=1;
				$emails_handled[]=$wp_user->user_email;
			}
		}
	}
	$res['not_sent']=join(', ',$not_sent);
	return $res;
}

add_action( 'wp_ajax_eme_mailingreport_list', 'eme_mailingreport_list' );
function eme_mailingreport_list() {
   global $wpdb;
   $table = $wpdb->prefix.MQUEUE_TBNAME;
   if (!isset($_REQUEST['mailing_id'])) return;
   $mailing_id=intval($_REQUEST['mailing_id']);
   $search_name = isset($_REQUEST['search_name']) ? esc_sql($_REQUEST['search_name']) : '';
   $where="";
   $where_arr=array();
   $where_arr[]= '(mailing_id='.intval($_REQUEST['mailing_id']) . ')';
   if (!empty($search_name)) {
      $where_arr[] = "(receivername like '%$search_name%' OR receiveremail like '%$search_name%')";
   }

   if (!empty($where_arr))
      $where = " WHERE ".implode(" AND ",$where_arr);

   $jTableResult = array();
   $sql = "SELECT COUNT(*) FROM $table $where";
   $recordCount = $wpdb->get_var($sql);
   $start= (isset($_REQUEST['jtStartIndex'])) ? intval($_REQUEST['jtStartIndex']) : 0;
   $pagesize= (isset($_REQUEST['jtPageSize'])) ? intval($_REQUEST['jtPageSize']) : 10;
   $sorting = (isset($_REQUEST['jtSorting'])) ? 'ORDER BY '.esc_sql($_REQUEST['jtSorting']) : '';
   $sql="SELECT * FROM $table $where $sorting LIMIT $start,$pagesize";
   $rows=$wpdb->get_results($sql,ARRAY_A);
   $records=array();
   $states=eme_mail_localizedstates();
   foreach ($rows as $item) {
         $record = array();
         $id= $item['id'];
         $record['receiveremail']= $item['receiveremail'];
         $record['receivername']= $item['receivername'];
         $record['status']= $states[$item['status']];
         $record['read_count']= $item['read_count'];
         $record['error_msg']= eme_esc_html($item['error_msg']);
	 if ($item['status']>0) {
		 $localized_datetime = eme_localized_datetime($item['sent_datetime']);
		 $record['sent_datetime']=$localized_datetime; 
		 if (!eme_is_empty_datetime($item['first_read_on'])) {
			 $record['first_read_on'] = eme_localized_datetime($item['first_read_on']);
			 // to account for older setups that didn't have the last_read_on column
			 if (eme_is_empty_datetime($item['last_read_on']))
				 $item['last_read_on']=$item['first_read_on'];
			 $record['last_read_on'] = eme_localized_datetime($item['last_read_on']);
			 // to account for older mailings
			 if ($record['read_count']==0) $record['read_count']=1;
		 } else {
			 $record['first_read_on']=''; 
			 $record['last_read_on']=''; 
		 }
		 $record['action'] = " <a href='".wp_nonce_url(admin_url("admin.php?page=eme-emails&amp;eme_admin_action=reuse_mail&amp;id=".$id),'eme_admin','eme_admin_nonce')."'>".__('Reuse','events-made-easy')."</a>";
	 } else {
		 $record['sent_datetime']=''; 
		 $record['first_read_on']=''; 
		 $record['last_read_on']=''; 
		 $record['action']=''; 
	 }
         $records[]  = $record;
   }
   $jTableResult['Result'] = "OK";
   $jTableResult['TotalRecordCount'] = $recordCount;
   $jTableResult['Records'] = $records;
   print json_encode($jTableResult);
   wp_die();
}

add_action( 'wp_ajax_eme_previeweventmail', 'eme_send_mails_ajax_action_previeweventmail' );
add_action( 'wp_ajax_eme_previewmail', 'eme_send_mails_ajax_action_previewmail' );
add_action( 'wp_ajax_eme_genericmail', 'eme_send_mails_ajax_action_genericmail' );
add_action( 'wp_ajax_eme_testmail', 'eme_send_mails_ajax_action_testmail' );
add_action( 'wp_ajax_eme_searchmail', 'eme_send_mails_ajax_action_searchmail' );
add_action( 'wp_ajax_eme_eventmail', 'eme_send_mails_ajax_action_eventmail' );

function eme_send_mails_ajax_action_searchmail() {
	eme_send_mails_ajax_actions('searchmail');
}
function eme_send_mails_ajax_action_testmail() {
	eme_send_mails_ajax_actions('testmail');
}
function eme_send_mails_ajax_action_eventmail() {
	eme_send_mails_ajax_actions('eventmail');
}
function eme_send_mails_ajax_action_genericmail() {
	eme_send_mails_ajax_actions('genericmail');
}
function eme_send_mails_ajax_action_previewmail() {
	eme_send_mails_ajax_actions('previewmail');
}
function eme_send_mails_ajax_action_previeweventmail() {
	eme_send_mails_ajax_actions('previeweventmail');
}

function eme_send_mails_ajax_actions($action) {
   global $wpdb, $eme_timezone;
   $event_ids = isset($_POST['event_ids']) ? $_POST['event_ids'] : 0;
   $ajaxResult = array();
   $conditions = array();

   if ($action == 'searchmail') {
      $table = $wpdb->prefix.MQUEUE_TBNAME;
      $where = "";
      if (eme_is_empty_string($_POST['search_text'])) {
	      if (!empty($_POST['search_failed']))
		      $where = "WHERE status=2";
	      $sql="SELECT * FROM $table $where ORDER BY id DESC LIMIT 100";
	      $rows=$wpdb->get_results($sql,ARRAY_A);
	      if (!empty($rows))
                      $rows=array_reverse($rows);
      } else {
	      $search_text = '%'.$wpdb->esc_like(eme_sanitize_request($_POST['search_text'])).'%';
	      if (!empty($_POST['search_failed']))
		      $where = "AND status=2";
	      $sql=$wpdb->prepare("SELECT * FROM $table WHERE (receivername LIKE %s OR receiveremail LIKE %s OR subject LIKE %s) $where",$search_text,$search_text,$search_text);
	      $rows=$wpdb->get_results($sql,ARRAY_A);
      }
      if (empty($rows)) {
	      $ajaxResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('No results found','events-made-easy')."</p></div>";
	      $ajaxResult['Result'] = "ERROR";
	      echo json_encode($ajaxResult);
	      wp_die();
      }
      $res='<table class="eme_mailings_table"><tr>'.
	      '<th>'.__('Name','events-made-easy').'</th>'.
	      '<th>'.__('E-mail','events-made-easy').'</th>'.
	      '<th>'.__('Subject','events-made-easy').'</th>'.
	      '<th>'.__('Status','events-made-easy').'</th>'.
	      '<th>'.__('Queued on','events-made-easy').'</th>'.
	      '<th>'.__('Sent on','events-made-easy').'</th>'.
	      '<th>'.__('First read on','events-made-easy').'</th>'.
	      '<th>'.__('Last read on','events-made-easy').'</th>'.
	      '<th>'.__('Read count','events-made-easy').'</th>'.
	      '<th>'.__('Error message','events-made-easy').'</th>'.
	      '<th>'.__('Action','events-made-easy').'</th>'.
	      '</tr>';

      $states=eme_mail_localizedstates();
      foreach ($rows as $item) {
	      $row = '<tr>';
	      $row.= '<td>'.eme_esc_html($item['receivername']).'</td>';
	      $row.= '<td>'.eme_esc_html($item['receiveremail']).'</td>';
	      $row.= '<td>'.eme_esc_html($item['subject']).'</td>';
	      $row.= '<td>'.$states[$item['status']].'</td>';
	      $row.= '<td>'.eme_localized_datetime($item['creation_date']).'</td>'; 
	      // if status >0, then the mail is already treated
	      if ($item['status']>0) {
		      if (!eme_is_empty_datetime($item['sent_datetime'])) {
			      $row.= '<td>'.eme_localized_datetime($item['sent_datetime']).'</td>'; 
		      } else {
			      $row.='<td>&nbsp;</td>'; 
		      }
		      if (!eme_is_empty_datetime($item['first_read_on'])) {
			      $row.= '<td>'.eme_localized_datetime($item['first_read_on']).'</td>'; 
			      // to account for older setups that didn't have the last_read_on column
			      if (eme_is_empty_datetime($item['last_read_on']))
				 $item['last_read_on']=$item['first_read_on'];
			      $row.= '<td>'.eme_localized_datetime($item['last_read_on']).'</td>'; 
			      $row.= '<td>'.$item['read_count'].'</td>'; 
		      } else {
			      $row.='<td>&nbsp;</td>'; 
			      $row.='<td>&nbsp;</td>'; 
			      $row.='<td>&nbsp;</td>'; 
		      }
		      $row.= '<td>'.eme_esc_html($item['error_msg']).'</td>';
		      $row.= "<td><a href='".wp_nonce_url(admin_url("admin.php?page=eme-emails&amp;eme_admin_action=reuse_mail&amp;id=".$item['id']),'eme_admin','eme_admin_nonce')."'>".__('Reuse','events-made-easy')."</a></td>";
	      } else {
		      $row.='<td>&nbsp;</td>';
		      $row.='<td>&nbsp;</td>';
		      $row.='<td>&nbsp;</td>';
		      $row.='<td>&nbsp;</td>'; 
		      $row.='<td>&nbsp;</td>';
		      // if the mail is not part of a mailing, allow to cancel it (otherwise it will be re-added anyway by eme_check_mailing_receivers)
		      if ($item['mailing_id']==0) {
			      $row.= "<td><a href='".wp_nonce_url(admin_url("admin.php?page=eme-emails&amp;eme_admin_action=cancel_mail&amp;id=".$item['id']),'eme_admin','eme_admin_nonce')."'>".__('Cancel','events-made-easy')."</a><br /><a href='".wp_nonce_url(admin_url("admin.php?page=eme-emails&amp;eme_admin_action=reuse_mail&amp;id=".$item['id']),'eme_admin','eme_admin_nonce')."'>".__('Reuse','events-made-easy')."</a></td>";
		      } else {
			      $row.='<td>'.__('This mail is part of a mailing, cancel the corresponding mailing if you want to cancel this mail.','events-made-easy').'</td>';
		      }
	      }
	      $row .= '</tr>';
	      $res.=$row;
      }
      $ajaxResult['htmlmessage'] = $res;
      $ajaxResult['Result'] = "OK";
      echo json_encode($ajaxResult);
      wp_die();
   }

   if ($action == 'testmail') {
      $testmail_to = eme_sanitize_email($_POST['testmail_to']);
      if (!eme_is_email($testmail_to,1)) {
	 $ajaxResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('Please enter a valid mail address','events-made-easy')."</p></div>";
	 $ajaxResult['Result'] = "ERROR";
	 echo json_encode($ajaxResult);
	 wp_die();
      }

      $contact_email = get_option('eme_mail_sender_address');
      $contact_name = get_option('eme_mail_sender_name');
      if (empty($contact_email)) {
	      $contact = eme_get_contact(0);
	      $contact_email = $contact->user_email;
	      $contact_name = $contact->display_name;
      }
      $person_name = "test recipient EME";
      $tmp_subject = "test subject EME";
      $tmp_message = "test message EME";
      $mail_res_arr=eme_send_mail($tmp_subject,$tmp_message, $testmail_to, $person_name, $contact_email, $contact_name);
      $mail_res=$mail_res_arr[0];
      $extra_html=eme_esc_html($mail_res_arr[1]);
      if (!empty($GLOBALS['eme_smtp_debug'])) {
	      $extra_html.=nl2br(eme_esc_html($GLOBALS['eme_smtp_debug']));
      }
      if ($mail_res) {
         $ajaxResult['htmlmessage'] = "<div id='message' class='updated eme-message-admin'><p>".__('The mail has been sent.','events-made-easy')."</p><p>$extra_html</p></div>";
	 $ajaxResult['Result'] = "OK";
      } else {
	 $ajaxResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('There were some problems while sending mail.','events-made-easy')."</p><p>$extra_html</p></div>";
	 $ajaxResult['Result'] = "ERROR";
      }
      echo json_encode($ajaxResult);
      wp_die();
   }

   $conditions['action']=$action;
   if ($action == 'genericmail' || $action == 'previewmail') {
	   $queue=intval(get_option('eme_queue_mails'));

	   if (!empty($_POST['genericmail_ignore_massmail_setting'])) {
		   $conditions['ignore_massmail_setting']=1;
	   }
	   $conditions['eme_generic_attach_ids']=eme_sanitize_request($_POST['eme_generic_attach_ids']);

	   if (!empty($_POST['generic_mail_subject']))
		   $mail_subject = eme_sanitize_request($_POST['generic_mail_subject']);
	   elseif (isset($_POST['generic_subject_template']) && intval($_POST['generic_subject_template'])>0)
		   $mail_subject = eme_get_template_format_plain(intval($_POST['generic_subject_template']));
	   else
		   $mail_subject = "";

	   if (!empty($_POST['generic_mail_message']))
		   $mail_message = eme_kses_maybe_unfiltered($_POST['generic_mail_message']);
	   elseif (isset($_POST['generic_message_template']) && intval($_POST['generic_message_template'])>0)
		   $mail_message = eme_get_template_format_plain(intval($_POST['generic_message_template']));
	   else
		   $mail_message = "";

	   // mail filters
	   $mail_subject = apply_filters('eme_generic_email_subject_filter',$mail_subject);
	   $mail_message = apply_filters('eme_generic_email_body_filter',$mail_message);

	   if (empty($mail_subject) || empty($mail_message)) {
		 $ajaxResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('Please enter both subject and message for the mail to be sent.','events-made-easy')."</p></div>";
		 $ajaxResult['Result'] = "ERROR";
		 echo json_encode($ajaxResult);
		 wp_die();
	   }

	   $mail_text_html=get_option('eme_rsvp_send_html')?"htmlmail":"text";
	   $contact_email = get_option('eme_mail_sender_address');
	   $contact_name = get_option('eme_mail_sender_name');
	   if (empty($contact_email)) {
		   $blank_event = eme_new_event();
		   $contact = eme_get_event_contact($blank_event);
		   $contact_email = $contact->user_email;
		   $contact_name = $contact->display_name;
	   }

	   if (empty($contact_email)) {
		   $ajaxResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('No default sender defined and no event contact email found, bailing out','events-made-easy')."</p></div>";
		   $ajaxResult['Result'] = "ERROR";
		   echo json_encode($ajaxResult);
		   wp_die();
	   }
	   $mailing_id=0;
	   if ($action=='previewmail') {
		   // let's add attachments too
		   if (isset($conditions['eme_generic_attach_ids']))
			   $attachment_ids=$conditions['eme_generic_attach_ids'];
		   if (!empty($attachment_ids)) {
			   $attachment_ids_arr=explode(",",$attachment_ids);
		   } else {
			   $attachment_ids_arr=array();
		   }
		   $preview_mail_to = intval($_POST['send_previewmailto_id']);
		   if ($preview_mail_to==0) {
			   $ajaxResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('Please select a person to send the preview mail to.','events-made-easy')."</p></div>";
			   $ajaxResult['Result'] = "ERROR";
		   } else {
			   $person=eme_get_person($preview_mail_to);
                           $person_name=eme_format_full_name($person['firstname'],$person['lastname']);
			   $mail_subject = eme_replace_generic_placeholders($mail_subject, "text");
			   $mail_message = eme_replace_generic_placeholders($mail_message, $mail_text_html);
			   $mail_subject = eme_replace_people_placeholders($mail_subject, $person, "text");
			   $mail_message = eme_replace_people_placeholders($mail_message, $person, $mail_text_html);
		   	   // no queueing for preview email
                           $res=eme_send_mail($mail_subject, $mail_message, $person['email'], $person_name, $contact_email, $contact_name, $attachment_ids_arr);
                           if ($res) {
                                   $ajaxResult['htmlmessage'] = "<div id='message' class='updated eme-message-admin'><p>".__('The mail has been sent.','events-made-easy')."</p></div>";
                                   $ajaxResult['Result'] = "OK";
                           } else {
                                   $ajaxResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('There were some problems while sending mail.','events-made-easy')."</p></div>";
                                   $ajaxResult['Result'] = "ERROR";
                           }
		   }
		   echo json_encode($ajaxResult);
		   wp_die();
	   } else {
		   if (!empty($_POST['genericmail_mailing_name'])) {
			   $mailing_name=eme_sanitize_request($_POST['genericmail_mailing_name']);
		   } else {
			   $mailing_name="";
		   }
		   if (!empty($_POST['genericmail_actualstartdate'])) {
			   $mailing_datetime=eme_sanitize_request($_POST['genericmail_actualstartdate']);
		   } else {
			   $mailing_datetime="";
		   }
		   if (isset($_POST['generic_send_now']) && $_POST['generic_send_now']==1) {
			   $eme_date_obj=new ExpressiveDate("now",$eme_timezone);
			   $mailing_datetime = $eme_date_obj->getDateTime();
			   $mailing_name="mailing $mailing_datetime";
		   }
		   if ($queue && empty($mailing_name)) {
			   $ajaxResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('Please enter a name for the mailing.','events-made-easy')."</p></div>";
			   $ajaxResult['Result'] = "ERROR";
			   echo json_encode($ajaxResult);
			   wp_die();
		   }
		   if (isset($_POST['eme_send_all_people'])) {
			   $conditions['eme_send_all_people']=1;
		   } else {
			   if (!empty($_POST['eme_genericmail_send_persons']) && eme_array_integers($_POST['eme_genericmail_send_persons'])) {
				$conditions['eme_genericmail_send_persons']=join(',',$_POST['eme_genericmail_send_persons']);
			   }
			   if (!empty($_POST['eme_send_members']) && eme_array_integers($_POST['eme_send_members'])) {
			   	$conditions['eme_send_members'] = join(',',$_POST['eme_send_members']);
			   }
			   if (!empty($_POST['eme_genericmail_send_peoplegroups']) && eme_array_integers($_POST['eme_genericmail_send_peoplegroups'])) {
				$conditions['eme_genericmail_send_peoplegroups']=join(',',$_POST['eme_genericmail_send_peoplegroups']);
			   }
			   if (!empty($_POST['eme_genericmail_send_membergroups']) && eme_array_integers($_POST['eme_genericmail_send_membergroups'])) {
				$conditions['eme_genericmail_send_membergroups']=join(',',$_POST['eme_genericmail_send_membergroups']);
			   }
			   if (!empty($_POST['eme_send_memberships']) && eme_array_integers($_POST['eme_send_memberships'])) {
				$conditions['eme_send_memberships']=join(',',$_POST['eme_send_memberships']);
			   }
		   }
		   if ($queue) {
			   // in case we want a mailing to be done at multiple times
			   if (strstr($mailing_datetime, ',')) {
				   $dates=explode(',',$mailing_datetime);
				   foreach ( $dates as $datetime ) {
					   $mailing_id = eme_db_insert_mailing($mailing_name,$datetime, $mail_subject, $mail_message, $contact_email, $contact_name, $mail_text_html,$conditions);
					   $res = eme_update_mailing_receivers($mail_subject,$mail_message, $contact_email, $contact_name, $mail_text_html, $conditions,$mailing_id);
					   eme_mark_mailing_planned($mailing_id);
				   }
			   } else {
				   $mailing_id = eme_db_insert_mailing($mailing_name,$mailing_datetime, $mail_subject, $mail_message, $contact_email, $contact_name, $mail_text_html,$conditions);
				   $res = eme_update_mailing_receivers($mail_subject,$mail_message, $contact_email, $contact_name, $mail_text_html, $conditions,$mailing_id);
				   eme_mark_mailing_planned($mailing_id);
			   } 
		   } else {
			   $res = eme_update_mailing_receivers($mail_subject,$mail_message, $contact_email, $contact_name, $mail_text_html, $conditions);
		   }
	   }

	   //now, we use the res output from the last call of eme_update_mailing_receivers (in case of multiple planned mailings, possible problems are the same for all anyway)
	   if (!$res['mail_problems']) {
		   if ($queue) {
			   if (!wp_next_scheduled('eme_cron_send_queued'))
				   $ajaxResult['htmlmessage'] = "<div id='message' class='updated eme-message-admin'><p>".sprintf(__('The mailing has been put on the queue, but you have not yet configured the queueing. Go in the <a href="%s">Scheduled actions</a> submenu and configure it now.','events-made-easy'), admin_url("admin.php?page=eme-cron"))."</p></div>";
			   else
				   $ajaxResult['htmlmessage'] = "<div id='message' class='updated eme-message-admin'><p>".__('The mailing has been planned.','events-made-easy')."</p></div>";
		   } else {
			   $ajaxResult['htmlmessage'] = "<div id='message' class='updated eme-message-admin'><p>".__('The mail has been sent.','events-made-easy')."</p></div>";
		   }
		   $ajaxResult['Result'] = "OK";
	   } else {
		   if ($queue) {
			   $ajaxResult['htmlmessage'] = "<div id='message' class='updated eme-message-admin'><p>".__('The mailing has been put on the queue, but not all persons will receive it.','events-made-easy')."</p></div>";
			   if (!empty($res['not_sent'])) {
				   $ajaxResult['htmlmessage'] .= "<div id='message' class='error eme-message-admin'><p>".__('The following persons will not receive the mail:','events-made-easy').' '.eme_esc_html($res['not_sent'])."</p></div>";
			   }
		   } else {
			   $ajaxResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('There were some problems while sending mail.','events-made-easy')."</p></div>";
			   if (!empty($res['not_sent'])) {
				   $ajaxResult['htmlmessage'] .= "<div id='message' class='error eme-message-admin'><p>".__('Mail to the following persons has not been sent:','events-made-easy').' '.eme_esc_html($res['not_sent'])."</p></div>";
			   }
		   }
		   $ajaxResult['Result'] = "ERROR";
	   }
	   echo json_encode($ajaxResult);
	   wp_die();
   }

   if ($action == 'eventmail' || $action == 'previeweventmail') {
	   $queue=intval(get_option('eme_queue_mails'));
	   if (!empty($_POST['eventmail_ignore_massmail_setting'])) {
		   $conditions['ignore_massmail_setting']=1;
	   }
	   $conditions['eme_eventmail_attach_ids']=eme_sanitize_request($_POST['eme_eventmail_attach_ids']);
	   if (!empty($_POST ['event_mail_subject']))
		   $mail_subject = eme_sanitize_request($_POST ['event_mail_subject']);
	   elseif (isset($_POST ['event_subject_template']) && intval($_POST ['event_subject_template'])>0)
		   $mail_subject = eme_get_template_format_plain(intval($_POST ['event_subject_template']));
	   else
		   $mail_subject = "";

	   if (!empty($_POST ['event_mail_message']))
		   $mail_message = eme_kses_maybe_unfiltered($_POST ['event_mail_message']);
	   elseif (isset($_POST ['event_message_template']) && intval($_POST ['event_message_template'])>0)
		   $mail_message = eme_get_template_format_plain(intval($_POST ['event_message_template']));
	   else
		   $mail_message = "";

	   // mail filters
	   $mail_subject = apply_filters('eme_event_email_subject_filter',$mail_subject);
	   $mail_message = apply_filters('eme_event_email_body_filter',$mail_message);

	   if (empty($mail_subject) || empty($mail_message)) {
                 $ajaxResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('Please enter both subject and message for the mail to be sent.','events-made-easy')."</p></div>";
                 $ajaxResult['Result'] = "ERROR";
                 echo json_encode($ajaxResult);
                 wp_die();
           }

	   if (!eme_array_integers($event_ids)) {
		   $ajaxResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('Please select at least one event.','events-made-easy')."</p></div>";
		   $ajaxResult['Result'] = "ERROR";
		   echo json_encode($ajaxResult);
		   wp_die();
	   }

	   if (!empty($_POST['eventmail_mailing_name'])) {
		   $mailing_name=eme_sanitize_request($_POST['eventmail_mailing_name']);
	   } else {
		   $mailing_name="";
	   }
	   if (!empty($_POST['eventmail_actualstartdate'])) {
		   $mailing_datetime=eme_sanitize_request($_POST['eventmail_actualstartdate']);
	   } else {
		   $mailing_datetime="";
	   }

	   if ($action=='previeweventmail') {
		   // let's add attachments too
		   if (isset($conditions['eme_generic_attach_ids']))
			   $attachment_ids=$conditions['eme_generic_attach_ids'];
		   if (!empty($attachment_ids)) {
			   $attachment_ids_arr=explode(",",$attachment_ids);
		   } else {
			   $attachment_ids_arr=array();
		   }
		   $preview_mail_to = intval($_POST['send_previeweventmailto_id']);
		   if ($preview_mail_to==0) {
			   $ajaxResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('Please select a person to send the preview mail to.','events-made-easy')."</p></div>";
			   $ajaxResult['Result'] = "ERROR";
		   } else {
			   $person=eme_get_person($preview_mail_to);
                           $person_name=eme_format_full_name($person['firstname'],$person['lastname']);
			   $event=eme_get_event($event_ids[0]);
			   if (!empty($event)) {
				   $contact = eme_get_event_contact ($event);
				   $contact_email = $contact->user_email;
				   $contact_name = $contact->display_name;
				   $mail_subject = eme_replace_event_placeholders($mail_subject, $event, "text", $person['lang'],0);
				   $mail_message = eme_replace_event_placeholders($mail_message, $event, $mail_text_html,$person['lang'],0);
				   $mail_message = eme_replace_email_event_placeholders($mail_message, $person['email'], $person['lastname'], $person['firstname'], $event, $person['lang']);
				   $mail_subject = eme_replace_people_placeholders($mail_subject, $person, "text");
				   $mail_message = eme_replace_people_placeholders($mail_message, $person, $mail_text_html);
				   // no queueing for preview email
				   $res=eme_send_mail($mail_subject, $mail_message, $person['email'], $person_name, $contact_email, $contact_name, $attachment_ids_arr);
				   if ($res) {
					   $ajaxResult['htmlmessage'] = "<div id='message' class='updated eme-message-admin'><p>".__('The mail has been sent.','events-made-easy')."</p></div>";
					   $ajaxResult['Result'] = "OK";
				   } else {
					   $ajaxResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('There were some problems while sending mail.','events-made-easy')."</p></div>";
					   $ajaxResult['Result'] = "ERROR";
				   }
			   } else {
				   $ajaxResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('No such event','events-made-easy')."</p></div>";
				   $ajaxResult['Result'] = "ERROR";
			   }
		   }
		   echo json_encode($ajaxResult);
		   wp_die();
	   } else {
		   if (isset($_POST['event_send_now']) && $_POST['event_send_now']==1) {
			   $eme_date_obj=new ExpressiveDate("now",$eme_timezone);
			   $mailing_datetime = $eme_date_obj->getDateTime();
			   $mailing_name="mailing $mailing_datetime";
		   }
		   if ($queue && empty($mailing_name)) {
			   $ajaxResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('Please enter a name for the mailing.','events-made-easy')."</p></div>";
			   $ajaxResult['Result'] = "ERROR";
			   echo json_encode($ajaxResult);
			   wp_die();
		   }
		   if (!empty($_POST['eme_eventmail_send_persons']) && eme_array_integers($_POST['eme_eventmail_send_persons'])) {
			   $conditions['eme_eventmail_send_persons']=join(',',$_POST['eme_eventmail_send_persons']);
		   }
		   if (!empty($_POST['eme_eventmail_send_groups']) && eme_array_integers($_POST['eme_eventmail_send_groups'])) {
			   $conditions['eme_eventmail_send_groups']=join(',',$_POST['eme_eventmail_send_groups']);
		   }
		   $eme_mail_type = isset($_POST ['eme_mail_type']) ? $_POST ['eme_mail_type'] : 'attendees';
		   if (empty($eme_mail_type)) {
			   $ajaxResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('Please select the type of mail to be sent.','events-made-easy')."</p></div>";
			   $ajaxResult['Result'] = "ERROR";
			   echo json_encode($ajaxResult);
			   wp_die();
		   }
		   $conditions['eme_mail_type']=$eme_mail_type;
	   }

	   $mailing_id=0;
	   $rsvp_status = isset($_POST ['rsvp_status']) ? intval($_POST ['rsvp_status']) : 0;
	   $only_unpaid = isset($_POST ['only_unpaid']) ? intval($_POST ['only_unpaid']) : 0;
	   $exclude_registered = isset($_POST ['exclude_registered']) ? intval($_POST ['exclude_registered']) : 0;
	   $conditions['rsvp_status']=$rsvp_status;
	   $conditions['only_unpaid']=$only_unpaid;
	   $conditions['exclude_registered']=$exclude_registered;
	   $current_userid=get_current_user_id();
	   $mail_problems=0;
	   $mail_access_problems=0;
	   $not_sent=array();
	   $count_event_ids=count($event_ids);
	   foreach ($event_ids as $event_id) {
		   $conditions['event_id']=$event_id;
		   $event = eme_get_event($event_id);
		   if (empty($event))
			   continue;
		   $mailing_id=0;
		   if ($count_event_ids>1)
			   $mailing_name.= " ($event_id)";
		   if (current_user_can( get_option('eme_cap_send_other_mails')) ||
			   (current_user_can( get_option('eme_cap_send_mails')) && ($event['event_author']==$current_userid || $event['event_contactperson_id']==$current_userid))) {  
			   $event_name = $event['event_name'];
			   $contact = eme_get_event_contact ($event);
			   $contact_email = $contact->user_email;
			   $contact_name = $contact->display_name;
			   $mail_text_html=get_option('eme_rsvp_send_html')?"htmlmail":"text";

			   if ($queue) {
				   // in case we want a mailing to be done at multiple times
				   if (strstr($mailing_datetime, ',')) {
					   $dates=explode(',',$mailing_datetime);
					   foreach ( $dates as $datetime ) {
						   $mailing_id = eme_db_insert_mailing($mailing_name,$datetime, $mail_subject, $mail_message, $contact_email, $contact_name, $mail_text_html,$conditions);
						   $res = eme_update_mailing_receivers($mail_subject,$mail_message, $contact_email, $contact_name, $mail_text_html, $conditions, $mailing_id);
						   eme_mark_mailing_planned($mailing_id);
					   }
				   } else {
					   $mailing_id = eme_db_insert_mailing($mailing_name,$mailing_datetime, $mail_subject, $mail_message, $contact_email, $contact_name, $mail_text_html,$conditions);
					   $res = eme_update_mailing_receivers($mail_subject,$mail_message, $contact_email, $contact_name, $mail_text_html, $conditions, $mailing_id);
					   eme_mark_mailing_planned($mailing_id);
				   }
			   } else {
				   $res = eme_update_mailing_receivers($mail_subject,$mail_message, $contact_email, $contact_name, $mail_text_html, $conditions);
			   }
			   $mail_problems += $res['mail_problems'];
			   $not_sent[]=$res['not_sent'];
		   } else {
			   $mail_access_problems = 1;
		   }
	   }

	   if (!$mail_problems) {
		   if ($queue) {
			   if (!wp_next_scheduled('eme_cron_send_queued'))
				   $ajaxResult['htmlmessage'] = "<div id='message' class='updated eme-message-admin'><p>".sprintf(__('The mailing has been put on the queue, but you have not yet configured the queueing. Go in the <a href="%s">Scheduled actions</a> submenu and configure it now.','events-made-easy'), admin_url("admin.php?page=eme-cron"))."</p></div>";
			   else
				   $ajaxResult['htmlmessage'] = "<div id='message' class='updated eme-message-admin'><p>".__('The mailing has been planned.','events-made-easy')."</p></div>";
		   } else {
			   $ajaxResult['htmlmessage'] = "<div id='message' class='updated eme-message-admin'><p>".__('The mail has been sent.','events-made-easy')."</p></div>";
		   }
		   $ajaxResult['Result'] = "OK";
	   } else {
		   if ($mail_access_problems) {
			   $ajaxResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('Only mails for events you have the right to send mails for have been sent.','events-made-easy')."</p></div>";
		   } else {
			   $ajaxResult['htmlmessage'] = "<div id='message' class='error eme-message-admin'><p>".__('There were some problems while sending mail.','events-made-easy')."</p></div>";
			   if (!empty($not_sent)) {
				   $ajaxResult['htmlmessage'] .= "<div class='error eme-message-admin'><p>".__('Mail to the following persons has not been sent:','events-made-easy').' '.join(', ',eme_esc_html($not_sent))."</p></div>";
			   }
		   }
		   $ajaxResult['Result'] = "ERROR";
	   }
	   echo json_encode($ajaxResult);
	   wp_die();
   }
   wp_die();
}

function eme_emails_page() {
   $eme_queue_mails = get_option('eme_queue_mails');
   if (!get_option('eme_cron_queue_count') || !wp_next_scheduled('eme_cron_send_queued')) {
	   $eme_queue_mails_configured=0;
   } else {
	   $eme_queue_mails_configured=1;
   }

   $mygroups=array();
   $mymembergroups=array();
   $person_ids=array();
   $membership_ids=array();
   $persongroup_ids=array();
   $membergroup_ids=array();
   $member_ids=array();
   // if we get a request for mailings, set the active tab to the 'tab-genericmails' tab (which is index 1)
   if (isset($_POST['eme_admin_action']) && $_POST['eme_admin_action']=='new_mailing') {
      $data_forced_tab="data-showtab=1";
      if (isset($_POST['tasksignup_ids'])) {
	      // when editing, select2 needs a populated list of selected items
	      $tasksignup_ids=$_POST['tasksignup_ids'];
	      $person_ids=eme_get_tasksignup_personids($tasksignup_ids);
	      $persons=eme_get_persons($person_ids);
	      foreach ($persons as $person) {
		      $mygroups[$person['person_id']]=eme_format_full_name($person['firstname'],$person['lastname']);
	      }
      }
      if (isset($_POST['booking_ids'])) {
	      // when editing, select2 needs a populated list of selected items
	      $booking_ids=$_POST['booking_ids'];
	      $person_ids=eme_get_booking_personids($booking_ids);
	      $persons=eme_get_persons($person_ids);
	      foreach ($persons as $person) {
		      $mygroups[$person['person_id']]=eme_format_full_name($person['firstname'],$person['lastname']);
	      }
      }
      if (isset($_POST['person_ids'])) {
	      // when editing, select2 needs a populated list of selected items
	      $person_ids=explode(',',$_POST['person_ids']);
	      $persons=eme_get_persons($person_ids);
	      foreach ($persons as $person) {
		      $mygroups[$person['person_id']]=eme_format_full_name($person['firstname'],$person['lastname']);
	      }
      }
      if (isset($_POST['member_ids'])) {
	      // when editing, select2 needs a populated list of selected items
	      $member_ids=explode(',',$_POST['member_ids']);
	      $members=eme_get_members($member_ids);
	      foreach ($members as $member) {
		      $mymembergroups[$member['member_id']]=eme_format_full_name($member['firstname'],$member['lastname']);
	      }
      }
      $send_to_all_people_checked="";
   } else {
      $send_to_all_people_checked="checked='checked'";
      $data_forced_tab="";
   }
   $ignore_massmail_setting="";
   $event_mail_subject='';
   $event_mail_message='';
   $generic_mail_subject='';
   $generic_mail_message='';
   $peoplegroups=eme_get_groups();
   $membergroups=eme_get_membergroups();

   if (isset($_POST['eme_admin_action']) && $_POST['eme_admin_action']=='archiveMailings' && isset($_POST['mailing_ids'])) {
	   check_admin_referer('eme_admin','eme_admin_nonce');
	   $mailing_ids=$_POST['mailing_ids'];
	   foreach ($mailing_ids as $mailing_id) {
		   eme_archive_mailing($mailing_id);
	   }
   }
   if (isset($_POST['eme_admin_action']) && $_POST['eme_admin_action']=='deleteMailings' && isset($_POST['mailing_ids'])) {
	   check_admin_referer('eme_admin','eme_admin_nonce');
	   $mailing_ids=$_POST['mailing_ids'];
	   foreach ($mailing_ids as $mailing_id) {
		   eme_delete_mailing($mailing_id);
	   }
   }
   $attachment_ids="";
   $attach_url_string="";
   if (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action']=='reuse_mail' && isset($_GET['id'])) {
	   check_admin_referer('eme_admin','eme_admin_nonce');
	   $send_to_all_people_checked="";
	   $id=intval($_GET['id']);
	   $mail=eme_get_mail($id);
	   if ($mail) {
		   $generic_mail_subject=$mail['subject'];
		   $generic_mail_message=$mail['body'];
		   if ($mail['person_id']>0) {
			   $person_ids[]=$mail['person_id'];
			   $person=eme_get_person($mail['person_id']);
			   $mygroups[$person['person_id']]=eme_format_full_name($person['firstname'],$person['lastname']);
			   $send_to_all_people_checked="";
		   } elseif ($mail['member_id']>0) {
			   $member_ids[]=$mail['member_id'];
			   $member=eme_get_member($mail['member_id']);
			   $person=eme_get_person($member['person_id']);
			   $mymembergroups[$member['member_id']]=eme_format_full_name($person['firstname'],$person['lastname']);
			   $send_to_all_people_checked="";
		   }
		   // reuse the attachments too
		   if (!empty($mail['attachments'])) {
			   $attachment_ids_arr=unserialize($mail['attachments']);
			   foreach ($attachment_ids_arr as $attachment_id) {
				   $attach_link= eme_get_attachment_link($attachment_id);
				   if (!empty($attach_link)) {
					   $attach_url_string.= $attach_link;
					   $attach_url_string.= "<br \>";
				   }
			   }
			   if (!empty($attachment_ids_arr))
				   $attachment_ids=join(',',$attachment_ids_arr);
		   }
		   $data_forced_tab="data-showtab=1";
	   }
   }
   if (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action']=='reuse_mailing' && isset($_GET['id'])) {
	   check_admin_referer('eme_admin','eme_admin_nonce');
	   $id=intval($_GET['id']);
	   $mailing=eme_get_mailing($id);
	   if ($mailing) {
		   $conditions=unserialize($mailing['conditions']);
		   if (!empty($conditions['ignore_massmail_setting'])) {
			   $ignore_massmail_setting="checked='checked'";
		   }
		   if ($conditions['action']=='genericmail') {
			   $generic_mail_subject=$mailing['subject'];
			   $generic_mail_message=$mailing['body'];
			   $data_forced_tab="data-showtab=1";
			   if (!empty($conditions['eme_send_all_people'])) {
				   $send_to_all_people_checked="checked='checked'";
			   } else {
				   $send_to_all_people_checked="";
				   if (!empty($conditions['eme_genericmail_send_persons'])) {
					   $person_ids = explode(',',$conditions['eme_genericmail_send_persons']);
					   $persons=eme_get_persons($person_ids);
					   foreach ($persons as $person) {
						   $mygroups[$person['person_id']]=eme_format_full_name($person['firstname'],$person['lastname']);
					   }
				   }
				   if (!empty($conditions['eme_send_members'])) {
					   $member_ids = explode(',',$conditions['eme_send_members']);
					   $members=eme_get_members($member_ids);
					   foreach ($members as $member) {
						   $mymembergroups[$member['member_id']]=eme_format_full_name($member['firstname'],$member['lastname']);
					   }
				   }
				   if (!empty($conditions['eme_genericmail_send_peoplegroups'])) {
					   $persongroup_ids = explode(',',$conditions['eme_genericmail_send_peoplegroups']);
				   }
				   if (!empty($conditions['eme_genericmail_send_membergroups'])) {
					   $membergroup_ids = explode(',',$conditions['eme_genericmail_send_membergroups']);
				   }
				   if (!empty($conditions['eme_send_memberships'])) {
					   $membership_ids = explode(',',$conditions['eme_send_memberships']);
				   }

			   }
			   // reuse the attachments too
			   if (!empty($conditions['eme_generic_attach_ids'])) {
				   $attachment_ids=$conditions['eme_generic_attach_ids'];
				   $attachment_ids_arr=explode(',',$attachment_ids);
				   foreach ($attachment_ids_arr as $attachment_id) {
					   $attach_link= eme_get_attachment_link($attachment_id);
					   if (!empty($attach_link)) {
						   $attach_url_string.= $attach_link;
						   $attach_url_string.= "<br \>";
					   }
				   }
			   }
		   } elseif ($conditions['action']=='eventmail') {
			   $event_mail_subject=$mailing['subject'];
			   $event_mail_message=$mailing['body'];
			   $data_forced_tab="data-showtab=0";
			   // reuse the attachments too
			   if (!empty($conditions['eme_eventmail_attach_ids'])) {
				   $attachment_ids=$conditions['eme_eventmail_attach_ids'];
				   $attachment_ids_arr=explode(',',$attachment_ids);
				   foreach ($attachment_ids_arr as $attachment_id) {
					   $attach_link= eme_get_attachment_link($attachment_id);
					   if (!empty($attach_link)) {
						   $attach_url_string.= $attach_link;
						   $attach_url_string.= "<br \>";
					   }
				   }
			   }
		   }
	   }
   }
   if (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action']=='archive_mailing' && isset($_GET['id'])) {
	   $id=intval($_GET['id']);
	   check_admin_referer('eme_admin','eme_admin_nonce');
	   eme_archive_mailing($id);
   }
   if (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action']=='delete_mailing' && isset($_GET['id'])) {
	   $id=intval($_GET['id']);
	   check_admin_referer('eme_admin','eme_admin_nonce');
	   eme_delete_mailing($id);
   }
   if (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action']=='cancel_mailing' && isset($_GET['id'])) {
	   $id=intval($_GET['id']);
	   check_admin_referer('eme_admin','eme_admin_nonce');
	   eme_cancel_mailing($id);
   }
   if (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action']=='cancel_mail' && isset($_GET['id'])) {
	   $id=intval($_GET['id']);
	   check_admin_referer('eme_admin','eme_admin_nonce');
	   eme_cancel_mail($id);
   }
   if (isset($_GET['eme_admin_action']) && $_GET['eme_admin_action']=='report_mailing' && isset($_GET['id'])) {
	   // the id param will be captured by js to fill out the report table via jtable
	   check_admin_referer('eme_admin','eme_admin_nonce');
   ?>
      <div class="wrap nosubsub">
       <div id="poststuff">
         <div id="icon-edit" class="icon32">
            <br />
         </div>
         <h1><?php _e('Mailing report', 'events-made-easy') ?></h1>
   <form action="#" method="post">
   <input type="text" class="clearable" name="search_name" id="search_name" placeholder="<?php _e('Person name','events-made-easy'); ?>" size=10>
   <button id="ReportLoadRecordsButton" class="button-secondary action"><?php _e('Filter','events-made-easy'); ?></button>
   </form>
   <p><?php _e('Remark: the list of recipients below is just an indication based on the moment the mailing was created. Just before the mailing will actually start, this list will be refreshed based on the conditions the mailing was created with.','events-made-easy');?></p>
	 <div id="MailingReportTableContainer"></div>
       </div>
      </div>
   <?php
	   return;
   }

   // now show the form
?>
<div class="wrap">
<div id="icon-events" class="icon32"><br />
</div>
<div id="mail-tabs" style="display: none;" <?php echo $data_forced_tab; ?>>
  <ul>
    <li><a href="#tab-eventmails"><?php _e('Event related email','events-made-easy');?></a></li>
    <li><a href="#tab-genericmails"><?php _e('Generic email','events-made-easy');?></a></li>
    <li><a href="<?php echo wp_nonce_url(admin_url("admin-ajax.php?action=eme_get_mailings_div"),'eme_admin','eme_admin_nonce') ;?>"><?php _e('Mailings','events-made-easy');?></a></li>
    <li><a href="<?php echo wp_nonce_url(admin_url("admin-ajax.php?action=eme_get_mail_archive_div"),'eme_admin','eme_admin_nonce') ;?>"><?php _e('Mailings archive','events-made-easy');?></a></li>
    <li><a href="#tab-sentmail"><?php _e('Sent emails','events-made-easy');?></a></li>
    <li><a href="#tab-testmail"><?php _e('Test email','events-made-easy');?></a></li>
  </ul>
  <div id="tab-eventmails">
   <h1><?php _e ('Send event related emails','events-made-easy'); ?></h1>
<?php
   $templates_array=eme_get_templates_array_by_id('rsvpmail');
?>
   <form id='send_mail' name='send_mail' action="#" method="post" onsubmit="return false;">
   <div id='send_event_mail_div'>
      <table>
      <tr>
      <td><?php _e('Select the event(s)','events-made-easy'); ?></td>
      <td>
	   <select name="event_ids[]" id="event_ids[]" multiple="multiple" size="5" class='eme_select2_events_class'>
           </select>
	   <br /><input id="eventsearch_all" name='eventsearch_all' value='1' type='checkbox'> <?php _e('Check this box to search through all events and not just future ones.','events-made-easy'); ?>
           <p class='eme_smaller'><?php _e('Remark: if you select multiple events, a mailing will be created for each selected event','events-made-easy'); ?></p>
      </td>
      </tr>
      <tr>
      <td><?php _e('Select the type of mail','events-made-easy'); ?></td>
      <td>
           <select name="eme_mail_type" required='required'>
           <option value=''>&nbsp;</option>
           <option value='attendees'><?php _e('Attendee mails','events-made-easy'); ?></option>
           <option value='bookings'><?php _e('Booking mails','events-made-easy'); ?></option>
           <option value='all_people'><?php _e('Mail to all people registered in EME','events-made-easy'); ?></option>
           <option value='people_and_groups'><?php _e('Mail to people and/or groups registered in EME','events-made-easy'); ?></option>
           <option value='all_wp'><?php _e('Mail to all WP users','events-made-easy'); ?></option>
           </select>
      </td>
      </tr>
      <tr id="eme_rsvp_status_row">
      <td><?php _e('Select your target audience','events-made-easy'); ?></td>
      <td>
           <select name="rsvp_status">
           <option value=0><?php _e('All registered persons','events-made-easy'); ?></option>
	   <option value=<?php echo EME_RSVP_STATUS_APPROVED;?>><?php _e('Only approved bookings','events-made-easy'); ?></option>
	   <option value=<?php echo EME_RSVP_STATUS_PENDING;?>><?php _e('Only pending bookings','events-made-easy'); ?></option>
           </select>
      </td>
      </tr>
      <tr id="eme_exclude_registered_row">
      <td><?php _e('Exclude already registered people','events-made-easy'); ?>&nbsp;</td>
      <td>
           <input type="checkbox" name="exclude_registered" value="1" />
      </td>
      </tr>
      <tr id="eme_only_unpaid_row">
      <td><?php _e('Only send mails to attendees who did not pay yet','events-made-easy'); ?>&nbsp;</td>
      <td>
           <input type="checkbox" name="only_unpaid" value="1" />
      </td>
      </tr>
      <tr id="eme_people_row">
      <td><?php $label=eme_esc_html('Send to a number of people','events-made-easy'); $aria_label='aria-label="'.$label.'"'; echo $label; ?></td>
      <td><?php echo eme_ui_multiselect($person_ids,'eme_eventmail_send_persons',$mygroups,5,'',0,'eme_select2_people_class', $aria_label); ?></td>
      </tr>
      <tr id="eme_groups_row">
      <td width='20%'><?php $label=eme_esc_html('Send to a number of groups','events-made-easy'); $aria_label='aria-label="'.$label.'"'; echo $label;?></td>
      <td><?php echo eme_ui_multiselect_key_value($persongroup_ids,'eme_eventmail_send_groups',$peoplegroups,'group_id','name',5,'',0,'eme_select2_groups_class',$aria_label); ?></td>
      </tr>
      </table>
	   <div class="form-field"><p>
      <b><?php _e('Subject','events-made-easy'); ?></b><br />
      <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select(0,'event_subject_template',$templates_array); ?><br />
      <?php _e('Or enter your own: ','events-made-easy');?>
      <input type="text" name="event_mail_subject" id="event_mail_subject" value="<?php echo eme_esc_html($event_mail_subject); ?>" />
	   </p></div>
	   <div class="form-field"><p>
	   <b><?php _e('Message','events-made-easy'); ?></b><br />
      <?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select(0,'event_message_template',$templates_array); ?><br />
      <?php _e('Or enter your own: ','events-made-easy');
          if (get_option('eme_rsvp_send_html')) {
	     // for mails, let enable the full html editor
             $eme_editor_settings = eme_get_editor_settings(true);
             wp_editor($event_mail_message,'event_mail_message',$eme_editor_settings);
	     if (current_user_can('unfiltered_html')) {
                       echo "<div class='eme_notice_unfiltered_html'>";
                       _e('Your account has the ability to post unrestricted HTML content here, except javascript.', 'events-made-easy');
                       echo "</div>";
             }
          } else {
		  echo "<textarea name='event_mail_message' id='event_mail_message' rows='10' required='required'>".eme_esc_html($event_mail_message)."</textarea>";
          }
      ?>
           </p></div>
	   <div><p>
	   <?php _e('You can use any placeholders mentioned here:','events-made-easy');
	   print "<br /><a href='//www.e-dynamics.be/wordpress/?cat=25'>".__('Event placeholders','events-made-easy')."</a>";
	   print "<br /><a href='//www.e-dynamics.be/wordpress/?cat=48'>".__('Attendees placeholders','events-made-easy')."</a> (".__('for ','events-made-easy').__('Attendee mails','events-made-easy').")";
	   print "<br /><a href='//www.e-dynamics.be/wordpress/?cat=45'>".__('Booking placeholders','events-made-easy')."</a> (".__('for ','events-made-easy').__('Booking mails','events-made-easy').")";
	   print "<br />".__('You can also use any shortcode you want.','events-made-easy');
	   ?>
	   </p></div>
           <hr>
	   <div id='div_event_mailing_attach'>
	   <p>
	   <b><?php _e('Optionally add attachments to your mailing','events-made-easy'); ?></b><br />
	   <span id="eventmail_attach_links"><?php echo $attach_url_string; ?></span>
	   <input type="hidden" name="eme_eventmail_attach_ids" id="eme_eventmail_attach_ids" value="<?php echo $attachment_ids; ?>">
           <input type="button" name="eventmail_attach_button" id="eventmail_attach_button" value="<?php _e('Add attachments', 'events-made-easy');?>">
           <input type="button" name="eventmail_remove_attach_button" id="eventmail_remove_attach_button" value="<?php _e('Remove attachments', 'events-made-easy');?>">
           </p>
	   </div>
	   <?php
	   if ($eme_queue_mails) { ?>
	   <hr>
	   <div id='div_event_mailing_definition'>
		<p>
		<b><?php _e('Set mailing name and start date and time','events-made-easy'); ?></b><br />
                <label for='eventmail_mailing_name'><?php _e('Mailing name: ','events-made-easy'); ?></label> <input type='text' name='eventmail_mailing_name' id='eventmail_mailing_name' value='' required='required'><br />
                <?php _e('Start date and time: ','events-made-easy'); ?>
		<input type='hidden' name='eventmail_actualstartdate' id='eventmail_actualstartdate' value=''>
                <input type='text' readonly='readonly' name='eventmail_startdate' id='eventmail_startdate' data-date='' data-alt-field='eventmail_actualstartdate' data-multiple-dates="true" style="background: #FCFFAA;" /><br />
		<span id='eventmail-specificdates' class="eme_smaller"></span>
		<span id='eventmail-multidates-expl' class="eme_smaller"><?php _e('(multiple dates can be selected, in which case the mailing will be planned on each selected date and time)','events-made-easy'); ?></span>
		</p>
	   </div>
	   <div>
		<p>
		<label for='event_send_now'><?php _e('Or check this option to send the mail immediately:','events-made-easy'); ?></label>
                <input id="event_send_now" name='event_send_now' value='1' type='checkbox' /><br />
		</p>
	   </div>
	   <?php } ?>
	   <hr>
	   <div id='div_event_ignore_massmail_setting'>
		<p>
		<b><label for='eventmail_ignore_massmail_setting'><?php _e('Ignore massmail setting:','events-made-easy'); ?></label></b>
                <input id="eventmail_ignore_massmail_setting" name='eventmail_ignore_massmail_setting' value='1' type='checkbox' <?php echo $ignore_massmail_setting; ?> /><br />
                <?php _e('When sending a mail to all EME people or certain groups, it is by default only sent to the people who have indicated they want to receive mass mailings. If you need to send the mail to all the persons regardless their massmail setting, check this option.','events-made-easy'); ?>
		</p>
	   </div>
	   <hr>
      <?php _e('Enter a test recipient','events-made-easy'); ?>
      <input type="hidden" name="send_previeweventmailto_id" id="send_previeweventmailto_id" value="" />
      <input type='text' id='eventmail_chooseperson' name='eventmail_chooseperson' placeholder="<?php _e('Start typing a name','events-made-easy'); ?>">
      <button id='previeweventmailButton' class="button-primary action"> <?php _e ( 'Send Preview Mail', 'events-made-easy'); ?></button>
      <div id="previeweventmail-message" style="display:none;" ></div>
	   <hr>
	   <button id='eventmailButton' class="button-primary action"> <?php _e ( 'Queue email', 'events-made-easy'); ?></button>
           <?php
              if (!$eme_queue_mails) {
           ?>
             <div class='eme-message-admin'><p>
           <?php
                 _e('Warning: using this functionality to send mails to attendees can result in a php timeout, so not everybody will receive the mail then. This depends on the number of attendees, the load on the server, ... . If this happens, activate and configure mail queueing.','events-made-easy');
           ?>
              </p></div>
           <?php
              } elseif ($eme_queue_mails && !$eme_queue_mails_configured) {
           ?>
             <div class='eme-message-admin'><p>
           <?php
                 printf(__('Mail queueing has been activated but not yet configured. Go in the <a href="%s">Scheduled actions</a> submenu and configure it now.','events-made-easy'), admin_url("admin.php?page=eme-cron"));
           ?>
              </p></div>
           <?php
              }
           ?>
   </div>
   </form>
   <div id="eventmail-message" style="display:none;" ></div>
  </div>

  <div id="tab-genericmails">
   <h1><?php _e ('Send generic emails','events-made-easy'); ?></h1>
   <?php _e ( "Use the below form to send a generic mail. Don't forget to use the #_UNSUB_URL for unsubscribe possibility.", 'events-made-easy'); ?>
   <form id='send_generic_mail' name='send_generic_mail' action="#" method="post" onsubmit="return false;">
	   <div class="form-field">
		<b><?php _e('Target audience:','events-made-easy'); ?></b><br />
		<label for='eme_send_all_people'><?php _e('Send to all EME people','events-made-easy'); ?></label>
		<input id="eme_send_all_people" name='eme_send_all_people' value='1' type='checkbox' <?php echo $send_to_all_people_checked; ?>><br />
		<?php
	           _e('Deselect this to select specific groups and/or memberships for your mailing','events-made-easy');
		   $memberships=eme_get_memberships();
		?>
		<div id='div_eme_send_groups'><table class='widefat'>
		<tr><td width='20%'><?php $label=eme_esc_html('Send to a number of people','events-made-easy'); $aria_label='aria-label="'.$label.'"'; echo $label; ?></td>
                <td><?php echo eme_ui_multiselect($person_ids,'eme_genericmail_send_persons',$mygroups,5,'',0,'eme_select2_people_class',$aria_label); ?></td></tr>
		<tr><td width='20%'><?php $label=eme_esc_html('Send to a number of groups','events-made-easy'); $aria_label='aria-label="'.$label.'"'; echo $label;?></td>
		<td><?php echo eme_ui_multiselect_key_value($persongroup_ids,'eme_genericmail_send_peoplegroups',$peoplegroups,'group_id','name',5,'',0,'eme_select2_groups_class',$aria_label); ?></td></tr>
		<tr><td width='20%'><?php $label=eme_esc_html('Send to a number of members','events-made-easy'); $aria_label='aria-label="'.$label.'"'; echo $label; ?></td>
                <td><?php echo eme_ui_multiselect($member_ids,'eme_send_members',$mymembergroups,5,'',0,'eme_select2_members_class',$aria_label); ?></td></tr>
		<tr><td width='20%'><?php $label=eme_esc_html('Send to a number of member groups','events-made-easy'); $aria_label='aria-label="'.$label.'"'; echo $label; ?></td>
		<td><?php echo eme_ui_multiselect_key_value($membergroup_ids,'eme_genericmail_send_membergroups',$membergroups,'group_id','name',5,'',0,'eme_select2_groups_class',$aria_label); ?></td></tr>
		<tr><td width='20%'><?php $label=eme_esc_html('Send to active members belonging to','events-made-easy'); $aria_label='aria-label="'.$label.'"'; echo $label; ?></td>
		<td><?php echo eme_ui_multiselect_key_value($membership_ids,'eme_send_memberships',$memberships,'membership_id','name',5,'',0,'eme_select2_memberships_class',$aria_label); ?></td></tr>
                </table>
		</div>
	   </div>
	   <div class="form-field">
		<p>
		<b><?php _e('Subject','events-made-easy'); ?></b><br />
		<input type="text" name="generic_mail_subject" id="generic_mail_subject" value="<?php echo eme_esc_html($generic_mail_subject); ?>" required='required' size='40' />
		</p>
	   </div>
	   <div class="form-field">
		<p>
		<b><?php _e('Message','events-made-easy'); ?></b><br />
		<?php $templates_array=eme_get_templates_array_by_id('mail'); ?>
		<?php _e('Either choose from a template: ','events-made-easy'); echo eme_ui_select(0,'generic_message_template',$templates_array); ?><br />
		<?php _e('Or enter your own: ','events-made-easy');
		if (get_option('eme_rsvp_send_html')) {
		  	// for mails, let enable the full html editor
			$eme_editor_settings = eme_get_editor_settings(true);
			wp_editor($generic_mail_message,'generic_mail_message',$eme_editor_settings);
			if (current_user_can('unfiltered_html')) {
				echo "<div class='eme_notice_unfiltered_html'>";
				_e('Your account has the ability to post unrestricted HTML content here, except javascript.', 'events-made-easy');
				echo "</div>";
			}
		} else {
			echo "<textarea name='generic_mail_message' id='generic_mail_message' rows='10' required='required'>".eme_esc_html($generic_mail_message)."</textarea>";
		}
		?>
		</p>
	   </div>
	   <div>
		<?php _e('You can use any placeholders mentioned here:','events-made-easy');
		print "<br /><a href='//www.e-dynamics.be/wordpress/category/documentation/7-placeholders/7-12-people/'>".__('People placeholders','events-made-easy')."</a> (".__('for ','events-made-easy').__('People or groups','events-made-easy').")";
		print "<br /><a href='//www.e-dynamics.be/wordpress/category/documentation/7-placeholders/7-14-members/'>".__('Member placeholders','events-made-easy')."</a> (".__('for ','events-made-easy').__('members','events-made-easy').")";
	        print "<br />".__('You can also use any shortcode you want.','events-made-easy');
		?>
	   </div>
           <hr>
	   <div id='div_generic_mailing_attach'>
	   <p>
	   <b><?php _e('Optionally add attachments to your mailing','events-made-easy'); ?></b><br />
           <span id="generic_attach_links"><?php echo $attach_url_string; ?></span>
           <input type="hidden" name="eme_generic_attach_ids" id="eme_generic_attach_ids" value="<?php echo $attachment_ids; ?>">
           <input type="button" name="generic_attach_button" id="generic_attach_button" value="<?php _e('Add attachments', 'events-made-easy');?>">
           <input type="button" name="generic_remove_attach_button" id="generic_remove_attach_button" value="<?php _e('Remove attachments', 'events-made-easy');?>">
           </p>
	   </div>
	   <?php
	   if ($eme_queue_mails) { ?>
	   <hr>
	   <div id='div_generic_mailing_definition'>
		<p>
		<b><?php _e('Set mailing name and start date and time','events-made-easy'); ?></b><br />
                <label for='genericmail_mailing_name'><?php _e('Mailing name: ','events-made-easy'); ?></label> <input type='text' name='genericmail_mailing_name' id='genericmail_mailing_name' value='' required='required'><br />
                <?php _e('Start date and time: ','events-made-easy'); ?>
		<input type='hidden' name='genericmail_actualstartdate' id='genericmail_actualstartdate' value=''>
                <input type='text' readonly='readonly' name='genericmail_startdate' id='genericmail_startdate' data-date='' data-alt-field='genericmail_actualstartdate' data-multiple-dates="true" style="background: #FCFFAA;" /><br />
		<span id='genericmail-specificdates' class="eme_smaller"></span>
		<span id='genericmail-multidates-expl' class="eme_smaller"><?php _e('(multiple dates can be selected, in which case the mailing will be planned on each selected date and time)','events-made-easy'); ?></span>
		</p>
	   </div>
	   <div>
		<p>
		<label for='generic_send_now'><?php _e('Or check this option to send the mail immediately:','events-made-easy'); ?></label>
                <input id="generic_send_now" name='generic_send_now' value='1' type='checkbox' /><br />
		</p>
	   </div>
	   <?php } ?>
	   <hr>
	   <div id='div_generic_ignore_massmail_setting'>
		<p>
		<b><label for='genericmail_ignore_massmail_setting'><?php _e('Ignore massmail setting:','events-made-easy'); ?></label></b>
                <input id="genericmail_ignore_massmail_setting" name='genericmail_ignore_massmail_setting' value='1' type='checkbox' <?php echo $ignore_massmail_setting; ?> /><br />
                <?php _e('When sending a mail to all EME people or certain groups, it is by default only sent to the people who have indicated they want to receive mass mailings. If you need to send the mail to all the persons regardless their massmail setting, check this option.','events-made-easy'); ?>
		</p>
	   </div>
	   <hr>
      <?php _e('Enter a test recipient','events-made-easy'); ?>
      <input type="hidden" name="send_previewmailto_id" id="send_previewmailto_id" value="" />
      <input type='text' id='chooseperson' name='chooseperson' placeholder="<?php _e('Start typing a name','events-made-easy'); ?>">
      <button id='previewmailButton' class="button-primary action"> <?php _e ( 'Send Preview Mail', 'events-made-easy'); ?></button>
      <div id="previewmail-message" style="display:none;" ></div>
      <hr>
      <button id='genericmailButton' class="button-primary action"> <?php _e ( 'Queue email', 'events-made-easy'); ?></button>
           <?php
              if (!$eme_queue_mails) {
           ?>
             <div class='eme-message-admin'><p>
           <?php
                 _e('Warning: using this functionality to send mails to attendees can result in a php timeout, so not everybody will receive the mail then. This depends on the number of attendees, the load on the server, ... . If this happens, activate and configure mail queueing.','events-made-easy');
           ?>
              </p></div>
           <?php
              } elseif ($eme_queue_mails && !$eme_queue_mails_configured) {
           ?>
             <div class='eme-message-admin'><p>
           <?php
                 printf(__('Mail queueing has been activated but not yet configured. Go in the <a href="%s">Scheduled actions</a> submenu and configure it now.','events-made-easy'), admin_url("admin.php?page=eme-cron"));
           ?>
              </p></div>
           <?php
              }
           ?>
   </form>
   <div id="genericmail-message" style="display:none;" ></div>
  </div>

  <div id="tab-sentmail">
   <h1><?php _e ('Sent emails','events-made-easy'); ?></h1>
   <div class='eme-message-admin'><p>
    <?php _e('If you want to archive old mailings and clean up old mails automatically, check the option "Automatically archive old mailings and remove old mails" in the GDPR Settings of EME','events-made-easy'); ?>
   </p></div>
   <form id='search_mail' name='search_mail' action="#" method="post" onsubmit="return false;">
   <label for='search_text'><?php _e('Enter the search text (leave empty to show the last 100 emails sent)','events-made-easy'); ?></label>
   <input type="text" name="search_text" id="search_text" value="" />
   <input id="search_failed" name='search_failed' value='1' type='checkbox' ><label for='search_failed'><?php _e ( 'Only show failed emails', 'events-made-easy'); ?></label>
   <button id='searchmailButton' class="button-primary action"> <?php _e ( 'Search', 'events-made-easy'); ?></button>
   </form>
   <br />
    <div id="searchmail-message" style="display:none;" ></div>
  </div>
  <div id="tab-testmail">
   <h1><?php _e ('Test mail settings','events-made-easy'); ?></h1>
    <div id="testmail-message" style="display:none;" ></div>
   <?php _e ( 'Use the below form to send a test mail', 'events-made-easy'); ?>
   <form id='send_testmail' name='send_testmail' action="#" method="post" onsubmit="return false;">
   <label for='testmail_to'><?php _e('Enter the recipient','events-made-easy'); ?></label>
   <input type="text" name="testmail_to" id="testmail_to" value="" />
   <button id='testmailButton' class="button-primary action"> <?php _e ( 'Send Mail', 'events-made-easy'); ?></button>
   </form>
  </div>
</div> <!-- mail-tabs -->

</div> <!-- wrap -->
   <?php
}

add_action( 'wp_ajax_eme_get_mailings_div', 'eme_ajax_mailings_div' );
function eme_ajax_mailings_div() {
   check_ajax_referer('eme_admin','eme_admin_nonce');
   $nonce_field = wp_nonce_field('eme_admin','eme_admin_nonce',false,false);

   ?>
   <h1><?php _e ('Mailings overview','events-made-easy'); ?></h1>
   <?php
   _e ( 'Here you can find an overview of all planned, ongoing or completed mailings. For an overview of all emails, check the "Sent emails" tab.', 'events-made-easy');
   print "<br>";
   _e('If you want to archive old mailings and clean up old mails automatically, check the option "Automatically archive old mailings and remove old mails" in the GDPR Settings of EME','events-made-easy');
   if (!get_option('eme_queue_mails')) {
	   print "<div class='eme-message-admin'><p>";
	   _e ('Mail queueing is not activated, so sent mails will only be visible in the "Sent emails" tab','events-made-easy');
	   print "</p></div>";
   }
   ?>

   <form action="#" method="post">
   <?php echo $nonce_field; ?>
   <select id="eme_admin_action" name="eme_admin_action">
   <option value="" selected="selected"><?php _e ( 'Bulk Actions' , 'events-made-easy'); ?></option>
   <option value="deleteMailings"><?php _e ( 'Delete selected mailings','events-made-easy'); ?></option>
   <option value="archiveMailings"><?php _e ( 'Archive selected mailings','events-made-easy'); ?></option>
   </select>
   <button id="MailingsActionsButton" class="button-secondary action"><?php _e ( 'Apply' , 'events-made-easy'); ?></button>

   <?php
   $archive=0;
   $mailings = eme_get_mailings($archive);
   $areyousure = esc_html__('Are you sure you want to do this?','events-made-easy');
   print "<table class='eme_mailings_table' id='eme_mailings_table'>";
   print "<thead><tr>";
   print '<th class="manage-column column-cb check-column" scope="col"><input type="checkbox" class="select-all" value="1" /></th>';
   print "<th>".__('Name','events-made-easy')."</th>";
   print "<th>".__('Subject','events-made-easy')."</th>";
   print "<th>".__('Planned execution time','events-made-easy')."</th>";
   print "<th>".__('Status','events-made-easy')."</th>";
   print "<th>".__('Unique read count','events-made-easy')."</th>";
   print "<th>".__('Read count','events-made-easy')."</th>";
   print "<th>".__('Extra info','events-made-easy')."</th>";
   print "<th>".__('Report','events-made-easy')."</th>";
   print "<th>".__('Action','events-made-easy')."</th>";
   print "</tr></thead><tbody>";
   foreach ($mailings as $mailing) {
	   $id=$mailing['id'];
	   if ($mailing['status']=='cancelled') {
		   $status=__('Cancelled','events-made-easy');
		   $stats=unserialize($mailing['stats']);
		   $extra=sprintf(__('%d mails sent, %d mails failed, %d mails cancelled','events-made-easy'),$stats['sent'],$stats['failed'],$stats['cancelled']);
		   $action = "<a onclick='return areyousure(\"$areyousure\");' href='".wp_nonce_url(admin_url("admin.php?page=eme-emails&amp;eme_admin_action=delete_mailing&amp;id=".$id),'eme_admin','eme_admin_nonce')."'>".__('Delete','events-made-easy')."</a> <a onclick='return areyousure(\"$areyousure\");' href='".wp_nonce_url(admin_url("admin.php?page=eme-emails&amp;eme_admin_action=archive_mailing&amp;id=".$id),'eme_admin','eme_admin_nonce')."'>".__('Archive','events-made-easy')."</a>";
	   } elseif ($mailing['status']=='initial') {
		   $status=__('Initializing ...','events-made-easy');
		   $stats = "";
		   $extra = "";
		   $action = "";
	   } elseif ($mailing['status']=='planned') {
		   $status=__('Planned','events-made-easy');
		   $stats = eme_get_mailing_stats($id);
		   $extra=sprintf(__('%d mails left','events-made-easy'),$stats['planned']);
		   $action = "<a onclick='return areyousure(\"$areyousure\");' href='".wp_nonce_url(admin_url("admin.php?page=eme-emails&amp;eme_admin_action=cancel_mailing&amp;id=".$id),'eme_admin','eme_admin_nonce')."'>".__('Cancel','events-made-easy')."</a>";
	   } elseif ($mailing['status']=='ongoing') {
		   $status=__('Ongoing','events-made-easy');
		   $stats = eme_get_mailing_stats($id);
		   $extra=sprintf(__('%d mails sent, %d mails failed, %d mails left','events-made-easy'),$stats['sent'],$stats['failed'],$stats['planned']);
		   $action = "<a onclick='return areyousure(\"$areyousure\");' href='".wp_nonce_url(admin_url("admin.php?page=eme-emails&amp;eme_admin_action=cancel_mailing&amp;id=".$id),'eme_admin','eme_admin_nonce')."'>".__('Cancel','events-made-easy')."</a>";
	   } elseif ($mailing['status']=='completed' || $mailing['status']=='') {
		   $status=__('Completed','events-made-easy');
		   $stats=unserialize($mailing['stats']);
		   $extra=sprintf(__('%d mails sent, %d mails failed','events-made-easy'),$stats['sent'],$stats['failed']);
		   $action = "<a onclick='return areyousure(\"$areyousure\");' href='".wp_nonce_url(admin_url("admin.php?page=eme-emails&amp;eme_admin_action=delete_mailing&amp;id=".$id),'eme_admin','eme_admin_nonce')."'>".__('Delete','events-made-easy')."</a> <a onclick='return areyousure(\"$areyousure\");' href='".wp_nonce_url(admin_url("admin.php?page=eme-emails&amp;eme_admin_action=archive_mailing&amp;id=".$id),'eme_admin','eme_admin_nonce')."'>".__('Archive','events-made-easy')."</a>";
	   }
	   if (!empty($mailing['subject']) && !empty($mailing['body']))
		   $action .= " <a href='".wp_nonce_url(admin_url("admin.php?page=eme-emails&amp;eme_admin_action=reuse_mailing&amp;id=".$id),'eme_admin','eme_admin_nonce')."'>".__('Reuse','events-made-easy')."</a>";
	   print "<tr>";
	   print "<td><input type='checkbox' class='row-selector' value='$id' name='mailing_ids[]' /></td>";
	   print "<td>".eme_esc_html($mailing['name'])."</td>";
	   print "<td>".eme_esc_html($mailing['subject'])."</td>";
	   print "<td>".eme_localized_datetime($mailing['planned_on'])."</td>";
	   print "<td>".eme_esc_html($status)."</td>";
	   print "<td>".intval($mailing['read_count'])."</td>";
	   print "<td>".intval($mailing['total_read_count'])."</td>";
	   print "<td>".eme_esc_html($extra)."</td>";
	   if ($mailing['status']=='archived')
		   print "<td>&nbsp;</td>";
	   else
		   print "<td><a href='".wp_nonce_url(admin_url("admin.php?page=eme-emails&amp;eme_admin_action=report_mailing&amp;id=".$id),'eme_admin','eme_admin_nonce')."'>".__('Report','events-made-easy')."</a></td>";
	   print "<td>".$action."</td>";
	   print "</tr>";
   }
   print "</tbody></table></form>";
   wp_die();
}

add_action( 'wp_ajax_eme_get_mail_archive_div', 'eme_ajax_mail_archive_div' );
function eme_ajax_mail_archive_div() {
   check_ajax_referer('eme_admin','eme_admin_nonce');
   $nonce_field = wp_nonce_field('eme_admin','eme_admin_nonce',false,false);
   ?>
   <h1><?php _e ('Mail archive','events-made-easy'); ?></h1>
   <?php
   _e ( 'Here you can find an overview of all archived mailings', 'events-made-easy');
   ?>
   <form action="#" method="post">
   <?php echo $nonce_field; ?>
   <select id="eme_admin_action" name="eme_admin_action">
   <option value="" selected="selected"><?php _e ( 'Bulk Actions' , 'events-made-easy'); ?></option>
   <option value="deleteMailings"><?php _e ( 'Delete selected mailings','events-made-easy'); ?></option>
   </select>
   <button id="MailingsActionsButton" class="button-secondary action"><?php _e ( 'Apply' , 'events-made-easy'); ?></button>

   <?php
   $archive=1;
   $mailings = eme_get_mailings($archive);
   $areyousure = esc_html__('Are you sure you want to do this?','events-made-easy');
   print "<table class='eme_mailings_table'>";
   print "<thead><tr>";
   print '<th class="manage-column column-cb check-column" scope="col"><input type="checkbox" class="select-all" value="1" /></th>';
   print "<th>".__('Name','events-made-easy')."</th>";
   print "<th>".__('Subject','events-made-easy')."</th>";
   print "<th>".__('Planned execution time','events-made-easy')."</th>";
   print "<th>".__('Unique read count','events-made-easy')."</th>";
   print "<th>".__('Read count','events-made-easy')."</th>";
   print "<th>".__('Extra info','events-made-easy')."</th>";
   print "<th>".__('Action','events-made-easy')."</th>";
   print "</tr></thead><tbody>";
   foreach ($mailings as $mailing) {
	   $id=$mailing['id'];
	   $stats=unserialize($mailing['stats']);
	   $extra=sprintf(__('%d mails sent, %d mails failed, %d mails cancelled','events-made-easy'),$stats['sent'],$stats['failed'],$stats['cancelled']);
	   $action = "<a onclick='return areyousure(\"$areyousure\");' href='".wp_nonce_url(admin_url("admin.php?page=eme-emails&amp;eme_admin_action=delete_mailing&amp;id=".$id),'eme_admin','eme_admin_nonce')."'>".__('Delete','events-made-easy')."</a>";
	   if (!empty($mailing['subject']) && !empty($mailing['body']))
		   $action .= " <a href='".wp_nonce_url(admin_url("admin.php?page=eme-emails&amp;eme_admin_action=reuse_mailing&amp;id=".$id),'eme_admin','eme_admin_nonce')."'>".__('Reuse','events-made-easy')."</a>";
	   print "<tr>";
	   print "<td><input type='checkbox' class='row-selector' value='$id' name='mailing_ids[]' /></td>";
	   print "<td>".eme_esc_html($mailing['name'])."</td>";
	   print "<td>".eme_esc_html($mailing['subject'])."</td>";
	   print "<td>".eme_localized_datetime($mailing['planned_on'])."</td>";
	   print "<td>".intval($mailing['read_count'])."</td>";
	   print "<td>".intval($mailing['total_read_count'])."</td>";
	   print "<td>".eme_esc_html($extra)."</td>";
	   print "<td>".$action."</td>";
	   print "</tr>";
   }
   print "</tbody></table></form>";
   wp_die();
}

?>
