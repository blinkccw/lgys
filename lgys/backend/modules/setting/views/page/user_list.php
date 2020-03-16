<?php

use yii\helpers\Html;
?>
<table class="table">
    <thead>
        <tr>
            <th>姓名</th>
            <th>用户名</th>
            <th>类型</th>
            <th class="date">最近登录日期</th>
            <th class="date">创建日期</th>
            <th class="min center">状态</th>
            <th class="do">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $item) { ?>
            <tr>
                <td><?= Html::encode($item['name']) ?></td>
                <td><?= Html::encode($item['username']) ?></td>
                <td><?= $item['is_admin'] == 1 ? '超级管理员' : '普通管理员' ?></td>
                <td><?=$item['logined_at']?date('Y-m-d H:i:s',$item['logined_at']):'无' ?></td>
                <td><?=date('Y-m-d H:i:s',$item['created_at']) ?></td>
                <td class="center">
                 <?php if ($item['status'] == 1) { ?>
                    <span class="iconfont icon-suc i-suc" title="开启"></span>
                    <?php } else { ?>
                    <span class="iconfont icon-error i-error" title="禁用"></span>
                    <?php } ?></td>
                <td class="do">
                    <?php if ($user['is_admin'] == 1&&$item['is_admin']==0) { ?>
                        <a class="do-btn" href="javascript:deleteItem(<?= $item['id'] ?>);" title="删除">删除</a>
                        <?php if ($item['status'] == 1) { ?>
                            <a class="do-btn" href="javascript:setItemStatus(<?= $item['id'] ?>,0);" title="禁用">禁用</a>
                        <?php } else { ?>
                            <a class="do-btn" href="javascript:setItemStatus(<?= $item['id'] ?>,1);" title="开启">开启</a>
                        <?php } ?>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        <?php if (count($list) <= 0) { ?> 
            <tr><td colspan="7" class="no-data">没有数据</td></tr>
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
