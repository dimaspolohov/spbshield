<?php
declare(strict_types=1);

/**
 * Custom Block Post Type
 * 
 * Registers custom_block post type for theme blocks
 * 
 * @package SpbShield
 * @since 1.0.0
 */

namespace SpbShield\Inc;

class CustomBlockPostType {
    
    /**
     * Post type slug
     */
    private const POST_TYPE = 'custom_block';
    
    /**
     * Constructor - Register hooks
     */
    public function __construct() {
        add_action('init', [$this, 'register_post_type']);
        add_filter('post_type_labels_' . self::POST_TYPE, [$this, 'customize_labels']);
    }
    
    /**
     * Register custom block post type
     */
    public function register_post_type(): void {
        register_post_type(self::POST_TYPE, [
            'public' => true,
            'label' => 'Блок',
            'publicly_queryable' => true,
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'supports' => ['title', 'thumbnail'],
            'taxonomies' => ['post_tag'],
            'menu_icon' => 'dashicons-welcome-widgets-menus',
            'exclude_from_search' => true,
            'labels' => ['add_new_item' => 'Добавить блок'],
        ]);
    }
    
    /**
     * Customize post type labels
     * 
     * @param mixed $labels Current labels
     * @return object Modified labels
     */
    public function customize_labels(mixed $labels): object {
        $new_labels = [
            'name' => 'Блоки',
            'singular_name' => 'Блок',
            'add_new' => 'Добавить блок',
            'add_new_item' => 'Добавить блок',
            'edit_item' => 'Редактировать блок',
            'new_item' => 'Новый блок',
            'view_item' => 'Просмотреть блок',
            'search_items' => 'Поиск блоков',
            'not_found' => 'Блоков не найдено.',
            'not_found_in_trash' => 'Блоков в корзине не найдено.',
            'parent_item_colon' => '',
            'all_items' => 'Все блоки',
            'archives' => 'Архивы блоков',
            'insert_into_item' => 'Вставить в блок',
            'uploaded_to_this_item' => 'Загруженные для этого блока',
            'featured_image' => 'Миниатюра блока',
            'filter_items_list' => 'Фильтровать список блоков',
            'items_list_navigation' => 'Навигация по списку блоков',
            'items_list' => 'Список блоков',
            'menu_name' => 'Блоки',
            'name_admin_bar' => 'Блоки',
        ];
        
        $existing = is_object($labels) ? (array) $labels : [];
        
        return (object) array_merge($existing, $new_labels);
    }
}
