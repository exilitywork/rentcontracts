<?php
/**
 * -------------------------------------------------------------------------
 * Rentcontracts plugin for GLPI
 * Copyright (C) 2021 by the Belwest, Kapeshko Oleg.
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of Rentcontracts.
 *
 * Rentcontracts is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Rentcontracts is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Rentcontracts. If not, see <http://www.gnu.org/licenses/>.
 * --------------------------------------------------------------------------
 */

define('PLUGIN_RENTCONTRACTS_MIN_GLPI_VERSION', '9.4');
define('PLUGIN_RENTCONTRACTS_NAMESPACE', 'rentcontracts');

if (!defined("RENTCONTRACTS_DOC_DIR")) {
    define("RENTCONTRACTS_DOC_DIR", GLPI_PLUGIN_DOC_DIR . "/rentcontracts");
}
if (!file_exists(RENTCONTRACTS_DOC_DIR)) {
    mkdir(RENTCONTRACTS_DOC_DIR);
}

/**
 * Plugin description
 *
 * @return boolean
 */
function plugin_version_rentcontracts() {
    return [
      'name' => 'Rent Contracts for Belwest',
      'version' => '0.2',
      'author' => 'BELWEST - Kapeshko Oleg',
      'homepage' => '',
      'license' => 'local',
      'minGlpiVersion' => PLUGIN_RENTCONTRACTS_MIN_GLPI_VERSION,
    ];
}

/**
 * Initialize plugin
 *
 * @return boolean
 */
function plugin_init_rentcontracts() {
    if (Session::getLoginUserID()) {
        global $PLUGIN_HOOKS;
        $PLUGIN_HOOKS['csrf_compliant'][PLUGIN_RENTCONTRACTS_NAMESPACE] = true;
        $PLUGIN_HOOKS['pre_item_form'][PLUGIN_RENTCONTRACTS_NAMESPACE] = ['PluginRentcontractsContract', 'pre_item_form'];
        $PLUGIN_HOOKS['post_item_form'][PLUGIN_RENTCONTRACTS_NAMESPACE] = ['PluginRentcontractsContract', 'post_item_form'];
        $PLUGIN_HOOKS['add_javascript'][PLUGIN_RENTCONTRACTS_NAMESPACE][] = 'js/importcontracts.js';
        //$PLUGIN_HOOKS['item_add'][PLUGIN_RENTCONTRACTS_NAMESPACE] = array('Contract' => array('PluginRentcontractsContract', 'addContract'));
        //$PLUGIN_HOOKS['pre_item_update'][PLUGIN_RENTCONTRACTS_NAMESPACE] = array('Contract' => array('PluginRentcontractsContract', 'updateContract'));
    }
}

/**
 * Check if plugin prerequisites are met
 *
 * @return boolean
 */
function plugin_rentcontracts_check_prerequisites() {
    $prerequisites_check_ok = false;

    try {
        if (version_compare(GLPI_VERSION, PLUGIN_RENTCONTRACTS_MIN_GLPI_VERSION, '<')) {
            throw new Exception('This plugin requires GLPI >= ' . PLUGIN_RENTCONTRACTS_MIN_GLPI_VERSION);
        }

        $prerequisites_check_ok = true;
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    return $prerequisites_check_ok;
}

/**
 * Check if config is compatible with plugin
 *
 * @return boolean
 */
function plugin_rentcontracts_check_config() {
    // nothing to do
    return true;
}
