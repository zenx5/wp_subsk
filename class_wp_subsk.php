<?php

require 'class_plugink.php';
require 'class_formk.php';

class WP_Subsk extends PluginK
{
    public static $var_metas = [
        'wp_subsk_cost',
        'wp_subsk_currency',
        'wp_subsk_content_select_post_enable',
        'wp_subsk_period',
        'wp_subsk_period_format',
        'wp_subsk_id_unique'
    ];

    public static $messages = [
        "not_access" => "<b>sin acceso a este contenido</b>",
        "any_error" => "<b>Hubo un error</b>",
        "not_assign" => "<b>No tiene ningun tipo de suscripcion asignada</b>"
    ];

    public static function active()
    {
        /*
            meta_subs_type
                ID
                id_post
                name
                value
        */

        self::create_db([
            "metas_subs_type" => [
                "ID" => "INT NOT NULL AUTO_INCREMENT",
                "id_post" => "INT NOT NULL",
                "name" => "VARCHAR(45) NULL",
                "value" => "VARCHAR(45) NULL",
            ]
        ], ["metas_subs_type" => "ID"]);
    }

    public static function deactive()
    {
    }

    public static function uninstall()
    {
    }

    public static function init()
    {
        add_action('init', array('WP_Subsk', 'create_subs_type'));
        add_action('admin_head', array('WP_Subsk', 'admin_head'));
        add_action('add_meta_boxes_subs_types', array('WP_Subsk', 'create_metas'));
        add_action('save_post', array('WP_Subsk', 'set_meta'));
        add_action('publish_post', array('WP_Subsk', 'set_meta'));
        add_action('draft_to_publish', array('WP_Subsk', 'set_meta'));
        add_action('edit_user_profile', array('WP_Subsk', 'edit_profile'));
        add_action('edit_user_profile_update', array('WP_Subsk', 'update_profile'));
        add_filter('the_content', array('WP_Subsk', 'filter_content'));

        self::$messages = apply_filters('wp_subsk_update_message', self::$messages);
        self::$var_metas = apply_filters('wp_subsk_update_var_metas', self::$var_metas);
    }

    public static function admin_head()
    {
?>
        <style>
            <?php include 'admin/style.css'; ?>
        </style>
        <?php
    }

    public static function edit_profile($user)
    {
        $currentUser = wp_get_current_user();

        if (in_array('subscriber', $user->roles) && in_array('administrator', $currentUser->roles)) :
            $type_subs = get_user_meta($user->data->ID, 'wp_subsk_type_subs');
            if (count($type_subs) > 0) {
                $type_subs = $type_subs[0];
            } else {
                $type_subs = -1;
            }
        ?>
            <h3>Tipo de suscripcion</h3>
            <script>
                console.log(<?= json_encode($type_subs) ?>)
            </script>
            <table class="form-table">
                <tr>
                    <th>
                        <label for="wp_subsk_type_subs">Suscripcion: </label>
                    </th>
                    <td>
                        <select id="wp_subsk_type_subs" name="wp_subsk_type_subs" class="regular-text">
                            <option value="-1">Sin suscripcion</option>
                            <?php foreach (self::get_all_sub() as $sub) : ?>
                                <?php if ($sub->post_status == 'publish') : ?>
                                    <option value="<?= $sub->ID ?>" <?= ($type_subs == $sub->ID) ? 'selected' : ''; ?>><?= $sub->post_name ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <p class="description">algo aqui sobre las descripciones</p>
                    </td>
                </tr>
            </table>
<?php
        endif;
    }

