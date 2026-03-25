<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Video page
 *
 * @package storefront
 */

$add_blocks = get_field("add_blocks") ?: '';

get_header('one-menu'); ?>
<div class="rs-child-tmpl">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" >

		<section class="rs-17" id="main-video">
			<div class="rs-main-video" id="video-block">
				
				<div class="container">
					<?php if(get_field('title')) : ?> 
					<div class="row">
						<div class="col-xs-12 section-title">
							<h2 class="text-center" data-nekoanim="fadeInUp" data-nekodelay="100">
								<?php the_field('title'); ?>
							</h2>	
						</div>
					</div>
					<?php endif; ?>
				</div>					

				<video autoplay="" loop="" muted="" class="video-bg" id="video_background" 
					<?php if (get_field('poster_video')) {
						echo 'poster="' . esc_url(get_field('poster_video')['url']) .'"';
					} ?>>
					<?php if(get_field('link_video_mp4')) { ?><source src="<?php the_field('link_video_mp4'); ?>" type="video/mp4"><?php } ?>
					<?php if(get_field('link_video_webm')) { ?><source src="<?php the_field('link_video_webm'); ?>" type="video/webm"><?php } ?>
					<?php if(get_field('link_video_ogg')) { ?><source src="<?php the_field('link_video_ogg'); ?>" type="video/ogg"><?php } ?>
				</video>
			</div>
		</section>
		<?php wp_reset_query();	?>

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
				<div class="rs-17">
					<div class="rs-page">

                        <?php if(get_field('on_banner') ) {
                            $slider_image = get_field('img_banner');
                            $url = $slider_image['url'];
                            $attachment_id = attachment_url_to_postid( $url );
                            $srcm = wp_get_attachment_image_url( $attachment_id, array(768,376) );
                            $src = wp_get_attachment_image_url( $attachment_id, 'large' );
                            $srcF = wp_get_attachment_image_url( $attachment_id, 'full' );
                            ?>
                            <div  class="parallax parallax-about <?php if(get_field('img_banner') ) { ?> b-lazy <?php } ?>" <?php if(get_field('img_banner') ) { ?>data-src="<?php echo esc_url($srcF); ?>" data-medium="<?php echo esc_url($src); ?>" data-small="<?php echo esc_url($srcm); ?>" style="background-attachment: fixed; background-size: cover;"<?php } ?>>
                                <?php if(get_field('text_banner') ) { ?>
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-xs-12 parallax-content">
                                                <h2><?php echo esc_html(get_field('text_banner')); ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

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
											<?php echo esc_html(get_field('short_desc')); ?>
										</p></div>
									</div>
								<?php endif; ?>
								<?php if (get_field('on_content')) : ?>
									<div class="col-xs-12 clearfix about-main">
										<?php the_content(); ?>
									</div>
								<?php endif; ?>								
							</div>
						</div>

					</div>
				</div>

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

		</main><!-- #main -->
	</div><!-- #primary -->

?> </div> <?php
get_footer();
