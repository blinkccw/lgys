<?php

namespace backend\modules\alliance\models\alliance;

use Yii;
use common\models\Alliance;
use yii\base\Model;

/**
 * 联盟表单
 */
class AllianceForm extends Model {

    public $id;
    public $name;
    public $info;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer'],
            [['name'], 'trim'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['info'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'id',
            'name' => '名称',
            'info' => '介绍'
        ];
    }

    /**
     * 保存
     */
    public function save() {
        $alliance = Alliance::find()->where(['id' => $this->id])->one();
        if (!$alliance) {
            $this->addError('save', '联盟信息不存在。');
            return FALSE;
        }
        $alliance->name = $this->name;
        $alliance->info = $this->info;
        return $alliance->save();
    }

}
