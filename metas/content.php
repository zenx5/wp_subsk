<?php do_action('wp_subsk_content_before'); ?>

<div style="border-bottom: 1px solid grey; padding-bottom:10px;padding-top:10px;">
    <select>
        <?php
        global $wp_post_types;
        foreach ($wp_post_types as $post) {
            //echo "<div style='width: 200px; margin:2px;'><input id='$post->name' type='checkbox' /><label for='$post->name'>$post->name</label></div>";
            echo "<option value='$post->name'>$post->name</option>";
        }
        ?>
    </select>
    <button>Agregar</button>
    <div>
        <?php
        foreach (WP_Subsk::get_content('post_type') as $post) {
            echo "<div>$post</div>";
        }
        ?>
    </div>
</div>
<div style="display:flex; flex-direction: row; align-items:center; padding-bottom:10px;padding-top:10px;">
    <select>
        <?php
        global $wp_post_types;
        foreach ($wp_post_types as $post) {
            //echo "<div style='width: 200px; margin:2px;'><input id='$post->name' type='checkbox' /><label for='$post->name'>$post->name</label></div>";
            echo "<option value='$post->name'>$post->name</option>";
        }
        ?>
    </select>
    <select>
        <?php
        global $wp_post_types;
        foreach ($wp_post_types as $post) {
            //echo "<div style='width: 200px; margin:2px;'><input id='$post->name' type='checkbox' /><label for='$post->name'>$post->name</label></div>";
            echo "<option value='$post->name'>$post->name</option>";
        }
        ?>
    </select>

</div>

<?php do_action('wp_subsk_content_after'); ?>