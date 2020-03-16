<?php

use yii\helpers\Html;
?>
<div class="main-containt">
    <div id="listBox">
        <div class="main-title">
            商户列表
        </div>
        <div class="main-page">
            <div class="search-box">
                <select id="ddlSearchSort">
                    <option value="0">全部分类</option>
                    <?php foreach ($sorts as $sort) { ?>
                        <option value="<?=$sort->id?>"><?=Html::encode($sort->name)?></option>
                    <?php } ?>
                </select>
                <select id="ddlSearchGrade">
                    <option value="-1">全部等级</option>
                    <?php foreach ($grades as $grade) { ?>
                        <option value="<?=$grade->id?>"><?=Html::encode($grade->name)?></option>
                    <?php } ?>
                </select>
                <select id="ddlSearchStatus">
                    <option value="-1">全部状态</option>
                    <option value="1">上架</option>
                    <option value="0">下架</option>
                </select>
                <input id="txtSearchKey" class="input" type="text" placeholder="商户名称/联系人/手机号码" />
                <input id="txtMinPoints" class="input" type="text"  style="width:80px;" placeholder="最小代币" />
                -
                <input id="txtMaxPoints" class="input" type="text" style="width:80px" placeholder="最大代币" />
                <input class="btn" id="btnSearch" type="button" value="搜索" />
                <input class="btn btn-gray" id="btnReset" type="button" value="重置" />
                <input class="btn btn-gray" id="btnExcel" type="button" value="导出" />
                <div class="btn-box" style="display: none;">
                    <input class="btn" id="btnAdd" type="button" value="新增" />
                </div>
            </div>
            <div class="main-list" id="dataListBox">
                <?= Yii::$app->runAction('/business/page/business-list') ?>
            </div>
        </div>
    </div>
    <div id="formBox"></div>
</div>
<?= backend\widgets\Script::registerJsFile(); ?>