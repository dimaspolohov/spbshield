<?php
/**
* The Template for displaying all single-collections posts.
*
* @package dazzling
*/

$site_url = site_url().'/new-assets';
$site_path = get_home_path().'new-assets/';
get_header(); 

if( get_the_ID()==138459) {

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
<a href="https://www.spbshield.ru/shop/topdog/" class="rs-btn _black-border-btn">Перейти ко всем товарам коллекции</a>
				</div>
			</div>


<div class="rs-store__gallery">
	<a href="https://youtu.be/BNAlgsHanrM?si=Xx1tgzpKlUNGNz8f" class="store-video" target="_blank">
		<img src="<?=$site_url?>/top-dog/img/video_link.jpg" alt="">
	</a>
<div class="store-gallery">

<!--

<a href="<?/*=$site_url*/?>/so/img/1@2x.jpg" data-fancybox class="store-gallery__item store-gallery__item_wide">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/1.jpg">
		<img src="<?/*=$site_url*/?>/so/img/1@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/2@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/2.jpg">
		<img src="<?/*=$site_url*/?>/so/img/2@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/3@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/3.jpg">
		<img src="<?/*=$site_url*/?>/so/img/3@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/4@2x.jpg" data-fancybox class="store-gallery__item store-gallery__item_wide">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/4.jpg">
		<img src="<?/*=$site_url*/?>/so/img/4@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/5@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/5.jpg">
		<img src="<?/*=$site_url*/?>/so/img/5@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/6@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/6.jpg">
		<img src="<?/*=$site_url*/?>/so/img/6@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/7@2x.jpg" data-fancybox class="store-gallery__item store-gallery__item_wide">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/7.jpg">
		<img src="<?/*=$site_url*/?>/so/img/7@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/08@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/08.jpg">
		<img src="<?/*=$site_url*/?>/so/img/08@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/09@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/09.jpg">
		<img src="<?/*=$site_url*/?>/so/img/09@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/10@2x.jpg" data-fancybox class="store-gallery__item store-gallery__item_wide">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/10.jpg">
		<img src="<?/*=$site_url*/?>/so/img/10@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/11@2x.jpg" data-fancybox class="store-gallery__item store-gallery__item_wide">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/11.jpg">
		<img src="<?/*=$site_url*/?>/so/img/11@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/12@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/12.jpg">
		<img src="<?/*=$site_url*/?>/so/img/12@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/13@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/13.jpg">
		<img src="<?/*=$site_url*/?>/so/img/13@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/14@2x.jpg" data-fancybox class="store-gallery__item store-gallery__item_wide">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/14.jpg">
		<img src="<?/*=$site_url*/?>/so/img/14@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/15@2x.jpg" data-fancybox class="store-gallery__item store-gallery__item_wide">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/15.jpg">
		<img src="<?/*=$site_url*/?>/so/img/15@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/16@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/16.jpg">
		<img src="<?/*=$site_url*/?>/so/img/16@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/17@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/17.jpg">
		<img src="<?/*=$site_url*/?>/so/img/17@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/18@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/18.jpg">
		<img src="<?/*=$site_url*/?>/so/img/18@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/19@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/19.jpg">
		<img src="<?/*=$site_url*/?>/so/img/19@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/20@2x.jpg" data-fancybox class="store-gallery__item store-gallery__item_wide">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/20.jpg">
		<img src="<?/*=$site_url*/?>/so/img/20@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/21@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/21.jpg">
		<img src="<?/*=$site_url*/?>/so/img/21@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/22@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/22.jpg">
		<img src="<?/*=$site_url*/?>/so/img/22@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/23@2x.jpg" data-fancybox class="store-gallery__item store-gallery__item_wide">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/23.jpg">
		<img src="<?/*=$site_url*/?>/so/img/23@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/24@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/24.jpg">
		<img src="<?/*=$site_url*/?>/so/img/24@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/25@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/25.jpg">
		<img src="<?/*=$site_url*/?>/so/img/25@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/26@2x.jpg" data-fancybox class="store-gallery__item store-gallery__item_wide">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/26.jpg">
		<img src="<?/*=$site_url*/?>/so/img/26@2x.jpg" loading="lazy" alt="">
	</picture>
</a>
	

<a href="<?/*=$site_url*/?>/so/img/27@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/27.jpg">
		<img src="<?/*=$site_url*/?>/so/img/27@2x.jpg" loading="lazy" alt="">
	</picture>
</a>

<a href="<?/*=$site_url*/?>/so/img/28@2x.jpg" data-fancybox class="store-gallery__item">
	<picture>
		<source media="(max-width: 1024px)" srcset="<?/*=$site_url*/?>/so/img/28.jpg">
		<img src="<?/*=$site_url*/?>/so/img/28@2x.jpg" loading="lazy" alt="">
	</picture>
</a>
	

-->


</div>

			</div>
		</div>		
	</div>
</div>
	<?
	
} ?>

<?php get_footer(); ?>