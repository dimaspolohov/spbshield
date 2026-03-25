<?php

function style_rs_team_theme() {
	wp_enqueue_style( 'rs-team', get_stylesheet_directory_uri() . '/template-parts/rs-team/css/rs-team2.css' );
}
add_action( 'wp_enqueue_scripts', 'style_rs_team_theme', 12 );

// Render "Our Team" block
function storefront_rs_team() {
	$query = new WP_Query( array(
		'post_type' => 'custom_block',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key'     => 'block_id',
				'value'   => 7, // block ID
				'compare' => '='
			)
		)
	));

	$query->the_post();
	$title       = get_field( 'title' ) ?: false;
	$bg_img      = get_field( 'bg_img' ) ?: false;
	$srcm = $src = $srcF = '';

	if ( $bg_img && ! empty( $bg_img['url'] ) ) {
		$url           = $bg_img['url'];
		$attachment_id = attachment_url_to_postid( $url );
		$srcm          = wp_get_attachment_image_url( $attachment_id, 'medium_large' );
		$src           = wp_get_attachment_image_url( $attachment_id, 'large' );
		$srcF          = wp_get_attachment_image_url( $attachment_id, 'full' );
	}

	$description = get_field( 'description' ) ?: false;
	?>
	<section class="rs-17">
		<div class="rs-team <?php if ( $bg_img ) { ?> b-lazy <?php } ?>" id="block-team" <?php if ( $bg_img ) { ?> data-src="<?php echo esc_url( $srcF ); ?>" data-medium="<?php echo esc_url( $src ); ?>" data-small="<?php echo esc_url( $srcm ); ?>" style="background-size: cover;"<?php } ?>>
			<div class="container">
				<?php if ( $title || $description ) : ?>
					<div class="row">
						<div class="col-xs-12">
							<?php if ( $title ) : ?>
								<h2 class="text-center section-title" data-nekoanim="fadeInUp" data-nekodelay="100">
									<span class="section-title--text"><?php echo esc_html( $title ); ?></span>
								</h2>
							<?php endif; ?>
							<?php if ( $description ) : ?>
								<div class="section-descr" data-nekoanim="fadeInUp" data-nekodelay="200">
									<p><?php echo wp_kses_post( $description ); ?></p>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
				<div class="row">
					<div class="col-xs-12">
						<div id="team-slider" class="owl-carousel">
							<?php
								$item = 0;
								while ( ! empty( get_field( "team_{$item}_img" ) ) ) {
									$img_field = get_field( "team_{$item}_img" );
									$img       = ! empty( $img_field['url'] ) ? $img_field['url'] : '';
									$name      = get_field( "team_{$item}_name" );
									$position  = get_field( "team_{$item}_position" );
									$item++; ?>
									<div class="team">
										<img class="img-responsive b-lazy" data-src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $name ); ?>">
										<?php if ( $name || $position ) : ?>
											<div class="team-info">
											<?php if ( $name ) : ?>
												<h3><?php echo esc_html( $name ); ?></h3>
											<?php endif; ?>
											<?php if ( $position ) : ?>
												<p><?php echo esc_html( $position ); ?></p>
											<?php endif; ?>
											</div>
										<?php endif; ?>
									</div>
									<?php
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
	wp_reset_postdata();
}
