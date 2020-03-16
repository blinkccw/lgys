<?php

use yii\helpers\Html;
?>
<div class="main-containt">
    <div class="main-title">
        商户分类
    </div>
    <div class="main-page">
        <div class="search-box">
            <input class="btn" id="btnAdd" type="button" value="新增" />
        </div>
        <div class="main-list" id="dataListBox">
            <table class="table">
                <thead>
                    <tr>
                        <th>名称</th>
                        <th class="min">排序号</th>
                        <th class="do">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $item) { ?>
                        <tr>
                            <td><?= Html::encode($item['name']) ?></td>
                            <td class="min"><input type="text" data-type="order" data-id="<?= $item['id'] ?>" class="input" style="width:60px;" value="<?= $item['order_num'] ?>" /></td>
                            <td class="do">
                                <a class="do-btn" href="javascript:doForm(<?= $item['id'] ?>);" title="编辑">编辑</a>
                                <a class="do-btn" href="javascript:deleteItem(<?= $item['id'] ?>);">删除</a>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (count($list) <= 0) { ?> 
                        <tr><td colspan="3" class="no-data">没有数据</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= backend\widgets\Script::registerJsFile(); ?>