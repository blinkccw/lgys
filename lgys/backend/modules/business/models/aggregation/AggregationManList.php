<?php

namespace backend\modules\business\models\aggregation;

use Yii;
use common\models\VipAggregationMan;
use common\core\BasePageModel;

/**
 * 聚合参与列表
 */
class AggregationManList extends BasePageModel {

    public $id;

    /**
     * @inheritdoc
     */
    public function rules() {
        $rules = [
            ['id', 'trim'],
            ['id', 'required'],
            ['id', 'integer']
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
    public function getList($page_size = 10) {
        $this->page_size = $page_size;
        $data['vip_aggregation_id'] = $this->id;
        $dataPage = VipAggregationMan::find()->where($data);
        $counts = $dataPage->count();
        $list = $dataPage->with(['vip'])->orderBy('id desc')
                        ->offset(($this->page_index - 1) * $this->page_size)
                        ->limit($this->page_size)
                        ->asArray()->all();
        $rel['list'] = $list;
        $rel['page'] = $this->getPageData($counts);
        return $rel;
    }

}
