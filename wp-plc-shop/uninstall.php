<?php
/**
 * Plugin reset and uninstall cleanup.
 *
 * @package   OnePlace\Shop
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch
 */

namespace OnePlace\Shop;

# if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' )) {
    die;
}

# Set options that should be cleared
$aOptions = [
    'plcshop_pages_main',
    'plcshop_pages_basket',
    'plcshop_currency_main',
    'plcshop_currency_pos',
    'plcshop_elementor_widget_article_slider_active',
    'plcshop_elementor_widget_basket_active',
    'plcshop_elementor_widget_featuredbox_active',
    'plcshop_popup_buybutton_enable',
    'plcshop_popup_buybutton_text',
    'plcshop_popup_buybutton_fontfamily',
    'plcshop_popup_buybutton_background',
    'plcshop_popup_buybutton_color',
    'plcshop_popup_buybutton_icon',
    'plcshop_popup_buybutton_iconcolor',
    'plcshop_popup_buybutton_transform',
];

foreach($aOptions as $sOption) {
    // delete option
    delete_option($sOption);
    // for site options in Multisite
    delete_site_option($sOption);
}
