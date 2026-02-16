<?php
declare(strict_types=1);

/**
 * Helper Functions
 * 
 * Contains utility functions and helper classes
 * 
 * @package SpbShield
 * @since 1.0.0
 */

namespace SpbShield\Inc;

class HelperFunctions {
    
    /**
     * Theme directory path
     */
    private readonly string $theme_dir;
    
    /**
     * Constructor - Register hooks
     */
    public function __construct() {
        $this->theme_dir = get_stylesheet_directory();
        
        // Theme updates
        add_action('wp_loaded', [$this, 'disable_theme_update']);
        
        // JPEG quality
        add_filter('jpeg_quality', [$this, 'set_jpeg_quality']);
        
        // Image sizes
        add_action('after_setup_theme', [$this, 'register_image_sizes']);
        
        // Customizer
        add_action('customize_register', [$this, 'logotypes_customize_register']);
        
        // Dashboard widget
        add_action('wp_dashboard_setup', [$this, 'add_help_widget']);
        
        // Template redirect
        add_action('template_redirect', [$this, 'get_tpl_include']);
        
        // Visited products cookie
        add_action('wp', [$this, 'set_user_visited_product_cookie']);
        
        // WebP fixes
        add_filter('wp_get_attachment_image_src', [$this, 'fix_double_webp_extensions'], 10, 3);
        add_filter('wp_get_attachment_image_srcset', [$this, 'fix_double_webp_srcset'], 10, 4);
        add_filter('the_content', [$this, 'fix_double_webp_in_content']);
        add_filter('wp_calculate_image_srcset', [$this, 'fix_double_webp_calculate_srcset'], 10, 5);
        
        // ACF Options
        $this->register_acf_options();
        
        // Disable big image threshold
        add_filter('big_image_size_threshold', '__return_false');
    }
    
