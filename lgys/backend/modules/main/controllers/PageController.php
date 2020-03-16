<?php

namespace backend\modules\main\controllers;

use backend\controllers\BaseController;
use yii\web\HttpException;
/**
 * 首页页面
 */
class PageController extends BaseController
{
  

    /**
     * 交易记录列表页面
     * @return type
     */
    public function actionLogList() {
          $model = new \backend\modules\main\models\LogList;
        $model->setAttributes($this->post);
        $rel=$model->getList(10);
        return $this->renderAjax('log_list',$rel);
    }
}

