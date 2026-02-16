<?php
declare(strict_types=1);

/**
 * Store Availability Management
 * 
 * Handles CSV parsing and store availability checks
 * 
 * @package SpbShield
 * @since 1.0.0
 */

namespace SpbShield\Inc;

class StoreAvailability {
    
    /**
     * CSV cache key
     */
    private const CACHE_KEY = 'gorokhovaya_csv_data';
    
    /**
     * Cache time in seconds (5 minutes)
     */
    private const CACHE_TIME = 300;
    
    /**
     * CSV URL
     */
    private const CSV_URL = 'https://bazar.mostech.ru/trueshituchet/libroot/integration/misim/?id_shop=17&password=clientSystem';
    
    /**
     * Known stores with their information
     * 
     * @var array<string, array{address: string, phone: string, coords: array<int, float>}>
     */
    private const KNOWN_STORES = [
        'Гороховая 49' => [
            'address' => 'ПН-ВС: с 12:00 до 21:00',
            'phone' => '',
            'coords' => [59.926917, 30.322906],
        ],
        'Каменоостровский 32' => [
            'address' => 'ПН-ВС: с 12:00 до 21:00',
            'phone' => '',
            'coords' => [59.963964, 30.313003],
        ],
    ];
    
    /**
     * Constructor - Register hooks
     */
    public function __construct() {
        $this->register_ajax_actions();
        $this->register_hooks();
    }
    
    /**
     * Register AJAX actions
     */
    private function register_ajax_actions(): void {
        $actions = [
            'get_gorokhovaya_csv_availability' => 'get_availability',
            'check_product_stock' => 'check_stock',
            'get_store_availability_oos' => 'get_availability_oos',
        ];
        
        foreach ($actions as $action => $method) {
            add_action("wp_ajax_{$action}", [$this, $method]);
            add_action("wp_ajax_nopriv_{$action}", [$this, $method]);
        }
    }
    
    /**
     * Register other hooks
     */
    private function register_hooks(): void {
        // Clear cache on product update
        add_action('woocommerce_update_product', [$this, 'clear_cache']);
        add_action('woocommerce_new_product', [$this, 'clear_cache']);
    }
    
    /**
     * Get cached CSV data with timeout and error handling
     * 
     * @return string|null CSV content or null on failure
     */
    public function get_cached_csv_data(): ?string {
        $cached = get_transient(self::CACHE_KEY);
        
        if ($cached !== false) {
            return $cached;
        }
        
        $response = wp_remote_get(self::CSV_URL, [
            'timeout' => 20,
            'headers' => ['User-Agent' => 'Mozilla/5.0']
        ]);
        
        if (is_wp_error($response)) {
            return null;
        }
        
        $body = wp_remote_retrieve_body($response);
        
        if (empty($body)) {
            return null;
        }
        
        set_transient(self::CACHE_KEY, $body, self::CACHE_TIME);
        
        return $body;
    }
    
    /**
     * Parse CSV for specific SKU with improved error handling
     * 
     * @param string $csv_body CSV content
     * @param string $sku Product SKU
     * @return array|null Parsed data or null if not found
     */
    public function parse_csv_for_sku(string $csv_body, string $sku): ?array {
        if (empty($csv_body) || empty($sku)) {
            return null;
        }
        
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $csv_body);
        rewind($stream);
        
        $headers = fgetcsv($stream, 0, ';');
        
        if (!$headers) {
            fclose($stream);
            return null;
        }
        
        $sku_normalized = trim($sku);
        
        while (($row = fgetcsv($stream, 0, ';')) !== false) {
            if (count($row) !== count($headers)) {
                continue;
            }
            
            $item = array_combine($headers, $row);
            
            if (isset($item['article']) && trim($item['article']) === $sku_normalized) {
                $result = $this->parse_csv_row($item);
                fclose($stream);
                return $result;
            }
        }
        
