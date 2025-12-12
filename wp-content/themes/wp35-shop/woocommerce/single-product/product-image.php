<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.0.0
 */
defined('ABSPATH') || exit;

global $product;
$product_id = $product->get_id();
?>

<div class="rs-product__pictures">
    <!-- Thumbnails Slider -->
    <div class="rs-thumbs__slider swiper">
        <div class="rs-thumbs__swiper swiper-wrapper">
            <?php
            // Главное изображение для thumbnails
            $main_image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'single-post-thumbnail');
            if ($main_image && !empty($main_image[0])) {
                $main_image_url = $main_image[0];
                $has_webp = has_webp_version($main_image_url);
            ?>
                <div class="rs-thumbs__slide swiper-slide">
                    <picture>
                        <?php if ($has_webp) { ?>
                            <source srcset="<?php echo esc_url($main_image_url); ?>.webp" type="image/webp">
                        <?php } ?>
                        <img src="<?php echo esc_url($main_image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" loading="lazy">
                    </picture>
                </div>
            <?php
            }

            // Галерея для thumbnails
            $attachment_ids = $product->get_gallery_image_ids();
            if ($attachment_ids) {
                foreach ($attachment_ids as $attachment_id) {
                    $gallery_image = wp_get_attachment_image_src($attachment_id, 'single-post-thumbnail');
                    if ($gallery_image && !empty($gallery_image[0])) {
                        $gallery_image_url = $gallery_image[0];
                        $has_webp = has_webp_version($gallery_image_url);
                        $alt_text = get_post_meta($attachment_id, '_wp_attachment_image_alt', true) ?: $product->get_name();
                    ?>
                        <div class="rs-thumbs__slide swiper-slide">
                            <picture>
                                <?php if ($has_webp) { ?>
                                    <source srcset="<?php echo esc_url($gallery_image_url); ?>.webp" type="image/webp">
                                <?php } ?>
                                <img src="<?php echo esc_url($gallery_image_url); ?>" alt="<?php echo esc_attr($alt_text); ?>" loading="lazy">
                            </picture>
                        </div>
                    <?php
                    }
                }
            }
            ?>
        </div>
    </div>

    <!-- Main Product Slider -->
    <div class="rs-product__slider swiper" data-gallery>
        <div class="rs-product__swiper swiper-wrapper">
            <?php
            // Главное изображение для основного слайдера
            $main_image_large = wp_get_attachment_image_url(get_post_thumbnail_id($product_id), '1536x1536');
            if ($main_image_large) {
                $has_webp = has_webp_version($main_image_large);
            ?>
                <div class="rs-product__slide swiper-slide" data-gallery-item data-src="<?php echo esc_url($main_image_large); ?>">
                    <picture>
                        <?php if ($has_webp) { ?>
                            <source srcset="<?php echo esc_url($main_image_large); ?>.webp" type="image/webp">
                        <?php } ?>
                        <img src="<?php echo esc_url($main_image_large); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" loading="lazy">
                    </picture>
                </div>
            <?php
            }

            // Галерея для основного слайдера
            if ($attachment_ids) {
                foreach ($attachment_ids as $attachment_id) {
                    $gallery_image_large = wp_get_attachment_image_url($attachment_id, '1536x1536');
                    if ($gallery_image_large) {
                        $has_webp = has_webp_version($gallery_image_large);
                        $alt_text = get_post_meta($attachment_id, '_wp_attachment_image_alt', true) ?: $product->get_name();
                    ?>
                        <div class="rs-product__slide swiper-slide" data-gallery-item data-src="<?php echo esc_url($gallery_image_large); ?>">
                            <picture>
                                <?php if ($has_webp) { ?>
                                    <source srcset="<?php echo esc_url($gallery_image_large); ?>.webp" type="image/webp">
                                <?php } ?>
                                <img src="<?php echo esc_url($gallery_image_large); ?>" alt="<?php echo esc_attr($alt_text); ?>" loading="lazy">
                            </picture>
                        </div>
                    <?php
                    }
                }
            }
            ?>
        </div>
        <div class="rs-product__pagination swiper-pagination"></div>
    </div>
</div>