<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.2.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product ); // WPCS: XSS ok.

$attributes = $product->get_attributes();

if ( $product->is_in_stock() ) : ?>
	
	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>
	<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
		<?php 
		foreach ( $attributes as $attribute_name => $options ) :
			if($attribute_name=='pa_size'){
				$options = $options['options'];
				if ( ! empty( $options ) ) {
					if ( taxonomy_exists( $attribute_name ) ) {
						$terms = wc_get_product_terms( $product->get_id(), $attribute_name, array( 'fields' => 'all' ) );
							if($terms[0]->slug!='onesizeonesize') : ?>
								<div class="rs-product__size size">
									<h6 class="s-medium-title"><?_e('Size:','storefront')?> <span data-title=""><?=$terms[0]->name?></span></h6>
									<div class="rs-product__size-list ">
									<? foreach ( $terms as $k => $term ) { ?>
										<?ob_start();?>
										<div class="rs-product__size-item">
											<label>
												<input type="radio" name="size" id="<?=$term->slug?>"<? if($k==0) echo ' checked="checked" class="checked"'?> value="<?=$term->slug?>">
												<span class="size <?=$term->slug?>" data-select="<?=$term->name?>"><?=$term->name?></span>
											</label>
										</div>
										<?
										$output = ob_get_contents();
										ob_end_clean();
										echo $output;
										if (!empty($form)) {
											$form .= $output;
										} else {
											$form = $output;
										}								
										?>
									<? } ?>
									</div>
									<? if(get_field('gid_po_razmeram')) { ?><a data-popup="#size-popup"><?_e('Гид по размерам','storefront')?></a><? } ?>
								</div>
						<? 	endif;
					}
				}
			} ?>
		
			<?
			$prodsVariationColor = get_field('field_63aec089bd07d',$product->get_id());
			$terms = wc_get_product_terms( $product->get_id(),'pa_color', array( 'fields' => 'all' ) );
			if($attribute_name=='pa_color'){
				if ((is_array($terms) && !empty($terms)) && (is_array($prodsVariationColor) && !empty($prodsVariationColor))) {
				?>
					<div class="rs-product__color color">
						<h6 class="s-medium-title"><?_e('Color:','storefront')?> <span data-title=""><?=$terms[0]->name?></span></h6>
						<div class="rs-product__color-list ">
							<? 
							$output = array();
							if ( taxonomy_exists( $attribute_name ) && (is_array($terms) && !empty($terms)) ) { 
								foreach ( $terms as $k => $term ) { 
									ob_start(); 
									
									// ИСПРАВЛЕННАЯ ЛОГИКА ДЛЯ ДВОЙНОГО ЦВЕТА
									if (get_field("enable_two_color", $term) === true) {
										$printColor = explode('-',$term->slug);
										$printColor = "linear-gradient(300deg, #".$printColor[0]." 50%, #".$printColor[1]." 50%)";
									} else {
										$printColor = "#" . explode('-',$term->slug)[0];
									}
									?>
									<div class="rs-product__color-item">
										<label>
											<input type="radio" name="color"<? if($k==0) echo ' checked="checked"'?> value="<?=$term->slug?>">
											<span class="color" style="background:<?=$printColor?>" data-select="<?=$term->name?>"></span>
										</label>
									</div>
									<? 
									$output[$term->term_id] = ob_get_contents(); 
									ob_end_clean(); 
								}
							}
							
							if($prodsVariationColor):
								foreach($prodsVariationColor as $key => $item): 
									ob_start(); 
									
									// ИСПРАВЛЕННАЯ ЛОГИКА ДЛЯ ДВОЙНОГО ЦВЕТА И ДЛЯ СВЯЗАННЫХ ТОВАРОВ
									if (get_field("enable_two_color", $item['color']) === true) {
										$printColor = explode('-',$item['color']->slug);
										$printColor = "linear-gradient(300deg, #".$printColor[0]." 50%, #".$printColor[1]." 50%)";
									} else {
										$printColor = "#" . explode('-',$item['color']->slug)[0];
									}
									?>
									<a href="<?=get_permalink($item['prod']->ID)?>" class="rs-product__color-item">
										<label>
											<span class="" style="background:<?=$printColor?>" title="<?=$item['color']->name?>"></span>
										</label>
									</a>
									<?php 
									$output[$item['color']->term_id] = ob_get_contents(); 
									ob_end_clean(); 
								endforeach;
							endif; 
							
							$all_colors = get_terms( 'pa_color', [
								'hide_empty' => false,
								'fields' => 'ids',
							] );
							
							foreach($all_colors as $key => $all_color) {
								if(isset($output[$all_color])) {
									$all_colors[$key] = $output[$all_color];
								} else {
									unset($all_colors[$key]);
								}
							}
							echo implode('',$all_colors);
							?>
						</div>
					</div>
				<? } ?>
			<? } ?>
		<? endforeach; ?>

		<div class="rs-product__footer">
			<div class="rs-product__buttons">
				<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button rs-btn _background-btn _black-btn"><?_e('Add to basket','storefront')?></button>
				<!-- Кнопка Купить на Mozij.com -->
				<a href="https://mozij.com/ru/product/1310" class="87but rs-btn _background-btn _black-btn" target="_blank">Купить на Mozij.com</a>
				<style>a.\38 7but {display:none;}</style>
				<?php if($_SERVER['REQUEST_URI'] == '/product/spbshield-ring/') { ?>
				<style>button.single_add_to_cart_button.rs-btn._background-btn._black-btn {display: none!important;}
				a.\38 7but.rs-btn._background-btn._black-btn {display: block!important;}</style>
				<?php } ?>
				<!-- Кнопка Купить на Mozij.com -->

				<!-- Кольцо серебро  -->
				<!-- Кнопка Купить на Mozij.com -->
				<a href="https://mozij.ru/product/1497" class="87-1but rs-btn _background-btn _black-btn" target="_blank">Купить на Mozij.com</a>
				<style> a.\38 7-1but {display:none;}</style>
				<?php 
				if($_SERVER['REQUEST_URI'] == '/product/kolczo-812-2-silver/') 
					{ ?>
				<style>
				button.single_add_to_cart_button.rs-btn._background-btn._black-btn {display: none!important;}
				a.\38 7-1but.rs-btn._background-btn._black-btn {display: block!important;}
				</style>
				<?php } ?>
				<!-- Кнопка Купить на Mozij.com -->

				<!-- Кольцо золото -->
				<!-- Кнопка Купить на Mozij.com -->
				<a href="https://mozij.ru/product/1503" class="87-2but rs-btn _background-btn _black-btn" target="_blank">Купить на Mozij.com</a>
				<style>a.\38 7-2but {display:none;}</style>
				<?php if($_SERVER['REQUEST_URI'] == '/product/kolczo-812-gold/') { ?>
				<style>button.single_add_to_cart_button.rs-btn._background-btn._black-btn {display: none!important;}
				a.\38 7-2but.rs-btn._background-btn._black-btn {display: block!important;}</style>
				<?php } ?>

				<!-- Подвеска 812 серебро  -->
				<!-- Кнопка Купить на Mozij.com -->
				<a href="https://mozij.ru/product/1498" class="87-3but rs-btn _background-btn _black-btn" target="_blank">Купить на Mozij.com</a>
				<style>a.\38 7-3but {display:none;}</style>
				<?php if($_SERVER['REQUEST_URI'] == '/product/podveska-812/') { ?>
				<style>button.single_add_to_cart_button.rs-btn._background-btn._black-btn {display: none!important;}
				a.\38 7-3but.rs-btn._background-btn._black-btn {display: block!important;}</style>
				<?php } ?>

				<!-- Подвеска 812 золото  -->
				<!-- Кнопка Купить на Mozij.com -->
				<a href="https://mozij.ru/product/1504" class="87-4but rs-btn _background-btn _black-btn" target="_blank">Купить на Mozij.com</a>
				<style>a.\38 7-4but {display:none;}</style>
				<?php if($_SERVER['REQUEST_URI'] == '/product/podveska-812-2/') { ?>
				<style>button.single_add_to_cart_button.rs-btn._background-btn._black-btn {display: none!important;}
				a.\38 7-4but.rs-btn._background-btn._black-btn {display: block!important;}</style>
				<?php } ?>

				<!-- Подвеска сияние серебро  -->
				<!-- Кнопка Купить на Mozij.com -->
				<a href="https://mozij.ru/product/1499" class="87-5but rs-btn _background-btn _black-btn" target="_blank">Купить на Mozij.com</a>
				<style>a.\38 7-5but {display:none;}</style>
				<?php if($_SERVER['REQUEST_URI'] == '/product/podveska-siyanie/') { ?>
				<style>button.single_add_to_cart_button.rs-btn._background-btn._black-btn {display: none!important;}
				a.\38 7-5but.rs-btn._background-btn._black-btn {display: block!important;}</style>
				<?php } ?>

				<!-- Подвеска сияние золото  -->
				<!-- Кнопка Купить на Mozij.com -->
				<a href="https://mozij.ru/product/1505" class="87-6but rs-btn _background-btn _black-btn" target="_blank">Купить на Mozij.com</a>
				<style>a.\38 7-6but {display:none;}</style>
				<?php if($_SERVER['REQUEST_URI'] == '/product/podveska-siyanie-2/') { ?>
				<style>button.single_add_to_cart_button.rs-btn._background-btn._black-btn {display: none!important;}
				a.\38 7-6but.rs-btn._background-btn._black-btn {display: block!important;}</style>
				<?php } ?>

				<!-- Подвеска Ростральная колонна серебро  -->
				<!-- Кнопка Купить на Mozij.com -->
				<a href="https://mozij.ru/product/1500" class="87-7but rs-btn _background-btn _black-btn" target="_blank">Купить на Mozij.com</a>
				<style>a.\38 7-7but {display:none;}</style>
				<?php if($_SERVER['REQUEST_URI'] == '/product/podveska-rostra/') { ?>
				<style>button.single_add_to_cart_button.rs-btn._background-btn._black-btn {display: none!important;}
				a.\38 7-7but.rs-btn._background-btn._black-btn {display: block!important;}</style>
				<?php } ?>

				<!-- Подвевска Ростральная колонна золото -->
				<!-- Кнопка Купить на Mozij.com -->
				<a href="https://mozij.ru/product/1506" class="87-8but rs-btn _background-btn _black-btn" target="_blank">Купить на Mozij.com</a>
				<style>a.\38 7-8but {display:none;}</style>
				<?php if($_SERVER['REQUEST_URI'] == '/product/podveska-rostra-2/') { ?>
				<style>button.single_add_to_cart_button.rs-btn._background-btn._black-btn {display: none!important;}
				a.\38 7-8but.rs-btn._background-btn._black-btn {display: block!important;}</style>
				<?php } ?>

				<!-- Браслет Звенья серебро  -->
				<!-- Кнопка Купить на Mozij.com -->
				<a href="https://mozij.ru/product/1502" class="87-9but rs-btn _background-btn _black-btn" target="_blank">Купить на Mozij.com</a>
				<style>a.\38 7-9but {display:none;}</style>
				<?php if($_SERVER['REQUEST_URI'] == '/product/braslet-zvenya/') { ?>
				<style>button.single_add_to_cart_button.rs-btn._background-btn._black-btn {display: none!important;}
				a.\38 7-9but.rs-btn._background-btn._black-btn {display: block!important;}</style>
				<?php } ?>

				<!-- Браслет Звенья золото  -->
				<!-- Кнопка Купить на Mozij.com -->
				<a href="https://mozij.ru/product/1508" class="87-10but rs-btn _background-btn _black-btn" target="_blank">Купить на Mozij.com</a>
				<style>a.\38 7-10but {display:none;}</style>
				<?php if($_SERVER['REQUEST_URI'] == '/product/braslet-zvenya-2/') { ?>
				<style>button.single_add_to_cart_button.rs-btn._background-btn._black-btn {display: none!important;}
				a.\38 7-10but.rs-btn._background-btn._black-btn {display: block!important;}</style>
				<?php } ?>

				<!-- Пуссеты Лого серебро  -->
				<!-- Кнопка Купить на Mozij.com -->
				<a href="https://mozij.ru/product/1501" class="87-11but rs-btn _background-btn _black-btn" target="_blank">Купить на Mozij.com</a>
				<style>a.\38 7-11but {display:none;}</style>
				<?php if($_SERVER['REQUEST_URI'] == '/product/pusety-logo/') { ?>
				<style>button.single_add_to_cart_button.rs-btn._background-btn._black-btn {display: none!important;}
				a.\38 7-11but.rs-btn._background-btn._black-btn {display: block!important;}</style>
				<?php } ?>

				<!-- Пуссеты Лого золото  -->
				<!-- Кнопка Купить на Mozij.com -->
				<a href="https://mozij.ru/product/1507" class="87-12but rs-btn _background-btn _black-btn" target="_blank">Купить на Mozij.com</a>
				<style>a.\38 7-12but {display:none;}</style>
				<?php if($_SERVER['REQUEST_URI'] == '/product/pusety-logo-2/') { ?>
					<style>button.single_add_to_cart_button.rs-btn._background-btn._black-btn {display: none!important;}
					a.\38 7-12but.rs-btn._background-btn._black-btn {display: block!important;}</style>
				<?php } ?>
			
				<? wishlist_icon()?>
			</div>
			<? free_delivery()?>
		</div>
		
		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>
	
	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
	
<?php endif; ?>