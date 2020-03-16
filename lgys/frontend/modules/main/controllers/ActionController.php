<?php

namespace frontend\modules\main\controllers;

use Yii;
use frontend\controllers\BaseVipController;
use common\models\Business;
use common\models\AllianceBusiness;
use common\models\Alliance;
use common\models\Notice;
use common\models\VipPoints;

/**
 * 页面信息
 */
class ActionController extends BaseVipController {

    /**
     * 首页信息
     * @return string
     */
    public function actionIndex() {
        $latitude = isset($this->post['latitude']) ? $this->post['latitude'] : 0;
        $longitude = isset($this->post['longitude']) ? $this->post['longitude'] : 0;
        $data['vip']['points'] = $this->vip->points;
        $data['vip']['avatar_url'] = $this->vip->avatar_url;
        $data['vip']['name'] = $this->vip->name;
        $data['vip']['vip_no'] = $this->vip->vip_no;
        $data['vip']['is_business'] = $this->vip->is_business;
//        $business_list = [];
//        $where_data['status'] = 1;
//        $where_data['is_audit'] = 1;
//        $where_data['is_hot'] = 1;
//        if ($latitude == 0 && $longitude == 0) {
//            $business_list = Business::find()->where($where_data)->with(['imgs'])->orderBy('id desc')->limit(50)->asArray()->all();
//        } else {
//            $order_by = "distance asc";
//            $business_list = Business::find()->where($where_data)->select('*,ACOS(SIN((' . $latitude . ' * 3.1415) / 180 ) *SIN((latitude * 3.1415) / 180 ) +COS((' . $latitude . ' * 3.1415) / 180 ) * COS((latitude * 3.1415) / 180 ) *COS((' . $longitude . ' * 3.1415) / 180 - (longitude * 3.1415) / 180 ) ) * 6380 as distance')->with(['imgs'])->orderBy($order_by)->limit(50)->asArray()->all();
//        }
//        foreach ($business_list as $k => $v) {
//            if ($business_list[$k]['face_path']) {
//                $business_list[$k]['face_path'] = Yii::$app->params['WEB_URL'] . $business_list[$k]['face_path'];
//            }
//            if ($business_list[$k]['imgs']) {
//                foreach ($business_list[$k]['imgs'] as $k1 => $v1) {
//                    if ($business_list[$k]['imgs'][$k1]['img_path']) {
//                        $business_list[$k]['imgs'][$k1]['img_path'] = Yii::$app->params['WEB_URL'] . $business_list[$k]['imgs'][$k1]['img_path'];
//                    }
//                }
//            }
//        }
//        $data['business_list'] = $business_list;
        $data['my_alliance_list'] = [];
        $data['alliance_list'] = [];
        $max_business = VipPoints::find()
                        ->leftJoin('alliance_business', 'vip_points.business_id=alliance_business.business_id')
                        ->leftJoin('business', 'vip_points.business_id=business.id')
                        ->where(['vip_points.vip_id' => $this->vip->id])
                        ->andWhere(['alliance_business.business_id' => null])
                        ->orderBy('vip_points.points desc')
                        ->select('vip_points.points,business.name')->asArray()->one();
        $max_points = 0;
        $max_name = '';
        if ($max_business) {
            $max_points = $max_business['points'];
            $max_name = $max_business['name'];
        }
        //我的联盟
        $alliance_model = new \frontend\modules\vip\models\AlliancePoints;
        $my_alliance = $alliance_model->getList($this->vip->id);
        if ($my_alliance) {
            $all_id = array_column($my_alliance, 'alliance_id');
            $business_nums = AllianceBusiness::find()->where(['alliance_id' => $all_id])->groupBy('alliance_id')->select('alliance_id,count(id) as num')->asArray()->all();
            foreach ($my_alliance as $k => $v) {
                $my_alliance[$k]['num'] = 0;
                $my_alliance[$k]['id'] = $my_alliance[$k]['alliance_id'];
                if ($max_points <= $my_alliance[$k]['points']) {
                    $max_points = $my_alliance[$k]['points'];
                    $max_name = $my_alliance[$k]['name'];
                }
                foreach ($business_nums as $tem_num) {
                    if ($my_alliance[$k]['alliance_id'] == $tem_num['alliance_id']) {
                        $my_alliance[$k]['num'] = $tem_num['num'];
                        break;
                    }
                }
            }
            $data['my_alliance_list'] = $my_alliance;
        } else {
            //推荐联盟
            $alliance_list = Alliance::find()->where(['is_hot' => 1])->asArray()->all();
            if ($alliance_list) {
                $all_id = array_column($alliance_list, 'id');
                $alliance_points = VipPoints::find()
                        ->leftJoin('alliance_business', 'vip_points.business_id=alliance_business.business_id')
                        ->where(['alliance_business.alliance_id' => $all_id])
                        ->andWhere(['vip_points.vip_id' => $this->vip->id])
                        ->select('alliance_business.alliance_id,sum(vip_points.points) alliance_points')
                        ->groupBy('alliance_business.alliance_id')
                        ->asArray()
                        ->all();
                foreach ($alliance_list as $k => $v) {
                    $alliance_list[$k]['alliance_points'] = 0;
                    foreach ($alliance_points as $tem) {
                        if ($alliance_list[$k]['id'] == $tem['alliance_id']) {
                            $alliance_list[$k]['alliance_points'] = $tem['alliance_points'];
                            break;
                        }
                    }
                }
            }
            $data['alliance_list'] = $alliance_list;
        }

        $data['vip']['max_points'] = $max_points;
        $data['vip']['max_name'] = $max_name;
        //最新消息列表
        $notice_list = Notice::find()
                        ->where(['vip_id' => $this->vip->id])
                        ->andWhere(['>', 'business_id', 0])
                        ->with(['business', 'business.face'])
                        ->limit(10)
                        ->orderBy('id desc')
                        ->asArray()->all();
        foreach ($notice_list as $k => $v) {
            $notice_list[$k]['created_at'] = date('Y/m/d H:i', strtotime($notice_list[$k]['created_at']));
            if ($notice_list[$k]['business']['face_path']) {
                $notice_list[$k]['business']['face_path'] = Yii::$app->params['WEB_URL'] . $notice_list[$k]['business']['face_path'];
            }
            if ($notice_list[$k]['face_path']) {
                $notice_list[$k]['face_path'] = Yii::$app->params['WEB_URL'] . $notice_list[$k]['face_path'];
            }
        }
        $data['notice_list'] = $notice_list;

        $sys_notice_list = Notice::find()
                        ->where(['vip_id' => $this->vip->id, 'business_id' => 0, 'is_read' => 0])
                        ->limit(50)
                        ->orderBy('id desc')
                        ->asArray()->all();
        foreach ($sys_notice_list as $k => $v) {
            $sys_notice_list[$k]['created_at'] = date('Y/m/d H:i', strtotime($sys_notice_list[$k]['created_at']));
        }
        $data['sys_notice_list'] = $sys_notice_list;
        return $this->sucJson($data);
    }

}
