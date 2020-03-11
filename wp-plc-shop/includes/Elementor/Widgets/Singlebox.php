<?php
/**
 * Elementor Article Single Box Widget
 *
 * @package   OnePlace\Shop\Modules
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/shop
 * @since 1.0.0
 */

class WPPLC_Shop_Singlebox extends \Elementor\Widget_Base {
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
    }

    public function get_name() {
        return 'wpplcshopsinglebox';
    }

    public function get_title() {
        return __('Article Box', 'wp-plc-shop');
    }

    public function get_icon() {
        return 'fa fa-image';
    }

    public function get_categories() {
        return ['wpplc-shop'];
    }

    protected function render() {
        $aSettings = $this->get_settings_for_display();

        $iArticleID = $aSettings['featured_product_id'];

        # Get Articles from onePlace API
        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/get/'.$iArticleID, ['listmode'=>'entity']);
        $sHost = \OnePlace\Connect\Plugin::getCDNServerAddress();

        if ($oAPIResponse->state == 'success') {
            $oItem = $oAPIResponse->oItem;

            require_once WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/partials/article_singlebox.php';
        } else {
            var_dump($oAPIResponse);
            echo 'ERROR CONNECTING TO SHOP SERVER';
        }
    }

    protected function _content_template() {

    }

    protected function _register_controls() {
        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/list/0', ['listmode'=>'entity','listmodefilter'=>'webonly']);
        $aArticles = [];
        if ($oAPIResponse->state == 'success') {
            foreach($oAPIResponse->results as $oArt) {
                $aArticles[$oArt->id] = $oArt->label;
            }
        }

        /**
         * General Settings - START
         */
        $this->start_controls_section(
            'featured_product_settings',
            [
                'label' => __( 'Featured Product', 'wp-plc-shop' ),
            ]
        );

        $this->add_control(
            'featured_product_id',
            [
                'label' => __( 'Gutschein', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 1,
                'options' => $aArticles,
            ]
        );

        $this->add_responsive_control(
            'featured_image_height',
            [
                'label' => __( 'Bildhöhe', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 500,
                        'step' => 1,
                    ],
                ],
                'desktop_default' => [
                    'size' => 200,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 200,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 120,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .plc-shop-featured-product-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'featured_product_show_desc',
            [
                'label' => __( 'Beschreibung anzeigen', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'no',
                'options' => [
                    'no' => __( 'No', 'wp-plc-shop' ),
                    'yes' => __( 'Yes', 'wp-plc-shop' ),
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * General Settings - END
         */

        /**
         * Custom Amount Settings - START
         */
        $this->start_controls_section(
            'featured_custom_amount',
            [
                'label' => __( 'Product - Individual Amount', 'wp-plc-shop' ),
            ]
        );

        $this->add_control(
            'featured_custom_amount_active',
            [
                'label' => __( 'Aktiviert', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'no',
                'options' => [
                    'no' => __( 'No', 'wp-plc-shop' ),
                    'yes' => __( 'Yes', 'wp-plc-shop' ),
                ],
            ]
        );

        $this->add_control(
            'featured_custom_amount_min',
            [
                'label' => __( 'Min Amount', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 1000,
                'step' => 1,
                'default' => 10,
            ]
        );

        $this->add_control(
            'featured_custom_amount_max',
            [
                'label' => __( 'Max Amount', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 1000,
                'step' => 1,
                'default' => 100,
            ]
        );

        $this->add_control(
            'featured_custom_amount_steps',
            [
                'label' => __( 'Steps', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 1000,
                'step' => 1,
                'default' => 5,
            ]
        );

        $this->end_controls_section();
        /**
         * Custom Amount Settings - END
         */

        /**
         * Custom Text Settings - START
         */
        $this->start_controls_section(
            'featured_custom_text',
            [
                'label' => __( 'Product - Custom Text', 'wp-plc-shop' ),
            ]
        );

        $this->add_control(
            'featured_custom_text_active',
            [
                'label' => __( 'Aktiviert', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'no',
                'options' => [
                    'no' => __( 'No', 'wp-plc-shop' ),
                    'yes' => __( 'Yes', 'wp-plc-shop' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'featured_custom_text_margin',
            [
                'label' => __( 'Margin', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-shop-singlebox-customtext' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
        /**
         * Custom Text Settings - END
         */

        /**
         * Buy Button Settings - START
         */
        $this->start_controls_section(
            'section_button_buy',
            [
                'label' => __('Slider Item - Button "Buy"', 'wp-plc-shop'),
            ]
        );

        $this->add_control(
            'btn1_active',
            [
                'label' => __( 'Aktiviert', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'no',
                'options' => [
                    'no' => __( 'No', 'wp-plc-shop' ),
                    'yes' => __( 'Yes', 'wp-plc-shop' ),
                ],
            ]
        );

        // Button Text
        $this->add_control(
            'btn1_text',
            [
                'label' => __('Text', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Buy', 'wp-plc-shop'),
                'placeholder' => __('Buy', 'wp-plc-shop'),
            ]
        );

        // Button Icon
        $this->add_control(
            'btn1_selected_icon',
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
         * Buy Button Settings - END
         */

        /**
         * Gift Button Settings - START
         */
        $this->start_controls_section(
            'section_button_gift',
            [
                'label' => __('Slider Item - Button "Gift"', 'wp-plc-shop'),
            ]
        );

        $this->add_control(
            'btn2_active',
            [
                'label' => __( 'Aktiviert', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'no',
                'options' => [
                    'no' => __( 'No', 'wp-plc-shop' ),
                    'yes' => __( 'Yes', 'wp-plc-shop' ),
                ],
            ]
        );

        // Button Text
        $this->add_control(
            'btn2_text',
            [
                'label' => __('Text', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Gift', 'wp-plc-shop'),
                'placeholder' => __('Gift', 'wp-plc-shop'),
            ]
        );

        // Button Icon
        $this->add_control(
            'btn2_selected_icon',
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
         * Gift Button Settings - END
         */

        /**
         * Buttons Style Settings - START
         */
        $this->start_controls_section(
            'section_buttons_style',
            [
                'label' => __('Slider Item - Buttons', 'elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-slider-button',
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
                    '{{WRAPPER}} a.plc-slider-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} a.plc-slider-button' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} a.plc-slider-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_hover_color',
            [
                'label' => __('Background Color', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.plc-slider-button:hover' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} a.plc-slider-button:hover' => 'border-color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} a.plc-slider-button',
            ]
        );
        $this->add_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} a.plc-slider-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .plc-slider-button',
            ]
        );
        $this->add_responsive_control(
            'text_padding',
            [
                'label' => __('Padding', 'elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} a.plc-slider-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        // End Section
        $this->end_controls_section();
        /**
         * Buttons Style Settings - END
         */

        /**
         * Price Style Settings - START
         */
        $this->start_controls_section(
            'slider_variant_selector',
            [
                'label' => __( 'Preis Auswahl', 'elementor' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'slider_variant_selector_typo',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} select',
            ]
        );

        $this->add_control(
            'slider_variant_selector_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} select' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'slider_variant_selector_background',
            [
                'label' => __( 'Background', 'elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} select' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'slider_variant_selector_border',
                'selector' => '{{WRAPPER}} select',
            ]
        );
        $this->add_control(
            'slider_variant_selector_borderradius',
            [
                'label' => __('Border Radius', 'elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Price Style Settings - END
         */

        /**
         * General Style Settings - START
         */
        $this->start_controls_section(
            'featured_box_style_settings',
            [
                'label' => __( 'Featured Box - Description', 'wp-plc-shop' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'box_content_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-shop-featured-product-desc',
            ]
        );

        $this->end_controls_section();
        /**
         * General Style Settings - END
         */
    }
}