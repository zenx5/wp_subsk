 <?php do_action('wp_subsk_cost_before'); ?>
 <div class="wp_subsk_cost_container" id="wp_subsk_cost_container">
     <?= WP_Subsk::get_cost_html() ?>
     <span name="wp_subsk_currency" id="wp_subsk_currency"><?= WP_Subsk::get_currency() ?></span>
     <input type="hidden" name="wp_subsk_currency" id="wp_subsk_currency" value="<?= WP_Subsk::get_var_meta('wp_subsk_currency') ?>" />
 </div>
 <?php do_action('wp_subsk_cost_after'); ?>