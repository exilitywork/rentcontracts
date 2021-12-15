console.log('test');
if($(location).attr('pathname').includes('contract.php')) {
    $.ajax({
        url: "../plugins/rentcontracts/ajax/importcontracts.php"
        //datatype: "json"
    }).done(function(response) {
        //let tab = $('.tab_glpi').first();
        //tab.find('tr').children('.left').removeAttr("width");
        //tab.find('tr').append(response);
        $("form[name='massformContract']").before(response);
        //$(`#searchcriteria`).append(response);
        //console.log(search);
    })
}


