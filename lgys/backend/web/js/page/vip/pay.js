var searchPayJson = {
    'id': 0,
    'key': '',
    'begin_at': '',
    'end_at': '',
    'page_index': 1
};
$(function () {
    searchPayJson['id'] = vipID;
    cal.manageFields("txtPaySearchBeginedAt", "txtPaySearchBeginedAt", "%Y-%m-%d");
    cal.manageFields("iconPaySearchBeginedAt", "txtPaySearchBeginedAt", "%Y-%m-%d");
    cal.manageFields("txtPaySearchEndedAt", "txtPaySearchEndedAt", "%Y-%m-%d");
    cal.manageFields("iconPaySearchEndedAt", "txtPaySearchEndedAt", "%Y-%m-%d");

    $('#btnPaySearch').on('click', function () {
        searchPay();
        return false;
    });
    $('#btnPayReset').on('click', function () {
        $('#txtPaySearchKey').val('');
        $('#txtPaySearchBeginedAt').val('');
        $('#txtPaySearchEndedAt').val('');
        searchPay();
        return false;
    });
})


function searchPay() {
    searchPayJson['key'] = $('#txtPaySearchKey').val();
    searchPayJson['begin_at'] = $('#txtPaySearchBeginedAt').val();
    searchPayJson['end_at'] = $('#txtPaySearchEndedAt').val();
    searchPayJson['page_index'] = 1;
    getPayList();
}

//获取列表
function getPayList() {
    $.LoadPage($('#dataPayListBox'), '/vip/page/pay-list', searchPayJson);
}

