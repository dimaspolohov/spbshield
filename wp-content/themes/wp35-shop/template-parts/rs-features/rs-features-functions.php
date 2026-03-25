<?php

// Enqueue features block styles
add_action( 'wp_enqueue_scripts', 'style_rs_features_theme' );

function style_rs_features_theme() {
	wp_enqueue_style( 'rs-features-theme', get_stylesheet_directory_uri() . '/template-parts/rs-features/css/rs-features.css' );
}

// Render 4-column features block on the homepage
function storefront_rs_show_custom_block() {
	$post_meta = null;
	$title     = '';

	$query = new WP_Query( array(
		'post_type'  => 'custom_block',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key'     => 'block_id',
				'value'   => 1,
				'compare' => '=',
			),
		),
	) );

	while ( $query->have_posts() ) {
		$query->the_post();
		$post_meta = get_post_meta( $query->post->ID );
	}

	if ( $post_meta ) {
		$title = get_field( 'title' ) ?: '';
	}
	?>
	<section class="rs-17">
		<div class="rs-features index-page">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<h2 class="text-center section-title" data-nekoanim="fadeInUp" data-nekodelay="100">
							<span class="section-title--text"><?php echo esc_html( $title ); ?></span>
						</h2>
					</div>
				</div>
				<div class="row">
					<?php
					if ( $post_meta ) {
						for ( $i = 1; $i < 9; $i++ ) {
							$feature_image       = get_field( "features_{$i}_img_1" );
							$feature_name        = get_field( "features_{$i}_name_1" );
							$feature_description = get_field( "features_{$i}_desc_1" );
							if ( $feature_image || $feature_name || $feature_description ) :
								?>
								<div class="col-xs-12 col-sm-6 col-md-3 feature-block">
									<div class="feature-item" data-nekoanim="fadeInDown" data-nekodelay="400">
										<div class="feature-item--icon">
											<img src="<?php echo esc_url( $feature_image ); ?>" alt="feature_picture">
										</div>
										<?php if ( $feature_name ) : ?>
											<h4><?php echo esc_html( $feature_name ); ?></h4>
										<?php endif; ?>
										<?php if ( $feature_description ) : ?>
											<p><?php echo wp_kses_post( $feature_description ); ?></p>
										<?php endif; ?>
									</div>
								</div>
							<?php
							endif;
						}
					}
					wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
	</section>
	<?php
}
