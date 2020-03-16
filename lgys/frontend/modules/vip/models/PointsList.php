<?php

namespace frontend\modules\vip\models;

use Yii;
use common\models\VipPointsLog;
use yii\base\Model;

/**
 * 获取会员积分（6个月）
 *
 * @author xjx
 */
class PointsList extends Model {

    /**
     * 获取代币信息
     */
    public function getList($vip_id) {
        $list = [];
        for ($i = 0; $i < 6; $i++) {
            $month = date('Y-m', strtotime('- ' . $i . ' month'));
            $month_name = date('Y年m日', strtotime('- ' . $i . ' month'));
            $item['month'] = $month;
            $item['month_name'] = $month_name;
            $item['logs'] = [];
            $list[] = $item;
        }
        $end_day = date('Y-m-d 23:59:59');
        $begin_day = date('Y-m-1', strtotime('- 6 month'));
        $logs = VipPointsLog::find()
                        ->where(['vip_id' => $vip_id, 'status' => 1])
                        ->andWhere(['between', 'created_at', $begin_day, $end_day])
                        ->with(['business'])->orderBy('id desc')->all();
        if ($logs) {
            foreach ($logs as $log) {
                foreach ($list as $k => $v) {
                    if ($list[$k]['month'] == date('Y-m', strtotime($log['created_at']))) {
                        $tem['id'] = $log['id'];
                        $tem['name'] = '无';
                        if ($log->points_type == 1) {
                            $tem['name'] = $log->business ? $log->business->name : '无';
                        } else if ($log->points_type == 0) {
                            $tem['name'] = '通用';
                        }
                        $tem['source'] = $log['source'];
                        $tem['flag'] = $log['flag'];
                        $tem['points'] = $log['points'];
                        $tem['created_at'] = $log['created_at'];
                        $list[$k]['logs'][] = $tem;
                        break;
                    }
                }
            }
        }

        return $list;
    }

}
