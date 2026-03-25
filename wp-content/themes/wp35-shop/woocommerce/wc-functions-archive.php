<?php

add_action( 'widgets_init', 'remove_storefront_widgets' );
function remove_storefront_widgets() {
	unregister_widget('WC_Widget_Layered_Nav');
	unregister_widget('WC_Widget_Price_Filter');
	unregister_widget('WC_Widget_Product_Categories');
}

function rs_archive_wrapper_start() { ?>
	<div class="col-lg-9 col-md-9 col-sm-12">
<?php }

function rs_archive_wrapper_end() { ?>
	</div>
<?php }

function rs_archive_woocommerce_sidebar() { ?>

<?php }

// Opening tag for the inner product grid
function rs_woocommerce_product_loop_start() { ?>
	<div class="row categoryProduct xsResponse clearfix grid-view">
<?php }

if(is_shop() || is_product_category() ){
    // Load styles for the "quick view" modal
    add_action( 'wp_enqueue_scripts', 'style_rs_product_view_theme');
    function style_rs_product_view_theme() {
        wp_enqueue_style( 'rs-product-view', get_stylesheet_directory_uri().'/woocommerce/css/rs-product-view.css');
    }
}

function rs_woocommerce_breadcrumb() { ?>
	<div class="rs-breadcrumbs">
		<div class="rs-breadcrumbs__container <?php echo esc_attr( !(is_shop() || is_product_category() || is_product_tag()) ? 'nav-left' : '' ); ?>">
		    <?php if(!\SpbShield\Inc\LegacySupport::is_mobile() || !(is_shop() || is_product_category() || is_product_tag()) ): ?>
                <nav class="rs-breadcrumbs__navigation">
                    <?php woocommerce_breadcrumb(
                        array(
                            'delimiter'   => '',
                            'wrap_before' => '<ul class="rs-breadcrumbs__list">',
                            'wrap_after'  => '</ul>',
                            'before'      => '<li class="rs-breadcrumbs__item">',
                            'after'       => '</li>',
                            'home'        => __('Главная','storefront'),
                        )
                    ); ?>
                </nav>
			<?php else: ?>
			    <button type="button" class="filter-show icon-filter _filters-active"><span><?php _e('Hide','storefront'); ?></span><span><?php _e('Show','storefront'); ?></span> <?php _e('filters','storefront'); ?></button>
			<?php endif; ?>
			<?php if( is_shop() || is_product_category() || is_product_tag() ) { ?>
			<!-- rs-filters -->
			<div class="rs-filters" data-da=".page, 992, first">
				<div class="rs-filters__wrapper">
				    <?php if(!\SpbShield\Inc\LegacySupport::is_mobile()): ?>
					    <button type="button" class="filter-show icon-filter _filters-active"><span><?php _e('Hide','storefront'); ?></span><span><?php _e('Show','storefront'); ?></span> <?php _e('filters','storefront'); ?></button>
					<?php else: ?>
                        <nav class="rs-breadcrumbs__navigation">
                            <?php woocommerce_breadcrumb(
                                array(
                                    'delimiter'   => '',
                                    'wrap_before' => '<ul class="rs-breadcrumbs__list">',
                                    'wrap_after'  => '</ul>',
                                    'before'      => '<li class="rs-breadcrumbs__item">',
                                    'after'       => '</li>',
                                    'home'        => __('Главная','storefront'),
                                )
                            ); ?>
                        </nav>
					<?php endif; ?>
					<div class="rs-filters__view">
						<div class="rs-filters__view-item rs-filters__view-1">
							<button type="button" class="icon-icon_1"></button>
						</div>
						<div class="rs-filters__view-item rs-filters__view-4">
							<button type="button" class="_active-view icon-icon_4"></button>
						</div>
					</div>
					<!-- edit in START wc-functions-archive.php -->
					<div class="rs-filters__sorting">
						<form>
							<div class="select" data-state="">
								<div class="select__title" data-default="Option 0"><?php _e('Sort','storefront'); ?></div>
								<?php if(isset($_GET['orderby'])) $ORDERBY = sanitize_text_field( wp_unslash( $_GET['orderby'] ) ); if(!isset($ORDERBY) || !$ORDERBY || ( $ORDERBY!='popularity' && $ORDERBY!='price' && $ORDERBY!='price-desc' && $ORDERBY!='date' ) ) $ORDERBY = 'menu_order'; ?>
								<div class="select__content">
									<input class="select__input" type="radio" name="orderby" disabled />
									<label class="select__label"><?php _e('Sort','storefront'); ?></label>
									<input id="singleSelect0" class="select__input" type="radio" name="orderby" value="menu_order" <?php \SpbShield\Inc\LegacySupport::filters_orderby('menu_order',$ORDERBY); ?> />
									<label for="singleSelect0" class="select__label"><span></span> <?php _e('по умолчанию','storefront'); ?></label>
									<input id="singleSelect4" class="select__input" type="radio" name="orderby" value="date" <?php \SpbShield\Inc\LegacySupport::filters_orderby('date',$ORDERBY); ?> />
									<label for="singleSelect4" class="select__label"><span></span> <?php _e('по новизне','storefront'); ?></label>
									<input id="singleSelect2" class="select__input" type="radio" name="orderby" value="price" <?php \SpbShield\Inc\LegacySupport::filters_orderby('price',$ORDERBY); ?> />
									<label for="singleSelect2" class="select__label"><span></span> <?php _e('дешевле','storefront'); ?></label>
									<input id="singleSelect3" class="select__input" type="radio" name="orderby" value="price-desc" <?php \SpbShield\Inc\LegacySupport::filters_orderby('price-desc',$ORDERBY); ?> />
									<label for="singleSelect3" class="select__label"><span></span> <?php _e('дороже','storefront'); ?></label>
								</div>
							</div>
						</form>
					</div>
					<!-- edit in END wc-functions-archive.php -->
				</div>
			</div>
			<!-- /rs-filters -->
			<?php } ?>
		</div>
	</div>
<?php  }