        fclose($stream);
        return null;
    }
    
    /**
     * Parse CSV row data
     * 
     * @param array<string, string> $item CSV row data
     * @return array{sizes_total: array<string, int>, stores_sizes: array<string, array<string, int>>} Parsed sizes and stores data
     */
    private function parse_csv_row(array $item): array {
        $sizes_total = [];
        $stores_sizes = [];
        
        foreach ($item as $col_val) {
            if (!is_string($col_val) || empty($col_val) || $col_val[0] !== '{') {
                continue;
            }
            
            $decoded = json_decode($col_val, true);
            
            if (!is_array($decoded) || empty($decoded)) {
                continue;
            }
            
            $first_val = reset($decoded);
            
            if (is_numeric($first_val)) {
                // Total stock by sizes
                foreach ($decoded as $k => $v) {
                    $sizes_total[strtoupper((string) $k)] = (int) $v;
                }
            } elseif (is_array($first_val)) {
                // Stock by stores - optimized version
                foreach ($decoded as $store_name => $sizes_arr) {
                    if (!is_array($sizes_arr)) {
                        continue;
                    }
                    
                    $store_sizes = [];
                    foreach ($sizes_arr as $size_key => $qty) {
                        $store_sizes[strtoupper((string) $size_key)] = (int) $qty;
                    }
                    
                    $stores_sizes[(string) $store_name] = $store_sizes;
                }
            }
        }
        
        return [
            'sizes_total' => $sizes_total,
            'stores_sizes' => $stores_sizes
        ];
    }
    
    /**
     * Match store name with CSV store name
     * 
     * @param string $known_store Known store name
     * @param string $csv_store CSV store name
     * @return bool True if matched
     */
    private function match_store_names(string $known_store, string $csv_store): bool {
        $normalized_known = mb_strtolower(trim($known_store));
        $normalized_csv = mb_strtolower(trim($csv_store));
        
        return strpos($normalized_csv, $normalized_known) !== false || 
               strpos($normalized_known, $normalized_csv) !== false;
    }
    
    /**
     * Get store quantity for specific size
     * 
     * @param array $stores_sizes Stores sizes data
     * @param string $store_name Store name
     * @param string $size Size (optional)
     * @return int Quantity
     */
    private function get_store_quantity(array $stores_sizes, string $store_name, string $size = ''): int {
        $normalized_known = mb_strtolower(trim($store_name));
        
        foreach ($stores_sizes as $csv_store_name => $sizes_by_store) {
            if (!$this->match_store_names($store_name, $csv_store_name)) {
                continue;
            }
            
            if (!empty($size) && isset($sizes_by_store[$size])) {
                return intval($sizes_by_store[$size]);
            }
            
            if (empty($size) && is_array($sizes_by_store)) {
                return array_sum(array_map('intval', $sizes_by_store));
            }
        }
        
        return 0;
    }
    
    /**
     * AJAX: Get availability for popup
     */
    public function get_availability(): never {
        $product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
        $product = wc_get_product($product_id);
        
        if (!$product || !$product instanceof \WC_Product) {
            wp_send_json_error('Product not found');
        }
        
        $sku = $product->get_sku();
        
        if (empty($sku)) {
            wp_send_json_success(['stores' => []]);
        }
        
        $csv_body = $this->get_cached_csv_data();
        $parsed = $this->parse_csv_for_sku($csv_body ?? '', $sku);
        
        $stores_sizes = $parsed['stores_sizes'] ?? [];
        $selected_size = isset($_POST['size']) ? strtoupper(sanitize_text_field($_POST['size'])) : '';
        
        $stores = [];
        
        foreach (self::KNOWN_STORES as $store_name => $meta) {
            $qty = $this->get_store_quantity($stores_sizes, $store_name, $selected_size);
            
            $stores[] = [
                'store' => $store_name,
                'address' => $meta['address'],
                'phone' => $meta['phone'],
                'quantity' => $qty > 0 ? 'В наличии' : 'Нет в наличии',
                'status' => $qty > 0 ? 'in-stock' : 'out-of-stock',
                'coords' => $meta['coords'],
            ];
        }
        
        wp_send_json_success(['stores' => $stores]);
    }
    
    /**
     * AJAX: Check stock for button text
     */
    public function check_stock(): never {
        $product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
        $selected_size = isset($_POST['size']) ? strtoupper(sanitize_text_field($_POST['size'])) : '';
        
        $product = wc_get_product($product_id);
        
        if (!$product || !$product instanceof \WC_Product) {
            wp_send_json_error('Product not found');
        }
        
        $sku = $product->get_sku();
        
        if (empty($sku)) {
            wp_send_json_success(['in_stock' => false, 'store_count' => 0]);
        }
        
        $csv_body = $this->get_cached_csv_data();
        $parsed = $this->parse_csv_for_sku($csv_body ?? '', $sku);
        
        if (!$parsed) {
            wp_send_json_success(['in_stock' => false, 'store_count' => 0]);
        }
        
        $sizes_total = $parsed['sizes_total'];
        $stores_sizes = $parsed['stores_sizes'];
        
        // Check total stock
        $total_stock = 0;
        
        if (!empty($selected_size) && isset($sizes_total[$selected_size])) {
            $total_stock = $sizes_total[$selected_size];
        } elseif (empty($selected_size) && !empty($sizes_total)) {
            $total_stock = array_sum($sizes_total);
        }
        
        // Count stores
        $store_count = 0;
        
        foreach (self::KNOWN_STORES as $store_name => $meta) {
            $qty = $this->get_store_quantity($stores_sizes, $store_name, $selected_size);
            
            if ($qty > 0) {
                $store_count++;
            }
        }
        
        wp_send_json_success([
            'in_stock' => $total_stock > 0,
            'store_count' => $store_count,
            'total_stock' => $total_stock
        ]);
    }
    
    /**
     * AJAX: Get availability for out of stock products
     */
    public function get_availability_oos(): never {
        $product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
        $product = wc_get_product($product_id);
        
        if (!$product || !$product instanceof \WC_Product) {
            wp_send_json_error('Product not found');
        }
        
        $sku = $product->get_sku();
        
        if (empty($sku)) {
            wp_send_json_success(['stores' => []]);
        }
        
        $csv_body = $this->get_cached_csv_data();
        $parsed = $this->parse_csv_for_sku($csv_body ?? '', $sku);
        
        $stores_sizes = $parsed['stores_sizes'] ?? [];
        $stores = [];
        
        foreach (self::KNOWN_STORES as $store_name => $meta) {
            $qty = $this->get_store_quantity($stores_sizes, $store_name);
            
            $stores[] = [
                'store' => $store_name,
                'quantity' => $qty > 0 ? 'В наличии' : 'Нет в наличии',
                'address' => $meta['address'],
                'phone' => $meta['phone'],
                'coords' => $meta['coords'],
                'status' => $qty > 0 ? 'in-stock' : 'out-of-stock',
            ];
        }
        
        wp_send_json_success(['stores' => $stores]);
    }
    
    /**
     * Check if product has store availability
     * 
     * @param \WC_Product|false|null $product WooCommerce product
     * @return bool True if product has store availability
     */
    public function product_has_store_availability(\WC_Product|false|null $product): bool {
        if (!$product instanceof \WC_Product) {
            return false;
        }
        
        $sku = $product->get_sku();
        
        if (empty($sku)) {
            return false;
        }
        
        $csv_body = $this->get_cached_csv_data();
        
        if (empty($csv_body)) {
            return false;
        }
        
        $parsed = $this->parse_csv_for_sku($csv_body, $sku);
        
        if (!$parsed || empty($parsed['stores_sizes'])) {
            return false;
        }
        
        // Check if any store has any size in stock
        foreach ($parsed['stores_sizes'] as $sizes_by_store) {
            if (!is_array($sizes_by_store)) {
                continue;
            }
            
            foreach ($sizes_by_store as $qty) {
                if ((int) $qty > 0) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Clear CSV cache
     */
    public function clear_cache(): void {
        delete_transient(self::CACHE_KEY);
    }
}
