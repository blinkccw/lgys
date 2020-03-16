<?php

namespace frontend\modules\vip\models\alliance;

use Yii;
use common\models\Alliance;
use common\models\AllianceBusiness;
use common\models\Business;
use common\models\Notice;
use yii\base\Model;

/**
 * 联盟邀请商户
 */
class InviteBusiness extends Model {

    public $business_id;
    public $alliance_id;
    public $json;

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
            [['alliance_id'], 'integer'],
            [['json'], 'trim'],
            [['json'], 'required'],
            [['json'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'business_id' => '商户',
            'alliance_id' => '名称',
            'json' => '商户信息'
        ];
    }

    /**
     * 保存
     */
    public function save($vip_id) {
        $json = json_decode($this->json);

        if (!$json) {
            $this->addError('save', '没有可以邀请的商户。');
            return FALSE;
        }
        $alliance = Alliance::find()->where(['id' => $this->alliance_id])->one();
        if (!$alliance) {
            $this->addError('save', '该联盟已经不存在。');
            return FALSE;
        }
        $business_list = [];
        //事务
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($json as $tem) {
                $alliance_business = AllianceBusiness::find()->where(['alliance_id' => $this->alliance_id, 'business_id' => $tem])->one();
                $business = Business::find()->where(['id' => $tem])->one();
                if (!$alliance_business) {
                    $alliance_business = new AllianceBusiness;
                    $alliance_business->alliance_id = $this->alliance_id;
                    $alliance_business->business_id = $tem;
                    $alliance_business->invite_business_id = $this->business_id;
                    $alliance_business->invite_vip_id = $vip_id;
                    if (!$alliance_business->save())
                        return false;
                }
                if ($business) {
                    $notice = new Notice;
                    $notice->title = "[".$alliance->name.']邀请您加入';
                    $notice->vip_id = $business->vip_id;
                    $notice->msg = "[".$alliance->name.']邀请您的商户['.$business->name.']加入';
                    $notice->save();
                }
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
        }
        return false;
    }

}
