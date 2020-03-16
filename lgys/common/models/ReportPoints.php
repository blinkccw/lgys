<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "report_points".
 *
 * @property int $id
 * @property int $year
 * @property int $month 月
 * @property int $day 日
 * @property double $exchange_points 发行量
 * @property double $deduction_points 承销量
 * @property string $created_at
 */
class ReportPoints extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'report_points';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['year', 'month', 'day'], 'required'],
            [['year', 'month', 'day'], 'integer'],
            [['exchange_points', 'deduction_points'], 'number'],
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
            'year' => 'Year',
            'month' => '月',
            'day' => '日',
            'exchange_points' => '发行量',
            'deduction_points' => '承销量',
            'created_at' => 'Created At',
        ];
    }
}
