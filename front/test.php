<?php
define('DO_NOT_CHECK_HTTP_REFERER', 1);
include ("../../../inc/includes.php");

Session::checkLoginUser();

include ("../lib/SimpleXLSX.php");

$file = RENTCONTRACTS_DOC_DIR."/contracts.xlsx";
print_r($_GET);
print_r($_FILES);
if (isset($_FILES['code_file']['tmp_name']) && $_FILES['code_file']['tmp_name'] != '') {
    $ext = explode('.', $_FILES['code_file']['name']);
    if(in_array(array_pop($ext), ['xlsx']))
    {
        if (move_uploaded_file($_FILES['code_file']['tmp_name'], $file)) {
            // запись в БД данных файла кодов
            if ( $xlsx = SimpleXLSX::parse('/var/www/glpi/plugins/rentcontracts/inc/rents.xlsx') ) {
                //var_dump( $xlsx->rows(0) );
                echo $xlsx->toHTML(2);
            } else {
                echo SimpleXLSX::parseError();
            }
        } else {
            die('Нет файла договоров!');
        }
    } else {
        die("Неверный формат файла!\n");
    }
}
?>