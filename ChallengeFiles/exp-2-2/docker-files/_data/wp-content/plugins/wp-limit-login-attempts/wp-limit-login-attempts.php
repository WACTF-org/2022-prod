<?php
/*
  Plugin Name: WP Limit Login Attempts
  Plugin URI: https://ciphercoin.com/
  Description: Limit rate of login attempts and block ip temporarily . It is protecting from brute force attack.
  Author: Arshid
  Author URI: https://ciphercoin.com
  Text Domain: wp-limit-login-attempts
  Version: 2.6.4
*/

/*  create or update table */
register_activation_hook(__FILE__,'wp_limit_login_update_tables');
function wp_limit_login_update_tables(){
    global $wpdb;
    $tablename = $wpdb->prefix."limit_login";
    if($wpdb->get_var("SHOW TABLES LIKE '$tablename'") != $tablename ){

        $sql = "CREATE TABLE `$tablename`  (
		`login_id` INT(11) NOT NULL AUTO_INCREMENT,
		`login_ip` VARCHAR(50) NOT NULL,
        `login_attempts` INT(11) NOT NULL,
		`attempt_time` DATETIME,
		`locked_time` VARCHAR(100) NOT NULL,
		PRIMARY KEY  (login_id)
		);";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
    }
    //Add options
    add_option( 'no_of_wp_limit_login_attepts', '5', '', 'yes' );
    add_option( 'limit_login_attepts_delay_time', '10', '', 'yes' );
    add_option( 'limit_login_attepts_captcha', '3', '', 'yes' );
    add_option( 'limit_login_captcha', 'checked', '', 'yes');
    add_option( 'limit_login_install_date', date('Y-m-d G:i:s'), '', 'yes');
}

/** plugin deactivation **/
register_deactivation_hook(__FILE__,'wp_limit_login_deactivation');
function wp_limit_login_deactivation(){
  error_log("Plugin deactivated..!");
}
/*** Plugin Style  ****/
function wp_limit_login_stylesheet() {
    wp_enqueue_script( 'login_captcha_script', '//code.jquery.com/jquery-1.8.2.js',1);
    wp_enqueue_style( 'login_captcha_style',  plugin_dir_url( __FILE__ )  . 'style.css');
    wp_enqueue_script( 'login_captcha_main_script', plugin_dir_url( __FILE__ ). 'js/main.js',2);
}

/*** Plugin main functions ***/
add_action( 'login_enqueue_scripts', 'wp_limit_login_stylesheet');
add_action( 'plugins_loaded', 'wp_limit_login_init', 1);
add_action( 'login_init', 'wp_limit_session_status', 1);

function wp_limit_is_session_started(){
  if ( php_sapi_name() !== 'cli' ) {
      if ( version_compare(phpversion(), '5.4.0', '>=') ) {
          return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
      } else {
          return session_id() === '' ? FALSE : TRUE;
      }
  }
  return FALSE;
}

function wp_limit_login_head(){
  /* check captcha input */
  if(!isset($_SESSION["popup_flag"]) && empty($_SESSION["popup_flag"])){
      $_SESSION["popup_flag"] = "first" ;
  }

  if(isset($_GET['captcha'])){
      if($_GET['captcha']== $_SESSION["wp_limit_captcha"]){
          $_SESSION["popup_flag"] = "true_0152" ;
      }else{
          $_SESSION["popup_flag"] = "false_0152";
      }
  }

  if(get_option('limit_login_captcha','checked') != 'checked'){
      $_SESSION["popup_flag"] = 'true_0152';
  }
?>
    <script>var popup_flag = "<?php  echo $_SESSION["popup_flag"] ?>";</script>
    <div class='popup' style="display: none;">
      <div class='popup_box'>
          <p class='x' id='x'> &times </p>
          <p>Please enter captcha text</p>
          <img class="captcha" src="<?php echo  plugin_dir_url( __FILE__ ).'/captcha.php';?>" />
          <form class="captcha_form" action="" method="GET">
              <input type="text" placeholder="Enter here.." name="captcha">
              <input class="submit" type="submit" value="Submit">
          </form>
      </div>
    </div>
<?php 
}

function wp_limit_session_status(){
  if( wp_limit_is_session_started() === FALSE ) session_start();
}

function wp_limit_login_init(){
    add_action('login_head', 'wp_limit_login_head');
    add_action('wp_login_failed', 'wp_limit_login_failed');
    add_action('login_errors','wp_limit_login_errors');
    add_filter( 'authenticate', 'wp_limit_login_auth_signon', 30, 3 );
    add_action( 'admin_init', 'wp_limit_login_admin_init' );
}

