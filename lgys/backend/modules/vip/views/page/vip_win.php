<?php

use yii\helpers\Html;
?>
<div class="main-form" style="width:720px;">
    <div class="search-box">
        <input id="txtWinSearchKey" class="input" type="text" placeholder="会员号/姓名/昵称" />
        <input class="btn" id="btnWinSearch" type="button" value="搜索" />
        <input class="btn btn-gray" id="btnWinReset" type="button" value="重置" />
    </div>
    <div class="main-list" id="dataWinListBox">
        <?= Yii::$app->runAction('/vip/page/vip-win-list') ?>
    </div>
</div>
<?= backend\widgets\Script::registerJsFile(); ?>