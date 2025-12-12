<?php
/**
 * The Template for displaying all single-collections posts.
 *
 * @package dazzling
 */

get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<?php 
	$banner = get_field('banner_img');
	if($banner && $banner!='') : ?>
	<!-- rs-banner -->
	<section class="rs-banner">
		<div class="rs-banner__bg">
			<picture>
				<source class="no-lazy" srcset="<?=get_field('banner_img')['url']?>.webp" type="image/webp">
				<img class="no-lazy" src="<?=get_field('banner_img')['url']?>" alt="">
			</picture>
			<picture>
				<source class="no-lazy" srcset="<?=get_field('banner_img')['sizes']['img-banner']?>.webp" type="image/webp">
				<img class="no-lazy" src="<?=get_field('banner_img')['sizes']['img-banner']?>" alt="">
			</picture>
		</div>
		<div class="rs-banner__container">
			<h2 class="large-title"><?=get_field('banner_text') ?></h2>
		</div>
	</section>
	<!-- /rs-banner -->
	<?php
	elseif (has_post_thumbnail( get_the_ID() )) : ?>
	<!-- rs-banner -->
	<section class="rs-banner">
		<div class="rs-banner__bg">
			<picture>
				<source srcset="<?=get_the_post_thumbnail_url( get_the_ID(), 'full' )?>.webp" type="image/webp">
				<img src="<?=get_the_post_thumbnail_url( get_the_ID(), 'full' )?>" alt="">
			</picture>
			<picture>
				<source srcset="<?=get_the_post_thumbnail_url( get_the_ID(), 'img-banner' )?>.webp" type="image/webp">
				<img src="<?=get_the_post_thumbnail_url( get_the_ID(), 'img-banner' )?>" alt="">
			</picture>
		</div>
		<div class="rs-banner__container">
			<h2 class="large-title"><?=get_field('banner_text') ?></h2>
		</div>
	</section>
	<!-- /rs-banner -->
	<?php else:?>	
	<script>jQuery('.rs-header').addClass('_black-header').removeClass('rs-header--white').removeClass('_white-header');</script>
	<?php endif; ?>
	
	<!-- rs-media-content -->
	<section class="rs-media-content">
		<div class="rs-media__container rs-content__container">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="rs-media__header">
                    <h2 class="section-title"><?php the_title()?></h2>
				</div><!-- .entry-header -->
				<div class="entry-content">
					<?php the_content(); ?>
				</div><!-- .entry-content -->
			</article>
		</div>
	</section>
	<!-- /rs-media-content -->
	
	<!-- rs-collection-product -->
	<?php
	$my_posts = get_field('rs-collection');
	if($my_posts) {?>
	<section class="rs-new-product">
		<div class="rs-new-product__container">
			<?/*<h2 class="section-title"><?_e('Collection','storefront')?></h2>*/?>
			<div class="rs-new-product__wrapper">
				<div class="rs-new-product__slider swiper">
					<div class="rs-new-product__swiper swiper-wrapper">
						<?
						global $post;
						foreach( $my_posts as $post ){
							setup_postdata( $post );
							wc_get_template_part( 'content', 'product-collection' );
						}
						wp_reset_postdata();
						?>
					</div>
				</div>
				<div class="rs-new-product__button-prev swiper-button-prev icon-slider-arrow_left"></div>
				<div class="rs-new-product__button-next swiper-button-next icon-slider-arrow_right"></div>
			</div>
		</div>
	</section>
	<? }?>
	<!-- /rs-collection-product -->
	
	<!-- rs-photogallery -->
	<? 
	$gallery_3 = get_field('image_gallery_3') ?: '';
	$gallery_4 = get_field('image_gallery_4') ?: '';
	$gallery_5 = get_field('image_gallery_5') ?: '';
	if(is_array($gallery_3) || is_array($gallery_4) || is_array($gallery_5)) :
		$title = get_field('title') ?: '';
		$description = get_field('description') ?: '';
		?>
		<section class="rs-media-content">
			<div class="rs-media__container">
				<div class="rs-photogallery" id="block-gallery">
					<h2 class="section-title"><?=$title; ?></h2>
					<p class="xl-medium-title"><?=$description; ?></p>
					<div class="row gallery-row" style="margin-top:35px">
						<?php $i = 0; 
							if ( is_array($gallery_3) ) {
							foreach ( $gallery_3 as $key => $item ) {?>
								<div class="col-xs-12 col-sm-4">
									<div class="gallery-item gallery-item--col3" data-nekoanim="fadeInLeft" data-nekodelay="<?= ++$i*100 ?>">
										<a title="<?php echo $item[ 'title' ]; ?>" href="<?php echo $item[ 'url' ]; ?>" data-fancybox="gallery" data-caption="<?php echo $item[ 'description' ]; ?>" class="b-lazy" data-src="<?php echo $item['sizes']['medium_large'];?>" style="background-size: cover;">
										</a>
									</div>
								</div>
							<?  }?>
							<div class="clearfix"></div>
						<?} ?>
						<?php if ( is_array($gallery_4) ) {
							foreach ( $gallery_4 as $key => $item ) {?>
								<div class="col-xs-12 col-sm-3 col-md-3">
									<div class="gallery-item gallery-item--col4" data-nekoanim="fadeInLeft" data-nekodelay="<?= ++$i*100 ?>">
										<a title="<?php echo $item[ 'title' ]; ?>" href="<?php echo $item[ 'url' ]; ?>" data-fancybox="gallery" data-caption="<?php echo $item[ 'description' ]; ?>" class="b-lazy" data-src="<?php echo $item['sizes']['medium_large'];?>" style="background-size: cover;">
										</a>
									</div>
								</div>
							<?  }?>
							<div class="clearfix"></div>
						<?} ?>
						<?php if ( is_array($gallery_5) ) {
							foreach ( $gallery_5 as $key => $item ) {?>
								<div class="col-xs-12 col-sm-4 col-md-2">
									<div class="gallery-item gallery-item--col6" data-nekoanim="fadeInLeft" data-nekodelay="<?= ++$i*100 ?>">
										<a title="<?php echo $item[ 'title' ]; ?>" href="<?php echo $item[ 'url' ]; ?>" class="b-lazy" data-src="<?php echo $item['sizes']['medium_large'];?>" data-fancybox="gallery" data-caption="<?php echo $item[ 'description' ]; ?>" style="background-size: cover;">
										</a>
									</div>
								</div>
							<?  } ?>
							<div class="clearfix"></div>
						<?} ?>
					</div>
				</div>
			</div>
		</section>
	<? endif?>
	<!-- /rs-photogallery -->
	
<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>