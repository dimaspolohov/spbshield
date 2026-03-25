<?php
/**
 * Template for the "New Drop / Gothica" collection page.
 *
 * Shows a store layout for specific posts, otherwise falls back
 * to the default collection page layout.
 *
 * @package dazzling
 */

$site_url  = site_url() . '/new-assets';
$site_path = get_home_path() . 'new-assets/';

get_header();

$store_post_ids = [130465, 132856, 155891];

if (in_array(get_the_ID(), $store_post_ids, true)) : ?>

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
						'button_link'     => 'https://www.spbshield.ru/shop/gothica/',
					]);
					?>
				</div>
			</div>
			<div class="store-gallery">
				<?php
				$drop_images = range(1, 20);
				foreach ($drop_images as $num) :
					$ext = in_array($num, [2, 7, 12, 15, 16, 17, 18, 19, 20], true) ? 'jpg' : 'JPG';
				?>
				<a href="<?php echo esc_url($site_url . '/drop/opt/' . $num . '.' . $ext); ?>" data-fancybox class="store-gallery__item store-gallery__item_wide">
					<picture>
						<source media="(max-width: 1024px)" srcset="<?php echo esc_url($site_url . '/drop/opt/' . $num . '.' . $ext); ?>">
						<img src="<?php echo esc_url($site_url . '/drop/opt/' . $num . '.' . $ext); ?>" loading="lazy" alt="">
					</picture>
				</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>

<?php else :

	get_template_part('template-parts/rs-collection/collection-default-layout', null, [
		'site_url'  => $site_url,
		'site_path' => $site_path,
	]);

endif; ?>

<?php get_footer(); ?>
