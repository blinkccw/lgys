<?php

use yii\helpers\Html;
?>
<table class="table">
    <thead>
        <tr>
            <th>名称</th>
            <th>创建商户</th>
            <th>代币发行量</th>
            <th>代币承销量</th>
            <th>商户数量</th>
            <th class="min">是否推荐</th>
            <th class="min">创建日期</th>
            <th class="do">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $item) { ?>
            <tr>
                <td><?= Html::encode($item['name']) ?></td>
                <td><?= $item['business'] ? Html::encode($item['business']['name']) : '无' ?></td>
                <td><a href="javascript:exchangeLog(<?= $item['id'] ?>);" title="查看记录"><span class="iconfont icon-search" style="color:#ccc; margin-right: 5px;" title="查看"></span><?= Html::encode($item['exchange_points']) ?></a></td>
                <td><a href="javascript:deductionLog(<?= $item['id'] ?>);" title="查看记录"><span class="iconfont icon-search" style="color:#ccc; margin-right: 5px;" title="查看"></span><?= Html::encode($item['deduction_points']) ?></a></td>
                <td><a href="javascript:businessPage(<?= $item['id'] ?>);" title="查看商户"><span class="iconfont icon-search" style="color:#ccc; margin-right: 5px;" title="查看"></span><?= Html::encode($item['num']) ?></a></td>
                 <td class="min">
                    <select data-type="is_hot" data-id="<?= $item['id'] ?>">
                        <option value="1" <?= $item['is_hot'] == 1 ? 'selected' : '' ?>>是</option>
                        <option value="0" <?= $item['is_hot'] == 0 ? 'selected' : '' ?>>否</option>
                    </select>
                </td>
                <td class="min"><?= $item['created_at'] ?></td>
                <td class="do">
                    <a class="do-btn" href="javascript:doForm(<?= $item['id'] ?>);" title="编辑">编辑</a>
                    <a class="do-btn" href="javascript:deleteItem(<?= $item['id'] ?>);">删除</a>
                </td>
            </tr>
        <?php } ?>
        <?php if (count($list) <= 0) { ?> 
            <tr><td colspan="9" class="no-data">没有数据</td></tr>
        <?php } ?>
    </tbody>
</table>
<div class="list-page" style="<?= count($list) == 0 ? 'display:none' : '' ?>" id="page"></div>
<script>
    $(function () {
        $('#page').createPage({
            pageSize:<?= $page['page_size'] ?>,
            pageCount:<?= $page['page_count'] ?>,
            current:<?= $page['page_index'] ?>,
            count:<?= $page['counts'] ?>,
            backFn: function (p) {
                searchJson['page_index'] = p;
                getList();
            }
        });
         initList();
    });
</script>
