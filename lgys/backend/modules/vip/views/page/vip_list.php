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
            <th>消费总金额</th>
            <th>代币余额</th>
            <th>代币销毁数</th>
            <th class="date">创建日期</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $item) { ?>
            <tr>
                <td><img class="vip-face" src="<?= $item['avatar_url'] ? $item['avatar_url'] : '/images/face.png' ?>" ></td>
                <td  class="min"><?= Html::encode($item['vip_no']) ?></td>
                <td><?= Html::encode($item['name']) ?></td>
                <td><?= Html::encode($item['nick_name']) ?></td>
                <td><a href="javascript:payPage(<?= $item['id'] ?>);" title="查看记录"><span class="iconfont icon-search" style="color:#ccc; margin-right: 5px;" title="查看"></span><?= Html::encode($item['total']) ?></a></td>
                <td><?= Html::encode($item['points']) ?></td>
                <td><?= Html::encode($item['used_points']) ?></td>
                <td class="min"><?= $item['created_at'] ?></td>
            </tr>
        <?php } ?>
        <?php if (count($list) <= 0) { ?> 
            <tr><td colspan="8" class="no-data">没有数据</td></tr>
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
