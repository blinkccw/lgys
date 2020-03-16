<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '细物互享-运营管理平台';
?>
<div class="login-page">
    <div class="login-box">
        <form id="loginForm" action="/action/login" method="post" onsubmit="return false;">
            <h4>运营管理平台</h4>
            <div class="login-item">
                <div class="item-name"><span>*</span>用户名</div>
                <div><input class="input" name="username" id="username" placeholder="用户名" tag="用户名" required autofocus /></div>
            </div>
            <div class="login-item">
                <div class="item-name"><span>*</span>密码</div>
                <div><input class="input" name="password" id="password" type="password" tag="密码" placeholder="密码" required  /></div>
            </div>
            <div class="login-item">
                <div class="item-name"><span>*</span>验证码</div>
                <div class="item-code"><input class="input input-captcha" id="verifycode" name="verifycode" tag="验证码" placeholder="验证码" required /><img id="imgCode" src="/action/captcha" /></div>
            </div>
            <div class="login-btn">
                <input class="btn" type="submit" id="btnSubmit" value="登录" />
            </div>
        </form>
    </div>
</div>
</div>
<script src="/js/page/login.js?030601"></script>