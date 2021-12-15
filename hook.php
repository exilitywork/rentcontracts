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

/**
 * Install the plugin
 *
 * @return boolean
 */
function plugin_rentcontracts_install() {
    global $DB;
    
    $types = [
        'rentcontracts_lease'       => 'аренда',
        'rentcontracts_sublease'    => 'субаренда'
    ];

    foreach ($types as $type => $name) {
        addRentTypes($type, $name);
    }

    if (!$DB->tableExists('glpi_plugin_rentcontracts_contracts')) {
        $create_table_query = "
            CREATE TABLE IF NOT EXISTS `glpi_plugin_rentcontracts_contracts`
            (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `contract_id` int(11) NOT NULL,
                `rent_landlord` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `rent_obj_type` int(11) NOT NULL DEFAULT '0',
                `rent_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `rent_end_date` datetime DEFAULT NULL,
                `rent_countrenew` int(11) NOT NULL DEFAULT '0',
                `rent_commentrenew` text COLLATE utf8_unicode_ci,
                `rent_refuse` int(11) NOT NULL DEFAULT '0',
                `rent_refuse_date` datetime DEFAULT NULL,
                `rent_comment1refuse` text COLLATE utf8_unicode_ci,
                `rent_sectionrefuse` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `rent_comment2refuse` text COLLATE utf8_unicode_ci,
                `rent_refuseban_begin` datetime DEFAULT NULL,
                `rent_refuseban_end` datetime DEFAULT NULL,
                `rent_notice` int(11) NOT NULL DEFAULT '0',
                `rent_notice_date` datetime DEFAULT NULL,
                `rent_commentnotice` text COLLATE utf8_unicode_ci,
                PRIMARY KEY (`id`),
                KEY (`contract_id`)
            ) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;
        ";
        $DB->query($create_table_query) or die($DB->error());
    }

    return true;
}

/**
 * Uninstall the plugin
 *
 * @return boolean
 */
function plugin_rentcontracts_uninstall() {
    global $DB;

    //$drop_table_query = "DROP TABLE IF EXISTS `glpi_plugin_unreadmessages`";

    //return $DB->query($drop_table_query) or die($DB->error());
    return true;
}

/**
 * Uninstall the plugin
 *
 * @return boolean
 */
function addRentTypes($type, $name) {
    global $DB;

    $is_create = $DB->result($DB->query("
                SELECT count(*) AS count
                FROM glpi_contracttypes
                WHERE comment = '".$type."'"), 0, 'count');

    if ($is_create > 1) {
        die ("ContractTypes error: в базе несколько категорий с комментарием ".$type);
    }
    if ($is_create) {
        return true;
    } else {
        $DB->insertOrDie(
            'glpi_contracttypes', [
                'name'                  => $name,
                'comment'               => $type
            ],
            'MySQL Error: Insert of item '.$type.' failed!'
        );
    }

    return true;
}
