<?php
/**
* Template Name: Бренд
*/

$site_url = site_url().'/new-assets';
$site_path = get_home_path().'new-assets/';
get_header(); ?>

<link rel="stylesheet" href="<?=$site_url?>/css/rs-style.css?v=<?=filemtime( $site_path . 'css/rs-style.css' )?>">
<link rel="stylesheet" href="<?=$site_url?>/css/rs-common-style.css?v=<?=filemtime( $site_path . 'css/rs-common-style.css' )?>">
<link rel="stylesheet" href="<?=$site_url?>/css/rs-footer.css?v=<?=filemtime( $site_path . 'css/rs-footer.css' )?>">

<?php if( have_rows('slajder') ): ?>
<!-- rs-slider -->
<link rel="stylesheet" href="<?=$site_url?>/css/pages/main/rs-slider.css?v=<?=filemtime( $site_path . 'css/pages/main/rs-slider.css' )?>">
<script src="<?=$site_url?>/js/pages/main/rs-slider.js?v=<?=filemtime( $site_path . 'js/pages/main/rs-slider.js' )?>" defer></script>
<section class="rs-slider rs-slider-brand">
	<div class="rs-slider-brand-wrapper">
		<div class="rs-slider__slider swiper">
			<div class="rs-slider__swiper swiper-wrapper">
				<?php while( have_rows('slajder') ): the_row(); ?>
				<div class="rs-slider__slide swiper-slide rs-slider__slide-100">
					<div class="rs-slider__item">
						<div class="rs-slider__bg">
							<img src="<?=get_sub_field('izobrazhenie_mob')['url']?>" alt="" class="img-mobile">
							<img src="<?=get_sub_field('izobrazhenie')['url']?>" alt="" class="img-desktop">
						</div>
					</div>
				</div>
				<?php endwhile; ?>
			</div>
			<div class="rs-slider__button-prev swiper-button-prev icon-slider-arrow_left"></div>
			<div class="rs-slider__button-next swiper-button-next icon-slider-arrow_right"></div>
			<div class="rs-slider__pagination swiper-pagination"></div>
		</div>
	</div>
</section>
<!-- /rs-slider -->
<?php endif; ?>

<link rel="stylesheet" href="<?=$site_url?>/css/pages/brand/rs-about.css?v=<?=filemtime( $site_path . 'css/pages/brand/rs-about.css' )?>">
<section class="rs-about">
	<div class="rs-about__container">
		<div class="rs-about__text">
			<h3 class="xxl-medium-title"><?=get_field('zagolovok')?></h3>
			<?=get_field('opisanie_1')?>
		</div>
		<div class="rs-about__block rs-about__block-1">
			<div class="rs-about__logo">
				<img src="<?=get_field('logo')['url']?>" alt="">
			</div>
			<div class="rs-about__img">
				<img src="<?=get_field('foto_1')['url']?>" alt="" class="img-desktop">
				<img src="<?=get_field('foto_mob_1')['url']?>" alt="" class="img-mobile">
			</div>
		</div>
		<div class="rs-about__block rs-about__block-2">
			<div class="rs-about__img">
				<img src="<?=get_field('foto_2')['url']?>" alt="" class="img-desktop">
				<img src="<?=get_field('foto_mob_2')['url']?>" alt="" class="img-mobile">
			</div>
			<div class="rs-about__text">
				<?=get_field('opisanie_2')?>
				<div class="rs-about__img">
					<img src="<?=get_field('foto_3')['url']?>" alt="" class="img-desktop">
					<img src="<?=get_field('foto_mob_3')['url']?>" alt="" class="img-mobile">
				</div>
			</div>
		</div>
		<div class="rs-about__block rs-about__block-3">
			<div class="rs-about__img">
				<img src="<?=get_field('foto_4')['url']?>" alt="" class="img-desktop">
				<img src="<?=get_field('foto_mob_4')['url']?>" alt="" class="img-mobile">
			</div>
			<div class="rs-about__img">
				<img src="<?=get_field('foto_5')['url']?>" alt="" class="img-desktop">
				<img src="<?=get_field('foto_mob_5')['url']?>" alt="" class="img-mobile">
			</div>
		</div>
	</div>
</section>


