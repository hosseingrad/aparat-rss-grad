<?php
/**
* Plugin Name:  Aparat Videos RSS Reader | GRAD
* Description:  Reading Aparat.com RSS and showing in a widget.
* Version:      0.9.0
* Author:       Hossein G Rad
* Author URI:   https://www.linkedin.com/in/hosseingrad/
* License:      GPL v3
* Text Domain:  aparss-grad
* Domain Path:  /languages
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*/

if ( !defined( 'ABSPATH' ) ) die( '-1' );

define ( 'APARSSGRAD__FILE', __FILE__ );
define ( 'APARSSGRAD__DIR', dirname( __FILE__ ) );
define ( 'APARSSGRAD__VERSION', '1.0.0' );

require_once dirname( __FILE__ ) . '/includes/widgets/class-widget-aparat-rss-list.php';
//require_once dirname( __FILE__ ) . '/includes/widgets/class-widget-aparat-rss-grid.php';



/**
* Register the new widget.
*
* @see 'widgets_init'
*/
function register_widgets_aparssgrad() {
    register_widget( 'Widget_Aparat_RSS_List__APARSSGRAD' );
}
add_action( 'widgets_init', 'register_widgets_aparssgrad' );



/**
* Activate hook for current plugin
*/
if ( function_exists('hook_activate_aparssgrad') ) {
    function hook_activate_aparssgrad() {

        if ( !current_user_can( 'activate_plugins' ) )
            return;
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "activate-plugin_{$plugin}" );

        $sngrad_option = array( 'ver' => 1, 'date' => '2018-11-12' );

        update_option( 'aparssgrad_version', $sngrad_option );
    }
}

/**
* Deactivate hook for current plugin
*/
if ( function_exists('hook_deactivate_aparssgrad') ) {
    function hook_deactivate_aparssgrad() {

        if ( !current_user_can( 'activate_plugins' ) )
            return;
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "deactivate-plugin_{$plugin}" );

    }
}

/**
* Uninstall hook for current plugin
*/
if ( function_exists('hook_uninstall_aparssgrad') ) {
    function hook_uninstall_aparssgrad() {

        if ( !current_user_can( 'activate_plugins' ) )
            return;
        check_admin_referer( "bulk-plugins" );

        delete_option( 'aparssgrad_version' );
    }
}

register_activation_hook    ( __FILE__, 'hook_activate_aparssgrad' );
register_deactivation_hook  ( __FILE__, 'hook_deactivate_aparssgrad' );
register_uninstall_hook     ( __FILE__, 'hook_uninstall_aparssgrad');






?>
