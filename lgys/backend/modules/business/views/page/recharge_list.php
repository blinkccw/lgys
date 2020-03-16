<?php

use yii\helpers\Html;
?>
<table class="table">
    <thead>
        <tr>
            <th>代币数</th>
            <th class="min">充值日期</th>
        </tr>
    </thead>
    <a href="recharge_list.php"></a>
    <tbody>
        <?php foreach ($list as $item) { ?>
            <tr>
                <td><?= Html::encode($item['points']) ?></td>
                <td class="min"><?= $item['created_at'] ?></td>
            </tr>
        <?php } ?>
        <?php if (count($list) <= 0) { ?> 
            <tr><td colspan="2" class="no-data">没有数据</td></tr>
        <?php } ?>
    </tbody>
</table>
<div class="list-page" style="<?= count($list) == 0 ? 'display:none' : '' ?>" id="rechargePage"></div>
<script>
    $(function () {
        $('#rechargePage').createPage({
            pageSize:<?= $page['page_size'] ?>,
            pageCount:<?= $page['page_count'] ?>,
            current:<?= $page['page_index'] ?>,
            count:<?= $page['counts'] ?>,
            backFn: function (p) {
                searchRechargeJson['page_index'] = p;
                getRechargeList();
            }
        });
    });
</script>
