<?php

use yii\helpers\Html;
?>
<table class="table">
    <thead>
        <tr>
            <th>姓名</th>
            <th>用户名</th>
            <th>IP</th>
            <th class="date">登录日期</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $item) { ?>
            <tr>
                <td><?= Html::encode($item['name']) ?></td>
                <td><?= Html::encode($item['username']) ?></td>
                 <td><?= Html::encode($item['ip']) ?></td>
                 <td><?= Html::encode($item['created_at']) ?></td>
            </tr>
        <?php } ?>
        <?php if (count($list) <= 0) { ?> 
            <tr><td colspan="4" class="no-data">没有数据</td></tr>
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
    });
</script>
