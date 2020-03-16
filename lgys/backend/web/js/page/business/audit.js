var searchJson = {
    'key': '',
    'is_audit':-1,
    'page_index': 1
};
$(function () {
    $('#btnAdd').on('click', function () {
        doForm(0);
        return false;
    });
    $('#btnSearch').on('click', function () {
        search();
        return false;
    });
    $('#btnReset').on('click', function () {
        $('#txtSearchKey').val('');
        $('#ddlSearchStatus').val(-1);
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
    searchJson['is_audit'] = $('#ddlSearchStatus').val();
    searchJson['page_index'] = 1;
    getList();
}

//获取列表
function getList() {
    $.LoadPage($('#dataListBox'), '/business/page/audit-list', searchJson);
}

function showItem(id){
    loadFormPage('/business/page/business-info', {'id': id});
}


//删除
function deleteItem(id) {
    $.ConfimDialog('确定要删除该商户吗？', function () {
        $.WebRequest('/business/action/business-delete', {'id': id}, function (data) {
            if (data.code == 1) {
                $.ShowMsg(true, '删除成功。');
                getList();
            } else {
                $.ShowMsg(false, '删除失败。');
            }
        });
    })
}

//审核
function auditItem(id,audit) {
    $.ConfimDialog(audit==1?'确定要通过该商户吗？':'确定要不通过该商户？', function () {
          $.WebRequest('/business/action/business-audit', {'id': id,'is_audit':audit}, function (data) {
            if (data.code == 1) {
                $.ShowMsg(true, '提交成功。');
                getList();
            } else {
                $.ShowMsg(false, '提交失败。');
            }
        });
    })
}