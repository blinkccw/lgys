<?php

use yii\helpers\Html;
?>
<table class="table">
    <thead>
        <tr>
            <th>用户</th>
            <th class="min">提供代币数</th>
            <th class="min">是否退还</th>
            <th class="min">退还日期</th>
            <th class="min">参与日期</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $item) { ?>
            <tr>
                <td>
                    <?= $item['vip'] ? Html::encode($item['vip']['name']) : '无' ?>
                </td>
                <td class="min">
                    <?= $item['points'] ?>
                </td>
                <td class="min">
                    <?= $item['is_return']==1?'是':'否'?>
                </td>
                 <td class="min"><?= $item['return_at'] ?></td>
                <td class="min"><?= $item['created_at'] ?></td>
            </tr>
        <?php } ?>
        <?php if (count($list) <= 0) { ?> 
            <tr><td colspan="5" class="no-data">没有数据</td></tr>
        <?php } ?>
    </tbody>
</table>
<div class="list-page" style="<?= count($list) == 0 ? 'display:none' : '' ?>" id="manPage"></div>
<script>
    $(function () {
        $('#manPage').createPage({
            pageSize:<?= $page['page_size'] ?>,
            pageCount:<?= $page['page_count'] ?>,
            current:<?= $page['page_index'] ?>,
            count:<?= $page['counts'] ?>,
            backFn: function (p) {
                manPageIndex=p;
                getManList();
            }
        });
    });
</script>
