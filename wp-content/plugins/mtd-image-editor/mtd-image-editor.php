<?php

/**
 * Plugin Name: Moortor Design Image Editor
 * Plugin URI: https://github.com/Deaner666/mtd-image-editor
 * Description: Plugin for integrating Aviary image editor into WooCommerce products
 * Version: 0.0.1
 * Author: Dave Dean
 * Author URI: http://www.moortor-design.co.uk
 * License: GPL2
 */

/*  Copyright 2014  Dave Dean  (email : dave@moortor-design.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Is Woocommerce installed?
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {


    //////////////////////////////////////////////////
    // 
    // Plugin activation
    // 
    //////////////////////////////////////////////////

    function mtd_activation() {
        // Activation code here
    }
    
    register_activation_hook(__FILE__, 'mtd_activation');

    //////////////////////////////////////////////////
    // 
    // Register scripts and styles
    // 
    //////////////////////////////////////////////////

    add_action('wp_enqueue_scripts', 'mtd_enqueue_scripts');

    function mtd_enqueue_scripts() {
        // Javascript...
        wp_register_script( 'file_upload_modal', plugins_url('/js/file_upload_modal.js', __FILE__ ), array( 'jquery-ui-widget' ) );
        wp_register_script( 'jquery-cropbox', plugins_url('/js/jquery.cropbox.js', __FILE__ ), array( 'jquery' ) );
        // Pass site_url info to file_upload_modal JS file
        wp_localize_script( 'file_upload_modal', 'mtd_site_url', get_site_url() );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-widget' );
        wp_enqueue_script( 'jquery-ui-dialog' );
        wp_enqueue_script( 'jquery-form' );
        wp_enqueue_script( 'jquery-md5', plugins_url('/js/spark-md5.min.js', __FILE__ ), array( 'jquery' ) );
        wp_enqueue_script( 'file_upload_modal' );
        wp_enqueue_script( 'aviary_editor', 'http://feather.aviary.com/js/feather.js' );
        wp_enqueue_script( 'jquery-cropbox' );
        // CSS...
        wp_enqueue_style( 'file_upload_modal', plugins_url('/css/file_upload_modal.css', __FILE__ ) );
        wp_enqueue_style( 'jquery-cropbox', plugins_url('/css/jquery.cropbox.css', __FILE__ ) );
        wp_enqueue_style( 'jquery-ui', plugins_url('/css/jquery-ui.css', __FILE__ ) );
    }


    //////////////////////////////////////////////////
    // 
    // Add 1,024 pixel wide attachment size for creating a proxy image
    // to work with when customers upload files
    // 
    //////////////////////////////////////////////////

    add_image_size( 'upload-proxy', 1024 ); // 1,024 pixels wide (and unlimited height)

    // Let us access it in the Media Library admin
    add_filter( 'image_size_names_choose', 'my_custom_sizes' );
    function my_custom_sizes( $sizes ) {
        return array_merge( $sizes, array(
            'upload-proxy' => __( 'Upload Proxy' ),
        ) );
    }

    

    //////////////////////////////////////////////////
    // 
    // Hook into single-product.php to add image upload modal
    // 
    //////////////////////////////////////////////////

    add_action('woocommerce_single_product_summary', 'mtd_image_upload_modal', 30);

    function mtd_image_upload_modal() {
        include_once 'templates/image-upload-modal.php';
        // locate_template( 'templates/image-upload-modal.php', true, true );
    }

    //////////////////////////////////////////////////
    // 
    // Gravity Forms Custom Validation for hidden fields
    // 
    //////////////////////////////////////////////////

    add_filter("gform_validation_1", "validate_hidden_fields");

    function validate_hidden_fields($validation_result) {
        $form = $validation_result['form'];

        foreach( $form["fields"] as &$field ) {
            if ( rgpost("input_{$field['id']}") == 'image_url' OR rgpost("input_{$field['id']}") == 'cropped_image_url' ) {
                $validation_result["is_valid"] = false;
                $field["failed_validation"] = true;
                $field["validation_message"] = '<div class="validation_error">Aww heck, something went wrong. Did you upload an image?</div>';
            }
            continue;
        }

        $validation_result['form'] = $form;
        return $validation_result;
    }

    add_filter("gform_validation_message_1", "change_message", 10, 2);
    
    function change_message($message, $form) {
      return '<div class="validation_error">Aww heck, something went wrong. Did you upload an image?</div>';
    }



    //////////////////////////////////////////////////
    // 
    // Plugin deactivation
    // 
    //////////////////////////////////////////////////

    function mtd_deactivation() {
        // Deactivation code here
    }
    
    register_deactivation_hook(__FILE__, 'mtd_deactivation');


} // end WooCommerce detection

?>