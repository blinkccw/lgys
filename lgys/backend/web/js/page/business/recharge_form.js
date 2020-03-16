$(function () {
    InitRechargeForm();
});

function InitRechargeForm() {
    $("#rechargeForm").validate({
        onfocusout: false,
        onkeyup: false,
        showErrors: function (errorMap, errorList) {
            $.validatorMsg(errorMap, errorList);
        },
        submitHandler: function (form) {
            $.FormRequest(form, function (data) {
                if (data.code == 1) {
                    $.ShowMsg(true, '充值成功。');
                    getRechargeList();
                    getList();
                    $.CloseWin();
                } else {
                    $.ShowMsg(false, '充值失败。');
                }
            });
            return false;
        }
    });
}