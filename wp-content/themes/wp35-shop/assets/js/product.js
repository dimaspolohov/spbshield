/* ====================================
Инициализация слайдера в блоке product
==================================== */
function initProductSlidersBottom() {
	const productSlider = document.querySelectorAll('.product__slider:not(.swiper-initialized)');//swiper-initialized
	let productMainPagination;



	productSlider.forEach(product => {

		// Перечень слайдеров
		const productSlider = new Swiper(product, {
			// Автопрокрутка
			/*autoplay: {
				// Пауза между прокруткой
				delay: 10000,
				// Закончить на последнем слайде
				stopOnLastSlide: false,
				// Отключить после ручного переключения
				disableOnInteraction: false,
			},*/

			// Обновить свайпер
			// при изменении элементов слайдера
			observer: true,
			// при изменении родительских элементов слайдера
			observeParents: true,
			// при изменении дочерних элементов слайдера
			observeSlideChildren: true,

			// Скорость смены слайдов
			speed: 0,

			// Включение/отключение
			// перетаскивание на ПК
			simulateTouch: true,
			// Чувствительность свайпа
			touchRadio: 1,
			// Угол срабатывания свайпа/перетаскивания
			touchAngle: 45,

			slidesPerView: 1,
			effect: 'fade',
			pagination: {
				el: '.rs-product__pagination',
				clickable: true,
				dynamicBullets: true,
				dynamicMainBullets:5,
			},
		});

		/*productSlider.on('slideChangeTransitionEnd', function () {
			const productPagination = product.querySelector('.swiper-pagination-bullet.swiper-pagination-bullet-active-next');

			if(productPagination){
				if(product.querySelector('.swiper-pagination-bullet.swiper-pagination-bullet-active-prev'))
				product.querySelector('.swiper-pagination-bullet.swiper-pagination-bullet-active-prev').style.opacity=0
			} else {
				if(product.querySelector('.swiper-pagination-bullet.swiper-pagination-bullet-active-prev'))
				product.querySelector('.swiper-pagination-bullet.swiper-pagination-bullet-active-prev').style.opacity=1
			}
		});*/


		if(product.querySelector('.swiper-pagination-bullets-dynamic')){
			productMainPagination = product.querySelector('.swiper-pagination-bullet.swiper-pagination-bullet-active-main');
			product.querySelector('.swiper-pagination-bullets-dynamic').style.cssText = `margin-left: -${productMainPagination.style.left};`;
			productSlider.on('slideChange', function () {
			let prev = this.previousIndex,
			current = this.activeIndex;

			const productNextPagination = product.querySelector('.swiper-pagination-bullet.swiper-pagination-bullet-active-next'),
			productPrevPagination = product.querySelector('.swiper-pagination-bullet.swiper-pagination-bullet-active-prev'),
			productActivePagination = product.querySelector('.swiper-pagination-bullet.swiper-pagination-bullet-active'),
			productMainPagination = product.querySelector('.swiper-pagination-bullet.swiper-pagination-bullet-active-main');

			console.log(productMainPagination);
			if(product.querySelector('.swiper-pagination-bullets-dynamic')){
				product.querySelector('.swiper-pagination-bullets-dynamic').style.cssText = `margin-left: ${-parseInt(productActivePagination.style.left)}px;`;
			}
			if(productPrevPagination){
				let bulletPrev = this.pagination.bullets[+current-1],
					bulletHide = this.pagination.bullets[+current+4];
				console.log(bulletPrev,bulletPrev.classList.contains('swiper-pagination-bullet-active-prev'));

				if(bulletPrev.classList.contains('swiper-pagination-bullet-active-prev')){
					productPrevPagination.style.cssText=`display: inline-block;left: ${productActivePagination.style.left};`;
					bulletHide.style.cssText=`display: none;`;
				} else {
					bulletPrev.style.cssText=`display: inline-block;left: ${productActivePagination.style.left};`;
					if(prev>current){
						productNextPagination.style.cssText=`display: none;`;
						bulletHide.style.cssText=`display: none;`;
					}
				}
			}

			if(productNextPagination){
				let bulletNext = this.pagination.bullets[current+(+1)],
					bulletHide = this.pagination.bullets[current+(+1)-5];
				if(bulletNext.classList.contains('swiper-pagination-bullet-active-next')){
					productNextPagination.style.cssText=`display: inline-block;left: ${productActivePagination.style.left};`;
					bulletHide.style.cssText=`display: none;`;
				}

				let nextTl = productNextPagination.previousElementSibling;
				/*nextTl.classList.add('swiper-pagination-bullet-active-next');
				nextTl.classList.remove('swiper-pagination-bullet-active-main');
				productNextPagination.classList.remove('swiper-pagination-bullet-active-next');
				productNextPagination.classList.add('swiper-pagination-bullet-active-next-next');
				nextTl.style.cssText=`display: inline-block;left: ${productActivePagination.style.left};`;*/
				//console.log(productNextPagination.previousElementSibling);
				/*if(current>2){
					if(product.querySelector('.swiper-pagination-bullet.swiper-pagination-bullet-active-next-next') && !product.querySelector('.swiper-pagination-bullet.swiper-pagination-bullet-active-prev')){
						console.log(3)
						product.querySelector('.swiper-pagination-bullet.swiper-pagination-bullet-active-next-next').style.cssText=`display: inline-block;left: ${productActivePagination.style.left};`;
						productMainPagination.nextElementSibling.style.cssText=`display: none;`;
					} else if(product.querySelector('.swiper-pagination-bullet.swiper-pagination-bullet-active-prev')) {
						console.log(4)
						product.querySelector('.swiper-pagination-bullet.swiper-pagination-bullet-active-prev').style.cssText=`display: inline-block;left: ${productActivePagination.style.left};`;
						productMainPagination.nextElementSibling.style.cssText=`display: none;`;
						//productMainPagination.nextElementSibling.style.cssText=`display: inline-block;left: ${productActivePagination.style.left};`;
					}
					productNextPagination.style.cssText=`display: inline-block;left: ${productActivePagination.style.left};`;
					productMainPagination.style.cssText=`display: none;`;
				} else {
					productNextPagination.style.cssText=`display: none;`;
					productMainPagination.style.cssText=`display: inline-block;left: ${productActivePagination.style.left};`;
				}*/
			}
			console.log(prev,current)
		});

		}
		const productSliders = product.querySelectorAll('.swiper-slide');

		productSliders.forEach(slide => {
			slide.addEventListener('mouseenter', function () {
				productSlider.slideNext();
			});
			slide.addEventListener('mouseleave', function (e) {
				productSlider.slidePrev();
			})
		});

	});
}

window.addEventListener("load", function (e) {
	// Запуск инициализации слайдеров
	initProductSlidersBottom();
});