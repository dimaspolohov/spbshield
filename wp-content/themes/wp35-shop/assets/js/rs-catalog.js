
/* ====================================
Открытие окона фильтров
==================================== */
function modalFilter() {
	const filterBtnShow = document.querySelector('.filter-show');
	const filterBtnClose = document.querySelector('.filter-close');
	const filter = document.getElementById('filters');

	filterBtnShow.addEventListener('click', function () {
		filter.classList.toggle('_filters-show');
		filter.classList.toggle('_filters-show__mobile');
		filterBtnShow.classList.toggle('_filters-active');
	})

	filterBtnClose.addEventListener('click', function () {
		filter.classList.remove('_filters-show')
		filter.classList.remove('_filters-show__mobile')
		filterBtnShow.classList.remove('_filters-active');
	})
}
if (document.querySelector('.filter-show') && document.getElementById('filters')) {
	modalFilter()
}

/* ====================================
Подсчет активных фильтров и отчистка
==================================== */
function filterClear() {
	const filter = document.getElementById('toreset');
	const clearBtn = document.querySelector('.filter-clear');
	const inputs = filter.querySelectorAll('input');

	clearBtn.addEventListener('click', function () {
		inputs.forEach(input => {
			input.checked = false;
		});
	})
}
if (document.getElementById('filters')) {
	filterClear();
}

/* ====================================
Смена отображения списка
==================================== */
function viewList() {
	const catalogContent = document.querySelector('.rs-catalog__list');
	const oneViewBtns = document.querySelector('.rs-filters__view-1 > button');
	const fourViewBtns = document.querySelector('.rs-filters__view-4 > button');

	oneViewBtns.addEventListener('click', function () {
		catalogContent.classList.remove('_view-4');
		catalogContent.classList.add('_view-1');
		fourViewBtns.classList.remove('_active-view');
		oneViewBtns.classList.add('_active-view');
	})

	fourViewBtns.addEventListener('click', function () {
		catalogContent.classList.remove('_view-1');
		catalogContent.classList.add('_view-4');
		oneViewBtns.classList.remove('_active-view');
		fourViewBtns.classList.add('_active-view');
	})
}
if (document.querySelector('.rs-catalog__list')) {
	viewList();
}

/* ====================================
Кастомный селект
==================================== */
function select() {
	const selectSingle = document.querySelector('.select');
	const selectSingle_title = selectSingle.querySelector('.select__title');
	const selectSingle_labels = selectSingle.querySelectorAll('.select__label');

	// Переключить меню
	selectSingle_title.addEventListener('click', () => {
		if ('active' === selectSingle.getAttribute('data-state')) {
			selectSingle.setAttribute('data-state', '');
		} else {
			selectSingle.setAttribute('data-state', 'active');
		}
	});

	// Закрыть при нажатии на опцию
	for (let i = 0; i < selectSingle_labels.length; i++) {
		selectSingle_labels[i].addEventListener('click', (evt) => {
			// selectSingle_title.textContent = evt.target.textContent;
			selectSingle.setAttribute('data-state', '');
		});
	}
}
if (document.querySelector('.select')) {
	select();
}

/**/

// Variable to store current AJAX request
var currentAjaxRequest = null;

function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;
    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};

jQuery('input[name="orderby"]').on('change',function(){
	var $term_id = jQuery('.rs-catalog__wrapper').attr('data-term');
	var $orderby = jQuery(this).closest('form').serialize().split('=');
	var queryParams = new URLSearchParams(window.location.search);
	queryParams.set($orderby[0], $orderby[1]);
	history.replaceState(null, null, "?"+queryParams.toString());
	reload_products($term_id,0);
});

jQuery('input[name="color"]').on('change',function(){
	var $term_id = jQuery('.rs-catalog__wrapper').attr('data-term');
	var $color_a = [];
	jQuery('input[name="color"]').each(function(){ if(jQuery(this).is(':checked')) $color_a.push(jQuery(this).val()); });
	var queryParams = new URLSearchParams(window.location.search);
	if($color_a.length>0) queryParams.set('color', $color_a.join('-')); else queryParams.delete('color');
	history.replaceState(null, null, "?"+queryParams.toString());
	remove_extra_question_mark();
	reload_products($term_id,0);
});
jQuery('input[name="size"]').on('change',function(){
	var $term_id = jQuery('.rs-catalog__wrapper').attr('data-term');
	var $color_a = [];
	jQuery('input[name="size"]').each(function(){ if(jQuery(this).is(':checked')) $color_a.push(jQuery(this).val()); });
	console.log($color_a );
	var queryParams = new URLSearchParams(window.location.search);
	if($color_a.length>0) queryParams.set('size', $color_a.join('-')); else queryParams.delete('size');
	history.replaceState(null, null, "?"+queryParams.toString());
	remove_extra_question_mark();
	reload_products($term_id,0);
});

