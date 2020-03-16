<?php

namespace frontend\modules\vip\controllers;

use Yii;
use frontend\controllers\BaseVipController;

/**
 * 会员的联盟信息操作
 */
class AllianceController extends BaseVipController {

    /**
     * 联盟管理首页
     */
    public function actionIndex() {
        $model = new \frontend\modules\vip\models\alliance\Index;
        $model->setAttributes($this->post);
        return $this->sucJson($model->index($this->vip_id));
    }

    /**
     * 获取联盟列表
     * @return type
     */
    public function actionList() {
        $model = new \frontend\modules\vip\models\alliance\AllianceList;
        $model->setAttributes($this->post);
        return $this->sucJson($model->getList(100));
    }

    /**
     * 获取商户列表（不包含在联盟中的）
     */
    public function actionBusinessList() {
        $model = new \frontend\modules\vip\models\alliance\BusinessList;
        $model->setAttributes($this->post);
        return $this->sucJson($model->getList(30));
    }

    /**
      联盟操作
     */
    public function actionAllianceForm() {
        $model = new \frontend\modules\vip\models\alliance\AllianceForm;
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save($this->vip_id))
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
      联盟邀请商户
     */
    public function actionInviteBusiness() {
        $model = new \frontend\modules\vip\models\alliance\InviteBusiness;
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save($this->vip_id))
            return $this->errorModelJson($model);
        return $this->sucJson();
    }
    
      /**
      删除联盟
     */
    public function actionDeleteAlliance() {
        $model = new \frontend\modules\vip\models\alliance\DeleteAlliance;
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save($this->vip_id))
            return $this->errorModelJson($model);
        return $this->sucJson();
    }
    
    /**
      联盟商户状态
     */
    public function actionBusinessStatus() {
        $model = new \frontend\modules\vip\models\alliance\BusinessStatus;
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

}
