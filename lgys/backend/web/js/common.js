$(document).data('webupload', new Array());
var isLoadingShow = true;
var $mainContent = null;
var $pageurl = '';
var csrf = '_auth';
var urlcsrf = '_url';
$.extend($.validator.messages, {
    required: "不能为空，请输入。",
    remote: "请修正该字段。",
    email: "不是正确的电子邮箱格式。",
    url: "不是正确的网址格式。",
    date: "不是正确的日期格式。",
    dateISO: "不是正确的日期（ISO）格式。",
    number: "为数字。",
    zNumber: "为大于0的数字。",
    zzNumber: "为大于等于0的数字。",
    digits: "为整数。",
    zDigits: "为大于0的整数。",
    phone: "不是正确的手机号码。",
    creditcard: "请输入正确的信用卡号。",
    equalTo: "输入不一致。",
    accept: "请输入拥有合法后缀名的字符串。",
    maxlength: $.validator.format("长度不要超过 {0} 个字。"),
    minlength: $.validator.format("长度不少于 {0} 个字。"),
    rangelength: $.validator.format("长度范围为[{0} ,{1}] 个字。"),
    range: $.validator.format("为在 {0} 和 {1} 之间的值。"),
    max: $.validator.format("最大值为 {0} 。"),
    min: $.validator.format("最小值为 {0} 。")
});
(function ($) {
    $.fn.extend({
        refreshCode: function () {
            var now = new Date();
            $(this).attr("src", "/action/captcha/?" + now.getTime());
        }
    })
    $.extend({
        validatorMsg: function (errorMap, errorList) {
            if (errorList.length > 0) {
                var element = $(errorList[0].element);
                var tagName = element.attr('tag') || '';
                if (element.is('select')) {
                    $.ShowMsg(false, '请选择' + tagName + '。');
                } else {
                    $.ShowMsg(false, tagName + errorList[0].message);
                }
            }
        },
        //获取时间戳
        getTimeStamp: function () {
            return new Date().getTime();
        },
        //是否为空
        isEmpty: function (value) {
            return $.trim(value) == '';
        },
        //是否为空
        isNullEmpty: function (value) {
            return value == null || value == 'undefined' || value == [] || $.trim(value) == '';
        },
        //判断是否正整数
        isFloat: function (vl) {
            var pattern = /^(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*))$/;
            return pattern.test($.trim(vl));
        },
        //判断是否正整数(包含0)
        isZeroInt: function (vl) {
            var pattern = /^\d+$/;
            return pattern.test($.trim(vl));
        },
        //判断是否正整数
        isZInt: function (vl) {
            var pattern = /^[0-9]*[1-9][0-9]*$/;
            return pattern.test($.trim(vl));
        },
        //判断是否大于0的数字
        isZFloat: function (vl) {
            var pattern = /^[1-9]\d*(\.\d+)?$/;
            return pattern.test($.trim(vl));
        },
        //是否手机号
        isPhone: function (value) {
            var partten = /^1\d{10}$/;
            return partten.test($.trim(value));
        },
        //判断日期
        isDate: function (vl) {
            var r = $.trim(vl).match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);
            if (r == null)
                return false;
            var d = new Date(r[1], r[3] - 1, r[4]);
            return (d.getFullYear() == r[1] && (d.getMonth() + 1) == r[3] && d.getDate() == r[4]);
        },
        //验证长度
        checkLen: function (value, min, max) {
            value = $.trim(value);
            var len = value.length;
            return (len >= min && len <= max);
        },
        checkSelect: function (checkboxs) {
            var tab = false;
            $(checkboxs).each(function () {
                if ($(this).prop('checked')) {
                    tab = true;
                    return false;
                }
            });
            return tab;
        },
        getMax: function (data) {
            var max = 0;
            for (var i = 0; i < data.length; i++) {
                if (max < data[i])
                    max = data[i];
            }
            return max;
        },
        //日志
        Log: function (title, txt) {
            try {
                console.group(title);
                console.log(txt);
                console.groupEnd();
            } catch (e) {
            }
        },
        ShowMsgTime: null,
        ShowMsg: function (rel, txt) {
            var msg = $('#showMsg');
            if (msg.length == 0) {
                msg = $('<div></div>').attr({'id': 'showMsg', 'class': 'msg'});
                msg.hide();
                $('body').append(msg);
            }
            if ($.ShowMsgTime != null)
                clearTimeout($.ShowMsgTime);
            rel ? msg.removeClass('msg-error') : msg.addClass('msg-error');
            msg.html('<i class="iconfont ' + (rel ? 'icon-suc' : 'icon-error') + '"></i> ' + txt);
            msg.css('margin-left', 0 - msg.width() / 2);
            msg.fadeIn(200);
            $.ShowMsgTime = setTimeout(function () {
                msg.fadeOut(200);
            }, 4000);
            return false;
        },
        //获取屏幕滚动条高度
        GetScrollTop: function () {
            if ($(document).scrollTop() > 0)
                return $(document).scrollTop();
            if ($('body').scrollTop() > 0)
                return $('body').scrollTop();
            return 0;
        },
        //加载提示
        Loading: function (loadingTxt) {
            if (!isLoadingShow)
                return false;
            var loading = $('#loading');
            if (loading.length == 0) {
                loading = $('<div></div>').attr({'id': 'loading', 'class': 'loading'});
                loading.html('<div class="loadding-box"><span class="iconfont icon-loading"></span><span class="loading-txt"></span></div><div class="loadding-bg"></div>');
                loading.hide();
                $('body').append(loading);
            }
            loading.find('.loading-txt:first').html(loadingTxt);
            loading.fadeIn(100);
        },
        UnLoading: function () {
            if (!isLoadingShow) {
                isLoadingShow = true;
                return false;
            }
            $('#loading').fadeOut(100);
        },
        CloseWin: function () {
            var wins = $('.win-panel');
            if (wins.length > 0) {
                var win = $(wins[wins.length - 1]);
                win.fadeOut(200, function () {
                    win.remove();
                });
            }
        },
        OpenWin: function (title, url, data, successFun) {
            var win = $('<div class="win-panel"></div>');
            var bodyHeight = $(window).height();
            if ($('#mainContent').length > 0) {
                bodyHeight = Math.max(bodyHeight, $('#mainContent').outerHeight() + 38);
            }
            win.height(bodyHeight);
            win.append(' <div class="win-bg"></div><div class="win-box"><div class="win-title"><h1>' + title + '</h1><span class="win-close iconfont icon-delete" title="关闭"></span></div><div class="win-content"></div></div>');
            win.find('.win-close').click(function () {
                win.remove();
//                win.fadeOut(200, function () {
//                    win.remove();
//                })
            });
            var winContent = win.find('div.win-content');
            var winBox = win.find('div.win-box');
            $.LoadPage(winContent, url, data, function () {
                $('body').append(win);
                win.show();
                //  win.fadeIn();
                win.find('input[type=text]:eq(0)').focus();
                var top = ($(window).height() - winBox.height()) / 2 + $.GetScrollTop();
                winBox.css({'margin-left': 0 - winBox.width() / 2, 'top': (top > 0 ? top : 0)});
                if (typeof successFun == 'function')
                    successFun(win);
            });

        },
        ShowWin: function (title, html, data, successFun, closeFun) {
            var win = $('<div class="win-panel"></div>');
            var bodyHeight = $(window).height();
            if ($('#mainContent').length > 0) {
                bodyHeight = Math.max(bodyHeight, $('#mainContent').outerHeight() + 38);
            }
            win.height(bodyHeight);
            win.append(' <div class="win-bg"></div><div class="win-box"><div class="win-title"><h1>' + title + '</h1><span class="win-close iconfont icon-delete" title="关闭"></span></div><div class="win-content"></div></div>');
            win.find('.win-close').click(function () {
                win.remove();
                if (typeof successFun == 'function')
                    closeFun(win);
            });
            var winContent = win.find('div.win-content');
            winContent.html(html);
            var winBox = win.find('div.win-box');
            $('body').append(win);
            win.show();
            var inputs = win.find('input[type=text]:eq(0)');
            if (inputs.length > 0)
                $(inputs[0]).focus();
            var top = ($(window).height() - winBox.height()) / 2 + $.GetScrollTop();
            winBox.css({'margin-left': 0 - winBox.width() / 2, 'top': (top > 0 ? top : 0)});
            if (typeof successFun == 'function')
                successFun(win);
        },
        //确认窗
        ConfimDialog: function (msg, sureFun, cancelFun) {
            var win = $('<div class="win-panel"></div>');
            var html = '<div class="win-bg"></div><div class="win-box"><div class="win-box-inner"><div class="win-title"><h1>确认提示</h1><span class="win-close iconfont icon-delete" title="关闭"></span></div><div class="win-content">';
            html += '<div class="main-form"><div class="form-text">' + msg + '</div><div class="form-btn"><input type="button" class="btn" value="确定" /> <input type="button" class="btn btn-gray" value="取消" /></div></div>';
            html += '</div></div></div>';
            win.append(html);
            win.find('.win-close').on('click', function () {
                $.CloseWin(win);
                if (typeof (cancelFun) == "function")
                    cancelFun();
            });
            var btns = win.find('input');
            $(btns[0]).on('click', function () {
                if (typeof (sureFun) == "function")
                    sureFun();
                $.CloseWin(win);
                return false;
            });
            $(btns[1]).on('click', function () {
                if (typeof (cancelFun) == "function")
                    cancelFun();
                $.CloseWin(win);
                return false;
            });
            $('body').append(win);
            var winBox = win.find('div.win-box');
            win.fadeIn();
            $(btns[0]).focus();
            winBox.css({'margin-top': 0 - winBox.height() / 2, 'margin-left': 0 - winBox.width() / 2});
        },
        SetWinHeight: function () {
            var win = $('.win-panel:first');
            var winBox = win.find('div.win-box');
            var top = ($(window).height() - winBox.height()) / 2 + $.GetScrollTop();
            winBox.css({'margin-left': 0 - winBox.width() / 2, 'top': (top > 0 ? top : 0)});
        },
        AutoImageSize: function (item, type, maxWidth) {
            item = $(item);
            maxWidth = maxWidth || '100%';
            if (type == 1) {
                item.css({'width': 'auto', 'height': 'auto', 'maxHeight': '100%', 'maxWidth': maxWidth});
            } else {
                var imgWidth = item.width();
                var imgHeight = item.height();
                var parent = item.parent();
                var boxH = parent.height();
                var boxW = parent.width();
                if ((imgWidth / imgHeight) < (boxW / boxH)) {
                    item.css({'width': '100%', 'height': 'auto', 'maxHeight': 'none'})
                } else {
                    item.css({'width': 'auto', 'height': '100%', 'maxWidth': 'none'})
                }
            }
        },
        //加载页面
        LoadPage: function (panel, url, postData, successFun) {
            $.Loading('加载中。。。');
            var token = $.GetToken();
            if (token != null) {
                if (postData == null)
                    postData = {};
                postData[csrf] = token;
                postData[urlcsrf] = $pageurl;
            }
            panel.load(url, postData, function (response, status, xhr) {
                $.UnLoading();
                if (xhr.status === 302) {
                    window.location.href = '/';
                }
                if (status == "success" || status == "notmodified") {
                    if (typeof successFun == 'function')
                        successFun();
                } else {
                    if (xhr.status === 404) {
                        $.ShowMsg(false, response);
                    } else {
                        $.ShowMsg(false, '加载失败。');
                    }
                }
            });
        },
        //主页面加载
        LoadMainPage: function (url, postData, successFun) {
            $.LoadPage($mainContent, url, postData, function () {
                $pageurl = url;
                if (typeof successFun == 'function') {
                    successFun();
                }
            });
        },
        /**
         * 资源选择
         * @param {type} fileType 文件类型
         * @param {type} lable 默认选中标签
         * @param {type} selectType (0:单文件选择，1:多文件选择)
         * @param {type} num 选择个数
         * @param {type} callFun 保存事件
         * @returns {undefined}
         */
        RescourceWin: function (fileType, lable, selectType, num, callFun, isWebUrl) {
            selectType = selectType || 0;
            num = num || 1;
            if (isWebUrl !== false)
                isWebUrl = true;
            if (selectType == 0)
                num = 1;
            var lableID = lable || 0;
            var oldLable=lableID;
            var name = '';
            var pageIndex = 1;
            var selectFile = [];
            //获取列表
            var getList = function (win) {
                $.WebRequest('/resource/action/win-list', {type: fileType, lable: lableID, page_index: pageIndex, name: name}, function (data) {
                    var listBox = win.find('.res-box ul:first');
                    if (pageIndex == 1)
                        listBox.html('');
                    if (data.list && data.list.length > 0) {
                        $.each(data.list, function () {
                            var item = '<li><div class="res-item">';
                            var fileSize = '';
                            switch (fileType) {
                                case UPFILETYPE.IMAGE:
                                    fileSize = $.ShowResSize(this.size) + "(" + this.pixel + ")";
                                    item += '<div class="res-img"><img src="' + top.SITECONFIG.IMG_URL + this.small_path + '" /></div>';
                                    break;
                                case UPFILETYPE.FLASH:
                                    fileSize = $.ShowResSize(this.size);
                                    item += '<i class="icon_resource icon_resource_flash"></i>';
                                    break;
                                case UPFILETYPE.FILE:
                                    fileSize = $.ShowResSize(this.size);
                                    switch (this.exten.toLowerCase()) {
                                        case 'doc':
                                        case 'docx':
                                            item += '<i class="icon_resource icon_resource_word"></i>';
                                            break;
                                        case 'xla':
                                        case 'xls':
                                        case 'xlsx':
                                        case 'xlt':
                                        case 'xlw':
                                            item += '<i class="icon_resource icon_resource_excel"></i>';
                                            break;
                                        case 'rar':
                                        case 'zip':
                                            item += '<i class="icon_resource icon_resource_rar"></i>';
                                            break;
                                        case 'pdf':
                                            item += '<i class="icon_resource icon_resource_pdf"></i>';
                                            break;
                                        case 'txt':
                                            item += '<i class="icon_resource icon_resource_text"></i>';
                                            break;
                                    }
                                    break;
                                case UPFILETYPE.VIDEO:
                                    fileSize = $.ShowResSize(this.size);
                                    item += '<i class="icon_resource  iconfont icon-video"></i>';
                                    break;
                            }
                            item += '<div class="res-size"><div class="mask"></div><span>' + fileSize + '</span></div><div class="res-selected iconfont icon-suc"></div></div><div class="res-name"></div></li>';
                            item = $(item);
                            item.attr({'data-id': this.id, 'data-path': this.path, 'data-name': this.name, 'data-exten': this.exten});
                            item.find('.res-name').attr('title', this.name).text(this.name);
                            listBox.append(item);
                            $.each(selectFile, function () {
                                if (parseInt(this.id) == parseInt(item.attr('data-id'))) {
                                    item.addClass('selected');
                                }
                            })
                        });
                        win.find('.no-data').hide();
                        var allList = listBox.find('li');
                        allList.each(function () {
                            allList.off('click').on('click', function () {
                                var item = $(this);
                                if (!item.hasClass('selected')) {
                                    if (selectType == 0) {
                                        allList.removeClass('selected');
                                        selectFile[0] = {'id': item.attr('data-id'), 'name': item.attr('data-name'), 'path': item.attr('data-path'), 'webpath': top.SITECONFIG.IMG_URL + item.attr('data-path'), 'exten': item.attr('data-exten')};
                                        item.addClass('selected');
                                    } else if (selectType == 1) {
                                        if (selectFile.length >= num) {
                                            top.$.ShowMsg(false, '只能选择' + num + '张图片。');
                                            return false;
                                        }
                                        selectFile[selectFile.length] = {'id': item.attr('data-id'), 'name': item.attr('data-name'), 'path': item.attr('data-path'), 'webpath': top.SITECONFIG.IMG_URL + item.attr('data-path'), 'exten': item.attr('data-exten')};
                                        item.addClass('selected');
                                    }
                                } else {
                                    if (selectType == 0) {
                                        selectFile = [];
                                        item.removeClass('selected');
                                    } else if (selectType == 1) {
                                        var index = -1;
                                        for (var i = 0; i < selectFile.length; i++) {
                                            if (parseInt(selectFile[i]['id']) == parseInt(item.attr('data-id'))) {
                                                index = i;
                                                break;
                                            }
                                        }
                                        if (index >= 0) {
                                            selectFile.splice(index, 1);
                                            item.removeClass('selected');
                                        }
                                    }
                                }
                                return false;
                            });
                        });
                    } else {
                        if (pageIndex == 1) {
                            win.find('.no-data').show();
                            selectFile = [];
                        }
                    }
                });
            }
            $.OpenWin('资源选择', '/resource/page/win', null, function (win) {
                //获取列表
                getList(win);
                var size = 1024 * 1024;
                var accept = {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/gif,image/jpg,image/jpeg,image/bmp,image/png'
                }
                var server = '/upload/uploadimage';
                switch (fileType) {
                    case UPFILETYPE.IMAGE:
                        size = 1024 * 1024;
                        break;
                    case UPFILETYPE.FILE:
                        size = 20 * 1024 * 1024;
                        server = '/upload/uploadfile';
                        accept = {
                            title: 'File',
                            extensions: 'txt,pdf,xla,xls,xlsx,xlt,xlw,zip,rar,doc,docx',
                            mimeTypes: ''
                        }
                        break;
                    case UPFILETYPE.FLASH:
                        server = '/upload/uploadflash';
                        accept = {
                            title: 'File',
                            extensions: 'swf',
                            mimeTypes: '*.swf'
                        }
                        break;
                    case UPFILETYPE.VIDEO:
                        size = 5 * 1024 * 1024;
                        server = '/upload/uploadvideo';
                        accept = {
                            title: 'File',
                            extensions: 'mp4',
                            mimeTypes: '*.mp4'
                        }
                        break;
                }
                var upload = $.onUpload({
                    server: server,
                    pick: {'id': '#comAddImg', 'label': '本地上传', 'multiple': true},
                    fileNumLimit: 50,
                    fileSingleSizeLimit: 2 * 1024 * 1024,
                    fileSingleSizeLimit: size,
                    accept: accept,
                    formData: {
                        lable: lableID,
                        '_auth': $.GetToken(),
                        encode: 'utf-8'
                    }
                }, {
                    'startUpload': function () {
                        $.Loading('正在上传中。。');
                        //$.UpLoading(0);
                    },
                    'uploadStart': function () {
                        upload.option('formData', {
                            lable: lableID,
                            encode: 'utf-8'
                        });

                    },
                    'uploadFinished': function () {
                        $.UnLoading();
                        //$.UpLoading(0);
                        pageIndex = 1;
                        getList(win);
                    },
                    'uploadProgress': function (file, percentage) {
                        // $.UpLoading(percentage * 100);
                    },
                    'uploadSuccess': function (file, response) {
                        if (response.state == 'SUCCESS') {
                            var temFile = {'id': response.id, 'name': response.original, 'path': response.url, 'webpath': top.SITECONFIG.IMG_URL + response.url};
                            if (selectType == 0) {
                                selectFile[0] = temFile;
                            } else if (selectType == 1) {
                                if (selectFile.length < num)
                                    selectFile[selectFile.length] = temFile;
                            }
                            $.ShowMsg(true, '上传成功。');
                        } else {
                            $.ShowMsg(false, '上传失败。');
                        }
                    }
                });
                //保存事件
                win.find('input[data-type=save]:first').on('click', function () {
                    if (selectFile.length == 0) {
                        $.ShowMsg(false, '请选择资源。');
                        return false;
                    }
                    if (typeof (callFun) == "function")
                        callFun(selectFile);
                    win.remove();
                    return false;
                });
                //查询
                win.find('input[data-type=search]:first').on('click', function () {
                    name = win.find('input[data-type=name]:first').val();
                    lableID= win.find('select[data-type=lables]:first').val();
                    pageIndex = 1;
                    getList(win);
                    return false;
                });
                //重置事件
                win.find('input[data-type=reset]:first').on('click', function () {
                    win.find('input[data-type=name]:first').val('');
                    win.find('select[data-type=lables]:first').val(oldLable);
                    lableID=oldLable;
                    name = '';
                    pageIndex = 1;
                    getList(win);
                    return false;
                });
                win.find('select[data-type=lables]:first').val(lableID);
                //滚动分页
                var imageList = win.find('div.res-box:first');
                imageList.scroll(function () {
                    var box = $(this);
                    var boxHeight = box.height();
                    var scrollTop = box[0].scrollTop;
                    var scrollHeight = box[0].scrollHeight;
                    if (scrollTop + boxHeight >= scrollHeight) {
                        pageIndex++
                        getList(win);
                    }
                });
            });
        },
        ShowResSize: function (size) {
            if (size < 1024 * 1024)
                return (size / 1024).toFixed(2) + 'K';
            return (size / (1024 * 1024)).toFixed(2) + 'M';
        },
        //请求出错处理
        ErrorFun: function (data) {
            if (data.code != 1) {
                if (!$.isNullEmpty(data.message)) {

                    top.$.ShowMsg(false, data.message);
                    return false;
                }
            }
            return true;
        },
        GetToken: function () {
            var csrfToken = $('meta[name=csrf-token]');
            if (csrfToken.length > 0)
                return csrfToken.attr('content');
            return null;
        },
        //表单请求
        FormRequest: function (form, successFun, errorFun, completeFun) {
            form = $(form);
            var token = $.GetToken();
            var postData = form.serializeArray();
            if (token != null) {
                postData[postData.length] = {'name': csrf, 'value': token};
                postData[postData.length] = {'name': urlcsrf, 'value': $pageurl};
            }
            $.BaseRequest(form.attr('action'), postData, successFun, errorFun, completeFun);
        },
        //普通请求
        WebRequest: function (url, postData, successFun, errorFun, completeFun) {
            var token = $.GetToken();
            if (token != null) {
                if (postData == null)
                    postData = {};
                postData[csrf] = token;
                postData[urlcsrf] = $pageurl;
            }
            $.BaseRequest(url, postData, successFun, errorFun, completeFun);
        },
        //请求方式
        BaseRequest: function (url, postData, successFun, errorFun, completeFun) {
            $.Loading('正在请求中。。。');
            jQuery.ajax({
                url: url,
                type: "post",
                data: postData,
                dataType: "json",
                success: function (data) {
                    if (!$.ErrorFun(data))
                        return false;
                    if (typeof successFun == 'function') {
                        successFun(data);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    if (XMLHttpRequest.status == 403) {
                        window.location.href = '/';
                        return false;
                    }
                    if (typeof errorFun == 'function') {
                        errorFun();
                    } else {
                        top.$.ShowMsg(false, '请求失败。');
                    }
                },
                complete: function (XMLHttpRequest, textStatus) {
                    $.UnLoading();
                    if (typeof completeFun == 'function')
                        completeFun();
                }
            });
        },
        //上传文件
        onUpload: function (options, actions) {
            var defaultOpts = {
                compress: false,
                // 选完文件后，是否自动上传。
                auto: true,
                // swf文件路径
                swf: '/js/plugins/webupload/Uploader.swf',
                // 文件接收服务端。
                server: '/upload/uploadimage',
                //通过粘贴来添加截屏的图片
//                paste:document.body,
                // form数据
                formData: {
                    encode: 'utf-8'
                },
                // 选择文件的按钮。可选。
                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                pick: {
                    'id': '.btn-upload-image',
                    'multiple': false
                },
                // 上传的input的name
                fileVal: 'upfile',
                //重复验证
                duplicate: true,
                // 禁用全局拖拽
                disableGlobalDnd: true,
                //文件限制
                fileNumLimit: 50,
                fileSingleSizeLimit: 5 * 1024 * 1024, // 5 M
                // 只允许选择图片文件。
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/gif,image/jpg,image/jpeg,image/bmp,image/png'
                }
            }
            var newOptions = $.extend({}, defaultOpts, options);
            var uploader = WebUploader.create(newOptions);
            if ($.isPlainObject(actions) || $.isArray(actions)) {
                for (var key in actions) {
                    $.isFunction(actions[key]) && uploader.on(key, actions[key]);
                }
            }
            // 拖拽时不接受 js, txt 文件。
            uploader.on('dndAccept', function (items) {
                var denied = false,
                        len = items.length,
                        i = 0,
                        // 修改js类型
                        unAllowed = 'text/plain;application/javascript';
                for (; i < len; i++) {
                    // 如果在列表里面
                    if (~unAllowed.indexOf(items[ i ].type)) {
                        denied = true;
                        break;
                    }
                }

                return !denied;
            });
            // 拖拽时不接受 js, txt 文件。
            uploader.on('error', function (items) {
                if (items == 'Q_FILE_EMPTY')
                    top.$.ShowMsg(false, '上传文件大小不能为0k。');
                if (items == 'Q_TYPE_DENIED')
                    top.$.ShowMsg(false, '上传文件格式不正确,格式为' + newOptions.accept.extensions + '。');
                if (items == 'F_EXCEED_SIZE')
                    top.$.ShowMsg(false, '上传文件大小不能超过' + (newOptions.fileSingleSizeLimit / 1024 / 1024) + 'M。');
                var denied = false,
                        len = items.length,
                        i = 0,
                        // 修改js类型
                        unAllowed = 'text/plain;application/javascript ';
                for (; i < len; i++) {
                    // 如果在列表里面
                    if (~unAllowed.indexOf(items[ i ].type)) {
                        denied = true;
                        break;
                    }
                }
                return !denied;
            });
            uploader.on('uploadError', function (file, type) {
                top.$.ShowMsg(false, '上传失败。');
            });
            $(document).data('webupload').push(uploader);
            return uploader;
        }
    })
})(jQuery);
var UPFILETYPE = {
    IMAGE: 1,
    FLASH: 2,
    FILE: 3,
    VIDEO: 4,
    ZIP:5,
    WX:6,
    OTHER:7
};
var LABELTYPE = {
    SHOP: 1
};
var SELECTTYPE = {
    ONE: 0,
    MORE: 1
}

