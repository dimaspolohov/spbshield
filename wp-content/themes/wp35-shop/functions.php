<?php

// add_filter( 'woocommerce_product_backorders_allowed', '__return_false', 1000 );

add_filter( 'jpeg_quality', function( $quality ){
    return 100;
} );

add_action( 'init', 'create_size_guide' );
function create_size_guide() {
    register_post_type( 'size_guide',
        array(
            'labels' => array(
                'name' => 'Гид по размерам',
                'singular_name' => 'Гид по размерам',
                'add_new' => 'Добавить гид',
                'add_new_item' => 'Добавить гид',
                'edit' => 'Изменить гид',
                'edit_item' => 'Гид по размерам',
                'new_item' => 'Новый гид',
                'view' => 'Показать гид',
                'view_item' => 'Показать гид',
                'search_items' => 'Искать гид',
                'not_found' => 'Гид не найден',
                'not_found_in_trash' => 'Гид не найден в корзине',
                'parent' => 'Родительский гид',
            ),
            'taxonomies' => array(),
            'publicly_queryable'  => false,
            'public' => true,
            'menu_position' => 4,
            'supports' => array( 'title', 'revisions' ),
            'menu_icon' => 'dashicons-move',
            'has_archive' => false,
        )
    );
}

add_filter( 'acf/fields/post_object/result', 'update_acf_post_object_field_choices', 10, 999 );
function update_acf_post_object_field_choices($title, $post, $field, $post_id) {
	if($field['key']=='field_63aec22dbd07f' || $field['key']=='field_6618e558ba653'){
	    $terms=wc_get_product_terms( $post->ID, 'pa_color', array( 'fields' => 'names' ) );
		$colors = array_shift( $terms );
		if ( $colors && !empty($colors) ) $title .= ' [' . $colors .  ']';
	}
	return $title;	
}

add_filter( 'wc_add_to_cart_message_html', '__return_false', 999 );
add_filter( 'woocommerce_cart_item_removed_notice_type', '__return_false', 999 );
add_filter( 'woocommerce_checkout_show_messages', '__return_false', 999 );

add_action( 'wp_loaded', 'disable_wp_theme_update_loaded' );
function disable_wp_theme_update_loaded() {
    remove_action( 'load-update-core.php', 'wp_update_themes' );
    add_filter( 'pre_site_transient_update_themes', '__return_null' );
}

//add_action( 'init', 'create_post_type_media' );
function create_post_type_media() {
    register_post_type( 'media',
        array(
            'labels' => array(
                'name' => __( 'Media', 'storefront' ),
                'singular_name' => 'Медиа',
                'add_new' => 'Добавить медиа',
                'add_new_item' => 'Добавить медиа',
                'edit' => 'Изменить медиа',
                'edit_item' => 'Медиа',
                'new_item' => 'Новое коллекция',
                'view' => 'Показать медиа',
                'view_item' => 'Показать медиа',
                'search_items' => 'Искать медиа',
                'not_found' => 'Медиа не найдены',
                'not_found_in_trash' => 'Медиа не найдены в корзине',
                'parent' => 'Родительское медиа',
            ),
            'taxonomies' => array('post_tag'),
            'publicly_queryable'  => true,
            'public' => true,
            'menu_position' => 4,
            'supports' => array( 'title','revisions','thumbnail','editor' ),
            'menu_icon' => 'dashicons-screenoptions',
            'has_archive' => true,
        )
    );
}

add_action( 'init', 'create_post_type_news' );
function create_post_type_news() {
    register_post_type( 'news',
        array(
            'labels' => array(
                'name' => __( 'Новости', 'storefront' ),
                'singular_name' => 'Новости',
                'add_new' => 'Добавить новость',
                'add_new_item' => 'Добавить новость',
                'edit' => 'Изменить новость',
                'edit_item' => 'Новость',
                'new_item' => 'Новость',
                'view' => 'Показать новости',
                'view_item' => 'Показать новость',
                'search_items' => 'Искать новости',
                'not_found' => 'Новости не найдены',
                'not_found_in_trash' => 'Новости не найдены в корзине',
                'parent' => 'Родительская новость',
            ),
            'taxonomies' => array('post_tag'),
            'publicly_queryable'  => true,
            'public' => true,
            'menu_position' => 4,
            'supports' => array( 'title','revisions','thumbnail','editor' ),
            'menu_icon' => 'dashicons-screenoptions',
            'has_archive' => true,
        )
    );
}

add_action( 'init', 'create_post_type_collection' );
function create_post_type_collection() {
    register_post_type( 'collections',
        array(
            'labels' => array(
                'name' => __( 'Collections', 'storefront' ),
                'singular_name' => 'Коллекция',
                'add_new' => 'Добавить коллекцию',
                'add_new_item' => 'Добавить коллекцию',
                'edit' => 'Изменить коллекцию',
                'edit_item' => 'Коллекция',
                'new_item' => 'Новая коллекция',
                'view' => 'Показать коллекцию',
                'view_item' => 'Показать коллекцию',
                'search_items' => 'Искать коллекцию',
                'not_found' => 'Коллекции не найдены',
                'not_found_in_trash' => 'Коллекции не найдены в корзине',
                'parent' => 'Родительская коллекцию',
            ),
            'taxonomies' => array(),
            'publicly_queryable'  => true,
            'public' => true,
            'menu_position' => 4,
            'supports' => array( 'title','revisions','thumbnail','editor','excerpt','page-attributes' ),
            'menu_icon' => 'dashicons-screenoptions',
            'has_archive' => true,
        )
    );
}

add_action('wp_ajax_rs_clients_form_function', 'rs_clients_form_function');
add_action('wp_ajax_nopriv_rs_clients_form_function', 'rs_clients_form_function');
function rs_clients_form_function() {
    if (defined('DOING_AJAX') && DOING_AJAX) {
        $to = get_field('email_form',$_POST['page_id']);
        $subject = __('New message from','storefront').' «'.get_bloginfo( 'name' ).'»';
        $message = '<strong>'.__('Your name','storefront').':</strong> '.$_POST['name'].'<br>';
        $message .= '<strong>'.__('E-mail','storefront').':</strong> '.$_POST['email'].'<br>';
        if($_POST['message'] && $_POST['message']!='') $message .= '<br>'.$_POST['message'].'<br>';
        $message .= '<br><i>'.__('With great respect, <br>the administration of the site','storefront').' «'.get_bloginfo( 'name' ).'»</i>';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $headers[] = 'From: No Reply <noreply@'.$_SERVER['SERVER_NAME'].'>';
        wp_mail( $to, $subject, $message, $headers );
    }
    wp_die();
}

add_action('wp_ajax_file_upload', 'file_upload_callback');
add_action('wp_ajax_nopriv_file_upload', 'file_upload_callback');
function file_upload_callback() {
    $arr_img_ext = array('image/png', 'image/jpeg', 'image/jpg', 'image/gif');
    $files = [];
    for($i = 0; $i < count($_FILES['file']['name']); $i++) {
        if (in_array($_FILES['file']['type'][$i], $arr_img_ext)) {
            $upload = wp_upload_bits($_FILES['file']['name'][$i], null, file_get_contents($_FILES['file']['tmp_name'][$i]));
            $files[] = $upload['file'];
        }
    }
    echo implode('|', $files );
    wp_die();
}

add_filter( 'big_image_size_threshold', '__return_false' );

add_action('wp_ajax_rs_store_form_function', 'rs_store_form_function');
add_action('wp_ajax_nopriv_rs_store_form_function', 'rs_store_form_function');
function rs_store_form_function() {
    if (defined('DOING_AJAX') && DOING_AJAX) {
        if($_POST['attachment'] && $_POST['attachment']!='') {
            $uploadedFiles = explode('|',$_POST['attachment']);
            $uploadedFile = $uploadedFiles[0];
        }
        $to = get_field('email_form',$_POST['page_id']);
        $subject = __('New message from','storefront').' «'.get_bloginfo( 'name' ).'»';
        $message = '<strong>'.__('Your name','storefront').':</strong> '.$_POST['name'].'<br>';
        $message .= '<strong>'.__('E-mail','storefront').':</strong> '.$_POST['email'].'<br>';
        $message .= '<strong>'.__('Category','storefront').':</strong> '.$_POST['category'].'<br>';
        if($_POST['message'] && $_POST['message']!='') $message .= '<br>'.$_POST['message'].'<br>';
        $message .= '<br><i>'.__('With great respect, <br>the administration of the site','storefront').' «'.get_bloginfo( 'name' ).'»</i>';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $headers[] = 'From: No Reply <noreply@'.$_SERVER['SERVER_NAME'].'>';
        $file_not_exists = false;
        foreach($uploadedFiles as $uploadedFile) if(empty($uploadedFile) || !file_exists($uploadedFile)) $file_not_exists = true;
        if(!$file_not_exists) {
            wp_mail( $to, $subject, $message, $headers, $uploadedFiles );
            foreach($uploadedFiles as $uploadedFile) @unlink($uploadedFile);
        } else {
            wp_mail( $to, $subject, $message, $headers );
        }
    }
    wp_die();
}

