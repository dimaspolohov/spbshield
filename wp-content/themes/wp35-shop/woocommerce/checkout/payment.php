<?php
/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 8.5.3
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );?>

<?php }
?>
    
<div id="payment" class="woocommerce-checkout-payment">
    <div class="woocommerce-before_payment">
        <p>Обработка и отправка заказа происходит в течение 3-х рабочих дней, не считая день оформления заказа. Трек-номер высылается на почту, указанную при оформлении заказа, как только начинается обработка заказа.</p>
    </div>
	<?php if ( WC()->cart->needs_payment() ) : ?>
		<div class="wc_payment_methods--title"><?=__( 'Оплата', 'woocommerce' )?>:</div>
		<ul class="wc_payment_methods payment_methods methods">
			<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
				}
			} else {
				echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
			}
			?>
		</ul>
	<?php endif; ?>
	
	<table class="woocommerce-checkout-total">
		<tbody>				
			<tr class="order-total">
				<td><?php 
				$quantity = 0; foreach ( WC()->cart->get_cart() as $cart_item ) $quantity += $cart_item['quantity']; 
				echo __( 'Итого', 'woocommerce' )  . ' ' . $quantity . ' ' . plural_form( $quantity, array('товар','товара','товаров') ) . ' ' . __( 'на сумму', 'woocommerce' ) . ':';			
				?></td>
				<td><?php wc_cart_totals_order_total_html(); ?></td>
			</tr>
		</tbody>
	</table>
	
	<div class="form-row place-order">
		<noscript>
			<?php
			/* translators: $1 and $2 opening and closing emphasis tags respectively */
			printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' );
			?>
			<br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
		</noscript>

		<?php wc_get_template( 'checkout/terms.php' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>
<div class="rs-product__buttons">
		<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="rs-btn  _background-btn _black-btn 0" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button><span class="tooltiptext">Выберите ПВЗ</span>' ); // @codingStandardsIgnoreLine ?>
</div>
        <div class="woocommerce-after_submit">
            <p>Подтверждая заказ, вы соглашаетесь с <a href="/clients/#privacy_policy" target="_blank">политикой конфиденциальности</a> и <a href="/clients/#terms_of_use" target="_blank" >договором-офертой</a></p>
        </div>
		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
	</div>
</div>
<?php
if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
