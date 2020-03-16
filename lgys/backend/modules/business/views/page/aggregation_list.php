<?php

use yii\helpers\Html;
?>
<table class="table">
    <thead>
        <tr>
            <th>商户</th>
            <th>用户</th>
            <th class="min">聚合代币数</th>
            <th class="min">已聚合代币数</th>
            <th class="min">状态</th>
            <th class="min">开始日期</th>
            <th class="min">结束日期</th>
            <th class="min">创建日期</th>
            <th class="do">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $item) { ?>
            <tr>
                <td>
                    <?= $item['business'] ? Html::encode($item['business']['name']) : '无' ?>
                </td>
                <td>
                    <?= $item['vip'] ? Html::encode($item['vip']['name']) : '无' ?>
                </td>
                <td class="min">
                    <?= $item['points'] ?>
                </td>
                <td class="min">
                    <?= $item['complete_points'] ?>
                </td>
                <td class="min">
                    <?php
                    switch ($item['status']) {
                        case 0:
                            echo '进行中';
                            break;
                        case 1:
                            echo '成功';
                            break;
                        case 2:
                            echo '失败';
                            break;
                    }
                    ?>
                </td>
                <td class="min">
                    <?= $item['begin_at'] ?>
                </td>
                <td class="min">
                    <?= $item['end_at'] ?>
                </td>
                <td class="min"><?= $item['created_at'] ?></td>
                <td class="do">
                    <a class="do-btn" href="javascript:showItem(<?= $item['id'] ?>);" title="查看">查看</a>
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
    });
</script>
