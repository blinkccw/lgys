<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "config".
 *
 * @property int $id
 * @property string $pay_mch_id
 * @property string $pay_key
 * @property double $dis_commission 异盟抽成
 * @property double $common_commission 平台抽成
 * @property double $same_commission 同等级比例
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dis_commission', 'common_commission'], 'required'],
            [['dis_commission', 'common_commission', 'same_commission'], 'number'],
            [['pay_mch_id', 'pay_key'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pay_mch_id' => 'Pay Mch ID',
            'pay_key' => 'Pay Key',
            'dis_commission' => '异盟抽成',
            'common_commission' => '平台抽成',
            'same_commission' => '同等级比例',
        ];
    }
}
