<?php

require 'class_plugink.php';
require 'class_formk.php';

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
        add_action('admin_head', array('WP_Subsk', 'insert_uicomponents'));
        add_action('add_meta_boxes_subs_types', array('WP_Subsk', 'create_metas'));
    }

    public static function insert_uicomponents()
    {
        echo "<script>";
        include 'uicomponents/currency.js';
        echo "</script>";
    }

    public static function get_currency()
    {
        $currency = "$";
        $currency = apply_filters('wp_subsk_currency', $currency);
        return $currency;
    }

    public static function get_period()
    {
        $period = 0;
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
        $format = 'dias';
        $format = apply_filters('wp_subsk_period_format', $format);
        return $format;
    }

    public static function get_period_html()
    {
        $format = self::get_period_format();
        $step = self::get_period_step();
        $max = self::get_period_max();
        $period = self::get_period();
        return "<input type='number' style='width: 25%;' name='wp_subsk_cost' id='wp_subsk_cost' value='$period' min='0' max='$max' step='$step' /> $format";
    }


    public static function get_cost()
    {
        $cost = 0;
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
        return "<input type='number' style='width: 25%;' name='wp_subsk_cost' id='wp_subsk_cost' value='$cost' min='$min' max='$max' step='$step' />";
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

        self::create_meta([
            [
                'title' => 'Precio',
                'render_callback' => function () {
                    include 'metas/costo.php';
                }
            ],
            [
                'title' => 'Periodo',
                'render_callback' => function () {
                    include 'metas/periodo.php';
                }
            ],
            [
                'title' => 'Post Type Permitido',
                'render_callback' => function () {
                    include 'metas/contenido.php';
                }
            ],
            [
                'title' => 'Accesos Especiales',
                'render_callback' => function () {
                    include 'metas/accesos.php';
                }
            ]

        ]);
    }
}
