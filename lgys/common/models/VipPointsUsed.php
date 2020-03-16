<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vip_points_used".
 *
 * @property int $id
 * @property int $log_id
 * @property int $used_log_id
 * @property int $used_business_id
 * @property double $used_points
 * @property string $created_at
 */
class VipPointsUsed extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vip_points_used';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['log_id', 'used_log_id', 'used_business_id'], 'required'],
            [['log_id', 'used_log_id', 'used_business_id'], 'integer'],
            [['used_points'], 'number'],
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
            'log_id' => 'Log ID',
            'used_log_id' => 'Used Log ID',
            'used_business_id' => 'Used Business ID',
            'used_points' => 'Used Points',
            'created_at' => 'Created At',
        ];
    }
}
