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

	<article id="image-upload-modal">
		<header>
			<h1>Upload your image</h1>
		</header>

		<div id="image-edit-forms">

			<form id="image-upload-form" name="image-upload-form" method="post" action="<?php echo $form_action ?>" enctype="multipart/form-data">
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

		</div>

		<div id="image-upload-preview"></div>
	</article>

	<!-- Instantiate Feather -->
	<script type='text/javascript'>
		var featherEditor = new Aviary.Feather({
	    	apiKey: 'aeae8484a8800725',
	    	apiVersion: 3,
	    	theme: 'light', // Check out our new 'light' and 'dark' themes!
	    	tools: 'all',
	    	appendTo: '',
	    	onSaveButtonClicked: function() {
	    		featherEditor.saveHiRes();
	    	},
	    	// onSave: function(imageID, newURL) {
	    	// 	var img = document.getElementById(imageID);
	     //    	img.src = newURL;
	    	// },
	    	postUrl: mtd_site_url+'/wp-content/plugins/mtd-image-editor/mtd-hires-image-save.php',
	    	onSaveHiRes: function(imageID, newURL) {
	    		var img = document.getElementById(imageID);
	        	img.src = newURL;
	        	// featherEditor.close();
	    	},
	    	onError: function(errorObj) {
	        	alert(errorObj.message);
	    	}
		});
		function launchEditor(id, src) {
			var auth = getAuth('aeae8484a8800725', '300a474918996da1', 'MD5');
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