 <?php
    global $post;
    
    $woo = class_exists('WooCommerce');
    $create = false;
    if (isset($_POST['BH_Subsk_create_product'])) {
        BH_Subsk::set_var_meta(get_the_ID(), ['BH_Subsk_create_product'], ['1']);
    }
    $create_product = BH_Subsk::get_var_meta('BH_Subsk_create_product');
    if (!$create_product) {
        $create_product = "Crear Producto";
        $products = query_posts( [
            'post_type' => 'product'
        ] );
        foreach( $products as $product ) {
            if( $product->post_title == $post->post_title ) {
                $create = true;
            }
        }

    }

    do_action('BH_Subsk_cost_before');

    
    ?>
 <div class="BH_Subsk_cost_container" id="BH_Subsk_cost_container">
     <table style="width:100%;">
         <tr style="width: 100%;">
             <td style="width:auto">
                 <?= BH_Subsk::get_cost_html() ?>
                 <span name="BH_Subsk_currency" id="BH_Subsk_currency"><?= BH_Subsk::get_currency() ?></span>
             </td>
             <td style="width:20%;">
                 <?php if (!$woo) : ?>
                     <input type="button" style="width:100%;padding:5px;" disabled value="Crear Producto" />
                     <small>Requiere woocommerce</small>
                 <?php else : ?>
                    <?php if(!$create): ?>
                        <input type="submit" style="width:100%;padding:5px;" name="BH_Subsk_create_product" value="Crear Producto" />
                    <?php else : ?>
                        <input type="submit" style="width:100%;padding:5px;" disabled value="Crear Producto" />
                    <?php endif; ?>
                 <?php endif; ?>
             </td>
         </tr>

     </table>
     <input type="hidden" name="BH_Subsk_currency" id="BH_Subsk_currency" required value="<?= BH_Subsk::get_var_meta('BH_Subsk_currency') ?>" />
 </div>
 <?php do_action('BH_Subsk_cost_after'); ?>