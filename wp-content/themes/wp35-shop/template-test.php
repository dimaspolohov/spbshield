<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Тест
 *
 * @package storefront
 */

use Cdek\Model\OrderMetaData;
use Cdek\Helper;
use Cdek\Model\Tariff;
use Cdek\MetaKeys;
use Cdek\Helpers\CheckoutHelper;
use Cdek\Config;
get_header(); ?>
<!-- rs-woo-style -->
<?php
$orderId = 135683;
$order         = wc_get_order($orderId);
$postOrderData = OrderMetaData::getMetaByOrderId($orderId);
echo '<br><br><br><br><br><br><br><br><pre>postOrderData before: <br>';
print_r($order->get_meta(Config::META_KEY));
echo '<br>';
print_r($postOrderData);
echo '</pre>';
echo '<pre>session: <br>';
print_r(WC()->session); //Config::DELIVERY_NAME."_office_code"
echo '</pre>';

$shippingMethod = CheckoutHelper::getOrderShippingMethod($order);
$tariffCode     = $shippingMethod->get_meta(MetaKeys::TARIFF_CODE) ?:
$shippingMethod->get_meta('tariff_code') ?: $postOrderData['tariff_id'];
$postOrderData  = [
	'currency'    => $order->get_currency() ?: 'RUB',
	'tariff_code' => $tariffCode,
	'type'        => Tariff::getTariffType($tariffCode),
	'office_code' => $shippingMethod->get_meta(MetaKeys::OFFICE_CODE) ?: $postOrderData['pvz_code'] ?: null,
];
echo '<pre>order: <br>';
print_r($order);
echo '</pre>';
echo '<pre>postOrderData after: <br>';
print_r($postOrderData);
echo '</pre>';
?>
<!-- /rs-full-width -->
<?php get_footer();