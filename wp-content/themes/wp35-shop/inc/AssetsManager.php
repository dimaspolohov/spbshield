<?php
declare(strict_types=1);

/**
 * Assets Manager
 * 
 * Handles enqueuing of styles and scripts
 * 
 * @package SpbShield
 * @since 1.0.0
 */

namespace SpbShield\Inc;

class AssetsManager {
    
    /**
     * Theme directory path
     */
    private readonly string $theme_dir;
    
    /**
     * Theme directory URI
     */
    private readonly string $theme_uri;
    
    /**
     * Constructor - Register hooks
     */
    public function __construct() {
        $this->theme_dir = get_stylesheet_directory();
        $this->theme_uri = get_stylesheet_directory_uri();
        
        // Dequeue default scripts and styles
        add_action('wp_print_scripts', [$this, 'dequeue_scripts'], 100);
        add_action('wp_print_styles', [$this, 'dequeue_styles'], 100);
        add_action('wp_enqueue_scripts', [$this, 'remove_block_css'], 100);
        
        // Enqueue theme assets
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
        add_action('wp_footer', [$this, 'enqueue_scripts'], 10);
        
        // Disable Gutenberg and move scripts
        if (!is_admin()) {
            add_filter('storefront_customizer_woocommerce_css', '__return_false');
            add_filter('storefront_customizer_css', '__return_false');
            add_filter('use_block_editor_for_post_type', '__return_false', 10);
            
            // Move scripts to footer
            remove_action('wp_head', 'wp_print_scripts');
            add_action('wp_footer', 'wp_print_scripts', 5);
        }
    }
    
    /**
     * Dequeue default WooCommerce and Storefront scripts
     */
    public function dequeue_scripts(): void {
        if (!function_exists('is_woocommerce')) {
            return;
        }
        
        wp_dequeue_script('storefront-header-cart');
        wp_dequeue_style('storefront-icons');
        
        if (!is_woocommerce() && !is_cart() && !is_checkout()) {
            $scripts_to_dequeue = [
                'storefront-header-cart',
                'prettyPhoto',
                'prettyPhoto-init',
                'jquery-blockui',
                'jquery-placeholder',
                'fancybox',
                'jqueryui',
            ];
            
            $styles_to_dequeue = [
                'woocommerce_frontend_styles',
                'woocommerce_fancybox_styles',
                'woocommerce_chosen_styles',
                'woocommerce_prettyPhoto_css',
            ];
            
            array_walk($scripts_to_dequeue, 'wp_dequeue_script');
            array_walk($styles_to_dequeue, 'wp_dequeue_style');
        }
        
        wp_dequeue_style('yandex-money-checkout');
    }
    
    /**
     * Dequeue and re-register Storefront styles
     */
    public function dequeue_styles(): void {
        if (is_admin()) {
            return;
        }
        
        $styles_to_remove = [
            'storefront-style',
            'storefront-style-inline',
            'storefront-woocommerce',
            'storefront-woocommerce-inline',
            'storefront-woocommerce-style-inline',
        ];
        
        foreach ($styles_to_remove as $style) {
            wp_dequeue_style($style);
            wp_deregister_style($style);
        }
        
        wp_enqueue_style('storefront-style', $this->theme_uri . '/assets/css/woocommerce.css');
    }
    
    /**
     * Remove Gutenberg block CSS
     */
    public function remove_block_css(): void {
        if (is_admin()) {
            return;
        }
        
        $styles_to_remove = [
            'wp-block-library',
            'wp-block-library-theme',
            'wc-block-style',
            'storefront-gutenberg-blocks',
            'storefront-gutenberg-blocks-inline',
            'woocommerce-inline',
            'storefront-fonts',
            'storefront-icons',
            'storefront-style',
            'storefront-woocommerce',
            'storefront-woocommerce-inline',
            'storefront-woocommerce-style-inline',
        ];
        
        array_walk($styles_to_remove, 'wp_dequeue_style');
    }
    
