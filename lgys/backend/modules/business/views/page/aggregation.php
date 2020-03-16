<?php

use yii\helpers\Html;
?>
<div class="main-containt">
    <div id="listBox">
        <div class="main-title">
            聚合列表
        </div>
        <div class="main-page">
            <div class="search-box">
                <select id="ddlSearchStatus">
                    <option value="-1">全部状态</option>
                    <option value="0">进行中</option>
                     <option value="1">成功</option>
                     <option value="2">失败</option>
                </select>
                <input id="txtSearchKey" class="input" type="text" placeholder="商户名称" />
                <input id="txtSearchVipKey" class="input" type="text" placeholder="会员号/姓名/昵称" />
                <input class="btn" id="btnSearch" type="button" value="搜索" />
                <input class="btn btn-gray" id="btnReset" type="button" value="重置" />
            </div>
            <div class="main-list" id="dataListBox">
                <?= Yii::$app->runAction('/business/page/aggregation-list') ?>
            </div>
        </div>
    </div>
    <div id="formBox"></div>
</div>
<?= backend\widgets\Script::registerJsFile(); ?>