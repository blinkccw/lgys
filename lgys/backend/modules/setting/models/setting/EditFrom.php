<?php

namespace backend\modules\setting\models\setting;

use Yii;
use yii\base\Model;

/**
 * 设置表单
 */
class EditFrom extends Model {

    public $old_password;
    public $new_password;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['old_password', 'new_password'], 'trim'],
            [['old_password', 'new_password'], 'required'],
            [['old_password'], 'string'],
            [['new_password'], 'string', 'min' => 6, 'max' => 20],
            [['new_password'], 'match', 'pattern' => '/^[_0-9a-zA-Z~!@#$%^&*]+$/i', 'message' => '{attribute}只能包含数字、字母和特殊字符(~!@#$%^&*)。'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'old_password' => '旧密码',
            'new_password' => '新密码'
        ];
    }

    /**
     * 保存
     */
    public function save($user) {
        if (!$user->validatePassword($this->old_password)) {
            $this->addError('save', '旧密码不正确。');
            return;
        }
        $user->setPassword($this->new_password);
        return $user->save();
    }

}
