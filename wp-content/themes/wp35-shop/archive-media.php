<?php
/**
 * The template for displaying archive-collections pages.
 *
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package storefront
 */

if ( have_posts() ) :

get_header();
$title=get_queried_object()->label;
$query = new WP_Query( array (
	'post_type' => 'custom_block',
	'meta_query' => array (
		'relation' => 'OR',
		array (
			'key'     => 'block_id',
			'value'   => 200, // block ID
			'compare' => '='
		)
	)
));
while ( $query->have_posts() ) {
	$query->the_post();
	$post_meta = get_post_meta($query->post->ID);
}
if ($post_meta) :
	$title = get_field("title_media") ?: $title;
	$linkk = get_field("link") ?: null;
	$title_banner = get_field("title_banner") ?: null;
	$text_banner = get_field("text_banner") ?: null;
	$video = get_field("video") ?: null;
	$video_webm = get_field("video_webm") ?: null;
	$slider_buttons = get_field("slider_buttons") ?: null;
	?>
	<section class="rs-banner-video">
		<div class="rs-banner-video__container">
			<div class="rs-banner-video__header">
				<h2 class="section-title"><?php echo esc_html($title); ?></h2>
				<?php if(isset($media_links) && is_array($media_links) && !empty($media_links)):?>
				<div class="rs-banner-video__links">
					<ul>
						<?php foreach($media_links as $link):  ?>
						<li><a href="<?php echo esc_url($link["link"]["url"]); ?>"><?php echo esc_html($link["link"]["title"]); ?></a></li>
						<?php endforeach;?>
					</ul>
				</div>
				<?php endif;?>
			</div>
			<div class="rs-banner-video__wrapper">
				<?php if($linkk) {
					?><a href="<?php echo esc_url($linkk); ?>" class="rs-banner-video__bg"><?php
				} else {
					?><div class="rs-banner-video__bg"><?php
				}?>
					<video class="bgvideo js-bgvideo" loop="" autoplay="" muted="">
						<?php if($video_webm):?>
						<source src="<?php echo esc_url($video_webm["url"]); ?>" type="video/webm">
						<?php endif;?>
						<?php if($video):?>
						<source src="<?php echo esc_url($video["url"]); ?>" type="video/mp4">
						<?php endif;?>
					</video>
				<?php if($linkk) {
					?></a><?php
				} else {
					?></div><?php
				}?>
				<div class="rs-banner-video__description">
					<div class="rs-banner-video__body">
						<?php if($title_banner):?>
						<h4><?php echo esc_html($title_banner); ?></h4>
						<?php endif;
						if($text_banner):?>
						<p><?php echo esc_html($text_banner); ?></p>
						<?php endif;?>
					</div>
				<?php if(is_array($slider_buttons) && !empty($slider_buttons)):?>
					<div class="rs-banner-video__btn">
						<ul class="bgvideo__controls js-bgvideo-controls" style="display: flex;">
							<li>
								<button class="bgvideo__control" id="bgvideoPlaypause" type="button" title="Play/Pause">
									<img src="<?php echo esc_url($slider_buttons["slider_buttons_play"]); ?>" alt="" class="video-icon video-icon--pause is-active">
									<img src="<?php echo esc_url($slider_buttons["slider_buttons_pause"]); ?>" alt="" class="video-icon video-icon--play">
								</button>
							</li>
						</ul>
					</div>
				<?php endif;?>
				</div>
			</div>
		</div>
	</section>
<?php
endif;
wp_reset_query();
global $post;
$last_posts = get_posts( array(
	'numberposts' => 3,
	'post_type'   => 'media',
	'orderby' 	  => 'date',
	'order'		  => 'DESC',
	'tax_query' => [
		'relation' => 'OR',
		[
			'taxonomy' => 'post_tag',
			'field'    => 'slug',
			'terms'    => array( 'video', 'novost' ),
		],
	]
) );
if($last_posts) : ?>
	<section class="rs-media-news" >
	<div class="rs-media-news__container">
		<div class="rs-media-news__list">
<?php
	foreach( $last_posts as $post ){
		setup_postdata( $post );
		$post_id=$post->ID;
		$post_thumbnail=get_the_post_thumbnail_url( $post, 'full' )?get_the_post_thumbnail_url( $post, 'full' ):null;
		$img=get_field('cat_img')?get_field('cat_img'):$post_thumbnail;
		$posttags=get_the_tags();
		?>
					<div class="rs-media-news__item">
						<a href="<?php the_permalink(); ?>">
							<div class="rs-media-news__img">
								<?php if($img):?>
								<picture>
									<source srcset="<?php echo esc_url($img); ?>.webp" type="image/webp">
									<img src="<?php echo esc_url($img); ?>" alt="">
								</picture>
								<?php endif;?>
							</div>
							<div class="rs-media-news__description">
								<h4 class="sm-bold-title"><?php the_title(); ?></h4>
							</div>
						</a>
					</div>
		<?php  } ?>
		</div>
	</div>
	</section>
<?php
	wp_reset_postdata();
endif;

$front_page_id = get_option('page_on_front');
if( have_rows('mikstejpy',$front_page_id) ): ?>
	<section class="rs-podcast">
		<div class="rs-podcast__container">
			<h2 class="section-title"><?php _e('Микстейпы','storefront'); ?></h2>
			<div class="rs-podcast__slider_wrapper">
				<div class="rs-podcast__slider swiper">
					<div class="rs-podcast__swiper swiper-wrapper">
					<?php while( have_rows('mikstejpy',$front_page_id) ): the_row(); ?>
						<div class="rs-podcast__slide swiper-slide">
							<div class="rs-podcast__item">
								<a href="<?php echo esc_url(get_sub_field('link')); ?>">
									<div class="rs-podcast__img">
										<picture>
											<source srcset="<?php echo esc_url(get_sub_field('image')['url']); ?>.webp" type="image/webp">
											<img src="<?php echo esc_url(get_sub_field('image')['url']); ?>" alt="">
										</picture>
									</div>
									<div class="rs-podcast__description"><?php echo esc_html(get_sub_field('nazvanie')); ?></div>
								</a>
							</div>
						</div>
					<?php endwhile; ?>
					</div>
				</div>
				<div class="rs-podcast__button-prev swiper-button-prev icon-slider-arrow_left"></div>
				<div class="rs-podcast__button-next swiper-button-next icon-slider-arrow_right"></div>
			</div>
		</div>
	</section>
<?php endif;

$my_posts = get_posts( array(
	'numberposts' => -1,
	'post_type'   => 'media',
    'orderby' 	  => 'date',
    'order'		  => 'DESC',
    'offset'	  => 3,
    'tax_query' => [
        'relation' => 'OR',
        [
            'taxonomy' => 'post_tag',
            'field'    => 'slug',
            'terms'    => array( 'video', 'novost' ),
        ],
    ]
) );
if($my_posts) :
?>
<!-- rs-media -->
    <section class="rs-media-news rs-media-news_load_more">
        <div class="rs-media-news__container">

            <?php
            if(count($my_posts)>6) {
                ?><div class="loading-more">
                    <div class="blob top"></div>
                    <div class="blob bottom"></div>
                    <div class="blob left"></div>
                    <div class="blob move-blob"></div>
                </div><?php
            }
            ?>
		</div>
	</div>
</section>
<!-- /rs-media -->
<?php
endif;

get_footer();

else:

wp_redirect( site_url() );

endif;
