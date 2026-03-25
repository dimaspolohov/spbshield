<?php
/**
 * Shared product card template
 *
 * Used by best-sellers and popular product blocks.
 *
 * @param array $args {
 *     @type string $modal_target    Modal dialog target selector
 *     @type bool   $lazy_load       Whether to use lazy loading for images
 *     @type bool   $show_sale_class Whether to add sale class to price
 * }
 */

if (!defined('ABSPATH')) {
    exit;
}

global $product;
if (!$product) {
    return;
}

$modal_target = $args['modal_target'] ?? '';
$lazy_load = $args['lazy_load'] ?? false;
$show_sale_class = $args['show_sale_class'] ?? false;

$discount = rs_get_product_discount($product);
$onsale = $product->is_on_sale();
$title_products = rs_get_truncated_title();

$post_id = get_the_ID();
$permalink = get_permalink($post_id);
$thumbnail_id = get_post_thumbnail_id($post_id);

$short_description = get_the_excerpt();
$description = $short_description
    ? \SpbShield\Inc\ServiceFunctions::truncate_text(strip_tags(preg_replace('~\[[^\]]+\]~', '', $short_description)), 80)
    : \SpbShield\Inc\ServiceFunctions::truncate_text(strip_tags(preg_replace('~\[[^\]]+\]~', '', $product->get_description())), 80);

$values = explode(",", $product->get_attribute('pa_size'));
$price_class = ($show_sale_class && $product->is_on_sale()) ? 'in_sale' : '';
?>
<div class="product-item">
    <div class="product">
        <div class="product-image">
            <div class="quickview">
                <a data-id="<?php echo esc_attr($post_id); ?>" class="btn btn-xs btn-quickview" href="#" data-path="<?php echo esc_url(get_stylesheet_directory_uri()); ?>" data-target="<?php echo esc_attr($modal_target); ?>" data-toggle="modal">Быстрый просмотр</a>
            </div>
            <a href="<?php echo esc_url($permalink); ?>">
                <?php if (has_post_thumbnail($post_id)) :
                    $img_url = wp_get_attachment_image_url($thumbnail_id, 'shop_catalog');
                    if ($lazy_load) : ?>
                        <img class="b-lazy" data-src="<?php echo esc_url($img_url); ?>" src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/img/img0.png" alt=" " />
                    <?php else : ?>
                        <img src="<?php echo esc_url($img_url); ?>" alt=" " />
                    <?php endif;
                else : ?>
                    <img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" alt="Placeholder" width="250px" height="250px" />
                <?php endif; ?>
            </a>
            <ul class="promotion">
                <?php if (get_field('_new_product') == 'yes') : ?>
                    <li class="new">NEW </li>
                <?php endif; ?>
                <?php if ($onsale) : ?>
                    <li class="discount">SALE <?php echo esc_html($discount); ?></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="description">
            <h3><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title_products); ?></a></h3>
            <p><?php echo esc_html($description); ?></p>
        </div>
        <div class="sub-description"><?php echo esc_html(implode('/', $values)); ?></div>
        <div class="price <?php echo esc_attr($price_class); ?>">
            <?php echo $product->get_price_html(); ?>
        </div>
        <div class="action-control">
            <a href="<?php echo esc_url($permalink); ?>" class="btn btn-color">Подробнее</a>
        </div>
    </div>
</div>
