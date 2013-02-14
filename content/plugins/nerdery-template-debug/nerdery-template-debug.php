<?php
/*
Plugin Name: Nerdery Debugger
Plugin URI: 
Description: Adds some useful debugging utilities to the WordPress theme. Only displays when WP_DEBUG = true.
Version: 0.1
Author: The Nerdery
Author URI: http://nerdery.com
Contributor(s): Neil Wargo <nwargo@nerdery.com>
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

class NerderyDebugger
{
    private $output;
    private $tabs;
    private $hooks;
    
    const TAB_TEMPLATE = 'Template';
    const TAB_SQL      = 'SQL';
    const TAB_FILTERS  = 'Actions and Filters';
    
    function __construct()
    {
        $this->output = '';
        
        /* due to order of rendering, we need to explicitly declare the tabs before the wp_before_admin_bar_render hook fires,
         * so they'll be present when rendering the admin menu. The actual content is rendered separately and positioned absolutely
         * That content is populated after admin bar render.
         */
        $this->tabs = array(self::TAB_TEMPLATE => '', self::TAB_SQL => '', self::TAB_FILTERS => '');
    }
    
    function init()
    {
        $this->addAssets();
        add_action('wp_footer', array($this, 'displayTemplateInfo'));
        add_action('wp_footer', array($this, 'displaySql'));
        add_action('all', array($this, 'addToHookList'));
        add_action('wp_before_admin_bar_render', array($this, 'addAdminBarTabs'));
        add_action('wp_after_admin_bar_render', array($this, 'displayHooks'));
        add_action('wp_after_admin_bar_render', array($this, 'outputData'));
    }
    
    function addAssets()
    {
        add_action('wp_enqueue_scripts', array($this, 'addScripts'));
        add_action('wp_enqueue_scripts', array($this, 'addStyles'), 100000);
    }
    
    function addScripts()
    {
        wp_register_script('nerdery-debug-script', plugins_url('debug.js', __FILE__), array(
            'jquery'
        ));
        wp_enqueue_script('nerdery-debug-script');
    }
    
    function addStyles()
    {
        wp_register_style('debug-style', plugins_url('debug.css', __FILE__));
        wp_enqueue_style('debug-style');
    }
    
    function addToHookList()
    {
        $this->hooks[] = current_filter();
    }
    
    function addAdminBarTabs()
    {
        global $wp_admin_bar;
        
        foreach(array_keys($this->tabs) as $i => $name) {
            $wp_admin_bar->add_node(array(
                'id' => "nerdery-debug-$i",
                'parent' => 'top-secondary',
                'title' => $name,
                'href' => "#debug-tab-$i",
                'meta' => array(
                    'class' => 'nerdery-debug-item',
                )
            ));
        }
    }
    
    function displayTemplateInfo()
    {
        global $template;
        
        ob_start();
        ?>
        <h2>Template Details</h2>
        <ul class="template-info">
            <li><strong>Template name:</strong> <?php echo $template ?></li>
            <li><strong>Post type:</strong> <?php echo get_post_type() ?></li>
            <li>
                <table>
                    <tr>
                        <td>is_single()</td>
                        <td><?php echo is_single() ? 'true' : 'false' ?></td>
                    </tr>
                    <tr>
                        <td>is_home()</td>
                        <td><?php echo is_home() ? 'true' : 'false' ?></td>
                    </tr>
                    <tr>
                        <td>is_front_page()</td>
                        <td><?php echo is_front_page() ? 'true' : 'false' ?></td>
                    </tr>
                    <tr>
                        <td>is_page()</td>
                        <td><?php echo is_page() ? 'true' : 'false' ?></td>
                    </tr>
                    <tr>
                        <td>is_category()</td>
                        <td><?php echo is_category() ? 'true' : 'false' ?></td>
                    </tr>
                    <tr>
                        <td>is_tag()</td>
                        <td><?php echo is_tag() ? 'true' : 'false' ?></td>
                    </tr>
                    <tr>
                        <td>is_tax()</td>
                        <td><?php echo is_tax() ? 'true' : 'false' ?></td>
                    </tr>
                    <tr>
                        <td>is_author()</td>
                        <td><?php echo is_author() ? 'true' : 'false' ?></td>
                    </tr>
                    <tr>
                        <td>is_date()</td>
                        <td><?php echo is_date() ? 'true' : 'false' ?></td>
                    </tr>
                    <tr>
                        <td>is_archive()</td>
                        <td><?php echo is_archive() ? 'true' : 'false' ?></td>
                    </tr>
                </table>
            </li>
        </ul>
        <?php
        $this->tabs[self::TAB_TEMPLATE] = ob_get_clean();
    }
    
    function displayHooks()
    {
        ob_start();
        ?>
        <h2><?php echo sizeof($this->hooks) ?> filters and actions run on this request</h2>
        <?php if($this->hooks): ?>
        <ul class="hooks">
            <?php foreach($this->hooks as $hook): ?>
            <li><?php echo $hook ?></li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p>No hooks.</p>
        <?php endif; ?>
        <?php
        $this->tabs[self::TAB_FILTERS] = ob_get_clean();
    }
    
    function displaySql()
    {
        if(!SAVEQUERIES) {
            unset($this->tabs[self::TAB_SQL]);
            return;
        }
        global $wpdb;
        ob_start();
        ?>
        <h2>Running <?php echo get_num_queries() ?> queries in <?php echo timer_stop(0, 3) ?> seconds</h2>
        <ul class="query">
        <?php foreach($wpdb->queries as $q) { ?>
            <li>
                <span><strong>Query:</strong> <?php echo $q[0]; ?></span>
                <span><strong>Time:</strong>  <?php echo number_format($q[1] * 1000, 2); ?> ms</span>
                <span><strong>Sources / callers:</strong><br /><?php echo implode('<br />', explode(', ', $q[2])); ?></span>
            </li>
        <?php
        }
        ?>
        </ul>
        <?php
        $this->tabs[self::TAB_SQL] = ob_get_clean();
    }
    
    function outputData()
    {
        if(!$this->tabs) {
            return;
        }
        ?>
        <div class="nerdery-debug">
            <div class="nerdery-debug-tab-items">
            <?php
            $i = 0;
            foreach($this->tabs as $tab) {
                ?>
                <div id="debug-tab-<?php echo $i++; ?>" class="nerdery-debug-tab-item"><?php echo $tab ?></div>
                <?php
            }
            ?>
            </div>
        </div>
        <?php
    }
}

//call from plugins_loaded since we want to use current_user_can, which isn't available til then
add_action('plugins_loaded', 'nerderyDebugInit');

function nerderyDebugInit()
{
    if(current_user_can('administrator') && !is_admin() && defined('WP_DEBUG') && WP_DEBUG) {
        if(!defined('SAVEQUERIES')) {
            define('SAVEQUERIES', true);
        }
        $nrdDebug = new NerderyDebugger();
        $nrdDebug->init();
    }
}