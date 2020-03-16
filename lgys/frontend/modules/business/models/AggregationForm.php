<?php

namespace frontend\modules\business\models;

use Yii;
use common\models\VipAggregation;
use yii\base\Model;

/**
 * 聚合表单
 */
class AggregationForm extends Model {

    public $business_id;
    public $points;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['business_id'], 'trim'],
            [['business_id'], 'required'],
            [['business_id'], 'integer'],
            [['points'], 'trim'],
            [['points'], 'required'],
            [['points'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'business_id' => '商户id',
            'points' => '聚合代币总数'
        ];
    }

    /**
     * 保存
     */
    public function save($vip_id) {
        $aggregation = new VipAggregation;
        $aggregation->business_id = $this->business_id;
        $aggregation->vip_id = $vip_id;
        $begin_at = date('Y-m-d H:i:s');
        $aggregation->begin_at = $begin_at;
        $aggregation->end_at = date('Y-m-d H:i:s', strtotime('+ 3 days',strtotime($begin_at)));
        $aggregation->points = $this->points;
        $rel= $aggregation->save();
        if($rel)
            return $aggregation->id;
        return false;
    }

}
