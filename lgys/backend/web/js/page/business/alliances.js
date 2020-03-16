//删除
function deleteAlliance(id) {
    $.ConfimDialog('确定要把该商户移出该联盟吗？', function () {
        $.WebRequest('/business/action/alliance-delete', {'id': id}, function (data) {
            if (data.code == 1) {
                $.ShowMsg(true, '删除成功。');
                showAlliance(businessID);
            } else {
                $.ShowMsg(false, '删除失败。');
            }
        });
    })
}
