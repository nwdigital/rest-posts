<?php /*

**************************************************************************
 * Plugin Name: RESTposts
 * Plugin URI: http://www.restposts.com
 * Description: Embed posts from your site or others' into your posts and pages with WP REST API using shortcodes or the RESTposts widget.
 * Version: 1.0
 * Author: Mathew Moore
 * Author URI: http://www.restposts.com
 * License: GPLv2 or later

**************************************************************************

Copyright (C) 2017 Mathew Moore

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

**************************************************************************/

require_once dirname( __FILE__ ) . '/inc/mrp-settings.php';
require_once dirname( __FILE__ ) . '/inc/mrp-posts-widget.php';
require_once dirname( __FILE__ ) . '/inc/mrp-featured-image.php';
require_once dirname( __FILE__ ) . '/inc/mrp-posts-shortcode.php';

require_once dirname( __FILE__ ) . '/inc/mrp-generate-new.php';

register_activation_hook( __FILE__, 'rest_posts_activation' );
register_deactivation_hook( __FILE__, 'rest_posts_deactivation' );
register_uninstall_hook(    __FILE__, 'rest_posts_uninstall' );

// Register Frontend Styles
function restposts_register_styles(){
wp_enqueue_style( 'shortcode_style_1', plugins_url( 'css/style.css' , __FILE__ ) );
}

add_action('wp_enqueue_scripts','restposts_register_styles');

// Register Admin Styles & Scripts
function restposts_register_scripts(){
wp_enqueue_script( 'shortcode_script_1', plugins_url( 'js/rp-admin.js' , __FILE__ ) );
wp_enqueue_script( 'shortcode_style_2', plugins_url( 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' , __FILE__ ) );
wp_enqueue_style( 'shortcode_style_3', plugins_url( 'css/settings-backend-style.css' , __FILE__ ) );
}

add_action('admin_enqueue_scripts','restposts_register_scripts');

// Readme & Settings link in plugins dashboard
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'restposts_plugin_action_links' );

function restposts_plugin_action_links( $links ) {
   $links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=restpost-settings') ) .'">Settings</a>';
   $links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=restpost-settings&tab=support') ) .'">Support</a>';
   return $links;
}

function rest_posts_activation()
    {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "activate-plugin_{$plugin}" );

        # Uncomment the following line to see the function in action
        # exit( var_dump( $_GET ) );
    }

function rest_posts_deactivation()
    {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "deactivate-plugin_{$plugin}" );

        # Uncomment the following line to see the function in action
        # exit( var_dump( $_GET ) );
    }

function rest_posts_uninstall() {
	if ( ! current_user_can( 'activate_plugins' ) )
            return;
        check_admin_referer( 'bulk-plugins' );

        // Important: Check if the file is the one
        // that was registered during the uninstall hook.
        if ( __FILE__ != WP_UNINSTALL_PLUGIN )
            return;

        # Uncomment the following line to see the function in action
        # exit( var_dump( $_GET ) );
}