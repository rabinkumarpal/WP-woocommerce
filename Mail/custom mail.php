<?php
/**
 * You can specify which template mail is to be sent from the following array elements.
 * 'WC_Email_New_Order'
 * 'WC_Email_Cancelled_Order'
 * 'WC_Email_Failed_Order'
 * 'WC_Email_Customer_On_Hold_Order'
 * 'WC_Email_Customer_Processing_Order'
 * 'WC_Email_Customer_Completed_Order'
 * 'WC_Email_Customer_Refunded_Order'
 * 'WC_Email_Customer_Invoice'
 * 'WC_Email_Customer_Note'
 * 'WC_Email_Customer_Reset_Password'
 * 'WC_Email_Customer_New_Account'
 */

function robin_custom_wooemail_headers( $headers, $email_id, $order ) {

    // The order ID | Compatibility with WC version +3
    $order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
    $email = get_post_meta( $order_id, '_approver_email', true );
	
	$payment_method = $order->get_payment_method();

    // Replace the emails below to your desire email
  
	 $emails = array('robinpal@live.com', $email);

    switch( $email_id ) {

        case 'new_order':
			if ($payment_method === 'yith-paypal-ec' || $payment_method == 'creditcard' || $payment_method == 'paypal' ) 
			{
					$headers .= 'Bcc: ' . implode(',', $emails) . "\r\n";
	 		}
			
            break;
     // case 'customer_processing_order':
          //  $headers .= 'Bcc: ' . implode(',', $emails) . "\r\n";
        //    break;
		case 'customer_completed_order':
       // $headers .= 'Bcc: ' . implode(',', $emails) . "\r\n"
        //or
        if ($payment_method == 'cod' || $payment_method == 'bacs'|| $payment_method == 'cheque' || $payment_method === 'yith-paypal-ec') {
					//$headers .= 'Bcc: ' . implode(',', $emails) . "\r\n";

					
					 	// Set the recipeent.
						
						WC()->mailer()->get_emails()['WC_Email_New_Order']->recipient = 'robinpal@live.com';
						
						// Allow resending new order email
						add_filter('woocommerce_new_order_email_allows_resend', '__return_true' );

						// Resend new order email
						WC()->mailer()->get_emails()['WC_Email_New_Order']->trigger( $order_id );

						// Disable resending new order email
						add_filter('woocommerce_new_order_email_allows_resend', '__return_false' );
					
	 			}
			
            break;
        case 'customer_invoice':

        default:
    }

    return $headers;
}

add_filter( 'woocommerce_email_headers', 'robin_custom_wooemail_headers', 10, 3); 
