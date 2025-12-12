<?php

function storefront_rs_representatives() {
    $query = new WP_Query( array (
        'post_type' => 'custom_block',
        'meta_query' => array (
            'relation' => 'OR',
            array (
                'key'     => 'block_id',
                'value'   => 61, // идентификатор блока
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
	<!-- rs-representatives -->
	<?php if( have_rows('representatives') ): ?>
	<section class="rs-representatives">
		<div class="rs-representatives__container">
			<h2 class="section-title"><? the_title()?></h2>
			<?php while( have_rows('representatives') ): the_row(); ?>
				<div class="rs-representatives__wrapper">
					<h4 class="xl-medium-title" data-aos="fade" data-aos-delay="0"><? the_sub_field('title')?></h4>
					<?php if( have_rows('representatives_group') ): ?>
					<div class="rs-representatives__slider swiper">
						<div class="rs-representatives__swiper swiper-wrapper">
							<?php while( have_rows('representatives_group') ): the_row(); ?>
							<div class="rs-representatives__slide swiper-slide" data-aos="fade" data-aos-delay="100">
								<div class="rs-representatives__item">
									<div class="rs-representatives__picture">
										<picture>
											<source srcset="<?=get_sub_field('image')['sizes']['img-representatives']?>.webp" type="image/webp">
											<img src="<?=get_sub_field('image')['sizes']['img-representatives']?>" alt="">
										</picture>
									</div>
									<div class="rs-representatives__description">
										<div class="rs-representatives__title">
											<h4 class="l-regular-title"><? the_sub_field('title')?></h4>
											<h5 class="m-regular-title"><? the_sub_field('description')?></h5>
										</div>
										<div class="rs-representatives__inst">
											<a href="<? the_sub_field('link')?>" target="_blank" class="icon-inst-border"></a>
										</div>
									</div>
								</div>
							</div>
							<?php endwhile; ?>
						</div>
					</div>
					<?php endif; ?>
				</div>
			<?php endwhile; ?>
		</div>
	</section>
	<?php endif; ?>
	<!-- /rs-representatives -->
	<?
}