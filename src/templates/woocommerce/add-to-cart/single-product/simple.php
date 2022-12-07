<?php

/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

global $product;


if (!$product->is_purchasable()) {
    return;
}

echo wc_get_stock_html($product); // WPCS: XSS ok.

if ($product->is_in_stock()) : ?>

    <?php do_action('woocommerce_before_add_to_cart_form'); ?>

    <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
        <?php do_action('woocommerce_before_add_to_cart_button'); ?>

        <?php
        do_action('woocommerce_before_add_to_cart_quantity');

        woocommerce_quantity_input(
            array(
                'min_value'   => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
            )
        );

        do_action('woocommerce_after_add_to_cart_quantity');
        ?>
        <div id="paypal-button-container"></div>
        <script>
            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '0.01'
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        alert('Transaction completed by ' + details.payer.name.give_name);
                    });
                }
            }).render('#paypal-button-container');
            // This function displays Smart Payment Buttons on your web page.
        </script>
        <?php if ($template == 1) : ?>
            <small style="display: block; padding:5px; border-radius: 4px; background-color: lightgray;">Debe ser suscriptor para adquirir este servicio</small>
        <?php else : ?>
            <?php
            if ($template == 3) :
                $user_id = wp_get_current_user()->ID;
                $sub_id = get_user_meta($user_id, 'BH_Subsk_type_subs');
                if (count($sub_id) > 0) {
                    $sub_id = $sub_id[0];
                    if ($sub_id != -1) {
                        $sub = BH_Subsk::get_sub($sub_id);
            ?>
                        <small style="display: block; padding:15px 5px; border-radius: 4px; color: red;">
                            <i>Actualmente usted posee la suscripcion <b><?= $sub->post_title ?></b></i>
                        </small>
                <?php
                    } else {
                        $template = 2;
                    }
                }
                ?>

            <?php endif; ?>
            <button type="submit" <?= (BH_Subsk::is_sub($product) && BH_Subsk::in_cart()) ? "disabled" : "" ?> name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="single_add_to_cart_button button alt">
                <?php
                if ($template == 2) {
                    echo "Suscribirse";
                } elseif ($template == 3) {
                    if ($sub->post_title == $product->get_name()) {
                        echo "Renovar Suscripcion <br>( <i>" . BH_Subsk::get_time_left($user_id) . " dias restantes </i>)";
                    } else {
                        echo "Cambiar Suscripcion";
                    }
                } else {
                    echo esc_html($product->single_add_to_cart_text());
                }

                ?>
            </button>
        <?php endif; ?>

        <?php do_action('woocommerce_after_add_to_cart_button'); ?>
    </form>

    <?php do_action('woocommerce_after_add_to_cart_form'); ?>

<?php endif; ?>