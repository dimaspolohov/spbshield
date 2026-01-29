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
									<h6 class="s-medium-title"><?php
                                        _e('Size:','storefront')?> <span data-title=""><?=$terms[0]->name?></span></h6>
									<div class="rs-product__size-list ">
                                        <?php
                                        foreach ( $terms as $k => $term ) { ?>
                                            <?php
                                            ob_start();?>
										<div class="rs-product__size-item">
											<label>
												<input type="radio" name="size" id="<?=$term->slug?>"<?php
                                                if($k == 0) echo ' checked="checked" class="checked"'?> value="<?=$term->slug?>">
												<span class="size <?=$term->slug?>" data-select="<?=$term->name?>"><?=$term->name?></span>
											</label>
										</div>
                                            <?php
										$output = ob_get_contents();
										ob_end_clean();
										echo $output;
										if (!empty($form)) {
											$form .= $output;
										} else {
											$form = $output;
										}								
										?>
                                        <?php
                                        } ?>
									</div>
                                    <?php
                                    if(get_field('gid_po_razmeram')) { ?><a data-popup="#size-popup"><?php
                                    _e('Гид по размерам','storefront')?></a><?php
                                    } ?>
								</div>
                            <?php
                            endif;
					}
				}
			} ?>

            <?php
			$prodsVariationColor = get_field('field_63aec089bd07d',$product->get_id());
			$terms = wc_get_product_terms( $product->get_id(),'pa_color', array( 'fields' => 'all' ) );
			if($attribute_name=='pa_color'){
				if ((is_array($terms) && !empty($terms)) && (is_array($prodsVariationColor) && !empty($prodsVariationColor))) {
				?>
					<div class="rs-product__color color">
						<h6 class="s-medium-title"><?php
                            _e('Color:','storefront')?> <span data-title=""><?=$terms[0]->name?></span></h6>
						<div class="rs-product__color-list ">
                            <?php
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
											<input type="radio" name="color"<?php
                                            if($k == 0) echo ' checked="checked"'?> value="<?=$term->slug?>">
											<span class="color" style="background:<?=$printColor?>" data-select="<?=$term->name?>"></span>
										</label>
									</div>
                                    <?php
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
                <?php
                } ?>
            <?php
            } ?>
        <?php
        endforeach; ?>

		<div class="rs-product__footer">
			<div class="rs-product__buttons">
				<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button rs-btn _background-btn _black-btn"><?php
                    _e('Add to basket','storefront')?></button>
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

                <?php \SpbShield\Inc\LegacySupport::wishlist_icon()?>
			</div>
			
			<!-- Availability check link for simple products -->
			<div class="availability-link-wrapper">
				<a href="#"
				   id="check-availability"
				   class="availability-link">
					<?php _e('<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M11.6014 8.84203C11.844 9.0361 11.8833 9.39008 11.6892 9.63266L8.53925 13.5702C8.43453 13.7011 8.2769 13.7784 8.10929 13.7812C7.94169 13.784 7.78158 13.7118 7.67261 13.5845L6.32261 12.0067C6.12064 11.7706 6.14827 11.4155 6.38432 11.2136C6.62037 11.0116 6.97545 11.0392 7.17741 11.2753L8.08545 12.3365L10.8108 8.92988C11.0048 8.68729 11.3588 8.64796 11.6014 8.84203Z" fill="black"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M2.017 5.9545C2.22798 5.74353 2.51413 5.625 2.8125 5.625H15.1875C15.4859 5.625 15.772 5.74353 15.983 5.9545C16.194 6.16548 16.3125 6.45163 16.3125 6.75V14.3877C16.3125 15.7778 15.1384 16.875 13.7812 16.875H4.21875C2.84496 16.875 1.6875 15.7175 1.6875 14.3438V6.75C1.6875 6.45163 1.80603 6.16548 2.017 5.9545ZM15.1875 6.75L2.8125 6.75L2.8125 14.3438C2.8125 15.0962 3.46629 15.75 4.21875 15.75H13.7812C14.5503 15.75 15.1875 15.1238 15.1875 14.3877V6.75Z" fill="black"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M9 2.25C8.25408 2.25 7.53871 2.54632 7.01126 3.07376C6.48382 3.60121 6.1875 4.31658 6.1875 5.0625V6.1875C6.1875 6.49816 5.93566 6.75 5.625 6.75C5.31434 6.75 5.0625 6.49816 5.0625 6.1875V5.0625C5.0625 4.01821 5.47734 3.01669 6.21577 2.27827C6.95419 1.53984 7.95571 1.125 9 1.125C10.0443 1.125 11.0458 1.53984 11.7842 2.27827C12.5227 3.01669 12.9375 4.01821 12.9375 5.0625V6.1875C12.9375 6.49816 12.6857 6.75 12.375 6.75C12.0643 6.75 11.8125 6.49816 11.8125 6.1875V5.0625C11.8125 4.31658 11.5162 3.60121 10.9887 3.07376C10.4613 2.54632 9.74592 2.25 9 2.25Z" fill="black"/>
</svg>
 <span id="availability-text">Проверить наличие в магазинах</span>', 'storefront'); ?>
				</a>
			</div>

            <?php \SpbShield\Inc\LegacySupport::free_delivery()?>
		</div>
		
		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>
	
	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
	
<?php endif; ?>

<!-- Availability Popup for simple products -->
<div id="availability-popup" class="availability-popup" style="display: none;">
    <div class="popup-overlay"></div>
    <div class="popup-content">
        
        <div class="popup-body">

            <div class="popup-loading">
                <div class="loading-spinner"></div>
                <p><?php _e('Загрузка...', 'storefront'); ?></p>
            </div>
            
            <div class="availability-content">
                <div class="map-section">
                    <div id="availability-map"></div>
                </div>
                
                <div class="info-section">
                    <span class="popup-close">&times;</span>

                    <div class="product-info">
                        <h3><?php _e('Наличие в магазинах', 'storefront'); ?></h3>
                        <h2 class="product-title"></h2>
                        <div class="product-attributes"></div>
                    </div>
                    
                    <div class="stores-list">
                        <h4><?php _e('', 'storefront'); ?></h4>
                        <div class="store-item in-stock storage-available">
                            <div class="store-name-flex">
                                <div class="store-name">В НАЛИЧИИ НА СКЛАДЕ</div>
                                <div class="store-address">Привезем в розничный магазин для самовывоза</div>
                            </div>
                        </div>
                        <div id="availability-stores"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AJAX handler and styles for simple products -->
<script>
jQuery(document).ready(function($) {
    window.availability_ajax = {
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        nonce: '<?php echo wp_create_nonce('check_availability_nonce'); ?>',
        product_id: <?php echo $product->get_id(); ?>
    };
});
</script>

<style>
/* Availability Link */
.availability-link-wrapper {
    text-align: left;
    margin-top: 15px;
}

.availability-link {
    color: #333;
    text-decoration: underline !important;
    font-size: 14px;
    cursor: pointer;
    transition: color 0.3s ease;
    display: flex !important;
    gap: 10px !important;
    align-items: center !important;
    justify-content: flex-start !important;
}

.availability-link:hover {
    color: #000;
}

.availability-link.is-disabled {
    color: #BDBDBD;
    pointer-events: auto;
    text-decoration: underline;
    cursor: pointer;
}

.availability-link.has-stock {
    color: #333;
    text-decoration: underline;
    cursor: pointer;
}

/* Popup Styles */
.availability-popup {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 9999;
    background: rgba(0, 0, 0, 0.7);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.availability-popup.show {
    opacity: 1;
    visibility: visible;
}

.popup-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.popup-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 95%;
    height: 90%;
    max-width: 1200px;
    max-height: 800px;
    overflow: hidden;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    display: flex;
    flex-direction: column;
}

.popup-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.popup-loading {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.95);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #333;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 20px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.popup-loading p {
    font-size: 16px;
    color: #666;
    margin: 0;
}

