$(function () {
    $('#btnAdd').on('click', function () {
        doForm(0);
        return false;
    });
    initList();
})


//操作
function doForm(id) {
    $.OpenWin(id == 0 ? '新增等级' : '编辑等级', '/business/page/grade-form', {'id': id});
}
//获取列表
function getList() {
    $.LoadMainPage("/business/page/grade");
}


//删除
function deleteItem(id) {
    $.ConfimDialog('确定要删除该等级吗？', function () {
        $.WebRequest('/business/action/grade-delete', {'id': id}, function (data) {
            if (data.code == 1) {
                $.ShowMsg(true, '删除成功。');
                getList();
            } else {
                $.ShowMsg(false, '删除失败。');
            }
        });
    })
}
