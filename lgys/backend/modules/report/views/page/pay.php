<?php

use yii\helpers\Html;
?>
<div class="main-containt">
    <div id="listBox">
        <div class="main-title">
            支付记录
        </div>
        <div class="main-page">
            <div class="search-box">
                <input id="txtPaySearchKey1" class="input" type="text" placeholder="联盟名称" />
                <input id="txtPaySearchKey2" class="input" type="text" placeholder="商户名称" />
                <input id="txtPaySearchKey3" class="input" type="text" placeholder="用户会员号/昵称" />
                <span class="date_panel">
                    <input id="txtPaySearchBeginedAt" name="begined_at" class="input" placeholder="开始日期" value="" type="text" style="width:100px;">
                    <i class="iconfont icon-date" id="iconPaySearchBeginedAt"></i>
                </span> - 
                <span class="date_panel">
                    <input id="txtPaySearchEndedAt" name="ended_at" class="input" placeholder="结束日期" type="text" style="width:100px;">
                    <i class="iconfont icon-date" id="iconPaySearchEndedAt"></i>
                </span>
                <input class="btn" id="btnPaySearch" type="button" value="搜索" />
                <input class="btn btn-gray" id="btnPayReset" type="button" value="重置" />
            </div>
            <div class="main-list" id="dataPayListBox">
                <?= Yii::$app->runAction('/report/page/pay-list') ?>
            </div>
        </div>
    </div>
    <div id="formBox"></div>
</div>
<?= backend\widgets\Script::registerJsFile(); ?>