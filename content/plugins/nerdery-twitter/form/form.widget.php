<?php
/**
 * Twitter Widget Form
 *
 * @category Nerdery_WordPress_Plugins
 * @package Nerdery_Twitter
 * @subpackage Nerdery_Twitter_Form
 * @author Jess Green <jgreen@nerdery.com>
 * @version $Id$
 */
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']))
    die('You are not allowed to call this page directly.');

    $nonce = wp_create_nonce('nerdery_twitter_widget');
?>

<div class="form-wrap">
    <div class="form-field">
        <label for="<?php echo $this->get_field_id('title'); ?>"><span class="required">* </span><?php _e('Title:', NERDERY_TWITTER_DOMAIN); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
    </div>
    <div class="form-field form-required<?php echo $no_username; ?>">
        <label for="<?php echo $this->get_field_id('username'); ?>"><span class="required">* </span><?php _e('Twitter Username:', NERDERY_TWITTER_DOMAIN) ?></label>
        <input type="text" name="<?php echo $this->get_field_name('username'); ?>" id="<?php echo $this->get_field_id('username'); ?>" class="widefat" value="<?php echo esc_attr($username); ?>"  />
    </div>
    <div class="form-field">
        <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Display # Tweets:', NERDERY_TWITTER_DOMAIN) ?>
            <input type="text" name="<?php echo $this->get_field_name('count'); ?>" id="<?php echo $this->get_field_id('count'); ?>" class="small-text" value="<?php echo esc_attr($count); ?>"  />
        </label>
    </div>
    <div class="form-field form-required">
        <label for="<?php echo $this->get_field_id('consumer_key'); ?>"><span class="required">* </span><?php _e('Consumer Key:', NERDERY_TWITTER_DOMAIN) ?></label>
        <input type="text" name="<?php echo $this->get_field_name('consumer_key'); ?>" id="<?php echo $this->get_field_id('consumer_key'); ?>" class="widefat" value="<?php echo esc_attr($consumer_key); ?>"  />
    </div>
    <div class="form-field form-required">
        <label for="<?php echo $this->get_field_id('consumer_secret'); ?>"><span class="required">* </span><?php _e('Consumer Secret:', NERDERY_TWITTER_DOMAIN) ?></label>
        <input type="text" name="<?php echo $this->get_field_name('consumer_secret'); ?>" id="<?php echo $this->get_field_id('consumer_secret'); ?>" class="widefat" value="<?php echo esc_attr($consumer_secret); ?>"  />
    </div>
    <div class="form-field form-required">
        <label for="<?php echo $this->get_field_id('access_token'); ?>"><span class="required">* </span><?php _e('Access Token:', NERDERY_TWITTER_DOMAIN) ?></label>
        <input type="text" name="<?php echo $this->get_field_name('access_token'); ?>" id="<?php echo $this->get_field_id('access_token'); ?>" class="widefat" value="<?php echo esc_attr($access_token); ?>"  />
    </div>
    <div class="form-field form-required">
        <label for="<?php echo $this->get_field_id('access_token_secret'); ?>"><span class="required">* </span><?php _e('Access Token Secret:', NERDERY_TWITTER_DOMAIN) ?></label>
        <input type="text" name="<?php echo $this->get_field_name('access_token_secret'); ?>" id="<?php echo $this->get_field_id('access_token_secret'); ?>" class="widefat" value="<?php echo esc_attr($access_token_secret); ?>"  />
    </div>
    <div class="form-field">
        <label for="<?php echo $this->get_field_id('oauth_callback'); ?>"><?php _e('OAuth Callback:', NERDERY_TWITTER_DOMAIN) ?></label>
        <input type="text" name="<?php echo $this->get_field_name('oauth_callback'); ?>" id="<?php echo $this->get_field_id('oauth_callback'); ?>" class="widefat" value="<?php echo esc_attr($oauth_callback); ?>"  />
    </div>
    <input type="hidden" name="<?php echo $this->get_field_name( '_nerdery_twitter_widget' ); ?>" value="<?php echo $nonce; ?>" />
</div>
