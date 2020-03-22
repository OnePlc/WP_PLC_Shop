<?php
/**
 * Elementor Article List Widget
 *
 * @package   OnePlace\Shop\Modules
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/shop
 * @since 1.0.0
 */

class WPPLC_Shop_List extends \Elementor\Widget_Base {
    /**
     * WPPLC_Shop_Slider constructor.
     *
     * @param array $data
     * @param null $args
     * @since 1.0.0
     */
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
    }

    /**
     * Unique Name for Elementor Editor (internal)
     *
     * @return string
     * @since 1.0.0
     */
    public function get_name() {
        return 'wpplcshoplist';
    }

    /**
     * Display Name for Elementor Editor
     *
     * @return mixed
     * @since 1.0.0
     */
    public function get_title() {
        return __('Article List', 'wp-plc-shop');
    }

    /**
     * Icon for Elementor Editor
     *
     * @return string
     * @since 1.0.0
     */
    public function get_icon() {
        return 'fa fa-list';
    }

    /**
     * Category for Elementor Editor
     *
     * @return array
     * @since 1.0.0
     */
    public function get_categories() {
        return ['wpplc-shop'];
    }

    /**
     * Render Elementor Widget
     *
     * @since 1.0.0
     */
    protected function render() {
        $aSettings = $this->get_settings_for_display();


        # Get Articles from onePlace API
        $aParams = [
            'listmode' => 'entity',
            'listsorting' => 'websort',
        ];

        if(is_numeric($aSettings['compactlist_filter_category'])) {
            $aParams['filter'] = '["category"]';
            $aParams['filtervalue'] = '["'.(int)$aSettings['compactlist_filter_category'].'"]';
        }
        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/article/api/list/0', $aParams);

        if ($oAPIResponse->state == 'success') {
            $aEvents = $oAPIResponse->results;

            require WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/partials/widget_list.php';
        } else {
            echo 'ERROR CONNECTING TO EVENT SERVER';
        }
    }

    /**
     * Elementor Editor Template
     *
     * @since 1.0.0
     */
    protected function _content_template() {

    }

    /**
     * Elementor Widget Controls
     *
     * @since 1.0.0
     */
    protected function _register_controls() {
        /**
         * Get Data from onePlace API
         */
        $aOptions = [
            'all' => 'Alle',
            'highlights' => 'Highlights',
        ];
        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/tag/api/list/article-single/category', []);
        if(is_object($oAPIResponse)) {
            if($oAPIResponse->state == 'success') {
                foreach($oAPIResponse->results as $oCat) {
                    $aOptions[$oCat->id] = $oCat->text;
                }
            }
        }

        /**
         * Year Compact General Settings - START
         */
        $this->start_controls_section(
            'compactlist_general_settings',
            [
                'label' => __('Compact List - General Settings', 'wp-plc-event'),
            ]
        );

        $this->add_control(
            'compactlist_filter_category',
            [
                'label' => __('Kategorie', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $aOptions,
                'default' => 'all',
            ]
        );

        // List Mode
        $this->add_control(
            'compactlist_general_mode',
            [
                'label' => __('Event Filter', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'all' => 'All',
                    'onlybookable' => 'Only Bookable',
                    'onlyevents' => 'Only Non-Bookable',
                ],
                'default' => ['all'],
            ]
        );

        // Show Button
        $this->add_control(
            'compactlist_show_list_item_image',
            [
                'label' => __( 'Show Image', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-event' ),
                'label_off' => __( 'Hide', 'wp-plc-event' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'compactlist_list_limit',
            [
                'label' => __('Limit', 'elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                ],
                'default' => '3',
            ]
        );

        $this->add_responsive_control(
            'compactlist_item_margin',
            [
                'label' => __( 'Margin', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-calendar-list-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Compact List General Settings - END
         */

        /**
         * Compact List Date Settings - START
         */
        $this->start_controls_section(
            'compactlist_date_settings',
            [
                'label' => __('Compact List - Date Settings', 'wp-plc-event'),
            ]
        );

        $this->add_responsive_control(
            'compactlist_date_margin',
            [
                'label' => __( 'Margin', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-calendar-list-date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Compact List Date Settings - END
         */

        /**
         * Compact List Title Settings - START
         */
        $this->start_controls_section(
            'compactlist_title_settings',
            [
                'label' => __('Compact List - Title Settings', 'wp-plc-event'),
            ]
        );

        $this->add_responsive_control(
            'compactlist_title_margin',
            [
                'label' => __( 'Margin', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-calendar-list-widget-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Compact List Title Settings - END
         */

        /**
         * "More" Button Settings - START
         */
        $this->start_controls_section(
            'section_button_more',
            [
                'label' => __('List Item - Button "More"', 'wp-plc-event'),
            ]
        );

        // Button Text
        $this->add_control(
            'btn_more_text',
            [
                'label' => __('Text', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('More', 'wp-plc-event'),
                'placeholder' => __('More', 'wp-plc-event'),
            ]
        );

        // Button Icon
        $this->add_control(
            'btn_more_selected_icon',
            [
                'label' => __('Icon', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'fa4compatibility' => 'icon',
            ]
        );

        $this->add_responsive_control(
            'button_more_margin',
            [
                'label' => __( 'Margin', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .event-load-info-modal' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * "More" Button Settings - END
         */

        /**
         * "E-Mail" Button Settings - START
         */
        $this->start_controls_section(
            'section_button_request',
            [
                'label' => __('List Item - Button "Request"', 'wp-plc-event'),
            ]
        );

        // Button Text
        $this->add_control(
            'btn_request_text',
            [
                'label' => __('Text', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Request', 'wp-plc-event'),
                'placeholder' => __('Request', 'wp-plc-event'),
            ]
        );

        // Button Icon
        $this->add_control(
            'btn_request_selected_icon',
            [
                'label' => __('Icon', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'fa4compatibility' => 'icon',
            ]
        );

        $this->add_responsive_control(
            'button_request_margin',
            [
                'label' => __( 'Margin', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-shop-event-request' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * "E-Mail" Button Settings - END
         */

        /**
         * "Buy" Button Settings - START
         */
        $this->start_controls_section(
            'section_button_buy',
            [
                'label' => __('List Item - Button "Buy"', 'wp-plc-event'),
            ]
        );

        // Button Text
        $this->add_control(
            'btn1_text',
            [
                'label' => __('Text', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Buy', 'wp-plc-event'),
                'placeholder' => __('Buy', 'wp-plc-event'),
            ]
        );

        // Button Icon
        $this->add_control(
            'btn1_selected_icon',
            [
                'label' => __('Icon', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'fa4compatibility' => 'icon',
            ]
        );

        $this->add_responsive_control(
            'button_buy_margin',
            [
                'label' => __( 'Margin', 'wp-plc-event' ),
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
                'label' => __('List Item - Button "Gift"', 'wp-plc-event'),
            ]
        );

        // Button Text
        $this->add_control(
            'btn2_text',
            [
                'label' => __('Text', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Gift', 'wp-plc-event'),
                'placeholder' => __('Gift', 'wp-plc-event'),
            ]
        );

        // Button Icon
        $this->add_control(
            'btn2_selected_icon',
            [
                'label' => __('Icon', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'fa4compatibility' => 'icon',
            ]
        );

        $this->add_responsive_control(
            'button_gift_margin',
            [
                'label' => __( 'Margin', 'wp-plc-event' ),
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
         * Event Title Style Settings - START
         */
        $this->start_controls_section(
            'event_title_style',
            [
                'label' => __( 'Event Title', 'elementor' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'event_title_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-calendar-list-widget-title, {{WRAPPER}} .plc-calendar-list-widget-title a',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'event_title_desc_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-calendar-list-widget-title span, {{WRAPPER}} .plc-calendar-list-widget-title a',
            ]
        );

        $this->add_control(
            'event_title_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .plc-calendar-list-widget-title, {{WRAPPER}} .plc-calendar-list-widget-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'list_event_title_background',
                'label' => __( 'Background', 'oneplace' ),
                'types' => [ 'classic', 'gradient', 'image' ],
                'selector' => '{{WRAPPER}} .plc-calendar-list-widget-title',
            ]
        );

        $this->add_responsive_control(
            'event_title_align',
            [
                'label' => __( 'Alignment', 'oneplace' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'oneplace' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'oneplace' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'oneplace' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'devices' => [ 'desktop', 'tablet' ],
                'prefix_class' => 'content-align-%s',
            ]
        );

        $this->add_responsive_control(
            'compactlist_item_padding',
            [
                'label' => __( 'Padding', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-calendar-list-widget-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Event Title Style Settings - END
         */

        /**
         * Event Date Style Settings - START
         */
        $this->start_controls_section(
            'event_date_style',
            [
                'label' => __( 'Event Date', 'elementor' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'event_date_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-calendar-list-date',
            ]
        );

        $this->add_control(
            'list_date_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .plc-calendar-list-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'list_event_date_background',
                'label' => __( 'Background', 'oneplace' ),
                'types' => [ 'classic', 'gradient', 'image' ],
                'selector' => '{{WRAPPER}} div.plc-calendar-list-date',
            ]
        );

        $this->end_controls_section();
        /**
         * Event Date Style Settings - END
         */

        /**
         * List Item - Button Style - START
         */
        $this->start_controls_section(
            'list_event_buttons_style',
            [
                'label' => __( 'List - Buttons', 'elementor' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'event_buttons_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-calendar-widget-button',
            ]
        );

        $this->add_control(
            'list_event_buttons_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .plc-calendar-widget-button i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'list_event_buttons_background',
                'label' => __( 'Background', 'oneplace' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .plc-calendar-widget-button',
            ]
        );

        $this->end_controls_section();
        /**
         * List Item - Button Style - END
         */
    }
}