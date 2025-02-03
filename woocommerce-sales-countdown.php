<?php
/*
Plugin Name: WooCommerce Sales Countdown Timer
Description: Adds a minimal sales countdown timer to WooCommerce product pages.
Version: 1.0
Author: Mehrshad Baqerzadegan
Author URI: https://ducksonmoon.ir/
Plugin URI: https://github.com/ducksonmoon/WooCommerce-Sales-Countdown-Timer
*/

if (!defined('ABSPATH')) {
    exit; // Prevent direct access.
}

// Enqueue styles and scripts.
function wc_sales_countdown_enqueue_scripts() {
    wp_enqueue_style('wc-sales-countdown-style', plugins_url('css/style.css', __FILE__));
    wp_enqueue_script('wc-sales-countdown-js', plugins_url('js/countdown.js', __FILE__), array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'wc_sales_countdown_enqueue_scripts');

// Add Countdown Timer to Single Product Page.
function wc_display_sales_countdown() {
    global $product;

    // Set countdown for products on sale with an end date.
    $sale_end = get_post_meta($product->get_id(), '_sale_price_dates_to', true);

    if ($product->is_on_sale() && $sale_end) {
        // Convert to local time using the site's offset
        $gmt_offset = get_option('gmt_offset'); // Offset in hours
        $local_time = $sale_end + ($gmt_offset * HOUR_IN_SECONDS); // Adjust GMT to local

        // Format as YYYY-MM-DD HH:MM:SS
        $formatted_end_date = date('Y-m-d H:i:s', $local_time);
        echo '<div id="sales-countdown-timer" data-end-date="' . esc_attr($formatted_end_date) . '">
                <span class="countdown-label">پیشنهاد شگفت انگیز</span>

                <div class="countdown-display">
                    <span class="hours">00</span> :
                    <span class="minutes">00</span> :
                    <span class="seconds">00</span>
                </div>
              </div>';
    }
}
add_action('woodmart_before_single_product_main_gallery', 'wc_display_sales_countdown', 25);

// Countdown Timer for Product Archive Pages
function wc_display_sales_countdown_archive() {
    global $product;

    $sale_end = get_post_meta($product->get_id(), '_sale_price_dates_to', true);

    if ($product->is_on_sale() && $sale_end) {
        $gmt_offset = get_option('gmt_offset');
        $local_time = $sale_end + ($gmt_offset * HOUR_IN_SECONDS);
        $formatted_end_date = date('Y-m-d H:i:s', $local_time);

        echo '<div class="archive-sales-countdown-timer" data-end-date="' . esc_attr($formatted_end_date) . '">
        
                <div class="countdown-display">
                    <span class="hours">00</span> :
                    <span class="minutes">00</span> :
                    <span class="seconds">00</span>
                </div>
              </div>';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'wc_display_sales_countdown_archive', 10);
