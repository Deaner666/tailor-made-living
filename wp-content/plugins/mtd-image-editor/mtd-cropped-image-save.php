<?php
// Let us access WordPress functions as we're outside the normal load path (this isn't ideal)
require_once( dirname( dirname( dirname( dirname( __FILE__ )))) . '/wp-load.php' );

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// These files need to be included as dependencies when on the front end.
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );

// $encoded_img = $_POST['img'];
// $encoded_img = str_replace(' ','+',$encoded_img);
// $decoded_img = base64_decode($encoded_img);

$img = $_FILES['img'];

$upload_overrides = array( 'test_form' => false );
$movefile = wp_handle_upload( $img, $upload_overrides );
if ( $movefile ) {
    // echo "File is valid, and was successfully uploaded.\n";
    // var_dump( $movefile);
    echo $movefile['url'];
} else {
    echo "Possible file upload attack!\n";
}