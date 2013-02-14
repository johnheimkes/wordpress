<?php
/*
Plugin Name: Nerdery Dashboard
Plugin URI: 
Description: Custom Nerdery dashboard widgets
Version: 1
Author: The Nerdery
Author URI: http://nerdery.com
License: GPL2

    Copyright 2010 The Nerdery

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

$dashboard_plugin_folder = dirname(__FILE__);
require_once($dashboard_plugin_folder . '/classes/feed-parser.php');
require_once($dashboard_plugin_folder . '/classes/server-info.php');

class NerderyDashboard 
{
    function init() 
    {
        add_action('wp_dashboard_setup', array($this, 'addDashboardWidgets'));
    }
    
    function addDashboardWidgets()
    {
        // Use add_meta_box instead of wp_add_dashboard_widget to allow us to position them
        // See http://wordpress.stackexchange.com/questions/4690/how-to-position-custom-dashboard-widgets-on-side-column
        add_meta_box('nerdery_contact', __('The Nerdery Interactive Labs'), array($this, 'addContactWidget'), 'dashboard', 'side', 'core');
        add_meta_box('nerdery_server_info', __('Server Info'), array($this, 'addServerInfoWidget'), 'dashboard', 'side', 'core');
        add_meta_box('nedery_blog', __('The Nerdery Blog'), array($this, 'addBlogWidget'), 'dashboard', 'side', 'core');
    }
    
    function addServerInfoWidget()
    {
        $serverInfo = new ServerInfo();
        $serverInfo->getWidget();
    }
    
    function addBlogWidget()
    {
        $blogWidget = new FeedParser();
        $blogWidget->getWidget();
    }
    
    function addContactWidget()
    { ?>
        <div style="overflow: hidden;">
            <div style="float: left; padding-right: 30px; line-height: 1.2em">
                 HEADQUARTERS<br />
                 9555 James Ave S<br />
                 Suite 245<br />
                 Bloomington, MN 55431<br />
            </div>
            <div style="float: left; line-height: 1.2em;">
                 CHICAGO<br />
                 300 N Elizabeth St.<br />
                 Suite 500C<br />
                 Chicago, IL 60607<br />
            </div> 
        </div>
            
        <ul style="margin-top: 20px;">
            <li><strong>Phone:</strong> (877) 664.NERD</li>
            <li><strong>Email:</strong> <a href="mailto:&#x69;&#x6E;&#x66;&#x6F;&#x40;&#x6E;&#x65;&#x72;&#x64;&#x65;&#x72;&#x79;&#x2E;&#x63;&#x6F;&#x6D;">info@nerdery.com</a></li>
            <li><strong>Fax:</strong> (952) 948.1611 </li>
            <li><strong>Website:</strong> <a href="http://nerdery.com" target="_blank">http://nerdery.com</a></li>
        </ul>
    <?php
    }
}

$nrdDash = new NerderyDashboard();
$nrdDash->init();