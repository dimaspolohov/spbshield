<?php
/**
 * WooCommerce Customizations
 * 
 * Handles all WooCommerce-specific customizations and filters
 * 
 * @package SpbShield
 * @since 1.0.0
 */

namespace SpbShield\Inc;

class WooCommerceCustomizations {
    
    /**
     * Constructor - Register hooks
     */
    public function __construct() {
        $this->register_filters();
        $this->register_actions();
    }
    
    /**
     * Register all filters
     */
    private function register_filters(): void
    {
        // Cart and checkout messages
        add_filter('wc_add_to_cart_message_html', '__return_false', 999);
        add_filter('woocommerce_cart_item_removed_notice_type', '__return_false', 999);
        add_filter('woocommerce_checkout_show_messages', '__return_false', 999);
        
        // ACF customizations
        add_filter('acf/fields/post_object/result', [$this, 'update_acf_post_object_field_choices'], 10, 4);
        
        // Price display
        add_filter('woocommerce_get_price_html', [$this, 'change_price_html'], 999, 2);
        
        // Shipping
        add_filter('woocommerce_shipping_free_shipping_instance_settings_values', [$this, 'sanitize_shipping_min_amount'], 10, 2);
        
        // Product titles in admin
        add_filter('the_title', [$this, 'add_color_to_product_title'], 10, 2);
        
        // Coupon search
        add_filter('woocommerce_get_shop_coupon_data', [$this, 'coupon_case_insensitive_search'], 10, 2);
        
        // Hide out of stock items from catalog (enable WooCommerce option programmatically)
        add_filter('pre_option_woocommerce_hide_out_of_stock_items', function($value) {
            return 'yes';
        });
    }
    
    /**
     * Register all actions
     */
    private function register_actions(): void {
        // Search filter
        add_action('pre_get_posts', [$this, 'search_filter']);
        
        // Order status changes
        add_action('woocommerce_order_status_changed', [$this, 'email_on_status_change'], 10, 4);
        
        // Import actions
        add_action('pmxi_saved_post', [$this, 'process_product_meta']);
        add_action('pmxi_saved_post', [$this, 'decrease_order_item_stock'], 100, 3);
        
        // Low stock badge
        add_action('woocommerce_product_options_inventory_product_data', [$this, 'add_low_stock_badge_field']);
        add_action('woocommerce_process_product_meta', [$this, 'save_low_stock_badge_field'], 10, 1);
        add_action('woocommerce_new_product', [$this, 'set_default_low_stock_badge']);
        
        // Manual out of stock checkbox
        add_action('woocommerce_product_options_inventory_product_data', [$this, 'add_manual_out_of_stock_field'], 5);
        add_action('woocommerce_process_product_meta', [$this, 'save_manual_out_of_stock_field'], 10, 1);
        add_filter('woocommerce_product_is_in_stock', [$this, 'check_manual_out_of_stock'], 10, 2);
        add_filter('woocommerce_product_get_stock_status', [$this, 'get_manual_stock_status'], 10, 2);
        add_filter('woocommerce_product_variation_get_stock_status', [$this, 'get_manual_stock_status'], 10, 2);
    }
    
    /**
     * Update ACF post object field choices with color info
     * 
     * @param string $title Field title
     * @param object $post Post object
     * @param array $field Field data
     * @param int $post_id Post ID
     * @return string Modified title
     */
    public function update_acf_post_object_field_choices(string $title, object $post, array $field, int $post_id): string {
        $allowed_fields = ['field_63aec22dbd07f', 'field_6618e558ba653'];
        
        if (!in_array($field['key'], $allowed_fields)) {
            return $title;
        }
        
        $terms = wc_get_product_terms($post->ID, 'pa_color', ['fields' => 'names']);
        
        if (empty($terms) || is_wp_error($terms)) {
            return $title;
        }
        
        $colors = array_shift($terms);
        
        if ($colors && !empty($colors)) {
            $title .= ' [' . esc_html($colors) . ']';
        }
        
        return $title;
    }
    
    /**
     * Change default price HTML with improved formatting
     * 
     * @param string $price Current price HTML
     * @param object $product Product object
     * @return string Modified price HTML
     */
    public function change_price_html(string $price, object $product): string {
        if ($product->is_type('simple')) {
            $action_price = $product->get_price();
            $base_price = $product->get_sale_price();
        } elseif ($product->is_type('variable')) {
            $action_price = $product->get_variation_price();
            $base_price = $product->get_variation_regular_price();
        } else {
            return $price;
        }
        
        if ($action_price != $base_price && $base_price) {
            $price = '<div class="rs-product__price rs-product__price-old">' . wc_price($base_price) . '</div>';
            $price .= '<div class="rs-product__price rs-product__price-new">' . wc_price($action_price) . '</div>';
        } else {
            $price = '<div class="rs-product__price rs-product__price-new">' . $price . '</div>';
        }
        
        return $price;
    }
    
