<?php
function style_rs_parallax_2_theme() {
    wp_enqueue_style('rs-parallax-2-theme', get_stylesheet_directory_uri() . '/template-parts/rs-parallax-2/css/rs-parallax-2.css');
}
add_action('wp_enqueue_scripts', 'style_rs_parallax_2_theme', 11);

function storefront_parallax_2() {
    $query = new WP_Query(array(
        'post_type' => 'custom_block',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key'     => 'block_id',
                'value'   => 24, // block ID
                'compare' => '='
            )
        )
    ));
    $post_meta = false;
    $bg_img = false;
    $title = '';
    while ($query->have_posts()) {
        $query->the_post();
        $post_meta = get_post_meta($query->post->ID);
    }
    if ($post_meta) {
        $bg_img = get_field('bg_img') ?: false;
        if ($bg_img && isset($bg_img['url'])) {
            $url = $bg_img['url'];
            $attachment_id = attachment_url_to_postid($url);
            $srcm = wp_get_attachment_image_url($attachment_id, 'medium_large');
            $src = wp_get_attachment_image_url($attachment_id, 'large');
            $srcF = wp_get_attachment_image_url($attachment_id, 'full');
        }
        $title = get_field('title') ?: '';
    }
    ?>
    <section class="rs-17">
        <div class="rs-parallax-2 parallax-block <?php if ($bg_img) { ?> b-lazy <?php } ?>" <?php if ($bg_img) { ?> data-src="<?php echo esc_url($srcF); ?>" data-medium="<?php echo esc_url($src); ?>" data-small="<?php echo esc_url($srcm); ?>" style="background-size: cover;"<?php } ?>>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 parallax-title">
                        <h2 data-nekoanim="fadeInUp" data-nekodelay="100" class="text-center"><?php echo esc_html($title); ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    wp_reset_postdata();
}
