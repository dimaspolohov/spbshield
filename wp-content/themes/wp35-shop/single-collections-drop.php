<?php
/*
 * Template Name: Single Drop
 * Template Post Type: collections
 */
$site_url = site_url() . '/new-assets';
$site_path = get_home_path() . 'new-assets/';
get_header();
?>

<script src="<?= $site_url ?>/812/js/swiper-bundle.min.js" defer></script>
<link rel="stylesheet" type="text/css" href="<?= $site_url ?>/812/css/swiper-bundle.min.css" />
<link rel="stylesheet" type="text/css" href="<?= $site_url ?>/812/css/block-css/style.css?v=<?= filemtime($site_path . '812/css/block-css/style.css') ?>" />
<link rel="stylesheet" type="text/css" href="<?= $site_url ?>/css/fancybox.css" />
<script src="<?= $site_url ?>/js/jquery-2.1.3.min.js" defer></script>
<script src="<?= $site_url ?>/js/fancybox.js" defer></script>
<script src="<?= $site_url ?>/812/js/app.js" defer></script>
<link rel="stylesheet" href="<?= $site_url ?>/812/css/block-css/rs-store.css" />

<div class="rs-store">	
    <div class="rs-store__container">
        <div class="rs-store__wrapper">
            <div class="rs-store__content">
                <div class="store-content">
                    <div class="store-content__about">
                        <div class="store-about">
                            <h2 class="store-about__title"><b><?= get_field('zagolovok_v_kataloge') ?></b></h2>
                            <p class="store-about__text"><?= get_field('opisanie_v_kataloge') ?></p>
                        </div>
                    </div>

                    <?php if (have_rows('tovary')): ?>
                        <div class="store-content__goods">
                            <div class="store-goods">
                                <br/><br/><br/><br/>
                                <div class="store-goods__slider">
                                    <div class="store-goods__swiper">
                                        <?php while (have_rows('tovary')): the_row();
                                            $image = get_sub_field('kartinka')['ID'];
                                            $product_id = get_sub_field('tovar');
                                            $product = wc_get_product($product_id);
                                            if ($product) {
                                                $regular_price = $product->is_type('variable') ? $product->get_variation_regular_price('min') : $product->get_regular_price();
                                                $sale_price = $product->is_type('variable') ? $product->get_variation_sale_price('min') : $product->get_sale_price();
                                            ?>
                                                <div class="store-goods__slide">
                                                    <article class="goods-card">	
                                                        <div class="goods-card__wrapper">
                                                            <div class="goods-card__photo"><?php echo wp_get_attachment_image($image, 'full'); ?></div>
                                                            <h5 class="goods-card__title"><?= get_sub_field('nazvanie') ?></h5>
                                                            <div class="goods-card__price">
                                                                <?php if ($sale_price && $sale_price != $regular_price): ?>
                                                                    <del><?= number_format($regular_price, 0, ',', '&nbsp;') ?>&nbsp;₽</del>
                                                                <?php endif; ?>
                                                                <span><?= number_format($sale_price ?: $regular_price, 0, ',', '&nbsp;') ?>&nbsp;₽</span>
                                                            </div>
                                                        </div>
                                                        <a href="<?= get_permalink($product_id) ?>" target="_blank"><?= get_sub_field('kategoriya') ?></a>
                                                    </article>
                                                </div>
                                            <?php } endwhile; ?>
                                    </div>
                                </div>
                                <div class="store-goods__nav">
                                    <div class="slider-arrows">
                                        <button type="button" class="slider-arrow slider-arrow_prev">
                                            <svg viewBox="0 0 14 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M13 1L1 13L13 25" stroke="black" />
                                            </svg>
                                        </button>
                                        <button type="button" class="slider-arrow slider-arrow_next">
                                            <svg viewBox="0 0 14 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 1L13 13L1 25" stroke="black" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <br/><br/>
                    <a href="/shop/new-drop/" class="rs-btn _black-border-btn">Перейти ко всем товарам дропа</a>
                </div>
            </div>
             <div class="rs-store__gallery">
        <video class="store-video" autoplay muted loop controls poster="<?= site_url() ?>/wp-content/themes/wp35-shop/assets/img/new-drop-poster.jpg"style="width:100%;">
    <source src="<?= site_url() ?>/wp-content/themes/wp35-shop/assets/video/turbo.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>