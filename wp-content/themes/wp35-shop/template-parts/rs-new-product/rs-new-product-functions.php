<?php
function storefront_rs_new_product() {?>
	<!-- rs-new-product -->
	<?php
	$frontpage_id = get_option('page_on_front');
	$term_id = get_field('novye_postupleniya',$frontpage_id);	
	/* $args = array(
		'posts_per_page' => -1,
		'post_type' => 'product',
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'term_id',
				'terms' => $term_id,
			),
	   ),
	); */
	$args = array(
		// 'posts_per_page' => 8,
		'posts_per_page' => -1,
		'post_type' => 'product', // тип товара
        'orderby' => 'menu_order',// сортировка
        'order' => 'ASC',
		'meta_query' => array(
			array(
				'key' => '_new_product',
				'value' => 'yes'
			)
		)						
	);
	$query = new WP_Query( $args );
	if( $query->have_posts() ){
	?>
	<section class="rs-new-product">
		<div class="rs-new-product__container">
			<h2 class="section-title"><?_e('New Arrivals','storefront')?></h2>
			<div class="rs-new-product__wrapper">
				<div class="rs-new-product__slider swiper">
					<div class="rs-new-product__swiper swiper-wrapper">
						<?
						/* $i = 0; */
						while( $query->have_posts() ){
							$query->the_post();
							/* ob_start(); */
							wc_get_template_part( 'content', 'product-new' );
							/* $output = ob_get_contents();
							ob_end_clean();
							if($output!=''&&$i<8) {
								$i++;
								echo $output;
							} */
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
	<? }?>
	<!-- /rs-new-product -->
	<?php
}