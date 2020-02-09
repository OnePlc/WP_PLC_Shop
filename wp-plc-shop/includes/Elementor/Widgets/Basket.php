<?php
/**
 * Elementor Basket Widget
 *
 * @package   OnePlace\Swissknife\Modules
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
        $sBasketSlug = $oBasketPage->post_name;

        // todo: howto access these settings after ajax-update?
        //update_option('plcshop_basket_btn_checkout_text',$aSettings['btn_checkout_text']);
        //update_option('plcshop_basket_btn_checkout_icon',$aSettings['btn_checkout_selected_icon']['value']);
        //update_option('plcshop_basket_btn_address_text',$aSettings['btn_address_text']);
        //update_option('plcshop_basket_btn_address_icon',$aSettings['btn_address_selected_icon']['value']);
        //update_option('plcshop_basket_btn_payment_text',$aSettings['btn_payment_text']);
        //update_option('plcshop_basket_btn_payment_icon',$aSettings['btn_payment_selected_icon']['value']);

        /**
         * get current step from querystring
         */
        $sStep = 'invalidstep';
        /**
        global $wp_query;
        if(array_key_exists('checkoutstep',$wp_query->query_vars)) {
            $sStepQuery = $wp_query->query_vars['checkoutstep'];
            if($sStepQuery != '') {
                $sStep = $sStepQuery;
            }
        }**/

        // get onePlace Server URL
        $sHost = get_option('plcconnect_server_url');
        $bPaymentCancelled = false;

        require_once WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/partials/basket_breadcrumb.php';

        switch($sStep) {
            default:
                require_once WPPLC_SHOP_PLUGIN_MAIN_DIR.'/includes/view/partials/basket_load.php';
                break;
        }
/**
        switch($sStep) {
            case 'thankyou':
                include __DIR__.'/view/checkout_done.php';
                break;
            case 'cancel':
                $bPaymentCancelled = true;
                include __DIR__.'/view/checkout_confirm.php';
                break;
            case 'pay':
                include __DIR__.'/view/checkout_pay.php';
                break;
            case 'confirm':
                include __DIR__.'/view/checkout_confirm.php';
                break;
            case 'payment':
                include __DIR__.'/view/checkout_payment.php';
                break;
            case 'address':
                include __DIR__.'/view/checkout_address.php';
                break;
            default: ?>
                <div class="plc-shop-basket">
                    <img src="<?=WPPLC_SHOP_PUB_DIR?>/assets/img/ajax-loader.gif" />
                </div>
                <script>
                    jQuery.post('<?=WPPLC_SHOP_PUB_DIR?>/includes/elementor/widgets/view/basket.php',{},function(retHTML) {
                        jQuery('.plc-shop-basket').html(retHTML);
                    })
                </script>
            <?php
                break;
        } **/
    }

    protected function _content_template() {

    }

    protected function _register_controls() {

    }
}