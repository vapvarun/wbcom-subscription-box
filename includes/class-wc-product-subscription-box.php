<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class WC_Product_Subscription_Box extends WC_Product
{
    public function __construct($product)
    {
        $this->product_type = 'subscription_box';
        parent::__construct($product);
    }

    public function get_price($context = 'view')
    {
        $subscription_frequency = $this->get_meta('subscription_frequency');
        $subscription_price = $this->get_meta('subscription_price');

        if (!empty($subscription_frequency) && !empty($subscription_price)) {
            switch ($subscription_frequency) {
                case 'monthly':
                    return $subscription_price;
                case 'quarterly':
                    return $subscription_price * 3;
                case 'annually':
                    return $subscription_price * 12;
                default:
                    return parent::get_price($context);
            }
        }

        return parent::get_price($context);
    }
}
