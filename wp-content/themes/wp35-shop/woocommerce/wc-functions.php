<?php

add_filter( 'woocommerce_default_address_fields' , 'rs_rename_state_province', 9999 );
function rs_rename_state_province( $fields ) {
    $fields['first_name']['label'] = __('Ваше имя','storefront');
    $fields['last_name']['label'] = __('Ваша Фамилия','storefront');
    $fields['email']['label'] = __('E-mail','storefront');
    $fields['address_1']['label'] = __('Адрес (название улицы и номер дома)','storefront');
    return $fields;
}

/**
 * Outputs a checkout/address form field.
 *
 * @param string $key Key.
 * @param mixed  $args Arguments.
 * @param string $value (default: null).
 * @return string
 */
function woocommerce_form_field( $key, $args, $value = null ) {
	$defaults = array(
		'type'              => 'text',
		'label'             => '',
		'description'       => '',
		'placeholder'       => '',
		'maxlength'         => false,
		'required'          => false,
		'autocomplete'      => false,
		'id'                => $key,
		'class'             => array(),
		'label_class'       => array(),
		'input_class'       => array(),
		'return'            => false,
		'options'           => array(),
		'custom_attributes' => array(),
		'validate'          => array(),
		'default'           => '',
		'autofocus'         => '',
		'priority'          => '',
	);

	$args = wp_parse_args( $args, $defaults );
	$args = apply_filters( 'woocommerce_form_field_args', $args, $key, $value );

	if ( is_string( $args['class'] ) ) {
		$args['class'] = array( $args['class'] );
	}
//var_dump($key);
	if ( $args['required'] || $key=='billing_address_1') {
		$args['class'][] = 'validate-required';
		$required        = '&nbsp;<abbr class="required" title="' . esc_attr__( 'required', 'woocommerce' ) . '">*</abbr>';
	} else {
		$required = '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'woocommerce' ) . ')</span>';
	}

	if ( is_string( $args['label_class'] ) ) {
		$args['label_class'] = array( $args['label_class'] );
	}

	if ( is_null( $value ) ) {
		$value = $args['default'];
	}

	// Custom attribute handling.
	$custom_attributes         = array();
	$args['custom_attributes'] = array_filter( (array) $args['custom_attributes'], 'strlen' );

	if ( $args['maxlength'] ) {
		$args['custom_attributes']['maxlength'] = absint( $args['maxlength'] );
	}

	if ( ! empty( $args['autocomplete'] ) ) {
		$args['custom_attributes']['autocomplete'] = $args['autocomplete'];
	}

	if ( true === $args['autofocus'] ) {
		$args['custom_attributes']['autofocus'] = 'autofocus';
	}

	if ( $args['description'] ) {
		$args['custom_attributes']['aria-describedby'] = $args['id'] . '-description';
	}

	if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
		foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
			$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
		}
	}

	if ( ! empty( $args['validate'] ) ) {
		foreach ( $args['validate'] as $validate ) {
			$args['class'][] = 'validate-' . $validate;
		}
	}

	$field           = '';
	$label_id        = $args['id'];
	$sort            = $args['priority'] ? $args['priority'] : '';
	$field_container = '<p class="form-row %1$s " id="%2$s" data-priority="' . esc_attr( $sort ) . '">%3$s</p>';

	switch ( $args['type'] ) {
		case 'country':
			$countries = 'shipping_country' === $key ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();
 //var_dump(WC()->countries->get_allowed_countries());
			if ( 1 === count( $countries ) ) {

				$field .= '<strong>' . current( array_values( $countries ) ) . '</strong>';

				$field .= '<input type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . current( array_keys( $countries ) ) . '" ' . implode( ' ', $custom_attributes ) . ' class="country_to_state" readonly="readonly" />';

			} else {
				$data_label = ! empty( $args['label'] ) ? 'data-label="' . esc_attr( $args['label'] ) . '"' : '';

				$field = '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="country_to_state country_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ? $args['placeholder'] : esc_attr__( 'Select a country / region&hellip;', 'woocommerce' ) ) . '" ' . $data_label . '><option value="">' . esc_html__( 'Select a country / region&hellip;', 'woocommerce' ) . '</option>';

				foreach ( $countries as $ckey => $cvalue ) {
				  //  var_dump($value, $ckey);
                    if (esc_attr( $ckey )!="BY" && esc_attr( $ckey )!="RU" && esc_attr( $ckey )!="KZ") continue;
					$field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
				}

				$field .= '</select>';

				$field .= '<noscript><button type="submit" name="woocommerce_checkout_update_totals" value="' . esc_attr__( 'Update country / region', 'woocommerce' ) . '">' . esc_html__( 'Update country / region', 'woocommerce' ) . '</button></noscript>';

			}

			break;
		case 'state':
			/* Get country this state field is representing */
			$for_country = isset( $args['country'] ) ? $args['country'] : WC()->checkout->get_value( 'billing_state' === $key ? 'billing_country' : 'shipping_country' );

			$states      = WC()->countries->get_states( $for_country );
           // var_dump($states);

			if ( is_array( $states ) && empty( $states ) ) {

				$field_container = '<p class="form-row %1$s" id="%2$s" style="display: none">%3$s</p>';

				$field .= '<input type="hidden" class="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="" ' . implode( ' ', $custom_attributes ) . ' placeholder="' . esc_attr( $args['placeholder'] ) . '" readonly="readonly" data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '"/>';

			} elseif ( ! is_null( $for_country ) && is_array( $states ) ) {
				$data_label = ! empty( $args['label'] ) ? 'data-label="' . esc_attr( $args['label'] ) . '"' : '';

				$field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="state_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ? $args['placeholder'] : esc_html__( 'Select an option&hellip;', 'woocommerce' ) ) . '"  data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . $data_label . '>
					<option value="">' . esc_html__( 'Select an option&hellip;', 'woocommerce' ) . '</option>';

				foreach ( $states as $ckey => $cvalue ) {
					$field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
				}

				$field .= '</select>';

			} else {

				$field .= '<input type="text" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $value ) . '"  placeholder="' . esc_attr( $args['label'] ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" ' . implode( ' ', $custom_attributes ) . ' data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '"/>';

			}

			break;
		case 'textarea':
			$field .= '<textarea name="' . esc_attr( $key ) . '" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . ( empty( $args['custom_attributes']['rows'] ) ? ' rows="2"' : '' ) . ( empty( $args['custom_attributes']['cols'] ) ? ' cols="5"' : '' ) . implode( ' ', $custom_attributes ) . '>' . esc_textarea( $value ) . '</textarea>';

			break;
		case 'checkbox':
			$field = '<label class="checkbox ' . implode( ' ', $args['label_class'] ) . '" ' . implode( ' ', $custom_attributes ) . '>
					<input type="' . esc_attr( $args['type'] ) . '" class="input-checkbox ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="1" ' . checked( $value, 1, false ) . ' /> ' . $args['label'] . $required . '</label>';

			break;
		case 'text':
		case 'password':
		case 'datetime':
		case 'datetime-local':
		case 'date':
		case 'month':
		case 'time':
		case 'week':
		case 'number':
		case 'email':
		case 'url':
		case 'tel':
			if (esc_attr( $key ) === 'billing_city') {
				$field .= '<span style="width:100%;display:block;padding-left: 11px; margin-bottom: 6px;">Сначала введите "Область/район"</span>';
			}
			$field .= '<input type="' . esc_attr( $args['type'] ) . '" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['label'] ) . '"  value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';
			break;
		case 'hidden':
			$field .= '<input type="' . esc_attr( $args['type'] ) . '" class="input-hidden ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

			break;
		case 'select':
			$field   = '';
			$options = '';

			if ( ! empty( $args['options'] ) ) {
				foreach ( $args['options'] as $option_key => $option_text ) {
					if ( '' === $option_key ) {
						// If we have a blank option, select2 needs a placeholder.
						if ( empty( $args['placeholder'] ) ) {
							$args['placeholder'] = $option_text ? $option_text : __( 'Choose an option', 'woocommerce' );
						}
						$custom_attributes[] = 'data-allow_clear="true"';
					}
					$options .= '<option value="' . esc_attr( $option_key ) . '" ' . selected( $value, $option_key, false ) . '>' . esc_html( $option_text ) . '</option>';
				}

				$field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ) . '">
						' . $options . '
					</select>';
			}

			break;
		case 'radio':
			$label_id .= '_' . current( array_keys( $args['options'] ) );

			if ( ! empty( $args['options'] ) ) {
				foreach ( $args['options'] as $option_key => $option_text ) {
					$field .= '<input type="radio" class="input-radio ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $option_key ) . '" name="' . esc_attr( $key ) . '" ' . implode( ' ', $custom_attributes ) . ' id="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '"' . checked( $value, $option_key, false ) . ' />';
					$field .= '<label for="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '" class="radio ' . implode( ' ', $args['label_class'] ) . '">' . esc_html( $option_text ) . '</label>';
				}
			}

			break;
	}

	if ( ! empty( $field ) ) {
		$field_html = '';

		// if ( $args['label'] && 'checkbox' !== $args['type'] ) {
			// $field_html .= '<label for="' . esc_attr( $label_id ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) . '">' . wp_kses_post( $args['label'] ) . $required . '</label>';
		// }

		$field_html .= '<span class="woocommerce-input-wrapper">' . $field;

		if ( $args['description'] ) {
			$field_html .= '<span class="description" id="' . esc_attr( $args['id'] ) . '-description" aria-hidden="true">' . wp_kses_post( $args['description'] ) . '</span>';
		}

		$field_html .= '</span>';
		$container_class = esc_attr( implode( ' ', $args['class'] ) );
		$container_class = str_replace( 'form-row-first', '', $container_class );
		$container_class = str_replace( 'form-row-last', '', $container_class );
		$container_class = str_replace( '  ', ' ', $container_class );
		$container_id    = esc_attr( $args['id'] ) . '_field';
		$field           = sprintf( $field_container, $container_class, $container_id, $field_html );
	}

	/**
	 * Filter by type.
	 */
	$field = apply_filters( 'woocommerce_form_field_' . $args['type'], $field, $key, $args, $value );

	/**
	 * General filter on form fields.
	 *
	 * @since 3.4.0
	 */
	$field = apply_filters( 'woocommerce_form_field', $field, $key, $args, $value );

	if ( $args['return'] ) {
		return $field;
	} else {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $field;
	}
}

