const header = document.querySelector('header');
const rsHeader = document.querySelector('.rs-header');
if (rsHeader.classList.contains('_white-header')) {
	header.style.paddingBottom = "0";
}

/* ====================================
Мобильное меню
==================================== */
const burger = () => {
	
	document.addEventListener("DOMContentLoaded", function() {
		
		const menuitemDropdownA = document.querySelectorAll('.menu-item.dropdown > a');
		menuitemDropdownA.forEach(menuIDA => {
			menuIDA.innerHTML +='<i class="icon-menu-arrow_down"></i>';
		});
	
		const menuMainList = document.querySelectorAll('.menu__list > .dropdown > a > i');
		const menuSubList = document.querySelectorAll('.dropdown-menu > .dropdown > a > i');
		const menuMainItem = document.querySelectorAll('.menu__list > .dropdown');
		const menuSubItem = document.querySelectorAll('.dropdown-menu > .dropdown');
		const menuItems = [...menuMainItem, ...menuSubItem];

		const menuSubItems = document.querySelectorAll('.dropdown-menu > li')
		menuSubItems.forEach(item => {
			if (item.classList.contains('current_menu_item')) {
				item.parentNode.closest('.menu__list > .dropdown').classList.add('current_menu_item');
				if (item.parentNode.closest('.dropdown-menu > .dropdown')) {
					item.parentNode.closest('.dropdown-menu > .dropdown').classList.add('current_menu_item')
				}
			}
		});

		function closeMenuDesk() {
			if (window.innerWidth > 992) {
				menuItems.forEach(item => {
					item.addEventListener('click', function (event) {
						event.stopPropagation();
					});

					document.addEventListener('click', function (e) {
						item.classList.remove('_open');
					});
				});
			}
		}
		closeMenuDesk()
		window.addEventListener('resize', closeMenuDesk)

		const burgerBtn = document.querySelector('.menu__icon');
		const burgerMenuBody = document.querySelector('.menu__body');

		if (burgerBtn) {
			burgerBtn.addEventListener('click', function () {

				// Закрытие подменю при клинке на бургер
				for (let i = 0; i < menuItems.length; i++) {
					if (menuItems[i].classList.contains('_open')) {
						menuItems[i].classList.remove('_open');
					}
				}

				// Показать меню
				burgerBtn.classList.toggle('_icon-open');
				burgerMenuBody.classList.toggle('_menu-active');
			});
		}

		// Открытие только одного из выпадающих списков (submenu)
		menuSubList.forEach(menuSubBtn => {
			menuSubBtn.addEventListener('click', (e) => {
				e.preventDefault();
				menuSubList.forEach((subBtn) => {
					if (menuSubBtn === subBtn) {
						subBtn.closest('.dropdown').classList.toggle('_open');
					} else {
						subBtn.closest('.dropdown').classList.remove('_open');
					}
				});
			});
		});

		// Открытие только одного из выпадающих списков (mainmenu)
		menuMainList.forEach(menuMainBtn => {
			const link = menuMainBtn.closest('.dropdown > a');
			// console.log(link);

			link.addEventListener('click', (e) => {
				if(jQuery(e.srcElement).hasClass('icon-menu-arrow_down')) {
					e.preventDefault();
				}
				// menuMainBtn.click();
			});
          
			menuMainBtn.addEventListener('click', (e) => {
				e.preventDefault();
				menuMainList.forEach((mainBtn) => {
					if (menuMainBtn === mainBtn) {
						mainBtn.closest('.dropdown').classList.toggle('_open');
						for (let i = 0; i < menuSubItem.length; i++) {
							menuSubItem[i].classList.remove('_open')
						}
					} else {
						mainBtn.closest('.dropdown').classList.remove('_open');
					}
				});
			});
		});
	
	});
}
if (document.querySelectorAll('.menu')) {
	burger()
}

/* ====================================
Отчистка поиска
==================================== */
function searchClear() {
	const searchClear = document.querySelector('.search__clear');
	const searchInput = document.querySelector('.search__input');

	searchInput.addEventListener('input', function (e) {
		searchClear.classList.add('_clear-search-active');
		this.classList.add('_active');

		if (this.value === '') {
			this.classList.remove('_active');
			searchClear.classList.remove('_clear-search-active')
		}
	})

	searchClear.addEventListener('click', function (e) {
		searchInput.value = '';
		searchClear.classList.remove('_clear-search-active')
		searchInput.classList.remove('_active');

		setTimeout(function () {
			searchInput.focus()
		}, 100);
	})
}
if (document.querySelector('.search__clear') && document.querySelector('.search__input')) {
	searchClear()
}

/* ====================================
Появление поиска
==================================== */
function search() {
	const searchShow = document.getElementById('search-show')
	const search = document.querySelector('div.search');
	const searchInput = document.querySelector('.search__input');

	searchShow.addEventListener("click", function (e) {
		search.classList.toggle('_search-active');

		if (document.querySelector('.rs-header--white')) {
			if (document.querySelector('.rs-header--white').classList.contains('_white-header')) {
				document.querySelector('.rs-header--white').classList.remove('_white-header')
				document.querySelector('.rs-header--white').classList.add('_black-header')
			} else {
				document.querySelector('.rs-header--white').classList.remove('_black-header')
				document.querySelector('.rs-header--white').classList.add('_white-header')
			}
		}

		setTimeout(function () {
			searchInput.focus()
		}, 100);
	});

	// Закрываем открытые модальные окна по оверлею и кнопке
	search.addEventListener('click', function (e) {
		const target = e.target;

		// Делегирование события
		if (
			target.classList.contains('search__overlay')
		) {
			search.classList.remove('_search-active');

			if (document.querySelector('.rs-header--white')) {
				if (document.querySelector('.rs-header--white').classList.contains('_white-header')) {
					document.querySelector('.rs-header--white').classList.remove('_white-header')
					document.querySelector('.rs-header--white').classList.add('_black-header')
				} else {
					document.querySelector('.rs-header--white').classList.remove('_black-header')
					document.querySelector('.rs-header--white').classList.add('_white-header')
				}
			}
		}
	});
}
if ((document.querySelector('.search'))) {
	search()
}

/* ====================================
Появление фиксированной шапки
==================================== */
/* function fixHead() {
	const header = document.querySelector('.rs-header'),
		body = document.body;
	if (body.classList.contains('error404')) {
		header.classList.add('_header-fixed')
	}

	window.addEventListener('scroll', function () {
		if (body.classList.contains('error404')) {
			header.classList.add('_header-fixed')
		} else{
			header.classList.toggle('_header-fixed', window.scrollY > 0)
		}
	});
}
if ((document.querySelector('.rs-header'))) {
	fixHead()
} */

/* ====================================
Кастомный курсор
==================================== */
function addCursorHover(hoveredElement, selectedElement, newClass) {
	document.querySelector(hoveredElement).addEventListener('mouseover', function () {
		document.querySelector(selectedElement).classList.add(newClass)
	})
	document.querySelector(hoveredElement).addEventListener('mouseleave', function () {
		document.querySelector(selectedElement).classList.remove(newClass)
	})
}

function addCursorMove(selectedElement) {
	document.body.addEventListener('mousemove', function (e) {
		document.querySelector(selectedElement).style.transform = `translate3d(calc(${e.clientX}px - 50%), calc(${e.clientY}px - 50%), 0)`
		document.querySelector(selectedElement).style.willChange = 'transform, opacity';
	});
}

addCursorHover(".search__overlay", ".cursor", "cursor__active");
addCursorMove(".cursor__circle")