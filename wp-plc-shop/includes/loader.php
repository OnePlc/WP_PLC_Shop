<?php

/**
 * Plugin loader.
 *
 * @package   OnePlace\Shop
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/shop
 */

namespace OnePlace\Shop;

/**
 * Load composer autoload files
 *
 */
//require __DIR__ . '/vendor/autoload.php';

// Load Plugin
require_once __DIR__.'/Plugin.php';

// Load Modules
require_once __DIR__.'/Modules/Settings.php';
require_once __DIR__.'/Modules/Basket.php';
require_once __DIR__.'/Modules/Elementor.php';

// Init Plugin
Plugin::load(WPPLC_SHOP_PLUGIN_MAIN_FILE);

