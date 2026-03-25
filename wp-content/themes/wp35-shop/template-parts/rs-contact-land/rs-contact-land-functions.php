<?php

add_action('wp_enqueue_scripts', 'style_rs_contact_land_theme', 11);
function style_rs_contact_land_theme() {
	wp_enqueue_style('rs-contact-land-theme', get_stylesheet_directory_uri() . '/template-parts/rs-contact-land/css/rs-contact-land.css');
}

function storefront_rs_contact_land_child() {
	$query = new WP_Query(array(
		'post_type' => 'custom_block',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key'     => 'block_id',
				'value'   => 29, // block identifier
				'compare' => '='
			)
		)
	));

	$post_meta = null;
	$bg_img = false;
	$srcm = '';
	$src = '';
	$srcF = '';
	$notification_header = '';
	$notification_text = '';
	$title = '';
	$description = '';
	$contact_form_7 = '';

	while ($query->have_posts()) {
		$query->the_post();
		$post_meta = get_post_meta($query->post->ID);
	}

	if ($post_meta) {
		$bg_img = get_field('img') ?: false;
		if ($bg_img) {
			$url = $bg_img;
			$attachment_id = attachment_url_to_postid($url);
			$srcm = wp_get_attachment_image_url($attachment_id, 'medium_large');
			$src = wp_get_attachment_image_url($attachment_id, 'large');
			$srcF = wp_get_attachment_image_url($attachment_id, 'full');
		}
		$notification_header = get_field("notification_header") ?: '';
		$notification_text = get_field("notification_text") ?: '';
		$title = get_field("title") ?: '';
		$description = get_field("description") ?: '';
		$contact_form_7 = get_field("contact_form_7") ?: '';
	}
	wp_reset_postdata();
?>
<section class="rs-17">
	<div class="rs-contact-land <?php if ($bg_img) { ?> b-lazy <?php } ?>" <?php if ($bg_img) { ?> data-src="<?php echo esc_url($srcF); ?>" data-medium="<?php echo esc_url($src); ?>" data-small="<?php echo esc_url($srcm); ?>" style="background-size: cover;"<?php } ?>>
		<div class="container">
			<div class="row">

				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-5" data-nekoanim="fadeInUp" data-nekodelay="300">
					<div class="back">
						<!--noindex-->
                        <?php echo do_shortcode($contact_form_7); ?>
						<!--/noindex-->
					</div>
				</div>
				
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-7 text-block" data-nekoanim="fadeInUp" data-nekodelay="600">
				    <h3 class="title-block"><?php echo esc_html($title); ?></h3>
				    <div class="block-description"><?php echo wp_kses_post($description); ?></div>
				</div>				
	

			</div>
		</div>
	</div>
</section>
<div class="rs-17">
	<div class="rs-modal">
		<div class="modal fade" tabindex="-1" id="agreement-contact-land">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<div class="modal-title"><?php echo esc_html($notification_header); ?></div>
					</div>
					<div class="modal-body">
						<?php echo wp_kses_post($notification_text); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
wp_reset_postdata();
}