    /**
     * Filter search results and product archives
     * 
     * @param object $query WP Query object
     */
    public function search_filter(object $query): void {
        if (is_admin() || !$query->is_main_query()) {
            return;
        }
        
        $meta_query = [
            [
                'key' => '_price',
                'value' => 0,
                'compare' => '>',
                'type' => 'NUMERIC',
            ],
        ];
        
        // Search query
        if ($query->is_search) {
            $query->set('post_type', ['product']);
            $query->set('meta_query', $meta_query);
            return;
        }
        
        // Product archive query
        if (isset($query->query_vars['post_type']) && $query->query_vars['post_type'] === 'product') {
            $existing_meta_query = (array)$query->get('meta_query');
            $query->set('meta_query', array_merge($existing_meta_query, $meta_query));
        }
    }
    
    /**
     * Send email on specific order status change
     * 
     * @param int $order_id Order ID
     * @param string $status_from Previous status
     * @param string $status_to New status
     * @param object $order Order object
     */
    public function email_on_status_change(int $order_id, string $status_from, string $status_to, object $order): void {
        if ($status_to !== 'pay_au') {
            return;
        }
        
        $wc_email = WC()->mailer()->get_emails()['WC_Email_Customer_On_Hold_Order'] ?? null;
        
        if (!$wc_email) {
            return;
        }
        
        // Customize email settings
        $wc_email->settings['subject'] = __('{site_title} - Спасибо за заказ ({order_number}) - {order_date}');
        $wc_email->settings['heading'] = __('Спасибо за заказ');
        
        // Send email
        $wc_email->trigger($order_id);
    }
    
    /**
     * Sanitize shipping minimum amount
     * 
     * @param array $instance_settings Settings
     * @return array Modified settings
     */
    public function sanitize_shipping_min_amount(array $instance_settings, mixed $shipping_method = null): array {
        if (isset($instance_settings['min_amount'])) {
            $instance_settings['min_amount'] = preg_replace('/[^0-9]/', '', $instance_settings['min_amount']);
        }
        
        return $instance_settings;
    }
    
    /**
     * Add color to product title in admin order page
     * 
     * @param string $title Product title
     * @param int $post_id Post ID
     * @return string Modified title
     */
    public function add_color_to_product_title(string $title, int $post_id): string {
        if (!is_admin() || 
            !isset($_GET['post_type'], $_GET['page']) ||
            $_GET['post_type'] !== 'product' ||
            $_GET['page'] !== 'order-post-types-product') {
            return $title;
        }
        
        $colors = get_the_terms($post_id, 'pa_color');
        
        if ($colors && !is_wp_error($colors)) {
            $first_color = $colors[0]->name;
            $title .= ' - ' . esc_html($first_color);
        }
        
        return $title;
    }
    
    /**
     * Process product meta on import
     * 
     * @param int $id Post ID
     */
    public function process_product_meta(int $id): void {
        $quantities = get_post_meta($id, '_sizes_quantities', true);
        $json = json_decode($quantities, true);
        
        if (!is_array($json)) {
            return;
        }
        
        $sku = get_post_meta($id, '_sku', true);
        
        $args = [
            'post_parent' => $id, 
            'post_type' => 'product_variation', 
            'posts_per_page' => -1,
        ];
        
        $children = get_children($args, ARRAY_A);
        
        foreach ($children as $child) {
            $size = strtoupper(get_post_meta($child['ID'], 'attribute_pa_size', true));
            
            if (strlen($size) < 1) {
                continue;
            }
            
            update_post_meta($child['ID'], '_sku', $sku . '-' . $size);
            update_post_meta($child['ID'], '_visibility', in_array($size, array_keys($json)) ? 'visible' : 'hidden');
            update_post_meta($child['ID'], '_stock', $json[$size] ?? 0);
            update_post_meta($child['ID'], '_backorders', 'no');
        }
    }
    
    /**
     * Decrease order item stock on specific import
     * 
     * @param int $post_id Post ID
     * @param object $xml_node XML node
     * @param bool $is_update Is update
     */
    public function decrease_order_item_stock(int $post_id, mixed $xml_node, bool $is_update): void {
        $import_id = $_GET['id'] ?? $_GET['import_id'] ?? 'new';
        
        if (in_array($import_id, [3])) {
            wc_reduce_stock_levels($post_id);
        }
    }
    
