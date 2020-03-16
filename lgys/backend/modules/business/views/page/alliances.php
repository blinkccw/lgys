<?php

use yii\helpers\Html;
?>
<div class="main-containt">
    <div class="main-title">
        商户联盟列表
    </div>
    <div class="main-page">
        <div class="search-box">
            <input type="button" onclick="hideFormPage()" class="btn btn-empty" value="返回" />
        </div>
        <div class="main-list" id="dataListBox">
            <table class="table">
                <thead>
                    <tr>
                        <th>名称</th>
                        <th>类型</th>
                        <th class="min">加入日期</th>
                        <th class="do">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $item) { ?>
                        <tr>
                            <td><?= Html::encode($item['alliance']['name']) ?></td>
                            <td><?= $item['is_host'] == 1 ? "创办人" : "成员" ?></td>
                            <td class="min"><?= Html::encode($item['created_at']) ?></td>
                            <td class="do">
                                <?php if ($item['is_host'] == 0) { ?>
                                    <a class="do-btn" href="javascript:deleteAlliance(<?= $item['id'] ?>);">删除</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (count($list) <= 0) { ?> 
                        <tr><td colspan="4" class="no-data">没有数据</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    var businessID =<?= $id ?>;
</script>

<?= backend\widgets\Script::registerJsFile(); ?>