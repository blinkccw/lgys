var searchJson = {
    'begin_at': '',
    'end_at': '',
    'page_index': 1
};
$(function () {
    $('#btnLogSearch').on('click', function () {
        search();
        return false;
    });
    $('#btnLogReset').on('click', function () {
        $('#txtBeginAt').val('');
        $('#txtEndAt').val('');
        search();
        return false;
    });
    $('#btnExcel').on('click', function () {
        reportExport();
        return false;
    });
});
function search() {
    searchJson['begin_at'] = $('#txtBeginAt').val();
    searchJson['end_at'] = $('#txtEndAt').val();
    searchJson['page_index'] = 1;
    getList();
}
//获取列表
function getList() {
    $.LoadPage($('#dataLogListBox'), '/main/page/log-list', searchJson);
}