add_action('wp_ajax_getProducts', 'getProductsFunc');
add_action('wp_ajax_nopriv_getProducts', 'getProductsFunc');
function getProductsFunc() {
    if (defined('DOING_AJAX') && DOING_AJAX) {
        wp_reset_postdata();
		$IDS  ='';
		$ORDER  ='';
        ob_start();
        $term_id = !empty($_POST['term_id'])? $_POST['term_id'] : 0;
        $offset = $_POST['offset'];
        $number = $_POST['number'];
        $orderby = $_POST['orderby'];
        $color = $_POST['color'];
        $size = $_POST['size'];
        $args = array(
            'numberposts' 	=> 49,
            'offset'	  	=> $offset,
            'post_type'   	=> 'product',
        );
        $relation = 0;
        $tax_query = [];
        if(intval($term_id)>0) {
            $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $term_id
            );
            $relation++;
        }
        if($color!='false') {
            $tax_query[] = array(
                'taxonomy' => 'pa_color',
                'field'    => 'term_id',
                'terms'    => explode('-',$color),
            );
            $relation++;
        }
        if($size!='false') {
            $tax_query[] = array(
                'taxonomy' => 'pa_size',
                'field'    => 'term_id',
                'terms'    => explode('-',$size),
            );
            $relation++;
        }
        if($relation>1) $tax_query['relation'] = 'AND';
        if($relation>0) $args['tax_query'] = $tax_query;
        $args['meta_query'] = array(
            array(
                'key'     => '_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'NUMERIC',
            ),
        );
        switch($orderby) {
            case 'popularity':
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
			break;
            case 'price':
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'ASC';
			break;
            case 'price-desc':
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
			break;
            case 'date':
                $args['meta_key'] = '';
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
			break;
            default:
            case 'menu_order':
				$args['numberposts'] = -1;
				$args['offset'] = 0;
                $args['meta_key'] = '';
                $args['orderby'] = 'menu_order';
                $args['order'] = 'DESC';
			break;
        }
		
		$ORDER = $args['orderby'];

        $my_posts = get_posts($args);
		if($orderby=='menu_order') {
            $my_posts = array_reverse($my_posts);
        };
        $offset_new = $ind = 0;
        if($my_posts) {
			if($orderby!='menu_order') $offset = 0;
            global $post;
            $count = count($my_posts);
            foreach( $my_posts as $post ){
				if($offset>0) {
					$offset--;
				} else {
					$offset_new++;
					setup_postdata( $post );
					global $product;
					$product = wc_setup_product_data( $post->ID );
					
					$skip = false;
						
					if($size!='false') {
						$productT = $product->get_id();
						$variations = $product->get_children();
						$skip = true;
						foreach($variations as $variation) {
							$product = wc_setup_product_data( $variation );
							$availability = $product->get_availability();
							if($availability['class']=='in-stock'){
								$attributes = $product->get_variation_attributes();

								if($attributes && $attributes['attribute_pa_size'] && $size!='false') {
									$termM = get_term_by( 'slug', $attributes['attribute_pa_size'], 'pa_size');

									if( in_array($termM->term_id,explode('-',$size)) && $product->stock_quantity ) {
										/* print_r($termM->slug); */
										$skip = false;
										if($IDS!='') $IDS .= ',';
										$IDS .= $productT;
									}
								}
							}
						}
						$product = wc_setup_product_data( $productT );
					} else {
						if($IDS!='') $IDS .= ',';
						$IDS .= $product->get_id();
					}
					
					if(!$skip) {
						if( $product->is_visible() ) $ind++; 
						wc_get_template_part( 'content', 'product_catalog' );
						if($ind==$number) {
							?>
							<div class="clear">
								<div class="loading-more">
									<div class="blob top"></div>
									<div class="blob bottom"></div>
									<div class="blob left"></div>
									<div class="blob move-blob"></div>
								</div>
							</div><?
							break;
						}
					}
				}
            }
            /*echo '<pre style="display:none;">';
            var_dump($IDS);
            var_dump($number,$ind);
            echo '</pre>';*/
            if(intval($IDS)==0 && intval($ind)==0) {

                ?><div class="not-found"><?_e('No products found for the specified parameters','storefront')?></div><?
            }
        } else {
            if(intval($offset)==0) {
                ?><div class="not-found"><?_e('No products found for the specified parameters','storefront')?></div><?
            }
        }
        $output1 = ob_get_contents();
        ob_end_clean();
        ob_start();
        $frontpage_id = get_option( 'page_on_front' );
        $shop_page_id = get_option( 'woocommerce_shop_page_id' );
        ?><li class="rs-breadcrumbs__item"><a href="<?=get_the_permalink($frontpage_id)?>" class="rs-breadcrumbs__link"><?=get_the_title($frontpage_id)?></a></li><? if(intval($term_id)>0) {?><li class="rs-breadcrumbs__item"><a href="<?=get_the_permalink($shop_page_id)?>" class="rs-breadcrumbs__link"><?=get_the_title($shop_page_id)?></a></li><li class="rs-breadcrumbs__item"><span class="rs-breadcrumbs__current"><?=get_term($term_id)->name?></span></li><? } else {?><li class="rs-breadcrumbs__item"><span class="rs-breadcrumbs__current"><?=get_the_title($shop_page_id)?></span></li><? }
        $output2 = ob_get_contents();
        ob_end_clean();
        echo json_encode([$output1,$output2,$offset_new,$IDS,$ORDER]);
    }
    wp_die();
}

add_action('wp_ajax_getCollections', 'getCollections');
add_action('wp_ajax_nopriv_getCollections', 'getCollections');
function getCollections() {
    if (defined('DOING_AJAX') && DOING_AJAX) {
        ob_start();
        $offset = $_POST['offset'];
        $number = intval($_POST['number'])+1;
        $args = array(
            'numberposts' => $number,
            'offset'	  => $offset,
            'post_type'   => 'collections',
            'orderby' 	  => 'menu_order',
            'order'		  => 'ASC',
        );
        wp_reset_postdata();
        $my_posts = get_posts($args);
        if($my_posts){
            $count = count($my_posts);
            for($index=0;$index<$count;$index++) {
                if($index<2) {
                    global $post;
                    $post = $my_posts[$index];
                    setup_postdata( $post );
                    ?>
                    <div class="rs-collections-archive__item">
                        <div class="rs-collections-archive__picture">
                            <picture>
                                <source srcset="<?=get_the_post_thumbnail_url( $post, 'full' )?>.webp" type="image/webp" />
                                <img src="<?=get_the_post_thumbnail_url( $post, 'full' )?>" alt="" draggable="false" />
                            </picture>
                            <picture>
                                <source srcset="<?=get_the_post_thumbnail_url( $post, 'full' )?>.webp" type="image/webp" />
                                <img src="<?=get_the_post_thumbnail_url( $post, 'full' )?>" alt="" draggable="false" />
                            </picture>
                        </div>
                        <div class="rs-collections-archive__description">
                            <a href="<? the_permalink()?>">
                                <h2 class="large-title"><? the_title()?></h2>
                                <h4 class="l-regular-title"><? the_excerpt()?></h4>
                            </a>
                            <a href="<? the_permalink()?>" class="rs-btn _border-btn _white-btn"><?_e('Collection','storefront')?></a>
                        </div>
                    </div>
                <? } else {
                    if($count==$number) {
                        ?><div class="loading-more">
                            <div class="blob top"></div>
                            <div class="blob bottom"></div>
                            <div class="blob left"></div>
                            <div class="blob move-blob"></div>
                        </div><?
                    }
                }
            }
            wp_reset_postdata();
        }
        $output1 = ob_get_contents();
        ob_end_clean();
        echo json_encode([$output1]);
    }
    wp_die();
}

add_action('wp_ajax_getMediaitems', 'getMediaitems');
add_action('wp_ajax_nopriv_getMediaitems', 'getMediaitems');
function getMediaitems() {
    if (defined('DOING_AJAX') && DOING_AJAX) {
        ob_start();
        wp_reset_postdata();
        $offset =  isset($_POST['offset']) ?(int)$_POST['offset']:3;
        $number = intval($_POST['number']);
        if(!$offset||$offset==3) $number = 3;
        $args = array(
            'numberposts' => $number,
            'offset'	  => $offset,
            'post_type'   => 'news',
            'orderby' 	  => 'date',
            'order'		  => 'DESC',
            'tax_query' => [
                'relation' => 'OR',
                [
                    'taxonomy' => 'post_tag',
                    'field'    => 'slug',
                    'terms'    => array( 'video', 'novost' ),
                ],
            ]
        );

        $my_posts = get_posts($args);
        if($my_posts){
            $count = count($my_posts); ?>
                <div class="rs-media-news__list">
                <?
            for($index=0;$index<$count;$index++) {
                    global $post;
                    $post = $my_posts[$index];
                    setup_postdata( $post );
                    $post_id=$post->ID;
                    $post_thumbnail=get_the_post_thumbnail_url( $post, 'full' )?get_the_post_thumbnail_url( $post, 'full' ):null;
                    $img=get_field('cat_img')?get_field('cat_img'):$post_thumbnail;
                    $posttags=get_the_tags();
                    ?>
                    <div class="rs-media-news__item">
                        <a href="<?php the_permalink(); ?>">
                            <div class="rs-media-news__img">
                                <?if($img):?>
                                    <picture>
                                        <source srcset="<?=$img?>.webp" type="image/webp">
                                        <img src="<?=$img?>" alt="">
                                    </picture>
                                <?endif;?>
                            </div>
                            <div class="rs-media-news__description">
                                <h4 class="sm-bold-title"><?php the_title(); ?></h4>
                            </div>
                        </a>
                    </div>
					<? } ?>
                </div>
            <?php
            if($count==$number) {
                ?><div class="loading-more">
                    <div class="blob top"></div>
                    <div class="blob bottom"></div>
                    <div class="blob left"></div>
                    <div class="blob move-blob"></div>
                </div><?
            }
            ?>
            <?wp_reset_postdata();
        }
        $output1 = ob_get_contents();
        ob_end_clean();
        echo json_encode([$output1]);
    }
    wp_die();
}

add_action('wp_ajax_getVariationGal', 'getVariationGal');
add_action('wp_ajax_nopriv_getVariationGal', 'getVariationGal');
function getVariationGal() {
    if (defined('DOING_AJAX') && DOING_AJAX) {
        $product_id = $_POST['variation_id'];
        $product = wc_get_product($product_id);
        $output = [];
        
        ob_start();
        ?>
        <div class="rs-product__pictures">
            <div class="rs-thumbs__slider swiper">
                <div class="rs-thumbs__swiper swiper-wrapper">
                    <?php 
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $_POST['product_id'] ), 'single-post-thumbnail' );
                    if($image) { ?>
                        <div class="rs-thumbs__slide swiper-slide">
                            <picture>
                                <source srcset="<?php  echo $image[0]; ?>.webp" type="image/webp">
                                <img src="<?php  echo $image[0]; ?>" alt="">
                            </picture>
                        </div>
                    <? }
                    $product_variation = woo_variation_gallery()->get_frontend()->get_available_variation( $_POST['product_id'], $_POST['variation_id'] );
                    if ( isset( $product_variation['variation_gallery_images'] ) ) {
                        $attachment_ids = wp_list_pluck( $product_variation['variation_gallery_images'], 'image_id' );
                        array_shift( $attachment_ids );
                    }
                    if($attachment_ids) {
                        foreach( $attachment_ids as $attachment_id ) { ?>
                            <div class="rs-thumbs__slide swiper-slide">
                                <picture>
                                    <source srcset="<? echo $image_link = wp_get_attachment_image_src( $attachment_id, 'single-post-thumbnail' )[0] ?>.webp" type="image/webp">
                                    <img src="<?=$image_link?>" alt="">
                                </picture>
                            </div>
                        <? }
                    } ?>
                </div>
            </div>
            <div class="rs-product__slider swiper" data-gallery>
                <div class="rs-product__swiper swiper-wrapper">
                    <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $_POST['product_id'] ), 'full' );
                    if($image) { ?>
                        <div class="rs-product__slide swiper-slide" data-gallery-item data-src="<?=$image[0]?>">
                            <picture>
                                <source srcset="<?=$image[0]?>.webp" type="image/webp">
                                <img src="<?=$image[0]?>" alt="">
                            </picture>
                        </div>
                    <? }
                    $product_variation = woo_variation_gallery()->get_frontend()->get_available_variation( $_POST['product_id'], $_POST['variation_id'] );
                    if ( isset( $product_variation['variation_gallery_images'] ) ) {
                        $attachment_ids = wp_list_pluck( $product_variation['variation_gallery_images'], 'image_id' );
                        array_shift( $attachment_ids );
                    }
                    if($attachment_ids) {
                        foreach( $attachment_ids as $attachment_id ) { ?>
                            <div class="rs-product__slide swiper-slide" data-gallery-item data-src="<? echo $image_link = wp_get_attachment_image_src( $attachment_id, 'full' )[0]; ?>">
                                <picture>
                                    <source srcset="<? echo $image_link = wp_get_attachment_url( $attachment_id ); ?>.webp" type="image/webp">
                                    <img src="<?=$image_link?>" alt="">
                                </picture>
                            </div>
                        <? }
                    } ?>
                </div>
                <div class="rs-product__pagination swiper-pagination"></div>
            </div>
        </div>
        <?
        $output[] = ob_get_contents();
        ob_end_clean();
        ob_start();
        echo $product->get_price_html();
        $output[] = ob_get_contents();
        ob_end_clean();
        echo json_encode($output);
    }
    wp_die();
}



