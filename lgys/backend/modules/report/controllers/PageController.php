<?php

namespace backend\modules\report\controllers;

use backend\controllers\BaseController;
use common\models\Vip;
use common\models\VipPointsLog;
use common\models\BusinessPoints;
use yii\web\HttpException;

/**
 * 统计页面
 */
class PageController extends BaseController {

    /**
     * 交易记录
     */
    public function actionPay() {
        return $this->renderAjax('pay');
    }

    /**
     * 交易记录列表页面
     * @return type
     */
    public function actionPayList() {
        $model = new \backend\modules\report\models\PayList;
        $model->setAttributes($this->post);
        $rel = $model->getList();
        return $this->renderAjax('pay_list', $rel);
    }

    /**
     * 交易代币使用记录
     */
    public function actionPayPointsLog() {
        $logs = VipPointsLog::find()->where(['pay_id' => $this->post['id'],'flag'=>-1])->with(['business'])->asArray()->all();
        return $this->renderAjax('pay_points_log', ['no'=>$this->post['no'],'logs'=>$logs]);
    }
    
      /**
     * 交易代币提成记录
     */
    public function actionPayErcentageLog() {
        $logs = BusinessPoints::find()->where(['pay_id' => $this->post['id'],'flag'=>1,'points_type'=>2])->with(['business'])->asArray()->all();
        return $this->renderAjax('pay_ercentage_log', ['no'=>$this->post['no'],'logs'=>$logs]);
    }

}