// Disable default catalog functions
function rs_delete_archive() {

	remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
	remove_action('woocommerce_before_main_content', 'WC_Structured_Data::generate_website_data', 30);
	remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);
	remove_action('woocommerce_archive_description', 'woocommerce_product_archive_description', 10);

	remove_action('woocommerce_before_main_content', 'storefront_before_content', 10);
	remove_action('woocommerce_after_main_content', 'storefront_after_content', 10);

	remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper', 9 );
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper_close', 31 );

	remove_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper', 9 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_before_shop_loop', 'storefront_woocommerce_pagination', 30 );
	remove_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper_close', 31 );

	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

	remove_action( 'woocommerce_after_single_product_summary', 'storefront_upsell_display', 15 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 6 );
	remove_action( 'woocommerce_after_single_product_summary', 'storefront_single_product_pagination', 30 );

	remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );

};
add_action( 'init', 'rs_delete_archive', 1);

// Add new catalog blocks
function rs_add_archive() {
    add_action('woocommerce_before_main_content', 'rs_woocommerce_breadcrumb', 5);
	add_action('woocommerce_archive_description', 'rs_woocommerce_taxonomy_archive_description', 10);
	add_action('woocommerce_archive_description', 'rs_woocommerce_product_archive_description', 10);

	add_action('woocommerce_before_main_content', 'rs_woocommerce_output_content_wrapper', 7);
	add_action('woocommerce_before_main_content', 'rs_storefront_before_content', 10);
	add_action('woocommerce_after_main_content', 'rs_storefront_after_content', 10);
	add_action('woocommerce_after_main_content', 'rs_woocommerce_output_content_wrapper_end', 20);

	add_action('woocommerce_before_shop_loop', 'rs_storefront_sorting_wrapper', 10);
	add_action('woocommerce_before_shop_loop', 'rs_woocommerce_result_count', 20 );
	add_action('woocommerce_before_shop_loop', 'rs_woocommerce_catalog_ordering', 25 );
	add_action('woocommerce_before_shop_loop', 'rs_storefront_sorting_wrapper_close', 31);

	add_action( 'woocommerce_before_shop_loop_item_title', 'rs_woocommerce_template_loop_product', 10 );

	add_action( 'woocommerce_shop_loop_subcategory_title', 'rs_woocommerce_template_loop_category_title', 10 );

}
add_action( 'init', 'rs_add_archive', 2);