add_action('wp_ajax_setWishlist', 'setWishlist');
add_action('wp_ajax_nopriv_setWishlist', 'setWishlist');
function setWishlist(){
    $output = [];
    ob_start();
    ?>
    <a href="<?=YITH_WCWL()->get_wishlist_url('view')?>" class="icon-heart"><?php $count = YITH_WCWL()->count_all_products(); if($count>0) { ?><span><?=$count?></span><? } ?></a>
    <?
    $output[] = ob_get_contents();
    ob_end_clean();
    echo json_encode($output);
    wp_die();
}

add_action( 'wp', 'set_user_visited_product_cookie' );
function set_user_visited_product_cookie() {
    global $post;
    if ( is_product() ){
        $recently = isset($_COOKIE['woocommerce_recently_viewed'])?$_COOKIE['woocommerce_recently_viewed']:false;
        if(!$recently) $recently = '';
        $arr_ = explode(',',$recently);
        $arr_ = array_diff($arr_, array(''));
        $arr_[] = $post->ID;
        $arr_ = array_unique($arr_);
        if (!empty($arr_) && is_array($arr_)) {
            array_splice($arr_, 7);
        }
        wc_setcookie( 'woocommerce_recently_viewed', implode(',',$arr_) );
    }
}

add_filter( 'site_transient_update_plugins', 'rosait_disable_plugin_updates' );
function rosait_disable_plugin_updates( $value ) {
    $pluginsNotUpdatable = [
        'rosait-woocommerce-wishlist/init.php',
        'rosait-woo-variation-gallery/woo-variation-gallery.php'
    ];
    if ( isset($value) && is_object($value) ) {
        foreach ($pluginsNotUpdatable as $plugin) {
            if ( isset( $value->response[$plugin] ) ) {
                unset( $value->response[$plugin] );
            }
        }
    }
    return $value;
}

function rs_filters_orderby( $value, $order ) {
    if($value==$order) echo 'checked="checked"';
}

function size_guide() {
    global $product;
    if(!empty(get_field( 'size_guide', $product->get_id() ))){
        ?><a class="eModal-1"><?_e('Size guide','storefront')?></a><?
    }
}

add_shortcode( 'size_guide_content', 'size_guide_content_func' );
function size_guide_content_func() {
    if( is_shop() || is_product_category() || is_product_tag() ) {
        $page_id = get_option( 'woocommerce_shop_page_id' );
        return get_the_content('','',$page_id);
    } else {
		if( is_404() ){
			return;
		} else {
			global $product;
			if(isset($product)) {
				return get_field( 'size_guide', $product->get_id() );
			} else {
				return;
			}
		}
    }
}

function wishlist_icon() {
    echo do_shortcode('[yith_wcwl_add_to_wishlist]');
}

function free_delivery() {
    $frontpage_id = get_option('page_on_front');
    ?><div class="rs-product__delivery icon-truck"><?=get_field('product_free_delivery',$frontpage_id)?></div><?
}

add_filter( 'woocommerce_get_price_html', 'dw_change_default_price_html', 999, 2 );
function dw_change_default_price_html( $price, $product ){
     if ( $product->is_type('simple') ) {
        $action_price = $product->get_price();
        $base_price = $product->get_sale_price();
    } else if ( $product->is_type('variable') ) {
        $action_price = $product->get_variation_price();
        $base_price = $product->get_variation_regular_price();
    }

    if ( $action_price != $base_price ) {
        $price = '<div class="rs-product__price rs-product__price-old">' . wc_price( $base_price ) . '</div>';
        $price .= '<div class="rs-product__price rs-product__price-new">' . wc_price( $action_price ) . '</div>';
    } else {
        $price = '<div class="rs-product__price rs-product__price-new">' . $price . '</div>';
    }

    return $price;
}

new ACF_Page_Type_Polylang();
class ACF_Page_Type_Polylang {
    // Whether we hooked page_on_front
    private $filtered = false;
    public function __construct() {
        add_filter( 'acf/location/rule_match/page_type', array( $this, 'hook_page_on_front' ) );
    }
    public function hook_page_on_front( $match ) {
        if ( ! $this->filtered ) {
            add_filter( 'option_page_on_front', array( $this, 'translate_page_on_front' ) );
            // Prevent second hooking
            $this->filtered = true;
        }
        return $match;
    }
    public function translate_page_on_front( $value ) {
        if ( function_exists( 'pll_get_post' ) ) {
            $value = pll_get_post( $value );
        }
        return $value;
    }
}

add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
    // show_admin_bar(false);
}
add_filter( 'jpeg_quality', function( $quality ){
    return 100;
} );
add_image_size( 'single-post-thumbnail', 98, 98, true );
add_image_size( 'img-about', 638, 638, false );
add_image_size( 'img-representatives', 431, 433, true );
add_image_size( 'img-product__slide', 431, 503, false );
add_image_size( 'img-banner', 375, 301, true );
add_image_size( 'img-mobile', 375, 750, true );
add_image_size( 'img-horizontal', 898, 360, true );
add_image_size( 'img-rs-collection', 960, 933, true );
add_image_size( 'img-rs-media', 898, 898, true );
add_image_size( 'img-rs-inst', 638, 638, true );

add_action('customize_register', 'logotypes_customize_register');
function logotypes_customize_register($wp_customize){
    $wp_customize->add_setting('text_logo');
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'text_logo', array(
        'label'    => __('Text Logo', 'storefront'),
        'section'  => 'title_tagline',
        'settings' => 'text_logo',
        'priority' => 8,
    )));
    $wp_customize->add_setting('footer_logo');
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'footer_logo', array(
        'label'    => __('Footer Logo', 'storefront'),
        'section'  => 'title_tagline',
        'settings' => 'footer_logo',
        'priority' => 9,
    )));
}

include("spacefix.php");//для карты сайта
//Disable Pingback
function wpschool_remove_pingback_header( $headers ) {
    unset( $headers['X-Pingback'] );
    return $headers;
}
function wpschool_remove_x_pingback_headers( $headers ) {
    if ( function_exists( 'header_remove' ) ) {
        header_remove( 'X-Pingback' );
        header_remove( 'Server' );
    }
}
function wpschool_block_xmlrpc_attacks( $methods ) {
    unset( $methods['pingback.ping'] );
    unset( $methods['pingback.extensions.getPingbacks'] );
    return $methods;
}
add_filter( 'wp_headers', 'wpschool_remove_pingback_header' );
add_filter( 'template_redirect', 'wpschool_remove_x_pingback_headers' );
add_filter( 'xmlrpc_methods', 'wpschool_block_xmlrpc_attacks' );
add_filter( 'xmlrpc_enabled','__return_false' );

//Disable XML-RPC RSD link from WordPress Header
remove_action ('wp_head', 'rsd_link');

//Remove WordPress version number
add_filter ( 'the_generator' ,  '__return_false' );

//Remove wlwmanifest link
remove_action( 'wp_head', 'wlwmanifest_link');

//Remove shortlink
remove_action( 'wp_head', 'wp_shortlink_wp_head');

//Remove api.w.org relation link
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
remove_action('template_redirect', 'rest_output_link_header', 11, 0);

// отключение типовых скриптов Woo и Storefront
add_action( 'wp_print_scripts', 'rs_dequeue_script', 100 );
function rs_dequeue_script() {
    if ( function_exists( 'is_woocommerce' ) ) {
        wp_dequeue_script( 'storefront-header-cart' );
        wp_dequeue_style( 'storefront-icons' );
    }
    wp_dequeue_style('yandex-money-checkout');
}

add_action( 'wp_print_styles', 'rs_dequeue_styles', 100 );
function rs_dequeue_styles() {
    if(!is_admin()) {
        wp_dequeue_style('storefront-style');
        wp_deregister_style('storefront-style');
        wp_enqueue_style('storefront-style', get_stylesheet_directory_uri() . '/assets/css/woocommerce.css');
        wp_dequeue_style('storefront-style-inline');
        wp_deregister_style('storefront-woocommerce');
        wp_deregister_style('storefront-woocommerce-inline');
        wp_dequeue_style('storefront-woocommerce-style-inline');
    }
}
if(!is_admin()) {
    add_filter('storefront_customizer_woocommerce_css', '__return_false');
    add_filter('storefront_customizer_css', '__return_false');
// Disable Gutenberg editor.
    add_filter('use_block_editor_for_post_type', '__return_false', 10);
}
// Don't load Gutenberg-related stylesheets.
add_action( 'wp_enqueue_scripts', 'remove_block_css', 100 );
function remove_block_css() {
    if(!is_admin()) {
        wp_dequeue_style('wp-block-library'); // Wordpress core
        wp_dequeue_style('wp-block-library-theme'); // Wordpress core
        wp_dequeue_style('wc-block-style'); // WooCommerce
        wp_dequeue_style('storefront-gutenberg-blocks'); // Storefront theme
        wp_dequeue_style('storefront-gutenberg-blocks-inline');
        wp_dequeue_style('woocommerce-inline');
        wp_dequeue_style('storefront-fonts');
        wp_dequeue_style('storefront-icons');
        wp_dequeue_style('storefront-style');
        wp_dequeue_style('storefront-woocommerce');
        wp_dequeue_style('storefront-woocommerce-inline');
        wp_dequeue_style('storefront-woocommerce-style-inline');
    }
}

