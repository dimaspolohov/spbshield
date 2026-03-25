<?php

function storefront_rs_about_us() {
	if ( have_rows( 'on_about_us' ) ) :
		while ( have_rows( 'on_about_us' ) ) : the_row();
			$bg_img = get_sub_field( 'bg_img' );
	?>
	<!-- rs-about-us -->
	<section class="rs-about-us">
		<div class="rs-about-us__bg">
			<img src="<?php echo esc_url( ! empty( $bg_img['url'] ) ? $bg_img['url'] : '' ); ?>" alt="" draggable="false">
		</div>
		<div class="rs-about-us__container">
			<div class="rs-about-us__wrapper">
				<div class="rs-about-us__description">
					<h2 class="section-title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
					<h4 class="m-regular-title"><?php echo esc_html( get_sub_field( 'subtitle' ) ); ?></h4>
					<div class="section-text">
						<?php echo wp_kses_post( get_sub_field( 'description' ) ); ?>
					</div>
				</div>
				<?php
				$slider = get_sub_field( 'slider' );
				if ( $slider ) : ?>
				<div class="rs-about-us__pictures">
					<div class="rs-about-us__slider swiper">
						<div class="rs-about-us__swiper swiper-wrapper">
						<?php foreach ( $slider as $slide ) : ?>
							<div class="rs-about-us__slide swiper-slide">
								<?php $slide_url = ! empty( $slide['sizes']['img-about'] ) ? $slide['sizes']['img-about'] : ''; ?>
								<picture>
									<source srcset="<?php echo esc_url( $slide_url ); ?>.webp" type="image/webp">
									<img src="<?php echo esc_url( $slide_url ); ?>" alt="">
								</picture>
							</div>
						<?php endforeach; ?>
						</div>
						<div class="rs-about-us__pagination swiper-pagination"></div>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<!-- /rs-about-us -->
	<?php endwhile; ?>
	<?php endif;
}
