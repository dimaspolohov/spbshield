<?php
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
     * Constructor - Register hooks
     */
    public function __construct() {
        add_action('init', [$this, 'register_size_guide']);
        add_action('init', [$this, 'register_news']);
        add_action('init', [$this, 'register_collections']);
    }
    
    /**
     * Register Size Guide post type
     */
    public function register_size_guide(): void {
        register_post_type('size_guide', $this->get_size_guide_args());
    }
    
    /**
     * Register News post type
     */
    public function register_news(): void {
        register_post_type('news', $this->get_news_args());
    }
    
    /**
     * Register Collections post type
     */
    public function register_collections(): void {
        register_post_type('collections', $this->get_collections_args());
    }
    
    /**
     * Register Media post type (currently commented out in original code)
     * Uncomment if needed
     */
    public function register_media(): void {
        register_post_type('media', $this->get_media_args());
    }
    
    /**
     * Get Size Guide post type arguments
     * 
     * @return array Post type arguments
     */
    private function get_size_guide_args(): array {
        return [
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
        ];
    }
    
    /**
     * Get News post type arguments
     * 
     * @return array Post type arguments
     */
    private function get_news_args(): array {
        return [
            'labels' => [
                'name' => __('Новости', 'storefront'),
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
        ];
    }
    
    /**
     * Get Collections post type arguments
     * 
     * @return array Post type arguments
     */
    private function get_collections_args(): array {
        return [
            'labels' => [
                'name' => __('Collections', 'storefront'),
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
        ];
    }
    
    /**
     * Get Media post type arguments
     * 
     * @return array Post type arguments
     */
    private function get_media_args(): array {
        return [
            'labels' => [
                'name' => __('Media', 'storefront'),
                'singular_name' => 'Медиа',
                'add_new' => 'Добавить медиа',
                'add_new_item' => 'Добавить медиа',
                'edit' => 'Изменить медиа',
                'edit_item' => 'Медиа',
                'new_item' => 'Новое коллекция',
                'view' => 'Показать медиа',
                'view_item' => 'Показать медиа',
                'search_items' => 'Искать медиа',
                'not_found' => 'Медиа не найдены',
                'not_found_in_trash' => 'Медиа не найдены в корзине',
                'parent' => 'Родительское медиа',
            ],
            'taxonomies' => ['post_tag'],
            'publicly_queryable' => true,
            'public' => true,
            'menu_position' => 4,
            'supports' => ['title', 'revisions', 'thumbnail', 'editor'],
            'menu_icon' => 'dashicons-screenoptions',
            'has_archive' => true,
        ];
    }
}
