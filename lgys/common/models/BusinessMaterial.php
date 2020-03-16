<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "business_material".
 *
 * @property int $id
 * @property int $business_id
 * @property string $license_path
 * @property string $email
 * @property string $bank_card
 * @property string $bank_add
 * @property string $card_path1
 * @property string $card_path2
 * @property string $food_license_path
 */
class BusinessMaterial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business_material';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_id'], 'required'],
            [['business_id'], 'integer'],
            [['license_path', 'card_path1', 'card_path2', 'food_license_path'], 'string', 'max' => 50],
            [['email', 'bank_add'], 'string', 'max' => 200],
            [['bank_card'], 'string', 'max' => 100],
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
            'license_path' => 'License Path',
            'email' => 'Email',
            'bank_card' => 'Bank Card',
            'bank_add' => 'Bank Add',
            'card_path1' => 'Card Path1',
            'card_path2' => 'Card Path2',
            'food_license_path' => 'Food License Path',
        ];
    }
}
