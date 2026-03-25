<?php

function style_rs_howworks_theme() {
	wp_enqueue_style( 'rs-howworks', get_stylesheet_directory_uri() . '/template-parts/rs-howworks/css/rs-howworks.css' );
}
add_action( 'wp_enqueue_scripts', 'style_rs_howworks_theme', 12 );

// Render "How We Work" block
function storefront_rs_howworks() {
	$query = new WP_Query( array(
		'post_type' => 'custom_block',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key'     => 'block_id',
				'value'   => 8, // block ID
				'compare' => '='
			)
		)
	));

	$post_meta   = null;
	$title       = '';
	$description = '';

	while ( $query->have_posts() ) {
		$query->the_post();
		$post_meta = get_post_meta( $query->post->ID );
	}

	if ( $post_meta ) {
		$title       = get_field( 'title' ) ?: '';
		$description = get_field( 'description' ) ?: '';
	}
	?>
	<section class="rs-17">
		<div class="rs-howworks" id="block-how-works">
			<div class="container">
				<?php if ( $title ) : ?>
					<div class="row">
						<div class="col-xs-12">
							<h2 class="text-center section-title" data-nekoanim="fadeInUp" data-nekodelay="100">
								<span class="section-title--text"><?php echo esc_html( $title ); ?></span>
							</h2>
							<?php if ( $description ) : ?>
								<div class="section-descr" data-nekoanim="fadeInUp" data-nekodelay="200">
									<p><?php echo wp_kses_post( $description ); ?></p>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
				<div class="row">
				<?php if ( $post_meta ) : ?>
					<?php
						$item = 0;
						while ( ! empty( get_field( "howworks_{$item}_img" ) ) ) {
							$feature_img_main  = get_field( "howworks_{$item}_img_main" );
							$feature_image_main = ! empty( $feature_img_main['url'] ) ? $feature_img_main['url'] : '';
							$feature_img       = get_field( "howworks_{$item}_img" );
							$feature_image     = ! empty( $feature_img['url'] ) ? $feature_img['url'] : '';
							$feature_name      = get_field( "howworks_{$item}_name" );
							$feature_description = get_field( "howworks_{$item}_text" );
							$item++; ?>
								<div class="col-xs-12 col-sm-4 works-item" data-nekoanim="fadeInUp" data-nekodelay="200">
									<div class="works-block <?php if ( $item == 3 ) echo 'line-connector-vertical'; ?>">
										<div class="works-wrap line-connector-right">
											<div class="works-icon">
												<img src="<?php echo esc_url( $feature_image_main ); ?>" alt="works-picture">
											</div>
											<div class="works-icon-hover">
												<img class="b-lazy" src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/img/img0.png" data-src="<?php echo esc_url( $feature_image ); ?>" alt="works-picture">
											</div>
										</div>
										<h4><?php echo esc_html( $feature_name ); ?></h4>
										<p><?php echo wp_kses_post( $feature_description ); ?></p>
									</div>
								</div>
							<?php
						}
					?>
				<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
	wp_reset_postdata();
}