function modify_wc_hooks() {
	remove_action( 'woocommerce_cart_is_empty', 'woocommerce_output_all_notices', 5 );
	remove_action( 'woocommerce_shortcode_before_product_cat_loop', 'woocommerce_output_all_notices', 10 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
	remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices', 10 );
	remove_action( 'woocommerce_before_cart', 'woocommerce_output_all_notices', 10 );
	remove_action( 'woocommerce_before_checkout_form_cart_notices', 'woocommerce_output_all_notices', 10 );
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10 );
	remove_action( 'woocommerce_account_content', 'woocommerce_output_all_notices', 5 );
	remove_action( 'woocommerce_before_customer_login_form', 'woocommerce_output_all_notices', 10 );
	remove_action( 'woocommerce_before_lost_password_form', 'woocommerce_output_all_notices', 10 );
	remove_action( 'before_woocommerce_pay', 'woocommerce_output_all_notices', 10 );
	remove_action( 'woocommerce_before_reset_password_form', 'woocommerce_output_all_notices', 10 );
}
add_action( 'init', 'modify_wc_hooks', 99 );

function plural_form($number, $after) {
	$cases = array (2, 0, 1, 1, 1, 2);
	return $after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
}

/**
 * Gets and formats a list of cart item data + variations for display on the frontend.
 *
 * @since 3.3.0
 * @param array $cart_item Cart item object.
 * @param bool  $flat Should the data be returned flat or in a list.
 * @return string
 */
