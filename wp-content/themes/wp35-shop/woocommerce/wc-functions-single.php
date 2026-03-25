<?php

// Disable default single product page functions
function rs_delete_single_product() {
	remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
	remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    remove_action( 'woocommerce_after_single_product_summary','woocommerce_output_product_data_tabs', 10 );
    remove_action( 'woocommerce_after_single_product_summary','woocommerce_output_related_products', 20 );
    remove_action( 'storefront_after_footer', 'storefront_sticky_single_add_to_cart', 999 );
}
add_action( 'init', 'rs_delete_single_product', 1);

// Add new single product page functions
function rs_add_single_product() {
	add_action('rs_woocommerce_before_main_content', 'rs_woocommerce_breadcrumb', 5);
    add_action( 'woocommerce_after_single_product_summary','woocommerce_upsell_display', 30 );
    add_action( 'rs_relation_woocommerce_before_shop_loop_item_title','rs_relation_woocommerce_template_loop_product', 10 );
}
add_action( 'init', 'rs_add_single_product', 2);

// Gallery image with custom class
function wc_get_gallery_image_html_new( $attachment_id, $main_image = false ) {
    $flexslider        = (bool) apply_filters( 'woocommerce_single_product_flexslider_enabled', get_theme_support( 'wc-product-gallery-slider' ) );
    $gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
    $thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
    $image_size        = apply_filters( 'woocommerce_gallery_image_size', $flexslider || $main_image ? 'woocommerce_single' : $thumbnail_size );
    $full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
    $thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
    $full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
    $image             = wp_get_attachment_image(
        $attachment_id,
        $image_size,
        false,
        apply_filters(
            'woocommerce_gallery_image_html_attachment_image_params',
            array(
                'title'                   => _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
                'data-caption'            => _wp_specialchars( get_post_field( 'post_excerpt', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
                'data-src'                => esc_url( $full_src[0] ),
                'data-large_image'        => esc_url( $full_src[0] ),
                'data-large_image_width'  => esc_attr( $full_src[1] ),
                'data-large_image_height' => esc_attr( $full_src[2] ),
                'class'                   => esc_attr( $main_image ? 'wp-post-image' : '' ),
            ),
            $attachment_id,
            $image_size,
            $main_image
        )
    );
    return '<div data-thumb="' . esc_url( $thumbnail_src[0] ) . '" class="woocommerce-product-gallery__image carousel-inner"><a href="' . esc_url( $full_src[0] ) . '">'. $image . '</a></div>';
}

add_filter('woocommerce_get_image_size_single','add_single_size',1,10);
function add_single_size($size){
    $size['width'] = 600;
    $size['height'] = 600;
    $size['crop']   = 0;
    return $size;
}

// Display title
function rs_woocommerce_template_single_title() {
	the_title( '<h1 class="product-title entry-title">', '</h1>' );
}

// Display SKU
function rs_woocommerce_template_single_sku() {
	global $product;
    if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
        <h3 class="product-code"><?php esc_html_e( 'АРТИКУЛ: ', 'woocommerce' ); ?><span><?php echo ( $sku = $product->get_sku() ) ? esc_html( $sku ) : esc_html__( 'N/A', 'woocommerce' ); ?> </span> </h3>
    <?php endif;
}

// Display price
function rs_woocommerce_template_single_price() {
	global $product;
	?>
	<div class="product-price <?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) );?>"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
	<?php
}

add_filter( 'wc_price', 'currency_price', 10, 3 );
function currency_price( $return, $price, $args){
    if(get_woocommerce_currency()=='RUB')
        $price=$price.' ₽';
    return $price;
}
if (!function_exists('my_commonPriceHtml')) {
    function my_commonPriceHtml($price_amt, $regular_price, $sale_price) {
        $html_price = '';
        if (($price_amt == $sale_price) && ($sale_price != 0)) {
            $html_price .= '<div class="rs-product__price rs-product__price-old">' . wc_price($regular_price) . '</div>';
            $html_price .= '<div class="rs-product__price rs-product__price-new">' . wc_price($sale_price) . '</div>';
        }
        else if (($price_amt == $sale_price) && ($sale_price == 0)) {
            $html_price .= '<div class="rs-product__price rs-product__price-new">' . wc_price($regular_price) . '</div>';
        }
        else if (($price_amt == $regular_price) && ($regular_price != 0)) {
            $html_price .= '<div class="rs-product__price rs-product__price-new">' . wc_price($regular_price) . '</div>';
        }
        else if (($price_amt == $regular_price) && ($regular_price == 0)) {
            $html_price .= '<div class="rs-product__price rs-product__price-old"></div>';
        }
        return $html_price;
    }
}
add_filter('woocommerce_get_price_html', 'my_simple_product_price_html', 999, 2);
function my_simple_product_price_html($price, $product) {
    if ($product->is_type('simple')) {
        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
        $price_amt = $product->get_price();
        return my_commonPriceHtml($price_amt, $regular_price, $sale_price);
    } else if($product->is_type( 'variable' )) {
        return $price;
    } else {
        return $price;
    }
}

// Display short description
function rs_woocommerce_template_single_excerpt() {
	global $post;
	$short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );
	if ( ! $short_description ) {
		return;
	}
	?>
	<div class="woocommerce-product-details__short-description details-description">
		<?php echo wp_kses_post( $short_description ); ?>
	</div>
	<?php
}

// "In stock" availability status
add_filter( 'woocommerce_get_availability', 'wcs_custom_get_availability', 1, 2);
function wcs_custom_get_availability( $availability, $_product ) {
    if ( $_product->is_in_stock() ) {
        $availability['availability'] = __('В наличии', 'woocommerce');
    }
    return $availability;
}

// Utility function to get the default variation (if it exists)
function get_default_variation( $product ){
    $attributes_count = count($product->get_variation_attributes());
    $default_attributes = $product->get_default_attributes();
    if( $attributes_count != count($default_attributes) )
        return false;
    foreach( $product->get_available_variations() as $variation ){
        $found = true;
        foreach( $variation['attributes'] as $key => $value ){
            $taxonomy = str_replace( 'attribute_', '', $key );
            if( isset($default_attributes[$taxonomy]) && $default_attributes[$taxonomy] != $value ){
                $found = false;
                break;
            }
        }
        if( $found ) {
            $default_variaton = $variation;
            break;
        }
        else {
            continue;
        }
    }
    return isset($default_variaton) ? $default_variaton : false;
}

add_action( 'woocommerce_before_single_product', 'move_variations_single_price', 1 );
function move_variations_single_price(){
    global $product, $post;
}

function rs_replace_variation_single_price(){
    global $product;
    $sku = '';
    // Main price
    $prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
    $active_price = $prices[0] !== $prices[1] ?    wc_price( $prices[0] ).' - '. wc_price( $prices[1] ): wc_price( $prices[0] );
    // Sale price
    $prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
    sort( $prices );
    $regular_price = $prices[0] !== $prices[1] ?  wc_price( $prices[0] ).' - '. wc_price( $prices[1] ) : wc_price( $prices[0] );
    if ( $active_price !== $regular_price && $product->is_on_sale() ) {
        $price = '<div class="rs-product__price rs-product__price-new">' . $regular_price . $product->get_price_suffix() . '</div><div class="rs-product__price rs-product__price-old">' . $active_price . $product->get_price_suffix() . '</div> ';
    } else {
        $price = '<div class="rs-product__price rs-product__price-new">' .$regular_price. '</div>';
    }
    // When a default variation is set for the variable product
    if( get_default_variation( $product ) ) {
        $default_variaton = get_default_variation( $product );

        if( ! empty($default_variaton['price_html']) ){
            $price_html = $default_variaton['price_html'];
        } else {
            if ( ! $product->is_on_sale() )
                $price_html = $price = wc_price($default_variaton['display_price']);
            else
                $price_html = $price;
        }
    } else {
        $price_html = $price;
        $sku = ($product->get_sku() ) ? $product->get_sku() : esc_html__( 'N/A', 'woocommerce' );
    }

    wp_localize_script('rs-product-single', 'rsVariationData', array(
        'priceHtml' => wp_kses_post($price_html),
        'sku'       => esc_html($sku),
    ));

    echo '<div class="product-price">' . wp_kses_post( $price_html ) . '</div>
    <div class="hidden-variable-price" >' . wp_kses_post( $price ) . '</div>
    <div class="hidden-variable-sku" >' . esc_html( $sku ) . '</div>';
}

// Wrapper for the "Buy in one click" button
add_action( 'woocommerce_before_add_to_cart_quantity', 'action_function_before_add_to_cart_quantity',10 );
function action_function_before_add_to_cart_quantity(){ ?>
    <div class="row">
    <?php
}

function action_function_after_add_to_cart_button(){ ?>
    </div>
    <div class="row btn-reverce">
    <?php
}

add_action( 'awooc_before_button', 'action_function_awooc_before_button',1  );
function action_function_awooc_before_button(){ ?>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    <?php
}
add_action( 'awooc_after_button', 'action_function_awooc_after_button',10  );
function action_function_awooc_after_button(){ ?>
    </div>
    </div>
    <?php
}

// Move categories and tags into the "description" tab
add_filter( 'woocommerce_product_tabs', 'woo_custom_description_tab', 98 );
function woo_custom_description_tab( $tabs ) {
    $tabs['description']['callback'] = 'woo_custom_description_tab_content';
    return $tabs;
}
function woo_custom_description_tab_content($tabs) {
    woocommerce_product_description_tab();
    add_action( 'description_tab_meta', 'woocommerce_template_single_meta', 5 );
    do_action( 'description_tab_meta' );
}

add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );
function woo_remove_product_tabs( $tabs ) {
    unset( $tabs['reviews'] );
    unset( $tabs['additional_information'] );
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {
    $tabs['description']['title'] = __( 'Детальное описание' );
    return $tabs;
}

add_filter('woocommerce_product_tabs','add_tabs');
function add_tabs($tabs){
    $tabs['new_tab'] = array(
        'title'    => 'Доставка',
        'priority' => 80,
        'callback' => 'product_delivery_tab'
    );
    return $tabs;
};

function product_delivery_tab(){
    echo wp_kses_post( get_field('delevery') );
}

function rs_share_custom_action() { ?>
    <div class="product-share clearfix">
        <p class="text-uppercase"><strong>Поделиться</strong></p>
        <script src="https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
        <script src="https://yastatic.net/share2/share.js"></script>
        <div class="ya-share2" data-services="collections,vkontakte,facebook,odnoklassniki,moimir"></div>
    </div>
    <?php
}

// Add notification to the "Buy in one click" popup
add_action( 'awooc_popup_column_left', 'awooc_popup_woocommerce_agreement', 50  );
function awooc_popup_woocommerce_agreement() {
    global $post;
    $query = new WP_Query( array (
        'post_type' => 'custom_block',
        'meta_query' => array (
            'relation' => 'OR',
            array (
                'key'     => 'block_id',
                'value'   => \SpbShield\Inc\ThemeConfig::BLOCK_ID_AGREEMENT,
                'compare' => '='
            )
        )
    ));
    while ( $query->have_posts() ) {
        $query->the_post();
        $post_meta = get_post_meta($query->post->ID);
    }
	$notification_header = $notification_text = '';
    if (isset($post_meta) && $post_meta) {
        $notification_header = get_field("notification_header") ?: '';
        $notification_text = get_field("notification_text") ?: '';
    }
    ?>
    <div class="rs-17">
        <div class="rs-modal">
            <div class="modal fade" tabindex="-1" id="agreement-price">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <div class="modal-title"><?php echo esc_html( $notification_header ); ?></div>
                        </div>
                        <div class="modal-body">
                            <?php echo wp_kses_post( $notification_text ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

}

// Enqueue product single page scripts and pass PHP data via wp_localize_script
add_action('wp_enqueue_scripts', 'rs_enqueue_product_single_scripts');
function rs_enqueue_product_single_scripts() {
    if (is_product()) {
        wp_enqueue_script('rs-product-single', get_stylesheet_directory_uri() . '/assets/js/rs-product-single.js', array('jquery'), false, true);
        wp_localize_script('rs-product-single', 'rsProductSingleData', array(
            'thumbnail' => esc_url(get_the_post_thumbnail_url()),
            'title'     => get_the_title(),
        ));
    }
}


if (wp_is_mobile()) {
	remove_action( 'awooc_after_button','woocommerce_single_variation',10 );
	add_action( 'woocommerce_single_product_summary','woocommerce_single_variation',19 );

    // Mobile back button — pass category link to external JS via wp_localize_script
    add_action( 'woocommerce_product_thumbnails','action_function_back_category',15 );
    function action_function_back_category(){
        $terms = wp_get_post_terms( get_the_id(), 'product_cat', array( 'include_children' => false ) );
        $term = reset($terms);
        $term_link = get_term_link( $term->term_id, 'product_cat' );
        wp_localize_script('rs-product-single', 'rsBackCategory', array(
            'termLink' => esc_url($term_link),
        ));
    }

    add_filter( 'woocommerce_single_product_carousel_options', 'ud_update_woo_flexslider_options' );
    function ud_update_woo_flexslider_options( $options ) {
        $options['controlNav'] = true;
        $options['sync'] = '';
        return $options;

    }
	function add_mobile_script() {
		wp_enqueue_script( 'mobile-part', get_stylesheet_directory_uri(). '/assets/js/mobile.js', array('jquery'), '1.0', true );
    }
    add_action( 'wp_enqueue_scripts', 'add_mobile_script' );

}

// Related products
function rs_woocommerce_output_related_products() {
    global $product;
    if ( ! $product ) {
        return;
    }
    $args = array(
        'posts_per_page' => 4,
        'columns'        => 4,
        'orderby'        => 'rand', // @codingStandardsIgnoreLine.
        'order'          => 'desc',
    );
    // Get visible related products then sort them at random.
    $args['related_products'] = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $args['posts_per_page'], $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );
    // Handle orderby.
    $args['related_products'] = wc_products_array_orderby( $args['related_products'], $args['orderby'], $args['order'] );
    // Set global loop values.
    wc_set_loop_prop( 'name', 'related' );
    wc_set_loop_prop( 'columns', apply_filters( 'woocommerce_related_products_columns', $args['columns'] ) );
    wc_get_template( 'single-product/related.php', $args );
}

// TODO: Consider using wc_get_template_part('content', 'product') to fully
// eliminate remaining markup duplication with content-product.php.
// Related products card output
function rs_relation_woocommerce_template_loop_product() {
    global $product;
    $discount_pct = rs_get_product_discount($product);
    $onsale = $product->is_on_sale();
    $discount = $discount_pct ? '-' . intval($discount_pct) . '%' : '';
    $title_products = rs_get_truncated_title(40);
    ?>
	<div class="rs-buy-product__slide swiper-slide">
		<div class="product">
			<a href="<?php echo esc_url( get_permalink() ); ?>">
				<div class="product__picture">
					<div class="product__slider swiper">
						<div class="product__swiper swiper-wrapper">
							<?php
							$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'img-product__slide' );
							if($image) { ?>
							<div class="product__slide swiper-slide">
								<picture>
									<source srcset="<?php echo esc_url( $image[0] ); ?>.webp" type="image/webp">
									<img src="<?php echo esc_url( $image[0] ); ?>" alt="">
								</picture>
							</div>
							<?php
							$attachment_ids = $product->get_gallery_image_ids();
							if(!$attachment_ids) $attachment = $image[0]; else $attachment = wp_get_attachment_image_src( $attachment_ids[0], 'img-product__slide' )[0]; ?>
							<div class="product__slide swiper-slide">
								<picture>
									<source srcset="<?php echo esc_url( $attachment ); ?>.webp" type="image/webp">
									<img src="<?php echo esc_url( $attachment ); ?>" alt="">
								</picture>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="product__description">
					<?php
					$attributes = $product->get_attributes();
					if(isset($attributes["pa_color"])) $pa_color = $attributes["pa_color"];
					if(isset($pa_color)) if($pa_color && isset($pa_color['options']) && count($pa_color['options'])>0) { ?>
					<div class="product__color">
						<ul>
							<?php foreach($pa_color['options'] as $color) { ?>
								<li style="background-color:#<?php echo esc_attr( get_term($color)->slug ); ?>"></li>
							<?php } ?>
						</ul>
					</div>
					<?php } ?>
					<h5 class="s-regular-title"><?php echo esc_html( $title_products ); ?></h5>
					<div class="product__footer">
						<div class="product__prices">
							<?php
							$price = $product->get_price_html();
							if(strpos($price,'rs-product__price rs-product__price-old')>-1) {
								$price = str_replace( 'rs-product__price rs-product__price-old', 'product__price product__price-old', $price );
								$price = str_replace( 'rs-product__price rs-product__price-new', 'product__price product__price-new', $price );
							} else {
								$price = str_replace( 'rs-product__price rs-product__price-new', 'product__price product__price-current', $price );
							}
							echo wp_kses_post( $price );
							?>
						</div>
						<div class="product__labels">
							<?php if($onsale ) : ?>
							<div class="product__label product__label-sale"><?php echo esc_html( $discount ); ?></div>
							<?php endif;
							if(get_field('_new_product')=='yes') : ?>
							<div class="product__label product__label-new"><?php _e('New','storefront'); ?></div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</a>
		</div>
	</div>
    <?php
}

// Output attributes as radio buttons
if ( ! function_exists( 'print_attribute_radio' ) ) {
    function print_attribute_radio( $checked_value,$image, $value, $label, $name ) {
        global $product;

        $input_name = 'attribute_' . esc_attr( $name ) ;
        $esc_value = esc_attr( $value );
        $bg = '#'.$esc_value;
        $id = esc_attr( $name . '_v_' . $value . $product->get_id() );
        $checked = checked( $checked_value, $value, false );
        $filtered_label = apply_filters( 'woocommerce_variation_option_name', $label, esc_attr( $name ) );
        if($image){
            printf( '<div><label for="%3$s"><input type="radio" name="%1$s" value="%2$s" id="%3$s" %4$s><span class="color-square" ><img alt="цвет" src="%6$s"></span>%5$s</label></div>', $input_name, $esc_value, $id, $checked, $filtered_label,$image );
        } else {
            printf( '<div><label for="%3$s"><input type="radio" name="%1$s" value="%2$s" id="%3$s" %4$s><span class="color-square" style="background-color:  %6$s"><i class="fas fa-check"></i></span></label></div>', $input_name, $esc_value, $id, $checked, $filtered_label,$bg );
        }
    }
}

// Output attributes as radio buttons with disabled support (for sizes when product is out of stock)
if ( ! function_exists( 'print_attribute_radio_wc' ) ) {
    function print_attribute_radio_wc( $checked_value, $value, $label, $name, $disabled = false ) {
        $input_name = 'attribute_' . esc_attr( $name );
        $esc_value = esc_attr( $value );
        $id = esc_attr( $name . '_v_' . $value );
        $checked = checked( $checked_value, $value, false );
        $disabled_attr = $disabled ? ' disabled="disabled"' : '';
        $disabled_class = $disabled ? ' disabled' : '';
        $filtered_label = apply_filters( 'woocommerce_variation_option_name', $label );

        printf(
            '<div class="rs-product__size-item%6$s"><label><input type="radio" name="%1$s" value="%2$s" id="%3$s" %4$s%5$s><span class="size" data-select="%7$s">%7$s</span></label></div>',
            $input_name,
            $esc_value,
            $id,
            $checked,
            $disabled_attr,
            $disabled_class,
            $filtered_label
        );
    }
}
