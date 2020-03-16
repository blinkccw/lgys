<?php

namespace backend\modules\business\models\business;

use Yii;
use common\models\Business;
use yii\base\Model;

/**
 * 商户推荐状态
 *
 * @author xjx
 */
class BusinessIshot extends Model {

    public $id;
    public $is_hot;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer'],
            [['is_hot'], 'trim'],
            [['is_hot'], 'required'],
            [['is_hot'], 'integer']];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'id',
            'is_hot'=>'是否推荐'
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
        $business->is_hot = $this->is_hot;
        return $business->save();
    }

}
