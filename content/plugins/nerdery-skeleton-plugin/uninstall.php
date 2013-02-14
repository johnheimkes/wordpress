<?php
/**
 * Nerdery Skeleton Plugin
 *
 * @package Nerdery_Skeleton_Plugin
 * @subpackage Uninstall
 * @author Jess Green <jgreen@nerdery.com>
 * @version $Id$
 */
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']))
    die('You are not allowed to call this page directly.');

if( !defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
    exit();

// do the uninstall stuff here