function map(n) {
	ymaps.ready(init);
	function init() {
		var myMap = new ymaps.Map("map", {
			controls: [],
			center: [59.944229, 30.322617], // Центрируем примерно между точками
			zoom: 13
		});

		// Первая точка (твоя основная)
		let myPlacemark1 = new ymaps.Placemark([59.926917, 30.322906], {
			balloonContent: 'Главный офис'
		}, {
			iconLayout: 'default#imageWithContent',
			iconImageHref: '/img/logo_black.svg',
			iconImageSize: [40, 42],
			iconImageOffset: [-20, -21],
		});

		// Вторая точка — Большая Пушкарская, 47
		let myPlacemark2 = new ymaps.Placemark([59.963964, 30.313003], {
			balloonContent: 'Большая Пушкарская улица, 47'
		}, {
			iconLayout: 'default#imageWithContent',
			iconImageHref: '/img/logo_black.svg',
			iconImageSize: [40, 42],
			iconImageOffset: [-20, -21],
		});

		// Добавляем обе точки на карту
		myMap.geoObjects.add(myPlacemark1);
		myMap.geoObjects.add(myPlacemark2);

		// Автоматически подгоняем карту, чтобы обе точки были видны
		myMap.setBounds(myMap.geoObjects.getBounds(), {
			checkZoomRange: true,
			zoomMargin: 40
		});

		myMap.behaviors.disable('multiTouch');

		var zoomControl = new ymaps.control.ZoomControl({
			options: {
				size: "large"
			}
		});
		myMap.controls.add(zoomControl);
	}
}
map();