// Подключение глобальных стилей
add_action( 'wp_enqueue_scripts', 'style_theme');
function style_theme() {
    wp_enqueue_style( 'owl-carousel', get_stylesheet_directory_uri().'/assets/css/owl.carousel.min.css');

    wp_enqueue_style( 'bootstrap-min', get_stylesheet_directory_uri().'/assets/css/bootstrap.min.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/bootstrap.min.css') );
   wp_enqueue_style( 'swiper-bundle', get_stylesheet_directory_uri().'/assets/css/swiper-bundle.min.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/swiper-bundle.min.css') );
    wp_enqueue_style( 'lightgallery-bundle', get_stylesheet_directory_uri().'/assets/css/lightgallery-bundle.min.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/lightgallery-bundle.min.css') );
    wp_enqueue_style( 'lg-zoom', get_stylesheet_directory_uri().'/assets/css/lg-zoom.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/lg-zoom.css') );
    wp_enqueue_style( 'aos', get_stylesheet_directory_uri().'/assets/css/aos.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/aos.css') );
    wp_enqueue_style( 'rs-style', get_stylesheet_directory_uri().'/assets/css/rs-style.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-style.css') );
    wp_enqueue_style( 'rs-common-style', get_stylesheet_directory_uri().'/assets/css/rs-common-style.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-common-style.css') );
    wp_enqueue_style( 'rs-header', get_stylesheet_directory_uri().'/assets/css/rs-header.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-header.css') );
    wp_enqueue_style( 'rs-footer', get_stylesheet_directory_uri().'/assets/css/rs-footer.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-footer.css') );

    if (get_field("on_slider") )
        wp_enqueue_style( 'rs-slider', get_stylesheet_directory_uri().'/assets/css/rs-slider.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-slider.css') );

    wp_enqueue_style( 'rs-new-product', get_stylesheet_directory_uri().'/assets/css/rs-new-product.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-new-product.css') );
    wp_enqueue_style( 'rs-collection', get_stylesheet_directory_uri().'/assets/css/rs-collection.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-collection.css') );
    wp_enqueue_style( 'rs-popular-product', get_stylesheet_directory_uri().'/assets/css/rs-popular-product.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-popular-product.css') );
    wp_enqueue_style( 'rs-media', get_stylesheet_directory_uri().'/assets/css/rs-media.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-media.css') );
    wp_enqueue_style( 'rs-inst', get_stylesheet_directory_uri().'/assets/css/rs-inst.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-inst.css') );
    wp_enqueue_style( 'rs-banner', get_stylesheet_directory_uri().'/assets/css/rs-banner.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-banner.css') );
    wp_enqueue_style( 'rs-about-us', get_stylesheet_directory_uri().'/assets/css/rs-about-us.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-about-us.css') );
    wp_enqueue_style( 'rs-representatives', get_stylesheet_directory_uri().'/assets/css/rs-representatives.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-representatives.css') );
    if( is_product() || is_shop() || is_product_category() || is_product_tag() || is_search() ) {
        wp_enqueue_style( 'rs-breadcrumbs', get_stylesheet_directory_uri().'/assets/css/rs-breadcrumbs.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-breadcrumbs.css') );
    }
    if( is_product() ) {
        wp_enqueue_style( 'rs-product', get_stylesheet_directory_uri().'/assets/css/rs-product.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-product.css') );
        wp_enqueue_style( 'rs-buy-product', get_stylesheet_directory_uri().'/assets/css/rs-buy-product.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-buy-product.css') );
        wp_enqueue_style( 'rs-recent-product', get_stylesheet_directory_uri().'/assets/css/rs-recent-product.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-recent-product.css') );
    }
    if( is_shop() || is_product_category() || is_product_tag() || is_search() || get_page_template_slug()=='template-wishlist.php' ) {
        wp_enqueue_style( 'rs-catalog', get_stylesheet_directory_uri().'/assets/css/rs-catalog.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-catalog.css') );
        wp_enqueue_style( 'rs-filters', get_stylesheet_directory_uri().'/assets/css/rs-filters.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-filters.css') );
    }
    if( is_cart() || is_checkout()) {
        wp_enqueue_style( 'intlTelInput', 'https://cdn.jsdelivr.net/npm/intl-tel-input@25.2.1/build/css/intlTelInput.css');
     }
    if( get_page_template_slug()=='template-clients.php' ) {
        wp_enqueue_style( 'rs-client-info', get_stylesheet_directory_uri().'/assets/css/rs-client-info.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-client-info.css') );
        wp_enqueue_style( 'rs-form', get_stylesheet_directory_uri().'/assets/css/rs-form.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-form.css') );
    }
    if( get_page_template_slug()=='template-store.php' ) {
        wp_enqueue_style( 'rs-store', get_stylesheet_directory_uri().'/assets/css/rs-store.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-store.css'));
        wp_enqueue_style( 'rs-tour', get_stylesheet_directory_uri().'/assets/css/rs-tour.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-tour.css'));
        wp_enqueue_style( 'rs-form', get_stylesheet_directory_uri().'/assets/css/rs-form.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-form.css') );
    }
    if( get_page_template_slug()=='template-woostyle.php' ) {
        wp_enqueue_style( 'rs-woo-addition', WP_CONTENT_URL . '/themes/storefront/assets/css/woocommerce/woocommerce.css');
        wp_enqueue_style( 'icons', get_stylesheet_directory_uri().'/assets/css/icons.css'); //Font Awesome Free 5.0.9
        wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri().'/assets/css/font-awesome.min.css'); // Font Awesome 4.7.0
        wp_enqueue_style( 'main', get_stylesheet_directory_uri().'/assets/css/style-main.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/style-main.css' ));
        wp_enqueue_style( 'rs-cart', get_stylesheet_directory_uri().'/woocommerce/css/rs-cart.css', array(), filemtime( get_stylesheet_directory() . '/woocommerce/css/rs-cart.css' ));
    }
    if(is_singular( 'news' )) {
        wp_enqueue_style( 'nekoanim', get_stylesheet_directory_uri().'/assets/css/nekoanim.css');
        wp_enqueue_style( 'animate', get_stylesheet_directory_uri().'/assets/css/animate.min.css');
        wp_enqueue_style( 'rs-photogallery', get_stylesheet_directory_uri().'/assets/css/rs-photogallery.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-photogallery.css' ));
    }
    if( is_post_type_archive( 'news' ) ) {
        wp_enqueue_style( 'rs-banner-video', get_stylesheet_directory_uri().'/assets/css/rs-banner-video.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-banner-video.css' ));
        wp_enqueue_style( 'rs-media-news', get_stylesheet_directory_uri().'/assets/css/rs-media-news.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-media-news.css' ));
        wp_enqueue_style( 'rs-podcast', get_stylesheet_directory_uri().'/assets/css/rs-podcast.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-podcast.css' ));
    }
    if( is_post_type_archive( 'collections' ) ) {
        wp_enqueue_style( 'rs-collections-archive', get_stylesheet_directory_uri().'/assets/css/rs-collections-archive.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/rs-collections-archive.css') );
    }
    wp_dequeue_style( 'storefront-child-style' );
    wp_deregister_style( 'storefront-child-style' );
    wp_register_style( 'storefront-child-style', get_stylesheet_directory_uri() . '/style.css', array(), filemtime( get_stylesheet_directory() . '/style.css' ) );
    wp_enqueue_style( 'storefront-child-style' );
}

