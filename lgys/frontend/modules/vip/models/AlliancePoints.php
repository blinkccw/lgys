<?php

namespace frontend\modules\vip\models;

use Yii;
use common\models\Alliance;
use common\models\VipPoints;
use common\models\AllianceBusiness;
use yii\base\Model;

/**
 * 获取会员联盟代币信息
 *
 * @author xjx
 */
class AlliancePoints extends Model {

    /**
     * 获取代币信息
     */
    public function getList($vip_id) {
        $vip_points = VipPoints::find()->where(['vip_id' => $vip_id])->all();
        $all_business = [];
        $list = [];
        if ($vip_points) {
            foreach ($vip_points as $points) {
                $all_business[] = $points->business_id;
            }
            $alliances = AllianceBusiness::find()->where(['business_id' => $all_business, 'status' => 1])->asArray()->all();
            if ($alliances) {
                $alliance_ids = array_unique(array_column($alliances, 'alliance_id'));
                foreach ($alliance_ids as $alliance_id) {
                    $item['alliance_id'] = $alliance_id;
                    $item['name'] = '无';
                    $item['points'] = 0;
                    $list[] = $item;
                }
                foreach ($alliances as $alliance) {
                    foreach ($vip_points as $vip_point) {
                        if ($alliance['business_id'] == $vip_point->business_id) {
                            foreach ($list as $k => $v) {
                                if ($list[$k]['alliance_id'] == $alliance['alliance_id']) {
                                    $list[$k]['points'] += round($vip_point->points,1);
                                }
                            }
                            break;
                        }
                    }
                }
            }
        }

        if ($list) {
            $alliances = Alliance::find()->where(['id' => array_column($list, 'alliance_id')])->all();
            foreach ($list as $k => $v) {
                foreach ($alliances as $alliance) {
                    if ($list[$k]['alliance_id'] ==$alliance->id) {
                        $list[$k]['name'] = $alliance->name;
                        break;
                    }
                }
            }
        }
        return $list;
    }

}
