<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Wbcom_Subscription_Box
{

    public function init()
    {
        // Include required classes.
        include_once dirname(__FILE__) . '/class-wbcom-subscription-box-product.php';
        include_once dirname(__FILE__) . '/class-wbcom-subscription-box-admin.php';

        // Initialize classes.
        new Wbcom_Subscription_Box_Product();
        new Wbcom_Subscription_Box_Admin();
    }
}
