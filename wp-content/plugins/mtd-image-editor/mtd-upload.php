<?php
// Let us access WordPress functions as we're outside the normal load path (this isn't ideal)
require_once( dirname( dirname( dirname( dirname( __FILE__ )))) . '/wp-load.php' );

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );

// global $post, $woocommerce, $product;

if(isset($_FILES['form-image']) && ($_FILES['form-image']['size'] > 0)) {

	// Set an array containing a list of acceptable formats
	$allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');
	// Get the type of the uploaded file. This is returned as "type/extension"
  $arr_file_type = wp_check_filetype(basename($_FILES['form-image']['name']));
  $uploaded_file_type = $arr_file_type['type'];
  // Set the max size of a file upload
  $max_size = 2100000;
              
  // If the uploaded file is the right format
  if(in_array($uploaded_file_type, $allowed_file_types)) {
    $uploadedfile = $_FILES['form-image'];
    $upload_overrides = array( 'test_form' => false );
    if ($_FILES['form-image']['size'] < $max_size) {
      $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
      if (isset($movefile['url'])) {
        echo '<img class="preview" alt="" src="'.$movefile['url'].'" />';
      } else { echo "Error: Could not move the file"; } // end if isset $movefile
    } else { echo "Error: File is larger than 16MB"; } // end if < $max_size
  } else { echo "Error: File is not a JPEG, GIF or PNG image"; } // end if in $allowed_file_types

} // end if exists form-image

?>