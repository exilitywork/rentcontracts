<?php
include ("../../../inc/includes.php");

Session::checkLoginUser();

if (empty($_FILES)) {
    echo "
        <div id='import-div' style='margin: 0 auto; width: 470px;'>
            <span class='secondary-text'>Импорт договоров аренды: </span>
            <input id='contract_file' type='file' name='contract_file' accept='.xlsx'>&nbsp;
            <input type='button' name='import' value=\""._sx('button', 'Загрузить')."\" class='submit' onclick='importContracts()'></td>
        </div>
        <script>
            function importContracts() {
                if (window.FormData === undefined) {
                    alert('В вашем браузере FormData не поддерживается')
                } else {
                    if ($('#contract_file')[0].files[0] !== undefined) {
                        let formData = new FormData();
                        formData.append('contract_file', $('#contract_file')[0].files[0]);
                        console.log($('#contract_file')[0].files[0]);
                        $.ajax({
                            type: 'POST',
                            url: '../plugins/rentcontracts/ajax/importcontracts.php',
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formData,
                            //dataType : 'json',
                            beforeSend: function() {
                                $('.preloader_bg').show();
                                $('.preloader_content').show();
                            },  
                            success: function(data){
                                $(\"form[name='massformContract']\").before(data);
                                console.log(data);
                                console.log('success');
                            },
                            error: function(){
                                console.log('error');
                            },
                            complete: function(data) {
                                //console.log(data);
                                console.log('complete');
                                $('.preloader_bg').hide();
                                $('.preloader_content').hide();
                            }
                        });
                    } else {
                        $('.preloader_bg').hide();
                        $('.preloader_content').hide();
                    }
                }
            }
        </script>
    ";
} else {
    include ("../lib/SimpleXLSX.php");

    $file = RENTCONTRACTS_DOC_DIR."/contracts.xlsx";

    if (isset($_FILES['contract_file']['tmp_name']) && $_FILES['contract_file']['tmp_name'] != '') {
        $ext = explode('.', $_FILES['contract_file']['name']);
        if (in_array(array_pop($ext), ['xlsx']))
        {
            if (move_uploaded_file($_FILES['contract_file']['tmp_name'], $file)) {
                if ($xlsx = SimpleXLSX::parse($file)) {
                    $rows = $xlsx->rows(6);
                    $fr = "";
                    $cr = "";
                    foreach($rows as $row) {
                        $fr .= $row[0]."|";
                        if (ctype_digit($row[0])) {
                            $cr .= "Отделение: ".$row[0]."|";
                            $cr .= "Адрес: ".$row[1]."|";
                            $cr .= "Арендодатель: ".$row[2]."|";
                            $cr .= "Договор: ".$row[3]."|";
                            $row[4] = mb_strtolower(preg_replace('/\s+/', '',$row[4]));
                            $cr .= "Тип: ".(in_array($row[4], ["аренда", "субаренда"]) ? $row[4] : "ОШИБКА - ".$row[4])."<br>";
                        }
                    }
                    echo $cr;
                    //echo $xlsx->toHTML(2);
                } else {
                    echo SimpleXLSX::parseError();
                }
                //echo ("OK!");
            } else {
                echo('Нет файла договоров!');
            }
        } else {
            echo("Неверный формат файла!\n");
        }
    }
}

?>