var searchLogJson = {
    'id': 0,
    'alliance_id': 0,
    'key': '',
    'begin_at': '',
    'end_at': '',
    'page_index': 1
};
$(function () {
    searchLogJson['id'] = businessID;
    cal.manageFields("txtLogSearchBeginedAt", "txtLogSearchBeginedAt", "%Y-%m-%d");
    cal.manageFields("iconLogSearchBeginedAt", "txtLogSearchBeginedAt", "%Y-%m-%d");
    cal.manageFields("txtLogSearchEndedAt", "txtLogSearchEndedAt", "%Y-%m-%d");
    cal.manageFields("iconLogSearchEndedAt", "txtLogSearchEndedAt", "%Y-%m-%d");

    $('#btnLogSearch').on('click', function () {
        searchLog();
        return false;
    });
    $('#btnLogReset').on('click', function () {
        $('#txtLogKey').val('');
        $('#ddlLogSearchAlliance').val(0);
        $('#txtLogSearchBeginedAt').val('');
        $('#txtLogSearchEndedAt').val('');
        searchLog();
        return false;
    });
})


function searchLog() {
    searchLogJson['alliance_id'] = $('#ddlLogSearchAlliance').val();
    searchLogJson['key'] = $('#txtLogKey').val();
    searchLogJson['begin_at'] = $('#txtLogSearchBeginedAt').val();
    searchLogJson['end_at'] = $('#txtLogSearchEndedAt').val();
    searchLogJson['page_index'] = 1;
    getLogList();
}

//获取列表
function getLogList() {
    $.LoadPage($('#dataLogListBox'), '/business/page/deduction-log-list', searchLogJson);
}

