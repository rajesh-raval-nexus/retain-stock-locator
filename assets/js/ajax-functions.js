jQuery(document).ready(function($) {

    /**
     * Generic function to fetch listings via AJAX
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
                    $('.gfam-product-grid').after(response.data.pagination);

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
     * Pagination click
     */
    $(document).on('click', '.ajax-pagination .page-number, .ajax-pagination .prev-page, .ajax-pagination .next-page', function() {
        if ($(this).hasClass('disabled') || $(this).hasClass('current')) return;

        let page = $(this).data('page');
        let filters = get_selected_filters();

        rsl_fetch_listings({
            page: page,
            per_page: rsl_ajax_obj.vdp_per_page,
            filters: filters
        });

        // Scroll to top of grid (optional)
        $('html, body').animate({ scrollTop: $('.gfam-product-grid').offset().top - 100 }, 300);
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

        rsl_fetch_listings({
            page: 1,
            per_page: rsl_ajax_obj.vdp_per_page,
            filters: filters
        });
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

    $(document).on('click', '.block-price-filter', function() {
        var filter_type = $(this).data('filter-type');
        var filter_price = $(this).data('filter-price');        

        let filters = get_selected_filters();              
        show_selected_val_on_sidebar(filters);

        if(filter_type == 'above' && filter_price != undefined){
            filters.price_from = filter_price;
        }else{
            filters.price_to = filter_price;
        }
        filters.filter_type = filter_type;
        filters.filter_price = filter_price;

        rsl_fetch_listings({
            page: 1,
            per_page: rsl_ajax_obj.vdp_per_page,
            filters: filters
        });
    });

    /**
     * Sorting click
     */
    $(".clear-btn").on("click", function (e) {
        e.preventDefault();
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
        
        let filters = get_selected_filters();        
        show_selected_val_on_sidebar(filters);

        rsl_fetch_listings({
            page: 1,
            per_page: rsl_ajax_obj.vdp_per_page,
            filters: filters
        });
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
        }

        if ($('input[name="make-model[]"]').length) {
            filters.makeModel = $('input[name="make-model[]"]:checked').map(function() {
                return $(this).val();
            }).get();
        }

        if ($('input[name="type[]"]').length) {
            filters.type = $('input[name="type[]"]:checked').map(function() {
                return $(this).val();
            }).get();
        }

        if ($('.rsl-price-tabs').length) {
            var activePriceTabID = $('.rsl-price-tabs.active').data('bs-target');            
            filters.price_from = $(activePriceTabID + ' .rsl-price-from').val();
            filters.price_to   = $(activePriceTabID + ' .rsl-price-to').val();
        }

        if ($('.rsl-year-tabs').length) {
            var activeYearTabID = $('.rsl-year-tabs.active').data('bs-target');
            filters.year_from = $(activeYearTabID + ' .rsl-year-from').val();
            filters.year_to   = $(activeYearTabID + ' .rsl-year-to').val();
        }

        if ($('.rsl-hours-tabs').length) {
            var activeHoursTabID = $('.rsl-hours-tabs.active').data('bs-target');
            filters.hours_from = $(activeHoursTabID + ' .rsl-hours-from').val();
            filters.hours_to   = $(activeHoursTabID + ' .rsl-hours-to').val();
        }

        if ($('.stock-sorting-cls').length) {
            filters.sort = $('.stock-sorting-cls.active').data('val') || '';
        }

        if ($('.main-listing-search').length) {
            filters.keyword = $('.main-listing-search').val()?.trim() || '';
        }

        return filters;
    }

    /**
     * Search input (with debounce)
     */
    let rslSearchTimeout = null;
    $(document).on('input', '.main-listing-search', function () {
        const value = $(this).val();
        $('.main-listing-search').not(this).val(value);

        clearTimeout(rslSearchTimeout);
        rslSearchTimeout = setTimeout(function() {
            let filters = get_selected_filters();
            show_selected_val_on_sidebar(filters);
            rsl_fetch_listings({ page: 1, per_page: rsl_ajax_obj.vdp_per_page, filters: filters });
        }, 500);
    });

    $(document).on('keypress', '.main-listing-search', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            clearTimeout(rslSearchTimeout);
            let filters = get_selected_filters();
            show_selected_val_on_sidebar(filters);
            rsl_fetch_listings({ page: 1, per_page: rsl_ajax_obj.vdp_per_page, filters: filters });
        }
    });

    /**
     * Apply filter button
     */
    $('.rsl-apply-filter').on('click', function(e) {
        e.preventDefault();
        $(this).blur();

        let filters = get_selected_filters();        
        show_selected_val_on_sidebar(filters);

        rsl_fetch_listings({ page: 1, per_page: rsl_ajax_obj.vdp_per_page, filters: filters });
    });

    /**
     * Sidebar tag helper
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

        if (filters.categories.length) filters.categories.forEach(val => addTag('.selected-category-options-list', val, 'category', val));
        if (filters.makeModel.length) filters.makeModel.forEach(val => addTag('.selected-make-options-list', val, 'make-model', val));
        if (filters.type.length) filters.type.forEach(val => addTag('.selected-type-options-list', val, 'type', val));
        if (filters.price_from || filters.price_to) addTag('.selected-price-options-list', `Price: ${filters.price_from || 'Any'} - ${filters.price_to || 'Any'}`, 'price', `${filters.price_from}-${filters.price_to}`);
        if (filters.year_from || filters.year_to) addTag('.selected-year-options-list', `Year: ${filters.year_from || 'Any'} - ${filters.year_to || 'Any'}`, 'year', `${filters.year_from}-${filters.year_to}`);
        if (filters.hours_from || filters.hours_to) addTag('.selected-hours-options-list', `Hours: ${filters.hours_from || 'Any'} - ${filters.hours_to || 'Any'}`, 'hours', `${filters.hours_from}-${filters.hours_to}`);

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
        else if (key === 'price') $('.rsl-price-from, .rsl-price-to').val('');
        else if (key === 'year') $('.rsl-year-from, .rsl-year-to').val('');
        else if (key === 'hours') $('.rsl-hours-from, .rsl-hours-to').val('');

        tag.remove();

        let filters = get_selected_filters();
        rsl_fetch_listings({ page: 1, per_page: rsl_ajax_obj.vdp_per_page, filters: filters });
    });

});