function wc_get_formatted_cart_item_data__alt( $cart_item, $flat = false ) {
	$item_data = array();

	// Variation values are shown only if they are not found in the title as of 3.0.
	// This is because variation titles display the attributes.
	if ( $cart_item['data']->is_type( 'variation' ) && is_array( $cart_item['variation'] ) ) {
		foreach ( $cart_item['variation'] as $name => $value ) {
			// print_r($value);
			$taxonomy = wc_attribute_taxonomy_name( str_replace( 'attribute_pa_', '', urldecode( $name ) ) );

			if ( taxonomy_exists( $taxonomy ) ) {
				// If this is a term slug, get the term's nice name.
				$term = get_term_by( 'slug', $value, $taxonomy );
				if ( ! is_wp_error( $term ) && $term && $term->name ) {
					$value = $term->name;
				}
				$label = wc_attribute_label( $taxonomy );
			} else {
				// If this is a custom option slug, get the options name.
				$value = apply_filters( 'woocommerce_variation_option_name', $value, null, $taxonomy, $cart_item['data'] );
				$label = wc_attribute_label( str_replace( 'attribute_', '', $name ), $cart_item['data'] );
			}

			// Check the nicename against the title.
			if ( '' === $value || wc_is_attribute_in_product_name( $value, $cart_item['data']->get_name() ) ) {
				continue;
			}

			$item_data[] = array(
				'key'   => $label,
				'value' => $value,
			);
		}
	} else {
		foreach ( $cart_item['data']->get_attributes() as $name => $options ) {
			$taxonomy = wc_attribute_taxonomy_name( str_replace( 'pa_', '', $name ) );
			// print_r($options['data']['options']);
			foreach($options['data']['options'] as $value) {
				if ( taxonomy_exists( $taxonomy ) ) {
					// If this is a term slug, get the term's nice name.
					$term = get_term_by( 'term_id', $value, $taxonomy );
					if ( ! is_wp_error( $term ) && $term && $term->name ) {
						$value = $term->name;
					}
					$label = wc_attribute_label( $taxonomy );
				} else {
					// If this is a custom option slug, get the options name.
					$value = apply_filters( 'woocommerce_variation_option_name', $value, null, $taxonomy, $cart_item['data'] );
					$label = wc_attribute_label( str_replace( 'attribute_', '', $name ), $cart_item['data'] );
				}
				if ( '' === $value || wc_is_attribute_in_product_name( $value, $cart_item['data']->get_name() ) ) {
					continue;
				}

				$item_data[] = array(
					'key'   => $label,
					'value' => $value,
				);	
			}

			// Check the nicename against the title.		
		}
	}

	// Filter item data to allow 3rd parties to add more to the array.
	$item_data = apply_filters( 'woocommerce_get_item_data', $item_data, $cart_item );

	// Format item data ready to display.
	foreach ( $item_data as $key => $data ) {
		// Set hidden to true to not display meta on cart.
		if ( ! empty( $data['hidden'] ) ) {
			unset( $item_data[ $key ] );
			continue;
		}
		$item_data[ $key ]['key']     = ! empty( $data['key'] ) ? $data['key'] : $data['name'];
		$item_data[ $key ]['display'] = ! empty( $data['display'] ) ? $data['display'] : $data['value'];
	}

	// Output flat or in list format.
	if ( count( $item_data ) > 0 ) {
		ob_start();
		
		foreach ( $item_data as $data ) {
			echo '<div class="cart-description--options">' . esc_html( $data['key'] ) . ': ' . wp_kses_post( $data['display'] ) . '</div>';
		}

		return ob_get_clean();
	}

	return '';
}

