/* ====================================
Табы
==================================== */
/*
Для родителя табов пишем атрибут data-tabs
Для родителя заголовков табов пишем атрибут data-tabs-titles
Для родителя блоков табов пишем атрибут data-tabs-body
Для родителя блоков табов можно указать data-tabs-hash, это втключит добавление хеша

Если нужно чтобы табы открывались с анимацией 
добавляем к data-tabs data-tabs-animate
По умолчанию, скорость анимации 500ms, 
указать свою скорость можно так: data-tabs-animate="1000"

Если нужно чтобы табы превращались в "спойлеры", на неком размере экранов, пишем параметры ширины.
Например: data-tabs="992" - табы будут превращаться в спойлеры на экранах меньше или равно 992px
*/
function tabs() {
	const tabs = document.querySelectorAll('[data-tabs]');
	let tabsActiveHash = [];

	if (tabs.length > 0) {
		// const hash = getHash();
		// if (hash && hash.startsWith('tab-')) {
		// 	tabsActiveHash = hash.replace('tab-', '').split('-');
		// }
		tabs.forEach((tabsBlock, index) => {
			tabsBlock.classList.add('_tab-init');
			tabsBlock.setAttribute('data-tabs-index', index);
			tabsBlock.addEventListener("click", setTabsAction);
			initTabs(tabsBlock);
		});

		// Получение слойлеров с медиа запросами
		let mdQueriesArray = dataMediaQueries(tabs, "tabs");
		if (mdQueriesArray && mdQueriesArray.length) {
			mdQueriesArray.forEach(mdQueriesItem => {
				// Событие
				mdQueriesItem.matchMedia.addEventListener("change", function () {
					setTitlePosition(mdQueriesItem.itemsArray, mdQueriesItem.matchMedia);
				});
				setTitlePosition(mdQueriesItem.itemsArray, mdQueriesItem.matchMedia);
			});
		}
	}
	// Установка позиций заголовков
	function setTitlePosition(tabsMediaArray, matchMedia) {
		tabsMediaArray.forEach(tabsMediaItem => {
			tabsMediaItem = tabsMediaItem.item;
			let tabsTitles = tabsMediaItem.querySelector('.tabs__navigation__sticky');
			let tabsTitleItems = tabsMediaItem.querySelectorAll('[data-tabs-title]');
			let tabsContent = tabsMediaItem.querySelector('[data-tabs-body]');
			let tabsContentItems = tabsMediaItem.querySelectorAll('[data-tabs-item]');
			tabsTitleItems = Array.from(tabsTitleItems).filter(item => item.closest('[data-tabs]') === tabsMediaItem);
			tabsContentItems = Array.from(tabsContentItems).filter(item => item.closest('[data-tabs]') === tabsMediaItem);
			tabsContentItems.forEach((tabsContentItem, index) => {
				if (matchMedia.matches) {
					tabsContent.append(tabsTitleItems[index]);
					tabsContent.append(tabsContentItem);
					tabsMediaItem.classList.add('_tab-spoller');
				} else {
					tabsTitles.append(tabsTitleItems[index]);
					tabsMediaItem.classList.remove('_tab-spoller');
				}
			});
		});
	}
	// Работа с контентом_tab-activ
	function initTabs(tabsBlock) {
		let tabsTitles = tabsBlock.querySelectorAll('[data-tabs-titles] .tabs__title');
		let tabsContent = tabsBlock.querySelectorAll('[data-tabs-body] .tabs__body');
		const tabsBlockIndex = tabsBlock.dataset.tabsIndex;
		const tabsActiveHashBlock = tabsActiveHash[0] == tabsBlockIndex;

		if (tabsActiveHashBlock) {
			const tabsActiveTitle = tabsBlock.querySelector('.tabs__navigation__sticky>._tab-active');
			tabsActiveTitle ? tabsActiveTitle.classList.remove('_tab-active') : null;
		}
		if (tabsContent.length) {
			tabsContent = Array.from(tabsContent).filter(item => item.closest('[data-tabs]') === tabsBlock);
			tabsTitles = Array.from(tabsTitles).filter(item => item.closest('[data-tabs]') === tabsBlock);
			tabsContent.forEach((tabsContentItem, index) => {
				tabsTitles[index].setAttribute('data-tabs-title', '');
				tabsContentItem.setAttribute('data-tabs-item', '');

				if (tabsActiveHashBlock && index == tabsActiveHash[1]) {
					tabsTitles[index].classList.toggle('_tab-active');
				}
				tabsContentItem.hidden = !tabsTitles[index].classList.contains('_tab-active');
			});
		}
	}
	function setTabsStatus(tabsBlock) {
		let tabsTitles = tabsBlock.querySelectorAll('[data-tabs-title]');
		let tabsContent = tabsBlock.querySelectorAll('[data-tabs-item]');
		const tabsBlockIndex = tabsBlock.dataset.tabsIndex;
		function isTabsAnamate(tabsBlock) {
			if (tabsBlock.hasAttribute('data-tabs-animate')) {
				return tabsBlock.dataset.tabsAnimate > 0 ? Number(tabsBlock.dataset.tabsAnimate) : 500;
			}
		}
		const tabsBlockAnimate = isTabsAnamate(tabsBlock);
		if (tabsContent.length > 0) {
			const isHash = tabsBlock.hasAttribute('data-tabs-hash');
			tabsContent = Array.from(tabsContent).filter(item => item.closest('[data-tabs]') === tabsBlock);
			tabsTitles = Array.from(tabsTitles).filter(item => item.closest('[data-tabs]') === tabsBlock);
			tabsContent.forEach((tabsContentItem, index) => {
				if (tabsTitles[index].classList.contains('_tab-active')) {
					if (tabsBlockAnimate) {
						_slideDown(tabsContentItem, tabsBlockAnimate);
					} else {
						tabsContentItem.hidden = false;
					}
					// if (isHash && !tabsContentItem.closest('.popup')) {
					// 	setHash(`tab-${tabsBlockIndex}-${index}`);
					// }
				} else {
					if (tabsBlockAnimate) {
						_slideUp(tabsContentItem, tabsBlockAnimate);
					} else {
						tabsContentItem.hidden = true;
					}
				}
			});
		}
	}
	function setTabsAction(e) {
		const el = e.target;
		if(el.closest('.rs-client-info__size_tabs')){
			var $id = jQuery(el).attr('data-id');
			jQuery('.rs-client-info__size .tabs__body').each(function(){
				jQuery(this).hide();
			});
			jQuery('.rs-client-info__size .tabs__body.'+$id).show();
		} else {	
			if (el.closest('[data-tabs-title]')) {
				const tabTitle = el.closest('[data-tabs-title]');
				const tabsBlock = tabTitle.closest('[data-tabs]');
				if (!tabTitle.classList.contains('_tab-active') && !tabsBlock.querySelector('._slide')) {
					let tabActiveTitle = tabsBlock.querySelectorAll('[data-tabs-title]._tab-active');
					tabActiveTitle.length ? tabActiveTitle = Array.from(tabActiveTitle).filter(item => item.closest('[data-tabs]') === tabsBlock) : null;
					tabActiveTitle.length ? tabActiveTitle[0].classList.remove('_tab-active') : null;
					tabTitle.classList.toggle('_tab-active');
					setTabsStatus(tabsBlock);
				} else {
					let tabActiveTitle = tabsBlock.querySelectorAll('[data-tabs-title]._tab-active');
					tabActiveTitle.length ? tabActiveTitle = Array.from(tabActiveTitle).filter(item => item.closest('[data-tabs]') === tabsBlock) : null;
					// tabActiveTitle.length ? tabActiveTitle[0].classList.remove('_tab-active') : null;
					tabTitle.classList.toggle('_tab-active');
					setTabsStatus(tabsBlock);				
				}
				e.preventDefault();
			}
		}
	}
}
if (document.querySelector('[data-tabs]')) {
	tabs()
}

