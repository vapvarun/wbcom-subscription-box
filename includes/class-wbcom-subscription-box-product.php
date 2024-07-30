<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Include the custom product class
include_once dirname(__FILE__) . '/class-wc-product-subscription-box.php';

class Wbcom_Subscription_Box_Product
{

    public function __construct()
    {
        add_action('init', array($this, 'register_subscription_box_product_type'));
        add_filter('product_type_selector', array($this, 'add_subscription_box_product'));
        add_filter('woocommerce_product_class', array($this, 'woocommerce_product_class'), 10, 2);
        add_filter('woocommerce_is_purchasable', array($this, 'woocommerce_is_purchasable'), 10, 2);
        add_action('woocommerce_before_calculate_totals', array($this, 'adjust_subscription_box_price'));
        add_action('woocommerce_process_product_meta', array($this, 'save_product_type'));

        // Ensure the Add to Cart button is displayed
        add_action('woocommerce_single_product_summary', array($this, 'display_add_to_cart_button'), 30);
    }

    public function register_subscription_box_product_type()
    {
        // Custom product class already included.
    }

    public function add_subscription_box_product($types)
    {
        $types['subscription_box'] = __('Subscription Box', 'wbcom-subscription-box');
        return $types;
    }

    public function woocommerce_product_class($classname, $product_type)
    {
        if ($product_type == 'subscription_box') {
            $classname = 'WC_Product_Subscription_Box';
        }
        return $classname;
    }

    public function woocommerce_is_purchasable($purchasable, $product)
    {
        if ($product->get_type() == 'subscription_box') {
            $purchasable = true;
        }
        return $purchasable;
    }

    public function adjust_subscription_box_price($cart_object)
    {
        foreach ($cart_object->get_cart() as $cart_item_key => $cart_item) {
            if ($cart_item['data']->get_type() == 'subscription_box') {
                $subscription_frequency = get_post_meta($cart_item['product_id'], 'subscription_frequency', true);
                $subscription_price = get_post_meta($cart_item['product_id'], 'subscription_price', true);

                if ($subscription_frequency && $subscription_price) {
                    switch ($subscription_frequency) {
                        case 'monthly':
                            $cart_item['data']->set_price($subscription_price);
                            break;
                        case 'quarterly':
                            $cart_item['data']->set_price($subscription_price);
                            break;
                        case 'annually':
                            $cart_item['data']->set_price($subscription_price);
                            break;
                    }
                }
            }
        }
    }

    public function save_product_type($post_id)
    {
        $product = wc_get_product($post_id);
        if (isset($_POST['product-type']) && $_POST['product-type'] === 'subscription_box') {
            update_post_meta($post_id, '_product_type', 'subscription_box');
        }
    }

    public function display_add_to_cart_button()
    {
        global $product;
        if ($product->get_type() == 'subscription_box') {
            woocommerce_simple_add_to_cart();
        }
    }
}
