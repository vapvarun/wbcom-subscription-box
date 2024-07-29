<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Wbcom_Subscription_Box_Product
{

    public function __construct()
    {
        add_action('init', array($this, 'register_subscription_box_product_type'));
        add_filter('product_type_selector', array($this, 'add_subscription_box_product'));
        add_action('woocommerce_before_calculate_totals', array($this, 'adjust_subscription_box_price'));
    }

    public function register_subscription_box_product_type()
    {
        class WC_Product_Subscription_Box extends WC_Product
        {
            public function __construct($product)
            {
                $this->product_type = 'subscription_box';
                parent::__construct($product);
            }
        }
    }

    public function add_subscription_box_product($types)
    {
        $types['subscription_box'] = __('Subscription Box', 'wbcom-subscription-box');
        return $types;
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
                            $cart_item['data']->set_price($subscription_price * 3);
                            break;
                        case 'annually':
                            $cart_item['data']->set_price($subscription_price * 12);
                            break;
                    }
                }
            }
        }
    }
}
