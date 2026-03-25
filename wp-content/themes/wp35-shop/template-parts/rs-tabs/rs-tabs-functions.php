<?php
function style_rs_tabs_theme() {
    wp_enqueue_style('rs-tabs-theme', get_stylesheet_directory_uri() . '/template-parts/rs-tabs/css/rs-tabs.css');
}
add_action('wp_enqueue_scripts', 'style_rs_tabs_theme', 11);

function storefront_rs_tabs() {
    $tabs = get_field('tabs') ?: '';
    ?>
    <?php if (is_array($tabs)) {
        foreach ($tabs as $key0 => $item0) {
            $title = isset($item0['title']) ? $item0['title'] : '';
            $description = isset($item0['description']) ? $item0['description'] : '';
            $tab_content = isset($item0['tab_content']) ? $item0['tab_content'] : array();
            ?>
            <section class="rs-17">
                <div class="rs-tabs">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12">
                                <?php if ($title) { ?>
                                    <h2 class="text-center section-title" data-nekoanim="fadeInUp" data-nekodelay="100"><?php echo esc_html($title); ?></h2>
                                <?php } ?>
                                <?php if ($description) { ?>
                                    <div class="section-descr" data-nekoanim="fadeInUp" data-nekodelay="200">
                                        <?php echo wp_kses_post($description); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="tabs-row row">
                            <div class="col-xs-12 col-sm-4 col-md-3" data-nekoanim="fadeInLeft" data-nekodelay="300">
                                <ul class="nav nav-pills nav-stacked" role="tablist">
                                    <?php
                                    $i = 0;
                                    if (is_array($tab_content)) {
                                        foreach ($tab_content as $key => $item) { ?>
                                            <li role="presentation" class="<?php if ($i++ == 0) echo 'active'; ?>">
                                                <a href="#tab-text<?php echo esc_attr($key0); ?><?php echo esc_attr($i); ?>" role="tab" data-toggle="tab"><?php echo esc_html($item['name']); ?></a>
                                            </li>
                                        <?php }
                                    } ?>
                                </ul>
                            </div>
                            <div class="col-xs-12 col-sm-8 col-md-9" data-nekoanim="fadeInRight" data-nekodelay="600">
                                <div class="tab-content">
                                    <?php
                                    $i = 0;
                                    if (is_array($tab_content)) {
                                        foreach ($tab_content as $item) { ?>
                                            <div role="tabpanel" class="tab-pane <?php if ($i++ == 0) echo 'active'; ?> fade in" id="tab-text<?php echo esc_attr($key0); ?><?php echo esc_attr($i); ?>">
                                                <?php echo wp_kses_post($item['data']); ?>
                                            </div>
                                        <?php }
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php }
    }
}
