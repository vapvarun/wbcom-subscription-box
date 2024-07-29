<?php
/**
 * Plugin Name: Wbcom Subscription Box
 * Description: Adds a custom product type called "Subscription Box" to WooCommerce.
 * Version: 1.0.0
 * Author: Wbcom Designs
 * Text Domain: wbcom-subscription-box
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Include the main class.
include_once dirname( __FILE__ ) . '/includes/class-wbcom-subscription-box.php';

// Initialize the plugin.
function wbcom_subscription_box_init() {
    $plugin = new Wbcom_Subscription_Box();
    $plugin->init();
}
add_action( 'plugins_loaded', 'wbcom_subscription_box_init' );
