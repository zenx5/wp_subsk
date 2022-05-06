<?php
do_action('wp_subsk_content_before');


$type_subs = self::get_sub(get_the_ID());
$id_unique = WP_Subsk::get_var_meta('wp_subsk_id_unique');

echo ("<script>console.log(" . json_encode($type_subs) . ")</script>");
$type_subs = $type_subs->post_name;

$posts = json_decode(get_option('wp_subsk_selected_post_enable_' . $id_unique), true) ?? [];
//update_option('wp_subsk_selected_post_specify_' . $id_unique, '[]');
$posts_specify = json_decode(get_option('wp_subsk_selected_post_specify_' . $id_unique), true) ?? [];


?>

<script>
    console.log(<?= json_encode($posts_specify) ?>)
</script>
<div id="wp_subsk_content" style="border-bottom: 1px solid lightgray; margin-bottom: 15px;padding-bottom: 10px;">
    <input type="hidden" name="wp_subsk_type_subs" value="<?= $type_subs ?>" />
    <select id='wp_subsk_content_select_post_enable' name='wp_subsk_content_select_post_enable'>
        <?php
        global $wp_post_types;
        foreach (apply_filters('wp_subsk_post_type_show', $wp_post_types) as $post) {
            if (!in_array($post->name, $posts)) {
                //echo "<div style='width: 200px; margin:2px;'><input id='$post->name' type='checkbox' /><label for='$post->name'>$post->name</label></div>";
                echo "<option value='$post->name'>$post->name</option>";
            }
        }
        ?>
    </select>
    <input type="submit" id="wp_subsk_btn_select_post_enable" name="wp_subsk_btn_select_post_enable" value="Agregar">
    <div class="wp_subsk_content_list_post">
        <?php
        //update_option('wp_subsk_selected_post_enable', "[]");
        // echo get_option('wp_subsk_selected_post_enable');
        // echo "<br>";
        // if (!is_array($posts)) {
        //     $posts = [$posts];
        // }
        foreach ($posts as $post) {
            echo "<div class='container'>
                <span class='name'>$post</span>
                <input type='submit' name='wp_subsk_delete_type_$post' class='btn-del' value='x' />
                </div>";
        }
        ?>
    </div>
</div>

<div id="wp_subsk_content_specify">
    <table style="width:100%">
        <tr style="width:100%">
            <td style="width:40%">
                <select id='wp_subsk_content_select_post_type' name='wp_subsk_content_select_post_type' style="width:100%">
                    <option id="-1">Seleccione tipo de post</option>
                    <?php
                    global $wp_post_types;
                    foreach (apply_filters('wp_subsk_post_type', $wp_post_types) as $post) {
                        //echo "<div style='width: 200px; margin:2px;'><input id='$post->name' type='checkbox' /><label for='$post->name'>$post->name</label></div>";
                        echo "<option value='$post->name'>$post->name</option>";
                    }
                    ?>
                </select>
            </td>
            <td style="width:40%">
                <select id='wp_subsk_content_select_post_specify' name='wp_subsk_content_select_post_specify' style="width:100%">
                    <option>Seleccionar</option>
                </select>
            </td>
            <td style="width:20%">
                <input type="submit" id="wp_subsk_btn_select_post_specify" name="wp_subsk_btn_select_post_specify" value="Agregar" style="width:100%;padding:5px;">
            </td>
        </tr>
    </table>
    <div class="wp_subsk_content_list_post">
        <?php
        foreach ($posts_specify as $index => $post) {
            echo "<div class='container'>
                <div><span class='name'>" . $post['ID'] . " " . self::get_post_name($post['post_type'], $post['ID']) . "</span>
                <sub>{$post['post_type']}</sub></div>
                <input type='submit' name='wp_subsk_delete_specify_post_$index' class='btn-del' value='x' />
                </div>";
        }
        ?>
    </div>
</div>

<?php do_action('wp_subsk_content_after'); ?>