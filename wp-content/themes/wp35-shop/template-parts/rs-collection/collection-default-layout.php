<?php
/**
 * Default/generic collection page layout.
 *
 * Used as the fallback layout in collection templates that have
 * both a custom store view and a standard collection view.
 * Includes: banner, video/photo, about section, gallery sliders,
 * product catalog, collection slider, and large photo sections.
 *
 * Accepted parameters via $args:
 *   - site_url  : Base URL for new-assets (e.g. site_url() . '/new-assets')
 *   - site_path : Server path for new-assets (e.g. get_home_path() . 'new-assets/')
 *
 * @package dazzling
 */

defined('ABSPATH') || exit;

$site_url  = $args['site_url'] ?? (site_url() . '/new-assets');
$site_path = $args['site_path'] ?? (get_home_path() . 'new-assets/');
?>

<link rel="stylesheet" href="<?php echo esc_url($site_url); ?>/css/rs-style.css?v=<?php echo esc_attr(filemtime($site_path . 'css/rs-style.css')); ?>">
<link rel="stylesheet" href="<?php echo esc_url($site_url); ?>/css/rs-common-style.css?v=<?php echo esc_attr(filemtime($site_path . 'css/rs-common-style.css')); ?>">
<link rel="stylesheet" href="<?php echo esc_url($site_url); ?>/css/rs-footer.css?v=<?php echo esc_attr(filemtime($site_path . 'css/rs-footer.css')); ?>">

<?php while (have_posts()) : the_post(); ?>

<!-- Banner -->
<link rel="stylesheet" href="<?php echo esc_url($site_url); ?>/css/pages/media/rs-banner-video.css">
<section class="rs-banner-video rs-banner-video-5">
	<div class="rs-banner-video_container">
		<div class="rs-banner-video__header">
			<h2 class="section-title"><?php the_title(); ?></h2>
		</div>
	</div>
</section>

<!-- Video or photo hero -->
<link rel="stylesheet" href="<?php echo esc_url($site_url); ?>/css/pages/brand/rs-gallery.css?v=<?php echo esc_attr(filemtime($site_path . 'css/pages/brand/rs-gallery.css')); ?>">
<?php
switch (get_field('otobrazhenie_v_kataloge')) {
	case 'Видео':
		$video_mp4  = get_field('video_mp4');
		$video_webm = get_field('video_webm');
		$poster     = get_field('prevyu_video');

		$video      = !empty($video_mp4['url']) ? $video_mp4['url'] : '';
		$video_webm = !empty($video_webm['url']) ? $video_webm['url'] : '';
		$poster_url = !empty($poster['url']) ? $poster['url'] : '';
		?>
		<section class="rs-banner-video rs-banner-video-2">
			<div class="rs-banner-video_container">
				<div class="rs-banner-video__wrapper rs-banner-video__bg__autoh rs-banner-video__bg__paddingh">
					<div id="video-container" class="rs-banner-video__bg">
						<video class="bgvideo js-bgvideo" loop autoplay muted poster="<?php echo esc_url($poster_url); ?>">
							<source src="<?php echo esc_url($video_webm); ?>" type="video/webm">
							<source src="<?php echo esc_url($video); ?>" type="video/mp4">
						</video>
					</div>
				</div>
				<script>
					(function() {
						var videoBlock = document.querySelector('#video-container');
						if (window.innerWidth >= 992) {
							videoBlock.innerHTML = '<video class="bgvideo js-bgvideo" loop autoplay muted poster="<?php echo esc_url($poster_url); ?>"><source src="<?php echo esc_url($video_webm); ?>" type="video/webm"><source src="<?php echo esc_url($video); ?>" type="video/mp4"></video>';
						} else {
							videoBlock.innerHTML = '<img src="<?php echo esc_url($poster_url); ?>" alt="">';
						}
					})();
				</script>
			</div>
		</section>
		<?php
		break;

	default:
		$big_photo_mob = get_field('bolshoe_foto_mob');
		$big_photo     = get_field('bolshoe_foto');
		if ($big_photo_mob && $big_photo) : ?>
		<section class="rs-gallery rs-gallery-2">
			<div class="rs-gallery_container">
				<div class="rs-gallery__img">
					<img src="<?php echo esc_url($big_photo_mob['url']); ?>" alt="" class="img-mobile">
					<img src="<?php echo esc_url($big_photo['url']); ?>" alt="" class="img-desktop">
				</div>
			</div>
		</section>
		<?php endif;
		break;
}
?>

