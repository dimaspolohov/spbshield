<?php
function style_rs_best_sellers_theme() {
    wp_enqueue_style( 'rs-best-sellers', get_stylesheet_directory_uri().'/template-parts/rs-best-sellers/css/rs-best-sellers.css');
}
add_action('wp_enqueue_scripts', 'style_rs_best_sellers_theme');

// Render best sellers block (replaces default)
function storefront_best_selling_products_child( $args ) {

    // Query products by meta
	$args = array(
			'post_type' => 'product', // product post type
			'orderby' => 'date', // sort order
			'meta_query' => array(
				array(
					'key' => '_best_seller',
					'value' => 'yes'
				)
			)						
		);
	$loop = new WP_Query( $args );
	if ($loop->have_posts()) :
	?>
	<section class="rs-17">
		<div class="rs-best-sellers">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<h2 class="text-center section-title" data-nekoanim="fadeInUp" data-nekodelay="100"><span class="section-title--text">самые продаваемые</span></h2>
					</div>
					<div class="col-xs-12 no-padding">
						<div id="product-slider-bs" class="owl-carousel" data-nekoanim="fadeInUp">
						<?php
						while ($loop->have_posts()) : $loop->the_post();
							get_template_part('template-parts/rs-product-card/rs-product-card', null, [
								'modal_target'    => '#best-product-details-modal',
								'lazy_load'       => false,
								'show_sale_class' => false,
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
                <div class="modal fade" id="best-product-details-modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div id="modal-product-best" class="modal-content">


                        </div>
                    </div>
                </div>
            </div>
        </div>
	<?php endif;
}