// Дополнительные типы полей
require 'rs-woo-custom-fields.php';

// Функционал Каталога
require 'wc-functions-arhive.php';

// Функционал Карточки товара
require 'wc-functions-single.php';

// Виджет Каталог товаров
require 'widgets/rs-wc-widget-product-categories.php';

// Виджет Фильтр по цене
require 'widgets/rs-wc-widget-price-filter.php';

// Виджет Фильтр по атрибутам
require 'widgets/rs-wc-widget-layered-nav.php';

// Виджет Фильтр по распродаже
require 'widgets/rs-wc-widget-onsale-filter.php';

// Виджет Кнопка Сбросить все фильтры
require 'widgets/rs-wc-widget-reset-button.php';

// add_action( 'wp_enqueue_scripts', 'rs_wc_addition_style', 11 );
function rs_wc_addition_style() {
    if(is_woocommerce()){
      //  wp_enqueue_style( 'rs-top-header', get_stylesheet_directory_uri().'/woocommerce/css/rs-top-header.css');
    }
    wp_enqueue_style( 'rs-woo-addition', WP_CONTENT_URL . '/themes/storefront/assets/css/woocommerce/woocommerce.css');
    wp_enqueue_style( 'rs-awooc-addition', WP_PLUGIN_URL . '/art-woocommerce-order-one-click/assets/css/awooc-styles.min.css');
    if(is_shop() || is_product_category() || is_tax()){
        wp_enqueue_style( 'rs-catalog', get_stylesheet_directory_uri().'/woocommerce/css/rs-catalog.css');
        wp_enqueue_style( 'rs-single-product', get_stylesheet_directory_uri().'/woocommerce/css/rs-product-view.css');
    }
    if(is_product()){
        // wp_enqueue_style( 'rs-single-product', get_stylesheet_directory_uri().'/woocommerce/css/rs-product.css');
    }
    if( is_cart() || is_checkout()) {
        wp_enqueue_style( 'rs-cart', get_stylesheet_directory_uri().'/woocommerce/css/rs-cart.css');
        wp_enqueue_style( 'intlTelInput', 'https://cdn.jsdelivr.net/npm/intl-tel-input@25.2.1/build/css/intlTelInput.css');
        wp_enqueue_script('intlTelInput','https://cdn.jsdelivr.net/npm/intl-tel-input@25.2.1/build/js/intlTelInput.min.js',[],'');
    }
	if (rs_is_cart_off()) {
		wp_enqueue_style( 'rs-cart-off', get_stylesheet_directory_uri().'/woocommerce/css/rs-cart-off.css');
	}
    if (!(wc_get_product() && wc_get_product()->is_type('bundle'))) {
        // wp_deregister_script( 'wc-add-to-cart-variation' );
        // wp_register_script( 'wc-add-to-cart-variation', get_stylesheet_directory_uri() . '/assets/js/add-to-cart-variation.js', array( 'jquery', 'wp-util' ));
    }
}

