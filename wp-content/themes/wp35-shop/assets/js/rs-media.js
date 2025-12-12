/* ====================================
Инициализация слайдера в блоке rs-media__slider
==================================== */
function initMediaSliders() {
	if (document.querySelector('.rs-media__slider')) {
		'use strict';
		const breakpoint = window.matchMedia('(min-width: 993px)');

		let MediaSlider;
		const breakpointChecker = function () {
			if (breakpoint.matches === true) {
				if (MediaSlider !== undefined) MediaSlider.destroy(true, true);
				return;
			} else if (breakpoint.matches === false) {
				return enableSwiper();
			}
		};

		const enableSwiper = function () {
			MediaSlider = new Swiper('.rs-media__slider', {
				// // Автопрокрутка
				// autoplay: {
				// 	// Пауза между прокруткой
				// 	delay: 5000,
				// 	// Закончить на последнем слайде
				// 	stopOnLastSlide: false,
				// 	// Отключить после ручного переключения
				// 	disableOnInteraction: false,
				// },
				// Обновить свайпер
				// при изменении элементов слайдера
				observer: true,
				// при изменении родительских элементов слайдера
				observeParents: true,
				// при изменении дочерних элементов слайдера
				observeSlideChildren: true,
				// Скорость смены слайдов
				speed: 500,
				// Курсор перетаскивания
				grabCursor: true,
				// Включение/отключение
				// перетаскивание на ПК
				simulateTouch: true,
				// Чувствительность свайпа
				touchRadio: 1,
				// Угол срабатывания свайпа/перетаскивания
				touchAngle: 45,
				grabCursor: true,

				// Управлениt колесом мыши
				mousewheel: {
					// Чувствительность колеса мыши
					sensitivity: 1,
					releaseOnEdges: true,
					forceToAxis: true,
				},

				// Брейкпоинты(адаптив)
				// Шрина экрана
				breakpoints: {
					320: {
						slidesPerView: 1.2,
						spaceBetween: 15,
						centeredSlides: false,
					},
					768: {
						slidesPerView: 2,
						spaceBetween: 20,
						centeredSlides: false,
					},
					992: {
						slidesPerView: 3,
						spaceBetween: 20,
						centeredSlides: false,
					},
				},
			});
		};

		breakpoint.addListener(breakpointChecker);
		breakpointChecker();
	}
}
window.addEventListener("load", function (e) {
	// Запуск инициализации слайдеров
	if(jQuery('.rs-media__slider').length>0) {
		initMediaSliders();
	}
});

/**/


	// load_mediaitems();
	if(jQuery('.post-type-archive .rs-media-news_load_more .rs-media-news__container').length>0) {
		console.log('load - '+jQuery('.rs-media-news_load_more').attr('offset'))
		//load_mediaitems();
	}


jQuery( window ).on('scroll',function(){
//console.log(jQuery('.rs-media-news_load_more .loading-more').length);
	if(jQuery('.rs-media-news').length && jQuery('.rs-media-news_load_more .loading-more').length>0 && jQuery('.rs-media-news_load_more.loading').length==0) {
		var $window = jQuery( window ).height();
		var $top = jQuery('.loading-more').offset().top-$window;
		var $scroll = jQuery(document).scrollTop();
		if($top<=$scroll){
			var $offset = jQuery('.rs-media-news_load_more').attr('offset');
			console.log('scroll - '+jQuery('.rs-media-news_load_more').attr('offset'))
			load_mediaitems( $offset );
		}
	}
});

function load_mediaitems( offset = 3 ) {
	const preloader = document.querySelector('.preloader');
	jQuery('.rs-media-news_load_more').addClass('loading');

	var data = {
		action: 'getMediaitems',
		offset: offset,
		number: 3,
	};
	jQuery.ajax( {
		data:data,
		type:'POST',
		url:'/wp-admin/admin-ajax.php',
		success: function(response,status) {
			var $response = jQuery.parseJSON(response);
			jQuery('.loading-more').remove();
			if(offset==0) {
				jQuery('.rs-media-news_load_more .rs-media-news__container').html($response[0]);
				if (!preloader.classList.contains('_done')) {
					preloader.classList.add('_done');
					document.body.classList.remove('lock');
				}
			} else {
				jQuery('.rs-media-news_load_more .rs-media-news__container').append($response[0]);

			}

			if(offset==0) {
				offset = 3;
			} else {
				offset = parseInt(offset)+3;
			}

			jQuery('.rs-media-news_load_more').attr('offset',offset);
			jQuery('.rs-media-news_load_more').removeClass('loading');
			//console.log(offset,jQuery('.rs-media-news_load_more').attr('offset'))
		}
	});
}