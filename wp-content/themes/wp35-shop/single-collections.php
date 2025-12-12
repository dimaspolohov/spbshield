<?php
/**
* The Template for displaying all single-collections posts.
*
 *
 * * @package dazzling
*/

$site_url = site_url().'/new-assets';
$site_path = get_home_path().'new-assets/';


$custom_ids = array(155891); // <- замените ID на нужный, можно несколько: [130465, 132856]

if ( is_singular('collections') && in_array( get_the_ID(), $custom_ids, true ) ) {
    // Ищем шаблон в текущей (или дочерней) теме
    $tpl = locate_template('single-collections-newdrop.php');
    if ( $tpl ) {
        include $tpl; // подключаем ваш отдельный шаблон
        return;      // прекращаем выполнение основного шаблона
    }
}

get_header(); 



if( get_the_ID()==130465 || get_the_ID()==132856 || get_the_ID()==155891 ) {	
	?>
<script src="<?=$site_url?>/812/js/swiper-bundle.min.js" defer></script>
<link rel="stylesheet" type="text/css" href="<?=$site_url?>/812/css/swiper-bundle.min.css" />
<link rel="stylesheet" type="text/css" href="<?=$site_url?>/812/css/block-css/style.css?v=<?=filemtime( $site_path . '812/css/block-css/style.css' )?>" />

<link rel="stylesheet" type="text/css" href="<?=$site_url?>/css/fancybox.css" />


<script src="<?=$site_url?>/js/jquery-2.1.3.min.js" defer></script>
<script src="<?=$site_url?>/js/fancybox.js" defer></script>
<script src="<?=$site_url?>/812/js/app.js" defer></script>

<link rel="stylesheet" href="<?=$site_url?>/812/css/block-css/rs-store.css" />
<div class="rs-store">	
	<div class="rs-store__container">
		<div class="rs-store__wrapper">
			<div class="rs-store__content">
				<div class="store-content">
					<div class="store-content__about">
<div class="store-about">
	<h2 class="store-about__title"><b><?= get_field('zagolovok_v_kataloge'); ?></b></h2>
	<p class="store-about__text"><?= get_field('opisanie_v_kataloge'); ?></p>
</div>
</div>

<?php if( have_rows('tovary') ): ?>
<div class="store-content__goods">
<div class="store-goods">
	<!--h3 class="store-goods__title">[Товары из коллекции]</h3-->
	<br/><br/><br/><br/>
	<div class="store-goods__slider">
    <div class="store-goods__swiper">
<?php while( have_rows('tovary') ): the_row(); $image = get_sub_field('kartinka')['ID']; 
	$product_id = get_sub_field('tovar');
	$product = wc_get_product( $product_id );
	if($product->is_type( 'variable' )){
		$regular_price = $product->get_variation_regular_price( 'min' );
		$sale_price = $product->get_variation_sale_price( 'min' );
	} else {
		$regular_price = $product->get_regular_price();
		$sale_price = $product->get_sale_price();
	}
	?>
	<div class="store-goods__slide">
		<article class="goods-card">	
			<div class="goods-card__wrapper">
				<div class="goods-card__photo"><?php echo wp_get_attachment_image( $image, 'full' ); ?></div>
				<!--div class="goods-card__category"><?//=get_sub_field('kategoriya')?></div-->

				<h5 class="goods-card__title"><?=get_sub_field('nazvanie')?></h5>
				<div class="goods-card__price">
					<?
					if($sale_price&&$sale_price!=$regular_price) {
						?><del><?
						echo number_format( $regular_price, 0,',','&nbsp;' ) . '&nbsp;₽';
						?></del><?
					}
					?>
					<span>
					<?
					if($sale_price) {
						echo number_format( $sale_price, 0,',','&nbsp;' ) . '&nbsp;₽';
					} else {
						echo number_format( $regular_price, 0,',','&nbsp;' ) . '&nbsp;₽';
					}
					?></span>
				</div>
			</div>
			<a href="<?=get_permalink( $product_id )?>" target="_blank"><?=get_sub_field('kategoriya')?></a>
		</article>
	</div>
<?php endwhile; ?>      
    </div>
  </div>
  <div class="store-goods__nav">
    <div class="slider-arrows">
			<button type="button" class="slider-arrow slider-arrow_prev">
				<svg viewBox="0 0 14 26" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M13 1L1 13L13 25" stroke="black" />
				</svg>
			</button>
			<button type="button" class="slider-arrow slider-arrow_next">
				<svg viewBox="0 0 14 26" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M1 1L13 13L1 25" stroke="black" />
				</svg>
			</button>
		</div>
  </div>
</div>
</div>
<?php endif; ?>
<br/><br/>
					<a href="https://www.spbshield.ru/shop/812/" class="rs-btn _black-border-btn">Перейти ко всем товарам коллекции!</a>

				</div>
			</div>

			
			<div class="rs-store__gallery">
				<a href="https://youtu.be/Rbdjg4TPwqk" class="store-video" target="_blank">
					<img src="<?=$site_url?>/812/img/video_link.jpg" alt=""> 
				</a>
<div class="store-gallery">

<a href="<?=$site_url?>/812/img/gallery_812_26@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_26.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_26@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_3@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_3.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_3@2x.jpg" loading="lazy" alt="">
	</picture>
</a>
	
<a href="<?=$site_url?>/812/img/gallery_812_23@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_23.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_23@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_19@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_19.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_19@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_4@2x.jpg" data-fancybox class="store-gallery__item store-gallery__item_wide">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_4.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_4@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_17@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_17.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_17@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_11@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_11.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_11@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_10@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_10.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_10@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_15@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_15.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_15@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_14@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_14.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_14@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_13@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_13.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_13@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_5@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_5.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_5@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_6@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_6.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_6@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_22@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_22.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_22@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_21@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_21.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_21@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_1@2x.jpg" data-fancybox class="store-gallery__item store-gallery__item_wide">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_1.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_1@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_2@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_2.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_2@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_8@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_8.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_8@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_12@2x.jpg" data-fancybox class="store-gallery__item store-gallery__item_wide">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_12.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_12@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_7@2x.jpg" data-fancybox class="store-gallery__item store-gallery__item_wide">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_7.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_7@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_25@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_25.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_25@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_24@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_24.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_24@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_16@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_16.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_16@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_16@2x_new.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_16_new.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_16@2x_new.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_18@2x.jpg" data-fancybox class="store-gallery__item store-gallery__item_wide">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_18.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_18@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_9@2x_new.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_9_new.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_9@2x_new.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?=$site_url?>/812/img/gallery_812_27@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?=$site_url?>/812/img/gallery_812_27.jpg">
		<img src="<?=$site_url?>/812/img/gallery_812_27@2x.jpg" loading="lazy" alt="">
	</picture>
</a>
	


	


	


	


	

	


	


</div>

			</div>
		</div>		
	</div>
</div>
	<?
	
} else {
?>

<link rel="stylesheet" href="<?=$site_url?>/css/rs-style.css?v=<?=filemtime( $site_path . 'css/rs-style.css' )?>">
<link rel="stylesheet" href="<?=$site_url?>/css/rs-common-style.css?v=<?=filemtime( $site_path . 'css/rs-common-style.css' )?>">
<link rel="stylesheet" href="<?=$site_url?>/css/rs-footer.css?v=<?=filemtime( $site_path . 'css/rs-footer.css' )?>">

<?php while ( have_posts() ) : the_post(); ?>

<!-- rs-banner-video -->
<link rel="stylesheet" href="<?=$site_url?>/css/pages/media/rs-banner-video.css">
<section class="rs-banner-video rs-banner-video-5">
	<div class="rs-banner-video_container">
		<div class="rs-banner-video__header">
			<h2 class="section-title"><?php the_title()?></h2>
		</div>
	</div>
</section>
<!-- /rs-banner-video -->

<link rel="stylesheet" href="<?=$site_url?>/css/pages/brand/rs-gallery.css?v=<?=filemtime( $site_path . 'css/pages/brand/rs-gallery.css' )?>">
<?php 
switch(get_field('otobrazhenie_v_kataloge')) {
	case 'Видео':
        $video = get_field("video_mp4")['url'] ?: null;
        $video_webm = get_field("video_webm")['url'] ?: null;
		?>
		<section class="rs-banner-video rs-banner-video-2">
			<div class="rs-banner-video_container">
				<div class="rs-banner-video__wrapper rs-banner-video__bg__autoh rs-banner-video__bg__paddingh">
					<div href="<?php the_permalink()?>" id="video-container" class="rs-banner-video__bg">
						<video class="bgvideo js-bgvideo" loop="" autoplay="" muted="" poster="<?=get_field('prevyu_video')['url']?>">
							<source data-src="<?=$video_webm?>" src="<?=$video_webm?>" type="video/webm">
							<source data-src="<?=$video?>" src="<?=$video?>" type="video/mp4">
						</video>
					</div>
				</div>
				<script>
					add_video();
					function add_video(){
						let videoBlock=document.querySelector('#video-container');
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
	default:
		if(get_field('bolshoe_foto_mob')&&get_field('bolshoe_foto')){?>
		<section class="rs-gallery rs-gallery-2">
			<div class="rs-gallery_container">
				<div class="rs-gallery__img">
					<img src="<?=get_field('bolshoe_foto_mob')['url']?>" alt="" class="img-mobile">
					<img src="<?=get_field('bolshoe_foto')['url']?>" alt="" class="img-desktop">
				</div>
			</div>
		</section>
		<?php }
	break;
}?>

<link rel="stylesheet" href="<?=$site_url?>/css/pages/brand/rs-about.css?v=<?=filemtime( $site_path . 'css/pages/brand/rs-about.css' )?>">
<section class="rs-about">
	<div class="rs-about_container">
		<div class="rs-about__text">
			<h3 class="xxl-medium-title"><?php the_title()?></h3>
			<? the_field('opisanie') ?>
		</div>
		<?php if(get_field('foto_1')&&get_field('foto_2')&&get_field('foto_3')){?>
		<div class="rs-about__block rs-about__block-4">
			<div class="rs-about__img">
				<img src="<?=get_field('foto_1')['url']?>" alt="">
			</div>
			<div class="rs-about__img">
				<img src="<?=get_field('foto_2')['url']?>" alt="">
			</div>
			<div class="rs-about__img">
				<img src="<?=get_field('foto_3')['url']?>" alt="">
			</div>
		</div>
		<?php } ?>
		<div class="rs-about__block rs-about__block-5">
			<div class="rs-about__text _center">
				<? the_field('opisanie_2') ?>
			</div>
		</div>
	</div>
</section>

<link rel="stylesheet" href="<?=$site_url?>/css/pages/collection-page/rs-collection-gallery.css">
<script src="<?=$site_url?>/js/pages/collection-page/rs-collection-gallery.js" defer></script>
<?php 
$images = get_field('slider');
if( $images ): ?>
<section class="rs-collection-gallery">
	<div class="rs-collection-gallery_container">
		<div class="rs-collection-gallery__wrapper">
			<div class="rs-collection-gallery__slider swiper swiper-autoheight" auto="true">
				<div class="rs-collection-gallery__swiper swiper-wrapper" auto="true">
					<?php foreach( $images as $image ): ?>
					<div class="rs-collection-gallery__slide swiper-slide">
						<img src="<?php echo esc_url($image['url']); ?>" alt="">
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="rs-collection-gallery__button-prev swiper-button-prev icon-slider-arrow_left"></div>
			<div class="rs-collection-gallery__button-next swiper-button-next icon-slider-arrow_right">
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if(get_field('bolshoe_foto_mob_2')&&get_field('bolshoe_foto_2')){?>
<section class="rs-gallery">
	<div class="rs-gallery_container">
		<div class="rs-gallery__img">
			<img src="<?=get_field('bolshoe_foto_mob_2')['url']?>" alt="" class="img-mobile">
			<img src="<?=get_field('bolshoe_foto_2')['url']?>" alt="" class="img-desktop">
		</div>
	</div>
</section>
<?php } ?>

<link rel="stylesheet" href="<?=$site_url?>/css/pages/collection-page/rs-collection-catalog.css?v=<?=filemtime( $site_path . 'css/pages/collection-page/rs-collection-catalog.css' )?>">
<script src="<?=$site_url?>/js/pages/collection-page/rs-collection-catalog.js" defer></script>
<?/*<script src="<?=$site_url?>/js/pages/common/product.js" defer></script>*/?>
<?php
$posts = get_field('rs-collection');
if( $posts ): ?>
<section class="rs-collection-catalog">
	<div class="rs-collection-catalog_container">
		<?/*<h3 class="xxl-medium-title"><?_e('Коллекции','storefront')?></h3>*/?>
		<div class="rs-collection-catalog__slider">
			<div class="rs-collection-catalog__swiper">
				<?php foreach( $posts as $post ):
					setup_postdata( $post );
					wc_get_template_part( 'content', 'product-collection-new' );
				endforeach; ?>
				<?php wp_reset_postdata(); ?>
			</div>
		</div>
		<?php if( have_rows('knopka_2') ): ?>
		<?php while( have_rows('knopka_2') ): the_row(); ?>
		<div class="rs-collection-catalog__button">
            <?php if(get_sub_field('ssylka')!='#') {?>
			<a <?php if(get_sub_field('ssylka')!='') {?>href="<?php the_sub_field('ssylka'); ?>" <?php }?>class="rs-btn <?php the_sub_field('klass'); ?>">
				<?php the_sub_field('nazvanie'); ?>
			</a>
            <?php }?>
		</div>
		<?php endwhile; ?>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<?php 
$images = get_field('slider_2');
if( $images ): ?>
<section class="rs-collection-gallery">
	<div class="rs-collection-gallery_container">
		<div class="rs-collection-gallery__wrapper">
			<div class="rs-collection-gallery__slider swiper">
				<div class="rs-collection-gallery__swiper swiper-wrapper">
					<?php foreach( $images as $image ): ?>
					<div class="rs-collection-gallery__slide swiper-slide">
						<img src="<?php echo esc_url($image['url']); ?>" alt="">
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="rs-collection-gallery__button-prev swiper-button-prev icon-slider-arrow_left"></div>
			<div class="rs-collection-gallery__button-next swiper-button-next icon-slider-arrow_right">
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<link rel="stylesheet" href="<?=$site_url?>/css/pages/brand/rs-collection-slider.css?v=<?=filemtime( $site_path . 'css/pages/brand/rs-collection-slider.css' )?>">
<script src="<?=$site_url?>/js/pages/brand/rs-collection-slider.js?v=<?=filemtime( $site_path . 'js/pages/brand/rs-collection-slider.js' )?>" defer></script>
<?php if( have_rows('kollekczii') ): ?>
<section class="rs-collection-slider rs-collection-slider-2">
	<div class="rs-collection-slider_container">
		<? if(get_field('zagolovok_kollekczii')||get_field('opisanie_kollekczii')){ ?><div class="rs-collection-slider__text">
			<? if(get_field('zagolovok_kollekczii')){ ?><h3 class="xxl-medium-title"><?=get_field('zagolovok_kollekczii')?></h3><? } ?>
			<? if(get_field('opisanie_kollekczii')){ ?><p class="large-text"><?=get_field('opisanie_kollekczii')?></p><? } ?>
		</div><? } ?>
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

<?php if(get_field('bolshoe_foto_mob_3')&&get_field('bolshoe_foto_3')){?>
<section class="rs-gallery">
	<div class="rs-gallery_container">
		<div class="rs-gallery__img">
			<img src="<?=get_field('bolshoe_foto_mob_3')['url']?>" alt="" class="img-mobile">
			<img src="<?=get_field('bolshoe_foto_3')['url']?>" alt="" class="img-desktop">
		</div>
	</div>
</section>
<?php } ?>

<?php endwhile; ?>

<?php } ?>

<?php get_footer(); ?>