<?php
/**
 * Template for the "Piterskij Shhit x Top Dog" collection page.
 *
 * @package dazzling
 */

$site_url  = site_url() . '/new-assets';
$site_path = get_home_path() . 'new-assets/';

get_header();

$top_dog_post_id = 138459;

if (get_the_ID() === $top_dog_post_id) : ?>

<script src="<?php echo esc_url($site_url); ?>/812/js/swiper-bundle.min.js" defer></script>
<link rel="stylesheet" type="text/css" href="<?php echo esc_url($site_url); ?>/812/css/swiper-bundle.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo esc_url($site_url); ?>/812/css/block-css/style.css?v=<?php echo esc_attr(filemtime($site_path . '812/css/block-css/style.css')); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo esc_url($site_url); ?>/css/fancybox.css" />
<script src="<?php echo esc_url($site_url); ?>/js/jquery-2.1.3.min.js" defer></script>
<script src="<?php echo esc_url($site_url); ?>/js/fancybox.js" defer></script>
<script src="<?php echo esc_url($site_url); ?>/812/js/app.js" defer></script>
<link rel="stylesheet" href="<?php echo esc_url($site_url); ?>/812/css/block-css/rs-store.css" />

<div class="rs-store">
	<div class="rs-store__container">
		<div class="rs-store__wrapper">
			<div class="rs-store__content">
				<div class="store-content">
					<?php
					get_template_part('template-parts/rs-collection/collection-header');

					get_template_part('template-parts/rs-collection/collection-store', null, [
						'products_source' => 'tovary',
						'button_text'     => 'Перейти ко всем товарам коллекции',
						'button_link'     => 'https://www.spbshield.ru/shop/topdog/',
					]);
					?>
				</div>
			</div>

			<div class="rs-store__gallery">
				<a href="https://youtu.be/BNAlgsHanrM?si=Xx1tgzpKlUNGNz8f" class="store-video" target="_blank">
					<img src="<?php echo esc_url($site_url); ?>/top-dog/img/video_link.jpg" alt="">
				</a>
				<div class="store-gallery">
				</div>
			</div>
		</div>
	</div>
</div>

<?php endif; ?>

<?php get_footer(); ?>
