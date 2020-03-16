<div class="main-containt">
    <div class="main-title">
        系统配置
    </div>
    <div class="main-page">
          <div class="main-form">
            <form id="doForm" action="/setting/action/config-form" method="post" onsubmit="return false;">
                <div class="table-form-box">
                    <table class="table-form">
                        <tr>
                            <th>微信支付配置：</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th><span class="star">*</span>商户号(mch_id)：</th>
                            <td> <input type="text" class="input" name="pay_mch_id" maxlength="50" tag="商户号(mch_id)" style="width:300px;" value="<?= $config->pay_mch_id?>"  required /></td>
                        </tr>
                         <tr>
                            <th><span class="star">*</span>API密钥(key)：</th>
                            <td>
                                <input type="text" class="input" name="pay_key" maxlength="50" tag="API密钥(key)"  style="width:300px;" value="<?= $config->pay_key?>" required />
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
<?= backend\widgets\Script::registerJsFile(); ?>
