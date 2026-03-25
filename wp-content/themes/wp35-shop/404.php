<?php
/**
* The template for displaying 404 pages (not found).
*
* @package storefront
*/

$site_url = site_url();
get_header(); ?>
<link rel="stylesheet" href="<?php echo esc_url($site_url . '/css/pages/404/rs-error.css?v=' . filemtime( get_home_path() . 'css/pages/404/rs-error.css' )); ?>">
<script src="<?php echo esc_url($site_url . '/js/pages/404/rs-error.js'); ?>" defer></script>
<section class="rs-error">
	<div class="rs-error__content">
		<img src="<?php echo esc_url($site_url . '/img/pages/404/404.png'); ?>" alt="">
		<a href="<?php echo esc_url(get_permalink(get_option('woocommerce_shop_page_id'))); ?>" class="rs-btn _black-btn"><?php _e('Вернуться в каталог', 'storefront'); ?></a>
	</div>
	<div class="frame">
		<div></div>
		<div></div>
		<div></div>
	</div>
	<canvas id="canvas"></canvas>
</section>	
<?php get_footer();
