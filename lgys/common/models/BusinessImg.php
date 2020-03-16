<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "business_img".
 *
 * @property int $id
 * @property int $business_id
 * @property string $img_path
 * @property int $order_num
 * @property string $created_at
 */
class BusinessImg extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business_img';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_id', 'img_path'], 'required'],
            [['business_id', 'order_num','type'], 'integer'],
            [['created_at'], 'safe'],
            [['img_path'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'business_id' => 'Business ID',
            'img_path' => 'Img Path',
            'type'=>'类型',
            'order_num' => 'Order Num',
            'created_at' => 'Created At',
        ];
    }
}
