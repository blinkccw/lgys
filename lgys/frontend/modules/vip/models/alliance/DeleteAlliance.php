<?php

namespace frontend\modules\vip\models\alliance;

use Yii;
use common\models\Alliance;
use common\models\AllianceBusiness;
use yii\base\Model;

/**
 * 退出联盟
 */
class DeleteAlliance extends Model {

    public $business_id;
    public $alliance_id;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['business_id'], 'trim'],
            [['business_id'], 'required'],
            [['business_id'], 'integer'],
            [['alliance_id'], 'trim'],
            [['alliance_id'], 'required'],
            [['alliance_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'business_id' => '商户',
            'alliance_id' => '名称'
        ];
    }

    /**
     * 保存
     */
    public function save($vip_id) {
        $alliance = Alliance::find()->where(['id' => $this->alliance_id])->one();
        if (!$alliance) {
            $this->addError('save', '该联盟已经不存在。');
            return FALSE;
        }
        if ($alliance->business_id == $this->business_id && $alliance->vip_id == $vip_id) {
            //事务
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!$alliance->delete()) {
                    return false;
                }
                $rel = AllianceBusiness::deleteAll(['alliance_id' => $alliance->id]);
                $transaction->commit();
                return $rel;
            } catch (Exception $ex) {
                $transaction->rollBack();
            }
        } else {
            $rel = AllianceBusiness::deleteAll(['alliance_id' => $alliance->id, 'business_id' => $this->business_id]);
            if($rel){
                 Alliance::updateAllCounters(['num' => -1], ['id' =>$alliance->id]);
            }
            return $rel;
        }
        return false;
    }

}
