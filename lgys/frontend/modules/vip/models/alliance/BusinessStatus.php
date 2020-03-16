<?php

namespace frontend\modules\vip\models\alliance;

use Yii;
use common\models\Alliance;
use common\models\AllianceBusiness;
use yii\base\Model;

/**
 *   联盟商户状态
 */
class BusinessStatus extends Model {

    public $id;
    public $business_id;
    public $alliance_id;
    public $status;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer'],
            [['business_id'], 'trim'],
            [['business_id'], 'required'],
            [['business_id'], 'integer'],
            [['alliance_id'], 'trim'],
            [['alliance_id'], 'required'],
            [['alliance_id'], 'integer'],
            [['status'], 'trim'],
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
            'business_id' => '商户',
            'alliance_id' => '名称',
            'status' => '状态'
        ];
    }

    /**
     * 保存
     */
    public function save() {
        $alliance_business = AllianceBusiness::find()->where(['id' => $this->id, 'business_id' => $this->business_id, 'alliance_id' => $this->alliance_id])->one();
        if (!$alliance_business) {
            $this->addError('save', '记录已经不存在。');
            return FALSE;
        }
        if ($alliance_business->status == 1) {
            $this->addError('save', '该邀请记录已经是通过状态。');
            return FALSE;
        }
        if ($this->status == 1) {
            $alliance_business->status = 1;
            $alliance_business->created_at = date('Y-m-d H:i:s');
            $rel = $alliance_business->save();
            if ($rel) {
                Alliance::updateAllCounters(['num' => +1], ['id' => $this->alliance_id]);
            }
            return $rel;
        } else if ($this->status == 0) {
            return $alliance_business->delete();
        }
        return false;
    }

}
