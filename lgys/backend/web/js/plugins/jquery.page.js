//分页插件
/**
 2014-08-05 ch
 **/
(function ($) {
    var ms = {
        init: function (obj, args) {
            return (function () {
                ms.fillHtml(obj, args);
                ms.bindEvent(obj, args);
            })();
        },
        //填充html
        fillHtml: function (obj, args) {
            return (function () {
                obj.empty();
                if (args.pageCount == 0 || args.count <= 0)
                    return false;
                if (args.count > 0)
                    obj.append('<div class="page-msg">共<span>' + args.count + '</span>条记录/ 每页<span>' + args.pageSize + '</span>条 /共<span>' + args.pageCount + '</span>页 /当前第<span>' + args.current + '</span>页</div>');
                var pageInner = $('<ul></ul>');
                //首页
                if (args.current == 1) {
                    pageInner.append('<li class="li-first disabled">«</li>');
                } else {
                    pageInner.append('<li class="li-first">«</li>');
                }
                //上一页
                if (args.current > 1) {
                    pageInner.append('<li class="li-pre">‹</li>');
                } else {
                    pageInner.append('<li class="li-pre disabled">‹</li>');
                }
                //中间页码
//                var pageNums = $('<div></div>');
//                pageNums.hide();
//                if (args.current != 1 && args.current >= 4 && args.pageCount != 4) {
//                    pageNums.append('<a href="javascript:;" class="tcdNumber">' + 1 + '</a>');
//                }
//                if (args.current - 2 > 2 && args.current <= args.pageCount && args.pageCount > 5) {
//                    pageNums.append('<span>...</span>');
//                }
//                var start = args.current - 2, end = args.current + 2;
//                if ((start > 1 && args.current < 4) || args.current == 1) {
//                    end++;
//                }
//                if (args.current > args.pageCount - 4 && args.current >= args.pageCount) {
//                    start--;
//                }
//                for (; start <= end; start++) {
//                    if (start <= args.pageCount && start >= 1) {
//                        if (start != args.current) {
//                            pageNums.append('<a href="javascript:;" class="tcdNumber">' + start + '</a>');
//                        } else {
//                            pageNums.append('<span class="current">' + start + '</span>');
//                        }
//                    }
//                }
//                if (args.current + 2 < args.pageCount - 1 && args.current >= 1 && args.pageCount > 5) {
//                    pageNums.append('<span>...</span>');
//                }
//                if (args.current != args.pageCount && args.current < args.pageCount - 2 && args.pageCount != 4) {
//                    pageNums.append('<a href="javascript:;" class="tcdNumber">' + args.pageCount + '</a>');
//                }
                //下一页

                if (args.current < args.pageCount) {
                    pageInner.append('<li class="li-next">›</li>');
                    pageInner.append('<li class="li-last">»</li>');
                } else {
                    pageInner.append('<li class="li-next disabled">›</li>');
                    pageInner.append('<li class="li-last disabled">»</li>');

                }

                //     pageInner.append('<div class="text"><input name="PageGO" class="main_form" type="text">页</div>');
                //  pageInner.append('<a class="btnpage btnpage_go" href="javascript:;">GO</a>');
                obj.append(pageInner);
                //obj.append(pageNums);
            })();
        },
        //绑定事件
        bindEvent: function (obj, args) {
            return (function () {
//                obj.on("click", "a.tcdNumber", function () {
//                    if (args.pageCount == 1)
//                        return false;
//                    var current = parseInt($(this).text());
//                    if (current <= 0)
//                        current = 1;
//                    ms.fillHtml(obj, {"current": current, "pageCount": args.pageCount});
//                    if (typeof (args.backFn) == "function") {
//                        args.backFn(current);
//                    }
//                });
                //首页
                obj.on("click", "li.li-first", function () {
                    if (args.pageCount == 1)
                        return false;
                    var current = 1;
                    ms.fillHtml(obj, {"current": current, "pageCount": args.pageCount});
                    if (typeof (args.backFn) == "function") {
                        args.backFn(current);
                    }
                });
                //上一页
                obj.on("click", "li.li-pre", function () {
                    var current = args.current;
                    if (current == 1)
                        return false;
                    ms.fillHtml(obj, {"current": current - 1, "pageCount": args.pageCount});
                    if (typeof (args.backFn) == "function") {
                        args.backFn(current - 1);
                    }
                });
                //下一页
                obj.on("click", "li.li-next", function () {
                    if (args.pageCount == 1)
                        return false;
                    var current = args.current;
                    if (current == args.pageCount)
                        return false;
                    ms.fillHtml(obj, {"current": current + 1, "pageCount": args.pageCount});
                    if (typeof (args.backFn) == "function") {
                        args.backFn(current + 1);
                    }
                });
                //尾页
                obj.on("click", "li.li-last", function () {
                    if (args.pageCount == 1)
                        return false;
                    var current = args.pageCount;
                    ms.fillHtml(obj, {"current": current, "pageCount": args.pageCount});
                    if (typeof (args.backFn) == "function") {
                        args.backFn(current);
                    }
                });
                //跳转
//                obj.on("click", "a.btnpage_go", function () {
//                    if (args.pageCount == 1)
//                        return false;
//                    var current = obj.find('.text input').val();
//                    if (!(current > 0 && current <= args.pageCount)) {
//                        current = 1;
//                    }
//                    ms.fillHtml(obj, {"current": current, "pageCount": args.pageCount});
//                    if (typeof (args.backFn) == "function") {
//                        args.backFn(current);
//                    }
//                });
            })();
        }
    }
    $.fn.createPage = function (options) {
        var args = $.extend({
            pageSize: 12,
            pageCount: 10,
            current: 1,
            count: 0,
            backFn: function () {
            }
        }, options);
        ms.init(this, args);
    }
})(jQuery);