.availability-content {
    display: flex;
    height: 100%;
    flex: 1;
    gap: 8px;
    overflow: hidden;
}

.map-section {
    flex: 1;
    position: relative;
    min-height: 400px;
}

#availability-map {
    width: 100%;
    height: 100%;
    filter: grayscale(100%);
}

.info-section {
    width: 400px;
    display: flex;
    flex-direction: column;
    background: #fafafa;
    border-left: 1px solid #eee;
    flex-shrink: 0;
}

.product-info {
    padding: 25px 25px 0;
}

.product-info h3 {
    font-weight: 500;
    font-style: Medium;
    font-size: 28px;
    line-height: 100%;
    letter-spacing: 1.78px;
}

.popup-close {
    position: absolute !important;
    z-index: 100001 !important;
    cursor: pointer;
    width: 40px;
    height: 40px;
    right: 0;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #666;
    transition: all 0.3s ease;
}

.popup-close:hover {
    background: #e0e0e0;
    color: #333;
    transform: scale(1.1);
}

.product-title {
    font-weight: 500;
    font-style: Medium;
    font-size: 24px;
    line-height: 24px;
    letter-spacing: 1.78px;
}

.product-attributes {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.popup-attribute {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    font-style: Medium;
    font-size: 13px;
    line-height: 24px;
    letter-spacing: 1.16px;
}

.attribute-label {
    font-weight: 600;
    color: #666;
}

.attribute-value {
    color: #333;
}

.stores-list {
    flex: 1;
    padding: 25px;
    overflow-y: auto;
}

.stores-list h4 {
    margin: 0 0 20px 0;
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

#availability-stores {
    display: flex;
    flex-direction: column;
}

.store-item {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid #eee;
    margin-bottom: 5px;
    padding-bottom: 5px;
    padding-top: 10px;
}

.storage-available{
    border-top: 1px solid #eee;
}


.store-name {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.store-address {
    font-size: 14px;
    color: #666;
    margin-bottom: 8px;
}

.store-status {
    font-size: 12px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 4px;
    text-transform: uppercase;
    display: inline-block;
}

.store-item.in-stock .store-status {
    font-weight: 600;
    font-size: 11px;
    line-height: 24px;
    letter-spacing: 1.78px;
    text-align: right;
}

.store-item.out-of-stock .store-status {
    background: #ffebee;
    color: #c62828;
}

.no-stores {
    text-align: center;
    padding: 40px 20px;
    color: #666;
    font-size: 16px;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .popup-content {
        width: 98%;
        height: 95%;
    }
    
    .info-section {
        width: 350px;
    }
}

@media (max-width: 768px) {
    .popup-content {
        background: #fff;
        width: 100%;
        height: 100%;
        border-radius: 0;
        max-height: 100%;
    }
    
    .availability-content {
        flex-direction: column;
    }
    
    .map-section {
        display: none;
    }
    
    .info-section {
        width: 100%;
        flex: 1;
        padding-top: 50px;
    }

    .popup-close {
        font-size: 50px;
    }
}

body.popup-open {
    overflow: hidden;
}

body.popup-open header,
body.popup-open .header {
    display: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}
</style>

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=YOUR_API_KEY"></script>
<script>
// Initialize AvailabilityChecker for simple products
jQuery(document).ready(function() {
    console.log('Initializing AvailabilityChecker for simple product...');
    
    if (!document.getElementById('check-availability')) {
        console.log('Availability link not found');
        return;
    }
    
    if (typeof AvailabilityChecker === 'undefined') {
        console.error('AvailabilityChecker class not found');
        return;
    }
    
    window.availabilityChecker = new AvailabilityChecker({
        popupId: 'availability-popup',
        linkId: 'check-availability',
        textId: 'availability-text',
        storesContainerId: 'availability-stores',
        mapContainerId: 'availability-map',
        ajaxAction: 'get_gorokhovaya_csv_availability',
        isOutOfStock: false
    });
    
    console.log('AvailabilityChecker initialized for simple product');
});
</script>