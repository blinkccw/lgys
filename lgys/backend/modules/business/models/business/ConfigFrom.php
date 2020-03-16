<?php

namespace backend\modules\business\models\business;

use Yii;
use yii\base\Model;
use common\models\Config;

/**
 * 配置表单
 */
class ConfigFrom extends Model {

    public $dis_commission;
    public $common_commission;
    public $same_commission;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dis_commission', 'common_commission','same_commission'], 'trim'],
            [['dis_commission', 'common_commission','same_commission'], 'required'],
            [['dis_commission', 'common_commission','same_commission'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dis_commission' => '异盟多增加抽成比例',
            'common_commission' => '平台抽成比例',
            'same_commission'=>'基础抽成比例'
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
        $config->dis_commission = $this->dis_commission;
        $config->common_commission = $this->common_commission;
        $config->same_commission = $this->same_commission;
        return $config->save();
    }

}
