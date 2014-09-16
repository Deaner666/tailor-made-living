jQuery(document).ready( function() {

  // Keep width and heigh input fields in sync
  jQuery(".sync-width input").keyup(function(){
    jQuery(".sync-width-modal").val(jQuery(".sync-width input").val());
  });
  jQuery(".sync-height input").keyup(function(){
    jQuery(".sync-height-modal").val(jQuery(".sync-height input").val());
  });
  jQuery(".sync-width-modal").keyup(function(){
    jQuery(".sync-width input").val(jQuery(".sync-width-modal").val());
  });
  jQuery(".sync-height-modal").keyup(function(){
    jQuery(".sync-height input").val(jQuery(".sync-height-modal").val());
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
      { text: "OK", click: function() { jQuery( this ).dialog( "close" ); } }
    ]
  });

  jQuery( ".modal-opener" ).click(function() {
    jQuery( "#image-upload-modal" ).dialog( "open" );
  });

  // AJAX form submission for upload of image, posts to mtd-upload.php for processing
  jQuery('#form-submit').click(function() {
    jQuery("#image-upload-preview").html('');
    jQuery("#image-upload-preview").html('<img src="' + mtd_site_url + '/wp-content/plugins/mtd-image-editor/images/loading.gif" />');
    jQuery("#image-upload-form").ajaxForm({
                                  target: '#image-upload-preview',
                                  success: function() {
                                    // Update WooCommerce product image
                                    jQuery(".wp-post-image").attr('src', jQuery(".preview").attr('src'));
                                    jQuery(".wp-post-image").attr('id', 'aviary-image');
                                    // Hidden field in the Gravity Form
                                    jQuery("input[value='image_url']").attr('value', jQuery(".preview").attr('src'));
                                    // Show the Aviary edit button and the width / height inputs
                                    jQuery('#aviary-edit-button').show();
                                    jQuery('.image-dimensions-field').show();
                                    // Crop tools for the image
                                    var currentWidth = dialogWidth * .48;
                                    console.log('current width: ' + currentWidth);
                                    if ( jQuery('.sync-height-modal').val() && jQuery('.sync-width-modal').val() ) {
                                      var ratioWidthToHeight = jQuery('.sync-height-modal').val() / jQuery('.sync-width-modal').val();
                                      var currentHeight = currentWidth * ratioWidthToHeight;
                                      console.log('normal if height: ' + currentHeight);
                                    } else {
                                      var currentHeight = 250;
                                    }
                                    jQuery( '#image-upload-preview img' ).cropbox({
                                      width: currentWidth,
                                      height: currentHeight
                                    }).on('cropbox', function(e, data) {
                                      console.log('crop window: ' + data);
                                    });
                                  }
                                }).submit();
  });

});