<?php
/*
Plugin Name: WooCommerce Sales Countdown Timer (Enhanced)
Description: Adds a minimal sales countdown timer to WooCommerce product and archive pages with fallback and expiration messages.
Version: 1.1
Author: Mehrshad Baqerzadegan (Modified)
Author URI: https://ducksonmoon.ir/
Plugin URI: https://github.com/ducksonmoon/WooCommerce-Sales-Countdown-Timer
*/

if (!defined('ABSPATH')) {
    exit; // Prevent direct access.
}

// Enqueue styles and scripts.
function wc_sales_countdown_enqueue_scripts() {
    wp_enqueue_style('wc-sales-countdown-style', plugins_url('css/style.css', __FILE__));
    wp_enqueue_script('wc-sales-countdown-js', plugins_url('js/countdown.js', __FILE__), array('jquery'), '1.1', true);
}
add_action('wp_enqueue_scripts', 'wc_sales_countdown_enqueue_scripts');

// Function to display countdown with fallback
function wc_display_sales_countdown($location = 'single') {
    global $product;

    $sale_end = get_post_meta($product->get_id(), '_sale_price_dates_to', true);

    if ($product->is_on_sale()) {
        $gmt_offset = get_option('gmt_offset');
        $local_time = $sale_end ? ($sale_end + ($gmt_offset * HOUR_IN_SECONDS)) : false;

        $formatted_end_date = $local_time ? date('Y-m-d H:i:s', $local_time) : '';

        // Display message based on whether there’s a sale end date or not
        $countdown_html = $local_time ? 
            '<div class="archive-sales-countdown-timer" data-end-date="' . esc_attr($formatted_end_date) . '">
                <div class="countdown-display">
                    <span class="days">00</span> :
                    <span class="hours">00</span> :
                    <span class="minutes">00</span> :
                    <span class="seconds">00</span>
                </div>
             </div>' :
            '<div class="no-end-date-message">پیشنهاد شگفت انگیز!</div>';

        echo $local_time ? '<div class="archive-sales-countdown-container">
                <div class="archive-sales-countdown-text">
                    <h5>وقت خرید!</h5>
                </div>
                    <div class="archive-sales-countdown-timer" data-end-date="' . esc_attr($formatted_end_date) . '">
                        <div class="countdown-display">
                            <span class="days">00</span> :
                            <span class="hours">00</span> :
                            <span class="minutes">00</span> :
                            <span class="seconds">00</span>
                        </div>
                    </div>

                </div>' :
                '<div class="archive-sales-countdown-container">
                    <div class="no-end-date-message">پیشنهاد شگفت انگیز!</div>
                </div>';
    }
}

// Display countdown on single product page
add_action('woodmart_before_single_product_main_gallery', function() {
    wc_display_sales_countdown('single');
}, 25);

// Display countdown on archive pages
add_action('woocommerce_before_shop_loop_item_title', function() {
    wc_display_sales_countdown('archive');
}, 10);
?>
