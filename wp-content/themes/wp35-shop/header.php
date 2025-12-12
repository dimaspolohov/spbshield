<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content"> тест
 *
 * @package storefront
 */

?><!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<? $site_url = site_url()?>
	<link rel="icon" sizes="192x192" href="https://www.spbshield.ru/img/icons/icons-ritmo/192x192.png" type="image/png"/><!-- белый фон -->
	<link rel="icon" sizes="128x128" href="https://www.spbshield.ru/img/icons/icons-ritmo/128x128.png" type="image/png"/><!-- белый фон -->
	<link rel="apple-touch-icon" href="https://www.spbshield.ru/img/icons/icons-ritmo/152x152-b.png" type="image/png"/><!-- черный фон 152x152-->
	<link rel="apple-touch-icon" sizes="48x48" href="https://www.spbshield.ru/img/icons/icons-ritmo/48x48-b.png" type="image/png"/><!-- черный фон -->
	<link rel="apple-touch-icon" sizes="76x76" href="https://www.spbshield.ru/img/icons/icons-ritmo/76x76-b.png" type="image/png"/><!-- черный фон -->
	<link rel="apple-touch-icon" sizes="120x120" href="https://www.spbshield.ru/img/icons/icons-ritmo/120x120-b.png" type="image/png"/><!-- черный фон -->
	<link rel="apple-touch-icon" sizes="152x152" href="https://www.spbshield.ru/img/icons/icons-ritmo/152x152.png" type="image/png"/><!-- белый фон -->
	

	<link rel="icon" type="image/png" sizes="16x16" href="<?=$site_url?>/fav/favicon-16x16.png">
	<link rel="manifest" href="<?=$site_url?>/fav/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?=$site_url?>/fav/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="<?=get_stylesheet_directory_uri()?>/assets/css/preloader.css">
	
	<!-- Стили для бегущей строки - перенесены в head для лучшей загрузки -->
	<style>
/* Исправленные стили для бегущей строки */
.header_top_line {
    background-color: #000000;
    height: 50px;
    width: 100%;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 9999;
    overflow: hidden;
    display: flex;
    align-items: center;
    box-sizing: border-box;
}

.running-line {
    display: flex;
    gap: 48px;
    /* Изменяем анимацию - убираем паузу и делаем бесконечной */
    animation: scroll-left 100s linear infinite;
    animation-play-state: running;
    white-space: nowrap;
    will-change: transform;
    /* Добавляем начальную позицию - строка сразу видна */
    transform: translateX(0);
}

.running-line.paused {
    animation-play-state: paused;
}

.running-line .text-item {
    color: #ffffff;
    font-size: 14px;
    font-family: "Manrope", Arial, sans-serif;
    text-transform: uppercase;
    cursor: pointer;
    font-weight: 400;
    letter-spacing: 1.25px;
    white-space: nowrap;
    min-width: 288px;
    display: inline-block;
}

/* Исправленная анимация - от 0 до -100% для бесконечного цикла */
@keyframes scroll-left {
    0% { 
        transform: translateX(0); 
    }
    100% { 
        transform: translateX(-50%); 
    }
}

/* Корректировка позиции основного хедера */
.rs-header._header-fixed {
    position: fixed;
}

/* Корректировка для основного контента */
.page {
    padding-top: 50px;
}

	/* Скрытие методов доставки */
	li:has(#shipping_method_0_fivepost_shipping_methodpickup),
	#shipping_method_0_fivepost_shipping_methodpickup,
	label[for="shipping_method_0_fivepost_shipping_methodpickup"],
	#fivepost-notice-0 {
		display: none !important;
	}
	</style>
	
	<?php wp_head(); ?>

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();
            for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
            k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(55512652, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true
        });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/55512652" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
	
</head>
<? $page_template_slug = get_page_template_slug() ?>
<body <?php body_class()?>>
<noscript><div><img src="https://mc.yandex.ru/watch/95888709" style="position:absolute; left:-9999px;" alt="" /></div></noscript>

