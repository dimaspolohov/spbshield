<?php
declare(strict_types=1);

/**
 * Custom Post Types Registration
 * 
 * Handles registration of all custom post types for the theme
 * 
 * @package SpbShield
 * @since 1.0.0
 */

namespace SpbShield\Inc;

class PostTypes {
    
    /**
     * Post type configuration cache
     */
    private const POST_TYPE_ARGS = [
        'size_guide' => [
            'labels' => [
                'name' => 'Гид по размерам',
                'singular_name' => 'Гид по размерам',
                'add_new' => 'Добавить гид',
                'add_new_item' => 'Добавить гид',
                'edit' => 'Изменить гид',
                'edit_item' => 'Гид по размерам',
                'new_item' => 'Новый гид',
                'view' => 'Показать гид',
                'view_item' => 'Показать гид',
                'search_items' => 'Искать гид',
                'not_found' => 'Гид не найден',
                'not_found_in_trash' => 'Гид не найден в корзине',
                'parent' => 'Родительский гид',
            ],
            'taxonomies' => [],
            'publicly_queryable' => false,
            'public' => true,
            'menu_position' => 4,
            'supports' => ['title', 'revisions'],
            'menu_icon' => 'dashicons-move',
            'has_archive' => false,
        ],
        'news' => [
            'labels' => [
                'name' => 'Новости',
                'singular_name' => 'Новости',
                'add_new' => 'Добавить новость',
                'add_new_item' => 'Добавить новость',
                'edit' => 'Изменить новость',
                'edit_item' => 'Новость',
                'new_item' => 'Новость',
                'view' => 'Показать новости',
                'view_item' => 'Показать новость',
                'search_items' => 'Искать новости',
                'not_found' => 'Новости не найдены',
                'not_found_in_trash' => 'Новости не найдены в корзине',
                'parent' => 'Родительская новость',
            ],
            'taxonomies' => ['post_tag'],
            'publicly_queryable' => true,
            'public' => true,
            'menu_position' => 4,
            'supports' => ['title', 'revisions', 'thumbnail', 'editor'],
            'menu_icon' => 'dashicons-screenoptions',
            'has_archive' => true,
        ],
        'collections' => [
            'labels' => [
                'name' => 'Collections',
                'singular_name' => 'Коллекция',
                'add_new' => 'Добавить коллекцию',
                'add_new_item' => 'Добавить коллекцию',
                'edit' => 'Изменить коллекцию',
                'edit_item' => 'Коллекция',
                'new_item' => 'Новая коллекция',
                'view' => 'Показать коллекцию',
                'view_item' => 'Показать коллекцию',
                'search_items' => 'Искать коллекцию',
                'not_found' => 'Коллекции не найдены',
                'not_found_in_trash' => 'Коллекции не найдены в корзине',
                'parent' => 'Родительская коллекцию',
            ],
            'taxonomies' => [],
            'publicly_queryable' => true,
            'public' => true,
            'menu_position' => 4,
            'supports' => ['title', 'revisions', 'thumbnail', 'editor', 'excerpt', 'page-attributes'],
            'menu_icon' => 'dashicons-screenoptions',
            'has_archive' => true,
        ],
    ];
    
    /**
     * Constructor - Register hooks
     */
    public function __construct() {
        add_action('init', [$this, 'register_all_post_types']);
    }
    
    /**
     * Register all post types at once
     */
    public function register_all_post_types(): void {
        foreach (self::POST_TYPE_ARGS as $post_type => $args) {
            register_post_type($post_type, $args);
        }
    }
}
