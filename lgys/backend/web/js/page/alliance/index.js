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

//操作
function doForm(id) {
    loadFormPage('/alliance/page/alliance-form', {'id': id});
}
//获取列表
function getList() {
    $.LoadPage($('#dataListBox'), '/alliance/page/alliance-list', searchJson);
}

//发行记录
function exchangeLog(id) {
    loadFormPage('/alliance/page/exchange-log', {'id': id});
}

//承销记录
function deductionLog(id) {
    loadFormPage('/alliance/page/deduction-log', {'id': id});
}

//商户列表
function businessPage(id) {
    loadFormPage('/alliance/page/business', {'id': id});
}


//删除
function deleteItem(id) {
    $.ConfimDialog('确定要删除该联盟吗？', function () {
        $.WebRequest('/alliance/action/alliance-delete', {'id': id}, function (data) {
            if (data.code == 1) {
                $.ShowMsg(true, '删除成功。');
                getList();
            } else {
                $.ShowMsg(false, '删除失败。');
            }
        });
    })
}

function reportExport() {
    $.WebRequest('/alliance/action/export-alliance', searchJson, function (data) {
        if (data.code == 1) {
            window.open('/excel/' + data.file);
            $.ShowMsg(true, '导出成功。');
        } else {
            $.ShowMsg(false, '导出失败。');
        }
    });
}


function initList() {
    $('#dataListBox').find('select').each(function () { if ($(this).data('type') == 'is_hot') {
            $(this).change(function () {
                $.WebRequest('/alliance/action/alliance-ishot', {'id': $(this).attr('data-id'), 'is_hot': $(this).val()}, function (data) {
                    if (data.code == 1) {
                        $.ShowMsg(true, '设置成功。');
                    } else {
                        $.ShowMsg(false, '设置失败。');
                    }
                });
                return false;
            })
        }
    });
}