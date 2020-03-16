<?php

namespace frontend\modules\business\models;

use Yii;
use common\models\VipAggregation;
use common\models\VipPoints;
use yii\base\Model;

/**
 * 聚合信息
 */
class AggregationInfo extends Model {

    public $id;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'id'
        ];
    }

    /**
     * 保存
     */
    public function info($vip_id) {
        $aggregation = VipAggregation::find()
                        ->where(['id' => $this->id])
                        ->with(['vip', 'business', 'business.face', 'mans', 'mans.vip', 'business.lastAlliance', 'business.lastAlliance.alliance'])
                        ->asArray()->one();
        if (!$aggregation) {
            $this->addError('save', '聚合信息已经不存在。');
            return FALSE;
        }
        if (!$aggregation['business']) {
            $this->addError('save', '商户信息已经不存在。');
            return FALSE;
        }
        $aggregation['complete_pre'] = round(($aggregation['complete_points'] / $aggregation['points'])*100);
        if ($aggregation['business']['face']) {
            $aggregation['business']['face']['img_path'] = Yii::$app->params['WEB_URL'] . $aggregation['business']['face']['img_path'];
        }
        $aggregation['begin_at'] = date('Y-m-d H:i', strtotime($aggregation['begin_at']));
        $aggregation['end_at'] = date('Y-m-d H:i', strtotime($aggregation['end_at']));
        $aggregation['time'] = 0;
    
        $end_at = strtotime($aggregation['end_at']);
        if ($end_at > time()) {
            $val = $end_at - time();
            if ($val < 60) {
                $aggregation['time'] = $val . '秒';
            } else if ($val >= 60 && $val < 3600) {
                $aggregation['time'] = floor($val / 60) . '分';
                if ($val % 60 > 0)
                    $aggregation['time'] .= ' ' . ($val % 60) . '秒';
            }else if ($val >= 3600) {
                $aggregation['time'] = floor($val / 3600) . '小时';
                if ($val % 3600 > 0) {
                    $aggregation['time'] .= ' ' . floor(($val % 3600) / 60) . '分';
                    if (($val % 3600) % 60 > 0) {
                        $aggregation['time'] .= ' ' . (($val % 3600) % 60) . '秒';
                    }
                }
            }
        } else if($aggregation['status']==0){
           VipAggregation::updateAll(['status'=>2],['id'=>$aggregation['id']]);
           $aggregation['status']=2;
        }
        $business_points = 0;
        $vip_points = VipPoints::find()->where(['business_id' => $aggregation['business']['id'], 'vip_id' => $vip_id, 'points_type' => 1])->one();
        if($vip_points)
            $business_points=$vip_points->points;
         $aggregation['business_points'] =$business_points;
        return $aggregation;
    }

}
