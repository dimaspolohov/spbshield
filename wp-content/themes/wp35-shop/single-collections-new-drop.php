<?php
/**
 * Template Name: New Drop
 * Template Post Type: collections
 *
 * Store-layout template for the "New Drop" collection.
 * Products are loaded via WP_Query from the ACF 'kategoriya_kollekczii' category.
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

<?php
$collection_category = get_field('kategoriya_kollekczii');
$category_link       = $collection_category ? get_term_link($collection_category) : '';
$category_btn_text   = get_field('zagolovok_button') ?: '';
?>

<div class="rs-store">
	<div class="rs-store__container">
		<div class="rs-store__wrapper">
			<div class="rs-store__content">
				<div class="store-content">
					<?php
					get_template_part('template-parts/rs-collection/collection-header');

					get_template_part('template-parts/rs-collection/collection-store', null, [
						'products_source' => 'category',
						'button_text'     => $category_btn_text,
						'button_link'     => is_string($category_link) ? $category_link : '',
					]);
					?>
				</div>
			</div>
			<?php if (have_rows('slajder')) : ?>
			<div class="rs-store__gallery">
				<div class="store-gallery gallery-drop-new">
					<?php while (have_rows('slajder')) : the_row();
						$slide_image = get_sub_field('izobrazhenie');
						$slide_url   = is_array($slide_image) ? $slide_image['url'] : '';
					?>
					<a href="<?php echo esc_url($slide_url); ?>" data-fancybox class="store-gallery__item">
						<picture>
							<source media="(max-width: 1024px)" srcset="<?php echo esc_url($slide_url); ?>">
							<img src="<?php echo esc_url($slide_url); ?>" loading="lazy" alt="">
						</picture>
					</a>
					<?php endwhile; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
