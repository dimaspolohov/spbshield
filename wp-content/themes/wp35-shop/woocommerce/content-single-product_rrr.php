<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
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

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>

<!-- rs-product -->
<section class="rs-product">
	<div class="rs-product__container">
		<? woocommerce_show_product_images(); ?>
		<div class="rs-product__description">
			<?php do_action( 'woocommerce_single_product_summary' ); ?>
			<div data-spollers data-one-spoller class="rs-product__spollers spollers">
				<div class="spollers__item">
					<button type="button" data-spoller class="spollers__title _spoller-active">
						<?_e('Description','storefront')?>
						<i class="spollers__icon"></i>
					</button>
					<div class="spollers__body">
						<div class="spollers__wrapper">
							<div class="spollers__part">
								<div class="section-text">
									<? the_content()?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="spollers__item">
					<button type="button" data-spoller class="spollers__title">
						<?_e('Composition','storefront')?>
						<i class="spollers__icon"></i>
					</button>
					<div class="spollers__body">
						<div class="spollers__wrapper">
							<div class="spollers__part">
								<div class="section-text">
									<? the_field('composition')?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="spollers__item">
					<button type="button" data-spoller class="spollers__title">
						<?_e('Delivery and returns','storefront')?>
						<i class="spollers__icon"></i>
					</button>
					<div class="spollers__body">
						<div class="spollers__wrapper">
							<div class="section-text">
								<? the_field('delevery')?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- /rs-product -->


<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
?>
<?
$recently_viewed = isset($_COOKIE['woocommerce_recently_viewed'])?$_COOKIE['woocommerce_recently_viewed']:'';
if(isset($recently_viewed) && $recently_viewed!=''):
    $recently_viewed = str_replace( get_the_ID(), '', $recently_viewed );
    $recently_viewed = str_replace( ',,', ',', $recently_viewed );
    if(isset($recently_viewed) && $recently_viewed!='' && $recently_viewed!=','):?>
        <!-- rs-recent-product -->
        <section class="rs-recent-product">
            <div class="rs-recent-product__container">
                <h2 class="section-title"><?_e('Recently viewed','storefront')?></h2>
                <div class="rs-recent-product__wrapper">
                    <div class="rs-recent-product__slider swiper">
                        <div class="rs-recent-product__swiper swiper-wrapper">
                            <?
                            $products = explode(',',$recently_viewed);
                            shuffle($products);
                            global $post;
                            foreach($products as $product_id) {
                                if($product_id && $product_id!='') {
                                    $product = wc_get_product( $product_id );
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
                                    <div class="rs-recent-product__slide swiper-slide">
                                        <div class="product">
                                            <a href="<?php echo get_permalink($product_id) ?>">
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
                                                    <?
                                                    $attributes = $product->get_attributes();
                                                    $pa_color = '';
                                                    if(isset($attributes["pa_color"])) $pa_color = $attributes["pa_color"];
                                                    if(isset($pa_color)) if($pa_color && isset($pa_color['options']) && count($pa_color['options'])>0) {?>
                                                        <div class="product__color">
                                                            <ul>
                                                                <? foreach($pa_color['options'] as $color) {
                                                                    ?><li style="background-color:#<?=get_term($color)->slug?>"></li>
                                                                    <?
                                                                }?>
                                                            </ul>
                                                        </div>
                                                    <? } ?>
                                                    <h5 class="s-regular-title"><?=get_the_title($product_id) ?></h5>
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
                                                            if(get_field('_new_product',$product_id)=='yes') :?>
                                                                <div class="product__label product__label-new"><?_e('New','storefront')?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <?
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="rs-recent-product__button-prev swiper-button-prev icon-slider-arrow_left"></div>
                    <div class="rs-recent-product__button-next swiper-button-next icon-slider-arrow_right"></div>
                </div>
            </div>
        </section>
        <!-- /rs-recent-product -->
    <? endif;?>
<? endif;?>


<?php do_action( 'woocommerce_after_single_product' ); ?>