function wp_limit_login_failed( $username ){

  global $msg, $ip, $wpdb;

  if ($_SESSION["popup_flag"] == "true_0152"){
    $ip = wp_limit_getip();

    $tablename = $wpdb->prefix."limit_login";
    $tablerows = $wpdb->get_results( "SELECT `login_id`, `login_ip`,`login_attempts`,`attempt_time`,`locked_time` FROM  `$tablename`   WHERE `login_ip` =  '$ip'  ORDER BY `login_id` DESC LIMIT 1 " );

    if(count($tablerows) == 1){
        $attempt = $tablerows[0]->login_attempts ;
        $noofattmpt = get_option( 'no_of_wp_limit_login_attepts', 5);
        if( $attempt < $noofattmpt){
          $attempt = $attempt +1;
          $update_table = array(
            'login_id' => $tablerows[0]->login_id ,
            'login_attempts'  => $attempt
          );
          $wpdb->update( $tablename,$update_table, array( 'login_id'=>$tablerows[0]->login_id ) );
          $no_ofattmpt =  $noofattmpt+1;
          $remain_attempt = $no_ofattmpt - $attempt;
          $msg = $remain_attempt.' attempts remaining..!';
          return $msg;
        }else{
            if(is_numeric($tablerows[0]->locked_time)){
              $attempt = $attempt +1;
              $update_table = array(
                'login_id' =>  $tablerows[0]->login_id ,
                'login_attempts'  =>   $attempt ,
              // 'attempt_time' => date('Y-m-d G:i:s'),
                'locked_time' => date('Y-m-d G:i:s')
              );
              $wpdb->update($tablename,$update_table,array('login_id'=>$tablerows[0]->login_id ) );
            }else{
              $attempt = $attempt +1;
              $update_table = array(
                'login_id' =>  $tablerows[0]->login_id ,
                'login_attempts'  =>   $attempt
                //'attempt_time' => date('Y-m-d G:i:s')
              );
              $wpdb->update($tablename,$update_table,array('login_id'=>$tablerows[0]->login_id ) );
            }
            $delay_time = get_option('limit_login_attepts_delay_time');
            $msg = "The maximum number of login attempts has been reached. Please try again in ".$delay_time." minutes";
            return $msg;
          }

          $time_now = date_create(date('Y-m-d G:i:s'));
          $attempt_time = date_create($tablerows[0]->attempt_time);
          // $interval = date_diff($attempt_time, $time_now);

        }else{
            $tablename = $wpdb->prefix."limit_login";
            $newdata = array(
              'login_ip' => $ip,
              'login_attempts' =>  1 ,
              'attempt_time' => date('Y-m-d G:i:s'),
              'locked_time' =>0
            );
            $wpdb->insert($tablename, $newdata);
            $remain_attempt = get_option('no_of_wp_limit_login_attepts',5);
            $msg = $remain_attempt.' attempts remaining..!';
            return $msg;
        }
    }else{
      $_SESSION["popup_flag"] = "first";
      $error = new WP_Error();
      $error->remove('wp_captcha', "Sorry..! captcha");
      return $error;
    }
}


function wp_limit_login_admin_init(){
    if(is_user_logged_in()){
      global $wpdb;
      $tablename = $wpdb->prefix."limit_login";
      $ip = wp_limit_getip();
      wp_limit_login_nag_ignore();
      $tablerows = $wpdb->get_results( "SELECT `login_id`, `login_ip`,`login_attempts`,`locked_time` FROM  `$tablename`   WHERE `login_ip` =  '$ip'  ORDER BY `login_id` DESC LIMIT 1 " );
      if(count($tablerows)==1){
          $update_table = array(
                            'login_id' =>  $tablerows[0]->login_id ,
                            'login_attempts'  =>   0 ,
                            //'attempt_time' => date('Y-m-d G:i:s'),
                            'locked_time' => 0
                          );
          $wpdb->update($tablename, $update_table, array( 'login_id'=>$tablerows[0]->login_id ) );
      }
    }
}



function wp_limit_login_errors($error){
    global $msg;
    $pos_first = strpos($error, 'Proxy');
    $pos_second = strpos($error, 'wait');
    $pos_third = strpos($error, 'captcha');
    if (is_int($pos_first)) {
      $error = "Sorry..! Proxy detected..!";
    }else if($pos_second){
      $delay_time = get_option('limit_login_attepts_delay_time',10);
      $error = "Sorry..! Please wait ".$delay_time." minutes..!";
    }else if($pos_third){
      $error = "Sorry..! Please enter correct captcha..!";
    }else{
      $error = "<strong>Login Failed</strong>: Sorry..! Wrong information..!  </br>".$msg;
    }
    return $error;
}





