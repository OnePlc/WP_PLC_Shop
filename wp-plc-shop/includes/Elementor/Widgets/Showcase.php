<?php
/**
 * Elementor Article Showcase Widget
 *
 * @package   OnePlace\Shop\Modules
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/shop
 * @since 1.0.0
 */

class WPPLC_Shop_Showcase extends \Elementor\Widget_Base {
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
    }

    public function get_name() {
        return 'wpplcshopshowcase';
    }

    public function get_title() {
        return __('Article Showcase', 'wp-plc-shop');
    }

    public function get_icon() {
        return 'fa fa-th-list';
    }

    public function get_categories() {
        return ['wpplc-shop'];
    }

    protected function render() {
        $aSettings = $this->get_settings_for_display();

        # Get Articles from onePlace API
        $aParams = ['listmode'=>'entity'];
        if($aSettings['slider_base_category'] != 0) {
            $aParams['filter'] = 'category';
            $aParams['filtervalue'] = $aSettings['slider_base_category'];
        }
        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/list/0', $aParams);

        if ($oAPIResponse->state == 'success') {
            $sHost = \OnePlace\Connect\Plugin::getCDNServerAddress();
            $aArticles = $oAPIResponse->results;

            require_once WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/partials/article_showcase.php';
        } else {
            echo 'ERROR CONNECTING TO SHOP SERVER';
        }
    }

    protected function _content_template() {

    }

    protected function _register_controls() {
        /**
         * Get Data from onePlace API
         */
        $aOptions = ['0'=>'Alle'];
        $aParams = ['listmode' => 'entity'];
        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/tag/api/list/article-single/category', $aParams);
        if($oAPIResponse->state == 'success') {
            foreach($oAPIResponse->results as $oCat) {
                $aOptions[$oCat->id] = $oCat->text;
            }
        }

        /**
         * Slider General Settings - START
         */
        $this->start_controls_section(
            'section_slider_settings',
            [
                'label' => __('Slider Einstellungen', 'oneplace'),
            ]
        );

        $this->add_control(
            'slider_base_category',
            [
                'label' => __('Kategorie', 'wpplc'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $aOptions,
                'default' => ['0'=>'Alle'],
            ]
        );

        $this->end_controls_section();
        /**
         * Slider General Settings - END
         */

        /**
         * "Buy" Button Settings - START
         */
        $this->start_controls_section(
            'section_button_buy',
            [
                'label' => __('Slider Item - Button "Buy"', 'wp-plc-shop'),
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
         * "Buy" Button Settings - END
         */

        /**
         * "Gift" Button Settings - START
         */
        $this->start_controls_section(
            'section_button_gift',
            [
                'label' => __('Slider Item - Button "Gift"', 'wp-plc-shop'),
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
         * "Gift" Button Settings - END
         */

        /**
         * Showcase Title Style Settings - START
         */
        $this->start_controls_section(
            'showcase_title',
            [
                'label' => __('Titel', 'wpplc'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'showcase_title_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} h2.plc-art-showcase-big-title',
            ]
        );

        $this->add_responsive_control(
            'showcase_title_align',
            [
                'label' => __('Alignment', 'wpplc'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'wpplc'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'wpplc'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'wpplc'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'devices' => ['desktop', 'tablet'],
                'prefix_class' => 'content-align-%s',
            ]
        );

        $this->add_responsive_control(
            'showcase_title_padding',
            [
                'label' => __('Padding', 'oneplace'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} h2.plc-art-showcase-big-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'showcase_title_color_odd',
            [
                'label' => __('Text Color Odd', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} h2.box-odd' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'showcase_title_color_even',
            [
                'label' => __('Text Color Even', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} h2.box-even' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Showcase Title Style Settings - END
         */

        /**
         * Showcase Boxes Style Settings - START
         */
        $this->start_controls_section(
            'showcase_boxes',
            [
                'label' => __('Boxen', 'wpplc'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'box_background_odd',
                'label' => __( 'Background Odd', 'plugin-domain' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} div.box-odd',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'box_background_even',
                'label' => __( 'Background Even', 'plugin-domain' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} div.box-even',
            ]
        );

        $this->add_responsive_control(
            'showcase_box_align',
            [
                'label' => __('Alignment', 'wpplc'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'wpplc'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'wpplc'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'wpplc'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'devices' => ['desktop', 'tablet'],
                'prefix_class' => 'content-align-%s',
            ]
        );

        $this->add_responsive_control(
            'showcase_box_padding',
            [
                'label' => __('Padding', 'oneplace'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} div.plc-art-showcase-big-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Showcase Boxes Style Settings - END
         */

        /**
         * Showcase Boxes - Description Style Settings - START
         */
        $this->start_controls_section(
            'showcase_description',
            [
                'label' => __('Beschreibung', 'wpplc'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'showcase_description_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} div.plc-art-showcase-big-description',
            ]
        );

        $this->add_control(
            'showcase_description_color_odd',
            [
                'label' => __('Text Color Odd', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} div.box-odd div.plc-art-showcase-big-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'showcase_description_color_even',
            [
                'label' => __('Text Color Even', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} div.box-even div.plc-art-showcase-big-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'showcase_description_padding',
            [
                'label' => __('Padding', 'oneplace'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} div.plc-art-showcase-big-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Showcase Boxes - Description Style Settings - END
         */

        /**
         * Buttons Style - START
         */
        $this->start_controls_section(
            'event_buttons_style',
            [
                'label' => __( 'Buttons', 'wp-plc-shop' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-slider-button,{{WRAPPER}} .plc-article-slider-more-lnk',
            ]
        );

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

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .plc-slider-button',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'border_radius',
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
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .plc-slider-button',
            ]
        );
        $this->add_responsive_control(
            'button_text_padding',
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
        $this->end_controls_section();
        /**
         * Buttons Style - END
         */
    }
}