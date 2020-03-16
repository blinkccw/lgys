<?php

use yii\helpers\Html;
?>
<table class="table">
    <thead>
        <tr>
            <th style="width:50px">头像</th>
            <th>会员号</th>
            <th>姓名</th>
            <th>昵称</th>
            <th class="date">创建日期</th>
            <th class="do">选择</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $item) { ?>
            <tr>
                <td><img class="vip-face" src="<?= $item['avatar_url'] ? $item['avatar_url'] : '/images/face.png' ?>" ></td>
                <td class="min"><?= Html::encode($item['vip_no']) ?></td>
                <td><?= Html::encode($item['name']) ?></td>
                <td><?= Html::encode($item['nick_name']) ?></td>
                <td class="date"><?= $item['created_at'] ?></td>
                <td class="do">
                    <a class="do-btn" href="javascript:selectVip(<?= $item['id'] ?>,'<?= $item['avatar_url'] ?>','<?= $item['name'] ?>');" title="选择">选择</a>
                </td>
            </tr>
        <?php } ?>
        <?php if (count($list) <= 0) { ?> 
            <tr><td colspan="6" class="no-data">没有数据</td></tr>
        <?php } ?>
    </tbody>
</table>
<div class="list-page" style="<?= count($list) == 0 ? 'display:none' : '' ?>" id="winPage"></div>
<script>
    $(function () {
            $('#winPage').createPage({
                pageSize:<?= $page['page_size'] ?>,
                pageCount:<?= $page['page_count'] ?>,
                current:<?= $page['page_index'] ?>,
                count:<?= $page['counts'] ?>,
                backFn: function (p) {
                    searchWinJson['page_index'] = p;
                    getWinList();
                }
            });
    });
</script>
