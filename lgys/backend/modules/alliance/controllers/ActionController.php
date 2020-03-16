<?php

namespace backend\modules\alliance\controllers;

use yii;
use backend\controllers\BaseActionController;
use common\core\CommonFun;

/**
 * 联盟请求控制器
 */
class ActionController extends BaseActionController {

    /**
      联盟操作
     */
    public function actionAllianceForm() {
        $model = new \backend\modules\alliance\models\alliance\AllianceForm;
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
     *  联盟删除
     */
    public function actionAllianceDelete() {
        $model = new \backend\modules\alliance\models\alliance\AllianceDelete();
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->delete())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }
    
    
    /**
     *   联盟推荐
     */
    public function actionAllianceIshot() {
        $model =new \backend\modules\alliance\models\alliance\AllianceIshot();
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
     * 导出联盟
     */
    public function actionExportAlliance() {
        $model = new \backend\modules\alliance\models\alliance\AllianceList;
        try {
            $page_index = 1;
            $file_name = 'alliance_' . time() . '_' . CommonFun::getRandom() . '.xls';
            $title[] = '名称';
            $title[] = '创建商户';
            $title[] = '代币发行量';
            $title[] = '代币承销量';
            $title[] = '商户数量';
            $title[] = '创建日期';
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
                    $row[] = $item['business'] ? $item['business']['name'] : '无';
                    $row[] = $item['exchange_points'];
                    $row[] = $item['deduction_points'];
                    $row[] = $item['num'];
                    $row[] = $item['created_at'];
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

}
