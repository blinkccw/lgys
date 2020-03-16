<?php

use yii\helpers\Html;
?>
<div class="main-containt">
    <div id="listBox">
        <div class="main-title">
            商户审核
        </div>
        <div class="main-page">
            <div class="search-box">
                <select id="ddlSearchStatus">
                    <option value="-1">全部审核状态</option>
                    <option value="0">待审核</option>
                    <option value="2">审核失败</option>
                </select>
                <input id="txtSearchKey" class="input" type="text" placeholder="商户名称/联系人/手机号码" />
                <input class="btn" id="btnSearch" type="button" value="搜索" />
                <input class="btn btn-gray" id="btnReset" type="button" value="重置" />
            </div>
            <div class="main-list" id="dataListBox">
                <?= Yii::$app->runAction('/business/page/audit-list') ?>
            </div>
        </div>
    </div>
    <div id="formBox"></div>
</div>
<?= backend\widgets\Script::registerJsFile(); ?>