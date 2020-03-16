<?php

namespace frontend\modules\business\controllers;

use Yii;
use frontend\controllers\BaseVipController;
use common\models\BusinessSort;
use common\models\VipAggregation;
use common\models\VipAggregationMan;

/**
 * 商户请求
 */
class ActionController extends BaseVipController {

    public function actionDoBusiness() {
        $model = new \frontend\modules\business\models\BusinessForm();
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save($this->vip_id))
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
     * 获取商户所有分类
     * @return type
     */
    public function actionGetSorts() {
        $sorts = BusinessSort::find()->orderBy('order_num,id')->asArray()->all();
        return $this->sucJson(['sorts' => $sorts]);
    }

    /**
     * 获取商户信息列表
     */
    public function actionGetList() {
        $model = new \frontend\modules\business\models\BusinessList;
        $model->setAttributes($this->post);
        $rel = $model->getList($this->vip->id,20);
        return $this->sucJson($rel);
    }

    /**
     * 获取商户信息
     */
    public function actionGetInfo() {
        $model = new \frontend\modules\business\models\BusinessInfo();
        $model->setAttributes($this->post);
        if (!$model->validate())
            return $this->errorModelJson($model);
        $rel = $model->info();
        if ($rel === false)
            return $this->errorModelJson($model);
        return $this->sucJson(['info' => $rel]);
    }

    /**
     * 创建聚合
     */
    public function actionAddAggregation() {
        $model = new \frontend\modules\business\models\AggregationForm;
        $model->setAttributes($this->post);
        if (!$model->validate())
            return $this->errorModelJson($model);
        $rel = $model->save($this->vip_id);
        if ($rel === false)
            return $this->errorModelJson($model);
        return $this->sucJson(['id' => $rel]);
    }

    /**
     * 获取聚合
     */
    public function actionAggregation() {
        $model = new \frontend\modules\business\models\AggregationInfo();
        $model->setAttributes($this->post);
        if (!$model->validate())
            return $this->errorModelJson($model);
        $rel = $model->info($this->vip_id);
        if ($rel === false)
            return $this->errorModelJson($model);
        return $this->sucJson(['info' => $rel]);
    }

    /**
     * 参与聚合
     */
    public function actionAddAggregationMan() {
        $model = new \frontend\modules\business\models\AggregationManForm;
        $model->setAttributes($this->post);
        if (!$model->validate()||!$model->save($this->vip_id))
            return $this->errorModelJson($model);
        return $this->sucJson();
    }
    
    /**
     * 生成聚合海报
     */
    public function actionCreateAggregationPoster(){
        $model = new \frontend\modules\business\models\CreateAggregationPoster;
        $model->setAttributes($this->post);
        if (!$model->validate())
            return $this->errorModelJson($model);
        $rel=$model->create();
         if ($rel === false)
            return $this->errorModelJson($model);
        return $this->sucJson(['url'=>$rel]);
    }

}
