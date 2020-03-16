var searchJson = {
    'key': '',
    'page_index': 1
};
$(function () {
    $('#btnAdd').on('click', function () {
        doForm();
        return false;
    });
    $('#btnSearch').on('click', function () {
        search();
        return false;
    });
    $('#btnReset').on('click', function () {
        $('#txtSearchKey').val('');
        search();
        return false;
    });
});
function search() {
    searchJson['key'] = $('#txtSearchKey').val();
    searchJson['page_index'] = 1;
    getList();
}
//操作
function doForm() {
    $.OpenWin('新增管理员', '/setting/page/user-form');
}
//获取列表
function getList() {
    $.LoadPage($('#dataListBox'), '/setting/page/user-list', searchJson);
}
//删除
function deleteItem(id) {
    $.ConfimDialog('确定要删除该条记录吗？', function () {
        $.WebRequest('/setting/action/user-delete', {'id': id}, function (data) {
            if (data.code == 1) {
                $.ShowMsg(true, '删除成功。');
                getList();
            } else {
                $.ShowMsg(false, '删除失败。');
            }
        });
    })
}
//设置状态
function setItemStatus(id, status) {
    $.ConfimDialog(status ? '确定要开启该管理员吗？' : '确定要禁用该管理员吗？', function () {
        $.WebRequest('/admin/action/admin-status', {'id': id, 'status': status}, function (data) {
            if (data.code == 1) {
                $.ShowMsg(true, '设置成功。');
                getList();
            } else {
                $.ShowMsg(false, '设置失败。');
            }
        });
    });
}