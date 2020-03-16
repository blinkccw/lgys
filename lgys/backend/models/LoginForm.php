<?php

namespace backend\models;

use Yii;
use yii\web\Cookie;
use yii\base\Model;
use common\models\User;
use common\models\UserLoginLog;

/**
 * 登录表单
 */
class LoginForm extends Model {

    public $username;
    public $password;
    public $verifycode;
    public $captcha;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['username', 'password', 'verifycode'], 'trim'],
            [['username', 'password', 'verifycode'], 'required'],
            ['verifycode', 'validateCode'],
            ['password', 'validatePassword']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'username' => '用户名',
            'password' => '密码',
            'verifycode' => '验证码'
        ];
    }

    /**
     * 验证验证码
     * @param type $attribute
     * @param type $params
     */
    public function validateCode($attribute, $params) {
        if (!$this->hasErrors()) {
            if (strlen($this->captcha) == 0 || strtolower($this->captcha) != strtolower($this->verifycode)) {
                $this->addError($attribute, '验证码不正确。');
                return;
            }
        }
    }

    /**
     * 验证密码
     * @param type $attribute
     * @param type $params
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或密码不正确。');
                return;
            }
            if ($user && $user->status == 0) {
                $this->addError($attribute, '您的帐号已被禁用，请联系管理员。');
                return;
            }
        }
    }

    /**
     * 登录
     * @return boolean
     */
    public function login() {
        if ($this->validate()) {
            $user = $this->getUser();
            if (Yii::$app->user->login($user)) {
                $user->logined_at = time();
                $user->save();
                try {
                    $log = new UserLoginLog();
                    $log->user_id = $user->id;
                    $log->username = $user->username;
                    $log->name = $user->name;
                    $log->ip = Yii::$app->request->userIP;
                    $log->save();
                } catch (Exception $e) {
                    
                }
                $cookies = Yii::$app->response->cookies;
                $cookies->add(new Cookie([
                    'name' => 'username',
                    'value' => $user->username,
                    'expire' => time() + 315360000
                ]));
                return true;
            }
            return false;
        } else {
            return false;
        }
    }

    /**
     * 获取用户信息
     * @return type
     */
    protected function getUser() {
        if ($this->_user === null) {
            $this->_user = User::find()->where(['username' => $this->username])->one();
        }
        return $this->_user;
    }

}
