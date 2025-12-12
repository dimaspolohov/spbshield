<?php

/* add_action( 'init', 'create_post_type_inst' );
function create_post_type_inst() {
    register_post_type( 'rs-inst',
        array(
            'labels' => array(
                'name' => 'Instagram',
                'singular_name' => 'Instagram',
                'add_new' => 'Добавить фото',
                'add_new_item' => 'Добавить фото',
                'edit' => 'Изменить фото',
                'edit_item' => 'Изменить фото',
                'new_item' => 'Новое фото',
                'view' => 'Показать фото',
                'view_item' => 'Показать фото',
                'search_items' => 'Искать фото',
                'not_found' => 'Не найдено',
                'not_found_in_trash' => 'Не найдено в корзине',
                'parent' => 'Родительское фото',
            ),
            'taxonomies' => array(),
            'publicly_queryable'  => false,
            'public' => true,
            'menu_position' => 4,
            'supports' => array( 'title','revisions' ),
            'menu_icon' => 'dashicons-screenoptions',
            'has_archive' => false
        )
    );
} */

function storefront_rs_inst() {
	/* $frontpage_id = get_option('page_on_front');
	// echo $frontpage_id;
	$my_posts = get_posts( array(
		'numberposts' => 8,
		'post_type'   => 'rs-inst',
	) );
	if($my_posts) :
	?>
	<!-- rs-inst -->
	<section class="rs-inst">
		<div class="rs-inst__container">
			<? $data = get_field('socials',$frontpage_id); ?>
			<h2 class="large-title"><a class="icon-inst" href="<?=$data['ig']?>" target="_blank">@<? $ig = explode('/',$data['ig']); while(count($ig)>0 && end($ig)=='') array_pop($ig); echo end($ig); ?></a></h2>
			<div class="rs-inst__wrapper">
				<div class="rs-inst__slider swiper">
					<div class="rs-inst__swiper swiper-wrapper">
						<?
						global $post;
						foreach( $my_posts as $post ){
							setup_postdata( $post );
							?>
						<div class="rs-inst__slide swiper-slide">
							<picture>
								<source srcset="<?php echo get_field( 'image' )['sizes']['img-rs-inst']?>.webp" type="image/webp">
								<img src="<?php echo get_field( 'image' )['sizes']['img-rs-inst']?>" alt="">
							</picture>
						</div>
							<? 
						}
						wp_reset_postdata();
						?>
					</div>
				</div>
				<div class="rs-inst__button-prev swiper-button-prev icon-menu-arrow_left"></div>
				<div class="rs-inst__button-next swiper-button-next icon-menu-arrow_right"></div>
			</div>
		</div>
	</section>
	<?php
	endif; */
	
	$front_page_id = get_option('page_on_front');
	$insta = get_field('insta',$front_page_id);
	if($insta) { ?>
	<!-- rs-inst -->
	<section class="rs-inst">
		<div class="rs-inst__container">
			<? $data = get_field('socials',$front_page_id); ?>
			<h2 class="large-title"><a href="<?=$data['ig']?>" target="_blank">@<? $ig = explode('/',$data['ig']); while(count($ig)>0 && end($ig)=='') array_pop($ig); echo end($ig); ?></a></h2>
			<div class="rs-inst__wrapper">
				<div class="rs-inst__slider swiper">
					<div class="rs-inst__swiper swiper-wrapper">
						<? foreach( $insta as $inst_item ){ ?>
						<div class="rs-inst__slide swiper-slide">
							<picture>
								<source srcset="<?php echo $inst_item['image']['sizes']['img-rs-inst']?>.webp" type="image/webp">
								<img src="<?php echo $inst_item['image']['sizes']['img-rs-inst']?>" alt="">
							</picture>
							<?php if($inst_item['link']&&$inst_item['link']!=''){?><a href="<?php echo $inst_item['link']?>" target="_blank"></a><?php } ?>
						</div>
						<? } ?>
					</div>
				</div>
				<div class="rs-inst__button-prev swiper-button-prev icon-menu-arrow_left"></div>
				<div class="rs-inst__button-next swiper-button-next icon-menu-arrow_right"></div>
			</div>
		</div>
	</section>
	<?php }
}