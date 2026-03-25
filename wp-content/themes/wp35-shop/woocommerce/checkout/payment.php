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
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
?>

<div id="payment" class="woocommerce-checkout-payment">
    <div class="woocommerce-before_payment">
        <p><?php esc_html_e( 'Order processing and shipping takes up to 3 business days, excluding the order date. The tracking number will be sent to the email provided at checkout as soon as order processing begins.', 'woocommerce' ); ?></p>
    </div>
	<?php if ( WC()->cart && WC()->cart->needs_payment() ) : ?>
		<div class="wc_payment_methods--title"><?php echo esc_html__( 'Payment', 'woocommerce' ); ?>:</div>
		<ul class="wc_payment_methods payment_methods methods">
			<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
				}
			} else {
				echo '<li>';
				wc_print_notice( apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ), 'notice' );
				echo '</li>';
			}
			?>
		</ul>
	<?php endif; ?>

	<table class="woocommerce-checkout-total">
		<tbody>
			<tr class="order-total">
				<td><?php
				$quantity = 0;
				foreach ( WC()->cart->get_cart() as $cart_item ) {
					$quantity += $cart_item['quantity'];
				}
				echo esc_html(
					sprintf(
						/* translators: 1: item count, 2: pluralized "item/items" */
						__( 'Total %1$d %2$s for the amount:', 'woocommerce' ),
						$quantity,
						_n( 'item', 'items', $quantity, 'woocommerce' )
					)
				);
				?></td>
				<td><?php wc_cart_totals_order_total_html(); ?></td>
			</tr>
		</tbody>
	</table>

	<div class="form-row place-order">
		<noscript>
			<?php
			printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' );
			?>
			<br/><button type="submit" class="button alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
		</noscript>

		<?php wc_get_template( 'checkout/terms.php' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>
<div class="rs-product__buttons">
		<?php echo wp_kses_post( apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="rs-btn  _background-btn _black-btn 0" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button><span class="tooltiptext">' . esc_html__( 'Select a pickup point', 'woocommerce' ) . '</span>' ) ); ?>
</div>
        <div class="woocommerce-after_submit">
            <p><?php
			printf(
				wp_kses(
					__( 'By confirming your order, you agree to the <a href="%1$s" target="_blank">privacy policy</a> and the <a href="%2$s" target="_blank">terms of service</a>', 'woocommerce' ),
					array( 'a' => array( 'href' => array(), 'target' => array() ) )
				),
				esc_url( '/clients/#privacy_policy' ),
				esc_url( '/clients/#terms_of_use' )
			);
			?></p>
        </div>
		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
	</div>
</div>
<?php
if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
