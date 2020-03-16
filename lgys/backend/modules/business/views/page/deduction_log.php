<?php

use yii\helpers\Html;
?>
<div class="main-title">
    商户【<?= Html::encode($model['name']) ?>】承销记录
</div>
<div class="main-page">
    <div class="search-box">
        <select id="ddlLogSearchAlliance">
            <option value="0">全部联盟</option>
            <?php foreach ($all_alliance as $alliance) { ?>
            <option value="<?=$alliance['alliance']['id']?>"><?=Html::encode($alliance['alliance']['name'])?></option>
            <?php } ?>
        </select>
        <input id="txtLogKey" class="input" type="text" placeholder="用户昵称/会员号" />
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
        <input type="button" onclick="hideFormPage()" class="btn btn-gray" value="返回" />
    </div>
    <div class="main-list" id="dataLogListBox">
        <?= Yii::$app->runAction('/business/page/deduction-log-list',['id'=>$id]) ?>
    </div>
</div>
<script>
    var businessID=<?=$id?>;
</script>
<?= backend\widgets\Script::registerJsFile(); ?>