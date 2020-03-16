<div class="main-form">
    <form id="doForm" action="/setting/action/user-form" method="post" onsubmit="return false;">
           <div class="table-form-box">
                <table class="table-form">
                    <tr>
                        <th><span class="star">*</span>姓名：</th>
                        <td><input type="text" class="input min-input" name="name" tag="姓名"  maxlength="20" value="" required autofocus /></td>
                    </tr>
                    <tr>
                        <th><span class="star">*</span>用户名：</th>
                        <td><input type="text" class="input min-input" name="username" tag="用户名" minlength="4" maxlength="20" value="" required /></td>
                    </tr>
                    <tr>
                        <th><span class="star">*</span>密码：</th>
                        <td><input type="password" id="txtPasswords" class="input min-input" name="password" tag="密码" minlength="6" maxlength="20"  value="" required /></td>
                    </tr>
                    <tr>
                        <th><span class="star">*</span>确认密码：</th>
                        <td><input type="password" class="input min-input" name="com_password" equalTo="#txtPasswords" tag="两次密码" maxlength="50" value="" required /></td>
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
<?= backend\widgets\Script::registerJsFile(); ?>