<div class="main-containt">
    <div class="main-title">
        抽成配置
    </div>
    <div class="main-page">
          <div class="main-form">
            <form id="doForm" action="/business/action/config-form" method="post" onsubmit="return false;">
                <div class="table-form-box">
                    <table class="table-form">
                        <tr>
                            <th><span class="star">*</span>异盟折损率：</th>
                            <td> <input type="text" class="input" name="dis_commission"  tag="异盟多增加抽成比例" style="width:60px;" value="<?= round($config->dis_commission,2)?>" zNumber="true"  required /> %</td>
                        </tr>
                         <tr>
                            <th><span class="star">*</span>基础抽成比例：</th>
                            <td> <input type="text" class="input" name="same_commission"  tag="基础抽成比例" style="width:60px;" value="<?= round($config->same_commission,2)?>" zNumber="true"  required /> %</td>
                        </tr>
                         <tr>
                            <th><span class="star">*</span>平台抽成比例：</th>
                            <td>
                                <input type="text" class="input" name="common_commission" tag="平台抽成比例"  style="width:60px;" value="<?=  round($config->common_commission,2)?>"  zNumber="true"  required /> %
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
