<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $product;

$is_out_of_stock = !$product->is_in_stock();

if($is_out_of_stock){
    // Get product attributes for the popup
    $term_color = wc_get_product_terms($product->get_id(), 'pa_color', array('fields' => 'names'));
    $color = array_shift($term_color);
    
    // Get ALL product sizes (not just in stock)
    $all_sizes = wc_get_product_terms($product->get_id(), 'pa_size', array('fields' => 'all'));
    ?>
    
    <!-- Hidden elements with all sizes for the modal window -->
    <div style="display: none;" class="hidden-sizes-for-modal">
        <?php 
        if (!empty($all_sizes) && !is_wp_error($all_sizes)) {
            foreach ($all_sizes as $size_term) {
                ?>
                <label style="display: inline-block; margin-right: 10px;">
                    <input type="radio" 
                           name="attribute_pa_size" 
                           value="<?php echo esc_attr($size_term->slug); ?>" 
                           data-size-name="<?php echo esc_attr($size_term->name); ?>" />
                    <span data-select="<?php echo esc_attr($size_term->name); ?>">
                        <?php echo esc_html($size_term->name); ?>
                    </span>
                </label>
                <?php
            }
        }
        ?>
    </div>
    <?php
}

if((int)$product->get_price()>0):
?>
<div class="rs-product__info">
    <div class="rs-product__prices">
        <?php echo wp_kses_post($product->get_price_html()); ?>
    </div>
    <?php
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
    $discount = $discount ? '-' . $discount . '%' : '';?>
    <div class="rs-product__labels">
        <?php if($onsale) : ?>
            <div class="rs-product__label rs-product__label-sale"><?php echo esc_html($discount); ?></div>
        <?php endif; ?>
        <?php if(get_field('_new_product')=='yes') : ?>
            <div class="rs-product__label rs-product__label-new"><?php esc_html_e('New', 'storefront'); ?></div>
        <?php endif; ?>
        <?php if($is_out_of_stock) : ?>
            <div class="rs-product__label rs-product__label-out-of-stock"><?php esc_html_e('Out of stock', 'storefront'); ?></div>
        <?php endif; ?>
    </div>
    <?php 
    $show_low_stock_badge = get_post_meta($product->get_id(), '_show_low_stock_badge', true);
    if ($show_low_stock_badge === '') {
        $show_low_stock_badge = 'yes';
    }

    if (!$is_out_of_stock && $show_low_stock_badge === 'yes' && $product->get_stock_quantity() > 0 && $product->get_stock_quantity() < 50): 
    ?>
        <div class="rs-product__label rs-product__label-low_stock"><?php esc_html_e('Almost sold out', 'storefront'); ?></div>
    <?php endif; ?>
</div>
<?php
endif;
?>
