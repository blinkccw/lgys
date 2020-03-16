var searchLogJson = {
    'begin_at': '',
    'end_at': '',
    'page_index': 1
};
$(function () {
    cal.manageFields("txtLogSearchBeginedAt", "txtLogSearchBeginedAt", "%Y-%m-%d");
    cal.manageFields("iconLogSearchBeginedAt", "txtLogSearchBeginedAt", "%Y-%m-%d");
    cal.manageFields("txtLogSearchEndedAt", "txtLogSearchEndedAt", "%Y-%m-%d");
    cal.manageFields("iconLogSearchEndedAt", "txtLogSearchEndedAt", "%Y-%m-%d");
    getLogList();
    $('#btnLogSearch').on('click', function () {
        searchLog();
        return false;
    });
    $('#btnLogReset').on('click', function () {
        $('#txtLogSearchBeginedAt').val('');
        $('#txtLogSearchEndedAt').val('');
        searchLog();
        return false;
    });
})


function searchLog() {
    searchLogJson['begin_at'] = $('#txtLogSearchBeginedAt').val();
    searchLogJson['end_at'] = $('#txtLogSearchEndedAt').val();
    searchLogJson['page_index'] = 1;
    getLogList();
}


//获取列表
function getLogList() {
    $.LoadPage($('#dataLogListBox'), '/main/page/log-list', searchLogJson);
}