    /**
     * Enqueue theme styles
     */
    public function enqueue_styles(): void {
        // Core styles
        $this->enqueue_core_styles();
        
        // Theme component styles
        $this->enqueue_component_styles();
        
        // Page-specific styles
        $this->enqueue_page_styles();
        
        // Main theme stylesheet
        $this->enqueue_main_stylesheet();
    }
    
    /**
     * Enqueue core library styles
     */
    private function enqueue_core_styles(): void {
        $styles = [
            'owl-carousel' => 'owl.carousel.min.css',
            'bootstrap-min' => 'bootstrap.min.css',
            'swiper-bundle' => 'swiper-bundle.min.css',
            'lightgallery-bundle' => 'lightgallery-bundle.min.css',
            'lg-zoom' => 'lg-zoom.css',
            'aos' => 'aos.css',
        ];
        
        foreach ($styles as $handle => $file) {
            $this->enqueue_style($handle, $file);
        }
    }
    
    /**
     * Enqueue theme component styles
     */
    private function enqueue_component_styles(): void {
        $components = [
            'rs-style',
            'rs-common-style',
            'rs-header',
            'rs-footer',
            'rs-new-product',
            'rs-collection',
            'rs-popular-product',
            'rs-media',
            'rs-inst',
            'rs-banner',
            'rs-about-us',
            'rs-representatives',
        ];
        
        if (get_field('on_slider')) {
            $components[] = 'rs-slider';
        }
        
        foreach ($components as $component) {
            $this->enqueue_style($component, "{$component}.css");
        }
    }
    
    /**
     * Enqueue page-specific styles
     */
    private function enqueue_page_styles(): void {
        // Product pages
        if (is_product() || is_shop() || is_product_category() || is_product_tag() || is_search()) {
            $this->enqueue_style('rs-breadcrumbs', 'rs-breadcrumbs.css');
        }
        
        if (is_product()) {
            $this->enqueue_style('rs-product', 'rs-product.css');
            $this->enqueue_style('rs-buy-product', 'rs-buy-product.css');
            $this->enqueue_style('rs-recent-product', 'rs-recent-product.css');
        }
        
        if (is_shop() || is_product_category() || is_product_tag() || is_search() || get_page_template_slug() === 'template-wishlist.php') {
            $this->enqueue_style('rs-catalog', 'rs-catalog.css');
            $this->enqueue_style('rs-filters', 'rs-filters.css');
        }
        
        // Cart and checkout
        if (is_cart() || is_checkout()) {
            wp_enqueue_style('intlTelInput', 'https://cdn.jsdelivr.net/npm/intl-tel-input@25.2.1/build/css/intlTelInput.css');
        }
        
        // Template-specific
        $this->enqueue_template_styles();
        
        // Post type archives
        $this->enqueue_archive_styles();
    }
    
    /**
     * Enqueue template-specific styles
     */
    private function enqueue_template_styles(): void {
        $template = get_page_template_slug();
        
        $template_styles = [
            'template-clients.php' => ['rs-client-info', 'rs-form'],
            'template-store.php' => ['rs-store', 'rs-tour', 'rs-form'],
            'template-woostyle.php' => function() {
                wp_enqueue_style('rs-woo-addition', WP_CONTENT_URL . '/themes/storefront/assets/css/woocommerce/woocommerce.css');
                wp_enqueue_style('icons', $this->theme_uri . '/assets/css/icons.css');
                wp_enqueue_style('font-awesome', $this->theme_uri . '/assets/css/font-awesome.min.css');
                $this->enqueue_style('main', 'style-main.css');
                $this->enqueue_style('rs-cart', '../woocommerce/css/rs-cart.css');
            },
        ];
        
        if (isset($template_styles[$template])) {
            if (is_callable($template_styles[$template])) {
                $template_styles[$template]();
            } else {
                foreach ($template_styles[$template] as $style) {
                    $this->enqueue_style($style, "{$style}.css");
                }
            }
        }
    }
    
