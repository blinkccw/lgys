<?php

namespace backend\modules\business\models\business;

use Yii;
use common\models\Alliance;
use common\models\AllianceBusiness;
use common\core\BasePageModel;

/**
 * 联盟列表
 */
class AllianceList extends BasePageModel {

    public $id;

    /**
     * @inheritdoc
     */
    public function rules() {
        $rules = [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer']
        ];
        return array_merge($rules, parent::rules());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        $labels = [
            'id' => 'id'
        ];
        return array_merge($labels, parent::attributeLabels());
    }

    /**
     * 列表
     */
    public function getList() {
        $dataPage = AllianceBusiness::find()->where(['business_id'=>$this->id,'status'=>1]);
        $list = $dataPage->with(['alliance'])->orderBy('id desc')
                        ->asArray()->all();
        return $list;
    }

}
