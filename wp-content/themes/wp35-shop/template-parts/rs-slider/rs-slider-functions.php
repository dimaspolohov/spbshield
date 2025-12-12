<?php

function style_rs_slider_store() {
    wp_enqueue_style( 'rs-slider', get_stylesheet_directory_uri().'/assets/css/rs-slider.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-slider.css') );
    wp_enqueue_script( 'rs-slider-js', get_stylesheet_directory_uri() . '/assets/js/rs-slider.js','','',false);
}
// Регистрация типа записей
add_post_type('slider', 'Слайдер', array(
	'supports'   => array( 'title', 'editor', 'thumbnail' ),
	'taxonomies' => array( 'post_tag' ),
	'menu_icon' => 'dashicons-format-gallery'
));

//$labels = apply_filters( "post_type_labels_{$post_type}", $labels );

add_filter('post_type_labels_slider', 'rename_posts_labels');
function rename_posts_labels( $labels ){
	$new = array(
		'name'                  => 'Слайды',
		'singular_name'         => 'Слайд',
		'add_new'               => 'Добавить слайд',
		'add_new_item'          => 'Добавить слайд',
		'edit_item'             => 'Редактировать слайд',
		'new_item'              => 'Новый слайд',
		'view_item'             => 'Просмотреть слайд',
		'search_items'          => 'Поиск слайдов',
		'not_found'             => 'Слайдов не найдено.',
		'not_found_in_trash'    => 'Слайдов в корзине не найдено.',
		'parent_item_colon'     => '',
		'all_items'             => 'Все слайды',
		'archives'              => 'Архивы слайдов',
		'insert_into_item'      => 'Вставить в слайд',
		'uploaded_to_this_item' => 'Загруженные для этого слайда',
		'featured_image'        => 'Миниатюра слайда',
		'filter_items_list'     => 'Фильтровать список слайдов',
		'items_list_navigation' => 'Навигация по списку слайдов',
		'items_list'            => 'Список слайдов',
		'menu_name'             => 'Слайдер',
		'name_admin_bar'        => 'Слайд', // пункте "добавить"
	);
	return (object) array_merge( (array) $labels, $new );
}
// Функция вывода блока Слайдер
function storefront_slider_child() { ?>
	<!-- rs-slider -->
	<?
	if( current_user_can('editor') || current_user_can('administrator') ) {
		$slides = get_posts( array(
			'numberposts' => -1,
			'post_type'   => 'slider',
			'post_status'    => array( 'publish', 'private' ),
		) );
	} else {
		$slides = get_posts( array(
			'numberposts' => -1,
			'post_type'   => 'slider',
		) );
	}
	global $post;
	if($slides) {?>
	<section class="rs-slider">
		<div class="rs-slider__slider swiper">
			<div class="rs-slider__swiper swiper-wrapper">
			<?
			$data = [];
			$img_folder = get_stylesheet_directory_uri().'/assets/img';
			foreach( $slides as $key => $post ){
				setup_postdata( $post );
				$data[$key] = [];
				$data[$key]['width'] = get_field('width');
				$type = get_field('type');
				$data[$key]['type'] = $type;
				ob_start();
				switch($type){
					case 'Видео':?>
				<ul class="bgvideo__controls js-bgvideo-controls" style="display: flex;">
					<li>
						<button class="bgvideo__control" id="bgvideoPlaypause" type="button" title="Play/Pause">
							<svg class="video-icon video-icon--pause is-active" aria-hidden="true" focusable="false">
								<use xlink:href="<?=$img_folder ?>/icons/video-controls.svg#ic-pause">
								</use>
							</svg>
							<svg class="video-icon video-icon--play" aria-hidden="true" focusable="false">
								<use xlink:href="<?=$img_folder ?>/icons/video-controls.svg#ic-play-button">
								</use>
							</svg>
						</button>
					</li>
					<li>
						<button class="bgvideo__control" id="bgvideoStop" type="button" title="Stop">
							<svg class="video-icon video-icon--stop" aria-hidden="true" focusable="false">
								<use xlink:href="<?=$img_folder ?>/icons/video-controls.svg#ic-stop">
								</use>
							</svg>
						</button>
					</li>
					<li>
						<button class="bgvideo__control" id="bgvideoMute" type="button" title="Mute">
							<svg class="video-icon video-icon--speaker is-active" aria-hidden="true" focusable="false">
								<use xlink:href="<?=$img_folder ?>/icons/video-controls.svg#ic-speaker">
								</use>
							</svg>
							<svg class="video-icon video-icon--mute" aria-hidden="true" focusable="false">
								<use xlink:href="<?=$img_folder ?>/icons/video-controls.svg#ic-mute">
								</use>
							</svg>
						</button>
					</li>
				</ul>
				<div class="rs-slider__bg bgvideo__video">
					<video class="bgvideo js-bgvideo" loop="" autoplay="" muted="" >
						<source data-url="<?=get_field('video_webm')['url']?>" src="<?=get_field('video_webm')['url']?>" type="video/webm">
						<source data-url="<?=get_field('video_webm')['url']?>" src="<?=get_field('video')['url']?>" type="video/mp4">
					</video>
				</div>
					<? break;
					case 'Изображение':
					default:?>
				<div class="rs-slider__bg">
					<picture class="img-mobile">
						<source <? if($key==0) echo 'class="no-lazy"'?> srcset="<? $img = get_field('image_mobile'); if(!$img) $img = get_field('image'); echo $img['url']; ?>.webp" type="image/webp">
						<img <? if($key==0) echo 'class="no-lazy"'?> src="<?=get_field('image')['url']?>" alt="">
					</picture>
					<picture class="img-desktop">
						<source srcset="<?=get_field('image')['url']?>.webp" type="image/webp">
						<img src="<?=get_field('image')['url']?>" alt="">
					</picture>
					<picture class="img-horizontal">
						<source <? if($key==0) echo 'class="no-lazy"'?> srcset="<?=get_field('image')['url']?>.webp" type="image/webp">
						<img <? if($key==0) echo 'class="no-lazy"'?> src="<?=get_field('image')['url']?>" alt="">
					</picture>
				</div>
					<? break;
				}
				$data[$key]['content'] = ob_get_contents();
				ob_end_clean();
				ob_start();
				?>
				<div class="rs-slider__container">
					<div class="rs-slider__body">
						<h2 class="large-title"><?=$post->post_title?></h2>
						<a href="<?=get_field('slider_buttons_one')['slider_buttons_link']?>" class="rs-btn _border-btn _white-btn"><?=get_field('slider_buttons_one')['slider_buttons_name']?></a>
					</div>
				</div>
				<?
				$data[$key]['btn'] = ob_get_contents();
				ob_end_clean();
			}
			$flag = false;
			$second = false;
			foreach( $data as $key => $d ){
				$index = $key+1;
				if( $d['width']=='100' && !$flag ) {
					$flag = false; ?>
					<div class="rs-slider__slide rs-slider__slide-<?=$index?> swiper-slide rs-slider__slide-100">
						<div class="rs-slider__item">
							<?=$d['content']?>
							<div class="rs-slider__content">
								<?=$d['btn']?>
							</div>
						</div>
					</div>
					<?
				} else {
					
					if( $d['width']=='50' && !$flag && isset($data[$index]) && $data[$index]['type']!='Видео' ) {
						$flag = true;
						?>
						<div class="rs-slider__slide rs-slider__slide-<?=$index?> swiper-slide rs-slider__slide-50">
							<div class="rs-slider__item">
								<?=$d['content']?>
								<div class="rs-slider__content">
									<?=$d['btn']?>
								</div>
							</div>
						<?
					} elseif( $flag ) {
						$flag = false;
						?>
							<div class="rs-slider__item" data-da=".rs-slider__slide-<?=$index?>, 992, first">
								<?=$d['content']?>
								<div class="rs-slider__content rs-slider__content-right">
									<?=$d['btn']?>
								</div>
							</div>
						</div><div class="rs-slider__slide rs-slider__slide-<?=$index?>"></div>
						<?
					} else {
						$flag = false; ?>
						<div class="rs-slider__slide rs-slider__slide-<?=$index?> swiper-slide rs-slider__slide-100">
							<div class="rs-slider__item">
								<?=$d['content']?>
								<div class="rs-slider__content">
									<?=$d['btn']?>
								</div>
							</div>
						</div>
						<?
					}
				}
			}
			?>
			</div>
			<div class="rs-slider__button-prev swiper-button-prev icon-slider-arrow_left"></div>
			<div class="rs-slider__button-next swiper-button-next icon-slider-arrow_right"></div>
			<div class="rs-slider__pagination swiper-pagination"></div>
		</div>
	</section>
	<? }
	wp_reset_postdata();
	?>
	<!-- /rs-slider -->
	<?php 
	wp_reset_query();
}