    /**
     * Enqueue archive-specific styles
     */
    private function enqueue_archive_styles(): void {
        if (is_singular('news')) {
            wp_enqueue_style('nekoanim', $this->theme_uri . '/assets/css/nekoanim.css');
            wp_enqueue_style('animate', $this->theme_uri . '/assets/css/animate.min.css');
            $this->enqueue_style('rs-photogallery', 'rs-photogallery.css');
        }
        
        if (is_post_type_archive('news')) {
            $this->enqueue_style('rs-banner-video', 'rs-banner-video.css');
            $this->enqueue_style('rs-media-news', 'rs-media-news.css');
            $this->enqueue_style('rs-podcast', 'rs-podcast.css');
        }
        
        if (is_post_type_archive('collections')) {
            $this->enqueue_style('rs-collections-archive', 'rs-collections-archive.css');
        }
    }
    
    /**
     * Enqueue main theme stylesheet
     */
    private function enqueue_main_stylesheet(): void {
        wp_dequeue_style('storefront-child-style');
        wp_deregister_style('storefront-child-style');
        wp_register_style('storefront-child-style', $this->theme_uri . '/style.css', [], filemtime($this->theme_dir . '/style.css'));
        wp_enqueue_style('storefront-child-style');
    }
    
    /**
     * Enqueue theme scripts
     */
    public function enqueue_scripts(): void {
        // Core scripts
        $this->enqueue_core_scripts();
        
        // Component scripts
        $this->enqueue_component_scripts();
        
        // Page-specific scripts
        $this->enqueue_page_scripts();
    }
    
    /**
     * Enqueue core library scripts
     */
    private function enqueue_core_scripts(): void {
        $scripts = [
            'jquery-cookie' => ['jquery.cookie.min.js', []],
            'bootstrap-min' => ['bootstrap.min.js', ['jquery-core']],
            'jquery-mCustomScrollbar' => ['jquery.mCustomScrollbar.concat.min.js', ['bootstrap-min']],
            'owl-carousel' => ['owl.carousel.min.js', ['bootstrap-min']],
            'mousewheel' => ['jquery.mousewheel.min.js', ['bootstrap-min']],
            'jquery-easing' => ['jquery.easing.1.3.js', ['bootstrap-min']],
            'slider-slick' => ['slick.min.js', ['bootstrap-min']],
            'jquery-appear' => ['jquery.appear.js', ['bootstrap-min']],
            'jquery-validate' => ['jquery.validate.min.js', ['bootstrap-min']],
            'jquery-counterup' => ['jquery.counterup.min.js', ['bootstrap-min']],
            'jquery-waypoints' => ['jquery.waypoints.min.js', ['bootstrap-min']],
        ];
        
        foreach ($scripts as $handle => [$file, $deps]) {
            wp_enqueue_script($handle, $this->theme_uri . '/assets/js/' . $file, $deps);
        }
        
        // Conditional mask input
        if (is_cart() || is_checkout()) {
            wp_enqueue_script('intlTelInput', 'https://cdn.jsdelivr.net/npm/intl-tel-input@25.2.1/build/js/intlTelInput.min.js', [], '');
            wp_enqueue_script('inputmask', 'https://cdn.jsdelivr.net/npm/inputmask@5.0.9/dist/jquery.inputmask.min.js', [], '');
            // Main script with dependencies for cart/checkout
            $this->enqueue_script('main-javascript', 'main.js', ['jquery-core', 'intlTelInput', 'inputmask']);
        } else {
            wp_enqueue_script('jquery-maskedinput', $this->theme_uri . '/assets/js/jquery.maskedinput.min.js', ['bootstrap-min']);
            // Main script without conditional dependencies
            $this->enqueue_script('main-javascript', 'main.js', ['jquery-core']);
        }

        // Autocomplete script
        $this->enqueue_script('autocomplete-javascript', 'jquery.autocomplete.js', ['jquery-core']);
    }
    
