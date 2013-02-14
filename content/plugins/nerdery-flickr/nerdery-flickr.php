<?php
/**
 * Default WordPress Flickr plugin
 *
 * @todo Add option for setting cache time
 * @todo Add option for controlling image size
 * @todo Add alternative theme for lightbox support and option to link to high-res image
 *
 * @package Nerdery_WordPress_Plugins
 * @subpackage Nerdery_Flickr
 * @version $Id$
 * @author Jess Green <jgreen@nerdery.com>
 */
/*
Plugin Name: Flickr Module Plugin
Description: Adds Flickr functionality for theme.
Version: 1.0-beta
Author: Nerdery Interactive Labs
Author URI: http://nerdery.com
License: GPL3
*/
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']))
    die('You are not allowed to call this page directly.');

$flickr_plugin_folder = basename(dirname(__FILE__));

if (!defined('NERDERY_FLICKR_ABSPATH'))
    define('NERDERY_FLICKR_ABSPATH', WP_CONTENT_DIR . '/plugins/' . $flickr_plugin_folder . '/' );

if (!defined('NERDERY_FLICKR_URLPATH'))
    define('NERDERY_FLICKR_URLPATH', WP_CONTENT_URL . '/plugins/' . $flickr_plugin_folder . '/');

if (!defined('NERDERY_FLICKR_LANG'))
    define('NERDERY_FLICKR_LANG', $flickr_plugin_folder . '/lang');

if (!defined('NERDERY_FLICKR_DOMAIN'))
    define('NERDERY_FLICKR_DOMAIN', $flickr_plugin_folder);

if (!defined('NERDERY_FLICKR_VERSION'))
    define('NERDERY_FLICKR_VERSION', '1.0-beta');

add_action( 'widgets_init', create_function( '', 'register_widget( "Nerdery_Flickr_Widget" );' ) );

/**
 * Flickr Widget Class
 *
 * @package Nerdery_Flickr
 * @subpackage WP_Widget
 * @author Jess Green <jgreen@nerdery.com>
 */
class Nerdery_Flickr_Widget extends WP_Widget
{
    /**
     * Transient expiration time
     */
    const FLICKR_TRANSIENT_EXP = 3600; // set transient to expire in 60 minutes

    /**
     * PHP5 Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(
            "Nerdery_Flickr_Widget",
            "Flickr Widget",
            array(
                'description' => __("Standard Flickr widget", NERDERY_FLICKR_DOMAIN),
            )
        );
    }

    /**
     * Handles view loading for widget
     *
     * @see WP_Widget::widget
     */
    public function widget($args, $instance)
    {
        global $widget_instance, $photo_stream;

        $widget_instance = $instance;

        $template_override = locate_template( array( 'views/widgets/flickr-widget.php' ) );
        $template_located = $template_override ? $template_override : NERDERY_FLICKR_ABSPATH . 'views/flickr-widget.php';

        $photo_stream = $this->_get_photos($instance);

        if ($photo_stream && !empty($photo_stream->stat))
            include($template_located);

    }

    /**
     * Sanitize and save widget options.
     *
     * @see WP_Widget::update
     */
    public function update($new_instance, $old_instance)
    {
        if (!wp_verify_nonce($new_instance['_nerdery_flickr_widget'], 'nerdery_flickr_widget')) {
            return $old_instance;
        }

        $instance = array();

        $instance['title']      = wp_kses($new_instance['title'], array(), array());
        $instance['api_key']    = wp_kses($new_instance['api_key'], array(), array());
        $instance['api_secret'] = wp_kses($new_instance['api_secret'], array(), array());
        $instance['tags']       = wp_kses($new_instance['tags'], array(), array());
        $instance['flickr_user']       = wp_kses($new_instance['flickr_user'], array(), array());

        $instance['flickr_account_url'] = wp_kses($new_instance['flickr_account_url'], array(), array('http', 'https'));
        $instance['picture_count']      = intval($new_instance['picture_count']);

        return $instance;

    }

    /**
     * Displays widget options form.
     *
     * @see WP_Widget::form
     */
    public function form($instance)
    {
        $title      = "";
        $api_key    = "";
        $api_secret = "";
        $tags       = "";
        $flickr_user = "";
        $flickr_account_url = "";
        $picture_count = "";

        if (isset($instance))
            extract($instance);

        $no_api_key = empty($api_key) ? ' form-invalid' : '';
        $no_api_secret = empty($api_secret) ? ' form-invalid' : '';
        $no_flickr_user = empty($flickr_user) ? ' form-invalid' : '';

        include(NERDERY_FLICKR_ABSPATH . '/form/form.widget.php');
    }

    /**
     * Private function for retrieving photos from Flickr
     *
     * @param array $args Arguments
     * @return boolean|string False on failure. JSON string on success.
     */
    private function _get_photos($args = array())
    {
        extract($args);

        // check username/NSID
        if (empty($args['flickr_user']))
            return false;

        if (empty($args['api_key']))
            return false;

        if (strpos($args['flickr_user'], '@N') === false)
            return false;

        // first, let's check if we did this already...
        $nsid = get_option('nerdery_flickr_nsid');
        if (!$nsid || $nsid !== $args['flickr_user']) {
            $nsid = wp_kses($args['flickr_user'], array(), array());
            update_option('nerdery_flickr_nsid', $nsid);

            $transient_expire = -1;
        } else {
            $transient_expire = self::FLICKR_TRANSIENT_EXP;
        }

        $picture_count = isset($args['picture_count']) ? $args['picture_count'] : 6;

        $tags_parm = "";
        if (!empty($tags))
            $tags_parm = "&tags=" . urlencode($tags);

        $url = "http://api.flickr.com/services/rest/?method=flickr.photos.search"
            . "&api_key={$api_key}"
            . "&user_id={$nsid}"
            . "&extras=owner_name%2C+url_sq%2C+url_t"
            . "{$tags_parm}&per_page={$picture_count}&page=1"
            . "&format=json&nojsoncallback=1";

        // Caching and retrieval...
        $photo_stream = get_transient('_nerdery_flickr');
        $failed = (empty($photo_stream->stat) || $photo_stream->stat == 'fail');
        if (!is_object($photo_stream) || $transient_expire === -1 || $failed) {
            $json_string  = file_get_contents($url);

            $photo_stream = json_decode($json_string);
            set_transient('_nerdery_flickr', $photo_stream, $transient_expire);
        }

        return $photo_stream;
    }
}