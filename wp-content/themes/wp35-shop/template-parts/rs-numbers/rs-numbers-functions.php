<?php

function style_rs_numbers_theme() {
	wp_enqueue_style( 'rs-numbers', get_stylesheet_directory_uri() . '/template-parts/rs-numbers/css/rs-numbers.css' );
}
add_action( 'wp_enqueue_scripts', 'style_rs_numbers_theme', 12 );

function storefront_rs_numbers() {
	$query = new WP_Query( array(
		'post_type' => 'custom_block',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key'     => 'block_id',
				'value'   => 11, // block ID
				'compare' => '='
			)
		)
	));

	$post_meta  = null;
	$numbers    = [];
	$col_format = '';
	$srcm = $src = $srcF = '';

	while ( $query->have_posts() ) {
		$query->the_post();
		$post_meta = get_post_meta( $query->post->ID );
	}

	$bg_img = get_field( 'bg_img' ) ?: false;
	if ( $bg_img && ! empty( $bg_img['url'] ) ) {
		$url           = $bg_img['url'];
		$attachment_id = attachment_url_to_postid( $url );
		$srcm          = wp_get_attachment_image_url( $attachment_id, 'medium_large' );
		$src           = wp_get_attachment_image_url( $attachment_id, 'large' );
		$srcF          = wp_get_attachment_image_url( $attachment_id, 'full' );
	}

	if ( $post_meta ) {
		$item = 0;
		while ( ! empty( get_field( "numbers_{$item}_img" ) ) ) {
			$num_img = get_field( "numbers_{$item}_img" );
			array_push( $numbers, [
				'img'    => ! empty( $num_img['url'] ) ? $num_img['url'] : '',
				'text'   => get_field( "numbers_{$item}_text" ) ?: '',
				'number' => get_field( "numbers_{$item}_number" ) ?: '',
				'type'   => get_field( "numbers_{$item}_type" ) ?: ''
			]);
			$item++;
		}
		switch ( $item ) {
			case 1:
				$col_format = 'col-xs-12 col-sm-12 col-md-12 col-lg-12 num-item';
				break;
			case 2:
				$col_format = 'col-xs-12 col-sm-12 col-md-6 col-lg-6 num-item';
				break;
			case 3:
				$col_format = 'col-xs-12 col-sm-12 col-md-4 col-lg-4 num-item';
				break;
			case 6:
				$col_format = 'col-xs-6 col-sm-6 col-md-2 col-lg-2 num-item';
				break;
			default:
				$col_format = 'col-xs-6 col-sm-6 col-md-3 col-lg-3 num-item';
				break;
		}
	}
	?>
	<section class="rs-17">
		<div class="rs-numbers <?php if ( $bg_img ) { ?> b-lazy <?php } ?>" id="block-nums" <?php if ( $bg_img ) { ?> data-src="<?php echo esc_url( $srcF ); ?>" data-medium="<?php echo esc_url( $src ); ?>" data-small="<?php echo esc_url( $srcm ); ?>" style="background-size: cover;"<?php } ?>>
			<h2 class="hidden-title">Блок цифры</h2>
			<div class="container">
				<div class="row">
				<?php foreach ( $numbers as $value ) : ?>
					<div class="<?php echo esc_attr( $col_format ); ?>">
						<div class="media">
							<div class="media-left">
								<img class="media-object b-lazy" data-src="<?php echo esc_url( $value['img'] ); ?>" alt="picture">
							</div>
							<div class="media-body">
								<span class="num-count">
									<span class="rs-count"><?php echo esc_html( $value['number'] ); ?></span>
									<?php echo esc_html( $value['type'] ); ?>
								</span>
								<p><?php echo esc_html( $value['text'] ); ?></p>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
	wp_reset_postdata();
}
