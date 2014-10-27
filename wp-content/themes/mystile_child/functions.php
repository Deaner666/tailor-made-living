<?php

	add_action( 'wp_enqueue_scripts', 'p2m_load_dashicons' );
	function p2m_load_dashicons() {
	    wp_enqueue_style( 'dashicons' );
	}

	add_action( 'wp_enqueue_scripts', 'p2m_load_custom_js' );
	function p2m_load_custom_js() {
		wp_register_script( 'custom-js', get_stylesheet_directory_uri() . '/includes/js/custom.js', array( 'jquery', 'wp-color-picker' ) );
     	wp_enqueue_script( 'custom-js' );
     }

	if (  is_product( 'canvas-prints' ) ) {
		add_action( 'wp_enqueue_scripts', 'colour_picker_scripts', 100 );
		function colour_picker_scripts() {
	        wp_enqueue_style( 'wp-color-picker' );
	        wp_enqueue_script(
	            'iris',
	            admin_url( 'js/iris.min.js' ),
	            array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),
	            false,
	            1
	        );
	        wp_enqueue_script(
	            'wp-color-picker',
	            admin_url( 'js/color-picker.min.js' ),
	            array( 'iris' ),
	            false,
	            1
	        );
	        $colorpicker_l10n = array(
	            'clear' => __( 'Clear' ),
	            'defaultString' => __( 'Default' ),
	            'pick' => __( 'Select Color' )
	        );
	        wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n ); 
	    }
	}

	add_shortcode( 'best_selling_products_by_cat', 'best_selling_products_by_cat' );

	/**
	 * List best selling products from a given category slug
	 *
	 * @access public
	 * @param array $atts
	 * @return string
	 */
	function best_selling_products_by_cat( $atts ){
	    global $woocommerce_loop;

	    extract( shortcode_atts( array(
	        'per_page'      => '12',
	        'columns'       => '4',
	        'category'		=> ''
	        ), $atts ) );

	    if ( ! $category ) return;

	    $args = array(
	        'post_type' => 'product',
	        'post_status' => 'publish',
	        'ignore_sticky_posts'   => 1,
	        'posts_per_page' => $per_page,
	        'meta_key' 		 => 'total_sales',
	    	'orderby' 		 => 'meta_value_num',
	        'meta_query' => array(
	            array(
	                'key' => '_visibility',
	                'value' => array( 'catalog', 'visible' ),
	                'compare' => 'IN'
	            )
	        ),
	        'tax_query' 			=> array(
		    	array(
			    	'taxonomy' 		=> 'product_cat',
					'terms' 		=> array( esc_attr($category) ),
					'field' 		=> 'slug',
					'operator' 		=> 'IN'
				)
		    )
	    );

	  	ob_start();

		$products = new WP_Query( $args );

		$woocommerce_loop['columns'] = $columns;

		if ( $products->have_posts() ) : ?>

			<?php woocommerce_product_loop_start(); ?>

				<?php while ( $products->have_posts() ) : $products->the_post(); ?>

					<?php woocommerce_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

		<?php endif;

		wp_reset_postdata();

		return '<div class="woocommerce">' . ob_get_clean() . '</div>';
	}

?>