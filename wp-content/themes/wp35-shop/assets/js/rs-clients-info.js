/* ====================================
Открытие подсказок
==================================== */
const tooltipOpen = () => {
	const tooltips = document.querySelectorAll('.tippy__item')

	tooltips.forEach(tooltip => {
		const tooltipBtn = tooltip.querySelector('.tippy__number')

		tooltipBtn.addEventListener('click', function () {
			tooltips.forEach(item => {
				item.classList.remove('_active-tippy')
			});
			if (!tooltip.classList.contains('_active-tippy')) {
				tooltip.classList.toggle('_active-tippy')
			} else {
				tooltip.classList.remove('_active-tippy')
			}
		})

		tooltip.addEventListener('click', function (e) {
			e.stopPropagation();
		});

		document.addEventListener('click', function (e) {
			tooltip.classList.remove('_active-tippy');
		});

		tooltipBtn.addEventListener('mouseenter', function () {
			tooltips.forEach(item => {
				item.classList.remove('_active-tippy')
			});
			if (!tooltip.classList.contains('_active-tippy')) {
				tooltip.classList.toggle('_active-tippy')
			} else {
				tooltip.classList.remove('_active-tippy')
			}
		})

	});


}
tooltipOpen();
jQuery('a[data-hash="#terms_of_use"]').on('click',function (){
	jQuery('button[data-hash="#terms_of_use"]').trigger('click');
})