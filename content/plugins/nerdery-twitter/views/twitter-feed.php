<?php
/**
 * Default WordPress Twitter Plugin template
 *
 *
 * @category Nerdery_WordPress_Plugins
 * @package Nerdery_Twitter
 * @subpackage Template
 * @version $Id$
 * @author Jess Green <jgreen@nerdery.com>
 */
?>
<li>
<?php global $tweets; ?>
    <h2>Twitter Feed</h2>
    <ul>
        <?php
        if (!isset($tweets->error)) :
            foreach ($tweets as $tweet) : ?>
        <li>
            <p>
                <?php echo $tweet->text; ?>
            </p>
            <span class="meta">
                <time datetime="<?php echo date('c', strtotime($tweet->created_at));?>" pubdate><?php echo human_time_diff(strtotime($tweet->created_at)); ?></time> from <?php echo $tweet->source ?>
            </span>

        </li>
        <?php endforeach;
        else: ?>
        <li><p><?php echo $tweets->error; ?></p></li>
        <?php endif; ?>
    </ul>
</li>
