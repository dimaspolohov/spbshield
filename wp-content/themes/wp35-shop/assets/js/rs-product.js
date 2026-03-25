jQuery('body').on('click','button.rs-btn.disabled',function(event){
	event.preventDefault()
});



/* ====================================
Инициализация слайдера в блоке rs-product
==================================== */
function initProductSliders() {
	if (window.innerWidth <= 992) return;

	var thumbsContainer = document.querySelector('.rs-product .rs-thumbs__swiper');
	if (!thumbsContainer) return;

	var mainSlides = document.querySelectorAll('.rs-product .rs-product__slide');
	var thumbSlides = thumbsContainer.querySelectorAll('.rs-thumbs__slide');
	if (!thumbsContainer.dataset.clickBound) {
		thumbsContainer.dataset.clickBound = '1';
		thumbSlides.forEach(function(thumb, index) {
			thumb.style.cursor = 'pointer';
			thumb.addEventListener('click', function() {
				if (mainSlides[index] && window.innerWidth > 992) {
					var headerOffset = 64;
					var top = mainSlides[index].getBoundingClientRect().top + window.pageYOffset - headerOffset;
					window.scrollTo({ top: top, behavior: 'smooth' });
				}
			});
		});
	}

	if (thumbsContainer.querySelector('.rs-thumbs__viewport')) return;

	var maxVisible = 5;
	var allSlides = Array.prototype.slice.call(thumbsContainer.children);
	var visibleSlides = allSlides.filter(function(s) {
		return s.offsetWidth > 0 && window.getComputedStyle(s).display !== 'none';
	});

	if (visibleSlides.length <= maxVisible) return;

	var firstSlide = visibleSlides[0];
	var slideHeight = firstSlide.offsetHeight;

	if (slideHeight < 10) {
		if (document.readyState !== 'complete') {
			window.addEventListener('load', initProductSliders, { once: true });
			return;
		}
		var vw = window.innerWidth;
		slideHeight = vw >= 1920 ? 100 : Math.round(70 + 30 * (vw - 992) / (1920 - 992));
	}

	var gap = parseFloat(window.getComputedStyle(firstSlide).marginBottom) || 8;
	var viewportMaxHeight = slideHeight * maxVisible + gap * (maxVisible - 1);
	var scrollStep = slideHeight + gap;

	var viewport = document.createElement('div');
	viewport.className = 'rs-thumbs__viewport';
	viewport.style.maxHeight = viewportMaxHeight + 'px';

	while (thumbsContainer.firstChild) {
		viewport.appendChild(thumbsContainer.firstChild);
	}

	var arrowUp = document.createElement('button');
	arrowUp.className = 'rs-thumbs__nav rs-thumbs__nav--up';
	arrowUp.type = 'button';
	arrowUp.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M18 15L12 9L6 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';

	var arrowDown = document.createElement('button');
	arrowDown.className = 'rs-thumbs__nav rs-thumbs__nav--down';
	arrowDown.type = 'button';
	arrowDown.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';

	thumbsContainer.appendChild(arrowUp);
	thumbsContainer.appendChild(viewport);
	thumbsContainer.appendChild(arrowDown);

	function updateArrows() {
		var scrollTop = viewport.scrollTop;
		var maxScroll = viewport.scrollHeight - viewport.clientHeight;

		if (scrollTop > 1) {
			arrowUp.classList.add('visible');
		} else {
			arrowUp.classList.remove('visible');
		}

		if (scrollTop < maxScroll - 1) {
			arrowDown.classList.add('visible');
		} else {
			arrowDown.classList.remove('visible');
		}
	}

	arrowDown.addEventListener('click', function(e) {
		e.preventDefault();
		e.stopPropagation();
		viewport.scrollBy({ top: scrollStep, behavior: 'smooth' });
	});

	arrowUp.addEventListener('click', function(e) {
		e.preventDefault();
		e.stopPropagation();
		viewport.scrollBy({ top: -scrollStep, behavior: 'smooth' });
	});

	viewport.addEventListener('scroll', updateArrows);
	updateArrows();
}

window.addEventListener('load', initProductSliders);

/**/
jQuery( document ).ready(function() {
	document.querySelector('input[name="variation_id"]').onchange = function() {
		// jQuery('.rs-product__pictures').addClass('loading');
		// jQuery('.rs-product__prices').addClass('loading');
		var $variation_id = jQuery(this).val();
		if($variation_id && $variation_id!=''){
			var data = {
				action: 'getVariationGal',
				product_id: jQuery('input[name="product_id"]').val(),
				variation_id: $variation_id,
			};
			jQuery.ajax( {
				data:data,
				type:'POST',
				url:'/wp-admin/admin-ajax.php',
				success: function(response,status) {
					var response_arr = jQuery.parseJSON(response);
					jQuery('.rs-product__pictures').replaceWith(response_arr[0]);
					jQuery('.rs-product__prices').html(response_arr[1]);
					// jQuery('.rs-product__prices').removeClass('loading');
					initProductSliders();
					initDataGallery();
				}
			});
		}
	}

});

/**/

/* ====================================
Фильтры
==================================== */

function selectColor() {
	const color = document.querySelector('.color');
	if(color){
		const colorTitle = color.querySelector('[data-title]');
		const colorSelects = color.querySelectorAll('[data-select]');
		var select = document.querySelector('select[name="attribute_pa_color"]');
		colorSelects.forEach(color => {
			color.addEventListener('click', function () {
				colorTitle.textContent = color.dataset.select;
				if(color.dataset.val) {
					select.value = color.dataset.val;
				} 
			})
		});
	}
}
selectColor()

function selectSize() {
	const size = document.querySelector('.size');
	if(size){
		const sizeTitle = size.querySelector('[data-title]');
		const sizeSelects = size.querySelectorAll('[data-select]');
		var select = document.querySelector('select[name="attribute_pa_size"]');
		sizeSelects.forEach(size => {
			size.addEventListener('click', function (e) {
				// Check if the size item is disabled
				const sizeItem = this.closest('.rs-product__size-item');
				const inputElement = sizeItem ? sizeItem.querySelector('input[type="radio"]') : null;
				
				if (sizeItem && sizeItem.classList.contains('disabled')) {
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
				
				if (inputElement && inputElement.disabled) {
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
				
				sizeTitle.textContent = size.dataset.select;
				if(size.dataset.val) {
					select.value = size.dataset.val;
				}
			})
		});
	}
}
selectSize()

// Prevent clicking on disabled size radio buttons
jQuery(document).ready(function($) {
	// Prevent any clicks on disabled size items
	$(document).on('click', '.rs-product__size-item.disabled', function(e) {
		e.preventDefault();
		e.stopPropagation();
		return false;
	});
	
	// Prevent any clicks on disabled radio buttons
	$(document).on('click', 'input[name="attribute_pa_size"]:disabled', function(e) {
		e.preventDefault();
		e.stopPropagation();
		return false;
	});
	
	// Prevent label clicks for disabled inputs
	$(document).on('click', '.rs-product__size-item.disabled label', function(e) {
		e.preventDefault();
		e.stopPropagation();
		return false;
	});
});