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

        // add custom header scripts
        add_action( 'wp_head', [$this,'loadHeaderScript'] );

        add_action( 'wp_body_open', [$this,'shopInfoPopup'] );

    }

    public function shopInfoPopup() {
        if(!isset($_SESSION['shop-popup-showed'])) {
            $_SESSION['shop-popup-showed'] = true;
            ?>
            <div id="myModal" class="modal">

                <!-- Modal content -->
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h3 style="color: #8c857d; font-family: 'Playfair Display', Sans-serif; font-weight: normal;">
                        Aktuelle News</h3>
                    <p style="font-family: 'Function Pro Book', Sans-seif;">
                        Liebe Gäste und Freunde des Schwitzer's,<br/>
                        <br/>
                        aufgrund der aktuellen Situation haben all unsere Restaurants <b>geschlossen</b>.<br/>
                        <br/>
                        Jedoch besteht die Möglichkeit unsere Speisen täglich zwischen 12.00 Uhr und <br/>
                        20 Uhr zum Mitnehmen für Zuhause zu bestellen.
                        <br/>Entdecken Sie hier unsere Speisen zum Mitnehmen
                        <br/><br/>
                        <b>Vorbestellung To-Go-Service</b><br/>
                        Telefonisch: 07243 354 850<br/>
                        Per E-Mail: info@schwitzers.com<br/>
                        Über WhatsApp: 00491717840526<br/>
                        <br/>
                        Wir wünschen allen unseren treuen und loyalen Mitarbeitern in dieser Zeit bestes
                        Durchhaltevermögen und ganz viel Kraft!
                        <br/>Vielen Dank unseren lieben Gästen und unseren treuen Partnern, wir wünschen allen beste
                        Gesundheit und bis ganz bald!
                        <br/>
                        Herzliche Grüße,<br/>
                        Stephanie & Cédric Schwitzer und Johannes Rupp</p>
                </div>

            </div>
            <?php
        }
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
        register_setting( 'wpplc_shop', 'plcshop_basket_maintenancemode', false );
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
     * Load header scripts
     * set variables for other scripts
     *
     * @since 1.0.0
     */
    public function loadHeaderScript() {
        $sBasketBreadColor = (!empty(get_option('plcshop_basket_steps_color'))) ? get_option('plcshop_basket_steps_color') : 'green';

        $sBuyIconColor = (!empty(get_option('plcshop_popup_buybutton_iconcolor'))) ? get_option('plcshop_popup_buybutton_iconcolor') : 'black';
        $sGiftIconColor = (!empty(get_option('plcshop_popup_giftbutton_iconcolor'))) ? get_option('plcshop_popup_giftbutton_iconcolor') : 'black';

        $sPopupBg = (!empty(get_option('plcshop_popup_content_background'))) ? get_option('plcshop_popup_content_background') : '#fff';
        $sPopupBorderColor = (!empty(get_option('plcshop_popup_content_bordercolor'))) ? get_option('plcshop_popup_content_bordercolor') : '#eee';
        ?>
        <style>
            .plc-shop-popup-content {
                background:<?=$sPopupBg?>; width:100%; display: inline-block; padding:12px;
            }
            .swal2-popup {
                background:<?=$sPopupBorderColor?> !important;
            }
            .plc-shop-popup-gift-icon {
                color:<?=$sGiftIconColor?>;
            }
            .plc-shop-popup-buy-icon {
                color:<?=$sBuyIconColor?>;
            }
            .cd-breadcrumb li.current > *, .cd-multi-steps li.current > * {
                /* selected step */
                color: <?=$sBasketBreadColor?>;
            }
            .no-touch .cd-breadcrumb a:hover, .no-touch .cd-multi-steps a:hover {
                /* steps already visited */
                color: <?=$sBasketBreadColor?>;
            }
            .cd-breadcrumb.triangle li.current > * {
                /* selected step */
                color: #ffffff;
                background-color: <?=$sBasketBreadColor?>;
                border-color: <?=$sBasketBreadColor?>;
            }
            .cd-multi-steps li.visited::after {
                background-color: <?=$sBasketBreadColor?>;
            }
            .cd-multi-steps.text-center li.current > *, .cd-multi-steps.text-center li.visited > * {
                color: #ffffff;
                background-color: <?=$sBasketBreadColor?>;
            }
            .cd-multi-steps.text-top li.visited > *::before,
            .cd-multi-steps.text-top li.current > *::before, .cd-multi-steps.text-bottom li.visited > *::before,
            .cd-multi-steps.text-bottom li.current > *::before {
                background-color: <?=$sBasketBreadColor?>;
            }
            .no-touch .cd-multi-steps.text-top a:hover, .no-touch .cd-multi-steps.text-bottom a:hover {
                color: <?=$sBasketBreadColor?>;
            }

            .plc-shop-popup-control {
                background:#ebebeb;
                border-radius: 0 !important;
                border:0 !important;
                color:#666;
                padding:6px;
            }

            button.swal2-styled, a.plc-shop-popup-button {
                background:<?=(!empty(get_option('plcshop_popup_buybutton_background'))) ? get_option('plcshop_popup_buybutton_background') : '#fff'?> !important;
                border-radius: 0 !important;
                border:0 !important;
                font-size: 16px !important;
                color:<?=(!empty(get_option('plcshop_popup_buybutton_color'))) ? get_option('plcshop_popup_buybutton_color') : '#000'?> !important;
                padding:12px;
                font-family: <?=(!empty(get_option('plcshop_popup_buybutton_fontfamily'))) ? get_option('plcshop_popup_buybutton_fontfamily') : 'sans-serif'?>;
                text-transform: uppercase;
            }

            button.swal2-styled:hover, a.plc-shop-popup-button:hover, a.plc-slider-button:hover, .plc-shop-checkout-button:hover {
                background: <?=(!empty(get_option('plcshop_popup_button_hover_color'))) ? get_option('plcshop_popup_button_hover_color') : '#eee'?> !important;
            }

            #swal2-content h1,#swal2-content h2,#swal2-content h3,#swal2-content h4 {
                font-family: <?=(!empty(get_option('plcshop_popup_title_fontfamily'))) ? get_option('plcshop_popup_title_fontfamily') : 'sans-serif'?> !important;
                color:<?=(!empty(get_option('plcshop_popup_title_color'))) ? get_option('plcshop_popup_title_color') : '#000'?> !important;
                font-weight:normal !important;
            }

            #swal2-content h4 {
                font-size:20px;
            }

            #swal2-content, #swal2-content a, #swal2-content p,#swal2-content span,#swal2-content div,#swal2-content b {
                font-family: <?=(!empty(get_option('plcshop_popup_content_fontfamily'))) ? get_option('plcshop_popup_content_fontfamily') : 'sans-serif'?> !important;
                color:<?=(!empty(get_option('plcshop_popup_content_color'))) ? get_option('plcshop_popup_content_color') : '#000'?>;
                font-weight:normal !important;
                font-size:16px;
            }

            #swal2-content input, #swal2-content select, #swal2-content textarea, .plc-article-slider-box-content select  {
                background:<?=(!empty(get_option('plcshop_popup_input_background'))) ? get_option('plcshop_popup_input_background') : '#fff'?>;
                color:<?=(!empty(get_option('plcshop_popup_input_color'))) ? get_option('plcshop_popup_input_color') : '#000'?>;
                border:0;
            }
        </style>
        <style>
            /* The Modal (background) */
            .modal {
                display: none; /* Hidden by default */
                position: fixed; /* Stay in place */
                z-index: 1; /* Sit on top */
                left: 0;
                top: 0;
                width: 100%; /* Full width */
                height: 100%; /* Full height */
                overflow: auto; /* Enable scroll if needed */
                background-color: rgb(0,0,0); /* Fallback color */
                background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            }

            /* Modal Content/Box */
            .modal-content {
                background-color: #fefefe;
                margin: 15% auto; /* 15% from the top and centered */
                padding: 20px;
                border: 1px solid #888;
                width: 80%; /* Could be more or less, depending on screen size */
                max-width:600px;
                min-width:400px;
            }

            /* The Close Button */
            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }

            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }
        </style>
        <script>
            jQuery(function() {
                // Get the modal
                var modal = document.getElementById("myModal");
                if(modal != undefined) {

                    // Get the <span> element that closes the modal
                    var span = document.getElementsByClassName("close")[0];

                    modal.style.display = "block";

                    // When the user clicks on <span> (x), close the modal
                    span.onclick = function () {
                        modal.style.display = "none";
                    }

                    // When the user clicks anywhere outside of the modal, close it
                    window.onclick = function (event) {
                        if (event.target == modal) {
                            modal.style.display = "none";
                        }
                    }
                }
            });
        </script>
        <?php
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