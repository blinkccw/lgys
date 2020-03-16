<div class="main-containt" style="min-height:0">
    <div class="main-title">
        概况
    </div>
    <div class="main-page">
        <div class="index-data clearfix">
            <div class="index-data-item">
                <div class="name">商户总数</div>
                <div class="num"><?= $business_num ?></div>
            </div>
            <div class="index-data-item">
                <div class="name">用户总数</div>
                <div class="num"><?= $vip_num ?></div>
            </div>
            <div class="index-data-item">
                <div class="name">联盟总数</div>
                <div class="num"><?= $alliance_num ?></div>
            </div>
            <div class="index-data-item">
                <div class="name">代币总发行数量</div>
                <div class="num"><?= $points_num > 0 ? $points_num : 0 ?></div>
            </div>
             <div class="index-data-item">
                <div class="name">平台抽成</div>
                <div class="num"><?= $ercentage_num > 0 ? $ercentage_num : 0 ?></div>
            </div>
        </div>
    </div>
</div>
<div class="main-containt" style="min-height:0; margin-top: 12px;">
    <div class="main-title">
        代币发行查询
    </div>
    <div class="main-page">
        <div class="search-box">
            <span class="date_panel">
                <input id="txtLogSearchBeginedAt" name="begined_at" class="input" placeholder="开始日期" value="" type="text" style="width:100px;">
                <i class="iconfont icon-date" id="iconLogSearchBeginedAt"></i>
            </span> - 
            <span class="date_panel">
                <input id="txtLogSearchEndedAt" name="ended_at" class="input" placeholder="结束日期" type="text" style="width:100px;">
                <i class="iconfont icon-date" id="iconLogSearchEndedAt"></i>
            </span>
            <input class="btn" id="btnLogSearch" type="button" value="搜索" />
            <input class="btn btn-gray" id="btnLogReset" type="button" value="重置" />
        </div>
        <div class="main-list" id="dataLogListBox">
        </div>
    </div>
</div>
<?= backend\widgets\Script::registerJsFile(); ?>