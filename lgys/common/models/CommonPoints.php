<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "common_points".
 *
 * @property int $id
 * @property double $points
 */
class CommonPoints extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'common_points';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['points'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'points' => 'Points',
        ];
    }
}
