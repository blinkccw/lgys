<?php

use yii\helpers\Html;
?>
<table class="table">
    <thead>
        <tr>
            <th>商户</th>
            <th>联盟</th>
            <th>支付总金额</th>
            <th>实际支付金额</th>
            <th>代币抵扣数</th>
            <th>代币获取数</th>
            <th class="date">支付日期</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $item) { ?>
            <tr>
                <td><?= $item['business'] ? Html::encode($item['business']['name']) : '无' ?></td>
                <td><?= $item['alliance'] ? Html::encode($item['alliance']['name']) : '无' ?></td>
                <td><?= Html::encode($item['money']) ?></td>
                <td><?= Html::encode($item['pay']) ?></td>
                <td><?= Html::encode($item['used_point']) ?></td>
                <td><?= Html::encode($item['point']) ?></td>
                <td class="min"><?= $item['created_at'] ?></td>
            </tr>
        <?php } ?>
        <?php if (count($list) <= 0) { ?> 
            <tr><td colspan="7" class="no-data">没有数据</td></tr>
        <?php } ?>
    </tbody>
</table>
<div class="list-page" style="<?= count($list) == 0 ? 'display:none' : '' ?>" id="payPage"></div>
<script>
    $(function () {
        $('#payPage').createPage({
            pageSize:<?= $page['page_size'] ?>,
            pageCount:<?= $page['page_count'] ?>,
            current:<?= $page['page_index'] ?>,
            count:<?= $page['counts'] ?>,
            backFn: function (p) {
                searchPayJson['page_index'] = p;
                getPayList();
            }
        });
    });
</script>
