<?php

namespace backend\modules\vip\controllers;

use backend\controllers\BaseController;
use common\models\Vip;
use yii\web\HttpException;

/**
 * 用户页面
 */
class PageController extends BaseController
{
     /**
     * 用户
     * @return string
     */
    public function actionIndex()
    {
        return $this->renderAjax('index');
    }
    
      /**
     * 用户列表页面
     * @return type
     */
    public function actionVipList() {
        $model = new \backend\modules\vip\models\VipList;
        $model->setAttributes($this->post);
        $rel=$model->getList();
        return $this->renderAjax('vip_list',$rel);
    }
    
    /**
     *用户
     * @return string
     */
    public function actionVipWin() {
        return $this->renderAjax('vip_win');
    }

    /**
     * 用户列表
     * @return string
     */
    public function actionVipWinList() {
        $model = new \backend\modules\vip\models\VipList;
        $this->post['status']=1;
        $model->setAttributes($this->post);
        return $this->renderAjax('vip_win_list', $model->getList(6));
    }
    
      
     /**
     * 交易记录
     */
    public function actionPay() {
        $id = $this->post['id'];
        $vip = Vip::find()->where(['id' => $id])->asArray()->one();
        if (!$vip)
            throw new HttpException(404, '用户已经不存在。');
        return $this->renderAjax('pay', ['id' => $id, 'model' => $vip]);
    }

    /**
     * 交易记录列表页面
     * @return type
     */
    public function actionPayList() {
        $model = new \backend\modules\vip\models\PayList;
        $model->setAttributes($this->post);
        $rel = $model->getList();
        return $this->renderAjax('pay_list', $rel);
    }
}
