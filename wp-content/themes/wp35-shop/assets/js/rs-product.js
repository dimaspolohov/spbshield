jQuery('body').on('click','button.rs-btn.disabled',function(event){
	event.preventDefault()
});



/* ====================================
Инициализация слайдера в блоке rs-product
==================================== */
function initProductSliders() {
	// Перечень слайдеров
	const thumbsSlider = new Swiper('.rs-thumbs__slider', {
		// // Автовысота
		// autoHeight: true,

		// // Бесконечность
		// loop: true,

		// // Предзагрузка изоражений
		// preloadImages: false,

		// // Ленивая загрузка
		// lazy: true,

		// Слежка за слайдером
		watchOverflow: true,

		// // Автопрокрутка
		// autoplay: {
		// 	// Пауза между прокруткой
		// 	delay: 5000,
		// 	// Закончить на последнем слайде
		// 	stopOnLastSlide: false,
		// 	// Отключить после ручного переключения
		// 	disableOnInteraction: false,
		// },

		// Кол-во показываемых слайдов
		slidesPerView: 8,

		// Отступ между слайдами
		spaceBetween: 20,

		// Обновить свайпер
		// при изменении элементов слайдера
		observer: true,
		// при изменении родительских элементов слайдера
		observeParents: true,
		// при изменении дочерних элементов слайдера
		observeSlideChildren: true,

		// Скорость смены слайдов
		speed: 500,

		// Включение/отключение
		// перетаскивание на ПК
		simulateTouch: true,
		// Чувствительность свайпа
		touchRadio: 1,
		// Угол срабатывания свайпа/перетаскивания
		touchAngle: 45,

		direction: 'vertical',
	});
	
	const productSlider = new Swiper('.rs-product__slider', {
		// Слияние слайдеров
		thumbs: {
			swiper: thumbsSlider
		},

		// // Автовысота
		// autoHeight: true,

		// // Бесконечность
		// loop: true,

		// // Предзагрузка изоражений
		// preloadImages: false,

		// // Ленивая загрузка
		// lazy: true,

		// Слежка за слайдером
		watchOverflow: true,

		// // Автопрокрутка
		// autoplay: {
		// 	// Пауза между прокруткой
		// 	delay: 5000,
		// 	// Закончить на последнем слайде
		// 	stopOnLastSlide: false,
		// 	// Отключить после ручного переключения
		// 	disableOnInteraction: false,
		// },

		// Кол-во показываемых слайдов
		slidesPerView: 1,

		// Отступ между слайдами
		spaceBetween: 30,

		// Обновить свайпер
		// при изменении элементов слайдера
		observer: true,
		// при изменении родительских элементов слайдера
		observeParents: true,
		// при изменении дочерних элементов слайдера
		observeSlideChildren: true,

		// Скорость смены слайдов
		speed: 500,

		// Включение/отключение
		// перетаскивание на ПК
		simulateTouch: true,
		// Чувствительность свайпа
		touchRadio: 1,
		// Угол срабатывания свайпа/перетаскивания
		touchAngle: 45,

		pagination: {
			el: '.rs-product__pagination',
			clickable: true,
		},

		// Брейкпоинты(адаптив)
		// Шрина экрана
		breakpoints: {
			320: {
				direction: 'horizontal',
			},
			992: {
				direction: 'vertical',
			},
		},
	});

	if (document.querySelector('.rs-product__pagination')) {
		const sliderPagination = document.querySelector('.rs-product__pagination')
		window.addEventListener('resize', function () {
			if (window.innerWidth <= 992) {
				sliderPagination.classList.remove('swiper-pagination-vertical')
				sliderPagination.classList.add('swiper-pagination-horizontal')
			} else {
				sliderPagination.classList.add('swiper-pagination-vertical')
				sliderPagination.classList.remove('swiper-pagination-horizontal')
			}
		})
	}
}

initProductSliders();

/**/
jQuery( document ).ready(function() {
	document.querySelector('input[name="variation_id"]').onchange = function() {
		// jQuery('.rs-product__pictures').addClass('loading');
		// jQuery('.rs-product__prices').addClass('loading');
		var $variation_id = jQuery(this).val();
		if($variation_id && $variation_id!=''){
			var data = {
				action: 'getVariationGal',
				product_id: jQuery('input[name="product_id"]').val(),
				variation_id: $variation_id,
			};
			jQuery.ajax( {
				data:data,
				type:'POST',
				url:'/wp-admin/admin-ajax.php',
				success: function(response,status) {
					var response_arr = jQuery.parseJSON(response);
					jQuery('.rs-product__pictures').replaceWith(response_arr[0]);
					jQuery('.rs-product__prices').html(response_arr[1]);
					// jQuery('.rs-product__prices').removeClass('loading');
					initProductSliders();
					initDataGallery();
				}
			});
		}
	}

});

/**/

/* ====================================
Фильтры
==================================== */

function selectColor() {
	const color = document.querySelector('.color');
	if(color){
		const colorTitle = color.querySelector('[data-title]');
		const colorSelects = color.querySelectorAll('[data-select]');
		var select = document.querySelector('select[name="attribute_pa_color"]');
		colorSelects.forEach(color => {
			color.addEventListener('click', function () {
				colorTitle.textContent = color.dataset.select;
				if(color.dataset.val) {
					select.value = color.dataset.val;
				} 
			})
		});
	}
}
selectColor()

function selectSize() {
	const size = document.querySelector('.size');
	if(size){
		const sizeTitle = size.querySelector('[data-title]');
		const sizeSelects = size.querySelectorAll('[data-select]');
		var select = document.querySelector('select[name="attribute_pa_size"]');
		sizeSelects.forEach(size => {
			size.addEventListener('click', function () {
				sizeTitle.textContent = size.dataset.select;
				if(size.dataset.val) {
					select.value = size.dataset.val;
				}
			})
		});
	}
}
selectSize()