<!-- About section -->
<link rel="stylesheet" href="<?php echo esc_url($site_url); ?>/css/pages/brand/rs-about.css?v=<?php echo esc_attr(filemtime($site_path . 'css/pages/brand/rs-about.css')); ?>">
<section class="rs-about">
	<div class="rs-about_container">
		<div class="rs-about__text">
			<h3 class="xxl-medium-title"><?php the_title(); ?></h3>
			<?php echo wp_kses_post(get_field('opisanie')); ?>
		</div>
		<?php
		$foto_1 = get_field('foto_1');
		$foto_2 = get_field('foto_2');
		$foto_3 = get_field('foto_3');
		if ($foto_1 && $foto_2 && $foto_3) : ?>
		<div class="rs-about__block rs-about__block-4">
			<div class="rs-about__img">
				<img src="<?php echo esc_url($foto_1['url']); ?>" alt="">
			</div>
			<div class="rs-about__img">
				<img src="<?php echo esc_url($foto_2['url']); ?>" alt="">
			</div>
			<div class="rs-about__img">
				<img src="<?php echo esc_url($foto_3['url']); ?>" alt="">
			</div>
		</div>
		<?php endif; ?>
		<div class="rs-about__block rs-about__block-5">
			<div class="rs-about__text _center">
				<?php echo wp_kses_post(get_field('opisanie_2')); ?>
			</div>
		</div>
	</div>
</section>

<!-- Gallery slider -->
<link rel="stylesheet" href="<?php echo esc_url($site_url); ?>/css/pages/collection-page/rs-collection-gallery.css">
<script src="<?php echo esc_url($site_url); ?>/js/pages/collection-page/rs-collection-gallery.js" defer></script>
<?php
$images = get_field('slider');
if ($images) : ?>
<section class="rs-collection-gallery">
	<div class="rs-collection-gallery_container">
		<div class="rs-collection-gallery__wrapper">
			<div class="rs-collection-gallery__slider swiper swiper-autoheight" auto="true">
				<div class="rs-collection-gallery__swiper swiper-wrapper" auto="true">
					<?php foreach ($images as $image) : ?>
					<div class="rs-collection-gallery__slide swiper-slide">
						<img src="<?php echo esc_url($image['url']); ?>" alt="">
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="rs-collection-gallery__button-prev swiper-button-prev icon-slider-arrow_left"></div>
			<div class="rs-collection-gallery__button-next swiper-button-next icon-slider-arrow_right"></div>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
$big_photo_mob_2 = get_field('bolshoe_foto_mob_2');
$big_photo_2     = get_field('bolshoe_foto_2');
if ($big_photo_mob_2 && $big_photo_2) : ?>
<section class="rs-gallery">
	<div class="rs-gallery_container">
		<div class="rs-gallery__img">
			<img src="<?php echo esc_url($big_photo_mob_2['url']); ?>" alt="" class="img-mobile">
			<img src="<?php echo esc_url($big_photo_2['url']); ?>" alt="" class="img-desktop">
		</div>
	</div>
</section>
<?php endif; ?>

<!-- Product catalog -->
<link rel="stylesheet" href="<?php echo esc_url($site_url); ?>/css/pages/collection-page/rs-collection-catalog.css?v=<?php echo esc_attr(filemtime($site_path . 'css/pages/collection-page/rs-collection-catalog.css')); ?>">
<script src="<?php echo esc_url($site_url); ?>/js/pages/collection-page/rs-collection-catalog.js" defer></script>
<?php
$catalog_posts = get_field('rs-collection');
if ($catalog_posts) : ?>
<section class="rs-collection-catalog">
	<div class="rs-collection-catalog_container">
		<div class="rs-collection-catalog__slider">
			<div class="rs-collection-catalog__swiper">
				<?php foreach ($catalog_posts as $post) :
					setup_postdata($post);
					wc_get_template_part('content', 'product-collection-new');
				endforeach; ?>
				<?php wp_reset_postdata(); ?>
			</div>
		</div>
		<?php if (have_rows('knopka_2')) : ?>
		<?php while (have_rows('knopka_2')) : the_row(); ?>
		<div class="rs-collection-catalog__button">
			<?php if (get_sub_field('ssylka') !== '#') : ?>
			<a <?php if (get_sub_field('ssylka') !== '') : ?>href="<?php the_sub_field('ssylka'); ?>" <?php endif; ?>class="rs-btn <?php the_sub_field('klass'); ?>">
				<?php the_sub_field('nazvanie'); ?>
			</a>
			<?php endif; ?>
		</div>
		<?php endwhile; ?>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<?php
