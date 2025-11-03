jQuery(document).ready(function($) {

    /**
     * =======================
     * CATEGORY MODAL VALIDATION
     * =======================
     */
    function validateCategoryModal() {
        const $modal = $('#popupCategoryDesktop, #gfampopupCategoryMobile');
        const $searchBtn = $modal.find('.rsl-apply-filter');
        const $clearButton = $modal.find('.clear-btn');
        const $checkboxes = $modal.find('input[name="category[]"]');

        $searchBtn.prop('disabled', true).addClass('disabled');
        $clearButton.prop('disabled', true).addClass('isDisabled');

        $checkboxes.on('change', function() {
            const hasChecked = $modal.find('input[name="category[]"]:checked').length > 0;
            $searchBtn.prop('disabled', !hasChecked).toggleClass('disabled', !hasChecked);
            $clearButton.prop('disabled', !hasChecked).toggleClass('isDisabled', !hasChecked);
        });

        $modal.on('shown.bs.modal', function() {
            const hasChecked = $modal.find('input[name="category[]"]:checked').length > 0;
            $searchBtn.prop('disabled', !hasChecked).toggleClass('disabled', !hasChecked);
            $clearButton.prop('disabled', !hasChecked).toggleClass('isDisabled', !hasChecked);
        });
    }

    /**
     * =======================
     * MAKE/MODEL MODAL VALIDATION
     * =======================
     */
    function validateMakeModelModal() {
        const $modal = $('#popupMakeDesktop, #gfampopupMakeMobile');
        const $searchBtn = $modal.find('.rsl-apply-filter');
        const $clearButton = $modal.find('.clear-btn');
        const $checkboxes = $modal.find('input[name="make-model[]"]');

        $searchBtn.prop('disabled', true).addClass('disabled');
        $clearButton.prop('disabled', true).addClass('isDisabled');

        $checkboxes.on('change', function() {
            const hasChecked = $modal.find('input[name="make-model[]"]:checked').length > 0;
            $searchBtn.prop('disabled', !hasChecked).toggleClass('disabled', !hasChecked);
            $clearButton.prop('disabled', !hasChecked).toggleClass('isDisabled', !hasChecked);
        });

        $modal.on('shown.bs.modal', function() {
            const hasChecked = $modal.find('input[name="make-model[]"]:checked').length > 0;
            $searchBtn.prop('disabled', !hasChecked).toggleClass('disabled', !hasChecked);
            $clearButton.prop('disabled', !hasChecked).toggleClass('isDisabled', !hasChecked);
        });
    }

    /**
     * =======================
     * TYPE MODAL VALIDATION
     * =======================
     */
    function validateTypeModal() {
        const $modal = $('#popupTypeDesktop, #gfampopupTypeMobile');
        const $searchBtn = $modal.find('.rsl-apply-filter');
        const $clearButton = $modal.find('.clear-btn');
        const $checkboxes = $modal.find('input[name="type[]"]');

        $searchBtn.prop('disabled', true).addClass('disabled');
        $clearButton.prop('disabled', true).addClass('isDisabled');

        $checkboxes.on('change', function() {
            const hasChecked = $modal.find('input[name="type[]"]:checked').length > 0;
            $searchBtn.prop('disabled', !hasChecked).toggleClass('disabled', !hasChecked);
            $clearButton.prop('disabled', !hasChecked).toggleClass('isDisabled', !hasChecked);
        });

        $modal.on('shown.bs.modal', function() {
            const hasChecked = $modal.find('input[name="type[]"]:checked').length > 0;
            $searchBtn.prop('disabled', !hasChecked).toggleClass('disabled', !hasChecked);
            $clearButton.prop('disabled', !hasChecked).toggleClass('isDisabled', !hasChecked);
        });
    }

    /**
     * =======================
     * PRICE RANGE VALIDATION
     * (full rules/messages preserved)
     * =======================
     */
    function validatePriceModal() {
        const $modal = $('#popupRangeDesktop, #gfampopupRangeMobile');
        const $form = $modal.find('#priceRangeForm');
        const $clearButton = $modal.find('.clear-btn');

        // Initialize validation (preserving From < To logic)
        $form.validate({
            rules: {
                'price_from': {
                    number: true,
                    min: function() {
                        return parseFloat($('#priceFromSelect option:not([value=""])').first().val()) || 0;
                    }
                },
                'price_to': {
                    number: true,
                    min: function() {
                        const fromVal = parseFloat($('#priceFromSelect').val());
                        return fromVal || parseFloat($('#priceToSelect option:not([value=""])').first().val()) || 0;
                    }
                },
                'priceFromInput': {
                    number: true,
                    min: function() {
                        return parseFloat($('#priceFromInput').attr('min')) || 0;
                    },
                    max: function() {
                        return parseFloat($('#priceFromInput').attr('max')) || 9999999;
                    }
                },
                'priceToInput': {
                    number: true,
                    min: function() {
                        const fromVal = parseFloat($('#priceFromInput').val());
                        return fromVal || parseFloat($('#priceToInput').attr('min')) || 0;
                    },
                    max: function() {
                        return parseFloat($('#priceToInput').attr('max')) || 9999999;
                    }
                }
            },
            messages: {
                'price-from': {
                    number: 'Please enter a valid price.',
                    min: function() {
                        const min = $('#priceFromSelect').attr('min') || 0;
                        return `The lowest priced vehicle we have starts around $${parseInt(min).toLocaleString()}.`;
                    },
                    max: function() {
                        const max = $('#priceFromSelect').attr('max') || 999999999;
                        return `Please choose a starting price below $${parseInt(max).toLocaleString()}.`;
                    }
                },
                'price-to': {
                    number: 'Please enter a valid price.',
                    min: function() {
                        const fromVal = $('.rsl-price-tabs.active').find('.rsl-price-from').val();
                        return fromVal
                            ? `Please enter more than $${parseFloat(fromVal).toLocaleString()}.`
                            : 'Please enter a starting price first.';
                    },
                    max: function() {
                        const max = $('#priceToSelect').attr('max') || 999999999;
                        return `The highest priced vehicle we have is around $${parseInt(max).toLocaleString()}.`;
                    }
                }
            },
            errorElement: 'span',
            errorClass: 'text-danger small d-block mt-1',
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            }
        });

        function validatePriceRange() {
            const activeTab = $modal.find('.rsl-price-tabs.active').data('bs-target');
            const $activePane = $modal.find(activeTab);
            const fromVal = $activePane.find('.rsl-price-from').val();
            const toVal = $activePane.find('.rsl-price-to').val();
            const $searchBtn = $modal.find('.rsl-apply-filter');

            const hasValue = (fromVal && fromVal !== '') && (toVal && toVal !== '');
            const isValid = $form.valid();

            $searchBtn.prop('disabled', !hasValue || !isValid).toggleClass('disabled', !hasValue || !isValid);
            $clearButton.prop('disabled', !hasValue).toggleClass('isDisabled', !hasValue);
        }

        $modal.find('.rsl-price-from, .rsl-price-to').on('input change', validatePriceRange);
        $modal.find('.rsl-price-tabs').on('shown.bs.tab', validatePriceRange);
        $modal.on('shown.bs.modal', validatePriceRange);

        $form.on('submit', function(e) {
            e.preventDefault();
            if ($form.valid()) validatePriceRange();
        });
    }

    /**
     * =======================
     * YEAR RANGE VALIDATION
     * (full rules/messages preserved)
     * =======================
     */
    function validateYearModal() {
        const $modal = $('#popupYearDesktop, #gfampopupYearMobile');
        const $form = $modal.find('#yearRangeForm');
        const $clearButton = $modal.find('.clear-btn');

        $form.validate({
            rules: {
                'year-from': {
                    number: true,
                    digits: true,
                    min: function() {
                        return parseInt($('#yearFromSelect').attr('min')) || 1900;
                    },
                    max: function() {
                        return parseInt($('#yearFromSelect').attr('max')) || new Date().getFullYear() + 1;
                    }
                },
                'year-to': {
                    number: true,
                    digits: true,
                    min: function() {
                        const fromVal = parseInt($('#yearFromSelect').val());
                        return fromVal || parseInt($('#yearToSelect').attr('min')) || 1900;
                    },
                    max: function() {
                        return parseInt($('#yearToSelect').attr('max')) || new Date().getFullYear() + 1;
                    }
                },
                'yearFromInput': {
                    number: true,
                    digits: true,
                    min: function() {
                        return parseInt($('#yearFromInput').attr('min')) || 1900;
                    },
                    max: function() {
                        return parseInt($('#yearFromInput').attr('max')) || new Date().getFullYear() + 1;
                    }
                },
                'yearToInput': {
                    number: true,
                    digits: true,
                    min: function() {
                        const fromVal = parseInt($('#yearFromInput').val());
                        return fromVal || parseInt($('#yearToInput').attr('min')) || 1900;
                    },
                    max: function() {
                        return parseInt($('#yearToInput').attr('max')) || new Date().getFullYear() + 1;
                    }
                }
            },
            messages: {
                'year-from': {
                    number: 'Please enter a valid year.',
                    digits: 'Years should contain numbers only.',
                    min: function() {
                        const min = $('#yearFromSelect').attr('min') || 1990;
                        return `The oldest model available is from ${min}.`;
                    },
                    max: function() {
                        const max = $('#yearFromSelect').attr('max') || new Date().getFullYear();
                        return `Please choose a year before ${max}.`;
                    }
                },
                'year-to': {
                    number: 'Please enter a valid year.',
                    digits: 'Years should contain numbers only.',
                    min: function() {
                        const fromVal = $('#yearFromSelect').val();
                        return fromVal
                            ? `Please enter a year after ${fromVal}.`
                            : 'Please choose a starting year first.';
                    },
                    max: function() {
                        const max = $('#yearToSelect').attr('max') || new Date().getFullYear();
                        return `The most recent model we have is from ${max}.`;
                    }
                }
            },
            errorElement: 'span',
            errorClass: 'text-danger small d-block mt-1',
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            }
        });

        function validateYearRange() {
            const activeTab = $modal.find('.rsl-year-tabs.active').data('bs-target');
            const $activePane = $modal.find(activeTab);
            const fromVal = $activePane.find('.rsl-year-from').val();
            const toVal = $activePane.find('.rsl-year-to').val();
            const $searchBtn = $modal.find('.rsl-apply-filter');

            const hasValue = (fromVal && fromVal !== '') && (toVal && toVal !== '');
            const isValid = $form.valid();

            $searchBtn.prop('disabled', !hasValue || !isValid).toggleClass('disabled', !hasValue || !isValid);
            $clearButton.prop('disabled', !hasValue).toggleClass('isDisabled', !hasValue);
        }

        $modal.find('.rsl-year-from, .rsl-year-to').on('input change', validateYearRange);
        $modal.find('.rsl-year-tabs').on('shown.bs.tab', validateYearRange);
        $modal.on('shown.bs.modal', validateYearRange);

        $form.on('submit', function(e) {
            e.preventDefault();
            if ($form.valid()) validateYearRange();
        });
    }

    /**
     * =======================
     * HOURS RANGE VALIDATION
     * (full rules/messages preserved)
     * =======================
     */
    function validateHoursModal() {
        const $modal = $('#popupHourDesktop, #gfampopupHourMobile');
        const $form = $modal.find('#hourRangeForm');
        const $clearButton = $modal.find('.clear-btn');

        $form.validate({
            rules: {
                'hour-from': {
                    number: true,
                    min: function() {
                        return parseFloat($('#hourFromSelect').attr('min')) || 0;
                    },
                    max: function() {
                        return parseFloat($('#hourFromSelect').attr('max')) || 999999;
                    }
                },
                'hour-to': {
                    number: true,
                    min: function() {
                        const fromVal = parseFloat($('#hourFromSelect').val());
                        return fromVal || parseFloat($('#hourToSelect').attr('min')) || 0;
                    },
                    max: function() {
                        return parseFloat($('#hourToSelect').attr('max')) || 999999;
                    }
                },
                'hourFromInput': {
                    number: true,
                    min: function() {
                        return parseFloat($('#hourFromInput').attr('min')) || 0;
                    },
                    max: function() {
                        return parseFloat($('#hourFromInput').attr('max')) || 999999;
                    }
                },
                'hourToInput': {
                    number: true,
                    min: function() {
                        const fromVal = parseFloat($('#hourFromInput').val());
                        return fromVal || parseFloat($('#hourToInput').attr('min')) || 0;
                    },
                    max: function() {
                        return parseFloat($('#hourToInput').attr('max')) || 999999;
                    }
                }
            },
            messages: {
                'hour-from': {
                    number: 'Please enter valid engine hours.',
                    min: function() {
                        const min = $('#hourFromSelect').attr('min') || 0;
                        return `The lowest recorded machine hours start from about ${min}.`;
                    },
                    max: function() {
                        const max = $('#hourFromSelect').attr('max') || 999999;
                        return `Please enter a starting value below ${max} hours.`;
                    }
                },
                'hour-to': {
                    number: 'Please enter valid engine hours.',
                    min: function() {
                        const fromVal = $('#hourFromSelect').val();
                        console.log(fromVal)
                        return fromVal
                            ? `Please enter more than ${fromVal} hours.`
                            : 'Please enter a starting value first.';
                    },
                    max: function() {
                        const max = $('#hourToSelect').attr('max') || 999999;
                        return `The highest recorded engine hours are around ${max}.`;
                    }
                }
            },
            errorElement: 'span',
            errorClass: 'text-danger small d-block mt-1',
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            }
        });

        function validateHoursRange() {
            const activeTab = $modal.find('.rsl-hours-tabs.active').data('bs-target');
            const $activePane = $modal.find(activeTab);
            const fromVal = $activePane.find('.rsl-hours-from').val();
            const toVal = $activePane.find('.rsl-hours-to').val();
            const $searchBtn = $modal.find('.rsl-apply-filter');

            const hasValue = (fromVal && fromVal !== '') && (toVal && toVal !== '');
            const isValid = $form.valid();

            $searchBtn.prop('disabled', !hasValue || !isValid).toggleClass('disabled', !hasValue || !isValid);
            $clearButton.prop('disabled', !hasValue).toggleClass('isDisabled', !hasValue);
        }

        $modal.find('.rsl-hours-from, .rsl-hours-to').on('input change', validateHoursRange);
        $modal.find('.rsl-hours-tabs').on('shown.bs.tab', validateHoursRange);
        $modal.on('shown.bs.modal', validateHoursRange);

        $form.on('submit', function(e) {
            e.preventDefault();
            if ($form.valid()) validateHoursRange();
        });
    }

    /**
     * =======================
     * CLEAR BUTTON HANDLING (unified)
     * - resets inputs/checkboxes/selects
     * - resets validation messages
     * - disables search + clear again
     * =======================
     */
    $(document).on('click', '.clear-btn', function() {
        const $modal = $(this).closest('.modal');
        const $searchBtn = $modal.find('.rsl-apply-filter');
        const $form = $modal.find('form');
        const $clearBtn = $modal.find('.clear-btn');

        // Reset checkboxes, text inputs, selects, number inputs
        $modal.find('input[type="checkbox"]').prop('checked', false);
        $modal.find('input[type="text"], input[type="number"], input[type="search"], input[type="tel"], textarea').val('');
        $modal.find('select').val('');
        // If there are special radio/select markup, you may need to trigger update events:
        $modal.find('input, select, textarea').trigger('change');

        // Reset form validation (if any)
        if ($form.length && $form.data('validator')) {
            $form.validate().resetForm();
        }

        // Small delay to let change handlers run, then disable buttons
        setTimeout(function() {
            $searchBtn.prop('disabled', true).addClass('disabled');
            $clearBtn.prop('disabled', true).addClass('isDisabled');
        }, 50);
    });

    /**
     * =======================
     * INITIALIZE ALL VALIDATIONS
     * =======================
     */
    validateCategoryModal();
    validateMakeModelModal();
    validateTypeModal();
    validatePriceModal();
    validateYearModal();
    validateHoursModal();

    /**
     * =======================
     * STYLES: visual feedback for disabled buttons
     * =======================
     */
    const style = document.createElement('style');
    style.textContent = `
        .rsl-apply-filter.disabled,
        .rsl-apply-filter:disabled,
        .clear-btn.isDisabled,
        .clear-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }
        .text-danger.small {
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    `;
    document.head.appendChild(style);

});
