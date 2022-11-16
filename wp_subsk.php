<?php

/**
 * @package WP_Subsk
 * @version 1.0.0
 */
/*
Plugin Name: WP Subscription by Bohiques
Plugin URI: https://bohiques.com
Description: Plugin de subscripciones
Author: Octavio Martinez
Version: 1.0.0
Author URI: https://wa.me/19104468990
*/

require 'vendor/autoload.php';

register_activation_hook(__FILE__, array('WP_Subsk', 'active'));
register_deactivation_hook(__FILE__, array('WP_Subsk', 'deactive'));

WP_Subsk::init();