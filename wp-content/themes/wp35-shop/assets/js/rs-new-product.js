
/* ====================================
Инициализация слайдера в блоке rs-new-product__slider
==================================== */
function initNewProductSliders() {
	if (document.querySelector('.rs-new-product__slider')) {
		'use strict';
		const breakpoint = window.matchMedia('(min-width: 1521px)');
		let NewProductSlider;
		const breakpointChecker = function () {
			if (breakpoint.matches === true) {
				if (NewProductSlider !== undefined) NewProductSlider.destroy(true, true);
				return;
			} else if (breakpoint.matches === false) {
				return enableSwiper();
			}
		};

		const enableSwiper = function () {
			NewProductSlider = new Swiper('.rs-new-product__slider', {
				// Автопрокрутка
				autoplay: {
					// Пауза между прокруткой
					delay: 5000,
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

				// Навигация
				navigation: {
					prevEl: ".rs-new-product__button-prev",
					nextEl: ".rs-new-product__button-next",
				},

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
		};

		breakpoint.addListener(breakpointChecker);
		breakpointChecker();
	}
}
window.addEventListener("load", function (e) {
	// Запуск инициализации слайдеров
	initNewProductSliders();
});