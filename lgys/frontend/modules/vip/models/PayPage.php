<?php

namespace frontend\modules\vip\models;

use Yii;
use common\models\Business;
use common\models\Config;
use common\models\AllianceBusiness;
use common\models\VipPoints;
use yii\base\Model;

/**
 * 支付页面
 *
 * @author xjx
 */
class PayPage extends Model {

    public $id;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => '商户ID'
        ];
    }

    /**
     * 支付页面
     */
    public function page($vip_id) {
        $business = Business::find()->where(['id' => $this->id])->one();
        if (!$business) {
            $this->addError('save', '商户信息不存在。');
            return FALSE;
        }
        if ($business->status == 0) {
            $this->addError('save', '该商户已经下架。');
            return FALSE;
        }
        $config = Config::find()->one();
        $data['config']['dis_commission'] = $config ? $config->dis_commission : 0;
        $data['business']['id'] = $business->id;
        $data['business']['name'] = $business->name;
        $data['business']['exchange_pre'] = $business->exchange_pre;
        $data['business']['deduction_pre'] = $business->deduction_pre;
        $data['business']['points'] = $business->points;
        $list = [];
        //获取会员在该商户所有联盟的代币数量
        $alliances = AllianceBusiness::find()->where(['business_id' => $this->id, 'status' => 1])->with(['alliance'])->asArray()->all();
        $business_point = null;
        // if (!$alliances) {
        //获取会员在商户的代币数量
        $vip_point = VipPoints::find()->where(['business_id' => $this->id, 'vip_id' => $vip_id, 'points_type' => 1])->one();
        Yii::Info($vip_point);
        if ($vip_point && $vip_point->points > 0) {
            $business_point = ['name' => '商户', 'business_id' => $this->id, 'alliance_id' => 0, 'points' => round($vip_point->points,1), 'pay' => 0, 'used_points' => 0, 'is_dif' => 0];
            // $list[]=$business_point;
        }
        //      }
        $all_alliances = [];

        //获取所有联盟代币
        $alliance_model = new \frontend\modules\vip\models\AlliancePoints;
        $my_alliance = $alliance_model->getList($vip_id);
        $dif_alliance = [];
        if ($my_alliance) {
            foreach ($my_alliance as $k => $v) {
                if ($my_alliance[$k]['points'] > 0) {
                    $my_alliance[$k]['business_id'] = 0;
                    $my_alliance[$k]['pay'] = 0;
                    $my_alliance[$k]['used_points'] = 0;
                    $my_alliance[$k]['is_dif'] = 0;
                    $tag = false;
                    foreach ($alliances as $alliance) {
                        if ($alliance['alliance_id'] == $my_alliance[$k]['alliance_id']) {
                            $all_alliances[] = $my_alliance[$k];
                            $tag = true;
                        }
                    }
                    if (!$tag) {
                        $my_alliance[$k]['is_dif'] = 1;
                        $dif_alliance[] = $my_alliance[$k];
                    }
                }
            }
            //去掉代币相同的
            if ($all_alliances) {
                $new_all_alliances = [];
                foreach ($all_alliances as $tem_alliance) {
                    $tag = false;
                    foreach ($new_all_alliances as $tem_new) {
                        if ($tem_alliance['points'] == $tem_new['points']) {
                            $tag = true;
                            break;
                        }
                    }
                    if (!$tag)
                        $new_all_alliances[] = $tem_alliance;
                }
                $all_alliances = $new_all_alliances;
            }
        }

        //是否有同盟
        if (count($all_alliances) == 0 && $business_point != null) {
            $list[] = $business_point;
        }
//
//        if ($alliances) {
//            $tem_all_alliances = [];
//            foreach ($alliances as $alliance) {
//                $subPage = (new \yii\db\Query())->select('business_id')->from('alliance_business')->where(['alliance_id' => $alliance['alliance_id']])->andWhere('vip_points.business_id=alliance_business.business_id');
//                $points = VipPoints::find()->where(['vip_id' => $vip_id, 'points_type' => 1])->andWhere(['exists', $subPage])->sum('points');
//                Yii::Info($points);
//                if ($points != null && $points > 0) {
//                    $tem_all_alliances[] = ['name' => $alliance['alliance']['name'], 'business_id' => 0, 'alliance_id' => $alliance['alliance_id'], 'points' => $points, 'pay' => 0, 'used_points' => 0];
//                }
//            }
//            foreach ($tem_all_alliances as $tem_alliance) {
//                if ($business_point == null || $tem_alliance['points'] != $business_point['points']) {
//                    $all_alliances[] = $tem_alliance;
//                }
//            }
//        }
//        if (!$all_alliances && $business_point != null) {
//            $list[] = $business_point;
//        }
        //获取会员通用代币数量
        $vip_point = VipPoints::find()->where(['vip_id' => $vip_id, 'points_type' => 0])->one();
        if ($vip_point && $vip_point->points > 0) {
            $list[] = ['name' => '通用', 'business_id' => 0, 'alliance_id' => 0, 'points' => round($vip_point->points,1), 'pay' => 0, 'used_points' => 0, 'is_dif' => 0];
        }
        Yii::Info($all_alliances);
        //没有商户代币，没有同盟代币，没有通用代币显示异盟代币
        if ($all_alliances) {
            $points = array_column($all_alliances, 'points');
            array_multisort($points, SORT_DESC, $all_alliances);
            foreach ($all_alliances as $tem_alliance) {
                $list[] = $tem_alliance;
            }
        }

        if (count($list) == 0 && count($dif_alliance)) {
            $tem_points = array_column($dif_alliance, 'points');
            array_multisort($tem_points, SORT_DESC, $dif_alliance);
            foreach ($dif_alliance as $tem_alliance) {
                $list[] = $tem_alliance;
            }
        }

        $data['list'] = $list;
        return $data;
    }

}
