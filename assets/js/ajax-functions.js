jQuery(document).ready(function($) {

    /**
     * Get the base path from current URL (the actual page path, not parent folders)
     * Handles subfolder installations like /stock-locator/for-sale/
     * Uses data attribute or falls back to intelligent detection
     */

    function getBasePath() {
        try {
            return new URL(rsl_ajax_obj.current_page_url, window.location.origin).pathname.replace(/\/+$/, '');
        } catch (e) {
            return window.location.pathname.replace(/\/+$/, '');
        }
    }

    /**
     * Get the base label for breadcrumbs
     */
    function getBaseLabel() {
        const basePath = getBasePath();
        const segments = basePath.split('/').filter(s => s);
        const pageSlug = segments[segments.length - 1] || 'Home';
        
        return pageSlug.replace(/-/g, ' ')
            .split(' ')
            .map(w => w.charAt(0).toUpperCase() + w.slice(1))
            .join(' ');
    }

    /**
     * Build SEO-friendly path URL from filters
     * Single selections: /base-path/type/make/model/category/
     * Multiple selections: /base-path/make/model?type=type1&type=type2
     */
    function buildSEOUrl(filters, page) {
        const basePath = getBasePath(); // e.g. '/stock-locator'
        const pathParts = [];
        const queryParts = [];

        // Helper for arrays
        function addToUrl(arr, paramName) {
            if (!arr) return;
            if (arr.length === 1) {
                // single value → path
                pathParts.push(encodeURIComponent(arr[0]));
            } else if (arr.length > 1) {
                // multiple → "type=type1&type2&type3"
                let paramStr = encodeURIComponent(paramName) + '=' + encodeURIComponent(arr[0]);
                for (let i = 1; i < arr.length; i++) {
                    paramStr += '&' + encodeURIComponent(arr[i]);
                }
                queryParts.push(paramStr);
            }
        }

        // Path-based filters
        addToUrl(filters.type, 'type');
        addToUrl(filters.make, 'make');
        addToUrl(filters.model, 'model');
        addToUrl(filters.categories, 'categories');

        // Scalar filters → normal ?key=value
        const scalarKeys = [
            'price_from','price_to','year_from','year_to',
            'hours_from','hours_to','sort','keyword','filter_type','filter_price'
        ];

        scalarKeys.forEach(k => {
            const v = filters[k];
            if (v !== undefined && v !== null && String(v).trim() !== '') {
                queryParts.push(`${encodeURIComponent(k)}=${encodeURIComponent(v)}`);
            }
        });

        // Pagination
        if (page && page > 1) queryParts.push(`page=${page}`);

        // Build final URL
        const cleanBase = basePath.replace(/\/+$/, '');
        const path = cleanBase + (pathParts.length ? '/' + pathParts.join('/') : '') + '/';
        const query = queryParts.join('&');

        return path + (query ? '?' + query : '');
    }

    /**
     * Parse filters from SEO-friendly URL
     */
    function parseFiltersFromURL() {
        const pathname = window.location.pathname;
        const params = new URLSearchParams(window.location.search);
        const search = window.location.search;
        const filters = {};

        const basePath = getBasePath();
        const filterPath = pathname.replace(basePath, '').replace(/^\/+|\/+$/g, '');
        const segments = filterPath.split('/').filter(s => s);

        // Create sets from localized arrays for quick lookup
        const validMakes = new Set(rsl_ajax_obj.validMakes || []);
        const validModels = new Set(rsl_ajax_obj.validModels || []);
        const validCategories = new Set(rsl_ajax_obj.validCategories || []);
        const validTypes = new Set(rsl_ajax_obj.validTypes || []);

        filters.type = [];
        filters.make = [];
        filters.model = [];
        filters.categories = [];        

        segments.forEach(segment => {
            if (validTypes.has(segment)) {
                filters.type.push(segment);
            } else if (validMakes.has(segment)) {
                filters.make.push(segment);
            } else if (validModels.has(segment)) {
                filters.model.push(segment);
            } else if (validCategories.has(segment)) {
                filters.categories.push(segment);
            } else {
                console.warn('Unknown segment in URL:', segment);
            }
        });

        // --- Handle query string manually ---
        const queryString = search.replace(/^\?/, '');
        if (queryString) {
            const parts = queryString.split('&');
            let lastKey = null;
            for (const part of parts) {
                if (!part) continue;
                if (part.includes('=')) {
                    const [key, value] = part.split('=');
                    lastKey = decodeURIComponent(key);
                    const decodedVal = decodeURIComponent(value || '').trim();
                    if (!filters[lastKey]) filters[lastKey] = [];
                    if (decodedVal) filters[lastKey].push(decodedVal);
                } else if (lastKey) {
                    // e.g. &Demo after ?type=New&Demo
                    const decodedVal = decodeURIComponent(part.trim());
                    if (!filters[lastKey]) filters[lastKey] = [];
                    filters[lastKey].push(decodedVal);
                }
            }
        }        

        // Merge query string params into filters as before

        // Clean empty arrays
        ['type', 'make', 'model', 'categories'].forEach(key => {
            if (filters[key].length === 0) delete filters[key];
        });

        // Scalars parsing...

        const page = params.has('page') ? parseInt(params.get('page'), 10) || 1 : 1;

        return { filters, page };
    }

    /**
     * Merge server-side parsed filters with URL filters
     */
    function getInitialFilters() {
        const parsed = parseFiltersFromURL();                
        let filters = parsed.filters;

        console.log(filters);
        
        if (typeof rsl_initial_filters !== 'undefined' && rsl_initial_filters) {
            if (rsl_initial_filters.type && Array.isArray(rsl_initial_filters.type) && rsl_initial_filters.type.length) {
                filters.type = [...new Set([...(rsl_initial_filters.type || []), ...(filters.type || [])])];
            }
            
            if (rsl_initial_filters.make && Array.isArray(rsl_initial_filters.make) && rsl_initial_filters.make.length) {
                filters.make = [...new Set([...(rsl_initial_filters.make || []), ...(filters.make || [])])];
            }
            
            if (rsl_initial_filters.model && Array.isArray(rsl_initial_filters.model) && rsl_initial_filters.model.length) {
                filters.model = [...new Set([...(rsl_initial_filters.model || []), ...(filters.model || [])])];
            }
            
            if (rsl_initial_filters.categories && Array.isArray(rsl_initial_filters.categories) && rsl_initial_filters.categories.length) {
                filters.categories = [...new Set([...(rsl_initial_filters.categories || []), ...(filters.categories || [])])];
            }
            
            const scalarKeys = ['price_from','price_to','year_from','year_to','hours_from','hours_to','sort','keyword','filter_type','filter_price'];
            scalarKeys.forEach(key => {
                if (!filters[key] && rsl_initial_filters[key]) {
                    filters[key] = rsl_initial_filters[key];
                }
            });
        }
        
        return { filters: filters, page: parsed.page };
    }

    /**
     * Convert current filters & page to URL and push state
     */
    function applyFiltersAndPushState(filters, page, replace) {
        page = page || 1;
        replace = replace || false;
        
        const newUrl = buildSEOUrl(filters, page);
        
        try {
            if (replace) {
                window.history.replaceState({filters: filters, page: page}, '', newUrl);
            } else {
                window.history.pushState({filters: filters, page: page}, '', newUrl);
            }
        } catch (e) {
            console.warn('History API not available', e);
        }

        updateBreadcrumbs(filters);
        updateTitle(filters);
        rsl_fetch_listings({ page: page, per_page: rsl_ajax_obj.vdp_per_page, filters: filters });
    }

    /**
     * Update breadcrumbs DOM based on filters
     */
    function updateBreadcrumbs(filters) {
        filters = filters || {};
        const $crumb = $('.gfam-breadcrumb');
        if (!$crumb.length) return;

        const baseLabel = getBaseLabel();
        const items = [];
        items.push({ label: baseLabel, filters: {} });

        if (Array.isArray(filters.type) && filters.type.length) {
            filters.type.forEach(t => {
                const newFilters = { type: [t] };
                items.push({ label: t, filters: newFilters });
            });
        }

        if (Array.isArray(filters.make) && filters.make.length) {
            filters.make.forEach(mk => {
                const newFilters = { 
                    ...(filters.type && { type: filters.type }),
                    make: [mk] 
                };
                items.push({ label: mk, filters: newFilters });
            });
        }

        if (Array.isArray(filters.model) && filters.model.length) {
            filters.model.forEach(md => {
                const newFilters = {
                    ...(filters.type && { type: filters.type }),
                    ...(filters.make && { make: filters.make }),
                    model: [md]
                };
                items.push({ label: md, filters: newFilters });
            });
        }

        if (Array.isArray(filters.categories) && filters.categories.length) {
            filters.categories.forEach(cat => {
                const newFilters = {
                    ...(filters.type && { type: filters.type }),
                    ...(filters.make && { make: filters.make }),
                    ...(filters.model && { model: filters.model }),
                    categories: [cat]
                };
                items.push({ label: cat, filters: newFilters });
            });
        }

        if (filters.price_from || filters.price_to) {
            const label = 'Price: ' + (filters.price_from || 'Any') + ' - ' + (filters.price_to || 'Any');
            items.push({ label: label, filters: filters });
        }

        if (filters.keyword) {
            items.push({ label: 'Search: ' + filters.keyword, filters: filters });
        }

        const html = items.map((it, idx) => {
            const href = buildSEOUrl(it.filters, 1);
            return '<a href="' + href + '" class="rsl-crumb-link" data-crumb-index="' + idx + '">' + it.label + '</a>';
        }).join(' <span class="crumb-sep">/</span> ');

        $crumb.html(html);
    }

    /**
     * Breadcrumb click handling
     */
    $(document).on('click', '.rsl-breadcrumb .rsl-crumb-link', function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        
        const tempUrl = new URL(href, window.location.origin);
        const tempPath = tempUrl.pathname;
        const tempSearch = tempUrl.search;
        
        const originalPath = window.location.pathname;
        const originalSearch = window.location.search;
        window.history.replaceState(null, '', tempPath + tempSearch);
        
        const parsed = parseFiltersFromURL();
        
        window.history.replaceState(null, '', originalPath + originalSearch);
        
        applyFiltersToUI(parsed.filters);
        applyFiltersAndPushState(parsed.filters, 1);
    });

    /**
     * Update H1 and document.title based on filters
     */
    function updateTitle(filters) {
        filters = filters || {};
        let titleParts = [];
        
        if (Array.isArray(filters.make) && filters.make.length) {
            titleParts.push(filters.make.join(' '));
        }
        if (Array.isArray(filters.model) && filters.model.length) {
            titleParts.push(filters.model.join(' '));
        }
        if (Array.isArray(filters.categories) && filters.categories.length) {
            titleParts.push(filters.categories.join(' '));
        }
        if (Array.isArray(filters.type) && filters.type.length) {
            titleParts.push(filters.type.join(' '));
        }
        if (filters.price_to) titleParts.push('under $' + filters.price_to);
        if (filters.keyword) titleParts.push(filters.keyword);

        const base = 'Cars for Sale';
        const title = titleParts.length ? titleParts.join(' - ') + ' | ' + base : base;
        $('h1').text(titleParts.length ? titleParts.join(' - ') : base);
        document.title = title;
    }

    /**
     * Apply filters to UI elements
     */
    function applyFiltersToUI(filters) {
        filters = filters || {};
        
        $('input[name="category[]"]').prop('checked', false);
        $('input[name="make-model[]"]').prop('checked', false);
        $('.make-listing').prop('checked', false);
        $('.model-listing').prop('checked', false);
        $('input[name="type[]"]').prop('checked', false);

        if (Array.isArray(filters.categories)) {
            filters.categories.forEach(v => $('input[name="category[]"][value="' + v + '"]').prop('checked', true));
        }
        if (Array.isArray(filters.make)) {
            filters.make.forEach(v => $('.make-listing[value="' + v + '"]').prop('checked', true));
        }
        if (Array.isArray(filters.model)) {
            filters.model.forEach(v => $('.model-listing[value="' + v + '"]').prop('checked', true));
        }
        if (Array.isArray(filters.type)) {
            filters.type.forEach(v => $('input[name="type[]"][value="' + v + '"]').prop('checked', true));
        }

        if (filters.price_from !== undefined) $('.rsl-price-from').val(filters.price_from || '');
        if (filters.price_to !== undefined) $('.rsl-price-to').val(filters.price_to || '');
        if (filters.year_from !== undefined) $('.rsl-year-from').val(filters.year_from || '');
        if (filters.year_to !== undefined) $('.rsl-year-to').val(filters.year_to || '');
        if (filters.hours_from !== undefined) $('.rsl-hours-from').val(filters.hours_from || '');
        if (filters.hours_to !== undefined) $('.rsl-hours-to').val(filters.hours_to || '');
        
        if (filters.sort !== undefined) {
            $('.stock-sorting-cls').removeClass('active');
            $('.stock-sorting-cls[data-val="' + filters.sort + '"]').addClass('active');
        }
        if (filters.keyword !== undefined) {
            $('.main-listing-search').val(filters.keyword);
        }

        $('.block-price-filter').removeClass('active');
        if (filters.filter_type && filters.filter_price) {
            $('.block-price-filter[data-filter-type="' + filters.filter_type + '"][data-filter-price="' + filters.filter_price + '"]').addClass('active');
        }

        show_selected_val_on_sidebar(get_selected_filters());
    }

    /**
     * Generic function to fetch listings via AJAX
     */
    function rsl_fetch_listings(params) {
        let page = params.page || 1;
        let per_page = params.per_page || 10;
        let filters = params.filters || {};

        $('#loader').fadeIn(500);

        $.ajax({
            url: rsl_ajax_obj.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'rsl_get_stock_list',
                security: rsl_ajax_obj.nonce,
                page: page,
                per_page: per_page,
                filters: filters
            },
            success: function(response) {
                if (response.success) {
                    $('.gfam-product-grid').html(response.data.html);
                    $('.ajax-pagination').remove();
                    $('.load-more-btn').html(response.data.pagination);
                    reinitCarousel();
                }
                $('#loader').fadeOut(500);
                setTimeout(function() {
                    $('html, body').stop(true).animate({
                        scrollTop: $('.gfam-product-grid').offset().top - 100
                    }, 400);
                }, 150);
            },
            error: function(xhr, status, error) {
                $('#loader').fadeOut(500);
                console.error('AJAX Error:', error);
            }
        });
    }

    /**
     * PAGINATION click
     */
    $(document).on('click', '.ajax-pagination .page-number, .ajax-pagination .prev-page, .ajax-pagination .next-page', function() {
        if ($(this).hasClass('disabled') || $(this).hasClass('current')) return;
        let page = $(this).data('page');
        let filters = get_selected_filters();
        applyFiltersAndPushState(filters, page);
    });

    /**
     * Sorting click
     */
    $(".dropdown-menu .dropdown-item").on("click", function (e) {
        e.preventDefault();
        $(".gfam-sort-btn").text($(this).text());
        $('.dropdown-menu .dropdown-item').removeClass('active');
        $(this).addClass('active');
        let filters = get_selected_filters();
        show_selected_val_on_sidebar(filters);
        applyFiltersAndPushState(filters, 1);
    });

    $(document).on('input', '#popupMakeDesktop .category-body .gfam-search-input, #gfampopupMakeMobile .category-body .gfam-search-input', function() {
        const searchTerm = $(this).val().toLowerCase().trim();
        const $items = $('#popupMakeDesktop .category-body .accordion-item, #gfampopupMakeMobile .category-body .accordion-item');
        $items.each(function() {
            const makeName = $(this).find('label').text().toLowerCase();
            $(this).toggle(makeName.indexOf(searchTerm) !== -1);
        });
    });

    $(document).on('input', '#popupMakeDesktop .subcategory-body .gfam-search-input, #gfampopupMakeMobile .subcategory-body .gfam-search-input', function() {
        const searchTerm = $(this).val().toLowerCase().trim();
        const $container = $(this).closest('.subcategory-body');
        const $items = $container.find('.accordion-item');
        $items.each(function() {
            const modelName = $(this).find('label').text().toLowerCase();
            $(this).toggle(modelName.indexOf(searchTerm) !== -1);
        });
    });

    $(document).on('click', '.block-price-filter', function(e) {
        e.preventDefault();
        var filter_type = $(this).data('filter-type');
        var filter_price = $(this).data('filter-price');

        $('.clear-btn').click();

        let filters = get_selected_filters();
        show_selected_val_on_sidebar(filters);

        filters.price_from = '';
        filters.price_to = '';

        if(filter_type == 'above' && filter_price != undefined){
            filters.price_from = filter_price;
        }else{
            filters.price_to = filter_price;
        }
        filters.filter_type = filter_type;
        filters.filter_price = filter_price;

        applyFiltersAndPushState(filters, 1);
    });

    /**
     * Clear button click
     */
    $(".clear-btn").on("click", function (e) {
        e.preventDefault();
        $(this).blur();
        var clicked_clear_section = $(this).data('type');

        if(clicked_clear_section == 'category' || clicked_clear_section == 'make-model' || clicked_clear_section == 'type'){
            $('input[name="' + clicked_clear_section + '[]"]').prop('checked', false);

            if (clicked_clear_section == 'make-model') {
                $('.make-listing').prop('checked', false);
                $('.model-listing').prop('checked', false);
                
                const $inputs = $('#popupMakeDesktop .category-body .gfam-search-input, #gfampopupMakeMobile .category-body .gfam-search-input, #popupMakeDesktop .subcategory-body .gfam-search-input, #gfampopupMakeMobile .subcategory-body .gfam-search-input');
                $inputs.val('');
                $('.category-body .accordion-item, .subcategory-body .accordion-item').show();
            }
        }

        if(clicked_clear_section == 'price-range'){
            $('select[name="price-from"]').val('').trigger('change');
            $('select[name="price-to"]').val('').trigger('change');
            $('input[name="priceFromInput"]').val('');
            $('input[name="priceToInput"]').val('');
        }

        if(clicked_clear_section == 'year-range'){
            $('select[name="year-from"]').val('').trigger('change');
            $('select[name="year-to"]').val('').trigger('change');
            $('input[name="yearFromInput"]').val('');
            $('input[name="yearToInput"]').val('');
        }

        if(clicked_clear_section == 'hours-range'){
            $('select[name="hour-from"]').val('').trigger('change');
            $('select[name="hour-to"]').val('').trigger('change');
            $('input[name="hourFromInput"]').val('');
            $('input[name="hourToInput"]').val('');
        }
        
        if (e.originalEvent && e.originalEvent.isTrusted) {
            let filters = get_selected_filters();
            show_selected_val_on_sidebar(filters);
            applyFiltersAndPushState(filters, 1);
        }
    });

    /**
     * Get all selected filters
     */
    function get_selected_filters() {
        let filters = {};

        if ($('input[name="category[]"]').length) {
            filters.categories = $('input[name="category[]"]:checked').map(function() {
                return $(this).val();
            }).get();
        } else {
            filters.categories = [];
        }

        if ($('.make-listing').length) {
            filters.make = $('.make-listing:checked').map(function() {
                return $(this).val();
            }).get();
        } else {
            filters.make = [];
        }

        if ($('.model-listing').length) {
            filters.model = $('.model-listing:checked').map(function() {
                return $(this).val();
            }).get();
        } else {
            filters.model = [];
        }

        if ($('input[name="type[]"]').length) {
            filters.type = $('input[name="type[]"]:checked').map(function() {
                return $(this).val();
            }).get();
        } else {
            filters.type = [];
        }

        if ($('.rsl-price-tabs').length) {
            var activePriceTabID = $('.rsl-price-tabs.active').data('bs-target');
            filters.price_from = $(activePriceTabID + ' .rsl-price-from').val() || '';
            filters.price_to   = $(activePriceTabID + ' .rsl-price-to').val() || '';
        } else {
            filters.price_from = '';
            filters.price_to = '';
        }

        if ($('.rsl-year-tabs').length) {
            var activeYearTabID = $('.rsl-year-tabs.active').data('bs-target');
            filters.year_from = $(activeYearTabID + ' .rsl-year-from').val() || '';
            filters.year_to   = $(activeYearTabID + ' .rsl-year-to').val() || '';
        } else {
            filters.year_from = '';
            filters.year_to = '';
        }

        if ($('.rsl-hours-tabs').length) {
            var activeHoursTabID = $('.rsl-hours-tabs.active').data('bs-target');
            filters.hours_from = $(activeHoursTabID + ' .rsl-hours-from').val() || '';
            filters.hours_to   = $(activeHoursTabID + ' .rsl-hours-to').val() || '';
        } else {
            filters.hours_from = '';
            filters.hours_to = '';
        }

        if ($('.stock-sorting-cls').length) {
            filters.sort = $('.stock-sorting-cls.active').data('val') || '';
        } else {
            filters.sort = '';
        }

        if ($('.main-listing-search').length) {
            filters.keyword = $('.main-listing-search').val() ? $('.main-listing-search').val().trim() : '';
        } else {
            filters.keyword = '';
        }

        if ((filters.price_from || filters.price_to) === '') {
            if($('.block-price-filter.active').length != 0){
                var current_active_type = $('.block-price-filter.active').data('filter-type');
                var current_filter_price = $('.block-price-filter.active').data('filter-price');
                if(current_active_type == 'above'){
                    filters.price_from = current_filter_price;
                }else{
                    filters.price_to = current_filter_price;
                }
                filters.filter_type = current_active_type;
                filters.filter_price = current_filter_price;
            }
        }

        return filters;
    }

    /**
     * Search input with debounce
     */
    let rslSearchTimeout = null;
    $(document).on('input', '.main-listing-search', function () {
        const value = $(this).val();
        $('.main-listing-search').not(this).val(value);

        clearTimeout(rslSearchTimeout);
        rslSearchTimeout = setTimeout(function() {
            let filters = get_selected_filters();
            show_selected_val_on_sidebar(filters);
            applyFiltersAndPushState(filters, 1);
        }, 500);
    });

    $(document).on('keypress', '.main-listing-search', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            clearTimeout(rslSearchTimeout);
            let filters = get_selected_filters();
            show_selected_val_on_sidebar(filters);
            applyFiltersAndPushState(filters, 1);
        }
    });

    /**
     * Apply filter button
     */
    $('.rsl-apply-filter').on('click', function(e) {
        e.preventDefault();
        $(this).blur();
        $('.block-price-filter').removeClass('active');
        let filters = get_selected_filters();
        console.log('selected-filters'+filters)
        show_selected_val_on_sidebar(filters);
        applyFiltersAndPushState(filters, 1);
    });

    /**
     * Sidebar tag helper
     */
    function addTag(containerSelector, label, key, value) {
        const tag = $('<span class="gfam-filter-tag" data-key="' + key + '" data-value="' + value + '">' + label + ' <span class="gfam-clear-tag">×</span></span>');
        $(containerSelector).append(tag);
    }

    function show_selected_val_on_sidebar(filters) {
        $('.selected-category-options-list, .selected-make-options-list, .selected-type-options-list, .selected-price-options-list, .selected-year-options-list, .selected-hours-options-list').empty();

        if (Array.isArray(filters.categories) && filters.categories.length) {
            filters.categories.forEach(val => addTag('.selected-category-options-list', val, 'category', val));
        }
        if (Array.isArray(filters.make) && filters.make.length) {
            filters.make.forEach(val => addTag('.selected-make-options-list', val, 'make', val));
        }
        if (Array.isArray(filters.model) && filters.model.length) {
            filters.model.forEach(val => addTag('.selected-make-options-list', val, 'model', val));
        }
        if (Array.isArray(filters.type) && filters.type.length) {
            filters.type.forEach(val => addTag('.selected-type-options-list', val, 'type', val));
        }

        reinitSeeMoreLess();
    }

    /**
     * Remove filter tag
     */
    $(document).on('click', '.gfam-clear-tag', function() {
        const tag = $(this).closest('.gfam-filter-tag');
        const key = tag.data('key');
        const value = tag.data('value');

        if (key === 'category') {
            $('input[name="category[]"][value="' + value + '"]').prop('checked', false);
        } else if (key === 'make') {
            $('.make-listing[value="' + value + '"]').prop('checked', false);
        } else if (key === 'model') {
            $('.model-listing[value="' + value + '"]').prop('checked', false);
        } else if (key === 'type') {
            $('input[name="type[]"][value="' + value + '"]').prop('checked', false);
        }

        tag.remove();

        let filters = get_selected_filters();
        applyFiltersAndPushState(filters, 1);
    });

    /**
     * Browser back/forward navigation
     */
    window.addEventListener('popstate', function(e) {
        const parsed = parseFiltersFromURL();
        applyFiltersToUI(parsed.filters);
        updateBreadcrumbs(parsed.filters);
        updateTitle(parsed.filters);
        rsl_fetch_listings({ page: parsed.page, per_page: rsl_ajax_obj.vdp_per_page, filters: parsed.filters });
    });

    /**
     * Initialize from URL on page load
     */
    (function initFromURL() {
        const initial = getInitialFilters();
        
        if (Object.keys(initial.filters).length) {
            applyFiltersToUI(initial.filters);
            applyFiltersAndPushState(initial.filters, initial.page, true);
        } else {
            const filters = get_selected_filters();
            updateBreadcrumbs(filters);
            updateTitle(filters);
            rsl_fetch_listings({ page: 1, per_page: rsl_ajax_obj.vdp_per_page, filters: filters });
        }
    })();

});