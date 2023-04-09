jQuery(document).ready( function($) {
	function updateShowHideCaptcha () {
		if ($('input[name=eme_recaptcha_for_forms]').prop('checked')) {
			$('tr#eme_recaptcha_site_key_row').show();
			$('tr#eme_recaptcha_secret_key_row').show(); 
			$('#eme_recaptcha_site_key').prop('required',true);
			$('#eme_recaptcha_secret_key').prop('required',true);
		} else {
			$('tr#eme_recaptcha_site_key_row').hide();
			$('tr#eme_recaptcha_secret_key_row').hide(); 
			$('#eme_recaptcha_site_key').prop('required',false);
			$('#eme_recaptcha_secret_key').prop('required',false);
		}
		if ($('input[name=eme_hcaptcha_for_forms]').prop('checked')) {
			$('tr#eme_hcaptcha_site_key_row').show();
			$('tr#eme_hcaptcha_secret_key_row').show(); 
			$('#eme_hcaptcha_site_key').prop('required',true);
			$('#eme_hcaptcha_secret_key').prop('required',true);
		} else {
			$('tr#eme_hcaptcha_site_key_row').hide();
			$('tr#eme_hcaptcha_secret_key_row').hide(); 
			$('#eme_hcaptcha_site_key').prop('required',false);
			$('#eme_hcaptcha_secret_key').prop('required',false);
		}
	}
	function updateShowHideMailQueueOptions () {
		if ($('input[name=eme_queue_mails]').prop('checked')) {
			$('tr#eme_queued_mails_options_row').show();
		} else {
			$('tr#eme_queued_mails_options_row').hide();
		}
	}
	function updateShowHideRsvpMailNotify () {
		if ($('input[name=eme_rsvp_mail_notify_is_active]').prop('checked')) {
			$('table#rsvp_mail_notify-data').show();
		} else {
			$('table#rsvp_mail_notify-data').hide();
		}
	}

	function updateShowHideRsvpMailSendMethod () {
		if ($('select[name=eme_rsvp_mail_send_method]').val() == 'smtp') {
			$('tr#eme_smtp_host_row').show();
			$('tr#eme_smtp_port_row').show(); 
			$('tr#eme_rsvp_mail_SMTPAuth_row').show();
			$('tr#eme_smtp_username_row').show(); 
			$('tr#eme_smtp_password_row').show(); 
			$('tr#eme_smtp_encryption_row').show(); 
			$('tr#eme_smtp_debug_row').show(); 
			$('tr#eme_smtp_verify_cert_row').show(); 
		} else {
			$('tr#eme_smtp_host_row').hide();
			$('tr#eme_smtp_port_row').hide(); 
			$('tr#eme_rsvp_mail_SMTPAuth_row').hide();
			$('tr#eme_smtp_username_row').hide(); 
			$('tr#eme_smtp_password_row').hide();
			$('tr#eme_smtp_encryption_row').hide(); 
			$('tr#eme_smtp_debug_row').hide(); 
			$('tr#eme_smtp_verify_cert_row').hide(); 
		}
	}

	function updateShowHideRsvpMailSMTPAuth () {
		if ($('input[name=eme_rsvp_mail_SMTPAuth]').prop('checked') && $('select[name=eme_rsvp_mail_send_method]').val() == 'smtp') {
			$('tr#eme_smtp_username_row').show(); 
			$('tr#eme_smtp_password_row').show(); 
		} else {
			$('tr#eme_smtp_username_row').hide(); 
			$('tr#eme_smtp_password_row').hide();
		}
	}

	function updateShowHideRsvpMailSMTPCert () {
                if ($('select[name=eme_smtp_encryption]').val() != 'none' && $('select[name=eme_rsvp_mail_send_method]').val() == 'smtp') {
                        $('tr#eme_smtp_verify_cert_row').show();
                } else {
                        $('tr#eme_smtp_verify_cert_row').hide();
                }
	}

	// for the eme-options pages
	updateShowHideCaptcha();
	updateShowHideRsvpMailNotify();
	updateShowHideRsvpMailSendMethod();
	updateShowHideRsvpMailSMTPAuth();
	updateShowHideRsvpMailSMTPCert();
	updateShowHideMailQueueOptions();
	$('input[name=eme_recaptcha_for_forms]').on("change",updateShowHideCaptcha);
	$('input[name=eme_hcaptcha_for_forms]').on("change",updateShowHideCaptcha);
	$('input[name=eme_rsvp_mail_notify_is_active]').on("change",updateShowHideRsvpMailNotify);
	$('select[name=eme_rsvp_mail_send_method]').on("change",updateShowHideRsvpMailSendMethod);
	$('input[name=eme_rsvp_mail_SMTPAuth]').on("change",updateShowHideRsvpMailSMTPAuth);
	$('select[name=eme_smtp_encryption]').on("change",updateShowHideRsvpMailSMTPCert);
	$('input[name=eme_queue_mails]').on("change",updateShowHideMailQueueOptions);
});

