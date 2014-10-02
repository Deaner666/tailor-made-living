<?php
// Let us access WordPress functions as we're outside the normal load path (this isn't ideal)
require_once( dirname( dirname( dirname( dirname( __FILE__ )))) . '/wp-load.php' );

// if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// These files need to be included as dependencies when on the front end.
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );

$url = $_REQUEST['url'];
$tmp = download_url( $url );
$post_id = 0;
$desc = "Edited with Aviary";
$file_array = array();

// Set variables for storage
// fix file filename for query strings
preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $url, $matches);
$file_array['name'] = basename($matches[0]);
$file_array['tmp_name'] = $tmp;

// If error storing temporarily, unlink
if ( is_wp_error( $tmp ) ) {
	@unlink($file_array['tmp_name']);
	$file_array['tmp_name'] = '';
}

// Let WordPress handle file creation and adding to the media library
$attachment_id = media_handle_sideload( $file_array, $post_id, $desc );

if ( is_wp_error( $attachment_id ) ) {
	echo $attachment_id->get_error_message();
	@unlink($file_array['tmp_name']);
	return $id;
}

$full_img = wp_get_attachment_image_src( $attachment_id, 'full' ); // upload-proxy
echo '<img class="preview" alt="" src="'.$full_img[0].'" />';

///////////////////////////////////////////////////////////
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ob_start();
var_dump($_REQUEST);
$image_data = file_get_contents($_REQUEST['url']);
file_put_contents("photo.jpg",$image_data);
$msg = ob_get_contents();
mail('You@Your.org', 'PostURL Data', $msg);
ob_end_clean();
///////////////////////////////////////////////////////////