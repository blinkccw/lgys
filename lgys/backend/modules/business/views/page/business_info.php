<?php

use yii\helpers\Html;
?>
<div class="main-title">
    商户详情 > <?= Html::encode($business['name']) ?>
</div>
<div class="main-page fiex-main-page">
    <div class="main-form">
        <div class="table-form-box">
            <table class="table-form">
                <tr>
                    <th>商户名称：</th>
                    <td><?= Html::encode($business['name']) ?></td>
                </tr>
                <tr>
                    <th>联系人：</th>
                    <td><?= Html::encode($business['contacts']) ?></td>
                </tr>
                <tr>
                    <th>商户分类：</th>
                    <td><?= $business['sort'] ? Html::encode($business['sort']['name']) : '无' ?></td>
                </tr>
                <tr>
                    <th>商户地址：</th>
                    <td><?= Html::encode($business['address']) ?></td>
                </tr>
                <tr>
                    <th>营业执照：</th>
                    <td>
                        <div class="preview-img">
                            <img src="<?= $business['material'] ? (Yii::$app->params['WEB_URL'] . $business['material']['license_path']) : '/images/image-empty.png' ?>" />
                        </div>
                        <?php if ($business['material']) { ?>
                        <div style="width:80px;text-align: center;"><a style="text-decoration: none;" href="<?= (Yii::$app->params['WEB_URL'] . $business['material']['license_path']) ?>" target="_blank">查看大图</a></div>
                        <?php } ?>
                    </td>
                </tr>
                <?php if($business['sort_id']==1){?>
                <tr>
                    <th>食品许可证：</th>
                    <td>
                        <div class="preview-img">
                            <img src="<?= $business['material'] ? (Yii::$app->params['WEB_URL'] . $business['material']['food_license_path']) : '/images/image-empty.png' ?>" />
                        </div>
                        <?php if ($business['material']) { ?>
                            <div style="width:80px;text-align: center"><a style="text-decoration: none;" href="<?= (Yii::$app->params['WEB_URL'] . $business['material']['food_license_path']) ?>" target="_blank">查看大图</a></div>
                        <?php } ?>
                    </td>
                </tr>
                <?php }?>
                <tr>
                    <th>手机号：</th>
                    <td><?= Html::encode($business['phone']) ?></td>
                </tr>
                <tr>
                    <th>电子邮箱：</th>
                    <td><?= $business['material'] ? Html::encode($business['material']['email']) : '无' ?></td>
                </tr>
                <tr>
                    <th>银行卡号：</th>
                    <td><?= $business['material'] ? Html::encode($business['material']['bank_card']) : '无' ?></td>
                </tr>
                <tr>
                    <th>开户行地址：</th>
                    <td><?= $business['material'] ? Html::encode($business['material']['bank_add']) : '无' ?></td>
                </tr>
                <tr>
                    <th>身份证正面：</th>
                    <td>
                        <div class="preview-img">
                            <img src="<?= $business['material'] ? (Yii::$app->params['WEB_URL'] . $business['material']['card_path1']) : '/images/image-empty.png' ?>" />
                        </div>
                        <?php if ($business['material']) { ?>
                            <div style="width:80px;text-align: center"><a style="text-decoration: none;" href="<?= (Yii::$app->params['WEB_URL'] . $business['material']['card_path1']) ?>" target="_blank">查看大图</a></div>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th>身份证反面：</th>
                    <td>
                        <div class="preview-img">
                            <img src="<?= $business['material'] ? (Yii::$app->params['WEB_URL'] . $business['material']['card_path2']) : '/images/image-empty.png' ?>" />
                        </div>
                          <?php if ($business['material']) { ?>
                            <div style="width:80px;text-align: center"><a style="text-decoration: none;" href="<?= (Yii::$app->params['WEB_URL'] . $business['material']['card_path2']) ?>" target="_blank">查看大图</a></div>
                        <?php } ?>
                    </td>
                </tr>
                    <tr>
                    <th>创建日期：</th>
                    <td><?= $business['created_at']?></td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <input type="button" onclick="hideFormPage()" class="btn btn-empty" value="返回" />
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
</script>
<?= backend\widgets\Script::registerJsFile(); ?>