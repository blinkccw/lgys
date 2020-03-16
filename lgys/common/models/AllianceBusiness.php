<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "alliance_business".
 *
 * @property int $id
 * @property int $alliance_id 联盟ID
 * @property int $business_id 商户ID
 * @property int $invite_business_id 邀请商户ID
 * @property int $is_host 是否创办人
 * @property int $status 审核状态
 * @property string $created_at 创建日期
 */
class AllianceBusiness extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'alliance_business';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['alliance_id', 'business_id'], 'required'],
            [['alliance_id', 'business_id', 'invite_business_id', 'invite_vip_id', 'is_host', 'status'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'alliance_id' => '联盟ID',
            'business_id' => '商户ID',
            'invite_business_id' => '邀请商户ID',
            'invite_vip_id' => '邀请人ID',
            'is_host' => '是否创办人',
            'status' => '审核状态',
            'created_at' => '创建日期',
        ];
    }

    /**
     * 关联商户
     * @return type
     */
    public function getBusiness() {
        return $this->hasOne(Business::className(), ['id' => 'business_id'])->where(['status'=>1,'is_audit'=>1]);
    }

    /**
     * 关联会员
     * @return type
     */
    public function getInviteVip() {
        return $this->hasOne(Vip::className(), ['id' => 'invite_vip_id'])->select('id,name');
    }

    /**
     * 关联联盟
     * @return type
     */
    public function getAlliance() {
        return $this->hasOne(Alliance::className(), ['id' => 'alliance_id']);
    }

}
