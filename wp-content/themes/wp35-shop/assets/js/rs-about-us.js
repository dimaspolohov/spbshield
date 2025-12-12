/* ====================================
Инициализация слайдера в блоке rs-about-us
==================================== */
function initAboutSliders() {
	if (document.querySelector('.rs-about-us__slider')) {
		// Перечень слайдеров
		new Swiper('.rs-about-us__slider', {
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
			mousewheel: {
				// Чувствительность колеса мыши
				sensitivity: 1,
			},

			// Включение/отключение
			// перетаскивание на ПК
			simulateTouch: true,
			// Чувствительность свайпа
			touchRadio: 1,
			// Угол срабатывания свайпа/перетаскивания
			touchAngle: 45,

			pagination: {
				el: '.rs-about-us__pagination',
				clickable: true,
				type: "progressbar",
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
					slidesPerView: 1.84,
					spaceBetween: 35,
					centeredSlides: false,
				},
			},
		});
	}
}

window.addEventListener("load", function (e) {
	// Запуск инициализации слайдеров
	initAboutSliders();
});