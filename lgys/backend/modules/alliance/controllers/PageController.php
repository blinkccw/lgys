<?php

namespace backend\modules\alliance\controllers;

use backend\controllers\BaseController;
use yii\web\HttpException;
use common\models\Alliance;
use common\models\AllianceBusiness;

/**
 * 联盟页面
 */
class PageController extends BaseController {

    /**
     * 联盟
     * @return string
     */
    public function actionIndex() {
        return $this->renderAjax('index');
    }

    /**
     * 联盟列表页面
     * @return type
     */
    public function actionAllianceList() {
        $model = new \backend\modules\alliance\models\alliance\AllianceList;
        $model->setAttributes($this->post);
        $rel = $model->getList();
        return $this->renderAjax('alliance_list', $rel);
    }
    
    
    /**
     * 联盟操作表单
     * @return type
     */
    public function actionAllianceForm() {
        $id = $this->post['id'];
        $alliance = new Alliance;
        if ($id > 0) {
            $alliance = Alliance::find()->where(['id' => $id])->asArray()->one();
            if (!$alliance)
                throw new HttpException(404, '信息已经不存在。');
        }
        return $this->renderAjax('alliance_form', ['id' => $id, 'model' => $alliance]);
    }

    /**
     * 联盟商户页面
     */
    public function actionBusiness() {
        $id = $this->post['id'];
        $alliance = Alliance::find()->where(['id' => $id])->asArray()->one();
        if (!$alliance)
            throw new HttpException(404, '联盟已经不存在。');
        return $this->renderAjax('business',['id' => $id, 'model' => $alliance]);
    }
    
        /**
     * 联盟商户列表页面
     * @return type
     */
    public function actionBusinessList() {
        $model = new \backend\modules\alliance\models\alliance\BusinessList;
        $model->setAttributes($this->post);
        $rel = $model->getList();
        return $this->renderAjax('business_list', $rel);
    }

    /**
     * 联盟发行记录
     */
    public function actionExchangeLog() {
        $id = $this->post['id'];
        $alliance = Alliance::find()->where(['id' => $id])->asArray()->one();
        if (!$alliance)
            throw new HttpException(404, '联盟已经不存在。');
        $all_business = AllianceBusiness::find()->where(['alliance_id' => $id, 'status' => 1])->with(['business'])->asArray()->all();
        return $this->renderAjax('exchange_log', ['id' => $id, 'model' => $alliance, 'all_business' => $all_business]);
    }

    /**
     * 联盟发行记录列表页面
     * @return type
     */
    public function actionExchangeLogList() {
        $model = new \backend\modules\alliance\models\log\LogList;
        $this->post['flag'] = -1;
        $model->setAttributes($this->post);
        $rel = $model->getList();
        return $this->renderAjax('exchange_log_list', $rel);
    }

    /**
     * 联盟承销记录
     */
    public function actionDeductionLog() {
        $id = $this->post['id'];
        $alliance = Alliance::find()->where(['id' => $id])->asArray()->one();
        if (!$alliance)
            throw new HttpException(404, '联盟已经不存在。');
        $all_business = AllianceBusiness::find()->where(['alliance_id' => $id, 'status' => 1])->with(['business'])->asArray()->all();
        return $this->renderAjax('deduction_log', ['id' => $id, 'model' => $alliance, 'all_business' => $all_business]);
    }

    /**
     *  联盟承销记录列表页面
     * @return type
     */
    public function actionDeductionLogList() {
        $model = new \backend\modules\alliance\models\log\LogList;
        $this->post['flag'] = -1;
        $model->setAttributes($this->post);
        $rel = $model->getList();
        return $this->renderAjax('deduction_log_list', $rel);
    }

}
