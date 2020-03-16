<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vip_points".
 *
 * @property int $id
 * @property int $vip_id 会吊ID
 * @property int $points_type 代币类型(0:通用，1:商户)
 * @property int $business_id 商户ID
 * @property double $points 剩余代币数
 * @property string $pay_at 最后消费日期
 * @property string $created_at 日期
 */
class VipPoints extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'vip_points';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['vip_id', 'business_id', 'points'], 'required'],
            [['vip_id', 'points_type', 'business_id'], 'integer'],
            [['points'], 'number'],
            [['pay_at', 'created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'vip_id' => '会吊ID',
            'points_type' => '代币类型(0:通用，1:商户)',
            'business_id' => '商户ID',
            'points' => '剩余代币数',
            'pay_at' => '最后消费日期',
            'created_at' => '日期',
        ];
    }

    /**
     * 关联Vip
     * @return type
     */
    public function getVip() {
        return $this->hasOne(Vip::className(), ['id' => 'vip_id'])->select('id,vip_no,name,avatar_url,nick_name');
    }

}
