<?php

use yii\helpers\Html;
?>
<div class="main-title">
    交易【<?= Html::encode($no) ?>】代币抵扣记录
</div>
<div class="main-page">
    <div class="search-box">
        <input type="button" onclick="hideFormPage()" class="btn btn-gray" value="返回" />
    </div>
    <div class="main-list">
        <table class="table">
            <thead>
                <tr>
                    <th>商户</th>
                    <th class="min">代币数</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $item) { ?>
                    <tr>
                        <td><?=$item['business_id']==0?'通用':($item['business'] ? Html::encode($item['business']['name']) : '无') ?></td>
                        <td class="min"><?= Html::encode($item['points']) ?></td>
                    </tr>
                <?php } ?>
                <?php if (count($logs) <= 0) { ?> 
                    <tr><td colspan="2" class="no-data">没有数据</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>