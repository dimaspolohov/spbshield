<?php

// Render new product slider section
function storefront_rs_new_product() {
	$args = array(
		'posts_per_page' => -1,
		'post_type'      => 'product',
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'meta_query'     => array(
			array(
				'key'   => '_new_product',
				'value' => 'yes',
			),
		),
	);

	$query = new WP_Query( $args );
	if ( $query->have_posts() ) :
		?>
		<section class="rs-new-product">
			<div class="rs-new-product__container">
				<h2 class="section-title"><?php _e( 'New Arrivals', 'storefront' ); ?></h2>
				<div class="rs-new-product__wrapper">
					<div class="rs-new-product__slider swiper">
						<div class="rs-new-product__swiper swiper-wrapper">
							<?php
							while ( $query->have_posts() ) {
								$query->the_post();
								wc_get_template_part( 'content', 'product-new' );
							}
							wp_reset_postdata();
							?>
						</div>
					</div>
					<div class="rs-new-product__button-prev swiper-button-prev icon-slider-arrow_left"></div>
					<div class="rs-new-product__button-next swiper-button-next icon-slider-arrow_right"></div>
				</div>
			</div>
		</section>
	<?php endif;
}
