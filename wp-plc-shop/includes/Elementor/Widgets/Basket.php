<?php

use OnePlace\Shop\Modules\Basket;
use OnePlace\Shop\Plugin;

/**
 * Elementor Basket Widget
 *
 * @package   OnePlace\Shop\Modules
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/shop
 * @since 1.0.0
 */

class WPPLC_Shop_Basket extends \Elementor\Widget_Base {
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
    }

    public function get_name() {
        return 'wpplcshopbasket';
    }

    public function get_title() {
        return __('Basket & Checkout', 'wp-plc-shop');
    }

    public function get_icon() {
        return 'fa fa-shopping-basket';
    }

    public function get_categories() {
        return ['wpplc-shop'];
    }

    protected function render() {
        $aSettings = $this->get_settings_for_display();

        $iBasketPageID = get_option( 'plcshop_pages_basket' );
        $oBasketPage = get_post($iBasketPageID);
        $sBasketSlug = get_option('plcshop_basket_slug');

        /**
         * get current step from querystring
         */
        $sStep = 'invalidstep';
        global $wp_query;
        if(array_key_exists('checkoutstep',$wp_query->query_vars)) {
            $sStepQuery = $wp_query->query_vars['checkoutstep'];
            if($sStepQuery != '') {
                $sStep = $sStepQuery;
            }
        }

        // get onePlace Server URL
        $sHost = \OnePlace\Connect\Plugin::getCDNServerAddress();
        $bPaymentCancelled = false;

        require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/partials/basket_breadcrumb.php';

        if(get_option('plcshop_basket_maintenancemode') == 1 && !is_user_logged_in()) {
            require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/partials/basket_maintenance.php';
            exit();
        }

        switch($sStep) {
            case 'confirm':
                $aAPIData = [
                    'paymentmethod' => $_REQUEST['shop_paymentmethod'],
                ];
                $bPaymentCancelled = false;
                $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/basket/wordpress/confirm', ['shop_session_id'=>session_id()],$aAPIData);
                $oInfo = $oAPIResponse;
                require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/checkout/confirm.php';
                break;
            case 'cancel':
                $aAPIData = [
                    'paymentmethod' => $_REQUEST['shop_paymentmethod'],
                ];
                $bPaymentCancelled = true;
                $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/basket/wordpress/confirm', ['shop_session_id'=>session_id()],$aAPIData);
                $oInfo = $oAPIResponse;
                require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/checkout/confirm.php';
                break;
            case 'payment':
                $aAddressData = [];
                if(isset( $_REQUEST['address_firstname'])) {
                    $aAddressData = [
                        'salutation' => $_REQUEST['address_salutation'],
                        'firstname' => $_REQUEST['address_firstname'],
                        'lastname' => $_REQUEST['address_lastname'],
                        'email' => $_REQUEST['address_email'],
                        'phone' => $_REQUEST['address_phone'],
                        'street' => $_REQUEST['address_street'],
                        'zip' => $_REQUEST['address_zip'],
                        'city' => $_REQUEST['address_city'],
                        'deliverymethod' => $_REQUEST['address_deliverymethod'],
                        'comment' => $_REQUEST['address_comment'],
                    ];
                }
                $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/basket/wordpress/payment', ['shop_session_id'=>session_id()],$aAddressData);
                if($oAPIResponse->state == 'success') {
                    require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/checkout/payment.php';
                } else {
                    var_dump($oAPIResponse);
                    echo 'ERROR CONNECTING TO SHOP SERVER - PLEASE TRY AGAIN';
                }
                break;
            case 'address':
                $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/basket/wordpress/checkout', ['shop_session_id'=>session_id()]);
                $oContact = (isset($oAPIResponse->contact)) ? $oAPIResponse->contact : false;
                require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/checkout/address.php';
                break;
            case 'paymentsuccess':
                // check if it is a stripe payment
                if(isset($_REQUEST['session_id'])) {
                    $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/basket/wordpress/stripe', [
                        'shop_session_id'=>session_id(),
                        'session_id' => $_REQUEST['session_id'],
                        'payment_state' => 'done',
                    ]);

                    if($oAPIResponse->state == 'success') {
                        require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/checkout/payment_received.php';
                    } else {
                        require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/checkout/payment_failed.php';
                    }
                }
                break;
            case 'pay':
                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/basket/wordpress/initpayment', ['shop_session_id'=>session_id()]);
                    if($oAPIResponse->state == 'success') {
                        if(isset($oAPIResponse->paymentmethod->gateway)) {
                            switch($oAPIResponse->paymentmethod->gateway) {
                                case 'prepay':
                                    require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/payment/prepay.php';
                                    break;
                                case 'instore':
                                    require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/payment/instore.php';
                                    break;
                                case 'paypal':
                                    require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/payment/paypal.php';
                                    break;
                                case 'stripe':
                                    require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/payment/stripe.php';
                                    break;
                                default:
                                    require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/payment/invalid_gateway.php';
                                    break;
                            }
                        }
                        //require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/checkout/payment.php';
                    } else {
                        var_dump($oAPIResponse);
                        echo 'ERROR CONTACTING SHOP PAYMENT SERVICE';
                    }
                } else {
                    echo 'TRY AGAIN';
                    $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/basket/wordpress/confirm', ['shop_session_id'=>session_id()]);
                    $oInfo = $oAPIResponse;
                    require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/checkout/confirm.php';
                    break;
                }
                break;
            default:
                # Get Articles from onePlace API
                //$oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/basket/wordpress/get', ['shop_session_id'=>session_id()]);
                //$sMode = 'default';
                //$oBasket = $oAPIResponse->oBasket;

                require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/partials/basket_load.php';
                break;
        }
    }

    protected function _content_template() {

    }

    protected function _register_controls() {
        /**
         * Form Style
         */
        $this->start_controls_section(
            'section_form_style',
            [
                'label' => __('Basket - Form', 'wp-plc-shop'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'label' => __('Title Font', 'elementor'),
                'name' => 'basket_title_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-cart-summary-title,{{WRAPPER}} h4',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'label' => __('Content Font', 'elementor'),
                'name' => 'basket_content_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} td,{{WRAPPER}} th,{{WRAPPER}} p,{{WRAPPER}} label,{{WRAPPER}} span',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'label' => __('Button Font', 'elementor'),
                'name' => 'basket_button_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-shop-checkout-button',
            ]
        );

        $this->add_control(
            'basket_button_icon_color',
            [
                'label' => __('Button Icon Color', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .plc-shop-checkout-button i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'label' => __( 'Background', 'wp-plc-shop' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .plc-shop-form',
            ]
        );

        $this->add_responsive_control(
            'text_padding',
            [
                'label' => __('Padding', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .plc-shop-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // End Section
        $this->end_controls_section();

        /**
         * Breadcrumbs Style
         */
        $this->start_controls_section(
            'section_br_style',
            [
                'label' => __('Basket - Breadcrumbs', 'wp-plc-shop'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-shop-breadcrumb-nav',
            ]
        );

        // End Section
        $this->end_controls_section();

        /**
         * Payment List Style
         */
        $this->start_controls_section(
            'section_paymentlist_style',
            [
                'label' => __('Basket - Paymentmethods', 'wp-plc-shop'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'paymentlist_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} ul.plcShopPayList li',
            ]
        );

        $this->add_control(
            'paymentlist_icon_color',
            [
                'label' => __('Icon Color', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} ul.plcShopPayList li i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'paymentlist_border',
                'selector' => '{{WRAPPER}} ul.plcShopPayList li',
            ]
        );

        $this->add_responsive_control(
            'paymentlist_item_padding',
            [
                'label' => __('Padding', 'elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} ul.plcShopPayList li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // End Section
        $this->end_controls_section();

        /**
         * "Basket" Breadcrumb Settings
         */
        $this->start_controls_section(
            'section_breadcrumb_basket',
            [
                'label' => __('Breadcrumbs - "Basket"', 'wp-plc-shop'),
            ]
        );

        // Button Text
        $this->add_control(
            'br_basket_text',
            [
                'label' => __('Text', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Basket', 'wp-plc-shop'),
                'placeholder' => __('Basket', 'wp-plc-shop'),
            ]
        );

        // Button Icon
        $this->add_control(
            'br_basket_selected_icon',
            [
                'label' => __('Icon', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'default' => 'fas fa-shopping-basket',
                'fa4compatibility' => 'icon',
            ]
        );

        // End Section
        $this->end_controls_section();

        /**
         * "Delivery" Breadcrumb Settings
         */
        $this->start_controls_section(
            'section_breadcrumb_delivery',
            [
                'label' => __('Breadcrumbs - "Delivery"', 'wp-plc-shop'),
            ]
        );

        // Button Text
        $this->add_control(
            'br_delivery_text',
            [
                'label' => __('Text', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Delivery', 'wp-plc-shop'),
                'placeholder' => __('Delivery', 'wp-plc-shop'),
            ]
        );

        // Button Icon
        $this->add_control(
            'br_delivery_selected_icon',
            [
                'label' => __('Icon', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'default' => 'fas fa-truk',
                'fa4compatibility' => 'icon',
            ]
        );

        // End Section
        $this->end_controls_section();

        /**
         * "Payment" Breadcrumb Settings
         */
        $this->start_controls_section(
            'section_breadcrumb_payment',
            [
                'label' => __('Breadcrumbs - "Payment"', 'wp-plc-shop'),
            ]
        );

        // Button Text
        $this->add_control(
            'br_payment_text',
            [
                'label' => __('Text', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Payment', 'wp-plc-shop'),
                'placeholder' => __('Payment', 'wp-plc-shop'),
            ]
        );

        // Button Icon
        $this->add_control(
            'br_payment_selected_icon',
            [
                'label' => __('Icon', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'default' => 'fas fa-truck',
                'fa4compatibility' => 'icon',
            ]
        );

        // End Section
        $this->end_controls_section();

        /**
         * "Review" Breadcrumb Settings
         */
        $this->start_controls_section(
            'section_breadcrumb_review',
            [
                'label' => __('Breadcrumbs - "Review"', 'wp-plc-shop'),
            ]
        );

        // Button Text
        $this->add_control(
            'br_review_text',
            [
                'label' => __('Text', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Review', 'wp-plc-shop'),
                'placeholder' => __('Review', 'wp-plc-shop'),
            ]
        );

        // Button Icon
        $this->add_control(
            'br_review_selected_icon',
            [
                'label' => __('Icon', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'default' => 'fas fa-check',
                'fa4compatibility' => 'icon',
            ]
        );

        // End Section
        $this->end_controls_section();

        /**
         * "Checkout" Button Settings
         */
        $this->start_controls_section(
            'section_button_checkout',
            [
                'label' => __('Basket - Button "Checkout"', 'wp-plc-shop'),
            ]
        );

        // Button Text
        $this->add_control(
            'btn_checkout_text',
            [
                'label' => __('Text', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Checkout', 'wp-plc-shop'),
                'placeholder' => __('Checkout', 'wp-plc-shop'),
            ]
        );

        // Button Icon
        $this->add_control(
            'btn_checkout_selected_icon',
            [
                'label' => __('Icon', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'fa4compatibility' => 'icon',
            ]
        );

        // End Section
        $this->end_controls_section();

        /**
         * "Choose Payment Method" Button Settings
         */
        $this->start_controls_section(
            'section_button_address',
            [
                'label' => __('Basket - Button "Choose Payment"', 'wp-plc-shop'),
            ]
        );

        // Button Text
        $this->add_control(
            'btn_address_text',
            [
                'label' => __('Text', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Choose Paymentmethod', 'wp-plc-shop'),
                'placeholder' => __('Choose Paymentmethod', 'wp-plc-shop'),
            ]
        );

        // Button Icon
        $this->add_control(
            'btn_address_selected_icon',
            [
                'label' => __('Icon', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'fa4compatibility' => 'icon',
            ]
        );

        // End Section
        $this->end_controls_section();

        /**
         * "Confirm Order" Button Settings
         */
        $this->start_controls_section(
            'section_button_payment',
            [
                'label' => __('Basket - Button "Confirm Order"', 'wp-plc-shop'),
            ]
        );

        // Button Text
        $this->add_control(
            'btn_payment_text',
            [
                'label' => __('Text', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Confirm Order', 'wp-plc-shop'),
                'placeholder' => __('Confirm Order', 'wp-plc-shop'),
            ]
        );

        // Button Icon
        $this->add_control(
            'btn_payment_selected_icon',
            [
                'label' => __('Icon', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'fa4compatibility' => 'icon',
            ]
        );

        // End Section
        $this->end_controls_section();

        /**
         * "Prepay Info Text" Settings - START
         */
        $this->start_controls_section(
            'section_payment_prepay_infotext_settings',
            [
                'label' => __('Basket - Payment "Prepay"', 'wp-plc-shop'),
            ]
        );

        $this->add_control(
            'payment_prepay_infotext',
            [
                'label' => __( 'Text', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => __( 'Default description', 'wp-plc-shop' ),
                'placeholder' => __( 'Type your description here', 'wp-plc-shop' ),
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * "Prepay Info Text" Settings - END
         */

        /**
         * "Instore Info Text" Settings - START
         */
        $this->start_controls_section(
            'section_payment_instore_infotext_settings',
            [
                'label' => __('Basket - Payment "In Store"', 'wp-plc-shop'),
            ]
        );

        $this->add_control(
            'payment_instore_infotext',
            [
                'label' => __( 'Text', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => __( 'Default description', 'wp-plc-shop' ),
                'placeholder' => __( 'Type your description here', 'wp-plc-shop' ),
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * "Instore Info Text" Settings - END
         */

        /**
         * "Stripe Info Text" Settings - START
         */
        $this->start_controls_section(
            'section_payment_stripe_infotext_settings',
            [
                'label' => __('Basket - Payment "Stripe"', 'wp-plc-shop'),
            ]
        );

        $this->add_control(
            'payment_stripe_infotext',
            [
                'label' => __( 'Text', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => __( 'Default description', 'wp-plc-shop' ),
                'placeholder' => __( 'Type your description here', 'wp-plc-shop' ),
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * "Stripe Info Text" Settings - END
         */

        /**
         * Buttons Style
         */
        $this->start_controls_section(
            'section_buttons_style',
            [
                'label' => __('Basket & Checkout - Buttons', 'elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-shop-checkout-button',
            ]
        );

        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __('Normal', 'elementor'),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .plc-shop-checkout-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => __('Background Color', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .plc-shop-checkout-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __('Hover', 'elementor'),
            ]
        );

        $this->add_control(
            'hover_color',
            [
                'label' => __('Text Color', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plc-shop-checkout-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_hover_color',
            [
                'label' => __('Background Color', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plc-shop-checkout-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __('Border Color', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .plc-shop-checkout-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => __('Hover Animation', 'elementor'),
                'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .plc-shop-checkout-button',
            ]
        );
        $this->add_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .plc-shop-checkout-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .plc-shop-checkout-button',
            ]
        );
        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __('Padding', 'elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .plc-shop-checkout-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // End Section
        $this->end_controls_section();
    }
}