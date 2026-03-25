<?php

// Enqueue styles for the main page
add_action('wp_enqueue_scripts', 'style_rs_page_base_theme', 11);
function style_rs_page_base_theme() {
	wp_enqueue_style('rs-page-base', get_stylesheet_directory_uri() . '/template-parts/rs-page-base/css/rs-page-base.css');
}

// Register additional template block hooks

// template-parts/rs-slider — Slider block
add_action('template_on_slider', 'storefront_slider_child');

// template-parts/rs-features — Features 4-column block
add_action('template_on_features_4', 'storefront_rs_show_custom_block');
// template-parts/rs-features-3x — Features 3-column block
add_action('template_on_features_3', 'storefront_rs_features_3x');
// template-parts/rs-features-3x — Features N-column block
add_action('template_on_features_x', 'storefront_rs_features_x');
// template-parts/rs-features-photo — Features with image block
add_action('template_on_features_photo', 'storefront_rs_features_photo');
// template-parts/rs-offers — Offers block
add_action('template_on_offers', 'storefront_rs_offers');
// template-parts/rs-offers — Our numbers block
add_action('template_on_numbers', 'storefront_rs_numbers');
// template-parts/rs-team — Our team block
add_action('template_on_team', 'storefront_rs_team');
// template-parts/rs-popular — Contact form block
add_action('template_on_form', 'storefront_rs_form_child');
// template-parts/rs-news — News block
add_action('template_on_news', 'storefront_news_child');
// template-parts/rs-reviews — Reviews block
add_action('template_on_reviews', 'storefront_reviews_child');
// template-parts/rs-services — Catalog block
add_action('template_on_catalog', 'storefront_rs_services');

// template-parts/rs-services — Custom catalog block
add_action('template_on_catalog_free', 'storefront_rs_services_3');

// template-parts/rs-services — Internal catalog block
add_action('template_on_catalog_free2', 'storefront_rs_services_4');

// template-parts/rs-services — How we work block
add_action('template_on_works', 'storefront_rs_howworks');
// template-parts/rs-contactus — Contact us block
add_action('template_on_contact', 'storefront_rs_contactus');
// template-parts/rs-parallax-land — Quote block
add_action('template_on_quote', 'storefront_rs_parallax_land');
// template-parts/rs-partners — Partners block
add_action('template_on_partners', 'storefront_rs_partners');
// template-parts/rs-video — Video block
add_action('template_on_video', 'storefront_rs_video');
// template-parts/rs-price — Pricing plans block
add_action('template_on_price', 'storefront_price_child');
// template-parts/rs-counter — Countdown timer block
add_action('template_on_counter', 'storefront_rs_counter');
// template-parts/rs-subscribe — Subscribe block
add_action('template_on_subscribe', 'storefront_rs_subscribe');
// template-parts/rs-photogallery — Photo gallery block
add_action('template_on_photogallery', 'storefront_rs_photogallery');
// template-parts/rs-video-new — Video clips block
add_action('template_on_video_new', 'storefront_rs_video_new');
// template-parts/rs-tabs — Tabs block
add_action('template_on_tabs', 'storefront_rs_tabs');
// template-parts/rs-parallax-1 — Parallax 1 block
add_action('template_on_parallax_1', 'storefront_parallax_1');
// template-parts/rs-parallax-2 — Parallax 2 block
add_action('template_on_parallax_2', 'storefront_parallax_2');
// template-parts/rs-portfolio-slider — Our projects block
add_action('template_on_examples', 'storefront_examples_child');
// template-parts/rs-services-icon — Services with icons block
add_action('template_on_services_icon', 'storefront_rs_services_icon');
// template-parts/rs-contact-land — Contact form with image block
add_action('template_on_contact_land', 'storefront_rs_contact_land_child');
// template-parts/rs-recommendations — Recommendations block
add_action('template_on_recommendations', 'storefront_rs_recommendations');
// template-parts/rs-carousel — Photo carousel block
add_action('template_on_carousel', 'storefront_rs_carousel');

// template-parts/rs-popular — Popular products block
add_action('template_on_popular', 'storefront_popular_products_child');
// template-parts/rs-onsale — On sale products block
add_action('template_on_onsale', 'storefront_onsale_products_child');
// template-parts/rs-best-sellers — Best sellers block
add_action('template_on_best_seller', 'storefront_best_selling_products_child');
// template-parts/rs-new-products — New products block
add_action('template_on_new_products', 'storefront_recent_products_child');

// template-parts/rs-new-product — New product block
add_action('template_on_new_product', 'storefront_rs_new_product');
// template-parts/rs-collection — Collection block
add_action('template_on_collection', 'storefront_rs_collection');
// template-parts/rs-popular-product — Popular product block
add_action('template_on_popular_product', 'storefront_rs_popular_product');
// template-parts/rs-media — Media block
add_action('template_on_media', 'storefront_rs_media');
// template-parts/rs-inst — Instagram block
add_action('template_on_inst', 'storefront_rs_inst');

// template-parts/rs-about-us — About us block
add_action('template_on_about_us', 'storefront_rs_about_us');
// template-parts/rs-representatives — Representatives block
add_action('template_on_representatives', 'storefront_rs_representatives');
