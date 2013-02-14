<?php
/**
 * Twitter Widget
 * 
 * @author Jess Green <jgreen@nerdery.com>
 * @category Nerdery_WordPress_Plugins
 * @package Nerdery_Twitter
 * @version $Id$
 * @author Jess Green <jgreen@nerdery.com>
 */
add_action('widgets_init', create_function('', 'register_widget( "Nerdery_Twitter_Widget" );'));

/**
 * Twitter Widget Class
 *
 * @package Nerdery_Twitter
 * @subpackage WP_Widget
 */
class Nerdery_Twitter_Widget extends WP_Widget
{
    /**
     * Transient expiration time
     */
    const TWITTER_TRANSIENT_EXP = 1800; // set transient to expire in 30 minutes

    /**
     * PHP5 constructor function
     */
    public function __construct()
    {
        parent::__construct(
            "Nerdery_Twitter_Widget",
            "Twitter Widget",
            array(
                'description' => __("Standard Twitter widget", NERDERY_TWITTER_DOMAIN),
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
        global $widget_instance, $tweets;

        $widget_instance = $instance;

        $template_override = locate_template(array('views/widgets/twitter-widget.php'));
        $template_located = $template_override
                            ? $template_override
                            : NERDERY_TWITTER_ABSPATH . '/views/twitter-widget.php';

        $tweets = $this->_get_tweets(array(
            'username'=> $instance['username'],
            'count'   => $instance['count'],
        ));

        if ($tweets) {
            include($template_located);
        }


    }

    /**
     * Sanitize and save widget options.
     *
     * @see WP_Widget::update
     */
    public function update($new_instance, $old_instance)
    {
        if (!wp_verify_nonce($new_instance['_nerdery_twitter_widget'], 'nerdery_twitter_widget')) {
            return $old_instance;
        }

        $instance = array();

        $instance['title']    = strip_tags($new_instance['title']);
        $instance['username'] = strip_tags($new_instance['username']);
        $instance['count']    = strip_tags($new_instance['count']);
        $instance['consumer_key']    = strip_tags($new_instance['consumer_key']);
        $instance['consumer_secret'] = strip_tags($new_instance['consumer_secret']);
        $instance['access_token']    = strip_tags($new_instance['access_token']);
        $instance['access_token_secret'] = strip_tags($new_instance['access_token_secret']);
        $instance['oauth_callback']      = strip_tags($new_instance['oauth_callback']);

        return $instance;

    }

    /**
     * Displays widget options form.
     *
     * @see WP_Widget::form
     */
    public function form($instance)
    {
        $title    = "";
        $username = "";
        $count    = 6;

        if (isset($instance))
            extract($instance);

        $no_username = empty($username) ? ' form-invalid' : '';

        include(NERDERY_TWITTER_ABSPATH . '/form/form.widget.php');
    }

    /**
     * Retrieves JSON from Twitter.
     *
     * @param array $args Arguments
     * @return boolean|string False on failure. JSON string on success.
     */
    private function _get_tweets($args = array())
    {
        extract($args);

        if (empty($args['username']))
            return false;

        $count = isset($args['count'])? intval($args['count']) : 6;

        // Caching and retrieval...
        $tweets = get_transient('_nerdery_twitter');
        if (!is_object($tweets)) {

            $no_oauth = (empty($args['consumer_key'])
                            && empty($args['consumer_secret'])
                            && empty($args['access_token'])
                            && empty($args['access_token_secret']));

            if ($no_oauth){
                $url = "http://api.twitter.com/1/statuses/user_timeline.json"
                    . "?screen_name={$args['username']}"
                    . "&include_entities=1"
                    . "&include_rts=1&count={$count}";

                $json_string = @file_get_contents($url);
                $tweets = json_decode($json_string);

            } else {
                $connection = new Nerdery_TwitterOAuth(
                    self::CONSUMER_KEY,
                    self::CONSUMER_SECRET,
                    self::ACCESS_TOKEN,
                    self::ACCESS_TOKEN_SECRET
                );

                $tweets = $connection->get(
                    "statuses/user_timeline",
                    array(
                        'screen_name'      => $args['username'],
                        'count'            => $count,
                        'include_rts'      => 1,
                        'include_entities' => 1,
                    )
                );
            }
            set_transient('_nerdery_twitter', $tweets, self::TWITTER_TRANSIENT_EXP);
        }

        return $tweets;
    }

}