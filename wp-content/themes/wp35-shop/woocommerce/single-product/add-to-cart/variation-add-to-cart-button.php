<?php
/**
 * Single variation cart button
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.2.0
 */

defined('ABSPATH') || exit;

global $product;

if (!$product->is_purchasable()) {
    return;
}

// Получаем атрибуты товара
$term_color = wc_get_product_terms($product->get_id(), 'pa_color', array('fields' => 'names'));
$color = array_shift($term_color);
$term_size = wc_get_product_terms($product->get_id(), 'pa_size', array('fields' => 'names'));
$size = array_shift($term_size);

// Формируем текст для тултипа
$tooltip_text = '';
if (($color || $size) && $product->is_in_stock()) {
    if ($size) {
        if ($tooltip_text != '') $tooltip_text .= ' ' . __('and', 'storefront') . ' ';
        $tooltip_text .= __('size', 'storefront');
    }
    $tooltip_text = __('Select', 'storefront') . ' ' . $tooltip_text;
}
?>

<?php if ($product->is_in_stock()): ?>
<div class="woocommerce-variation-add-to-cart variations_button cart-actions clearfix">
    <?php do_action('woocommerce_before_add_to_cart_button'); ?>
    
    <div class="rs-product__footer">
        <div class="rs-product__buttons">
            <button type="submit" name="add-to-cart" class="rs-btn _background-btn _black-btn single_add_to_cart_button">
                <?php _e('Add to basket', 'storefront'); ?>
            </button>
            
            <?php if ($tooltip_text): ?>
                <span class="tooltiptext"><?php echo esc_html($tooltip_text); ?></span>
            <?php endif; ?>
            
            <?php wishlist_icon(); ?>
        </div>
        

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
       
        
        <?php free_delivery(); ?>
    </div>
    
    <!-- Hidden inputs -->
    <input type="hidden" name="quantity" value="1" />
    <input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>" />
    <input type="hidden" name="product_id" value="<?php echo absint($product->get_id()); ?>" />
    <input type="hidden" name="variation_id" class="variation_id" value="0" />
    <input type="hidden" name="product_image" class="product_image" value="<?php echo esc_url(get_the_post_thumbnail_url($product->get_id(), "thumbnail")); ?>" />
</div>

<!-- Availability Popup -->
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
                        <div id="availability-stores"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AJAX handler для проверки остатков -->
<script>
// AJAX endpoint для проверки остатков
jQuery(document).ready(function($) {
    // Добавляем nonce для безопасности
    window.availability_ajax = {
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        nonce: '<?php echo wp_create_nonce('check_availability_nonce'); ?>',
        product_id: <?php echo $product->get_id(); ?>
    };
});
</script>

<style>
/* Availability Link */

/* Показываем тултип не только на hover, но и при классе .visible */
.availability-link .tooltiptext.visible {
  opacity: 1 !important;
  visibility: visible !important;
}


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
}

.availability-link:hover {
    color: #000;
}


/* базовая ссылка */
.availability-link{
    position:relative;
    color:#333;
    text-decoration:underline;
    transition:color .3s;
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

/* тултип */
.availability-link .tooltiptext{
    position:absolute;
    top:100%; left:50%;
    transform:translate(-50%,8px);
    background:#333;
    color:#fff;
    padding:6px 10px;
    border-radius:6px;
    font-size:13px;
    line-height:1.2;
    white-space:nowrap;
    opacity:0; visibility:hidden;
    transition:opacity .25s;
    z-index:100;
    pointer-events:none;
}

/* показываем тултип только в неактивном состоянии */
.availability-link.is-disabled:hover .tooltiptext{
    opacity:1; visibility:visible;
}



/* Popup Base Styles */
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

.popup-header {
    padding: 20px 25px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    flex-shrink: 0;
}

.popup-header h3 {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
    color: #333;
    letter-spacing: 1px;
}

.popup-close {
    width: 40px;
    height: 40px;
    position:absolute;
right: 0;
    border: none;
    cursor: pointer;
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

.popup-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

/* Loading State */
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

/* Content Layout */
.availability-content {
    display: flex;
    height: 100%;
    flex: 1;
    gap:8px;
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
    padding: 25px;
    border-bottom: 1px solid #eee;
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
    min-width: 60px;
}

.attribute-value {
    color: #333;
}

.popup-sizes {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 5px;
}

/* Поправка: строгое кольцо */
.popup-size-item{
    width:32px;
    height:32px;
    line-height:32px;
    padding:0;
    
    display:flex;
    align-items:center;
    justify-content:center;

    border:1px solid #ddd;
    border-radius:50%;
    font-size:12px;
    font-weight:500;
    text-transform:uppercase;
    transition:all .3s;
}

.popup-size-item.selected{
    border-color:#333;
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
    gap: 15px;
}

.store-item {
     
    display: flex
;
    justify-content: space-between;
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
   color:#BABABA;
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
        max-height:100%;
    }
    
    .availability-content {
        flex-direction: column;
    }
    
    .map-section {
    
        display:none;
    }
    
    .info-section {
        width: 100%;
        flex: 1;
        padding-top:50px;
    }
    
    .popup-header h3 {
        font-size: 20px;
    }

.popup-close{
    font-size:50px;
}

}

@media (max-width: 480px) {
    .map-section {
        height: 250px;
    }
    
    .product-info,
    .stores-list {
        padding: 20px;
    }
    
    .popup-header {
        padding: 15px 20px;
    }
}

/* Body class for popup open state */
body.popup-open {
    overflow: hidden;
}

body.popup-open header,
body.popup-open .header {
    display:none;
    opacity: 0;
    transition: opacity 0.3s ease;
}


.availability-link{
    display: flex !important;
    gap: 10px !important;
    align-items: center !important;
}
</style>

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=YOUR_API_KEY"></script>
<script>
// Инициализация для товаров в наличии
jQuery(document).ready(() => {
    console.log('DOM ready, initializing AvailabilityChecker for in-stock...');
    
    if (!document.getElementById('check-availability')) {
        console.log('Availability link not found, skipping initialization');
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
    
    // Дополнительная проверка через 2 секунды
    setTimeout(() => {
        if (window.availabilityChecker) {
            console.log('Re-checking availability link after 2 seconds...');
            window.availabilityChecker.updateAvailabilityLink();
        }
    }, 2000);
});
</script>

<?php endif; ?>