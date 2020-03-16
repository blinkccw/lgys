<?php

use yii\helpers\Html;
?>
<table class="table">
    <thead>
        <tr>
            <th>承销代币数</th>
            <th>商户</th>
            <th>会员号</th>
            <th>昵称</th>
            <th class="min">发行日期</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $item) { ?>
            <tr>
                <td><?= Html::encode($item['points']) ?></td>
                <td><?= $item['business'] ? Html::encode($item['business']['points']) : '无' ?></td>
                <td><?= $item['vip'] ? Html::encode($item['vip']['vip_no']) : '无' ?></td>
                <td><?= $item['vip'] ? Html::encode($item['vip']['nick_name']) : '无' ?></td>
                <td class="min"><?= $item['created_at'] ?></td>
            </tr>
        <?php } ?>
        <?php if (count($list) <= 0) { ?> 
            <tr><td colspan="5" class="no-data">没有数据</td></tr>
        <?php } ?>
    </tbody>
</table>
<div class="list-page" style="<?= count($list) == 0 ? 'display:none' : '' ?>" id="logPage"></div>
<script>
    $(function () {
        $('#logPage').createPage({
            pageSize:<?= $page['page_size'] ?>,
            pageCount:<?= $page['page_count'] ?>,
            current:<?= $page['page_index'] ?>,
            count:<?= $page['counts'] ?>,
            backFn: function (p) {
                searchLogJson['page_index'] = p;
                getLogList();
            }
        });
    });
</script>
