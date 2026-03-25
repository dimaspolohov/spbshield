<?php
/**
 * WooCommerce template helper functions
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Calculate product discount percentage
 *
 * @param WC_Product $product
 * @return string Formatted discount string (e.g. "25 %") or empty string
 */
function rs_get_product_discount($product) {
    if ($product->is_type('variable')) {
        $regular_price = $product->get_variation_regular_price('min');
        $sale_price = $product->get_variation_sale_price('min');
    } else {
        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
    }

    $discount = ($regular_price && $sale_price) ? ceil((($regular_price - $sale_price) * 100) / $regular_price) : '';
    $discount = $discount == 100 ? '' : $discount;

    return $discount ? $discount . ' %' : '';
}

/**
 * Get truncated product title
 *
 * @param int $max_length Maximum title length
 * @return string
 */
function rs_get_truncated_title($max_length = 45) {
    $title = get_the_title();
    return (mb_strlen($title) > $max_length) ? mb_substr($title, 0, $max_length) . '...' : $title;
}
