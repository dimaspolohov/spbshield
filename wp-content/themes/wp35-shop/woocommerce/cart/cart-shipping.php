<?php
/**
 * Shipping Methods Display
 *
 * In 2.1 we show methods per package. This allows for multiple methods per order if so desired.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 8.8.0
 */

defined( 'ABSPATH' ) || exit;

$formatted_destination    = isset( $formatted_destination ) ? $formatted_destination : WC()->countries->get_formatted_address( $package['destination'], ', ' );
$has_calculated_shipping  = ! empty( $has_calculated_shipping );
$show_shipping_calculator = ! empty( $show_shipping_calculator );
$calculator_text          = '';

?>
<tr class="woocommerce-shipping-totals shipping">
	<td colspan="2"><?php _e( 'Способ доставки', 'woocommerce' ); ?>:</td>
</tr>
<tr class="woocommerce-shipping-totals shipping">
	<td colspan="2">
		
        <? if (!empty($_POST['post_data'])) { parse_str($_POST['post_data'], $fields_values); } ?>
		<? if ( $available_methods ) :
		
			if(empty($fields_values["billing_city"]) || empty($fields_values["billing_address_1"])) { ?>
				<p class="shipping_no_methods">Введите адрес, чтобы увидеть способы доставки</p>
			<? } else { 
                // Сортируем методы доставки в нужном порядке: СДЭК, 5Пост, Почта России
                $sorted_methods = array();
                $cdek_methods = array();
                $fivepost_method = null;
                $russian_post_method = null;
                $other_methods = array();
                
                foreach ($available_methods as $method) {
                    $method_id = $method->get_id();
                    
                    // СДЭК методы
                    if (strpos($method_id, 'official_cdek') === 0) {
                        $cdek_methods[] = $method;
                    }
                    // 5Пост метод
                    elseif (strpos($method_id, 'fivepost_shipping_method') === 0) {
                        $fivepost_method = $method;
                    }
                    // Почта России (предполагаем что это flat_rate:1)
                    elseif ($method_id === 'flat_rate:1') {
                        $russian_post_method = $method;
                    }
                    // Остальные методы
                    else {
                        $other_methods[] = $method;
                    }
                }
                
                // Объединяем методы в нужном порядке
                $sorted_methods = array_merge($cdek_methods, array());
                if ($fivepost_method) {
                    $sorted_methods[] = $fivepost_method;
                }
                if ($russian_post_method) {
                    $sorted_methods[] = $russian_post_method;
                }
                $sorted_methods = array_merge($sorted_methods, $other_methods);
                ?>
				<ul id="shipping_method" class="woocommerce-shipping-methods">
				
					<?php foreach ( $sorted_methods as $method ) :
						if($method->get_id()=="local_pickup:7" && (mb_strtoupper($fields_values["billing_city"])!="САНКТ-ПЕТЕРБУРГ" && mb_strtoupper($fields_values["billing_city"])!="САНКТ ПЕТЕРБУРГ")) continue;
						?>
						<li>
							<?php
							if ( 1 < count( $sorted_methods ) ) {
								printf( '<input type="radio" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" %4$s />', $index, esc_attr( sanitize_title( $method->id ) ), esc_attr( $method->id ), checked( $method->id, $chosen_method, false ) ); // WPCS: XSS ok.
							} else {
								printf( '<input type="hidden" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" />', $index, esc_attr( sanitize_title( $method->id ) ), esc_attr( $method->id ) ); // WPCS: XSS ok.
							}
							
							// Проверяем, является ли метод доставки 5Post
							$is_fivepost = strpos($method->get_id(), 'fivepost_shipping_method') === 0;
							
							// Выводим обычный label для всех методов
							printf( '<label for="shipping_method_%1$s_%2$s">%3$s</label>', $index, esc_attr( sanitize_title( $method->id ) ), wc_cart_totals_shipping_method_label( $method ) );
							
							// Добавляем сообщение для 5Post только если ПВЗ еще ни разу не был выбран
							if ($is_fivepost) {
								// Проверяем, был ли уже выбран ПВЗ
								if (!empty($_POST['post_data'])) {
									parse_str($_POST['post_data'], $post_data);
									$pvz_already_selected = !empty($post_data['fivepost_point_id']);
								} else {
									$pvz_already_selected = false;
								}
								
								// Показываем надпись только если ПВЗ еще никогда не выбирался
								if (!$pvz_already_selected) {
									echo ' <span id="fivepost-notice-' . $index . '" class="fivepost-notice" style="color: #777; font-size: 12px;">цена будет доступна после выбора ПВЗ</span>';
								}
							}
							
							do_action( 'woocommerce_after_shipping_rate', $method, $index );
							?>
						</li>
					<?php endforeach; ?>
				</ul>
				
				<!-- JavaScript для управления показом/скрытием уведомления 5Post -->
				<script>
				jQuery(document).ready(function($) {
					// Обработчик для всех методов доставки
					$('input[name="shipping_method[<?php echo $index; ?>]"]').change(function() {
						var selectedValue = $(this).val();
						
						// Если выбран 5Post метод - скрываем уведомление
						if (selectedValue.indexOf('fivepost_shipping_method') !== -1) {
							$('#fivepost-notice-<?php echo $index; ?>').hide();
						} else {
							// Если выбран любой другой метод - показываем уведомление
							$('#fivepost-notice-<?php echo $index; ?>').show();
						}
					});
				});
				</script>
				
			<?php
			}
		elseif ( ! $has_calculated_shipping || ! $formatted_destination ) :
			if ( is_cart() && 'no' === get_option( 'woocommerce_enable_shipping_calc' ) ) {
				echo wp_kses_post( apply_filters( 'woocommerce_shipping_not_enabled_on_cart_html', __( 'Shipping costs are calculated during checkout.', 'woocommerce' ) ) );
			} else {
				echo wp_kses_post( apply_filters( 'woocommerce_shipping_may_be_available_html', __( 'Enter your address to view shipping options.', 'woocommerce' ) ) );
			}
		elseif ( ! is_cart() ) :
			echo wp_kses_post( apply_filters( 'woocommerce_no_shipping_available_html', __( 'There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'woocommerce' ) ) );
		else:
			echo wp_kses_post(
				/**
				 * Provides a means of overriding the default 'no shipping available' HTML string.
				 *
				 * @since 3.0.0
				 *
				 * @param string $html                  HTML message.
				 * @param string $formatted_destination The formatted shipping destination.
				 */
				apply_filters(
					'woocommerce_cart_no_shipping_available_html',
					// Translators: $s shipping destination.
					sprintf( esc_html__( 'No shipping options were found for %s.', 'woocommerce' ) . ' ', '<strong>' . esc_html( $formatted_destination ) . '</strong>' ),
					$formatted_destination
				)
			);
			$calculator_text = esc_html__( 'Enter a different address', 'woocommerce' );
		endif;
		?>

		<?php if ( $show_package_details ) : ?>
			<?php echo '<p class="woocommerce-shipping-contents"><small>' . esc_html( $package_details ) . '</small></p>'; ?>
		<?php endif; ?>

		<?php if ( $show_shipping_calculator ) : ?>
			<?php woocommerce_shipping_calculator( $calculator_text ); ?>
		<?php endif; ?>
	</td>
</tr>