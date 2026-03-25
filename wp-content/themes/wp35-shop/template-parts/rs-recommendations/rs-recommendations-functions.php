<?php

add_action('wp_enqueue_scripts', 'style_rs_recommendations_theme', 11);
function style_rs_recommendations_theme() {
	wp_enqueue_style('rs-recommendations', get_stylesheet_directory_uri() . '/template-parts/rs-recommendations/css/rs-recommendations.css');
}

function storefront_rs_recommendations() {
	$query = new WP_Query(array(
		'post_type' => 'custom_block',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key'     => 'block_id',
				'value'   => 30, // block identifier
				'compare' => '='
			)
		)
	));

	$post_meta = null;
	$title = '';
	$company_name = '';
	$description = '';
	$review = null;

	while ($query->have_posts()) {
		$query->the_post();
		$post_meta = get_post_meta($query->post->ID);
	}

	if ($post_meta) {
		$title = get_field('title') ?: '';
		$company_name = get_field('company_name') ?: '';
		$description = get_field('description') ?: '';
		$review = get_field('review') ?: '';
	}
	wp_reset_postdata();
	?>
	<section class="rs-17">
		<div class="rs-recommendations" id="block-recommendations">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<?php if ($company_name) : ?>
							<span class="text-center subsection-title" data-nekoanim="fadeInUp" data-nekodelay="100"><?php echo esc_html($company_name); ?></span>
						<?php endif; ?>
						<?php if ($title) : ?>
							<h2 class="text-center section-title" data-nekoanim="fadeInUp" data-nekodelay="200"><?php echo esc_html($title); ?></h2>
						<?php endif; ?>	
						<?php if ($description) : ?>
							<div class="section-descr" data-nekoanim="fadeInUp" data-nekodelay="300">
							<?php echo wp_kses_post($description); ?>
							</div>
						<?php endif; ?>												
					</div>
				</div>
				<?php if (is_array($review)) : ?>
					<div class="row">
						<div id="recommendations-slider" class="owl-carousel">				
						<?php foreach ($review as $elem) : ?>
							<?php if (!empty($elem['img']) && is_array($elem['img'])) : ?>
							<div class="recommendation">
						    <a title="<?php echo esc_attr($elem['img']['title']); ?>"
                               class="b-lazy"
						    	href="<?php echo esc_url($elem['img']['url']); ?>" 
						    	data-fancybox="gallery" 
						    	data-caption=""
                               data-src="<?php echo esc_url($elem['img']['url']); ?>"
						    	style="background-size: cover;"></a>
							</div>
							<?php endif; ?>
						<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>	
			</div>			
		</div>
	</section>
	<?php
}
