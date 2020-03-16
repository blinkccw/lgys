$(function () {
    InitEvent();
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
            if ($('#ddlSort').val() == 0) {
                $.ShowMsg(false, '请选择分类。');
                return false;
            }
            if ($.isEmpty($('#txtVipID').val())) {
                $.ShowMsg(false, '请选择用户。');
                return false;
            }
            if ($.isEmpty($('#txtLongitude').val()) || $.isEmpty($('#txtLatitude').val())) {
                $.ShowMsg(false, '请设置门店定位。');
                return false;
            }
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
    $('#btnLocation').on('click', function () {
        shopLocation();
        return false;
    });

    $('#btnVip').on('click', function () {
        $.OpenWin('选择用户', '/vip/page/vip-win', {id: parseInt($('#txtVipID').val())}, function () {
            winCallFun = function (id, face, name) {
                $('#txtVipID').val(id);
                $('#vipFace').attr('src',face);
                $('#vipBox').html(name);
            }
        });
    });
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

//操作
function shopLocation() {
    $.OpenWin('腾讯坐标拾取', '/business/page/shop-location', {'longitude': $('#txtLongitude').val(), 'latitude': $('#txtLatitude').val()});
}
