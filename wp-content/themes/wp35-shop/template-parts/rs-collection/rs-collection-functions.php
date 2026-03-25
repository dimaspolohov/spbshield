<?php

function storefront_rs_collection() {
    $query = new WP_Query( array (
        'post_type' => 'custom_block',
        'meta_query' => array (
            'relation' => 'OR',
            array (
                'key'     => 'block_id',
                'value'   => \SpbShield\Inc\ThemeConfig::BLOCK_ID_COLLECTION,
                'compare' => '='
            )
        )
    ));
    while ( $query->have_posts() ) {
        $query->the_post();
        $post_meta = get_post_meta($query->post->ID);
    }
    if ($post_meta) {
        $block_title = get_field("block_title");
    }
	?>
	<!-- rs-collection -->
	<?php if( have_rows('rs-collection') ): ?>
	<section class="rs-collection">
		<div class="rs-collection__wrapper">
			<?php while( have_rows('rs-collection') ): the_row();
			$image = get_sub_field('image');
			$img_url = ($image && isset($image['sizes']['img-rs-collection'])) ? $image['sizes']['img-rs-collection'] : '';
			$btn_link = get_sub_field('btnlink');
		?>
			<div class="rs-collection__item">
				<div class="rs-collection__picture">
					<picture>
						<source srcset="<?php echo esc_url($img_url); ?>.webp" type="image/webp">
						<img src="<?php echo esc_url($img_url); ?>" alt="">
					</picture>
					<a href="<?php echo esc_url($btn_link); ?>" class="bg-link"></a>
				</div>
				<div class="rs-collection__description">
					<h2 class="large-title"><a href="<?php echo esc_url($btn_link); ?>"><?php echo esc_html(get_sub_field('title')); ?></a></h2>
					<a href="<?php echo esc_url($btn_link); ?>" class="rs-btn _border-btn _black-border-btn"><?php echo esc_html(get_sub_field('btntext')); ?></a>
				</div>
			</div>
			<?php endwhile; ?>
		</div>
	</section>
	<?php endif; ?>
	<!-- /rs-collection -->
	<?php
}