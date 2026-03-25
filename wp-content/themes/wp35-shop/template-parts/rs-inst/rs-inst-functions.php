<?php

function storefront_rs_inst() {
	$front_page_id = get_option( 'page_on_front' );
	$insta = get_field( 'insta', $front_page_id );

	if ( $insta ) { ?>
	<!-- rs-inst -->
	<section class="rs-inst">
		<div class="rs-inst__container">
			<?php $data = get_field( 'socials', $front_page_id ); ?>
			<?php if ( ! empty( $data['ig'] ) ) : ?>
			<h2 class="large-title"><a href="<?php echo esc_url( $data['ig'] ); ?>" target="_blank">@<?php
				$ig = explode( '/', $data['ig'] );
				while ( count( $ig ) > 0 && end( $ig ) == '' ) {
					array_pop( $ig );
				}
				echo esc_html( end( $ig ) );
			?></a></h2>
			<?php endif; ?>
			<div class="rs-inst__wrapper">
				<div class="rs-inst__slider swiper">
					<div class="rs-inst__swiper swiper-wrapper">
						<?php foreach ( $insta as $inst_item ) : ?>
						<div class="rs-inst__slide swiper-slide">
							<?php $img_url = ! empty( $inst_item['image']['sizes']['img-rs-inst'] ) ? $inst_item['image']['sizes']['img-rs-inst'] : ''; ?>
							<picture>
								<source srcset="<?php echo esc_url( $img_url ); ?>.webp" type="image/webp">
								<img src="<?php echo esc_url( $img_url ); ?>" alt="">
							</picture>
							<?php if ( ! empty( $inst_item['link'] ) ) : ?>
								<a href="<?php echo esc_url( $inst_item['link'] ); ?>" target="_blank"></a>
							<?php endif; ?>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="rs-inst__button-prev swiper-button-prev icon-menu-arrow_left"></div>
				<div class="rs-inst__button-next swiper-button-next icon-menu-arrow_right"></div>
			</div>
		</div>
	</section>
	<?php }
}
