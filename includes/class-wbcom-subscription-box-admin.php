<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Wbcom_Subscription_Box_Admin
{

    public function __construct()
    {
        add_action('woocommerce_product_options_general_product_data', array($this, 'subscription_box_custom_fields'));
        add_action('woocommerce_process_product_meta', array($this, 'save_subscription_box_custom_fields'));
        add_action('woocommerce_single_product_summary', array($this, 'display_subscription_box_custom_fields'), 25);
    }

    public function subscription_box_custom_fields()
    {
        global $woocommerce, $post;

        echo '<div class="options_group">';

        // Subscription Frequency
        woocommerce_wp_select(
            array(
                'id'          => 'subscription_frequency',
                'label'       => __('Subscription Frequency', 'wbcom-subscription-box'),
                'options'     => array(
                    'monthly'   => __('Monthly', 'wbcom-subscription-box'),
                    'quarterly' => __('Quarterly', 'wbcom-subscription-box'),
                    'annually'  => __('Annually', 'wbcom-subscription-box')
                )
            )
        );

        // Number of Items in Box
        woocommerce_wp_text_input(
            array(
                'id'          => 'number_of_items',
                'label'       => __('Number of Items', 'wbcom-subscription-box'),
                'placeholder' => 'Enter number of items in the box',
                'desc_tip'    => 'true',
                'description' => __('Specify the number of items that will be included in each subscription box.', 'wbcom-subscription-box'),
                'type'        => 'number',
                'custom_attributes' => array(
                    'min' => '1',
                    'step' => '1'
                )
            )
        );

        // Different Pricing
        woocommerce_wp_text_input(
            array(
                'id'          => 'subscription_price',
                'label'       => __('Subscription Price', 'wbcom-subscription-box'),
                'placeholder' => 'Enter price based on subscription period',
                'desc_tip'    => 'true',
                'description' => __('Specify the price for each subscription period.', 'wbcom-subscription-box'),
                'type'        => 'text'
            )
        );

        echo '</div>';
    }

    public function save_subscription_box_custom_fields($post_id)
    {
        $subscription_frequency = isset($_POST['subscription_frequency']) ? sanitize_text_field($_POST['subscription_frequency']) : '';
        if (!empty($subscription_frequency)) {
            update_post_meta($post_id, 'subscription_frequency', esc_attr($subscription_frequency));
        }

        $number_of_items = isset($_POST['number_of_items']) ? sanitize_text_field($_POST['number_of_items']) : '';
        if (!empty($number_of_items)) {
            update_post_meta($post_id, 'number_of_items', esc_attr($number_of_items));
        }

        $subscription_price = isset($_POST['subscription_price']) ? sanitize_text_field($_POST['subscription_price']) : '';
        if (!empty($subscription_price)) {
            update_post_meta($post_id, 'subscription_price', esc_attr($subscription_price));
        }
    }

    public function display_subscription_box_custom_fields()
    {
        global $post;

        $product = wc_get_product($post->ID);
        $subscription_frequency = get_post_meta($post->ID, 'subscription_frequency', true);
        $number_of_items = get_post_meta($post->ID, 'number_of_items', true);
        $subscription_price = get_post_meta($post->ID, 'subscription_price', true);

        if ($product->get_type() == 'subscription_box') {
            echo '<div class="subscription-box-details">';
            echo '<p>' . __('Subscription Frequency: ', 'wbcom-subscription-box') . $subscription_frequency . '</p>';
            echo '<p>' . __('Number of Items in Box: ', 'wbcom-subscription-box') . $number_of_items . '</p>';
            echo '<p>' . __('Subscription Price: ', 'wbcom-subscription-box') . $subscription_price . '</p>';
            echo '</div>';
        }
    }
}
