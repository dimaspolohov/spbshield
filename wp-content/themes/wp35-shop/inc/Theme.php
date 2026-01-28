<?php
/**
 * Main Theme Bootstrap Class
 * 
 * Central class for theme initialization and management
 * 
 * @package SpbShield
 * @since 2.1.0
 */

namespace SpbShield\Inc;

class Theme {
    
    /**
     * Theme directory path
     * 
     * @var string
     */
    private string $theme_dir;
    
    /**
     * Constructor - Initialize theme
     */
    public function __construct() {
        $this->theme_dir = get_stylesheet_directory();
        
        // Register hooks
        add_action('after_setup_theme', [$this, 'init_core_classes'], 1);
        add_action('after_setup_theme', [$this, 'load_template_functions'], 5);
        
        // Load legacy files
        $this->load_legacy_files();
    }
    
    /**
     * Initialize core theme classes
     * 
     * Creates instances of all main theme classes
     * Classes are initialized in order of dependency
     */
    public function init_core_classes(): void {
        // Security features (loaded first for security)
        new SecurityFeatures();
        
        // Core functionality
        new PostTypes();
        new HelperFunctions();
        new Shortcodes();
        
        // Assets management
        new AssetsManager();
        
        // AJAX handlers
        new AjaxHandlers();
        
        // Store availability
        new StoreAvailability();
        
        // WooCommerce customizations (only if WooCommerce is active)
        if (function_exists('is_woocommerce')) {
            new WooCommerceCustomizations();
        }
        
        // ACF Polylang compatibility (only if ACF is active)
        if (function_exists('acf_add_options_page')) {
            new ACF_Page_Type_Polylang();
        }
    }
    
    /**
     * Load template part functions
     * 
     * Includes function files for all template parts
     */
    public function load_template_functions(): void {
        $template_parts = [
            'rs-header',
            'rs-text-block',
            'rs-features',
            'rs-features-3x',
            'rs-services',
            'rs-slider',
            'rs-best-sellers',
            'rs-popular',
            'rs-onsale',
            'rs-new-products',
            'rs-new-product',
            'rs-collection',
            'rs-popular-product',
            'rs-media',
            'rs-inst',
            'rs-about-us',
            'rs-representatives',
            'rs-form',
            'rs-team',
            'rs-howworks',
            'rs-offers',
            'rs-features-photo',
            'rs-numbers',
            'rs-contactus',
            'rs-parallax-land',
            'rs-partners',
            'rs-video',
            'rs-counter',
            'rs-subscribe',
            'rs-photogallery',
            'rs-video-new',
            'rs-tabs',
            'rs-parallax-1',
            'rs-parallax-2',
            'rs-services-icon',
            'rs-contact-land',
            'rs-recommendations',
            'rs-carousel',
            'rs-footer',
        ];
        
        foreach ($template_parts as $part) {
            $this->load_template_part_functions($part);
        }
    }
    
    /**
     * Load functions for a specific template part
     * 
     * @param string $part Template part name
     */
    private function load_template_part_functions(string $part): void {
        $file = $this->theme_dir . '/template-parts/' . $part . '/' . $part . '-functions.php';
        
        if (file_exists($file)) {
            require_once $file;
        }
    }
    
    /**
     * Load legacy files that haven't been converted to classes
     */
    private function load_legacy_files(): void {
        $legacy_files = [
            '/spacefix.php',
            '/inc/common.php',
            '/inc/post-types.php',
            '/inc/services-functions.php',
        ];
        
        foreach ($legacy_files as $file) {
            $path = $this->theme_dir . $file;
            if (file_exists($path)) {
                require_once $path;
            }
        }
        
        // WooCommerce functions (conditional)
        if (function_exists('is_woocommerce')) {
            $wc_file = $this->theme_dir . '/woocommerce/wc-functions.php';
            if (file_exists($wc_file)) {
                require_once $wc_file;
            }
        }
    }
}
