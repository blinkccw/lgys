<?php

namespace backend\modules\setting\models\user;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * 管理员状态设置
 *
 * @author xjx
 */
class UserStatus extends Model {

    public $id;
    public $status;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['status'], 'required'],
            [['status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'id',
            'status' => '状态'
        ];
    }

    /**
     * 操作
     */
    public function save($cur_user) {
        if ($cur_user->is_admin != 1) {
            $this->addError('save', '非超级管理员无法操作。');
            return false;
        }
        $user= $this->getUser();
        if (!$user) {
            $this->addError('save', '该管理员信息不存在。');
            return false;
        }
        $user->status = $this->status;
        return $user->save();
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
