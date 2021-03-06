<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $post, $woocommerce, $product;
	$form_action = plugins_url('/mtd-upload.php', dirname(__FILE__));

	// if ( has_term('framed-map-prints', 'product_cat') ) {
?>

<section id="mtd-image-uploader">
	
	<div id="image-upload-lead-in">
		<a href="javascript:;" class="modal-opener fancy-button upload-icon">
			Upload Your Image
		</a>
	</div>

	<div id="image-upload-done" class="woocommerce-message">
		<p>Cool. You uploaded your image and you're ready to order! </p>
	</div>

	<article id="image-upload-modal">
		<header>
			<h1>Upload your image</h1>
		</header>

		<div id="image-edit-forms">

			<form id="image-upload-form" name="image-upload-form" method="post" action="<?php echo $form_action ?>" enctype="multipart/form-data">
				<?php wp_nonce_field('upload-image', 'upload-form-nonce'); ?>
				<ul class="form-fields">
					<li>
						<div class="form-label"><label for="form-image">Choose an image to upload</label></div>
						<div class="form-input"><input id="form-image" name="form-image" type="file"/></div>
					</li>
					<li><input id="form-submit" name="form-submit" type="submit" value="Upload"/></li>
				</ul>
			</form>

			<div id="image-uploaded">
				<div class="woocommerce-message">Image uploaded. You're doing great!</div>
			</div>

			<div id="aviary-edit-button">
				<h3>Make Your Image Awesome</h3>
				<p>Apply special effects to your image, add text and more.</p>
				<a class="fancy-button edit-image-icon" href="javascript:;" onclick="return launchEditor( 'aviary-image', jQuery('#image-upload-preview img').attr('src') );">
					Edit Image
				</a>
			</div>

			<?php
			// Determine which product we're on and which width and height fields we need to show
			switch ( $post->post_name ) {
				case 'custom-wallpaper':
			?>
					<div class="image-dimensions-field">
						<h3>Your Wall Dimensions</h3>
						<div class="wall-width">
							<label for="modal-width">Width (cm)</label>
							<input id="modal-width" class="sync-width-modal" type="number" step="any" />
						</div>
						<div class="wall-height">
							<label for="modal-height">Height (cm)</label>
							<input id="modal-height" class="sync-height-modal" type="number" step="any" />
						</div>
					</div>
			<?php 
					break;
				case 'canvas-prints':
			?>
					<div class="image-dimensions-field">
						<h3>Your Canvas Dimensions</h3>
						<div class="wall-width">
							<label for="modal-width">Width (in)</label>
							<select id="modal-width" class="sync-width-modal">
								<option value='8' >8</option>
								<option value='12' >12</option>
								<option value='16' >16</option>
								<option value='18' >18</option>
								<option value='24' >24</option>
								<option value='30' >30</option>
								<option value='32' >32</option>
								<option value='36' >36</option>
							</select>
						</div>
						<div class="wall-height">
							<label for="modal-height">Height (in)</label>
							<select id="modal-height" class="sync-height-modal">
								<option value='8' >8</option>
								<option value='12' >12</option>
								<option value='16' >16</option>
								<option value='18' >18</option>
								<option value='24' >24</option>
								<option value='30' >30</option>
								<option value='32' >32</option>
								<option value='36' >36</option>
							</select>
						</div>
					</div>
			<?php
					break;
				case 'foamex-prints' || 'dibond-prints' || 'personalised-posters':
			?>
					<div class="image-dimensions-field">
						<h3>Your Print Dimensions</h3>
						<div class="wall-width">
							<label for="modal-width">Width (cm)</label>
							<input id="modal-width" class="sync-width-modal" type="number" step="any" />
						</div>
						<div class="wall-height">
							<label for="modal-height">Height (cm)</label>
							<input id="modal-height" class="sync-height-modal" type="number" step="any" />
						</div>
					</div>
			<?php 
					break;
			}
			?>

		</div>

		<div id="image-upload-preview"></div>
	</article>

	<!-- Instantiate Feather -->
	<script type='text/javascript'>
		var toolsToUse = ['redeye', 'blemish', 'effects', 'enhance', 'warmth', 'brightness', 'contrast', 'saturation', 'whiten', 'sharpness', 'focus', 'colorsplash', 'draw', 'stickers', 'orientation'];
		var featherEditor = new Aviary.Feather({
	    	apiKey: 'aeae8484a8800725',
	    	apiVersion: 3,
	    	theme: 'light', // Check out our new 'light' and 'dark' themes!
	    	tools: toolsToUse,
	    	appendTo: '',
	    	enableCORS: true,
	    	onSaveButtonClicked: function() {
	    		// disable the jQuery dialog save button whilst we wait for the crop image to update
	    		jQuery('button.ui-button').prop('disabled', true);
	    		jQuery('button.ui-button span').html('Please wait, image updating...');
	    		featherEditor.saveHiRes();
	    	},
	    	onSaveHiRes: function(imageID, newURL) {
	    		var img = jQuery('.preview');
	        	jQuery.ajax({
	        		type: 'POST',
	        		url: mtd_site_url+'/wp-content/plugins/mtd-image-editor/mtd-hires-image-save.php',
	        		data: 'url='+newURL,
	        		success: function(response) {
	        			img.attr('src', response);
	        			img.on('load', function() {
	        				jQuery('button.ui-button').prop('disabled', false);
		        			jQuery('button.ui-button span').html('Save');
	        			});
	        		}
	        	});
	        	featherEditor.close();
	    	},
	    	onError: function(errorObj) {
	        	alert(errorObj.message);
	    	}
		});
		function launchEditor(id, src) {
			var auth = getAuth('aeae8484a8800725', '300a474918996da1', 'MD5');
	    	console.log(jQuery(".preview").attr('src'));
	    	featherEditor.launch({
	    		image: id,
	        	url: src,
	        	timestamp: auth.timestamp,
		    	salt: auth.salt,
			    encryptionMethod: auth.encryptionMethod,
			    signature: auth.signature,
			    hiresUrl: jQuery(".preview").attr('src')
	    	});
	    	return false;
		}
	</script>

	<div id='injection_site'></div>

</section>

<?php
// }
?>