// Кастомизация заголовков виджетов
function rs_change_widget_title($title, $instance, $wid) { 
	if ($wid == 'rs_woocommerce_product_categories') {
		$title = '<span class="panel-heading"><span class="panel-title"><a data-toggle="collapse" href="#collapseCategory"><i class="fa fa-caret-right"></i>' . $title .
			'</a></span></span>';		
	} else if ($wid == 'rs_woocommerce_price_filter') {
		$title = '<span class="panel-heading"><span class="panel-title"><a data-toggle="collapse" href="#collapsePrice"><i class="fa fa-caret-right"></i>' . $title .
			'</a></span></span>';				
	} else if ($wid == 'rs_woocommerce_layered_nav') {
		$title_translit = rs_string_translit($title);
		$title = '<span class="panel-heading"><span class="panel-title"><a data-toggle="collapse" href="#collapse_' . $title_translit. '"><i class="fa fa-caret-right"></i>' . $title .
			'</a></span></span>';					
	} else if ($wid == 'rs_woocommerce_onsale_filter') {
		$title = '<span class="panel-heading"><span class="panel-title"><a data-toggle="collapse" href="#collapseOnsale"><i class="fa fa-caret-right"></i>' . $title .
			'</a></span></span>';			
	} else if ($wid == 'rs_woocommerce_reset_button') {
        $title = '';
    }

	return $title;
}
add_filter('widget_title', 'rs_change_widget_title', 10, 3);

/*Отключение блока оплты*/
//add_filter( 'woocommerce_cart_needs_payment', '__return_false' );

// Редактирование кастомайзера
add_action( 'customize_register', 'my_theme_customize_register', 11 );
function my_theme_customize_register($wp_customize) {
   $wp_customize->remove_section('storefront_footer');
   $wp_customize->remove_control('woocommerce_catalog_columns');
}; 

// Отключение корзины
function rs_is_cart_off() {
	$query = new WP_Query( array (
		'post_type' => 'custom_block',
		'meta_query' => array ( 
			'relation' => 'OR', 
			array (
				'key'     => 'block_id',
				'value'   => 32, // id блока
				'compare' => '=' 
			)
		)
	));	
	while ( $query->have_posts() ) {
		$query->the_post();
	}
	$result = get_field("cart_on") ?: '';
	wp_reset_query();	
	return $result;
}

// Что выводить сумму или количество у мини-корзины
function rs_is_cart_count() {
    $query = new WP_Query( array (
        'post_type' => 'custom_block',
        'meta_query' => array (
            'relation' => 'OR',
            array (
                'key'     => 'block_id',
                'value'   => 32, // id блока
                'compare' => '='
            )
        )
    ));
    while ( $query->have_posts() ) {
        $query->the_post();
    }
    $result = get_field("check_params");
    wp_reset_query();
    return $result;
}

