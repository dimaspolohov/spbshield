/* ====================================
Инициализация слайдера в блоке product
==================================== */
function initrepresentativesSliders() {
	const representativesSlider = document.querySelectorAll('.rs-representatives__slider');
	representativesSlider.forEach(product => {
		// Перечень слайдеров
		new Swiper(product, {
			// Автопрокрутка
			// autoplay: {
			// 	// Пауза между прокруткой
			// 	delay: 10000,
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
			speed: 300,

			// Включение/отключение
			// перетаскивание на ПК
			simulateTouch: true,
			// Чувствительность свайпа
			touchRadio: 1,
			// Угол срабатывания свайпа/перетаскивания
			touchAngle: 45,

			slidesPerView: 4,
			spaceBetween: 30,

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
				1450: {
					slidesPerView: 4,
					spaceBetween: 30,
					centeredSlides: false,
				}
			},
		});
	});
}
window.addEventListener("load", function (e) {
	// Запуск инициализации слайдеров
	initrepresentativesSliders();
});