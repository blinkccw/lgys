<?php

namespace backend\modules\setting\models\user;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * 删除管理员
 *
 * @author xjx
 */
class UserDelete extends Model {

    public $id;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'required'],
            [['id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'id'
        ];
    }

    /**
     * 删除操作
     */
    public function delete($cur_user) {
        if ($cur_user->is_admin != 1) {
            $this->addError('save', '非超级管理员无法操作。');
            return false;
        }
        $user = $this->getUser();
        if (!$user) {
            $this->addError('save', '该管理员信息不存在。');
            return false;
        }
        return $user->delete();
    }

    /**
     * 获管理员信息
     * @return type
     */
    protected function getUser() {
        if ($this->_user === null) {
            $this->_user = User::findOne(['id' => $this->id]);
        }
        return $this->_user;
    }

}
