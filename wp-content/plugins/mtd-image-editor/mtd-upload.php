<?php
echo ABSPATH;
if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );

global $post, $woocommerce, $product;

if(isset($_FILES['form-image']) && ($_FILES['form-image']['size'] > 0)) {

	// Set an array containing a list of acceptable formats
	$allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');
	// Get the type of the uploaded file. This is returned as "type/extension"
    // $arr_file_type = wp_check_filetype(basename($_FILES['form-image']['name']));
    // $uploaded_file_type = $arr_file_type['type'];

    // If the uploaded file is the right format
    // if(in_array($uploaded_file_type, $allowed_file_types)) {

    // }

} // end if exists form-image

echo "You've reached mtd-upload.php";


?>