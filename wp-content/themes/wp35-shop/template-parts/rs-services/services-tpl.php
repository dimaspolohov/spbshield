<?php
	$add_blocks = get_field("add_blocks") ?: '';
	$gallery_3 = get_field('image_gallery_3') ?: '';
	$gallery_4 = get_field('image_gallery_4') ?: '';
	$gallery_5 = get_field('image_gallery_5') ?: '';
	get_header();
?>

<div class="rs-child-tmpl">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" >

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

					<?php if ( get_field('text_banner') ) { ?>
						<div class="parallax parallax-about b-lazy"<?php if ( get_field('img_banner') ) { ?> data-src="<?php echo esc_url( get_field('img_banner')['url'] ); ?>" style="background-size: cover;"<?php } ?>>
							<div class="container">
								<div class="row">
									<div class="col-xs-12 parallax-content">
										<h2><?php echo esc_html( get_field('text_banner') ); ?></h2>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
						<div class="container rs-page-inner">
							<div class="row">
								<?php storefront_rs_services_list( get_the_ID() ); ?>
								<div class="col-xs-12 col-sm-12 col-md-8 col-lg-9 clearfix about-main">
									<?php if (get_field('on_title')) : ?>
										<h1 class="section-title style2" data-nekoanim="fadeInUp" data-nekodelay="50"><?php the_title(); ?></h1>
									<?php endif; ?>
									<?php if (get_field('on_content')) : ?>
										<?php the_content(); ?>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if (is_array($gallery_3) || is_array($gallery_4) || is_array($gallery_5) ) : ?>
				<section class="rs-17">
					<div class="rs-photogallery" id="block-gallery">
						<div class="container">
							<div class="row gallery-row">
								<?php $i = 0;
									if ( is_array($gallery_3) ) {

									foreach ( $gallery_3 as $key => $item ) { ?>

										<div class="col-xs-12 col-sm-4">
											<div class="gallery-item gallery-item--col3" data-nekoanim="fadeInLeft" data-nekodelay="<?php echo esc_attr( ++$i * 100 ); ?>">
												<a class="b-lazy" title="<?php echo esc_attr( $item['title'] ); ?>" href="<?php echo esc_url( $item['url'] ); ?>" data-fancybox="gallery" data-caption="<?php echo esc_attr( $item['description'] ); ?>" data-src="<?php echo esc_url( $item['url'] ); ?>" style="background-size: cover;">
												</a>
											</div>
										</div>
									<?php } ?>
									<div class="clearfix"></div>
								<?php } ?>
								<?php if ( is_array($gallery_4) ) {
									foreach ( $gallery_4 as $key => $item ) { ?>
										<div class="col-xs-12 col-sm-3 col-md-3">
											<div class="gallery-item gallery-item--col4" data-nekoanim="fadeInLeft" data-nekodelay="<?php echo esc_attr( ++$i * 100 ); ?>">
												<a class="b-lazy" title="<?php echo esc_attr( $item['title'] ); ?>" href="<?php echo esc_url( $item['url'] ); ?>" data-fancybox="gallery" data-caption="<?php echo esc_attr( $item['description'] ); ?>" data-src="<?php echo esc_url( $item['url'] ); ?>" style="background-size: cover;">
												</a>
											</div>
										</div>
									<?php } ?>
									<div class="clearfix"></div>
								<?php } ?>
								<?php if ( is_array($gallery_5) ) {
									foreach ( $gallery_5 as $key => $item ) { ?>
										<div class="col-xs-12 col-sm-4 col-md-2">
											<div class="gallery-item gallery-item--col6" data-nekoanim="fadeInLeft" data-nekodelay="<?php echo esc_attr( ++$i * 100 ); ?>">
												<a class="b-lazy" title="<?php echo esc_attr( $item['title'] ); ?>" href="<?php echo esc_url( $item['url'] ); ?>" data-fancybox="gallery" data-caption="<?php echo esc_attr( $item['description'] ); ?>" data-src="<?php echo esc_url( $item['url'] ); ?>" style="background-size: cover;">
												</a>
											</div>
										</div>
									<?php } ?>
									<div class="clearfix"></div>
								<?php } ?>
							</div>
						</div>
					</div>
				</section>
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

		</main>
	</div>
</div>
<?php get_footer(); ?>
