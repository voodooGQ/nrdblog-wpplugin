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

/*
 * These two hooks must be declared separately.
 * These two declarations and their associated methods can be removed
 * if the plugin doesn't require setup and deactivation.
 */
register_activation_hook(__FILE__, array('Awesome_Plugin', 'do_activate'));
register_deactivation_hook( __FILE__, array('Awesome_Plugin', 'do_deactivate'));

add_action('init', array('Awesome_Plugin', 'init'));

class Awesome_Plugin
{
    /**
    * Prefix for the plugin
    * 
    * @constant
    */
    const PLUGIN_PREFIX = 'awesome_plugin';

    /**
     * Initialize
     */
    public static function init()
    {
        add_action( 'admin_init', array($this, 'register_awesome_plugin_fields' ));
    }

    /**
     * Plugin activation function
     */
    public static function do_activate()
    {
        global $wpdb;

        $table_name = $wpdb->base_prefix . PLUGIN_PREFIX;

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          name tinytext NOT NULL,,
          UNIQUE KEY id (id)
        );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );    
    }

    /**
     * Plugin deactivation function
     */
    public static function do_deactivate()
    {
        global $wpdb;
        
        $cleanup = get_option(PLUGIN_PREFIX . '_cleanup');

        if($cleanup) {
            $table_name = $wpdb->base_prefix . PLUGIN_PREFIX;
            $sql = "DROP TABLE IF EXISTS $table_name;";
            // Don't forget to remove the option too!
            delete_option(PLUGIN_PREFIX . '_cleanup');
            $wpdb->query($sql);
        }
    }

    /**
     * Register the deactivation database cleanup option
     */
    protected function register_awesome_plugin_fields() {
        register_setting( 'general', PLUGIN_PREFIX . '_cleanup', 'esc_attr' );
        add_settings_field('awesome_cleanup', '<label for="' . PLUGIN_PREFIX . '_cleanup">'.__('Delete database entries for Awesome Plugin?' , PLUGIN_PREFIX . '_cleanup' ).'</label>' , array(&$this, 'awesome_cleanup_checkbox') , 'general' );
    }

    /**
     * Cleanup checkbox field
     */
    protected function awesome_cleanup_checkbox() {
        $value = get_option( PLUGIN_PREFIX . '_cleanup' );
        $checked = $value ? 'checked="checked"' : '';
        echo '<input type="checkbox" id="' . PLUGIN_PREFIX . '_cleanup" name="' . PLUGIN_PREFIX . '_cleanup" value="yes" '. $checked . ' />';
    }


}