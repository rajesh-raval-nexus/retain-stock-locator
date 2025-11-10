<?php 
// Get selected Stock Locator page ID from ACF Options
$stock_locator_page_ID = get_field('select_stock_locator_page', 'option');
$page_title = get_the_title( $stock_locator_page_ID );
$page_url = get_the_permalink($stock_locator_page_ID);
?>
<div class="gfam-breadcrumb mb-lg-5 mb-3 p-lg-3 p-2">
    <!-- Breadcrumb -->
    <nav class="d-flex align-items-center gap-2">
        <a href="<?php echo home_url(); ?>" class="d-flex align-items-center">
            <svg class="mb-1" width="19" height="22" viewBox="0 0 19 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M17.4865 22H13.5926C12.7593 22 12.0815 21.164 12.0815 20.1359V12.9631C12.0815 12.7504 11.9403 12.5761 11.7678 12.5761H7.22977C7.05735 12.5761 6.91606 12.7504 6.91606 12.9631V20.1359C6.91606 21.164 6.23834 22 5.40497 22H1.51109C0.677719 22 0 21.164 0 20.1359V10.4402C0 9.46529 0.344847 8.54357 0.948325 7.91433L7.76141 0.788774C8.7672 -0.262925 10.228 -0.262925 11.2338 0.788774L18.0517 7.91728C18.6552 8.54653 19 9.46824 19 10.4431V20.1359C19 21.164 18.3223 22 17.4889 22H17.4865ZM7.22738 11.099H11.7654C12.5988 11.099 13.2765 11.935 13.2765 12.9631V20.1359C13.2765 20.3486 13.4178 20.5229 13.5902 20.5229H17.4841C17.6565 20.5229 17.7978 20.3486 17.7978 20.1359V10.4431C17.7978 9.90251 17.6063 9.39143 17.271 9.04284L10.4531 1.91433C9.89514 1.33235 9.08571 1.33235 8.52773 1.91433L1.71465 9.03988C1.37938 9.38848 1.19019 9.89956 1.19019 10.4402V20.1359C1.19019 20.3486 1.33148 20.5229 1.50391 20.5229H5.39778C5.57021 20.5229 5.7115 20.3486 5.7115 20.1359V12.9631C5.7115 11.935 6.38921 11.099 7.22259 11.099H7.22738Z"
                    fill="#313131"
                />
            </svg>
        </a>
        <span> > </span>
        <a href="<?php echo esc_url($page_url); ?>"><?php echo $page_title; ?></a>
        <!-- <span> > </span> -->
        <!-- <span class="active">Farm Tractors For Sale</span> -->
    </nav>
</div>