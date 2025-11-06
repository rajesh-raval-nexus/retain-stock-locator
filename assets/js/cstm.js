jQuery(document).ready(function($) {

  $('.btn-message-detail').on('click', function() {
     jQuery('button.lg-close.lg-icon').click();
   });

  // $('.custom-select .selected').click(function(){
  //   $(this).siblings('.options').slideToggle(200);
  // });

  // $('.custom-select .options li').click(function(){
  //   var value = $(this).data('value');
  //   var text = $(this).text();
  //   $(this).closest('.custom-select').find('.selected').text(text);
  //   $(this).parent().slideUp(200);
  // });

  // // Close dropdown if clicked outside
  // $(document).click(function(e){
  //   if(!$(e.target).closest('.custom-select').length){
  //     $('.custom-select .options').slideUp(200);
  //   }
  // });
  
  var $button = $('.ask-question-btn');

  // Initially disable the button
  $button.prop('disabled', true);

  $('.custom-select .selected').click(function(){
    $(this).siblings('.options').slideToggle(200);
  });

  $('.custom-select .options li').click(function(){
    var value = $(this).data('value');
    var text = $(this).text();

    $('#askQuestionModalLabel').text(text);
    $('.ask_question_fm_val').val(text);

    var $select = $(this).closest('.custom-select');
    $select.find('.selected').text(text).addClass('selected-d-block'); // add class on selection

    $(this).parent().slideUp(200);

    // Enable the button now
    $button.prop('disabled', false);
  });

  // Close dropdown if clicked outside
  $(document).click(function(e){
    if(!$(e.target).closest('.custom-select').length){
      $('.custom-select .options').slideUp(200);
    }
  });

  $("#gfam-form").validate({
    rules: {
      first_name: { required: true, minlength: 2 },
      last_name: { required: true, minlength: 2 },
      email: { required: true, email: true },
      phone: { required: true, digits: true, minlength: 10, maxlength: 15 },
      comments: { required: true, minlength: 5 },
      trade_in: { required: true }
    },
    messages: {
      first_name: {
        required: "Please enter your first name",
        minlength: "First name must be at least 2 characters"
      },
      last_name: {
        required: "Please enter your last name",
        minlength: "Last name must be at least 2 characters"
      },
      email: {
        required: "Please enter your email address",
        email: "Please enter a valid email address"
      },
      phone: {
        required: "Please enter your phone number",
        digits: "Please enter only digits",
        minlength: "Phone must be at least 10 digits",
        maxlength: "Phone cannot exceed 15 digits"
      },
      comments: {
        required: "Please enter your comments",
        minlength: "Comments must be at least 5 characters"
      },
      trade_in: {
        required: "Please confirm if you have a trade-in"
      }
    },
    errorElement: "div",
    errorPlacement: function(error, element) {
      error.addClass("text-danger mt-1 small");
      if (element.attr("type") === "checkbox") {
        error.insertAfter(element.closest(".form-check"));
      } else {
        error.insertAfter(element);
      }
    },

    submitHandler: function(form) {
      // Validation passed, now do AJAX
      var formData = $(form).serialize();
      $('#gfam-response').html('<p>Submitting...</p>');

      $.ajax({
        url: gfam_ajax_obj.ajax_url,
        type: 'POST',
        data: formData + '&action=request_call_back_submit&security=' + gfam_ajax_obj.nonce,
        success: function(response) {
          if (response.success) {
            $('#gfam-response').html('<p style="color:green;">' + response.data + '</p>');
            form.reset();
          } else {
            $('#gfam-response').html('<p style="color:red;">' + response.data + '</p>');
          }
        },
        error: function() {
          $('#gfam-response').html('<p style="color:red;">Error! Please try again.</p>');
        }
      });
    }
  });



  // Video Walkthrough form js
  $('#reqVideoDropdownMenu .gfam-detail-dropdown-item').on('click', function() {
    var value = $(this).data('value');
    var text = $(this).text();
    $('#gfamMakeInput').val(value);
    $('#reqVideoDropdown .gfam-detail-dropdown-text').text(text);
  });

  // Form submit handler
  $("#requestVideoForm").validate({
    rules: {
      first_name: { required: true, minlength: 2 },
      last_name: { required: true, minlength: 2 },
      phone: { required: true, digits: true, minlength: 8, maxlength: 15 },
      post_code: { required: true, minlength: 3 },
      email: { required: true, email: true },
      make: { required: true }
    },
    messages: {
      first_name: "Please enter your first name",
      last_name: "Please enter your last name",
      phone: {
        required: "Please enter your phone number",
        digits: "Please enter only numbers"
      },
      post_code: "Please enter your post code",
      email: "Please enter a valid email address",
      make: "Please select a make"
    },
    errorElement: "div",
    errorPlacement: function(error, element) {
      error.addClass("text-danger mt-1 small");
      if (element.attr("type") === "checkbox") {
        error.insertAfter(element.closest(".form-check"));
      } else if (element.attr("name") === "make") {
        error.insertAfter("#gfamMakeDropdown"); // show below dropdown button
      } else {
        error.insertAfter(element);
      }
    },

    submitHandler: function(form) {
      var formData = $(form).serialize();
      $('#reqVideoFrmResponse').html('<p>Submitting...</p>');

      $.ajax({
        url: gfam_ajax_obj.ajax_url,
        type: 'POST',
        data: formData + '&action=request_video_submit&security=' + gfam_ajax_obj.nonce,
        success: function(response) {
          if (response.success) {
            $('#reqVideoFrmResponse').html('<p style="color:green;">' + response.data + '</p>');
            form.reset();
            $('#reqVideoDropdown .gfam-detail-dropdown-text').text('Make'); // reset dropdown
          } else {
            $('#reqVideoFrmResponse').html('<p style="color:red;">' + response.data + '</p>');
          }
        },
        error: function() {
          $('#reqVideoFrmResponse').html('<p style="color:red;">Error! Please try again.</p>');
        }
      });

      return false;
    }
  });


  // Ask a Question FORM
  $("#askQuestionModalForm").validate({
    rules: {
      first_name: { required: true, minlength: 2 },
      last_name: { required: true, minlength: 2 },
      phone: { required: true, digits: true, minlength: 8, maxlength: 15 },
      post_code: { required: true, minlength: 3 },
      email: { required: true, email: true }
    },
    messages: {
      first_name: "Please enter your first name",
      last_name: "Please enter your last name",
      phone: {
        required: "Please enter your phone number",
        digits: "Please enter only numbers"
      },
      post_code: "Please enter your post code",
      email: "Please enter a valid email address"
    },
    errorElement: "div",
    errorPlacement: function(error, element) {
      error.addClass("text-danger mt-1 small");
      if (element.attr("type") === "checkbox") {
        error.insertAfter(element.closest(".form-check"));
      } else if (element.attr("name") === "make") {
        error.insertAfter("#gfamMakeDropdown"); // show below dropdown button
      } else {
        error.insertAfter(element);
      }
    },

    submitHandler: function(form) {
      var formData = $(form).serialize();
      $('#askQuestionResponse').html('<p>Submitting...</p>');

      $.ajax({
        url: gfam_ajax_obj.ajax_url,
        type: 'POST',
        data: formData + '&action=ask_question_form_submit&security=' + gfam_ajax_obj.nonce,
        success: function(response) {
          if (response.success) {
            $('#askQuestionResponse').html('<p style="color:green;">' + response.data + '</p>');
            form.reset();
            $('#reqVideoDropdown .gfam-detail-dropdown-text').text('Make'); // reset dropdown
          } else {
            $('#askQuestionResponse').html('<p style="color:red;">' + response.data + '</p>');
          }
        },
        error: function() {
          $('#askQuestionResponse').html('<p style="color:red;">Error! Please try again.</p>');
        }
      });

      return false;
    }
  });


  // Contact US FORM
  $("#contactUsModalForm").validate({
    rules: {
      first_name: { required: true, minlength: 2 },
      last_name: { required: true, minlength: 2 },
      phone: { required: true, digits: true, minlength: 8, maxlength: 15 },
      email: { required: true, email: true }
    },
    messages: {
      first_name: "Please enter your first name",
      last_name: "Please enter your last name",
      phone: {
        required: "Please enter your phone number",
        digits: "Please enter only numbers"
      },
      email: "Please enter a valid email address"
    },
    errorElement: "div",
    errorPlacement: function(error, element) {
      error.addClass("text-danger mt-1 small");
      if (element.attr("type") === "checkbox") {
        error.insertAfter(element.closest(".form-check"));
      } else if (element.attr("name") === "make") {
        error.insertAfter("#gfamMakeDropdown"); // show below dropdown button
      } else {
        error.insertAfter(element);
      }
    },

    submitHandler: function(form) {
      var formData = $(form).serialize();
      $('#contactUsModalResponse').html('<p>Submitting...</p>');

      $.ajax({
        url: gfam_ajax_obj.ajax_url,
        type: 'POST',
        data: formData + '&action=contact_us_request_submit&security=' + gfam_ajax_obj.nonce,
        success: function(response) {
          if (response.success) {
            $('#contactUsModalResponse').html('<p style="color:green;">' + response.data + '</p>');
            form.reset();
            $('#reqVideoDropdown .gfam-detail-dropdown-text').text('Make'); // reset dropdown
          } else {
            $('#contactUsModalResponse').html('<p style="color:red;">' + response.data + '</p>');
          }
        },
        error: function() {
          $('#contactUsModalResponse').html('<p style="color:red;">Error! Please try again.</p>');
        }
      });

      return false;
    }
  });


  //Request a Test Drive form js
   // Dropdown logic
   $('#testDriveDropdownMenu .gfam-detail-dropdown-item').on('click', function() {
      var value = $(this).data('value');
      var text = $(this).text();
      $('#testDriveMakeInput').val(value);
      $('#testDriveDropdown .gfam-detail-dropdown-text').text(text);
    });

    // // Example date/time picker initialization (optional)
    
    // $('#gfam-detail-datepicker').datepicker({
    //   dateFormat: 'dd-mm-yy',
    //   minDate: 0
    // });

    // $('#gfam-detail-timepicker').timepicker({
    //   timeFormat: 'h:mm p',
    //   interval: 30,
    //   minTime: '9:00am',
    //   maxTime: '6:00pm',
    //   dynamic: false,
    //   dropdown: true,
    //   scrollbar: true
    // });

    // Submit handler
    $('#requestTestDriveForm').validate({
      rules: {
        make: { required: true },
        email: { required: true, email: true },
        phone: { required: true }, // optional: add phoneUS method if needed
        first_name: { required: true },
        last_name: { required: true },
        post_code: { required: true },
        preferred_date: { required: true },
        preferred_time: { required: true }
      },
      messages: {
        make: "Please select a Make",
        email: { required: "Please enter your email", email: "Please enter a valid email address" },
        phone: { required: "Please enter your phone number", phoneUS: "Please enter a valid phone number" },
        first_name: "Please enter your first name",
        last_name: "Please enter your last name",
        post_code: "Please enter your postcode",
        preferred_date: "Please select a date",
        preferred_time: "Please select a time"
      },
      errorElement: "div",
      errorPlacement: function(error, element) {
        error.addClass("text-danger mt-1 small");
      },
      submitHandler: function(form) {
        var formData = $(form).serialize();
        $('#gfamDetailResponse').html('<p>Submitting...</p>');

        $.ajax({
          url: gfam_ajax_obj.ajax_url,
          type: 'POST',
          data: formData + '&action=test_drive_request_submit&security=' + gfam_ajax_obj.nonce,
          success: function(response) {
            if (response.success) {
              $('#gfamDetailResponse').html('<p style="color:green;">' + response.data + '</p>');
              form.reset();
              $('#testDriveDropdown .gfam-detail-dropdown-text').text('Make'); // reset dropdown
            } else {
              $('#gfamDetailResponse').html('<p style="color:red;">' + response.data + '</p>');
            }
          },
          error: function() {
            $('#gfamDetailResponse').html('<p style="color:red;">Error! Please try again.</p>');
          }
        });

        return false; // prevent default form submit
      }
    });

});
