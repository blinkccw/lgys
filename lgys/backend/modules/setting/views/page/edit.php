<?php

use yii\helpers\Html;
?>
<div class="main-containt">
    <div class="main-title">
        修改密码
    </div>
    <div class="main-page">
        <div class="main-form">
            <form id="doForm" action="/setting/action/edit-form" method="post" onsubmit="return false;">
                <div class="table-form-box">
                    <table class="table-form">
                        <tr>
                            <th>姓名：</th>
                            <td><?= Html::encode($user->name) ?></td>
                        </tr>
                        <tr>
                            <th>用户名：</th>
                            <td><?= Html::encode($user->username) ?></td>
                        </tr>
                         <tr>
                            <th><span class="star">*</span>旧密码：</th>
                            <td>
                                <input type="password" class="input min-input" name="old_password" tag="旧密码"  required />
                            </td>
                        </tr>
                         <tr>
                            <th><span class="star">*</span>新密码：</th>
                            <td>
                                <input id="txtNewPasswords" type="password" class="input min-input" name="new_password" tag="新密码" minlength="6" maxlength="20"  required />
                            </td>
                        </tr>
                         <tr>
                            <th><span class="star">*</span>确认新密码：</th>
                            <td>
                                <input type="password" class="input min-input" name="com_new_password" tag="两次新密码" equalTo="#txtNewPasswords" />
                            </td>
                        </tr>
                        <tr>
                            <th></th>
                            <td>
                                <input type="submit" class="btn" value="提交" />
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<?= backend\widgets\Script::registerJsFile(); ?>