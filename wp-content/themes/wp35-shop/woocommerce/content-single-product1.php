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
<section class="rs-product rs-product-fixed">
	<div class="rs-product__container">
		<? wc_get_template( 'single-product/product-image1.php' ); ?>
		<div class="rs-product__description">
			<div class="rs-product__description__wrapper">
				<?php do_action( 'woocommerce_single_product_summary' ); ?>
				<div data-spollers data-one-spoller class="rs-product__spollers spollers">
					<?
					$content = get_the_content();
					if($content&&$content!="") {?>
					<div class="spollers__item">
						<button type="button" data-spoller class="spollers__title">
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
					<? } ?>
					<? $razmer_i_posadka = get_field('razmer_i_posadka'); if($razmer_i_posadka&&$razmer_i_posadka!='') {?>
					<div class="spollers__item">
						<button type="button" data-spoller class="spollers__title">
							<?_e('Размер и посадка')?>
							<i class="spollers__icon"></i>
						</button>
						<div class="spollers__body">
							<div class="spollers__wrapper">
								<div class="spollers__part">
									<div class="section-text">
										<?=$razmer_i_posadka?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<? }?>
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
										
											<h4 class="s-bold-title">Россия, Беларусь, Казахстан</h4>

											<p>Доставка заказов по России, Беларуси и Казахстану* осуществляется <strong>транспортной компанией </strong><strong>CDEK </strong>до пункта выдачи или до двери курьером. Стоимость рассчитывается автоматически при оформлении заказа. Сборка заказов осуществляется <strong>в рабочие дни</strong> с 10:00 − 18:00. Обработка и отправка заказа происходит в течение 5-ти рабочих дней, не считая день оформления заказа. Трек-номер высылается на почту, указанную при оформлении заказа, как только начинается обработка заказа.</p>
											<p>В случае, если в вашем городе нет отделения <strong>CDEK</strong>, вы можете оформить заказ с доставкой <strong>Почты России</strong>. Трек-номер отправления придет на почту, указанную при оформлении заказа. Передача заказов в почтовое отделение происходит в течение 5-ти рабочих дней, не считая день оформления заказа.</p>
											<a href="/clients/#dostavka">Подробнее о доставке</a>
										</div>
									<?  }  ?>
								</div>
							</div>
						</div>
				</div>

				<?php
				$featured_posts = get_field('na_modeli');
				if( $featured_posts ): ?>
				<button type="button" class="spollers__title">
					<? _e('На модели'); ?>
				</button>

				<div class="on-model owl-carousel">
					<?php foreach( $featured_posts as $post ): setup_postdata($post); ?>
						<div class="on-model-item">
							<a href="<?php the_permalink(); ?>">
								<?php echo get_the_post_thumbnail( get_the_ID(), 'medium' ); ?>
							</a>
						</div>
					<?php endforeach; ?>
				</div>

				<?php wp_reset_postdata(); ?>
				<?php endif; ?>
				</div>
			</div>
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