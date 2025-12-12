/* ====================================
Инициализация слайдера в блоке rs-slider
==================================== */
function initMainSliders() {
	if (document.querySelector('.rs-slider__slider')) {
		// Перечень слайдеров
		const slider = new Swiper('.rs-slider__slider', {
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

			// Включение/отключение
			// перетаскивание на ПК
			simulateTouch: true,
			// Чувствительность свайпа
			touchRadio: 1,
			// Угол срабатывания свайпа/перетаскивания
			touchAngle: 45,

			// Навигация
			navigation: {
				prevEl: ".rs-slider__button-prev",
				nextEl: ".rs-slider__button-next",
			},

			// Пагинация
			pagination: {
				el: '.rs-slider__pagination',
				clickable: true,
			},

			loop: true,
			watchSlidesProgress: true,
			slidesPerView: 'auto',
			grabCursor: true,

			effect: "fade",

			on: {
				init() {
					this.el.addEventListener('mouseenter', () => {
						this.autoplay.stop();
					});

					this.el.addEventListener('mouseleave', () => {
						this.autoplay.start();
					});
					let color = this.el.querySelector('.swiper-slide-active').getAttribute('data-color');
					this.el.classList.remove("color-black", "color-white");
					this.el.classList.add(`color-${color}`);
				},
				slideChangeTransitionEnd: function () {
					let color = this.el.querySelector('.swiper-slide-active').getAttribute('data-color');
					this.el.classList.remove("color-black", "color-white");
					 this.el.classList.add(`color-${color}`);
				}
			},



			// Управлениt колесом мыши
			mousewheel: {
				// Чувствительность колеса мыши
				sensitivity: 1,
				releaseOnEdges: true,
				forceToAxis: true,
			},
		});

		// Инициализировать слайд 3 в мобильной версии
		function addSlides() {
			if (window.innerWidth <= 992) {
				const slider3 = document.querySelector('.rs-slider__slide-4');
				if (slider3) {
					if (window.innerWidth <= 992 && !slider3.classList.contains('swiper-slide')) {
						slider3.classList.add('swiper-slide')
					}
				}
			}
		}
		addSlides()
		window.addEventListener('resize', addSlides)
	}
}

window.addEventListener("load", function (e) {
	// Запуск инициализации слайдеров
	initMainSliders();
});


(function () {
	var supportsVideo = !!document.createElement('video').canPlayType;
	if (supportsVideo) {
		var videoContainer = document.querySelector('.js-bgvideo-container');
		var video = document.querySelector('.js-bgvideo');
		var videoControls = document.querySelector('.js-bgvideo-controls');

		// Hide the default controls
		if (video) {
			video.controls = false;
		}

		// Display the own defined video controls
		if (videoControls) {
			videoControls.style.display = 'flex';
		}

		var playpause = document.getElementById('bgvideoPlaypause');
		var stop = document.getElementById('bgvideoStop');
		var mute = document.getElementById('bgvideoMute');
		var playIcon = document.querySelector('.bgvideo__control .video-icon--play');
		var pauseIcon = document.querySelector('.bgvideo__control .video-icon--pause');
		var speakerIcon = document.querySelector('.bgvideo__control .video-icon--speaker');
		var muteIcon = document.querySelector('.bgvideo__control .video-icon--mute');

		if (playpause) {
			playpause.addEventListener('click', function (e) {
				playIcon.classList.toggle('is-active');
				pauseIcon.classList.toggle('is-active');
				if (video.paused || video.ended) video.play();
				else video.pause();
			});
		}

		if (stop) {
			stop.addEventListener('click', function (e) {
				if (pauseIcon.classList.contains('is-active')) {
					pauseIcon.classList.remove('is-active');
					playIcon.classList.add('is-active');
				}
				video.pause();
				video.currentTime = 0;
				progress.value = 0;
			});
		}

		if (stop) {
			mute.addEventListener('click', function (e) {
				speakerIcon.classList.toggle('is-active');
				muteIcon.classList.toggle('is-active');
				video.muted = !video.muted;
			});
		}
	}
})();