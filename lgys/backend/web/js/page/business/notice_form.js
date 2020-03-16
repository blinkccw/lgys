var richProductConfig = {toolbars: [['source', 'Undo', 'Redo', 'bold', 'italic', 'underline', 'forecolor', 'fontsize', 'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', 'removeformat','simpleupload']], initialFrameHeight: 180};
var temID = $.getTimeStamp();
$(function () {
    InitForm();
    InitRich();
    InitEvent();
});

//商品详情富文本
function InitRich() {
    //富文本
    if (ue != null) {
        ue.destroy();
        ue = null;
    }
    ue = UE.getEditor('txtRichInfo', richProductConfig);
}

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


function InitEvent() {
    var upload = $.onUpload({
        server: '/upload?action=uploadimage',
        pick: {'id': '#btnImg', 'label': '+选择图片', 'multiple': false},
        fileNumLimit: 1,
        fileSingleSizeLimit: 2 * 1024 * 1024,
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/gif,image/jpg,image/jpeg,image/bmp,image/png'
        },
        formData: {
            '_auth': $.GetToken(),
            encode: 'utf-8'
        }
    }, {
        'startUpload': function () {
            $.Loading('正在上传中。。');
        },
        'uploadStart': function () {
        },
        'uploadFinished': function () {
            $.UnLoading();
        },
        'uploadProgress': function (file, percentage) {
        },
        'uploadSuccess': function (file, response) {
            if (response.state == 'SUCCESS') {
                $('#txtFacePath').val(response.name);
                $('#previewImg').find('img').attr('src', response.url);
                $.ShowMsg(true, '上传成功。');
            } else {
                $.ShowMsg(false, '上传失败。');
            }
        }
    });
}