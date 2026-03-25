<?php require_once("../../../../wp-load.php");

global $product;

$ID = (int)$_REQUEST[ 'id' ];
$product = wc_get_product( $ID );
$title_products = $product->get_name();
$gallery = $product->get_gallery_image_ids();
$main_img = $product->get_image_id();
	?>

	<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
		<div class="main-image col-lg-12 no-padding">
			<div class="product-largeimg-link">
				<?php if ($main_img) { ?>
				<img class="product-largeimg" src="<?php echo esc_url( wp_get_attachment_image_src($main_img, 'medium')[0] ); ?>" alt="product">
				<?php } else { ?>
				<img class="product-largeimg" src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="product">
				<?php } ?>
			</div>
		</div>

		<div class="modal-product-thumb">
			<a class="thumbLink selected">
				<?php if($main_img) { ?>
				<img data-large="<?php echo esc_url( wp_get_attachment_image_src($main_img, 'medium')[0] ); ?>"
							 alt="product"
							 class="img-responsive"
							 src="<?php echo esc_url( wp_get_attachment_image_src($main_img)[0] ); ?>">
				<?php } else { ?>
				<img data-large="<?php echo esc_url( wc_placeholder_img_src() ); ?>"
							 alt="product"
							 class="img-responsive"
							 src="<?php echo esc_url( wc_placeholder_img_src() ); ?>">
				<?php } ?>
			</a>

			<?php if ( is_array($gallery) ) { ?>
				<?php foreach ( $gallery as $value ) { ?>
					<a class="thumbLink">
						<img data-large="<?php echo esc_url( wp_get_attachment_image_src($value, 'medium')[0] ); ?>"
							 alt="product"
							 class="img-responsive"
							 src="<?php echo esc_url( wp_get_attachment_image_src($value)[0] ); ?>">
					</a>
				<?php } ?>
			<?php } ?>
			</div>

	</div>

	<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 modal-details no-padding">
		<div class="modal-details-inner">
			<h2 class="product-title"><?php echo esc_html( $title_products ); ?></h2>

			<h3 class="product-code">Артикул: <?php echo esc_html( $product->get_sku() ); ?></h3>

			<div class="product-price">
				<span class="price-sales">
					<?php echo wp_kses_post( $product->get_price_html() ); ?>
				</span>
			</div>


			<div class="details-description">
				<p><?php echo wp_kses_post( $product->get_description() ); ?></p>
			</div>

			<div class="cart-actions">
				<div class="addto row">
					<div class="col-lg-6">
						<a class="btn btn-lg btn-color" href="<?php echo esc_url( $product->get_permalink() ); ?>" >Подробнее</a>
					</div>
					<div class="clearfix"></div>
					<div class="success text-center"></div>
				</div>
			</div>

		</div>

	</div>
	<div class="clearfix"></div>

<?php wp_reset_postdata(); ?>
