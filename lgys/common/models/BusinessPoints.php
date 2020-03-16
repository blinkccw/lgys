<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "business_points".
 *
 * @property int $id
 * @property int $points_type (1:充值，2：抽成)
 * @property int $business_id 商户ID
 * @property double $points 代币数
 * @property int $flag
 * @property int $pay_id 支付ID
 * @property double $pre 提成比
 * @property double $cur_pre 当前比例
 * @property int $is_dif 是否异盟
 * @property string $created_at
 */
class BusinessPoints extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business_points';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['points_type', 'business_id', 'flag', 'pay_id', 'is_dif'], 'integer'],
            [['business_id', 'points'], 'required'],
            [['points', 'pre', 'cur_pre'], 'number'],
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
            'points_type' => '(1:充值，2：抽成)',
            'business_id' => '商户ID',
            'points' => '代币数',
            'flag' => 'Flag',
            'pay_id' => '支付ID',
            'pre' => '提成比',
            'cur_pre' => '当前比例',
            'is_dif' => '是否异盟',
            'created_at' => 'Created At',
        ];
    }
    
        /**
     * 关联商户
     * @return type
     */
    public function getBusiness() {
        return $this->hasOne(Business::className(), ['id' => 'business_id']);
    }
}