/**/

jQuery('body').on('click','.tabs__navigation__sticky>.tabs__title',function() {
	if(jQuery(this).attr('data-hash')) {
		var $hash = jQuery(this).attr('data-hash');
		window.location.hash = $hash;
	}
});
jQuery('body').on('click','.rs-client-info__size .tabs__title',function() {
	var container = jQuery(this).parent();
	jQuery('._tab-active',container).removeClass('_tab-active');
	jQuery(this).addClass('_tab-active');
});
jQuery( document ).ready(function() {
    var $hash = window.location.hash.toLowerCase();
	if($hash) {
		if(jQuery('.tabs__navigation__sticky>.tabs__title[data-hash="'+$hash+'"]').length>0){
			jQuery('.tabs__navigation__sticky>.tabs__title').each(function(index){
				if(jQuery(this).attr('data-hash')==$hash){
					jQuery(this).addClass('_tab-active');
					index++;
					jQuery('.tabs__content>.tabs__body:nth-child('+index+')').removeAttr('hidden');
				}
			});
		} else {
			jQuery('.tabs__navigation__sticky>.tabs__title:first-child').addClass('_tab-active');
			jQuery('.tabs__content>.tabs__body:first-child').removeAttr('hidden');
			if(jQuery('.tabs__content>.tabs__title[data-hash="'+$hash+'"]').length>0){
				jQuery('.tabs__content>.tabs__title').each(function(index){
					if(jQuery(this).attr('data-hash')==$hash){
						jQuery(this).addClass('_tab-active');
						jQuery(this).next().removeAttr('hidden');
					}
				});
			} else {
				jQuery('.tabs__content>.tabs__title:first-child').addClass('_tab-active').next().removeAttr('hidden');
				removeHash();
			}
		}
	} else {
		jQuery('.tabs__navigation__sticky>.tabs__title:first-child').addClass('_tab-active');
		jQuery('.tabs__content>.tabs__body:first-child').removeAttr('hidden');
		jQuery('.tabs__content>.tabs__title:first-child').addClass('_tab-active').next().removeAttr('hidden');
	}
});

