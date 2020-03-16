<?php

namespace frontend\controllers;

use Yii;
use common\core\BaseAjaxController;
use yii\filters\VerbFilter;
use common\models\Vip;

/**
 * 微信小程序接口
 *
 * @author xiaojx
 */
class BaseVipController extends BaseAjaxController {

    public $enableCsrfValidation = false;
    public $vip;
    public $vip_id;
    public $token;
    public $post;

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
            ]
        ];
    }

    /**
     * 初始化
     */
    function init() {
        parent::init();
        $this->post = Yii::$app->request->post();
        if (!isset($this->post['vip_id']) || !isset($this->post['token'])) {
            echo json_encode(['code' => -403]);
            die();
        }
        $this->vip_id = $this->post['vip_id'];
        $this->token = $this->post['token'];
        if (Yii::$app->cache->exists('vip:' . $this->vip_id) != 1) {
            echo json_encode(['code' => -403]);
            die();
        }
        if (Yii::$app->cache->get('vip:' . $this->vip_id) != $this->token) {
            echo json_encode(['code' => -403]);
            die();
        }
        $this->vip = Vip::find()->where(['id' => $this->vip_id])->one();
        Yii::info($this->vip);
        if (!$this->vip) {
            echo json_encode(['code' => 0, 'message' => '帐号已经不存在。']);
            die();
        }
    }

}
