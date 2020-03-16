var searchLogJson = {
    'business_id': 0,
    'id': 0,
    'key': '',
    'begin_at': '',
    'end_at': '',
    'page_index': 1
};
$(function () {
    searchLogJson['id'] = allianceID;
    cal.manageFields("txtLogSearchBeginedAt", "txtLogSearchBeginedAt", "%Y-%m-%d");
    cal.manageFields("iconLogSearchBeginedAt", "txtLogSearchBeginedAt", "%Y-%m-%d");
    cal.manageFields("txtLogSearchEndedAt", "txtLogSearchEndedAt", "%Y-%m-%d");
    cal.manageFields("iconLogSearchEndedAt", "txtLogSearchEndedAt", "%Y-%m-%d");

    $('#btnLogSearch').on('click', function () {
        searchLog();
        return false;
    });
    $('#btnLogReset').on('click', function () {
        $('#ddlLogSearchBusiness').val(0);
        $('#txtLogKey').val('');
        $('#txtLogSearchBeginedAt').val('');
        $('#txtLogSearchEndedAt').val('');
        searchLog();
        return false;
    });
})


function searchLog() {
    searchLogJson['business_id'] = $('#ddlLogSearchBusiness').val();
    searchLogJson['key'] = $('#txtLogKey').val();
    searchLogJson['begin_at'] = $('#txtLogSearchBeginedAt').val();
    searchLogJson['end_at'] = $('#txtLogSearchEndedAt').val();
    searchLogJson['page_index'] = 1;
    getLogList();
}

//获取列表
function getLogList() {
    $.LoadPage($('#dataLogListBox'), '/alliance/page/exchange-log-list', searchLogJson);
}

