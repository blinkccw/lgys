<?php

namespace frontend\modules\vip\controllers;

use Yii;
use frontend\controllers\BaseVipController;
use common\models\Business;
use common\models\BusinessActivity;
use common\models\BusinessImg;

/**
 * 会员的商户信息操作
 */
class BusinessController extends BaseVipController {

    /**
     * 商户管理首页
     */
    public function actionIndex() {
        $model = new \frontend\modules\vip\models\business\Index;
        $model->setAttributes($this->post);
        return $this->sucJson($model->index($this->vip_id));
    }

    /**
     * 商户日志首页
     */
    public function actionLogIndex() {
        $model = new \frontend\modules\vip\models\business\LogIndex;
        $model->setAttributes($this->post);
        return $this->sucJson($model->index($this->vip_id));
    }

    /**
     * 商户消费记录
     * @return type
     */
    public function actionPayList() {
        $model = new \frontend\modules\vip\models\business\PayList;
        $model->setAttributes($this->post);
        return $this->sucJson($model->getList(50));
    }

    /**
     * 商户消费报表
     */
    public function actionPayReport() {
        $model = new \frontend\modules\vip\models\business\PayReport;
        $model->setAttributes($this->post);
        return $this->sucJson($model->getList());
    }

    /**
     * 获取商户信息
     */
    public function actionGetInfo() {
        $business = Business::find()->where(['id' => $this->post['id'], 'vip_id' => $this->vip_id])->with(['foodImgs', 'shopImgs'])->asArray()->one();
        if (!$business) {
            return $this->errorJson('该商户已经不存在');
        }
        if ($business['face_path']) {
            $business['face_path'] = Yii::$app->params['WEB_URL'] . $business['face_path'];
        }
        if ($business['per'] > 0) {
            $business['per'] = round($business['per'], 2);
        }
        if ($business['foodImgs']) {
            foreach ($business['foodImgs'] as $k => $v) {
                $business['foodImgs'][$k]['img_path'] = $business['foodImgs'][$k]['img_path'];
                $business['foodImgs'][$k]['web_img_path'] = Yii::$app->params['WEB_URL'] . $business['foodImgs'][$k]['img_path'];
            }
        }
        if ($business['shopImgs']) {
            foreach ($business['shopImgs'] as $k => $v) {
                $business['shopImgs'][$k]['img_path'] = $business['shopImgs'][$k]['img_path'];
                $business['shopImgs'][$k]['web_img_path'] = Yii::$app->params['WEB_URL'] . $business['shopImgs'][$k]['img_path'];
            }
        }
        $activity = BusinessActivity::find()->where(['business_id' => $this->post['id']])->one();
        if ($activity) {
            $business['activity_title'] = $activity->title;
            $business['activity_msg'] = $activity->msg;
            $business['activity_begin_at'] = date('Y-m-d', strtotime($activity->begin_at));
            $business['activity_end_at'] = date('Y-m-d', strtotime($activity->end_at));
        }
        return $this->sucJson(['business' => $business]);
    }

    /**
     * 更新
     */
    public function actionEdit() {
        $business = Business::find()->where(['id' => $this->post['id'], 'vip_id' => $this->vip_id])->one();
        if (!$business) {
            return $this->errorJson('该商户已经不存在');
        }
        if ($this->post['face_path']) {
            $business->face_path = $this->post['face_path'];
        }
        $business->name = $this->post['name'];
        $business->address = $this->post['address'];
        $business->tel = $this->post['tel'];
        $business->hours = $this->post['hours'];
        $business->exchange_pre = $this->post['exchange_pre'];
        $business->deduction_pre = $this->post['deduction_pre'];
        $business->per = $this->post['per'];
        $rel = $business->save();
        if ($rel) {
            BusinessImg::deleteAll(['business_id' => $business->id]);
            $order_num = 1;
            $shop_img_list = json_decode($this->post['shop_img_list'], true);
            foreach ($shop_img_list as $img) {
                $business_img = new BusinessImg;
                $business_img->business_id = $business->id;
                $business_img->type = 1;
                $business_img->img_path = $img['img_path'];
                $business_img->order_num = $order_num++;
                $business_img->save();
            }
            $order_num = 1;
            $food_img_list = json_decode($this->post['food_img_list'], true);
            foreach ($food_img_list as $img) {
                $business_img = new BusinessImg;
                $business_img->business_id = $business->id;
                $business_img->type = 2;
                $business_img->img_path = $img['img_path'];
                $business_img->order_num = $order_num++;
                $business_img->save();
            }
            BusinessActivity::deleteAll(['business_id' => $business->id]);
            if ($this->post['activity_title']) {
                $activity = new BusinessActivity;
                $activity->business_id = $business->id;
                $activity->title = $this->post['activity_title'];
                $activity->msg = $this->post['activity_msg'];
                $activity->begin_at = $this->post['activity_begin_at'];
                $activity->end_at = $this->post['activity_end_at'];
                $activity->save();
            }
        }
        return $this->relJson($rel);
    }

    /**
     * 消息任务
     */
    public function actionNoticeTaskForm() {
        $model = new \frontend\modules\vip\models\business\NoticeTaskForm;
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save($this->vip_id))
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
     * 我的会员
     * @return type
     */
    public function actionVipList() {
        $model = new \frontend\modules\vip\models\business\VipList;
        $model->setAttributes($this->post);
        return $this->sucJson($model->getList(50));
    }

}