<!-- Бегущая строка - вынесена в самое начало body -->
<div class="header_top_line" style="display: none">
    <div class="running-line" id="runningLine">
        <?php
        if (function_exists('have_rows') && have_rows('running_line_items', 'option')):  // 'option' потому что глобально
            while (have_rows('running_line_items', 'option')): the_row();
                $text = get_sub_field('text');
                $link = get_sub_field('link');
                // Если добавил true/false для target
                $target = get_sub_field('open_in_new_tab') ? 'target="_blank"' : '';
        ?>
                <span class="text-item">
                    <a href="<?php echo esc_url($link); ?>" <?php echo $target; ?>>
                        <?php echo esc_html($text); ?>
                    </a>
                </span>
        <?php
            endwhile;
        else:
            // Fallback, если ничего не добавлено в админке
            echo '<span class="text-item">Default text</span>';  // Или оставь пустым
        endif;
        ?>
    </div>
</div>





<!-- preloader -->
<div class="preloader">
    <svg viewBox="0 0 101.872 120.937">
        <path fill-rule="evenodd" clip-rule="evenodd" fill="none" stroke="#000" stroke-miterlimit="10" stroke-dasharray="0, 1001.72"
              stroke-dashoffset="0" d="M29.266,81.138
				c-1.32-2.21-2.343-4.615-3.643-6.835l7.49,3.82l2.21,1.12l2.3,1.14l2.36,1.15c1,0.49,1.95,0.94,2.77,1.32s1.78,0.83,2.71,1.24
				s1.79,0.79,2.63,1.15s1.66,0.69,2.56,1l0.31,0.12l0.38,0.13l0.65-0.26c1.57-0.66,3.09-1.32,4.57-2s2.94-1.37,4.56-2.16
				s3.16-1.56,4.58-2.27l4.54-2.31l6.26-3.2c-1.46,2.46-2.9,4.93-4.38,7.37l0,0l-1.2,1.93l0,0l-0.74,1.19l-0.81,1.27l-0.8,1.23
				l-0.81,1.23l-0.84,1.25l-0.81,1.19l-0.8,1.17l-1,1.37l-1,1.34l-1,1.36l-1,1.34l-1,1.31l-1,1.28l-1,1.3l-1,1.23l-0.89,1.13
				l-0.91,1.12l-0.83,1c-1.23,1.47-2.48,2.91-3.75,4.35c-1.61-1.75-2.93-3.25-4.43-5.08l-0.21-0.26l0,0l-0.24-0.29l0,0l-0.23-0.28
				l-1.09-1.31l0,0l-1.2-1.49l0,0l-2-2.64l-1-1.29l-1-1.34l-1-1.36l-1-1.35l-1-1.35l-0.81-1.16l-0.82-1.2l-0.777-1.035l-0.82-1.23
				l-0.77-1.18l-0.77-1.18l-0.78-1.23l-0.76-1.21c-0.031-0.058-0.064-0.115-0.1-0.17l0,0l-0.09-0.17c-0.08-0.12-0.77-1.25-0.77-1.27
				V81.138z M82.802,61.223l-0.6,1.44l-3.58-0.27l-2.36-0.17l-2.4-0.16l-2.42-0.15l-2.09-0.13l-2.09-0.12l-2.11-0.11l-15.12-0.7
				c8.27-1.79,10.72-2.61,19.06-4.07c1.17-0.21,8.3-1.51,9.77-1.74c1.32-0.2,2.62-0.39,3.89-0.56l2.83-0.38l-1.51,3.95l0,0
				c0,0-1.28,3.14-1.28,3.16L82.802,61.223z M89.972,40.223l-1.88,6l-3.34-0.39l-1.93-0.22l-4-0.4l-2.31-0.22l-2.32-0.19l-2.26-0.18
				l-2.21-0.16l-2.32-0.14l0,0l-2.32-0.13l-2.35-0.11l-2.36-0.09l-2.31-0.07l-2.3-0.05h-2.31h-4.64h-2.31l-2.29,0.05l-2.3,0.07l0,0
				l-2.33,0.1l-2.33,0.11l-2.29,0.13l-2.3,0.15l0,0l-2.28,0.17l-2.33,0.2l-2.37,0.21l-2.35,0.23l-2,0.22l-2,0.22l-2,0.25l-3.41,0.43
				l-0.64-2c-0.59-1.86-1.18-3.71-1.79-5.8l-0.49-1.68l0,0c-0.32-1.13-0.63-2.27-0.94-3.41l-0.4-1.53l0,0l-0.38-1.47l0,0
				c-0.56-2.33-1.07-4.59-1.55-6.94l3.11-0.74l1.39-0.34l1.06-0.28l1-0.28l2.24-0.62l2.21-0.67l1-0.31l2-0.67l1-0.36l2.07-0.75l1-0.37
				l1-0.39l1-0.41l1-0.41l1-0.42l1-0.44l1-0.44l1-0.46l2-1l1-0.49l1-0.49l0.6-0.3l0.59-0.31c0.88-0.47,1.75-1,2.63-1.45l1.85,3.3
				c0.32,0.56,0.66,1.15,1,1.76s0.63,1.08,1,1.71l1,1.66c0.407,0.667,0.837,1.333,1.29,2c0.46,0.66,0.9,1.28,1.32,1.84
				s0.86,1.12,1.34,1.69s0.91,1.06,1.37,1.53l0.1,0.1l0.1-0.1c0.48-0.5,0.93-1,1.37-1.53s0.88-1.1,1.32-1.7s0.85-1.18,1.31-1.85
				s0.89-1.35,1.29-2s0.69-1.12,1-1.68l1-1.73l1-1.77l1.83-3.28l2,1.12l0.59,0.31l0,0l1.19,0.6l1,0.49l1,0.48l2,0.95l1,0.45l1,0.44
				l1,0.43l1,0.41l1,0.41l1,0.4l2,0.76l1,0.37l1,0.35l1,0.35l1.05,0.35l1.07,0.34l1,0.33l1,0.3l2.38,0.67l2.16,0.58l1.12,0.28
				l1.43,0.35l3.07,0.72l-0.26,1.34c-0.86,4.08-1.85,8-2.95,12l0,0c-0.27,1-0.82,2.57-1,3.48V40.223z M18.972,60.753l0.59,1.43
				l1.47-0.45c1.813-0.56,3.62-1.1,5.42-1.62s3.563-1.02,5.29-1.5c1.68-0.47,3.4-0.93,5.16-1.4l5.12-1.32l1.91-0.48l3.66-0.9
				l17.39-4.15l-15.85,0.33l-2.17,0.06l-2.2,0.07l-2.2,0.09l-2.19,0.11c-1.93,0.1-3.82,0.22-5.64,0.35s-3.67,0.3-5.62,0.48
				s-3.78,0.38-5.51,0.58s-3.57,0.44-5.38,0.69l-2.11,0.3c0.54,1.41,1.07,2.82,1.62,4.23C18.122,58.653,18.502,59.603,18.972,60.753
				L18.972,60.753z M19.129,76.078l0.933,1.875c0.24,0.46,0.5,1,0.78,1.47l0.8,1.48l0.82,1.47l0.81,1.42l0.85,1.47l0.86,1.45l0.85,1.38
				l0.84,1.37l0.2,0.32c1,1.68,2.12,3.31,3.2,4.95c0.5,0.74,1,1.44,1.53,2.17c0.57,0.83,1.16,1.66,1.75,2.48l0.59,0.83l1,1.38
				l0.15,0.21l0.22,0.28c0.18,0.24,0.37,0.48,0.54,0.73l0.5,0.67c0.177,0.189,0.338,0.393,0.48,0.61l0.27,0.38
				c0.072,0.072,0.139,0.149,0.2,0.23c0.48,0.62,1.01,1.287,1.59,2c0.6,0.74,1.14,1.4,1.62,2c0.48,0.6,1,1.23,1.63,1.94
				s1.14,1.32,1.65,1.89c0.51,0.57,1.14,1.28,1.67,1.86s1.14,1.24,1.69,1.82l1.68,1.74l1.71,1.72l0.57,0.56l0.57-0.57
				c0.57-0.56,1.14-1.14,1.71-1.73s1.21-1.26,1.67-1.75l1.69-1.83l1.65-1.85l1.64-1.9l1.63-1.95c0.48-0.58,1-1.24,1.6-2
				s1.1-1.38,1.59-2l1.5-2l1.47-2c0.38-0.507,0.86-1.173,1.44-2c0.48-0.68,1-1.36,1.42-2c0.42-0.64,0.88-1.29,1.25-1.84
				s0.81-1.22,1.22-1.86l1.2-1.87c0.3-0.48,0.71-1.13,1.22-2l0.5-0.81l0.2-0.35c0.16-0.27,0.31-0.51,0.43-0.68l0.48-0.81l1.37-2.39
				l1.32-2.36l0.31-0.56c0.31-0.55,0.6-1.1,0.89-1.63c0.18-0.34,0.49-0.92,0.92-1.77s0.7-1.34,0.93-1.8c0.23-0.46,0.61-1.21,0.91-1.82
				c0.3-0.61,0.65-1.31,0.87-1.77c0.14-0.3,0.43-0.91,0.83-1.78c0.27-0.57,0.54-1.16,0.83-1.8c0.39-0.87,0.75-1.66,1.06-2.38
				s0.65-1.53,1-2.39c0.35-0.86,0.71-1.71,1-2.38c0.28-0.69,0.59-1.49,0.93-2.38l0.9-2.4c0.32-0.88,0.6-1.68,0.84-2.37
				s0.47-1.38,0.81-2.39s0.53-1.66,0.77-2.41l0.48-1.57l0.68-2.19l0.61-2l0.54-1.81l0.53-1.82l0.52-1.85l0.25-0.89
				c0.306-0.945,0.559-1.907,0.76-2.88l0.52-2l0.22-0.9l0.41-1.76c0.21-0.95,0.41-1.91,0.62-2.87l0.38-1.92l0.17-0.92l0.31-1.81
				l0.29-1.88l0.13-0.94l0.21-1.58l-1.43-0.23l-1.31-0.21l-1.32-0.25c-0.4-0.08-0.83-0.16-1.31-0.27l-1.34-0.29l-1.32-0.3l-1.33-0.33
				c-0.39-0.09-0.84-0.21-1.35-0.35l-1.38-0.38l-1.35-0.4c-0.43-0.12-0.88-0.26-1.34-0.41l-1.37-0.44l-1.37-0.46l-1.36-0.47l-1.37-0.51
				c-0.48-0.17-0.92-0.34-1.33-0.5s-0.94-0.36-1.32-0.52l-1.34-0.55l-1.32-0.57l-1.31-0.57l-1.27-0.59l-1.25-0.59l-1.24-0.61
				l-1.25-0.64l-1.22-0.64l-1.17-0.65l-1.15-0.66l-1.1-0.64l-1.07-0.67l-1-0.65l-1-0.66c-0.3-0.21-0.61-0.42-0.93-0.66
				c-0.32-0.24-0.57-0.4-0.9-0.66l-1.33-0.95l-0.14,0.32c-0.61,1.44-1.21,2.86-1.73,4.06s-1.14,2.59-1.74,3.86l-0.62,1.31l-0.63,1.26
				c-0.15,0.3-0.36,0.7-0.64,1.23l-0.67,1.21l-1.47,2.59l-1.48-2.58c-0.21-0.36-0.43-0.76-0.67-1.2c-0.24-0.44-0.43-0.81-0.65-1.24
				s-0.43-0.84-0.64-1.27c-0.21-0.43-0.43-0.88-0.63-1.3c-0.31-0.65-0.6-1.28-0.88-1.9s-0.59-1.31-0.87-1.95l-1.86-4.39l-1.29,1
				l-0.89,0.65l-0.94,0.67l-1,0.67l-1,0.66l-1.09,0.68l-1.11,0.66c-0.32,0.19-0.69,0.41-1.13,0.65s-0.72,0.41-1.15,0.64l-1.2,0.65
				l-1.22,0.63l-1.25,0.62l-1.28,0.62l-1.28,0.59l-1.29,0.59l-1.32,0.56c-0.6,0.26-1.05,0.45-1.34,0.56l-1.35,0.54
				c-0.41,0.17-0.87,0.34-1.36,0.53l-1.35,0.48l-1.34,0.48l-1.37,0.46l-1.36,0.44l-1.35,0.43l-1.34,0.39l-1.34,0.38l-1.33,0.36
				l-1.36,0.34l-1.34,0.32l-1.33,0.29l-1.33,0.28l-1.3,0.24c-0.51,0.1-1,0.17-1.32,0.23l-1.43,0.25l0.22,1.57l0.28,1.89l0.14,0.9
				l0.33,1.84l0.17,0.91c0.13,0.67,0.26,1.31,0.39,1.93l0.42,1.94l0.2,0.89l0.43,1.78l0.48,1.91l0.26,1l0.52,2l0.5,1.8l0.56,1.93
				l0.53,1.82l0.53,1.74l0.6,2l1.18,3.76l0.77,2.37c0.24,0.72,0.52,1.51,0.82,2.37c0.3,0.86,0.56,1.58,0.87,2.4s0.64,1.73,0.91,2.42
				c0.27,0.69,0.61,1.55,0.94,2.39c0.33,0.84,0.68,1.65,1,2.37c0.32,0.72,0.62,1.44,1,2.37l0.9,2l0.89,1.81l0.21,0.46l0.37,0.76
				c0.049,0.098,0.092,0.198,0.13,0.3c0.32,0.71,0.63,1.35,0.94,1.95s0.65,1.27,1,2L19.129,76.078z M43.402,1.163L43.402,1.163z
				 M53.402,77.593c0,0,9.55-4.72,9.76-4.83l1-0.46l0.49-0.22l6.43-2.93l-7.74-0.34l-1.85-0.08l-2.53-0.08l-2.55-0.06h-2.58h-2.6l0,0
				h-2.65l-2.62,0.06l-2.61,0.07l-2.59,0.1c-3.13,0-6.6,0.31-9.72,0.46l7.18,3.13l1.45,0.68c1.72,0.853,3.443,1.693,5.17,2.52l0,0
				c2.15,1,4.29,2,6.45,3L53.402,77.593z" />
    </svg>
