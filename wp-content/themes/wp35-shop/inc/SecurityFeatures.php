<?php
declare(strict_types=1);

/**
 * Security Features
 * 
 * Handles security-related filters and hardening
 * 
 * @package SpbShield
 * @since 1.0.0
 */

namespace SpbShield\Inc;

class SecurityFeatures {
    
    /**
     * Constructor - Register hooks
     */
    public function __construct() {
        // Disable pingback and XML-RPC
        add_filter('wp_headers', [$this, 'remove_pingback_header']);
        add_action('template_redirect', [$this, 'remove_x_pingback_headers']);
        add_filter('xmlrpc_methods', [$this, 'block_xmlrpc_attacks']);
        add_filter('xmlrpc_enabled', '__return_false');
        
        // Remove WordPress meta tags
        $this->remove_wp_meta_tags();
        
        // Remove WordPress version number
        add_filter('the_generator', '__return_false');
    }
    
    /**
     * Remove WordPress meta tags from head
     */
    private function remove_wp_meta_tags(): void {
        $actions_to_remove = [
            ['wp_head', 'rsd_link'],
            ['wp_head', 'wlwmanifest_link'],
            ['wp_head', 'wp_shortlink_wp_head'],
            ['wp_head', 'rest_output_link_wp_head', 10],
            ['wp_head', 'wp_oembed_add_discovery_links', 10],
            ['template_redirect', 'rest_output_link_header', 11],
        ];
        
        foreach ($actions_to_remove as $action) {
            remove_action(...$action);
        }
    }
    
    /**
     * Remove pingback header
     * 
     * @param array<string, mixed> $headers HTTP headers
     * @return array<string, mixed> Modified headers
     */
    public function remove_pingback_header(array $headers): array {
        unset($headers['X-Pingback']);
        return $headers;
    }
    
    /**
     * Remove X-Pingback and Server headers
     */
    public function remove_x_pingback_headers(): void {
        if (function_exists('header_remove')) {
            header_remove('X-Pingback');
            header_remove('Server');
        }
    }
    
    /**
     * Block XML-RPC pingback attacks
     * 
     * @param array<string, callable> $methods XML-RPC methods
     * @return array<string, callable> Modified methods
     */
    public function block_xmlrpc_attacks(array $methods): array {
        unset($methods['pingback.ping'], $methods['pingback.extensions.getPingbacks']);
        return $methods;
    }
}
