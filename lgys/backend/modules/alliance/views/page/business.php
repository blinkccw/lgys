<?php

use yii\helpers\Html;
?>
<div class="main-title">
    联盟【<?= Html::encode($model['name']) ?>】商户列表
</div>
<div class="main-page">
    <div class="search-box">
        <input id="txtBusinessKey" class="input" type="text" placeholder="商户名称" />
        <input class="btn" id="btnBusinessSearch" type="button" value="搜索" />
        <input class="btn btn-gray" id="btnBusinessReset" type="button" value="重置" />
        <input type="button" onclick="hideFormPage()" class="btn btn-gray" value="返回" />
    </div>
    <div class="main-list" id="dataBusinessListBox">
        <?= Yii::$app->runAction('/alliance/page/business-list', ['id' => $id]) ?>
    </div>
</div>
<script>
    var allianceID =<?= $id ?>;
</script>
<?= backend\widgets\Script::registerJsFile(); ?>