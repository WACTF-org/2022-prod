<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Register Settings
 */
function gwolle_gb_register_settings() {
	//                                      option_name                   sanitize       default value
	register_setting( 'gwolle_gb_options', 'gwolle_gb_addon-moderation_keys', 'strval' ); // 'true', taken from the Add-On since 4.0.4
	register_setting( 'gwolle_gb_options', 'gwolle_gb-admin_style',       'strval' ); // 'false'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-adminMailContent',  'strval' ); // empty by default
	register_setting( 'gwolle_gb_options', 'gwolle_gb-akismet-active',    'strval' ); // 'false'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-antispam-question', 'strval' ); // empty string
	register_setting( 'gwolle_gb_options', 'gwolle_gb-antispam-answer',   'strval' ); // empty string
	register_setting( 'gwolle_gb_options', 'gwolle_gb-authorMailContent', 'strval' ); // empty by default
	register_setting( 'gwolle_gb_options', 'gwolle_gb-authormoderationcontent', 'strval' ); // empty by default
	register_setting( 'gwolle_gb_options', 'gwolle_gb-entries_per_page',  'intval' ); // 20
	register_setting( 'gwolle_gb_options', 'gwolle_gb-entriesPerPage',    'intval' ); // 20
	register_setting( 'gwolle_gb_options', 'gwolle_gb-excerpt_length',    'intval' ); // 0
	register_setting( 'gwolle_gb_options', 'gwolle_gb-form',              'array'  ); // serialized array, but initially empty
	register_setting( 'gwolle_gb_options', 'gwolle_gb-form_ajax',         'strval' ); // 'true'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-header',            'strval' ); // string, but initially empty
	register_setting( 'gwolle_gb_options', 'gwolle_gb-honeypot',          'strval' ); // 'true'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-honeypot_value',    'intval' ); // random 1 - 100
	register_setting( 'gwolle_gb_options', 'gwolle_gb-labels_float',      'strval' ); // 'true'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-linkAuthorWebsite', 'strval' ); // 'true'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-linkchecker',       'strval' ); // 'true'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-longtext',          'strval' ); // 'true'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-mail-from',         'strval' ); // empty string
	register_setting( 'gwolle_gb_options', 'gwolle_gb-mail_admin_replyContent', 'strval' ); // empty by default
	register_setting( 'gwolle_gb_options', 'gwolle_gb-mail_author',       'strval' ); // 'false'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-mail_author_moderation', 'strval' ); // 'false'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-moderate-entries',  'strval' ); // 'true'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-navigation',        'intval' ); // 0 or 1, default is 0
	register_setting( 'gwolle_gb_options', 'gwolle_gb-nonce',             'strval' ); // 'true'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-notifyByMail',      'strval' ); // comma separated list of User IDs, initially empty
	register_setting( 'gwolle_gb_options', 'gwolle_gb-notice',            'strval' ); // string, but initially empty
	register_setting( 'gwolle_gb_options', 'gwolle_gb-paginate_all',      'strval' ); // 'false'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-read',              'array'  ); // serialized array, but initially empty
	register_setting( 'gwolle_gb_options', 'gwolle_gb-refuse-spam',       'strval' ); // 'false'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-require_login',     'strval' ); // 'false'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-sfs',               'strval' ); // 'false'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-store_ip',          'strval' ); // 'true'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-showEntryIcons',    'strval' ); // 'true'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-showLineBreaks',    'strval' ); // 'false'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-showSmilies',       'strval' ); // 'true'
	register_setting( 'gwolle_gb_options', 'gwolle_gb-timeout',           'strval' ); // 'true'
	register_setting( 'gwolle_gb_options', 'gwolle_gb_version',           'strval' ); // string, mind the underscore
}
add_action( 'admin_init', 'gwolle_gb_register_settings' );


/*
 * Get the setting for Gwolle-GB that is saved as serialized data.
 *
 * @param string $request value 'form' or 'read'.
 *
 * @return
 * - Array with settings for that request.
 * - or false if no setting.
 */
function gwolle_gb_get_setting( $request ) {

	$provided = array( 'form', 'read' );
	if ( in_array( $request, $provided ) ) {
		switch ( $request ) {
			case 'form':
				$defaults = array(
					'form_name_enabled'       => 'true',
					'form_name_mandatory'     => 'true',
					'form_city_enabled'       => 'true',
					'form_city_mandatory'     => 'false',
					'form_email_enabled'      => 'true',
					'form_email_mandatory'    => 'true',
					'form_homepage_enabled'   => 'true',
					'form_homepage_mandatory' => 'false',
					'form_message_enabled'    => 'true',
					'form_message_mandatory'  => 'true',
					'form_message_maxlength'  => 0,
					'form_bbcode_enabled'     => 'false',
					'form_antispam_enabled'   => 'false',
					'form_privacy_enabled'    => 'false',
					);
				$setting = get_option( 'gwolle_gb-form', array() );
				if ( is_string( $setting ) ) {
					$setting = maybe_unserialize( $setting );
				}
				if ( is_array($setting) && ! empty($setting) ) {
					$setting = array_merge( $defaults, $setting );
					return $setting;
				}
				return $defaults;

			case 'read':
				if ( get_option('show_avatars') ) {
					$avatar = 'true';
				} else {
					$avatar = 'false';
				}

				$defaults = array(
					'read_avatar'   => $avatar,
					'read_name'     => 'true',
					'read_city'     => 'true',
					'read_datetime' => 'true',
					'read_date'     => 'false',
					'read_content'  => 'true',
					'read_aavatar'  => 'false',
					'read_editlink' => 'true',
					);
				$setting = get_option( 'gwolle_gb-read', array() );
				if ( is_string( $setting ) ) {
					$setting = maybe_unserialize( $setting );
				}
				if ( is_array($setting) && ! empty($setting) ) {
					$setting = array_merge( $defaults, $setting );
					return $setting;
				}
				return $defaults;

			default:
				return false;
		}
	}
	return false;

}
