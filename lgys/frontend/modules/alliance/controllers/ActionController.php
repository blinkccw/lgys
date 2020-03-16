<?php

namespace frontend\modules\alliance\controllers;

use Yii;
use frontend\controllers\BaseVipController;
use common\models\Alliance;
use common\models\AllianceBusiness;

/**
 * 联盟请求
 */
class ActionController extends BaseVipController {

    /**
     * 获取联盟信息
     */
    public function actionGetInfo() {
        $alliance = Alliance::find()->where(['id' => $this->post['id']])->with(['vip'])->asArray()->one();
        if (!$alliance) {
            return $this->errorJson('该联盟已经不存在');
        }
        $alliance['created_at'] = date('Y-m-d', strtotime($alliance['created_at']));
        $business_list = AllianceBusiness::find()
                ->leftJoin('business','alliance_business.business_id=business.id')
                ->where(['alliance_business.alliance_id' => $this->post['id'], 'alliance_business.status' => 1, 'business.status' => 1, 'business.is_audit' => 1])
                ->with(['business'])
                ->orderBy('alliance_business.id desc')
                ->asArray()->all();
        if ($business_list) {
            $business_list = array_column($business_list, 'business');
            foreach ($business_list as $k => $v) {
                if ($business_list[$k]['face_path']) {
                    $business_list[$k]['face_path'] = Yii::$app->params['WEB_URL'] . $business_list[$k]['face_path'];
                }
//                if ($business_list[$k]['imgs']) {
//                    foreach ($business_list[$k]['imgs'] as $k1 => $v1) {
//                        if ($business_list[$k]['imgs'][$k1]['img_path']) {
//                            $business_list[$k]['imgs'][$k1]['img_path'] = Yii::$app->params['WEB_URL'] . $business_list[$k]['imgs'][$k1]['img_path'];
//                        }
//                    }
//                }
            }
        }
        return $this->sucJson(['alliance' => $alliance, 'business_list' => $business_list]);
    }

}
