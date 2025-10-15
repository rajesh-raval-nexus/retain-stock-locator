<?php
// Handle AJAX Form Submission
add_action('wp_ajax_gfam_form_submit', 'handle_gfam_form_submit');
add_action('wp_ajax_nopriv_gfam_form_submit', 'handle_gfam_form_submit');

function handle_gfam_form_submit() {
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

    // Example: Send Email
    $to = get_option('admin_email');
    $subject = 'New Form Submission - GFAM';
    $message = "
        <strong>Name:</strong> $first_name $last_name<br>
        <strong>Email:</strong> $email<br>
        <strong>Phone:</strong> $phone<br>
        <strong>Trade In:</strong> $trade_in<br>
        <strong>Comments:</strong><br>$comments
    ";

    $headers = ['Content-Type: text/html; charset=UTF-8'];

    if (wp_mail($to, $subject, $message, $headers)) {
        wp_send_json_success('Thank you! Your message has been sent successfully.');
    } else {
        wp_send_json_error('Failed to send email. Please try again.');
    }

    wp_die();
}


// Video Walkthrough form ajax code
add_action('wp_ajax_gfam_detail_form_submit', 'handle_gfam_detail_form_submit');
add_action('wp_ajax_nopriv_gfam_detail_form_submit', 'handle_gfam_detail_form_submit');

function handle_gfam_detail_form_submit() {
    check_ajax_referer('gfam_form_nonce', 'security');

    // Sanitize and validate
    $first_name = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name  = sanitize_text_field($_POST['last_name'] ?? '');
    $email      = sanitize_email($_POST['email'] ?? '');
    $phone      = sanitize_text_field($_POST['phone'] ?? '');
    $post_code  = sanitize_text_field($_POST['post_code'] ?? '');
    $make       = sanitize_text_field($_POST['make'] ?? '');

    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($post_code) || empty($make)) {
        wp_send_json_error('Please fill all required fields.');
    }

    // Prepare email
    $to = get_option('admin_email');
    $subject = 'New Request a Video Submission - GFAM';
    $message = "
        <strong>Name:</strong> {$first_name} {$last_name}<br>
        <strong>Email:</strong> {$email}<br>
        <strong>Phone:</strong> {$phone}<br>
        <strong>Post Code:</strong> {$post_code}<br>
        <strong>Make:</strong> {$make}<br>
    ";
    $headers = ['Content-Type: text/html; charset=UTF-8'];

    if (wp_mail($to, $subject, $message, $headers)) {
        wp_send_json_success('Thank you! Your request has been sent successfully.');
    } else {
        wp_send_json_error('Failed to send email. Please try again.');
    }

    wp_die();
}

//Request a Test Drive form code
add_action('wp_ajax_gfam_video_request_submit', 'handle_gfam_video_request_submit');
add_action('wp_ajax_nopriv_gfam_video_request_submit', 'handle_gfam_video_request_submit');

function handle_gfam_video_request_submit() {
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

    
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($post_code) || empty($make) || empty($preferred_date) || empty($preferred_time)) {
        wp_send_json_error('Please fill all required fields.');
    }

    // Send email
    $to = get_option('admin_email');
    $subject = 'New Video Request Submission - GFAM';
    $message = "
        <strong>Name:</strong> {$first_name} {$last_name}<br>
        <strong>Email:</strong> {$email}<br>
        <strong>Phone:</strong> {$phone}<br>
        <strong>Post Code:</strong> {$post_code}<br>
        <strong>Make:</strong> {$make}<br>
        <strong>Preferred Date:</strong> {$preferred_date}<br>
        <strong>Preferred Time:</strong> {$preferred_time}<br>
    ";
    $headers = ['Content-Type: text/html; charset=UTF-8'];

    if (wp_mail($to, $subject, $message, $headers)) {
        wp_send_json_success('Thank you! Your video request has been sent successfully.');
    } else {
        wp_send_json_error('Failed to send email. Please try again.');
    }

    wp_die();
}




?>