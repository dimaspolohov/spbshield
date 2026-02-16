<?php
declare(strict_types=1);

/**
 * Shortcodes
 * 
 * Handles all theme shortcodes
 * 
 * @package SpbShield
 * @since 1.0.0
 */

namespace SpbShield\Inc;

class Shortcodes {
    
    /**
     * Constructor - Register shortcodes
     */
    public function __construct() {
        add_shortcode('size_guide_content', [$this, 'size_guide_content']);
    }
    
    /**
     * Size guide content shortcode
     * 
     * @return string Content to display
     */
    public function size_guide_content(): string {
        if (is_shop() || is_product_category() || is_product_tag()) {
            $page_id = (int) get_option('woocommerce_shop_page_id');
            return (string) get_the_content('', false, $page_id);
        }
        
        if (is_404()) {
            return '';
        }
        
        global $product;
        
        if (!isset($product) || !$product instanceof \WC_Product) {
            return '';
        }
        
        return (string) get_field('size_guide', $product->get_id());
    }
}