jQuery('input[name="category"]').on('change',function(){
	var $term_id = jQuery(this).val();
	var $url = window.location.href.split('?');
	jQuery('.rs-catalog__wrapper').attr('data-term',$term_id);
	$url[0] = jQuery(this).attr('data-url');
	window.history.pushState(null, null, $url.join('?'));
	reload_products($term_id,0);
});

jQuery( document ).ready(function() {
	var $term_id = jQuery('.rs-catalog__wrapper').attr('data-term');
	reload_products($term_id,0);
});

jQuery('body').on('click','.rs-catalog__filters_button',function(e){
	e.preventDefault();
	var $term_id = jQuery('.rs-catalog__wrapper').attr('data-term');
	reload_products($term_id,0);
});

jQuery('body').on('click','.filter-clear',function(e){
	e.preventDefault();
	var $term_id = jQuery('.rs-catalog__wrapper').attr('data-term');
	var queryParams = new URLSearchParams(window.location.search);
	queryParams.delete('color');
	queryParams.delete('size');
	history.replaceState(null, null, "?"+queryParams.toString());
	remove_extra_question_mark();
	reload_products($term_id,0);
});

function remove_extra_question_mark(){
	var $url = window.location.href.split('?');
	if(!$url[1]||$url[1]=='') window.history.pushState(null, null, $url[0]);
}

jQuery( window ).on('scroll',function() {
	if(jQuery('.loading-more').length>0 && jQuery('.rs-catalog__list.loading').length==0) {
		var $window = jQuery( window ).height();
		var $top = jQuery('.loading-more').offset().top-$window;
		var $scroll = jQuery(document).scrollTop();
		if($top<=$scroll){
			var $term_id = jQuery('.rs-catalog__wrapper').attr('data-term');
			var $offset = jQuery('.rs-catalog__list').attr('offset');
			// console.log($offset)
			reload_products( $term_id, $offset );
		}
	}
});

function reload_products( term_id, offset ) {
	// Abort previous AJAX request if it's still running
	if (currentAjaxRequest != null) {
		currentAjaxRequest.abort();
	}
	
	var orderby = getUrlParameter('orderby');
	if(!orderby) orderby = 'menu_order';
	var color = getUrlParameter('color');
	var size = getUrlParameter('size');
	jQuery('.rs-catalog__list').addClass('loading');	
	var data = {
		action: 'getProducts',
		term_id: term_id,
		offset: offset,
		number: 12,
		orderby: orderby,
		color: color,
		size: size,
	};
	currentAjaxRequest = jQuery.ajax( {
		data:data,
		type:'POST',
		url:'/wp-admin/admin-ajax.php',
		success: function(response,status) {
			var $response = jQuery.parseJSON(response);
			// console.log($response);
			jQuery('.clear').remove();
			if(offset==0) {
				jQuery('.rs-catalog__list').html($response[0]);
			} else {
				// Remove duplicates before appending
				var $newContent = jQuery('<div>').html($response[0]);
				var existingIds = [];
				
				// Get all existing product IDs
				jQuery('.rs-catalog__list [data-product-id]').each(function() {
					existingIds.push(jQuery(this).attr('data-product-id'));
				});
				
				// Filter out duplicates from new content
				$newContent.find('[data-product-id]').each(function() {
					var productId = jQuery(this).attr('data-product-id');
					if (existingIds.indexOf(productId) !== -1) {
						// Remove duplicate product
						jQuery(this).remove();
					}
				});
				
				jQuery('.rs-catalog__list').append($newContent.html());
			}
			jQuery('.rs-breadcrumbs__list').html($response[1]);
			// offset = parseInt($response[2]);
			offset = parseInt(offset) + parseInt($response[2]);
			jQuery('.rs-catalog__list').attr('offset',offset);
			jQuery('.rs-catalog__list').removeClass('loading');
			currentAjaxRequest = null;
			initProductSlidersBottom();
			// console.log($response[3])
			// console.log($response[4])
		},
		error: function(xhr, status, error) {
			// Handle error, but ignore aborted requests
			if (status !== 'abort') {
				console.error('Error loading products:', error);
			}
			jQuery('.rs-catalog__list').removeClass('loading');
			currentAjaxRequest = null;
		}
	});
	jQuery('#filters').removeClass('_filters-show__mobile');
}