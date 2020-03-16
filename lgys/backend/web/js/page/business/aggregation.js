var searchJson = {
    'key': '',
    'vip_key': '',
    'status': -1,
    'page_index': 1
};
$(function () {
    $('#btnSearch').on('click', function () {
        search();
        return false;
    });
    $('#btnReset').on('click', function () {
        $('#ddlSearchStatus').val(-1);
        $('#txtSearchKey').val('');
        $('#txtSearchVipKey').val('');
        search();
        return false;
    });
});
function search() {
    searchJson['key'] = $('#txtSearchKey').val();
    searchJson['vip_key'] = $('#txtSearchVipKey').val();
    searchJson['status'] = $('#ddlSearchStatus').val();
    searchJson['page_index'] = 1;
    getList();
}

//获取列表
function getList() {
    $.LoadPage($('#dataListBox'), '/business/page/aggregation-list', searchJson);
}
function showItem(id){
     loadFormPage('/business/page/aggregation-man', {'id': id});
}