</div>
<script src="<?=get_stylesheet_directory_uri()?>/assets/js/preloader.js"></script>
<!-- /preloader -->
	<!-- wrapper -->
	<div class="wrapper">
		
<!-- переучёт --
<style>.note{background-color: red;color: #fff;padding: 10px 0;display: flex;align-items: center;height: 50px;position: fixed;top: 0;z-index: 50;width: 100%;}
.note p{margin: 0;font-size: 18px;}
.note ~ header .rs-header._header-fixed{top: 50px;}
@media (max-width: 768px) {
.note p{font-size: 14px;}}

</style>
<div class="note"><div class="rs-header__container"><p>Ведутся технические работы. Сайт доступен в режиме просмотра. Оформление заказов приостановлено до 02:01.2025</p></div></div>
-- / переучёт -->		
		
		<!-- rs-header -->

		<header>
			<div class="rs-header <? 
				if( 
					is_shop() || is_product_category() || is_product_tag() || is_product() // страницы WooCommerce
					|| $page_template_slug=='template-clients.php' // страница "Клиентам"
					|| $page_template_slug=='template-store.php' // страница "Магазин"*/
					|| $page_template_slug=='template-wishlist.php' // страницы со стилями WooCommerce (cart, checkout, my-account)
					|| $page_template_slug=='template-woostyle.php' // страницы со стилями WooCommerce (cart, checkout, my-account)
					|| $page_template_slug=='page-about.php' // Бренд
					|| is_singular('collections') || is_post_type_archive( 'collections' ) // Коллекции
					/*|| is_singular('media')*/ || is_post_type_archive( 'news' ) // Медиа
					|| is_search() // Поиск
					// || is_404()
				) { 
					?>_black-header<? 
				} else { 
					if(is_404()){
						?>rs-header--white _white-header _bg-no<?
					} else {
						?>rs-header--white _white-header<? 
					}
				}?> _header-fixed">
				<div class="rs-header__container">
					<button type="button" class="menu__icon icon-menu">
						<svg width="19" height="13" viewBox="0 0 19 13" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_9_67)"><path d="M1.29541 1.38635H17.7045" stroke="black" stroke-width="1.5" stroke-linecap="round"/><path d="M1.29541 6.38635H17.7045" stroke="black" stroke-width="1.5" stroke-linecap="round"/><path d="M1.29541 11.3864H17.7045" stroke="black" stroke-width="1.5" stroke-linecap="round"/></g><defs><clipPath id="clip0_9_67"><rect width="19" height="13" fill="white"/></clipPath></defs></svg>
						<?/*<span></span><span></span>*/?>
					</button>
					<div class="rs-header__logo">
						<a href="<?=get_home_url()?>">
							<img class="no-lazy" src="<? $custom_logo_id = get_theme_mod( 'custom_logo' ); if(!$custom_logo_id || $custom_logo_id=='') $logo = get_stylesheet_directory_uri().'/assets/img/logo.svg'; else $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' )[0]; 
							if(isset($_SERVER['HTTPS'])) { if ($_SERVER['HTTPS'] == "on") { $logo = str_replace('http://','https://',$logo); } } echo $logo?>" alt="<?=get_bloginfo('name')?>">
						</a>
					</div>
					<div class="rs-header__menu menu">
						<?php 
						wp_nav_menu(
							array( 
								'theme_location' 	=> 'primary',
								'container'       	=> 'nav',
								'container_class' 	=> 'menu__body',
								'menu_class'      	=> 'menu__list'
							) 
						); 
						?>
					</div>
					<!-- <div class="button_box">
					<a href="#">Парикмахерская «Под Феном»</a>
					</div> -->
					<div class="rs-header__function">
						<div class="rs-header__buttons" data-da=".rs-header__menu .menu__body, 992, last">
							<div class="rs-header__account">
								<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account','storefront'); ?>"><?php _e('My Account','storefront'); ?></a>
							</div>
							<?php
							if(function_exists('pll_the_languages')) {
								$langs_array = pll_the_languages( array( 'hide_current' => false, 'raw' => true ) );
								if ($langs_array) : ?>
								<ul class="rs-header__language">
									<?php foreach ($langs_array as $lang) : ?>
									<li><a href="<?php echo $lang['url']; ?>"><?php echo $lang['slug']; ?></a></li>
									<?php endforeach; ?>
								</ul>
								<?php endif;
							} else {
								?><ul class="rs-header__language"><?
									?><li><?php echo do_shortcode('[gt-link lang="ru" label="RU" widget_look="lang_codes"]');?></li><?php
									?><li><?php echo do_shortcode('[gt-link lang="en" label="EN" widget_look="lang_codes"]');?></li><?php
								?></ul><?
							} ?>
						</div>
						<div class="rs-header__search">
							<button id="search-show" class="search-btn icon-search svg-icon" type="button">
                                <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_809_239)">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.69511 14.2662C11.3265 14.2662 14.2662 11.3265 14.2662 7.69511C14.2662 4.06371 11.3265 1.124 7.69511 1.124C4.06371 1.124 1.124 4.06371 1.124 7.69511C1.124 11.3265 4.15017 14.2662 7.69511 14.2662ZM15.4767 7.69511C15.4767 11.9317 12.0182 15.3902 7.78157 15.3902C3.54494 15.3902 0 12.0182 0 7.69511C0 3.37201 3.45847 0 7.69511 0C11.9317 0 15.4767 3.45847 15.4767 7.69511Z" fill="black"/>
                                        <path d="M12 12.8132L12.8839 11.9293L18.8059 17.8514C19.05 18.0954 19.05 18.4912 18.8059 18.7353C18.5618 18.9793 18.1661 18.9793 17.922 18.7353L12 12.8132Z" fill="black"/>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_809_239">
                                            <rect width="19" height="19" fill="white"/>
                                        </clipPath>
                                    </defs>
                                </svg>
							</button>
						</div>
						<ul class="rs-header__action">
							<li><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" class="icon-person svg-icon">
                                    <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_809_235)">
                                            <circle cx="9" cy="5" r="4.425" stroke="black" stroke-width="1.15"/>
                                            <path d="M0.575 17.5455C0.575 14.2481 3.24806 11.575 6.54545 11.575H12.4545C15.7519 11.575 18.425 14.2481 18.425 17.5455C18.425 18.0312 18.0312 18.425 17.5455 18.425H1.45455C0.968786 18.425 0.575 18.0312 0.575 17.5455Z" stroke="black" stroke-width="1.15"/>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_809_235">
                                                <rect width="19" height="19" fill="white"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
							</a></li>
							<? if ( class_exists( 'WooCommerce' ) ) {?>
							<li><a href="<?=YITH_WCWL()->get_wishlist_url('view')?>" class="icon-heart svg-icon">
								<?php $count = YITH_WCWL()->count_all_products(); ?>
									<svg width="21" height="19" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="enable-background:new 0 0 21 19" viewBox="0 0 21 19"><path d="M10.6 18.4C-8.2 6.5 4.8-2.9 10.3 2.1l.2.2.2-.2c5.7-5 18.6 4.4-.1 16.3z" style="fill:<?php if($count>0) { echo '#000'; } else echo 'none'; ?>;stroke:#000;stroke-width:1.15"/></svg>
								<?php if($count>0) { ?><span><?=$count?></span><? } ?>
							</a></li>
							<?storefront_header_cart_child()?>
							<? } ?>
						</ul>
					</div>
				</div>
			</div>
			<div class="search">
				<div class="search__overlay">
					<div class="cursor">
						<div class="cursor__point-zero">
							<div class="cursor__wrapper">
								<div class="cursor__circle icon-close"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="search__wrapper">
					<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search__form">
						<div class="search__field">
							<button id="search-submit" class="search__submit icon-search svg-icon" type="submit">
                                <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_809_239)">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.69511 14.2662C11.3265 14.2662 14.2662 11.3265 14.2662 7.69511C14.2662 4.06371 11.3265 1.124 7.69511 1.124C4.06371 1.124 1.124 4.06371 1.124 7.69511C1.124 11.3265 4.15017 14.2662 7.69511 14.2662ZM15.4767 7.69511C15.4767 11.9317 12.0182 15.3902 7.78157 15.3902C3.54494 15.3902 0 12.0182 0 7.69511C0 3.37201 3.45847 0 7.69511 0C11.9317 0 15.4767 3.45847 15.4767 7.69511Z" fill="black"/>
                                        <path d="M12 12.8132L12.8839 11.9293L18.8059 17.8514C19.05 18.0954 19.05 18.4912 18.8059 18.7353C18.5618 18.9793 18.1661 18.9793 17.922 18.7353L12 12.8132Z" fill="black"/>
                                    </g>
                                </svg>
							</button>
							<input autocomplete="off" type="text" placeholder="<?php _e( 'Search...', 'storefront' ); ?>" class="search__input asd" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" required>
							<button type="submit" id="search-clear" class="search__clear icon-close"></button>
						</div>
					</form>
				</div>
			</div>
		</header>
		<!-- /rs-header -->

		<!-- page -->
		<main class="page<?if(
			$page_template_slug=='page-about.php'
			|| is_singular('collections') || is_post_type_archive( 'collections' ) // Коллекции
		) echo ' _page-container _page-container-no-border'?>">

