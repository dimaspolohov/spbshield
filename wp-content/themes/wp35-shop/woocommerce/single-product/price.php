<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $product;

if(!$product->is_in_stock()){
    // Получаем атрибуты товара для попапа
    $term_color = wc_get_product_terms($product->get_id(), 'pa_color', array('fields' => 'names'));
    $color = array_shift($term_color);
    
    // Получаем ВСЕ размеры товара (не только в наличии)
    $all_sizes = wc_get_product_terms($product->get_id(), 'pa_size', array('fields' => 'all'));
    ?>
    
    <!-- Скрытые элементы со всеми размерами для модального окна -->
    <div style="display: none;" class="hidden-sizes-for-modal">
        <?php 
        if (!empty($all_sizes) && !is_wp_error($all_sizes)) {
            foreach ($all_sizes as $size_term) {
                ?>
                <label style="display: inline-block; margin-right: 10px;">
                    <input type="radio" 
                           name="attribute_pa_size" 
                           value="<?php echo esc_attr($size_term->slug); ?>" 
                           data-size-name="<?php echo esc_attr($size_term->name); ?>" />
                    <span data-select="<?php echo esc_attr($size_term->name); ?>">
                        <?php echo esc_html($size_term->name); ?>
                    </span>
                </label>
                <?php
            }
        }
        ?>
    </div>
    
    <div class="not_in_stock">
        <div class="out-of-stock-text"><?php _e('Нет в наличии', 'storefront'); ?></div>
        
        <div class="availability-link-wrapper">
            <a href="#"
               id="check-availability-oos"
               class="availability-link is-disabled">
                <?php _e('<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M11.6014 8.84203C11.844 9.0361 11.8833 9.39008 11.6892 9.63266L8.53925 13.5702C8.43453 13.7011 8.2769 13.7784 8.10929 13.7812C7.94169 13.784 7.78158 13.7118 7.67261 13.5845L6.32261 12.0067C6.12064 11.7706 6.14827 11.4155 6.38432 11.2136C6.62037 11.0116 6.97545 11.0392 7.17741 11.2753L8.08545 12.3365L10.8108 8.92988C11.0048 8.68729 11.3588 8.64796 11.6014 8.84203Z" fill="black"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M2.017 5.9545C2.22798 5.74353 2.51413 5.625 2.8125 5.625H15.1875C15.4859 5.625 15.772 5.74353 15.983 5.9545C16.194 6.16548 16.3125 6.45163 16.3125 6.75V14.3877C16.3125 15.7778 15.1384 16.875 13.7812 16.875H4.21875C2.84496 16.875 1.6875 15.7175 1.6875 14.3438V6.75C1.6875 6.45163 1.80603 6.16548 2.017 5.9545ZM15.1875 6.75L2.8125 6.75L2.8125 14.3438C2.8125 15.0962 3.46629 15.75 4.21875 15.75H13.7812C14.5503 15.75 15.1875 15.1238 15.1875 14.3877V6.75Z" fill="black"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M9 2.25C8.25408 2.25 7.53871 2.54632 7.01126 3.07376C6.48382 3.60121 6.1875 4.31658 6.1875 5.0625V6.1875C6.1875 6.49816 5.93566 6.75 5.625 6.75C5.31434 6.75 5.0625 6.49816 5.0625 6.1875V5.0625C5.0625 4.01821 5.47734 3.01669 6.21577 2.27827C6.95419 1.53984 7.95571 1.125 9 1.125C10.0443 1.125 11.0458 1.53984 11.7842 2.27827C12.5227 3.01669 12.9375 4.01821 12.9375 5.0625V6.1875C12.9375 6.49816 12.6857 6.75 12.375 6.75C12.0643 6.75 11.8125 6.49816 11.8125 6.1875V5.0625C11.8125 4.31658 11.5162 3.60121 10.9887 3.07376C10.4613 2.54632 9.74592 2.25 9 2.25Z" fill="black"/>
</svg>
 <span id="availability-text-oos">Проверить наличие в других магазинах</span>', 'storefront'); ?>
            </a>
        </div>
    </div>


    <!-- Availability Popup для товаров не в наличии -->
    <div id="availability-popup-oos" class="availability-popup">
        <div class="popup-overlay"></div>
        <div class="popup-content">
            
            <div class="popup-body">

                <div class="popup-loading">
                    <div class="loading-spinner"></div>
                    <p><?php _e('Загружаем карту...', 'storefront'); ?></p>
                </div>
                
                <div class="availability-content">
                    <div class="map-section">
                        <div id="availability-map-oos"></div>
                    </div>
                    
                    <div class="info-section">
                        <span class="popup-close">&times;</span>

                        <div class="product-info">
                            <h3><?php _e('Наличие в других магазинах', 'storefront'); ?></h3>
                            <h2 class="product-title"></h2>
                            <div class="product-attributes"></div>
                        </div>
                        
                        <div class="stores-list">
                            <h4><?php _e('', 'storefront'); ?></h4>
                            <div class="store-item in-stock storage-available">
                                <div class="store-name-flex">
                                    <div class="store-name">В НАЛИЧИИ НА СКЛАДЕ</div>
                                    <div class="store-address">Привезем в офлайн магазин для самовывоза или отправим службой доставки в ваш город</div>
                                </div>
                            </div>
                            <div id="availability-stores-oos"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AJAX handler для проверки остатков (out of stock) -->
    <script>
    // AJAX endpoint для проверки остатков
    jQuery(document).ready(function($) {
        // Добавляем nonce для безопасности
        window.availability_ajax_oos = {
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            nonce: '<?php echo wp_create_nonce('check_availability_nonce'); ?>',
            product_id: <?php echo $product->get_id(); ?>
        };
    });
    </script>

    <style>
    /* Out of Stock Styles */
    .not_in_stock {
        text-align: center;
        padding: 20px;
        background: #f8f8f8;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .out-of-stock-text {
        font-size: 18px;
        font-weight: 600;
        color: #c62828;
        margin-bottom: 15px;
    }

    /* Availability Link */
    .availability-link-wrapper {
        text-align: center;
        margin-top: 15px;
    }

    .availability-link {
        color: #333;
        text-decoration: underline;
        font-size: 14px;
        cursor: pointer;
        transition: color 0.3s ease;
        display: flex !important;
        gap: 10px !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .availability-link:hover {
        color: #000;
    }

    .availability-link.is-disabled {
        color: #333;
        pointer-events: auto;
        text-decoration: underline;
        cursor: pointer;
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

    #availability-map-oos {
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

    #availability-stores-oos {
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

        .popup-close{
            font-size:50px;
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
    </style>

    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=YOUR_API_KEY"></script>
    <script>
    // Инициализация для товаров не в наличии
    jQuery(document).ready(() => {
        console.log('DOM ready, initializing AvailabilityChecker for OOS...');
        
        if (!document.getElementById('check-availability-oos')) {
            console.log('OOS Availability link not found, skipping initialization');
            return;
        }
        
        window.oosAvailabilityChecker = new AvailabilityChecker({
            popupId: 'availability-popup-oos',
            linkId: 'check-availability-oos',
            textId: 'availability-text-oos',
            storesContainerId: 'availability-stores-oos',
            mapContainerId: 'availability-map-oos',
            ajaxAction: 'get_gorokhovaya_csv_availability',
            isOutOfStock: true
        });
    });
    </script>

    <?php
} else {
    // Товар в наличии - показываем обычную цену
    if((int)$product->get_price()>0):
    ?>
    <div class="rs-product__info">
        <div class="rs-product__prices">
            <?php echo $product->get_price_html(); ?>
        </div>
        <?php
        global $product;
        if($product->is_type( 'variable' )){
            $regular_price = $product->get_variation_regular_price( 'min' );
            $sale_price = $product->get_variation_sale_price( 'min' );
        } else {
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
        }
        $discount = ($regular_price && $sale_price) ? ceil((($regular_price - $sale_price) * 100) / $regular_price) : '';
        $onsale = $product->is_on_sale();
        $discount = $discount == 100 ? '' : $discount;
        $discount = $discount ? '-' . $discount . '%' : '';?>
        <div class="rs-product__labels">
            <?php if($onsale ) :?>
            <div class="rs-product__label rs-product__label-sale"><?=$discount; ?></div>
            <?php endif; ?>
            <?php if(get_field('_new_product')=='yes') :?>
            <div class="rs-product__label rs-product__label-new">Новинка</div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    endif;
} ?>