    /**
     * Register ACF Options pages
     */
    private function register_acf_options(): void {
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page([
                'page_title' => 'Header Settings',
                'menu_title' => 'Header Settings',
                'menu_slug' => 'header-settings',
                'capability' => 'edit_posts',
                'redirect' => false
            ]);
        }
    }
    
    /**
     * Disable theme updates
     */
    public function disable_theme_update(): void {
        remove_action('load-update-core.php', 'wp_update_themes');
        add_filter('pre_site_transient_update_themes', '__return_null');
    }
    
    /**
     * Set JPEG quality to 100%
     * 
     * @param int $quality Current quality
     * @return int Modified quality
     */
    public function set_jpeg_quality(int $quality): int {
        return 100;
    }
    
    /**
     * Register custom image sizes
     */
    public function register_image_sizes(): void {
        $sizes = [
            'single-post-thumbnail' => [98, 98, true],
            'img-about' => [638, 638, false],
            'img-representatives' => [431, 433, true],
            'img-product__slide' => [431, 503, false],
            'img-banner' => [375, 301, true],
            'img-mobile' => [375, 750, true],
            'img-horizontal' => [898, 360, true],
            'img-rs-collection' => [960, 933, true],
            'img-rs-media' => [898, 898, true],
            'img-rs-inst' => [638, 638, true],
        ];
        
        foreach ($sizes as $name => [$width, $height, $crop]) {
            add_image_size($name, $width, $height, $crop);
        }
    }
    
    /**
     * Add logotype settings to customizer
     * 
     * @param \WP_Customize_Manager $wp_customize WP_Customize_Manager instance
     */
    public function logotypes_customize_register(\WP_Customize_Manager $wp_customize): void {
        $wp_customize->add_setting('text_logo');
        $wp_customize->add_control(new \WP_Customize_Image_Control($wp_customize, 'text_logo', [
            'label' => __('Text Logo', 'storefront'),
            'section' => 'title_tagline',
            'settings' => 'text_logo',
            'priority' => 8,
        ]));
        
        $wp_customize->add_setting('footer_logo');
        $wp_customize->add_control(new \WP_Customize_Image_Control($wp_customize, 'footer_logo', [
            'label' => __('Footer Logo', 'storefront'),
            'section' => 'title_tagline',
            'settings' => 'footer_logo',
            'priority' => 9,
        ]));
    }
    
    /**
     * Add help widget to dashboard
     */
    public function add_help_widget(): void {
        wp_add_dashboard_widget(
            'help_widget',
            'Добро пожаловать в РСУ',
            [$this, 'help_widget_content']
        );
    }
    
    /**
     * Display help widget content
     */
    public function help_widget_content(): void {
        echo '<p><a href="https://rosait.ru/wordpress-instruktsiya/" target="_blank">Руководство по работе</a> с системой управления WordPress</p>';
        echo '<p>Техническая поддержка: support@rosait.ru<p>';
        echo '<p>Отдел продаж: +7 (800) 222-90-72 по всей России (бесплатно)<p>';
        echo '<hr/><p>РСУ - Россайт Система управления для Wordpress</p>';
    }
    
    /**
     * Include template-specific functions
     */
    public function get_tpl_include(): void {
        $template = get_page_template_slug(get_the_ID());
        $base_path = $this->theme_dir . '/template-parts/';
        
        $templates = [
            'template-allblocks.php' => 'rs-page-allblocks/rs-page-allblocks-functions.php',
            'contacts.php' => 'rs-page-contacts/rs-page-contacts-functions.php',
            'template-burger' => 'rs-page-burger-menu/rs-page-burger-menu-functions.php',
        ];
        
        if (isset($templates[$template])) {
            $file = $base_path . $templates[$template];
            if (file_exists($file)) {
                require_once $file;
            }
        }
        
        // Always include base page functions
        $base_file = $base_path . 'rs-page-base/rs-page-base-functions.php';
        if (file_exists($base_file)) {
            require_once $base_file;
        }
    }
    
    /**
     * Set cookie for visited products
     */
    public function set_user_visited_product_cookie(): void {
        global $post;
        
        if (!is_product() || !isset($post->ID)) {
            return;
        }
        
        $recently = $_COOKIE['woocommerce_recently_viewed'] ?? '';
        $arr = array_filter(array_map('intval', explode(',', $recently)));
        $arr[] = $post->ID;
        $arr = array_unique($arr);
        
        // Keep only last 7 items
        if (count($arr) > 7) {
            $arr = array_slice($arr, -7);
        }
        
        wc_setcookie('woocommerce_recently_viewed', implode(',', $arr));
    }
    
    /**
     * Fix double .webp extensions
     * 
     * @param array<int, mixed>|false $image Image data
     * @param int $attachment_id Attachment ID
     * @param string|int[]|array<int, int> $size Image size
     * @return array<int, mixed>|false Modified image data
     */
    public function fix_double_webp_extensions(array|false $image, int $attachment_id, string|array $size): array|false {
        if (is_array($image) && isset($image[0]) && is_string($image[0])) {
            $image[0] = str_replace('.webp.webp', '.webp', $image[0]);
        }
        return $image;
    }
    
    /**
     * Fix double .webp in srcset
     * 
     * @param array<int|string, array<string, mixed>>|false $sources Sources array
     * @param array<int, int> $size_array Size array
     * @param string $image_src Image source
     * @param array<string, mixed> $image_meta Image meta
     * @return array<int|string, array<string, mixed>>|false Modified sources
     */
    public function fix_double_webp_srcset(array|false $sources, array $size_array, string $image_src, array $image_meta): array|false {
        if (is_array($sources)) {
            foreach ($sources as &$source) {
                if (isset($source['url']) && is_string($source['url'])) {
                    $source['url'] = str_replace('.webp.webp', '.webp', $source['url']);
                }
            }
        }
        return $sources;
    }
    
    /**
     * Fix double .webp in content
     * 
     * @param string $content Post content
     * @return string Modified content
     */
    public function fix_double_webp_in_content(string $content): string {
        return str_replace('.webp.webp', '.webp', $content);
    }
    
    /**
     * Fix double .webp in calculated srcset
     * 
     * @param array<int|string, array<string, mixed>>|false $sources Sources array
     * @param array<int, int> $size_array Size array
     * @param string $image_src Image source
     * @param array<string, mixed> $image_meta Image meta
     * @param int $attachment_id Attachment ID
     * @return array<int|string, array<string, mixed>>|false Modified sources
     */
    public function fix_double_webp_calculate_srcset(array|false $sources, array $size_array, string $image_src, array $image_meta, int $attachment_id): array|false {
        if (is_array($sources)) {
            foreach ($sources as &$source) {
                if (isset($source['url']) && is_string($source['url'])) {
                    $source['url'] = str_replace('.webp.webp', '.webp', $source['url']);
                }
            }
        }
        return $sources;
    }
    
    /**
     * Check if mobile device
     * 
     * @return bool True if mobile
     */
    public static function is_mobile(): bool {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        return (bool) preg_match(
            "/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i",
            $user_agent
        );
    }
}
