function map(n) {
	ymaps.ready(init);
	function init() {
		var myMap = new ymaps.Map("map", {
			controls: [],
			center: [59.944229, 30.322617],
			zoom: 13
		});

		var placemarkOptions = {
			iconLayout: 'default#imageWithContent',
			iconImageHref: '/img/logo_black.svg',
			iconImageSize: [40, 42],
			iconImageOffset: [-20, -21],
		};

		var myPlacemark1 = new ymaps.Placemark([59.926917, 30.322906], {
			balloonContent: 'Гороховая, 49'
		}, placemarkOptions);

		var myPlacemark2 = new ymaps.Placemark([59.963964, 30.313003], {
			balloonContent: 'Каменоостровский, 32'
		}, placemarkOptions);

		var myPlacemark3 = new ymaps.Placemark([55.805786, 37.584649], {
			balloonContent: 'Большая Новодмитровская, 36 ст. 8'
		}, placemarkOptions);

		// Cluster layout using the same logo icon, offset mirrors iconImageOffset [-20, -21]
		var ClusterLayout = ymaps.templateLayoutFactory.createClass(
			'<div style="position:relative;width:40px;height:42px;margin-left:-20px;margin-top:-25px;">' +
				'<img src="/img/logo_black.svg" style="width:40px;height:42px;" />' +
			'</div>'
		);

		var spbClusterer = new ymaps.Clusterer({
			clusterIconLayout: ClusterLayout,
			clusterIconShape: {
				type: 'Rectangle',
				coordinates: [[-20, -21], [20, 21]]
			},
			groupByCoordinates: false,
			clusterDisableClickZoom: false,
			gridSize: 30
		});

		spbClusterer.add([myPlacemark1, myPlacemark2]);

		myMap.geoObjects.add(spbClusterer);
		myMap.geoObjects.add(myPlacemark3);

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