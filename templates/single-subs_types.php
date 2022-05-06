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
$id_unique = WP_Subsk::get_var_meta('wp_subsk_id_unique');
$posts_enable = json_decode(get_option('wp_subsk_selected_post_enable_' . $id_unique), true) ?? [];
$posts_specify = json_decode(get_option('wp_subsk_selected_post_specify_' . $id_unique), true) ?? [];
$others = [];
get_header(); ?>

<main style="margin:20px 0; width:100%;">
    <h2 style="margin-bottom: 20px;"><?= $post->post_title ?></h2>
    <section style="display:flex; flex-direction: row; width: 100%;">
        <div style="display: flex;flex-direction: column; width:50%;">
            <div style="display: flex;flex-direction: row; align-items:center; width:100%">
                <div style="display:flex; justify-content:center;width:100%;font-size:80px;">
                    <?= WP_Subsk::get_cost() . " " . WP_Subsk::get_currency() ?>
                </div>
                <div style="display: flex;flex-direction: column; justify-content:center; width:100%;">
                    <span style="display:flex; justify-content:center;font-size:100px;"><?= WP_Subsk::get_period() ?></span>
                    <sub style="display:flex; justify-content:center;font-size:50px;top:-25px"><?= WP_Subsk::get_period_format_tag() ?></sub>
                </div>
            </div>
            <button>Adquirir</button>
        </div>
        <div style="display:flex; justify-content:center;width:50%">
            <ul>
                <?php foreach ($posts_enable as $post) : ?>
                    <li>
                        <?= $post ?>
                        <ul>
                            <?php foreach ($posts_specify as $specify) : ?>
                                <?php if ($specify['post_type'] == $post) : ?>
                                    <li><?= WP_Subsk::get_post_name($specify['post_type'], $specify['ID'])  ?></li>
                                <?php endif; ?>
                            <?php
                                if (!in_array(!$specify['post_type'], $posts)) {
                                    $is = false;
                                    foreach ($others as $other) {
                                        if (($other['post_type'] == $specify['post_type']) && ($other['ID'] == $specify['ID'])) {
                                            $is = true;
                                        }
                                    }
                                    if (!$is) {
                                        $others[] = $specify;
                                    }
                                }
                            endforeach; ?>
                        </ul>
                    </li>
                <?php
                endforeach;
                if (count($others) > 0) : ?>
                    <li> Other:
                        <ul>
                            <?php foreach ($others as $other) : ?>

                                <li style="display:flex;align-items: center;"><?= WP_Subsk::get_post_name($other['post_type'], $other['ID'])  ?> <sub>(<?= $other['post_type'] ?>)</sub></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </section>
    <section style="display:flex; flex-direction: row; width: 100%;">

    </section>
</main>
<?php

get_footer();
