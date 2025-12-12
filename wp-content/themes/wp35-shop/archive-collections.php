<?php
/**
 * The template for displaying archive-collections pages.
 *
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package storefront
 */

if ( have_posts() ) :
$site_url = site_url().'/new-assets';
$site_path = get_home_path().'new-assets/';
get_header();
?>

<link rel="stylesheet" href="<?=$site_url?>/css/rs-style.css?v=<?=filemtime( $site_path . 'css/rs-style.css' )?>">
<link rel="stylesheet" href="<?=$site_url?>/css/rs-common-style.css?v=<?=filemtime( $site_path . 'css/rs-common-style.css' )?>">
<link rel="stylesheet" href="<?=$site_url?>/css/rs-footer.css?v=<?=filemtime( $site_path . 'css/rs-footer.css' )?>">
<link rel="stylesheet" href="<?=$site_url?>/css/pages/media/rs-banner-video.css?v=<?=filemtime( $site_path . 'css/pages/media/rs-banner-video.css' )?>">
<script src="<?=$site_url?>/js/pages/media/rs-banner-video.js" defer></script>


<?php 
$index = 0;

while ( have_posts() ) : the_post(); $index++; ?>

<? if($index<4) { ?>

<? switch(get_field('otobrazhenie_v_kataloge')) {
	case 'Видео':
        $video = get_field("video_mp4")['url'] ?: null;
        $video_webm = get_field("video_webm")['url'] ?: null;
		?>
<section class="rs-banner-video rs-banner-video-2">
	<div class="rs-banner-video_container">
		<? if($index==1) { ?>

		<? }?>
		<div class="rs-banner-video__wrapper rs-banner-video__bg__autoh rs-banner-video__bg__paddingh">
			<a href="<?php the_permalink()?>" id="video-container<? echo $rand = rand(1000,9999)?>" class="rs-banner-video__bg">
				<video class="bgvideo js-bgvideo" loop="" autoplay="" muted="" poster="<?=get_field('prevyu_video')['url']?>">
					<source data-src="<?=$video_webm?>" src="<?=$video_webm?>" type="video/webm">
					<source data-src="<?=$video?>" src="<?=$video?>" type="video/mp4">
				</video>
			</a>
		</div>
		<div class="rs-banner-video__about">
			<div class="rs-banner-video__about_body">
				<a href="<?php the_permalink()?>" class="xxl-regular-title"><?
				$title = get_field('zagolovok_v_kataloge');
				if(!$title||$title=='') $title = get_the_title();
				echo $title;				
				?></a>
				<p class="large-text"><?php the_field('opisanie_v_kataloge')?></p>
				<a href="<?php the_permalink()?>" class="rs-btn _black-border-btn">
					<?php _e( 'Посмотреть', 'storefront' ); ?>
				</a>
			</div>
		</div>
        <script>
            add_video<?=$rand?>();
            function add_video<?=$rand?>(){
                let videoBlock=document.querySelector('#video-container<?=$rand?>');
                if(window.innerWidth >= 992) {
                    videoBlock.innerHTML = '<video class="bgvideo js-bgvideo" loop="" autoplay="" muted="" poster="<?=get_field('prevyu_video')['url']?>"><source  src="<?=$video_webm?>" type="video/webm"><source  src="<?=$video?>" type="video/mp4"></video>'
                } else {
                    videoBlock.innerHTML ='<img src="<?=get_field('prevyu_video')['url']?>"  alt="">'
                }
            };
        </script>
	</div>
</section>
		<?		
	break;
	case 'Слайдер 1':
		?>
<section class="rs-banner-video rs-banner-video-3">
	<div class="rs-banner-video_container">
		<div class="rs-banner-video__wrapper rs-banner-video__bg__autoh rs-banner-video__bg__nopadd">
			<a href="<?php the_permalink()?>" class="rs-banner-video__bg rs-banner-video__bg__stat">
				<div class="rs-banner-video__slider swiper">
					<div class="rs-banner-video__swiper swiper-wrapper">
						<?php while( have_rows('slajder') ): the_row(); ?>
						<div class="rs-banner-video__slide swiper-slide">
							<div class="rs-banner-video__img">
								<img class="img-desktop" src="<?=get_sub_field('izobrazhenie')['url']?>" alt="">
								<img class="img-mobile" src="<?=get_sub_field('izobrazhenie_mob')['url']?>" alt="">
							</div>
						</div>
						<?php endwhile; ?>
					</div>
				</div>
			</a>
			<div class="rs-banner-video__description">
				<div class="rs-banner-video__btn">
					<div class="rs-banner-video__navigation swiper-navigation">
						<div class="rs-banner-video__button-prev swiper-button-prev icon-slider-arrow_left"></div>
						<div class="rs-banner-video__button-next swiper-button-next icon-slider-arrow_right"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="rs-banner-video__about">
			<div class="rs-banner-video__about_body">
				<a href="<?php the_permalink()?>" class="xxl-regular-title"><?
				$title = get_field('zagolovok_v_kataloge');
				if(!$title||$title=='') $title = get_the_title();
				echo $title;				
				?></a>
				<p class="large-text"><?php the_field('opisanie_v_kataloge')?></p>
				<a href="<?php the_permalink()?>" class="rs-btn _black-border-btn">
					<?php _e( 'Посмотреть', 'storefront' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
		<?		
	break;
	case 'Слайдер 2':
		?>
<section class="rs-banner-video rs-banner-video-4">
	<div class="rs-banner-video_container">
		<div class="rs-banner-video__wrapper">
			<div class="rs-banner-video__bg">
				<div class="rs-banner-video__slider swiper">
					<div class="rs-banner-video__swiper swiper-wrapper">
						<?
						$title = get_field('zagolovok_v_kataloge');
						if(!$title||$title=='') $title = get_the_title();
						$description = get_field('opisanie_v_kataloge');
						?>
						<?php while( have_rows('slajder') ): the_row(); ?>
						<div class="rs-banner-video__slide swiper-slide">
							<div class="rs-banner-video__img">
								<img class="img-desktop" src="<?=get_sub_field('izobrazhenie')['url']?>" alt="">
								<img class="img-mobile" src="<?=get_sub_field('izobrazhenie_mob')['url']?>" alt="">
							</div>
							<div class="rs-banner-video__text">
								<h2 class="large-title"><?=$title?></h2>
								<p class="large-text"><?=$description?></p>
								<a href="<?php the_permalink()?>" class="rs-link"><span><?php _e( 'Посмотреть', 'storefront' ); ?></span> <i class="icon-slider-arrow_right"></i></a>
								<a href="<?php the_permalink()?>" class="rs-btn _black-border-btn"><?php _e( 'Посмотреть', 'storefront' ); ?> </a>
							</div>
						</div>
						<?php endwhile; ?>
					</div>
				</div>
			</div>
			<div class="rs-banner-video__description">
				<div class="rs-banner-video__btn">
					<div class="rs-banner-video__navigation swiper-navigation">
						<div class="rs-banner-video__button-prev swiper-button-prev icon-slider-arrow_left"></div>
						<div class="rs-banner-video__button-next swiper-button-next icon-slider-arrow_right"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
		<?
	break;
	default:
		?>
<section class="rs-banner-video rs-banner-video-3">
	<div class="rs-banner-video_container">
		<div class="rs-banner-video__about">
			<div class="rs-banner-video__about_body">
				<h3 class="xxl-regular-title"><?
				$title = get_field('zagolovok_v_kataloge');
				if(!$title||$title=='') $title = get_the_title();
				echo $title;				
				?></h3>
				<p class="large-text"><?php the_field('opisanie_v_kataloge')?></p>
				<a href="<?php the_permalink()?>" class="rs-btn _black-border-btn">
					<?php _e( 'Посмотреть', 'storefront' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
		<?		
	break;
}?>

<? } else {?>
	<? if($index==4) { ?>
	<link rel="stylesheet" href="<?=$site_url?>/css/pages/collection/rs-old-collection.css?v=<?=filemtime( $site_path . 'css/pages/collection/rs-old-collection.css' )?>">
	<script src="<?=$site_url?>/js/pages/collection/rs-old-collection.js?v=<?=filemtime( $site_path . 'js/pages/collection/rs-old-collection.js' )?>" defer></script>
	<section class="rs-old-collection">
		<div class="rs-old-collection_container">
			<h2 class="large-title" data-aos="fade" data-aos-delay="0"><?php _e( 'Прошлые коллекции', 'storefront' ); ?></h2>
			<div class="rs-old-collection__wrapper">
				<div class="rs-old-collection__slider swiper">
					<div class="rs-old-collection__swiper swiper-wrapper">
	<? }?>
	<div class="rs-old-collection__slide swiper-slide" data-aos="fade" data-aos-delay="100">
		<div class="rs-old-collection__item">
			<a href="<?php the_permalink()?>">
				<div class="rs-old-collection__picture">
					<img src="<?
					$img = get_the_post_thumbnail_url();
					if(!$img||$img=='') $img = $site_url . '/img/pages/brand/fish.jpg';
					echo $img; ?>" alt="">
				</div>
				<div class="rs-old-collection__description">
					<div class="rs-old-collection__title">
						<h4 class="l-semibold-title"><?
						$title = get_field('zagolovok_v_kataloge');
						if(!$title||$title=='') $title = get_the_title();
						echo $title;				
						?></h4>
					</div>
				</div>
			</a>
		</div>
	</div>
<? } ?>

<?php endwhile; ?>

<? if($index>3) { ?>
				</div>
				<div class="rs-slider__button-prev swiper-button-prev icon-slider-arrow_left"></div>
				<div class="rs-slider__button-next swiper-button-next icon-slider-arrow_right"></div>
			</div>
		</div>
	</div>
</section>
<? }?>

<?
get_footer();
else:
wp_redirect( site_url() );
endif;