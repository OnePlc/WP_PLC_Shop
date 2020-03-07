<?php
/**
 * Plugin main file.
 *
 * @package   OnePlace\Shop
 * @copyright 2020 OnePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch
 * @since 1.0.0
 *
 * @wordpress-plugin
 * Plugin Name: WP PLC Shop
 * Plugin URI:  https://1plc.ch/wordpress-plugins/shop
 * Description: Build your Online Store with onePlace as Backend. Supports Elementor.
 * Version:     1.0.0
 * Author:      onePlace
 * Author URI:  https://1plc.ch
 * License:     GNU General Public License, version 2
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
 * Text Domain: wp-plc-shop
 */

// Some basic security
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if(!session_id()) {
    session_start();
}

// Define global constants
define( 'WPPLC_SHOP_VERSION', '1.0.0' );
define( 'WPPLC_SHOP_PLUGIN_MAIN_FILE', __FILE__ );
define( 'WPPLC_SHOP_PLUGIN_MAIN_DIR', __DIR__ );

/**
 * Handles plugin activation.
 *
 * Throws an error if the plugin is activated on an older version than PHP 5.4.
 *
 * @access private
 *
 * @param bool $network_wide Whether to activate network-wide.
 */
function wpplcshop_activate_plugin( $network_wide ) {
    if ( version_compare( PHP_VERSION, '5.4.0', '<' ) ) {
        wp_die(
            esc_html__( 'WP PLC Events requires PHP version 5.4.', 'wp-plc-shop' ),
            esc_html__( 'Error Activating', 'wp-plc-shop' )
        );
    }

    // check if oneplace connect is already loaded
    if ( ! defined('WPPLC_CONNECT_VERSION') ) {
        // show error if version cannot be determined
        wp_die(
            esc_html__( 'WP PLC Shop requires WP PLC Connect', 'wp-plc-shop' ),
            esc_html__( 'Error Activating', 'wp-plc-shop' )
        );
    }
}

register_activation_hook( __FILE__, 'wpplcshop_activate_plugin' );

if ( version_compare( PHP_VERSION, '5.4.0', '>=' ) ) {
    require_once plugin_dir_path( __FILE__ ) . 'includes/loader.php';
}