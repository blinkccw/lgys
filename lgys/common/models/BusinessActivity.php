<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "business_activity".
 *
 * @property int $id
 * @property int $business_id
 * @property string $title
 * @property string $msg
 * @property string $begin_at
 * @property string $end_at
 * @property string $created_at
 */
class BusinessActivity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business_activity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_id', 'title', 'msg'], 'required'],
            [['business_id'], 'integer'],
            [['begin_at', 'end_at', 'created_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['msg'], 'string', 'max' => 1000],
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
            'title' => 'Title',
            'msg' => 'Msg',
            'begin_at' => 'Begin At',
            'end_at' => 'End At',
            'created_at' => 'Created At',
        ];
    }
}
