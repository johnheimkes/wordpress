<?php
/**
 * Nerdery Twitter Plugin Options
 *
 * @category Nerdery_WordPress_Plugins
 * @package Nerdery_Twitter
 * @author Jess Green <jgreen@nerdery.com>
 * @version $Id$
 */
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']))
    die('You are not allowed to call this page directly.');

    $sections = array_keys($this->get_sections());

    // if settings have been changed, refresh cache
    if (isset($_GET['settings-updated'])) {
        delete_transient('_nerdery_twitter');
    }
?>
<style type="text/css">
    #icon-nerdery-twitter-options {
        background-image: url('<?php echo NERDERY_TWITTER_URLPATH; ?>/images/logo-32x32.png');
    }
</style>
<div class="wrap">
    <?php screen_icon(); ?>
    <h2>Twitter Options</h2>
    <form action="options.php" method="post" id="nerdery_twitter_options_form">
        <?php settings_fields('nerdery_twitter_options'); ?>

        <?php
            foreach ($sections as $section_name){
                do_settings_sections("nerdery_twitter_options-{$section_name}");
            }
        ?>
        <p>
            <input name="nerdery_twitter_option-submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', NERDERY_TWITTER_DOMAIN); ?>" />
            <input name="nerdery_twitter_options-reset" type="reset" class="button-secondary" value="<?php esc_attr_e('Reset Defaults', NERDERY_TWITTER_DOMAIN); ?>" />
        </p>
    </form>
</div>