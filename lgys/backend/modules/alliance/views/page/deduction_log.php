<?php

use yii\helpers\Html;
?>
<div class="main-title">
    联盟【<?= Html::encode($model['name']) ?>】承销记录
</div>
<div class="main-page">
    <div class="search-box">
        <select id="ddlLogSearchBusiness">
            <option value="0">全部商户</option>
            <?php foreach ($all_business as $business) { ?>
            <option value="<?=$business['business']['id']?>"><?=Html::encode($business['business']['name'])?></option>
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
        <?= Yii::$app->runAction('/alliance/page/deduction-log-list',['id'=>$id]) ?>
    </div>
</div>
<script>
    var allianceID=<?=$id?>;
</script>
<?= backend\widgets\Script::registerJsFile(); ?>