// Функция вывода блока Слайдер
function rs_slider_store() {
    $slides = get_field('slaider');
    ?>
    <!-- rs-slider -->
    <?   if($slides) {  ?>
    <section class="rs-slider rs-slider-store">
        <div class="rs-slider__slider swiper">
            <div class="rs-slider__swiper swiper-wrapper">
                <?foreach( $slides as $key => $slide ){?>
                <div class="rs-slider__slide swiper-slide rs-slider__slide-100" data-color="<?=$slide['color_a']?>">
                    <div class="rs-slider__item">
                        <div class="rs-slider__bg">
                            <picture class="img-mobile">
                                <source srcset="<?=$slide['img_mob']?>.webp" type="image/webp">
                                <img src="<?=$slide['img_mob']?>" alt="">
                            </picture>
                            <picture class="img-desktop">
                                <source srcset="<?=$slide['img']?>.webp" type="image/webp">
                                <img src="<?=$slide['img']?>" alt="">
                            </picture>
                        </div>
                    </div>
                </div>
                <?}?>
            </div>
            <div class="rs-slider__button-prev swiper-button-prev icon-slider-arrow_left"></div>
            <div class="rs-slider__button-next swiper-button-next icon-slider-arrow_right"></div>
          
			<div class="rs-slider__pagination swiper-pagination"></div>
        </div>
    </section>
    <!-- /rs-slider -->
    <? }  ?>
    <?php
}