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

if (!stripos($_SERVER['HTTP_REFERER'], '/front/contract.form.php')) {
    die("Sorry. You can't access directly to this file");
}

global $DB;

include ("../../../inc/includes.php");

// запись в лог сообщения
$logfile="../rent.log";
if (!file_exists($logfile)) {
    $newfile = fopen($logfile, 'w+');
    fclose($newfile); 
}
//error_log($message, 3, $logfile);

$total = $DB->result($DB->query("
                SELECT count(*) AS total
                FROM glpi_contracttypes
                WHERE comment = 'rentcontracts_lease1'"), 0, 'total');
$resp['total'] = ($total ? 1000 : -1000);
if (!empty($_POST['begin_date'])) {
    switch ($_POST['duration']) {
        case -1:
            $resp['end_date'] = PluginRentcontractsContract::getWarrantyExpir($_POST['begin_date'],
                                                        $_POST['duration'], 0, true, false);
            
            break;
        case 0:
            $resp['end_date'] = Html::showDateField("rent_end_date", [//'value' => $_POST['rent_end_date'],
                                                        'display' => false]);
            break;
        default:
        $resp['end_date'] = PluginRentcontractsContract::getWarrantyExpir($_POST['begin_date'],
                                                        $_POST['duration'], 0, true, true);
    }
    $resp['alert'] = 0;
} else {
    $resp['alert'] = 1;
}
//error_log($resp, 3, $logfile);
print(json_encode($resp));
//print($_POST['duration']);