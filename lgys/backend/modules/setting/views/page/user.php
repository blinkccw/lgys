<?php

use yii\helpers\Html;
?>
<div class="main-containt">
    <div class="main-title">
        管理员列表
    </div>
    <div class="main-page">
        <div class="search-box">
            <input id="txtSearchKey" class="input" type="text" placeholder="请输入姓名/用户名" />
            <input class="btn" id="btnSearch" type="button" value="搜索" />
            <input class="btn btn-gray" id="btnReset" type="button" value="重置" />
            <div class="btn-box">
                <?php if ($user->is_admin) { ?>
                    <input class="btn" id="btnAdd" type="button" value="新增" />
                <?php } ?>
            </div>
        </div>
        <div class="main-list" id="dataListBox">
            <?= Yii::$app->runAction('/setting/page/user-list') ?>
        </div>
    </div>
</div>
<?= backend\widgets\Script::registerJsFile(); ?>