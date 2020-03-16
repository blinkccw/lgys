<?php

use yii\helpers\Html;
?>
<div class="main-title">
   聚合详情
</div>
<div class="main-page">
    <div class="search-box">
        <input class="btn btn-gray" type="button"  onclick="hideFormPage()"  value="返回" />
    </div>
    <div class="main-list" id="dataManListBox">
        <?= Yii::$app->runAction('/business/page/aggregation-man-list') ?>
    </div>
</div>
<?= backend\widgets\Script::registerJsFile(); ?>