<?php

/**
 * @package BH_Subsk
 * @version 1.0.0
 */
/*
Plugin Name: BH Subscription by Bohiques
Plugin URI: https://bohiques.com
Description: Plugin de subscripciones desarrollado por Bohiques
Author: Octavio Martinez
Version: 1.0.0
License: GPLv3
Author URI: https://wa.me/584260644067
*/

require 'vendor/autoload.php';

register_activation_hook(__FILE__, array('BH_Subsk', 'active'));
register_deactivation_hook(__FILE__, array('BH_Subsk', 'deactive'));

BH_Subsk::init();