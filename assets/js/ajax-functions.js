jQuery(document).ready(function($) {

    /**
     * Helper: serialize filters into URLSearchParams
     * keeps arrays as repeated params: categories=cat1&categories=cat2
     */
    function buildSearchParams(filters, page) {
        const params = new URLSearchParams();

        if (page && page > 1) params.set('page', page);

        if (!filters) return params;

        // categories, makeModel, type are arrays
        if (Array.isArray(filters.categories)) {
            filters.categories.forEach(v => { if (v !== null && v !== '') params.append('categories', v); });
        }
        if (Array.isArray(filters.makeModel)) {
            filters.makeModel.forEach(v => { if (v !== null && v !== '') params.append('makeModel', v); });
        }
        if (Array.isArray(filters.type)) {
            filters.type.forEach(v => { if (v !== null && v !== '') params.append('type', v); });
        }

        // scalar filters
        const scalarKeys = ['price_from','price_to','year_from','year_to','hours_from','hours_to','sort','keyword','filter_type','filter_price'];
        scalarKeys.forEach(k => {
            if (filters[k] !== undefined && filters[k] !== null && String(filters[k]).trim() !== '') {
                params.set(k, filters[k]);
            }
        });

        return params;
    }

    /**
     * Convert current filters & page to URL and push state
     */
    function applyFiltersAndPushState(filters, page = 1, replace = false) {
        const params = buildSearchParams(filters, page);
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        try {
            if (replace) window.history.replaceState({filters: filters, page: page}, '', newUrl);
            else window.history.pushState({filters: filters, page: page}, '', newUrl);
        } catch (e) {
            // silent fallback
            console.warn('History API not available', e);
        }

        // update UI pieces
        updateBreadcrumbs(filters);
        updateTitle(filters);

        // finally fetch
        rsl_fetch_listings({ page: page, per_page: rsl_ajax_obj.vdp_per_page, filters: filters });
    }

    /**
     * Parse filters & page from current URL
     */
    function parseFiltersFromURL() {
        const params = new URLSearchParams(window.location.search);
        const filters = {};

        // arrays
        const cats = params.getAll('categories');
        if (cats && cats.length) filters.categories = cats;

        const makes = params.getAll('makeModel');
        if (makes && makes.length) filters.makeModel = makes;

        const types = params.getAll('type');
        if (types && types.length) filters.type = types;

        // scalars
        ['price_from','price_to','year_from','year_to','hours_from','hours_to','sort','keyword','filter_type','filter_price'].forEach(k => {
            if (params.has(k)) filters[k] = params.get(k);
        });

        const page = params.has('page') ? parseInt(params.get('page'), 10) || 1 : 1;

        return { filters, page };
    }

    /**
     * Update breadcrumbs DOM based on filters
     * Requires container `.rsl-breadcrumb` in your markup
     */
    function updateBreadcrumbs(filters = {}) {
        const $crumb = $('.rsl-breadcrumb');
        if (!$crumb.length) return;

        const items = [];
        // Home / base
        items.push({ label: 'Cars for sale', params: {} });

        // categories
        if (Array.isArray(filters.categories) && filters.categories.length) {
            filters.categories.forEach(cat => {
                items.push({ label: cat, params: { categories: [cat] } });
            });
        }

        // makeModel - if values are "Make|Model" you may want to split. For now treat label as-is
        if (Array.isArray(filters.makeModel) && filters.makeModel.length) {
            filters.makeModel.forEach(mm => {
                // try to split on common separator if present
                let label = mm;
                if (mm.indexOf('|') !== -1) label = mm.split('|').join(' ');
                else if (mm.indexOf(':') !== -1) label = mm.split(':').join(' ');
                items.push({ label: label, params: { makeModel: [mm] } });
            });
        }

        // type
        if (Array.isArray(filters.type) && filters.type.length) {
            filters.type.forEach(t => items.push({ label: t, params: { type: [t] } }));
        }

        // price
        if (filters.price_from || filters.price_to) {
            const label = `Price: ${filters.price_from || 'Any'} - ${filters.price_to || 'Any'}`;
            items.push({ label: label, params: { price_from: filters.price_from || '', price_to: filters.price_to || '' } });
        }

        // keyword
        if (filters.keyword) {
            items.push({ label: `Search: ${filters.keyword}`, params: { keyword: filters.keyword } });
        }

        // render
        const html = items.map((it, idx) => {
            // build href for this crumb (params => querystring)
            const params = new URLSearchParams();
            // params can contain arrays - we accept either arrays in it.params
            for (const k in it.params) {
                const v = it.params[k];
                if (Array.isArray(v)) v.forEach(x => { if (x !== '') params.append(k, x); });
                else if (v !== undefined && v !== '') params.set(k, v);
            }
            const href = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
            // final crumb anchor
            return `<a href="${href}" class="rsl-crumb-link" data-crumb-index="${idx}">${it.label}</a>`;
        }).join(' <span class="crumb-sep">/</span> ');

        $crumb.html(html);
    }

    /**
     * Breadcrumb click handling (intercept and apply subset filters)
     */
    $(document).on('click', '.rsl-breadcrumb .rsl-crumb-link', function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        const url = new URL(href, window.location.origin);
        const params = Object.fromEntries(url.searchParams.entries());

        // convert single-valued params to arrays where relevant
        const filters = {};
        if (url.searchParams.has('categories')) filters.categories = url.searchParams.getAll('categories');
        if (url.searchParams.has('makeModel')) filters.makeModel = url.searchParams.getAll('makeModel');
        if (url.searchParams.has('type')) filters.type = url.searchParams.getAll('type');

        // scalars
        ['price_from','price_to','year_from','year_to','hours_from','hours_to','sort','keyword','filter_type','filter_price'].forEach(k => {
            if (url.searchParams.has(k)) filters[k] = url.searchParams.get(k);
        });

        // ensure UI reflects filters (set inputs)
        applyFiltersToUI(filters);

        // apply and fetch
        applyFiltersAndPushState(filters, 1);
    });

    /**
     * Update H1 and document.title based on filters
     */
    function updateTitle(filters = {}) {
        let titleParts = [];
        if (Array.isArray(filters.makeModel) && filters.makeModel.length) {
            titleParts.push(filters.makeModel.join(' '));
        }
        if (Array.isArray(filters.categories) && filters.categories.length) {
            titleParts.push(filters.categories.join(' '));
        }
        if (filters.price_to) titleParts.push('under $' + filters.price_to);
        if (filters.keyword) titleParts.push(filters.keyword);

        const base = 'Cars for Sale';
        const title = titleParts.length ? `${titleParts.join(' - ')} | ${base}` : base;
        $('h1').text(titleParts.length ? titleParts.join(' - ') : base);
        document.title = title;
    }

    /**
     * Apply filters to UI elements (checkboxes, selects, inputs, etc.)
     * This keeps the UI consistent when loading from URL or clicking breadcrumbs
     */
    function applyFiltersToUI(filters = {}) {
        // Clear relevant groups first
        $('input[name="category[]"]').prop('checked', false);
        $('input[name="make-model[]"]').prop('checked', false);
        $('input[name="type[]"]').prop('checked', false);

        // set arrays
        if (Array.isArray(filters.categories)) {
            filters.categories.forEach(v => $(`input[name="category[]"][value="${v}"]`).prop('checked', true));
        }
        if (Array.isArray(filters.makeModel)) {
            filters.makeModel.forEach(v => $(`input[name="make-model[]"][value="${v}"]`).prop('checked', true));
        }
        if (Array.isArray(filters.type)) {
            filters.type.forEach(v => $(`input[name="type[]"][value="${v}"]`).prop('checked', true));
        }

        // scalars
        if (filters.price_from !== undefined) {
            $('.rsl-price-from').val(filters.price_from || '');
        }
        if (filters.price_to !== undefined) {
            $('.rsl-price-to').val(filters.price_to || '');
        }
        if (filters.year_from !== undefined) $('.rsl-year-from').val(filters.year_from || '');
        if (filters.year_to !== undefined) $('.rsl-year-to').val(filters.year_to || '');
        if (filters.hours_from !== undefined) $('.rsl-hours-from').val(filters.hours_from || '');
        if (filters.hours_to !== undefined) $('.rsl-hours-to').val(filters.hours_to || '');
        if (filters.sort !== undefined) {
            // mark active sort
            $('.stock-sorting-cls').removeClass('active');
            $(`.stock-sorting-cls[data-val="${filters.sort}"]`).addClass('active');
        }
        if (filters.keyword !== undefined) {
            $('.main-listing-search').val(filters.keyword);
        }

        // reflect block-price-filter active if filter_type/filter_price present
        $('.block-price-filter').removeClass('active');
        if (filters.filter_type && filters.filter_price) {
            $(`.block-price-filter[data-filter-type="${filters.filter_type}"][data-filter-price="${filters.filter_price}"]`).addClass('active');
        }

        // show selected values on sidebar
        show_selected_val_on_sidebar(get_selected_filters());
    }

    /**
     * Generic function to fetch listings via AJAX (original, unchanged except small comment)
     */
    function rsl_fetch_listings(params) {
        let { page = 1, per_page = 10, filters = {} } = params;

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
                    // Replace product grid
                    $('.gfam-product-grid').html(response.data.html);

                    // Remove old pagination and append new one
                    $('.ajax-pagination').remove();
                    $('.load-more-btn').html(response.data.pagination);

                    reinitCarousel();
                }
                $('#loader').fadeOut(500);
                // Give browser time to render all items first
                setTimeout(function() {
                    $('html, body').stop(true).animate({
                        scrollTop: $('.gfam-product-grid').offset().top - 100
                    }, 400);
                }, 150); // small delay helps smooth scroll

            },
            error: function(xhr, status, error) {
                $('#loader').fadeOut(500);
                console.error('AJAX Error:', error);
            }
        });
    }

    /**
     * PAGINATION click -> read page, current filters from UI, then apply
     */
    $(document).on('click', '.ajax-pagination .page-number, .ajax-pagination .prev-page, .ajax-pagination .next-page', function() {
        if ($(this).hasClass('disabled') || $(this).hasClass('current')) return;

        let page = $(this).data('page');
        let filters = get_selected_filters();

        // use wrapper so URL is updated
        applyFiltersAndPushState(filters, page);
    });

    /**
     * Sorting click -> set active, then apply filters
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

    // --- Search for Makes ---
    $(document).on('input', '#popupMakeDesktop .category-body .gfam-search-input, #gfampopupMakeMobile .category-body .gfam-search-input', function() {
      const searchTerm = $(this).val().toLowerCase().trim();
      const $items = $('#popupMakeDesktop .category-body .accordion-item, #gfampopupMakeMobile .category-body .accordion-item');

      $items.each(function() {
        const makeName = $(this).find('label').text().toLowerCase();
        $(this).toggle(makeName.indexOf(searchTerm) !== -1);
      });
    });

    // --- Search for Models (inside subcategory-body) ---
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
            // Uncheck all checkboxes for that group
            $('input[name="' + clicked_clear_section + '[]"]').prop('checked', false);

            if (clicked_clear_section == 'make-model') {
                const $inputs = $('#popupMakeDesktop .category-body .gfam-search-input, \
                                    #gfampopupMakeMobile .category-body .gfam-search-input, \
                                    #popupMakeDesktop .subcategory-body .gfam-search-input, \
                                    #gfampopupMakeMobile .subcategory-body .gfam-search-input');

                $inputs.val('');
                $('.category-body .accordion-item, .subcategory-body .accordion-item').show();
            }
        }

        if(clicked_clear_section == 'price-range'){
            // Clear the select(s) having that name
            $('select[name="price-from"]').val('').trigger('change');
            $('select[name="price-to"]').val('').trigger('change');
            $('input[name="priceFromInput"]').val('');
            $('input[name="priceToInput"]').val('');
        }

        if(clicked_clear_section == 'year-range'){
            // Clear the select(s) having that name
            $('select[name="year-from"]').val('').trigger('change');
            $('select[name="year-to"]').val('').trigger('change');
            $('input[name="yearFromInput"]').val('');
            $('input[name="yearToInput"]').val('');
        }

        if(clicked_clear_section == 'hours-range'){
            // Clear the select(s) having that name
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

        if ($('input[name="make-model[]"]').length) {
            filters.makeModel = $('input[name="make-model[]"]:checked').map(function() {
                return $(this).val();
            }).get();
        } else {
            filters.makeModel = [];
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
            filters.keyword = $('.main-listing-search').val()?.trim() || '';
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
                    filters.price_to   = current_filter_price;
                }
                filters.filter_type = current_active_type;
                filters.filter_price = current_filter_price;
            }
        }

        return filters;
    }

    /**
     * Search input (with debounce) -> apply
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
        show_selected_val_on_sidebar(filters);

        applyFiltersAndPushState(filters, 1);
    });

    /**
     * Sidebar tag helper (unchanged)
     */
    function addTag(containerSelector, label, key, value) {
        const tag = $(`
            <span class="gfam-filter-tag" data-key="${key}" data-value="${value}">
                ${label} <span class="gfam-clear-tag">×</span>
            </span>
        `);
        $(containerSelector).append(tag);
    }

    function show_selected_val_on_sidebar(filters) {
        $('.selected-category-options-list, .selected-make-options-list, .selected-type-options-list, .selected-price-options-list, .selected-year-options-list, .selected-hours-options-list').empty();

        if (Array.isArray(filters.categories) && filters.categories.length) filters.categories.forEach(val => addTag('.selected-category-options-list', val, 'category', val));
        if (Array.isArray(filters.makeModel) && filters.makeModel.length) filters.makeModel.forEach(val => addTag('.selected-make-options-list', val, 'make-model', val));
        if (Array.isArray(filters.type) && filters.type.length) filters.type.forEach(val => addTag('.selected-type-options-list', val, 'type', val));
        // keep commented lines in case you want to show price/year/hours tags
        // if (filters.price_from || filters.price_to) addTag('.selected-price-options-list', `Price: ${filters.price_from || 'Any'} - ${filters.price_to || 'Any'}`, 'price', `${filters.price_from}-${filters.price_to}`);
        // if (filters.year_from || filters.year_to) addTag('.selected-year-options-list', `Year: ${filters.year_from || 'Any'} - ${filters.year_to || 'Any'}`, 'year', `${filters.year_from}-${filters.year_to}`);
        // if (filters.hours_from || filters.hours_to) addTag('.selected-hours-options-list', `Hours: ${filters.hours_from || 'Any'} - ${filters.hours_to || 'Any'}`, 'hours', `${filters.hours_from}-${filters.hours_to}`);

        reinitSeeMoreLess();
    }

    /**
     * Remove filter tag
     */
    $(document).on('click', '.gfam-clear-tag', function() {
        const tag = $(this).closest('.gfam-filter-tag');
        const key = tag.data('key');
        const value = tag.data('value');

        if (key === 'category') $(`input[name="category[]"][value="${value}"]`).prop('checked', false);
        else if (key === 'make-model') $(`input[name="make-model[]"][value="${value}"]`).prop('checked', false);
        else if (key === 'type') $(`input[name="type[]"][value="${value}"]`).prop('checked', false);

        tag.remove();

        let filters = get_selected_filters();
        applyFiltersAndPushState(filters, 1);
    });

    /**
     * On browser navigation (back/forward), re-apply filters from URL
     */
    window.addEventListener('popstate', function(e) {
        const parsed = parseFiltersFromURL();
        applyFiltersToUI(parsed.filters);
        updateBreadcrumbs(parsed.filters);
        updateTitle(parsed.filters);
        // fetch listings for that page
        rsl_fetch_listings({ page: parsed.page, per_page: rsl_ajax_obj.vdp_per_page, filters: parsed.filters });
    });

    /**
     * Init: if URL has params, apply them; otherwise load default first page
     */
    (function initFromURL() {
        const parsed = parseFiltersFromURL();
        if (Object.keys(parsed.filters).length) {
            applyFiltersToUI(parsed.filters);
            // replace state rather than push so back-button isn't polluted on initial load
            applyFiltersAndPushState(parsed.filters, parsed.page, true);
        } else {
            // no filters in url -> load default listing
            const filters = get_selected_filters();
            updateBreadcrumbs(filters);
            updateTitle(filters);
            rsl_fetch_listings({ page: 1, per_page: rsl_ajax_obj.vdp_per_page, filters: filters });
        }
    })();

});
