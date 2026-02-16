<?php
declare(strict_types=1);

/**
 * Template Post Types Helper
 * 
 * Helper class for registering post types from template-parts
 * 
 * @package SpbShield
 * @since 1.0.0
 */

namespace SpbShield\Inc;

class TemplatePostTypes {
    
    /**
     * Register custom post type (legacy helper replacement)
     * 
     * @param string $name Post type name
     * @param string $label Post type label
     * @param array<string, mixed> $args Additional arguments
     */
    public static function register(string $name, string $label, array $args = []): void {
        add_action('init', function() use($name, $label, $args) {
            $name = strtolower(str_replace(' ', '_', $name));
            
            $default_args = [
                'public' => true,
                'label' => $label,
                'publicly_queryable' => true,
                'show_ui' => true,
                'query_var' => true,
                'capability_type' => 'post',
                'has_archive' => true,
                'labels' => ['add_new_item' => 'Add New'],
                'supports' => ['comments', 'title', 'editor', 'excerpt', 'thumbnail'],
                'taxonomies' => ['post_tag', 'category'],
            ];
            
            $args = array_merge($default_args, $args);
            register_post_type($name, $args);
        });
    }
    
    /**
     * Register custom taxonomy (legacy helper replacement)
     * 
     * @param string $name Taxonomy name
     * @param string $label Taxonomy label
     * @param string|array<int, string> $post_type Post type(s)
     * @param array<string, mixed> $args Additional arguments
     */
    public static function register_taxonomy(string $name, string $label, string|array $post_type, array $args = []): void {
        $name = strtolower($name);
        add_action('init', function() use($name, $label, $post_type, $args) {
            register_taxonomy($name, $post_type, [
                'hierarchical' => true,
                'label' => __($label),
                'singular_label' => $name,
                'query_var' => $name
            ]);
        }, 0);
    }
}
