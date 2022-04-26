<?php do_action('wp_subsk_content_before'); ?>

<div>
    <select id='wp_subsk_content_select_post_enable' name='wp_subsk_content_select_post_enable'>
        <?php
        global $wp_post_types;
        foreach (apply_filters('wp_subsk_post_type', $wp_post_types) as $post) {
            //echo "<div style='width: 200px; margin:2px;'><input id='$post->name' type='checkbox' /><label for='$post->name'>$post->name</label></div>";
            echo "<option value='$post->name'>$post->name</option>";
        }
        ?>
    </select>
    <button>Agregar</button>
    <div>
        <?php
        $posts = WP_Subsk::get_content('wp_subsk_content_select_post_enable');
        if (!is_array($posts)) {
            $posts = [$posts];
        }
        foreach ($posts as $post) {
            echo "<div>$post</div>";
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