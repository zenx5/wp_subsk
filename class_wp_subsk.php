<?php

require 'class_plugink.php';

class WP_Subsk extends PluginK
{
    public static function active()
    {
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
        add_action('add_meta_boxes_subs_types', array('WP_Subsk', 'create_metas'));
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

    public static function create_metas()
    {
        function content()
        {
            echo "hola";
        }
        add_meta_box('id', 'title', 'content', 'subs_types', 'normal', 'high');
    }
}
