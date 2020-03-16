<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vip_aggregation_man".
 *
 * @property int $id
 * @property int $vip_aggregation_id 聚合ID
 * @property int $vip_id 会员ID
 * @property int $points 积分
 * @property int $is_return 是否退还
 * @property string $created_at 创建日期
 */
class VipAggregationMan extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'vip_aggregation_man';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['vip_aggregation_id', 'vip_id', 'points'], 'required'],
            [['vip_aggregation_id', 'vip_id', 'points', 'is_return'], 'integer'],
            [['created_at', 'return_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'vip_aggregation_id' => '聚合ID',
            'vip_id' => '会员ID',
            'points' => '积分',
            'is_return' => '是否退还',
            'return_at' => '退否日期',
            'created_at' => '创建日期',
        ];
    }

    /**
     * 关联用户
     * @return type
     */
    public function getVip() {
        return $this->hasOne(Vip::className(), ['id' => 'vip_id'])->select('id,name,nick_name,avatar_url');
    }

    /**
     * 关联聚合
     * @return type
     */
    public function getAggregation() {
        return $this->hasOne(VipAggregation::className(), ['id' => 'vip_aggregation_id']);
    }

}
