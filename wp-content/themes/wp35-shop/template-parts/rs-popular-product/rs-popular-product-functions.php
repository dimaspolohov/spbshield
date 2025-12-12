<?php
function storefront_rs_popular_product() {
	?>
	<!-- rs-popular-product -->
	<?php

    global $wpdb;
    $current_time=current_time('mysql', "1");
    $date = date('Y-m-d',time());
    $d = strtotime("-7 day");
    $day = date("Y-m-d", $d);
    //var_dump(date("Y-m-d", $d));

  // var_dump(date("Y-m-d", strtotime('monday this week', strtotime($date))));//понедельник
    $table = $wpdb->prefix . "wc_order_product_lookup";
    $strSQL ="SELECT product_id, SUM(product_qty) FROM $table   WHERE date_created >='" .$day. "' GROUP BY product_id ORDER BY SUM(product_qty) DESC LIMIT 50";

    $result = $wpdb->get_results($strSQL);

    $products =[];
    if($result){
        foreach ($result as $item) {
            $products[]=(int)$item->product_id;
            echo '<pre style="display:none;">';
            var_dump((int)$item->product_id);
            echo '</pre>';
        }
    }


    $args =array(
		'post_type' => 'product',
		'post_status' => 'publish',
        'posts_per_page' => 10,
        'meta_query'    => [
            'relation' => 'AND',
            [
                'key'       => '_popular_product',
                'value'     => 'yes',
                'compare'   => '=',
            ],
        ],
    );

    if(!empty($products)){
        $args['post__in'] = $products;
        $args['orderby'] = 'post__in';
        if(count($products)<10) $args['posts_per_page'] = -1;
    } else {
        $args['meta_key'] = 'total_sales';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
    }

    global $post;

    $popular =new WP_Query($args);
	if( $popular->have_posts() ): ?>
	<section class="rs-popular-product ">
		<div class="rs-popular-product__container">
			<h2 class="section-title"><?_e('Popular Products','storefront')?></h2>
			<div class="rs-popular-product__wrapper">
				<div class="rs-popular-product__slider swiper">
					<div class="rs-popular-product__swiper swiper-wrapper">
					<?php
					while ( $popular->have_posts() ) {
                        $popular->the_post();
						wc_get_template_part( 'content', 'product-popular' );
					} ?>
					</div>
				</div>
				<div class="rs-popular-product__button-prev swiper-button-prev icon-slider-arrow_left"></div>
				<div class="rs-popular-product__button-next swiper-button-next icon-slider-arrow_right"></div>
			</div>
		</div>
	</section>
	<?php wp_reset_postdata();
	endif; ?>
	<!-- /rs-popular-product -->
	<?php
}