<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     9.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $upsells ) : ?>

<!-- rs-buy-product -->
<section class="rs-buy-product">
	<div class="rs-buy-product__container">
		<h2 class="section-title">
			<?_e('Смотри ещё')?>
			<?//_e('Buy with this product','storefront')?>
		</h2>
		<div class="rs-buy-product__wrapper">
			<div class="rs-buy-product__slider swiper">
				<div class="rs-buy-product__swiper swiper-wrapper">
				<?php //woocommerce_product_loop_start(); ?>

					<?php foreach ( $upsells as $upsell ) : ?>

						<?php
							$post_object = get_post( $upsell->get_id() );

							setup_postdata( $GLOBALS['post'] =& $post_object );

							wc_get_template_part( 'content', 'related-product' ); ?>

					<?php endforeach; ?>

				<?php //woocommerce_product_loop_end(); ?>
				</div>
			</div>
			<div class="rs-buy-product__button-prev swiper-button-prev icon-slider-arrow_left"></div>
			<div class="rs-buy-product__button-next swiper-button-next icon-slider-arrow_right"></div>
		</div>
	</div>
</section>
<!-- /rs-buy-product -->

<?php endif;

wp_reset_postdata();
