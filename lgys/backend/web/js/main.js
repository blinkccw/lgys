var ue = null;
var cal = Calendar.setup({
    onSelect: function (cal) {
        cal.hide()
    },
    showTime: false,
    timePos: 'left',
    minuteStep: 60
});
//组件类型
var COMPONENTTYPE = {
    TITLE: 1,
    TEXT: 2,
    RICHTXT: 3,
    IMAGE: 4,
    GALLERY: 5,
    SLIDESHOW: 6,
    LANTERN: 7,
    BUTTON: 8,
    DIVIDER: 9,
    SPACER: 10,
    SEARCH: 11,
    VIDEO: 12,
    PRODUCTS: 13,
    PRODUCT: 14,
    PRODUCTSORT: 15,
    ARTICLES: 16,
    ARTICLESORT: 17,
    MAP: 18
};

var LeftMenuJsons = [
    {
        'type': 'one-menu',
        'url': {'name': '首页', 'url': '/main/default/welcome'}
    },
    {
        'type': 'menu',
        'menus': [
            {
                'title': '商户管理',
                'menus': [
                    {'name': '商户审核', 'url': '/business/page/audit'},
                    {'name': '商户列表', 'url': '/business/page'},
                    {'name': '商户分类', 'url': '/business/page/sort'},
                    {'name': '商户等级', 'url': '/business/page/grade'},
                    {'name': '抽成配置', 'url': '/business/page/config'}
                ]
            },
          {
                'title': '聚合管理',
                'menus': [
                    {'name': '聚合列表', 'url': '/business/page/aggregation'}
                    
                ]
            }]
    },
    {
        'type': 'one-menu',
        'url': {'name': '用户', 'url': '/vip/page'}
    },
    {
        'type': 'one-menu',
        'url': {'name': '联盟', 'url': '/alliance/page'}
    },
    {
        'type': 'one-menu',
        'url': {'name': '记录', 'url': '/report/page/pay'}
    },
    {
        'type': 'menu',
        'menus': [
            {
                'title': '管理员',
                'menus': [
                    {'name': '管理员列表', 'url': '/setting/page/user'},
                    {'name': '登录日志', 'url': '/setting/page/login-log'}
                ]
            },
            {
                'title': '系统',
                'menus': [
                    {'name': '系统配置', 'url': '/setting/page'},
                    {'name': '修改密码', 'url': '/setting/page/edit'}
                ]
            }
        ]
    }
];
(function ($) {
    $.extend({
        /**
         * 保存页面
         * @returns {undefined}
         */
        SavePage: function () {
            if ($mainBody)
                $mainBody.paiEditor('ActiveSavePage');
        },
        /**
         * 跳转页面
         */
        GetPage: function (pageID) {
            if ($mainBody)
                $mainBody.paiEditor('GetPage', pageID);
        },
        /**
         * 刷新页面
         * @returns {undefined}
         */
        ResetPage: function () {
            if ($mainBody)
                $mainBody.paiEditor('ResetPage');
        }
    })
})(jQuery);
var leftMenus, leftSubMenus, mainLeft;
$(function () {
    $mainContent = $('#mainContent');
    mainLeft = $('#mainLeft');
    leftMenus = $('#leftMenus');
    leftSubMenus = $('#leftSubMenus');
    var subMenusBox = leftSubMenus.find('.sub-menu-box:first');
    var allMenus = leftMenus.find('li');
    allMenus.each(function () {
        $(this).on('click', function () {
            cal.hide();
            allMenus.removeClass('cur');
            $(this).addClass('cur');
            var json = LeftMenuJsons[$(this).index()];
            subMenusBox.html('');
            if (json['type'] == 'menu') {
                $(json['menus']).each(function () {
                    subMenusBox.append('<div class="title">' + this.title + '</div>');
                    var ul = $('<ul></ul>');
                    $(this.menus).each(function () {
                        ul.append('<li data-url="' + this.url + '" title="' + this.name + '">' + this.name + '</li>');
                    });
                    var lis = ul.find('li');
                    lis.each(function () {
                        $(this).on('click', function () {
                            subMenusBox.find('li').removeClass('cur');
                            $(this).addClass('cur');
                            $.LoadMainPage($(this).data('url'), null, function () {
                                hideFormPage();
                            });
                        });
                    });
                    subMenusBox.append(ul);
                });
                subMenusBox.find('li:first').click();
                $mainContent.removeClass('main-right2').addClass('main-right1');
                mainLeft.removeClass('main-left2').addClass('main-left1');
            } else if (json['type'] == 'one-menu') {
                $mainContent.removeClass('main-right1');
                mainLeft.removeClass('main-left1');
                $.LoadMainPage(json['url']['url'], null, function () {
                    hideFormPage();
                });
            } else if (json['type'] == 'diy') {
                var html = '<div class="com-bar">';
                html += '<div class="title">组件</div>';
                html += '<div class="com-box" id="leftComBar">';
                $.each(json['menus'], function () {
                    html += '<div class="bar-title">' + this.name + '</div><dl>';
                    $.each(this.items, function () {
                        html += '<dd data-type="' + this.type + '" title="按住拖拉到页面"><div class="com-item"><i class="com-icon iconfont ' + this.class + '"></i><span>' + this.name + '</span></div></dd>';
                    });
                    html += '</dl>';
                });
                html += '</div></div>';
                subMenusBox.html(html);
                $mainContent.addClass('main-right2').removeClass('main-right1');
                mainLeft.addClass('main-left2').removeClass('main-left1');
                $.LoadMainPage('/design/', null, function () {
                    hideFormPage();
                });
            }
            return false;
        });
    });
    //   $(allMenus[0]).click();
    subMenusBox.slimScroll({height: '100%', width: '100%', allowPageScroll: true, color: '#909090', disableFadeOut: true});
})

