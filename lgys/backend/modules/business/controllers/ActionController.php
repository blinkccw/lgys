<?php

namespace backend\modules\business\controllers;

use yii;
use backend\controllers\BaseActionController;
use common\core\CommonFun;

/**
 * 商户请求控制器
 */
class ActionController extends BaseActionController {

    /**
      商户操作
     */
    public function actionBusinessForm() {
        $model = new \backend\modules\business\models\business\BusinessForm();
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
     *  商户删除
     */
    public function actionBusinessDelete() {
        $model = new \backend\modules\business\models\business\BusinessDelete();
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->delete())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
     *  商户状态
     */
    public function actionBusinessStatus() {
        $model = new \backend\modules\business\models\business\BusinessStatus;
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
     *  商户审核状态
     */
    public function actionBusinessAudit() {
        $model = new \backend\modules\business\models\business\BusinessAudit;
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
     *  商户推荐
     */
    public function actionBusinessIshot() {
        $model = new \backend\modules\business\models\business\BusinessIshot();
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
      商户充值操作
     */
    public function actionRechargeForm() {
        $model = new \backend\modules\business\models\recharge\RechargeForm();
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
      分类操作
     */
    public function actionSortForm() {
        $model = new \backend\modules\business\models\sort\SortForm;
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
     * 分类排序操作
     */
    public function actionSetSortOrder() {
        $model = new \backend\modules\business\models\sort\SortOrder;
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
     *  分类删除
     */
    public function actionSortDelete() {
        $model = new \backend\modules\business\models\sort\SortDelete();
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->delete())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
      等级操作
     */
    public function actionGradeForm() {
        $model = new \backend\modules\business\models\grade\GradeForm;
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
     * 等级删除
     */
    public function actionGradeDelete() {
        $model = new \backend\modules\business\models\grade\GradeDelete;
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->delete())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
     * 导出商户
     */
    public function actionExportBusiness() {
        $model = new \backend\modules\business\models\business\BusinessList;
        try {
            $page_index = 1;
            $file_name = 'business_' . time() . '_' . CommonFun::getRandom() . '.xls';
            $title[] = '名称';
            $title[] = '分类';
            $title[] = '等级';
            $title[] = '人均';
            $title[] = '代币余额';
            $title[] = '发行量';
            $title[] = '承销量';
            $title[] = '发行比例';
            $title[] = '承销比例';
            $title[] = '联系人';
            $title[] = '手机号';
            $title[] = '用户';
            $title[] = '创建日期';
            $title[] = '状态';
            // $this->saveExcel($file_name, $title);
            $data[] = $title;
            do {
                $this->post['page_index'] = $page_index++;
                $model->setAttributes($this->post);
                $rel = $model->getList(100);
                if (!$rel['list'])
                    break;
                foreach ($rel['list'] as $item) {
                    $row = [];
                    $row[] = $item['name'];
                    $row[] = $item['sort'] ? $item['sort']['name'] : '无';
                    $row[] = $item['grade'] ? $item['grade']['name'] : '无';
                    $row[] = $item['per']>0 ? round($item['per'],2): '未知';
                    $row[] = $item['points'];
                    $row[] = $item['exchange_points'];
                    $row[] = $item['deduction_points'];
                    $row[] = $item['exchange_pre'];
                    $row[] = $item['deduction_pre'];
                    $row[] = $item['contacts'];
                    $row[] = $item['phone'];
                    $row[] = $item['vip'] ? $item['vip']['nick_name'] : '无';
                    $row[] = $item['created_at'];
                    $row[] = $item['status'] == 1 ? '上架' : '下架';
                    $data[] = $row;
                }
            } while (true);
            $this->saveExcel($file_name, $data);
            return $this->sucJson(['file' => $file_name]);
        } catch (Exception $e) {
            Yii::error('导出excel:' . $e);
        }
        return $this->errorJson();
    }
    
      /**
     * 操作
     */
    public function actionConfigForm() {
        $model = new \backend\modules\business\models\business\ConfigFrom();
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }
    
       /**
     * 消息任务
     */
    public function actionNoticeForm() {
        $model = new \backend\modules\business\models\business\NoticeTaskForm;
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }
    
    
    
    /**
     *  商户联盟删除
     */
    public function actionAllianceDelete() {
        $model = new \backend\modules\business\models\business\AllianceDelete();
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->delete())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }


}
