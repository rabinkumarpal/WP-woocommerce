
<?php

// Hook when a coupon is applied to a cart
add_action( 'woocommerce_applied_coupon', 'mwd_get_applied_coupons' );

// Get the current applied coupon and compare it with other applied coupons
function mwd_get_applied_coupons() {
    // Get the currently applied coupon's type using the $_POST global to retrieve the current coupon's code
    foreach ( WC()->cart->get_coupons() as $code => $coupon ) {
        if($coupon->code == $_POST['coupon_code']) {
            // Get this coupon's type so we can remove all other coupons of this type from the cart
            $type_to_remove = $coupon->discount_type;
        }
    }    
    // Remove any other coupons of this type
    // Loop through all other applied coupons
    foreach ( WC()->cart->get_coupons() as $code => $coupon ) {
        // Check whether each coupon matches the type of the coupon we just applied
        if($coupon->code != $_POST['coupon_code'] && $coupon->discount_type == $type_to_remove) {
            // Exclude "smart_coupon" type, since users should be able to use as many gift cards as they like
            if($coupon->discount_type !=  'smart_coupon') {
                // Remove all coupons that match the type of the coupon we just applied
                WC()->cart->remove_coupon( $coupon->code );
            }
        }
    }
}

?>