/**
 * 加载表单页面
 * @param {type} url
 * @param {type} parm
 * @returns {undefined}
 */
function loadFormPage(url, parm) {
    $.LoadPage($('#formBox'), url, parm, function () {
        $('#formBox').show();
        $('#listBox').hide();
    });
}
function initCheckBox(box, callFun) {
    var checkboxs = box.find('.toggle-panel input[type="checkbox"]');
    //单选框事件
    checkboxs.each(function (index, item) {
        item = $(item);
        item.on('change', function () {
            var checked = true;
            if (item.hasClass('box-checked'))
                checked = false;
            item[checked ? 'addClass' : 'removeClass']('box-checked');
            if (typeof (callFun) == "function")
                callFun(checked);
            return false;
        });
    })
}

/**
 * 隐藏表单页面
 * @returns {undefined}
 */
function hideFormPage(callFun) {
    if ($('#formBox').length > 0) {
        $('#formBox').hide();
        $('#listBox').show();
    }
    if (typeof callFun == 'function')
        callFun();
}
/**
 * 列表全选事件
 * @param {type} btnAll
 * @returns {undefined}
 */
function InitSelected(box) {
    var allCheckBox = box.find('tbody input:checkbox');
    var btnAll = box.find('thead input:checkbox:first');
    btnAll.on('change', function () {
        allCheckBox.prop('checked', $(this).prop('checked'));
        return false;
    });
    allCheckBox.each(function () {
        $(this).on('change', function () {
            if (!$(this).prop('checked')) {
                btnAll.prop('checked', false);
            } else {
                var count = 0;
                allCheckBox.each(function () {
                    if ($(this).prop('checked'))
                        count++;
                })
                if (count == allCheckBox.length)
                    btnAll.prop('checked', true);
            }
            return false;
        });
    });
}


