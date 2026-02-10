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
    exit; // Exit if accessed directly
}
global $product;

// Show price even when out of stock (sizes/colors will be shown with disabled state in add-to-cart templates)
// Store out of stock info for popup functionality
$is_out_of_stock = !$product->is_in_stock();

if($is_out_of_stock){
    // Получаем атрибуты товара для попапа
    $term_color = wc_get_product_terms($product->get_id(), 'pa_color', array('fields' => 'names'));
    $color = array_shift($term_color);
    
    // Получаем ВСЕ размеры товара (не только в наличии)
    $all_sizes = wc_get_product_terms($product->get_id(), 'pa_size', array('fields' => 'all'));
    ?>
    
    <!-- Скрытые элементы со всеми размерами для модального окна -->
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

// Always show price section (whether in stock or not)
if((int)$product->get_price()>0):
?>
<div class="rs-product__info">
    <div class="rs-product__prices">
        <?php echo $product->get_price_html(); ?>
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
        <?php if($onsale ) :?>
            <div class="rs-product__label rs-product__label-sale"><?=$discount; ?></div>
        <?php endif; ?>
        <?php if(get_field('_new_product')=='yes') :?>
            <div class="rs-product__label rs-product__label-new">Новинка</div>
        <?php endif; ?>
        <?php if($is_out_of_stock) :?>
            <div class="rs-product__label rs-product__label-out-of-stock">Товар закончился</div>
        <?php endif; ?>
    </div>
    <?php 
    // Check if "Show Low Stock Badge" is enabled (default: yes)
    $show_low_stock_badge = get_post_meta($product->get_id(), '_show_low_stock_badge', true);
    if ($show_low_stock_badge === '') {
        $show_low_stock_badge = 'yes'; // Default value
    }

    
    // Show badge only if enabled AND stock is low (and product is in stock)
    if (!$is_out_of_stock && $show_low_stock_badge === 'yes' && $product->get_stock_quantity() > 0 && $product->get_stock_quantity() < 50): 
    ?>
        <div class="rs-product__label rs-product__label-low_stock">Скоро закончится</div>
    <?php endif; ?>
</div>
<?php
endif;
?>

