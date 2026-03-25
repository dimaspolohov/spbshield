<?php
/**
 * Template for the "Zenit x Piterskij Shhit 2024" collection page.
 *
 * Shows a store layout for a specific post (uses ACF relationship field),
 * otherwise falls back to the default collection page layout.
 *
 * @package dazzling
 */

$site_url  = site_url() . '/new-assets';
$site_path = get_home_path() . 'new-assets/';

get_header();

$zenit_post_id = 135851;

if (get_the_ID() === $zenit_post_id) : ?>

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
						'button_link'     => 'https://www.spbshield.ru/shop/zenit-x-piterskij-shhit-2024/',
					]);
					?>
				</div>
			</div>
			<div class="rs-store__gallery">
				<div class="store-gallery">
					<?php
					$zenit_images = [
						['file' => '1',  'wide' => true],
						['file' => '2',  'wide' => false],
						['file' => '3',  'wide' => false],
						['file' => '4',  'wide' => true],
						['file' => '5',  'wide' => false],
						['file' => '6',  'wide' => false],
						['file' => '7',  'wide' => true],
						['file' => '08', 'wide' => false],
						['file' => '09', 'wide' => false],
						['file' => '10', 'wide' => true],
						['file' => '11', 'wide' => true],
						['file' => '12', 'wide' => false],
						['file' => '13', 'wide' => false],
						['file' => '14', 'wide' => true],
						['file' => '15', 'wide' => true],
						['file' => '16', 'wide' => false],
						['file' => '17', 'wide' => false],
						['file' => '18', 'wide' => false],
						['file' => '19', 'wide' => false],
						['file' => '20', 'wide' => true],
					];
					foreach ($zenit_images as $img) :
						$wide_class = $img['wide'] ? ' store-gallery__item_wide' : '';
					?>
					<a href="<?php echo esc_url($site_url . '/zenit/img/' . $img['file'] . '@2x.jpg'); ?>" data-fancybox class="store-gallery__item<?php echo esc_attr($wide_class); ?>">
						<picture>
							<source media="(max-width: 1024px)" srcset="<?php echo esc_url($site_url . '/zenit/img/' . $img['file'] . '.jpg'); ?>">
							<img src="<?php echo esc_url($site_url . '/zenit/img/' . $img['file'] . '@2x.jpg'); ?>" loading="lazy" alt="">
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
