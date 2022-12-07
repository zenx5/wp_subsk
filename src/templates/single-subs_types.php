<?php

/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Astra
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
$id_unique = BH_Subsk::get_var_meta('BH_Subsk_id_unique');
$posts_enable = json_decode(get_option('BH_Subsk_selected_post_enable_' . $id_unique), true) ?? [];
$posts_specify = json_decode(get_option('BH_Subsk_selected_post_specify_' . $id_unique), true) ?? [];
$others = [];
$user = wp_get_current_user();

if (isset($_GET['adquirir'])) {
    update_user_meta(
        $user->ID,
        'BH_Subsk_type_subs',
        $post->ID
    );
}

$type_subs = get_user_meta($user->data->ID, 'BH_Subsk_type_subs');
if (count($type_subs) > 0) {
    $type_subs = $type_subs[0];
} else {
    $type_subs = -1;
}

get_header();

?>
<main style="margin:20px 0; width:100%;">
    <h2 style="margin-bottom: 20px;"><?= $post->post_title ?></h2>
    <section style="display:flex; flex-direction: row; width: 100%;">
        <div style="display: flex;flex-direction: column; width:50%;">
            <div style="display: flex;flex-direction: row; align-items:center; width:100%">
                <div style="display:flex; justify-content:center;width:100%;font-size:80px;">
                    <?= BH_Subsk::get_cost() . " " . BH_Subsk::get_currency() ?>
                </div>
                <div style="display: flex;flex-direction: column; justify-content:center; width:100%;">
                    <span style="display:flex; justify-content:center;font-size:100px;"><?= BH_Subsk::get_period() ?></span>
                    <sub style="display:flex; justify-content:center;font-size:50px;top:-25px"><?= BH_Subsk::get_period_format_tag() ?></sub>
                </div>
            </div>
            <?php if (in_array('subscriber', $user->roles) && $type_subs == -1) : ?>
                <a class="button" href="/?post_type=subs_types&p=<?= $post->ID ?>&adquirir" style="text-align: center; font-weight:bold">Adquirir</a>
            <?php elseif (!in_array('administrator', $user->roles)) : ?>
                <i class="button disabled" style="text-align: center; font-weight:bold; color: grey;background-color:lightgray">Suscripcion adquirida</i>
            <?php endif; ?>
        </div>
        <div style="display:flex; justify-content:center;width:50%">
            <ul>
                <?php foreach ($posts_enable as $post2) : ?>
                    <li>
                        <?= $post2 ?>
                        <ul>
                            <?php foreach ($posts_specify as $specify) : ?>
                                <?php if ($specify['post_type'] == $post2) : ?>
                                    <li><?= BH_Subsk::get_post_name($specify['post_type'], $specify['ID'])  ?></li>
                                <?php endif; ?>
                            <?php
                                if (!in_array(!$specify['post_type'], $posts)) {
                                    $is = false;
                                    foreach ($others as $other) {
                                        if (($other['post_type'] == $specify['post_type']) && ($other['ID'] == $specify['ID'])) {
                                            $is = true;
                                        }
                                    }
                                    if ($is) {
                                        $others[] = $specify;
                                    }
                                }
                            endforeach; ?>
                        </ul>
                    </li>
                <?php
                endforeach;
                if (count($others) > 0) : ?>
                    <li> <?= _('Other') ?>:
                        <ul>
                            <?php foreach ($others as $other) : ?>

                                <li style="display:flex;align-items: center;"><?= BH_Subsk::get_post_name($other['post_type'], $other['ID'])  ?> <sub>(<?= $other['post_type'] ?>)</sub></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </section>
    <?php if (in_array('administrator', $user->roles)) : ?>
        <section style="display:flex; flex-direction: row; width: 100%;">
            <?php
            $subscribers = get_users([
                'role' => 'subscriber',
                'meta_key' => 'BH_Subsk_type_subs',
                'meta_value' => $post->ID
            ]);

            $subscribers = apply_filters('wp_subks_get_subscribers', $subscribers);

            if (count($subscribers) > 0) :
            ?>
                <table>
                    <tr>
                        <?php foreach (BH_Subsk::get_field_subscriber() as $field) : ?>
                            <th <?php foreach ($field['attrs_head'] as $attr) {
                                    echo $attr['name'] . "='" . $attr['value'] . "'";
                                } ?>>
                                <?= $field['name_head'] ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                    <?php foreach ($subscribers as $index => $subscriber) : ?>
                        <tr style="text-align: center;">
                            <?php foreach (BH_Subsk::get_field_subscriber() as $field) : ?>
                                <td <?php foreach ($field['attrs'] as $attr) {
                                        echo $attr['name'] . "='" . $attr['value'] . "'";
                                    } ?>>
                                    <?php if ($field['name'] == 'index') : ?>
                                        <?= $index ?>
                                    <?php else : ?>
                                        <?= $subscriber->{$field['name']} ?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </section>
    <?php endif; ?>

</main>
<?php

get_footer();
