<?php
/**
 * Plugin Name: WP Visitor Statistics (Real Time Traffic)
 * Plugin URI: http://plugins-market.com/contact-us
 * Description: This plugin will track the web analytics for each page and show various analytics report in admin panel as well as in front end.
 * Version: 5.7
 * Author: osamaesh 
 * Author URI: http://plugins-market.com/
 * Developer: osamaesh
 * Developer URI: http://www.prismitsystems.com
 * Text Domain: wp-stats-manager
 * Domain Path: /languages
 * Copyright:  Plugins-market 2017.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
**/


if ( ! defined( 'ABSPATH' ) ) {
    die( 'Access denied.' );
}

//require_once( ABSPATH. 'wp-includes/pluggable.php');
include_once('notifications.php');



define( 'WSM_PREFIX','wsm' );                       
define( 'WSM_NAME',__('Visitor Statistics (Free)','wp-stats-manager') );
define( 'WSM_DIR', plugin_dir_path( __FILE__ ) );
define( 'WSM_URL', plugin_dir_url( __FILE__ ) );
define( 'WSM_FILE', __FILE__ );
define( 'WSM_ONLINE_SESSION',15 ); //DEFINE ONLINE SESSION TIME IN MINUTES
define( 'WSM_PAGE_LIMIT',10 ); //DEFINE ONLINE SESSION TIME IN MINUTES
global $wsmAdminColors,$wsmAdminJavaScript,$wsmAdminPageHooks,$wsmRequestArray,$arrCashedStats;
$wsmAdminJavaScript='';
$wsmAdminPageHooks=array();
$wsmRequestArray=array();
if(isset($_REQUEST) && is_array($_REQUEST)){
	
	
  
		$page = isset($_REQUEST['page']) ? sanitize_text_field($_REQUEST['page']) : '';
		$subPage = isset($_REQUEST['subPage']) ? sanitize_text_field($_REQUEST['subPage']) : '';
		$subTab = isset($_REQUEST['subTab']) ? sanitize_text_field($_REQUEST['subTab']) : '';
		$wmcAction = isset($_REQUEST['wmcAction']) ? sanitize_text_field($_REQUEST['wmcAction']) : '';
		$action_name = isset($_REQUEST['action_name']) ? sanitize_text_field($_REQUEST['action_name']) : '';
		$visitorId = isset($_REQUEST['visitorId']) ? sanitize_text_field($_REQUEST['visitorId']) : '';
		$setSiteId = isset($_REQUEST['setSiteId']) ? sanitize_text_field($_REQUEST['setSiteId']) : '';
		$setPageId = isset($_REQUEST['setPageId']) ? sanitize_text_field($_REQUEST['setPageId']) : '';
		$setUrlReferrer = isset($_REQUEST['setUrlReferrer']) ? sanitize_text_field($_REQUEST['setUrlReferrer']) : '';
		$setTrackerUrl = isset($_REQUEST['setTrackerUrl']) ? sanitize_text_field($_REQUEST['setTrackerUrl']) : '';
		$setWpUserId = isset($_REQUEST['setWpUserId']) ? sanitize_text_field($_REQUEST['setWpUserId']) : '';
		$siteId = isset($_REQUEST['siteId']) ? intval($_REQUEST['siteId']) : '';
		$rec = isset($_REQUEST['rec']) ? intval($_REQUEST['rec']) : '';
		$rand = isset($_REQUEST['rand']) ? intval($_REQUEST['rand']) : '';
		$h = isset($_REQUEST['h']) ? intval($_REQUEST['h']) : '';
		$m = isset($_REQUEST['m']) ? intval($_REQUEST['m']) : '';
		$s = isset($_REQUEST['s']) ? intval($_REQUEST['s']) : '';
		$url = isset($_REQUEST['url']) ? sanitize_text_field($_REQUEST['url']) : '';
		$uid = isset($_REQUEST['uid']) ? intval($_REQUEST['uid']) : '';
		$pid = isset($_REQUEST['pid']) ? intval($_REQUEST['pid']) : '';
		$fvts = isset($_REQUEST['fvts']) ? sanitize_text_field($_REQUEST['fvts']) : '';
		$vc = isset($_REQUEST['vc']) ? intval($_REQUEST['vc']) : '';
		$idn = isset($_REQUEST['idn']) ? intval($_REQUEST['idn']) : '';
		$refts = isset($_REQUEST['refts']) ? intval($_REQUEST['refts']) : '';
		$lvts = isset($_REQUEST['lvts']) ? sanitize_text_field($_REQUEST['lvts']) : '';
		$fullRef = isset($_REQUEST['fullRef']) ? sanitize_text_field($_REQUEST['fullRef']) : '';
		$send_image = isset($_REQUEST['send_image']) ? intval($_REQUEST['send_image']) : '';
		$cookie = isset($_REQUEST['cookie']) ? intval($_REQUEST['cookie']) : '';
		$res = isset($_REQUEST['res']) ? sanitize_text_field($_REQUEST['res']) : '';
		$gtms = isset($_REQUEST['gtms']) ? sanitize_text_field($_REQUEST['gtms']) : '';
		$pvId = isset($_REQUEST['pvId']) ? sanitize_text_field($_REQUEST['pvId']) : '';
		$browser = isset($_REQUEST['browser']) ? sanitize_text_field($_REQUEST['browser']) : '';
		$os = isset($_REQUEST['os']) ? sanitize_text_field($_REQUEST['os']) : '';
		$device = isset($_REQUEST['device']) ? sanitize_text_field($_REQUEST['device']) : '';
	
	
		$wsmRequestArray = array(
							'page' => $page,
							'subPage' => $subPage,
							'subTab' => $subTab,
							'wmcAction' => $wmcAction,
							'action_name' => $action_name,
							'visitorId' => $visitorId,
							'setSiteId' => $setSiteId,
							'setPageId' => $setPageId,
							'setUrlReferrer' => $setUrlReferrer,
							'setTrackerUrl' => $setTrackerUrl,
							'setWpUserId' => $setWpUserId,
							
							'siteId' => $siteId,
							'rec' => $rec,
							'rand' => $rand,
							'h' => $h,
							'm' => $m,
							's' => $s,
							'url' => $url,
							'uid' => $uid,
							'pid' => $pid,
							
							'fvts' => $fvts,
							'vc' => $vc,
							'idn' => $idn,
							'refts' => $refts,
							'lvts' => $lvts,
							'fullRef' => $fullRef,
							'send_image' => $send_image,
							'cookie' => $cookie,
							'res' => $res,
							'gtms' => $gtms,
							'pvId' => $pvId,
							'browser' => $browser,
							'os' => $os,
							'device' => $device
							);
	
 
}
include_once(WSM_DIR .'includes/'. WSM_PREFIX."_init.php");
wsmInitPlugin::initWsm();
add_action( 'plugins_loaded', function() { load_plugin_textdomain( 'wp-stats-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );	} );
?>