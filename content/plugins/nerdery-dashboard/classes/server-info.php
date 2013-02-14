<?php
/**
 * A class to build the server info widget
 *
 * @author Kelly Meath <kmeath@nerdery.com>
 * @author Neil Wargo <nwargo@nerdery.com
 */
class ServerInfo
{
    function getWidget()
    {
        $current_php_version = phpversion();
        $current_mysql_version = $this->mysqlVersion();
        $current_apache_version = $this->serverInfo();
        
        ?>
        <div style="width: 40%; margin: 3%; padding-right: 3%; float: left; display: inline; border-right: 1px solid #DFDFDF">
            <h5 style="margin: 0 0 .5em 0; font-size: 12px">Current Server Info:</h5>
            <p><?php
            echo "<strong>PHP Version:</strong> $current_php_version<br />";
            echo "<strong>MySql Version:</strong> $current_mysql_version<br />";
            echo "<strong>Apache Version:</strong> $current_apache_version<br />";
            ?></p>
        </div>
        <div style="width: 40%; margin: 3%; float: left">
            <h5 style="margin: 0 0 .5em 0; font-size: 12px">Initial Server Info:</h5>
            <p><?php 
            echo "<strong>PHP Version:</strong> " . $this->optionGetSet('nerdery_config_phpversion', $current_php_version) . "<br />";
            echo "<strong>MySql Version:</strong> " . $this->optionGetSet('nerdery_config_mysqlversion', $current_mysql_version) . "<br />";
            echo "<strong>Apache Version:</strong> " . $this->optionGetSet('nerdery_config_apacheversion', $current_apache_version) . "<br />";
            ?></p>
        </div>
        <div style="clear: left"></div><?
    }
    
    function serverInfo() 
    {
       $ver = split("[/ ]",$_SERVER['SERVER_SOFTWARE']);
       $apver = "$ver[1] $ver[2]";
       return $apver;
    }
    
    function mysqlVersion() {
       $output = shell_exec('mysql -V');
       preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
       if(!isset($version[0])) {
           return '';
       }
       return $version[0];
    }
    
    function optionGetSet($option_name, $option_value) {
       //if the value hasn't previously been set, then set it
       if (!get_option($option_name)) {
           update_option($option_name, $option_value);
       } 
       return(get_option($option_name));
    }
}