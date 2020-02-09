<?php

/**
 * Plugin loader.
 *
 * @package   OnePlace\Shop
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/shop
 */

namespace OnePlace\Shop;

/**
 * Main class for the plugin
 */
final class Plugin {
    /**
     * Main instance of the plugin.
     *
     * @var Plugin|null
     * @since 1.0.0
     */
    private static $instance = null;

    /**
     * Retrieves the main instance of the plugin.
     *
     * @return Plugin Plugin main instance.
     * @since 1.0.0
     */
    public static function instance() {
        return static::$instance;
    }

    /**
     * Registers the plugin with WordPress.
     *
     * @since 1.0.0
     */
    public function register() {
        # Enable Settings Page
        Modules\Settings::load();

        # Enable Basket Core
        Modules\Basket::load();

        if(get_option( 'plcshop_elementor_active') == 1) {
            # Enable Elementor Integration
            Modules\Elementor::load();
        }
    }

    /**
     * Loads the plugin main instance and initializes it.
     *
     * @param string $main_file Absolute path to the plugin main file.
     * @return bool True if the plugin main instance could be loaded, false otherwise.
     * @since 1.0.0
     */
    public static function load( $main_file ) {
        if ( null !== static::$instance ) {
            return false;
        }
        static::$instance = new static( $main_file );
        static::$instance->register();
        return true;
    }
}