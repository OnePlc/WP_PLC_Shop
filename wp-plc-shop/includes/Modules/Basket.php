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
    private $bMenuAdded = false;

    /**
     * Shop Elementor Integration
     *
     * @since 1.0.0
     */
    public function register() {
        // enqueue slider custom scripts for frontend
        add_action( 'wp_enqueue_scripts', [$this,'enqueueScripts'] );

        # Register AJAX Hooks
        add_action('wp_ajax_nopriv_plc_showbasket', [ $this, 'showBasket' ] );
        add_action('wp_ajax_plc_showbasket', [ $this, 'showBasket' ] );

        add_action('wp_ajax_nopriv_plc_addtobasket', [ $this, 'addToBasket' ] );
        add_action('wp_ajax_plc_addtobasket', [ $this, 'addToBasket' ] );

        add_action('wp_ajax_nopriv_plc_popupbasket', [ $this, 'showPopupBasket' ] );
        add_action('wp_ajax_plc_popupbasket', [ $this, 'showPopupBasket' ] );

        add_action('wp_ajax_nopriv_plc_popupgift', [ $this, 'showPopupGift' ] );
        add_action('wp_ajax_plc_popupgift', [ $this, 'showPopupGift' ] );

        add_action('wp_ajax_nopriv_plc_basketupdatepos', [ $this, 'updateBasketPosition' ] );
        add_action('wp_ajax_plc_basketupdatepos', [ $this, 'updateBasketPosition' ] );

        add_action( 'init', [$this, 'startShopSession']);

        // add shop custom rewrites
        add_action('init', [$this,'registerRewriteRules'], 10, 0);

        // Add Icon to Basket
        if(get_option('plcshop_basket_icon_menu')) {
            // add cart icon to main menu
            add_filter( 'wp_nav_menu_items', [$this,'shopMenuIcon'], 10, 2 );
        }
    }

    public function startShopSession() {
        if(!session_id()) {
            session_start();
        }
    }

    /**
     * Remove Basket Position
     *
     * @since 1.0.0
     */
    public function updateBasketPosition() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(array_key_exists('position_id',$_REQUEST)) {
                $iPosID = (int)$_REQUEST['position_id'];
                $sPosAction = $_REQUEST['position_mode'];
                $aSettings = [
                    'btn_checkout_text' => 'Zur Kasse',
                    'btn_checkout_selected_icon' => ['value' => 'fas fa-cash-register'],
                ];
                switch($sPosAction) {
                    case 'remove':
                        $aParams = ['position_id' => $iPosID, 'shop_session_id' => session_id()];
                        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/basket/wordpress/remove', $aParams);
                        $sHost = \OnePlace\Connect\Plugin::getCDNServerAddress();
                        $sMode = 'default';
                        if($oAPIResponse->state == 'success') {
                            $oBasket = $oAPIResponse->basket;
                            require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/partials/basket.php';
                        } else {
                            echo 'ERROR CONNECTING TO SHOP BASKET SERVER';
                        }
                        break;
                    case 'update':
                        $aParams = [
                            'position_id' => $iPosID,
                            'shop_session_id' => session_id(),
                            'position_amount' => (int)$_REQUEST['position_amount']];
                        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/basket/wordpress/update', $aParams);
                        $sHost = \OnePlace\Connect\Plugin::getCDNServerAddress();
                        $sMode = 'default';
                        if($oAPIResponse->state == 'success') {
                            $oBasket = $oAPIResponse->basket;
                            require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/partials/basket.php';
                        } else {
                            echo 'ERROR CONNECTING TO SHOP BASKET SERVER';
                        }
                        break;
                    default:
                        echo 'INVALID ACTION';
                        break;
                }
            } else {
                echo 'NO POSITION ID FOUND';
            }
        } else {
            echo 'NOT ALLOWED';
        }
        exit();
    }

    /**
     * Register Basket and Checkout Rewrite Rules
     *
     * @since 1.0.0
     */
    public function registerRewriteRules() {
        // Shop Filters
        add_rewrite_tag('%checkoutstep%', '([^&]+)');

        // Shop Basket
        $iBasketPageID = get_option( 'plcshop_pages_basket' );
        $sBasketSlug = get_option('plcshop_basket_slug');

        // Shop Checkout
        add_rewrite_rule('^'.$sBasketSlug.'/([^/]*)/?','index.php?page_id='.$iBasketPageID.'&checkoutstep=$matches[1]','top');
        add_rewrite_rule('^'.$sBasketSlug.'/?','index.php?page_id='.$iBasketPageID,'top');
    }

    /**
     * Show Basket (AJAX)
     */
    public function showBasket()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(isset($_REQUEST['plc_basket_step'])) {
                $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/basket/wordpress/initpayment', ['shop_session_id'=>session_id()]);
                $sHost = \OnePlace\Connect\Plugin::getCDNServerAddress();
                $sMode = 'default';
                if($oAPIResponse->state == 'success') {
                    $aSettings = [];
                    $aSettings['payment_paypal_infotext'] = 'Vielen Dank für Ihre Bestellung. Wir haben Ihnen per E-Mail eine Bestätigung Ihrer Bestellung zukommen lassen. Vielen Dank für Ihre Zahlung per Paypal. Ihre Bestellung ist bereits in kurzer Zeit unterwegs zu Ihnen.';
                    require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/payment/paypal.php';
                } else {
                    echo 'ERROR CONNECTING TO SHOP BASKET SERVER';
                    echo '<pre>'.var_dump($oAPIResponse).'</pre>';
                }
            } else {
                $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/basket/wordpress/get', ['shop_session_id'=>session_id()]);
                $sHost = \OnePlace\Connect\Plugin::getCDNServerAddress();
                $sMode = 'default';
                if($oAPIResponse->state == 'success') {
                    $oBasket = $oAPIResponse->oBasket;
                    $aSettings = [
                        'btn_checkout_text' => 'Zur Kasse',
                        'btn_checkout_selected_icon' => ['value' => 'fas fa-cash-register'],
                    ];
                    require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/partials/basket.php';
                } else {
                    echo 'ERROR CONNECTING TO SHOP BASKET SERVER';
                }
            }
        }
        exit();
    }
    /**
     * Popup to Add Item to Basket (AJAX)
     *
     * @since 1.0.0
     */
    public function showPopupBasket() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $iItemID = $_REQUEST['shop_item_id'];
            $iVariantID = (isset($_REQUEST['shop_item_variant_id'])) ? (int)$_REQUEST['shop_item_variant_id'] : 0;
            $sItemType = $_REQUEST['shop_item_type'];

            # Get Articles from onePlace API
            $oAPIResponse = (object)['state' => 'error'];
            switch($sItemType) {
                case 'article':
                case 'variant':
                    $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/get/'.$iItemID, ['listmode'=>'entity']);
                    break;
                case 'event':
                    $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/event/api/get/'.$iItemID, ['listmode'=>'entity']);
                    break;
                default:
                    break;
            }

            // Set Language for all Date functions
            $sLang = 'en_US';
            if (defined('ICL_LANGUAGE_CODE')) {
                if (ICL_LANGUAGE_CODE == 'en') {
                    $sLang = 'en_US';
                }
                if (ICL_LANGUAGE_CODE == 'de') {
                    $sLang = 'de_DE';
                }
                $aParams['lang'] = $sLang;
            }
            setlocale(LC_TIME, $sLang);


            if ($oAPIResponse->state == 'success') {
                $oItem = $oAPIResponse->oItem;
                require __DIR__.'/../view/partials/popup_basket.php';
            } else {
                echo 'ERROR CONNECTING TO SHOP SERVER';
                var_dump($oAPIResponse);
            }


        }
        exit();
    }

    /**
     * Popup to Add Gift to Basket (AJAX)
     *
     * @since 1.0.0
     */
    public function showPopupGift() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $iItemID = $_REQUEST['shop_item_id'];
            $sItemType = $_REQUEST['shop_item_type'];

            # Get Articles from onePlace API
            # Get Articles from onePlace API
            $oAPIResponse = (object)['state' => 'error'];
            switch($sItemType) {
                case 'article':
                case 'variant':
                    $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/get/'.$iItemID, ['listmode'=>'entity']);
                    break;
                case 'event':
                    $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/event/api/get/'.$iItemID, ['listmode'=>'entity']);
                    break;
                default:
                    break;
            }

            // Set Language for all Date functions
            $sLang = 'en_US';
            if (defined('ICL_LANGUAGE_CODE')) {
                if (ICL_LANGUAGE_CODE == 'en') {
                    $sLang = 'en_US';
                }
                if (ICL_LANGUAGE_CODE == 'de') {
                    $sLang = 'de_DE';
                }
                $aParams['lang'] = $sLang;
            }
            setlocale(LC_TIME, $sLang);

            if ($oAPIResponse->state == 'success') {
                $oItem = $oAPIResponse->oItem;
                require __DIR__.'/../view/partials/popup_gift.php';
            } else {
                echo 'ERROR CONNECTING TO SHOP SERVER';
            }
        }
        exit();
    }

    /**
     * Add Item to Basket (AJAX)
     *
     * @since 1.0.0
     */
    public function addToBasket() {
        # only execute if started from our javascript
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $iItemID = $_REQUEST['shop_item_id'];
            $sItemType = $_REQUEST['shop_item_type'];
            $fItemAmount = $_REQUEST['shop_item_amount'];
            $fCustomPrice = $_REQUEST['shop_item_customprice'];
            $sItemComment = $_REQUEST['shop_item_comment'];
            $iRefID = (isset($_REQUEST['shop_item_ref_idfs'])) ? (int)$_REQUEST['shop_item_ref_idfs'] : 0;
            $sRefType = isset($_REQUEST['shop_item_ref_type']) ? $_REQUEST['shop_item_ref_type'] : 'none';

            # Get Articles from onePlace API
            $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/basket/wordpress/add', [
                'shop_session_id' => session_id(),
                'shop_item_id' => (int)$iItemID,
                'shop_item_type' => $sItemType,
                'shop_item_amount' => (float)$fItemAmount,
                'shop_item_comment' => $sItemComment,
                'shop_item_customprice' => $fCustomPrice,
                'shop_item_ref_idfs' => $iRefID,
                'shop_item_ref_type' => $sRefType,
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
     * Cart icon for wordpress navigation
     *
     * @since 1.0.0
     * @param $items
     * @param $args
     * @return string
     */
    public function shopMenuIcon($items, $args) {
        # get all menus and check if we have to attach icon to one
        $sMenuLocation = get_option( 'plcshop_basket_icon_menu' );
        $theme_locations = get_nav_menu_locations();
        $menu_obj = get_term( $theme_locations[$sMenuLocation], 'nav_menu' );
        $menu_name = $menu_obj->name;

        # the function runs twice as it seems - we only want to add and run our code once
        if (strtolower($args->menu->name) == strtolower($menu_name) && !$this->bMenuAdded) {
            # get basket slug from settings
            $sBasketSlug = (get_option('plcshop_basket_slug')) ? get_option('plcshop_basket_slug') : 'basket';
            # only run code once
            $this->bMenuAdded = true;

            # get basket from oneplace
            $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/basket/wordpress/get', ['shop_session_id'=>session_id()]);
            $iCount = 0;
            if($oAPIResponse->state == 'success') {
                $iCount = $oAPIResponse->amount;
            }

            $items .= '<li class="menu-item menu-item-type-custom menu-item-object-custom" style="padding-top:12px; background:transparent;">';
            $items .= '<a href="/'.$sBasketSlug.'" style="margin:0 12px 0 12px; padding:0;">';
            $items .= '<i class="fas fa-shopping-cart shop-badge" style="color:#626261;"></i>';
            $items .= ' <span class="plc-shop-badge-counter" style="right:-12px; bottom:-10px; position:absolute; width:16px; height:16px; background:red; padding-left:4px; border-radius: 50%; line-height:16px; color:#fff; font-size:12px;">';
            $items .= (int)$iCount.'</span></a></li>';
            $items .= '</li>';
        }
        return $items;
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
            'shopBasketSlug' => get_option('plcshop_basket_slug'),
            'popupButtonBackground' => get_option('plcshop_popup_buybutton_background'),
            'shopCurrency' => get_option('plcshop_currency_main')
        ] );
        wp_enqueue_script( 'sweet-alert', 'https://cdn.jsdelivr.net/npm/sweetalert2@9', [ 'jquery' ] );
        wp_enqueue_script( 'jquery-mask', 'https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.8/dist/jquery.mask.min.js', [ 'jquery' ] );

        wp_enqueue_style( 'jquery-intltel', 'https://cdn.jsdelivr.net/npm/intl-tel-input@16.0.7/build/css/intlTelInput.min.css');
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