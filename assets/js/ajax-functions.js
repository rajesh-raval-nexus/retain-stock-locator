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

                    if ($btn) {
                        $btn.text('Load More...').prop('disabled', false);
                        $btn.attr('next-page', response.data.next_page);                        

                        if (page >= response.data.max_pages) {
                            $btn.addClass('d-none');
                        }
                    }else{                    
                        let $loadmore_button = $('.load-more-stocks-btn');
                        if ($loadmore_button.length) {
                            if (response.data.has_more === false) {
                                $loadmore_button.addClass('d-none');
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
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
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
            Type: selectedType,
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
        
        show_selected_val_on_sidebar(this);
        var filters = get_selected_filters();        

        rsl_fetch_listings({
            page: 1,
            per_page: rsl_ajax_obj.vdp_per_page,
            append: false,
            filters: filters
        });
    });

    function show_selected_val_on_sidebar(search_btn){        
        var clicked_search_filter_id = jQuery(search_btn).data('search');

        if(clicked_search_filter_id == 'category-filter-search'){
            
        }
    }
});
