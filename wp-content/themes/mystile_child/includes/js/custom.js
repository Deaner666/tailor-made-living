jQuery(document).ready(function() {
	var options = {
	  hide: true,
	  palettes: false//,
	  // change: function(event, ui) {
	  //   jQuery(".quote-text-preview textarea").css( 'background', ui.color.toString());
	  // }
	}
	if ( jQuery('.my-color-field input').length > 0 ) {
		jQuery('.my-color-field input').wpColorPicker(options);
	}
});