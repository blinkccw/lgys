var searchJson = {
    'key': '',
    'page_index': 1
};
$(function () {
    $('#btnSearch').on('click', function () {
        search();
        return false;
    });
    $('#btnReset').on('click', function () {
        $('#txtSearchKey').val('');
        search();
        return false;
    });
    $('#btnExcel').on('click', function () {
        reportExport();
        return false;
    });
});
function search() {
    searchJson['key'] = $('#txtSearchKey').val();
    searchJson['page_index'] = 1;
    getList();
}
//获取列表
function getList() {
    $.LoadPage($('#dataListBox'), '/vip/page/vip-list', searchJson);
}

//消费
function payPage(id) {
    loadFormPage('/vip/page/pay', {'id': id});
}


function reportExport() {
        $.WebRequest('/vip/action/export-vip', searchJson, function (data) {
            if (data.code == 1) {
                window.open('/excel/'+data.file);
                $.ShowMsg(true, '导出成功。');
            } else {
                $.ShowMsg(false, '导出失败。');
            }
        });
    }