    /**
     * Case insensitive coupon search
     * 
     * @param mixed $data Coupon data
     * @param string $code Coupon code
     * @return mixed Modified coupon data
     */
    public function coupon_case_insensitive_search(mixed $data, string $code): mixed {
        if (!empty($data)) {
            return $data;
        }
        
        global $wpdb;
        
        $coupon_post = $wpdb->get_row($wpdb->prepare("
            SELECT * FROM {$wpdb->posts} 
            WHERE post_type = 'shop_coupon' 
            AND LOWER(post_title) = LOWER(%s) 
            AND post_status = 'publish'
            LIMIT 1
        ", $code));
        
        if ($coupon_post) {
            $coupon = new \WC_Coupon($coupon_post->ID);
            return $coupon->get_data();
        }
        
        return $data;
    }
    
    /**
     * Add low stock badge checkbox field
     */
    public function add_low_stock_badge_field(): void {
        global $product_object;
        
        if (!$product_object) {
            return;
        }
        
        $current_value = get_post_meta($product_object->get_id(), '_show_low_stock_badge', true);
        
        if ($current_value === '') {
            $current_value = 'yes';
        }
        
        echo '<div class="options_group show_if_simple show_if_variable">';
        
        woocommerce_wp_checkbox([
            'id' => '_show_low_stock_badge',
            'label' => __('Показывать "Скоро закончится"', 'woocommerce'),
            'description' => __('Показывать плашку "Скоро закончится" когда остаток меньше 50 единиц', 'woocommerce'),
            'desc_tip' => true,
            'value' => $current_value,
        ]);
        
        echo '</div>';
    }
    
    /**
     * Save low stock badge checkbox field
     * 
     * @param int $post_id Post ID
     */
    public function save_low_stock_badge_field(int $post_id): void {
        $checkbox_value = isset($_POST['_show_low_stock_badge']) && $_POST['_show_low_stock_badge'] === 'yes' ? 'yes' : 'no';
        update_post_meta($post_id, '_show_low_stock_badge', $checkbox_value);
    }
    
    /**
     * Set default low stock badge value for new products
     * 
     * @param int $product_id Product ID
     */
    public function set_default_low_stock_badge(int $product_id): void {
        $current_value = get_post_meta($product_id, '_show_low_stock_badge', true);
        
        if ($current_value === '') {
            update_post_meta($product_id, '_show_low_stock_badge', 'yes');
        }
    }
    
    /**
     * Add manual out of stock checkbox field
     */
    public function add_manual_out_of_stock_field(): void {
        global $product_object;
        
        if (!$product_object) {
            return;
        }
        
        $current_value = get_post_meta($product_object->get_id(), '_manual_out_of_stock', true);
        
        echo '<div class="options_group show_if_simple show_if_variable">';
        
        woocommerce_wp_checkbox([
            'id' => '_manual_out_of_stock',
            'label' => __('Нет в наличии', 'woocommerce'),
            'description' => __('Отметьте, чтобы сделать товар недоступным для покупки (как будто закончился)', 'woocommerce'),
            'desc_tip' => true,
            'value' => $current_value,
        ]);
        
        echo '</div>';
    }
    
    /**
     * Save manual out of stock checkbox field
     * 
     * @param int $post_id Post ID
     */
    public function save_manual_out_of_stock_field(int $post_id): void {
        $checkbox_value = isset($_POST['_manual_out_of_stock']) && $_POST['_manual_out_of_stock'] === 'yes' ? 'yes' : 'no';
        update_post_meta($post_id, '_manual_out_of_stock', $checkbox_value);
    }
    
    /**
     * Check if product is manually marked as out of stock
     * 
     * @param bool $is_in_stock Current stock status
     * @param object $product Product object
     * @return bool Modified stock status
     */
    public function check_manual_out_of_stock(bool $is_in_stock, object $product): bool {
        if (!$product) {
            return $is_in_stock;
        }
        
        $manual_out_of_stock = get_post_meta($product->get_id(), '_manual_out_of_stock', true);
        
        if ($manual_out_of_stock === 'yes') {
            return false;
        }
        
        return $is_in_stock;
    }
    
    /**
     * Get manual stock status for product
     * 
     * @param string $stock_status Current stock status
     * @param object $product Product object
     * @return string Modified stock status
     */
    public function get_manual_stock_status(string $stock_status, object $product): string {
        if (!$product) {
            return $stock_status;
        }
        
        $manual_out_of_stock = get_post_meta($product->get_id(), '_manual_out_of_stock', true);
        
        if ($manual_out_of_stock === 'yes') {
            return 'outofstock';
        }
        
        return $stock_status;
    }
}