// Opening and closing catalog content functions
function rs_storefront_before_content() {
	do_action( 'storefront_sidebar' );
	?>
		<div id="primary" class="col-lg-9 col-md-9 col-sm-12">
			<main id="main" class="site-main">
	<?php
}
function rs_storefront_after_content() {
	?>
			</main><!-- #main -->
		</div><!-- #primary -->
	<?php

	do_action( 'storefront_sidebar' );
}

// Wrapper for catalog and sidebar
function rs_woocommerce_output_content_wrapper() {
	?>
		<div class="row">
	<?php
}
function rs_woocommerce_output_content_wrapper_end() {
	?>
		</div>
	<?php
}


// Customize breadcrumbs
add_filter( 'woocommerce_breadcrumb_defaults', 'rs_custom_breadcrumb', 25 );
function rs_custom_breadcrumb( $defaults ) {
    if (is_woocommerce()) {
		$defaults['delimiter'] = '<span class="sep"> / </span>';
		$defaults['wrap_before'] = '<div class="breadcrumbs">';
		$defaults['wrap_after'] = '</div>';
		$defaults['before'] = '<span>';
		$defaults['after'] = '</span>';
		$defaults['home'] = get_the_title(\SpbShield\Inc\ThemeConfig::SHOP_PAGE_ID);
    } else {
        $defaults['delimiter'] = '<span class="sep"> / </span>';
		$defaults['wrap_before'] = '<div class="breadcrumbs">';
		$defaults['wrap_after'] = '</div>';
		$defaults['before'] = '<span>';
		$defaults['after'] = '</span>';
    }
    return $defaults;
}

function rs_custom_breadcrumb_home( $home_url ) {
    if (is_woocommerce()) {
		return  get_post_type_archive_link('product');
	} else {
       return $home_url;
	}

}

// Customize category description
function rs_woocommerce_taxonomy_archive_description() {
	global $post;
	if ( is_product_taxonomy() && 0 === absint( get_query_var( 'paged' ) ) ) {
		$term = get_queried_object();
		if ($term && $img_id = get_term_meta( $term->term_id, 'thumbnail_id', true)) {
			$image = wp_get_attachment_image_src( $img_id, 'large' );
			?>
			<div class="img-banner">
				<img data-src='<?php echo esc_url( $image[0] ); ?>' src='<?php echo esc_url( $image[0] ); ?>' class='img-responsive b-lazy' alt>
			</div>
			<?php
		}
		if ( $term && ! empty( $term->description ) ) {
			echo '<div>' . wc_format_content( $term->description ) . '</div>';
		}
	}
}

function rs_woocommerce_product_archive_description() {
	if ( is_search() ) {
		return;
	}
	if ( is_post_type_archive( 'product' ) && in_array( absint( get_query_var( 'paged' ) ), array( 0, 1 ), true ) ) {
		$shop_page = get_post( wc_get_page_id( 'shop' ) );
		if ( $shop_page ) {
			$description = wc_format_content( $shop_page->post_content );
			if ( $description ) {
				echo '<p>' . wp_kses_post( $description ) . '</p>';
			}
		}
	}
}

// Customize product grid filter
function rs_storefront_sorting_wrapper() {
	echo '<div class="product-filter clearfix">';
}

function rs_storefront_sorting_wrapper_close() {
	echo '</div>';
}

