<?php

namespace backend\modules\business\models\aggregation;

use Yii;
use common\models\VipAggregation;
use common\core\BasePageModel;

/**
 * 聚合列表
 */
class AggregationList extends BasePageModel {

    public $key;
    public $vip_key;
    public $status;

    /**
     * @inheritdoc
     */
    public function rules() {
        $rules = [
            ['key', 'trim'],
            ['key', 'string'],
            ['vip_key', 'trim'],
            ['vip_key', 'string'],
            ['status', 'trim'],
            ['status', 'integer']
        ];
        return array_merge($rules, parent::rules());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        $labels = [
            'key' => '关键字',
            'vip_key' => '关键字',
            'status' => '状态'
        ];
        return array_merge($labels, parent::attributeLabels());
    }

    /**
     * 列表
     */
    public function getList($page_size = 10) {
        $this->page_size = $page_size;
        $data = [];
        if($this->status!=null&&$this->status>=0)
            $data['vip_aggregation.status']=$this->status;
        $dataPage = VipAggregation::find()->where($data);
        if ($this->key != '') {
            $dataPage->leftJoin('business','vip_aggregation.business_id=business.id')->andWhere(['like', 'business.name', $this->key]);
        }
        if ($this->vip_key != '') {
            $dataPage->leftJoin('vip','vip_aggregation.vip_id=vip.id')->andWhere(['or', ['like', 'vip.name', $this->vip_key], ['like', 'vip.nick_name', $this->vip_key],['like', 'vip.vip_no', $this->vip_key]]);
        }
        $counts = $dataPage->count();
        $list = $dataPage->with(['vip','business'])->orderBy('id desc')
                        ->offset(($this->page_index - 1) * $this->page_size)
                        ->limit($this->page_size)
                        ->asArray()->all();
        $rel['list'] = $list;
        $rel['page'] = $this->getPageData($counts);
        return $rel;
    }

}
