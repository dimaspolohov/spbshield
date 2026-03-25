<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.9.5
 */

defined( 'ABSPATH' ) || exit;

global $product;

global $rs_sizes;

$attribute_keys  = array_keys( $attributes );

$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

    <table class="variations" cellspacing="0">
        <?php
        $terms = wp_get_post_terms( $product->get_id(), 'pa_color', array( 'fields' => 'all' ) );
        $prodsVariationColor = get_field('field_63aec089bd07d',$product->get_id());
        if((is_array($terms) && !empty($terms))): ?>
            <tr>
                <td class="value">
                    <div class="rs-product__color color">
                        <h6 class="s-medium-title"><?php _e('Color:','storefront'); ?> <span data-title=""><?php echo esc_html( $terms[0]->name ); ?></span></h6>
                        <div class="rs-product__color-list ">
                            <?php
                            $output = array();
                            if((is_array($terms) && !empty($terms))):
                                foreach ( $terms as $k => $term ) { ob_start();
                                    $colors = $term->slug;
                                    if (get_field("enable_two_color", $term) === true) {
                                        $printColor = explode('-',$term->slug);
                                        $printColor = "linear-gradient(300deg, #".$printColor[0]." 50%, #".$printColor[1]." 50%)";
                                    } else {
                                        $printColor = "#" . explode('-',$term->slug)[0];
                                    } ?>
                                    <div class="rs-product__color-item">
                                        <label>
                                            <input type="radio" name="color"<?php if($k==0) echo ' checked="checked" class="checked"'; ?> value="<?php echo esc_attr( $term->slug ); ?>">
                                            <span class="color" style="background:<?php echo esc_attr( $printColor ); ?>" data-select="<?php echo esc_attr( $term->name ); ?>"></span>
                                        </label>
                                    </div>
                                    <?php $output[$term->term_id] = ob_get_contents(); ob_end_clean();
                                }
                            endif; ?>

                            <?php if(!empty($prodsVariationColor) && is_array($prodsVariationColor)):
                                foreach($prodsVariationColor as $key => $item): ob_start();

                                $colors = $item['color']->slug;
                                if (get_field("enable_two_color", $item['color']) === true) {
                                    $printColor = explode('-',$item['color']->slug);
                                    $printColor = "linear-gradient(300deg, #".$printColor[0]." 50%, #".$printColor[1]." 50%)";
                                } else {
                                    $printColor = "#" . explode('-',$item['color']->slug)[0];
                                } ?>
                                    <a href="<?php echo esc_url( get_the_permalink($item['prod']->ID) ); ?>" class="rs-product__color-item">
                                        <label>
                                            <span class="" style="background:<?php echo esc_attr( $printColor ); ?>" title="<?php echo esc_attr( $item['color']->name ); ?>"></span>
                                        </label>
                                    </a>
                                <?php $output[$item['color']->term_id] = ob_get_contents(); ob_end_clean(); endforeach;
                            endif;

                            $all_colors = get_terms( 'pa_color', [
                                'hide_empty' => false,
                                'fields' => 'ids',
                            ] );
                            foreach($all_colors as $key => $all_color) {
                                if(isset($output[$all_color])) {
                                    $all_colors[$key] = $output[$all_color];
                                } else {
                                    unset($all_colors[$key]);
                                }
                            }
                            echo implode('',$all_colors);
                            ?>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endif; ?>
        <?php foreach ( $attributes as $name => $options ) : ?>
            <?php $sanitized_name = sanitize_title( $name ); ?>
                <?php
                if ( isset( $_REQUEST[ 'attribute_' . $sanitized_name ] ) ) {
                    $checked_value = $_REQUEST[ 'attribute_' . $sanitized_name ];
                } elseif ( isset( $selected_attributes[ $sanitized_name ] ) ) {
                    $checked_value = $selected_attributes[ $sanitized_name ];
                } else {
                    $checked_value = '';
                }
                ?>
                <tr>
                    <td class="value">
                        <?php
                        if ( ! empty( $options ) ) {
                            switch($name) {
                                case 'pa_color':
                                    $str = __('Color','storefront');
                                    $class = "color";
                                break;
                                case 'pa_size':
                                    $str = __('Size','storefront');
                                    $class = "size";
                                break;
                            }
                            if($name!='pa_color'):
                               $is_in_stock = $product->is_purchasable() && $product->is_in_stock();
                            ?>
                            <div class="rs-product__<?php echo esc_attr( $class ); ?> <?php echo esc_attr( $class ); ?>">
                                <h6 class="s-medium-title rrr"><?php echo esc_html( $str ); ?>: <span data-title=""><?php if($checked_value && $checked_value!='') echo esc_html( get_term_by('slug',$checked_value,$name)->name ); ?></span></h6>
                                <div class="rs-product__color-list ">
                                    <?php

                                    if ( taxonomy_exists( $name ) ) {
                                        $terms = wc_get_product_terms( $product->get_id(), $name, array( 'fields' => 'all' ) );

                                        $sku = $product->get_sku();
                                        foreach ( $terms as $term ){
                                            if($term->slug=='onesizeonesize') continue;

                                            if ( ! in_array( $term->slug, $options ) ) {
                                                continue;
                                            }

                                            $variation_id = wc_get_product_id_by_sku($sku.'-'.$term->slug);
                                            $variation = wc_get_product( $variation_id );
                                            $stock_quantity = 0;

                                            if($variation) {
                                                $stock_quantity = $variation->get_stock_quantity();
                                            }

                                            $should_disable = !$is_in_stock || !$stock_quantity || $stock_quantity <= 0;

                                            print_attribute_radio_wc( $checked_value, $term->slug, $term->name, $sanitized_name, $should_disable );
                                        }
                                    } else {
                                        foreach ( $options as $option ) {
                                            print_attribute_radio_wc( $checked_value, $option, $option, $sanitized_name, !$is_in_stock );
                                        }
                                    }
                                    ?>
                                </div>
                                <?php if($name=='pa_size' ) {
                                    $rs_sizes = $options;
                                    if(get_field('gid_po_razmeram')) { ?><a data-popup="#size-popup"><?php _e('Гид по размерам','storefront'); ?></a><?php }
                                } ?>

                            </div><?php
                            endif;
                        }
                        ?>
                    </td>
                </tr>
        <?php endforeach; ?>
    </table>

    <?php
    /**
     * Hook: woocommerce_before_single_variation.
     */
    do_action( 'woocommerce_before_single_variation' );

    /**
     * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
     *
     * @since 2.4.0
     * @hooked woocommerce_single_variation - 10 Empty div for variation data.
     * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
     */
    do_action( 'woocommerce_single_variation' );

    /**
     * Hook: woocommerce_after_single_variation.
     */
    do_action( 'woocommerce_after_single_variation' );
    ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
