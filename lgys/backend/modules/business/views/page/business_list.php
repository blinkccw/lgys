<?php

use yii\helpers\Html;
?>
<table class="table">
    <thead>
        <tr>
            <th style="width:50px">头像</th>
            <th>商户</th>
            <th class="min">分类</th>
            <th class="min">等级</th>
            <th class="min">人均</th>
            <th class="min">代币余额</th>
            <th class="min">发行量</th>
            <th class="min">承销量</th>
            <th class="min">抽成</th>
            <th class="min">发行率</th>
            <th class="min">承销率</th>
            <th class="min">是否推荐</th>
            <th class="min">状态</th>
            <th class="do">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $item) { ?>
            <tr>
                <td><img class="vip-face" src="<?= $item['face_path'] ? (Yii::$app->params['WEB_URL'] . $item['face_path']) : '/images/face.png' ?>" ></td>
                <td>
                    名称：<?= Html::encode($item['name']) ?><br/>
                    联系人：<?= Html::encode($item['contacts']) ?><br/>
                    手机：<?= Html::encode($item['phone']) ?><br/>
                    用户：<?= $item['vip'] ? Html::encode($item['vip']['nick_name']) : '无' ?>
                </td>
                <td class="min"><?= $item['sort'] ? Html::encode($item['sort']['name']) : '无' ?></td>
                <td class="min"><?= $item['grade'] ? Html::encode($item['grade']['name']) : '无' ?></td>
                <td class="min"><?= $item['per'] > 0 ? round($item['per'], 2) : '未知' ?></td>
                <td class="min"><a href="javascript:rechargePage(<?= $item['id'] ?>);" title="充值"><span class="iconfont icon-edit" style="color:#ccc; margin-right: 5px;" title="充值"></span><?= Html::encode($item['points']) ?></a></td>
                <td class="min"><a href="javascript:exchangeLog(<?= $item['id'] ?>);" title="查看记录"><span class="iconfont icon-search" style="color:#ccc; margin-right: 5px;" title="查看"></span><?= Html::encode($item['exchange_points']) ?></a></td>
                <td class="min"><a href="javascript:deductionLog(<?= $item['id'] ?>);" title="查看记录"><span class="iconfont icon-search" style="color:#ccc; margin-right: 5px;" title="查看"></span><?= Html::encode($item['deduction_points']) ?></a></td>
                <td class="min"><?= round($item['ercentage'],1) ?></td>
                <td class="min"><?= Html::encode($item['exchange_pre']) ?>%</td>
                <td class="min"><?= Html::encode($item['deduction_pre']) ?>%</td>
                <td class="min">
                    <select data-type="is_hot" data-id="<?= $item['id'] ?>">
                        <option value="1" <?= $item['is_hot'] == 1 ? 'selected' : '' ?>>是</option>
                        <option value="0" <?= $item['is_hot'] == 0 ? 'selected' : '' ?>>否</option>
                    </select>
                </td>
                <td class="min">
                    <select data-type="status" data-id="<?= $item['id'] ?>">
                        <option value="1" <?= $item['status'] == 1 ? 'selected' : '' ?>>上架</option>
                        <option value="0" <?= $item['status'] == 0 ? 'selected' : '' ?>>下架</option>
                    </select>
                </td>
                <td class="do">
                    <a class="do-btn" href="javascript:showItem(<?= $item['id'] ?>);">查看</a><br/>
                    <a class="do-btn" href="javascript:noticeForm(<?= $item['id'] ?>);" title="消息">消息</a><br/>
                    <a class="do-btn" href="javascript:showAlliance(<?= $item['id'] ?>);" title="联盟">联盟</a><br/>
                    <a class="do-btn" href="javascript:doForm(<?= $item['id'] ?>);" title="编辑">编辑</a><br/>
                     <a class="do-btn"  title="下载二维码" href="/down?file_path=<?= 'b_' . $item['id'] ?>.png" target="_blank">下载二维码</a><br>
                    <a class="do-btn" href="javascript:deleteItem(<?= $item['id'] ?>);">删除</a>
                </td>
            </tr>
        <?php } ?>
        <?php if (count($list) <= 0) { ?> 
            <tr><td colspan="22" class="no-data">没有数据</td></tr>
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
        initList();
    });
</script>
