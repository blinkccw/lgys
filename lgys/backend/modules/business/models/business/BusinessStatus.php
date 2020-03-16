<?php

namespace backend\modules\business\models\business;

use Yii;
use common\models\Business;
use yii\base\Model;

/**
 * 商户状态
 *
 * @author xjx
 */
class BusinessStatus extends Model {

    public $id;
    public $status;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer'],
            [['status'], 'trim'],
            [['status'], 'required'],
            [['status'], 'integer']];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'id',
            'status'=>'状态'
        ];
    }

    /**
     * 删除操作
     */
    public function save() {
        $business = Business::find()->where(['id' => $this->id])->one();
        if (!$business) {
            $this->addError('save', '商户信息不存在。');
            return FALSE;
        }
        $business->status = $this->status;
        return $business->save();
    }

}
