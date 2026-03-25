<?php

// Render popular products slider section
function storefront_rs_popular_product() {
	global $wpdb;

	$d   = strtotime( '-7 day' );
	$day = date( 'Y-m-d', $d );

	$table  = $wpdb->prefix . 'wc_order_product_lookup';
	$result = $wpdb->get_results( $wpdb->prepare(
		"SELECT product_id, SUM(product_qty) FROM {$table} WHERE date_created >= %s GROUP BY product_id ORDER BY SUM(product_qty) DESC LIMIT 50",
		$day
	) );

	$products = array();
	if ( $result ) {
		foreach ( $result as $item ) {
			$products[] = (int) $item->product_id;
		}
	}

	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 10,
		'meta_query'     => array(
			'relation' => 'AND',
			array(
				'key'     => '_popular_product',
				'value'   => 'yes',
				'compare' => '=',
			),
		),
	);

	if ( ! empty( $products ) ) {
		$args['post__in'] = $products;
		$args['orderby']  = 'post__in';
		if ( count( $products ) < 10 ) {
			$args['posts_per_page'] = -1;
		}
	} else {
		$args['meta_key'] = 'total_sales';
		$args['orderby']  = 'meta_value_num';
		$args['order']    = 'DESC';
	}

	$popular = new WP_Query( $args );
	if ( $popular->have_posts() ) :
		?>
		<section class="rs-popular-product">
			<div class="rs-popular-product__container">
				<h2 class="section-title"><?php _e( 'Popular Products', 'storefront' ); ?></h2>
				<div class="rs-popular-product__wrapper">
					<div class="rs-popular-product__slider swiper">
						<div class="rs-popular-product__swiper swiper-wrapper">
							<?php
							while ( $popular->have_posts() ) {
								$popular->the_post();
								wc_get_template_part( 'content', 'product-popular' );
							}
							?>
						</div>
					</div>
					<div class="rs-popular-product__button-prev swiper-button-prev icon-slider-arrow_left"></div>
					<div class="rs-popular-product__button-next swiper-button-next icon-slider-arrow_right"></div>
				</div>
			</div>
		</section>
	<?php
		wp_reset_postdata();
	endif;
}
