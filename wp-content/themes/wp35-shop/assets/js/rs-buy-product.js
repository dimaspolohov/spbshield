/* ====================================
Инициализация слайдера в блоке rs-buy-product
==================================== */
function initBuyProductSliders() {
	if (document.querySelector('.rs-buy-product__slider')) {
		// Перечень слайдеров
		new Swiper('.rs-buy-product__slider', {
			// Автопрокрутка
			autoplay: {
				// Пауза между прокруткой
				delay: 3000,
				// Закончить на последнем слайде
				stopOnLastSlide: false,
				// Отключить после ручного переключения
				disableOnInteraction: false,
			},

			// Обновить свайпер
			// при изменении элементов слайдера
			observer: true,
			// при изменении родительских элементов слайдера
			observeParents: true,
			// при изменении дочерних элементов слайдера
			observeSlideChildren: true,

			// Скорость смены слайдов
			speed: 500,

			// Управлениt колесом мыши
			/* mousewheel: {
				// Чувствительность колеса мыши
				sensitivity: 1,
			}, */

			on: {
				init() {
					this.el.addEventListener('mouseenter', () => {
						this.autoplay.stop();
					});

					this.el.addEventListener('mouseleave', () => {
						this.autoplay.start();
					});
				}
			},

			// Включение/отключение
			// перетаскивание на ПК
			simulateTouch: true,
			// Чувствительность свайпа
			touchRadio: 1,
			// Угол срабатывания свайпа/перетаскивания
			touchAngle: 45,

			slidesPerView: 4,

			// Навигация
			navigation: {
				prevEl: ".rs-buy-product__button-prev",
				nextEl: ".rs-buy-product__button-next",
			},

			// Брейкпоинты(адаптив)
			// Шрина экрана
			breakpoints: {
				320: {
					slidesPerView: 1,
					spaceBetween: 15,
					centeredSlides: false,
				},
				375: {
					slidesPerView: 1.7,
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
				1450: {
					slidesPerView: 4,
					spaceBetween: 30,
					centeredSlides: false,
				}
			},
		});
	}
}

window.addEventListener("load", function (e) {
	// Запуск инициализации слайдеров
	initBuyProductSliders();
});