<?php
/**
 * Nerdery Skeleton Plugin
 *
 * @package Nerdery_Skeleton_Plugin
 * @subpackage Bootstrap
 * @author Jess Green <jgreen@nerdery.com>
 * @version $Id$
 */
/*
Plugin Name: Nerdery Skeleton Plugin
Description: <description here>
Version: <version here; start with 1.0 for new custom plugins>
Author: Nerdery Interactive Labs
Author URI: http://nerdery.com
*/

/*
 * These two hooks must be declared separately.
 * These two declarations and their associated methods can be removed
 * if the plugin doesn't require setup and deactivation.
 */
register_activation_hook(__FILE__, array('Nerdery_Plugin_Bootstrap', 'do_activate'));
register_deactivation_hook( __FILE__, array('Nerdery_Plugin_Bootstrap', 'do_deactivate'));

add_action('init', array('Nerdery_Plugin_Bootstrap', 'init'));

class Nerdery_Plugin_Bootstrap
{

    public static function init()
    {
        /*
         * Declare other classes here or do init functionality,
         * like admin menu pages, etc.
         */
    }

    public static function do_activate()
    {
        // do plugin activation stuff
    }

    public static function do_deactivate()
    {
        // do plugin deactivation stuff
    }

}