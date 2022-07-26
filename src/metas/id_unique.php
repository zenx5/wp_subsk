<?php
do_action('wp_subsk_id_unique_before');
$id_unique = WP_Subsk::get_var_meta('wp_subsk_id_unique');
if ($id_unique == '') {
    $id_unique = self::generate_id();
}
?>
<div class="wp_subsk_id_unique_container" id="wp_subsk_id_unique_container">
    <span class="wp_subsk_id_unique"><?= $id_unique ?></span>
    <input type="hidden" name="wp_subsk_id_unique" id="wp_subsk_id_unique" value="<?= $id_unique ?>" />
</div>
<?php do_action('wp_subsk_id_unique_after'); ?>