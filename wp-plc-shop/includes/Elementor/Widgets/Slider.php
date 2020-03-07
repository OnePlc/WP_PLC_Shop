<?php
/**
 * Elementor Article Slider Widget
 *
 * @package   OnePlace\Shop\Modules
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/shop
 * @since 1.0.0
 */

class WPPLC_Shop_Slider extends \Elementor\Widget_Base {
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
    }

    public function get_name() {
        return 'wpplcshopslider';
    }

    public function get_title() {
        return __('Article Slider', 'wp-plc-shop');
    }

    public function get_icon() {
        return 'fa fa-images';
    }

    public function get_categories() {
        return ['wpplc-shop'];
    }

    protected function render() {
        $aSettings = $this->get_settings_for_display();

        # Get Articles from onePlace API
        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/list/0', ['listmode'=>'entity']);

        if ($oAPIResponse->state == 'success') {
            echo $oAPIResponse->message;
        } else {
            echo 'ERROR CONNECTING TO SHOP SERVER';
        }

        require_once WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/partials/article_slider.php';
    }

    protected function _content_template() {

    }

    protected function _register_controls() {
        /**
         * Get Data from onePlace API
         */
        $aOptions = [];

        /**
         * Slider General Settings - START
         */
        $this->start_controls_section(
            'section_slider_settings',
            [
                'label' => __('Slider - General Settings', 'wp-plc-shop'),
            ]
        );

        // Slides per View
        $this->add_control(
            'slider_slides_per_view',
            [
                'label' => __('Slides', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'default' => '3',
            ]
        );

        // Slider Base Category
        $this->add_control(
            'slider_base_category',
            [
                'label' => __('Kategorie', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $aOptions,
                'default' => ['0'=>'Alle'],
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * Slider General Settings - END
         */

        /**
        * Slider Slide Settings - START
        */
        $this->start_controls_section(
            'section_slider_slide_settings',
            [
                'label' => __('Slider - Slide Settings', 'wp-plc-shop'),
            ]
        );

        // Show Image
        $this->add_control(
            'slider_slide_show_featuredimage',
            [
                'label' => __( 'Show Featured Image', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-shop' ),
                'label_off' => __( 'Hide', 'wp-plc-shop' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Show Title
        $this->add_control(
            'slider_slide_show_title',
            [
                'label' => __( 'Show Title', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-shop' ),
                'label_off' => __( 'Hide', 'wp-plc-shop' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Show Description
        $this->add_control(
            'slider_slide_show_description',
            [
                'label' => __( 'Show Description', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-shop' ),
                'label_off' => __( 'Hide', 'wp-plc-shop' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Show Price
        $this->add_control(
            'slider_slide_show_price',
            [
                'label' => __( 'Show Price', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-shop' ),
                'label_off' => __( 'Hide', 'wp-plc-shop' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * Slider Slide Settings - END
         */

        /**
         * Slider Slide Price Settings - START
         */
        $this->start_controls_section(
            'section_slider_slide_price_settings',
            [
                'label' => __('Slide - Price Settings', 'wp-plc-shop'),
            ]
        );

        $this->add_responsive_control(
            'slider_slide_price_margin',
            [
                'label' => __( 'Margin', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} select.plc-slider-slide-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * Slider Slide Price Settings - END
         */

        /**
         * "Buy" Button Settings - START
         */
        $this->start_controls_section(
            'section_button_buy',
            [
                'label' => __('Slide - Button "Buy"', 'wp-plc-shop'),
            ]
        );

        // Show Button
        $this->add_control(
            'slider_slide_show_button_buy',
            [
                'label' => __( 'Show Buy Button', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-shop' ),
                'label_off' => __( 'Hide', 'wp-plc-shop' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Show Button
        $this->add_control(
            'slider_slide_show_popup_basket',
            [
                'label' => __( 'Show Popup for Amount', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-shop' ),
                'label_off' => __( 'Hide', 'wp-plc-shop' ),
                'return_value' => 'yes',
                'default' => 'yes',
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

        $this->add_responsive_control(
            'button_buy_margin',
            [
                'label' => __( 'Margin', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-shop-additem-tobasket' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * "Buy" Button Settings - END
         */

        /**
         * "Gift" Button Settings - START
         */
        $this->start_controls_section(
            'section_button_gift',
            [
                'label' => __('Slide - Button "Gift"', 'wp-plc-shop'),
            ]
        );

        // Show Button
        $this->add_control(
            'slider_slide_show_button_gift',
            [
                'label' => __( 'Show Gift Button', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-shop' ),
                'label_off' => __( 'Hide', 'wp-plc-shop' ),
                'return_value' => 'yes',
                'default' => 'yes',
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

        $this->add_responsive_control(
            'button_gift_margin',
            [
                'label' => __( 'Margin', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-shop-giftitem-tobasket' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * "Gift" Button Settings - END
         */

        /**
         * Slider Slide Title - START
         */
        $this->start_controls_section(
            'slider_slide_title_style',
            [
                'label' => __( 'Slides - Title', 'wp-plc-shop' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Height
        $this->add_control(
            'slider_slide_title_height',
            [
                'label' => __('Height', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '120px' => 'Fixed 90px',
                    'auto' => 'Auto',
                ],
                'default' => 'auto',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'slider_slide_title_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} h3.plc-slider-slide-title',
            ]
        );

        $this->add_control(
            'slider_slide_title_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} h3.plc-slider-slide-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_slide_title_align',
            [
                'label' => __( 'Alignment', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'wp-plc-shop' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'wp-plc-shop' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'wp-plc-shop' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'devices' => [ 'desktop', 'tablet' ],
                'prefix_class' => 'content-align-%s',
            ]
        );

        $this->add_responsive_control(
            'slider_slide_title_padding',
            [
                'label' => __( 'Padding', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} h3.plc-slider-slide-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Slider Slide Title - END
         */

        /**
         * Slider Slide Description - START
         */
        $this->start_controls_section(
            'slider_slide_description_style',
            [
                'label' => __( 'Slides - Description', 'wp-plc-shop' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Height
        $this->add_control(
            'slider_slide_desc_height',
            [
                'label' => __('Height', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '120px' => 'Fixed 120px',
                    'auto' => 'Auto',
                ],
                'default' => 'auto',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'slider_slide_desc_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} div.plc-slider-slide-description',
            ]
        );

        $this->add_control(
            'slider_slide_desc_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} div.plc-slider-slide-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_slide_desc_align',
            [
                'label' => __( 'Alignment', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'wp-plc-shop' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'wp-plc-shop' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'wp-plc-shop' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'devices' => [ 'desktop', 'tablet' ],
                'prefix_class' => 'content-align-%s',
            ]
        );

        $this->add_responsive_control(
            'slider_slide_desc_padding',
            [
                'label' => __( 'Padding', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} div.plc-slider-slide-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Slider Slide Description - END
         */

        /**
         * Slider Slide Price - START
         */
        $this->start_controls_section(
            'slider_slide_price_style',
            [
                'label' => __( 'Slides - Price', 'wp-plc-shop' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'slider_slide_price_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} select.plc-slider-slide-price',
            ]
        );

        $this->add_control(
            'slider_slide_price_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} select.plc-slider-slide-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'slider_slide_price_background_color',
            [
                'label' => __( 'Background Color', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} select.plc-slider-slide-price' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_slide_price_align',
            [
                'label' => __( 'Alignment', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'wp-plc-shop' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'wp-plc-shop' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'wp-plc-shop' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'devices' => [ 'desktop', 'tablet' ],
                'prefix_class' => 'content-align-%s',
            ]
        );

        $this->add_responsive_control(
            'slider_slide_price_padding',
            [
                'label' => __( 'Padding', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} select.plc-slider-slide-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Button Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'slider_slide_price_border',
                'selector' => '{{WRAPPER}} select.plc-slider-slide-price',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'slider_slide_price_border_radius',
            [
                'label' => __( 'Border Radius', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} select.plc-slider-slide-price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Slider Slide Price - END
         */

        /**
         * Buttons Style - START
         */
        $this->start_controls_section(
            'slider_slide_buttons_style',
            [
                'label' => __( 'Slides - Buttons', 'wp-plc-shop' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Button Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-slider-button,{{WRAPPER}} .plc-article-slider-more-lnk',
            ]
        );

        // Text Color for normal/hover
        $this->start_controls_tabs( 'tabs_button_style' );
        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'wp-plc-shop' ),
            ]
        );
        $this->add_control(
            'button_text_color',
            [
                'label' => __( 'Text Color', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-button' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_background_color',
            [
                'label' => __( 'Background Color', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        // Hover
        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'wp-plc-shop' ),
            ]
        );
        $this->add_control(
            'button_hover_color',
            [
                'label' => __( 'Text Color', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_background_hover_color',
            [
                'label' => __( 'Background Color', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __( 'Border Color', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_hover_animation',
            [
                'label' => __( 'Hover Animation', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        // Button Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'slider_slide_buttons_border',
                'selector' => '{{WRAPPER}} .plc-slider-button',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'slider_slide_buttons_border_radius',
            [
                'label' => __( 'Border Radius', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'slider_slide_buttons_box_shadow',
                'selector' => '{{WRAPPER}} .plc-slider-button',
            ]
        );
        $this->add_responsive_control(
            'slider_slide_buttons_text_padding',
            [
                'label' => __( 'Padding', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        // Slide Buttons Width
        $this->add_control(
            'buttons_slide_width',
            [
                'label' => __('Width', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '100%' => 'Full Width',
                    'auto' => 'Auto',
                ],
                'default' => 'auto',
            ]
        );
        $this->end_controls_section();
        /**
         * Buttons Style - END
         */
    }
}