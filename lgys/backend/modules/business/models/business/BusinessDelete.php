<?php

namespace backend\modules\business\models\business;

use Yii;
use common\models\Business;
use common\models\BusinessMaterial;
use common\models\AllianceBusiness;
use common\models\Vip;
use yii\base\Model;

/**
 * 删除商户
 *
 * @author xjx
 */
class BusinessDelete extends Model {

    public $id;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer']];
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
    public function delete() {
        $business = Business::find()->where(['id' => $this->id])->one();
        if (!$business) {
            $this->addError('save', '商户信息不存在。');
            return FALSE;
        }
        $vip = Vip::find()->where(['id' => $business->vip_id])->one();
        $counts = 1;
        if ($vip)
            $counts = Business::find ()->where (['vip_id' =>$vip->id])->count('id');
        //事务
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($business->delete()) {
                AllianceBusiness::deleteAll(['business_id'=>$business->id]);
                if($counts<=1&&$vip){
                    $vip->is_business=0;
                    $vip->save();
                }
                BusinessMaterial::deleteAll(['business_id'=>$business->id]);
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
        }
        return false;
    }

}
