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

    /**
     * Get all selected filters
     */
    function get_selected_filters(){        
        let filters = {
            categories: $('input[name="category[]"]:checked').map(function(){ return $(this).val(); }).get(),
            makeModel: $('input[name="make-model[]"]:checked').map(function(){ return $(this).val(); }).get(),
            type: $('input[name="type[]"]:checked').map(function(){ return $(this).val(); }).get(),
            price_from: $('.rsl-price-tabs.active .rsl-price-from').val() || '',
            price_to: $('.rsl-price-tabs.active .rsl-price-to').val() || '',
            year_from: $('.rsl-year-tabs.active .rsl-year-from').val() || '',
            year_to: $('.rsl-year-tabs.active .rsl-year-to').val() || '',
            hours_from: $('.rsl-hours-tabs.active .rsl-hours-from').val() || '',
            hours_to: $('.rsl-hours-tabs.active .rsl-hours-to').val() || '',
            sort: $('.stock-sorting-cls.active').data('val') || '',
            keyword: $('.main-listing-search').val().trim() || ''
        };
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