    /**
     * Enqueue component scripts
     */
    private function enqueue_component_scripts(): void {
        $scripts = [
            'swiper-bundle-js' => ['swiper-bundle.min.js', '', true],
            'lightgallery-js' => ['lightgallery.min.js', '', true],
            'lg-zoom-js' => ['lg-zoom.umd.js', '', true],
            'aos-js' => ['aos.js', '', true],
            'dynamic_adapt-js' => ['dynamic_adapt.js', '', true],
        ];
        
        foreach ($scripts as $handle => [$file, $deps, $in_footer]) {
            wp_enqueue_script($handle, $this->theme_uri . '/assets/js/' . $file, $deps ?: [], '', $in_footer);
        }
        
        // Theme scripts with versioning
        $this->enqueue_script('rs-script-js', 'rs-script.js', [], true);
        $this->enqueue_script('rs-header-js', 'rs-header.js', [], false);
        
        if (get_field('on_slider')) {
            wp_enqueue_script('rs-slider-js', $this->theme_uri . '/assets/js/rs-slider.js', [], '', false);
        }
        
        $components = [
            'rs-new-product-js' => 'rs-new-product.js',
            'rs-popular-product-js' => 'rs-popular-product.js',
            'rs-inst-js' => 'rs-inst.js',
            'rs-about-us-js' => 'rs-about-us.js',
            'rs-representatives-js' => 'rs-representatives.js',
        ];
        
        foreach ($components as $handle => $file) {
            wp_enqueue_script($handle, $this->theme_uri . '/assets/js/' . $file, [], '', false);
        }
        
        $this->enqueue_script('rs-media-js', 'rs-media.js', [], false);
    }
    
    /**
     * Enqueue page-specific scripts
     */
    private function enqueue_page_scripts(): void {
        if (is_product()) {
            $this->enqueue_script('rs-availability-checker-js', 'rs-availability-checker.js', [], false);
            $this->enqueue_script('rs-product-js', 'rs-product.js', [], false);
            wp_enqueue_script('spollers-js', $this->theme_uri . '/assets/js/spollers.js', [], '', false);
            $this->enqueue_script('rs-buy-product-js', 'rs-buy-product.js', [], false);
            wp_enqueue_script('rs-recent-product-js', $this->theme_uri . '/assets/js/rs-recent-product.js', [], '', false);
        }
        
        $this->enqueue_script('product-js', 'product.js', [], false);
        
        if (is_shop() || is_product_category() || is_product_tag()) {
            wp_enqueue_script('spollers-js', $this->theme_uri . '/assets/js/spollers.js', [], '', false);
            $this->enqueue_script('rs-catalog', 'rs-catalog.js', [], false);
        }
        
        $template = get_page_template_slug();
        
        if ($template === 'template-clients.php') {
            $this->enqueue_script('tabs-js', 'tabs.js', [], false);
            $this->enqueue_script('rs-clients-info-js', 'rs-clients-info.js', [], false);
        }
        
        if ($template === 'template-store.php') {
            $this->enqueue_script('rs-map-js', 'rs-map.js', [], false);
        }
        
        if (is_post_type_archive('collections')) {
            $this->enqueue_script('rs-collections-archive-js', 'rs-collections-archive.js', [], false);
        }
        
        if (is_post_type_archive('news')) {
            $this->enqueue_script('rs-banner-video-js', 'rs-banner-video.js', [], false);
            $this->enqueue_script('rs-podcast-js', 'rs-podcast.js', [], false);
            $this->enqueue_script('rs-media-new-js', 'rs-media.js', [], false);
        }
    }
    
    /**
     * Helper: Enqueue style with versioning
     * 
     * @param string $handle Style handle
     * @param string $file File name
     */
    private function enqueue_style(string $handle, string $file): void {
        $path = $this->theme_dir . '/assets/css/' . $file;
        $uri = $this->theme_uri . '/assets/css/' . $file;
        $version = file_exists($path) ? (string) filemtime($path) : '1.0.0';
        
        wp_enqueue_style($handle, $uri, [], $version);
    }
    
    /**
     * Helper: Enqueue script with versioning
     * 
     * @param string $handle Script handle
     * @param string $file File name
     * @param array<int, string> $deps Dependencies
     * @param bool $in_footer Load in footer
     */
    private function enqueue_script(string $handle, string $file, array $deps = [], bool $in_footer = false): void {
        $path = $this->theme_dir . '/assets/js/' . $file;
        $uri = $this->theme_uri . '/assets/js/' . $file;
        $version = file_exists($path) ? (string) filemtime($path) : '1.0.0';
        
        wp_enqueue_script($handle, $uri, $deps, $version, $in_footer);
    }
}
