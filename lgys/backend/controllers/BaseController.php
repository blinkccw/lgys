<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Description of BaseAjaxController
 *
 * @author xiaojx
 */
class BaseController extends Controller {

    public $user;
    public $user_id;
    public $post;
    public $page_size = 12;

    /**
     * @inheritdoc
     */
    function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    '*' => ['post'],
                ],
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
     * 初始化
     */
    function init() {
        parent::init();
        if (!Yii::$app->user->isGuest) {
            $this->user= Yii::$app->user->identity;
            $this->user_id = $this->user->id;
        }
        $this->post = Yii::$app->request->post();
    }
    
}
