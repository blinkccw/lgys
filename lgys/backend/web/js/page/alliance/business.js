var searchLogJson = {
    'id': 0,
    'key': '',
    'page_index': 1
};
$(function () {
    searchLogJson['id'] = allianceID;

    $('#btnBusinessSearch').on('click', function () {
        searchLog();
        return false;
    });
    $('#btnBusinessReset').on('click', function () {
        $('#txtLogKey').val('');
        searchLog();
        return false;
    });
})


function searchLog() {
    searchLogJson['key'] = $('#txtBusinessKey').val();
    searchLogJson['page_index'] = 1;
    getBusinessList();
}

//获取列表
function getBusinessList() {
    $.LoadPage($('#dataBusinessListBox'), '/alliance/page/business-list', searchLogJson);
}