$images_2 = get_field('slider_2');
if ($images_2) : ?>
<section class="rs-collection-gallery">
	<div class="rs-collection-gallery_container">
		<div class="rs-collection-gallery__wrapper">
			<div class="rs-collection-gallery__slider swiper">
				<div class="rs-collection-gallery__swiper swiper-wrapper">
					<?php foreach ($images_2 as $image) : ?>
					<div class="rs-collection-gallery__slide swiper-slide">
						<img src="<?php echo esc_url($image['url']); ?>" alt="">
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="rs-collection-gallery__button-prev swiper-button-prev icon-slider-arrow_left"></div>
			<div class="rs-collection-gallery__button-next swiper-button-next icon-slider-arrow_right"></div>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- Collection slider -->
<link rel="stylesheet" href="<?php echo esc_url($site_url); ?>/css/pages/brand/rs-collection-slider.css?v=<?php echo esc_attr(filemtime($site_path . 'css/pages/brand/rs-collection-slider.css')); ?>">
<script src="<?php echo esc_url($site_url); ?>/js/pages/brand/rs-collection-slider.js?v=<?php echo esc_attr(filemtime($site_path . 'js/pages/brand/rs-collection-slider.js')); ?>" defer></script>
<?php if (have_rows('kollekczii')) : ?>
<section class="rs-collection-slider rs-collection-slider-2">
	<div class="rs-collection-slider_container">
		<?php
		$col_title = get_field('zagolovok_kollekczii');
		$col_desc  = get_field('opisanie_kollekczii');
		if ($col_title || $col_desc) : ?>
		<div class="rs-collection-slider__text">
			<?php if ($col_title) : ?><h3 class="xxl-medium-title"><?php echo esc_html($col_title); ?></h3><?php endif; ?>
			<?php if ($col_desc) : ?><p class="large-text"><?php echo esc_html($col_desc); ?></p><?php endif; ?>
		</div>
		<?php endif; ?>
		<div class="rs-collection-slider__wrapper">
			<div class="rs-collection-slider__slider swiper">
				<div class="rs-collection-slider__swiper swiper-wrapper">
					<?php while (have_rows('kollekczii')) : the_row(); ?>
					<div class="rs-collection-slider__slide swiper-slide">
						<div class="rs-collection-slider__item">
							<a href="<?php the_sub_field('ssylka'); ?>" class="rs-collection-slider__link">
								<div class="rs-collection-slider__img">
									<?php $col_img = get_sub_field('izobrazhenie'); ?>
									<img src="<?php echo esc_url($col_img['url'] ?? ''); ?>" alt="">
								</div>
								<div class="rs-collection-slider__description">
									<h6 class="sm-bold-title"><?php the_sub_field('nazvanie'); ?></h6>
								</div>
							</a>
						</div>
					</div>
					<?php endwhile; ?>
				</div>
			</div>
			<div class="rs-collection-slider__button-prev swiper-button-prev icon-slider-arrow_left"></div>
			<div class="rs-collection-slider__button-next swiper-button-next icon-slider-arrow_right"></div>
			<?php if (have_rows('knopka')) : ?>
			<?php while (have_rows('knopka')) : the_row(); ?>
			<div class="rs-collection-slider__button">
				<a <?php if (get_sub_field('ssylka') !== '') : ?>href="<?php the_sub_field('ssylka'); ?>" <?php endif; ?>class="rs-btn <?php the_sub_field('klass'); ?>">
					<?php the_sub_field('nazvanie'); ?>
				</a>
			</div>
			<?php endwhile; ?>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
$big_photo_mob_3 = get_field('bolshoe_foto_mob_3');
$big_photo_3     = get_field('bolshoe_foto_3');
if ($big_photo_mob_3 && $big_photo_3) : ?>
<section class="rs-gallery">
	<div class="rs-gallery_container">
		<div class="rs-gallery__img">
			<img src="<?php echo esc_url($big_photo_mob_3['url']); ?>" alt="" class="img-mobile">
			<img src="<?php echo esc_url($big_photo_3['url']); ?>" alt="" class="img-desktop">
		</div>
	</div>
</section>
<?php endif; ?>

<?php endwhile; ?>
