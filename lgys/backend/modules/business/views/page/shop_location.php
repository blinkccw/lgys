<?php

use yii\helpers\Html;
?>
<style type="text/css">
    #map_tooles{ padding:8px 10px;background:#eef1f5;position: relative;z-index:100;color:#7f9fcb;}
    #map_city_name{ font-weight:bold; font-size: 14px;}
    #map_change_btn{text-decoration:underline;cursor:pointer}
    .map_change_city{ margin-left: 10px;}
    #map_level{margin-left: 10px;}
    #map_city {width:356px;height:380px;padding:10px;border: 2px solid #D6D6D6;position: absolute;left:56px;top:30px;z-index: 999;background: #fff;overflow: auto;color: black;}
    #map_city .city_class{background: #fff;}
    #map_city .city_container {margin-top: 10px;margin-bottom: 10px;background: #fff;}
    #map_city .city_container_left {width: 48px;float: left;}
    #map_city .city_container_right {width: 289px;float: left;}
    #map_city .city_close {width: 20px;height: 20px;display: inline-block;float: right;font-size: 20px;font-weight: normal;cursor: pointer;}
    #map_city .city_name {line-height: 20px;margin-left: 5px;color: #2F82C4;cursor: pointer;display: inline-block;font-size: 12px;}
    #map_side_left{width:260px;height:360px;float: left;overflow: auto; line-height:18px; overflow-x:hidden }
    #map_side_right {width:630px;height:360px;margin-left: 10px;border-left:1px solid #e3e6ec;float: left;font-size: 12px;}
    #map_container{width:100%;height:360px}
    .info_list {clear:both;cursor:pointer;width:100%; padding:10px;word-break:break-all;word-wrap:break-word;width:230px}
    #map_cur_add{ float: right;}
    .left_msg{padding:10px;}
    #txtMsgList{ position:absolute;z-index: 1000;top:58px;left:35px; background: #fff; border: 1px solid #e3e6ec;width:207px; display: none;}
    #txtMsgList li{padding: 5px 10px; cursor:pointer}
    #txtMsgList li:hover{ background: #f3f3f3}
