<?php
/**
 * The template for displaying all pages.
 *
 * This is the templ ate that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package storefront
 */

$add_blocks = get_field("add_blocks") ?: '';

get_header(); ?>
<?php
if (get_field("on_slider")) {
	do_action( 'template_on_slider' ); 
}
while ( have_posts() ) :
	the_post();
	do_action( 'storefront_page_before' );
	if (get_field("text_block_before_show")) {
		get_template_part('template-parts/rs-text-block/rs-text-block-before');				
	}
	?>
	<?php if (get_field('on_title') || get_field('on_content') || get_field('on_banner')) : ?>
	<!-- rs-banner -->
	<section class="rs-banner">
		<div class="rs-banner__bg">
			<picture>
				<source class="no-lazy" srcset="<?=get_field('img_banner')['url']?>.webp" type="image/webp">
				<img class="no-lazy" src="<?=get_field('img_banner')['url']?>" alt="">
			</picture>
			<picture>
				<source class="no-lazy" srcset="<?=get_field('img_banner')['sizes']['img-banner']?>.webp" type="image/webp">
				<img class="no-lazy" src="<?=get_field('img_banner')['sizes']['img-banner']?>" alt="">
			</picture>
		</div>
		<div class="rs-banner__container">
			<h2 class="large-title"><?=get_field('title_banner') ?></h2>
		</div>
	</section>
	<!-- /rs-banner -->
	<?php endif; ?>
	<?php if (get_field('on_title') || get_field('on_content')) : ?>
	<div class="container rs-page-inner">
		<div class="row">
			<?php if (get_field('on_title')) : ?>
				<div class="col-xs-12">
					<h1 class="text-center section-title" data-nekoanim="fadeInUp" data-nekodelay="50"><span class="section-title--text">
						<?php the_title() ?></span>
					</h1>
				</div>
			<?php endif; ?>
			<?php if (get_field('short_desc')) : ?>
				<div class="col-xs-12 clearfix about-main">
					<div class="section-descr"><p>
						<?=get_field('short_desc'); ?>
					</p></div>
				</div>
			<?php endif; ?>
			<?php if (get_field('on_content')) : ?>
				<div class="col-xs-12 clearfix about-main">
					<?=the_content() ; ?>
				</div>
			<?php endif; ?>								
		</div>
	</div>
	<?php endif; ?>
	<?php
	if ($add_blocks) {
		foreach ($add_blocks as $value) {
			do_action( 'template_'. $value['block_name'] ); 
		}					
	}
	if (get_field("text_block_after_show")) {
		get_template_part('template-parts/rs-text-block/rs-text-block-after');
	}
	do_action( 'storefront_page_after' );
endwhile;
?>

<?php
get_footer();