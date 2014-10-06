jQuery(document).ready( function() {

  // Keep width and heigh input fields in sync
  jQuery(".sync-width input").keyup(function(){
    jQuery(".sync-width-modal").val(jQuery(".sync-width input").val());
    jQuery( ".sync-width-modal" ).trigger( "change" );
  });
  jQuery(".sync-height input").keyup(function(){
    jQuery(".sync-height-modal").val(jQuery(".sync-height input").val());
    jQuery( ".sync-height-modal" ).trigger( "change" );
  });
  jQuery(".sync-width-modal").keyup(function(){
    jQuery(".sync-width input").val(jQuery(".sync-width-modal").val());
    jQuery( ".sync-width input" ).trigger( "change" );
    doCrop();
  });
  jQuery(".sync-height-modal").keyup(function(){
    jQuery(".sync-height input").val(jQuery(".sync-height-modal").val());
    jQuery( ".sync-height input" ).trigger( "change" );
    doCrop();
  });

  // Monitor filename of uploaded image so we can pass it to doCropSave()
  var uploadedFilename = "";
  jQuery("#form-image").change(function(){
    uploadedFilename = jQuery('#form-image').val();
  });

  // Set width and height for jQuery dialog based on current window size
  var dialogWidth = jQuery( window ).width() * 0.8;
  var dialogHeight = jQuery( window ).height() * 0.85;

  // jQuery dialog settings for image upload modal
  jQuery( "#image-upload-modal" ).dialog({
    modal: true,
    autoOpen: false,
    position: { my: "center", at: "center", of: window },
    width: dialogWidth,
    height: dialogHeight,
    draggable: false,
    resizable: false,
    show: 175,
    hide: 175,
    buttons: [
      { text: "Save", click: function() {
        // Check to make sure dimensions have been entered
        if ( jQuery('#modal-width').val() && jQuery('#modal-height').val() ) {

          if (jQuery('#image-upload-preview img').length != 0) {
            doSaveCrop();
          };

          // Hide the upload image button on the main page
          jQuery('#image-upload-lead-in').hide();
          jQuery('#image-upload-done').show();

          // Close the dialog
          jQuery( this ).dialog( "close" );
        } else {
          alert("Please enter your dimensions!");
          return false;
        }
      } }
    ]
  });

  jQuery( ".modal-opener" ).click(function() {
    jQuery( "#image-upload-modal" ).dialog( "open" );
  });

  // AJAX form submission for upload of image, posts to mtd-upload.php for processing
  jQuery('#form-submit').click(function() {
    jQuery("#image-upload-preview").html('');
    jQuery("#image-upload-preview").html('<img src="' + mtd_site_url + '/wp-content/plugins/mtd-image-editor/images/loadingAnimation.gif" /><p>Progress: <span class="completion">0</span>%</p><p style="margin-top:20px;">Thanks for uploading your image. We need a large file to ensure a high quality finish but it means it may take a while for your image to upload. Why not put the kettle on whilst you\'re waiting?</p><p>As your file uploads we are creating a fast, low resolution version of the image for editing.</p>');
    jQuery("#image-upload-form").ajaxForm({
                                  target: '#image-upload-preview',
                                  uploadProgress: function(event, position, total, percentComplete) {
                                    jQuery('.completion').html(percentComplete);
                                  },
                                  success: function() {
                                    // Update WooCommerce product image
                                    jQuery(".wp-post-image").attr('src', jQuery(".preview").attr('src'));
                                    // Add Aviary ID to the modal preview so we know which image to update
                                    jQuery("#image-upload-preview img").attr('id', 'aviary-image');
                                    // Hidden field in the Gravity Form
                                    // TODO: This is currently pointing to the proxy image - make it point to the
                                    // original upload or the original after editing in Aviary
                                    jQuery("input[value='image_url']").attr('value', jQuery(".preview").attr('src'));
                                    // Hide the upload form
                                    jQuery('#image-upload-form').hide();
                                    // Show the image uploaded div, Aviary edit button and the width / height inputs
                                    jQuery('#image-uploaded').show();
                                    jQuery('#aviary-edit-button').show();
                                    jQuery('.image-dimensions-field').show();
                                    // Crop tools for the image
                                    doCrop();
                                  }
                                }).submit(function(){
                                  return false; // To avoid default form submit which results in two uploads
                                });
  });

  // Function to update the crop preview
  function doCrop() {
    var currentWidth = dialogWidth * .48;
    if ( jQuery('.sync-height-modal').val() && jQuery('.sync-width-modal').val() ) {
      var ratioWidthToHeight = jQuery('.sync-height-modal').val() / jQuery('.sync-width-modal').val();
      var currentHeight = currentWidth * ratioWidthToHeight;
    } else {
      var currentHeight = 250;
    }
    jQuery( '#image-upload-preview img' ).cropbox({
      width: currentWidth,
      height: currentHeight,
      showControls: 'always'
    }).on('cropbox', function(e, data) {
      console.log('crop window: ' + data);
    });
  }

  // Function to send the cropped image to PHP script that saves it
  // to the WordPress filesystem (posts to mtd-cropped-image-save.php)
  function doSaveCrop() {
    var crop = jQuery('#image-upload-preview img').data('cropbox');
    var data = new FormData();
    var blob = crop.getBlob();
    var filename = uploadedFilename+"_cropped.png";
    data.append('img', blob, filename);

    jQuery.ajax({
      type: 'POST',
      contentType: false,
      url: mtd_site_url+'/wp-content/plugins/mtd-image-editor/mtd-cropped-image-save.php',
      cache: false,
      processData: false,
      data: data,
      success: function(response){
        console.log('Returned data from crop save PHP page:' + response);
        // Hidden field in the Gravity Form
        jQuery("input[value='cropped_image_url']").attr('value', response);
        // Update WooCommerce product image
        jQuery(".wp-post-image").attr('src', response);
      }
    });
  }

});

// Function to generate unique signature to pass to Aviary HiRes API
var getAuth = function(apikey, apisecret, method) {
  var getSalt = function() {
    return generateUUID(); // random id
  };

  var getUnixTimestamp = function() {
    return (new Date()).getTime() / 1000; 
  };

  var getSignature = function(key, secret, timestamp, salt, encryptionmethod) {
    // Concat string
    var sig = key + secret + timestamp + salt;
    // Encrypt
    return SparkMD5.hash(sig);
  };

  var authObj = {
    apiKey: apikey,
    salt: getSalt(),
    timestamp: getUnixTimestamp().toString(),
    encryptionMethod: method
  };

  authObj.signature = getSignature(authObj.apiKey, apisecret, authObj.timestamp,  authObj.salt, authObj.encryptionMethod);

  return authObj;
}

// Function to generate a random version 4 GUID
function generateUUID() {
  var d = new Date().getTime();
  var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
    var r = (d + Math.random()*16)%16 | 0;
    d = Math.floor(d/16);
    return (c=='x' ? r : (r&0x7|0x8)).toString(16);
  });
  return uuid;
};