<?php if( have_rows('kollekczii') ): ?>
<link rel="stylesheet" href="<?=$site_url?>/css/pages/brand/rs-collection-slider.css?v=<?=filemtime( $site_path . 'css/pages/brand/rs-collection-slider.css' )?>">
<script src="<?=$site_url?>/js/pages/brand/rs-collection-slider.js?v=<?=filemtime( $site_path . 'js/pages/brand/rs-collection-slider.js' )?>" defer></script>
<section class="rs-collection-slider">
	<div class="rs-collection-slider__container">
		<div class="rs-collection-slider__text">
			<h3 class="xxl-medium-title"><?=get_field('zagolovok_kollekczii')?></h3>
			<p class="large-text"><?=get_field('opisanie_kollekczii')?></p>
		</div>

		<div class="rs-collection-slider__wrapper">
			<div class="rs-collection-slider__slider swiper">
				<div class="rs-collection-slider__swiper swiper-wrapper">
					<?php while( have_rows('kollekczii') ): the_row(); ?>
					<div class="rs-collection-slider__slide swiper-slide">
						<div class="rs-collection-slider__item">
							<a href="<?php the_sub_field('ssylka'); ?>" class="rs-collection-slider__link">
								<div class="rs-collection-slider__img">
									<img src="<?=get_sub_field('izobrazhenie')['url']?>" alt="">
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
			<?php if( have_rows('knopka') ): ?>
			<?php while( have_rows('knopka') ): the_row(); ?>
			<div class="rs-collection-slider__button">
				<a <?php if(get_sub_field('ssylka')!='') {?>href="<?php the_sub_field('ssylka'); ?>" <?php }?>class="rs-btn <?php the_sub_field('klass'); ?>">
					<?php the_sub_field('nazvanie'); ?>
				</a>
			</div>
			<?php endwhile; ?>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<link rel="stylesheet" href="<?=$site_url?>/css/pages/brand/rs-gallery.css?v=<?=filemtime( $site_path . 'css/pages/brand/rs-gallery.css' )?>">
<section class="rs-gallery">
	<div class="rs-gallery__container">
		<div class="rs-gallery__img">
			<img src="<?=get_field('bolshoe_foto_mob')['url']?>" alt="" class="img-mobile">
			<img src="<?=get_field('bolshoe_foto')['url']?>" alt="" class="img-desktop">
		</div>
	</div>
</section>

<?php if( have_rows('komandy') ): ?>
<link rel="stylesheet" href="<?=$site_url?>/css/pages/brand/rs-team-list.css?v=<?=filemtime( $site_path . 'css/pages/brand/rs-team-list.css' )?>">
<script src="<?=$site_url?>/js/pages/brand/rs-team-list.js?v=<?=filemtime( $site_path . 'js/pages/brand/rs-team-list.js' )?>" defer></script>
<?php while( have_rows('komandy') ): the_row(); ?>
<section class="rs-team-list <?php the_sub_field('klass'); ?>">
	<div class="rs-team-list__container">
		<div class="rs-team-list__wrapper">
			<h3 class="xxl-medium-title"><?php the_sub_field('zagolovok'); ?></h3>
			<div class="rs-team-list__slider swiper">
				<div class="rs-team-list__swiper swiper-wrapper">
					<?php while( have_rows('komanda') ): the_row(); ?>
					<div class="rs-team-list__slide swiper-slide">
						<div class="rs-team-list__item">
							<a href="<?php the_sub_field('ssylka'); ?>" target="_blank">
								<div class="rs-team-list__img">
									<img src="<? if(get_sub_field('foto')) echo get_sub_field('foto')['url']; else echo $site_url . '/img/pages/brand/member_fish.jpg'?>" alt="">
								</div>
								<div class="rs-team-list__desc">
									<h6 class="sm-bold-title icon-inst"><?php the_sub_field('imya'); ?></h6>
									<span><?php the_sub_field('dolzhnost'); ?></span>
								</div>
							</a>
						</div>
					</div>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php endwhile; ?>
<?php endif; ?>

<link rel="stylesheet" href="<?=$site_url?>/css/pages/brand/rs-gallery.css?v=<?=filemtime( $site_path . 'css/pages/brand/rs-gallery.css' )?>">
<section class="rs-gallery">
	<div class="rs-gallery__container">
		<div class="rs-gallery__img">
			<img src="<?=get_field('bolshoe_foto_mob_2')['url']?>" alt="" class="img-mobile">
			<img src="<?=get_field('bolshoe_foto_2')['url']?>" alt="" class="img-desktop">
		</div>
	</div>
</section>

<?php get_footer();