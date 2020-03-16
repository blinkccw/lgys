<?php

use yii\helpers\Html;
?>
<table class="table">
    <thead>
        <tr>
            <th>商户名称</th>
            <th class="min">加入日期</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $item) { ?>
            <tr>
                <td><?= Html::encode($item['business']['name']) ?></td>
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
                getBusinessList();
            }
        });
    });
</script>
