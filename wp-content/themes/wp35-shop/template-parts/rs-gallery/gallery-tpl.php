<?php get_header(); ?>
<?php $gallery = get_field('gallery'); ?>
	<section class="rs-17">
		<div class="rs-gallery">
			<div class="container rs-gallery-inner">
				<div class="row">
					<div class="col-xs-12">
						<h1 class="text-center section-title" data-nekoanim="fadeInUp" data-nekodelay="100">
						<span class="section-title--text">
							<?php the_title(); ?>
						</span>
						</h1>
						<div class="section-descr" data-nekoanim="fadeInUp" data-nekodelay="200">
							<?php
							    global $more;
							    while( have_posts() ) : the_post();
							        $more = 1;
							        if (get_field('text_anons')) the_field('text_anons');
							    endwhile;
							?>
						</div>
					</div>
				</div>
				<div class="row">
					<?php if ( is_array($gallery) ) {
						foreach ( $gallery as $item ) {
							$params = array( 'width' => 263, 'height' => 199 );
							$image = bfi_thumb( $item['url'], $params );
							?>
							<div class="col-xs-12 col-sm-6 col-md-3 album-photo">
								<a href="<?php echo esc_url( $item['url'] ); ?>" data-fancybox="gallery" data-caption="<?php echo esc_attr( $item['description'] ); ?>">
									<img data-src="<?php echo esc_url( $image ); ?>" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/img0.png' ); ?>"
										alt="<?php echo esc_attr( $item['alt'] ); ?>" class="img-responsive b-lazy">
								</a>
							</div>
						<?php }
					} ?>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<a href="<?php echo esc_url( get_post_type_archive_link('gallery') ); ?>" class="btn btn-link btn-back"><i class="fa fa-long-arrow-left"></i>Назад к списку</a>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php get_footer(); ?>
