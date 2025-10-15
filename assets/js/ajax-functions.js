jQuery(document).ready(function($) {

    /**
     * Generic function to fetch listings via AJAX
     */
    function rsl_fetch_listings(params) {        
        let {
            page = 1,
            per_page = 10,
            append = false,
            $btn = null,
        } = params;

        if ($btn) {
            $btn.text('Loading...').prop('disabled', true);
        }

        $.ajax({
            url: rsl_ajax_obj.ajax_url,
            type: 'POST',
            dataType: 'json',
            beforeSend: function( xhr ) {
                $('#loader').fadeIn(500);
            },
            data: {
                action: 'rsl_get_stock_list',
                security: rsl_ajax_obj.nonce,
                page: page,
                per_page: per_page,
                filters: params.filters || {}
            },
            success: function(response) {
                if (response.success && response.data.html) {
                    if (append) {
                        $('.gfam-product-grid').append(response.data.html);
                    } else {
                        $('.gfam-product-grid').html(response.data.html);
                    }
                    reinitCarousel();

                    if ($btn) {
                        $btn.text('Load More...').prop('disabled', false);
                        $btn.attr('next-page', response.data.next_page);                        

                        if (page >= response.data.max_pages) {
                            $btn.addClass('d-none');
                        }else{
                            $btn.removeClass('d-none');
                        }
                    }else{                    
                        let $loadmore_button = $('.load-more-stocks-btn');
                        if ($loadmore_button.length) {
                            if (response.data.has_more === false) {
                                $loadmore_button.addClass('d-none');
                            }else{
                                $loadmore_button.removeClass('d-none');
                            }
                            $loadmore_button.attr({
                                'next-page': response.data.next_page,
                                'max-pages': response.data.max_pages
                            });
                        } else {
                            console.warn('No load more button found');
                        }
                    }
                } else {
                    if ($btn) $btn.addClass('d-none');
                }
                $('#loader').fadeOut(500);
            },
            error: function(xhr, status, error) {
                $('#loader').fadeOut(500);
                if ($btn) $btn.text('Error').prop('disabled', true);
            }
        });
    }

    /**
     * Load More Click
     */
    $('.load-more-stocks-btn').on('click', function(e) {
        e.preventDefault();

        let $btn = $(this);
        let currentPage = parseInt($btn.attr('next-page'));
        let maxPages = parseInt($btn.attr('max-pages'));

        if (currentPage > maxPages) {
            $btn.addClass('d-none');
            return;
        }
        
        var filters = get_selected_filters();

        rsl_fetch_listings({
            page: currentPage,
            per_page: rsl_ajax_obj.vdp_per_page,
            append: true,
            $btn: $btn,
            max_pages: maxPages,
            filters: filters
        });
    });

    function get_selected_filters(){
        // Collect all checked checkboxes with name="category[]"
        var selectedCategories = $('input[name="category[]"]:checked').map(function() {
            return $(this).val();
        }).get(); // convert to array

        // Collect all checked checkboxes with name="category[]"
        var selectedMakeModel = $('input[name="make-model[]"]:checked').map(function() {
            return $(this).val();
        }).get(); // convert to array

        // Collect all checked checkboxes with name="category[]"
        var selectedType = $('input[name="type[]"]:checked').map(function() {
            return $(this).val();
        }).get(); // convert to array


        // Collect selected price range (dropdowns)
        var activePriceTabID = $('.rsl-price-tabs.active').data('bs-target');
        var selectedPriceFrom = $(activePriceTabID + ' .rsl-price-from').val();
        var selectedPriceTo   = $(activePriceTabID + ' .rsl-price-to').val();

        // Collect selected price range (dropdowns)
        var activeYearTabID = $('.rsl-year-tabs.active').data('bs-target');
        var selectedYearFrom = $(activeYearTabID + ' .rsl-year-from').val();
        var selectedYearTo   = $(activeYearTabID + ' .rsl-year-to').val();

        // Collect selected hour range (dropdowns)
        var activeHoursTabID = $('.rsl-hours-tabs.active').data('bs-target');
        var selectedHoursFrom = $(activeHoursTabID + ' .rsl-hours-from').val();
        var selectedHoursTo   = $(activeHoursTabID + ' .rsl-hours-to').val();

        // Example filter collection
        let filters = {
            categories: selectedCategories,        
            makeModel: selectedMakeModel,
            type: selectedType,
            price_from: selectedPriceFrom || '', // empty if "Any" selected
            price_to: selectedPriceTo   || '',
            year_from: selectedYearFrom || '', // empty if "Any" selected
            year_to: selectedYearTo   || '',
            hours_from: selectedHoursFrom || '', // empty if "Any" selected
            hours_to: selectedHoursTo   || '',                
        };

        return filters
    }

    /**
     * Filter Apply Click
     */
    $('.rsl-apply-filter').on('click', function(e) {
        e.preventDefault();
        
        // remove focus temporarily
        $(this).blur();
        var filters = get_selected_filters();        
        show_selected_val_on_sidebar(filters);

        rsl_fetch_listings({
            page: 1,
            per_page: rsl_ajax_obj.vdp_per_page,
            append: false,
            filters: filters
        });
    });

    function show_selected_val_on_sidebar(filters) {        
        // clear each list
        $('.selected-category-options-list, .selected-make-options-list, .selected-type-options-list, .selected-price-options-list, .selected-year-options-list, .selected-hours-options-list').empty();

        // small helper to append tag in a specific container
        function addTag(containerSelector, label, key, value) {
            const tag = $(`
                <span class="gfam-filter-tag" data-key="${key}" data-value="${value}">
                    ${label} <span class="gfam-clear-tag">×</span>
                </span>
            `);
            $(containerSelector).append(tag);
        }

        // --- Category ---
        if (filters.categories.length) {
            filters.categories.forEach(val => addTag('.selected-category-options-list', val, 'category', val));
        }

        // --- Make / Model ---
        if (filters.makeModel.length) {
            filters.makeModel.forEach(val => addTag('.selected-make-options-list', val, 'make-model', val));
        }

        // --- Type ---
        if (filters.type.length) {
            filters.type.forEach(val => addTag('.selected-type-options-list', val, 'type', val));
        }

        // --- Price ---
        if (filters.price_from || filters.price_to) {
            let label = `${filters.price_from || 'Any'} - ${filters.price_to || 'Any'}`;
            addTag('.selected-price-options-list', 'Price: ' + label, 'price', label);
        }

        // --- Year ---
        if (filters.year_from || filters.year_to) {
            let label = `${filters.year_from || 'Any'} - ${filters.year_to || 'Any'}`;
            addTag('.selected-year-options-list', 'Year: ' + label, 'year', label);
        }

        // --- Hours ---
        if (filters.hours_from || filters.hours_to) {
            let label = `${filters.hours_from || 'Any'} - ${filters.hours_to || 'Any'}`;
            addTag('.selected-hours-options-list', 'Hours: ' + label, 'hours', label);
        }
        
        reinitSeeMoreLess();        
    }

    $(document).on('click', '.gfam-clear-tag', function() {
        const tag = $(this).closest('.gfam-filter-tag');
        const key = tag.data('key');
        const value = tag.data('value');

        // Uncheck or reset corresponding filter
        if (key === 'category') {
            $(`input[name="category[]"][value="${value}"]`).prop('checked', false);
        } else if (key === 'make-model') {
            $(`input[name="make-model[]"][value="${value}"]`).prop('checked', false);
        } else if (key === 'type') {
            $(`input[name="type[]"][value="${value}"]`).prop('checked', false);
        } else if (key === 'price') {
            $('.rsl-price-from, .rsl-price-to').val('');
        } else if (key === 'year') {
            $('.rsl-year-from, .rsl-year-to').val('');
        } else if (key === 'hours') {
            $('.rsl-hours-from, .rsl-hours-to').val('');
        }

        // Remove tag
        tag.remove();

        // Re-fetch updated listings
        const filters = get_selected_filters();
        rsl_fetch_listings({
            page: 1,
            per_page: rsl_ajax_obj.vdp_per_page,
            append: false,
            filters: filters
        });
    });
});