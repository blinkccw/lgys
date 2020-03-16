<?php

namespace backend\controllers;

use Yii;
use common\core\BaseAjaxController;
use backend\models\LoginForm;

/**
 * Site controller
 */
class ActionController extends BaseAjaxController {

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'captcha' => [
                'class' => 'common\components\SiteCaptcha',
                'backColor' => 0xffffff, //背景颜色
                'height' => 40,
                'width' => 80,
                'minLength' => 4,
                'maxLength' => 4,
                'offset' => 0
            ],
        ];
    }

    /**
     * 登陆
     */
    public function actionLogin() {
        $model = new LoginForm;
        $model->captcha = $this->createAction('captcha')->getVerifyCode();
        $post = Yii::$app->request->post();
        $model->setAttributes($post);
        if (!$model->validate())
            return $this->errorModelJson($model);
        $rel = $model->login();
        return $this->relJson($rel);
    }

}
