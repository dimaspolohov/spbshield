<?php
/**
 * The default template for displaying single collection posts.
 *
 * Handles routing to specific collection templates for certain posts,
 * displays the "812" store layout for designated collections,
 * and falls back to the generic collection layout for all others.
 *
 * @package dazzling
 */

$site_url  = site_url() . '/new-assets';
$site_path = get_home_path() . 'new-assets/';

// Route specific posts to dedicated templates
$newdrop_post_ids = [155891];

if (is_singular('collections') && in_array(get_the_ID(), $newdrop_post_ids, true)) {
	$tpl = locate_template('single-collections-newdrop.php');
	if ($tpl) {
		include $tpl;
		return;
	}
}

get_header();

$store_812_post_ids = [130465, 132856, 155891];

if (in_array(get_the_ID(), $store_812_post_ids, true)) : ?>

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
						'button_text'     => 'Перейти ко всем товарам коллекции!',
						'button_link'     => 'https://www.spbshield.ru/shop/812/',
					]);
					?>
				</div>
			</div>

			<div class="rs-store__gallery">
				<a href="https://youtu.be/Rbdjg4TPwqk" class="store-video" target="_blank">
					<img src="<?php echo esc_url($site_url); ?>/812/img/video_link.jpg" alt="">
				</a>
				<div class="store-gallery">
					<?php
					$gallery_812 = [
						['mobile' => 'gallery_812_26',     'retina' => 'gallery_812_26@2x',     'wide' => false],
						['mobile' => 'gallery_812_3',      'retina' => 'gallery_812_3@2x',      'wide' => false],
						['mobile' => 'gallery_812_23',     'retina' => 'gallery_812_23@2x',     'wide' => false],
						['mobile' => 'gallery_812_19',     'retina' => 'gallery_812_19@2x',     'wide' => false],
						['mobile' => 'gallery_812_4',      'retina' => 'gallery_812_4@2x',      'wide' => true],
						['mobile' => 'gallery_812_17',     'retina' => 'gallery_812_17@2x',     'wide' => false],
						['mobile' => 'gallery_812_11',     'retina' => 'gallery_812_11@2x',     'wide' => false],
						['mobile' => 'gallery_812_10',     'retina' => 'gallery_812_10@2x',     'wide' => false],
						['mobile' => 'gallery_812_15',     'retina' => 'gallery_812_15@2x',     'wide' => false],
						['mobile' => 'gallery_812_14',     'retina' => 'gallery_812_14@2x',     'wide' => false],
						['mobile' => 'gallery_812_13',     'retina' => 'gallery_812_13@2x',     'wide' => false],
						['mobile' => 'gallery_812_5',      'retina' => 'gallery_812_5@2x',      'wide' => false],
						['mobile' => 'gallery_812_6',      'retina' => 'gallery_812_6@2x',      'wide' => false],
						['mobile' => 'gallery_812_22',     'retina' => 'gallery_812_22@2x',     'wide' => false],
						['mobile' => 'gallery_812_21',     'retina' => 'gallery_812_21@2x',     'wide' => false],
						['mobile' => 'gallery_812_1',      'retina' => 'gallery_812_1@2x',      'wide' => true],
						['mobile' => 'gallery_812_2',      'retina' => 'gallery_812_2@2x',      'wide' => false],
						['mobile' => 'gallery_812_8',      'retina' => 'gallery_812_8@2x',      'wide' => false],
						['mobile' => 'gallery_812_12',     'retina' => 'gallery_812_12@2x',     'wide' => true],
						['mobile' => 'gallery_812_7',      'retina' => 'gallery_812_7@2x',      'wide' => true],
						['mobile' => 'gallery_812_25',     'retina' => 'gallery_812_25@2x',     'wide' => false],
						['mobile' => 'gallery_812_24',     'retina' => 'gallery_812_24@2x',     'wide' => false],
						['mobile' => 'gallery_812_16',     'retina' => 'gallery_812_16@2x',     'wide' => false],
						['mobile' => 'gallery_812_16_new', 'retina' => 'gallery_812_16@2x_new', 'wide' => false],
						['mobile' => 'gallery_812_18',     'retina' => 'gallery_812_18@2x',     'wide' => true],
						['mobile' => 'gallery_812_9_new',  'retina' => 'gallery_812_9@2x_new',  'wide' => false],
						['mobile' => 'gallery_812_27',     'retina' => 'gallery_812_27@2x',     'wide' => false],
					];

					foreach ($gallery_812 as $img) :
						$wide_class = $img['wide'] ? ' store-gallery__item_wide' : '';
					?>
					<a href="<?php echo esc_url($site_url . '/812/img/' . $img['retina'] . '.jpg'); ?>" data-fancybox class="store-gallery__item<?php echo esc_attr($wide_class); ?>">
						<picture>
							<source media="(max-width: 1024px)" srcset="<?php echo esc_url($site_url . '/812/img/' . $img['mobile'] . '.jpg'); ?>">
							<img src="<?php echo esc_url($site_url . '/812/img/' . $img['retina'] . '.jpg'); ?>" loading="lazy" alt="">
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
