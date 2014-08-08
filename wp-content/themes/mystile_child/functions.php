<?php

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