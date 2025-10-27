<?php
// Handle AJAX Form Submission
add_action('wp_ajax_request_call_back_submit', 'handle_request_call_back_submit');
add_action('wp_ajax_nopriv_request_call_back_submit', 'handle_request_call_back_submit');

function handle_request_call_back_submit() {
    check_ajax_referer('gfam_form_nonce', 'security');

    // Sanitize fields
    $first_name = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name  = sanitize_text_field($_POST['last_name'] ?? '');
    $email      = sanitize_email($_POST['email'] ?? '');
    $phone      = sanitize_text_field($_POST['phone'] ?? '');
    $comments   = sanitize_textarea_field($_POST['comments'] ?? '');
    $trade_in   = isset($_POST['trade_in']) ? 'Yes' : 'No';

    if (empty($first_name) || empty($last_name) || empty($email)) {
        wp_send_json_error('Please fill all required fields.');
    }

    // Load email template dynamically
    ob_start();
    include RSL_PLUGIN_DIR . 'templates/email-templates/request-call-back-form.php';
    $message = ob_get_clean();

    // Email details
    $to = get_option('admin_email');
    $subject = 'New Call Back Request - GFAM';
    $headers = ['Content-Type: text/html; charset=UTF-8'];

    if (wp_mail($to, $subject, $message, $headers)) {
        wp_send_json_success('Thank you! Your request has been sent successfully.');
    } else {
        wp_send_json_error('Failed to send email. Please try again.');
    }

    wp_die();
}


// Video Walkthrough form ajax code
add_action('wp_ajax_request_video_submit', 'handle_request_video_submit');
add_action('wp_ajax_nopriv_request_video_submit', 'handle_request_video_submit');

function handle_request_video_submit() {
    check_ajax_referer('gfam_form_nonce', 'security');

    // Sanitize inputs
    $first_name = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name  = sanitize_text_field($_POST['last_name'] ?? '');
    $email      = sanitize_email($_POST['email'] ?? '');
    $phone      = sanitize_text_field($_POST['phone'] ?? '');
    $post_code  = sanitize_text_field($_POST['post_code'] ?? '');
    $make       = sanitize_text_field($_POST['make'] ?? '');

    if (empty($first_name) || empty($last_name) || empty($email)) {
        wp_send_json_error('Please fill all required fields.');
    }

    ob_start();

    // These variables will be available inside your template
    include RSL_PLUGIN_DIR . 'templates/email-templates/video-walkthrough-form.php';

    $message = ob_get_clean();

    // Send Email
    $to = get_option('admin_email');
    $subject = 'New Video Walkthrough Request - GFAM';
    $headers = ['Content-Type: text/html; charset=UTF-8'];

    if (wp_mail($to, $subject, $message, $headers)) {
        wp_send_json_success('Thank you! Your request has been sent successfully.');
    } else {
        wp_send_json_error('Failed to send email. Please try again.');
    }

    wp_die();
}

add_action('wp_ajax_ask_question_form_submit', 'handle_ask_question_form_submit');
add_action('wp_ajax_nopriv_ask_question_form_submit', 'handle_ask_question_form_submit');

function handle_ask_question_form_submit() {
    check_ajax_referer('gfam_form_nonce', 'security');

    // Sanitize and validate
    $first_name = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name  = sanitize_text_field($_POST['last_name'] ?? '');
    $email      = sanitize_email($_POST['email'] ?? '');
    $phone      = sanitize_text_field($_POST['phone'] ?? '');
    $post_code  = sanitize_text_field($_POST['post_code'] ?? '');

    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($post_code)) {
        wp_send_json_error('Please fill all required fields.');
    }

    // Load email template dynamically
    ob_start();
    include RSL_PLUGIN_DIR . 'templates/email-templates/ask-question-form.php';
    $message = ob_get_clean();

    // Email settings
    $to = get_option('admin_email');
    $subject = 'New Ask a Question Submission - GFAM';
    $headers = ['Content-Type: text/html; charset=UTF-8'];

    if (wp_mail($to, $subject, $message, $headers)) {
        wp_send_json_success('Thank you! Your question has been sent successfully.');
    } else {
        wp_send_json_error('Failed to send email. Please try again.');
    }

    wp_die();
}


//Request a Test Drive form code
add_action('wp_ajax_test_drive_request_submit', 'handle_test_drive_request_submit');
add_action('wp_ajax_nopriv_test_drive_request_submit', 'handle_test_drive_request_submit');

function handle_test_drive_request_submit() {
    check_ajax_referer('gfam_form_nonce', 'security');

    // Sanitize input
    $first_name     = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name      = sanitize_text_field($_POST['last_name'] ?? '');
    $email          = sanitize_email($_POST['email'] ?? '');
    $phone          = sanitize_text_field($_POST['phone'] ?? '');
    $post_code      = sanitize_text_field($_POST['post_code'] ?? '');
    $make           = sanitize_text_field($_POST['make'] ?? '');
    $preferred_date = sanitize_text_field($_POST['preferred_date'] ?? '');
    $preferred_time = sanitize_text_field($_POST['preferred_time'] ?? '');

    if (
        empty($first_name) || empty($last_name) || empty($email) ||
        empty($phone) || empty($post_code) || empty($make) ||
        empty($preferred_date) || empty($preferred_time)
    ) {
        wp_send_json_error('Please fill all required fields.');
    }

    // Load email template dynamically
    ob_start();
    include RSL_PLUGIN_DIR . 'templates/email-templates/test-drive-request-form.php';
    $message = ob_get_clean();

    // Send Email
    $to = get_option('admin_email');
    $subject = 'New Test Drive Request - GFAM';
    $headers = ['Content-Type: text/html; charset=UTF-8'];

    if (wp_mail($to, $subject, $message, $headers)) {
        wp_send_json_success('Thank you! Your test drive request has been sent successfully.');
    } else {
        wp_send_json_error('Failed to send email. Please try again.');
    }

    wp_die();
}

?>