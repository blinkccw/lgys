<?php

namespace frontend\modules\vip\controllers;

use Yii;
use frontend\controllers\BaseVipController;
use common\models\Business;
use common\models\Pay;
use common\models\Notice;

/**
 * 会员操作
 */
class ActionController extends BaseVipController {

    /**
     * 更新会员信息
     * @return type
     */
    public function actionSetVip() {
        $rel = false;
        //   $this->vip->name = $this->post['nickName'];
        if ($this->vip->is_auth == 0) {
            $this->vip->avatar_url = $this->post['avatarUrl'];
            $this->vip->name = $this->post['nickName'];
            $this->vip->nick_name = $this->post['nickName'];
            $this->vip->gender = $this->post['gender'];
            $this->vip->country = $this->post['country'];
            $this->vip->province = $this->post['province'];
            $this->vip->city = $this->post['city'];
            $this->vip->language = $this->post['language'];
            $this->vip->is_auth = 1;
            $this->vip->status = 1;
            $rel = $this->vip->save();
//            if ($rel) {
//                $notice = new Notice;
//                $notice->title = "欢迎您的加入";
//                $notice->vip_id = $this->vip->id;
//                $notice->msg = "欢迎您的加入";
//                $notice->save();
//            }
        } else if ($this->vip->is_auth == 1) {
            $rel = true;
        }
        if ($rel) {
            $user_info['id'] = $this->vip->id;
            $user_info['avatar_url'] = $this->vip->avatar_url;
            $user_info['name'] = $this->vip->name;
            $user_info['nick_name'] = $this->vip->nick_name;
            $user_info['country'] = $this->vip->country;
            $user_info['province'] = $this->vip->province;
            $user_info['city'] = $this->vip->city;
            $user_info['language'] = $this->vip->language;
            $user_info['status'] = $this->vip->status;
            $user_info['is_auth'] = $this->vip->is_auth;
            return $this->sucJson(['userInfo' => $user_info]);
        } else {
            return $this->errorJson();
        }
    }

    /**
     * 获取会员信息
     */
    public function actionGetVip() {
        $data['avatar_url'] = $this->vip->avatar_url;
        $data['vip_no'] = $this->vip->vip_no;
        $data['name'] = $this->vip->name;
        $data['nick_name'] = $this->vip->nick_name;
        $data['total'] = $this->vip->total;
        $data['points'] = $this->vip->points;
        $data['used_points'] = $this->vip->used_points;
        $data['total_points'] = $this->vip->total_points;
        $data['is_business'] = $this->vip->is_business;
        $data['birthday'] = $this->vip->birthday ? date('Y-m-d', strtotime($this->vip->birthday)) : $this->vip->birthday;
        $data['gender'] = $this->vip->gender;
        return $this->sucJson(['userInfo' => $data]);
    }

    /**
     * 更新会员
     */
    public function actionEditVip() {
        if ($this->post['avatar_url']) {
            $this->vip->avatar_url = Yii::$app->params['WEB_URL'] . $this->post['avatar_url'];
        }
        $this->vip->name = $this->post['name'];
        $this->vip->gender = $this->post['gender'];
        $this->vip->birthday = $this->post['birthday'];
        return $this->relJson($this->vip->save());
    }

    /**
     * 发送短信
     */
    public function actionSendSms() {
        $model = new \frontend\modules\vip\models\SendSms;
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->send())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
     * 获取商户列表
     */
    public function actionGetBusinessList() {
        $business_list = Business::find()->where(['vip_id' => $this->vip_id])->orderBy('id')->asArray()->all();
        return $this->sucJson(['business_list' => $business_list]);
    }

    /**
     * 获取商户信息
     */
    public function actionGetBusiness() {
        $business = Business::find()->where(['id' => $this->post['id'], 'vip_id' => $this->vip_id])->with(['grade'])->asArray()->one();
        if (!$business) {
            return $this->errorJson('该商户已经不存在');
        }
        if ($business['face_path']) {
            $business['face_path'] = Yii::$app->params['WEB_URL'] . $business['face_path'];
        }
        return $this->sucJson(['business' => $business]);
    }

    /**
     * 获取联盟代币
     */
    public function actionAlliancePoints() {
        $model = new \frontend\modules\vip\models\AlliancePoints;
        return $this->sucJson(['list' => $model->getList($this->vip_id)]);
    }

    /**
     * 获取代币明细
     */
    public function actionGetPointsList() {
        $model = new \frontend\modules\vip\models\PointsList;
        return $this->sucJson(['list' => $model->getList($this->vip_id)]);
    }

    /**
     * 会员支付页面
     */
    public function actionPayPage() {
        $model = new \frontend\modules\vip\models\PayPage;
        $model->setAttributes($this->post);
        if (!$model->validate())
            return $this->errorModelJson($model);
        $data = $model->page($this->vip_id);
        if ($data === false) {
            return $this->errorModelJson($model);
        }
        return $this->sucJson($data);
    }

    /**
     * 支付
     */
    public function actionPay() {
        $model = new \frontend\modules\vip\models\PayForm;
        $model->setAttributes($this->post);
        if (!$model->validate())
            return $this->errorModelJson($model);
        $rel = $model->save($this->vip);
        if ($rel === FALSE)
            return $this->errorModelJson($model);
        return $this->sucJson($rel);
    }

    public function actionGetPay() {
        $pay = Pay::find()->where(['id' => $this->post['id'], 'vip_id' => $this->vip_id])->select('id,business_id,money,pay,point,used_point')->asArray()->one();
        if (!$pay) {
            return $this->errorJson('支付信息不存在。');
        }
        return $this->sucJson(['pay' => $pay]);
    }

}
