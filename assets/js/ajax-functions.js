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

    function normalizeForSlug(str) {
        return String(str || '')
            .trim()
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')   // Replace any non-alphanumeric with a hyphen
            .replace(/^-+|-+$/g, '');      // Trim leading/trailing hyphens
    }

    function encodeSegment(segment) {
        return normalizeForSlug(segment);
    }

    function decodeSegment(segment) {
        // Just return the raw slug — we won’t “reverse” it,
        // we’ll *match* it to real values via normalization later.
        return String(segment || '').trim();
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

        function addToUrl(arr, paramName) {
            if (!arr || !Array.isArray(arr)) return;

            // Normalize values: lowercase, replace /, space, special chars → '-'
            function normalizeForSlug(str) {
                return String(str || '')
                    .trim()
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')  // spaces, slashes, symbols → '-'
                    .replace(/^-+|-+$/g, '');     // trim starting/ending '-'
            }

            if (arr.length === 1) {
                // Single value → use it in the path
                pathParts.push(normalizeForSlug(arr[0]));
            } else if (arr.length > 1) {
                // Multiple values → single key, ampersand-separated
                const slugValues = arr.map(v => normalizeForSlug(v));
                const combined = encodeURIComponent(paramName) + '=' + slugValues.join('&');
                queryParts.push(combined);
            }
        }


        // 1) add single-value path segments (type / make / model / categories)
        addToUrl(filters.type, 'type');
        addToUrl(filters.make, 'make');
        addToUrl(filters.model, 'model');
        addToUrl(filters.categories, 'categories');
        
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

        const basePath = getBasePath(); // e.g. '/stock-locator'
        const filterPath = pathname.replace(basePath, '').replace(/^\/+|\/+$/g, '');
        const segments = filterPath.split('/').filter(s => s && s.trim().length);

        // Slug maps
        const slugMap = (rsl_ajax_obj.slugMap || {});        
        const makeMap = slugMap.makes || {};
        const modelMap = slugMap.models || {};
        const catMap   = slugMap.categories || {};
        const typeMap  = slugMap.types || {};

        // Fallback valid sets
        const validMakes = new Set((rsl_ajax_obj.validMakes || []).map(m => String(m).toLowerCase()));
        const validModels = new Set((rsl_ajax_obj.validModels || []).map(m => String(m).toLowerCase()));
        const validCategories = new Set((rsl_ajax_obj.validCategories || []).map(m => String(m).toLowerCase()));
        const validTypes = new Set((rsl_ajax_obj.validTypes || []).map(m => String(m).toLowerCase()));

        filters.type = [];
        filters.make = [];
        filters.model = [];
        filters.categories = [];

        let hasPricePath = false;

        // Normalize to slug
        function normalizeForSlug(str) {
            return String(str || '')
                .trim()
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        // --- Step 1: Parse path segments ---
        for (const seg of segments) {
            const raw = decodeSegment(seg);
            const slug = normalizeForSlug(raw);

            // Handle /under-2500/
            const m = slug.match(/^(under|above)-(\d+)$/i);
            if (m) {
                const type = m[1].toLowerCase();
                const val = m[2];
                hasPricePath = true;
                filters.filter_type = type;
                filters.filter_price = val;
                if (type === 'under') filters.price_to = val;
                else filters.price_from = val;
                continue;
            }

            // Match via slugMap
            if (typeMap[slug]) filters.type.push(typeMap[slug]);
            else if (makeMap[slug]) filters.make.push(makeMap[slug]);
            else if (modelMap[slug]) filters.model.push(modelMap[slug]);
            else if (catMap[slug]) filters.categories.push(catMap[slug]);

            // Fallback sets
            else if (validTypes.has(slug)) filters.type.push(raw);
            else if (validMakes.has(slug)) filters.make.push(raw);
            else if (validModels.has(slug)) filters.model.push(raw);
            else if (validCategories.has(slug)) filters.categories.push(raw);
            else console.warn('Unknown segment in URL:', seg);
        }

        // --- Step 2: Parse query parameters (UPDATED PART INCLUDED) ---
        const queryString = search.replace(/^\?/, '');
        if (queryString) {
            const parts = queryString.split('&');
            let lastKey = null;

            for (const part of parts) {
                if (!part) continue;

                // key=value
                if (part.includes('=')) {
                    const [rawKey, rawVal] = part.split('=');
                    const key = decodeURIComponent(rawKey || '').trim();
                    const rawClean = decodeURIComponent(rawVal || '').trim();
                    const slug = normalizeForSlug(rawClean);

                    lastKey = key;
                    if (!filters[key]) filters[key] = [];

                    // SlugMap mapping for proper keys
                    if (key === 'categories' && catMap[slug]) filters[key].push(catMap[slug]);
                    else if (key === 'make' && makeMap[slug]) filters[key].push(makeMap[slug]);
                    else if (key === 'model' && modelMap[slug]) filters[key].push(modelMap[slug]);
                    else if (key === 'type' && typeMap[slug]) filters[key].push(typeMap[slug]);
                    else filters[key].push(rawClean);

                } else if (lastKey) {
                    // --- ONLY CHANGE YOU REQUESTED ---
                    const rawClean = decodeURIComponent(part).trim();
                    const slug = normalizeForSlug(rawClean);

                    if (!filters[lastKey]) filters[lastKey] = [];

                    // Support orphan values for category / make / model / type
                    if (lastKey === 'categories' && catMap[slug]) filters[lastKey].push(catMap[slug]);
                    else if (lastKey === 'make' && makeMap[slug]) filters[lastKey].push(makeMap[slug]);
                    else if (lastKey === 'model' && modelMap[slug]) filters[lastKey].push(modelMap[slug]);
                    else if (lastKey === 'type' && typeMap[slug]) filters[lastKey].push(typeMap[slug]);
                    else filters[lastKey].push(rawClean);
                }
            }
        }

        // --- Step 3: Range ---
        const rangeKeys = ['price', 'year', 'hours'];
        for (const key of rangeKeys) {
            const range = params.get(`${key}_range`);
            if (range) {
                const [from, to] = range.split('-').map(s => (s || '').trim());
                if (from) filters[`${key}_from`] = from;
                if (to) filters[`${key}_to`] = to;
            }
            if (params.has(`${key}_from`) && !filters[`${key}_from`]) filters[`${key}_from`] = params.get(`${key}_from`);
            if (params.has(`${key}_to`) && !filters[`${key}_to`]) filters[`${key}_to`] = params.get(`${key}_to`);
        }

        // --- Step 4 ---
        if (!hasPricePath) {
            if (Array.isArray(filters.filter_type) && filters.filter_type.length)
                filters.filter_type = String(filters.filter_type[0]);
            if (Array.isArray(filters.filter_price) && filters.filter_price.length)
                filters.filter_price = String(filters.filter_price[0]);
        }

        // --- Step 5: Cleanup ---
        ['type', 'make', 'model', 'categories'].forEach(k => {
            if (Array.isArray(filters[k]) && filters[k].length === 0) delete filters[k];
        });

        // --- Step 6 ---
        const page = params.has('pg') ? (parseInt(params.get('pg'), 10) || 1) : 1;

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

        updateBreadcrumbs(filters);
        updateTitle(filters);
        rsl_fetch_listings({ page: page, per_page: rsl_ajax_obj.vdp_per_page, filters: filters });
    }

    /**
     * Update breadcrumbs DOM based on filters
     */
    function updateBreadcrumbs(filters) {
        // return;
        filters = filters || {};
        const $crumb = $('.gfam-breadcrumb nav');
        if (!$crumb.length) return;

        const baseURL = rsl_ajax_obj.home_url; // Define in your theme or localize_script
        const stockURL = rsl_ajax_obj.stock_page_url; // Pass from PHP using wp_localize_script
        const stockTitle = rsl_ajax_obj.stock_page_title;

        // --- Step 1: Start with static crumbs ---
        let items = [
            { label: '<svg class="mb-1" width="19" height="22" viewBox="0 0 19 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17.4865 22H13.5926C12.7593 22 12.0815 21.164 12.0815 20.1359V12.9631C12.0815 12.7504 11.9403 12.5761 11.7678 12.5761H7.22977C7.05735 12.5761 6.91606 12.7504 6.91606 12.9631V20.1359C6.91606 21.164 6.23834 22 5.40497 22H1.51109C0.677719 22 0 21.164 0 20.1359V10.4402C0 9.46529 0.344847 8.54357 0.948325 7.91433L7.76141 0.788774C8.7672 -0.262925 10.228 -0.262925 11.2338 0.788774L18.0517 7.91728C18.6552 8.54653 19 9.46824 19 10.4431V20.1359C19 21.164 18.3223 22 17.4889 22H17.4865ZM7.22738 11.099H11.7654C12.5988 11.099 13.2765 11.935 13.2765 12.9631V20.1359C13.2765 20.3486 13.4178 20.5229 13.5902 20.5229H17.4841C17.6565 20.5229 17.7978 20.3486 17.7978 20.1359V10.4431C17.7978 9.90251 17.6063 9.39143 17.271 9.04284L10.4531 1.91433C9.89514 1.33235 9.08571 1.33235 8.52773 1.91433L1.71465 9.03988C1.37938 9.38848 1.19019 9.89956 1.19019 10.4402V20.1359C1.19019 20.3486 1.33148 20.5229 1.50391 20.5229H5.39778C5.57021 20.5229 5.7115 20.3486 5.7115 201359V12.9631C5.7115 11.935 6.38921 11.099 7.22259 11.099H7.22738Z" fill="#313131"></path></svg>', url: baseURL },
            { label: stockTitle, url: stockURL },            
            { label: 'Farm Machinery For Sale', url: '#' },            
        ];

        // --- Step 2: Build dynamic breadcrumb ---
        let labelParts = [];

        // Type (like Tractors, Harvesters)
        // if (Array.isArray(filters.type) && filters.type.length) {
        //     const typeLabel = filters.type.join(', ');
        //     labelParts.push(typeLabel);
        //     items.push({
        //         label: `${typeLabel} For Sale`,
        //         url: buildSEOUrl({ type: filters.type }, 1)
        //     });
        // }

        // Make (like John Deere, Mahindra)
        // if (Array.isArray(filters.make) && filters.make.length) {
        //     const makeLabel = filters.make.join(', ');
        //     labelParts.push(makeLabel);
        //     items.push({
        //         label: makeLabel,
        //         url: buildSEOUrl({ type: filters.type, make: filters.make }, 1)
        //     });
        // }

        // Model (like 5050E, Swaraj 744)
        // if (Array.isArray(filters.model) && filters.model.length) {
        //     const modelLabel = filters.model.join(', ');
        //     labelParts.push(modelLabel);
        //     items.push({
        //         label: modelLabel,
        //         url: buildSEOUrl({ 
        //             type: filters.type, 
        //             make: filters.make, 
        //             model: filters.model 
        //         }, 1)
        //     });
        // }

        // Category (New / Used / Certified)
        if (Array.isArray(filters.categories) && filters.categories.length) {

            let catLabel = '';

            if (filters.categories.length === 1) {
                // Only one category
                catLabel = filters.categories[0];
            } else if (filters.categories.length === 2) {
                // Two categories → join with comma
                catLabel = filters.categories.join(', ');
            } else {
                // More than two → show first two and add "& more"
                const shown = filters.categories.slice(0, 2).join(', ');
                catLabel = shown + ' & more';
            }

            items[2] = {
                label: catLabel+' For Sale',
                url: buildSEOUrl({
                    // type: filters.type,
                    // make: filters.make,
                    // model: filters.model,
                    categories: filters.categories
                }, 1)
            }

            // items.push({
            //     label: catLabel,
            //     url: buildSEOUrl({
            //         // type: filters.type,
            //         // make: filters.make,
            //         // model: filters.model,
            //         categories: filters.categories
            //     }, 1)
            // });
        }

        // Price
        // if (filters.price_from || filters.price_to) {
        //     const priceLabel = `$${filters.price_from || 'Any'} - $${filters.price_to || 'Any'}`;
        //     labelParts.push(priceLabel);
        //     items.push({
        //         label: `Price: ${priceLabel}`,
        //         url: buildSEOUrl(filters, 1)
        //     });
        // }

        // // Keyword Search
        // if (filters.keyword) {
        //     const keywordLabel = `Search: ${filters.keyword}`;
        //     labelParts.push(keywordLabel);
        //     items.push({
        //         label: keywordLabel,
        //         url: buildSEOUrl(filters, 1)
        //     });
        // }

        // --- Step 3: Build HTML ---
        const html = items.map((it, idx) => {            
            if (idx === items.length - 1) {
                return `<span class="active">${it.label}</span>`;
            }
            return `<a href="${it.url}" class="rsl-crumb-link" data-crumb-index="${idx}">${it.label}</a>`;
        }).join('<span> > </span>');

        // --- Step 4: Render ---
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
        const siteTitle = rsl_ajax_obj.site_title || '';

        let parts = [];

        // Type (e.g., Tractors, Harvesters)
        // if (Array.isArray(filters.type) && filters.type.length) {
        //     parts.push(filters.type.join(' '));
        // }

        // Make (e.g., John Deere)
        // if (Array.isArray(filters.make) && filters.make.length) {
        //     parts.push(filters.make.join(' '));
        // }

        // Model (e.g., 5050E)
        // if (Array.isArray(filters.model) && filters.model.length) {
        //     parts.push(filters.model.join(' '));
        // }

        // Categories (e.g., Used, New)
        if (Array.isArray(filters.categories) && filters.categories.length) {
            let catLabel = '';
            if (filters.categories.length === 1) {
                catLabel = filters.categories[0];
            } else if (filters.categories.length === 2) {
                catLabel = filters.categories.join(', ');
            } else {
                catLabel = filters.categories.slice(0, 2).join(', ') + ' & more';
            }
            parts.push(catLabel);
        }

        // Price filter
        // if (filters.price_to) {
        //     parts.push('Under $' + filters.price_to);
        // }

        // Keyword search
        // if (filters.keyword) {
        //     parts.push(filters.keyword);
        // }

        // --- Base logic ---
        const baseLabel = 'For Sale';
        const joined = parts.length ? parts.join(' ') : 'Farm Machinery';

        // Final readable title
        const fullTitle = `${joined} ${baseLabel} — ${siteTitle}`;

        // --- Update DOM ---
        $('h1').text(`${joined} ${baseLabel}`);
        document.title = fullTitle;
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
                    reinitSeeMoreLess();
                    setTimeout(function() {
                        reinitSeeMoreLess();
                    }, 2000);
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

        filters.price_from = '';
        filters.price_to = '';

        if(filter_type == 'above' && filter_price != undefined){
            filters.price_from = filter_price;
        }else{
            filters.price_to = filter_price;
        }
        filters.filter_type = filter_type;
        filters.filter_price = filter_price;

        show_selected_val_on_sidebar(filters);
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
        $('.selected-category-options-list, .selected-make-options-list, .selected-type-options-list, .selected-price-range-options-list, .selected-year-range-options-list, .selected-hours-range-options-list').empty();

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

        /** -----------------------------
         *   PRICE RANGE TAG
         * ----------------------------- */
        if ((filters.price_from || filters.price_to)) {
            let label = '';
            if (filters.price_from && filters.price_to) {
                label = '$' + filters.price_from + ' - $' + filters.price_to;
            } else if (filters.price_from) {                
                label = 'Above $' + filters.price_from;
            } else if (filters.price_to) {
                label = 'Under $' + filters.price_to;
            }
            addTag('.selected-price-range-options-list', label, 'price_range', label);
        }

        /** -----------------------------
         *   YEAR RANGE TAG
         * ----------------------------- */
        if (filters.year_from || filters.year_to) {
            let label = '';
            if (filters.year_from && filters.year_to) {
                label = filters.year_from + ' - ' + filters.year_to;
            } else if (filters.year_from) {
                label = 'From ' + filters.year_from;
            } else if (filters.year_to) {
                label = 'Up to ' + filters.year_to;
            }
            addTag('.selected-year-range-options-list', label, 'year_range', label);
        }

        /** -----------------------------
         *   HOURS RANGE TAG
         * ----------------------------- */
        if (filters.hours_from || filters.hours_to) {
            let label = '';
            if (filters.hours_from && filters.hours_to) {
                label = filters.hours_from + ' - ' + filters.hours_to + ' Hours';
            } else if (filters.hours_from) {
                label = 'Above ' + filters.hours_from + ' Hours';
            } else if (filters.hours_to) {
                label = 'Under ' + filters.hours_to + ' Hours';
            }
            addTag('.selected-hours-range-options-list', label, 'hours_range', label);
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

        /** -----------------------------
         *  PRICE RANGE CLEAR
         * ----------------------------- */
        if (key === 'price_range') {            
            $('select[name="price_from"]').val('').trigger('change');
            $('select[name="price_to"]').val('').trigger('change');
            $('input[name="priceFromInput"]').val('');
            $('input[name="priceToInput"]').val('');
            $('.block-price-filter').removeClass('active');
        }

        /** -----------------------------
         *  YEAR RANGE CLEAR
         * ----------------------------- */
        if (key === 'year_range') {
            $('select[name="year-from"]').val('').trigger('change');
            $('select[name="year-to"]').val('').trigger('change');
            $('input[name="yearFromInput"]').val('');
            $('input[name="yearToInput"]').val('');
        }

        /** -----------------------------
         *  HOURS RANGE CLEAR
         * ----------------------------- */
        if (key === 'hours_range') {
            $('select[name="hour-from"]').val('').trigger('change');
            $('select[name="hour-to"]').val('').trigger('change');
            $('input[name="hourFromInput"]').val('');
            $('input[name="hourToInput"]').val('');
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