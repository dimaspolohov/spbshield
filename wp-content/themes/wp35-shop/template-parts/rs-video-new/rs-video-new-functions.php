<?php
function style_rs_video_new_theme() {
    wp_enqueue_style('rs-video-new', get_stylesheet_directory_uri() . '/template-parts/rs-video-new/css/rs-video-new.css');
}
add_action('wp_enqueue_scripts', 'style_rs_video_new_theme', 11);

function storefront_rs_video_new() {
    $query = new WP_Query(array(
        'post_type' => 'custom_block',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key'     => 'block_id',
                'value'   => 20, // block ID
                'compare' => '='
            )
        )
    ));
    $query->the_post();
    $title = get_field('title') ?: '';
    $bg_img = get_field('bg_img') ?: false;
    if ($bg_img && isset($bg_img['url'])) {
        $url = $bg_img['url'];
        $attachment_id = attachment_url_to_postid($url);
        $srcm = wp_get_attachment_image_url($attachment_id, 'medium_large');
        $src = wp_get_attachment_image_url($attachment_id, 'large');
        $srcF = wp_get_attachment_image_url($attachment_id, 'full');
    }
    $bg_img_bottom_field = get_field('bg_img_bottom');
    $bg_img_bottom = (is_array($bg_img_bottom_field) && isset($bg_img_bottom_field['url'])) ? $bg_img_bottom_field['url'] : '';
    $video_box = get_field('new_video_block') ?: '';
    ?>
    <section class="rs-17">
        <div class="rs-video-new <?php if ($bg_img) { ?> b-lazy <?php } ?>" <?php if ($bg_img) { ?> data-src="<?php echo esc_url($srcF); ?>" data-medium="<?php echo esc_url($src); ?>" data-small="<?php echo esc_url($srcm); ?>" style="background-size: cover;"<?php } ?>>
            <div class="bg-video" style="background: url(<?php echo esc_url($bg_img_bottom); ?>) no-repeat; background-size: auto;">
                <div class="container">
                    <?php if ($title) : ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <h2 class="text-center section-title" data-nekoanim="fadeInUp" data-nekodelay="50"><span class="section-title--text"><?php echo esc_html($title); ?></span></h2>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (is_array($video_box)) {
                        $i = 1;
                        foreach ($video_box as $item) { ?>
                            <div class="col-xs-12 col-sm-6">
                                <div class="video-item">
                                    <?php if (!empty($item['link'])) { ?>
                                        <div>
                                            <iframe class="b-lazy" width="540" height="295" style="border:0;" data-src="https://www.youtube.com/embed/<?php echo esc_attr($item['link']); ?>?modestbranding=1&amp;rel=0&amp;showinfo=0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                        </div>
                                    <?php } ?>
                                    <?php if (!empty($item['name'])) { ?>
                                        <h3><?php echo esc_html($item['name']); ?></h3>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="clearfix"></div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <?php
    wp_reset_postdata();
}