// Подключение глобальных скриптов
add_action( 'wp_footer', 'scripts_child_theme', 10);
function scripts_child_theme() {
    // отключение типовых скриптов Woo
    if ( function_exists( 'is_woocommerce' ) ) {
        if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
            wp_dequeue_script( 'storefront-header-cart' );
            wp_dequeue_style( 'woocommerce_frontend_styles' );
            wp_dequeue_style( 'woocommerce_fancybox_styles' );
            wp_dequeue_style( 'woocommerce_chosen_styles' );
            wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
            //wp_dequeue_script( 'wc_price_slider' );
            //wp_dequeue_script( 'wc-single-product' );
            //wp_dequeue_script( 'wc-add-to-cart' );
            //wp_dequeue_script( 'wc-cart-fragments' );
            //wp_dequeue_script( 'wc-checkout' );
            //wp_dequeue_script( 'wc-add-to-cart-variation' );
            //wp_dequeue_script( 'wc-single-product' );
            //wp_dequeue_script( 'wc-cart' );
            //wp_dequeue_script( 'wc-chosen' );
            //wp_dequeue_script( 'woocommerce' );
            wp_dequeue_script( 'prettyPhoto' );
            wp_dequeue_script( 'prettyPhoto-init' );
            wp_dequeue_script( 'jquery-blockui' );
            wp_dequeue_script( 'jquery-placeholder' );
            wp_dequeue_script( 'fancybox' );
            wp_dequeue_script( 'jqueryui' );
        }
    }

    wp_enqueue_script( 'jquery-cookie', get_stylesheet_directory_uri() . '/assets/js/jquery.cookie.min.js');
    wp_enqueue_script( 'bootstrap-min', get_stylesheet_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery-core'));
    wp_enqueue_script( 'jquery-mCustomScrollbar', get_stylesheet_directory_uri() . '/assets/js/jquery.mCustomScrollbar.concat.min.js', array('bootstrap-min'));
    wp_enqueue_script( 'owl-carousel', get_stylesheet_directory_uri() . '/assets/js/owl.carousel.min.js', array('bootstrap-min'));
    wp_enqueue_script( 'mousewheel', get_stylesheet_directory_uri() . '/assets/js/jquery.mousewheel.min.js', array('bootstrap-min'));
    wp_enqueue_script( 'jquery-easing', get_stylesheet_directory_uri() . '/assets/js/jquery.easing.1.3.js', array('bootstrap-min'));
    wp_enqueue_script( 'slider-slick', get_stylesheet_directory_uri() . '/assets/js/slick.min.js', array('bootstrap-min'));
    wp_enqueue_script( 'jquery-appear', get_stylesheet_directory_uri() . '/assets/js/jquery.appear.js', array('bootstrap-min'));
    wp_enqueue_script( 'jquery-validate', get_stylesheet_directory_uri() . '/assets/js/jquery.validate.min.js', array('bootstrap-min'));
    wp_enqueue_script( 'jquery-counterup', get_stylesheet_directory_uri() . '/assets/js/jquery.counterup.min.js', array('bootstrap-min'));
    wp_enqueue_script( 'jquery-waypoints', get_stylesheet_directory_uri() . '/assets/js/jquery.waypoints.min.js', array('bootstrap-min'));

    if( is_cart() || is_checkout()) {

        wp_enqueue_style( 'intlTelInput', 'https://cdn.jsdelivr.net/npm/intl-tel-input@25.2.1/build/css/intlTelInput.css');
        wp_enqueue_script('intlTelInput','https://cdn.jsdelivr.net/npm/intl-tel-input@25.2.1/build/js/intlTelInput.min.js',[],'');
        wp_enqueue_script('inputmask','https://cdn.jsdelivr.net/npm/inputmask@5.0.9/dist/jquery.inputmask.min.js',[],'');


    } else {
        wp_enqueue_script( 'jquery-maskedinput', get_stylesheet_directory_uri() . '/assets/js/jquery.maskedinput.min.js', array('bootstrap-min'));
    }

    wp_enqueue_script( 'autocomplete-javascript', get_stylesheet_directory_uri() . '/assets/js/jquery.autocomplete.js', '',filemtime( get_stylesheet_directory() . '/assets/js/jquery.autocomplete.js'),array('jquery-core'));
    wp_enqueue_script( 'main-javascript', get_stylesheet_directory_uri() . '/assets/js/main.js', '',filemtime( get_stylesheet_directory() . '/assets/js/main.js'),array('jquery-core','intlTelInput','inputmask'));
    /**/
    wp_enqueue_script( 'swiper-bundle-js', get_stylesheet_directory_uri() . '/assets/js/swiper-bundle.min.js','','',true);
    wp_enqueue_script( 'lightgallery-js', get_stylesheet_directory_uri() . '/assets/js/lightgallery.min.js','','',true);
    wp_enqueue_script( 'lg-zoom-js', get_stylesheet_directory_uri() . '/assets/js/lg-zoom.umd.js','','',true);
    wp_enqueue_script( 'aos-js', get_stylesheet_directory_uri() . '/assets/js/aos.js','','',true);
    wp_enqueue_script( 'dynamic_adapt-js', get_stylesheet_directory_uri() . '/assets/js/dynamic_adapt.js','','',true);
    wp_enqueue_script( 'rs-script-js', get_stylesheet_directory_uri() . '/assets/js/rs-script.js','',filemtime( get_stylesheet_directory() . '/assets/js/rs-script.js'),true);
    wp_enqueue_script( 'rs-header-js', get_stylesheet_directory_uri() . '/assets/js/rs-header.js','',filemtime( get_stylesheet_directory() . '/assets/js/rs-header.js'),false);
    if (get_field("on_slider"))
        wp_enqueue_script( 'rs-slider-js', get_stylesheet_directory_uri() . '/assets/js/rs-slider.js','','',false);
    wp_enqueue_script( 'rs-new-product-js', get_stylesheet_directory_uri() . '/assets/js/rs-new-product.js','','',false);
    wp_enqueue_script( 'rs-popular-product-js', get_stylesheet_directory_uri() . '/assets/js/rs-popular-product.js','','',false);
    wp_enqueue_script( 'rs-media-js', get_stylesheet_directory_uri() . '/assets/js/rs-media.js','',filemtime( get_stylesheet_directory() . '/assets/js/rs-media.js'),false);
    wp_enqueue_script( 'rs-inst-js', get_stylesheet_directory_uri() . '/assets/js/rs-inst.js','','',false);
    wp_enqueue_script( 'rs-about-us-js', get_stylesheet_directory_uri() . '/assets/js/rs-about-us.js','','',false);
    wp_enqueue_script( 'rs-representatives-js', get_stylesheet_directory_uri() . '/assets/js/rs-representatives.js','','',false);

    if( is_product() ) {
        wp_enqueue_script( 'rs-availability-checker-js', get_stylesheet_directory_uri() . '/assets/js/rs-availability-checker.js','',filemtime( get_stylesheet_directory() . '/assets/js/rs-availability-checker.js'),false);
        wp_enqueue_script( 'rs-product-js', get_stylesheet_directory_uri() . '/assets/js/rs-product.js','',filemtime( get_stylesheet_directory() . '/assets/js/rs-product.js'),false);
        wp_enqueue_script( 'spollers-js', get_stylesheet_directory_uri() . '/assets/js/spollers.js','','',false);
        wp_enqueue_script( 'rs-buy-product-js', get_stylesheet_directory_uri() . '/assets/js/rs-buy-product.js','',filemtime( get_stylesheet_directory() . '/assets/js/rs-buy-product.js' ),false);
        wp_enqueue_script( 'rs-recent-product-js', get_stylesheet_directory_uri() . '/assets/js/rs-recent-product.js','','',false);
    }
    wp_enqueue_script( 'product-js', get_stylesheet_directory_uri() . '/assets/js/product.js','',filemtime( get_stylesheet_directory() . '/assets/js/product.js'),false);

    if( is_shop() || is_product_category() || is_product_tag() ) {
        wp_enqueue_script( 'spollers-js', get_stylesheet_directory_uri() . '/assets/js/spollers.js','','',false);
        wp_enqueue_script( 'rs-catalog', get_stylesheet_directory_uri() . '/assets/js/rs-catalog.js','',filemtime( get_stylesheet_directory() . '/assets/js/rs-catalog.js'),false);
    }
    if( get_page_template_slug()=='template-clients.php' ) {
        wp_enqueue_script( 'tabs-js', get_stylesheet_directory_uri() . '/assets/js/tabs.js','',filemtime( get_stylesheet_directory() . '/assets/js/tabs.js'),false);
        wp_enqueue_script( 'rs-clients-info-js', get_stylesheet_directory_uri() . '/assets/js/rs-clients-info.js','',filemtime( get_stylesheet_directory() . '/assets/js/rs-clients-info.js'),false);
    }
    if( get_page_template_slug()=='template-store.php' ) {
        //wp_enqueue_script( 'store-js', get_stylesheet_directory_uri() . '/assets/js/store.js','',filemtime( get_stylesheet_directory() . '/assets/js/store.js'),false);
        wp_enqueue_script( 'rs-map-js', get_stylesheet_directory_uri() . '/assets/js/rs-map.js','',filemtime( get_stylesheet_directory() . '/assets/js/rs-map.js'),false);
    }
    if( is_post_type_archive( 'collections' ) ) {
        wp_enqueue_script( 'rs-collections-archive-js', get_stylesheet_directory_uri() . '/assets/js/rs-collections-archive.js','',filemtime( get_stylesheet_directory() . '/assets/js/rs-collections-archive.js'),false);
    }
    if( is_post_type_archive( 'news' ) ) {
        wp_enqueue_script( 'rs-banner-video-js', get_stylesheet_directory_uri() . '/assets/js/rs-banner-video.js','',filemtime( get_stylesheet_directory() . '/assets/js/rs-banner-video.js'),false);
        wp_enqueue_script( 'rs-podcast-js', get_stylesheet_directory_uri() . '/assets/js/rs-podcast.js','',filemtime( get_stylesheet_directory() . '/assets/js/rs-podcast.js'),false);
        wp_enqueue_script( 'rs-media-new-js', get_stylesheet_directory_uri() . '/assets/js/rs-media.js','',filemtime( get_stylesheet_directory() . '/assets/js/rs-media.js'),false);
    }
}

function rs_add_defer_attribute( $tag, $handle ) {
    $handles = array(
        'jquery321min',
        'jquery-cookie',
        'bootstrap-min',
        'jquery-mCustomScrollbar',
        'owl-carousel',
        'mousewheel',
        'jquery-easing',
        'slider-slick',
        'jquery-appear',
        'jquery-validate',
        'jquery-maskedinput',
        'jquery-fancybox',
        'main-javascript',
    );
    foreach( $handles as $defer_script) {
        if ( $defer_script === $handle ) {
            return str_replace( ' src', ' defer="defer" src', $tag );
        }
    }
    return $tag;
}
//add_filter( 'script_loader_tag', 'rs_add_defer_attribute', 10, 2 );

if(!is_admin()){
    remove_action('wp_head', 'wp_print_scripts');
    add_action('wp_footer', 'wp_print_scripts', 5);
}

//add_action('wp_loaded', 'output_buffer_start');
function output_buffer_start() {
    ob_start("output_callback");
}

//add_action('shutdown', 'output_buffer_end');
function output_buffer_end() {
    ob_end_flush();
}

function output_callback($tag) {
    return preg_replace( "%[ ]type=[\'\"]text\/(javascript|css)[\'\"]%", '', $tag);
}

// Подключение обработчика отправки форм
require 'inc/common.php';

// Подключение библиотеки обработки миниатюр
//require 'inc/BFI_Thumb.php';

// Подключение обработчика дополнительных типов записей
require 'inc/post-types.php';

// Подключение сервисных функций 
require 'inc/services-functions.php';

// Подключение функций Woo дочерней темы
if ( function_exists( 'is_woocommerce' ) ) {
    require 'woocommerce/wc-functions.php';
}

// Подключение функционала для хедера
require 'template-parts/rs-header/rs-header-functions.php';

// Подключение функционала для текстовых блоков
require 'template-parts/rs-text-block/rs-text-block-functions.php';

// Подключение функционала для блока Преимущества
require 'template-parts/rs-features/rs-features-functions.php';

// Подключение функционала для блока Преимущества 3 колонки
require 'template-parts/rs-features-3x/rs-features-3x-functions.php';

// Подключение функционала для блока Каталог
require 'template-parts/rs-services/rs-services-functions.php';

// Подключение функционала для слайдера
require 'template-parts/rs-slider/rs-slider-functions.php';

// Подключение функционала для блока Самые продаваемые
require 'template-parts/rs-best-sellers/rs-best-sellers-functions.php';

// Подключение функционала для блока Популярные
require 'template-parts/rs-popular/rs-popular-functions.php';

// Подключение функционала для блока Распродажа
require 'template-parts/rs-onsale/rs-onsale-functions.php';

// Подключение функционала для блока Новинки (old)
require 'template-parts/rs-new-products/rs-new-products-functions.php';

// Подключение функционала для блока Новинки
require 'template-parts/rs-new-product/rs-new-product-functions.php';

// Подключение функционала для блока Коллекция
require 'template-parts/rs-collection/rs-collection-functions.php';

// Подключение функционала для блока Популярные товары
require 'template-parts/rs-popular-product/rs-popular-product-functions.php';

// Подключение функционала для блока Медиа
require 'template-parts/rs-media/rs-media-functions.php';

// Подключение функционала для блока Instagram
require 'template-parts/rs-inst/rs-inst-functions.php';

// Подключение функционала для блока О нас
require 'template-parts/rs-about-us/rs-about-us-functions.php';

// Подключение функционала для блока Представители
require 'template-parts/rs-representatives/rs-representatives-functions.php';

// Подключение функционала для формы обратной связи
require 'template-parts/rs-form/rs-form-functions.php';

// Подключение функционала для блока Наша команда
require 'template-parts/rs-team/rs-team-functions.php';

// Подключение функционала для блока Как мы работаем
require 'template-parts/rs-howworks/rs-howworks-functions.php';

// Подключение функционала для блока Предложения
require 'template-parts/rs-offers/rs-offers-functions.php';

// Подключение функционала для блока Преимущества с картинкой
require 'template-parts/rs-features-photo/rs-features-photo-functions.php';

// Подключение функционала для блока Цифры
require 'template-parts/rs-numbers/rs-numbers-functions.php';

// Подключение функционала для блока Свяжитесь с нами
require 'template-parts/rs-contactus/rs-contactus-functions.php';

// Подключение функционала для блока Цитата
require 'template-parts/rs-parallax-land/rs-parallax-land-functions.php';

// Подключение функционала для блока Партнёры
require 'template-parts/rs-partners/rs-partners-functions.php';

// Подключение функционала для блока Видео
require 'template-parts/rs-video/rs-video-functions.php';

// Подключение функционала для блока Тарифы
//require 'template-parts/rs-price/rs-price-functions.php';

// Подключение функционала для блока Таймер
require 'template-parts/rs-counter/rs-counter-functions.php';

// Подключение функционала для блока Подписаться
require 'template-parts/rs-subscribe/rs-subscribe-functions.php';

// Подключение функционала для блока Фотогалерея
require 'template-parts/rs-photogallery/rs-photogallery-functions.php';

