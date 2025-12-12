/* ====================================
Инициализация слайдера в блоке rs-podcast
==================================== */
function initPodcastSliders() {
	if (document.querySelector('.rs-podcast__slider')) {
		// Перечень слайдеров
		new Swiper('.rs-podcast__slider', {
			// Автопрокрутка
			autoplay: {
				// Пауза между прокруткой
				delay: 10000,
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
				prevEl: ".rs-podcast__button-prev",
				nextEl: ".rs-podcast__button-next",
			},

			// Брейкпоинты(адаптив)
			// Шрина экрана
			breakpoints: {
				320: {
					slidesPerView: 2.2,
					spaceBetween: 15,
					centeredSlides: false,
				},
				768: {
					slidesPerView: 3,
					spaceBetween: 30,
					centeredSlides: false,
				},
				1070: {
					slidesPerView: 4,
					spaceBetween: 35,
					centeredSlides: false,
				},
				1450: {
					slidesPerView: 5,
					spaceBetween: 47,
					centeredSlides: false,
				}
			},
		});
	}
}

window.addEventListener("load", function (e) {
	// Запуск инициализации слайдеров
	initPodcastSliders();
});