<?php

namespace backend\modules\alliance\models\alliance;

use Yii;
use common\models\AllianceBusiness;
use common\core\BasePageModel;

/**
 * 商户列表
 */
class BusinessList extends BasePageModel {

    public $id;
    public $key;

    /**
     * @inheritdoc
     */
    public function rules() {
        $rules = [
            ['id', 'trim'],
            ['id', 'required'],
            ['id', 'integer'],
            ['key', 'trim'],
            ['key', 'string']
        ];
        return array_merge($rules, parent::rules());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        $labels = [
            'id' => '联盟ID',
            'key' => '关键字'
        ];
        return array_merge($labels, parent::attributeLabels());
    }

    /**
     * 列表
     */
    public function getList($page_size = 10) {
        $this->page_size = $page_size;
        $data['alliance_business.alliance_id'] = $this->id;
        $data['alliance_business.status'] = 1;
        $dataPage = AllianceBusiness::find()->where($data);
        if ($this->key != '') {
            $dataPage->leftJoin('business', 'alliance_business.business_id=business.id')->andWhere(['like', 'business.name', $this->key]);
        }
        $counts = $dataPage->count();
        $list = $dataPage->with(['business'])->orderBy('alliance_business.id desc')
                        ->offset(($this->page_index - 1) * $this->page_size)
                        ->limit($this->page_size)
                        ->asArray()->all();
        $rel['list'] = $list;
        $rel['page'] = $this->getPageData($counts);
        return $rel;
    }

}
