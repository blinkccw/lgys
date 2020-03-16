<?php

use yii\helpers\Html;
?>
<div class="main-form">
    <form id="rechargeForm" action="/business/action/recharge-form" method="post" onsubmit="return false;">
        <div class="table-form-box">
            <table class="table-form">
                <tr>
                    <th><span class="star">*</span>代币数：</th>
                    <td><input type="text" class="input min-input" name="points" tag="代币数" zDigits="true" required autofocus /></td>
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