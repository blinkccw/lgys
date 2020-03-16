<?php

use yii\helpers\Html;
?>
<div class="main-title">
    用户【<?= Html::encode($model['name']) ?>】消费记录
</div>
<div class="main-page">
    <div class="search-box">
        <input id="txtPaySearchKey" class="input" type="text" placeholder="商户名称/联盟名称" />
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
        <input type="button" onclick="hideFormPage()" class="btn btn-gray" value="返回" />
    </div>
    <div class="main-list" id="dataPayListBox">
        <?= Yii::$app->runAction('/vip/page/pay-list',['id'=>$id]) ?>
    </div>
</div>
<script>
    var vipID=<?=$id?>;
</script>
<?= backend\widgets\Script::registerJsFile(); ?>