// Подключение функционала для блока Видеоролики
require 'template-parts/rs-video-new/rs-video-new-functions.php';

// Подключение функционала для блока с переключателями
require 'template-parts/rs-tabs/rs-tabs-functions.php';

// Подключение функционала для блока Параллакс 1
require 'template-parts/rs-parallax-1/rs-parallax-1-functions.php';

// Подключение функционала для блока Параллакс 2
require 'template-parts/rs-parallax-2/rs-parallax-2-functions.php';

// Подключение функционала для блока Иконки с услугами
require 'template-parts/rs-services-icon/rs-services-icon-functions.php';

// Подключение функционала для блока Форма ОС с картнкой
require 'template-parts/rs-contact-land/rs-contact-land-functions.php';

// Подключение функционала для блока Рекоммендации
require 'template-parts/rs-recommendations/rs-recommendations-functions.php';

// Подключение функционала для блока наши проекты
//require 'template-parts/rs-portfolio-slider/rs-portfolio-slider-functions.php';

// Подключение функционала для блока карусель фотографий
require 'template-parts/rs-carousel/rs-carousel-functions.php';

// Подключение функционала для футера
require 'template-parts/rs-footer/rs-footer-functions.php';

add_action( 'template_redirect', 'rs_get_tpl_include' );
function rs_get_tpl_include(){
    $get_template = get_page_template_slug( get_the_ID());
    if($get_template =='template-allblocks.php'){
        $file_path = locate_template( $get_template );
        // Подключение функционала для шаблона страницы Все блоки
        require 'template-parts/rs-page-allblocks/rs-page-allblocks-functions.php';
    } else if ($get_template =='contacts.php'){
        // Подключение функционала для шаблона страницы контакты
        require 'template-parts/rs-page-contacts/rs-page-contacts-functions.php';
    } else if ($get_template =='template-burger') {
        // Подключение функционала для шаблона страницы бургер-меню
        require 'template-parts/rs-page-burger-menu/rs-page-burger-menu-functions.php';
    } else {
        //
    }
    // Подключение функционала для шаблона основной страницы
    require 'template-parts/rs-page-base/rs-page-base-functions.php';
}

//Вывод вспомогательной информации в администраторе #0.23
add_action('wp_dashboard_setup', 'blogood_ru_help_widgets');
function blogood_ru_help_widgets() {
    global $wp_meta_boxes;
    wp_add_dashboard_widget(
        'help_widget', //Слаг виджета
        'Добро пожаловать в РСУ', //Заголовок виджета
        'help' //Функция вывода
    );
}

function help() {
    echo '<p><a href="https://rosait.ru/wordpress-instruktsiya/" target="_blank">Руководство по работе</a> с системой управления WordPress</p>';
    echo '<p>Техническая поддержка: support@rosait.ru<p>';
    echo '<p>Отдел продаж: +7 (800) 222-90-72 по всей России (бесплатно)<p>';
    echo '<hr/><p>РСУ - Россайт Система управления для Wordpress</p>';
}

function set_size( $id ) {
    $quantities = get_post_meta( $id, '_sizes_quantities', TRUE);
    $json = json_decode($quantities,true);
    $sku = get_post_meta( $id, '_sku', TRUE);
    error_log('sku: '.$sku .' $id '.$id);
    $args = array('post_parent' => $id, 'post_type' => 'product_variation', 'posts_per_page' => -1,);
    $children = get_children($args, ARRAY_A);
    foreach ($children as $child) {
        $size = strtoupper(get_post_meta($child['ID'], 'attribute_pa_size', TRUE));
        if (strlen($size) < 1) continue;
        update_post_meta($child['ID'], '_sku', $sku.'-'.$size);
        update_post_meta($child['ID'], '_visibility', in_array($size, array_keys($json)) ? "visible" : "hidden" );
        update_post_meta($child['ID'], '_stock', isset($json[$size])?$json[$size]:0);
        update_post_meta($child['ID'], '_backorders', 'no');
        error_log('variation id: '.$child['ID'].' backorders: '. get_post_meta( $child['ID'], '_backorders', TRUE));
    }
}
add_action ( 'pmxi_saved_post' , 'rs_process_product_meta_type_action' );
function  rs_process_product_meta_type_action ( $id )   {
    set_size($id);
}

function decrease_order_item_stock($post_id, $xml_node, $is_update) {
    // Retrieve the import ID.
    $import_id = ( isset( $_GET['id'] ) ? $_GET['id'] : ( isset( $_GET['import_id'] ) ? $_GET['import_id'] : 'new' ) );
    if ( in_array( $import_id, [3] ) ) {
        wc_reduce_stock_levels( $post_id );
    }
}
add_action('pmxi_saved_post', 'decrease_order_item_stock', 100, 3);

add_action( 'pre_get_posts', 'search_filter' );
function search_filter( $query )
{
    if (is_admin()) {
        return;
    }
    if ($query->is_main_query() && $query->is_search) {
        $query->set('post_type', array('product'));
        $meta_query[] = array(
            'key' => '_price',
            'value' => 0,
            'compare' => '>',
            'type' => 'NUMERIC',
        );
        $query->set('meta_query', $meta_query);
    }
    if ($query->is_main_query() && isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'product') {
        $meta_query = (array)$query->get('meta_query');
        $meta_query[] = array(
            'key' => '_price',
            'value' => 0,
            'compare' => '>',
            'type' => 'NUMERIC',
        );
        $query->set('meta_query', $meta_query);
    }
}

function test(){

}

//add_action( 'woocommerce_order_status_pay_au', 'woocommerce_order_status_pay_au_action', 10, 2 );
function woocommerce_order_status_pay_au_action( $order_id, $that ){

    $order = wc_get_order( $order_id );
    error_log( 'MAIL:'.$order_id);
    WC()->mailer()->get_emails()['WC_Email_Customer_On_Hold_Order']->trigger( $order_id );
}

//var_dump(WC()->mailer()->get_emails());

add_action( 'woocommerce_order_status_changed', 'wpgid_email_complated_to_faled', 10, 4 );
function wpgid_email_complated_to_faled( $order_id, $status_transition_from, $status_transition_to, $that ){
    // проверяем изначальный статус заказа (если он был "completed", то выполняем функцию)
    error_log( 'MAIL2:'.$status_transition_to);
    if( $status_transition_to === 'pay_au' ) {
        error_log( 'transition_to:'.$order_id);
        //WC()->mailer()->get_emails()['WC_Email_New_Order']->trigger( $order_id );
        $wc_email = WC()->mailer()->get_emails()['WC_Email_Customer_On_Hold_Order'];
        ## -- Customizing Heading, subject (and optionally add recipients)  -- ##
        // Change Subject
        $wc_email->settings['subject'] = __('{site_title} - Спасибо за заказ ({order_number}) - {order_date}');
        // Change Heading
        $wc_email->settings['heading'] = __('Спасибо за заказ');
        // Send "New Email" notification (to admin)
        $wc_email->trigger( $order_id );
    }
}

//////////////////////////////////////////////////////////////////////////////////////////

add_filter('woocommerce_shipping_free_shipping_instance_settings_values', function($instance_settings){
    if (isset($instance_settings['min_amount'])) {
        $instance_settings['min_amount'] = preg_replace('/[^0-9]/', '', $instance_settings['min_amount']);
    }
    return $instance_settings;
}, 10, 2);

add_filter('the_title', 'add_color_to_product_title', 10, 2);

function add_color_to_product_title($title, $post_id) {
    if (is_admin() && isset($_GET['post_type']) && $_GET['post_type'] === 'product' && isset($_GET['page']) && $_GET['page'] === 'order-post-types-product') {
        $colors = get_the_terms($post_id, 'pa_color');
        if ($colors && !is_wp_error($colors)) {
            $first_color = $colors[0]->name;
            $title .= ' - ' . $first_color;
        }
    }
    return $title;
}

class StructureMenu {
    // название зарегистрированного меню
    private $menu_name = '';

    /** @var WP_Post[] - не сортированные пункты меню */
    private $menu_items = [];

    /** @var WP_Post[] - сортированные пункты меню */
    private $menu_struct_items = [];

    public function __construct(string $menu_name) {
        $this->menu_name = $menu_name;

    }

    public function getItemsStructureMenu(): array {
        if (empty($this->menu_name)) {
            return [];
        }

        $locations = get_nav_menu_locations();


            // получаем элементы меню
            $this->menu_items = wp_get_nav_menu_items($this->menu_name);

            $this->menu_struct_items = $this->_sortStructureMenu();


        return $this->menu_struct_items;
    }

    private function _sortStructureMenu(): array {
        if (empty($this->menu_name)) {
            return [];
        }

        $result = [];

        // текущий левел, меню всегда начинает с первого уровня
        $current_level = 1;

        // сюда складываем  ссылки на последние айтемы в каждом уровне
        $last_level_item_menu = [];

        foreach ($this->menu_items as &$menu_item) {
            // Определяем уровень
            if ($menu_item->menu_item_parent == 0) {
                // у первого уровня этот параметр всегда 0
                $current_level = 1;
            } else {
                // $current_level все еще от предыдущего айтема
                if ($last_level_item_menu[$current_level]->ID == $menu_item->menu_item_parent) {
                    // значит предыдущий был нашим предком - повысим текущий уровень
                    $current_level++;
                } else if (isset($last_level_item_menu[$current_level - 1])
                    && $last_level_item_menu[$current_level - 1]->ID == $menu_item->menu_item_parent) {
                    // у нас один и тот же предок с предыдущим
                    // мы на том же уровне
                } else {
                    // уровень снизился ищем предка по всему массиву,
                    // по сути две предыдущие проверки нужны чтоб поменьше бегать по этому массиву
                    foreach ($last_level_item_menu as $level => $item_menu) {
                        /*  @var  WP_Post $item_menu */
                        if ($item_menu->ID == $menu_item->menu_item_parent) { // вот он наш предок
                            // накидываем к его левелу
                            $current_level = $level + 1;
                            break;
                        }
                    }

                }
            }

            // для наследников складываем ссылку на текущий левел
            $last_level_item_menu[$current_level] = &$menu_item;

            // по вкусу добавляем левел, и не ноем больше, что его нет #тыж_программист :)
            $last_level_item_menu[$current_level]->level_menu = $current_level;

            if ($current_level === 1) { // первый уровень складываем в наш структурный массив
                $result[] = $menu_item;
            } else {
                // если это первый чилдрен
                if (!isset($last_level_item_menu[$current_level - 1]->sub_item_menu)) {
                    $last_level_item_menu[$current_level - 1]->sub_item_menu = [];
                }

                // наш предок на уровень выше
                $last_level_item_menu[$current_level - 1]->sub_item_menu[] = $last_level_item_menu[$current_level];
            }
        }

        return $result;
    }
}



function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}



// Добавь этот код в конец файла functions.php (перед закрывающим 
add_filter('woocommerce_get_shop_coupon_data', 'coupon_case_insensitive_search', 10, 2);

