<?php

use yii\helpers\Html;
?>
<table class="table">
    <thead>
        <tr>
            <th>交易号</th>
            <th>会员号</th>
            <th>用户</th>
            <th>商户</th>
            <th>联盟</th>
            <th class="min">支付总金额</th>
            <th class="min">实际支付金额</th>
            <th class="min">代币抵扣数</th>
            <th class="min">代币获取数</th>
            <th class="min">抽成</th>
            <th class="min">支付日期</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $item) { ?>
            <tr>
                <td class="min"><?= Html::encode($item['no']) ?></td>
                <td><?= $item['vip'] ? Html::encode($item['vip']['vip_no']) : '无' ?></td>
                <td><?= $item['vip'] ? Html::encode($item['vip']['nick_name']) : '无' ?></td>
                <td><?= $item['business'] ? Html::encode($item['business']['name']) : '无' ?></td>
                <td><?= $item['alliance'] ? Html::encode($item['alliance']['name']) : '无' ?></td>
                <td class="min"><?= Html::encode($item['money']) ?></td>
                <td class="min"><?= Html::encode($item['pay']) ?></td>
                <td class="min"><a href="javascript:pointsLog('<?= $item['no'] ?>',<?= $item['id'] ?>);" title="查看记录"><span class="iconfont icon-search" style="color:#ccc; margin-right: 5px;" title="查看"></span><?= Html::encode($item['used_point']) ?></a></td>
                <td class="min"><?= Html::encode($item['point']) ?></td>
                <td class="min"><a href="javascript:ercentageLog('<?= $item['no'] ?>',<?= $item['id'] ?>);" title="查看记录"><span class="iconfont icon-search" style="color:#ccc; margin-right: 5px;" title="查看"></span><?= Html::encode($item['ercentage']) ?></a></td>
                <td class="min"><?= $item['created_at'] ?></td>
            </tr>
        <?php } ?>
        <?php if (count($list) <= 0) { ?> 
            <tr><td colspan="11" class="no-data">没有数据</td></tr>
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
