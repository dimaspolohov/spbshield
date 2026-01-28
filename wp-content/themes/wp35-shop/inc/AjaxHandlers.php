<?php
declare(strict_types=1);

/**
 * AJAX Handlers
 * 
 * Handles all AJAX requests for the theme
 * 
 * @package SpbShield
 * @since 1.0.0
 */

namespace SpbShield\Inc;

class AjaxHandlers {
    
    /**
     * Allowed image types for upload
     */
    private const ALLOWED_IMAGE_TYPES = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
    
    /**
     * Constructor - Register AJAX hooks
     */
    public function __construct() {
        $this->register_ajax_actions();
    }
    
    /**
     * Register all AJAX actions
     */
    private function register_ajax_actions(): void {
        $actions = [
            'rs_clients_form_function' => 'clients_form',
            'file_upload' => 'file_upload',
            'rs_store_form_function' => 'store_form',
            'getProducts' => 'get_products',
            'getCollections' => 'get_collections',
            'getMediaitems' => 'get_media_items',
            'getVariationGal' => 'get_variation_gallery',
            'setWishlist' => 'set_wishlist',
        ];
        
        foreach ($actions as $action => $method) {
            add_action("wp_ajax_{$action}", [$this, $method]);
            add_action("wp_ajax_nopriv_{$action}", [$this, $method]);
        }
    }
    
    /**
     * Verify AJAX request
     * 
     * @return bool
     */
    private function verify_ajax(): bool {
        return defined('DOING_AJAX') && DOING_AJAX;
    }
    
    /**
     * Send email
     * 
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $message Email message
     * @param array $attachments Optional attachments
     */
    private function send_email(string $to, string $subject, string $message, array $attachments = []): void {
        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: No Reply <noreply@' . $_SERVER['SERVER_NAME'] . '>'
        ];
        
