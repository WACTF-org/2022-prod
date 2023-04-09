<?php 

//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

    global $wpdb;
    $tablename = $wpdb->prefix."limit_login"; 

    if($wpdb->get_var("SHOW TABLES LIKE '$tablename'") == $tablename ){
        $sql = "DROP TABLE `$tablename`;";  
        $wpdb->query($sql);
    }

    //Delete options 
     delete_option('no_of_wp_limit_login_attepts');
    delete_option( 'limit_login_attepts_delay_time');
    delete_option( 'limit_login_attepts_captcha');
?>