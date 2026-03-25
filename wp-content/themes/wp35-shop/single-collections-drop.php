<?php
/**
 * Template Name: Single Drop
 * Template Post Type: collections
 *
 * Store-layout template for the "Drop" collection page.
 *
 * @package dazzling
 */

$site_url  = site_url() . '/new-assets';
$site_path = get_home_path() . 'new-assets/';

get_header();
?>

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
						'button_text'     => 'Перейти ко всем товарам дропа',
						'button_link'     => '/shop/new-drop/',
					]);
					?>
				</div>
			</div>
			<div class="rs-store__gallery">
				<video class="store-video" autoplay muted loop controls poster="<?php echo esc_url(site_url()); ?>/wp-content/themes/wp35-shop/assets/img/new-drop-poster.jpg" style="width:100%;">
					<source src="<?php echo esc_url(site_url()); ?>/wp-content/themes/wp35-shop/assets/video/turbo.mp4" type="video/mp4">
				</video>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
