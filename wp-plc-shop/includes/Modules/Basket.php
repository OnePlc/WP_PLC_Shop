<?php

/**
 * Shop Basket
 *
 * @package   OnePlace\Shop\Modules
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/shop
 * @since 1.0.0
 */

namespace OnePlace\Shop\Modules;

use OnePlace\Shop\Plugin;

final class Basket {
    /**
     * Main instance of the module
     *
     * @var Plugin|null
     * @since 1.0.0
     */
    private static $instance = null;

    /**
     * Shop Elementor Integration
     *
     * @since 1.0.0
     */
    public function register() {
        // enqueue slider custom scripts for frontend
        add_action( 'wp_enqueue_scripts', [$this,'enqueueScripts'] );

        # Register AJAX Hooks
        add_action('wp_ajax_plc_showbasket', [ $this, 'showBasket' ] );
        add_action('wp_ajax_plc_addtobasket', [ $this, 'addToBasket' ] );
    }

    /**
     * Add Item to Basket (AJAX)
     *
     * @since 1.0.0
     */
    public function addToBasket() {
        # only execute if started from our javascript
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(!session_id()) {
                session_start();
            }

            $iItemID = $_REQUEST['shop_item_id'];
            $sItemType = $_REQUEST['shop_item_type'];

            # Get Articles from onePlace API
            $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/basket/wordpress/add', [
                'shop_session_id' => session_id(),
                'shop_item_id' => (int)$iItemID,
                'shop_item_type' => $sItemType,
            ]);

            if ($oAPIResponse->state == 'success') {
                echo $oAPIResponse->message;
            } else {
                echo 'no json success';
            }
        }

        # Don't forget to always exit in the ajax function.
        exit();
    }

    /**
     * Show Basket (Ajax)
     *
     * @since 1.0.0
     */
    public function showBasket() {
        # only execute if started from our javascript
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(!session_id()) {
                session_start();
            }

            # Get Articles from onePlace API
            $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/basket/wordpress/get', ['shop_session_id'=>session_id()]);

            echo session_id();


            if ($oAPIResponse->state == 'success') {
                echo $oAPIResponse->message;
            } else {
                echo 'no json success';
            }
        }

        # Don't forget to always exit in the ajax function.
        exit();
    }

    /**
     * Enqueue Elementor Widget Frontend Custom Scripts
     *
     * @since 1.0.0
     */
    public function enqueueScripts() {
        wp_enqueue_script( 'plc-shop-basket', plugins_url('assets/js/basket.js', WPPLC_SHOP_PLUGIN_MAIN_FILE), [ 'jquery' ] );
        wp_localize_script( 'plc-shop-basket', 'basketAjax', [
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'pluginUrl' => plugins_url('',WPPLC_SHOP_PLUGIN_MAIN_FILE),
        ] );
    }

    /**
     * Loads the module main instance and initializes it.
     *
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