<?php
/**
 * Default WordPress Twitter Plugin
 *
 * @todo Make templates array filterable
 * @todo Move API end-points to constants
 *
 * @category Nerdery_WordPress_Plugins
 * @package Nerdery_Twitter
 * @version $Id$
 * @author Jess Green <jgreen@nerdery.com>
 */

function do_twitter_feed()
{
    global $tweets;

    $template_override = locate_template(array('views/twitter/twitter-feed.php'));
    $template_located = $template_override
                        ? $template_override
                        : NERDERY_TWITTER_ABSPATH . '/views/twitter-feed.php';

    $tweets = _get_tweets();

    if ($tweets) {
        include($template_located);
    }
}
/**
 * Retrieves JSON from Twitter.
 *
 * @param array $args Arguments
 * @return boolean|string False on failure. JSON string on success.
 */
function _get_tweets()
{
    $args = Nerdery_Twitter_Bootstrap::get_options();

    if (empty($args['standard']['username']))
        return false;

    $count      = isset($args['standard']['count'])
                    ? intval($args['standard']['count']) : 5;
    $username   = esc_attr($args['standard']['username']);
    $cache_time = isset($args['standard']['cache_time'])
                    ? intval($args['standard']['cache_time']) : 1800;

    $consumer_key = empty($args['twitter_oauth']['consumer_secret'])
                        ? false : esc_attr($args['twitter_oauth']['consumer_secret']);
    $consumer_secret = empty($args['twitter_oauth']['consumer_secret_key'])
                        ? false : esc_attr($args['twitter_oauth']['consumer_secret_key']);
    $access_token = empty($args['twitter_oauth']['access_token'])
                        ? false : esc_attr($args['twitter_oauth']['access_token']);
    $access_token_secret = empty($args['twitter_oauth']['access_token_key'])
                        ? false : esc_attr($args['twitter_oauth']['access_token_key']);

    // Caching and retrieval...
    $tweets = get_transient('_nerdery_twitter');

    if (!is_object($tweets)) {

        $have_oauth = ($consumer_key || $consumer_secret || $access_token || $access_token_secret);

        if ($have_oauth){
            $connection = new Nerdery_TwitterOAuth(
                $consumer_key,
                $consumer_secret,
                $access_token,
                $access_token_secret
            );

            $tweets = $connection->get(
                "statuses/user_timeline",
                array(
                    'screen_name'      => $username,
                    'count'            => $count,
                    'include_rts'      => 1,
                    'include_entities' => 1,
                )
            );
        } else {
            $url = "http://api.twitter.com/1/statuses/user_timeline.json"
                . "?screen_name={$username}"
                . "&include_entities=1"
                . "&include_rts=1&count={$count}";

            $json_string = @file_get_contents($url);
            $tweets = json_decode($json_string);
        }

        if (!isset($tweets->error)){
            set_transient('_nerdery_twitter', $tweets, $cache_time);
        }
    }

    return $tweets;
}