<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "alliance".
 *
 * @property int $id
 * @property int $vip_id 创办人
 * @property int $business_id 创建商户
 * @property string $name 名称
 * @property string $info 介绍
 * @property int $status 状态
 * @property int $num 商户数量
 * @property double $exchange_points
 * @property double $deduction_points
 * @property int $is_hot 是否推荐
 * @property string $created_at 创建日期
 */
class Alliance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'alliance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vip_id', 'business_id', 'name'], 'required'],
            [['vip_id', 'business_id', 'status', 'num', 'is_hot'], 'integer'],
            [['info'], 'string'],
            [['exchange_points', 'deduction_points'], 'number'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vip_id' => '创办人',
            'business_id' => '创建商户',
            'name' => '名称',
            'info' => '介绍',
            'status' => '状态',
            'num' => '商户数量',
            'exchange_points' => 'Exchange Points',
            'deduction_points' => 'Deduction Points',
            'is_hot' => '是否推荐',
            'created_at' => '创建日期',
        ];
    }
    
    
    /**
     * 关联创建用户
     * @return type
     */
    public function getVip() {
        return $this->hasOne(Vip::className(), ['id' => 'vip_id'])->select('id,name');
    }

    /**
     * 关联创建商户
     * @return type
     */
    public function getBusiness() {
        return $this->hasOne(Business::className(), ['id' => 'business_id']);
    }

    /**
     * 关联所有商户
     * @return type
     */
    public function getBusinessList() {
        return $this->hasMany(AllianceBusiness::className(), ['alliance_id' => 'id'])->where(['status' => 1])->orderBy('id desc');
    }

}
