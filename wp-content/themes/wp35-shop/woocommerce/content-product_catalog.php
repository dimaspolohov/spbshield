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

global $product;
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
?>
<div class="rs-buy-product__slide swiper-slide">
	<div class="product">
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
						<?php
						$attachment_ids = $product->get_gallery_image_ids();
						?>
                        <?php if(isMobile()) {

                            if($attachment_ids) {
                                foreach( $attachment_ids as $attachment_id ) {
                                    $attachment = wp_get_attachment_image_src( $attachment_id, 'img-product__slide' )[0]
                                ?>
                                    <div class="product__slide swiper-slide 1">
                                        <picture>
                                            <source srcset="<?php echo $attachment ?>.webp" type="image/webp">
                                            <img src="<?=$attachment?>" alt="">
                                        </picture>
                                    </div>
                            <?php
                                }
                            }
                        } else {
                                if(!$attachment_ids) $attachment = $image[0]; else $attachment = wp_get_attachment_image_src( $attachment_ids[0], 'img-product__slide' )[0]
                            ?>
                                <div class="product__slide swiper-slide 2">
                                    <picture>
                                        <source srcset="<?php echo $attachment ?>.webp" type="image/webp">
                                        <img src="<?=$attachment?>" alt="">
                                    </picture>
                                </div>
						<?php }
						} ?>
					</div>
                    <div class="rs-product__pagination"></div>
				</div>
			</div>
			<div class="product__description">
				<h5 class="s-regular-title"><?php the_title() ?></h5>
				<div class="product__footer">
					<div class="product__prices">
						<?php 
						// Check if product is only available in stores (not in warehouse)
						if (function_exists('is_product_store_only') && is_product_store_only($product)) {
							echo '<span class="product__price product__out-of-stock" style="color: #c62828; font-weight: 600;">Нет в наличии</span>';
						} else {
						$price = $product->get_price_html();
						//var_dump($product->get_price());
						if(strpos($price,'rs-product__price rs-product__price-old')>-1) {
							$price = str_replace( 'rs-product__price rs-product__price-old', 'product__price product__price-old', $price );
							$price = str_replace( 'rs-product__price rs-product__price-new', 'product__price product__price-new', $price );
						} else {
							$price = str_replace( 'rs-product__price rs-product__price-new', 'product__price product__price-current', $price );
						}
						if((int)$product->get_price()>0) {echo $price;}else{ echo '&nbsp;';};
						}
						?>
					</div>
					<div class="product__labels">
						<?php if($onsale ) :?>
							<div class="product__label product__label-sale"><?=$discount?></div>
						<?php endif; ?>

						<?php  $productCats = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) ); ?>
						<?php if(get_field('_new_product')=='yes' || in_array( 244, $productCats )) :?>
							<div class="product__label product__label-new">Новинка</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</a>
	</div>
</div>