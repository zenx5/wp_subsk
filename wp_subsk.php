<?php

/**
 * @package WP_Rulette_Kavav
 * @version 1.7.2
 */
/*
Plugin Name: WP Subscription by Bohiques
Plugin URI: https://bohiques.com
Description: Plugin de subscripciones
Author: Octavio Martinez
Version: 1.0.0
Author URI: https://wa.me/19104468990
*/

require 'class_wp_subsk.php';

register_activation_hook(__FILE__, array('WP_Subsk', 'active'));
register_deactivation_hook(__FILE__, array('WP_Subsk', 'deactive'));
// register_uninstall_hook(__FILE__, array('WP_Subsk', 'uninstall') );

WP_Subsk::init();
//add_action('init', array('WP_Subsk', 'init'));