    public static function update_profile($user_id)
    {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }
        if (isset($_POST['wp_subsk_type_subs'])) {
            return update_user_meta(
                $user_id,
                'wp_subsk_type_subs',
                $_POST['wp_subsk_type_subs']
            );
        }
        return false;
    }

    public static function get_all_sub()
    {
        $post = new WP_Query([
            "post_type" => 'subs_types',
            'posts_per_page' => -1
        ]);
        return $post->posts;
    }

    public static function get_sub($id)
    {
        $post = new WP_Query([
            "ID" => $id,
            "post_type" => 'subs_types'
        ]);
        //$_SERVER['REQUEST_URI']
        return $post->post;
    }



    public static function get_value($id, $name)
    {
        global $wpdb;
        $sql = "SELECT value FROM `{$wpdb->prefix}metas_subs_type` WHERE name=`$name` AND post_id=$id";
        $wpdb->get_results($sql);
    }

    public static function get_currency()
    {
        $currency = WP_Subsk::get_var_meta('wp_subsk_currency') | '$';
        $currency = apply_filters('wp_subsk_currency', $currency);
        return $currency;
    }

    public static function get_period()
    {
        $period = self::get_var_meta('wp_subsk_period');
        $period = apply_filters('wp_subsk_period', $period);
        return $period;
    }

    public static function get_period_min()
    {
        $period = 0;
        $period = apply_filters('wp_subsk_period_min', $period);
        return $period;
    }

    public static function get_period_max()
    {
        $period = 120;
        $period = apply_filters('wp_subsk_period_max', $period);
        return $period;
    }

    public static function get_period_step()
    {
        $period = 1;
        $period = apply_filters('wp_subsk_period_step', $period);
        return $period;
    }

    public static function get_period_format()
    {
        $format = self::get_var_meta('wp_subsk_period_format');
        $format = apply_filters('wp_subsk_period_format', $format);
        return $format;
    }



    public static function get_period_format_options()
    {
        $format = [
            ['tag' => 'dias', 'ratio' => 1],
            ['tag' => 'meses', 'ratio' => 30],
            ['tag' => 'años', 'ratio' => 365]
        ];
        $format = apply_filters('wp_subsk_period_format_options', $format);
        return $format;
    }

    public static function get_period_html()
    {
        $format = self::get_period_format();
        $step = self::get_period_step();
        $max = self::get_period_max();
        $period = self::get_period();
        $format_options = self::get_period_format_options();
        $options = "";
        foreach ($format_options as $option) {
            $selected = ($option['ratio'] == $format) ? 'selected' : '';
            $options .= "<option value='{$option['ratio']}' $selected>{$option['tag']}</option>";
        }
        return "<input type='number' name='wp_subsk_period' id='wp_subsk_period' value='$period' min='0' max='$max' step='$step' /> <select name='wp_subsk_period_format' id='wp_subsk_period_format'>$options</select>";
    }


    public static function get_cost()
    {
        $cost = self::get_var_meta('wp_subsk_cost');
        $cost = apply_filters('wp_subsk_cost', $cost);
        return $cost;
    }

    public static function get_cost_min()
    {
        $cost = 0;
        $cost = apply_filters('wp_subsk_cost_min', $cost);
        return $cost;
    }
    public static function get_cost_max()
    {
        $cost = null;
        $cost = apply_filters('wp_subsk_cost_max', $cost);
        return $cost;
    }

    public static function get_cost_step()
    {
        $cost = 0.1;
        $cost = apply_filters('wp_subsk_cost_step', $cost);
        return $cost;
    }

    public static function get_cost_html()
    {
        $cost = self::get_cost();
        $min = self::get_cost_min();
        $max = self::get_cost_max();
        $step = self::get_cost_step();
        return "<input type='number' name='wp_subsk_cost' id='wp_subsk_cost' value='$cost' min='$min' max='$max' step='$step' />";
    }

    public static function create_subs_type()
    {
        self::create_type_post('subs_types', 'Tipo de Subscriptor', 'Tipos de subscriptores', [
            'description' => 'Define los diferentes niveles de subscriptores que se manejaran el site',
            'public'       => false,
            'can_export'   => false,
            'show_ui'      => true,
            'show_in_menu' => true,
            'query_var'    => false,
            'rewrite'      => false,
            'has_archive'  => false,
            'hierarchical' => false,
            'supports'     => array('title'),
            //'menu_icon'    => pods_svg_icon('pods'),
            //'menu_position' => 5,
            'show_in_nav_menus' => false,
        ]);
    }

    public static function message_error($tag_msg)
    {
        $tag = apply_filters('wp_subsk_message_tag', $tag_msg);
        return apply_filters('wp_subsk_message_error', self::$messages[$tag]);
    }


    public static function filter_content($content)
    {
        //usuario activo
        $user = wp_get_current_user();
        //el usuario activo es 'subscriber'

        if (in_array('subscriber', $user->roles)) {
            //tipo de suscripcion
            $type_subs = get_user_meta($user->data->ID, 'wp_subsk_type_subs');
            //si posee un tipo de suscripcion
            if (count($type_subs) > 0) {
                $type_subs = $type_subs[0];
                $post_id = get_the_ID();
                if ($post_id) {
                    $post = new WP_Query([
                        'ID' => $post_id
                    ]);
                    $id_unique = WP_Subsk::get_var_meta('wp_subsk_id_unique', $type_subs);
                    $posts = json_decode(get_option('wp_subsk_selected_post_enable_' . $id_unique), true) ?? [];
                    $post_type = $post->post->post_type;
                    if (!in_array($post_type, $posts)) {
                        return self::message_error('not_access');
                    }
                } else {
                    return self::message_error('any_error');
                }
            } else {
                return self::message_error('not_assign');
            }
        }
        return $content;
    }

    public static function generate_id()
    {
        $id_unique = '';
        do {
            $regenerate = false;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            for ($i = 0; $i < 10; $i++) {
                $id_unique .= $characters[rand(0, $charactersLength - 1)];
            }
            $subs = self::get_all_sub();
            foreach ($subs as $sub) {
                $id = self::get_var_meta('wp_subsk_id_unique', $sub->ID);
                if ($id == $id_unique) {
                    $regenerate = true;
                }
            }
        } while ($regenerate);
        return $id_unique;
    }

    public static function set_meta($post_id, $post = null)
    {
        if (isset($_POST['wp_subsk_btn_select_post_enable'])) {
            $id_unique = $_POST['wp_subsk_id_unique'];
            if ($id_unique == '') {
                $id_unique = self::generate_id();
            }
            $type_subs = $_POST['wp_subsk_type_subs'];
            $new_content = $_POST['wp_subsk_content_select_post_enable'];
            echo $new_content;
            $posts = json_decode(get_option('wp_subsk_selected_post_enable_' . $id_unique), true) ?? [];
            $posts[] = $new_content;
            update_option('wp_subsk_selected_post_enable_' . $id_unique, json_encode($posts));
        } else {
            self::if_delete_any_post();
            self::set_var_meta($post_id, WP_Subsk::$var_metas);
        }
    }

    public static function if_delete_any_post()
    {
        $id_unique = isset($_POST['wp_subsk_id_unique']) ? $_POST['wp_subsk_id_unique'] : null;
        if (!$id_unique) {
            return;
        }
        $is_delete = false;
        $posts = json_decode(get_option('wp_subsk_selected_post_enable_' . $id_unique), true) ?? [];
        echo (json_encode($posts)) . "<br>";
        $posts_filtered = [];
        foreach ($posts as $post) {
            echo $post . "<br>";
            if (!isset($_POST['wp_subsk_delete_type_' . $post])) {
                $posts_filtered[] = $post;
            } else {
                $is_delete = true;
            }
        };
        if ($is_delete) {
            update_option('wp_subsk_selected_post_enable_' . $id_unique, json_encode($posts_filtered));
        }
    }

    public static function create_metas()
    {

        self::create_meta('subs_types', [
            [
                'title' => 'Identificador Unico',
                'render_callback' => function () {
                    include 'metas/id_unique.php';
                }
            ],
            [
                'title' => 'Precio',
                'render_callback' => function () {
                    include 'metas/cost.php';
                }
            ],
            [
                'title' => 'Periodo',
                'render_callback' => function () {
                    include 'metas/period.php';
                }
            ],
            [
                'title' => 'Post Type Permitido',
                'render_callback' => function () {
                    include 'metas/content.php';
                }
            ],
            // [
            //     'title' => 'Accesos Especiales',
            //     'render_callback' => function () {
            //         include 'metas/access.php';
            //     }
            // ]

        ]);
    }
}
