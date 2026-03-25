jQuery(document).ready(function () {
    changePVZ();
    jQuery(document).on('click', '#background', changePVZ);
    jQuery(document).on('click', '.cdek-map .cursor-pinter', changePVZ);
    jQuery(document).on('click', '.cdek-map a', changePVZ);

    // When place_order is disabled — prevent submit, scroll to first unfilled field
    jQuery(document).on('click', '#place_order', function (e) {
        if (!jQuery(this).hasClass('disabled')) return;
        e.preventDefault();
        e.stopImmediatePropagation();
        var scrollToId = jQuery(this).attr('data-scroll');
        if (!scrollToId) return;
        var $target = jQuery('#' + scrollToId);
        if (!$target.length) return;
        jQuery('html, body').animate({scrollTop: $target.offset().top - 120}, 400, function () {
            var $input = $target.is('input, select, textarea')
                ? $target
                : $target.find('input:visible, select:visible, textarea:visible').first();
            if ($input.length) $input.focus();
        });
    });

    jQuery(document).ajaxComplete(function () {
        changePVZ();
    });
});

function changePVZ() {
    jQuery('body #place_order').addClass('disabled').attr('data-scroll', '');
    var key = 0,
        infoText = 'Заполните данные',
        elem = document.querySelector('.cdek-office-code'),
        pvz_btn = document.querySelector('.open-pvz-btn'),
        info = document.querySelector('.rs-product__buttons .tooltiptext'),
        shipping_method = document.querySelector('#shipping_method input:checked'),
        billing_city = document.querySelector('#billing_city'),
        billing_state = document.querySelector('#billing_state'),
        billing_address = document.querySelector('#billing_address_1');

    if (!billing_state.value) { key++; infoText = key == 1 ? 'Введите область/район' : infoText; jQuery('body #place_order').attr('data-scroll', 'billing_state'); }
    else if (!billing_city.value) { key++; infoText = key == 1 ? 'Введите город' : infoText; jQuery('body #place_order').attr('data-scroll', 'billing_city'); }
    else if (!billing_address.value) { key++; infoText = key == 1 ? 'Введите адрес' : infoText; jQuery('body #place_order').attr('data-scroll', 'billing_address_1'); }
    else if (!shipping_method) { key++; infoText = key == 1 ? 'Выберите способ доставки' : infoText; jQuery('body #place_order').attr('data-scroll', 'order_review'); }

    if (!key) {
        if (pvz_btn) {
            if (!elem.value) {
                infoText = 'Выберите ПВЗ';
                jQuery('body #place_order').attr('data-scroll', 'order_review');
            } else {
                jQuery('body #place_order').removeClass('disabled').attr('data-scroll', '');
                infoText = '';
            }
        } else {
            infoText = '';
            jQuery('body #place_order').removeClass('disabled').attr('data-scroll', '');
        }
    }
    info.innerHTML = infoText;
}
