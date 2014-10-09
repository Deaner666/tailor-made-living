<?php
// File Security Check
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page!' );
}
?>
<?php
/**
 * Page Template
 *
 * This template is the default page template. It is used to display content when someone is viewing a
 * singular view of a page ('page' post_type) unless another page template overrules this one.
 * @link http://codex.wordpress.org/Pages
 *
 * @package WooFramework
 * @subpackage Template
 */
	get_header();
	global $woo_options;
?>
       
    <div id="content" class="page col-full">

        <?php woo_main_before(); ?>
    	
		<section id="main" class="col-left">

            <?php
                if ( is_front_page() ) { ?>

                <h1 id="home-page-header">Custom printed products for the home</h1>

                <article class="full-width-feature dark" id="picture-perfect">
                    <img src="wp-content/themes/mystile_child/images/features/picture-perfect.jpg" alt="" class="pull-right" />
                    <div class="blurb">
                        <header>
                            <h1>Picture Perfect</h1>
                            <h2>Beautifully printed custom products from your own photos, tailor-made for you.</h2>
                        </header>
                        <p>
                            Our top-of-the-line printers produce awesome print on the finest, carefully selected materials.
                        </p>
                    </div>
                </article>

                <article class="full-width-feature light" id="easy-peasy-editing">
                    <img src="wp-content/themes/mystile_child/images/features/easy-peasy-editing.jpg" alt="" />
                    <div class="pull-right blurb">
                        <header>
                            <h1>Easy Peasy Editing</h1>
                            <h2>Incredible built-in image editor to make your photos look their best.</h2>
                        </header>
                        <p>
                            Like Photoshop, but 1,000 times easier to use. Think Instagram filters are cool? You haven’t seen anything yet&hellip;
                        </p>
                    </div>
                </article>

                <article class="full-width-feature dark" id="closing-statement">
                    <div id="make-a-statement">
                        <img src="wp-content/themes/mystile_child/images/features/products-icons.png" alt="" />
                        <div class="blurb">
                            <header>
                                <h1>Make a statement</h1>
                                <h2>Blow the minds of friends, family and visitors.</h2>
                            </header>
                            <p>
                                Create a feature wallpaper, giant mounted print, personalised poster or custom printed canvas.
                            </p>
                        </div>
                    </div> <!-- /#make-a-statement -->

                    <div id="tailor-made-emphasis">
                        <img src="wp-content/themes/mystile_child/images/features/tape-measure-icon.png" alt="" />
                        <div class="blurb">
                            <header>
                                <h1>Tailor Made</h1>
                                <h2>Made-to-measure. Easy to install.</h2>
                            </header>
                            <p>
                                Every item tailor-made to your specification. Simple to follow installation instructions provided. Anyone can do it.
                            </p>
                        </div>
                    </div> <!-- /#tailor-made-emphasis -->
                </article>

                <h2 id="page-bottom-header">Tailor make me a &hellip;</h2>

                <div id="home-page-buttons">
                    <ul>
                        <li><a href="http://www.tailormade-living.co.uk/product/custom-printed-wallpaper/">Custom Wallpaper</a></li>
                        <li><a href="http://www.tailormade-living.co.uk/product/custom-mounted-prints/">Mounted Print</a></li>
                        <li><a href="http://www.tailormade-living.co.uk/product/personalised-posters/">Personalised Poster</a></li>
                        <li><a href="http://www.tailormade-living.co.uk/product/custom-printed-canvases/">Printed Canvas</a></li>
                    </ul>
                </div>

                <section id="home-page-description-text">
                    <h1>Tailor Made Living</h1>
                    <p>Make a statement with a tailor-made custom printed product for your home. Upload your own image and make it awesome with our easy-to-use photo editor. We turn it into a made-to-measure and easy to install wallpaper, mounted print, personalised poster or printed canvas.</p>
                    <p><strong>Easy peasy from mouse to house.</strong></p>
                </section>

            <?php }

            	if ( have_posts() ) { $count = 0;
            		while ( have_posts() ) { the_post(); $count++;
            ?>                                                           
            <article <?php post_class(); ?>>
				
				<?php
                    if (!is_front_page()) { ?>
                        <header>
        			    	<h1><?php the_title(); ?></h1>
        				</header>
                    <?php }
                ?>

                <section class="entry">
                	<?php the_content(); ?>

					<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</div>' ) ); ?>
               	</section><!-- /.entry -->
                
            </article><!-- /.post -->
            
            <?php
            	// Determine wether or not to display comments here, based on "Theme Options".
            	if ( isset( $woo_options['woo_comments'] ) && in_array( $woo_options['woo_comments'], array( 'page', 'both' ) ) ) {
            		comments_template();
            	}

				} // End WHILE Loop
			} else {
		?>
			<article <?php post_class(); ?>>
            	<p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            </article><!-- /.post -->
        <?php } // End IF Statement ?>  
        
		</section><!-- /#main -->
		
		<?php woo_main_after(); ?>

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>