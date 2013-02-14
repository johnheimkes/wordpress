<?php
/**
 * Default WordPress Flickr Plugin template
 *
 * @todo Add field in widget form for $base_url
 *
 * @category Nerdery_WordPress_Plugins
 * @package Nerdery_Flickr
 * @subpackage Template
 * @version $Id$
 * @author Jess Green <jgreen@nerdery.com>
 */
?>
<li>
<?php
global $widget_instance, $photo_stream;
$base_url = "http://www.flickr.com/photos/";
?>
    <h2><?php echo esc_attr($widget_instance['title']);?></h2>
    <ul>
    <?php

    if (!empty($photo_stream->photos->photo) && $photo_stream->stat == "ok" || $photo_stream->photos->total != 0) {
        foreach($photo_stream->photos->photo as $photo) {
            $photo_url = $base_url . $photo->owner . "/" . $photo->id;
            $owner_url = $base_url . $photo->owner;

        ?>
        <li>
            <a href="<?php echo $photo_url; ?>" target="_blank">
                <img src="<?php echo $photo->url_t;?>" width="<?php echo $photo->width_t; ?>" height="<?php echo $photo->height_t; ?>" alt="<?php echo $photo->title; ?>" />
            </a>
            <span class="fn">from <a href="<?php echo $owner_url; ?>"><?php echo $photo->ownername ?></a></span>
        </li>
        <?php
        }
    } else {
    ?>
        <li>No Photos Found!</li>
    <?php
    }
    ?>
    </ul>
</li>
