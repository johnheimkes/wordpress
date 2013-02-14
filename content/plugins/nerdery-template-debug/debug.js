jQuery(document).ready(function($) {
    $('.nerdery-debug-item a').click(function(evt) {
        evt.preventDefault();
        var $link = $(evt.currentTarget);
        var clickedTabId = $link.attr('href');
        var $tab = $(clickedTabId);
        var $showingTab = $('.nerdery-debug-tab-item.showing').eq(0);
        
        if(('#' + $showingTab.attr('id')) == clickedTabId) {
            $showingTab.removeClass('showing').animate({
                top: '-500px'
            }, 100);
            return;
        }
        
        if($showingTab.length > 0) {
            $showingTab.removeClass('showing').animate({
                top: '-500px'
            }, 100);
        }
        $tab.addClass('showing').animate({
            top: '28px'
        }, 100);
    });
});