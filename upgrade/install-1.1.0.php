<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@expedierpascher.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade your module to newer
 * versions in the future.
 *
 * @author    MyFlyingBox <contact@myflyingbox.com>
 * @copyright 2017 MyFlyingBox
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @version   1.0
 *
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_1_0($module)
{
    // Refresh LCE Products to update logos
    $shipper_country = Configuration::get('MOD_LCE_DEFAULT_COUNTRY');
    $module->_refreshLceProducts($shipper_country);

    // Add fields is_return on lce_shipments table
    Db::getInstance()->Execute('
        ALTER TABLE `' . _DB_PREFIX_ . 'lce_shipments` 
            ADD `is_return` TINYINT(1) NOT NULL DEFAULT 0 AFTER `delete`; ');

    // Add default value for extended warranty
    Configuration::updateValue('MOD_LCE_DEFAULT_EXTENDED_WARRANTY', '0');

    // Add fields for extended warranty
    Db::getInstance()->Execute('
        ALTER TABLE `'._DB_PREFIX_.'lce_offers` 
            ADD COLUMN `extended_cover_available` TINYINT(1) DEFAULT 0 AFTER `insurance_price_in_cents`,
            ADD COLUMN `price_with_extended_cover` INT(11) NOT NULL AFTER `extended_cover_available`,
            ADD COLUMN `total_price_with_extended_cover` INT(11) NOT NULL AFTER `price_with_extended_cover`;
    ');

    // Add fields id_address
    Db::getInstance()->Execute('
        ALTER TABLE `'._DB_PREFIX_.'lce_quotes` 
            ADD `id_address` INT(11) NOT NULL AFTER `id_cart`;');

    // Delete quote when products in cart are updated
    $module->registerHook('actionCartUpdateQuantityBefore');
    if (version_compare(_PS_VERSION_, '1.7.1.0', '<')) {
        $module->registerHook('actionDeleteProductInCartAfter');
    } else {
        $module->registerHook('actionObjectProductInCartDeleteAfter');
    }
    
    // Initialize the Google Maps API key with the previous hard-coded value to avoid breaking current installations
    // This is a temporary solution until the key is properly configured in the module settings.
    // This will ensure that existing users do not experience issues with the Google Maps functionality.
    // After publication of the new version of the module, the key will be locked to be usable only on current active customers' websites.
    Configuration::updateValue('MOD_LCE_GOOGLE_CLOUD_API_KEY', 'AIzaSyBDTbHvOQcvZG4EmPI5GDAHge7ivXVvIKA');

    return true;
}
