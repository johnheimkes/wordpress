<?php
/**
 * Flickr Widget Form
 *
 * @package Nerdery_Flickr
 * @subpackage Nerdery_Flickr_Form
 * @author Jess Green <jgreen@nerdery.com>
 * @version $Id$
 */
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']))
    die('You are not allowed to call this page directly.');

    $nonce = wp_create_nonce('nerdery_flickr_widget');

?>
<div class="form-wrap">
    <div class="form-field">
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', NERDERY_FLICKR_DOMAIN ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </div>
    <div class="form-field form-required<?php echo $no_api_key; ?>">
        <label for="<?php echo $this->get_field_id( 'api_key' ); ?>"><?php _e('Flickr API Key:', NERDERY_FLICKR_DOMAIN) ?></label>
        <input type="text" name="<?php echo $this->get_field_name( 'api_key' ); ?>" id="<?php echo $this->get_field_id( 'api_key' ); ?>" class="widefat" value="<?php echo esc_attr( $api_key ); ?>"  />
    </div>
    <div class="form-field form-required<?php echo $no_api_secret; ?>">
        <label for="<?php echo $this->get_field_id( 'api_secret' ); ?>"><?php _e('Flickr API Secret:', NERDERY_FLICKR_DOMAIN) ?></label>
        <input type="text" name="<?php echo $this->get_field_name( 'api_secret' ); ?>" id="<?php echo $this->get_field_id( 'api_secret' ); ?>" class="widefat" value="<?php echo esc_attr( $api_secret ); ?>"  />
    </div>
    <div class="form-field form-required<?php echo $no_flickr_user; ?>">
        <label for="<?php echo $this->get_field_id( 'flickr_user' ); ?>"><?php _e('Flickr User NSID:', NERDERY_FLICKR_DOMAIN) ?></label>
        <input type="text" name="<?php echo $this->get_field_name( 'flickr_user' ); ?>" id="<?php echo $this->get_field_id( 'flickr_user' ); ?>" class="widefat" value="<?php echo esc_attr( $flickr_user ); ?>"  />
        <p class="description">The Flickr user NSID (ex: 0000000@N00) can usually be retrieved by using a third-party utility such as <a href="http://idgettr.com/" target="_blank">idGettr</a>.</p>
    </div>
    <div class="form-field">
        <label for="<?php echo $this->get_field_id( 'tags' ); ?>"><?php _e('Flickr Tags (comma-delineated):', NERDERY_FLICKR_DOMAIN) ?></label>
        <input type="text" name="<?php echo $this->get_field_name( 'tags' ); ?>" id="<?php echo $this->get_field_id( 'tags' ); ?>" class="widefat" value="<?php echo esc_attr( $tags ); ?>"  />
    </div>
    <div class="form-field">
        <label for="<?php echo $this->get_field_id( 'flickr_account_url' ); ?>"><?php _e('Flickr Account URL:', NERDERY_FLICKR_DOMAIN) ?></label>
        <input type="text" name="<?php echo $this->get_field_name( 'flickr_account_url' ); ?>" id="<?php echo $this->get_field_id( 'flickr_account_url' ); ?>" class="widefat" value="<?php echo esc_attr( $flickr_account_url ); ?>"  />
    </div>
    <div>
        <label for="<?php echo $this->get_field_id( 'picture_count' ); ?>"><?php _e('Display # Pictures:', NERDERY_FLICKR_DOMAIN) ?>
            <input type="text" name="<?php echo $this->get_field_name( 'picture_count' ); ?>" id="<?php echo $this->get_field_id( 'picture_count' ); ?>" class="small-text" value="<?php echo esc_attr( $picture_count ); ?>"  />
        </label>
    </div>
    <input type="hidden" name="<?php echo $this->get_field_name( '_nerdery_flickr_widget' ); ?>" value="<?php echo $nonce; ?>" />

</div>
