<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;
global $post,$product;
$product_id = $product->get_id();

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
if($product->is_type( 'variable' )){
	$regular_price = $product->get_variation_regular_price( 'min' );
	$sale_price = $product->get_variation_sale_price( 'min' );
} else {
	$regular_price = $product->get_regular_price();
	$sale_price = $product->get_sale_price();
}
$discount = ($regular_price && $sale_price) ? ceil((($regular_price - $sale_price) * 100) / $regular_price) : '';
$onsale = $product->is_on_sale();
$discount = $discount == 100 ? '' : $discount;
$discount = $discount ? '-' . $discount . '%' : '';

if( !$regular_price ) return;
?>
<div class="rs-popular-product__slide swiper-slide">
	<div class="product  product-<?=$product_id?>" >
		<a href="<?php echo get_permalink() ?>">
			<div class="product__picture">
				<div class="product__slider swiper">
					<div class="product__swiper swiper-wrapper">
						<?
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'img-product__slide' ); 
						if($image) { ?>    
						<div class="product__slide swiper-slide">
							<picture>
								<source srcset="<?php  echo $image[0]; ?>.webp" type="image/webp">
								<img src="<?php echo $image[0]; ?>" alt="">
							</picture>
						</div>
						<? 
						$attachment_ids = $product->get_gallery_image_ids();
						if(!$attachment_ids) $attachment = $image[0]; else $attachment = wp_get_attachment_image_src( $attachment_ids[0], 'img-product__slide' )[0]?>
						<div class="product__slide swiper-slide">
							<picture>
								<source srcset="<? echo $attachment ?>.webp" type="image/webp">
								<img src="<?=$attachment?>" alt="">
							</picture>
						</div>
						<? } ?>
					</div>
				</div>
			</div>
			<div class="product__description">
				<h5 class="s-regular-title"><? the_title() ?></h5>
				<div class="product__footer">
					<div class="product__prices">
						<?php 
						$price = $product->get_price_html();
						if(strpos($price,'rs-product__price rs-product__price-old')>-1) {
							$price = str_replace( 'rs-product__price rs-product__price-old', 'product__price product__price-old', $price );
							$price = str_replace( 'rs-product__price rs-product__price-new', 'product__price product__price-new', $price );
						} else {
							$price = str_replace( 'rs-product__price rs-product__price-new', 'product__price product__price-current', $price );
						}
						echo $price;
						?>
					</div>
					<div class="product__labels">
						<?php if($onsale ) :?>
						<div class="product__label product__label-sale"><?=$discount?></div>
						<?php endif;
						if(get_field('_new_product')=='yes') :?>
						<div class="product__label product__label-new"><?_e('New','storefront')?></div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</a>
	</div>
</div>