function coupon_case_insensitive_search($data, $code) {
    if (empty($data)) {
        global $wpdb;
        
        $coupon_post = $wpdb->get_row($wpdb->prepare("
            SELECT * FROM {$wpdb->posts} 
            WHERE post_type = 'shop_coupon' 
            AND LOWER(post_title) = LOWER(%s) 
            AND post_status = 'publish'
            LIMIT 1
        ", $code));
        
        if ($coupon_post) {
            $coupon = new WC_Coupon($coupon_post->ID);
            return $coupon->get_data();
        }
    }
    return $data;
}




add_filter('wp_get_attachment_image_src', 'fix_double_webp_extensions', 10, 3);
function fix_double_webp_extensions($image, $attachment_id, $size) {
    if (isset($image[0])) {
        $image[0] = str_replace('.webp.webp', '.webp', $image[0]);
    }
    return $image;
}

// Фикс для wp_get_attachment_image_srcset
add_filter('wp_get_attachment_image_srcset', 'fix_double_webp_srcset', 10, 4);
function fix_double_webp_srcset($sources, $size_array, $image_src, $image_meta) {
    if (is_array($sources)) {
        foreach ($sources as &$source) {
            if (isset($source['url'])) {
                $source['url'] = str_replace('.webp.webp', '.webp', $source['url']);
            }
        }
    }
    return $sources;
}

// Фикс для всех URL изображений в контенте
add_filter('the_content', 'fix_double_webp_in_content');
function fix_double_webp_in_content($content) {
    return str_replace('.webp.webp', '.webp', $content);
}

// Фикс специально для srcset в picture тегах
add_filter('wp_calculate_image_srcset', 'fix_double_webp_calculate_srcset', 10, 5);
function fix_double_webp_calculate_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id) {
    if (is_array($sources)) {
        foreach ($sources as &$source) {
            if (isset($source['url'])) {
                $source['url'] = str_replace('.webp.webp', '.webp', $source['url']);
            }
        }
    }
    return $sources;
}




// Добавляем AJAX handlers в functions.php или отдельный файл

// Проверка остатков для выбранного размера
add_action('wp_ajax_check_product_stock', 'check_product_stock_handler');
add_action('wp_ajax_nopriv_check_product_stock', 'check_product_stock_handler');

function check_product_stock_handler() {
    // Проверяем nonce
    if (!wp_verify_nonce($_POST['nonce'], 'check_availability_nonce')) {
        wp_die('Security check failed');
    }

    $product_id = intval($_POST['product_id']);
    $size = sanitize_text_field($_POST['size']);

    // Получаем вариант товара по размеру
    $product = wc_get_product($product_id);
    if (!$product || !$product->is_type('variable')) {
        wp_send_json_error('Invalid product');
    }

    // Ищем вариацию с нужным размером
    $variations = $product->get_available_variations();
    $variation_id = null;
    
    foreach ($variations as $variation) {
        if (isset($variation['attributes']['attribute_pa_size']) && 
            $variation['attributes']['attribute_pa_size'] === $size) {
            $variation_id = $variation['variation_id'];
            break;
        }
    }

    if (!$variation_id) {
        wp_send_json_error('Variation not found');
    }

    // Получаем вариацию и проверяем остатки
    $variation = wc_get_product($variation_id);
    $in_stock = $variation->is_in_stock();
    
    // Подсчитываем количество магазинов (пока заглушка - 1 магазин)
    $store_count = $in_stock ? 1 : 0;

    wp_send_json_success([
        'in_stock' => $in_stock,
        'store_count' => $store_count,
        'stock_quantity' => $variation->get_stock_quantity()
    ]);
}

// Получение списка магазинов с наличием
add_action('wp_ajax_get_store_availability', 'get_store_availability_handler');
add_action('wp_ajax_nopriv_get_store_availability', 'get_store_availability_handler');

function get_store_availability_handler() {
    // Проверяем nonce
    if (!wp_verify_nonce($_POST['nonce'], 'check_availability_nonce')) {
        wp_die('Security check failed');
    }

    $product_id = intval($_POST['product_id']);
    $size = sanitize_text_field($_POST['size']);

    // Получаем вариант товара по размеру
    $product = wc_get_product($product_id);
    if (!$product || !$product->is_type('variable')) {
        wp_send_json_error('Invalid product');
    }

    // Ищем вариацию с нужным размером
    $variations = $product->get_available_variations();
    $variation_id = null;
    
    foreach ($variations as $variation) {
        if (isset($variation['attributes']['attribute_pa_size']) && 
            $variation['attributes']['attribute_pa_size'] === $size) {
            $variation_id = $variation['variation_id'];
            break;
        }
    }

    if (!$variation_id) {
        wp_send_json_error('Variation not found');
    }

    // Получаем вариацию и проверяем остатки
    $variation = wc_get_product($variation_id);
    $stores = [];
    
    if ($variation->is_in_stock()) {
        // Пока один магазин, но можно расширить
        $stores[] = [
            'store' => 'Гороховая, 49',
            'quantity' => 'в наличии',
            'address' => 'ПН–ВС: с 12:00 до 21:00',
            'phone' => '',
            'coords' => [59.926917, 30.322906],
            'status' => 'in-stock'
        ];
    }

    wp_send_json_success([
        'stores' => $stores
    ]);
}
   if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Header Settings',  // Название страницы в админке
        'menu_title' => 'Header Settings',  // Название в меню
        'menu_slug'  => 'header-settings',  // Уникальный слаг (URL)
        'capability' => 'edit_posts',       // Кто может видеть (админы/редакторы)
        'redirect'   => false               // Не перенаправлять
    ));
}




















// Удаляем старые хуки
remove_all_actions('wp_ajax_get_store_availability');
remove_all_actions('wp_ajax_nopriv_get_store_availability');
remove_action('woocommerce_single_product_summary', 'misim_display_stock_info_csv_console', 25);

// Регистрируем AJAX
add_action('wp_ajax_get_gorokhovaya_csv_availability', 'get_gorokhovaya_availability', 5);
add_action('wp_ajax_nopriv_get_gorokhovaya_csv_availability', 'get_gorokhovaya_availability', 5);
add_action('wp_ajax_check_product_stock', 'check_gorokhovaya_stock');
add_action('wp_ajax_nopriv_check_product_stock', 'check_gorokhovaya_stock');

/**
 * Кэширование CSV (5 минут)
 */
if (!function_exists('get_cached_csv_data')) {
    function get_cached_csv_data() {
        $cache_key = 'gorokhovaya_csv_data';
        $cache_time = 5 * MINUTE_IN_SECONDS;
        
        $cached = get_transient($cache_key);
        if ($cached !== false) {
            return $cached;
        }
        
        $csv_url = 'https://bazar.mostech.ru/trueshituchet/libroot/integration/misim/?id_shop=17&password=clientSystem';
        $response = wp_remote_get($csv_url, array(
            'timeout' => 20,
            'headers' => array('User-Agent' => 'Mozilla/5.0')
        ));
        
        if (is_wp_error($response) || !$response) {
            return null;
        }
        
        $body = wp_remote_retrieve_body($response);
        if (!$body) {
            return null;
        }
        
        set_transient($cache_key, $body, $cache_time);
        return $body;
    }
}

/**
 * Парсинг CSV по SKU
 */
if (!function_exists('parse_csv_for_sku')) {
    function parse_csv_for_sku($csv_body, $sku) {
        if (!$csv_body) return null;

        // Use proper CSV parsing with stream to handle multiline fields
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $csv_body);
        rewind($stream);
        
        // Read headers
        $headers = fgetcsv($stream, 0, ';');
        if (!$headers) {
            fclose($stream);
            return null;
        }
        
        // Read all rows
        while (($row = fgetcsv($stream, 0, ';')) !== false) {
            // Skip rows with incorrect number of columns
            if (count($row) != count($headers)) continue;
            
            $item = array_combine($headers, $row);

            if (isset($item['article']) && trim($item['article']) === trim($sku)) {
                $sizes_total = array();
                $stores_sizes = array();
                
                foreach ($item as $colVal) {
                    if (!is_string($colVal)) continue;
                    $val = trim($colVal);
                    if ($val === '' || $val[0] !== '{') continue;
                    $decoded = json_decode($val, true);
                    if (!is_array($decoded)) continue;
                    
                    $firstVal = reset($decoded);
                    if (is_numeric($firstVal)) {
                        // Общие остатки по размерам
                        foreach ($decoded as $k => $v) {
                            $sizes_total[strtoupper($k)] = intval($v);
                        }
                    } elseif (is_array($firstVal)) {
                        // Остатки по магазинам
                        foreach ($decoded as $storeName => $sizesArr) {
                            if (!is_array($sizesArr)) continue;
                            $norm = array();
                            foreach ($sizesArr as $sizeKey => $qty) {
                                $norm[strtoupper($sizeKey)] = intval($qty);
                            }
                            $stores_sizes[$storeName] = $norm;
                        }
                    }
                }
                
                fclose($stream);
                return array(
                    'sizes_total' => $sizes_total,
                    'stores_sizes' => $stores_sizes
                );
            }
        }
        
        fclose($stream);
        return null;
    }
}

/**
 * Информация о магазинах
 */
if (!function_exists('get_known_stores')) {
    function get_known_stores() {
        return array(
            'Гороховая 49' => array(
                'address' => 'ПН-ВС: с 12:00 до 21:00',
                'phone'   => '',
                'coords'  => array(59.926917, 30.322906),
            ),
            'Каменоостровский 32' => array(
                'address' => 'ПН-ВС: с 12:00 до 21:00',
                'phone'   => '',
                'coords'  => array(59.963964, 30.313003),
            ),
        );
    }
}

/**
 * AJAX: Данные для попапа
 */

