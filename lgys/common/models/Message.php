<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property int $vip_id
 * @property int $business_id
 * @property int $shop_id
 * @property string $title
 * @property string $msg
 * @property int $is_read
 * @property string $readed_at
 * @property string $created_at
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vip_id', 'title', 'msg'], 'required'],
            [['vip_id', 'business_id', 'shop_id', 'is_read'], 'integer'],
            [['msg'], 'string'],
            [['readed_at', 'created_at'], 'safe'],
            [['title'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vip_id' => 'Vip ID',
            'business_id' => 'Business ID',
            'shop_id' => 'Shop ID',
            'title' => 'Title',
            'msg' => 'Msg',
            'is_read' => 'Is Read',
            'readed_at' => 'Readed At',
            'created_at' => 'Created At',
        ];
    }
}
