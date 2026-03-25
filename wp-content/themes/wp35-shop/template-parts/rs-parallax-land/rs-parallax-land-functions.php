<?php
function style_rs_parallax_land_theme() {
    wp_enqueue_style('rs-parallax-land', get_stylesheet_directory_uri() . '/template-parts/rs-parallax-land/css/rs-parallax-land.css');
}
add_action('wp_enqueue_scripts', 'style_rs_parallax_land_theme', 12);

function storefront_rs_parallax_land() {
    $query = new WP_Query(array(
        'post_type' => 'custom_block',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key'     => 'block_id',
                'value'   => 13, // block ID
                'compare' => '='
            )
        )
    ));
    $query->the_post();
    $bg_img = get_field('bg_img') ?: false;
    if ($bg_img && isset($bg_img['url'])) {
        $url = $bg_img['url'];
        $attachment_id = attachment_url_to_postid($url);
        $srcm = wp_get_attachment_image_url($attachment_id, 'medium_large');
        $src = wp_get_attachment_image_url($attachment_id, 'large');
        $srcF = wp_get_attachment_image_url($attachment_id, 'full');
    }
    $title = get_field('title') ?: '';
    $text = get_field('text') ?: '';
    $author = get_field('author') ?: '';
    ?>

    <section class="rs-17">
        <div class="rs-parallax2 parallax-text parallax-land <?php if ($bg_img) { ?> b-lazy <?php } ?>" <?php if ($bg_img) { ?> data-src="<?php echo esc_url($srcF); ?>" data-medium="<?php echo esc_url($src); ?>" data-small="<?php echo esc_url($srcm); ?>" style="background-size: cover;"<?php } ?>>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-md-6 parallax-title text-left">
                        <?php if ($title) : ?>
                            <h2 data-nekoanim="fadeInUp" data-nekodelay="100"><?php echo esc_html($title); ?></h2>
                        <?php endif; ?>
                        <?php if ($text) : ?>
                            <h3 data-nekoanim="fadeInUp" data-nekodelay="100"><?php echo esc_html($text); ?></h3>
                        <?php endif; ?>
                        <?php if ($author) : ?>
                            <div class="quote" data-nekoanim="fadeInUp" data-nekodelay="150"><?php echo esc_html($author); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    wp_reset_postdata();
}
