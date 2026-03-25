<?php

// Enqueue on-sale block styles
add_action( 'wp_enqueue_scripts', 'style_rs_onsale_theme' );

function style_rs_onsale_theme() {
	wp_enqueue_style( 'rs-onsale', get_stylesheet_directory_uri() . '/template-parts/rs-onsale/css/rs-onsale.css' );
}

// Render on-sale products block
function storefront_onsale_products_child( $args ) {
	$args = array(
		'post_type'  => 'product',
		'orderby'    => 'date',
		'meta_query' => array(
			array(
				'key'   => '_onsale_product',
				'value' => 'yes',
			),
		),
	);

	$loop = new WP_Query( $args );
	if ( $loop->have_posts() ) :
		?>
		<section class="rs-17">
			<div class="rs-onsale">
				<div class="container">
					<div class="row">
						<div class="col-xs-12">
							<h2 class="text-center section-title" data-nekoanim="fadeInUp" data-nekodelay="100"><span class="section-title--text">распродажа</span></h2>
						</div>
						<div class="col-xs-12 no-padding">
							<div id="product-slider-onsale" class="owl-carousel" data-nekoanim="fadeInUp">
								<?php
								while ( $loop->have_posts() ) : $loop->the_post();
									global $product;
									if ( $product->is_type( 'variable' ) ) {
										$regular_price = $product->get_variation_regular_price( 'min' );
										$sale_price    = $product->get_variation_sale_price( 'min' );
									} else {
										$regular_price = $product->get_regular_price();
										$sale_price    = $product->get_sale_price();
									}
									$discount = ( $regular_price && $sale_price ) ? ceil( ( ( $regular_price - $sale_price ) * 100 ) / $regular_price ) : '';
									$onsale   = $product->is_on_sale();
									$discount = $discount == 100 ? '' : $discount;
									$discount = $discount ? $discount . ' %' : '';

									ob_start();
									the_title();
									$title_products = ob_get_clean();
									$title_products = ( mb_strlen( $title_products ) > 45 )
										? mb_substr( $title_products, 0, 45 ) . '...' : $title_products;

									$post_id      = $loop->post->ID;
									$permalink    = get_permalink( $post_id );
									$thumbnail_id = get_post_thumbnail_id( $post_id );
									?>
									<div class="product-item">
										<div class="product">
											<div class="product-image">
												<div class="quickview">
													<a data-id="<?php echo absint( $post_id ); ?>" class="btn btn-xs btn-quickview" href="#" data-path="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>" data-target="#onsale-product-details-modal" data-toggle="modal">Быстрый просмотр</a>
												</div>
												<a href="<?php echo esc_url( $permalink ); ?>">
													<?php if ( has_post_thumbnail( $post_id ) ) :
														$img_url = wp_get_attachment_image_url( $thumbnail_id, 'shop_catalog' );
														?>
														<img class="b-lazy" data-src="<?php echo esc_url( $img_url ); ?>" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/img0.png' ); ?>" alt="">
													<?php else : ?>
														<img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="Placeholder" width="250" height="250">
													<?php endif; ?>
												</a>
												<ul class="promotion">
													<?php if ( get_field( '_new_product' ) === 'yes' ) : ?>
														<li class="new">NEW</li>
													<?php endif; ?>
													<?php if ( $onsale ) : ?>
														<li class="discount">SALE <?php echo esc_html( $discount ); ?></li>
													<?php endif; ?>
												</ul>
											</div>
											<div class="description">
												<h3><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title_products ); ?></a></h3>
												<p>
													<?php
													$short_description = get_the_excerpt();
													$description       = $short_description
														? \SpbShield\Inc\ServiceFunctions::truncate_text( strip_tags( preg_replace( '~\[[^\]]+\]~', '', $short_description ) ), 80 )
														: \SpbShield\Inc\ServiceFunctions::truncate_text( strip_tags( preg_replace( '~\[[^\]]+\]~', '', $product->get_description() ) ), 80 );
													echo esc_html( $description );
													?>
												</p>
											</div>
											<?php
											$values = explode( ',', $product->get_attribute( 'pa_size' ) );
											echo '<div class="sub-description">' . esc_html( implode( '/', $values ) ) . '</div>';
											$class = $product->is_on_sale() ? 'in_sale' : '';
											?>
											<div class="price <?php echo esc_attr( $class ); ?>">
												<?php echo wp_kses_post( $product->get_price_html() ); ?>
											</div>
											<div class="action-control">
												<a href="<?php echo esc_url( $permalink ); ?>" class="btn btn-color">Подробнее</a>
											</div>
										</div>
									</div>
								<?php endwhile; ?>
								<?php wp_reset_postdata(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<div class="rs-17">
			<div class="rs-product-view">
				<div class="modal fade" id="onsale-product-details-modal" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog">
						<div id="modal-product-onsale" class="modal-content">
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif;
}
