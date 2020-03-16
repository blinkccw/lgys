<?php

namespace backend\modules\setting\models\user;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * 管理员表单
 */
class UserForm extends Model {

    public $name;
    public $username;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'username', 'password'], 'trim'],
            [['name', 'username', 'password'], 'required'],
            [['name'], 'string', 'max' => 20],
            [['username'], 'string', 'min' => 4, 'max' => 20],
            [['username'], 'match', 'pattern' => '/^[_0-9a-zA-Z]+$/i', 'message' => '{attribute}只能包含数字、字母和下划线。'],
            [['username'], 'unique', 'targetClass' => User::className(), 'message' => '用户名已经被注册。'],
            [['password'], 'string', 'min' => 6, 'max' => 20],
            [['password'], 'match', 'pattern' => '/^[_0-9a-zA-Z~!@#$%^&*]+$/i', 'message' => '{attribute}只能包含数字、字母和特殊字符(~!@#$%^&*)。'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'name' => '姓名',
            'username' => '用户名',
            'password' => '密码'
        ];
    }

    /**
     * 保存
     */
    public function save() {
        $user = new User;
        $user->name = $this->name;
        $user->username = $this->username;
        $user->generateAuthKey();
        $user->setPassword($this->password);
        $user->is_admin = 0;
        return $user->save();
    }

}
