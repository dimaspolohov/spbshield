<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.1
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;
$product_id = $product->get_id(); ?>
<div class="rs-product__pictures">
	<div class="rs-thumbs__slider">
		<div class="rs-thumbs__swiper">
			<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' ); 
			$index = 0;
			if($image) { $index++; ?>    
				<div class="rs-thumbs__slide-slide" data-href="#rs-product__slide-slide-<?=$index?>">
					<picture>
						<source srcset="<?php  echo $image[0]; ?>.webp" type="image/webp">
						<img src="<?php  echo $image[0]; ?>" alt="">
					</picture>
				</div>
			<? }
			$attachment_ids = $product->get_gallery_image_ids();
			if($attachment_ids) { 
				foreach( $attachment_ids as $attachment_id ) { $index++; ?>
				<div class="rs-thumbs__slide-slide" data-href="#rs-product__slide-slide-<?=$index?>">
					<picture>
						<?php $image_link = !empty(wp_get_attachment_image_src( $attachment_id, 'single-post-thumbnail' )) ? wp_get_attachment_image_src( $attachment_id, 'single-post-thumbnail' )[0] : null;?>
						<source srcset="<?= $image_link; ?>.webp" type="image/webp">
						<img src="<?= $image_link; ?>" alt="">
					</picture>
				</div>
				<? } 
			} ?>
		</div>
	</div>
	<div class="rs-product__slider" data-gallery>
		<div class="rs-product__swiper">
			<?php 
			$index = 0;
			$image = wp_get_attachment_image_url( get_post_thumbnail_id( $product_id ), '1536x1536' );
			if($image) { $index++; ?>
				<div class="rs-product__slide-slide" id="rs-product__slide-slide-<?=$index?>" data-gallery-item data-src="<?=$image?>">
					<picture>
						<source srcset="<?=$image?>.webp" type="image/webp">
						<img src="<?=$image?>" alt="">
					</picture>
				</div>
			<? }
			$attachment_ids = $product->get_gallery_image_ids();
			if($attachment_ids) { 
				foreach( $attachment_ids as $attachment_id ) { $index++; ?>
				<div class="rs-product__slide-slide" id="rs-product__slide-slide-<?=$index?>" data-gallery-item data-src="<? echo $image_link = wp_get_attachment_image_url( $attachment_id, '1536x1536' ); ?>">
					<picture>
						<source srcset="<? echo $image_link = wp_get_attachment_image_url( $attachment_id, '1536x1536' ); ?>.webp" type="image/webp">
						<img src="<?=$image_link?>" alt="">
					</picture>
				</div>
				<? } 
			} ?>
		</div>
		<div class="rs-product__pagination-pagination"></div>
	</div>
</div>