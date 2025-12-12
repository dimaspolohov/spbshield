<?php

function storefront_rs_collection() {
    $query = new WP_Query( array (
        'post_type' => 'custom_block',
        'meta_query' => array (
            'relation' => 'OR',
            array (
                'key'     => 'block_id',
                'value'   => 62, // идентификатор блока
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
			<?php while( have_rows('rs-collection') ): the_row(); ?>
			<div class="rs-collection__item">
				<div class="rs-collection__picture">
					<picture>
						<source srcset="<?php echo get_sub_field( 'image' )['sizes']['img-rs-collection']?>.webp" type="image/webp">
						<img src="<?php echo get_sub_field( 'image' )['sizes']['img-rs-collection']?>" alt="">
					</picture>
					<a href="<?php the_sub_field('btnlink')?>" class="bg-link"></a>
				</div>
				<div class="rs-collection__description">
					<h2 class="large-title"><a href="<?php the_sub_field('btnlink')?>"><?php the_sub_field('title')?></a></h2>
					<a href="<?php the_sub_field('btnlink')?>" class="rs-btn _border-btn _black-border-btn"><?php the_sub_field('btntext')?></a>
				</div>
			</div>
			<?php endwhile; ?>
		</div>
	</section>
	<?php endif; ?>
	<!-- /rs-collection -->
	<?php
}