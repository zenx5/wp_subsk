<?php
do_action('BH_Subsk_id_unique_before');
$id_unique = BH_Subsk::get_var_meta('BH_Subsk_id_unique');
if ($id_unique == '') {
    $id_unique = self::generate_id();
}
?>
<div class="BH_Subsk_id_unique_container" id="BH_Subsk_id_unique_container">
    <span class="BH_Subsk_id_unique"><?= $id_unique ?></span>
    <input type="hidden" name="BH_Subsk_id_unique" id="BH_Subsk_id_unique" value="<?= $id_unique ?>" />
</div>
<?php do_action('BH_Subsk_id_unique_after'); ?>