function rs_woocommerce_result_count() {
	if ( ! wc_get_loop_prop( 'is_paginated' ) ) {
		return;
	}
	$args = array(
		'total'    => wc_get_loop_prop( 'total' ),
		'per_page' => wc_get_loop_prop( 'per_page' ),
		'current'  => wc_get_loop_prop( 'current_page' ),
	);
	?>
		<p class="pull-left">
			<?php
			if ( $args['total'] <= $args['per_page'] || -1 === $args['per_page'] ) {
			    echo 'Показано продуктов: <b class="count">' . esc_html( $args['total'] ) . '</b>';
			}  else {
				$first = ( $args['per_page'] * $args['current'] ) - $args['per_page'] + 1;
				$last  = min( $args['total'], $args['per_page'] * $args['current'] );

				printf( _nx( '', 'Показано продуктов: <b class="count">%3$d</b>', $args['total'],  'woocommerce' ), $first, $last, $args['total'] );
			}
			?>
		</p>
	<?php
}

function rs_woocommerce_catalog_ordering() {

	if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() || ! woocommerce_product_loop() ) {
		return;
	}
	$show_default_orderby = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );
	$catalog_orderby_options = apply_filters(
		'woocommerce_catalog_orderby',
		array(
			'menu_order' => __( 'Сортировать по:', 'woocommerce' ),
			'popularity' => __( 'Sort by popularity', 'woocommerce' ),
			'price'      => __( 'По цене (по возрастанию)', 'woocommerce' ),
			'price-desc' => __( 'По цене (по убыванию)', 'woocommerce' ),
			'date'       => __( 'По новизне', 'woocommerce' ),
		)
	);
	$default_orderby = wc_get_loop_prop( 'is_search' ) ? 'relevance' : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', '' ) );
	$orderby         = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby; // WPCS: sanitization ok, input var ok, CSRF ok.
	if ( wc_get_loop_prop( 'is_search' ) ) {
		$catalog_orderby_options = array_merge( array( 'relevance' => __( 'Relevance', 'woocommerce' ) ), $catalog_orderby_options );
		unset( $catalog_orderby_options['menu_order'] );
	}
	if ( ! $show_default_orderby ) {
		unset( $catalog_orderby_options['menu_order'] );
	}
	unset( $catalog_orderby_options['rating'] );
	if ( ! array_key_exists( $orderby, $catalog_orderby_options ) ) {
		$orderby = current( array_keys( $catalog_orderby_options ) );
	}
	?>
	<div class="change-order pull-right">
		<form class="woocommerce-ordering" method="get">
			<select name="orderby" class="orderby select2-container">
				<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
					<option class="select2-container__option" value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
				<?php endforeach; ?>
			</select>
			<input type="hidden" name="paged" value="1" />
			<?php wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page' ) ); ?>
		</form>
	</div>
	<?php
}

// Customize product grid
function rs_woocommerce_template_loop_product_thumbnail() {
	global $product;
	?>
		<div class="product-image">
	<?php

		$image_size = apply_filters( 'single_product_archive_thumbnail_size', 'woocommerce_thumbnail' );
		echo $product ? $product->get_image( $image_size ) : '';
	?>
		</div>
	<?php
}

