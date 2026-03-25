<?php

// Enqueue features-photo block styles
add_action( 'wp_enqueue_scripts', 'style_rs_features_photo_theme' );

function style_rs_features_photo_theme() {
	wp_enqueue_style( 'rs-features-photo-theme', get_stylesheet_directory_uri() . '/template-parts/rs-features-photo/css/rs-features-photo.css' );
}

// Render features block with central photo
function storefront_rs_features_photo() {
	$post_meta    = null;
	$title        = '';
	$bg_img       = false;
	$center_image = '';
	$items        = array_fill( 0, 4, array( 'name' => '', 'text' => '' ) );
	$srcm         = '';
	$src          = '';
	$srcF         = '';

	$query = new WP_Query( array(
		'post_type'  => 'custom_block',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key'     => 'block_id',
				'value'   => 9,
				'compare' => '=',
			),
		),
	) );

	while ( $query->have_posts() ) {
		$query->the_post();
		$post_meta = get_post_meta( $query->post->ID );
	}

	if ( $post_meta ) {
		$bg_img = get_field( 'bg_img' ) ?: false;
		if ( $bg_img ) {
			$url           = $bg_img['url'];
			$attachment_id = attachment_url_to_postid( $url );
			$srcm          = wp_get_attachment_image_url( $attachment_id, 'medium_large' );
			$src           = wp_get_attachment_image_url( $attachment_id, 'large' );
			$srcF          = wp_get_attachment_image_url( $attachment_id, 'full' );
		}
		$center_field = get_field( 'center_img' );
		$center_image = isset( $center_field['url'] ) ? $center_field['url'] : '';
		$title        = get_field( 'title' ) ?: '';
		for ( $i = 0; $i < 4; $i++ ) {
			$items[ $i ] = array(
				'name' => get_field( "feature_{$i}_name" ) ?: '',
				'text' => get_field( "feature_{$i}_text" ) ?: '',
			);
		}
	}
	?>
	<section class="rs-17">
		<div class="rs-features-photo <?php if ( $bg_img ) { ?> b-lazy <?php } ?>" id="block-features-photo" <?php if ( $bg_img ) { ?> data-src="<?php echo esc_url( $srcF ); ?>" data-medium="<?php echo esc_url( $src ); ?>" data-small="<?php echo esc_url( $srcm ); ?>" style="background-size: cover;"<?php } ?>>
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<h2 class="text-center section-title" data-nekoanim="fadeInUp" data-nekodelay="100">
							<span class="section-title--text"><?php echo esc_html( $title ); ?></span>
						</h2>
					</div>
				</div>
				<div class="special-features">
					<div class="row">
						<div class="col-sm-3" data-nekoanim="fadeInLeft" data-nekodelay="200">
							<div class="special-content text-right">
								<h4><?php echo esc_html( $items[0]['name'] ); ?></h4>
								<p><?php echo esc_html( $items[0]['text'] ); ?></p>
							</div>
							<div class="special-content text-right">
								<h4><?php echo esc_html( $items[1]['name'] ); ?></h4>
								<p><?php echo esc_html( $items[1]['text'] ); ?></p>
							</div>
						</div>
						<div class="col-sm-6" data-nekoanim="fadeInUp" data-nekodelay="300">
							<div class="special-image">
								<img class="img-responsive b-lazy" src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/img/img0.png" data-src="<?php echo esc_url( $center_image ); ?>" alt="center-img">
							</div>
						</div>
						<div class="col-sm-3" data-nekoanim="fadeInRight" data-nekodelay="400">
							<div class="special-content text-left">
								<h4><?php echo esc_html( $items[2]['name'] ); ?></h4>
								<p><?php echo esc_html( $items[2]['text'] ); ?></p>
							</div>
							<div class="special-content text-left">
								<h4><?php echo esc_html( $items[3]['name'] ); ?></h4>
								<p><?php echo esc_html( $items[3]['text'] ); ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
	wp_reset_postdata();
}
