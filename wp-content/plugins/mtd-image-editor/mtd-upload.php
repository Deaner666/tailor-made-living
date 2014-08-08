<?php

// if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $woocommerce, $product;

$file_formats = array("jpg", "png", "gif"); // Set File format
// $upload_dir = wp_upload_dir();
// $filepath = $upload_dir['path'];
$filepath = "/Users/Dave/php_apps/tailormadelive/wp-content/uploads/2014/08/";
// $max_size = 2048 * 1024;
$max_size = 2097152;

if ($_POST['form-submit']=="Upload") {
  $name = $_FILES['form-image']['name'];
  $size = $_FILES['form-image']['size'];

   if (strlen($name)) {
      $extension = substr($name, strrpos($name, '.')+1);
      if (in_array($extension, $file_formats)) { 
          if ($size < $max_size) {
             $imagename = $name . md5(uniqid().time()).".".$extension;
             $tmp = $_FILES['form-image']['tmp_name'];
             if (move_uploaded_file($tmp, $filepath . $imagename)) {
		 echo '<img class="preview" alt="" src="'.$filepath.'/'. 
			$imagename .'" />';
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