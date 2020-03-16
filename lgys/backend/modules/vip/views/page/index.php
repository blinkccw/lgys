<?php

use yii\helpers\Html;
?>
<div class="main-containt">
    <div id="listBox">
        <div class="main-title">
            用户列表
        </div>
        <div class="main-page">
            <div class="search-box">
                <input id="txtSearchKey" class="input" type="text" placeholder="会员号/姓名/昵称" />
                <input class="btn" id="btnSearch" type="button" value="搜索" />
                <input class="btn btn-gray" id="btnReset" type="button" value="重置" />
                 <input class="btn btn-gray" id="btnExcel" type="button" value="导出" />
            </div>
            <div class="main-list" id="dataListBox">
                <?= Yii::$app->runAction('/vip/page/vip-list') ?>
            </div>
        </div>
    </div>
    <div id="formBox"></div>
</div>
<?= backend\widgets\Script::registerJsFile(); ?>