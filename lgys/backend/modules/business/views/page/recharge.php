<?php

use yii\helpers\Html;
?>
<div class="main-title">
    商户【<?= Html::encode($model['name']) ?>】充值记录
</div>
<div class="main-page">
    <div class="search-box">
        <span class="date_panel">
            <input id="txtRechargeSearchBeginedAt" name="begined_at" class="input" placeholder="开始日期" value="" type="text" style="width:100px;">
            <i class="iconfont icon-date" id="iconRechargeSearchBeginedAt"></i>
        </span> - 
        <span class="date_panel">
            <input id="txtRechargeSearchEndedAt" name="ended_at" class="input" placeholder="结束日期" type="text" style="width:100px;">
            <i class="iconfont icon-date" id="iconRechargeSearchEndedAt"></i>
        </span>
        <input class="btn" id="btnRechargeSearch" type="button" value="搜索" />
        <input class="btn btn-gray" id="btnRechargeReset" type="button" value="重置" />
        <input type="button" onclick="hideFormPage()" class="btn btn-gray" value="返回" />
        <div class="btn-box">
            <input class="btn" id="btnRecharge" type="button" value="充值" />
        </div>
    </div>
    <div class="main-list" id="dataRechargeListBox">
        <?= Yii::$app->runAction('/business/page/recharge-list',['id'=>$id]) ?>
    </div>
</div>
<script>
    var businessID=<?=$id?>;
</script>
<?= backend\widgets\Script::registerJsFile(); ?>