<?php
declare(strict_types=1);

/**
 * ACF Page Type Polylang Compatibility
 * 
 * Provides compatibility between ACF and Polylang for page types
 * 
 * @package SpbShield
 * @since 1.0.0
 */

namespace SpbShield\Inc;

class ACF_Page_Type_Polylang {
    
    /**
     * Whether we hooked page_on_front
     */
    private bool $filtered = false;
    
    /**
     * Constructor - Register hooks
     */
    public function __construct() {
        add_filter('acf/location/rule_match/page_type', [$this, 'hook_page_on_front']);
    }
    
    /**
     * Hook page_on_front filter
     * 
     * @param bool $match Match result
     * @return bool Match result
     */
    public function hook_page_on_front(bool $match): bool {
        if (!$this->filtered) {
            add_filter('option_page_on_front', [$this, 'translate_page_on_front']);
            $this->filtered = true;
        }
        
        return $match;
    }
    
    /**
     * Translate page_on_front for Polylang
     * 
     * @param mixed $value Page ID or false
     * @return mixed Translated page ID or original value
     */
    public function translate_page_on_front(mixed $value): mixed {
        if (!function_exists('pll_get_post') || !is_numeric($value)) {
            return $value;
        }
        
        $translated = pll_get_post((int) $value);
        
        return $translated ?: $value;
    }
}