(function ($) {
    $.extend({
        //获取商品窗口
        GoodsWin: function (num, callFun) {
            var key = '';
            var pageIndex = 1;
            var selectFile = [];
            var getList = function (win) {
                $.WebRequest('/goods/action/win-list', {pageindex: pageIndex, key: key}, function (data) {
                    var listBox = win.find('.res-box ul:first');
                    if (pageIndex == 1)
                        listBox.html('');
                    if (data.list && data.list.length > 0) {
                        $.each(data.list, function () {
                            var item = '<li><div class="res-item"><img src="' + ($.isEmpty(this.img_url) ? '/images/image-empty.png' : this.img_url) + '" /><div class="res-selected iconfont icon-suc"></div></div><div class="res-name"></div></li>';
                            item = $(item);
                            item.attr({'data-id': this.id, 'data-name': this.name, 'data-path': this.img_url});
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
                        allList.off('click').on('click', function () {
                            var item = $(this);
                            if (!item.hasClass('selected')) {
                                if (num == 1) {
                                    allList.removeClass('selected');
                                    selectFile[0] = {'id': item.attr('data-id'), 'name': item.attr('data-name'), 'path': item.attr('data-path')};
                                    item.addClass('selected');
                                } else {
                                    selectFile[selectFile.length] = {'id': item.attr('data-id'), 'name': item.attr('data-name'), 'path': item.attr('data-path')};
                                    item.addClass('selected');
                                }
                            } else {
                                var newFile = [];
                                $(selectFile).each(function () {
                                    if (parseInt(item.attr('data-id')) != parseInt(this.id)) {
                                        newFile[newFile.length] = this;
                                    }
                                });
                                selectFile = newFile;
                                item.removeClass('selected');
                            }
                            return false;
                        });
                    } else {
                        if (pageIndex == 1) {
                            win.find('.no-data').show();
                            selectFile = [];
                        }
                    }
                });
            }
            $.OpenWin('商品选择', '/js/editor/bar/goods_win.html?t=' + $.getTimeStamp(), null, function (win) {
                getList(win);
                //保存事件
                win.find('input[data-type=save]:first').on('click', function () {
                    if (selectFile.length == 0) {
                        $.ShowMsg(false, '请选择商品。');
                        return false;
                    }
                    if (num > 1 && selectFile.length > num) {
                        $.ShowMsg(false, '最多只能选择' + num + '个商品。');
                        return false;
                    }
                    if (typeof (callFun) == "function")
                        callFun(selectFile);
                    win.remove();
                    return false;
                });
                //查询
                win.find('input[data-type=search]:first').on('click', function () {
                    key = win.find('input[data-type=name]:first').val();
                    pageIndex = 1;
                    getList(win);
                    return false;
                });
                //重置事件
                win.find('input[data-type=reset]:first').on('click', function () {
                    win.find('input[data-type=name]:first').val('');
                    key = '';
                    pageIndex = 1;
                    getList(win);
                    return false;
                });
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
        //获取商品列表
        GetGoodsList: function (json, info, callFun) {
            $.WebRequest('/design/action/get-goods-list', {json: json, info: info}, function (data) {
                if (typeof (callFun) == "function")
                    callFun(data.goods);
            });
        },
        //获取礼品窗口
        GiftWin: function (callFun) {
            var key = '';
            var pageIndex = 1;
            var winBox;
            var getList = function (win) {
                $.WebRequest('/mall/action/gift-win-list', {pageindex: pageIndex, key: key}, function (data) {
                    var listBox = win.find('tbody:first');
                    if (pageIndex == 1)
                        listBox.html('');
                    if (data.list && data.list.length > 0) {
                        $.each(data.list, function () {
                            var item = '<tr><td>' + this.name + '</td><td class="do"><a href="javascript:;">选择</a></td></tr>';
                            item = $(item);
                            item.attr({'data-id': this.id, 'data-name': this.name});
                            listBox.append(item);
                        });
                        listBox.find('td a').off('click').on('click', function () {
                            var tr = $(this).parents('tr:first');
                            if (typeof (callFun) == "function")
                                callFun({'id': tr.data('id'), 'name': tr.data('name')});
                            win.remove();
                            return false;
                        });
                    } else {
                        if (pageIndex == 1) {
                            listBox.html('<tr><td colspan="2" class="no-data">没有信息</td></tr>');
                        }
                    }
                    winBox = win.find('div.win-box');
                    var top = ($(window).height() - winBox.height()) / 2 + $.GetScrollTop();
                    winBox.css({'top': (top > 0 ? top : 0)});
                });
            }
            $.OpenWin('礼品选择', '/js/editor/bar/gift_win.html?t=' + $.getTimeStamp(), null, function (win) {
                getList(win);

                //查询
                win.find('input[data-type=search]:first').on('click', function () {
                    key = win.find('input[data-type=name]:first').val();
                    pageIndex = 1;
                    getList(win);
                    return false;
                });
                //重置事件
                win.find('input[data-type=reset]:first').on('click', function () {
                    win.find('input[data-type=name]:first').val('');
                    key = '';
                    pageIndex = 1;
                    getList(win);
                    return false;
                });
            });
        }
    })
})(jQuery);
