<?php
// Let us access WordPress functions as we're outside the normal load path (this isn't ideal)
require_once( dirname( dirname( dirname( dirname( __FILE__ )))) . '/wp-load.php' );

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// These files need to be included as dependencies when on the front end.
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );


// global $post, $woocommerce, $product;

if(isset($_FILES['form-image']) && ($_FILES['form-image']['size'] > 0)) {

	// Set an array containing a list of acceptable formats
	$allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');
	// Get the type of the uploaded file. This is returned as "type/extension"
  $arr_file_type = wp_check_filetype(basename($_FILES['form-image']['name']));
  $uploaded_file_type = $arr_file_type['type'];
  // Set the max size of a file upload
  $max_size = 12582912;
              
  // If the uploaded file is the right format
  if(in_array($uploaded_file_type, $allowed_file_types)) {
    if ($_FILES['form-image']['size'] < $max_size) {
      $attachment_id = media_handle_upload( 'form-image', 0 );
      if ( !is_wp_error( $attachment_id ) ) {
        $proxy_img = wp_get_attachment_image( $attachment_id, 'upload-proxy' );
        echo $proxy_img;
        echo '<img class="preview" alt="" src="'.$proxy_img[0].'" />';
      } else { echo $attachment_id->get_error_message(); } // end if isset $movefile
    } else { echo "Error: File is larger than 12MB"; } // end if < $max_size
  } else { echo "Error: File is not a JPEG, GIF or PNG image"; } // end if in $allowed_file_types

} // end if exists form-image

?>