function wp_limit_login_auth_signon( $user, $username, $password ) {

    global $ip , $msg,$wpdb;
    $ip = wp_limit_getip();

    if ( empty( $username ) || empty( $password ) ) {
        //do_action( 'wp_login_failed' );
    }
    if (isset($_SESSION["popup_flag"]) && $_SESSION["popup_flag"] == "true_0152"){
        $tablename = $wpdb->prefix."limit_login";
        $tablerows = $wpdb->get_results( "SELECT `login_id`, `login_ip`,`login_attempts`,`attempt_time`,`locked_time` FROM  `$tablename`   WHERE `login_ip` =  '$ip'  ORDER BY `login_id` DESC LIMIT 1 " );
        if(count($tablerows)==1){
          $time_now = date_create(date('Y-m-d G:i:s'));
          $attempt_time = date_create($tablerows[0]->attempt_time);
          $interval = date_diff($attempt_time, $time_now);
          if(($interval->format("%s")) <= 1){
            if(($tablerows[0]->login_attempts)!=0){
                wp_redirect(home_url());
                exit;
            }else{
                return $user;
            }
          }else{
            $captcha_popup_attempt = get_option( 'limit_login_attepts_captcha',3);
            if((($tablerows[0]->login_attempts) % $captcha_popup_attempt) == 0){
                if (($tablerows[0]->login_attempts) != 0){
                    $attempts = $tablerows[0]->login_attempts;
                    $attempts = $attempts + 1;
                    $_SESSION["popup_flag"] = "first";
                    $update_table = array(
                      'login_id' =>  $tablerows[0]->login_id ,
                      'login_attempts'  =>   $attempts ,
                    );
                    $wpdb->update($tablename, $update_table, array('login_id'=>$tablerows[0]->login_id ) );
                }
          }

            if(!is_numeric($tablerows[0]->locked_time)){
                $locked_time = date_create($tablerows[0]->locked_time);
                $time_now = date_create(date('Y-m-d G:i:s'));
                $interval = date_diff($locked_time, $time_now);

                $delay_time = get_option('limit_login_attepts_delay_time',10);
                if(($interval->format("%i")) <= $delay_time){
                    $msg = "Sorry..! Please wait". $delay_time." minutes..!";
                    $error = new WP_Error();
                    $error->add('wp_to_many_try', $msg);
                    return $error;
                }else{

                  $update_table = array(
                    'login_id' =>  $tablerows[0]->login_id ,
                    'login_attempts'  =>   0 ,
                    'attempt_time' => date('Y-m-d G:i:s'),
                    'locked_time' => 0
                  );
                  $wpdb->update($tablename,$update_table,array('login_id'=>$tablerows[0]->login_id ) );
                  return $user;
                }
            }else{
              return $user;
            }

          }
        }else{

            return $user;
        }
    } else{

          $_SESSION["popup_flag"] = "first";
          $error = new WP_Error();
          $error->remove('wp_captcha', "Sorry..! captcha");
          return $error;
    }
}


function wp_limit_login_nag_ignore(){
    global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if ( isset($_GET['wp_limit_login_nag_ignore']) && '0' == $_GET['wp_limit_login_nag_ignore'] ) {
          add_user_meta($user_id, 'wp_limit_login_nag_ignore', 'true', true);
    }
}


function wp_limit_getip(){

  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip =esc_sql($_SERVER['HTTP_CLIENT_IP']);
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = esc_sql($_SERVER['HTTP_X_FORWARDED_FOR']);
  } else {
      $ip =  esc_sql($_SERVER['REMOTE_ADDR']);
      if($ip=='::1'){
            //$ip = '127.0.0.1';
          $ip = '127.0.1.6';
      }
  }

  if( (!isset($_SESSION["IP_hash"]) ) && (empty($_SESSION["IP_hash"]) ) ){
      $_SESSION["IP_hash"] = md5( $ip );
    }else{
      if( !empty($_SESSION["IP_hash"]) && ( $_SESSION["IP_hash"] != md5( $ip ) ) ){
        session_unset();
      }
  }
  return md5($ip);
}

//auto fill login
add_action("login_form", "wp_login_attempt_focus_start");
function wp_login_attempt_focus_start() {
    ob_start("wp_login_attempt_focus_replace");
}

function wp_login_attempt_focus_replace($html) {
    return preg_replace("/d.value = '';/", "", $html);
}

add_action("login_footer", "wp_login_attempt_focus_end");
function wp_login_attempt_focus_end() {
    session_write_close();
    ob_end_flush();
}

/* Display a notice that can be dismissed */

add_action('admin_notices', 'wp_limit_login_admin_notice');

