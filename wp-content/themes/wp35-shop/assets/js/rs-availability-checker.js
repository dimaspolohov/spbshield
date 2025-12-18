/**
 * Availability Checker - общий функционал проверки наличия товаров в магазинах
 * Используется как для товаров в наличии, так и для товаров без остатков на складе
 */

class AvailabilityChecker {
    constructor(config = {}) {
        this.map = null;
        this.popup = null;
        this.isLoading = false;
        this.currentSelectedSize = null;
        
        // Конфигурация
        this.config = {
            popupId: config.popupId || 'availability-popup',
            linkId: config.linkId || 'check-availability',
            textId: config.textId || 'availability-text',
            storesContainerId: config.storesContainerId || 'availability-stores',
            mapContainerId: config.mapContainerId || 'availability-map',
            ajaxAction: config.ajaxAction || 'get_gorokhovaya_csv_availability',
            isOutOfStock: config.isOutOfStock || false
        };
        
        this.init();
    }

    init() {
        this.popup = document.getElementById(this.config.popupId);
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    this.bindEvents();
                    if (!this.config.isOutOfStock) {
                        this.updateAvailabilityLink();
                    }
                }, 100);
            });
        } else {
            setTimeout(() => {
                this.bindEvents();
                if (!this.config.isOutOfStock) {
                    this.updateAvailabilityLink();
                }
            }, 500);
        }
    }

    bindEvents() {
        console.log('Binding availability events...');
        
        // Слушаем события выбора вариаций WooCommerce (только для товаров в наличии)
        if (!this.config.isOutOfStock) {
            jQuery(document).on('change', '.variations select', (e) => {
                console.log('Variation changed:', e.target.name, e.target.value);
                setTimeout(() => {
                    this.updateAvailabilityLink();
                }, 100);
            });
            
            jQuery(document).on('change', 'input[name*="attribute_"]', () => {
                console.log('Attribute input changed');
                setTimeout(() => {
                    this.updateAvailabilityLink();
                }, 100);
            });
            
            jQuery(document).on('change', 'select[name*="attribute_"]', () => {
                console.log('Attribute select changed');
                setTimeout(() => {
                    this.updateAvailabilityLink();
                }, 100);
            });

            jQuery('form.variations_form').on('found_variation', (event, variation) => {
                console.log('WooCommerce variation found:', variation);
                setTimeout(() => {
                    this.updateAvailabilityLink();
                }, 100);
            });
        }

        // Клик по ссылке проверки наличия
        jQuery(document).on('click', '#' + this.config.linkId, (e) => {
            e.preventDefault();
            console.log('Availability link clicked');
            this.openPopup();
        });

        // Клик по размерам в попапе
        jQuery(document).on('click', '.popup-size-item', (e) => {
            const clickedSize = jQuery(e.target);
            const sizeValue = clickedSize.data('size-value') || clickedSize.text().trim();
            
            jQuery('.popup-size-item').removeClass('selected');
            clickedSize.addClass('selected');
            
            this.currentSelectedSize = sizeValue;
            this.loadAvailabilityData();
        });

        // Закрытие попапа
        jQuery(document).on('click', '.popup-close, .popup-overlay', () => {
            this.closePopup();
        });

        // ESC
        jQuery(document).on('keydown', (e) => {
            if (e.keyCode === 27 && this.popup?.classList.contains('show')) {
                this.closePopup();
            }
        });
    }

    checkStockForSelectedSize() {
        const selectedSize = this.getSelectedSize();
        if (!selectedSize) return;

        jQuery.ajax({
            url: window.availability_ajax.url,
            type: 'POST',
            data: {
                action: 'check_product_stock',
                product_id: window.availability_ajax.product_id,
                size: selectedSize,
                nonce: window.availability_ajax.nonce
            },
            success: (response) => {
                console.log('AJAX response:', response);
                if (response.success) {
                    this.updateAvailabilityText(response.data);
                } else {
                    console.error('AJAX error:', response);
                }
            },
            error: (xhr, status, error) => {
                console.error('AJAX request failed:', error);
                this.updateAvailabilityText({
                    in_stock: true,
                    store_count: 1
                });
            }
        });
    }

    updateAvailabilityText(data) {
        const link = document.getElementById(this.config.linkId);
        const textSpan = document.getElementById(this.config.textId);
        
        if (!link || !textSpan) return;

        if (data.in_stock && Number(data.store_count) === 0) {
            link.classList.add('is-disabled');
            link.classList.remove('has-stock');
            textSpan.textContent = 'Доступно только онлайн';
        } else if (data.in_stock && Number(data.store_count) > 0) {
            link.classList.remove('is-disabled');
            link.classList.add('has-stock');
            textSpan.textContent = 'Проверить наличие в магазинах';
        } else {
            link.classList.add('is-disabled');
            link.classList.remove('has-stock');
            textSpan.textContent = 'Нет в наличии';
        }
    }      
                
    updateAvailabilityLink() {
        const link = document.getElementById(this.config.linkId);
        const textSpan = document.getElementById(this.config.textId);
        
        if (!link || !textSpan) return;

        const selectedSize = this.getSelectedSize();
        console.log('Selected size:', selectedSize);
        
        if (selectedSize) {
            this.checkStockForSelectedSize();
        }
    }
 
    openPopup() {
        if (this.isLoading) return;

        let selectedSize = this.getSelectedSize();
        const allSizes = this.getAllSizes();
        
        // For variable products with sizes
        if (allSizes.length > 0) {
            if (!selectedSize) {
                selectedSize = allSizes[0].value;
                console.log('No size selected, using first available size:', selectedSize);
            }
            this.currentSelectedSize = selectedSize;
        } else {
            // For simple products without sizes
            console.log('Simple product detected (no sizes), checking all store availability');
            this.currentSelectedSize = ''; // Empty string for simple products
        }
        
        this.showPopup();
        this.loadProductInfo();
        this.loadAvailabilityData();
    }

    showPopup() {
        this.popup?.classList.add('show');
        document.body.classList.add('popup-open');
    }

    closePopup() {
        this.popup?.classList.remove('show');
        document.body.classList.remove('popup-open');

        if (this.map) { 
            this.map.destroy(); 
            this.map = null; 
        }
        this.currentSelectedSize = null;
    }

    loadProductInfo() {
        const productTitle = jQuery('.section-title').text() || jQuery('h1').first().text();
        const selectedColor = this.getSelectedColor();
        const allSizes = this.getAllSizes();

        jQuery('.product-title').text(productTitle);

        let html = '';

        if (selectedColor) {
            html += `
                <div class="popup-attribute">
                    <span class="attribute-label">Цвет:</span>
                    <span class="attribute-value">${selectedColor}</span>
                </div>`;
        }

        if (allSizes.length) {
            html += `
                <div class="popup-attribute">
                    <span class="attribute-label">Размер:</span>
                    <div class="popup-sizes">
                        ${allSizes.map(s => `
                            <span class="popup-size-item ${s.value === this.currentSelectedSize ? 'selected' : ''}" 
                                  data-size-value="${s.value}" 
                                  style="cursor: pointer;">
                                ${s.name}
                            </span>`).join('')}
                    </div>
                </div>`;
        }

        jQuery('.product-attributes').html(html);
    }

    async loadAvailabilityData() {
        this.isLoading = true;
        this.showLoading();

        console.log('Loading availability data...');
        console.log('Current selected size:', this.currentSelectedSize);
        
        const ajaxConfig = this.config.isOutOfStock ? window.availability_ajax_oos : window.availability_ajax;
        console.log('AJAX config:', ajaxConfig);

        try {
            const requestData = {
                action: this.config.ajaxAction,
                product_id: ajaxConfig.product_id,
                size: this.currentSelectedSize,
                nonce: ajaxConfig.nonce
            };
            
            console.log('Sending AJAX request with data:', requestData);

            const response = await jQuery.ajax({
                url: ajaxConfig.url,
                type: 'POST',
                data: requestData
            });

            console.log('AJAX response:', response);

            if (response.success) {
                console.log('Response data:', response.data);
                console.log('Stores:', response.data.stores);
                
                this.displayStores(response.data.stores);
                this.initMap(response.data.stores);
            } else {
                console.error('AJAX returned error:', response);
                this.showError();
            }

        } catch (err) {
            console.error('AJAX request failed:', err);
            console.error('Error details:', err.responseText);
            this.showError();
        } finally {
            this.isLoading = false;
            this.hideLoading();
        }
    }

    displayStores(stores = []) {
        const wrap = document.getElementById(this.config.storesContainerId);
        console.log('displayStores called with:', stores);

        if (!wrap) return;

        if (!stores || !Array.isArray(stores) || stores.length === 0) {
            wrap.innerHTML = '<div class="no-stores">Нет данных о наличии</div>';
            return;
        }

        wrap.innerHTML = stores.map(st => `
            <div class="store-item ${st.status}">
                <div class="store-name-flex">
                    <div class="store-name">${st.store}</div>
                    <div class="store-address">${st.address}</div>
                    ${st.phone ? `<div class="store-phone" style="font-size: 13px; color: #888; margin-top: 4px;">${st.phone}</div>` : ''}
                </div>
                <div class="store-status">${st.quantity}</div>
            </div>
        `).join('');

        console.log('Stores HTML generated and inserted');
    }

    initMap(stores = []) {
        console.log('initMap called with stores:', stores);
        console.log('Map container ID:', this.config.mapContainerId);
        
        if (typeof ymaps === 'undefined') {
            console.error('❌ Yandex Maps API not loaded');
            return;
        }
        
        console.log('✓ Yandex Maps API loaded');

        ymaps.ready(() => {
            console.log('✓ ymaps.ready triggered');
            
            const container = document.getElementById(this.config.mapContainerId);
            console.log('Map container element:', container);
            
            if (!container) {
                console.error('❌ Map container not found:', this.config.mapContainerId);
                return;
            }
            
            console.log('✓ Container found, size:', container.offsetWidth, 'x', container.offsetHeight);

            if (this.map) {
                console.log('Destroying old map instance');
                this.map.destroy();
            }

            // Проверяем, есть ли магазины с координатами
            const storesWithCoords = stores.filter(st => st.coords && Array.isArray(st.coords) && st.coords.length === 2);
            console.log('Stores with valid coords:', storesWithCoords.length);
            
            if (storesWithCoords.length === 0) {
                console.warn('⚠ No stores with valid coordinates to show on map');
            }

            try {
                const center = storesWithCoords[0]?.coords || [59.926917, 30.322906];
                console.log('Map center:', center);
                
                this.map = new ymaps.Map(container, {
                    center: center,
                    zoom: 15,
                    controls: ['zoomControl']
                });
                
                console.log('✓ Map created successfully');

                storesWithCoords.forEach(st => {
                    console.log('Adding placemark for:', st.store, 'at', st.coords);
                    
                    const placemark = new ymaps.Placemark(st.coords, {
                        balloonContent: `
                            <div>
                                <strong>${st.store}</strong><br>
                                ${st.address}<br>
                                <em>${st.quantity}</em>
                            </div>`
                    }, {
                        iconLayout: 'default#imageWithContent',
                        iconImageHref: '/img/logo_black.svg',
                        iconImageSize: [40, 42],
                        iconImageOffset: [-20, -21]
                    });
                    
                    this.map.geoObjects.add(placemark);
                    console.log('✓ Placemark added');
                });

                if (storesWithCoords.length > 1) {
                    console.log('Multiple stores, fitting bounds...');
                    this.map.setBounds(this.map.geoObjects.getBounds(), { 
                        checkZoomRange: true,
                        duration: 300
                    });
                }

                setTimeout(() => {
                    this.map.container.fitToViewport();
                    console.log('✓ Map fitted to viewport');
                }, 100);
                
            } catch (error) {
                console.error('❌ Error creating map:', error);
            }
        });
    }

    showLoading() { jQuery('.popup-loading').show(); }
    hideLoading() { jQuery('.popup-loading').hide(); }

    showError() {
        jQuery('#' + this.config.storesContainerId).html(
            '<div class="availability-error">Ошибка при загрузке наличия</div>');
    }

    getSelectedSize() {
        console.log('Getting selected size...');
        
        let selectedSize = null;
        
        // 1. Стандартный WooCommerce селект
        const sizeSelect = jQuery('select[name="attribute_pa_size"]');
        if (sizeSelect.length && sizeSelect.val() && sizeSelect.val() !== '') {
            selectedSize = sizeSelect.val();
            console.log('Found size in select:', selectedSize);
        }
        
        // 2. Радио кнопки
        if (!selectedSize) {
            const sizeRadio = jQuery('input[name="attribute_pa_size"]:checked');
            if (sizeRadio.length) {
                selectedSize = sizeRadio.val();
                console.log('Found size in radio:', selectedSize);
            }
        }
        
        // 3. Вариации form data
        if (!selectedSize) {
            const variationsForm = jQuery('form.variations_form');
            if (variationsForm.length) {
                const formData = variationsForm.serializeArray();
                const sizeData = formData.find(item => item.name === 'attribute_pa_size');
                if (sizeData && sizeData.value && sizeData.value !== '') {
                    selectedSize = sizeData.value;
                    console.log('Found size in form data:', selectedSize);
                }
            }
        }
        
        // 4. Проверяем все селекты с атрибутами
        if (!selectedSize) {
            jQuery('select[name*="attribute_"]').each(function() {
                const name = jQuery(this).attr('name');
                const value = jQuery(this).val();
                console.log('Found attribute:', name, '=', value);
                if (name && name.includes('size') && value && value !== '') {
                    selectedSize = value;
                    return false;
                }
            });
        }
        
        console.log('Final selected size:', selectedSize);
        return selectedSize || null;
    }

    getSelectedColor() {
        const variationsForm = jQuery('form.variations_form');
        if (!variationsForm.length) return null;
        
        const variationsData = variationsForm.data('product_variations');
        if (!variationsData || !Array.isArray(variationsData)) return null;
        
        const allColors = new Set();
        variationsData.forEach(variation => {
            if (variation.attributes && variation.attributes.attribute_pa_color) {
                allColors.add(variation.attributes.attribute_pa_color);
            }
        });
        
        const colorInput = jQuery('input[name="attribute_pa_color"]:checked');
        if (colorInput.length) {
            return colorInput.siblings('span').data('select') || colorInput.val();
        }
        
        if (allColors.size > 0) {
            return Array.from(allColors)[0];
        }
        
        return null;
    }

    getAllSizes() {
        console.log('getAllSizes: getting ALL product sizes for modal...');
        
        // 1. Сначала пытаемся получить ВСЕ размеры из DOM (input radio buttons)
        // Это даст нам все размеры товара независимо от наличия
        const sizeInputs = jQuery('input[name="attribute_pa_size"]');
        if (sizeInputs.length > 0) {
            const sizes = sizeInputs.map(function () {
                const $input = jQuery(this);
                const val = $input.val();
                
                // Пытаемся получить название из разных источников
                let name = $input.data('size-name') // из data-size-name атрибута
                        || $input.siblings('span').data('select') // из span data-select
                        || $input.siblings('span').text().trim() // из текста span
                        || $input.next('span').data('select') // из следующего span
                        || $input.next('span').text().trim() // из текста следующего span
                        || val.toUpperCase(); // fallback - просто uppercase от slug
                
                console.log('getAllSizes: size', val, '-> display name:', name);
                return { value: val, name: name };
            }).get();
            
            if (sizes.length > 0) {
                console.log('getAllSizes: found', sizes.length, 'sizes in DOM inputs:', sizes);
                return sizes;
            }
        }
        
        // 2. Если нет radio buttons, пытаемся получить из select dropdown
        const sizeSelect = jQuery('select[name="attribute_pa_size"] option');
        if (sizeSelect.length > 0) {
            const sizes = [];
            sizeSelect.each(function() {
                const val = jQuery(this).val();
                const text = jQuery(this).text();
                // Пропускаем пустые опции (типа "Выберите размер")
                if (val && val !== '') {
                    sizes.push({ value: val, name: text || val });
                }
            });
            
            if (sizes.length > 0) {
                console.log('getAllSizes: found', sizes.length, 'sizes in select options:', sizes);
                return sizes;
            }
        }
        
        // 3. Если в DOM ничего нет, используем variations_form как fallback
        console.log('getAllSizes: no sizes in DOM, trying variations_form...');
        const variationsForm = jQuery('form.variations_form');
        if (!variationsForm.length) {
            console.log('getAllSizes: no variations form found, returning empty');
            return [];
        }
        
        const variationsData = variationsForm.data('product_variations');
        if (!variationsData || !Array.isArray(variationsData)) {
            console.log('getAllSizes: no variations data, returning empty');
            return [];
        }
        
        const allSizes = new Map();
        
        variationsData.forEach(variation => {
            if (variation.attributes && variation.attributes.attribute_pa_size) {
                const sizeValue = variation.attributes.attribute_pa_size;
                
                const sizeInput = jQuery(`input[name="attribute_pa_size"][value="${sizeValue}"]`);
                let sizeName = sizeValue;
                
                if (sizeInput.length) {
                    sizeName = sizeInput.siblings('span').data('select')
                            || sizeInput.siblings('span').text()
                            || sizeValue;
                }
                
                if (!sizeInput.length && variation.attributes_label) {
                    sizeName = variation.attributes_label.attribute_pa_size || sizeValue;
                }
                
                allSizes.set(sizeValue, sizeName);
            }
        });
        
        const sizes = Array.from(allSizes, ([value, name]) => ({ value, name }));
        console.log('getAllSizes: found', sizes.length, 'sizes from variations_form:', sizes);
        return sizes;
    }
}

