<?php do_action('wp_subsk_cost_before'); ?>
<div>
    <?= WP_Subsk::get_cost_html() ?>
    <span name="wp_subsk_currency" id="wp_subsk_currency"><?= WP_Subsk::get_currency() ?></span>
</div>
<?php do_action('wp_subsk_cost_after'); ?>