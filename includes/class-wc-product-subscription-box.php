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
}
