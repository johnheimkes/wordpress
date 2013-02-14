<?php
/*
Plugin Name: Nerdery Admin Cleanup
Plugin URI: 
Description: Removes typically unused admin area widgets and text.
Version: 0.1
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

/**
 * Developed by Neil Wargo <nwargo@nerdery.com>
 * This should serve as a guideline for cleaning up the admin area.
 * Modify as appropriate for your project.
 */
class NerderyAdminCleanup 
{
    function init()
    {
        add_action('wp_dashboard_setup', array($this, 'removeUnusedWidgets'));
        add_filter('admin_footer_text', array($this, 'customizeAdminFooter'));
        add_action('wp_before_admin_bar_render', array($this, 'customizeAdminBar'));
        add_action('auth_redirect', array($this, 'forceKitchenSink'));
    }
    
    /**
     * Removes the majority of the dashboard widgets.
     * The only default widget that remains is the "Right Now" widget.
     */
    function removeUnusedWidgets()
    {
        global $wp_meta_boxes;
        
        if(isset($wp_meta_boxes['dashboard']['normal']['high']['dashboard_browser_nag'])) {
            unset($wp_meta_boxes['dashboard']['normal']['high']['dashboard_browser_nag']);
        }
        
        if(isset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'])) {
            unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
        }
        
        if(isset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'])) {
            unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
        }
        
        if(isset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'])) {
            unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
        }
        
        if(isset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'])) {
            unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
        }
        
        if(isset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts'])) {
            unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
        }
        
        if(isset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'])) {
            unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
        }
        
        if(isset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'])) {
            unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
        }
    }
    
    /**
     * Sets the text that shows in the lower left corner of the admin area.
     * It is set to blank by default, but you may want modify the text to say a bit of client specific text
     * or something like "Created by The Nerdery"
     */
    function customizeAdminFooter()
    {
        echo '';
    }
    
    /**
     * Removes the WP logo dropdown from the admin bar
     */
    function customizeAdminBar()
    {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('wp-logo');
    }
    
    /**
     * Force the kitchen sink to be always open in the post editor
     */
    function forceKitchenSink()
    {
        set_user_setting('hidetb', 1);
    }
}

$nrdAdmin = new NerderyAdminCleanup();
$nrdAdmin->init();