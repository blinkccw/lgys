<?php

use yii\helpers\Html;
?>
<div class="main-title">
    联盟列表 > <?= ($id == 0 ? '新增联盟' : '编辑联盟') ?>
</div>
<div class="main-page fiex-main-page">
    <div class="main-form">
        <form id="doForm" action="/alliance/action/alliance-form" method="post" onsubmit="return false;">
            <div class="table-form-box">
                <table class="table-form">
                    
                    <tr>
                        <th><span class="star">*</span>名称：</th>
                        <td><input type="text" class="input min-input" name="name" tag="名称"  maxlength="100" value="<?= $model['name'] ?>" style="width:300px;" required autofocus /></td>
                    </tr>
                    <tr>
                        <th>介绍：</th>
                        <td><textarea name="info" class="input" style="width:400px;height:200px;"><?= $model['info'] ?></textarea></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <input type="hidden" name="id" value="<?= $id ?>" />
                            <input type="submit" class="btn" value="提交" />
                            <input type="button" onclick="hideFormPage()" class="btn btn-empty" value="返回" />
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>
<?= backend\widgets\Script::registerJsFile(); ?>