<?php

namespace backend\modules\setting\models\setting;

use Yii;
use yii\base\Model;
use common\models\Config;

/**
 * 配置表单
 */
class ConfigFrom extends Model {

    public $pay_mch_id;
    public $pay_key;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['pay_mch_id', 'pay_key'], 'trim'],
            [['pay_mch_id', 'pay_key'], 'required'],
            [['pay_mch_id', 'pay_key'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'pay_mch_id' => '商户号(mch_id)',
            'pay_key' => 'API密钥(key)'
        ];
    }

    /**
     * 保存
     */
    public function save() {
        $config = Config::findOne(['id' => 1]);
        if (!$config) {
            $this->addError('save', '配置信息已经不存在。');
            return;
        }
        $config->pay_mch_id=$this->pay_mch_id;
        $config->pay_key=$this->pay_key;
        return $config->save();
    }

}
