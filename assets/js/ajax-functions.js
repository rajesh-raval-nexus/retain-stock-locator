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

    function encodeSegment(segment) {
        if (!segment) return '';
        // Replace '/' with a safe alternative (e.g., double dash '-' or a unique token)
        // Use double dash as example
        return encodeURIComponent(String(segment).replace(/\//g, '-'));
    }

    function decodeSegment(segment) {
        if (!segment) return '';
        // Reverse the replacement of '-' back to '/'
        return decodeURIComponent(segment).replace(/-/g, '/');
    }

    /**
     * Build SEO-friendly path URL from filters
     * Single selections: /base-path/type/make/model/category/
     * Multiple selections: /base-path/make/model?type=type1&type=type2
     */
    // ---------------------------
    // BUILD: creates URL from filters
    // ---------------------------
    function buildSEOUrl(filters = {}, page) {
        const basePath = getBasePath(); // e.g. '/stock-locator'
        const pathParts = [];
        const queryParts = [];

        // Helper: arrays -> path if single, query if multiple
        function addToUrl(arr, paramName) {
            if (!arr) return;
            if (!Array.isArray(arr)) return;
            if (arr.length === 1) {
                pathParts.push(encodeSegment(String(arr[0])));
            } else if (arr.length > 1) {
                // multiple → "type=type1&type2&type3"
                let paramStr = encodeURIComponent(paramName) + '=' + encodeURIComponent(arr[0]);
                for (let i = 1; i < arr.length; i++) {
                    paramStr += '&' + encodeURIComponent(arr[i]);
                }
                queryParts.push(paramStr);
            }
        }

        // 1) add single-value path segments (type / make / model / categories)
        addToUrl(filters.type, 'type');
        addToUrl(filters.make, 'make');
        addToUrl(filters.model, 'model');
        addToUrl(filters.categories, 'categories');
        
        console.log(filters)
        
        // 2) if filter_type present -> add /under-2500/ or /above-50000/
        if (filters.filter_type && filters.filter_price) {
            const type = String(filters.filter_type).trim().toLowerCase();
            const price = String(filters.filter_price).trim();
            if ((type === 'under' || type === 'above') && price) {
                pathParts.push(`${type}-${encodeURIComponent(price)}`);
            }
            // NOTE: we intentionally DO NOT push filter_type/filter_price into queryParts
        } else {
            // 3) price range remains query param (only if both present)
            if (filters.price_from && filters.price_to) {
                queryParts.push(`price_range=${encodeURIComponent(String(filters.price_from) + '-' + String(filters.price_to))}`);
            }
        }

        // 4) year_range & hours_range always in querystring (as from-to)
        if (filters.year_from && filters.year_to) {
            queryParts.push(`year_range=${encodeURIComponent(String(filters.year_from) + '-' + String(filters.year_to))}`);
        }
        if (filters.hours_from && filters.hours_to) {
            queryParts.push(`hours_range=${encodeURIComponent(String(filters.hours_from) + '-' + String(filters.hours_to))}`);
        }

        // 5) other scalar query params
        ['keyword', 'sort'].forEach(k => {
            if (filters[k] !== undefined && filters[k] !== null && String(filters[k]).trim() !== '') {
                queryParts.push(`${encodeURIComponent(k)}=${encodeURIComponent(String(filters[k]))}`);
            }
        });

        // 6) pagination
        if (page && Number(page) > 1) queryParts.push(`pg=${Number(page)}`);

        // 7) build final URL
        const cleanBase = basePath.replace(/\/+$/, '');
        const path = cleanBase + (pathParts.length ? '/' + pathParts.join('/') : '') + '/';
        const query = queryParts.join('&');

        return path + (query ? '?' + query : '');
    }    

    // ---------------------------
    // PARSE: reads URL -> filters object
    // ---------------------------

    function parseFiltersFromURL() {
        const pathname = window.location.pathname || '';
        const params = new URLSearchParams(window.location.search || '');
        const search = window.location.search || '';
        const filters = {};

        const basePath = getBasePath(); // '/stock-locator'
        const filterPath = pathname.replace(basePath, '').replace(/^\/+|\/+$/g, '');
        const segments = filterPath.split('/').filter(s => s && s.trim().length);

        // sets for classification (populated server side via rsl_ajax_obj)
        const validMakes = new Set((rsl_ajax_obj.validMakes || []).map(m => String(m).toLowerCase()));
        const validModels = new Set((rsl_ajax_obj.validModels || []).map(m => String(m).toLowerCase()));
        const validCategories = new Set((rsl_ajax_obj.validCategories || []).map(m => String(m).toLowerCase()));
        const validTypes = new Set((rsl_ajax_obj.validTypes || []).map(m => String(m).toLowerCase()));

        filters.type = [];
        filters.make = [];
        filters.model = [];
        filters.categories = [];

        let hasPricePath = false;

        // parse path segments
        for (const seg of segments) {
            const decodedRaw = decodeSegment(seg); // use decodeSegment here
            const decoded = String(decodedRaw).toLowerCase();

            // match /under-2500/ or /above-50000/
            const m = decoded.match(/^(under|above)[/-](\d+)$/i);

            if (m) {
                const t = m[1].toLowerCase();
                const val = m[2];
                hasPricePath = true;
                filters.filter_type = t;
                filters.filter_price = val;
                if (t === 'under') {
                    filters.price_to = val;
                } else {
                    filters.price_from = val;
                }
                continue;
            }

            // classify other segments (case-insensitive comparison)
            if (validTypes.has(decoded)) {
                filters.type.push(decodedRaw);
            } else if (validMakes.has(decoded)) {
                filters.make.push(decodedRaw);
            } else if (validModels.has(decoded)) {
                filters.model.push(decodedRaw);
            } else if (validCategories.has(decoded)) {
                filters.categories.push(decodedRaw);
            } else {
                console.warn('Unknown segment in URL:', decodedRaw);
            }
        }

        // Handle query string multi-values (keeps support for weird ?type=New&Demo form)
            const queryString = search.replace(/^\?/, '');
            if (queryString) {
                const parts = queryString.split('&');
                let lastKey = null;
                for (const part of parts) {
                    if (!part) continue;
                    if (part.includes('=')) {
                        const [rawKey, rawVal] = part.split('=');
                        const key = decodeURIComponent(rawKey || '').trim();
                        const rawValue = rawVal === undefined ? '' : rawVal;
                        const value = decodeURIComponent(rawValue).trim();
                        lastKey = key;
                        // store multi-values as arrays (except special range keys we'll decode later)
                        if (!filters[key]) filters[key] = [];
                        if (value !== '') filters[key].push(value);
                    } else if (lastKey) {
                        // e.g. ?type=New&Demo (Demo has no key) -> use lastKey
                        const value = decodeURIComponent(part).trim();
                        if (!filters[lastKey]) filters[lastKey] = [];
                        if (value !== '') filters[lastKey].push(value);
                    }
                }
            }

            // decode canonical range query params (price_range/year_range/hours_range)
            if (params.has('price_range')) {
                const range = params.get('price_range') || '';
                const [from, to] = range.split('-').map(s => (s || '').trim());
                if (from) filters.price_from = from;
                if (to) filters.price_to = to;
            }

            if (params.has('year_range')) {
                const range = params.get('year_range') || '';
                const [from, to] = range.split('-').map(s => (s || '').trim());
                if (from) filters.year_from = from;
                if (to) filters.year_to = to;
            }

            if (params.has('hours_range')) {
                const range = params.get('hours_range') || '';
                const [from, to] = range.split('-').map(s => (s || '').trim());
                if (from) filters.hours_from = from;
                if (to) filters.hours_to = to;
            }

            // also support explicit numeric params (if someone used price_from/price_to directly)
            if (params.has('price_from') && !filters.price_from) filters.price_from = params.get('price_from');
            if (params.has('price_to') && !filters.price_to) filters.price_to = params.get('price_to');

            if (params.has('year_from') && !filters.year_from) filters.year_from = params.get('year_from');
            if (params.has('year_to') && !filters.year_to) filters.year_to = params.get('year_to');

            if (params.has('hours_from') && !filters.hours_from) filters.hours_from = params.get('hours_from');
            if (params.has('hours_to') && !filters.hours_to) filters.hours_to = params.get('hours_to');

            // If path-based price exists, skip any query-provided filter_type/filter_price to avoid duplicates
            if (hasPricePath) {
                if (filters.filter_type && filters.filter_price) {
                    // already set from path; remove any query-sourced duplicates kept in filters['filter_type'] array
                    if (Array.isArray(filters.filter_type)) delete filters.filter_type;
                    if (Array.isArray(filters.filter_price)) delete filters.filter_price;
                }
            } else {
                // if no price in path but filter_type/filter_price in query arrays, normalize them
                if (Array.isArray(filters.filter_type) && filters.filter_type.length) {
                    filters.filter_type = String(filters.filter_type[0]);
                }
                if (Array.isArray(filters.filter_price) && filters.filter_price.length) {
                    filters.filter_price = String(filters.filter_price[0]);
                }
            }

            // Normalize multi-valued keys left from query parsing:
            // convert arrays to proper typed filters where applicable
            ['type', 'make', 'model', 'categories'].forEach(k => {
                if (filters[k] && filters[k].length === 0) delete filters[k];
                // if query gave these as arrays via ?make=Abbey&make=Aitchison, we want arrays preserved
                if (filters[k] && filters[k].length === 1) {
                    // keep as array with single value (your buildURL expects arrays)
                }
            });

        // Final cleanup
        ['type', 'make', 'model', 'categories'].forEach(k => {
            if (Array.isArray(filters[k]) && filters[k].length === 0) delete filters[k];
        });

        // return both filters and page as before
        const page = params.has('pg') ? (parseInt(params.get('pg'), 10) || 1) : 1;

        // Scalars from params (keyword, sort)
        if (params.has('keyword')) filters.keyword = params.get('keyword');
        if (params.has('sort')) filters.sort = params.get('sort');

        return { filters, page };
    }

    /**
     * Merge server-side parsed filters with URL filters
     */
    function getInitialFilters() {
        const parsed = parseFiltersFromURL();                   
        let filters = parsed.filters;        
        
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

        // updateBreadcrumbs(filters);
        // updateTitle(filters);
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
            const $activeSort = $('.stock-sorting-cls[data-val="' + filters.sort + '"]');
            $activeSort.addClass('active');

            // Update dropdown button text to match active sort
            if ($activeSort.length) {
                $('.gfam-sort-btn').text($activeSort.text().trim());
            }
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
                    $('.rsl-stock-locator .gfam-product-grid').html(response.data.html);
                    $('.ajax-pagination').remove();
                    $('.load-more-btn').html(response.data.pagination);
                    reinitCarousel();
                }
                $('#loader').fadeOut(500);                
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

        // Wait slightly longer to ensure AJAX content is loaded
        setTimeout(function() {
            // Make sure the target exists
            let $target = $('.gfam-breadcrumb');
            if ($target.length) {
                // Calculate actual visible header height (sticky/fixed headers)
                let headerHeight = $('header:visible').outerHeight(true) || 0;

                // Calculate exact scroll position
                let scrollTarget = $target.offset().top - headerHeight - 50; // add small 20px buffer

                // Animate scroll
                $('html, body').stop(true).animate({
                    scrollTop: scrollTarget
                }, 500);
            }
        }, 600); // increased delay for safety
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
            setTimeout(() => {
                let filters = get_selected_filters();
                show_selected_val_on_sidebar(filters);
                applyFiltersAndPushState(filters, 1);
            }, 300); // 300ms delay — adjust as needed

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
        // updateBreadcrumbs(parsed.filters);
        // updateTitle(parsed.filters);
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
            // updateBreadcrumbs(filters);
            // updateTitle(filters);
            rsl_fetch_listings({ page: 1, per_page: rsl_ajax_obj.vdp_per_page, filters: filters });
        }
    })();
});