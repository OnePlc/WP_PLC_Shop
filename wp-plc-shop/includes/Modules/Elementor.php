<?php

/**
 * Shop Elementor Widgets
 *
 * @package   OnePlace\Shop\Modules
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/shop
 * @since 1.0.0
 */

namespace OnePlace\Shop\Modules;

use OnePlace\Shop\Plugin;

final class Elementor {
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
        // Register Settings
        add_action( 'admin_init', [ $this, 'registerSettings' ] );

        // create category for elementor
        add_action( 'elementor/elements/categories_registered', [$this,'addElementorWidgetCategories'] );

        // register elementor widgets
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'initElementorWidgets' ] );

        // enqueue slider custom scripts for frontend
        add_action( 'wp_enqueue_scripts', [$this,'enqueueScripts'] );
    }

    /**
     * Enqueue Elementor Widget Frontend Custom Scripts
     *
     * @since 1.0.0
     */
    public function enqueueScripts() {
        if(get_option('plcshop_elementor_widget_article_slider_active') == 1) {
            wp_enqueue_script('shop-article-slider', plugins_url('assets/js/article-slider.js', WPPLC_SHOP_PLUGIN_MAIN_FILE), ['jquery']);
            //wp_enqueue_style( 'shop-article-slider-style', '/wp-content/plugins/wp-plc-shop/assets/css/article-slider.css');
        }
        // ##SWAL2BTNBG##
        wp_enqueue_style( 'shop-base-style', plugins_url('assets/css/shop-base-style.css', WPPLC_SHOP_PLUGIN_MAIN_FILE));
    }

    /**
     * Initialize Elementor Widgets if activated
     *
     * @since 1.0.0
     */
    public function initElementorWidgets() {
        // Basket Widget
        if(get_option('plcshop_elementor_widget_basket_active') == 1) {
            require_once(__DIR__ . '/../Elementor/Widgets/Basket.php');
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \WPPLC_Shop_Basket());
        }

        // Article Slider Widget
        if(get_option('plcshop_elementor_widget_article_slider_active') == 1) {
            require_once(__DIR__ . '/../Elementor/Widgets/Slider.php');
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \WPPLC_Shop_Slider());
        }

        // Article Showcase Widget
        if(get_option('plcshop_elementor_widget_showcase_active') == 1) {
            require_once(__DIR__ . '/../Elementor/Widgets/Showcase.php');
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \WPPLC_Shop_Showcase());
        }

        // Article Single Box Widget
        if(get_option('plcshop_elementor_widget_featuredbox_active') == 1) {
            require_once(__DIR__ . '/../Elementor/Widgets/Singlebox.php');
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \WPPLC_Shop_Singlebox());
        }

        // Article List Widget
        if(get_option('plcshop_elementor_widget_list_active') == 1) {
            require_once(__DIR__ . '/../Elementor/Widgets/List.php');
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \WPPLC_Shop_List());
        }
    }

    /**
     * Create Category for our Elementor
     * Widgets
     *
     * @since 1.0.0
     */
    public function addElementorWidgetCategories( $elements_manager ) {
        $elements_manager->add_category(
            'wpplc-shop',
            [
                'title' => __( 'onePlace Shop', 'wp-plc-shop' ),
                'icon' => 'fa fa-shopping-basket',
            ]
        );
    }

    /**
     * Register Elementor specific settings
     *
     * @since 1.0.0
     */
    public function registerSettings() {
        // Widgets
        register_setting( 'wpplc_shop_elementor', 'plcshop_elementor_widget_article_slider_active', false );
        register_setting( 'wpplc_shop_elementor', 'plcshop_elementor_widget_basket_active', false );
        register_setting( 'wpplc_shop_elementor', 'plcshop_elementor_widget_featuredbox_active', false );

        // Popup Settings
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_buybutton_enable', false );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_buybutton_text', 'Buy' );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_buybutton_fontfamily', '' );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_buybutton_background', '#fff' );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_buybutton_color', '#000' );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_buybutton_icon', '' );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_buybutton_iconcolor', '#000' );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_buybutton_transform', 'none' );

        register_setting( 'wpplc_shop_popup', 'plcshop_popup_giftbutton_enable', false );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_giftbutton_text', 'Gift' );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_giftbutton_fontfamily', '' );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_giftbutton_background', '#fff' );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_giftbutton_color', '#000' );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_giftbutton_icon', '' );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_giftbutton_iconcolor', '#000' );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_giftbutton_transform', 'none' );

        register_setting( 'wpplc_shop_popup', 'plcshop_popup_emailbutton_enable', false );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_emailbutton_text', 'E-Mail' );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_emailbutton_fontfamily', '' );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_emailbutton_background', '#fff' );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_emailbutton_color', '#000' );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_emailbutton_icon', '' );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_emailbutton_iconcolor', '#000' );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_emailbutton_transform', 'none' );

        register_setting( 'wpplc_shop_popup', 'plcshop_popup_title_color', false );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_title_fontfamily', false );

        register_setting( 'wpplc_shop_popup', 'plcshop_popup_content_bordercolor', false );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_content_background', false );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_content_color', false );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_content_fontfamily', false );

        register_setting( 'wpplc_shop_popup', 'plcshop_popup_input_color', false );
        register_setting( 'wpplc_shop_popup', 'plcshop_popup_input_background', false );

        register_setting( 'wpplc_shop_popup', 'plcshop_popup_button_hover_color', false );
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