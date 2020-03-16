<?php

namespace backend\modules\business\controllers;

use backend\controllers\BaseController;
use yii\web\HttpException;
use common\models\Business;
use common\models\AllianceBusiness;
use common\models\BusinessSort;
use common\models\BusinessGrade;
use common\models\Config;

/**
 * 商户页面
 */
class PageController extends BaseController {

    /**
     * 商户
     * @return string
     */
    public function actionIndex() {
        $sorts = BusinessSort::find()->orderBy('order_num,id')->all();
        $grades = BusinessGrade::find()->orderBy('vip_num')->all();
        return $this->renderAjax('index', ['sorts' => $sorts, 'grades' => $grades]);
    }

    /**
     * 商户信息
     */
    public function actionBusinessInfo() {
        $id = $this->post['id'];
        $business = Business::find()->where(['id' => $id])->with(['material', 'sort'])->asArray()->one();
        if (!$business)
            throw new HttpException(404, '信息已经不存在。');
        return $this->renderAjax('business_info', ['business' => $business]);
    }

    /**
     * 商户列表页面
     * @return type
     */
    public function actionBusinessList() {
        $this->post['is_audit'] = 1;
        $model = new \backend\modules\business\models\business\BusinessList;
        $model->setAttributes($this->post);
        $rel = $model->getList();
        return $this->renderAjax('business_list', $rel);
    }

    /**
     * 商户联盟
     * @return string
     */
    public function actionAlliances() {
        $model = new \backend\modules\business\models\business\AllianceList;
        $model->setAttributes($this->post);
        return $this->renderAjax('alliances', ['list' => $model->getList(),'id'=>$this->post['id']]);
    }

    /**
     * 商户审核
     */
    public function actionAudit() {
        return $this->renderAjax('audit');
    }

    /**
     * 商户审核列表页面
     * @return type
     */
    public function actionAuditList() {
        $this->post['is_audit'] = 0;
        $model = new \backend\modules\business\models\business\BusinessList;
        $model->setAttributes($this->post);
        $rel = $model->getList();
        return $this->renderAjax('audit_list', $rel);
    }

    /**
     * 商户操作表单
     * @return type
     */
    public function actionBusinessForm() {
        $id = $this->post['id'];
        $business = new Business;
        if ($id > 0) {
            $business = Business::find()->where(['id' => $id])->with(['vip'])->asArray()->one();
            if (!$business)
                throw new HttpException(404, '信息已经不存在。');
        }
        $sorts = BusinessSort::find()->orderBy('order_num,id')->all();
        return $this->renderAjax('business_form', ['id' => $id, 'model' => $business, 'sorts' => $sorts]);
    }

    /**
     * 充值
     */
    public function actionRecharge() {
        $id = $this->post['id'];
        $business = Business::find()->where(['id' => $id])->asArray()->one();
        if (!$business)
            throw new HttpException(404, '商户已经不存在。');
        return $this->renderAjax('recharge', ['id' => $id, 'model' => $business]);
    }

    /**
     * 商户充值列表页面
     * @return type
     */
    public function actionRechargeList() {
        $model = new \backend\modules\business\models\recharge\RechargeList;
        $model->setAttributes($this->post);
        $rel = $model->getList();
        return $this->renderAjax('recharge_list', $rel);
    }

    /**
     * 商户发行记录
     */
    public function actionExchangeLog() {
        $id = $this->post['id'];
        $business = Business::find()->where(['id' => $id])->asArray()->one();
        if (!$business)
            throw new HttpException(404, '商户已经不存在。');
        $all_alliance = AllianceBusiness::find()->where(['business_id' => $id, 'status' => 1])->with(['alliance'])->asArray()->all();
        return $this->renderAjax('exchange_log', ['id' => $id, 'model' => $business, 'all_alliance' => $all_alliance]);
    }

    /**
     * 商户发行记录列表页面
     * @return type
     */
    public function actionExchangeLogList() {
        $model = new \backend\modules\business\models\log\LogList;
        $this->post['flag'] = 1;
        $model->setAttributes($this->post);
        $rel = $model->getList();
        return $this->renderAjax('exchange_log_list', $rel);
    }

