<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>

<!-- rs-product -->
<section class="rs-product">
	<div class="rs-product__container">
		<? woocommerce_show_product_images(); ?>
		<div class="rs-product__description">
			<?php do_action( 'woocommerce_single_product_summary' ); ?>
			<div data-spollers data-one-spoller class="rs-product__spollers spollers">
				<div class="spollers__item">
					<button type="button" data-spoller class="spollers__title _spoller-active">
						<?_e('Description','storefront')?>
						<i class="spollers__icon"></i>
					</button>
					<div class="spollers__body">
						<div class="spollers__wrapper">
							<div class="spollers__part">
								<div class="section-text">
									<? the_content()?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<? if(get_field('composition')&&get_field('composition')!="") {?>
				<div class="spollers__item">
					<button type="button" data-spoller class="spollers__title">
						<?_e('Composition','storefront')?>
						<i class="spollers__icon"></i>
					</button>
					<div class="spollers__body">
						<div class="spollers__wrapper">
							<div class="spollers__part">
								<div class="section-text">
									<? the_field('composition')?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<? }?>
				<div class="spollers__item">
					<button type="button" data-spoller class="spollers__title">
						<?_e('Delivery and returns','storefront')?>
						<i class="spollers__icon"></i>
					</button>
					<div class="spollers__body">
						<div class="spollers__wrapper">
							<div class="section-text">
								<?
                                $delevery = get_field('delevery');
                                //var_dump($delevery);
                                if(isset($delevery) && !empty($delevery)) {
                                    echo $delevery;
                                }else{?>
									<h4 class="s-bold-title">Доставка по России, Казахстану, Беларуси</h4>
									<div class="section-text-info__part">
										<ul>
											<li>Осуществляется транспортной компанией CDEK до пункта выдачи или до двери курьером. Стоимость рассчитывается автоматически при оформлении заказа.</li>
										</ul>
									</div>
									<h4 class="s-bold-title">Самовывоз из магазина на Гороховой 49</h4>
									<div class="section-text-info__part">
										<ul>
											<li>Заказы будут доставлены со склада интернет-магазина в розничный магазин в течении 2-х рабочих дней. Как только заказ будет готов к выдаче, менеджер интернет-магазина оповестит Вас о его готовности к получению.</li>
										</ul>
										<a href="/clients/#dostavka">Подробнее о доставке</a>
									</div>
								<?  }  ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			$featured_posts = get_field('na_modeli');
			if( $featured_posts ): ?>
			<button type="button" class="spollers__title _spoller-active">
				<?_e('На модели')?>
			</button>
			<div class="on-model owl-carousel">
			<?php foreach( $featured_posts as $post ): setup_postdata($post); ?>
				<div class="on-model-item">
					<a href="<?php the_permalink(); ?>">
						<?php echo get_the_post_thumbnail( $id, 'medium' ); ?>
					</a>
				</div>
			<?php endforeach; ?>
			</div>
			<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>
	</div>
</section>
<!-- /rs-product -->

<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
?>

<?php do_action( 'woocommerce_after_single_product' ); ?>