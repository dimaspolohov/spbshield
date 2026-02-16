<?php
declare(strict_types=1);

/**
 * Legacy Support
 * 
 * Provides backward compatibility for template functions
 * 
 * @package SpbShield
 * @since 1.0.0
 */

namespace SpbShield\Inc;

class LegacySupport {
    
    /**
     * Display wishlist icon
     */
    public static function wishlist_icon(): void {
        echo do_shortcode('[yith_wcwl_add_to_wishlist]');
    }
    
    /**
     * Display free delivery message
     */
    public static function free_delivery(): void {
        $frontpage_id = (int) get_option('page_on_front');
        if (!$frontpage_id) {
            return;
        }
        
        $delivery_text = get_field('product_free_delivery', $frontpage_id);
        
        if (empty($delivery_text)) {
            return;
        }
        
        echo '<div class="rs-product__delivery icon-truck">' . esc_html((string) $delivery_text) . '</div>';
    }
    
    /**
     * Display filter orderby radio checked state
     * 
     * @param string $value Current value
     * @param string $order Order to check
     */
    public static function filters_orderby(string $value, string $order): void {
        if ($value === $order) {
            echo 'checked="checked"';
        }
    }
    
    /**
     * Check if mobile device
     * 
     * @return bool True if mobile
     */
    public static function is_mobile(): bool {
        return HelperFunctions::is_mobile();
    }
}
