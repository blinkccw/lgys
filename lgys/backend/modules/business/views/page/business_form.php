<?php

use yii\helpers\Html;
?>
<div class="main-title">
    商户列表 > <?= ($id == 0 ? '新增商户' : '编辑商户') ?>
</div>
<div class="main-page fiex-main-page">
    <div class="main-form">
        <form id="doForm" action="/business/action/business-form" method="post" onsubmit="return false;">
            <div class="table-form-box">
                <table class="table-form">
                    <tr>
                        <th>头像：</th>
                        <td>
                            <span class="btn btn-select btn-gray " id="btnImg">+选择图片</span>
                            <input value="<?= $model['face_path'] ?>" type="hidden" name="face_path" id="txtFacePath" />
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <div class="preview-img" id="previewImg">
                                <img src="<?= $model['face_path'] ? (Yii::$app->params['WEB_URL'] . $model['face_path']) : '/images/image-empty.png' ?>" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><span class="star">*</span>名称：</th>
                        <td><input type="text" class="input min-input" name="name" tag="名称"  maxlength="100" value="<?= $model['name'] ?>" style="width:300px;" required autofocus /></td>
                    </tr>
                    <tr>
                        <th><span class="star">*</span>分类：</th>
                        <td><select id="ddlSort" name="sort_id">
                                <option value="0">请选择分类</option>
                                <?php foreach ($sorts as $sort) { ?>
                                    <option value="<?= $sort->id ?>" <?= $model['sort_id'] == $sort->id ? 'selected' : '' ?>><?= Html::encode($sort->name) ?></option>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <th><span class="star">*</span>发行比例：</th>
                        <td><input type="text" class="input min-input" name="exchange_pre" tag="发行比例"  value="<?= $model['exchange_pre'] ?>" style="width:60px;" zDigits="true" required /> %</td>
                    </tr>
                    <tr>
                        <th><span class="star">*</span>抵扣比例：</th>
                        <td><input type="text" class="input min-input" name="deduction_pre" tag="抵扣比例"  value="<?= $model['deduction_pre'] ?>" style="width:60px;" zDigits="true" required /> %</td>
                    </tr>
                    <tr>
                        <th><span class="star">*</span>联系人：</th>
                        <td><input type="text" class="input min-input" name="contacts" tag="联系人"  maxlength="50" value="<?= $model['contacts'] ?>"  required /></td>
                    </tr>
                    <tr>
                        <th><span class="star">*</span>手机号：</th>
                        <td><input type="text" class="input min-input" name="phone" tag="手机号"  maxlength="11" value="<?= $model['phone'] ?>" phone="true" required /></td>
                    </tr>
                    <tr>
                        <th><span class="star">*</span>用户：</th>
                        <td>
                            <input type="button" id="btnVip"  class="btn btn-select btn-gray" value="+选择用户" />
                            <input type="hidden" name="vip_id" id="txtVipID" value="<?= $model['vip'] ? $model['vip_id'] : '' ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td><img id="vipFace" class="vip-face" src="<?= ($model['vip'] && $model['vip']['avatar_url']) ? $model['vip']['avatar_url'] : '/images/face.png' ?>" style="width:25px;height:25px; vertical-align: middle;" ><span id="vipBox" style="vertical-align: middle;"><?= $model['vip'] ? Html::encode($model['vip']['nick_name']) : '无' ?></span></td>
                    </tr>

                    <tr>
                        <th><span class="star">*</span>门店定位：</th>
                        <td>
                            <input type="button" id="btnLocation"  class="btn btn-select btn-gray" value="+选择定位" />
                            <input value="<?= $model['longitude'] ?>" type="hidden" name="longitude" id="txtLongitude" />
                            <input value="<?= $model['latitude'] ?>" type="hidden" name="latitude" id="txtLatitude" />
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            纬度：<span id="latitudeBox"><?= $model['longitude'] ? $model['longitude'] : '无' ?></span>&nbsp;&nbsp;&nbsp;&nbsp;经度：<span id="longitudeBox"><?= $model['latitude'] ? $model['latitude'] : '无' ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th><span class="star">*</span>门店地址：</th>
                        <td><input type="text" class="input min-input" name="address" tag="地址" maxlength="200"  value="<?= $model['address'] ?>" style="width:400px;" required /></td>
                    </tr>
                      <tr>
                        <th>门店电话：</th>
                        <td><input type="text" class="input min-input" name="tel" tag="门店电话" maxlength="50"  value="<?= $model['tel'] ?>"  /></td>
                    </tr>
                    <tr>
                        <th>营业时间：</th>
                        <td><input type="text" class="input min-input" name="hours" tag="营业时间" maxlength="50"  value="<?= $model['hours'] ?>"  /></td>
                    </tr>
                      <tr>
                        <th>微信支付商户号：</th>
                        <td><input type="text" class="input min-input" name="mch_id" tag="微信支付商户号" maxlength="100"  value="<?= $model['mch_id'] ?>"  /></td>
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