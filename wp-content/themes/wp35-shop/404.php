<?php
/**
* The template for displaying 404 pages (not found).
*
* @package storefront
*/

$site_url = site_url();
get_header(); ?>
<link rel="stylesheet" href="<?=$site_url?>/css/pages/404/rs-error.css?v=<?=filemtime( get_home_path() . 'css/pages/404/rs-error.css' )?>">
<script src="<?=$site_url?>/js/pages/404/rs-error.js" defer></script>
<section class="rs-error">
	<div class="rs-error__content">
		<img src="<?=$site_url?>/img/pages/404/404.png" alt="">
		<a href="<?=get_permalink(get_option('woocommerce_shop_page_id'))?>" class="rs-btn _black-btn"><?_e('Вернуться в каталог')?></a>
	</div>
	<div class="frame">
		<div></div>
		<div></div>
		<div></div>
	</div>
	<div class="caps"><img src="http://ademilter.com/caps.png" alt=""></div>
	<canvas id="canvas"></canvas>
</section>	
<?php get_footer();