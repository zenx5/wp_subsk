<?php
do_action('wp_subsk_content_before');


$type_subs = self::get_name_sub();
$posts = json_decode(get_option('wp_subsk_selected_post_enable_' . $type_subs), true) ?? [];


?>
<script>
    console.log("<?= $_SERVER['REQUEST_URI'] ?>")
</script>



<div id="wp_subsk_content">
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
<!--div style="display:flex; flex-direction: row; align-items:center; padding-bottom:10px;padding-top:10px;">
    <select id='wp_subsk_content_select_post_type' name='wp_subsk_content_select_post_type'>
        <?php
        global $wp_post_types;
        foreach (apply_filters('wp_subsk_post_type', $wp_post_types) as $post) {
            //echo "<div style='width: 200px; margin:2px;'><input id='$post->name' type='checkbox' /><label for='$post->name'>$post->name</label></div>";
            echo "<option value='$post->name'>$post->name</option>";
        }
        ?>
    </select>
    <select id='wp_subsk_content_select_post_specify' name='wp_subsk_content_select_post_specify'>
        <option>Seleccionar</option>
    </select>
    <button>Agregar</button>
</div-->

<?php do_action('wp_subsk_content_after'); ?>