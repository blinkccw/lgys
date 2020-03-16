$(function () {
    $('#btnAdd').on('click', function () {
        doForm(0);
        return false;
    });
    initList();
})


//操作
function doForm(id) {
    $.OpenWin(id == 0 ? '新增分类' : '编辑分类', '/business/page/sort-form', {'id': id});
}


//删除
function deleteItem(id) {
    $.ConfimDialog('确定要删除该分类吗？', function () {
        $.WebRequest('/business/action/sort-delete', {'id': id}, function (data) {
            if (data.code == 1) {
                $.ShowMsg(true, '删除成功。');
                getList();
            } else {
                $.ShowMsg(false, '删除失败。');
            }
        });
    })
}

//获取列表
function getList() {
    $.LoadMainPage("/business/page/sort");
}
var curOrderNum = 1;
function initList() {
    $('#dataListBox').find('input[data-type=order]').each(function () {
        $(this).focus(function () {
            curOrderNum = $.trim($(this).val());
        });
        $(this).blur(function () {
            var item = $(this);
            var txt = $.trim(item.val());
            if (curOrderNum == txt)
                return false;
            if ($.isEmpty(txt)) {
                $.ShowMsg(false, '升级顺序不能为空。');
                item.focus();
                return false;
            }
            if (!$.isZInt(txt) || txt <= 0) {
                $.ShowMsg(false, '升级顺序为大于0的正整数。');
                item.focus();
                return false;
            }
            var id = item.attr('data-id');
            $.WebRequest('/business/action/set-sort-order', {'id': id, 'order_num': txt}, function (data) {
                if (data.code == 1) {
                    $.ShowMsg(true, '设置成功。');
                    getList();
                } else {
                    $.ShowMsg(false, '设置失败。');
                    item.val(curOrderNum);
                }
            });
            return false;
        });
    });
}