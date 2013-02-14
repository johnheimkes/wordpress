<?php
/**
 * Default WordPress Twitter Plugin
 *
 * @todo Consolidate get_tweets code from widget and functions file.
 *
 * @category Nerdery_WordPress_Plugins
 * @package Nerdery_Twitter
 * @version $Id$
 * @author Jess Green <jgreen@nerdery.com>
 */
/*
Plugin Name: Twitter Module Plugin
Description: Adds Twitter functionality for theme.
Version: 1.5
Author: Nerdery Interactive Labs
Author URI: http://nerdery.com/Ge
License: GPL3
*/
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']))
    die('You are not allowed to call this page directly.');

$twitter_plugin_folder = basename(dirname(__FILE__));

if (!defined('NERDERY_TWITTER_ABSPATH'))
    define('NERDERY_TWITTER_ABSPATH', WP_CONTENT_DIR . '/plugins/' . $twitter_plugin_folder . '/');

if (!defined('NERDERY_TWITTER_URLPATH'))
    define('NERDERY_TWITTER_URLPATH', WP_CONTENT_URL . '/plugins/' . $twitter_plugin_folder . '/');

if (!defined('NERDERY_TWITTER_LANG'))
    define('NERDERY_TWITTER_LANG', $twitter_plugin_folder . '/lang');

if (!defined('NERDERY_TWITTER_DOMAIN'))
    define('NERDERY_TWITTER_DOMAIN', $twitter_plugin_folder);

if (!defined('NERDERY_TWITTER_VERSION'))
    define('NERDERY_TWITTER_VERSION', '1.5');

include_once 'libs/twitteroauth.php';
include_once 'nerdery-twitter-widget.php';
include_once 'nerdery-twitter-options.php';
include_once 'nerdery-twitter-functions.php';

add_action('init', array('Nerdery_Twitter_Bootstrap', 'init'));

register_activation_hook(__FILE__, array('Nerdery_Twitter_Bootstrap', 'do_activate'));
register_deactivation_hook( __FILE__, array('Nerdery_Twitter_Bootstrap', 'do_deactivate'));

class Nerdery_Twitter_Bootstrap
{
    protected static $options;

    protected static $version;

    /**
     * Default options for plugin installation
     *
     * @var array
     */
    protected static $default_options = array(
        'username'            => '',
        'count'               => 5,
        'consumer_key'        => '',
        'consumer_key_secret' => '',
        'access_token'        => '',
        'access_token_key'    => '',
        'oauth_callback'      => '',
    );

    public static function init()
    {
        global $nerdery_twitter_widget_options;

        self::set_options();

        $nerdery_twitter_widget_options = new Nerdery_Twitter_Options();

    }

    public static function do_activate()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Check for capability
        if ( !current_user_can('activate_plugins') )
            wp_die( __('Sorry, you do not have suffient permissions to activate this plugin.', NERDERY_TWITTER_DOMAIN) );

        self::$version = strval( get_option('nerdery_twitter_ver') );

        // version_compare will still evaluate against an empty string
        // so we have to tell it not to.
        if (version_compare(self::$version, NERDERY_TWITTER_VERSION, '<') && !(self::$version == '')) {

            add_option( 'nerdery_twitter_upgrade', 'yes', '', 'no');

        } elseif (self::$version == '') {

            add_option( 'nerdery_twitter_ver', NERDERY_TWITTER_VERSION, '', 'no');
            update_option( 'nerdery_twitter_options', self::$default_options);

        }

        flush_rewrite_rules();
    }

    public static function do_deactivate()
    {
        flush_rewrite_rules();
    }

    /**
     * Set plugin options. This method should run every time
     * plugin options are updated.
     *
     * @return void
     */
    public static function set_options()
    {
        $options = maybe_unserialize(get_option('nerdery_twitter_options'));

        if (empty($options)) {
            $options = self::$default_options;
        }

        self::$options = $options;

    }

    /**
     * Get plugin options
     *
     * @return array
     */
    public static function get_options()
    {
        return self::$options;
    }

}