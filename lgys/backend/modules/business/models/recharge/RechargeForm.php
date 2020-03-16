<?php

namespace backend\modules\business\models\recharge;

use Yii;
use common\models\Business;
use common\models\BusinessPoints;
use yii\base\Model;

/**
 * 商户充值表单
 */
class RechargeForm extends Model {

    public $id;
    public $points;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer'],
            [['points'], 'trim'],
            [['points'], 'required'],
            [['points'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => '商户id',
            'points' => '代币数'
        ];
    }

    /**
     * 保存
     */
    public function save() {
        $business = Business::find()->where(['id' => $this->id])->one();
        if (!$business) {
            $this->addError('save', '商户信息不存在。');
            return FALSE;
        }
        //事务
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $points = new BusinessPoints;
            $points->business_id = $business->id;
            $points->points = $this->points;
            if (!$points->save())
                return false;
            $business->points += $this->points;
            $business->total_points += $this->points;
            if (!$business->save())
                return false;
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
        }
        return false;
    }

}
