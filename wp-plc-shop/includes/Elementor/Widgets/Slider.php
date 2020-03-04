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

    }
}