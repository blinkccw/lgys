<?php

use yii\helpers\Html;
?>
<div class="main-form">
    <form id="sortForm" action="/business/action/sort-form" method="post" onsubmit="return false;">
        <div class="table-form-box">
            <table class="table-form">
                <tr>
                    <th><span class="star">*</span>名称：</th>
                    <td><input type="text" class="input min-input" name="name" tag="名称" maxlength="50" value="<?=$model['name']?>" required autofocus /></td>
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