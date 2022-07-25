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
        'wp_subsk_id_unique',
        'wp_subsk_type_control'
    ];

    public static $messages = [
        "not_access" => "<b>sin acceso a este contenido</b>",
        "any_error" => "<b>Hubo un error</b>",
        "not_assign" => "<b>No tiene ningun tipo de suscripcion asignada</b>",
        "not_login" => "<b>Debe hacer login para acceder a este contenido</b>"
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
            "metas_subs_types" => [
                "ID" => "INT NOT NULL AUTO_INCREMENT",
                "id_post" => "INT NOT NULL",
                "name" => "VARCHAR(45) NULL",
                "value" => "VARCHAR(45) NULL",
            ]
        ], ["metas_subs_types" => "ID"]);
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
        //remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
        add_action('woocommerce_single_product_summary', array('WP_Subsk', 'add_to_cart'));
        //add_action('woocommerce_simple_add_to_cart', array('WP_Subsk', 'custom_template'), 10);
        add_action('admin_head', array('WP_Subsk', 'admin_head'));
        add_action('wp_head', array('WP_Subsk', 'wp_head'));
        add_action('add_meta_boxes_subs_types', array('WP_Subsk', 'create_metas'));
        add_action('save_post', array('WP_Subsk', 'set_meta'));
        add_action('publish_post', array('WP_Subsk', 'set_meta'));
        add_action('draft_to_publish', array('WP_Subsk', 'set_meta'));
        add_action('edit_user_profile', array('WP_Subsk', 'edit_profile'));
        add_action('edit_user_profile_update', array('WP_Subsk', 'update_profile'));
        add_action('wp_enqueue_scripts', array('WP_Subsk', 'ajax_url'));
        //add_action('wp_ajax_nopriv_get_publish_posts', array('WP_Subsk', 'get_publish_posts'));
        add_action('wp_ajax_get_publish_posts', array('WP_Subsk', 'get_publish_posts'));
        add_action('the_content', array('WP_Subsk', 'filter_content'));
        //add_action('loop_start', array('WP_Subsk', 'filter_content', 1));
        add_action('woocommerce_edit_account_form_start', array('WP_Subsk', 'add_details_suscription'));

        add_filter('single_template', array('WP_Subsk', 'custom_template'));
        add_filter('template_include', array('WP_Subsk', 'custom_template'), 50);
        add_filter('woocommerce_product_single_add_to_cart_text', array('WP_Subsk', 'add_to_cart_text'));


        add_filter('woocommerce_add_to_cart_quantity', function () {
            return 1;
        }); //array('WP_Subsk', 'unique_subs'));


        //add_action('woocommerce_add_to_cart', array('WP_Subsk', 'add_to_cart'), 10, 6);
        add_action('woocommerce_checkout_order_created', array('WP_Subsk', 'new_subscriber'));

        self::$messages = apply_filters('wp_subsk_update_message', self::$messages);
        self::$var_metas = apply_filters('wp_subsk_update_var_metas', self::$var_metas);

        update_option('paypal_client_id', 'AaLvFXgQ6aYxkNQpECvHTYX5QGygTSWjo1LhzFS-b9WE6jBW4NXTjpTPJ8nIH99dyc_Kl_oKTNglf5pg');
    }

    public static function add_details_suscription()
    {
        $user = wp_get_current_user();

        echo "<script>console.log(" . json_encode($user) . ")</script>";
        if (in_array('subscriber', $user->roles)) :
            if (count(get_user_meta($user->data->ID, 'wp_subsk_type_subs')) != 0) :
?>
                <fieldset style="margin-bottom: 50px; ">
                    <legend>Datos de la Suscripcion</legend>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label>Tipo de Suscripción</label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" disabled value="<?= self::get_sub(get_user_meta($user->data->ID, 'wp_subsk_type_subs')[0])->post_title ?>" />
                        <span>
                            <em>Si necesita cambiar su suscripcion dirijase a la tienda y seleccione entre los diferentes planes disponibles</em>
                        </span>
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                        <label>fecha de Suscripción</label>
                        <input type="date" class="woocommerce-Input woocommerce-Input--text input-text" disabled value="<?= self::get_date_up($user->data->ID) ?>" />
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
                        <label>Dias Restantes</label>
                        <input type="number" class="woocommerce-Input woocommerce-Input--text input-text" disabled value="<?= self::get_time_left($user->data->ID) ?>" />
                    </p>
                </fieldset>
        <?php
            endif;
        endif;
    }

    public static function is_sub($product)
    {
        foreach (self::get_all_sub() as $sub) {
            if ($sub->post_title == $product->get_name()) {
                return true;
            }
        }
        return false;
    }

    public static function in_cart($detail = false)
    {
        $cart_key = array_keys(WC()->cart->cart_contents);
        if (count($cart_key) > 0) {
            $cart_key = $cart_key[0];
            $cart_content = WC()->cart->cart_contents[$cart_key];

            if ($cart_content['quantity'] > 0) {
                $product = new WC_Product($cart_content['product_id']);
                foreach (self::get_all_sub() as $sub) {
                    if ($sub->post_title == $product->get_name()) {
                        if ($detail) return $sub;
                        else return true;
                    }
                }
            }
        }
        return null;
    }

    public static function add_to_cart($cart_item_key, $product_id = 0, $quantity = 1, $variation_id = null, $variation = null, $cart_item_data = null)
    {
        global $product;
        $user = wp_get_current_user();
        $subs = self::get_all_sub();
        $type_subs = get_user_meta($user->ID, 'wp_subsk_type_subs');
        if ($type_subs && count($type_subs) > 0) {
            $currentSub = self::get_sub($type_subs[0]);
        }
        if (!$product && isset($_POST['add-to-cart'])) {
            $cart_key = array_keys(WC()->cart->cart_contents)[0];
            $cart_content = WC()->cart->cart_contents[$cart_key];
            $product_id = $_POST['add-to-cart'];
            $product = new WC_Product($product_id);
            //Si la suscripcion actual es igual a la agregada en el carrito
            if ($currentSub->post_title == $product->get_name()) {
                self::set_subscriber($user->ID, $currentSub->ID);
            }
        }
        //echo json_encode($subs);
        //echo json_encode($type_subs);
        $name = $product->get_name();

        $template = 1; //debe ser suscriptor para adquirir el producto
        foreach ($subs as $sub) {
            if ($sub->post_title == $name) {
                if (in_array('subscriber', $user->roles)) {
                    if (count($type_subs) == 0) {
                        $template = 2; //Suscribirse
                    } else {
                        $template = 3; //Cambiar Suscripcion
                    }
                }
            }
        }
        include 'templates/woocommerce/add-to-cart/single-product/simple.php';
    }

    public static function add_to_cart_text($text)
    {
        global $product;
        $name = $product->get_name();
        $subs = self::get_all_sub();
        $user = wp_get_current_user();

        foreach ($subs as $sub) {
            if ($sub->post_title == $name) {
                if (in_array('subscriber', $user->roles)) {
                    $type_subs = get_user_meta($user->data->ID, 'wp_subsk_type_subs');
                    if (count($type_subs) == 0) {
                        return 'Suscribirse';
                    } else {
                        return 'Cambiar Suscripcion';
                    }
                }
            }
        }
        return $text;
    }

    public static function get_publish_posts()
    {
        $posts = new WP_Query(array(
            "post_type" => $_POST['post_type'],
            "post_per_page" => -1
        ));

        echo json_encode(array(
            "post_type" => $_POST['post_type'],
            "posts" => $posts->posts
        ));
        wp_die();
    }
    public static function ajax_url()
    {
        //wp_register_script('wp_subsk_script_js', get_stylesheet_directory_uri() . '/admin/js/publish_posts.js', ['jquery'], 1, true);
        wp_enqueue_script('wp_subsk_script_js', '/admin/js/publish_posts.js', ['jquery'], 1, true);
        wp_localize_script('wp_subsk_script_js', 'wp_subsk_ajax', ['url' => admin_url('admin-ajax.php')]);
    }

    public static function wp_head()
    {
        ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- <script src="https://www.paypal.com/sdk/js?client-id=<?= get_option('paypal_client_id') ?>"></script> -->
    <?php
    }

    public static function admin_head()
    {
    ?>
        <style>
            <?php include 'admin/style.css'; ?>
        </style>
        <script>
            (function($) {
                $(document).ready(function() {
                    console.log("READY!!")
                    $('#wp_subsk_content_select_post_type').change(function(ev) {
                        const post_type = ev.target.value;
                        console.log(post_type)
                        $.ajax({
                            type: 'post',
                            url: ajaxurl,
                            data: {
                                action: 'get_publish_posts',
                                post_type: post_type
                            },
                            beforeSend: function() {
                                console.log('Sending....')
                            },
                            success: function(response) {
                                response = JSON.parse(response)
                                console.log(response)
                                let options = "<option value='-1'>Seleccionar</option>"
                                response.posts.forEach(post => {
                                    options += "<option value='" + post.ID + "'>" + post.post_name + "</option>"
                                })
                                $('#wp_subsk_content_select_post_specify').html(options);
                            }
                        })
                    })
                })

            })(jQuery);
        </script>
<?php
    }


    public static function get_type_sub($id)
    {
        $type_subs = get_user_meta($id, 'wp_subsk_type_subs');
        if (count($type_subs) > 0) {
            return $type_subs[0];
        }
        return -1;
    }

    public static function get_date_up($id)
    {
        $dateup = get_user_meta($id, 'wp_subsk_date_up');
        if (count($dateup) > 0) {
            return $dateup[0];
        }
        return null;
    }

    public static function get_time_left($id)
    {
        $now = new DateTime();
        $diff = $now->diff(new DateTime(self::get_date_up($id)));
        $period = self::get_var_meta('wp_subsk_period', self::get_type_sub($id)) ? self::get_var_meta('wp_subsk_period', self::get_type_sub($id)) : 0;
        return $period - $diff->days;
    }

    public static function edit_profile($user)
    {
        include 'admin/edit_profile.php';
    }

    public static function update_profile($user_id)
    {
        include 'admin/update_profile.php';
    }

    public static function new_subscriber($order)
    {
        if (self::in_cart()) {
            $sub = self::in_cart(true);
            self::set_subscriber($order->get_customer_id(), $sub->ID);
        }
    }


    public static function set_subscriber($id_user, $id_subscribtion, $date = null)
    {
        if (!$date) {
            $date = new DateTime();
        }

        update_user_meta(
            $id_user,
            'wp_subsk_type_subs',
            $id_subscribtion
        );
        update_user_meta(
            $id_user,
            'wp_subsk_date_up',
            $date->format('Y-m-d')
        );
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
            "p" => $id,
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


    public static function get_period_format_tag()
    {
        $tag = "";
        $ratio = self::get_period_format();
        $options = self::get_period_format_options();
        foreach ($options as $option) {
            if ($option['ratio'] == $ratio) {
                $tag = $option['tag'];
            }
        }
        return apply_filters('wp_subsk_period_format_tag', $tag);
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
            'public'       => true,
            'can_export'   => false,
            'show_ui'      => true,
            'show_in_menu' => true,
            'query_var'    => false,
            'rewrite'      => false,
            'has_archive'  => false,
            'hierarchical' => false,
            'supports'     => array('title'),
            'show_in_nav_menus' => false,
        ]);

        self::pay_check();
    }

    public static function pay_check()
    {
    }

    public static function message_error($tag_msg)
    {
        $tag = apply_filters('wp_subsk_message_tag', $tag_msg);
        return apply_filters('wp_subsk_message_error', self::$messages[$tag]);
    }

    public static function filter_content($content)
    {
        //die(json_encode(is_woocommerce()));
        if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) return $content;
        //usuario activo
        $user = wp_get_current_user();
        //el usuario activo es 'subscriber'

        if (in_array('subscriber', $user->roles)) {
            //tipo de suscripcion
            $type_subs = get_user_meta($user->data->ID, 'wp_subsk_type_subs');
            $timeleft = WP_Subsk::get_time_left($user->data->ID);
            //si posee un tipo de suscripcion
            if ((count($type_subs) > 0) && ($timeleft > 0)) {
                $type_subs = $type_subs[0];
                $post_id = get_the_ID();
                try {
                    if ($post_id) {
                        if (is_page()) {
                            $post = new WP_Query([
                                'page_id' => $post_id
                            ]);
                        } else {
                            $post = new WP_Query([
                                'p' => $post_id
                            ]);
                        }
                        $id_unique = self::get_var_meta('wp_subsk_id_unique');
                        $posts = json_decode(get_option('wp_subsk_selected_post_enable_' . $id_unique), true) ?? [];
                        $post_type = get_post_type();
                        if (in_array($post_type, $posts)) {
                            $posts_specify = json_decode(get_option('wp_subsk_selected_post_specify_' . $id_unique), true) ?? [];
                            $type_control = self::get_var_meta('wp_subsk_type_control') ?? "1";
                            $ID = $post->post ? $post->post->ID : -1;
                            foreach ($posts_specify as $post) {
                                if (($post['ID'] == $ID) && ($post['post_type'] == $post_type) && $type_control) {
                                    return self::message_error('not_access');
                                }
                            }
                        } else {
                            $posts_specify = json_decode(get_option('wp_subsk_selected_post_specify_' . $id_unique), true) ?? [];
                            $type_control = self::get_var_meta('wp_subsk_type_control') ?? "1";
                            $ID = $post->post->ID;
                            foreach ($posts_specify as $post) {
                                if (($post['ID'] == $ID) && ($post['post_type'] == $post_type) && $type_control) {
                                    return self::message_error('not_access');
                                }
                            }
                            return self::message_error('not_access');
                        }
                    } else {
                        return self::message_error('any_error');
                    }
                } catch (Exception $err) {
                    return json_encode($err);
                }
            } else {
                return self::message_error('not_assign');
            }
        }else if( count($user->roles) == 0 ){
            //getting current post
            if (is_page()) {
                $post = new WP_Query([
                    'page_id' => get_the_ID()
                ]);
            } else {
                $post = new WP_Query([
                    'p' => get_the_ID()
                ]);
            }

            $current_title = $post->posts[0]->post_title;
            $current_type = $post->posts[0]->post_type;

            
            //getting all subscription
            $post_subs_query = new WP_Query([
                "post_type" => "subs_types",
                "post_per_page" => -1
            ]);
            $post_subs = $post_subs_query->posts;
            //mapping of subscription
            foreach( $post_subs as $sub ){
                $id_unique = self::get_var_meta('wp_subsk_id_unique',$sub->ID);
                $type_control = self::get_var_meta('wp_subsk_type_control',$sub->ID) ?? "1";
                $bySpecify = json_decode( get_option('wp_subsk_selected_post_specify_' . $id_unique) );
                $byType = json_decode( get_option('wp_subsk_selected_post_enable_' . $id_unique) );
                
                if( $bySpecify ){
                    foreach( $bySpecify as $specify ){
                        if ( 'page' === $specify->post_type ) {
                            $detail = new WP_Query([
                                'page_id' => $specify->ID
                            ]);
                        } else {
                            $detail = new WP_Query([
                                'p' => $specify->ID,
                                'post_type' => $specify->post_type
                            ]);
                        }
                        if( 
                            ($detail->posts[0]->post_title == $current_title) &&
                            ($detail->posts[0]->post_type == $current_type) &&
                            ($type_control == '0')
                        ) {
                            return self::message_error('not_login');
                        }
                    }
                }
                if( $byType ){
                    foreach( $byType as $type ){
                        if( 
                            ($type == $current_type) &&
                            ($type_control == '0')
                        ) {
                            return self::message_error('not_login');
                        }
                    }
                }
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
            $new_content = $_POST['wp_subsk_content_select_post_enable'];
            $posts = json_decode(get_option('wp_subsk_selected_post_enable_' . $id_unique), true) ?? [];
            $posts[] = $new_content;
            update_option('wp_subsk_selected_post_enable_' . $id_unique, json_encode($posts));
        } elseif (isset($_POST['wp_subsk_btn_select_post_specify'])) {
            $id_unique = $_POST['wp_subsk_id_unique'];
            if ($id_unique == '') {
                $id_unique = self::generate_id();
            }
            $id_post = $_POST['wp_subsk_content_select_post_specify'];
            $post_type = $_POST['wp_subsk_content_select_post_type'];
            $posts = json_decode(get_option('wp_subsk_selected_post_specify_' . $id_unique), true) ?? [];
            $posts[] = [
                'ID' => $id_post,
                'post_type' => $post_type
            ];
            update_option('wp_subsk_selected_post_specify_' . $id_unique, json_encode($posts));
        } elseif (isset($_POST['wp_subsk_create_product'])) {
            include 'admin/create_product.php';
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
        $posts_filtered = [];
        foreach ($posts as $post) {
            if (!isset($_POST['wp_subsk_delete_type_' . $post])) {
                $posts_filtered[] = $post;
            } else {
                $is_delete = true;
            }
        }
        if ($is_delete) {
            update_option('wp_subsk_selected_post_enable_' . $id_unique, json_encode($posts_filtered));
        }

        $is_delete = false;
        $posts_specify = json_decode(get_option('wp_subsk_selected_post_specify_' . $id_unique), true) ?? [];
        $count = count($posts_specify);
        $posts_filtered = [];
        for ($i = 0; $i < $count; $i++) {
            if (!isset($_POST['wp_subsk_delete_specify_post_' . $i])) {
                $posts_filtered[] = $posts_specify[$i];
            } else {
                $is_delete = true;
            }
        }
        if ($is_delete) {
            update_option('wp_subsk_selected_post_specify_' . $id_unique, json_encode($posts_filtered));
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
            ]

        ]);
    }

    public static function custom_template($template)
    {
        if (is_single()) {
            if ('subs_types' === get_post_type()) {
                return self::get_template('templates/single-subs_types.php');
            }
            if ('product' === get_post_type()) {
                //return self::get_template('templates/single-subs_types.php');
                return self::get_template('templates/woocommerce/single-product.php');
            }
        }
        return $template;
    }


    public static function get_field_subscriber()
    {
        return apply_filters('wp_subsk_field_suscriber_showed', [
            [
                "name" => "index",
                "attrs" => [
                    ["name" => "style", "value" => "text-align:center"]
                ],
                "attrs_head"  => [],
                "name_head" => "#"
            ],
            [
                "name" => "user_nicename",
                "attrs" => [
                    ["name" => "style", "value" => "text-align:center"]
                ],
                "attrs_head"  => [],
                "name_head" => "User"
            ],
            [
                "name" => "display_name",
                "attrs" => [
                    ["name" => "style", "value" => "text-align:center"]
                ],
                "attrs_head"  => [],
                "name_head" => "Name"
            ],
            [
                "name" => "user_email",
                "attrs" => [
                    ["name" => "style", "value" => "text-align:center"]
                ],
                "attrs_head"  => [],
                "name_head" => "Email"
            ]
        ]);
    }
}
