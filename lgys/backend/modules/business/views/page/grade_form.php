<?php

use yii\helpers\Html;
?>
<div class="main-form">
    <form id="gradeForm" action="/business/action/grade-form" method="post" onsubmit="return false;">
        <div class="table-form-box">
            <table class="table-form">
                <tr>
                    <th><span class="star">*</span>名称：</th>
                    <td><input type="text" class="input min-input" name="name" tag="名称" maxlength="50" value="<?=$model['name']?>" required autofocus /></td>
                </tr>
                 <tr>
                    <th><span class="star">*</span>会员数量：</th>
                    <td><input type="text" class="input min-input" name="vip_num" tag="会员数量"  value="<?=$model['vip_num']?>" zDigits="true" required  /></td>
                </tr>
                <tr>
                    <th><span class="star">*</span>抽成比例：</th>
                    <td><input type="text" class="input min-input" name="commission" tag="抽成比例"  value="<?=round($model['commission'],2)?>" zDigits="true" required  />%</td>
                </tr>
            </table>
        </div>
        <div class="form-btn">
            <input type="hidden" name="id" value="<?= $id ?>" />
            <input type="submit" class="btn" value="确定" />
        </div>
    </form>
</div>
<?= backend\widgets\Script::registerJsFile(); ?>