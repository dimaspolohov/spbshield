<?php

add_action('wp_enqueue_scripts', 'style_rs_carousel_theme', 11);
function style_rs_carousel_theme() {
	wp_enqueue_style('rs-carousel', get_stylesheet_directory_uri() . '/template-parts/rs-carousel/css/rs-carousel.css');
}

function storefront_rs_carousel() {
	$query = new WP_Query(array(
		'post_type' => 'custom_block',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key'     => 'block_id',
				'value'   => 31, // block identifier
				'compare' => '='
			)
		)
	));

	$post_meta = null;
	$title = '';
	$description = '';
	$carousel = null;

	while ($query->have_posts()) {
		$query->the_post();
		$post_meta = get_post_meta($query->post->ID);
	}

	if ($post_meta) {
		$title = get_field('title') ?: '';
		$description = get_field('description') ?: '';
		$carousel = get_field('carousel') ?: '';
	}
	wp_reset_postdata();
	?>
	<section class="rs-17">
		<div class="rs-carousel main">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<?php if ($title) : ?>
							<h2 class="text-center section-title" data-nekoanim="fadeInUp" data-nekodelay="100"><?php echo esc_html($title); ?></h2>
						<?php endif; ?>	
						<?php if ($description) : ?>
						<div class="section-descr" data-nekoanim="fadeInUp" data-nekodelay="200">
							<p><?php echo esc_html($description); ?></p>
						</div>
						<?php endif; ?>	
					</div>
				</div>
			</div>

			<div class="container" data-nekoanim="fadeInUp" data-nekodelay="300">
				<div class="row">
					<div class="col-xs-12" id="foto-carousel-nav">
					</div>
				</div>
			</div>

			<?php if (is_array($carousel)) : ?>
			<div class="container-fluid">
				<div class="row gallery-row">
					<div id="foto-carousel-slider" class="owl-carousel" data-nekoanim="fadeInUp" data-nekodelay="400">
						<?php foreach ($carousel as $elem) : ?>
							<?php if (!empty($elem['img']) && is_array($elem['img'])) : ?>
							<div class="gallery-item">
							    <a title="<?php echo esc_attr($elem['img']['title']); ?>"
                                   class="b-lazy"
							    	href="<?php echo esc_url($elem['img']['url']); ?>" 
							    	data-fancybox="gallery" 
							    	data-caption=""
                                   data-src="<?php echo esc_url($elem['img']['url']); ?>"
							    	style=" background-size: cover;">
							    </a>
							</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<?php endif; ?>	
		</div>
	</section>
	<?php
}
