<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.2.0
 */

defined( 'ABSPATH' ) || exit;

// Обработка ошибок восстановления пароля
$custom_reset_error = '';
if ( isset( $_POST['wc_reset_password'] ) && ! empty( $_POST['user_login'] ) ) {
    // Проверяем nonce для безопасности
    if ( wp_verify_nonce( $_POST['woocommerce-lost-password-nonce'], 'lost_password' ) ) {
        $user_login = sanitize_text_field( $_POST['user_login'] );
        
        // Проверяем существует ли пользователь по логину или email
        $user = get_user_by( 'login', $user_login );
        if ( ! $user && is_email( $user_login ) ) {
            $user = get_user_by( 'email', $user_login );
        }
        
        if ( ! $user ) {
            $custom_reset_error = 'Пользователь с таким email не найден';
        }
    }
}

do_action( 'woocommerce_before_lost_password_form' );
?>

<div class="u-columns col2-set" id="customer_login">
	<div class="u-column1 col-1">
		<h2><?php esc_html_e( 'Lost password', 'woocommerce' ); ?></h2>

		<?php if ( ! empty( $custom_reset_error ) ) : ?>
			<div class="woocommerce-error" style="color: #721c24; padding: 12px; margin-bottom: 20px; background-color: white !important;">
				<?php echo esc_html( $custom_reset_error ); ?>
			</div>
		<?php endif; ?>

		<form method="post" class="woocommerce-form woocommerce-ResetPassword lost_reset_password">

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide"><?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'woocommerce' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<input placeholder="<?php esc_html_e( 'Username or email', 'woocommerce' ); ?>" class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="username" value="<?php echo ( ! empty( $_POST['user_login'] ) ) ? esc_attr( wp_unslash( $_POST['user_login'] ) ) : ''; ?>" />
			</p>

			<div class="clear"></div>

			<?php do_action( 'woocommerce_lostpassword_form' ); ?>

			<p class="woocommerce-form-row form-row">
				<input type="hidden" name="wc_reset_password" value="true" />
				<button type="submit" class="rs-btn _border-btn _black-btn" value="<?php esc_attr_e( 'Reset password', 'woocommerce' ); ?>"><?php esc_html_e( 'Reset password', 'woocommerce' ); ?></button>
			</p>

			<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

		</form>
	</div>
</div>
<?php
do_action( 'woocommerce_after_lost_password_form' );