// Product card output in catalog
function rs_woocommerce_template_loop_product() {
	global $product;
	$title_products = rs_get_truncated_title();
	$discount = rs_get_product_discount($product);
	$onsale = $product->is_on_sale();
	// Truncate short description to 80 characters
	$short_description = get_the_excerpt();
    $description = $short_description? \SpbShield\Inc\ServiceFunctions::truncate_text(strip_tags(preg_replace ('~\[[^\]]+\]~', '',$short_description)), 80):\SpbShield\Inc\ServiceFunctions::truncate_text(strip_tags(preg_replace ('~\[[^\]]+\]~', '',$product->get_description())), 80);
	?>
	<div class="product">
		<div class="product-image">
			<div class="quickview">
				<a data-id="<?php echo esc_attr( $product->get_ID() ); ?>" class="btn btn-xs btn-quickview" href="#" data-target="#product-details-modal" data-path="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>"
				   data-toggle="modal">Быстрый просмотр</a>
			</div>
			<a href="<?php echo esc_url( get_permalink() ); ?>">
				<?php if (has_post_thumbnail()) { ?>
				  <img src="<?php the_post_thumbnail_url( 'shop_catalog' ); ?>" alt="<?php echo esc_attr( $title_products ); ?>">
				<?php }
					else echo '<img src="' . esc_url( wc_placeholder_img_src() ) . '" data-src="' . esc_url( wc_placeholder_img_src() ) . '" alt="Placeholder" width="250px" height="250px" />';
				?>
			</a>

			<ul class="promotion">
				<?php
				if(get_field('_new_product')=='yes') : ?>
					<li class="new">NEW </li>
				<?php endif; ?>
				<?php if($onsale ) : ?>
					<li class="discount">SALE <?php echo esc_html( $discount ); ?></li>
				<?php endif; ?>
			</ul>

		</div>
		<div class="description">
			<h4><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( $title_products ); ?></a></h4>
			<p><?php echo esc_html( $description ); ?></p>
		</div>
		 <?php
            rs_available_attributes();
            $class = ($product->is_on_sale())?'in_sale':'';
		?>
		<div class="price-block">
			<span class="price <?php echo esc_attr( $class ); ?>">
				<?php echo wp_kses_post( $product->get_price_html() ); ?>
			</span>
		</div>
		<div class="action-control">
			<a href="<?php echo esc_url( get_permalink() ); ?>" class="btn btn-color">Подробнее</a>
			<div class="btn btn-default addBascetAjax gridlist-buttonwrap"><?php woocommerce_template_loop_add_to_cart(); ?></div>
		</div>
		<div class="success text-center"></div>
	</div>
	<?php
}
// Output attributes in the catalog product card
function rs_available_attributes() {
    global $product;
    if ( ! $product->is_type( 'variable' ) ) {
        return;
    }
    $attributes = rs_get_available_attributes( $product );
    ksort($attributes);
    if ( empty( $attributes ) ) {
        return;
    }
    foreach ( $attributes as $key=>$attribute ) {

        if($attribute["slug"]=='pa_color'):
        ?>
        <div class="sub-description sub-description_color">
                <?php foreach ( $attribute['values'] as $key=>$value ) {
                    ?>
                    <span class="iconic-available-attributes__value <?php echo $value['available'] ? '' : 'iconic-available-attributes__value--unavailable'; ?>" style="background-color: #<?php echo esc_attr( $key ); ?>;"><?php echo esc_html( $value['name'] ); ?></span>
                <?php } ?>
        </div>
        <?php elseif ($attribute["slug"]=='pa_size'):
        ?>
        <div class='sub-description sub-description_size'>
            <?php
            $tag_list = Array();
            foreach ( $attribute['values'] as $value ) {
                $tag_list[] = esc_html( $value['name'] );
            }
            echo implode( ' / ', $tag_list );
            ?>
         </div>
       <?php else: ?>
        <div class="sub-description">
            <ul class="iconic-available-attributes__values ">
                <?php foreach ( $attribute['values'] as $key=>$value ) {
                    ?>
                    <li class="iconic-available-attributes__value <?php echo $value['available'] ? '' : 'iconic-available-attributes__value--unavailable'; ?>" ><?php echo esc_html( $value['name'] ); ?></li>
                <?php } ?>
            </ul>
        </div>
        <?php
        endif;
    }
}
function rs_get_available_attributes( $product ) {
	static $available_attributes = array();
	$product_id = $product->get_id();
	if ( isset( $available_attributes[ $product_id ] ) ) {
		return $available_attributes[ $product_id ];
	}
	$available_attributes[ $product_id ] = array();
	$attributes = $product->get_variation_attributes();
	if ( empty( $attributes ) ) {
		return $available_attributes[ $product_id ];
	}
	$attributes_to_show = rs_get_attributes_to_show();
	foreach ( $attributes as $attribute => $values ) {
		if ( ! in_array( $attribute, $attributes_to_show ) ) {
			continue;
		}
		$available_attribute = rs_get_available_attribute( $product, $attribute, $values );

		if ( empty( $available_attribute ) ) {
			continue;
		}
		$key = array_search($attribute, $attributes_to_show);
		$available_attributes[ $product_id ][$key] = $available_attribute;
	}
	return $available_attributes[ $product_id ];
}
// Which attributes to display
function rs_get_attributes_to_show() {
    return apply_filters( 'rs_get_attributes_to_show', array('pa_size','pa_color',) );
}
function rs_get_available_attribute( $product, $attribute, $values ) {
    $available_attribute = array(
        'slug' => $attribute,
    );
    if ( ! taxonomy_exists( $attribute ) ) {
        $available_attribute['name'] = $attribute;
        foreach ( $values as $value ) {
            $available_attribute['values'][ $value ] = array(
                'name'      => $value,
                'available' => rs_has_available_variation( $product, $attribute, $value ),
            );
        }
        return $available_attribute;
    }
    $taxonomy = get_taxonomy( $attribute );
    $labels   = get_taxonomy_labels( $taxonomy );
    $available_attribute['name']   = $labels->singular_name;
    $available_attribute['values'] = array();
    foreach ( $values as $value ) {
        $term = get_term_by( 'slug', $value, $attribute );
        if ( ! $term ) {
            continue;
        }
        $available_attribute['values'][ $value ] = array(
            'name'      => $term->name,
            'available' => rs_has_available_variation( $product, $attribute, $value ),
        );
    }
    return $available_attribute;
}
// Check if variation is available for purchase
function rs_has_available_variation( $product, $attribute, $value ) {
    $available_variation = false;
    $attribute           = 'attribute_' . sanitize_title( $attribute );
    $variations          = $product->get_available_variations();
    if ( empty( $variations ) ) {
        return $available_variation;
    }
    foreach ( $variations as $variation ) {
        foreach ( $variation['attributes'] as $variation_attribute_name => $variation_attribute_value ) {
            if ( $attribute !== $variation_attribute_name ) {
                continue;
            }
            if ( $value !== $variation_attribute_value && ! empty( $variation_attribute_value ) ) {
                continue;
            }
            $available_variation = $variation['is_purchasable'] && $variation['is_in_stock'];
            break;
        }
        if ( $available_variation ) {
            break;
        }
    }
    return $available_variation;
}

// AJAX mini-cart fragment filter
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	ob_start();
	storefront_header_cart_child();
	$fragments['#header-cart-content'] = ob_get_clean();
	return $fragments;
}

function remove_product(){
    $cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) : '';
    if ($cart_item_key) {
		WC()->cart->remove_cart_item( $cart_item_key );
    }
}

// Customize category title output
function rs_woocommerce_template_loop_category_title( $category ) {
	?>
	<div class="catalog-description">
		<?php
		echo '<h4>' . esc_html( $category->name );
		echo '</h4>';
		?>
	</div>
	<?php
}

// Filter products by sale status
add_action( 'woocommerce_product_query', 'so_20990199_product_query' );
function so_20990199_product_query( $q ){
	if (isset($_GET['onsale_filter']) && $_GET['onsale_filter'] == 1) {
	    $product_ids_on_sale = wc_get_product_ids_on_sale();
	    $q->set( 'post__in', $product_ids_on_sale );
	} else if (isset($_GET['onsale_filter']) && $_GET['onsale_filter'] == 0) {
	    $product_ids_on_sale = wc_get_product_ids_on_sale();
	    $q->set( 'post__not_in', $product_ids_on_sale );
	}
}
