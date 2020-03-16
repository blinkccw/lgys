<?php

namespace frontend\modules\vip\models;

use Yii;
use common\models\Vip;
use yii\base\Model;

/**
 * 发送短信
 *
 * @author xjx
 */
class SendSms extends Model {

    public $phone;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['phone'], 'trim'],
            [['phone'], 'required'],
            ['phone', 'match', 'pattern' => '/^1[0-9]{10}$/', 'message' => '{attribute}格式不正确。'],
            [['phone'], 'unique', 'targetClass' => Vip::className(), 'message' => '{attribute}已经被注册。'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'phone' => '手机号'
        ];
    }

    /**
     * 发送短信
     */
    public function send() {
       
        return true;
    }

}