function removeHash() { 
    history.pushState("", document.title, window.location.pathname + window.location.search);
}

/**/

jQuery('#rs_clients_form').submit(function(event){
	event.preventDefault();
	var $form = jQuery(this);
	if( !validate_rs_clients_form( $form ) ) {
		$form.addClass('loading');
		var data = {
			action: 'rs_clients_form_function',
			name: jQuery('[name="user-name"]',$form).val(),
			email: jQuery('[name="user-email"]',$form).val(),
			message: jQuery('[name="user-message"]',$form).val(),
			page_id: $form.attr('data-page'),
		};
		jQuery.ajax( {
			data:data,
			type:'POST',
			url:'/wp-admin/admin-ajax.php',
			success: function(response,status) {
				$form.removeClass('loading');
				$form[0].reset();
				jQuery('.valid',$form).removeClass('valid');
				jQuery('.invalid',$form).removeClass('invalid');
				jQuery('.form-message.success').slideDown();
			}
		});
	};
});

jQuery('#rs_clients_form').click(function(event){
	jQuery('.form-message.success').slideUp();
});

jQuery('body').on('change','#rs_clients_form [name="user-name"],#rs_clients_form [name="user-email"]',function(){
	var $form = jQuery('#rs_clients_form');
	var $name = jQuery(this).attr('name');
	validate_rs_clients_form( $form, $name );
});

function validate_rs_clients_form( $form, $name = '' ){
	var $error = false;
	
	if($name=='' || $name=='user-name') {
		$value = jQuery('[name="user-name"]',$form).val();
		if(!$value || $value=='') {
			jQuery('[name="user-name"]',$form).addClass('invalid');
			$error = true;
		} else {
			jQuery('[name="user-name"]',$form).removeClass('invalid').addClass('valid');
		}
	}
	if($name=='' || $name=='user-email') {
		$value = jQuery('[name="user-email"]',$form).val();
		if(!$value || $value=='' || !isEmail($value)) {
			jQuery('[name="user-email"]',$form).addClass('invalid');
			$error = true;
		} else {
			jQuery('[name="user-email"]',$form).removeClass('invalid').addClass('valid');
		}
	}
	if($error===true) {
		jQuery('.form-message.error').slideDown();
	} else {
		jQuery('.form-message.error').slideUp();
	}
	return $error;
}

function isEmail(email) {
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}