<?php
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
        // Disable pingback
        add_filter('wp_headers', [$this, 'remove_pingback_header']);
        add_filter('template_redirect', [$this, 'remove_x_pingback_headers']);
        add_filter('xmlrpc_methods', [$this, 'block_xmlrpc_attacks']);
        add_filter('xmlrpc_enabled', '__return_false');
        
        // Remove various WordPress meta tags
        $this->remove_wp_meta_tags();
        
        // Remove WordPress version number
        add_filter('the_generator', '__return_false');
    }
    
    /**
     * Remove WordPress meta tags from head
     */
    private function remove_wp_meta_tags(): void {
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'rest_output_link_wp_head', 10);
        remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
        remove_action('template_redirect', 'rest_output_link_header', 11, 0);
    }
    
    /**
     * Remove pingback header
     * 
     * @param array $headers HTTP headers
     * @return array Modified headers
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
     * @param array $methods XML-RPC methods
     * @return array Modified methods
     */
    public function block_xmlrpc_attacks(array $methods): array {
        unset($methods['pingback.ping'], $methods['pingback.extensions.getPingbacks']);
        return $methods;
    }
}
