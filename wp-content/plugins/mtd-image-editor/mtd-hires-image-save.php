<?php
// Let us access WordPress functions as we're outside the normal load path (this isn't ideal)
require_once( dirname( dirname( dirname( dirname( __FILE__ )))) . '/wp-load.php' );

// if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// These files need to be included as dependencies when on the front end.
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );

// $img = $_FILES['img'];
$img = $_REQUEST['url'];

$attachment_id = media_handle_upload( $img, 0 );
if ( !is_wp_error( $attachment_id ) ) {
    $full_img = wp_get_attachment_image_src( $attachment_id, 'full' ); // upload-proxy
    echo '<img class="preview" alt="" src="'.$full_img[0].'" />';
    // echo $movefile['url'];
} else {
    echo "Possible file upload attack!\n";
}