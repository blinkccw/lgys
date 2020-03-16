<?php

use yii\helpers\Html;
?>
<div class="main-title">
    <?= Html::encode($business['name']) ?> > 代发消息
</div>
<div class="main-page fiex-main-page">
    <div class="main-form">
        <form id="doForm" action="/business/action/notice-form" method="post" onsubmit="return false;">
            <div class="table-form-box">
                <table class="table-form">
                    <tr>
                        <th>封面：</th>
                        <td>
                            <span class="btn btn-select btn-gray " id="btnImg">+选择图片</span>
                            <input value="" type="hidden" name="face_path" id="txtFacePath" />
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <div class="preview-img" id="previewImg">
                                <img src="/images/image-empty.png" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><span class="star">*</span>标题：</th>
                        <td><input type="text" class="input min-input" name="title" tag="标题"  maxlength="100"  style="width:300px;" required autofocus /></td>
                    </tr>
                    <tr>
                        <th><span class="star">*</span>内容：</th>
                        <td>
                            <div id="txtRichInfo" style="width:80%;">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><span class="star">*</span>发送条件：</th>
                        <td>
                            <select name="term">
                                <option value="1">全部会员</option>
                                <option value="2">7天内新会员</option>
                                <option value="3">30天未消费会员</option>
                            </select>
                        </td>
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
<script type="text/javascript">
</script>
<?= backend\widgets\Script::registerJsFile(); ?>