</style>
<div class="main-form" style="height:470px;width: 942px">
    <div class="list_table">
        <table class="table_form">
            <tbody>
                <tr>
                    <td style="padding-bottom:10px">
                        <span class="main_title">
                            <input id="txtPosAddress" name="pos_address" class="input" value="" type="text" style="float: none;width:280px;"/> 
                            <input type="button" id="btnMapSearch" class="btn" value="搜索" />
                            <span style=" float: right">
                                <form id="posForm" onsubmit="return false;">
                                    <span style="padding:0 20px;">纬度：<input name="latitude" id="txtWinLatitude" class="input" style="width:80px;float: none;" value="<?=$latitude?>" type="text" required/>
                                        经度：<input name="longitude" id="txtWinLongitude" class="input" style="width:80px;float: none;" value="<?=$longitude?>" type="text" required/>
                                    </span>
                                    <input id="btnSubmit" type="submit" class="btn" value="确定" />
                                </form>
                            </span>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0;border:1px solid #c8c8c8">
                        <div id="map_tooles" style="border-bottom:1px solid #c8c8c8">
                            <div id="map_cur_city">
                                <strong id="map_city_name">北京市</strong><span class="map_change_city">[<span id="map_change_btn">更换城市</span>]<span id="map_level">当前缩放等级：12</span></span>
                                <div id="map_city" class="hide" style=" display: none;">
                                    <h3 class="city_class">热门城市<span class="city_close iconfont icon-delete"></span></h3>
                                    <div class="city_container">
                                        <span class="city_name">北京</span>
                                        <span class="city_name">深圳</span>
                                        <span class="city_name">上海</span>
                                        <span class="city_name">厦门</span>
                                        <span class="city_name" data-type="0">香港</span>
                                        <span class="city_name" data-type="0">澳门</span>
                                        <span class="city_name">广州</span>
                                        <span class="city_name">天津</span>
                                        <span class="city_name">重庆</span>
                                        <span class="city_name">杭州</span>
                                        <span class="city_name">成都</span>
                                        <span class="city_name">武汉</span>
                                        <span class="city_name">青岛</span>
                                    </div>
                                    <h3 class="city_class">全国城市</h3>
                                    <div class="city_container">
                                        <div class="city_container_left">直辖市</div>
                                        <div class="city_container_right">
                                            <span class="city_name">北京</span>
                                            <span class="city_name">上海</span>
                                            <span class="city_name">天津</span>
                                            <span class="city_name">重庆</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">内蒙古</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">呼和浩特</span>
                                            <span class="city_name">包头</span>
                                            <span class="city_name">乌海</span>
                                            <span class="city_name">赤峰</span>
                                            <span class="city_name">通辽</span>
                                            <span class="city_name">鄂尔多斯</span>
                                            <span class="city_name">呼伦贝尔</span>
                                            <span class="city_name">巴彦淖尔</span>
                                            <span class="city_name" data-type="0">乌兰察布</span>
                                            <span class="city_name" data-type="0">兴安盟</span>
                                            <span class="city_name" data-type="0">锡林郭勒盟</span>
                                            <span class="city_name" data-type="0">阿拉善盟</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">山西</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">太原</span>
                                            <span class="city_name">大同</span>
                                            <span class="city_name">阳泉</span>
                                            <span class="city_name">长治</span>
                                            <span class="city_name">晋城</span>
                                            <span class="city_name">朔州</span>
                                            <span class="city_name">晋中</span>
                                            <span class="city_name">运城</span>
                                            <span class="city_name">忻州</span>
                                            <span class="city_name">临汾</span>
                                            <span class="city_name">吕梁</span>

                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">陕西</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">西安</span>
                                            <span class="city_name">铜川</span>
                                            <span class="city_name">宝鸡</span>
                                            <span class="city_name">咸阳</span>
                                            <span class="city_name">渭南</span>
                                            <span class="city_name">延安</span>
                                            <span class="city_name">汉中</span>
                                            <span class="city_name">榆林</span>
                                            <span class="city_name">安康</span>
                                            <span class="city_name">商洛</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">河北</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">石家庄</span>
                                            <span class="city_name">唐山</span>
                                            <span class="city_name">秦皇岛</span>
                                            <span class="city_name">邯郸</span>
                                            <span class="city_name">邢台</span>
                                            <span class="city_name">保定</span>
                                            <span class="city_name">张家口</span>
                                            <span class="city_name">承德</span>
                                            <span class="city_name">沧州</span>
                                            <span class="city_name">廊坊</span>
                                            <span class="city_name">衡水</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">辽宁</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">沈阳</span>
                                            <span class="city_name">大连</span>
                                            <span class="city_name">鞍山</span>
                                            <span class="city_name">抚顺</span>
                                            <span class="city_name">本溪</span>
                                            <span class="city_name">丹东</span>
                                            <span class="city_name">锦州</span>
                                            <span class="city_name">营口</span>
                                            <span class="city_name">阜新</span>
                                            <span class="city_name">辽阳</span>
                                            <span class="city_name">盘锦</span>
                                            <span class="city_name">铁岭</span>
                                            <span class="city_name">朝阳</span>
                                            <span class="city_name">葫芦岛</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">吉林</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">长春</span>
                                            <span class="city_name">吉林</span>
                                            <span class="city_name">四平</span>
                                            <span class="city_name">辽源</span>
                                            <span class="city_name">通化</span>
                                            <span class="city_name">白山</span>
                                            <span class="city_name">松原</span>
                                            <span class="city_name">白城</span>
                                            <span class="city_name">延边</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">黑龙江</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">哈尔滨</span>
                                            <span class="city_name">齐齐哈尔</span>
                                            <span class="city_name">鸡西</span>
                                            <span class="city_name">鹤岗</span>
                                            <span class="city_name">双鸭山</span>
                                            <span class="city_name">大庆</span>
                                            <span class="city_name">伊春</span>
                                            <span class="city_name">牡丹江</span>
                                            <span class="city_name">佳木斯</span>
                                            <span class="city_name">七台河</span>
                                            <span class="city_name">黑河</span>
                                            <span class="city_name">绥化</span>
                                            <span class="city_name">大兴安岭</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">江苏</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">南京</span>
                                            <span class="city_name">无锡</span>
                                            <span class="city_name">徐州</span>
                                            <span class="city_name">常州</span>
                                            <span class="city_name">苏州</span>
                                            <span class="city_name">南通</span>
                                            <span class="city_name">连云港</span>
                                            <span class="city_name">淮安</span>
                                            <span class="city_name">盐城</span>
                                            <span class="city_name">扬州</span>
                                            <span class="city_name">镇江</span>
                                            <span class="city_name">泰州</span>
                                            <span class="city_name">宿迁</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">安徽</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">合肥</span>
                                            <span class="city_name">蚌埠</span>
                                            <span class="city_name">芜湖</span>
                                            <span class="city_name">淮南</span>
                                            <span class="city_name">马鞍山</span>
                                            <span class="city_name">淮北</span>
                                            <span class="city_name">铜陵</span>
                                            <span class="city_name">安庆</span>
                                            <span class="city_name">黄山</span>
                                            <span class="city_name">阜阳</span>
                                            <span class="city_name">宿州</span>
                                            <span class="city_name">滁州</span>
                                            <span class="city_name">六安</span>
                                            <span class="city_name">宣城</span>
                                            <span class="city_name">池州</span>
                                            <span class="city_name">亳州</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">山东</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">济南</span>
                                            <span class="city_name">青岛</span>
                                            <span class="city_name">淄博</span>
                                            <span class="city_name">枣庄</span>
                                            <span class="city_name">东营</span>
                                            <span class="city_name">潍坊</span>
                                            <span class="city_name">烟台</span>
                                            <span class="city_name">威海</span>
                                            <span class="city_name">济宁</span>
                                            <span class="city_name">泰安</span>
                                            <span class="city_name">日照</span>
                                            <span class="city_name">莱芜</span>
                                            <span class="city_name">临沂</span>
                                            <span class="city_name">德州</span>
                                            <span class="city_name">聊城</span>
                                            <span class="city_name">滨州</span>
                                            <span class="city_name">菏泽</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">浙江</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">杭州</span>
                                            <span class="city_name">宁波</span>
                                            <span class="city_name">温州</span>
                                            <span class="city_name">嘉兴</span>
                                            <span class="city_name">绍兴</span>
                                            <span class="city_name">金华</span>
                                            <span class="city_name">衢州</span>
                                            <span class="city_name">舟山</span>
                                            <span class="city_name">台州</span>
                                            <span class="city_name">丽水</span>
                                            <span class="city_name">湖州</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">江西</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">南昌</span>
                                            <span class="city_name">景德镇</span>
                                            <span class="city_name">萍乡</span>
                                            <span class="city_name">九江</span>
                                            <span class="city_name">新余</span>
                                            <span class="city_name">鹰潭</span>
                                            <span class="city_name">赣州</span>
                                            <span class="city_name">吉安</span>
                                            <span class="city_name">宜春</span>
                                            <span class="city_name">抚州</span>
                                            <span class="city_name">上饶</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">福建</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">福州</span>
                                            <span class="city_name">厦门</span>
                                            <span class="city_name">莆田</span>
                                            <span class="city_name">三明</span>
                                            <span class="city_name">泉州</span>
                                            <span class="city_name">漳州</span>
                                            <span class="city_name">南平</span>
                                            <span class="city_name">龙岩</span>
                                            <span class="city_name">宁德</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">湖南</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">长沙</span>
                                            <span class="city_name">株洲</span>
                                            <span class="city_name">湘潭</span>
                                            <span class="city_name">衡阳</span>
                                            <span class="city_name">邵阳</span>
                                            <span class="city_name">岳阳</span>
                                            <span class="city_name">常德</span>
                                            <span class="city_name">张家界</span>
                                            <span class="city_name">益阳</span>
                                            <span class="city_name">郴州</span>
                                            <span class="city_name">永州</span>
                                            <span class="city_name">怀化</span>
                                            <span class="city_name">娄底</span>
                                            <span class="city_name">湘西</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">湖北</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">武汉</span>
                                            <span class="city_name">黄石</span>
                                            <span class="city_name">襄樊</span>
                                            <span class="city_name">十堰</span>
                                            <span class="city_name">宜昌</span>
                                            <span class="city_name">荆门</span>
                                            <span class="city_name">鄂州</span>
                                            <span class="city_name">孝感</span>
                                            <span class="city_name">荆州</span>
                                            <span class="city_name">黄冈</span>
                                            <span class="city_name">咸宁</span>
                                            <span class="city_name">随州</span>
                                            <span class="city_name">恩施</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">河南</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">郑州</span>
                                            <span class="city_name">开封</span>
                                            <span class="city_name">洛阳</span>
                                            <span class="city_name">平顶山</span>
                                            <span class="city_name">焦作</span>
                                            <span class="city_name">鹤壁</span>
                                            <span class="city_name">新乡</span>
                                            <span class="city_name">安阳</span>
                                            <span class="city_name">濮阳</span>
                                            <span class="city_name">许昌</span>
                                            <span class="city_name">漯河</span>
                                            <span class="city_name">三门峡</span>
                                            <span class="city_name">南阳</span>
                                            <span class="city_name">商丘</span>
                                            <span class="city_name">信阳</span>
                                            <span class="city_name">周口</span>
                                            <span class="city_name">驻马店</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">海南</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">海口</span>
                                            <span class="city_name">三亚</span>
                                            <span class="city_name">三沙</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">广东</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">广州</span>
                                            <span class="city_name">深圳</span>
                                            <span class="city_name">珠海</span>
                                            <span class="city_name">汕头</span>
                                            <span class="city_name">韶关</span>
                                            <span class="city_name">佛山</span>
                                            <span class="city_name">江门</span>
                                            <span class="city_name">湛江</span>
                                            <span class="city_name">茂名</span>
                                            <span class="city_name">东沙群岛</span>
                                            <span class="city_name">肇庆</span>
                                            <span class="city_name">惠州</span>
                                            <span class="city_name">梅州</span>
                                            <span class="city_name">汕尾</span>
                                            <span class="city_name">河源</span>
                                            <span class="city_name">阳江</span>
                                            <span class="city_name">清远</span>
                                            <span class="city_name">东莞</span>
                                            <span class="city_name">中山</span>
                                            <span class="city_name">潮州</span>
                                            <span class="city_name">揭阳</span>
                                            <span class="city_name">云浮</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">广西</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">南宁</span>
                                            <span class="city_name">柳州</span>
                                            <span class="city_name">桂林</span>
                                            <span class="city_name">梧州</span>
                                            <span class="city_name">北海</span>
                                            <span class="city_name">防城港</span>
                                            <span class="city_name">钦州</span>
                                            <span class="city_name">贵港</span>
                                            <span class="city_name">玉林</span>
                                            <span class="city_name">百色</span>
                                            <span class="city_name">贺州</span>
                                            <span class="city_name">河池</span>
                                            <span class="city_name">来宾</span>
                                            <span class="city_name">崇左</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">贵州</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">贵阳</span>
                                            <span class="city_name">遵义</span>
                                            <span class="city_name">安顺</span>
                                            <span class="city_name">铜仁</span>
                                            <span class="city_name">毕节</span>
                                            <span class="city_name">六盘水</span>
                                            <span class="city_name" data-type="0">黔西南</span>
                                            <span class="city_name" data-type="0">黔东南</span>
                                            <span class="city_name" data-type="0">黔南</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">四川</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">成都</span>
                                            <span class="city_name">自贡</span>
                                            <span class="city_name">攀枝花</span>
                                            <span class="city_name">泸州</span>
                                            <span class="city_name">德阳</span>
                                            <span class="city_name">绵阳</span>
                                            <span class="city_name">广元</span>
                                            <span class="city_name">遂宁</span>
                                            <span class="city_name">内江</span>
                                            <span class="city_name">乐山</span>
                                            <span class="city_name">南充</span>
                                            <span class="city_name">宜宾</span>
                                            <span class="city_name">广安</span>
                                            <span class="city_name">达州</span>
                                            <span class="city_name">眉山</span>
                                            <span class="city_name">雅安</span>
                                            <span class="city_name">巴中</span>
                                            <span class="city_name">资阳</span>
                                            <span class="city_name">阿坝</span>
                                            <span class="city_name">甘孜</span>
                                            <span class="city_name">凉山</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">云南</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">昆明</span>
                                            <span class="city_name">保山</span>
                                            <span class="city_name">昭通</span>
                                            <span class="city_name">丽江</span>
                                            <span class="city_name">普洱</span>
                                            <span class="city_name">临沧</span>
                                            <span class="city_name">曲靖</span>
                                            <span class="city_name">玉溪</span>
                                            <span class="city_name">文山</span>
                                            <span class="city_name">西双版纳</span>
                                            <span class="city_name">楚雄</span>
                                            <span class="city_name">红河</span>
                                            <span class="city_name">德宏</span>
                                            <span class="city_name">大理</span>
                                            <span class="city_name">怒江</span>
                                            <span class="city_name">迪庆</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">甘肃</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">兰州</span>
                                            <span class="city_name">嘉峪关</span>
                                            <span class="city_name">金昌</span>
                                            <span class="city_name">白银</span>
                                            <span class="city_name">天水</span>
                                            <span class="city_name">酒泉</span>
                                            <span class="city_name">张掖</span>
                                            <span class="city_name">武威</span>
                                            <span class="city_name">定西</span>
                                            <span class="city_name">陇南</span>
                                            <span class="city_name">平凉</span>
                                            <span class="city_name">庆阳</span>
                                            <span class="city_name">临夏</span>
                                            <span class="city_name">甘南</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">宁夏</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">银川</span>
                                            <span class="city_name">石嘴山</span>
                                            <span class="city_name">吴忠</span>
                                            <span class="city_name">固原</span>
                                            <span class="city_name">中卫</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">青海</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">西宁</span>
                                            <span class="city_name">玉树</span>
                                            <span class="city_name">果洛</span>
                                            <span class="city_name">海东</span>
                                            <span class="city_name">海西</span>
                                            <span class="city_name">黄南</span>
                                            <span class="city_name">海北</span>
                                            <span class="city_name">海南</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">西藏</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">拉萨</span>
                                            <span class="city_name">那曲</span>
                                            <span class="city_name">昌都</span>
                                            <span class="city_name">山南</span>
                                            <span class="city_name">日喀则</span>
                                            <span class="city_name" data-type="0">阿里</span>
                                            <span class="city_name">林芝</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="city_container">
                                        <div class="city_container_left"><span class="style_color">新疆</span></div>
                                        <div class="city_container_right">
                                            <span class="city_name">乌鲁木齐</span>
                                            <span class="city_name">克拉玛依</span>
                                            <span class="city_name">吐鲁番</span>
                                            <span class="city_name" data-type="0">哈密</span>
                                            <span class="city_name" data-type="0">博尔塔拉</span>
                                            <span class="city_name" data-type="0">巴音郭楞</span>
                                            <span class="city_name" data-type="0">克孜勒苏</span>
                                            <span class="city_name" data-type="0">和田</span>
                                            <span class="city_name" data-type="0">阿克苏</span>
                                            <span class="city_name" data-type="0">喀什</span>
                                            <span class="city_name" data-type="0">塔城</span>
                                            <span class="city_name" data-type="0">伊犁</span>
                                            <span class="city_name" data-type="0">昌吉</span>
                                            <span class="city_name" data-type="0">阿勒泰</span>
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                </div>
                                <span id="map_cur_add"></span>
                            </div>
                        </div>
                        <div id="map_side_left">
                            <div class="left_msg">
                                <h3>功能简介：</h3>
                                <p>1、支持地址 精确/模糊 查询；</p>
                                <p>2、支持POI点坐标显示；</p>
                                <p>3、坐标鼠标跟随显示；</p>
                                <h3>使用说明：</h3>
                                <p>在搜索框搜索关键词后，地图上会显示相应poi点，同时左侧显示对应该点的信息，点击某点或某信息，右上角会显示相应该点的坐标和地址。</p>
                            </div>
                        </div>
                        <div id="map_side_right">
                            <div id="map_container"></div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div id="txtMsgList">
    <ul>
    </ul>