        wp_mail($to, $subject, $message, $headers, $attachments);
    }
    
    /**
     * Get email message footer
     * 
     * @return string
     */
    private function get_email_footer(): string {
        return '<br><i>' . __('With great respect, <br>the administration of the site', 'storefront') . 
               ' «' . get_bloginfo('name') . '»</i>';
    }
    
    /**
     * Handle client form submission
     */
    public function clients_form(): void {
        if (!$this->verify_ajax()) {
            wp_die();
        }
        
        $to = get_field('email_form', intval($_POST['page_id']));
        $subject = __('New message from', 'storefront') . ' «' . get_bloginfo('name') . '»';
        
        $message = '<strong>' . __('Your name', 'storefront') . ':</strong> ' . sanitize_text_field($_POST['name']) . '<br>';
        $message .= '<strong>' . __('E-mail', 'storefront') . ':</strong> ' . sanitize_email($_POST['email']) . '<br>';
        
        if (!empty($_POST['message'])) {
            $message .= '<br>' . wp_kses_post($_POST['message']) . '<br>';
        }
        
        $message .= $this->get_email_footer();
        
        $this->send_email($to, $subject, $message);
        wp_die();
    }
    
    /**
     * Handle file upload
     */
    public function file_upload(): void {
        $files = [];
        
        if (!isset($_FILES['file']['name'])) {
            echo '';
            wp_die();
        }
        
        $file_count = count($_FILES['file']['name']);
        
        for ($i = 0; $i < $file_count; $i++) {
            if (!in_array($_FILES['file']['type'][$i], self::ALLOWED_IMAGE_TYPES)) {
                continue;
            }
            
            $upload = wp_upload_bits(
                sanitize_file_name($_FILES['file']['name'][$i]), 
                null, 
                file_get_contents($_FILES['file']['tmp_name'][$i])
            );
            
            if (!$upload['error']) {
                $files[] = $upload['file'];
            }
        }
        
        echo implode('|', $files);
        wp_die();
    }
    
    /**
     * Handle store form submission
     */
    public function store_form(): void {
        if (!$this->verify_ajax()) {
            wp_die();
        }
        
        $uploaded_files = !empty($_POST['attachment']) ? explode('|', $_POST['attachment']) : [];
        
        $to = get_field('email_form', intval($_POST['page_id']));
        $subject = __('New message from', 'storefront') . ' «' . get_bloginfo('name') . '»';
        
        $message = '<strong>' . __('Your name', 'storefront') . ':</strong> ' . sanitize_text_field($_POST['name']) . '<br>';
        $message .= '<strong>' . __('E-mail', 'storefront') . ':</strong> ' . sanitize_email($_POST['email']) . '<br>';
        $message .= '<strong>' . __('Category', 'storefront') . ':</strong> ' . sanitize_text_field($_POST['category']) . '<br>';
        
        if (!empty($_POST['message'])) {
            $message .= '<br>' . wp_kses_post($_POST['message']) . '<br>';
        }
        
        $message .= $this->get_email_footer();
        
        // Validate files exist
        $valid_files = array_filter($uploaded_files, 'file_exists');
        
        if (!empty($valid_files)) {
            $this->send_email($to, $subject, $message, $valid_files);
            
            // Cleanup uploaded files
            array_walk($valid_files, function($file) {
                @unlink($file);
            });
        } else {
            $this->send_email($to, $subject, $message);
        }
        
        wp_die();
    }
    
    /**
     * Get products via AJAX
     */
    public function get_products(): void {
        if (!$this->verify_ajax()) {
            wp_die();
        }
        
        wp_reset_postdata();
        
        $params = $this->get_product_params();
        $args = $this->build_product_query($params);
        
        $my_posts = get_posts($args);
        
        if ($params['orderby'] === 'menu_order') {
            $my_posts = array_reverse($my_posts);
        }
        
        ob_start();
        $this->render_products($my_posts, $params);
        $output1 = ob_get_clean();
        
        ob_start();
        $this->render_breadcrumbs($params['term_id']);
        $output2 = ob_get_clean();
        
        echo json_encode([$output1, $output2, $params['offset_new'], $params['ids'], $args['orderby']]);
        wp_die();
    }
    
    /**
     * Get product parameters from POST
     * 
     * @return array
     */
    private function get_product_params(): array {
        return [
            'term_id' => !empty($_POST['term_id']) ? intval($_POST['term_id']) : 0,
            'offset' => intval($_POST['offset']),
            'number' => intval($_POST['number']),
            'orderby' => sanitize_text_field($_POST['orderby']),
            'color' => sanitize_text_field($_POST['color']),
            'size' => sanitize_text_field($_POST['size']),
            'offset_new' => 0,
            'ids' => '',
        ];
    }
    
    /**
     * Build product query arguments
     * 
     * @param array $params Query parameters
     * @return array WP_Query arguments
     */
    private function build_product_query(array $params): array {
        $args = [
            'numberposts' => 49,
            'offset' => $params['offset'],
            'post_type' => 'product',
            'meta_query' => [
                [
                    'key' => '_price',
                    'value' => 0,
                    'compare' => '>',
                    'type' => 'NUMERIC',
                ],
            ],
        ];
        
        // Taxonomy query
        $tax_query = [];
        $relation = 0;
        
        if ($params['term_id'] > 0) {
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $params['term_id']
            ];
            $relation++;
        }
        
        if ($params['color'] !== 'false') {
            $tax_query[] = [
                'taxonomy' => 'pa_color',
                'field' => 'term_id',
                'terms' => explode('-', $params['color']),
            ];
            $relation++;
        }
        
        if ($params['size'] !== 'false') {
            $tax_query[] = [
                'taxonomy' => 'pa_size',
                'field' => 'term_id',
                'terms' => explode('-', $params['size']),
            ];
            $relation++;
        }
        
        if ($relation > 1) {
            $tax_query['relation'] = 'AND';
        }
        
        if ($relation > 0) {
            $args['tax_query'] = $tax_query;
        }
        
        // Order by
        $this->set_orderby($args, $params['orderby']);
        
        return $args;
    }
    
    /**
     * Set orderby parameters
     * 
     * @param array &$args Query arguments
     * @param string $orderby Order by value
     */
    private function set_orderby(array &$args, string $orderby): void {
        switch ($orderby) {
            case 'popularity':
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
                
            case 'price':
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'ASC';
                break;
                
            case 'price-desc':
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
                
            case 'date':
                $args['meta_key'] = '';
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
                break;
                
            case 'menu_order':
            default:
                $args['numberposts'] = -1;
                $args['offset'] = 0;
                $args['meta_key'] = '';
                $args['orderby'] = 'menu_order';
                $args['order'] = 'DESC';
                break;
        }
    }
    
    /**
     * Render products
     * 
     * @param array $posts Posts array
     * @param array $params Parameters
     */
    private function render_products(array $posts, array &$params): void {
        if (empty($posts)) {
            if (intval($params['offset']) === 0) {
                echo '<div class="not-found">' . __('No products found for the specified parameters', 'storefront') . '</div>';
            }
            return;
        }
        
        global $post;
        $ind = 0;
        $offset = $params['orderby'] !== 'menu_order' ? 0 : $params['offset'];
        
        foreach ($posts as $post) {
            if ($offset > 0) {
                $offset--;
                continue;
            }
            
            $params['offset_new']++;
            setup_postdata($post);
            
            global $product;
            $product = wc_setup_product_data($post->ID);
            
            $skip = $this->check_product_availability($product, $params);
            
            if (!$skip && $product->is_visible()) {
                $ind++;
                wc_get_template_part('content', 'product_catalog');
                
                if ($ind === $params['number']) {
                    $this->render_loading_more();
                    break;
                }
            }
        }
        
        if (intval($params['ids']) === 0 && $ind === 0) {
            echo '<div class="not-found">' . __('No products found for the specified parameters', 'storefront') . '</div>';
        }
    }
    
    /**
     * Check product availability
     * 
     * @param object $product Product object
     * @param array &$params Parameters
     * @return bool Skip product
     */
    private function check_product_availability(object $product, array &$params): bool {
        if ($params['size'] === 'false') {
            if ($params['ids'] !== '') {
                $params['ids'] .= ',';
            }
            $params['ids'] .= $product->get_id();
            return false;
        }
        
        $product_id = $product->get_id();
        $variations = $product->get_children();
        $skip = true;
        
        foreach ($variations as $variation) {
            $variation_product = wc_setup_product_data($variation);
            $availability = $variation_product->get_availability();
            
            if ($availability['class'] === 'in-stock') {
                $attributes = $variation_product->get_variation_attributes();
                
                if (!empty($attributes['attribute_pa_size'])) {
                    $term = get_term_by('slug', $attributes['attribute_pa_size'], 'pa_size');
                    
                    if ($term && in_array($term->term_id, explode('-', $params['size'])) && $variation_product->stock_quantity) {
                        $skip = false;
                        
                        if ($params['ids'] !== '') {
                            $params['ids'] .= ',';
                        }
                        $params['ids'] .= $product_id;
                        break;
                    }
                }
            }
        }
        
        wc_setup_product_data($product_id);
        return $skip;
    }
    
    /**
     * Render loading more indicator
     */
    private function render_loading_more(): void {
        ?>
        <div class="clear">
            <div class="loading-more">
                <div class="blob top"></div>
                <div class="blob bottom"></div>
                <div class="blob left"></div>
                <div class="blob move-blob"></div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render breadcrumbs
     * 
     * @param int $term_id Category term ID
     */
    private function render_breadcrumbs(int $term_id): void {
        $frontpage_id = get_option('page_on_front');
        $shop_page_id = get_option('woocommerce_shop_page_id');
        ?>
        <li class="rs-breadcrumbs__item">
            <a href="<?php echo esc_url(get_the_permalink($frontpage_id)); ?>" class="rs-breadcrumbs__link">
                <?php echo esc_html(get_the_title($frontpage_id)); ?>
            </a>
        </li>
        <?php if ($term_id > 0) : 
            $term = get_term($term_id);
        ?>
            <li class="rs-breadcrumbs__item">
                <a href="<?php echo esc_url(get_the_permalink($shop_page_id)); ?>" class="rs-breadcrumbs__link">
                    <?php echo esc_html(get_the_title($shop_page_id)); ?>
                </a>
            </li>
            <li class="rs-breadcrumbs__item">
                <span class="rs-breadcrumbs__current"><?php echo esc_html($term->name); ?></span>
            </li>
        <?php else : ?>
            <li class="rs-breadcrumbs__item">
                <span class="rs-breadcrumbs__current"><?php echo esc_html(get_the_title($shop_page_id)); ?></span>
            </li>
        <?php endif;
    }
    
    /**
     * Get collections via AJAX
     */
    public function get_collections(): void {
        if (!$this->verify_ajax()) {
            wp_die();
        }
        
        ob_start();
        
        $offset = intval($_POST['offset']);
        $number = intval($_POST['number']) + 1;
        
        $args = [
            'numberposts' => $number,
            'offset' => $offset,
            'post_type' => 'collections',
            'orderby' => 'menu_order',
            'order' => 'ASC',
        ];
        
        wp_reset_postdata();
        $my_posts = get_posts($args);
        
        if ($my_posts) {
            $count = count($my_posts);
            
            for ($index = 0; $index < min($count, 2); $index++) {
                global $post;
                $post = $my_posts[$index];
                setup_postdata($post);
                $this->render_collection_item($post);
            }
            
            if ($count === $number) {
                $this->render_loading_more();
            }
            
            wp_reset_postdata();
        }
        
        $output = ob_get_clean();
        echo json_encode([$output]);
        wp_die();
    }
    
    /**
     * Render single collection item
     * 
     * @param object $post Post object
     */
    private function render_collection_item(object $post): void {
        $thumbnail_url = get_the_post_thumbnail_url($post, 'full');
        ?>
        <div class="rs-collections-archive__item">
            <div class="rs-collections-archive__picture">
                <picture>
                    <source srcset="<?php echo esc_url($thumbnail_url); ?>.webp" type="image/webp" />
                    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" draggable="false" />
                </picture>
                <picture>
                    <source srcset="<?php echo esc_url($thumbnail_url); ?>.webp" type="image/webp" />
                    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" draggable="false" />
                </picture>
            </div>
            <div class="rs-collections-archive__description">
                <a href="<?php the_permalink(); ?>">
                    <h2 class="large-title"><?php the_title(); ?></h2>
                    <h4 class="l-regular-title"><?php the_excerpt(); ?></h4>
                </a>
                <a href="<?php the_permalink(); ?>" class="rs-btn _border-btn _white-btn">
                    <?php _e('Collection', 'storefront'); ?>
                </a>
            </div>
        </div>
        <?php
    }
    
    /**
     * Get media items via AJAX
     */
    public function get_media_items(): void {
        if (!$this->verify_ajax()) {
            wp_die();
        }
        
        ob_start();
        wp_reset_postdata();
        
        $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 3;
        $number = intval($_POST['number']);
        
        if (!$offset || $offset === 3) {
            $number = 3;
        }
        
        $args = [
            'numberposts' => $number,
            'offset' => $offset,
            'post_type' => 'news',
            'orderby' => 'date',
            'order' => 'DESC',
            'tax_query' => [
                'relation' => 'OR',
                [
                    'taxonomy' => 'post_tag',
                    'field' => 'slug',
                    'terms' => ['video', 'novost'],
                ],
            ]
        ];
        
        $my_posts = get_posts($args);
        
        if ($my_posts) {
            echo '<div class="rs-media-news__list">';
            
            foreach ($my_posts as $post) {
                global $post;
                setup_postdata($post);
                $this->render_media_item($post);
            }
            
            echo '</div>';
            
            if (count($my_posts) === $number) {
                $this->render_loading_more();
            }
            
            wp_reset_postdata();
        }
        
        $output = ob_get_clean();
        echo json_encode([$output]);
        wp_die();
    }
    
    /**
     * Render single media item
     * 
     * @param object $post Post object
     */
    private function render_media_item(object $post): void {
        $post_thumbnail = get_the_post_thumbnail_url($post, 'full');
        $img = get_field('cat_img') ?: $post_thumbnail;
        ?>
        <div class="rs-media-news__item">
            <a href="<?php the_permalink(); ?>">
                <div class="rs-media-news__img">
                    <?php if ($img) : ?>
                        <picture>
                            <source srcset="<?php echo esc_url($img); ?>.webp" type="image/webp">
                            <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                        </picture>
                    <?php endif; ?>
                </div>
                <div class="rs-media-news__description">
                    <h4 class="sm-bold-title"><?php the_title(); ?></h4>
                </div>
            </a>
        </div>
        <?php
    }
    
    /**
     * Get variation gallery via AJAX
     */
    public function get_variation_gallery(): void {
        if (!$this->verify_ajax()) {
            wp_die();
        }
        
        $product_id = intval($_POST['variation_id']);
        $parent_id = intval($_POST['product_id']);
        $product = wc_get_product($product_id);
        $output = [];
        
        ob_start();
        $this->render_variation_gallery($parent_id, $product_id);
        $output[] = ob_get_clean();
        
        ob_start();
        echo $product->get_price_html();
        $output[] = ob_get_clean();
        
        echo json_encode($output);
        wp_die();
    }
    
    /**
     * Render variation gallery
     * 
     * @param int $parent_id Parent product ID
     * @param int $variation_id Variation ID
     */
    private function render_variation_gallery(int $parent_id, int $variation_id): void {
        $product_variation = woo_variation_gallery()->get_frontend()->get_available_variation($parent_id, $variation_id);
        $attachment_ids = [];
        
        if (isset($product_variation['variation_gallery_images'])) {
            $attachment_ids = wp_list_pluck($product_variation['variation_gallery_images'], 'image_id');
            array_shift($attachment_ids);
        }
        ?>
        <div class="rs-product__pictures">
            <div class="rs-thumbs__slider swiper">
                <div class="rs-thumbs__swiper swiper-wrapper">
                    <?php $this->render_thumbnail_images($parent_id, $attachment_ids); ?>
                </div>
            </div>
            <div class="rs-product__slider swiper" data-gallery>
                <div class="rs-product__swiper swiper-wrapper">
                    <?php $this->render_gallery_images($parent_id, $attachment_ids); ?>
                </div>
                <div class="rs-product__pagination swiper-pagination"></div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render thumbnail images
     * 
     * @param int $parent_id Parent product ID
     * @param array $attachment_ids Attachment IDs
     */
    private function render_thumbnail_images(int $parent_id, array $attachment_ids): void {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($parent_id), 'single-post-thumbnail');
        
        if ($image) :
            ?>
            <div class="rs-thumbs__slide swiper-slide">
                <picture>
                    <source srcset="<?php echo esc_url($image[0]); ?>.webp" type="image/webp">
                    <img src="<?php echo esc_url($image[0]); ?>" alt="">
                </picture>
            </div>
            <?php
        endif;
        
        if ($attachment_ids) {
            foreach ($attachment_ids as $attachment_id) {
                $image_link = wp_get_attachment_image_src($attachment_id, 'single-post-thumbnail')[0];
                ?>
                <div class="rs-thumbs__slide swiper-slide">
                    <picture>
                        <source srcset="<?php echo esc_url($image_link); ?>.webp" type="image/webp">
                        <img src="<?php echo esc_url($image_link); ?>" alt="">
                    </picture>
                </div>
                <?php
            }
        }
    }
    
    /**
     * Render gallery images
     * 
     * @param int $parent_id Parent product ID
     * @param array $attachment_ids Attachment IDs
     */
    private function render_gallery_images(int $parent_id, array $attachment_ids): void {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($parent_id), 'full');
        
        if ($image) :
            ?>
            <div class="rs-product__slide swiper-slide" data-gallery-item data-src="<?php echo esc_url($image[0]); ?>">
                <picture>
                    <source srcset="<?php echo esc_url($image[0]); ?>.webp" type="image/webp">
                    <img src="<?php echo esc_url($image[0]); ?>" alt="">
                </picture>
            </div>
            <?php
        endif;
        
        if ($attachment_ids) {
            foreach ($attachment_ids as $attachment_id) {
                $image_link = wp_get_attachment_image_src($attachment_id, 'full')[0];
                $image_url = wp_get_attachment_url($attachment_id);
                ?>
                <div class="rs-product__slide swiper-slide" data-gallery-item data-src="<?php echo esc_url($image_link); ?>">
                    <picture>
                        <source srcset="<?php echo esc_url($image_url); ?>.webp" type="image/webp">
                        <img src="<?php echo esc_url($image_url); ?>" alt="">
                    </picture>
                </div>
                <?php
            }
        }
    }
    
    /**
     * Set wishlist count via AJAX
     */
    public function set_wishlist(): void {
        ob_start();
        ?>
        <a href="<?php echo esc_url(YITH_WCWL()->get_wishlist_url('view')); ?>" class="icon-heart">
            <?php
            $count = YITH_WCWL()->count_all_products();
            if ($count > 0) :
                ?>
                <span><?php echo esc_html($count); ?></span>
                <?php
            endif;
            ?>
        </a>
        <?php
        $output = ob_get_clean();
        
        echo json_encode([$output]);
        wp_die();
    }
}
