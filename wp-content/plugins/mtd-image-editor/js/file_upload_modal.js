jQuery(document).ready( function() {

  var dialogWidth = jQuery( window ).width() * 0.8;
  var dialogHeight = jQuery( window ).height() * 0.85;

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

  jQuery('#form-submit').click(function() {
    jQuery("#image-upload-preview").html('');
    jQuery("#image-upload-preview").html('<p>Loading...</p><img src="' + mtd_site_url + '/wp-content/plugins/mtd-image-editor/images/loading.gif" />');
    jQuery("#image-upload-form").ajaxForm({ target: '#image-upload-preview' }).submit();
  });

});