<!-- Улучшенный JavaScript для бегущей строки -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const headerTopLine = document.querySelector('.header_top_line');
    const line = document.getElementById('runningLine');
    
    if (!line || !headerTopLine) return;

    const items = Array.from(line.children);
    const originalItems = [...items];

    // Функция для создания достаточного количества элементов для бесшовной анимации
    function duplicateItems() {
        // Очищаем контейнер
        line.innerHTML = '';
        
        // Добавляем оригинальные элементы
        originalItems.forEach(item => {
            line.appendChild(item.cloneNode(true));
        });
        
        // Дублируем элементы много раз для создания непрерывного потока
        const screenWidth = window.innerWidth;
        const itemWidth = 288 + 48; // min-width + gap
        const itemsNeeded = Math.ceil(screenWidth / itemWidth) + originalItems.length + 3;
        
        for (let i = 0; i < itemsNeeded; i++) {
            originalItems.forEach(item => {
                line.appendChild(item.cloneNode(true));
            });
        }
    }

    // Инициализация
    duplicateItems();

    // Переменная для отслеживания состояния
    let isHovered = false;

    // Функции для управления анимацией
    function pauseAnimation() {
        if (!isHovered) {
            isHovered = true;
            line.style.animationPlayState = 'paused';
        }
    }

    function resumeAnimation() {
        if (isHovered) {
            isHovered = false;
            line.style.animationPlayState = 'running';
        }
    }

    // Обработчики для паузы при наведении - навешиваем на контейнер
    headerTopLine.addEventListener('mouseenter', pauseAnimation);
    headerTopLine.addEventListener('mouseleave', resumeAnimation);

    // Дополнительная защита
    headerTopLine.addEventListener('mouseover', pauseAnimation);
    headerTopLine.addEventListener('mouseout', function(e) {
        // Проверяем, что мышь действительно покинула контейнер
        if (!headerTopLine.contains(e.relatedTarget)) {
            resumeAnimation();
        }
    });

    // Пересоздание при изменении размера окна
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            duplicateItems();
        }, 250);
    });

    // Запускаем анимацию сразу
    line.style.animationPlayState = 'running';
});
</script>

