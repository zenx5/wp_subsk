 <?php

    $woo = class_exists('WooCommerce');
    $create_product = WP_Subsk::get_var_meta('wp_subsk_create_product');
    if (!$create_product) {
        $create_product = "Crear Producto";
    }

    do_action('wp_subsk_cost_before'); ?>

 <div class="wp_subsk_cost_container" id="wp_subsk_cost_container">
     <table style="width:100%;">
         <tr style="width: 100%;">
             <td style="width:auto">
                 <?= WP_Subsk::get_cost_html() ?>
                 <span name="wp_subsk_currency" id="wp_subsk_currency"><?= WP_Subsk::get_currency() ?></span>
             </td>
             <td style="width:20%;">
                 <?php if (!$woo) : ?>
                     <input type="button" style="width:100%;padding:5px;" disabled value="Crear Producto" />
                     <small>Requiere woocommerce</small>
                 <?php else : ?>
                     <input type="submit" style="width:100%;padding:5px;" name="wp_subsk_create_product" value="<?= $create_product ?>" />
                 <?php endif; ?>
             </td>
         </tr>

     </table>
     <input type="hidden" name="wp_subsk_currency" id="wp_subsk_currency" required value="<?= WP_Subsk::get_var_meta('wp_subsk_currency') ?>" />
 </div>
 <?php do_action('wp_subsk_cost_after'); ?>