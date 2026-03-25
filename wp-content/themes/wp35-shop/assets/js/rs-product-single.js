(function ($) {
    'use strict';

    // Variation price handler — updates displayed price, SKU and
    // description when user selects a product variation on the single page.
    var priceSelectors = {
        sales: 'body div.product-price .price-sales',
        standard: 'body div.product-price .price-standard',
        container: 'body div.product-price',
        sku: 'body h3.product-code span'
    };

    $('body').on('change', '.filterBox select, .filterBox [type="radio"]', function () {
        var variationData = $('.variations_form').data('product_variations');
        $('.variations_form').trigger('check_variations');
        $('body .woocommerce-product-details__short-description .woocommerce-variation-description').remove();
        var variationId = $('input.variation_id').val();

        function findVariation(variationId) {
            for (var i = 0, len = variationData.length; i < len; i++) {
                if (variationData[i].variation_id === parseInt(variationId)) {
                    var price = '';
                    if (variationData[i].display_price)
                        price += '<span class="price-sales">' + variationData[i].display_price.toLocaleString('ru-RU') + ' <i class="fas fa-ruble-sign"></i></span>';
                    if (variationData[i].display_regular_price)
                        price += '<span class="price-standard">' + variationData[i].display_regular_price.toLocaleString('ru-RU') + ' <i class="fas fa-ruble-sign"></i></span>';
                    return price;
                }
            }
            return null;
        }

        if ('' != $('input.variation_id').val()) {
            $(priceSelectors.sales).html($('body div.woocommerce-variation-price > span.price ins').html());
            $(priceSelectors.standard).html($('body div.woocommerce-variation-price > span.price del').html());
            $(priceSelectors.container).html(findVariation(variationId));
            $(priceSelectors.sku).html($('body div.woocommerce-variation-sku').html());
            $('body .woocommerce-variation.single_variation .woocommerce-variation-description').appendTo($('body .woocommerce-product-details__short-description'));
            $('body .woocommerce-variation.single_variation .woocommerce-variation-price').remove();
        } else if (typeof rsVariationData !== 'undefined') {
            $(priceSelectors.container).html(rsVariationData.priceHtml);
            $(priceSelectors.sku).html(rsVariationData.sku);
        }

        if ($('body .single_add_to_cart_button').hasClass('disabled')) {
            $('body .show-add-to-card').addClass('disabled');
        } else {
            $('body .show-add-to-card').removeClass('disabled');
        }
    });

    // Add-to-cart modal — intercepts the add-to-cart button click,
    // performs AJAX add-to-cart, and shows a confirmation modal with product info.
    $.fn.serializeArrayAll = function () {
        var rCRLF = /\r?\n/g;
        return this.map(function () {
            return this.elements ? $.makeArray(this.elements) : this;
        }).map(function (i, elem) {
            var val = $(this).val();
            if (val == null) {
                return val == null;
            } else if (this.type == "checkbox" && this.checked == false) {
                return {name: this.name, value: this.checked ? this.value : ''};
            } else {
                return $.isArray(val) ?
                    $.map(val, function (val, i) {
                        return {name: elem.name, value: val.replace(rCRLF, "\r\n")};
                    }) :
                    {name: elem.name, value: val.replace(rCRLF, "\r\n")};
            }
        }).get();
    };

    $(document).on('click', '.single_add_to_cart_button:not(.disabled)', function (e) {
        var $thisbutton = $(this),
            $form = $thisbutton.closest('form.cart'),
            quantity = $form.find('input[name=quantity]').val() || 1,
            product_id = $form.find('input[name=variation_id]').val() || $thisbutton.val(),
            data = $form.find('input:not([name="product_id"]), select, button, textarea').serializeArrayAll() || 0;

        $.each(data, function (i, item) {
            if (item.name == 'add-to-cart') {
                item.name = 'product_id';
                item.value = $form.find('input[name=variation_id]').val() || $thisbutton.val();
            }
        });

        e.preventDefault();
        $(document.body).trigger('adding_to_cart', [$thisbutton, data]);

        $.ajax({
            type: 'POST',
            url: woocommerce_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
            data: data,
            beforeSend: function (response) {
                $thisbutton.removeClass('added').addClass('loading');
            },
            complete: function (response) {
                $thisbutton.addClass('added').removeClass('loading');
                $('body').on('click', '.closeModalAddCart', function () {
                    $.unblockUI();
                    return false;
                });
            },
            success: function (response) {
                if (response.error & response.product_url) {
                    window.location = response.product_url;
                    return;
                }
                var thumbnail = rsProductSingleData.thumbnail;
                var product_title = rsProductSingleData.title;
                var tpl = '<div class="modalAddCart"><button type="button" class="button closeModalAddCart close" >&times;</button>';
                tpl += '<div class="section-title--text">Товар добавлен в корзину</div>';
                tpl += '<div class="product-info">';
                tpl += '<div id="checkout_thumbnail"><img src="' + thumbnail + '" alt="изображение товара ' + product_title + '"></div>';
                tpl += '<div class="product-text">';
                tpl += '<div class="product-title">' + product_title + '</div>';
                tpl += '<div class="product-quantity">' + quantity + ' шт.</div>';
                tpl += '</div>';
                tpl += '</div>';
                tpl += '<div style="clear: both;" class="btn-group">';
                tpl += '<a href="/checkout/" class="rs-btn _background-btn _black-btn">Оформить заказ</a>';
                tpl += '<button class="rs-btn _border-btn _black-border-btn closeModalAddCart">Продолжить покупки</button>';
                tpl += '</div></div>';

                $.blockUI({
                    onOverlayClick: $.unblockUI,
                    message: tpl,
                    focusInput: false,
                    css: {
                        width: '90%',
                        maxWidth: '400px',
                        border: 0,
                        padding: '15px',
                        top: "5%",
                        left: "50%",
                        cursor: 'default',
                        transform: "translateX(-50%)"
                    }
                });

                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
            }
        });

        return false;
    });

    // Mobile back button — prepends a "back to category" arrow
    // in the product gallery on mobile devices.
    if (typeof rsBackCategory !== 'undefined' && rsBackCategory.termLink) {
        $(window).on('load', function () {
            $('body .woocommerce-product-gallery').prepend(
                '<div class="backward"><a href="' + rsBackCategory.termLink + '">' +
                '<svg width="31px" height="31px" viewBox="0 0 31 31" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">' +
                '<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">' +
                '<g transform="translate(0.000000, -1.000000)">' +
                '<path id="Combined-Shape" d="M16.5771645,12 L31,12 L31,21 L17.363961,21 L21.2132034,24.8492424 L14.8492424, 31.2132034 L-2.84217094e-14, 16.363961 L0.257359313,16.1066017 L-2.84217094e-14, 15.8492424 L14.8492424,1 L21.2132034, 7.36396103 L16.5771645,12 Z" fill="#FFFFFF"></path>' +
                '<path id="Combined-Shape" d="M9.34314575,15 L28,15 L28,18 L10.3431458,18 L16.8284271,24.4852814 L14.7071068, 26.6066017 L6.10050506,18 L6,18 L6,17.8994949 L4.10050506,16 L6.22182541,13.8786797 L14.7071068,5.39339828 L16.8284271,7.51471863 L9.34314575,15 Z" fill="#222222"></path>' +
                '</g></g></svg></a></div>'
            );
        });
    }

})(jQuery);
