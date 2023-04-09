<?php
 
// Start the session
function is_session_started(){
            if ( php_sapi_name() !== 'cli' ) {
                if ( version_compare(phpversion(), '5.4.0', '>=') ) {
                    return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
                } else {
                    return session_id() === '' ? FALSE : TRUE;
                }
            }
            return FALSE;
        }
          
if( is_session_started() === FALSE ) session_start();
 

// Set the content-type
header('Content-Type: image/png');

// Create the image
$im =  @imagecreatefrompng("./images/white-wave.png");

// Create some colors
$white = imagecolorallocate($im, 255, 255, 255);
$grey = imagecolorallocate($im, 128, 128, 128);
$black = imagecolorallocate($im, 0, 0, 0);
//imagefilledrectangle($im, 0, 0, 399, 29, $white);

// The text to draw
$text = substr(md5(microtime()),rand(0,26),5);
$_SESSION["wp_limit_captcha"] = $text;

// Replace path by your own font path
$font = './images/coolvetica.ttf';



// Add the text
imagettftext($im, 20, 0, 35, 35, $black, $font, $text);

imagepng($im);
imagedestroy($im);
?>