</div>
<script type="text/javascript">
    var mapKey = 'MGNBZ-IOPR6-FUYSD-MMMUF-Z5A42-4NBKZ';
    $(function () {
        initForm();
        initMapEvent();
    });
    var url, query_city, markerArray = [];
    var mapCity, mapChangeBtn, mapCityName, mapContainer, txtPosAddress, mapSideLeft, txtLatitude, txtLongitude, txtMsgList;
    function initMapEvent() {
        mapCity = $('#map_city');
        mapCityName = $('#map_city_name');
        mapContainer = $('#map_container');
        mapChangeBtn = $('#map_change_btn');
        txtPosAddress = $('#txtPosAddress');
        mapSideLeft = $('#map_side_left');
        txtLatitude = $('#txtWinLatitude');
        txtLongitude = $('#txtWinLongitude');
        txtMsgList = $('#txtMsgList');
        var map = new qq.maps.Map(document.getElementById("map_container"), {
            zoom: 12
        });
        var label = new qq.maps.Label({
            map: map,
            offset: new qq.maps.Size(15, -12),
            draggable: false,
            clickable: false
        });
        if ($.isEmpty(txtLatitude.val()) || $.isEmpty(txtLongitude.val())) {
            var cityService = new qq.maps.CityService({
                complete: function (result) {
                    mapCityName.html(result.detail.name);
                    map.setCenter(result.detail.latLng);
                }
            });
            cityService.searchLocalCity();
        } else {
            var temUrl = encodeURI("http://apis.map.qq.com/ws/geocoder/v1/?location=" + txtLatitude.val() + "," + txtLongitude.val() + "&key=" + mapKey + "&output=jsonp&callback=?");
            $.getJSON(temUrl, function (result) {
                if (result.result != undefined) {
                    var center = new qq.maps.LatLng(txtLatitude.val(), txtLongitude.val());
                    map.panTo(center);
                    var marker = new qq.maps.Marker({
                        //设置Marker的位置坐标
                        position: center,
                        //设置显示Marker的地图
                        map: map
                    });
                    $('#map_cur_add').html(result.result.address);
                    if (result.result.address_component.city != undefined)
                        mapCityName.html(result.result.address_component.city);
                } else {
                    $('#map_cur_add').html('');
                }
            })
        }
        map.setOptions({
            draggableCursor: "crosshair"
        });

        mapContainer.mouseenter(function () {
            label.setMap(map);
        });
        mapContainer.mouseleave(function () {
            label.setMap(null);
        });


        qq.maps.event.addListener(map, "mousemove", function (e) {
            var latlng = e.latLng;
            label.setPosition(latlng);
            label.setContent(latlng.getLat().toFixed(6) + "," + latlng.getLng().toFixed(6));
        });


        var url3;
        qq.maps.event.addListener(map, "click", function (e) {
            txtLatitude.val(e.latLng.getLat().toFixed(6));
            txtLongitude.val(e.latLng.getLng().toFixed(6));
            url3 = encodeURI("http://apis.map.qq.com/ws/geocoder/v1/?location=" + e.latLng.getLat() + "," + e.latLng.getLng() + "&key=" + mapKey + "&output=jsonp&callback=?");
            $.getJSON(url3, function (result) {
                if (result.result != undefined) {
                    $('#map_cur_add').html(result.result.address);
                } else {
                    $('#map_cur_add').html('');
                }

            })
        });

        qq.maps.event.addListener(map, "zoom_changed", function () {
            $("#map_level").html("当前缩放等级：" + map.getZoom());
        });

        var btnMapSearch = document.getElementById("btnMapSearch");
        var listener_arr = [];
        var isNoValue = false;
        qq.maps.event.addDomListener(btnMapSearch, 'click', function () {
            var value = txtPosAddress.val();
            var latlngBounds = new qq.maps.LatLngBounds();
            for (var i = 0, l = listener_arr.length; i < l; i++) {
                qq.maps.event.removeListener(listener_arr[i]);
            }
            listener_arr.length = 0;
            query_city = mapCityName.html();
            url = encodeURI("http://apis.map.qq.com/ws/place/v1/search?keyword=" + value + "&boundary=region(" + query_city + ",0)&page_size=9&page_index=1&key=" + mapKey + "&output=jsonp&&callback=?");
            $.getJSON(url, function (result) {
                if (result.count) {
                    isNoValue = false;
                    mapSideLeft.html('');
                    each(markerArray, function (n, ele) {
                        ele.setMap(null);
                    });
                    markerArray.length = 0;
                    each(result.data, function (n, ele) {
                        var latlng = new qq.maps.LatLng(ele.location.lat, ele.location.lng);
                        latlngBounds.extend(latlng);
                        var left = n * 27;
                        var marker = new qq.maps.Marker({
                            map: map,
                            position: latlng,
                            zIndex: 10
                        });
                        marker.index = n;
                        marker.isClicked = false;
                        setAnchor(marker, true);
                        markerArray.push(marker);
                        var listener1 = qq.maps.event.addDomListener(marker, "mouseover", function () {
                            var n = this.index;
                            setCurrent(markerArray, n, false);
                            setCurrent(markerArray, n, true);
                            label.setContent(this.position.getLat().toFixed(6) + "," + this.position.getLng().toFixed(6));
                            label.setPosition(this.position);
                            label.setOptions({
                                offset: new qq.maps.Size(15, -20)
                            })

                        });
                        listener_arr.push(listener1);
                        var listener2 = qq.maps.event.addDomListener(marker, "mouseout", function () {
                            var n = this.index;
                            setCurrent(markerArray, n, false);
                            setCurrent(markerArray, n, true);
                            label.setOptions({
                                offset: new qq.maps.Size(15, -12)
                            })
                        });
                        listener_arr.push(listener2);
                        var listener3 = qq.maps.event.addDomListener(marker, "click", function () {
                            var n = this.index;
                            setFlagClicked(markerArray, n);
                            setCurrent(markerArray, n, false);
                            setCurrent(markerArray, n, true);
                            $('#map_cur_add').html(mapSideLeft.children(n).find('.map_info_address').html().substring(3));
                            // document.getElementById("addr_cur").value = bside.childNodes[n].childNodes[1].childNodes[1].innerHTML.substring(3);
                        });
                        listener_arr.push(listener3);
                        map.fitBounds(latlngBounds);
                        var div = $('<div class="info_list"></div>');
                        var order = $('<div></div>');
                        var leftn = -54 - 17 * n;
                        order.attr('style', "width:17px;height:17px;margin:3px 3px 0px 0px;float:left;background:url(/images/marker_n.png) " + leftn + "px 0px");
                        div.append(order);
                        var pannel = $('<div></div>');
                        pannel.attr('style', "margin-left:20px;");
                        div.append(pannel);
                        var name = $('<p></p>');
                        name.attr('style', "font-weight:bold");
                        name.html(ele.title);
                        pannel.append(name);
                        var address = $('<p></p>');
                        address.addClass('map_info_address');
                        address.html("地址：" + ele.address);
                        pannel.append(address);
                        if (!$.isNullEmpty(ele.tel)) {
                            var phone = $('<p></p>');
                            phone.html("电话：" + ele.tel);
                            pannel.append(phone);
                        }
                        var m_position = $('<p></p>');
                        m_position.html("坐标：" + ele.location.lat.toFixed(6) + "，" + ele.location.lng.toFixed(6));
                        pannel.append(m_position);
                        mapSideLeft.append(div);
                        // div.style.height = pannel.offsetHeight + "px";
                        div.attr('isClicked', 'false');
                        div.attr('index', n);
                        marker.div = div;
                        // div.attr('marker',marker);
                    });
                    mapSideLeft.on("mouseover", ".info_list", function (e) {
                        var n = $(this).attr('index');
                        setCurrent(markerArray, n, false);
                        setCurrent(markerArray, n, true);
                    });
                    mapSideLeft.on("mouseout", ".info_list", function () {
                        each(markerArray, function (n, ele) {
                            if (!ele.isClicked) {
                                setAnchor(ele, true);
                                $(ele.div).css('background', '#fff');
                                //ele.div.style.background = "#fff";
                            }
                        })
                    });
                    mapSideLeft.on("click", ".info_list", function (e) {
                        var n = $(this).attr('index');
                        setFlagClicked(markerArray, n);
                        setCurrent(markerArray, n, false);
                        setCurrent(markerArray, n, true);
                        map.setCenter(markerArray[n].position);
                        $('#map_cur_add').html($(this).find('.map_info_address:first').html().substring(3));
                        // document.getElementById("addr_cur").value = this.childNodes[1].childNodes[1].innerHTML.substring(3);
                    });
                } else {
                    mapSideLeft.html();
                    each(markerArray, function (n, ele) {
                        ele.setMap(null);
                    });
                    markerArray.length = 0;
                    mapSideLeft.html('对不起，没有搜索到你要找的结果!');
                    //  var novalue = document.createElement('div');
                    //  novalue.id = "no_value";
                    // novalue.innerHTML = "对不起，没有搜索到你要找的结果!";
                    //  bside.appendChild(novalue);
                    isNoValue = true;
                }
            });
        });


        mapChangeBtn.on('click', function () {
            mapCity.show();
            return false;
        });
        mapCity.find('.city_close').on('click', function () {
            mapCity.hide();
            return false;
        });
        mapCity.find('.city_name').on('click', function () {
            var cityName = $(this).text();
            if ($(this).data('type') != 0)
                cityName += '市';
            mapCityName.html(cityName);
            url = encodeURI("http://apis.map.qq.com/ws/geocoder/v1/?region=" + mapCityName.text() + "&address=" + mapCityName.text() + "&key=" + mapKey + "&output=jsonp&&callback=?");
            $.getJSON(url, function (result) {
                if (!$.isNullEmpty(result.result)) {
                    map.setCenter(new qq.maps.LatLng(result.result.location.lat, result.result.location.lng));
                    map.setZoom(10);
                }
            });
            mapCity.hide();
            return false;
        });

//        txtPosAddress.on('keyup', function () {
//            var ul = txtMsgList.find('ul');
//            var val = $(this).val();
//            if ($.isEmpty(val)) {
//                txtMsgList.hide();
//                ul.html('');
//                return false;
//            }
//            var msgurl = encodeURI("http://apis.map.qq.com/ws/place/v1/suggestion/?keyword=" + val + "&region=" + mapCityName.text() + "&key=" + mapKey + "&output=jsonp&&callback=?");
//            $.getJSON(msgurl, function (result) {
//                if (result.status == 0) {
//                    ul.html('');
//                    $(result.data).each(function () {
//                        ul.append('<li>' + this.title + '</li>');
//                    });
//                    ul.on('click', 'li', function () {
//                        txtMsgList.hide();
//                        txtPosAddress.val($(this).text());
//                        $(btnMapSearch).click();
//                    })
//                }
//            });
//            if (txtMsgList.is(':hidden'))
//                txtMsgList.show();
//            return false;
//        });
    }

    function setFlagClicked(arr, index) {
        each(markerArray, function (n, ele) {
            if (n == index) {
                ele.isClicked = true;
                ele.div.isClicked = true;
                //    var str = '<div style="width:250px;">' + ele.div.children[1].innerHTML.toString() + '</div>';
                var latLng = ele.getPosition();
                txtLatitude.val(latLng.getLat().toFixed(6));
                txtLongitude.val(latLng.getLng().toFixed(6));
            } else {
                ele.isClicked = false;
                ele.div.isClicked = false;
            }
        });
    }

    function setAnchor(marker, flag) {
        var left = marker.index * 27;
        if (flag == true) {
            var anchor = new qq.maps.Point(10, 30),
                    origin = new qq.maps.Point(left, 0),
                    size = new qq.maps.Size(27, 33),
                    icon = new qq.maps.MarkerImage("/images/marker10.png", size, origin, anchor);
            marker.setIcon(icon);
        } else {
            var anchor = new qq.maps.Point(10, 30),
                    origin = new qq.maps.Point(left, 35),
                    size = new qq.maps.Size(27, 33),
                    icon = new qq.maps.MarkerImage("/images/marker10.png", size, origin, anchor);
            marker.setIcon(icon);
        }
    }
    function setCurrent(arr, index, isMarker) {
        if (isMarker) {
            each(markerArray, function (n, ele) {
                if (n == index) {
                    setAnchor(ele, false);
                    ele.setZIndex(10);
                } else {
                    if (!ele.isClicked) {
                        setAnchor(ele, true);
                        ele.setZIndex(9);
                    }
                }
            });
        } else {
            each(markerArray, function (n, ele) {
                if (n == index) {
                    $(ele.div).css('background', '#f3f3f3');
                } else {
                    if (!ele.div.isClicked) {
                        $(ele.div).css('background', '#fff');
                    }
                }
            });
        }
    }
    function each(obj, fn) {
        for (var n = 0, l = obj.length; n < l; n++) {
            fn.call(obj[n], n, obj[n]);
        }
    }
    function initForm() {
        $("#posForm").validate({
            onfocusout: false,
            onkeyup: false,
            messages: {
                latitude: {required: '维度不能为空，请输入。'},
                longitude: {required: '经度不能为空，请输入。'}
            },
            showErrors: function (errorMap, errorList) {
                if (errorList.length > 0)
                    $.ShowMsg(false, errorList[0].message);
            },
            submitHandler: function (form) {
                $('#txtLongitude').val($('#txtWinLongitude').val());
                $('#longitudeBox').html($('#txtWinLongitude').val());
                $('#txtLatitude').val($('#txtWinLatitude').val());
                $('#latitudeBox').html($('#txtWinLatitude').val());
                $.CloseWin();
                return false;
            }
        });
    }
</script>