    /**
     * 商户承销记录
     */
    public function actionDeductionLog() {
        $id = $this->post['id'];
        $business = Business::find()->where(['id' => $id])->asArray()->one();
        if (!$business)
            throw new HttpException(404, '商户已经不存在。');
        $all_alliance = AllianceBusiness::find()->where(['business_id' => $id, 'status' => 1])->with(['alliance'])->asArray()->all();
        return $this->renderAjax('deduction_log', ['id' => $id, 'model' => $business, 'all_alliance' => $all_alliance]);
    }

    /**
     * 商户承销记录列表页面
     * @return type
     */
    public function actionDeductionLogList() {
        $model = new \backend\modules\business\models\log\LogList;
        $this->post['flag'] = -1;
        $model->setAttributes($this->post);
        $rel = $model->getList();
        return $this->renderAjax('deduction_log_list', $rel);
    }

    /**
     * 充值操作表单
     * @return type
     */
    public function actionRechargeForm() {
        $id = $this->post['id'];
        return $this->renderAjax('recharge_form', ['id' => $id]);
    }

    /**
     * 位置选择器
     */
    public function actionShopLocation() {
        return $this->renderAjax('shop_location', ['longitude' => $this->post['longitude'], 'latitude' => $this->post['latitude']]);
    }

    /**
     * 显示分类
     */
    public function actionSort() {
        $sorts = BusinessSort::find()->orderBy('order_num,id')->all();
        return $this->renderAjax('sort', ['list' => $sorts]);
    }

    /**
     * 分类表单
     * @return type
     */
    public function actionSortForm() {
        $id = $this->post['id'];
        $sort = new BusinessSort;
        if ($id > 0) {
            $sort = BusinessSort::find()->where(['id' => $id])->asArray()->one();
            if (!$sort)
                throw new HttpException(404, '信息已经不存在。');
        }
        return $this->renderAjax('sort_form', ['id' => $id, 'model' => $sort]);
    }

    /**
     * 等级
     */
    public function actionGrade() {
        $grades = BusinessGrade::find()->orderBy('vip_num')->all();
        return $this->renderAjax('grade', ['list' => $grades]);
    }

    /**
     * 等级表单
     * @return type
     */
    public function actionGradeForm() {
        $id = $this->post['id'];
        $grade = new BusinessGrade;
        if ($id > 0) {
            $grade = BusinessGrade::find()->where(['id' => $id])->asArray()->one();
            if (!$grade)
                throw new HttpException(404, '信息已经不存在。');
        }
        return $this->renderAjax('grade_form', ['id' => $id, 'model' => $grade]);
    }

    /**
     * 发送信息
     */
    public function actionNoticeForm() {
        $id = $this->post['id'];
        $business = Business::find()->where(['id' => $id])->asArray()->one();
        if (!$business)
            throw new HttpException(404, '商户已经不存在。');
        return $this->renderAjax('notice_form', ['id' => $id, 'business' => $business]);
    }

    /**
     * 聚合页面
     */
    public function actionAggregation() {
        return $this->renderAjax('aggregation');
    }

    /**
     *  聚合列表页面
     * @return type
     */
    public function actionAggregationList() {
        $model = new \backend\modules\business\models\aggregation\AggregationList();
        $model->setAttributes($this->post);
        $rel = $model->getList();
        return $this->renderAjax('aggregation_list', $rel);
    }

    /**
     * 聚合详情页面
     */
    public function actionAggregationMan() {
        return $this->renderAjax('aggregation_man');
    }

    /**
     *  聚合列表页面
     * @return type
     */
    public function actionAggregationManList() {
        $model = new \backend\modules\business\models\aggregation\AggregationManList();
        $model->setAttributes($this->post);
        $rel = $model->getList();
        return $this->renderAjax('aggregation_man_list', $rel);
    }

    /**
     * 配置页面
     */
    public function actionConfig() {
        $config = Config::findOne(['id' => 1]);
        if (!$config)
            throw new HttpException(404, '配置信息已经不存在。');
        return $this->renderAjax('config', ['config' => $config]);
    }

}
