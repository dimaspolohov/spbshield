<?php
/**
 * Thank You Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 8.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="rs-checkout container">
	<br/><br/><br/><br/>
<div class="woocommerce-order">

	<?php if ( $order ) : ?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>

		<?php else : ?>

			<h1 class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo esc_html( apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you for your order!', 'woocommerce' ), $order ) ); ?></h1>
			<p>
				<?php
				printf(
					wp_kses( __( 'You can check the order processing and shipping times <a href="%s">here</a>.', 'woocommerce' ), array( 'a' => array( 'href' => array() ) ) ),
					esc_url( 'https://www.spbshield.ru/clients/#dostavka' )
				);
				?>
			</p>
			<p>
				<?php
				printf(
					wp_kses( __( 'If you have any questions, please <a href="%s">contact us</a>.', 'woocommerce' ), array( 'a' => array( 'href' => array() ) ) ),
					esc_url( 'https://www.spbshield.ru/clients/#kontakti' )
				);
				?>
			</p>

			<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
			<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

		<?php endif; ?>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo esc_html( apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ) ); ?></p>

	<?php endif; ?>

</div>
</div>
