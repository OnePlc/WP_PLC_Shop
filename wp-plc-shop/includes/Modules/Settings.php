<?php

/**
 * Shop Settings Main
 *
 * @package   OnePlace\Shop\Modules
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/shop
 * @since 1.0.0
 */

namespace OnePlace\Shop\Modules;

use OnePlace\Shop\Plugin;

final class Settings {
    /**
     * Main instance of the module
     *
     * @var Plugin|null
     * @since 1.0.0
     */
    private static $instance = null;

    /**
     * Shop Settings
     *
     *  @since 1.0.0
     */
    public function register() {
        // add custom scripts for admin section
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueueScripts' ] );

        // Add submenu page for settings
        add_action("admin_menu", [ $this, 'addSubMenuPage' ]);

        // Register Settings
        add_action( 'admin_init', [ $this, 'registerSettings' ] );

        // Add Plugin Languages
        add_action('plugins_loaded', [ $this, 'loadTextDomain' ] );
    }

    /**
     * load text domain (translations)
     *
     * @since 1.0.0
     */
    public function loadTextDomain() {
        load_plugin_textdomain( 'wp-plc-shop', false, dirname( plugin_basename(WPPLC_SHOP_PLUGIN_MAIN_FILE) ) . '/language/' );
    }

    /**
     * Register Plugin Settings in Wordpress
     *
     * @since 1.0.0
     */
    public function registerSettings() {
        // Core Settings
        register_setting( 'wpplc_shop', 'plcshop_pages_main', false );
        register_setting( 'wpplc_shop', 'plcshop_pages_basket', false );
        register_setting( 'wpplc_shop', 'plcshop_elementor_active', false );
        register_setting( 'wpplc_shop', 'plcshop_currency_main', '$' );
        register_setting( 'wpplc_shop', 'plcshop_currency_pos', 'before' );
        register_setting( 'wpplc_shop', 'plcshop_basket_slug', 'basket' );
    }

    /**
     * Enqueue Style and Javascript for Admin Panel
     *
     * @since 1.0.0
     */
    public function enqueueScripts() {
    }

    /**
     * Add Submenu Page to OnePlace Settings Menu
     *
     * @since 1.0.0
     */
    public function addSubMenuPage() {
        $page_title = 'OnePlace Shop';
        $menu_title = 'Shop';
        $capability = 'manage_options';
        $menu_slug  = 'oneplace-connect';
        $function   = [$this,'AdminMenu'];
        $icon_url   = 'dashicons-media-code';
        $position   = 5;

        add_submenu_page( $menu_slug, $page_title, $menu_title,
            $capability, 'oneplace-shop',  [$this,'renderSettingsPage'] );
    }

    /**
     * Render Settings Page for Plugin
     *
     * @since 1.0.0
     */
    public function renderSettingsPage() {
        require_once __DIR__.'/../view/settings.php';
    }

    /**
     * Loads the module main instance and initializes it.

     * @return bool True if the plugin main instance could be loaded, false otherwise.
     * @since 1.0.0
     */
    public static function load() {
        if ( null !== static::$instance ) {
            return false;
        }
        static::$instance = new self();
        static::$instance->register();
        return true;
    }
}