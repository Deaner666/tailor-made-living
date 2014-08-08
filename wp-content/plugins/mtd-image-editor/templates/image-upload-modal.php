<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $post, $woocommerce, $product;

	// if ( has_term('framed-map-prints', 'product_cat') ) {
?>

<section id="picture-frame-selector">
	
	<div id="image-upload-lead-in">
		<a href="javascript:;" class="modal-opener">Upload your image here</a>
	</div>

	<article id="image-upload-modal">
		<header>
			<h1>Upload your image</h1>
		</header>

		<form id="image-upload-form" method="post" enctype="multipart/form-data">
			<ul class="form-fields">
				<li>
					<div class="form-label"><label for="form-image">Choose an image to upload</label></div>
					<div class="form-input"><input id="form-image" name="form-image" type="file"/></div>
				</li>
				<li><input id="form-submit" name="form-submit" type="submit" value="Upload"/></li>
			</ul>
		</form>
	</article>

</section>

<?php
// }
?>