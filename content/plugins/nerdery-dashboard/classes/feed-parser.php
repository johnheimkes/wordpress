<?php
/**
 * A class to build the feed widget
 *
 * @author Kelly Meath <kmeath@nerdery.com>
 * @author Neil Wargo <nwargo@nerdery.com
 */
class FeedParser
{
    function getWidget()
    {
        include_once(ABSPATH . WPINC . '/feed.php');
        $rss = fetch_feed('http://blog.nerdery.com?feed=rss2'); ?>
        <div class="rss-widget">
            <ul>
            <?php
            if (is_wp_error($rss)) {
                ?>
                <li>An error occurred parsing the feed: <?php echo $rss->get_error_message() ?></li>
                <?php 
            } else {
                $maxitems = $rss->get_item_quantity(5);
                $rss_items = $rss->get_items(0, $maxitems);
                ?>
                    <?php if ($maxitems == 0) echo '<li>No items.</li>';
                    else
                    foreach ( $rss_items as $item ) : ?>
                    <li>
                        <a href="<?php echo $item->get_permalink(); ?>"
                        title='Read more' target='_blank' class="rsswidget">
                        <?php echo $item->get_title(); ?></a> <span class="rss-date"><?php print str_replace(' ', '&nbsp;', $item->get_date('F j, Y') ); ?></span>
                                <div class="rssSummary"><?php print $this->getRssExcerpt($item->get_content(), $item->get_permalink()); ?></div>
                    </li>
                    <?php endforeach;
            } ?>
            </ul>
        </div>
        <?php
    }
    
    function getRssExcerpt($text, $permalink)
    {
        $text = preg_replace(
            array(
              // Remove invisible content
                '@<head[^>]*?>.*?</head>@siu',
                '@<style[^>]*?>.*?</style>@siu',
                '@<script[^>]*?.*?</script>@siu',
                '@<object[^>]*?.*?</object>@siu',
                '@<embed[^>]*?.*?</embed>@siu',
                '@<applet[^>]*?.*?</applet>@siu',
                '@<noframes[^>]*?.*?</noframes>@siu',
                '@<noscript[^>]*?.*?</noscript>@siu',
                '@<noembed[^>]*?.*?</noembed>@siu',
              // Add line breaks before and after blocks
                '@</?((address)|(blockquote)|(center)|(del))@iu',
                '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
                '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
                '@</?((table)|(th)|(td)|(caption))@iu',
                '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
                '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
                '@</?((frameset)|(frame)|(iframe))@iu',
            ),
            array(
                ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
                "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
                "\n\$0", "\n\$0",
            ),
            $text );
                    $text = strip_tags( $text);
                    $text = substr($text, 0, 360);
                    $lastSpace = strrpos($text, " ");
                    $text = substr($text, 0 , $lastSpace);
                    $text .= '<a href="'.$permalink.'" target="_blank" title="Read more"> [&#0133;]</a>';
        return $text;
    }
}