<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vip_aggregation".
 *
 * @property int $id
 * @property int $vip_id 会员ID
 * @property int $business_id 商户ID
 * @property int $points 积分
 * @property int $complete_points 完成聚合
 * @property int $status 状态
 * @property string $begin_at 开始日期
 * @property string $end_at 结束日期
 * @property string $created_at 创建日期
 */
class VipAggregation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vip_aggregation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vip_id', 'business_id', 'points'], 'required'],
            [['vip_id', 'business_id', 'points', 'complete_points', 'status'], 'integer'],
            [['begin_at', 'end_at', 'created_at','suc_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vip_id' => '会员ID',
            'business_id' => '商户ID',
            'points' => '积分',
            'complete_points' => '完成聚合',
            'status' => '状态',
            'begin_at' => '开始日期',
            'end_at' => '结束日期',
            'suc_at'=>'成功日期',
            'created_at' => '创建日期',
        ];
    }
    
    
    /**
     * 关联创建用户
     * @return type
     */
    public function getVip() {
        return $this->hasOne(Vip::className(), ['id' => 'vip_id'])->select('id,name,nick_name');
    }

    /**
     * 关联创建商户
     * @return type
     */
    public function getBusiness() {
        return $this->hasOne(Business::className(), ['id' => 'business_id']);
    }
    
        /**
     * 关联用户
     * @return type
     */
    public function getMans() {
        return $this->hasMany(VipAggregationMan::className(), ['vip_aggregation_id' => 'id'])->orderBy('id desc');
    }
}
