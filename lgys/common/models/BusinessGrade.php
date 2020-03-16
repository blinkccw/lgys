<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "business_grade".
 *
 * @property int $id
 * @property string $name
 * @property int $vip_num
 * @property double $commission 抽成
 * @property string $created_at
 */
class BusinessGrade extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business_grade';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'commission'], 'required'],
            [['vip_num'], 'integer'],
            [['commission'], 'number'],
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
            'vip_num' => 'Vip Num',
            'commission' => '抽成',
            'created_at' => 'Created At',
        ];
    }
}
