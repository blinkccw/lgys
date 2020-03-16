var searchJson = {
    'key': '',
    'sort_id': 0,
    'min_points': null,
    'max_points': null,
    'grade_id': -1,
    'status': -1,
    'is_audit': 1,
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
        $('#ddlSearchSort').val(0);
        $('#ddlSearchGrade').val(-1);
        $('#txtSearchKey').val('');
        $('#ddlSearchStatus').val(-1);
        $('#txtMinPoints').val('');
        $('#txtMaxPoints').val('');
        search();
        return false;
    });

    $('#btnExcel').on('click', function () {
        reportExport();
        return false;
    });
});
function search() {
    searchJson['sort_id'] = $('#ddlSearchSort').val();
    searchJson['grade_id'] = $('#ddlSearchGrade').val();
    searchJson['key'] = $('#txtSearchKey').val();
    searchJson['status'] = $('#ddlSearchStatus').val();
    searchJson['min_points'] = $('#txtMinPoints').val();
    searchJson['max_points'] = $('#txtMaxPoints').val();
    searchJson['page_index'] = 1;
    getList();
}

//操作
function doForm(id) {
    loadFormPage('/business/page/business-form', {'id': id});
}
//发信息
function noticeForm(id) {
    loadFormPage('/business/page/notice-form', {'id': id});
}
//获取列表
function getList() {
    $.LoadPage($('#dataListBox'), '/business/page/business-list', searchJson);
}
//充值
function rechargePage(id) {
    loadFormPage('/business/page/recharge', {'id': id});
}

//发行记录
function exchangeLog(id) {
    loadFormPage('/business/page/exchange-log', {'id': id});
}

//承销记录
function deductionLog(id) {
    loadFormPage('/business/page/deduction-log', {'id': id});
}

function showItem(id) {
    loadFormPage('/business/page/business-info', {'id': id});
}
function showAlliance(id) {
    loadFormPage('/business/page/alliances', {'id': id});
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

function setItemStatus(id, status) {
    $.ConfimDialog(status == 0 ? '确定是否下架该商户吗？' : '确定是否上架该商户吗？', function () {
        $.WebRequest('/business/action/business-status', {'id': id, 'status': status}, function (data) {
            if (data.code == 1) {
                $.ShowMsg(true, '设置成功。');
                getList();
            } else {
                $.ShowMsg(false, '设置失败。');
            }
        });
    })
}

function reportExport() {
    $.WebRequest('/business/action/export-business', searchJson, function (data) {
        if (data.code == 1) {
            window.open('/excel/' + data.file);
            $.ShowMsg(true, '导出成功。');
        } else {
            $.ShowMsg(false, '导出失败。');
        }
    });
}

function initList() {
    $('#dataListBox').find('select').each(function () {
        if ($(this).data('type') == 'status') {
            $(this).change(function () {
                $.WebRequest('/business/action/business-status', {'id': $(this).attr('data-id'), 'status': $(this).val()}, function (data) {
                    if (data.code == 1) {
                        $.ShowMsg(true, '设置成功。');
                    } else {
                        $.ShowMsg(false, '设置失败。');
                    }
                });
                return false;
            })
        } else if ($(this).data('type') == 'is_hot') {
            $(this).change(function () {
                $.WebRequest('/business/action/business-ishot', {'id': $(this).attr('data-id'), 'is_hot': $(this).val()}, function (data) {
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