<?php

namespace frontend\modules\business\models;

use Yii;
use common\models\VipAggregation;
use common\models\VipPoints;
use common\models\VipAggregationMan;
use common\models\VipPointsLog;
use common\models\VipPointsUsed;
use common\models\BisBusiness;
use yii\base\Model;

/**
 * 参与聚合表单
 */
class AggregationManForm extends Model {

    public $id;
    public $points;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer'],
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
            'id' => '聚合id',
            'points' => '聚合代币总数'
        ];
    }

    /**
     * 保存
     */
    public function save($vip_id) {
        $aggregation = VipAggregation::find()->where(['id' => $this->id])->with(['business'])->one();
        if (!$aggregation) {
            $this->addError('save', '聚合信息已经不存在。');
            return FALSE;
        }
        if (!$aggregation->business) {
            $this->addError('save', '商户信息已经不存在。');
            return FALSE;
        }
        if ($aggregation->status != 0) {
            $this->addError('save', '聚合活动已经结束。');
            return FALSE;
        }
        if (strtotime($aggregation->end_at) < time()) {
            $this->addError('save', '聚合活动已经结束。');
            return FALSE;
        }
        if ($aggregation->vip_id == $vip_id) {
            $this->addError('save', '不能参与自己发起的聚合活动。');
            return FALSE;
        }
        $man = VipAggregationMan::find()->where(['vip_aggregation_id' => $aggregation->id, 'vip_id' => $vip_id])->one();
        if ($man) {
            $this->addError('save', '您已经参与过该聚合活动。');
            return FALSE;
        }
        $vip_points = VipPoints::find()->where(['business_id' => $aggregation->business->id, 'vip_id' => $vip_id])->one();
        if (!$vip_points) {
            $this->addError('save', '您在该商户已经没有剩余代币。');
            return FALSE;
        }
        if ($vip_points->points < $this->points) {
            $this->addError('save', '您输入的代币已经超过您在该商户的剩余代币。');
            return FALSE;
        }
        if ($this->points > ($aggregation->points - $aggregation->complete_points)) {
            $this->addError('save', '您输入的代币已经超过聚合剩余数。');
            return FALSE;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $vip_points->points -= $this->points;
            if ($vip_points->points < 0)
                $vip_points->points = 0;
            if (!$vip_points->save())
                return false;
            //记录
            $man = new VipAggregationMan;
            $man->vip_aggregation_id = $aggregation->id;
            $man->vip_id = $vip_id;
            $man->points = $this->points;
            $man->is_return = 0;
            if (!$man->save())
                return false;
            if (!$this->businessPoints($vip_id, $aggregation)) {
                return false;
            }
            $is_suc = false;
            $aggregation->complete_points += $this->points;
            if ($aggregation->complete_points == $aggregation->points) {
                $aggregation->status = 1;
                $aggregation->suc_at = date('Y-m-d H:i:s');
                $is_suc = true;
            }
            if (!$aggregation->save())
                return false;
            if ($is_suc) {
                if (!$this->doSuccess($aggregation))
                    return false;
            }
            $transaction->commit();
            return true;
        } catch (Exception $ex) {
            
        }
        return false;
    }

    /**
     * 聚合代币计算
     * @param type $pay
     * @param type $vip_point
     */
    private function businessPoints($vip_id, $aggregation) {
        $log = new VipPointsLog;
        $log->pay_id = 0;
        $log->business_id = $aggregation->business->id;
        $log->alliance_id = 0;
        $log->vip_id = $vip_id;
        $log->points_type = 1;
        $log->pre = 0;
        $log->points = $this->points;
        $log->used_points = 0;
        $log->flag = -1;
        $log->status = 1;
        $log->source = 4;
        $log->source_id = $aggregation->id;
        if (!$log->save())
            return FALSE;
        $used_point = $this->points;
        $logs = VipPointsLog::find()
                        ->where(['vip_id' => $vip_id, 'points_type' => 1, 'business_id' => $aggregation->business->id, 'flag' => 1, 'status' => 1])
                        ->andWhere('points>used_points')
                        ->orderBy('id')->all();
        foreach ($logs as $tem_log) {
            if ($used_point == 0)
                break;
            $last_points = $tem_log->points - $tem_log->used_points;
            $tem_used = 0;
            if ($used_point > $last_points) {
                $used_point -= $last_points;
                $tem_used = $last_points;
                $tem_log->used_points += $last_points;
            } else if ($used_point <= $last_points) {
                $tem_log->used_points += $used_point;
                $tem_used = $used_point;
                $used_point = 0;
            }
            if (!$tem_log->save())
                return false;
            $used_log = new VipPointsUsed;
            $used_log->log_id = $tem_log->id;
            $used_log->used_log_id = $log->id;
            $used_log->used_business_id = $log->business_id;
            $used_log->used_points = $tem_used;
            if (!$used_log->save())
                return false;
        }
        return true;
    }

    /**
     * 聚合成功处理
     * @param type $vip_id
     * @param type $aggregation
     */
    private function doSuccess($aggregation) {
        $log = new VipPointsLog;
        $log->business_id = $aggregation->business->id;
        $log->pay_id = 0;
        $log->alliance_id = 0;
        $log->vip_id = $aggregation->vip_id;
        $log->points_type = 1;
        $log->pre = 0;
        $log->points = $aggregation->points;
        $log->used_points = 0;
        $log->flag = 1;
        $log->status = 1;
        $log->source = 4;
        $log->source_id = $aggregation->id;
        if (!$log->save())
            return FALSE;
        $is_new = false;
        $vip_points = VipPoints::find()->where(['business_id' => $aggregation->business->id, 'vip_id' => $aggregation->vip_id])->one();
        if (!$vip_points) {
            $vip_points = new VipPoints;
            $vip_points->points_type = 1;
            $vip_points->vip_id = $aggregation->vip_id;
            $vip_points->business_id = $aggregation->business->id;
            $vip_points->points = 0;
            $is_new = true;
        }
        $vip_points->points += $aggregation->points;
        if (!$vip_points->save())
            return FALSE;
        if ($is_new) {
            //是否升级
            $bis_business = new BisBusiness;
            $bis_business->upgradeGrade($aggregation->business);
        }
        return true;
    }

}
