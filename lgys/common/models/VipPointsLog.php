<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vip_points_log".
 *
 * @property int $id
 * @property int $pay_id 支付ID
 * @property int $vip_id 会员ID
 * @property int $points_type 代币类型（0：通用,1:商户）
 * @property int $business_id 商户ID
 * @property int $business_grade_id
 * @property int $alliance_id 联盟ID
 * @property int $pre 比例
 * @property double $points 代币
 * @property double $used_points 使用代币
 * @property int $flag
 * @property int $status 状态
 * @property int $parent_id 父级
 * @property int $source 来源（1：消费，2：赠送，3：到期）
 * @property int $source_id
 * @property string $created_at 创建日期
 */
class VipPointsLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vip_points_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pay_id', 'vip_id', 'business_id', 'pre', 'points'], 'required'],
            [['pay_id', 'vip_id', 'points_type', 'business_id', 'business_grade_id', 'alliance_id', 'pre', 'flag', 'status', 'parent_id', 'source', 'source_id'], 'integer'],
            [['points', 'used_points'], 'number'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pay_id' => '支付ID',
            'vip_id' => '会员ID',
            'points_type' => '代币类型（0：通用,1:商户）',
            'business_id' => '商户ID',
            'business_grade_id' => 'Business Grade ID',
            'alliance_id' => '联盟ID',
            'pre' => '比例',
            'points' => '代币',
            'used_points' => '使用代币',
            'flag' => 'Flag',
            'status' => '状态',
            'parent_id' => '父级',
            'source' => '来源（1：消费，2：赠送，3：到期）',
            'source_id' => 'Source ID',
            'created_at' => '创建日期',
        ];
    }
    
    /**
     * 关联用户
     * @return type
     */
    public function getVip() {
        return $this->hasOne(Vip::className(), ['id' => 'vip_id']);
    }

    /**
     * 关联商户
     * @return type
     */
    public function getBusiness() {
        return $this->hasOne(Business::className(), ['id' => 'business_id']);
    }
    
        /**
     * 关联联盟
     * @return type
     */
    public function getAlliance() {
        return $this->hasOne(Alliance::className(), ['id' => 'alliance_id']);
    }

}
