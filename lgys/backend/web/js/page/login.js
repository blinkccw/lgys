window.onload = function () {
    if (!isH5()) {
        window.location.href = '/site/h5';
    }
}
function isH5(){
    var elem = document.createElement('canvas');
    return !!(elem.getContext && elem.getContext('2d'));
}
$(function () {
    $("#loginForm").validate({
        onfocusout: false,
        onkeyup: false,
        showErrors: function (errorMap, errorList) {
            $.validatorMsg(errorMap, errorList);
        },
        submitHandler: function (form) {
            $('#btnSubmit').val('登录中...').attr('disabled', true);
            isLoadingShow = false;
            $.FormRequest(form, function (data) {
                if (data.code == 1) {
                    window.location.replace('/main/');
                } else {
                    $.ShowMsg(false, '登录失败。');
                }
            }, null, function () {
                $('#btnSubmit').val('登录').attr('disabled', false);
                $('#imgCode').refreshCode();
                $('#verifycode').val('');
            });
            return false;
        }
    });
    $('#imgCode').on('click', function () {
        $(this).refreshCode()
    });
});