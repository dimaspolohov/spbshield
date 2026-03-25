<?php
declare(strict_types=1);

namespace SpbShield\Inc;

/**
 * Theme configuration constants
 *
 * Centralizes hardcoded IDs and configuration values
 * that were previously scattered across template files
 */
class ThemeConfig {

    // Contact Form 7 IDs
    const CF7_MODAL_FORM_ID = 2255;

    // Custom block IDs used in WP_Query for ACF blocks
    const BLOCK_ID_CATALOG = 2;
    const BLOCK_ID_CATALOG_2 = 25;
    const BLOCK_ID_CATALOG_3 = 33;
    const BLOCK_ID_NEWS = 5;
    const BLOCK_ID_COLLECTION = 62;

    // WooCommerce page / category IDs
    const SHOP_PAGE_ID = 5;
    const STRUCTURE_MENU_ID = 493;
    const NEW_CATEGORY_ID = 244;

    // WooCommerce custom block IDs
    const BLOCK_ID_CART_TOGGLE = 32;
    const BLOCK_ID_AGREEMENT = 16;

    // Mobile menu options page ID
    const MOBILE_MENU_OPTIONS_ID = 1910;

    /**
     * Get CF7 shortcode for modal form
     */
    public static function get_modal_form_shortcode(): string {
        return '[contact-form-7 id="' . self::CF7_MODAL_FORM_ID . '" title="Modal form"]';
    }

    /**
     * Query custom block by block_id
     */
    public static function get_custom_block(int $block_id): ?\WP_Post {
        $query = new \WP_Query([
            'post_type' => 'custom_block',
            'meta_query' => [
                [
                    'key' => 'block_id',
                    'value' => $block_id,
                    'compare' => '=',
                ],
            ],
            'posts_per_page' => 1,
        ]);

        if ($query->have_posts()) {
            $query->the_post();
            $post = get_post();
            wp_reset_postdata();
            return $post;
        }

        return null;
    }
}
