<?php
// Available variables: $first_name, $last_name, $email, $phone, $post_code, $make, $preferred_date, $preferred_time
$logo_url = '';
if( function_exists('get_field') ) {
    $logo_url = get_field('email_logo', 'option'); // get logo from options
}

// Fallback to default if ACF not active or no logo set
if (empty($logo_url)) {
    $logo_url = RSL_PLUGIN_DIR . 'assets/images/default-logo.png';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc_html__('Test Drive Request', 'retain-stock-locator') ?></title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        a {
            text-decoration: none;
        }

        .cta-button {
            display: inline-block;
            background-color: #92191C;
            color: #ffffff;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
        }

        .cta-button:hover {
            background-color: #740f11;
        }

        .note {
            background-color: #fff9e6;
            border: 1px solid #FDBD3D;
            padding: 15px;
            border-radius: 4px;
        }

        @media only screen and (max-width:600px) {
            .content td {
                display: block;
                width: 100% !important;
            }
        }
    </style>
</head>

<body>
    <table width="100%" bgcolor="#f5f5f5" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 20px;">
                <table width="600" bgcolor="#ffffff" cellpadding="0" cellspacing="0"
                    style="border-radius:8px; overflow:hidden;">
                    <!-- Header -->
                    <tr>
                        <td bgcolor="#92191C" style="padding: 30px 20px; text-align:center;">
                            <div style="background-color: #fff; padding: 10px; width: max-content; margin: 0 auto 20px; border-radius: 10px;">
                               <img src="<?= esc_url($logo_url) ?>" alt="<?= esc_attr__('Logo', 'retain-stock-locator') ?>" style="max-width:120px; height:auto;">
                            </div>
                            <h1 style="color:#fff; font-size:24px; margin-bottom:10px;">
                                <?= esc_html__('Test Drive Request', 'retain-stock-locator') ?>
                            </h1>
                            <p style="color:#FDBD3D; font-size:14px;">
                                <?= esc_html__('Submission details below', 'retain-stock-locator') ?>
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:40px 30px;">
                            <p style="color:#333333; font-size:16px; line-height:1.6; margin-bottom:30px;">
                                <?= esc_html__('Hi Admin,', 'retain-stock-locator') ?><br>
                                <?= esc_html__('A new test drive request has been received. Details are as follows:', 'retain-stock-locator') ?>
                            </p>

                            <table width="100%" bgcolor="#f9f9f9" cellpadding="15" cellspacing="0"
                                style="border-left:4px solid #FDBD3D; border-radius:4px; margin-bottom:30px;">
                                <tr>
                                    <td colspan="2" style="border-bottom:2px solid #FDBD3D; padding-bottom:10px; font-size:18px; color:#92191C;">
                                        <?= esc_html__('Request Details', 'retain-stock-locator') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold; color:#92191C; width:160px; font-size:14px;">
                                        <?= esc_html__('Name', 'retain-stock-locator') ?>
                                    </td>
                                    <td style="color:#333333; font-size:14px;"><?= esc_html($first_name . ' ' . $last_name) ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold; color:#92191C; font-size:14px;">
                                        <?= esc_html__('Email', 'retain-stock-locator') ?>
                                    </td>
                                    <td style="color:#333333; font-size:14px;"><?= esc_html($email) ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold; color:#92191C; font-size:14px;">
                                        <?= esc_html__('Phone', 'retain-stock-locator') ?>
                                    </td>
                                    <td style="color:#333333; font-size:14px;"><?= esc_html($phone) ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold; color:#92191C; font-size:14px;">
                                        <?= esc_html__('Post Code', 'retain-stock-locator') ?>
                                    </td>
                                    <td style="color:#333333; font-size:14px;"><?= esc_html($post_code) ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold; color:#92191C; font-size:14px;">
                                        <?= esc_html__('Make', 'retain-stock-locator') ?>
                                    </td>
                                    <td style="color:#333333; font-size:14px;"><?= esc_html($make) ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold; color:#92191C; font-size:14px;">
                                        <?= esc_html__('Preferred Date', 'retain-stock-locator') ?>
                                    </td>
                                    <td style="color:#333333; font-size:14px;"><?= esc_html($preferred_date) ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold; color:#92191C; font-size:14px;">
                                        <?= esc_html__('Preferred Time', 'retain-stock-locator') ?>
                                    </td>
                                    <td style="color:#333333; font-size:14px;"><?= esc_html($preferred_time) ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td bgcolor="#333333" style="color:#ffffff; padding:25px 30px; text-align:center; font-size:12px; line-height:1.6;">
                            &copy; 2025 <?= esc_html__('Your Company Name. All rights reserved.', 'retain-stock-locator') ?><br>
                            <a href="[UNSUBSCRIBE_LINK]" style="color:#FDBD3D;"><?= esc_html__('Unsubscribe', 'retain-stock-locator') ?></a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
