<?php

use yii\helpers\Html;
?>
<table class="table">
    <thead>
        <tr>
            <th>商户名称</th>
            <th>联系人</th>
            <th>手机号码</th>
            <th>商户地址</th>
            <th class="min">审核状态</th>
            <th class="min">创建日期</th>
            <th class="do">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $item) { ?>
            <tr>
                <td><?= Html::encode($item['name']) ?></td>
                <td><?= Html::encode($item['contacts']) ?></td>
                <td><?= Html::encode($item['phone']) ?></td>
                <td><?= Html::encode($item['address']) ?></td>
                <td class="min"><?= $item['is_audit'] == 0 ? '待审核' : '审核失败' ?></td>
                <td class="min"><?= $item['created_at'] ?></td>
                <td class="do">
                     <a class="do-btn" href="javascript:showItem(<?= $item['id'] ?>);">查看</a><br/>
                    <?php if ($item['is_audit'] == 0) { ?>
                        <a class="do-btn" href="javascript:auditItem(<?= $item['id'] ?>,1);" >通过</a><br/>
                        <a class="do-btn" href="javascript:auditItem(<?= $item['id'] ?>,2);" >不通过</a><br/>
                    <?php } else { ?>
                        <a class="do-btn" href="javascript:auditItem(<?= $item['id'] ?>,1);" >通过</a><br/>
                    <?php } ?>                  
                    <a class="do-btn" href="javascript:deleteItem(<?= $item['id'] ?>);">删除</a>
                </td>
            </tr>
        <?php } ?>
        <?php if (count($list) <= 0) { ?> 
            <tr><td colspan="18" class="no-data">没有数据</td></tr>
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
