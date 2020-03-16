$(function () {
    InitForm();
});

function InitForm() {
    $("#doForm").validate({
        onfocusout: false,
        onkeyup: false,
        showErrors: function (errorMap, errorList) {
            $.validatorMsg(errorMap, errorList);
        },
        submitHandler: function (form) {
            $.FormRequest(form, function (data) {
                if (data.code == 1) {
                    $.ShowMsg(true, '提交成功。');
                    getList();
                    hideFormPage();
                } else {
                    $.ShowMsg(false, '提交失败。');
                }
            });
            return false;
        }
    });
}