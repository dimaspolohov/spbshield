<?php
function style_rs_popular_theme() {
    wp_enqueue_style( 'rs-popular', get_stylesheet_directory_uri().'/template-parts/rs-popular/css/rs-popular.css');
    wp_enqueue_style( 'rs-product-view', get_stylesheet_directory_uri().'/woocommerce/css/rs-product-view.css');
}
add_action('wp_enqueue_scripts', 'style_rs_popular_theme');

// Render popular products block (replaces default)
function storefront_popular_products_child( $args ) {

    // Query products by meta
	$args = array(
			'post_type' => 'product', // product post type
			'orderby' => 'date', // sort order
			'meta_query' => array(
				array(
					'key' => '_popular_product',
					'value' => 'yes'
				)
			)						
		);
	 
	$loop = new WP_Query( $args );

	if ($loop->have_posts()) :
	?>
	<section class="rs-17">
		<div class="rs-popular">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<h2 class="text-center section-title" data-nekoanim="fadeInUp" data-nekodelay="100"><span class="section-title--text">популярные</span></h2>
					</div>

					<div class="col-xs-12 no-padding">
						<div id="product-slider-popular" class="owl-carousel" data-nekoanim="fadeInUp">
						
						<?php
						while ($loop->have_posts()) : $loop->the_post();
							get_template_part('template-parts/rs-product-card/rs-product-card', null, [
								'modal_target'    => '#popular-product-details-modal',
								'lazy_load'       => true,
								'show_sale_class' => true,
							]);
						endwhile;
						?>
						<!-- Reset query data -->
						<?php wp_reset_postdata(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>

	</section>
        <div class="rs-17">
            <div class="rs-product-view">
                <div class="modal fade" id="popular-product-details-modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div id="modal-product" class="modal-content">


                        </div>
                    </div>
                </div>
            </div>
        </div>
	<?php endif;
}
