<?php

add_action('wp_enqueue_scripts', 'style_rs_services_icon_theme', 11);
function style_rs_services_icon_theme() {
	wp_enqueue_style('rs-services-icon', get_stylesheet_directory_uri() . '/template-parts/rs-services-icon/css/rs-services-icon.css');
}

function storefront_rs_services_icon() {
	$query = new WP_Query(array(
		'post_type' => 'custom_block',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key'     => 'block_id',
				'value'   => 28, // block identifier
				'compare' => '='
			)
		)
	));

	$post_meta = null;
	while ($query->have_posts()) {
		$query->the_post();
		$post_meta = get_post_meta($query->post->ID);
	}

	$box_services_2_icon = null;
	$box_services_3_icon = null;
	$box_services_4_icon = null;

	if ($post_meta) {
		$box_services_2_icon = get_field('box_services_2_icon');
		$box_services_3_icon = get_field('box_services_3_icon');
		$box_services_4_icon = get_field('box_services_4_icon');
	}
	wp_reset_postdata();
?>
<section class="rs-17">
	<div class="rs-services-icon">
		<div class="container">
			<?php if (get_field('block_title')) : ?>
			<div class="row">
				<div class="col-xs-12">
					<h2 class="text-center section-title" data-nekoanim="fadeInUp" data-nekodelay="50"><?php echo esc_html(get_field('block_title')); ?></h2>
				</div>
			</div>
			<?php endif; ?>
			<div class="row services-row">
				<?php if (is_array($box_services_2_icon)) {
					$i = 1;
					foreach ($box_services_2_icon as $item) { ?>
						<div class="col-xs-12 col-sm-6">
							<div class="services-item" data-nekoanim="fadeInUp" data-nekodelay="<?php echo esc_attr(100 * $i++); ?>">
								<a href="<?php echo esc_url($item['link']); ?>">
									<div class="services-item--title">
										<?php if (!empty($item['name'])) { ?>
										<h3>
											<?php echo esc_html($item['name']); ?>
										</h3>
										<?php } ?>
									</div>
									<div class="services-item--description">
										<?php if (!empty($item['desc'])) { ?>
										<p>
											<?php echo esc_html($item['desc']); ?>
										</p>
										<?php } ?>
									</div>
									<div class="overlay"></div>
									<?php if (!empty($item['image'])) {
										$item_image2 = $item['image']; ?>
									<div class="services-item--img b-lazy"
                                         data-src="<?php echo esc_url($item_image2['url']); ?>"></div>
									<?php } ?>
								</a>
							</div>
						</div>
					<?php } ?>
					<div class="clearfix"></div>
				<?php } ?>
				<?php if (is_array($box_services_3_icon)) {
					foreach ($box_services_3_icon as $item) { ?>
						<div class="col-xs-12 col-sm-4">
							<div class="services-item" data-nekoanim="fadeInUp" data-nekodelay="<?php echo esc_attr(100 * $i++); ?>">
								<a href="<?php echo esc_url($item['link']); ?>">
									<div class="services-item--title">
										<?php if (!empty($item['name'])) { ?>
										<h3>
											<?php echo esc_html($item['name']); ?>
										</h3>
										<?php } ?>
									</div>
									<div class="services-item--description">
										<?php if (!empty($item['desc'])) { ?>
										<p>
											<?php echo esc_html($item['desc']); ?>
										</p>
										<?php } ?>
									</div>
									<div class="overlay"></div>
									<?php if (!empty($item['image'])) {
										$item_image3 = $item['image']; ?>
									<div class="services-item--img b-lazy"
                                         data-src="<?php echo esc_url($item_image3['url']); ?>"></div>
									<?php } ?>
								</a>
							</div>
						</div>
					<?php } ?>
					<div class="clearfix"></div>
				<?php } ?>
				<?php if (is_array($box_services_4_icon)) {
					foreach ($box_services_4_icon as $item) { ?>
						<div class="col-xs-12 col-sm-3 col-sm-3">
							<div class="services-item" data-nekoanim="fadeInUp" data-nekodelay="<?php echo esc_attr(100 * $i++); ?>">
								<a href="<?php echo esc_url($item['link']); ?>">
									<div class="services-item--title">
										<?php if (!empty($item['name'])) { ?>
										<h3>
											<?php echo esc_html($item['name']); ?>
										</h3>
										<?php } ?>
									</div>
									<div class="services-item--description">
										<?php if (!empty($item['desc'])) { ?>
										<p>
											<?php echo esc_html($item['desc']); ?>
										</p>
										<?php } ?>
									</div>
									<div class="overlay"></div>
									<?php if (!empty($item['image'])) {
										$item_image4 = $item['image']; ?>
									<div class="services-item--img b-lazy"
                                         data-src="<?php echo esc_url($item_image4['url']); ?>"></div>
									<?php } ?>
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
<?php
}