if (!function_exists('get_gorokhovaya_availability')) {
    function get_gorokhovaya_availability() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'check_availability_nonce')) {
            wp_send_json_error('Security check failed');
            return;
        }

        $product_id = intval($_POST['product_id']);
        $product = wc_get_product($product_id);

        if (!$product) {
            wp_send_json_error('Product not found');
            return;
        }

        $sku = $product->get_sku();
        if (!$sku) {
            wp_send_json_success(['stores' => []]);
            return;
        }

        $csv_body = get_cached_csv_data();
        $parsed = parse_csv_for_sku($csv_body, $sku);

        $stores_sizes = $parsed ? $parsed['stores_sizes'] : [];
        $knownStores = get_known_stores();
        $selected_size = isset($_POST['size']) ? strtoupper(sanitize_text_field($_POST['size'])) : '';

        error_log("=== GET_AVAILABILITY POPUP ===");
        error_log("SKU: $sku");
        error_log("Selected size: $selected_size");
        error_log("Stores from CSV: " . print_r(array_keys($stores_sizes), true));
        error_log("Known stores: " . print_r(array_keys($knownStores), true));

        $stores = [];

        foreach ($knownStores as $storeName => $meta) {
            $qty = 0;
            $matchedStore = null;

            // Нормализуем название магазина из knownStores
            $normalizedKnown = mb_strtolower(trim($storeName));

            foreach ($stores_sizes as $csvStoreName => $sizesByStore) {
                $normalizedCsv = mb_strtolower(trim($csvStoreName));

                // Сравнение по подстроке в обе стороны
                if (
                    strpos($normalizedCsv, $normalizedKnown) !== false ||
                    strpos($normalizedKnown, $normalizedCsv) !== false
                ) {
                    $matchedStore = $csvStoreName;
                    error_log("✔ MATCH: CSV '{$csvStoreName}' <=> '{$storeName}'");

                    if ($selected_size !== '' && isset($sizesByStore[$selected_size])) {
                        $qty = intval($sizesByStore[$selected_size]);
                        error_log("   → Size {$selected_size}: qty {$qty}");
                    } elseif ($selected_size === '') {
                        // Если размер не указан — суммируем всё
                        $qty = array_sum(array_map('intval', $sizesByStore));
                        error_log("   → No size selected, total qty {$qty}");
                    }
                    break;
                }
            }

            if (!$matchedStore) {
                error_log("⚠ NO MATCH for known store '{$storeName}' in CSV");
            }

            $stores[] = [
                'store'    => $storeName,
                'address'  => $meta['address'] ?? '',
                'phone'    => $meta['phone'] ?? '',
                'quantity' => $qty > 0 ? 'В наличии' : 'Нет в наличии',
                'status'   => $qty > 0 ? 'in-stock' : 'out-of-stock',
                'coords'   => $meta['coords'] ?? '',
            ];
        }

        error_log("Final stores count: " . count($stores));
        error_log("=== END GET_AVAILABILITY ===");

        wp_send_json_success(['stores' => $stores]);
    }
}
/**
 * AJAX: Проверка наличия для текста кнопки
 * КЛЮЧЕВАЯ ФУНКЦИЯ!
 */
if (!function_exists('check_gorokhovaya_stock')) {
    function check_gorokhovaya_stock() {
        if (!wp_verify_nonce($_POST['nonce'], 'check_availability_nonce')) {
            wp_send_json_error('Security check failed');
            return;
        }
        
        $product_id = intval($_POST['product_id']);
        $selected_size = isset($_POST['size']) ? strtoupper(sanitize_text_field($_POST['size'])) : '';
        
        $product = wc_get_product($product_id);
        if (!$product) {
            wp_send_json_error('Product not found');
            return;
        }
        
        $sku = $product->get_sku();
        if (!$sku) {
            wp_send_json_success(array('in_stock' => false, 'store_count' => 0));
            return;
        }
        
        $csv_body = get_cached_csv_data();
        $parsed = parse_csv_for_sku($csv_body, $sku);
        
        if (!$parsed) {
            wp_send_json_success(array('in_stock' => false, 'store_count' => 0));
            return;
        }
        
        $sizes_total = $parsed['sizes_total'];
        $stores_sizes = $parsed['stores_sizes'];
        
        error_log('=== CHECK_STOCK BUTTON ===');
        error_log('SKU: ' . $sku);
        error_log('Size: ' . $selected_size);
        error_log('Product type: ' . $product->get_type());
        error_log('Total stock: ' . print_r($sizes_total, true));
        error_log('Stores from CSV: ' . print_r(array_keys($stores_sizes), true));
        
        // 1. Проверяем ОБЩИЙ остаток
        $total_stock = 0;
        
        if ($selected_size !== '') {
            // Variable product with specific size selected
            if (!empty($sizes_total) && isset($sizes_total[$selected_size])) {
                $total_stock = intval($sizes_total[$selected_size]);
            }
            error_log('Total stock for size ' . $selected_size . ': ' . $total_stock);
        } else {
            // Simple product or no size selected - sum all sizes
            if (!empty($sizes_total)) {
                $total_stock = array_sum(array_map('intval', $sizes_total));
            }
            error_log('Total stock (all sizes): ' . $total_stock);
        }
        
        // 2. Считаем МАГАЗИНЫ
        $store_count = 0;
        if (!empty($stores_sizes)) {
            foreach ($stores_sizes as $storeName => $sizesByStore) {
                if (!is_array($sizesByStore)) continue;
                
                error_log('Checking store: "' . $storeName . '"');
                
                if ($selected_size !== '') {
                    // Check specific size
                    if (isset($sizesByStore[$selected_size])) {
                        $qty = intval($sizesByStore[$selected_size]);
                        error_log('  Qty for size ' . $selected_size . ': ' . $qty);
                        
                        if ($qty > 0) {
                            $store_count++;
                            error_log('  ✓ Counted! Total: ' . $store_count);
                        }
                    } else {
                        error_log('  ✗ Size not found in this store');
                    }
                } else {
                    // Check if store has any size in stock (for simple products)
                    $store_qty = array_sum(array_map('intval', $sizesByStore));
                    error_log('  Total qty (all sizes): ' . $store_qty);
                    
                    if ($store_qty > 0) {
                        $store_count++;
                        error_log('  ✓ Counted! Total: ' . $store_count);
                    }
                }
            }
        }
        
        $in_stock = $total_stock > 0;
        
        error_log('RESULT: in_stock=' . ($in_stock ? 'YES' : 'NO') . ', store_count=' . $store_count);
        error_log('=== END CHECK_STOCK ===');
        
        wp_send_json_success(array(
            'in_stock' => $in_stock,
            'store_count' => $store_count,
            'total_stock' => $total_stock
        ));
    }
}

/**
 * Очистка кэша
 */
add_action('woocommerce_update_product', 'clear_gorokhovaya_csv_cache');
add_action('woocommerce_new_product', 'clear_gorokhovaya_csv_cache');

if (!function_exists('clear_gorokhovaya_csv_cache')) {
    function clear_gorokhovaya_csv_cache() {
        delete_transient('gorokhovaya_csv_data');
    }
}


if (!function_exists('product_has_store_availability')) {
    function product_has_store_availability($product) {
        if (!$product) return false;

        $sku = $product->get_sku();
        if (!$sku) return false;

        $csv_body = get_cached_csv_data();
        if (!$csv_body) return false;

        $parsed = parse_csv_for_sku($csv_body, $sku);
        if (!$parsed) return false;

        $stores_sizes = $parsed['stores_sizes'];
        if (empty($stores_sizes)) return false;

        // Check if any store has any size in stock
        foreach ($stores_sizes as $storeName => $sizesByStore) {
            if (!is_array($sizesByStore)) continue;

            foreach ($sizesByStore as $size => $qty) {
                if (intval($qty) > 0) {
                    return true;
                }
            }
        }

        return false;
    }
}

add_filter('woocommerce_product_is_visible', 'show_products_with_store_availability', 10, 2);
if (!function_exists('show_products_with_store_availability')) {
    function show_products_with_store_availability($visible, $product_id) {
        // If already visible (in stock in warehouse), return as is
        if ($visible) return $visible;
 
        $product = wc_get_product($product_id);
        if (!$product) return $visible;
 
        // Product is not visible (out of stock in warehouse)
        // Check if has store availability to make it visible
        if (product_has_store_availability($product)) {
            return true; // Make visible because available in stores
        }
 
        // Not in warehouse and not in stores - keep hidden
        return false;
    }
}


if (!function_exists('is_product_store_only')) {
    function is_product_store_only($product) {
        if (!$product) return false;

        // If in stock in warehouse, not store-only
        if ($product->is_in_stock()) return false;

        // Check if has store availability
        return product_has_store_availability($product);
    }
}


/**
 * Redirect to 404 if product is not available anywhere (warehouse + stores)
 */
add_action('template_redirect', 'redirect_unavailable_products_to_404');
if (!function_exists('redirect_unavailable_products_to_404')) {
    function redirect_unavailable_products_to_404() {
        if (!is_product()) return;
        
        global $post;
        if (!$post) return;
        
        $product = wc_get_product($post->ID);
        if (!$product) return;
        
        // If product is in stock in warehouse, it's available
        if ($product->is_in_stock()) return;
        
        // If product is out of stock in warehouse, check stores
        if (!product_has_store_availability($product)) {
            // Not available in warehouse and not in stores - show 404
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            get_template_part(404);
            exit;
        }
    }
}

add_action('wp_ajax_get_store_availability_oos', 'get_store_availability_oos_handler');
add_action('wp_ajax_nopriv_get_store_availability_oos', 'get_store_availability_oos_handler');

if (!function_exists('get_store_availability_oos_handler')) {
    function get_store_availability_oos_handler() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'check_availability_nonce')) {
            wp_send_json_error('Security check failed');
            return;
        }

        $product_id = intval($_POST['product_id']);
        $product = wc_get_product($product_id);

        if (!$product) {
            wp_send_json_error('Product not found');
            return;
        }

        $sku = $product->get_sku();
        if (!$sku) {
            wp_send_json_success(['stores' => []]);
            return;
        }

        $csv_body = get_cached_csv_data();
        $parsed = parse_csv_for_sku($csv_body, $sku);

        $stores_sizes = $parsed ? $parsed['stores_sizes'] : [];
        $knownStores = get_known_stores();

        error_log("=== OOS AVAILABILITY CHECK ===");
        error_log("Product type: " . $product->get_type());
        error_log("SKU: $sku");

        $stores = [];

        foreach ($knownStores as $storeName => $meta) {
            $qty = 0;
            $matchedStore = null;

            // Нормализуем название магазина из knownStores
            $normalizedKnown = mb_strtolower(trim($storeName));

            foreach ($stores_sizes as $csvStoreName => $sizesByStore) {
                $normalizedCsv = mb_strtolower(trim($csvStoreName));

                // Сравнение по подстроке в обе стороны
                if (
                    strpos($normalizedCsv, $normalizedKnown) !== false ||
                    strpos($normalizedKnown, $normalizedCsv) !== false
                ) {
                    $matchedStore = $csvStoreName;
                    
                    // For simple products or when no specific size - sum all sizes
                    if (!empty($sizesByStore) && is_array($sizesByStore)) {
                        $qty = array_sum(array_map('intval', $sizesByStore));
                    }
                    
                    error_log("✔ MATCH: CSV '{$csvStoreName}' <=> '{$storeName}', total qty: {$qty}");
                    break;
                }
            }

            if (!$matchedStore) {
                error_log("⚠ NO MATCH for known store '{$storeName}' in CSV");
            }

            // Add store to list
            $stores[] = [
                'store'    => $storeName,
                'quantity' => $qty > 0 ? 'В наличии' : 'Нет в наличии',
                'address'  => $meta['address'] ?? '',
                'phone'    => $meta['phone'] ?? '',
                'coords'   => $meta['coords'] ?? '',
                'status'   => $qty > 0 ? 'in-stock' : 'out-of-stock',
            ];
        }

        error_log("Final stores count: " . count($stores));
        error_log("=== END OOS AVAILABILITY ===");

        wp_send_json_success(['stores' => $stores]);
    }
}
