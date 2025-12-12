function animatePath(pathname, animation) {
	const paths = document.querySelectorAll(pathname);

	paths.forEach(path => {
		const length = path.getTotalLength();
		path.style.transition = path.style.WebkitTransition = 'none';
		path.style.strokeDasharray = length + ' ' + length;
		path.style.strokeDashoffset = length;
		path.getBoundingClientRect();
		path.style.transition = path.style.WebkitTransition =
			animation;
		path.style.strokeDashoffset = '0';
	});
}
animatePath('.preloader svg path', 'stroke-dashoffset 2s ease-in-out');

var scroll = window.requestAnimationFrame ||
	function (callback) { window.setTimeout(callback, 1000 / 60) };
var elementsToShow = document.querySelectorAll('.show-on-scroll');

function loop() {
	elementsToShow.forEach(function (element) {
		if (isElementInViewport(element)) {
			element.classList.add('is-visible');
			if (element.getAttribute("src") == undefined || element.getAttribute("src") == '') {
				element.setAttribute("src", element.getAttribute("data-src"));
			}
		} else {
			element.classList.remove('is-visible');
		}
	});
	scroll(loop);
}
if(elementsToShow){
	loop();
}
function isElementInViewport(el) {
	if (typeof jQuery === "function" && el instanceof jQuery) {
		el = el[0];
	}
	var rect = el.getBoundingClientRect();
	return (
		(rect.top <= 0
			&& rect.bottom >= 0)
		||
		(rect.bottom >= (window.innerHeight || document.documentElement.clientHeight) &&
			rect.top <= (window.innerHeight || document.documentElement.clientHeight))
		||
		(rect.top >= 0 &&
			rect.bottom <= (window.innerHeight || document.documentElement.clientHeight))
	);
}

function preloaderLoading() {

	/*const images = document.images;
	const timeLoad = window.innerWidth > 767 ? 500 : 200;

	imagesTotalCount = images.length;
	imagesLoadedCount = 0;

	for (let i = 0; i < imagesTotalCount; i++) {
		imageClone = new Image();
		imageClone.onload = imagesLoaded;
		imageClone.onerror = imagesLoaded;
		imageClone.src = images[i].src;
	}*/



	/*function imagesLoaded() {
		imagesLoadedCount++;
		if (imagesLoadedCount >= imagesTotalCount) {
			setTimeout(function () {
				if (!preloader.classList.contains('_done')) {
					preloader.classList.add('_done');
					document.body.classList.remove('lock');
				}
			}, timeLoad);
		}
	}*/

	loop();
}

if (document.querySelector('.preloader') ) {
	const preloader = document.querySelector('.preloader');
	document.body.classList.add('lock');
	window.addEventListener('load', function() {
		if (!preloader.classList.contains('_done') /*&& !document.querySelector('.post-type-archive-media .rs-media__slider') && !document.querySelector('.home')*/) {
			//preloader.classList.add('_done');
			preloader.remove();
			document.body.classList.remove('lock');
		}
	})
	//preloaderLoading()
}