function wp_limit_login_admin_notice() {
    global $current_user ;
    $user_id = $current_user->ID;
    if ( ! get_user_meta($user_id, 'wp_limit_login_nag_ignore') ) {
          echo '<div style="border-radius: 4px; -moz-border-radius: 4px; -webkit-border-radius: 4px; background: #EBF8A4; border: 1px solid #a2d246; color: #066711; font-size: 14px; font-weight: bold; height: auto; margin: 30px 15px 15px 0px; overflow: hidden; padding: 4px 10px 6px; line-height: 30px;"><p>';
          printf(__('Your admin is protected. Light wordpress plugin -  <a href="http://www.ciphercoin.com" target="_blank">CipherCoin</a>  |<a href="options-general.php?page=wp-limit-login-attempts">Settings</a> | <a href="%1$s">Hide Notice</a>'), '?wp_limit_login_nag_ignore=0');
          echo "</p></div>";
    }
}

/*  add menue in admin */
add_action( 'admin_menu', 'wp_limit_login_plugin_menu' );

/** Step 1. */
function wp_limit_login_plugin_menu() {
	wp_enqueue_style( 'login_captcha_style',  plugin_dir_url( __FILE__ )  . 'style.css');
	add_options_page( 'My Plugin Options', 'WP Limit Login', 'manage_options', 'wp-limit-login-attempts', 'wp_limit_login_plugin_options' );
}

/** Step 3. */
function wp_limit_login_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="warn_msg">
    <img src="'.plugin_dir_url( __FILE__ )  .'/images/warn.png""> <b>WP Limit Login attempts Lite</b> is a fully functional but limited version of <b><a href="http://www.ciphercoin.com" target="_blank">WP Limit Login attempts Pro</a></b>. Consider upgrading to get access to all premium features and premium support.
  </div>';


	echo '<div class="admin_menu">';
	echo '<h2>WP Limit Login Attempts</h2>';
	//echo '<form method="POST" action="" >';
	echo '<div class="row1"><label>Number of login attempts :</label><input disabled type="number" value="5" name="attempts" class="attempts" ></div>';

	echo '<div class="row2"><label>Lockdown time in minutes:</label><input type="number" value="10"  name="delay" disabled class="delay" ></div>';

	echo '<div class="row3"><label>Number of attempts for captcha:</label><input disabled type="number" value="3" name="no_captcha" class="delay" ></div>';

  echo '<div class="row4"><label>Enable captcha:</label>
  <input class="captcha" type="checkbox" disabled name="enable_captcha" checked></div>';
	echo '<div class="row5"><input type="submit" class="submit_admin" value="Submit"></div>';
	//echo '</form>';
  echo '</div>';

	//<form method="post" action="">
	//<input type="text" name
  echo '<div class="warn_msg">
    <img src="'.plugin_dir_url( __FILE__ )  .'/images/warn.png"">   Please consider upgrading to <b><a href="http://www.ciphercoin.com" target="_blank">WP Limit Login attempts Pro</a></b> if you want to use this feature.
  </div>';
}


// Add settings link on plugin page
function wp_limit_login_settings_link($links) {
  $settings_link = '<a href="options-general.php?page=wp-limit-login-attempts">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'wp_limit_login_settings_link' );

/* Display a notice that can be dismissed */
add_action('admin_notices', 'limit_login_admin_notice');

function limit_login_admin_notice() {

  $install_date = get_option( 'limit_login_install_date', '');
  $install_date = date_create( $install_date );
  $date_now     = date_create( date('Y-m-d G:i:s') );
  $date_diff    = date_diff( $install_date, $date_now );

  if ( $date_diff->format("%d") <= 7 ) {

    return false;
  }

    global $current_user ;
    $user_id = $current_user->ID;

    if ( ! get_user_meta($user_id, 'limit_login_rating_ignore_notice' ) ) {

        echo '<div class="updated"><p>';

        printf(__('Awesome, you\'ve been using <a href="options-general.php?page=wp-limit-login-attempts">
        WP Limit Login Attempts Plugin</a> for more than 1 week. May we ask you to give it a 5-star rating on WordPress? | <a href="%2$s" target="_blank">Ok, you deserved it</a> | <a href="%1$s">I already did</a> | <a href="%1$s">No, not good enough</a>'), 'options-general.php?page=wp-limit-login-attempts&wp_limit_login_rating_ignore=0','https://wordpress.org/plugins/wp-limit-login-attempts/');
        echo "</p></div>";
    }
}

add_action('admin_init', 'wp_limit_login_rating_ignore');

function wp_limit_login_rating_ignore() {
    global $current_user;
    $user_id = $current_user->ID;

    if ( isset($_GET['wp_limit_login_rating_ignore']) && '0' == $_GET['wp_limit_login_rating_ignore'] ) {

        add_user_meta($user_id, 'limit_login_rating_ignore_notice', 'true', true);
    }
}


