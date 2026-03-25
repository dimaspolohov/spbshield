<?php
/**
 * Template for the "Hella Shield" collection page.
 *
 * Shows a store layout for a specific post (uses ACF relationship field),
 * otherwise falls back to the default collection page layout.
 *
 * @package dazzling
 */

$site_url  = site_url() . '/new-assets';
$site_path = get_home_path() . 'new-assets/';

get_header();

$hella_shield_post_id = 146216;

if (get_the_ID() === $hella_shield_post_id) : ?>

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
						'products_source' => 'relationship',
						'button_text'     => 'Перейти ко всем товарам коллекции',
						'button_link'     => 'https://www.spbshield.ru/shop/hellashield/',
					]);
					?>
				</div>
			</div>
			<div class="rs-store__gallery">
				<div class="store-gallery">
					<?php
					$hella_images = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14];
					foreach ($hella_images as $num) :
					?>
					<a href="<?php echo esc_url($site_url . '/hella-shield/img/' . $num . '.JPG'); ?>" data-fancybox class="store-gallery__item">
						<picture>
							<source media="(max-width: 1024px)" srcset="<?php echo esc_url($site_url . '/hella-shield/img/' . $num . '.JPG'); ?>">
							<img src="<?php echo esc_url($site_url . '/hella-shield/img/' . $num . '.JPG'); ?>" loading="lazy" alt="">
						</picture>
					</a>
					<?php endforeach; ?>
				</div>
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