// Отключение хуков главной и корзины 
function delete_homepage() {
	remove_action( 'storefront_page', 'storefront_page_header', 10 );
	remove_action('homepage', 'storefront_homepage_content', 10);
	remove_action('homepage', 'storefront_product_categories', 20);
	remove_action('homepage', 'storefront_recent_products', 30);
	remove_action('homepage', 'storefront_featured_products', 40);
	remove_action('homepage', 'storefront_popular_products', 50);
	remove_action('homepage', 'storefront_on_sale_products', 60);
	remove_action('homepage', 'storefront_best_selling_products', 70);
};
add_action( 'init', 'delete_homepage', 1);

// Добавить новые хуки для главной 
function add_homepage() {
	//add_action('storefront_page', 'storefront_page_header_child', 10);
	// блок template-parts/rs-slider
	add_action('homepage', 'storefront_slider_child', 5);
	// блок template-parts/rs-text-blocks
	//add_action('homepage', 'storefront_homepage_content_child', 10);
	// блок template-parts/rs-services
	add_action('homepage', 'storefront_rs_services', 30);	
	// блок template-parts/rs-popular
	add_action('homepage', 'storefront_popular_products_child', 50);
	// блок template-parts/rs-onsale
	add_action('homepage', 'storefront_onsale_products_child', 60);			
	// блок template-parts/rs-new-products
	add_action('homepage', 'storefront_best_selling_products_child', 70);
	// блок template-parts/rs-best-sellers
	add_action('homepage', 'storefront_recent_products_child', 80);
}
add_action( 'init', 'add_homepage', 2);

add_action( 'init', 'rs_wc_mobile_menu', 3);
function rs_wc_mobile_menu(){
    global $del_link,$add_link;
    if(!is_admin()):
//Кастомизация ссылок мобильного меню
    $on_mobile_menu= get_field("on_mobile_menu",1910);
    if(!$on_mobile_menu){
        //Отключение всего блока
        add_action( 'init', 'rs_remove_storefront_handheld_footer_bar' );
        function rs_remove_storefront_handheld_footer_bar() {
            remove_action( 'storefront_footer', 'storefront_handheld_footer_bar', 999 );
        }
    } else {

        $field_del_link=get_field_object("del_link",1910);
        $del_link = $field_del_link['value'];
        $link_home = get_field("link_home",1910);
        if( $del_link ):  ?>
            <?php
            //Отключение ссылок
            add_filter ('storefront_handheld_footer_bar_links', 'rs_remove_handheld_footer_links');
            function rs_remove_handheld_footer_links ($links) {
                global $del_link;
                foreach( $del_link  as $link ): ?>
                    <?php unset( $links[$link] );?>
                <?php endforeach;
                return $links;
             } ?>
        <?php endif;
        //Добавление ссылки на главную страницу
        if( $link_home ):  ?>
            <?php
            add_filter( 'storefront_handheld_footer_bar_links', 'rs_add_home_link' );
            function rs_add_home_link( $links ) {
                $new_links = array(
                    'home' => array(
                        'priority' => 10,
                        'callback' => 'rs_home_link',
                    ),
                );
                $links = array_merge( $new_links, $links );
                return $links;
            }
            function rs_home_link() {
                echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . __( 'Home' ) . '</a>';
            }
            ?>
        <?php  endif;
    }
    endif;
}

add_filter( 'woocommerce_product_variation_title_include_attributes', '__return_false' );
add_filter( 'woocommerce_is_attribute_in_product_name', '__return_false' );

function sort_fields_billing($fields) {

    $fields["billing"]["billing_first_name"]["priority"] = 1;
    $fields["billing"]["billing_last_name"]["priority"] = 2;
    $fields["billing"]["billing_email"]["priority"] = 4;
    $fields["billing"]["billing_phone"]["priority"] = 3;
    $fields["billing"]["billing_country"]["priority"] = 5;
    $fields["billing"]["billing_city"]["priority"] = 7;
    $fields["billing"]["billing_state"]["priority"] = 6;
    $fields["billing"]["billing_address_1"]["priority"] = 10;
    $fields["billing"]["billing_address_2"]["priority"] = 11;
	$fields["billing"]["billing_state"]["required"] = true;
    $fields["billing"]["billing_city"]["required"] = true;
    $fields["billing"]["billing_address_1"]["required"] = true;
    $fields['billing']['billing_postcode']['required']  = false;
    unset($fields['billing']['billing_postcode']);
    return $fields;
}

