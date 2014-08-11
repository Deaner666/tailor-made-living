<?php

// if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );

global $post, $woocommerce, $product;

$file_formats = array("jpg", "png", "gif"); // Set File format
// $upload_dir = wp_upload_dir();
// $filepath = $upload_dir['path'];
// $filepath = get_site_url() . "/wp-content/uploads/2014/08";
// $max_size = 2048 * 1024;
$max_size = 2097152;

if ($_POST['form-submit']=="Upload") {
  $name = $_FILES['form-image']['name'];
  $size = $_FILES['form-image']['size'];
  $uploadedfile = $_FILES['file'];
  $upload_overrides = array( 'test_form' => false );
  echo $name, $size, $uploadedfile, $upload_overrides;

   if (strlen($name)) {
      $extension = substr($name, strrpos($name, '.')+1);
      if (in_array($extension, $file_formats)) { 
          if ($size < $max_size) {
            // $imagename = md5(uniqid().time()).".".$extension;
            // $tmp = $_FILES['form-image']['tmp_name'];
            $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
            if (isset($movefile['url'])) {
     echo '<img class="preview" alt="" src="'.$movefile['url'].'" />';
	     } else {
		 echo "Could not move the file.";
	     }
	  } else {
		echo "Your image size is bigger than 2MB.";
	  }
       } else {
	       echo "Invalid file format.";
       }
  } else {
       echo "Please select image..!";
  }
exit();
}
?>