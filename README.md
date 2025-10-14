# retain-stock-locator


/**
 * Custom stock status messages (Low stock / Backorder / Out of stock)
 * for listing, single, and cart pages.
 */

// ===============================
// 2️⃣ SINGLE PRODUCT PAGE
// ===============================
// add_action( 'woocommerce_single_product_summary', 'jb_custom_stock_message_single', 25 );
function jb_custom_stock_message_single() {
    global $product;

    if ( ! $product->managing_stock() || get_the_ID() != 1168 ) {
        return;
    }

    $stock_quantity        = $product->get_stock_quantity();
    $low_stock_threshold   = $product->get_low_stock_amount();
    $backorders_allowed    = $product->backorders_allowed();

    echo "<div class='dfdsfdsf' style='display:none;'>";
    echo $backorders_allowed. 'hgu'.$stock_quantity.'erer'.$low_stock_threshold;
    echo "</div>";

    echo '<div class="jb-stock-message-single">';

    if ( $stock_quantity > 0 && $stock_quantity <= $low_stock_threshold ) {
        echo '<div class="jb-stock-box low-stock">⚡ Hurry! Only <strong>' . esc_html( $stock_quantity ) . '</strong> left in stock.</div>';
    }
    elseif ( $stock_quantity <= 0 && $backorders_allowed ) {
        echo '<div class="jb-stock-box backorder">📝 Available on backorder — order now to reserve yours.</div>';
    }
    elseif ( $stock_quantity <= 0 && ! $backorders_allowed ) {
        echo '<div class="jb-stock-box out-of-stock">❌ This item is currently out of stock. Check back soon!</div>';
    }

    echo '</div>';
}

// ===============================
// 3️⃣ CART / CHECKOUT PAGE NOTICE
// ===============================
// add_action( 'woocommerce_before_cart', 'jb_custom_stock_notice_cart' );
// add_action( 'woocommerce_before_checkout_form', 'jb_custom_stock_notice_cart' );
// function jb_custom_stock_notice_cart() {
//     foreach ( WC()->cart->get_cart() as $cart_item ) {
//         $product = $cart_item['data'];
//         if ( ! $product->managing_stock() ) continue;

//         $qty_cart             = $cart_item['quantity'];
//         $stock_quantity       = $product->get_stock_quantity();
//         $backorders_allowed   = $product->backorders_allowed();
//         $is_in_stock          = $product->is_in_stock();

//         // Low stock warning in cart
//         if ( $is_in_stock && $stock_quantity > 0 && $qty_cart > $stock_quantity ) {
//             wc_print_notice(
//                 sprintf( '⚠️ There are only %d units of <strong>%s</strong> left in stock.', $stock_quantity, $product->get_name() ),
//                 'notice'
//             );
//         }

//         // Out of stock without backorder
//         if ( ! $is_in_stock && ! $backorders_allowed ) {
//             wc_print_notice(
//                 sprintf( '❌ <strong>%s</strong> is no longer in stock. Please remove it to continue checkout.', $product->get_name() ),
//                 'error'
//             );
//         }
//     }
// }

// ===============================
// 4️⃣ BASIC STYLES (Optional quick CSS)
// ===============================
add_action( 'wp_head', function() {
    ?>
    <style>
    .jb-stock-badge {
        display: block;
        font-size: 0.85rem;
        margin-top: 5px;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 500;
    }
    .jb-stock-badge.low-stock,
    .jb-stock-box.low-stock {
        background: #FFF7E0;
        color: #92400E;
    }
    .jb-stock-badge.backorder,
    .jb-stock-box.backorder {
        background: #E0F2FE;
        color: #075985;
    }
    .jb-stock-badge.out-of-stock,
    .jb-stock-box.out-of-stock {
        background: #FEE2E2;
        color: #991B1B;
    }
    .jb-stock-box {
        padding: 10px 12px;
        margin-top: 10px;
        border-radius: 6px;
        font-size: 0.9rem;
    }
    </style>
    <?php
});