add_filter("woocommerce_checkout_fields", "sort_fields_billing",1000);

function pewc_filter_is_purchasable( $is_purchasable, $product ) {
	if( $product->is_in_stock() ) {
		return true;
	}
	return $is_purchasable;
}
add_filter( 'woocommerce_is_purchasable', 'pewc_filter_is_purchasable', 10, 2 );

add_filter( 'woocommerce_cart_needs_shipping', 'filter_cart_needs_shipping' );
function filter_cart_needs_shipping( $needs_shipping )
{
	if (is_cart()) {
		$needs_shipping = false;
	}
	return $needs_shipping;

}

add_filter( 'woocommerce_cart_shipping_method_full_label', 'rs_shipping_label', 10, 2 );
function rs_shipping_label( $label, $method ) {
	if(mb_stripos($label,'CDEK: Посылка склад-склад')!== false) $label=str_replace('CDEK: Посылка склад-склад','CDEK: Посылка до пункта выдачи',$label);
	if(mb_stripos($label,'CDEK: Посылка склад-дверь')!== false) $label=str_replace('CDEK: Посылка склад-дверь','CDEK: Посылка до двери',$label);
	return $label;
}

add_action('wp_footer', 'cdek_add_script_update_pvz_method',100);
function cdek_add_script_update_pvz_method()
{
	if (is_checkout()) {
		?>
		<script>
            jQuery(document).ready(function() {
                changePVZ();
                jQuery(document).on('click','#background', changePVZ);
                jQuery(document).on('click', '.cdek-map .cursor-pinter', changePVZ);
                jQuery(document).on('click', '.cdek-map a', changePVZ);



                jQuery(document).ajaxComplete(function(){
                    changePVZ();
                });
            });
            function changePVZ() {
                jQuery('body #place_order').addClass('disabled').attr('data-scroll','');
                let key=0,
                    infoText='Заполните данные',
                    elem = document.querySelector('.cdek-office-code'),
                    pvz_btn = document.querySelector('.open-pvz-btn'),
                    info = document.querySelector('.rs-product__buttons .tooltiptext'),
                    shipping_method=document.querySelector('#shipping_method input:checked'),
                    billing_city=document.querySelector('#billing_city'),
                    billing_state=document.querySelector('#billing_state'),
                    billing_address=document.querySelector('#billing_address_1');

                if(!billing_state.value) {key++; infoText=key==1?'Введите область/район':infoText;jQuery('body #place_order').attr('data-scroll','billing_state')}
                else if(!billing_city.value) {key++; infoText=key==1?'Введите город':infoText;jQuery('body #place_order').attr('data-scroll','billing_city')}
                else if(!billing_address.value) {key++; infoText=key==1?'Введите адрес':infoText;jQuery('body #place_order').attr('data-scroll','billing_address_1')}
                else if(!shipping_method) {key++; infoText=key==1?'Выберите способ доставки':infoText;jQuery('body #place_order').attr('data-scroll','order_review')}

                if(!key) {
                    if (pvz_btn) {
                        if (!elem.value) {
                            infoText = 'Выберите ПВЗ'
                           /* jQuery('body .open-pvz-btn').html('Выберите ПВЗ')*/
                            jQuery('body #place_order').attr('data-scroll','order_review')
                        } else {
                           // jQuery('body .open-pvz-btn').html('ПВЗ выбран');
                            jQuery('body #place_order').removeClass('disabled').attr('data-scroll','');
                            infoText = '';
                        }
                    } else {
                        infoText = '';
                        jQuery('body #place_order').removeClass('disabled').attr('data-scroll','')
                    }
                }
               info.innerHTML=infoText;
            }
		</script>
		<?php
	}
}

add_filter( 'pre_option_woocommerce_default_gateway' . '__return_false', 99 );
add_filter( 'woocommerce_shipping_chosen_method', '__return_false', 99);

add_filter( 'woocommerce_package_rates', 'rs_remove_shipping_method', 20, 2 );

function rs_remove_shipping_method( $rates, $package ) {
    // удаляем способ доставки
        unset( $rates[ 'official_cdek_plug' ] );
      return $rates;
}

add_filter( 'woocommerce_product_backorders_allowed', '__return_false' );


