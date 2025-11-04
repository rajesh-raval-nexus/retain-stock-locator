<?php
// Handle AJAX Form Submission
add_action('wp_ajax_request_call_back_submit', 'handle_request_call_back_submit');
add_action('wp_ajax_nopriv_request_call_back_submit', 'handle_request_call_back_submit');

function handle_request_call_back_submit() {
    check_ajax_referer('gfam_form_nonce', 'security');

    // -----------------------------
    // Sanitize form fields
    // -----------------------------
    $first_name = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name  = sanitize_text_field($_POST['last_name'] ?? '');
    $email      = sanitize_email($_POST['email'] ?? '');
    $phone      = sanitize_text_field($_POST['phone'] ?? '');
    $comments   = sanitize_textarea_field($_POST['comments'] ?? '');
    $trade_in   = isset($_POST['trade_in']) ? 'Yes' : 'No';

    if (empty($first_name) || empty($last_name) || empty($email)) {
        wp_send_json_error('Please fill all required fields.');
    }

    // -----------------------------
    // Get email templates from ACF group
    // -----------------------------
    $template_data = get_field('request_a_call_back_email_template', 'option');

    $admin_subject = $template_data['request_a_call_back_subject_name'] ?? 'New Call Back Request';
    $admin_content = $template_data['request_a_call_back_email_content'] ?? '';

    $user_subject = $template_data['request_a_call_back_subject_name_for_user'] ?? 'Thank you for your request';
    $user_content = $template_data['request_a_call_back_email_content_for_user'] ?? '';

    // -----------------------------
    // Default fallback
    // -----------------------------
    if (empty($admin_content)) {
        $admin_content = '<p>No admin email template found in backend.</p>';
    }

    if (empty($user_content)) {
        $user_content = '<p>No user email template found in backend.</p>';
    }

    // -----------------------------
    // Prepare placeholders
    // -----------------------------
    $logo_url = get_field('email_logo', 'option');
    if (empty($logo_url)) {
        $logo_url = get_site_url() . '/wp-content/uploads/default-logo.png';
    }

    $replacements = [
        '{first_name}' => esc_html($first_name),
        '{last_name}'  => esc_html($last_name),
        '{email}'      => esc_html($email),
        '{phone}'      => esc_html($phone),
        '{trade_in}'   => esc_html($trade_in),
        '{comments}'   => nl2br(esc_html($comments)),
        '{logo_url}'   => esc_url($logo_url),
        '{site_name}'  => esc_html(get_bloginfo('name')),
        '{year}'       => date('Y'),
    ];

    // -----------------------------
    // Send Email to Admin
    // -----------------------------
    $admin_message = strtr($admin_content, $replacements);
    $to_admin = get_option('admin_email');
    $headers = ['Content-Type: text/html; charset=UTF-8'];

    $admin_sent = wp_mail($to_admin, $admin_subject, $admin_message, $headers);

    // -----------------------------
    // Send Email to User
    // -----------------------------
    $user_message = strtr($user_content, $replacements);

    if (!empty($email)) {
        $user_sent = wp_mail($email, $user_subject, $user_message, $headers);
        if (!$user_sent) {
            error_log('User email failed to send to: ' . $email);
        }
    } else {
        error_log('No user email found for callback form.');
    }

    // -----------------------------
    // Final Response
    // -----------------------------
    if ($admin_sent) {
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

    // -----------------------------
    // Sanitize form fields
    // -----------------------------
    $first_name = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name  = sanitize_text_field($_POST['last_name'] ?? '');
    $email      = sanitize_email($_POST['email'] ?? '');
    $phone      = sanitize_text_field($_POST['phone'] ?? '');
    $post_code  = sanitize_text_field($_POST['post_code'] ?? '');
    $make       = sanitize_text_field($_POST['make'] ?? '');

    if (empty($first_name) || empty($last_name) || empty($email)) {
        wp_send_json_error('Please fill all required fields.');
    }

    // -----------------------------
    // Get email templates from ACF group
    // -----------------------------
    $template_data = get_field('video_walkthrough_email_template', 'option');

    $admin_subject = $template_data['video_walkthrough_subject_name'] ?? 'New Video Walkthrough Request';
    $admin_content = $template_data['video_walkthrough_email_content'] ?? '';

    $user_subject  = $template_data['video_walkthrough_subject_name_for_user'] ?? 'Thank you for your request';
    $user_content  = $template_data['video_walkthrough_email_content_for_user'] ?? '';

    // -----------------------------
    // Default fallback
    // -----------------------------
    if (empty($admin_content)) {
        $admin_content = '<p>No admin email template found in backend.</p>';
    }

    if (empty($user_content)) {
        $user_content = '<p>No user email template found in backend.</p>';
    }

    // -----------------------------
    // Prepare placeholders
    // -----------------------------
    $logo_url = get_field('email_logo', 'option');
    if (empty($logo_url)) {
        $logo_url = get_site_url() . '/wp-content/uploads/default-logo.png';
    }

    $replacements = [
        '{first_name}' => esc_html($first_name),
        '{last_name}'  => esc_html($last_name),
        '{email}'      => esc_html($email),
        '{phone}'      => esc_html($phone),
        '{post_code}'  => esc_html($post_code),
        '{make}'       => esc_html($make),
        '{logo_url}'   => esc_url($logo_url),
        '{site_name}'  => esc_html(get_bloginfo('name')),
        '{year}'       => date('Y'),
    ];

    // -----------------------------
    // Replace placeholders
    // -----------------------------
    $admin_message = strtr($admin_content, $replacements);
    $user_message  = strtr($user_content, $replacements);

    // -----------------------------
    // Email Headers
    // -----------------------------
    $headers = ['Content-Type: text/html; charset=UTF-8'];

    // -----------------------------
    // Send Admin Email
    // -----------------------------
    $to_admin = get_option('admin_email');
    $admin_sent = wp_mail($to_admin, $admin_subject, $admin_message, $headers);

    // -----------------------------
    // Send User Email
    // -----------------------------
    if (!empty($email)) {
        $user_sent = wp_mail($email, $user_subject, $user_message, $headers);
        if (!$user_sent) {
            error_log('User email failed to send to: ' . $email);
        }
    } else {
        error_log('No user email found for video walkthrough form.');
    }

    // -----------------------------
    // Final Response
    // -----------------------------
    if ($admin_sent) {
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

    // -----------------------------
    // Sanitize and validate fields
    // -----------------------------
    $first_name = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name  = sanitize_text_field($_POST['last_name'] ?? '');
    $email      = sanitize_email($_POST['email'] ?? '');
    $phone      = sanitize_text_field($_POST['phone'] ?? '');
    $post_code  = sanitize_text_field($_POST['post_code'] ?? '');
    $question   = sanitize_textarea_field($_POST['question'] ?? '');

    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($post_code)) {
        wp_send_json_error('Please fill all required fields.');
    }

    // -----------------------------
    // Get ACF Email Templates
    // -----------------------------
    $template_data = get_field('ask_question_email_template', 'option');

    $admin_subject = $template_data['ask_question_subject_name'] ?? 'New Question Submission';
    $admin_content = $template_data['ask_question_email_content'] ?? '';

    $user_subject  = $template_data['ask_question_subject_name_for_user'] ?? 'Thank you for your question';
    $user_content  = $template_data['ask_question_email_content_for_user'] ?? '';

    if (empty($admin_content)) {
        $admin_content = '<p>No admin email template found in backend.</p>';
    }

    if (empty($user_content)) {
        $user_content = '<p>No user email template found in backend.</p>';
    }

    // -----------------------------
    // Prepare placeholders
    // -----------------------------
    $logo_url = get_field('email_logo', 'option');
    if (empty($logo_url)) {
        $logo_url = get_site_url() . '/wp-content/uploads/default-logo.png';
    }

    $replacements = [
        '{first_name}' => esc_html($first_name),
        '{last_name}'  => esc_html($last_name),
        '{email}'      => esc_html($email),
        '{phone}'      => esc_html($phone),
        '{post_code}'  => esc_html($post_code),
        '{question}'   => nl2br(esc_html($question)),
        '{logo_url}'   => esc_url($logo_url),
        '{site_name}'  => esc_html(get_bloginfo('name')),
        '{year}'       => date('Y'),
    ];

    // -----------------------------
    // Replace placeholders
    // -----------------------------
    $admin_message = strtr($admin_content, $replacements);
    $user_message  = strtr($user_content, $replacements);

    $headers = ['Content-Type: text/html; charset=UTF-8'];
    $to_admin = get_option('admin_email');

    // -----------------------------
    // Send emails
    // -----------------------------
    $admin_sent = wp_mail($to_admin, $admin_subject, $admin_message, $headers);

    if (!empty($email)) {
        $user_sent = wp_mail($email, $user_subject, $user_message, $headers);
        if (!$user_sent) {
            error_log('User email failed to send to: ' . $email);
        }
    }

    // -----------------------------
    // Final response
    // -----------------------------
    if ($admin_sent) {
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

    // Sanitize input fields
    $first_name     = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name      = sanitize_text_field($_POST['last_name'] ?? '');
    $email          = sanitize_email($_POST['email'] ?? '');
    $phone          = sanitize_text_field($_POST['phone'] ?? '');
    $post_code      = sanitize_text_field($_POST['post_code'] ?? '');
    $make           = sanitize_text_field($_POST['make'] ?? '');
    $preferred_date = sanitize_text_field($_POST['preferred_date'] ?? '');
    $preferred_time = sanitize_text_field($_POST['preferred_time'] ?? '');

    // Validate required fields
    if (
        empty($first_name) || empty($last_name) || empty($email) ||
        empty($phone) || empty($post_code) || empty($make) ||
        empty($preferred_date) || empty($preferred_time)
    ) {
        wp_send_json_error('Please fill all required fields.');
    }

    $logo_url = get_field('email_logo', 'option');
    if (empty($logo_url)) {
        $logo_url = get_site_url() . '/wp-content/uploads/default-logo.png';
    }

    // ==============================
    // Get ACF email template group fields
    // ==============================
    $email_template_group = get_field('test_drive_email_template', 'option');

    $admin_subject = $email_template_group['test_drive_subject_name'] ?? 'New Test Drive Request - GFAM';
    $admin_message = $email_template_group['test_drive_email_content'] ?? '';

    $user_subject  = $email_template_group['test_drive_subject_name_for_user'] ?? 'Thank You for Your Test Drive Request';
    $user_message  = $email_template_group['test_drive_email_content_for_user'] ?? '';

    // ==============================
    // Replace placeholders dynamically
    // ==============================
    $placeholders = [
        '{first_name}'     => $first_name,
        '{last_name}'      => $last_name,
        '{email}'          => $email,
        '{phone}'          => $phone,
        '{post_code}'      => $post_code,
        '{make}'           => $make,
        '{preferred_date}' => $preferred_date,
        '{preferred_time}' => $preferred_time,
        '{site_name}'      => get_bloginfo('name'),
        '{site_url}'       => home_url(),
        '{logo_url}'       => esc_url($logo_url),
        '{year}'           => date('Y'),
    ];

    $admin_message = str_replace(array_keys($placeholders), array_values($placeholders), $admin_message);
    $user_message  = str_replace(array_keys($placeholders), array_values($placeholders), $user_message);

    // ==============================
    // Send Emails
    // ==============================
    $headers = ['Content-Type: text/html; charset=UTF-8'];
    $admin_email = get_option('admin_email');

    // Send to Admin
    wp_mail($admin_email, $admin_subject, $admin_message, $headers);

    // Send to User
    wp_mail($email, $user_subject, $user_message, $headers);

    // ==============================
    // Return success response
    // ==============================
    wp_send_json_success('Thank you! Your test drive request has been sent successfully.');
    wp_die();
}



?>