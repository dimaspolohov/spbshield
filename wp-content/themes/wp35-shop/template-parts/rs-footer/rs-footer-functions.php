<?php 

// Enqueue footer styles
add_action( 'wp_enqueue_scripts', 'style_rs_footer_theme');
function style_rs_footer_theme() {
	wp_enqueue_style( 'rs-footer', get_stylesheet_directory_uri().'/template-parts/rs-footer/css/rs-footer.css');
	wp_enqueue_style( 'rs-cookie-popup', get_stylesheet_directory_uri().'/assets/css/rs-cookie-popup.css', array(), '1.0.0');
	wp_enqueue_script( 'rs-footer', get_stylesheet_directory_uri().'/assets/js/rs-footer.js', array('jquery'), '1.0.0', true);
}

// Unregister parent theme footer widgets
function remove_some_widgets(){
   unregister_sidebar( 'footer-1' );
   unregister_sidebar( 'footer-2' );
   unregister_sidebar( 'footer-3' );
   unregister_sidebar( 'footer-4' );
}
add_action( 'widgets_init', 'remove_some_widgets', 11 );

function delete_footer() {
   remove_action('storefront_footer', 'storefront_credit', 20);
}
add_action( 'init', 'delete_footer', 1);

add_action('after_setup_theme', 'footer_widgets_setup');
function footer_widgets_setup() {

   register_sidebar(array(
      'name'          => 'Подвал Столбец 1',
      'id'            => 'footer-left',
      'description'   => 'Добавьте сюда виджеты, которые хотите разместить в подвале сайта слева',
      'before_widget' => '<div class="info-list">',
      'after_widget'  => '</div>',
      'before_title'  => '<h4>',
      'after_title'   => '</h4>',
   ));   
   register_sidebar(array(
      'name'          => 'Подвал Столбец 2',
      'id'            => 'footer-left-center',
      'description'   => 'Добавьте сюда виджеты, которые хотите разместить в подвале сайта в центре-слева',
      'before_widget' => '<div class="info-list">',
      'after_widget'  => '</div>',
      'before_title'  => '<h4>',
      'after_title'   => '</h4>',
   ));   
   register_sidebar(array(
      'name'          => 'Подвал Столбец 3',
      'id'            => 'footer-right-center',
      'description'   => 'Добавьте сюда виджеты, которые хотите разместить в подвале сайта в центре-справа',
      'before_widget' => '<div class="info-list">',
      'after_widget'  => '</div>',
      'before_title'  => '<h4>',
      'after_title'   => '</h4>',
   ));
   register_sidebar(array(
      'name'          => 'Подвал Столбец 4',
      'id'            => 'footer-right',
      'description'   => 'Добавьте сюда виджеты, которые хотите разместить в подвале сайта справа',
      'before_widget' => '<div class="info-list">',
      'after_widget'  => '</div>',
      'before_title'  => '<h4>',
      'after_title'   => '</h4>',
   )); 
   register_sidebar(array(
      'name'          => 'Нижняя часть сайта',
      'id'            => 'bottom',
      'description'   => 'Место для размещения копирайтов и меню соцсетей',
      'before_widget' => '',
      'after_widget'  => '',
      'before_title'  => '',
      'after_title'   => '',
   ));           
}

// Include contacts widget
include get_stylesheet_directory() . '/template-parts/rs-footer/contact-data-widget.php';

// Include copyright and social widget
include get_stylesheet_directory() . '/template-parts/rs-footer/bottom-data-widget.php';
