var searchPayJson = {
    'key1': '',
    'key2': '',
    'key3': '',
    'begin_at': '',
    'end_at': '',
    'page_index': 1
};
$(function () {
    cal.manageFields("txtPaySearchBeginedAt", "txtPaySearchBeginedAt", "%Y-%m-%d");
    cal.manageFields("iconPaySearchBeginedAt", "txtPaySearchBeginedAt", "%Y-%m-%d");
    cal.manageFields("txtPaySearchEndedAt", "txtPaySearchEndedAt", "%Y-%m-%d");
    cal.manageFields("iconPaySearchEndedAt", "txtPaySearchEndedAt", "%Y-%m-%d");

    $('#btnPaySearch').on('click', function () {
        searchPay();
        return false;
    });
    $('#btnPayReset').on('click', function () {
        $('#txtPaySearchKey1').val('');
        $('#txtPaySearchKey2').val('');
        $('#txtPaySearchKey3').val('');
        $('#txtPaySearchBeginedAt').val('');
        $('#txtPaySearchEndedAt').val('');
        searchPay();
        return false;
    });
})


function searchPay() {
    searchPayJson['key1'] = $('#txtPaySearchKey1').val();
    searchPayJson['key2'] = $('#txtPaySearchKey2').val();
    searchPayJson['key3'] = $('#txtPaySearchKey3').val();
    searchPayJson['begin_at'] = $('#txtPaySearchBeginedAt').val();
    searchPayJson['end_at'] = $('#txtPaySearchEndedAt').val();
    searchPayJson['page_index'] = 1;
    getPayList();
}

//获取列表
function getPayList() {
    $.LoadPage($('#dataPayListBox'), '/report/page/pay-list', searchPayJson);
}

//代币使用记录
function pointsLog(no,id) {
    loadFormPage('/report/page/pay-points-log', {'no':no,'id': id});
}

//代币使用记录
function ercentageLog(no,id) {
    loadFormPage('/report/page/pay-ercentage-log', {'no':no,'id': id});
}
