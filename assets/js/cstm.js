jQuery(document).ready(function($) {
  $('#gfam-form').on('submit', function(e) {
    e.preventDefault();

    var formData = $(this).serialize();
    $('#gfam-response').html('<p>Submitting...</p>');

    $.ajax({
      url: gfam_ajax_obj.ajax_url,
      type: 'POST',
      data: formData + '&action=gfam_form_submit&security=' + gfam_ajax_obj.nonce,
      success: function(response) {
        if (response.success) {
          $('#gfam-response').html('<p style="color:green;">' + response.data + '</p>');
          $('#gfam-form')[0].reset();
        } else {
          $('#gfam-response').html('<p style="color:red;">' + response.data + '</p>');
        }
      },
      error: function() {
        $('#gfam-response').html('<p style="color:red;">Error! Please try again.</p>');
      }
    });
  });


  // Video Walkthrough form js

  $('#reqVideoDropdownMenu .gfam-detail-dropdown-item').on('click', function() {
    var value = $(this).data('value');
    var text = $(this).text();
    $('#gfamMakeInput').val(value);
    $('#reqVideoDropdown .gfam-detail-dropdown-text').text(text);
  });

  // Form submit handler
  $('#requestVideoForm').on('submit', function(e) {
    e.preventDefault();

    var formData = $(this).serialize();
    $('#reqVideoFrmResponse').html('<p>Submitting...</p>');

    $.ajax({
      url: gfam_ajax_obj.ajax_url,
      type: 'POST',
      data: formData + '&action=gfam_detail_form_submit&security=' + gfam_ajax_obj.nonce,
      success: function(response) {
        if (response.success) {
          $('#reqVideoFrmResponse').html('<p style="color:green;">' + response.data + '</p>');
          $('#requestVideoForm')[0].reset();
          $('#reqVideoDropdown .gfam-detail-dropdown-text').text('Make'); // Reset dropdown text
        } else {
          $('#reqVideoFrmResponse').html('<p style="color:red;">' + response.data + '</p>');
        }
      },
      error: function() {
        $('#reqVideoFrmResponse').html('<p style="color:red;">Error! Please try again.</p>');
      }
    });
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
    $('#requestTestDriveForm').on('submit', function(e) {
      e.preventDefault();

      var formData = $(this).serialize();
      $('#gfamDetailResponse').html('<p>Submitting...</p>');

      $.ajax({
        url: gfam_ajax_obj.ajax_url,
        type: 'POST',
        data: formData + '&action=gfam_video_request_submit&security=' + gfam_ajax_obj.nonce,
        success: function(response) {
          if (response.success) {
            $('#gfamDetailResponse').html('<p style="color:green;">' + response.data + '</p>');
            $('#requestTestDriveForm')[0].reset();
            $('#testDriveDropdown .gfam-detail-dropdown-text').text('Make'); // reset dropdown text
          } else {
            $('#gfamDetailResponse').html('<p style="color:red;">' + response.data + '</p>');
          }
        },
        error: function() {
          $('#gfamDetailResponse').html('<p style="color:red;">Error! Please try again.</p>');
        }
      });
    });


});
