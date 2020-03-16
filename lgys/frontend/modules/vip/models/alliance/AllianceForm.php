<?php

namespace frontend\modules\vip\models\alliance;

use Yii;
use common\models\Alliance;
use common\models\AllianceBusiness;
use yii\base\Model;

/**
 * 联盟表单
 */
class AllianceForm extends Model {

    public $business_id;
    public $name;
    public $info;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['business_id'], 'trim'],
            [['business_id'], 'required'],
            [['business_id'], 'integer'],
            [['name'], 'trim'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['info'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'business_id' => '商户',
            'name' => '名称',
            'info' => '介绍'
        ];
    }

    /**
     * 保存
     */
    public function save($vip_id) {
        //事务
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $alliance = new Alliance;
            $alliance->vip_id = $vip_id;
            $alliance->business_id = $this->business_id;
            $alliance->name = $this->name;
            $alliance->info = $this->info;
            if (!$alliance->save())
                return false;
            $alliance_business = new AllianceBusiness;
            $alliance_business->alliance_id = $alliance->id;
            $alliance_business->business_id = $this->business_id;
            $alliance_business->is_host = 1;
            $alliance_business->status = 1;
            if (!$alliance_business->save())
                return false;
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
        }
        return false;
    }

}
