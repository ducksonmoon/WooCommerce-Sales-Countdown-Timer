<?php
/*
Plugin Name: WooCommerce Sales Countdown Timer (Enhanced)
Description: Displays a countdown timer on WooCommerce single product pages, archive pages, and for each on-sale cart item—with fallback and expiration messages.
Version: 1.3
Author: Mehrshad Baqerzadegan (Modified)
Author URI: https://ducksonmoon.ir/
Plugin URI: https://github.com/ducksonmoon/WooCommerce-Sales-Countdown-Timer
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Enqueue styles and scripts.
 */
function wc_sales_countdown_enqueue_scripts() {
    wp_enqueue_style( 'wc-sales-countdown-style', plugins_url( 'css/style.css', __FILE__ ) );
    wp_enqueue_script( 'wc-sales-countdown-js', plugins_url( 'js/countdown.js', __FILE__ ), array( 'jquery' ), '1.3', true );
}
add_action( 'wp_enqueue_scripts', 'wc_sales_countdown_enqueue_scripts' );

/**
 * Display the countdown timer markup for product and archive pages.
 *
 * @param string $location Context identifier: 'single' or 'archive'.
 */
function wc_display_sales_countdown( $location = 'single' ) {
    global $product;
    
    if ( ! $product ) {
        return;
    }

    if ( $product->is_on_sale() ) {
        // Retrieve the sale end timestamp.
        $sale_end = get_post_meta( $product->get_id(), '_sale_price_dates_to', true );
        $gmt_offset = get_option( 'gmt_offset' );
        // Adjust the sale end time to local time.
        $local_time = $sale_end ? ( $sale_end + ( $gmt_offset * HOUR_IN_SECONDS ) ) : false;
        
        // For testing/demonstration you could add a unique offset per product:
        // $local_time = $local_time ? $local_time + ($product->get_id() % 60) : false;
        //
        // Use ISO 8601 format (with a "T") so JavaScript can parse it reliably.
        $formatted_end_date = $local_time ? date( 'Y-m-d\TH:i:s', $local_time ) : '';

        if ( $local_time ) {
            echo '<div class="archive-sales-countdown-container">
                    <div class="archive-sales-countdown-text">
                        <h5 class="red-color">وقت خرید!</h5>
                    </div>
                    <div class="archive-sales-countdown-timer" data-end-date="' . esc_attr( $formatted_end_date ) . '">
                        <div class="countdown-display">
                            <span class="hours">00</span> :
                            <span class="minutes">00</span> :
                            <span class="seconds">00</span>
                        </div>
                    </div>
                  </div>';
        } else {
            echo '<div class="archive-sales-countdown-container">
                    <div class="no-end-date-message">پیشنهاد شگفت انگیز!</div>
                  </div>';
        }
    } else {
        // For non-sale products, output a placeholder to maintain layout consistency.
        echo '<div class="archive-sales-countdown-container no-sale">
                <div class="archive-sales-countdown-placeholder"></div>
              </div>';
    }
}

// Display countdown on single product pages.
// (The hook below is from the WoodMart theme. Adjust if using a different theme.)
add_action( 'woodmart_before_single_product_main_gallery', function() {
    wc_display_sales_countdown( 'single' );
}, 25 );

// Display countdown on archive pages.
add_action( 'woocommerce_before_shop_loop_item_title', function() {
    wc_display_sales_countdown( 'archive' );
}, 10 );

/**
 * Add countdown timer markup to each on-sale cart item.
 */
add_action( 'woocommerce_after_cart_item_name', 'wc_sales_countdown_cart', 10, 2 );
function wc_sales_countdown_cart( $cart_item, $cart_item_key ) {
    $product = $cart_item['data'];

    if ( $product->is_on_sale() ) {
        $sale_end = get_post_meta( $product->get_id(), '_sale_price_dates_to', true );
        $gmt_offset = get_option( 'gmt_offset' );
        $local_time = $sale_end ? ( $sale_end + ( $gmt_offset * HOUR_IN_SECONDS ) ) : false;
        $formatted_end_date = $local_time ? date( 'Y-m-d\TH:i:s', $local_time ) : '';

        if ( $local_time ) {
            echo '<div class="archive-sales-countdown-container">
                    <div class="archive-sales-countdown-text">
                        <h5 class="red-color">وقت خرید!</h5>
                    </div>
                    <div class="archive-sales-countdown-timer" data-end-date="' . esc_attr( $formatted_end_date ) . '">
                        <div class="countdown-display">
                            <span class="hours">00</span> :
                            <span class="minutes">00</span> :
                            <span class="seconds">00</span>
                        </div>
                    </div>
                  </div>';
        } else {
            echo '<div class="archive-sales-countdown-container">
                    <div class="no-end-date-message">پیشنهاد شگفت انگیز!</div>
                  </div>';
        }
    }
}
