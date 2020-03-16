<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "business_sort".
 *
 * @property int $id
 * @property string $name
 * @property int $order_num
 * @property string $created_at
 */
class BusinessSort extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business_sort';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['order_num'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'order_num' => 'Order Num',
            'created_at' => 'Created At',
        ];
    }
}
