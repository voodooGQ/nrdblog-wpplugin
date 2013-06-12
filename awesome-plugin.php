<?php
/**
 * Awesome Plugin
 *
 * @package Awesome_Plugin
 * @subpackage WordPress
 * @author Shane Smith <ssmith@nerdery.com> <twitter:voodoogq> <github:voodoogq>
 * @version 1.0
 */
/*
Plugin Name: My Awesome Plugin
Description: My Awesome Plugin - Make sure to check 'Settings > General' before deactivation
Version: 1.0
Author: Nerdery Interactive Labs
Author URI: http://nerdery.com
*/


/**
 * Run on plugin activation
 */
function do_activate()
{
    global $wpdb;

    $table_name = $wpdb->base_prefix . "awesome_plugin";

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      name tinytext NOT NULL,,
      UNIQUE KEY id (id)
    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );        
}
register_activation_hook(__FILE__, 'do_activate');

/**
 * Run on plugin deactivation
 */
function do_deactivate()
{
    global $wpdb;
    
    $cleanup = get_option('awesome_plugin_cleanup');

    if($cleanup) {
        $table_name = $wpdb->base_prefix . "awesome_plugin";
        $sql = "DROP TABLE IF EXISTS $table_name;";
        // Don't forget to remove the option too!
        delete_option('awesome_plugin_cleanup');
        $wpdb->query($sql);
    }
}
register_deactivation_hook( __FILE__, 'do_deactivate');

/**
 * Register the deactivation database cleanup option
 */
function register_awesome_plugin_fields() {
    register_setting( 'general', 'awesome_plugin_cleanup', 'esc_attr' );
    add_settings_field('awesome_plugin_cleanup', '<label for="awesome_plugin_cleanup">'.__('Delete database entries for Awesome Plugin on deactivation?' , 'awesome_plugin_cleanup' ).'</label>' , 'awesome_cleanup_checkbox' , 'general' );
}
add_filter( 'admin_init' , 'register_awesome_plugin_fields' );

/**
 * Cleanup checkbox field
 */
function awesome_cleanup_checkbox() {
    $value = get_option( 'awesome_plugin_cleanup' );
    $checked = $value ? 'checked="checked"' : '';
    echo '<input type="checkbox" id="awesome_plugin_cleanup" name="awesome_plugin_cleanup" value="yes" '. $checked . ' />';
}

