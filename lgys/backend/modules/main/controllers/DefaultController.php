<?php

namespace backend\modules\main\controllers;

use yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use backend\controllers\BaseController;
use common\models\Vip;
use common\models\Business;
use common\models\Alliance;
use common\models\VipPointsLog;
use common\models\BusinessPoints;

/**
 * 管理首页
 */
class DefaultController extends BaseController {

    /**
     * @inheritdoc
     */
    function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'Index' => ['get']
                ]
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 
     * @return string
     */
    public function actionIndex() {
        $this->layout = "main";
        $view = Yii::$app->view;
        $view->params['username'] = $this->user->username;
        return $this->render('index');
    }

    /**
     * 欢迎
     */
    public function actionWelcome() {
        $data['vip_num'] = Vip::find()->where(['is_auth' => 1])->count('id');
        $data['business_num'] = Business::find()->count('id');
        $data['alliance_num'] = Alliance::find()->count('id');
        $data['points_num'] = VipPointsLog::find()->where(['flag' => 1, 'status' => 1])->sum('points');
        $data['ercentage_num'] = BusinessPoints::find()->where(['business_id' => 0, 'points_type' => 2, 'flag' => 1])->sum('points');
        return $this->renderAjax('welcome', $data);
    }

}