<style>

.header_top_line:hover .running-line {
    animation-play-state: paused !important;
}

.running-line {
    pointer-events: none;
}

.running-line .text-item {
    pointer-events: auto;
}

	/* от 1980px и выше */
#menu-item-155075 {
    position: relative;
    left: 77%;
}

/* от 1889px до 1979px */
@media (max-width: 1979px) {
    #menu-item-155075 {
        left: 73%;
    }
}

/* от 1846px до 1888px */
@media (max-width: 1888px) {
    #menu-item-155075 {
        left: 72%;
    }
}

/* от 1800px до 1845px */
@media (max-width: 1845px) {
    #menu-item-155075 {
        left: 70%;
    }
}

/* от 1750px до 1799px */
@media (max-width: 1799px) {
    #menu-item-155075 {
        left: 58%;
    }
}

/* от 1700px до 1749px */
@media (max-width: 1749px) {
    #menu-item-155075 {
        left: 57%;
    }
}

/* от 1650px до 1699px */
@media (max-width: 1699px) {
    #menu-item-155075 {
        left: 53%;
    }
}

/* от 1600px до 1649px */
@media (max-width: 1649px) {
    #menu-item-155075 {
        left: 50%;
    }
}

/* от 1550px до 1599px */
@media (max-width: 1599px) {
    #menu-item-155075 {
        left: 40%;
    }
}

/* от 1500px до 1549px */
@media (max-width: 1549px) {
    #menu-item-155075 {
        left: 39%;
    }
}

/* от 1450px до 1499px */
@media (max-width: 1499px) {
    #menu-item-155075 {
        left: 31%;
    }
}

/* от 1400px до 1449px */
@media (max-width: 1449px) {
    #menu-item-155075 {
        left: 28%;
    }
}

/* от 1350px до 1399px */
@media (max-width: 1399px) {
    #menu-item-155075 {
        left: 20%;
    }
}

/* от 1300px до 1349px */
@media (max-width: 1349px) {
    #menu-item-155075 {
        left: 14%;
    }
}

/* от 1250px до 1299px */
@media (max-width: 1299px) {
    #menu-item-155075 {
        left: 11%;
    }
}

/* от 1200px до 1249px */
@media (max-width: 1249px) {
    #menu-item-155075 {
        left: 0%;
    }
}

/* от 1150px до 1199px */
@media (max-width: 1199px) {
    #menu-item-155075 {
        left: 0%;
    }
}

/* от 1100px до 1149px */
@media (max-width: 1149px) {
    #menu-item-155075 {
        left: 0%;
    }
}



</style>